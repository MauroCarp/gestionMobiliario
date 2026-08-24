@php
    $items = ($documentos ?? collect())
        ->map(fn ($media) => [
            'url' => $media->getUrl(),
            'nombre' => $media->name ?: $media->file_name,
        ])
        ->values();
@endphp

@if ($items->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">No hay documentos técnicos cargados.</p>
@else
    <div class="space-y-2 py-2">
        @foreach ($items as $documento)
            <a href="{{ $documento['url'] }}"
               target="_blank"
               rel="noopener noreferrer"
               class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span>{{ $documento['nombre'] }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        @endforeach
    </div>
@endif
