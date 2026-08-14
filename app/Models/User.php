<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\CausesActivity;
use App\Support\FilamentResourceVisibility;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, CausesActivity;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'activo',
        'visible_resources',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'visible_resources' => 'array',
        ];
    }

    public function canViewFilamentResource(string $key): bool
    {
        if ($this->hasRole('Administrador')) {
            return true;
        }

        if ($this->visible_resources === null) {
            return FilamentResourceVisibility::canViewViaPolicy($this, $key);
        }

        return in_array($key, $this->visible_resources, true);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->activo && $this->hasAnyRole([
            'Administrador',
            'Ventas',
            'Producción',
            'Depósito / Stock',
            'Solo lectura',
        ]);
    }
}
