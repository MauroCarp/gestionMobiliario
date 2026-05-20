<?php

namespace App\Filament\Resources\ProyectoResource\RelationManagers;

use App\Models\Proyecto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HistorialRelationManager extends RelationManager
{
    protected static string $relationship = 'historial';
    protected static ?string $title = 'Historial de estados';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Textarea::make('observacion')
                ->label('Observación')
                ->rows(3),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('estado_nuevo')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\BadgeColumn::make('estado_anterior')
                    ->formatStateUsing(fn ($state) => Proyecto::ESTADOS[$state] ?? $state ?? '—')
                    ->color('gray'),
                Tables\Columns\BadgeColumn::make('estado_nuevo')
                    ->formatStateUsing(fn ($state) => Proyecto::ESTADOS[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'presupuestar'  => 'gray',
                        'presupuestado' => 'warning',
                        'confirmado'    => 'info',
                        'en_produccion' => 'primary',
                        'entregado'     => 'success',
                        default         => 'gray',
                    }),
                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Usuario')->default('Sistema'),
                Tables\Columns\TextColumn::make('observacion')->limit(60),
            ])
            ->defaultSort('created_at', 'desc')
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
