<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\PresupuestoResource;
use App\Models\Presupuesto;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class PresupuestosPendientesEntregaWidget extends BaseWidget
{
    protected static ?int    $sort    = 2;
    protected static ?string $heading = 'Presupuestos pendientes de entrega';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Presupuesto::query()
                    ->whereIn('estado', ['confirmado', 'pagado', 'entregado_parcial'])
                    ->with(['agencia.proyecto.marca', 'agencia.provincia', 'agencia.ciudad'])
                    ->withCount([
                        'items as muebles_pendientes_count' => fn ($query) => $query
                            ->whereNotNull('mobiliario_id')
                            ->whereNull('finalizado_at'),
                        'items as muebles_total_count' => fn ($query) => $query
                            ->whereNotNull('mobiliario_id')

                    ])
                    // ->orderByRaw('fecha_vencimiento IS NULL')
                    // ->orderBy('fecha_vencimiento')
                    // ->latest('id')
            )
            ->defaultSort('fecha_vencimiento', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Presupuesto')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                Tables\Columns\TextColumn::make('agencia.proyecto.marca.nombre')
                    ->label('Marca')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('agencia.nombre')
                    ->label('Agencia')
                    ->placeholder('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('agencia.provincia.nombre')
                    ->label('Provincia')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('agencia.ciudad.nombre')
                    ->label('Ciudad')
                    ->sortable()
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('muebles_pendientes_finalizar')
                    ->label('Muebles pendientes de finalizar')
                    ->badge()
                    ->getStateUsing(fn (Presupuesto $record): string => "{$record->muebles_pendientes_count}/{$record->muebles_total_count}")
                    ->color(fn (Presupuesto $record): string => $record->muebles_pendientes_count > 0 ? 'warning' : 'success'),

                Tables\Columns\TextColumn::make('fecha_vencimiento')
                    ->label('Fecha vencimiento')
                    ->date('d/m/Y')
                    ->badge()
                    ->sortable()
                    ->extraAttributes(['class' => 'mi-badge-grande'])                    ->color(fn ($record) => match (true) {
                        $record->fecha_vencimiento === null                        => 'gray',
                        $record->fecha_vencimiento->isPast()                      => 'danger',
                        $record->fecha_vencimiento->diffInDays(now(), absolute: true) <= 10       => 'danger',
                        $record->fecha_vencimiento->diffInDays(now(), absolute: true) >= 15       => 'success',
                        default                                                   => 'warning',
                    })
                    ->placeholder('Fecha no definida'),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('Ver presupuesto')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Presupuesto $record): string => PresupuestoResource::getUrl('view', ['record' => $record])),
            ])
            ->emptyStateHeading('Sin presupuestos en producción pendientes de entrega')
            ->emptyStateIcon('heroicon-o-document-text')
            ->paginated([5, 10, 25]);
    }
}
