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
            <a href="{{ $imagenUrl }}" target="_blank" rel="noopener noreferrer" class="block">
                <img
                    src="{{ $imagenUrl }}"
                    alt="Imagen de {{ $record->nombre }}"
                    class="max-h-80 w-full rounded-lg border border-gray-200 dark:border-gray-700 object-contain bg-gray-50 dark:bg-gray-900 p-2"
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
            <object
                data="{{ $planoUrl }}"
                type="application/pdf"
                class="w-full h-80 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900"
            >
                <div class="flex h-80 flex-col items-center justify-center gap-3 p-4 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">No se pudo mostrar el PDF en el navegador.</p>
                    <a
                        href="{{ $planoUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700"
                    >
                        Abrir plano PDF
                    </a>
                </div>
            </object>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                {{ $planoMedia?->name ?? 'Plano técnico' }}
            </p>
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 italic">Sin plano cargado</p>
        @endif
    </div>
</div>
