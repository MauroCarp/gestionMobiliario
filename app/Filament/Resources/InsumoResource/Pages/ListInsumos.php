<?php

namespace App\Filament\Resources\InsumoResource\Pages;

use App\Exports\InsumosExport;
use App\Filament\Resources\InsumoResource;
use App\Imports\InsumosStockImport;
use App\Models\CategoriaInsumo;
use App\Models\Insumo;
use App\Models\Proveedor;
use App\Services\ActualizarPreciosInsumosService;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
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

            Actions\Action::make('actualizarPrecios')
                ->label('Actualizar precios')
                ->icon('heroicon-o-arrow-trending-up')
                ->color('success')
                ->visible(fn (): bool => auth()->user()?->hasRole('Administrador') ?? false)
                ->authorize(fn (): bool => auth()->user()?->hasRole('Administrador') ?? false)
                ->requiresConfirmation()
                ->modalHeading('Confirmar actualización de precios')
                ->modalDescription('Se aplicará el porcentaje al último precio de los insumos coincidentes. La fecha de último precio pasará a hoy. Esta acción no se puede deshacer.')
                ->modalSubmitActionLabel('Sí, actualizar')
                ->modalCancelActionLabel('Cancelar')
                ->form([
                    Forms\Components\Select::make('actualizar_por')
                        ->label('Actualizar por')
                        ->options([
                            ActualizarPreciosInsumosService::CRITERIO_CATEGORIA => 'Categoría',
                            ActualizarPreciosInsumosService::CRITERIO_PROVEEDOR => 'Proveedor',
                            ActualizarPreciosInsumosService::CRITERIO_FECHA => 'Fecha último precio',
                        ])
                        ->default(ActualizarPreciosInsumosService::CRITERIO_CATEGORIA)
                        ->required()
                        ->live(),

                    Forms\Components\Select::make('categoria_id')
                        ->label('Categoría')
                        ->options(fn (): array => CategoriaInsumo::query()
                            ->where('activo', true)
                            ->orderBy('nombre')
                            ->pluck('nombre', 'id')
                            ->all())
                        ->searchable()
                        ->preload()
                        ->default(fn (): mixed => CategoriaInsumo::query()
                            ->whereRaw('LOWER(nombre) = ?', ['maderas'])
                            ->value('id'))
                        ->required(fn (Get $get): bool => $get('actualizar_por') === ActualizarPreciosInsumosService::CRITERIO_CATEGORIA)
                        ->visible(fn (Get $get): bool => $get('actualizar_por') === ActualizarPreciosInsumosService::CRITERIO_CATEGORIA),

                    Forms\Components\Select::make('proveedor_id')
                        ->label('Proveedor')
                        ->options(fn (): array => Proveedor::query()
                            ->where('activo', true)
                            ->orderBy('razon_social')
                            ->pluck('razon_social', 'id')
                            ->all())
                        ->searchable()
                        ->preload()
                        ->required(fn (Get $get): bool => $get('actualizar_por') === ActualizarPreciosInsumosService::CRITERIO_PROVEEDOR)
                        ->visible(fn (Get $get): bool => $get('actualizar_por') === ActualizarPreciosInsumosService::CRITERIO_PROVEEDOR),

                        Forms\Components\DatePicker::make('mes_anio')
                        ->label('Mes y año')
                        ->native(false)
                        ->displayFormat('m/Y')
                        ->format('Y-m')
                        ->placeholder('MM/AAAA')
                        ->suffixIcon('heroicon-o-calendar')
                        ->closeOnDateSelection()
                        ->required(fn (Get $get): bool => $get('actualizar_por') === ActualizarPreciosInsumosService::CRITERIO_FECHA)
                        ->visible(fn (Get $get): bool => $get('actualizar_por') === ActualizarPreciosInsumosService::CRITERIO_FECHA),
                    Forms\Components\TextInput::make('porcentaje')
                        ->label('Porcentaje')
                        ->numeric()
                        ->minValue(0.01)
                        ->step(0.01)
                        ->suffix('%')
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $criterio = $data['actualizar_por'];
                    $valor = match ($criterio) {
                        ActualizarPreciosInsumosService::CRITERIO_CATEGORIA => $data['categoria_id'],
                        ActualizarPreciosInsumosService::CRITERIO_PROVEEDOR => $data['proveedor_id'],
                        ActualizarPreciosInsumosService::CRITERIO_FECHA => $data['mes_anio'],
                    };

                    $cantidad = app(ActualizarPreciosInsumosService::class)
                        ->actualizar($criterio, $valor, (float) $data['porcentaje']);

                    if ($cantidad === 0) {
                        Notification::make()
                            ->title('Ningún insumo coincidente')
                            ->body('No se encontraron insumos para actualizar con el criterio seleccionado.')
                            ->warning()
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->title('Precios actualizados')
                        ->body("Se actualizó el último precio de {$cantidad} insumo(s).")
                        ->success()
                        ->send();
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
