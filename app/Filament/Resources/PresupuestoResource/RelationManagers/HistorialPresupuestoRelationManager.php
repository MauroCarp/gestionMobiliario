<?php

namespace App\Filament\Resources\PresupuestoResource\RelationManagers;

use App\Models\Presupuesto;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HistorialPresupuestoRelationManager extends RelationManager
{
    protected static string $relationship = 'historial';
    protected static ?string $title       = 'Historial de Estados';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('estado_anterior')
                    ->label('Estado anterior')
                    ->badge()
                    ->color(fn (?string $state): string => $state ? (Presupuesto::ESTADO_COLORS[$state] ?? 'gray') : 'gray')
                    ->formatStateUsing(fn (?string $state): string => $state ? (Presupuesto::ESTADOS[$state] ?? $state) : '—')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('estado_nuevo')
                    ->label('Estado nuevo')
                    ->badge()
                    ->color(fn (string $state): string => Presupuesto::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => Presupuesto::ESTADOS[$state] ?? $state),

                Tables\Columns\TextColumn::make('comentario')
                    ->label('Comentario')
                    ->wrap()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Por')
                    ->placeholder('Sistema'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->actions([]);
    }
}
