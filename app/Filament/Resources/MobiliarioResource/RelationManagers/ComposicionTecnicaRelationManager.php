<?php

namespace App\Filament\Resources\MobiliarioResource\RelationManagers;

use App\Models\Insumo;
use App\Models\Mobiliario;
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
            Forms\Components\Toggle::make('es_componente_casco')
                ->label('Componente del casco')
                ->helperText('Si está activo, este insumo solo se reserva/fabrica por las unidades de casco faltantes (categoría Sillas).')
                ->visible(fn (): bool => $this->mobiliarioEsCategoriaSillas())
                ->default(false),
        ]);
    }

    private function mobiliarioEsCategoriaSillas(): bool
    {
        $mobiliario = $this->getOwnerRecord();

        if (! $mobiliario instanceof Mobiliario) {
            return false;
        }

        $mobiliario->loadMissing('categoria');

        return ($mobiliario->categoria?->nombre ?? '') === 'Sillas';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('insumo.nombre')
            ->columns([
                Tables\Columns\TextColumn::make('insumo.codigo')->label('Código')->badge(),
                Tables\Columns\TextColumn::make('insumo.nombre')->label('Insumo')->searchable(),
                Tables\Columns\TextColumn::make('cantidad'),
                Tables\Columns\IconColumn::make('es_componente_casco')
                    ->label('Casco')
                    ->boolean()
                    ->visible(fn (): bool => $this->mobiliarioEsCategoriaSillas()),
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
