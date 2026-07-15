@php
    $items = $imagenes
        ->map(fn ($media) => [
            'url' => $media->getFullUrl(),
            'name' => $media->name ?: $media->file_name,
        ])
        ->values();
@endphp

@if ($items->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">Sin imágenes cargadas en la galería.</p>
@else
    <div class="mobiliario-galeria" data-splide-root>
        <div class="mobiliario-galeria__main splide" data-splide-main aria-label="Galería de imágenes">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ($items as $item)
                        <li class="splide__slide">
                            <img
                                src="{{ $item['url'] }}"
                                alt="{{ $item['name'] }}"
                                class="mobiliario-galeria__image"
                            >
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @if ($items->count() > 1)
            <div class="mobiliario-galeria__thumbs splide" data-splide-thumbs aria-label="Miniaturas de la galería">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ($items as $item)
                            <li class="splide__slide">
                                <img
                                    src="{{ $item['url'] }}"
                                    alt="{{ $item['name'] }}"
                                    class="mobiliario-galeria__thumb"
                                >
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endif
