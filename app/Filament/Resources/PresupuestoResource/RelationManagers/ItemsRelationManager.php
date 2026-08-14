<?php

namespace App\Filament\Resources\PresupuestoResource\RelationManagers;

use App\Models\Insumo;
use App\Models\PresupuestoItem;
use App\Models\PresupuestoItemEtapa;
use App\Services\PresupuestoEntregaService;
use App\Services\PresupuestoItemProduccionService;
use App\Services\StockReservaService;
use App\Support\PresupuestoAuthorization;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';
    protected static ?string $title       = 'Ítems del presupuesto';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_nombre')
            ->defaultSort('orden')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('etapasProduccion'))
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
                    ->label('Solicitado')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('cantidad_desde_stock')
                    ->label('Desde stock')
                    ->alignCenter()
                    ->placeholder('—')
                    ->visible(fn (): bool => in_array($this->ownerRecord->estado, [
                        'confirmado', 'pagado', 'entregado_parcial', 'entregado',
                    ])),

                Tables\Columns\TextColumn::make('cantidad_a_fabricar')
                    ->label('A fabricar')
                    ->alignCenter()
                    ->placeholder('—')
                    ->visible(fn (): bool => in_array($this->ownerRecord->estado, [
                        'confirmado', 'pagado', 'entregado_parcial', 'entregado',
                    ])),

                Tables\Columns\TextColumn::make('sector.nombre')
                    ->label('Sector')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('progreso_produccion')
                    ->label('Producción')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('etapa_actual_produccion')
                    ->label('Etapa actual')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('estado_finalizacion')
                    ->label('Finalización')
                    ->badge()
                    ->getStateUsing(function (PresupuestoItem $record): string {
                        if ($record->mobiliario_id && ! $record->requiereFabricacion()) {
                            return 'No requiere fabricación';
                        }

                        return $record->estaFinalizado() ? 'Finalizado' : 'Pendiente';
                    })
                    ->color(function (PresupuestoItem $record): string {
                        if ($record->mobiliario_id && ! $record->requiereFabricacion()) {
                            return 'info';
                        }

                        return $record->estaFinalizado() ? 'success' : 'gray';
                    }),

                Tables\Columns\TextColumn::make('estado_entrega')
                    ->label('Entrega')
                    ->badge()
                    ->color(fn (PresupuestoItem $record): string =>
                        $record->estaEntregado() ? 'success' : 'gray'
                    ),

                Tables\Columns\TextColumn::make('entregado_at')
                    ->label('Entregado el')
                    ->dateTime('d/m/Y H:i')
                    ->placeholder('—'),

                    
                    // Tables\Columns\TextColumn::make('entregadoPor.name')
                    //     ->label('Entregado por')
                    //     ->placeholder('—'),
                    
                Tables\Columns\TextColumn::make('finalizado_at')
                ->label('Finalizado el')
                ->dateTime('d/m/Y H:i')
                ->placeholder('—'),
                
                Tables\Columns\TextColumn::make('entrega_observaciones')
                    ->label('Observaciones entrega')
                    ->placeholder('—')
                    ->wrap()
                    ->toggleable(),
                // Tables\Columns\TextColumn::make('finalizadoPor.name')
                //     ->label('Finalizado por')
                //     ->placeholder('—'),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('etapasProduccion')
                    ->label('Etapas')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn (PresupuestoItem $record): bool =>
                        PresupuestoAuthorization::canForRecord('manageItemStages', $this->ownerRecord)
                        && (bool) $record->mobiliario_id
                        && $record->cantidadParaFabricacion() > 0
                        && in_array($this->ownerRecord->estado, ['confirmado', 'pagado', 'entregado_parcial', 'entregado'])
                    )
                    ->authorize(fn (): bool => auth()->user()?->can('manageItemStages', $this->ownerRecord) ?? false)
                    ->modalHeading(fn (PresupuestoItem $record): string => "Etapas de producción - {$record->item_nombre}")
                    ->modalWidth('6xl')
                    ->mountUsing(function (Forms\ComponentContainer $form, PresupuestoItem $record): void {
                        app(PresupuestoItemProduccionService::class)->crearEtapasParaItem($record);

                        $record->load('etapasProduccion.iniciadoPor', 'etapasProduccion.completadoPor');

                        $form->fill([
                            'etapas' => $record->etapasProduccion
                                ->map(fn (PresupuestoItemEtapa $etapa): array => [
                                    'id'             => $etapa->id,
                                    'orden'          => $etapa->orden,
                                    'nombre'         => $etapa->nombre,
                                    'estado'         => $etapa->nombre === PresupuestoItemProduccionService::ETAPA_INICIO && $etapa->estado === 'en_proceso'
                                        ? 'pendiente'
                                        : $etapa->estado,
                                    'fecha_inicio'   => $etapa->fecha_inicio?->format('Y-m-d'),
                                    'fecha_fin'      => $etapa->fecha_fin?->format('Y-m-d'),
                                    'iniciado_por'   => $etapa->iniciadoPor?->name ?? '—',
                                    'completado_por' => $etapa->completadoPor?->name ?? '—',
                                    'observaciones'  => $etapa->observaciones,
                                ])
                                ->values()
                                ->all(),
                        ]);
                    })
                    ->form([
                        Forms\Components\Repeater::make('etapas')
                            ->label('Etapas')
                            ->schema([
                                Forms\Components\Hidden::make('id'),
                                Forms\Components\TextInput::make('orden')
                                    ->label('Orden')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('nombre')
                                    ->label('Etapa')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2),
                                Forms\Components\Select::make('estado')
                                    ->label('Estado')
                                    ->options(fn (Get $get): array => $get('nombre') === PresupuestoItemProduccionService::ETAPA_INICIO
                                        ? PresupuestoItemEtapa::ESTADOS_INICIO
                                        : PresupuestoItemEtapa::ESTADOS)
                                    ->required()
                                    ->live()
                                    ->columnSpan(2),
                                Forms\Components\DatePicker::make('fecha_inicio')
                                    ->label('Fecha inicio')
                                    ->visible(fn (Get $get): bool => $get('nombre') === PresupuestoItemProduccionService::ETAPA_INICIO
                                        ? $get('estado') === 'completado'
                                        : true)
                                    ->columnSpan(2),
                                Forms\Components\DatePicker::make('fecha_fin')
                                    ->label('Fecha fin')
                                    ->visible(fn (Get $get): bool => $get('nombre') !== PresupuestoItemProduccionService::ETAPA_INICIO)
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('iniciado_por')
                                    ->label('Iniciado por')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->visible(fn (Get $get): bool => $get('nombre') !== PresupuestoItemProduccionService::ETAPA_INICIO)
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('completado_por')
                                    ->label('Completado por')
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->visible(fn (Get $get): bool => $get('nombre') !== PresupuestoItemProduccionService::ETAPA_INICIO)
                                    ->columnSpan(2),
                                Forms\Components\Textarea::make('observaciones')
                                    ->label('Observaciones')
                                    ->rows(2)
                                    ->visible(fn (Get $get): bool => $get('nombre') !== PresupuestoItemProduccionService::ETAPA_INICIO)
                                    ->columnSpanFull(),
                            ])
                            ->columns(8)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false),
                    ])
                    ->action(function (PresupuestoItem $record, array $data): void {
                        app(PresupuestoItemProduccionService::class)->actualizarEtapas($record, $data['etapas'] ?? []);

                        Notification::make()
                            ->title('Etapas de producción actualizadas')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('marcarEntregado')
                    ->label('Marcar entregado')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->visible(fn (PresupuestoItem $record): bool =>
                        PresupuestoAuthorization::canForRecord('registerDelivery', $this->ownerRecord)
                        && ! $record->estaEntregado()
                        && $this->ownerRecord->puedeRegistrarEntrega()
                    )
                    ->authorize(fn (): bool => auth()->user()?->can('registerDelivery', $this->ownerRecord) ?? false)
                    ->form([
                        Forms\Components\Textarea::make('entrega_observaciones')
                            ->label('Observaciones de entrega')
                            ->rows(2),
                    ])
                    ->action(function (PresupuestoItem $record, array $data): void {
                        app(PresupuestoEntregaService::class)->toggleItemEntrega(
                            $record,
                            true,
                            $data['entrega_observaciones'] ?? null,
                        );

                        Notification::make()
                            ->title('Ítem marcado como entregado')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('desmarcarEntregado')
                    ->label('Desmarcar entrega')
                    ->icon('heroicon-o-x-circle')
                    ->color('gray')
                    ->visible(fn (PresupuestoItem $record): bool =>
                        PresupuestoAuthorization::canForRecord('registerDelivery', $this->ownerRecord)
                        && $record->estaEntregado()
                        && $this->ownerRecord->puedeRegistrarEntrega()
                    )
                    ->authorize(fn (): bool => auth()->user()?->can('registerDelivery', $this->ownerRecord) ?? false)
                    ->requiresConfirmation()
                    ->action(function (PresupuestoItem $record): void {
                        app(PresupuestoEntregaService::class)->toggleItemEntrega($record, false);

                        Notification::make()
                            ->title('Entrega del ítem revertida')
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('confirmarFinalizacion')
                    ->label('Confirmar finalización')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (PresupuestoItem $record): bool =>
                        ! $record->estaFinalizado()
                        && $record->requiereFabricacion()
                        && in_array($this->ownerRecord->estado, ['confirmado', 'pagado', 'entregado_parcial', 'entregado'])
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
