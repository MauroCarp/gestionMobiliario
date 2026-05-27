<?php

namespace App\Filament\Resources\InsumoResource\RelationManagers;

use App\Models\Tercero;
use App\Models\TipoProcesoExterno;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PlantillaFlujosRelationManager extends RelationManager
{
    protected static string $relationship = 'plantillaFlujos';

    protected static ?string $title = 'Plantillas de flujo externo';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->label('Nombre')
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),

            Forms\Components\Toggle::make('activo')
                ->label('Activa')
                ->default(true),

            Forms\Components\Section::make('Etapas')
                ->columnSpanFull()
                ->schema([
                    Forms\Components\Repeater::make('etapas')
                        ->relationship('etapas')
                        ->label('Etapas del flujo')
                        ->columns(6)
                        ->cloneable()
                        ->orderColumn('orden')
                        ->schema([
                            Forms\Components\TextInput::make('orden')
                                ->label('Orden')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(1)
                                ->columnSpan(1),

                            Forms\Components\Select::make('tipo_proceso_id')
                                ->label('Tipo de proceso')
                                ->relationship('tipoProceso', 'nombre')
                                ->options(TipoProcesoExterno::where('activo', true)->pluck('nombre', 'id'))
                                ->searchable()
                                ->required()
                                ->columnSpan(2),

                            Forms\Components\Select::make('tercero_id')
                                ->label('Tercero asignado')
                                ->relationship('tercero', 'nombre')
                                ->options(Tercero::where('activo', true)->pluck('nombre', 'id'))
                                ->searchable()
                                ->nullable()
                                ->placeholder('Sin asignar')
                                ->columnSpan(2),

                            Forms\Components\TextInput::make('dias_estimados')
                                ->label('Días est.')
                                ->numeric()
                                ->required()
                                ->minValue(1)
                                ->default(1)
                                ->columnSpan(1),

                            Forms\Components\Textarea::make('observaciones')
                                ->label('Observaciones')
                                ->rows(2)
                                ->columnSpanFull(),
                        ]),
                ]),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('etapas_count')
                    ->label('Etapas')
                    ->counts('etapas')
                    ->badge()
                    ->color('info'),

                Tables\Columns\IconColumn::make('activo')
                    ->label('Activa')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['entidad_tipo'] = 'insumo';
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->emptyStateHeading('Sin plantillas de flujo')
            ->emptyStateDescription('Creá una plantilla para definir las etapas de procesamiento externo de este insumo.');
    }
}
