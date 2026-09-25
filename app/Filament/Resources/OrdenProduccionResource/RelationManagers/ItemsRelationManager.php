<?php

namespace App\Filament\Resources\OrdenProduccionResource\RelationManagers;

use App\Exceptions\OrdenProduccionException;
use App\Filament\Resources\MobiliarioResource;
use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionItem;
use App\Models\OrdenProduccionItemEtapa;
use App\Services\OrdenProduccionService;
use App\Services\PresupuestoItemProduccionService;
use App\Support\OrdenProduccionAuthorization;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Líneas de producción';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with([
                'mobiliario',
                'marca',
                'etapas',
                'insumos.insumo',
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('mobiliario.nombre')
                    ->label('Mobiliario')
                    ->url(fn (OrdenProduccionItem $record): ?string => $record->mobiliario_id
                        ? MobiliarioResource::getUrl('view', ['record' => $record->mobiliario_id])
                        : null)
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('cantidad_ingresada')
                    ->label('Avance')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('observaciones')
                    ->label('Observaciones')
                    ->wrap(),

                // Tables\Columns\TextColumn::make('insumos')
                //     ->label('Insumos')
                //     ->state(function (OrdenProduccionItem $record): string {
                //         $nombres = $record->insumos
                //             ->map(fn ($snapshot) => $snapshot->insumo?->nombre ?? "#{$snapshot->insumo_id}")
                //             ->filter()
                //             ->values();

                //         if ($nombres->isNotEmpty()) {
                //             return $nombres->implode(', ');
                //         }

                //         $ids = $record->idsInsumosSeleccionados();

                //         if ($ids === null) {
                //             return 'Todos';
                //         }

                //         if ($ids === []) {
                //             return 'Ninguno';
                //         }

                //         return $record->mobiliario?->composicionFabricable()
                //             ->whereIn('insumo_id', $ids)
                //             ->map(fn ($comp) => $comp->insumo?->nombre ?? "#{$comp->insumo_id}")
                //             ->implode(', ') ?: implode(', ', $ids);
                //     })
                //     ->wrap()
                //     ->toggleable(),

                Tables\Columns\TextColumn::make('pendiente')
                    ->label('Pendiente')
                    ->alignCenter()
                    ->state(fn (OrdenProduccionItem $record): int => $record->cantidadPendiente()),

                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => OrdenProduccionItem::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenProduccionItem::ESTADOS[$state] ?? $state),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('etapas')
                    ->label('Etapas')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('info')
                    ->visible(fn (): bool => OrdenProduccionAuthorization::canForRecord('manageItemStages', $this->ownerRecord)
                    )
                    ->modalHeading(fn (OrdenProduccionItem $record): string => "Etapas — {$record->item_nombre}")
                    ->modalWidth('6xl')
                    ->mountUsing(function (Forms\ComponentContainer $form, OrdenProduccionItem $record): void {
                        app(OrdenProduccionService::class)->crearEtapasParaItem($record);

                        $record->load('etapas.iniciadoPor', 'etapas.completadoPor');

                        $form->fill([
                            'etapas' => $record->etapas
                                ->map(fn (OrdenProduccionItemEtapa $etapa): array => [
                                    'id' => $etapa->id,
                                    'orden' => $etapa->orden,
                                    'nombre' => $etapa->nombre,
                                    'estado' => $etapa->nombre === PresupuestoItemProduccionService::ETAPA_INICIO && $etapa->estado === 'en_proceso'
                                        ? 'pendiente'
                                        : $etapa->estado,
                                    'fecha_inicio' => $etapa->fecha_inicio?->format('Y-m-d'),
                                    'fecha_fin' => $etapa->fecha_fin?->format('Y-m-d'),
                                    'iniciado_por' => $etapa->iniciadoPor?->name ?? '—',
                                    'completado_por' => $etapa->completadoPor?->name ?? '—',
                                    'observaciones' => $etapa->observaciones,
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
                                        ? OrdenProduccionItemEtapa::ESTADOS_INICIO
                                        : OrdenProduccionItemEtapa::ESTADOS)
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
                    ->action(function (OrdenProduccionItem $record, array $data): void {
                        try {
                            app(OrdenProduccionService::class)->actualizarEtapas($record, $data['etapas'] ?? []);

                            Notification::make()
                                ->title('Etapas actualizadas')
                                ->success()
                                ->send();
                        } catch (OrdenProduccionException $e) {
                            Notification::make()
                                ->title('No se pudieron actualizar las etapas')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('ingresar')
                    ->label('Registrar avance')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->visible(fn (OrdenProduccionItem $record): bool => OrdenProduccionAuthorization::canForRecord('registerProduction', $this->ownerRecord)
                        && $record->cantidadPendiente() > 0
                    )
                    ->fillForm(fn (OrdenProduccionItem $record): array => [
                        'cantidad' => $record->cantidadPendiente(),
                    ])
                    ->form(fn (OrdenProduccionItem $record): array => [
                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad fabricada')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue($record->cantidadPendiente())
                            ->default($record->cantidadPendiente()),
                        Forms\Components\Textarea::make('observaciones')
                            ->label('Observaciones')
                            ->rows(2),
                    ])
                    ->action(function (OrdenProduccionItem $record, array $data): void {
                        try {
                            app(OrdenProduccionService::class)->registrarIngreso(
                                $record,
                                (int) $data['cantidad'],
                                $data['observaciones'] ?? null,
                            );

                            Notification::make()
                                ->title('Avance registrado')
                                ->success()
                                ->send();

                            $this->getOwnerRecord()->refresh();
                        } catch (OrdenProduccionException|\InvalidArgumentException $e) {
                            Notification::make()
                                ->title('No se pudo registrar el avance')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof OrdenProduccion;
    }
}
