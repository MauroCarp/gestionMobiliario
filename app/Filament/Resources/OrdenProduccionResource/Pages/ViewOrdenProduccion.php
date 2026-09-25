<?php

namespace App\Filament\Resources\OrdenProduccionResource\Pages;

use App\Exceptions\OrdenProduccionException;
use App\Filament\Resources\OrdenProduccionResource;
use App\Filament\Resources\OrdenProduccionResource\RelationManagers\HistorialRelationManager;
use App\Filament\Resources\OrdenProduccionResource\RelationManagers\IngresosRelationManager;
use App\Filament\Resources\OrdenProduccionResource\RelationManagers\ItemsRelationManager;
use App\Models\OrdenProduccion;
use App\Services\OrdenProduccionService;
use App\Support\OrdenProduccionAuthorization;
use Filament\Actions;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\HtmlString;

class ViewOrdenProduccion extends ViewRecord
{
    protected static string $resource = OrdenProduccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn (): bool => $this->record->puedeEditar()),

            Actions\Action::make('iniciar')
                ->label('Iniciar')
                ->icon('heroicon-o-play')
                ->color('success')
                ->visible(fn (): bool => $this->record->puedeIniciar()
                    && OrdenProduccionAuthorization::canForRecord('start', $this->record)
                )
                ->modalHeading('Iniciar orden de producción')
                ->modalDescription('Se congelará la composición técnica y se reservará la demanda completa. Si falta material, se generarán órdenes de compra o lotes de proceso externo por el faltante.')
                ->modalContent(fn (): HtmlString => new HtmlString(
                    view('filament.orden-produccion.analisis-insumos', [
                        'analisis' => app(OrdenProduccionService::class)->analizarDisponibilidad($this->record),
                    ])->render()
                ))
                ->requiresConfirmation()
                ->modalWidth('6xl')
                ->action(function (): void {
                    try {
                        $orden = app(OrdenProduccionService::class)->iniciar($this->record);
                        $this->record = $orden;

                        Notification::make()
                            ->title('Orden iniciada. Insumos reservados.')
                            ->body($this->cuerpoDocumentosReposicion($orden))
                            ->success()
                            ->send();

                        $this->refreshFormData(['estado', 'iniciado_at', 'cancelado_at', 'completado_at']);
                    } catch (OrdenProduccionException $e) {
                        $this->notificarError($e);
                    }
                }),

            Actions\Action::make('pausar')
                ->label('Pausar')
                ->icon('heroicon-o-pause')
                ->color('warning')
                ->visible(fn (): bool => $this->record->puedePausar()
                    && OrdenProduccionAuthorization::canForRecord('pause', $this->record)
                )
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->ejecutarOperacion(
                        fn (OrdenProduccionService $service) => $service->pausar($this->record),
                        'Orden pausada.',
                    );
                }),

            Actions\Action::make('reanudar')
                ->label('Reanudar')
                ->icon('heroicon-o-play')
                ->color('info')
                ->visible(fn (): bool => $this->record->puedeReanudar()
                    && OrdenProduccionAuthorization::canForRecord('resume', $this->record)
                )
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->ejecutarOperacion(
                        fn (OrdenProduccionService $service) => $service->reanudar($this->record),
                        'Orden reanudada.',
                    );
                }),

            Actions\Action::make('cancelar')
                ->label('Cancelar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => $this->record->puedeCancelar()
                    && OrdenProduccionAuthorization::canForRecord('cancel', $this->record)
                )
                ->form([
                    Textarea::make('comentario')
                        ->label('Motivo')
                        ->rows(2),
                ])
                ->requiresConfirmation()
                ->action(function (array $data): void {
                    $this->ejecutarOperacion(
                        fn (OrdenProduccionService $service) => $service->cancelar($this->record, $data['comentario'] ?? null),
                        'Orden cancelada. Reservas pendientes liberadas.',
                    );
                }),

            Actions\DeleteAction::make()
                ->visible(fn (): bool => $this->record->puedeEditar()),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            ItemsRelationManager::class,
            IngresosRelationManager::class,
            HistorialRelationManager::class,
        ];
    }

    /**
     * @param  callable(OrdenProduccionService): mixed  $operacion
     */
    private function ejecutarOperacion(callable $operacion, string $exito): void
    {
        try {
            $operacion(app(OrdenProduccionService::class));

            Notification::make()->title($exito)->success()->send();

            $this->refreshFormData(['estado', 'iniciado_at', 'cancelado_at', 'completado_at']);
            $this->record->refresh();
        } catch (OrdenProduccionException $e) {
            $this->notificarError($e);
        }
    }

    private function notificarError(OrdenProduccionException $e): void
    {
        $cuerpo = $e->getMessage();

        if ($e->faltantes !== []) {
            $detalle = collect($e->faltantes)
                ->map(fn (array $fila): string => sprintf(
                    '%s: faltan %s',
                    $fila['nombre'] ?? $fila['insumo_id'],
                    $fila['faltante']
                ))
                ->implode(' · ');

            $cuerpo .= ' '.$detalle;
        }

        Notification::make()
            ->title('No se pudo completar la operación')
            ->body($cuerpo)
            ->danger()
            ->send();
    }

    private function cuerpoDocumentosReposicion(OrdenProduccion $orden): ?string
    {
        $partes = [];

        $codigosOc = $orden->ordenesCompra
            ->pluck('codigo')
            ->filter()
            ->values();

        $codigosLote = $orden->lotesProcesoExterno
            ->pluck('codigo')
            ->filter()
            ->values();

        if ($codigosOc->isNotEmpty()) {
            $partes[] = 'Órdenes de compra: '.$codigosOc->implode(', ');
        }

        if ($codigosLote->isNotEmpty()) {
            $partes[] = 'Lotes: '.$codigosLote->implode(', ');
        }

        return $partes === [] ? null : implode('. ', $partes).'.';
    }
}
