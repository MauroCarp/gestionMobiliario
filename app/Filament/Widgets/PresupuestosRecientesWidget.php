<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PresupuestoResource;
use App\Models\Presupuesto;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PresupuestosRecientesWidget extends BaseWidget
{
    protected static ?int    $sort    = 2;
    protected static ?string $heading = 'Presupuestos activos';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Presupuesto::query()
                    ->whereIn('estado', ['borrador', 'en_revision', 'aprobado', 'confirmado'])
                    ->with(['agencia.proyecto.marca'])
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('agencia.proyecto.marca.nombre')
                    ->label('Marca')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('agencia.nombre')
                    ->label('Agencia')
                    ->placeholder('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => Presupuesto::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => Presupuesto::ESTADOS[$state] ?? $state),

                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->badge()
                    ->color(fn ($record) => match (true) {
                        $record->fecha_vencimiento === null                        => 'gray',
                        $record->fecha_vencimiento->isPast()                      => 'danger',
                        $record->fecha_vencimiento->diffInDays(now()) <= 3        => 'warning',
                        default                                                   => 'success',
                    })
                    ->placeholder('Sin vencimiento'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->url(fn (Presupuesto $r) => PresupuestoResource::getUrl('edit', ['record' => $r]))
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('gray'),
            ])
            ->emptyStateHeading('Sin presupuestos activos')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated([5, 10]);
    }
}
