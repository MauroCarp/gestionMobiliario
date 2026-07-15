<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Filament\Resources\MobiliarioResource;
use App\Models\Mobiliario;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendientesEntregaMobiliarios extends ListRecords
{
    protected static string $resource = MobiliarioResource::class;

    protected static ?string $title = 'Pendientes de Entrega';

    protected static ?string $navigationLabel = 'Pendientes de Entrega';

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
            Actions\Action::make('volver')
                ->label('Volver a Mobiliarios')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(MobiliarioResource::getUrl('index')),
        ];
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
        return Mobiliario::query()
            ->withoutGlobalScopes([SoftDeletingScope::class])
            ->with(['atributos', 'marca', 'media'])
            ->whereHas('presupuestoItems', function (Builder $query): void {
                $query
                    ->whereNull('entregado_at')
                    ->whereHas('presupuesto', fn (Builder $presupuestoQuery) => $presupuestoQuery->whereIn('estado', [
                        'confirmado',
                        'pagado',
                        'entregado_parcial',
                    ]));
            })
            ->withSum(['presupuestoItems as cantidad_pendiente_entrega' => function (Builder $query): void {
                $query
                    ->whereNull('entregado_at')
                    ->whereHas('presupuesto', fn (Builder $presupuestoQuery) => $presupuestoQuery->whereIn('estado', [
                        'confirmado',
                        'pagado',
                        'entregado_parcial',
                    ]));
            }], 'cantidad');
    }
}
