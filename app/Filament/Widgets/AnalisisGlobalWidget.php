<?php

namespace App\Filament\Widgets;

use App\Models\Presupuesto;
use App\Services\AnalisisPresupuestoService;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Collection;

class AnalisisGlobalWidget extends BaseWidget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static ?int $sort = 1;

    protected static string $view = 'filament.widgets.analisis-global';

    protected function getStats(): array
    {
        $stats = app(AnalisisPresupuestoService::class)->estadisticas();

        return [
            Stat::make('Faltantes (demanda futura)', $stats['totalFaltantes'])
                ->description('Insumos insuficientes para cubrir pedidos. Clic para ver el detalle')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color($stats['totalFaltantes'] > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-shopping-bag')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "mountAction('faltantes')",
                    'role' => 'button',
                ]),

            Stat::make('Presupuestos pendientes', $stats['presupuestosPendientes'])
                ->description('Enviados a cliente. Clic para ver el detalle')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info')
                ->icon('heroicon-o-document-text')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "mountAction('enviados')",
                    'role' => 'button',
                ]),
        ];
    }

    public function faltantesAction(): Action
    {
        return Action::make('faltantes')
            ->modalHeading('Insumos insuficientes')
            ->modalDescription('Insumos con stock insuficiente para cubrir la demanda futura.')
            ->modalWidth('7xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->modalContent(fn () => view('filament.widgets.faltantes-modal', [
                'faltantes' => $this->insumosFaltantes(),
            ]));
    }

    public function enviadosAction(): Action
    {
        return Action::make('enviados')
            ->modalHeading('Presupuestos enviados a cliente')
            ->modalDescription('Presupuestos pendientes de respuesta del cliente.')
            ->modalWidth('7xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Cerrar')
            ->modalContent(fn () => view('filament.widgets.presupuestos-enviados-modal', [
                'presupuestos' => $this->presupuestosEnviados(),
            ]));
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    private function insumosFaltantes(): Collection
    {
        return collect(app(AnalisisPresupuestoService::class)->analizarDemandaFutura())
            ->filter(fn (array $row): bool => ($row['faltante'] ?? 0) > 0)
            ->values();
    }

    /**
     * @return Collection<int, Presupuesto>
     */
    private function presupuestosEnviados(): Collection
    {
        return app(AnalisisPresupuestoService::class)->presupuestosEnviadosACliente();
    }

    protected function getPollingInterval(): ?string
    {
        return '60s';
    }
}
