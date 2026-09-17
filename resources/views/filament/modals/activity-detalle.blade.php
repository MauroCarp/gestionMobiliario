@php
    $props = $activity->properties;
    $props = $props instanceof \Illuminate\Support\Collection ? $props->toArray() : (array) $props;

    $old = is_array($props['old'] ?? null) ? $props['old'] : [];
    $new = is_array($props['attributes'] ?? null) ? $props['attributes'] : [];
    $keys = array_values(array_unique(array_merge(array_keys($old), array_keys($new))));

    $accion = match ($activity->description) {
        'created' => 'Creado',
        'updated' => 'Modificado',
        'deleted' => 'Eliminado',
        default => $activity->description,
    };

    $formatCampo = fn (string $campo): string => ucfirst(str_replace('_', ' ', $campo));

    $formatValor = function (mixed $valor): string {
        if ($valor === null || $valor === '') {
            return '—';
        }

        if (is_bool($valor)) {
            return $valor ? 'Sí' : 'No';
        }

        if (is_array($valor) || is_object($valor)) {
            return json_encode($valor, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return (string) $valor;
    };

    $esComplejo = fn (mixed $valor): bool => is_array($valor) || is_object($valor);
@endphp

<div class="space-y-4 text-sm">
    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2 text-gray-700 dark:text-gray-200">
        <div>
            <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Fecha</dt>
            <dd>{{ $activity->created_at?->format('d/m/Y H:i') ?? '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Usuario</dt>
            <dd>{{ $activity->causer?->name ?? 'Sistema' }}</dd>
        </div>
        <div>
            <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Acción</dt>
            <dd>{{ $accion }}</dd>
        </div>
        <div>
            <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">Modelo</dt>
            <dd>{{ $activity->subject_type ? class_basename($activity->subject_type) : '—' }}</dd>
        </div>
        <div>
            <dt class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">ID</dt>
            <dd>{{ $activity->subject_id ?? '—' }}</dd>
        </div>
    </dl>

    @if($keys === [])
        <p class="text-gray-500 dark:text-gray-400 italic">Sin cambios de campos.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left border border-gray-200 dark:border-gray-700 rounded">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-3 py-2">Campo</th>
                        <th class="px-3 py-2">Anterior</th>
                        <th class="px-3 py-2">Nuevo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($keys as $campo)
                        @php
                            $tieneAnterior = array_key_exists($campo, $old);
                            $tieneNuevo = array_key_exists($campo, $new);
                            $valorAnterior = $tieneAnterior ? $old[$campo] : null;
                            $valorNuevo = $tieneNuevo ? $new[$campo] : null;
                        @endphp
                        <tr class="border-t border-gray-200 dark:border-gray-700 align-top">
                            <td class="px-3 py-2 font-medium whitespace-nowrap">{{ $formatCampo($campo) }}</td>
                            <td class="px-3 py-2">
                                @if(! $tieneAnterior)
                                    <span class="text-gray-400">—</span>
                                @elseif($esComplejo($valorAnterior))
                                    <pre class="whitespace-pre-wrap text-xs">{{ $formatValor($valorAnterior) }}</pre>
                                @else
                                    {{ $formatValor($valorAnterior) }}
                                @endif
                            </td>
                            <td class="px-3 py-2">
                                @if(! $tieneNuevo)
                                    <span class="text-gray-400">—</span>
                                @elseif($esComplejo($valorNuevo))
                                    <pre class="whitespace-pre-wrap text-xs">{{ $formatValor($valorNuevo) }}</pre>
                                @else
                                    {{ $formatValor($valorNuevo) }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
