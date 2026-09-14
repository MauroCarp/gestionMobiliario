<?php

namespace App\Http\Controllers;

use App\Filament\Pages\Impresora;
use App\Models\Empleado;
use App\Models\Marca;
use App\Models\Mobiliario;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ImpresoraEtiquetaController extends Controller
{
    public function __invoke(Request $request): View
    {
        abort_unless(Impresora::canAccess(), 403);

        $data = $request->validate([
            'marca_id' => ['required', 'integer', 'exists:marcas,id'],
            'mobiliario_id' => ['required', 'integer', 'exists:mobiliarios,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'legajos' => ['required', 'array', 'min:1'],
            'legajos.*' => ['required', 'string', 'exists:empleados,legajo'],
        ]);

        $marca = Marca::query()->findOrFail($data['marca_id']);
        $mobiliario = Mobiliario::query()
            ->whereKey($data['mobiliario_id'])
            ->whereHas('marcas', fn ($query) => $query->where('marcas.id', $marca->id))
            ->firstOrFail();
        $empleadosPorLegajo = Empleado::query()
            ->whereIn('legajo', $data['legajos'])
            ->get()
            ->keyBy('legajo');
        $empleados = collect($data['legajos'])
            ->map(fn (string $legajo) => $empleadosPorLegajo->get($legajo))
            ->filter();

        return view('impresora.etiqueta', [
            'marca' => $marca,
            'mobiliario' => $mobiliario,
            'cantidad' => (int) $data['cantidad'],
            'empleados' => $empleados,
        ]);
    }
}
