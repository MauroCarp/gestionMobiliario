<?php

namespace Tests\Unit;

use App\Models\OrdenProduccion;
use App\Models\User;
use App\Policies\OrdenProduccionPolicy;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OrdenProduccionPolicyTest extends TestCase
{
    private OrdenProduccionPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->policy = new OrdenProduccionPolicy;
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    #[Test]
    public function produccion_puede_crear_iniciar_e_ingresar(): void
    {
        $user = $this->mockUser(role: 'Producción');
        $borrador = new OrdenProduccion(['estado' => 'borrador']);
        $enProceso = new OrdenProduccion(['estado' => 'en_proceso']);

        $this->assertTrue($this->policy->viewAny($user));
        $this->assertTrue($this->policy->create($user));
        $this->assertTrue($this->policy->start($user, $borrador));
        $this->assertTrue($this->policy->registerProduction($user, $enProceso));
        $this->assertTrue($this->policy->manageItemStages($user, $enProceso));
        $this->assertFalse($this->policy->delete($user, $borrador));
    }

    #[Test]
    public function ventas_solo_consulta(): void
    {
        $user = $this->mockUser(role: 'Ventas');
        $borrador = new OrdenProduccion(['estado' => 'borrador']);
        $enProceso = new OrdenProduccion(['estado' => 'en_proceso']);

        $this->assertTrue($this->policy->viewAny($user));
        $this->assertTrue($this->policy->view($user, $borrador));
        $this->assertFalse($this->policy->create($user));
        $this->assertFalse($this->policy->start($user, $borrador));
        $this->assertFalse($this->policy->registerProduction($user, $enProceso));
        $this->assertFalse($this->policy->manageItemStages($user, $enProceso));
    }

    #[Test]
    public function no_permite_operar_fuera_del_estado(): void
    {
        $user = $this->mockUser(role: 'Producción');
        $completada = new OrdenProduccion(['estado' => 'completada']);

        $this->assertFalse($this->policy->start($user, $completada));
        $this->assertFalse($this->policy->registerProduction($user, $completada));
        $this->assertFalse($this->policy->cancel($user, $completada));
        $this->assertFalse($this->policy->update($user, $completada));
    }

    private function mockUser(string $role = 'Ventas', bool $isAdmin = false): User
    {
        $user = Mockery::mock(User::class)->makePartial();

        $user->shouldReceive('hasRole')
            ->with('Administrador')
            ->andReturn($isAdmin);

        $user->shouldReceive('hasAnyRole')
            ->andReturnUsing(function (mixed $roles) use ($role, $isAdmin): bool {
                $roles = is_array($roles) ? $roles : [$roles];

                return $isAdmin || in_array($role, $roles, true);
            });

        return $user;
    }
}
