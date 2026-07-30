@php
    $mobiliario = $getRecord()->mobiliario;
    $media = $mobiliario?->getFirstMedia('imagenes');
    $url = null;

    if ($media) {
        $conversion = $media->hasGeneratedConversion('thumb') ? 'thumb' : '';
        $relativePath = $media->getPathRelativeToRoot($conversion);
        $url = \Illuminate\Support\Facades\Storage::disk($media->disk)->url($relativePath);
    }
@endphp

<div class="flex h-16 w-16 items-center justify-center overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800">
    @if ($url)
        <img
            src="{{ $url }}"
            alt="{{ $mobiliario?->nombre ?? 'Mobiliario' }}"
            class="h-full w-full object-contain"
        >
    @else
        <x-filament::icon
            icon="heroicon-o-photo"
            class="h-8 w-8 text-gray-400"
        />
    @endif
</div>
