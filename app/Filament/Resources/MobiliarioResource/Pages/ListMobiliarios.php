<?php

namespace App\Filament\Resources\MobiliarioResource\Pages;

use App\Exports\MobiliariosExport;
use App\Filament\Resources\MobiliarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListMobiliarios extends ListRecords
{
    protected static string $resource = MobiliarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportarExcel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $query = $this->getTableQueryForExport();

                    return Excel::download(
                        new MobiliariosExport($query),
                        'mobiliarios-' . now()->format('Y-m-d_His') . '.xlsx',
                    );
                }),
            Actions\CreateAction::make(),
        ];
    }
}
