<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\LoteProcesoExternoResource;
use App\Models\LoteProcesoExterno;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LotesEnProcesoWidget extends BaseWidget
{
    protected static ?int    $sort    = 4;
    protected static ?string $heading = 'Lotes de proceso externo activos';
    protected int | string | array $columnSpan = 'half';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                LoteProcesoExterno::query()
                    ->whereIn('estado', ['pendiente', 'en_proceso'])
                    ->with([
                        'etapas.tipoProceso',
                        'etapas.tercero',
                    ])
                    ->latest('fecha_inicio')
            )
            ->columns([
            //     Tables\Columns\TextColumn::make('codigo')
            //         ->label('Código')
            //         ->badge()
            //         ->color('primary')
            //         ->searchable(),

                // Tables\Columns\TextColumn::make('entidad_tipo')
                //     ->label('Tipo')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'insumo'     => 'info',
                //         'mobiliario' => 'warning',
                //         default      => 'gray',
                //     })
                    // ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('entidad_nombre')
                    ->label('Elemento')
                    ->getStateUsing(fn (LoteProcesoExterno $record): string => $record->entidad_nombre ?? '—')
                    ->searchable(false)
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('cantidad')
                    ->label('Cantidad')
                    ->numeric(decimalPlaces: 2)
                    ->alignEnd(),

                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => LoteProcesoExterno::ESTADO_COLORS[$state] ?? 'gray')
                    ->formatStateUsing(fn (string $state): string => LoteProcesoExterno::ESTADOS[$state] ?? $state),

                Tables\Columns\TextColumn::make('etapa_actual')
                    ->label('Etapa actual')
                    ->badge()
                    ->color(fn ($state, LoteProcesoExterno $record): string =>
                        $record->etapaActual?->tipoProceso?->color ?? 'gray'
                    )
                    ->getStateUsing(fn (LoteProcesoExterno $record): string =>
                        $record->etapaActual?->tipoProceso?->nombre ?? '—'
                    ),

                Tables\Columns\TextColumn::make('tercero_actual')
                    ->label('Tercero')
                    ->getStateUsing(fn (LoteProcesoExterno $record): string =>
                        $record->etapaActual?->tercero?->nombre ?? '—'
                    )
                    ->placeholder('—'),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_finalizacion_estimada')
                    ->label('Fin estimado')
                    ->date('d/m/Y')
                    ->badge()
                    ->color(fn ($state, LoteProcesoExterno $record): string => match (true) {
                        $record->fecha_finalizacion_estimada === null                             => 'gray',
                        $record->fecha_finalizacion_estimada->isPast()                           => 'danger',
                        $record->fecha_finalizacion_estimada->diffInDays(now()) <= 2             => 'warning',
                        default                                                                  => 'success',
                    })
                    ->placeholder('Sin fecha'),
            ])
            ->actions([
                Tables\Actions\Action::make('ver')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->url(fn (LoteProcesoExterno $record): string =>
                        LoteProcesoExternoResource::getUrl('view', ['record' => $record])
                    ),
            ])
            ->emptyStateHeading('Sin lotes activos')
            ->emptyStateDescription('No hay lotes de proceso externo en curso.')
            ->emptyStateIcon('heroicon-o-arrow-path')
            ->paginated([5, 10, 25]);
    }
}
