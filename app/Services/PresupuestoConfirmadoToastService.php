<?php

namespace App\Services;

use App\Filament\Resources\PresupuestoResource;
use App\Models\Presupuesto;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;

class PresupuestoConfirmadoToastService
{
    public function publicar(Presupuesto $presupuesto): void
    {
        $presupuesto->loadMissing(['agencia.proyecto.marca']);

        $codigo  = $presupuesto->codigo ?: '#'.$presupuesto->id;
        $marca   = $presupuesto->agencia?->proyecto?->marca?->nombre ?? '—';
        $agencia = $presupuesto->agencia?->nombre ?? '—';

        $recipients = User::query()
            ->where('activo', true)
            ->when(auth()->id(), fn ($query) => $query->whereKeyNot(auth()->id()))
            ->get();

        if ($recipients->isEmpty()) {
            return;
        }

        $notification = Notification::make()
            ->title("El presupuesto {$codigo} de la marca {$marca}, agencia {$agencia}, ha sido confirmado.")
            ->success();

        $url = $this->urlVista($presupuesto);

        if (filled($url)) {
            $notification->actions([
                Action::make('ver')
                    ->label('Ver')
                    ->url($url)
                    ->markAsRead(),
            ]);
        }

        foreach ($recipients as $user) {
            $user->notifyNow($notification->toDatabase());
        }
    }

    private function urlVista(Presupuesto $presupuesto): ?string
    {
        try {
            return PresupuestoResource::getUrl('view', ['record' => $presupuesto], panel: 'admin');
        } catch (\Throwable) {
            return null;
        }
    }
}
