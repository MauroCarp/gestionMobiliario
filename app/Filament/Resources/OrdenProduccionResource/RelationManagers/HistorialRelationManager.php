<?php

namespace App\Filament\Resources\OrdenProduccionResource\RelationManagers;

use App\Models\OrdenProduccion;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HistorialRelationManager extends RelationManager
{
    protected static string $relationship = 'historial';

    protected static ?string $title = 'Historial';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('estado_anterior')
                    ->label('Desde')
                    ->formatStateUsing(fn (?string $state): string => $state
                        ? (OrdenProduccion::ESTADOS[$state] ?? $state)
                        : '—'),

                Tables\Columns\TextColumn::make('estado_nuevo')
                    ->label('Hacia')
                    ->badge()
                    ->color(fn (string $state): string => OrdenProduccion::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenProduccion::ESTADOS[$state] ?? $state),

                Tables\Columns\TextColumn::make('comentario')
                    ->placeholder('—')
                    ->wrap(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuario')
                    ->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
