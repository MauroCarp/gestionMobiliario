<?php

namespace App\Filament\Resources\PresupuestoResource\RelationManagers;

use App\Models\Insumo;
use App\Models\PresupuestoItem;
use App\Services\StockReservaService;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title       = 'Ítems del presupuesto';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_nombre')
            ->defaultSort('orden')
            ->columns([
                Tables\Columns\TextColumn::make('item_codigo')
                    ->label('Código')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('item_nombre')
                    ->label('Item')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->getStateUsing(fn (PresupuestoItem $record): string =>
                        $record->mobiliario_id ? 'Mobiliario' : 'Insumo'
                    )
                    ->color(fn (PresupuestoItem $record): string =>
                        $record->mobiliario_id ? 'warning' : 'info'
                    ),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('sector.nombre')
                    ->label('Sector')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('estado_finalizacion')
                    ->label('Estado')
                    ->badge()
                    ->getStateUsing(fn (PresupuestoItem $record): string =>
                        $record->estaFinalizado() ? 'Finalizado' : 'Pendiente'
                    )
                    ->color(fn (PresupuestoItem $record): string =>
                        $record->estaFinalizado() ? 'success' : 'gray'
                    ),

                Tables\Columns\TextColumn::make('finalizado_at')
                    ->label('Finalizado el')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('finalizadoPor.name')
                    ->label('Finalizado por')
                    ->placeholder('—'),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('confirmarFinalizacion')
                    ->label('Confirmar finalización')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (PresupuestoItem $record): bool =>
                        ! $record->estaFinalizado()
                        && in_array($this->ownerRecord->estado, ['confirmado', 'pagado'])
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar finalización')
                    ->modalDescription(function (PresupuestoItem $record): string {
                        $demanda = $record->demandaInsumos();

                        if (empty($demanda)) {
                            return 'Este ítem no requiere insumos. ¿Confirmar finalización?';
                        }

                        $insumos = Insumo::whereIn('id', array_keys($demanda))
                            ->with('unidadMedida')
                            ->get()
                            ->keyBy('id');

                        $lineas = collect($demanda)->map(function (float $cantidad, int $insumoId) use ($insumos): string {
                            $insumo = $insumos[$insumoId] ?? null;
                            $nombre = $insumo?->nombre ?? "Insumo #{$insumoId}";
                            $unidad = $insumo?->unidadMedida?->nombre ?? '';

                            return "• {$nombre}: " . number_format($cantidad, 2, ',', '.') . ($unidad ? " {$unidad}" : '');
                        });

                        return "Se descontará del stock:\n\n" . $lineas->implode("\n");
                    })
                    ->action(function (PresupuestoItem $record): void {
                        $demanda = $record->demandaInsumos();

                        app(StockReservaService::class)->finalizarItem($record);

                        Notification::make()
                            ->title('Finalización confirmada')
                            ->body(empty($demanda)
                                ? 'El ítem fue marcado como finalizado.'
                                : 'El stock de insumos fue descontado.')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
