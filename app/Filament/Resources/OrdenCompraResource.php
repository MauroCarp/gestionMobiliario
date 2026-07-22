<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenCompraResource\Pages;
use App\Filament\Resources\OrdenCompraResource\RelationManagers\ItemsRelationManager;
use App\Models\Insumo;
use App\Models\OrdenCompra;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrdenCompraResource extends Resource
{
    protected static ?string $model = OrdenCompra::class;
    protected static ?string $navigationIcon  = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $navigationLabel = 'Órdenes de Compra';
    protected static ?string $modelLabel      = 'Orden de Compra';
    protected static ?string $pluralModelLabel = 'Órdenes de Compra';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos generales')->schema([
                Forms\Components\TextInput::make('codigo')
                    ->label('Código')
                    ->disabled()
                    ->dehydrated(false)
                    ->hiddenOn('create'),

                Forms\Components\Select::make('estado')
                    ->options(OrdenCompra::ESTADOS)
                    ->default('pendiente')
                    ->required()
                    ->hiddenOn('create'),

                Forms\Components\Select::make('prioridad')
                    ->options(OrdenCompra::PRIORIDADES)
                    ->default('media')
                    ->required()
                    ->hiddenOn('create'),

                Forms\Components\Select::make('proveedor_id')
                    ->label('Proveedor')
                    ->relationship('proveedor', 'razon_social')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),

                Forms\Components\DatePicker::make('fecha_pactada_entrega')
                    ->label('Fecha pactada de entrega')
                    ->nullable(),

                Forms\Components\Textarea::make('observaciones')
                    ->rows(2)
                    ->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Insumos a solicitar')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('insumo_id')
                            ->label('Insumo')
                            ->options(fn () => Insumo::where('activo', true)
                                ->orderBy('nombre')
                                ->get()
                                ->mapWithKeys(fn ($i) => [$i->id => $i->nombre]))
                            ->searchable()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $insumo = Insumo::find($state);
                                $set('precio_unitario', $insumo?->precio_costo);

                                if ($insumo?->proveedor_id) {
                                    $set('../../proveedor_id', $insumo->proveedor_id);
                                }
                            })
                            ->columnSpan(2),

                        Forms\Components\TextInput::make('cantidad_solicitada')
                            ->label('Cantidad')
                            ->numeric()
                            ->minValue(0.01)
                            ->required()
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('cantidad_recibida')
                            ->label('Recibida')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        Forms\Components\TextInput::make('precio_unitario')
                            ->label('Precio unit.')
                            ->numeric()
                            ->prefix('$')
                            ->nullable()
                            ->columnSpan(1),

                        Forms\Components\Textarea::make('observaciones')
                            ->label('Obs.')
                            ->rows(1)
                            ->columnSpan(3),
                    ])
                    ->columns(5)
                    ->addActionLabel('Agregar insumo')
                    ->defaultItems(0)
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
                    ->color(fn (string $state): string => OrdenCompra::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenCompra::ESTADOS[$state] ?? $state),

                Infolists\Components\TextEntry::make('prioridad')
                    ->badge()
                    ->color(fn (string $state): string => OrdenCompra::PRIORIDAD_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenCompra::PRIORIDADES[$state] ?? $state),

                Infolists\Components\TextEntry::make('proveedor.razon_social')
                    ->label('Proveedor')
                    ->placeholder('—'),

                Infolists\Components\TextEntry::make('fecha_pactada_entrega')
                    ->label('Fecha pactada de entrega')
                    ->date('d/m/Y')
                    ->placeholder('—'),

                Infolists\Components\IconEntry::make('generado_automaticamente')
                    ->label('Generada automáticamente')
                    ->boolean(),

                Infolists\Components\TextEntry::make('created_at')
                    ->label('Creada')
                    ->date('d/m/Y'),

                Infolists\Components\TextEntry::make('observaciones')
                    ->placeholder('—')
                    ->columnSpanFull(),
            ])->columns(3),
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
                    ->color(fn (string $state): string => OrdenCompra::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenCompra::ESTADOS[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('prioridad')
                    ->badge()
                    ->color(fn (string $state): string => OrdenCompra::PRIORIDAD_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenCompra::PRIORIDADES[$state] ?? $state)
                    ->sortable(),

                Tables\Columns\TextColumn::make('proveedor.razon_social')
                    ->label('Proveedor')
                    ->placeholder('—')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_pactada_entrega')
                    ->label('Entrega pactada')
                    ->date('d/m/Y')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('generado_automaticamente')
                    ->label('Auto')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('estado')
                    ->options(OrdenCompra::ESTADOS),

                Tables\Filters\SelectFilter::make('prioridad')
                    ->options(OrdenCompra::PRIORIDADES),

                Tables\Filters\SelectFilter::make('proveedor_id')
                    ->label('Proveedor')
                    ->relationship('proveedor', 'razon_social')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('automaticas')
                    ->label('Solo automáticas')
                    ->query(fn ($query) => $query->where('generado_automaticamente', true)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('aprobar')
                    ->label('Aprobar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (OrdenCompra $r) => $r->estado === 'sugerida' || $r->estado === 'pendiente')
                    ->requiresConfirmation()
                    ->action(function (OrdenCompra $record) {
                        $record->update(['estado' => 'aprobada']);
                        Notification::make()->title('Orden aprobada')->success()->send();
                    }),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrdenesCompra::route('/'),
            'create' => Pages\CreateOrdenCompra::route('/create'),
            'view'   => Pages\ViewOrdenCompra::route('/{record}'),
            'edit'   => Pages\EditOrdenCompra::route('/{record}/edit'),
        ];
    }
}
