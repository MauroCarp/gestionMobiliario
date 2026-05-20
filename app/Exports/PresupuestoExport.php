<?php

namespace App\Exports;

use App\Models\Presupuesto;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\View\View;

class PresupuestoExport implements FromView, WithTitle, ShouldAutoSize
{
    public function __construct(private Presupuesto $presupuesto) {}

    public function view(): View
    {
        return view('exports.presupuesto_excel', [
            'presupuesto' => $this->presupuesto,
        ]);
    }

    public function title(): string
    {
        return $this->presupuesto->codigo;
    }
}
