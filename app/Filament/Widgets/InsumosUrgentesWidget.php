<?php

namespace App\Filament\Widgets;

use App\Models\Insumo;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class InsumosUrgentesWidget extends BaseWidget
{
    protected static ?int  $sort     = 2;
    protected static ?string $heading = 'Insumos críticos y sin stock';
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Insumo::query()
                    ->where('activo', true)
                    ->where(function (Builder $q) {
                        $q->whereRaw('stock_actual <= stock_minimo')
                          ->orWhere('stock_actual', 0);
                    })
                    ->with('unidadMedida')
                    ->orderBy('stock_actual')
            )
            ->columns([
                Tables\Columns\TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('stock_actual')
                    ->label('Stock actual')
                    ->alignRight()
                    ->badge()
                    ->color(fn ($record) => $record->stock_actual == 0 ? 'danger' : 'warning'),

                Tables\Columns\TextColumn::make('stock_minimo')
                    ->label('Mínimo')
                    ->alignRight(),

                Tables\Columns\TextColumn::make('unidadMedida.nombre')
                    ->label('Unidad')
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->placeholder('—'),
            ])
            ->paginated([10, 25]);
    }
}
