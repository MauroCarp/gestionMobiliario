<?php

namespace App\Filament\Resources\LoteProcesoExternoResource\Pages;

use App\Exports\LotesProcesoExternoExport;
use App\Filament\Resources\LoteProcesoExternoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListLotesProcesoExterno extends ListRecords
{
    protected static string $resource = LoteProcesoExternoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportarExcel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $query = $this->getTableQueryForExport();

                    return Excel::download(
                        new LotesProcesoExternoExport($query),
                        'lotes-proceso-' . now()->format('Y-m-d_His') . '.xlsx',
                    );
                }),
            Actions\CreateAction::make(),
        ];
    }
}
