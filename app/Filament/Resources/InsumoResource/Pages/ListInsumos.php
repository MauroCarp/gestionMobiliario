<?php

namespace App\Filament\Resources\InsumoResource\Pages;

use App\Exports\InsumosExport;
use App\Filament\Resources\InsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListInsumos extends ListRecords
{
    protected static string $resource = InsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('exportarExcel')
                ->label('Exportar Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $query = $this->getTableQueryForExport();

                    return Excel::download(
                        new InsumosExport($query),
                        'insumos-' . now()->format('Y-m-d_His') . '.xlsx',
                    );
                }),
            Actions\CreateAction::make(),
        ];
    }
}
