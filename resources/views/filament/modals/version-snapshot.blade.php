<div class="space-y-4 text-sm">
    <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
        Versión {{ $version->numero_version }} — {{ $version->created_at->format('d/m/Y H:i') }}
    </h3>

    @if($version->motivo_cambio)
        <p class="text-gray-500 italic">Motivo: {{ $version->motivo_cambio }}</p>
    @endif

    @php $snap = $version->snapshot; @endphp

    <div class="overflow-x-auto">
        <table class="w-full text-left border border-gray-200 dark:border-gray-700 rounded">
            <thead class="bg-gray-100 dark:bg-gray-800">
                <tr>
                    <th class="px-3 py-2">#</th>
                    <th class="px-3 py-2">Mobiliario</th>
                    <th class="px-3 py-2">Cant.</th>
                    <th class="px-3 py-2">Precio Unit.</th>
                    <th class="px-3 py-2">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($snap['items'] ?? [] as $i => $item)
                <tr class="border-t border-gray-200 dark:border-gray-700">
                    <td class="px-3 py-2">{{ $i + 1 }}</td>
                    <td class="px-3 py-2">{{ $item['mobiliario']['nombre'] ?? $item['mobiliario_id'] }}</td>
                    <td class="px-3 py-2">{{ $item['cantidad'] }}</td>
                    <td class="px-3 py-2">{{ $item['precio_unitario'] ? '$' . number_format($item['precio_unitario'], 2) : '—' }}</td>
                    <td class="px-3 py-2">{{ $item['observaciones'] ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
