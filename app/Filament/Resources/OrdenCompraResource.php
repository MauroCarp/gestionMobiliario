<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdenCompraResource\Pages;
use App\Models\Insumo;
use App\Models\OrdenCompra;
use Filament\Forms;
use Filament\Forms\Form;
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

                Forms\Components\Select::make('presupuesto_id')
                    ->label('Presupuesto')
                    ->relationship('presupuesto', 'codigo')
                    ->searchable()
                    ->nullable()
                    ->hiddenOn('create'),

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
                                $precio = Insumo::find($state)?->precio_costo;
                                $set('precio_unitario', $precio);
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

                Tables\Columns\TextColumn::make('presupuesto.codigo')
                    ->label('Presupuesto')
                    ->placeholder('—'),

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

                Tables\Filters\Filter::make('automaticas')
                    ->label('Solo automáticas')
                    ->query(fn ($query) => $query->where('generado_automaticamente', true)),
            ])
            ->actions([
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

                Tables\Actions\Action::make('recibida')
                    ->label('Marcar recibida')
                    ->icon('heroicon-o-inbox-arrow-down')
                    ->color('info')
                    ->visible(fn (OrdenCompra $r) => $r->estado === 'aprobada')
                    ->requiresConfirmation()
                    ->action(function (OrdenCompra $record) {
                        // Actualizar stock de cada insumo
                        foreach ($record->items()->with('insumo')->get() as $item) {
                            $item->insumo->increment('stock_actual', $item->cantidad_solicitada);
                            $item->update(['cantidad_recibida' => $item->cantidad_solicitada]);
                        }
                        $record->update(['estado' => 'recibida']);
                        Notification::make()->title('Stock actualizado')->success()->send();
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrdenesCompra::route('/'),
            'create' => Pages\CreateOrdenCompra::route('/create'),
            'edit'   => Pages\EditOrdenCompra::route('/{record}/edit'),
        ];
    }
}
