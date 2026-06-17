<?php

namespace App\Exports;

use App\Exports\Sheets\PresupuestoProduccionDetalleInsumosSheet;
use App\Exports\Sheets\PresupuestoProduccionResumenInsumosSheet;
use App\Exports\Sheets\PresupuestoProduccionResumenSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PresupuestoProduccionExport implements WithMultipleSheets
{
    public function __construct(private array $data) {}

    public function sheets(): array
    {
        return [
            new PresupuestoProduccionResumenSheet($this->data),
            new PresupuestoProduccionDetalleInsumosSheet($this->data),
            new PresupuestoProduccionResumenInsumosSheet($this->data),
        ];
    }
}
