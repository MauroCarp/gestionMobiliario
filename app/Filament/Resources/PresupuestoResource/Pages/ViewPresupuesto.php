<?php

namespace App\Filament\Resources\PresupuestoResource\Pages;

use App\Filament\Forms\PresupuestoEntregaParcialForm;
use App\Filament\Resources\PresupuestoResource;
use App\Filament\Resources\PresupuestoResource\RelationManagers\ItemsRelationManager;
use App\Services\PresupuestoEntregaService;
use App\Support\PresupuestoAuthorization;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewPresupuesto extends ViewRecord
{
    protected static string $resource = PresupuestoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
                // ->visible(fn (): bool => $this->record->puedeEditar()),

            PresupuestoResource::clonarPageAction(fn () => $this->record),

            PresupuestoResource::pdfPageAction(fn () => $this->record),

            PresupuestoResource::layoutPageAction(fn () => $this->record),

            Actions\Action::make('produccionPdf')
                ->label('Produccion PDF')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('warning')
                ->url(fn () => route('presupuesto.produccion.viewer', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('produccionExcel')
                ->label('Produccion Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->url(fn () => route('presupuesto.produccion.excel', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('excel')
                ->label('Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('export', $this->record))
                ->authorize('export')
                ->url(fn () => route('presupuesto.excel', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('enviarRevision')
                ->label('Enviar a Revisión')
                ->icon('heroicon-o-arrow-right-circle')
                ->color('warning')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('changeState', $this->record) && $this->record->puedeEnviarARevision())
                ->authorize('changeState')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->cambiarEstado('en_revision');
                    Notification::make()->success()->title('Enviado a revisión')->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('enviarACliente')
                ->label('Enviar a Cliente')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('changeState', $this->record) && $this->record->puedeEnviarACliente())
                ->authorize('changeState')
                ->requiresConfirmation()
                ->modalHeading('Enviar presupuesto a cliente')
                ->modalDescription('Se van a congelar los precios de los mobiliarios e insumos/sillas del presupuesto. Dejarán de seguir el precio de lista futuro. Se abrirá el PDF comercial con precios.')
                ->action(function (): void {
                    $this->record->cambiarEstado('enviado_a_cliente');
                    $pdfUrl = PresupuestoResource::abrirPdfComercialConPrecios($this->record, $this);
                    PresupuestoResource::notificarEnviadoACliente($pdfUrl);
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('aprobar')
                ->label('Aprobar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('changeState', $this->record) && $this->record->puedeAprobar())
                ->authorize('changeState')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->cambiarEstado('aprobado');
                    Notification::make()->success()->title('Presupuesto aprobado')->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('confirmar')
                ->label('Confirmar')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('changeState', $this->record) && $this->record->estado === 'aprobado')
                ->authorize('changeState')
                ->requiresConfirmation()
                ->modalHeading('Confirmar presupuesto')
                ->modalDescription('Se reservará el stock de insumos y el presupuesto pasará a estado Confirmado.')
                ->action(function (): void {
                    $this->record->cambiarEstado('confirmado');
                    Notification::make()->success()->title('Presupuesto confirmado. Stock reservado.')->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('marcarPagado')
                ->label('Marcar Pagado')
                ->icon('heroicon-o-banknotes')
                ->color('warning')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('changeState', $this->record) && $this->record->estado === 'confirmado')
                ->authorize('changeState')
                ->requiresConfirmation()
                ->modalHeading('Registrar pago')
                ->modalDescription('Se registrará el pago del presupuesto. El stock se descuenta al confirmar la finalización de cada ítem.')
                ->action(function (): void {
                    $this->record->cambiarEstado('pagado');
                    Notification::make()->success()->title('Presupuesto marcado como pagado.')->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('entregarCompleto')
                ->label('Entregar completo')
                ->icon('heroicon-o-truck')
                ->color('success')
                ->visible(fn (): bool =>
                    PresupuestoAuthorization::canForRecord('registerDelivery', $this->record)
                    && $this->record->puedeRegistrarEntrega()
                    && $this->record->tieneItemsPendientesEntrega()
                )
                ->authorize('registerDelivery')
                ->requiresConfirmation()
                ->modalHeading('Entregar presupuesto completo')
                ->modalDescription('Se entregará el saldo pendiente de todos los ítems disponibles y el presupuesto pasará a estado Entregado si no queda saldo.')
                ->action(function (): void {
                    try {
                        app(PresupuestoEntregaService::class)->marcarEntregaCompleta($this->record);

                        Notification::make()
                            ->success()
                            ->title('Presupuesto entregado completamente')
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('No se pudo registrar la entrega')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }

                    $this->record->refresh();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('entregarParcial')
                ->label('Entregar parcial')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('warning')
                ->visible(fn (): bool =>
                    PresupuestoAuthorization::canForRecord('registerDelivery', $this->record)
                    && $this->record->puedeRegistrarEntrega()
                    && $this->record->tieneItemsPendientesEntrega()
                )
                ->authorize('registerDelivery')
                ->fillForm(fn (): array => PresupuestoEntregaParcialForm::state($this->record))
                ->form(PresupuestoEntregaParcialForm::schema())
                ->action(function (array $data): void {
                    try {
                        app(PresupuestoEntregaService::class)->marcarEntregaParcial(
                            $this->record,
                            PresupuestoEntregaParcialForm::cantidades($data),
                        );

                        $this->record->refresh();

                        Notification::make()
                            ->success()
                            ->title(match ($this->record->estado) {
                                'entregado'         => 'Presupuesto entregado completamente',
                                'entregado_parcial' => 'Entrega parcial registrada',
                                default             => 'Entrega actualizada',
                            })
                            ->send();
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('No se pudo registrar la entrega')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }

                    $this->record->refresh();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('rechazar')
                ->label('Rechazar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('changeState', $this->record) && $this->record->puedeRechazar())
                ->authorize('changeState')
                ->form([
                    Forms\Components\Textarea::make('comentario')
                        ->label('Motivo del rechazo')
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data): void {
                    $this->record->cambiarEstado('rechazado', $data['comentario']);
                    Notification::make()->warning()->title('Presupuesto rechazado')->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('cancelar')
                ->label('Cancelar')
                ->icon('heroicon-o-archive-box-x-mark')
                ->color('gray')
                ->visible(fn (): bool => PresupuestoAuthorization::canForRecord('changeState', $this->record) && $this->record->puedeCancelar())
                ->authorize('changeState')
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->cambiarEstado('cancelado');
                    Notification::make()->info()->title('Presupuesto cancelado')->send();
                    $this->refreshFormData(['estado']);
                }),
        ];
    }

    public function getRelationManagers(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }
}
