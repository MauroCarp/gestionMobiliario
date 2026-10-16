@php
    if (! isset($imagenes)) {
        $imagenes = is_callable($getState ?? null) ? collect($getState() ?? []) : collect();
    }
@endphp

@include('filament.mobiliarios.galeria-carousel', ['imagenes' => $imagenes])
