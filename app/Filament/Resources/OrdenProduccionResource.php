<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenProduccionResource\Pages;
use App\Filament\Resources\OrdenProduccionResource\RelationManagers\HistorialRelationManager;
use App\Filament\Resources\OrdenProduccionResource\RelationManagers\IngresosRelationManager;
use App\Filament\Resources\OrdenProduccionResource\RelationManagers\ItemsRelationManager;
use App\Models\LoteProcesoExterno;
use App\Models\Marca;
use App\Models\Mobiliario;
use App\Models\OrdenCompra;
use App\Models\OrdenProduccion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrdenProduccionResource extends BaseResource
{
    protected static ?string $model = OrdenProduccion::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Operaciones';

    protected static ?string $navigationLabel = 'Órdenes de Producción';

    protected static ?string $modelLabel = 'Orden de Producción';

    protected static ?string $pluralModelLabel = 'Órdenes de Producción';

    protected static ?int $navigationSort = 4;

    // public static function shouldRegisterNavigation(): bool
    // {
    //     return strcasecmp((string) auth()->user()?->email, 'admin@gestionmobiliario.com') === 0;
    // }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos generales')->schema([
                Forms\Components\TextInput::make('codigo')
                    ->label('Código')
                    ->disabled()
                    ->dehydrated(false)
                    ->hiddenOn('create'),

                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Fecha de inicio'),

                Forms\Components\Textarea::make('observaciones')
                    ->rows(2)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Mobiliarios a fabricar')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('marca_id')
                            ->label('Marca')
                            ->options(fn () => Marca::query()
                                ->where('activo', true)
                                ->orderBy('nombre')
                                ->pluck('nombre', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Set $set): void {
                                $set('mobiliario_id', null);
                                $set('insumos_seleccionados', []);
                            })
                            ->columnSpan(2),

                        Forms\Components\Select::make('mobiliario_id')
                            ->label('Mobiliario')
                            ->options(function (Get $get) {
                                $marcaId = $get('marca_id');

                                if (! $marcaId) {
                                    return [];
                                }

                                return Mobiliario::query()
                                    ->where('estado', 'activo')
                                    ->whereHas('marcas', fn (Builder $query) => $query->where('marcas.id', $marcaId))
                                    ->orderBy('nombre')
                                    ->get()
                                    ->mapWithKeys(fn (Mobiliario $m) => [
                                        $m->id => trim(($m->codigo_interno ? "[{$m->codigo_interno}] " : '').$m->nombre),
                                    ]);
                            })
                            ->searchable()
                            ->required()
                            ->live()
                            ->disabled(fn (Get $get): bool => blank($get('marca_id')))
                            ->afterStateUpdated(function (Set $set, $state): void {
                                $set('insumos_seleccionados', array_keys(static::opcionesInsumosFabricables($state)));
                            })
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->default(1)
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('observaciones')
                            ->label('Obs.')
                            ->rows(1)
                            ->columnSpan(3),

                        Forms\Components\CheckboxList::make('insumos_seleccionados')
                            ->label('Insumos a utilizar')
                            ->helperText('Destildá los insumos que no se van a usar en esta fabricación. Si el mobiliario tiene composición, debe quedar al menos uno.')
                            ->options(fn (Get $get): array => static::opcionesInsumosFabricables($get('mobiliario_id')))
                            ->descriptions(fn (Get $get): array => static::descripcionesInsumosFabricables($get('mobiliario_id')))
                            ->afterStateHydrated(function (Forms\Components\CheckboxList $component, $state, Get $get): void {
                                if ($state !== null) {
                                    return;
                                }

                                $component->state(array_keys(static::opcionesInsumosFabricables($get('mobiliario_id'))));
                            })
                            ->visible(fn (Get $get): bool => filled($get('mobiliario_id')))
                            ->required(fn (Get $get): bool => static::opcionesInsumosFabricables($get('mobiliario_id')) !== [])
                            ->minItems(fn (Get $get): int => static::opcionesInsumosFabricables($get('mobiliario_id')) !== [] ? 1 : 0)
                            ->bulkToggleable()
                            ->columns(1)
                            ->columnSpanFull(),
                    ])
                    ->columns(4)
                    ->addActionLabel('Agregar mobiliario')
                    ->defaultItems(1)
                    ->minItems(1)
                    ->reorderable(false),
            ]),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Datos generales')->schema([
                Infolists\Components\TextEntry::make('codigo')
                    ->label('Código')
                    ->badge()
                    ->color('primary'),

                Infolists\Components\TextEntry::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => OrdenProduccion::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenProduccion::ESTADOS[$state] ?? $state),

                Infolists\Components\TextEntry::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('iniciadoPor.name')
                    ->label('Iniciada por')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('cantidad_ingresada')
                    ->label('Progreso')
                    ->state(fn (OrdenProduccion $record): string => $record->cantidadIngresada().' / '.$record->cantidadTotal()
                    ),

                Infolists\Components\TextEntry::make('created_at')
                    ->label('Creada')
                    ->date('d/m/Y'),

                Infolists\Components\TextEntry::make('observaciones')
                    ->placeholder('—')
                    ->columnSpanFull(),
            ])->columns(3),

            Infolists\Components\Section::make('Documentos de reposición')
                ->schema([
                    Infolists\Components\RepeatableEntry::make('ordenesCompra')
                        ->label('Órdenes de compra')
                        ->schema([
                            Infolists\Components\TextEntry::make('codigo')
                                ->label('Código')
                                ->url(fn (OrdenCompra $record): string => OrdenCompraResource::getUrl('view', ['record' => $record]))
                                ->color('primary'),

                            Infolists\Components\TextEntry::make('estado')
                                ->badge()
                                ->formatStateUsing(fn (string $state): string => OrdenCompra::ESTADOS[$state] ?? $state)
                                ->color(fn (string $state): string => OrdenCompra::ESTADO_COLORS[$state] ?? 'gray'),

                            Infolists\Components\TextEntry::make('prioridad')
                                ->badge()
                                ->formatStateUsing(fn (?string $state): string => OrdenCompra::PRIORIDADES[$state] ?? ($state ?: '—'))
                                ->color(fn (?string $state): string => OrdenCompra::PRIORIDAD_COLORS[$state] ?? 'gray'),
                        ])
                        ->columns(3)
                        ->placeholder('Sin órdenes de compra asociadas.')
                        ->columnSpanFull(),

                    Infolists\Components\RepeatableEntry::make('lotesProcesoExterno')
                        ->label('Lotes de proceso externo')
                        ->schema([
                            Infolists\Components\TextEntry::make('codigo')
                                ->label('Código')
                                ->url(fn (LoteProcesoExterno $record): string => LoteProcesoExternoResource::getUrl('view', ['record' => $record]))
                                ->color('primary'),

                            Infolists\Components\TextEntry::make('estado')
                                ->badge()
                                ->formatStateUsing(fn (string $state): string => LoteProcesoExterno::ESTADOS[$state] ?? $state)
                                ->color(fn (string $state): string => LoteProcesoExterno::ESTADO_COLORS[$state] ?? 'gray'),

                            Infolists\Components\TextEntry::make('cantidad')
                                ->label('Cantidad'),
                        ])
                        ->columns(3)
                        ->placeholder('Sin lotes asociados.')
                        ->columnSpanFull(),
                ])
                ->visible(fn (OrdenProduccion $record): bool => $record->ordenesCompra()->exists()
                    || $record->lotesProcesoExterno()->exists()
                )
                ->columns(1),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => OrdenProduccion::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenProduccion::ESTADOS[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Líneas')
                    ->counts('items')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('progreso')
                    ->label('Progreso')
                    ->state(fn (OrdenProduccion $record): string => $record->cantidadIngresada().'/'.$record->cantidadTotal()
                    )
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(OrdenProduccion::ESTADOS),

                Tables\Filters\SelectFilter::make('marca_id')
                    ->label('Marca')
                    ->options(fn () => Marca::query()->orderBy('nombre')->pluck('nombre', 'id'))
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                            ? $query->whereHas('items', fn (Builder $q) => $q->where('marca_id', $data['value']))
                            : $query
                    ),

                Tables\Filters\SelectFilter::make('mobiliario_id')
                    ->label('Mobiliario')
                    ->options(fn () => Mobiliario::query()->orderBy('nombre')->pluck('nombre', 'id'))
                    ->searchable()
                    ->query(fn (Builder $query, array $data): Builder => filled($data['value'] ?? null)
                            ? $query->whereHas('items', fn (Builder $q) => $q->where('mobiliario_id', $data['value']))
                            : $query
                    ),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('desde')->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['desde'] ?? null, fn (Builder $q, $fecha) => $q->whereDate('created_at', '>=', $fecha))
                            ->when($data['hasta'] ?? null, fn (Builder $q, $fecha) => $q->whereDate('created_at', '<=', $fecha));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn (OrdenProduccion $record): bool => $record->puedeEditar()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (OrdenProduccion $record): bool => $record->puedeEditar()),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
            IngresosRelationManager::class,
            HistorialRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrdenesProduccion::route('/'),
            'create' => Pages\CreateOrdenProduccion::route('/create'),
            'view' => Pages\ViewOrdenProduccion::route('/{record}'),
            'edit' => Pages\EditOrdenProduccion::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['items', 'ordenesCompra', 'lotesProcesoExterno']);
    }

    /**
     * @return array<int, string>
     */
    public static function opcionesInsumosFabricables(mixed $mobiliarioId): array
    {
        if (! $mobiliarioId) {
            return [];
        }

        $mobiliario = Mobiliario::query()->find($mobiliarioId);

        if (! $mobiliario) {
            return [];
        }

        return $mobiliario->composicionFabricable()
            ->mapWithKeys(function ($comp): array {
                $insumo = $comp->insumo;
                $etiqueta = trim(($insumo?->codigo ? "[{$insumo->codigo}] " : '').($insumo?->nombre ?? "Insumo #{$comp->insumo_id}"));

                return [(int) $comp->insumo_id => $etiqueta];
            })
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public static function descripcionesInsumosFabricables(mixed $mobiliarioId): array
    {
        if (! $mobiliarioId) {
            return [];
        }

        $mobiliario = Mobiliario::query()->find($mobiliarioId);

        if (! $mobiliario) {
            return [];
        }

        return $mobiliario->composicionFabricable()
            ->mapWithKeys(fn ($comp): array => [
                (int) $comp->insumo_id => 'Cantidad por unidad: '.$comp->cantidad,
            ])
            ->all();
    }
}
