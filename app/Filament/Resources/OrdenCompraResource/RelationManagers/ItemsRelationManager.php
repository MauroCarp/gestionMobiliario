<?php

namespace App\Filament\Resources\OrdenCompraResource\RelationManagers;

use App\Models\OrdenCompraItem;
use App\Services\OrdenCompraRecepcionService;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Insumos de la orden';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with(['insumo.unidadMedida', 'recepciones']))
            ->columns([
                Tables\Columns\TextColumn::make('insumo.nombre')
                    ->label('Insumo')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('insumo.unidadMedida.nombre')
                    ->label('Unidad')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('cantidad_solicitada')
                    ->label('Cantidad pedida')
                    ->numeric(2)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('cantidad_recibida')
                    ->label('Cantidad recibida')
                    ->numeric(2)
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('pendiente')
                    ->label('Cantidad restante')
                    ->numeric(2)
                    ->alignCenter()
                    ->getStateUsing(fn (OrdenCompraItem $record): float => $record->pendiente),

                Tables\Columns\TextColumn::make('precio_unitario')
                    ->label('Precio unit.')
                    ->money('ARS')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id')
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('recepcion_total')
                    ->label('Recepción total')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (OrdenCompraItem $record): bool =>
                        $record->cantidad_recibida < $record->cantidad_solicitada
                        && in_array($this->getOwnerRecord()->estado, ['aprobada', 'recibida_parcial'], true)
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar recepción total')
                    ->modalDescription(fn (OrdenCompraItem $record): string =>
                        "Se registrará la recepción total de {$record->insumo?->nombre} con fecha de hoy."
                    )
                    ->action(function (OrdenCompraItem $record): void {
                        try {
                            app(OrdenCompraRecepcionService::class)->registrarRecepcionTotal($record);

                            Notification::make()
                                ->title('Recepción total registrada')
                                ->success()
                                ->send();

                            $this->getOwnerRecord()->refresh();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('No se pudo registrar la recepción')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('recepcion_parcial')
                    ->label('Recepción parcial')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('info')
                    ->visible(fn (OrdenCompraItem $record): bool =>
                        $record->cantidad_recibida < $record->cantidad_solicitada
                        && in_array($this->getOwnerRecord()->estado, ['aprobada', 'recibida_parcial'], true)
                    )
                    ->form(fn (OrdenCompraItem $record): array => [
                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad recibida')
                            ->numeric()
                            ->required()
                            ->minValue(0.01)
                            ->maxValue($record->pendiente)
                            ->default($record->pendiente),

                        Forms\Components\DatePicker::make('fecha_recepcion')
                            ->label('Fecha de recepción')
                            ->required()
                            ->default(now()),

                        Forms\Components\Textarea::make('notas')
                            ->label('Notas')
                            ->rows(2),
                    ])
                    ->action(function (OrdenCompraItem $record, array $data): void {
                        try {
                            app(OrdenCompraRecepcionService::class)->registrarRecepcionParcial(
                                $record,
                                (float) $data['cantidad'],
                                \Carbon\Carbon::parse($data['fecha_recepcion']),
                                $data['notas'] ?? null,
                            );

                            Notification::make()
                                ->title('Recepción parcial registrada')
                                ->success()
                                ->send();

                            $this->getOwnerRecord()->refresh();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('No se pudo registrar la recepción')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('ver_historial')
                    ->label('Ver historial')
                    ->icon('heroicon-o-clock')
                    ->color('gray')
                    ->modalHeading(fn (OrdenCompraItem $record): string =>
                        "Historial de recepciones — {$record->insumo?->nombre}"
                    )
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar')
                    ->modalContent(fn (OrdenCompraItem $record): HtmlString => new HtmlString(
                        view('filament.orden-compra.item-recepciones-historial', [
                            'recepciones' => $record->recepciones()->orderByDesc('fecha_recepcion')->get(),
                        ])->render()
                    )),
            ])
            ->bulkActions([]);
    }
}
