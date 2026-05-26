<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ProyectoResource;
use App\Models\Proyecto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProyectosActivosWidget extends BaseWidget
{
    protected static ?int    $sort    = 4;
    protected static ?string $heading = 'Proyectos activos';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Proyecto::query()
                    ->whereIn('estado', ['confirmado', 'en_produccion'])
                    ->with('marca')
                    ->orderBy('fecha_entrega_estimada')
            )
            ->columns([
                Tables\Columns\TextColumn::make('codigo_interno')
                    ->label('Código')
                    ->searchable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('marca.nombre')
                    ->label('Marca')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => Proyecto::ESTADOS[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'confirmado'    => 'warning',
                        'en_produccion' => 'info',
                        default         => 'gray',
                    }),

                Tables\Columns\TextColumn::make('fecha_entrega_estimada')
                    ->label('Entrega estimada')
                    ->date('d/m/Y')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->fecha_entrega_estimada === null                => 'gray',
                        $record->fecha_entrega_estimada->isPast()               => 'danger',
                        $record->fecha_entrega_estimada->diffInDays(now()) <= 7 => 'warning',
                        default                                                 => 'success',
                    })
                    ->placeholder('Sin fecha'),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->placeholder('—'),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->url(fn (Proyecto $r) => ProyectoResource::getUrl('edit', ['record' => $r]))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray'),
            ])
            ->emptyStateHeading('Sin proyectos activos')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated([5, 10]);
    }
}
