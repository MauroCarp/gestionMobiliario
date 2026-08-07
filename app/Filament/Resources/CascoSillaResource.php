<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CascoSillaResource\Pages;
use App\Models\PlantillaFlujoExterno;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CascoSillaResource extends Resource
{
    protected static ?string $model = PlantillaFlujoExterno::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Mobiliario';

    protected static ?string $navigationLabel = 'Cascos de sillas';

    protected static ?string $modelLabel = 'Casco de silla';

    protected static ?string $pluralModelLabel = 'Cascos de sillas';

    protected static ?int $navigationSort = 3;

    protected static ?string $slug = 'cascos-sillas';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Stock de casco')->schema([
                Forms\Components\Placeholder::make('mobiliario_nombre')
                    ->label('Mobiliario')
                    ->content(fn (PlantillaFlujoExterno $record): string => $record->mobiliario?->nombre ?? $record->nombre),

                Forms\Components\TextInput::make('stock_casco')
                    ->label('Stock actual')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->required()
                    ->helperText('Stock de cascos tapizados por tercero. También se incrementa al completar lotes externos.'),

                Forms\Components\Placeholder::make('casco_comprometido')
                    ->label('Cantidad comprometida')
                    ->content(fn (PlantillaFlujoExterno $record): string => (string) $record->casco_comprometido),

                Forms\Components\Placeholder::make('casco_en_fabricacion')
                    ->label('Cantidad en fabricación')
                    ->content(fn (PlantillaFlujoExterno $record): string => (string) $record->casco_en_fabricacion),

                Forms\Components\Placeholder::make('stock_proyectado_casco')
                    ->label('Stock proyectado')
                    ->content(fn (PlantillaFlujoExterno $record): string => (string) $record->stock_proyectado_casco),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ViewColumn::make('mobiliario_imagen')
                    ->label('Imagen')
                    ->view('filament.casco-silla.mobiliario-imagen'),

                Tables\Columns\TextColumn::make('mobiliario.nombre')
                    ->label('Nombre')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $query) use ($search): void {
                            $query->where('nombre', 'like', "%{$search}%")
                                ->orWhereHas('mobiliario', fn (Builder $q) => $q
                                    ->where('nombre', 'like', "%{$search}%")
                                    ->orWhere('codigo_interno', 'like', "%{$search}%"));
                        });
                    })
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->join('mobiliarios', function ($join): void {
                            $join->on('mobiliarios.id', '=', 'plantillas_flujo_externo.entidad_id')
                                ->where('plantillas_flujo_externo.entidad_tipo', 'mobiliario');
                        })->orderBy('mobiliarios.nombre', $direction)
                            ->select('plantillas_flujo_externo.*');
                    })
                    ->placeholder(fn (PlantillaFlujoExterno $record): string => $record->nombre),

                Tables\Columns\TextColumn::make('mobiliario.marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('gray')
                    ->placeholder('—')
                    ->sortable(),

                Tables\Columns\TextColumn::make('mobiliario.codigo_interno')
                    ->label('Código')
                    ->badge()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('stock_casco')
                    ->label('Stock actual')
                    ->tooltip('Stock físico de cascos disponibles.')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('casco_comprometido')
                    ->label('Cantidad comprometida')
                    ->tooltip('Demanda de presupuestos activos (ítems no finalizados).')
                    ->alignCenter()
                    ->numeric(0),

                Tables\Columns\TextColumn::make('casco_en_fabricacion')
                    ->label('Cantidad en fabricación')
                    ->tooltip('Unidades en lotes externos pendientes o en proceso.')
                    ->alignCenter()
                    ->numeric(0),

                Tables\Columns\TextColumn::make('stock_proyectado_casco')
                    ->label('Stock proyectado')
                    ->tooltip('Stock actual + cantidad en fabricación − cantidad comprometida.')
                    ->alignCenter()
                    ->color(fn ($state): string => (int) $state < 0 ? 'danger' : 'success')
                    ->numeric(0),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Plantilla activa')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('stock_casco', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Editar stock'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCascosSillas::route('/'),
            'edit' => Pages\EditCascoSilla::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->cascosSilla()
            ->with(['mobiliario.media', 'mobiliario.categoria', 'mobiliario.marca']);
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
