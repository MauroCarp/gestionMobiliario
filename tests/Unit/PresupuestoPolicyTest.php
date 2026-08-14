<?php

namespace Tests\Unit;

use App\Models\Presupuesto;
use App\Models\User;
use App\Policies\PresupuestoPolicy;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PresupuestoPolicyTest extends TestCase
{
    private PresupuestoPolicy $policy;

    private Presupuesto $presupuesto;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new PresupuestoPolicy();
        $this->presupuesto = new Presupuesto(['estado' => 'borrador']);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test]
    public function administrador_puede_ejecutar_acciones_administrativas(): void
    {
        $admin = $this->mockUser(isAdmin: true);

        $this->assertTrue($this->policy->changeState($admin, $this->presupuesto));
        $this->assertTrue($this->policy->export($admin, $this->presupuesto));
        $this->assertTrue($this->policy->manageItemStages($admin, $this->presupuesto));
        $this->assertTrue($this->policy->registerDelivery($admin, $this->presupuesto));
        $this->assertTrue($this->policy->clonePresupuesto($admin, $this->presupuesto));
    }

    #[Test]
    public function ventas_no_puede_ejecutar_acciones_administrativas(): void
    {
        $ventas = $this->mockUser(role: 'Ventas');

        $this->assertFalse($this->policy->changeState($ventas, $this->presupuesto));
        $this->assertFalse($this->policy->export($ventas, $this->presupuesto));
        $this->assertFalse($this->policy->manageItemStages($ventas, $this->presupuesto));
        $this->assertFalse($this->policy->registerDelivery($ventas, $this->presupuesto));
        $this->assertFalse($this->policy->clonePresupuesto($ventas, $this->presupuesto));
    }

    #[Test]
    public function produccion_puede_ver_pero_no_exportar(): void
    {
        $produccion = $this->mockUser(role: 'Producción');

        $this->assertTrue($this->policy->viewAny($produccion));
        $this->assertTrue($this->policy->view($produccion, $this->presupuesto));
        $this->assertFalse($this->policy->export($produccion, $this->presupuesto));
    }

    #[Test]
    public function gate_bloquea_exportacion_para_no_administradores(): void
    {
        Gate::policy(Presupuesto::class, PresupuestoPolicy::class);

        $admin = $this->mockUser(isAdmin: true);
        $ventas = $this->mockUser(role: 'Ventas');

        Gate::forUser($admin)->authorize('export', $this->presupuesto);

        $this->expectException(AuthorizationException::class);

        Gate::forUser($ventas)->authorize('export', $this->presupuesto);
    }

    private function mockUser(string $role = 'Ventas', bool $isAdmin = false): User
    {
        $user = Mockery::mock(User::class)->makePartial();

        $user->shouldReceive('hasRole')
            ->with('Administrador')
            ->andReturn($isAdmin);

        $user->shouldReceive('hasAnyRole')
            ->andReturnUsing(fn (array $roles): bool => $isAdmin || in_array($role, $roles, true));

        return $user;
    }
}
