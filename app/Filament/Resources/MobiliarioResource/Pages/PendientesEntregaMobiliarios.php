<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Filament\Resources\MobiliarioResource;
use App\Models\Marca;
use App\Models\Mobiliario;
use App\Services\PresupuestoItemProduccionService;
use Filament\Actions;
use Filament\Forms;
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
                ->form([
                    Forms\Components\Select::make('etapa')
                        ->label('Etapa de producción')
                        ->options([
                            'todas' => 'Todas',
                            ...array_combine(
                                array_values(PresupuestoItemProduccionService::ETAPAS_PREDETERMINADAS),
                                array_values(PresupuestoItemProduccionService::ETAPAS_PREDETERMINADAS),
                            ),
                            'Completado' => 'Completado',
                        ])
                        ->default('todas')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $url = route('mobiliarios.pendientes-entrega.pdf', [
                        'marca' => $this->activeTab,
                        'etapa' => $data['etapa'] ?? 'todas',
                    ]);

                    $this->js('window.open(' . json_encode($url) . ", '_blank')");
                }),

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
            ->join('marca_mobiliario', 'marca_mobiliario.mobiliario_id', '=', 'mobiliarios.id')
            ->selectRaw('marca_mobiliario.marca_id as marca_id, count(*) as aggregate')
            ->groupBy('marca_mobiliario.marca_id')
            ->pluck('aggregate', 'marca_id');

        $sinMarca = (clone $baseQuery)->whereDoesntHave('marcas')->count();

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
                    ->modifyQueryUsing(fn (Builder $query) => $query->whereHas(
                        'marcas',
                        fn (Builder $marcasQuery) => $marcasQuery->where('marcas.id', $marca->id),
                    ))
                    ->badge($counts[$marca->id] ?? 0);
            });

        if ($sinMarca > 0) {
            $tabs['sin_marca'] = Tab::make('Sin marca')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('marcas'))
                ->badge($sinMarca);
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

                Tables\Columns\TextColumn::make('marcas.nombre')
                    ->label('Marcas')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—')
                    ->searchable(),

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
            ->with(['atributos', 'marcas', 'media'])
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
