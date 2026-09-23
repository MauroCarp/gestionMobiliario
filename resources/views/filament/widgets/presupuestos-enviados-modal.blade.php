<div>
    @if($presupuestos->isNotEmpty())
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full text-sm">
                <thead class="bg-blue-800 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Código</th>
                        <th class="px-3 py-2 text-left">Marca</th>
                        <th class="px-3 py-2 text-left">Agencia</th>
                        <th class="px-3 py-2 text-left">Responsable</th>
                        <th class="px-3 py-2 text-left">Emisión</th>
                        <th class="px-3 py-2 text-left">Fecha posible de entrega</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($presupuestos as $i => $presupuesto)
                        <tr class="{{ $i % 2 === 0 ? 'bg-white dark:bg-gray-900' : 'bg-gray-50 dark:bg-gray-800' }} border-b border-gray-100 dark:border-gray-700">
                            <td class="px-4 py-2 font-medium">
                                <a
                                    href="{{ \App\Filament\Resources\PresupuestoResource::getUrl('view', ['record' => $presupuesto]) }}"
                                    class="text-primary-600 hover:underline dark:text-primary-400"
                                >
                                    {{ $presupuesto->codigo }}
                                </a>
                            </td>
                            <td class="px-3 py-2">{{ $presupuesto->agencia?->proyecto?->marca?->nombre ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $presupuesto->agencia?->nombre ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $presupuesto->responsable?->name ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $presupuesto->fecha_emision?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-3 py-2">{{ $presupuesto->fecha_vencimiento?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="py-8 text-center text-sm text-gray-500 dark:text-gray-400">
            No hay presupuestos enviados a cliente.
        </p>
    @endif
</div>
