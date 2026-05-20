<?php

namespace App\Filament\Resources\MobiliarioResource\RelationManagers;

use App\Models\Insumo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ComposicionTecnicaRelationManager extends RelationManager
{
    protected static string $relationship = 'composicionTecnica';
    protected static ?string $title = 'Composición Técnica (BOM)';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('insumo_id')
                ->label('Insumo')
                ->relationship('insumo', 'nombre')
                ->searchable()->preload()->required(),
            Forms\Components\TextInput::make('cantidad')
                ->numeric()->minValue(0.0001)->required(),
            Forms\Components\TextInput::make('version')
                ->numeric()->default(fn () => $this->getOwnerRecord()->version_actual)->required(),
            Forms\Components\Textarea::make('observaciones')->rows(2),
            Forms\Components\Toggle::make('activo')->default(true),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('insumo.nombre')
            ->columns([
                Tables\Columns\TextColumn::make('insumo.codigo')->label('Código')->badge(),
                Tables\Columns\TextColumn::make('insumo.nombre')->label('Insumo')->searchable(),
                Tables\Columns\TextColumn::make('cantidad'),
                Tables\Columns\TextColumn::make('insumo.unidadMedida.abreviatura')->label('Unidad'),
                Tables\Columns\TextColumn::make('version')->badge()->color('info'),
                Tables\Columns\IconColumn::make('activo')->boolean(),
            ])
            ->headerActions([Tables\Actions\CreateAction::make()])
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
