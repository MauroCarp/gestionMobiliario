<?php

namespace App\Filament\Resources\OrdenProduccionResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class IngresosRelationManager extends RelationManager
{
    protected static string $relationship = 'movimientos';

    protected static ?string $title = 'Avances de producción';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with([
                'item.mobiliario',
                'item.marca',
                'registradoPor',
            ]))
            ->columns([
                Tables\Columns\TextColumn::make('registrado_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('item.mobiliario.nombre')
                    ->label('Mobiliario')
                    ->searchable(),

                Tables\Columns\TextColumn::make('item.marca.nombre')
                    ->label('Marca')
                    ->badge(),

                Tables\Columns\TextColumn::make('cantidad')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('registradoPor.name')
                    ->label('Usuario')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('observaciones')
                    ->placeholder('—')
                    ->wrap(),
            ])
            ->defaultSort('registrado_at', 'desc')
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
