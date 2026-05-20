<?php

namespace App\Filament\Resources\PresupuestoResource\Pages;

use App\Filament\Resources\PresupuestoResource;
use App\Models\Presupuesto;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPresupuesto extends EditRecord
{
    protected static string $resource = PresupuestoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('PDF')
                ->icon('heroicon-o-document-text')
                ->color('danger')
                ->url(fn () => route('presupuesto.pdf', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('excel')
                ->label('Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->url(fn () => route('presupuesto.excel', $this->record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('imprimir')
                ->label('Imprimir')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn () => route('presupuesto.pdf', $this->record->id) . '?print=1')
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

            Actions\Action::make('nuevaVersion')
                ->label('Nueva Versión')
                ->icon('heroicon-o-document-duplicate')
                ->color('info')
                ->visible(fn (): bool => in_array($this->record->estado, ['aprobado', 'rechazado']))
                ->form([
                    Forms\Components\Textarea::make('motivo')
                        ->label('Motivo de la nueva versión')
                        ->rows(2)
                        ->nullable(),
                ])
                ->action(function (array $data): void {
                    $this->record->crearVersion($data['motivo'] ?? null);
                    $nuevoNumero = $this->record->version + 1;
                    $this->record->update(['version' => $nuevoNumero]);
                    $this->record->cambiarEstado('borrador', 'Nueva versión: v' . $nuevoNumero);
                    Notification::make()->success()
                        ->title("Nueva versión creada: v{$nuevoNumero}")
                        ->send();
                    $this->refreshFormData(['estado', 'version']);
                }),

            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
