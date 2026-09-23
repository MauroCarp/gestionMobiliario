<?php

namespace App\Filament\Resources\InsumoResource\Pages;

use App\Exports\InsumosExport;
use App\Filament\Resources\InsumoResource;
use App\Imports\InsumosStockImport;
use App\Models\Insumo;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListInsumos extends ListRecords
{
    protected static string $resource = InsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('importarStock')
                ->label('Importar stock')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->form([
                    Forms\Components\FileUpload::make('archivo')
                        ->label('Archivo Excel')
                        ->helperText('Columnas: Codigo + Cantidad (o Stock actual si exportó desde el sistema). Vacía o 0 = stock en cero. Log en storage/app/logs/imports/insumos-stock/.')
                        ->disk('local')
                        ->directory('imports/insumos-stock')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $path = Storage::disk('local')->path($data['archivo']);

                    try {
                        $import = (new InsumosStockImport())
                            ->setArchivoOrigen(basename($data['archivo']));
                        Excel::import($import, $path);

                        Storage::disk('local')->delete($data['archivo']);

                        $rutaLog = $import->guardarLog();

                        $notification = Notification::make()
                            ->title('Importación de stock finalizada')
                            ->body($import->getResumenMensaje() . ' Log: storage/app/' . $rutaLog);

                        if ($import->actualizados > 0 && $import->omitidos === 0) {
                            $notification->success();
                        } else {
                            $notification->warning();
                        }

                        $notification->send();
                    } catch (\Throwable $e) {
                        if (isset($data['archivo'])) {
                            Storage::disk('local')->delete($data['archivo']);
                        }

                        Notification::make()
                            ->title('Error al importar stock')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

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

            Actions\Action::make('exportarSillas')
                ->label('Exportar Sillas')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->visible(fn (): bool => auth()->user()?->can('viewAny', Insumo::class) ?? false)
                ->action(function (): void {
                    $this->js('window.open(' . json_encode(route('insumos.sillas.pdf')) . ", '_blank')");
                }),

            Actions\CreateAction::make(),
        ];
    }
}
