<?php

namespace App\Filament\Widgets;

use App\Models\OrdenCompra;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OrdenesCompraWidget extends BaseWidget
{
    protected static bool    $isDiscovered = false;
    protected static ?int    $sort         = 3;
    protected static ?string $heading      = 'Órdenes de compra pendientes';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrdenCompra::query()
                    ->whereIn('estado', ['sugerida', 'pendiente', 'aprobada'])
                    ->withCount('items')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable(),

                Tables\Columns\TextColumn::make('estado')
                    ->badge()
                    ->color(fn (string $state): string => OrdenCompra::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenCompra::ESTADOS[$state] ?? $state),

                Tables\Columns\TextColumn::make('prioridad')
                    ->badge()
                    ->color(fn (string $state): string => OrdenCompra::PRIORIDAD_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => OrdenCompra::PRIORIDADES[$state] ?? $state),

                Tables\Columns\TextColumn::make('presupuesto.codigo')
                    ->label('Presupuesto')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('items_count')
                    ->label('Items')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('generado_automaticamente')
                    ->label('Auto')
                    ->boolean()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date('d/m/Y'),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->url(fn (OrdenCompra $r) => \App\Filament\Resources\OrdenCompraResource::getUrl('edit', ['record' => $r]))
                    ->icon('heroicon-o-eye'),
            ])
            ->paginated([5, 10]);
    }
}
