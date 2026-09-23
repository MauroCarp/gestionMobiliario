@if ($entregas->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">No hay entregas registradas para este ítem.</p>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <th class="py-2 pr-4 font-medium">Fecha</th>
                    <th class="py-2 pr-4 font-medium">Cantidad</th>
                    <th class="py-2 pr-4 font-medium">Usuario</th>
                    <th class="py-2 pr-4 font-medium">Observaciones</th>
                    <th class="py-2 font-medium">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entregas as $entrega)
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <td class="py-2 pr-4">{{ $entrega->entregado_at?->format('d/m/Y H:i') }}</td>
                        <td class="py-2 pr-4">{{ $entrega->cantidad }}</td>
                        <td class="py-2 pr-4">{{ $entrega->entregadoPor?->name ?: '—' }}</td>
                        <td class="py-2 pr-4">{{ $entrega->observaciones ?: '—' }}</td>
                        <td class="py-2">
                            @if ($entrega->estaAnulada())
                                Anulado
                                @if ($entrega->motivo_anulacion)
                                    — {{ $entrega->motivo_anulacion }}
                                @endif
                                @if ($entrega->anuladoPor)
                                    ({{ $entrega->anuladoPor->name }})
                                @endif
                            @else
                                Activo
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
