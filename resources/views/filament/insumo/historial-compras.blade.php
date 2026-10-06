@if ($filas->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">Este insumo no tiene órdenes de compra.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4 font-medium">Orden de compra</th>
                    <th class="py-2 pr-4 font-medium">Fecha del costo</th>
                    <th class="py-2 pr-4 font-medium">Costo unitario</th>
                    <th class="py-2 pr-4 font-medium">Cantidad</th>
                    <th class="py-2 pr-4 font-medium">Estado</th>
                    <th class="py-2 font-medium">Proveedor</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($filas as $fila)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-2 pr-4">
                            @if (! empty($fila['url']))
                                <a href="{{ $fila['url'] }}" class="text-primary-600 hover:underline dark:text-primary-400">
                                    {{ $fila['codigo'] }}
                                </a>
                            @else
                                {{ $fila['codigo'] }}
                            @endif
                        </td>
                        <td class="py-2 pr-4">{{ $fila['fecha']?->format('d/m/Y') ?? '—' }}</td>
                        <td class="py-2 pr-4">
                            {{ $fila['costo'] !== null ? '$' . number_format((float) $fila['costo'], 2, ',', '.') : '—' }}
                        </td>
                        <td class="py-2 pr-4">
                            {{ $fila['cantidad'] !== null ? number_format((float) $fila['cantidad'], 2, ',', '.') : '—' }}
                        </td>
                        <td class="py-2 pr-4">{{ $fila['estado_label'] }}</td>
                        <td class="py-2">{{ $fila['proveedor'] ?: '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
