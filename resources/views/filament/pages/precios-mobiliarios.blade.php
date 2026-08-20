<x-filament-panels::page>
    <div class="flex flex-col gap-y-6">
        @include('filament.resources.presupuestos.marca-tabs')

        {{ $this->table }}

        @if ($this->activeMarcaTab !== 'sin_marca')
            @livewire('precios-sillas-table', ['activeMarcaTab' => $this->activeMarcaTab], key('sillas-' . $this->activeMarcaTab))
        @endif
    </div>
</x-filament-panels::page>
