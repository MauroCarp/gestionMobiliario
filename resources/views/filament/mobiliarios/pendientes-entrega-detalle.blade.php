@if ($items->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">No hay ítems pendientes de entrega para este mobiliario.</p>
@else
    <div class="space-y-4">
        @foreach ($items as $item)
            @php
                /** @var \App\Models\PresupuestoItem $item */
                $presupuesto = $item->presupuesto;
                $descripcion = $item->descripcion_override
                    ?: $item->mobiliario?->descripcion
                    ?: '—';
            @endphp

            <div class="rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                <div class="mb-3 flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            Presupuesto {{ $presupuesto?->codigo ?? '—' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Agencia: {{ $presupuesto?->agencia?->nombre ?? '—' }}
                        </p>
                    </div>
                    <span class="inline-flex items-center rounded-full bg-warning-50 px-2.5 py-0.5 text-xs font-medium text-warning-700 dark:bg-warning-500/10 dark:text-warning-400">
                        {{ $item->estaEntregaParcial() ? 'Entrega parcial' : 'Pendiente de entrega' }}
                    </span>
                </div>

                <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Cantidad</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $item->cantidadPendiente() }} pendientes / {{ $item->cantidad }} total</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Sector</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $item->sector?->nombre ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Descripción</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $descripcion }}</dd>
                    </div>
                    @if (filled($item->observaciones))
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Observaciones</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ $item->observaciones }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Etapa actual</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $item->etapa_actual_produccion }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Progreso producción</dt>
                        <dd class="text-gray-900 dark:text-gray-100">{{ $item->progreso_produccion }}</dd>
                    </div>
                </dl>

                @if ($item->etapasProduccion->isNotEmpty())
                    <div class="mt-4 border-t border-gray-100 pt-3 dark:border-gray-800">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Etapas de producción
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($item->etapasProduccion as $etapa)
                                <span @class([
                                    'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium',
                                    'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400' => $etapa->estado === 'completado',
                                    'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400' => $etapa->estado === 'en_proceso',
                                    'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300' => $etapa->estado === 'pendiente',
                                ])>
                                    {{ $etapa->nombre }} — {{ \App\Models\PresupuestoItemEtapa::ESTADOS[$etapa->estado] ?? $etapa->estado }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif
