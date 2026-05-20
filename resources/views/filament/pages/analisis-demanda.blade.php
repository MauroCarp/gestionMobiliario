<x-filament-panels::page>
    {{-- Selector de presupuesto --}}
    <div class="flex flex-wrap items-end gap-3 mb-6">
        <div class="flex-1 min-w-[260px]">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Filtrar por presupuesto
            </label>
            <select
                wire:model="presupuesto_id"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 text-sm"
            >
                <option value="">— Demanda global (todos los aprobados/confirmados) —</option>
                @foreach(\App\Models\Presupuesto::whereIn('estado', ['aprobado','confirmado','pagado'])->orderByDesc('created_at')->get() as $p)
                    <option value="{{ $p->id }}">{{ $p->codigo }} — {{ \App\Models\Presupuesto::ESTADOS[$p->estado] ?? $p->estado }}</option>
                @endforeach
            </select>
        </div>
        <button
            wire:click="analizarPresupuesto"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700"
        >
            Analizar
        </button>
    </div>

    {{-- Título del modo --}}
    <div class="mb-4">
        <h2 class="text-lg font-bold text-gray-800 dark:text-gray-200">
            @if($modoGlobal)
                📊 Demanda futura global (presupuestos aprobados + confirmados + pagados)
            @else
                📋 Análisis del presupuesto seleccionado
            @endif
        </h2>
    </div>

    {{-- Tabla de insumos requeridos --}}
    @if(count($resultados) > 0)
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 mb-8">
            <table class="w-full text-sm">
                <thead class="bg-blue-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Insumo</th>
                        <th class="px-3 py-2 text-right">Requerido</th>
                        <th class="px-3 py-2 text-right">Stock actual</th>
                        <th class="px-3 py-2 text-right">Reservado</th>
                        <th class="px-3 py-2 text-right">Disponible</th>
                        <th class="px-3 py-2 text-right">Faltante</th>
                        <th class="px-3 py-2 text-center">Unidad</th>
                        <th class="px-3 py-2 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultados as $i => $r)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} border-b border-gray-100 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium">
                                <div>{{ $r['insumo']->nombre }}</div>
                                <div class="text-xs text-gray-400">{{ $r['insumo']->codigo }}</div>
                            </td>
                            <td class="px-3 py-2 text-right">{{ number_format($r['requerido'], 2) }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($r['stock_actual'], 2) }}</td>
                            <td class="px-3 py-2 text-right text-yellow-600">{{ number_format($r['reservado'], 2) }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($r['disponible'], 2) }}</td>
                            <td class="px-3 py-2 text-right font-bold {{ $r['faltante'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                {{ $r['faltante'] > 0 ? number_format($r['faltante'], 2) : '✓' }}
                            </td>
                            <td class="px-3 py-2 text-center text-gray-500">{{ $r['unidad'] }}</td>
                            <td class="px-3 py-2 text-center">
                                @if($r['faltante'] > 0)
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300">Faltante</span>
                                @elseif($r['critico'])
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300">Crítico</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300">OK</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-12 text-gray-400">
            No hay datos de demanda. Asegúrese de que los mobiliarios tengan composición técnica definida.
        </div>
    @endif

    {{-- Sugerencias de compra (solo modo global) --}}
    @if($modoGlobal && count($sugerencias) > 0)
        <h3 class="text-base font-bold text-gray-800 dark:text-gray-200 mb-3">
            🛒 Sugerencias de compra
        </h3>
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-yellow-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Insumo</th>
                        <th class="px-3 py-2 text-right">Demanda</th>
                        <th class="px-3 py-2 text-right">Disponible</th>
                        <th class="px-3 py-2 text-right">Faltante</th>
                        <th class="px-3 py-2 text-right font-bold">Sugerido comprar</th>
                        <th class="px-3 py-2 text-right">Precio unit.</th>
                        <th class="px-3 py-2 text-right">Total est.</th>
                        <th class="px-3 py-2 text-center">Prioridad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sugerencias as $i => $s)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} border-b border-gray-100 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium">{{ $s['insumo']['nombre'] ?? $s['insumo']->nombre }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($s['demanda_total'], 2) }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($s['stock_disponible'], 2) }}</td>
                            <td class="px-3 py-2 text-right text-red-600">{{ number_format($s['faltante'], 2) }}</td>
                            <td class="px-3 py-2 text-right font-bold text-blue-700 dark:text-blue-300">{{ number_format($s['cantidad_sugerida'], 2) }}</td>
                            <td class="px-3 py-2 text-right">
                                @php $precio = is_array($s['insumo']) ? ($s['insumo']['precio_costo'] ?? 0) : ($s['insumo']->precio_costo ?? 0); @endphp
                                {{ $precio ? '$' . number_format($precio, 2) : '—' }}
                            </td>
                            <td class="px-3 py-2 text-right">{{ $s['valor_estimado'] > 0 ? '$' . number_format($s['valor_estimado'], 2) : '—' }}</td>
                            <td class="px-3 py-2 text-center">
                                @php $colors = ['critica' => 'red', 'alta' => 'orange', 'media' => 'yellow', 'baja' => 'gray']; $c = $colors[$s['prioridad']] ?? 'gray'; @endphp
                                <span class="px-2 py-0.5 rounded-full text-xs bg-{{ $c }}-100 text-{{ $c }}-700 dark:bg-{{ $c }}-900 dark:text-{{ $c }}-300">
                                    {{ ucfirst($s['prioridad']) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-filament-panels::page>
