<?php

namespace App\Filament\Resources\PresupuestoResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class VersionesRelationManager extends RelationManager
{
    protected static string $relationship = 'versiones';
    protected static ?string $title       = 'Historial de Versiones';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero_version')
                    ->label('Versión')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('motivo_cambio')
                    ->label('Motivo')
                    ->wrap()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('creadoPor.name')
                    ->label('Por')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('numero_version', 'desc')
            ->headerActions([])
            ->actions([
                Tables\Actions\Action::make('ver_snapshot')
                    ->label('Ver snapshot')
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn ($record) => view('filament.modals.version-snapshot', ['version' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
            ]);
    }
}
