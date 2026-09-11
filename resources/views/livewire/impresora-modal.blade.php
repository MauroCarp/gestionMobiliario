<div
    x-data
    x-on:click.capture.window="
        const href = $event.target.closest('a')?.getAttribute('href') ?? '';
        if (href !== '#impresora' && ! href.endsWith('#impresora')) return;
        $event.preventDefault();
        $wire.abrir();
    "
>
    <x-filament-actions::modals />
</div>
