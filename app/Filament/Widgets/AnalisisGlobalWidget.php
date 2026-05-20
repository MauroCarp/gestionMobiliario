<?php

namespace App\Filament\Widgets;

use App\Services\AnalisisPresupuestoService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalisisGlobalWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $stats = app(AnalisisPresupuestoService::class)->estadisticas();

        return [
            Stat::make('Insumos críticos', $stats['criticos'])
                ->description('Stock por debajo del mínimo')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($stats['criticos'] > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-cube'),

            Stat::make('Sin stock', $stats['sinStock'])
                ->description('Insumos con stock = 0')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color($stats['sinStock'] > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-archive-box-x-mark'),

            Stat::make('Faltantes (demanda futura)', $stats['totalFaltantes'])
                ->description('Insumos insuficientes para cubrir pedidos')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color($stats['totalFaltantes'] > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-shopping-bag'),

            Stat::make('Presupuestos pendientes', $stats['presupuestosPendientes'])
                ->description('Aprobados o confirmados')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->icon('heroicon-o-document-text'),

            Stat::make('Presupuestos pagados', $stats['presupuestosPagados'])
                ->description('Stock descontado')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success')
                ->icon('heroicon-o-banknotes'),

            Stat::make('Compras estimadas', '$' . number_format($stats['valorFaltantes'], 2))
                ->description('Valor total de insumos faltantes')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color($stats['valorFaltantes'] > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-shopping-cart'),
        ];
    }

    protected function getPollingInterval(): ?string
    {
        return '60s';
    }
}
