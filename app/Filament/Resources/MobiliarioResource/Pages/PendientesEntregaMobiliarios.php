<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Filament\Resources\MobiliarioResource;
use App\Models\Marca;
use App\Models\Mobiliario;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PendientesEntregaMobiliarios extends ListRecords
{
    protected static string $resource = MobiliarioResource::class;

    protected static ?string $title = 'Pendientes de Entrega';

    protected static ?string $navigationLabel = 'Pendientes de Entrega';

    private const ESTADOS_PRESUPUESTO_PENDIENTES_ENTREGA = [
        'confirmado',
        'pagado',
        'entregado_parcial',
    ];

    public static function getNavigationLabel(): string
    {
        return 'Pendientes de Entrega';
    }

    public function getTitle(): string
    {
        return 'Pendientes de Entrega';
    }

    public function getBreadcrumb(): string
    {
        return 'Pendientes de Entrega';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('Generar PDF')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->url(fn (): string => route('mobiliarios.pendientes-entrega.pdf', [
                    'marca' => $this->activeTab,
                ]))
                ->openUrlInNewTab(),

            Actions\Action::make('volver')
                ->label('Volver a Mobiliarios')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(MobiliarioResource::getUrl('index')),
        ];
    }

    public function getTabs(): array
    {
        $baseQuery = $this->getPendientesEntregaBaseQuery();

        $counts = (clone $baseQuery)
            ->selectRaw('marca_id, count(*) as aggregate')
            ->groupBy('marca_id')
            ->pluck('aggregate', 'marca_id');

        $tabs = [
            'todos' => Tab::make('Todos')
                ->badge((clone $baseQuery)->count()),
        ];

        Marca::query()
            ->whereIn('id', $counts->keys()->filter()->all())
            ->orderBy('nombre')
            ->get()
            ->each(function (Marca $marca) use (&$tabs, $counts): void {
                $tabs['marca_' . $marca->id] = Tab::make($marca->nombre)
                    ->modifyQueryUsing(fn (Builder $query) => $query->where('marca_id', $marca->id))
                    ->badge($counts[$marca->id] ?? 0);
            });

        if (($counts[null] ?? 0) > 0) {
            $tabs['sin_marca'] = Tab::make('Sin marca')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('marca_id'))
                ->badge($counts[null]);
        }

        return $tabs;
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('imagenes')
                    ->collection('imagenes')
                    ->conversion('thumb')
                    ->label('Imagen')
                    ->square()
                    ->extraImgAttributes(['style' => 'object-fit:contain; background:#f3f4f6;']),

                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('codigo_interno')
                    ->label('Código')
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('cantidad_pendiente_entrega')
                    ->label('Cantidad')
                    ->numeric()
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('atributos_resumen')
                    ->label('Atributos')
                    ->getStateUsing(fn (Mobiliario $record): string =>
                        $record->atributos->isNotEmpty()
                            ? $record->atributos->map(fn ($a) => $a->clave . ': ' . $a->valor)->join('  ·  ')
                            : '—'
                    )
                    ->wrap()
                    ->searchable(false)
                    ->sortable(false),
            ])
            ->defaultSort('nombre')
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (Mobiliario $record): string => "Ítems pendientes — {$record->nombre}")
                    ->modalWidth('5xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(fn (Mobiliario $record) => view('filament.mobiliarios.pendientes-entrega-detalle', [
                        'items' => $record->presupuestoItemsPendientesEntrega()
                            ->with([
                                'presupuesto.agencia.proyecto.marca',
                                'mobiliario',
                                'sector',
                                'etapasProduccion',
                            ])
                            ->orderBy('presupuesto_id')
                            ->orderBy('orden')
                            ->get(),
                    ])),
            ])
            ->bulkActions([]);
    }

    protected function getTableQuery(): ?Builder
    {
        return $this->getPendientesEntregaBaseQuery()
            ->with(['atributos', 'marca', 'media'])
            ->withSum(['presupuestoItems as cantidad_pendiente_entrega' => function (Builder $query): void {
                $this->applyPendienteEntregaConstraint($query);
            }], 'cantidad');
    }

    protected function getPendientesEntregaBaseQuery(): Builder
    {
        return Mobiliario::query()
            ->whereHas('presupuestoItems', function (Builder $query): void {
                $this->applyPendienteEntregaConstraint($query);
            });
    }

    protected function applyPendienteEntregaConstraint(Builder $query): void
    {
        $query
            ->whereNull('entregado_at')
            ->whereHas('presupuesto', fn (Builder $presupuestoQuery) => $presupuestoQuery->whereIn(
                'estado',
                self::ESTADOS_PRESUPUESTO_PENDIENTES_ENTREGA,
            ));
    }
}
