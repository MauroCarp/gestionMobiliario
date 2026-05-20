<?php

namespace App\Filament\Pages;

use App\Models\Presupuesto;
use App\Services\AnalisisPresupuestoService;
use App\Services\StockReservaService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class AnalisisDemanda extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon  = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Operaciones';
    protected static ?string $navigationLabel = 'Análisis de Demanda';
    protected static ?string $title           = 'Análisis de Demanda e Insumos';
    protected static ?int    $navigationSort  = 2;

    protected static string $view = 'filament.pages.analisis-demanda';

    public ?int $presupuesto_id = null;

    /** @var array<int, array<string, mixed>> */
    public array $resultados = [];

    /** @var array<int, array<string, mixed>> */
    public array $sugerencias = [];

    public bool $modoGlobal = true;

    public function mount(): void
    {
        $this->analizarGlobal();
    }

    public function analizarGlobal(): void
    {
        $this->modoGlobal     = true;
        $this->presupuesto_id = null;
        $this->resultados     = app(AnalisisPresupuestoService::class)->analizarDemandaFutura();
        $this->sugerencias    = app(AnalisisPresupuestoService::class)->sugerenciasCompra()->toArray();
    }

    public function analizarPresupuesto(): void
    {
        if (! $this->presupuesto_id) {
            return;
        }

        $presupuesto = Presupuesto::find($this->presupuesto_id);
        if (! $presupuesto) return;

        $this->modoGlobal  = false;
        $this->resultados  = app(AnalisisPresupuestoService::class)->analizar($presupuesto);
        $this->sugerencias = [];
    }

    public function generarOrden(): void
    {
        if (! $this->presupuesto_id) {
            Notification::make()->title('Seleccione un presupuesto')->warning()->send();
            return;
        }

        $presupuesto = Presupuesto::find($this->presupuesto_id);
        $orden = app(StockReservaService::class)->generarOrdenCompraAutomatica($presupuesto);

        if ($orden) {
            Notification::make()
                ->title("Orden {$orden->codigo} creada con prioridad {$orden->prioridad}")
                ->success()
                ->send();
        } else {
            Notification::make()->title('No hay insumos faltantes, no se generó orden')->info()->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('global')
                ->label('Ver demanda global')
                ->icon('heroicon-o-globe-alt')
                ->color('gray')
                ->action('analizarGlobal'),

            Action::make('generar_orden')
                ->label('Generar orden de compra')
                ->icon('heroicon-o-shopping-cart')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Generar orden de compra automática')
                ->modalDescription('Se creará una orden de compra con los insumos faltantes del presupuesto seleccionado.')
                ->action('generarOrden'),
        ];
    }
}
