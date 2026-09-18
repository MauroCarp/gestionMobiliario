<?php

namespace App\Livewire;

use App\Models\User;
use Filament\Notifications\Notification;
use Livewire\Component;

class PresupuestoConfirmadoNotifier extends Component
{
    public string $cutoffAt = '';

    /** @var array<int, string> */
    public array $toastedIds = [];

    public function mount(): void
    {
        $this->cutoffAt = now()->toDateTimeString();
    }

    public function check(): void
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return;
        }

        $nuevas = $user->unreadNotifications()
            ->where('created_at', '>', $this->cutoffAt)
            ->whereNotIn('id', $this->toastedIds)
            ->orderBy('created_at')
            ->get();

        if ($nuevas->isEmpty()) {
            return;
        }

        foreach ($nuevas as $notification) {
            $this->toastedIds[] = $notification->id;

            if (($notification->data['format'] ?? null) !== 'filament') {
                continue;
            }

            Notification::fromDatabase($notification)
                ->seconds(8)
                ->send();
        }

        $this->dispatch('databaseNotificationsSent');
    }

    public function render()
    {
        return view('livewire.presupuesto-confirmado-notifier');
    }
}
