<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class PresupuestoProduccionResumenInsumosSheet implements FromView, WithTitle, ShouldAutoSize
{
    public function __construct(private array $data) {}

    public function view(): View
    {
        return view('exports.presupuesto_produccion_resumen_insumos', $this->data);
    }

    public function title(): string
    {
        return 'Resumen insumos';
    }
}
