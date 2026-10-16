@php
    $items = $imagenes
        ->map(fn ($media) => [
            'url' => $media->getFullUrl(),
            'name' => $media->name ?: $media->file_name,
            'esVideo' => str_starts_with((string) $media->mime_type, 'video/'),
        ])
        ->values();
@endphp

@if ($items->isEmpty())
    <p class="text-sm text-gray-500 dark:text-gray-400">Sin archivos cargados en la galería.</p>
@else
    <div class="mobiliario-galeria" data-splide-root>
        <div class="mobiliario-galeria__main splide" data-splide-main aria-label="Galería">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach ($items as $item)
                        <li class="splide__slide">
                            @if ($item['esVideo'])
                                <video
                                    class="mobiliario-galeria__video"
                                    src="{{ $item['url'] }}"
                                    controls
                                    playsinline
                                    preload="metadata"
                                ></video>
                            @else
                                <img
                                    src="{{ $item['url'] }}"
                                    alt="{{ $item['name'] }}"
                                    class="mobiliario-galeria__image"
                                >
                            @endif
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
                                @if ($item['esVideo'])
                                    <span class="mobiliario-galeria__thumb-wrap">
                                        <video
                                            class="mobiliario-galeria__thumb"
                                            src="{{ $item['url'] }}"
                                            muted
                                            playsinline
                                            preload="metadata"
                                        ></video>
                                        <span class="mobiliario-galeria__thumb-play" aria-hidden="true">▶</span>
                                    </span>
                                @else
                                    <img
                                        src="{{ $item['url'] }}"
                                        alt="{{ $item['name'] }}"
                                        class="mobiliario-galeria__thumb"
                                    >
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endif
