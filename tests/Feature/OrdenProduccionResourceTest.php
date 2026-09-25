<?php

namespace Tests\Feature;

use App\Filament\Resources\OrdenProduccionResource;
use App\Models\User;
use App\Support\FilamentResourceVisibility;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrdenProduccionResourceTest extends TestCase
{
    #[Test]
    public function el_recurso_queda_registrado_en_operaciones(): void
    {
        $this->assertSame('Operaciones', OrdenProduccionResource::getNavigationGroup());
        $this->assertSame('Órdenes de Producción', OrdenProduccionResource::getPluralModelLabel());
        $this->assertArrayHasKey('index', OrdenProduccionResource::getPages());
        $this->assertArrayHasKey('create', OrdenProduccionResource::getPages());
        $this->assertArrayHasKey('view', OrdenProduccionResource::getPages());
        $this->assertArrayHasKey('edit', OrdenProduccionResource::getPages());
        $this->assertArrayHasKey('ordenes-produccion', FilamentResourceVisibility::options());
        $this->assertSame(
            'ordenes-produccion',
            FilamentResourceVisibility::keyForResourceClass(OrdenProduccionResource::class)
        );
    }

    #[Test]
    public function el_menu_solo_aparece_para_el_admin_de_prueba(): void
    {
        $this->assertFalse(OrdenProduccionResource::shouldRegisterNavigation());

        $this->actingAs(new User(['email' => 'otro@example.com']));
        $this->assertFalse(OrdenProduccionResource::shouldRegisterNavigation());

        $this->actingAs(new User(['email' => 'admin@gestionmobiliario.com']));
        $this->assertTrue(OrdenProduccionResource::shouldRegisterNavigation());
    }
}
