<?php

namespace Tests\Unit;

use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Services\AnalisisPresupuestoService;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

class AnalisisPresupuestoServiceTest extends TestCase
{
    #[Test]
    public function calcular_demanda_omite_items_finalizados(): void
    {
        $finalizado = new PresupuestoItem([
            'insumo_id' => 1,
            'cantidad' => 10,
            'finalizado_at' => now(),
        ]);
        $pendiente = new PresupuestoItem([
            'insumo_id' => 2,
            'cantidad' => 5,
            'finalizado_at' => null,
        ]);

        $presupuesto = new Presupuesto;
        $presupuesto->setRelation('items', collect([$finalizado, $pendiente]));

        $service = new AnalisisPresupuestoService;
        $method = new ReflectionMethod($service, 'calcularDemanda');
        $method->setAccessible(true);

        $demanda = $method->invoke($service, collect([$presupuesto]));

        $this->assertSame([2 => 5.0], $demanda);
        $this->assertArrayNotHasKey(1, $demanda);
    }
}
