@if (count($marcaTabs = $this->getCachedMarcaTabs()))
    @php
        $activeMarcaTab = strval($this->activeMarcaTab);
    @endphp

    <x-filament::tabs>
        @foreach ($marcaTabs as $tabKey => $tab)
            @php
                $tabKey = strval($tabKey);
            @endphp

            <x-filament::tabs.item
                :active="$activeMarcaTab === $tabKey"
                :badge="$tab->getBadge()"
                :badge-color="$tab->getBadgeColor()"
                :badge-icon="$tab->getBadgeIcon()"
                :badge-icon-position="$tab->getBadgeIconPosition()"
                :icon="$tab->getIcon()"
                :icon-position="$tab->getIconPosition()"
                :wire:click="'$set(\'activeMarcaTab\', ' . (filled($tabKey) ? ('\'' . $tabKey . '\'') : 'null') . ')'"
                :attributes="$tab->getExtraAttributeBag()"
            >
                {{ $tab->getLabel() ?? str($tabKey)->replace(['_', '-'], ' ')->ucfirst() }}
            </x-filament::tabs.item>
        @endforeach
    </x-filament::tabs>
@endif
