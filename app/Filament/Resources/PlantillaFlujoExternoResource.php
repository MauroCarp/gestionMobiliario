<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlantillaFlujoExternoResource\Pages;
use App\Models\Insumo;
use App\Models\Mobiliario;
use App\Models\PlantillaFlujoExterno;
use App\Models\TipoProcesoExterno;
use App\Models\Tercero;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlantillaFlujoExternoResource extends Resource
{
    protected static ?string $model = PlantillaFlujoExterno::class;
    protected static ?string $navigationIcon  = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Tercerizados';
    protected static ?string $modelLabel      = 'Plantilla de flujo';
    protected static ?string $pluralModelLabel = 'Plantillas de flujo';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Datos generales')->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej: Flujo patas multimarcas'),
                Forms\Components\Select::make('entidad_tipo')
                    ->label('Tipo de item')
                    ->options(PlantillaFlujoExterno::ENTIDAD_TIPOS)
                    ->required()
                    ->live()
                    ->afterStateUpdated(fn (Forms\Set $set) => $set('entidad_id', null)),
                Forms\Components\Select::make('entidad_id')
                    ->label('Item')
                    ->options(fn (Forms\Get $get) => match ($get('entidad_tipo')) {
                        'insumo'     => Insumo::where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'),
                        'mobiliario' => Mobiliario::orderBy('nombre')->pluck('nombre', 'id'),
                        default      => [],
                    })
                    ->searchable()
                    ->required()
                    ->live(),
                Forms\Components\Toggle::make('activo')
                    ->default(true),
            ])->columns(2),

            Forms\Components\Section::make('Etapas del flujo')
                ->description('Etapas con el mismo número de orden se ejecutan en paralelo (ej: pintura + corte de vidrio).')
                ->schema([
                    Forms\Components\Repeater::make('etapas')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('orden')
                                ->label('Orden')
                                ->numeric()
                                ->minValue(1)
                                ->default(1)
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\Select::make('tipo_proceso_id')
                                ->label('Tipo de proceso')
                                ->options(fn () => TipoProcesoExterno::where('activo', true)->pluck('nombre', 'id'))
                                ->searchable()
                                ->required()
                                ->columnSpan(2),
                            Forms\Components\Select::make('tercero_id')
                                ->label('Tercero predeterminado')
                                ->options(fn () => Tercero::where('activo', true)->orderBy('nombre')->pluck('nombre', 'id'))
                                ->searchable()
                                ->nullable()
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('dias_estimados')
                                ->label('Días estimados')
                                ->numeric()
                                ->minValue(1)
                                ->nullable()
                                ->columnSpan(1),
                            Forms\Components\Textarea::make('observaciones')
                                ->label('Observaciones')
                                ->rows(1)
                                ->nullable()
                                ->columnSpan(3),
                        ])
                        ->columns(6)
                        ->addActionLabel('Agregar etapa')
                        ->defaultItems(0)
                        ->reorderable(false)
                        ->cloneable(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('entidad_tipo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'insumo' ? 'info' : 'warning')
                    ->formatStateUsing(fn (string $state): string => PlantillaFlujoExterno::ENTIDAD_TIPOS[$state] ?? $state),
                Tables\Columns\TextColumn::make('entidad_nombre')
                    ->label('Item')
                    ->searchable(query: fn ($query, string $search) => $query),
                Tables\Columns\TextColumn::make('etapas_count')
                    ->label('Etapas')
                    ->counts('etapas')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('entidad_tipo')
                    ->label('Tipo de item')
                    ->options(PlantillaFlujoExterno::ENTIDAD_TIPOS),
                Tables\Filters\TernaryFilter::make('activo'),
            ])
            ->actions([
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
            'index'  => Pages\ListPlantillasFlujoExterno::route('/'),
            'create' => Pages\CreatePlantillaFlujoExterno::route('/create'),
            'edit'   => Pages\EditPlantillaFlujoExterno::route('/{record}/edit'),
        ];
    }
}
