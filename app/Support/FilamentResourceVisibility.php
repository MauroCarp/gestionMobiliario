<?php

namespace App\Support;

use App\Filament\Pages\AnalisisDemanda;
use App\Filament\Pages\Auditoria;
use App\Filament\Pages\Impresora;
use App\Filament\Pages\PreciosMobiliarios;
use App\Filament\Resources\AgenciaResource;
use App\Filament\Resources\CascoSillaResource;
use App\Filament\Resources\CategoriaMobiliarioResource;
use App\Filament\Resources\InsumoResource;
use App\Filament\Resources\LoteProcesoExternoResource;
use App\Filament\Resources\MarcaResource;
use App\Filament\Resources\MobiliarioResource;
use App\Filament\Resources\OrdenCompraResource;
use App\Filament\Resources\PlantillaFlujoExternoResource;
use App\Filament\Resources\PresupuestoResource;
use App\Filament\Resources\ProveedorResource;
use App\Filament\Resources\ProyectoResource;
use App\Filament\Resources\TerceroResource;
use App\Filament\Resources\TipoProcesoExternoResource;
use App\Filament\Resources\UnidadMedidaResource;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class FilamentResourceVisibility
{
    /**
     * @return array<string, array{type: string, class: class-string, label: string, model?: class-string}>
     */
    public static function catalog(): array
    {
        return array_merge(static::resourceCatalog(), static::pageCatalog());
    }

    /**
     * @return array<string, array{type: string, class: class-string, label: string, model: class-string}>
     */
    protected static function resourceCatalog(): array
    {
        return [
            'agencias' => [
                'type' => 'resource',
                'class' => AgenciaResource::class,
                'model' => AgenciaResource::getModel(),
                'label' => AgenciaResource::getPluralModelLabel(),
            ],
            'proyectos' => [
                'type' => 'resource',
                'class' => ProyectoResource::class,
                'model' => ProyectoResource::getModel(),
                'label' => ProyectoResource::getPluralModelLabel(),
            ],
            'presupuestos' => [
                'type' => 'resource',
                'class' => PresupuestoResource::class,
                'model' => PresupuestoResource::getModel(),
                'label' => PresupuestoResource::getPluralModelLabel(),
            ],
            'ordenes-compra' => [
                'type' => 'resource',
                'class' => OrdenCompraResource::class,
                'model' => OrdenCompraResource::getModel(),
                'label' => OrdenCompraResource::getPluralModelLabel(),
            ],
            'mobiliarios' => [
                'type' => 'resource',
                'class' => MobiliarioResource::class,
                'model' => MobiliarioResource::getModel(),
                'label' => MobiliarioResource::getPluralModelLabel(),
            ],
            'insumos' => [
                'type' => 'resource',
                'class' => InsumoResource::class,
                'model' => InsumoResource::getModel(),
                'label' => InsumoResource::getPluralModelLabel(),
            ],
            'cascos-sillas' => [
                'type' => 'resource',
                'class' => CascoSillaResource::class,
                'model' => CascoSillaResource::getModel(),
                'label' => CascoSillaResource::getPluralModelLabel(),
            ],
            'categorias-mobiliario' => [
                'type' => 'resource',
                'class' => CategoriaMobiliarioResource::class,
                'model' => CategoriaMobiliarioResource::getModel(),
                'label' => CategoriaMobiliarioResource::getPluralModelLabel(),
            ],
            'unidades-medida' => [
                'type' => 'resource',
                'class' => UnidadMedidaResource::class,
                'model' => UnidadMedidaResource::getModel(),
                'label' => UnidadMedidaResource::getPluralModelLabel(),
            ],
            'marcas' => [
                'type' => 'resource',
                'class' => MarcaResource::class,
                'model' => MarcaResource::getModel(),
                'label' => MarcaResource::getPluralModelLabel(),
            ],
            'proveedores' => [
                'type' => 'resource',
                'class' => ProveedorResource::class,
                'model' => ProveedorResource::getModel(),
                'label' => ProveedorResource::getPluralModelLabel(),
            ],
            'terceros' => [
                'type' => 'resource',
                'class' => TerceroResource::class,
                'model' => TerceroResource::getModel(),
                'label' => TerceroResource::getPluralModelLabel(),
            ],
            'plantillas-flujo' => [
                'type' => 'resource',
                'class' => PlantillaFlujoExternoResource::class,
                'model' => PlantillaFlujoExternoResource::getModel(),
                'label' => PlantillaFlujoExternoResource::getPluralModelLabel(),
            ],
            'lotes-proceso' => [
                'type' => 'resource',
                'class' => LoteProcesoExternoResource::class,
                'model' => LoteProcesoExternoResource::getModel(),
                'label' => LoteProcesoExternoResource::getPluralModelLabel(),
            ],
            'tipos-proceso' => [
                'type' => 'resource',
                'class' => TipoProcesoExternoResource::class,
                'model' => TipoProcesoExternoResource::getModel(),
                'label' => TipoProcesoExternoResource::getPluralModelLabel(),
            ],
            'usuarios' => [
                'type' => 'resource',
                'class' => UserResource::class,
                'model' => UserResource::getModel(),
                'label' => UserResource::getPluralModelLabel(),
            ],
        ];
    }

    /**
     * @return array<string, array{type: string, class: class-string, label: string}>
     */
    protected static function pageCatalog(): array
    {
        return [
            'lista-precios' => [
                'type' => 'page',
                'class' => PreciosMobiliarios::class,
                'label' => PreciosMobiliarios::getNavigationLabel(),
            ],
            'analisis-demanda' => [
                'type' => 'page',
                'class' => AnalisisDemanda::class,
                'label' => AnalisisDemanda::getNavigationLabel(),
            ],
            'auditoria' => [
                'type' => 'page',
                'class' => Auditoria::class,
                'label' => Auditoria::getNavigationLabel(),
            ],
            'impresora' => [
                'type' => 'page',
                'class' => Impresora::class,
                'label' => Impresora::getNavigationLabel(),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(static::catalog())
            ->mapWithKeys(fn (array $entry, string $key): array => [$key => $entry['label']])
            ->all();
    }

    public static function keyForResourceClass(string $resourceClass): ?string
    {
        foreach (static::resourceCatalog() as $key => $entry) {
            if ($entry['class'] === $resourceClass) {
                return $key;
            }
        }

        return null;
    }

    public static function keyForPageClass(string $pageClass): ?string
    {
        foreach (static::pageCatalog() as $key => $entry) {
            if ($entry['class'] === $pageClass) {
                return $key;
            }
        }

        return null;
    }

    public static function canViewViaPolicy(User $user, string $key): bool
    {
        $entry = static::catalog()[$key] ?? null;

        if ($entry === null) {
            return false;
        }

        if ($entry['type'] === 'page') {
            return $user->canAccessPanel(filament()->getCurrentPanel() ?? filament()->getDefaultPanel());
        }

        return Gate::forUser($user)->check('viewAny', $entry['model']);
    }

    /**
     * @return list<string>
     */
    public static function resolveDefaultVisibleResourcesForUser(User $user): array
    {
        $visible = [];

        foreach (static::catalog() as $key => $entry) {
            if (static::canViewViaPolicy($user, $key)) {
                $visible[] = $key;
            }
        }

        return $visible;
    }
}
