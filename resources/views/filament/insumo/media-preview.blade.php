@php
    $record = $getRecord();
    $imagenUrl = $record->getFirstMediaUrl('imagen');
    $planoUrl = $record->getFirstMediaUrl('plano');
    $planoMedia = $record->getFirstMedia('plano');
@endphp

<div class="grid gap-6 md:grid-cols-2">
    <div>
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Imagen</h4>
        @if ($imagenUrl)
            <a href="{{ $imagenUrl }}" target="_blank" rel="noopener noreferrer" class="inline-block">
                <img
                    src="{{ $imagenUrl }}"
                    alt="Imagen de {{ $record->nombre }}"
                    class="h-32 w-32 rounded-lg border border-gray-200 dark:border-gray-700 object-contain bg-gray-50 dark:bg-gray-900 p-2"
                />
            </a>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Clic para abrir en tamaño completo</p>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Sin imagen cargada</p>
        @endif
    </div>

    <div>
        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Plano</h4>
        @if ($planoUrl)
            <div class="flex flex-wrap items-center gap-3 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 p-3">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    {{ $planoMedia?->name ?? $planoMedia?->file_name ?? 'Plano técnico' }}
                </span>
                <a
                    href="{{ $planoUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center rounded-lg bg-primary-600 px-3 py-2 text-xs font-medium text-white hover:bg-primary-700"
                >
                    Abrir en nueva pestaña
                </a>
            </div>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Sin plano cargado</p>
        @endif
    </div>
</div>
