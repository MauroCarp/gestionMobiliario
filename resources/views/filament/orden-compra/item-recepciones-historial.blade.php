@if ($recepciones->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">No hay recepciones registradas para este ítem.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4 font-medium">Fecha</th>
                    <th class="py-2 pr-4 font-medium">Cantidad</th>
                    <th class="py-2 font-medium">Notas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recepciones as $recepcion)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-2 pr-4">{{ $recepcion->fecha_recepcion?->format('d/m/Y') }}</td>
                        <td class="py-2 pr-4">{{ number_format($recepcion->cantidad, 2, ',', '.') }}</td>
                        <td class="py-2">{{ $recepcion->notas ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
