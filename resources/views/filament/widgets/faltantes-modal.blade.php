@if($faltantes->isNotEmpty())
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
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
                </tr>
            </thead>
            <tbody>
                @foreach($faltantes as $i => $r)
                    <tr class="{{ $i % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} border-b border-gray-100 dark:border-gray-700">
                        <td class="px-4 py-2 font-medium">
                            <div>{{ $r['insumo']->nombre }}</div>
                            <div class="text-xs text-gray-400">{{ $r['insumo']->codigo }}</div>
                        </td>
                        <td class="px-3 py-2 text-right">{{ number_format($r['requerido'], 2) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($r['stock_actual'], 2) }}</td>
                        <td class="px-3 py-2 text-right text-yellow-600 dark:text-yellow-400">{{ number_format($r['reservado'], 2) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($r['disponible'], 2) }}</td>
                        <td class="px-3 py-2 text-right font-bold text-red-600 dark:text-red-400">{{ number_format($r['faltante'], 2) }}</td>
                        <td class="px-3 py-2 text-center text-gray-500">{{ $r['unidad'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">
        No hay insumos insuficientes para la demanda futura.
    </p>
@endif
