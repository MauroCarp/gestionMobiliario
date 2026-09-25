@php
    /** @var array{puede_iniciar: bool, insumos: list<array<string, mixed>>, faltantes: list<array<string, mixed>>, generara_reposicion?: bool} $analisis */
@endphp

<div class="space-y-3 text-sm">
    @if (($analisis['insumos'] ?? []) === [])
        <p>Esta orden no requiere insumos de la composición técnica (o solo tiene componentes de casco, que se gestionan por el flujo de sillas).</p>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-white/10">
            <table class="w-full text-left">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-white/5 dark:text-gray-400">
                    <tr>
                        <th class="px-3 py-2">Insumo</th>
                        <th class="px-3 py-2 text-right">Requerido</th>
                        <th class="px-3 py-2 text-right">Stock</th>
                        <th class="px-3 py-2 text-right">Reservado</th>
                        <th class="px-3 py-2 text-right">Disponible</th>
                        <th class="px-3 py-2 text-right">Faltante</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($analisis['insumos'] as $fila)
                        <tr class="border-t border-gray-100 dark:border-white/10">
                            <td class="px-3 py-2">
                                {{ $fila['nombre'] }}
                                @if (! empty($fila['codigo']))
                                    <span class="text-xs text-gray-500">[{{ $fila['codigo'] }}]</span>
                                @endif
                            </td>
                            <td class="px-3 py-2 text-right">{{ $fila['requerido'] }}</td>
                            <td class="px-3 py-2 text-right">{{ $fila['stock_actual'] }}</td>
                            <td class="px-3 py-2 text-right">{{ $fila['reservado'] }}</td>
                            <td class="px-3 py-2 text-right">{{ $fila['disponible'] }}</td>
                            <td class="px-3 py-2 text-right {{ $fila['faltante'] > 0 ? 'font-semibold text-danger-600' : 'text-success-600' }}">
                                {{ $fila['faltante'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if (($analisis['faltantes'] ?? []) !== [])
        <p class="font-medium text-warning-600 dark:text-warning-400">
            Hay faltantes. La orden se iniciará igual: se reservará la demanda completa y se generarán órdenes de compra o lotes de proceso externo por el material faltante.
        </p>
    @endif

    @if (! ($analisis['puede_iniciar'] ?? false))
        <p class="font-medium text-danger-600">La orden no tiene líneas para fabricar.</p>
    @endif
</div>
