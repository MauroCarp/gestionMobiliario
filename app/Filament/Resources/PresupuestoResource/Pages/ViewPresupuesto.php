<?php

namespace App\Filament\Resources\PresupuestoResource\Pages;

use App\Filament\Resources\PresupuestoResource;
use App\Filament\Resources\PresupuestoResource\RelationManagers\ItemsRelationManager;
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
            Actions\EditAction::make()
                ->visible(fn (): bool => $this->record->puedeEditar()),

            Actions\Action::make('pdf')
                ->label('PDF')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->url(fn () => route('presupuesto.pdf.viewer', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('produccionPdf')
                ->label('Produccion PDF')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('warning')
                ->visible(fn (): bool => in_array($this->record->estado, ['aprobado', 'confirmado', 'pagado']))
                ->url(fn () => route('presupuesto.produccion.viewer', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('produccionExcel')
                ->label('Produccion Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->estado, ['aprobado', 'confirmado', 'pagado']))
                ->url(fn () => route('presupuesto.produccion.excel', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('excel')
                ->label('Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->url(fn () => route('presupuesto.excel', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('enviarRevision')
                ->label('Enviar a Revisión')
                ->icon('heroicon-o-arrow-right-circle')
                ->color('warning')
                ->visible(fn (): bool => $this->record->puedeEnviarARevision())
                ->requiresConfirmation()
                ->action(function (): void {
                    $this->record->cambiarEstado('en_revision');
                    Notification::make()->success()->title('Enviado a revisión')->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('aprobar')
                ->label('Aprobar')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => $this->record->puedeAprobar())
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
                ->visible(fn (): bool => $this->record->estado === 'aprobado')
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
                ->visible(fn (): bool => $this->record->estado === 'confirmado')
                ->requiresConfirmation()
                ->modalHeading('Registrar pago')
                ->modalDescription('Se registrará el pago del presupuesto. El stock se descuenta al confirmar la finalización de cada ítem.')
                ->action(function (): void {
                    $this->record->cambiarEstado('pagado');
                    Notification::make()->success()->title('Presupuesto marcado como pagado.')->send();
                    $this->refreshFormData(['estado']);
                }),

            Actions\Action::make('rechazar')
                ->label('Rechazar')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (): bool => $this->record->puedeRechazar())
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
                ->visible(fn (): bool => $this->record->puedeCancelar())
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
