<?php

namespace App\Providers;

use App\Models\Agencia;
use App\Models\Insumo;
use App\Models\Marca;
use App\Models\Mobiliario;
use App\Models\Presupuesto;
use App\Models\User;
use App\Observers\PresupuestoObserver;
use App\Observers\MobiliarioObserver;
use App\Policies\AgenciaPolicy;
use App\Policies\InsumoPolicy;
use App\Policies\MarcaPolicy;
use App\Policies\MobiliarioPolicy;
use App\Policies\PresupuestoPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Marca::class       => MarcaPolicy::class,
        Agencia::class     => AgenciaPolicy::class,
        Mobiliario::class  => MobiliarioPolicy::class,
        Insumo::class      => InsumoPolicy::class,
        Presupuesto::class => PresupuestoPolicy::class,
        User::class        => UserPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        $this->registerPolicies();

        // Fix for MySQL < 5.7.7 or utf8mb4 key length issues
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // Super-admin gate: bypass all policies
        Gate::before(function (User $user, string $ability) {
            if ($user->hasRole('Administrador')) {
                return true;
            }
        });

        // Observers
        Presupuesto::observe(PresupuestoObserver::class);
        Mobiliario::observe(MobiliarioObserver::class);
    }
}

