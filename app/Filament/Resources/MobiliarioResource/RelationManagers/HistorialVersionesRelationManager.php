<?php

namespace App\Filament\Resources\MobiliarioResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HistorialVersionesRelationManager extends RelationManager
{
    protected static string $relationship = 'historialVersiones';
    protected static ?string $title = 'Historial de versiones';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('version')->numeric()->required(),
            Forms\Components\TextInput::make('descripcion_cambio')->required()->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('version')
            ->columns([
                Tables\Columns\TextColumn::make('version')->badge()->color('info'),
                Tables\Columns\TextColumn::make('descripcion_cambio')->label('Cambio')->wrap(),
                Tables\Columns\TextColumn::make('usuario.name')->label('Usuario')->default('Sistema'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i')->label('Fecha'),
            ])
            ->defaultSort('version', 'desc')
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
