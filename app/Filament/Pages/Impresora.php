<?php

namespace App\Filament\Pages;

class Impresora extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationGroup = 'Mobiliario';

    protected static ?string $navigationLabel = 'Impresora';

    protected static ?string $title = 'Impresora';

    protected static ?int $navigationSort = 0;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.impresora';

    public function mount(): void
    {
        $this->redirect(filament()->getUrl());
    }
}
