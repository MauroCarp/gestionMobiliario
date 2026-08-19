<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        @include('filament.resources.presupuestos.marca-tabs')

        {{ $this->table }}
    </div>
</x-filament-panels::page>
