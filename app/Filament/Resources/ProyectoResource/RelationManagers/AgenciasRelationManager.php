<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AgenciasRelationManager extends RelationManager
{
    protected static string $relationship = 'agencias';
    protected static ?string $title = 'Agencias del proyecto';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('codigo_interno')
                ->label('Código interno')
                ->required()
                ->maxLength(50)
                ->unique(ignoreRecord: true),
            Forms\Components\Select::make('prioridad')
                ->options([1 => 'Alta', 2 => 'Media', 3 => 'Baja'])
                ->default(3)
                ->required(),
            Forms\Components\TextInput::make('responsable')
                ->maxLength(255),
            Forms\Components\TextInput::make('direccion')
                ->label('Dirección')
                ->maxLength(255),
            Forms\Components\Toggle::make('activo')
                ->label('Activa')
                ->default(true),
            Forms\Components\TagsInput::make('etiquetas')
                ->separator(',')
                ->label('Etiquetas')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('observaciones')
                ->rows(3)
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nombre')
            ->columns([
                Tables\Columns\TextColumn::make('codigo_interno')
                    ->label('Código')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('prioridad')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => 'Alta', 2 => 'Media', default => 'Baja',
                    })
                    ->color(fn ($state) => match ($state) {
                        1 => 'danger', 2 => 'warning', default => 'success',
                    }),
                Tables\Columns\IconColumn::make('activo')
                    ->boolean(),
                Tables\Columns\TextColumn::make('presupuestos_count')
                    ->label('Presupuestos')
                    ->counts('presupuestos'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
