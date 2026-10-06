<?php

namespace Tests\Unit;

use App\Models\Insumo;
use App\Models\OrdenCompra;
use App\Models\OrdenCompraItem;
use App\Models\OrdenCompraItemRecepcion;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InsumoHistorialComprasTest extends TestCase
{
    #[Test]
    public function omite_ordenes_canceladas_y_usa_la_recepcion_si_existe(): void
    {
        $insumo = new Insumo(['nombre' => 'Tornillo']);

        $ordenRecibida = new OrdenCompra(['codigo' => 'OC-2026-0001', 'estado' => 'recibida']);
        $ordenRecibida->created_at = now()->subDays(10);

        $recepcion = new OrdenCompraItemRecepcion([
            'cantidad' => 5,
            'costo_unitario' => 12.5,
            'fecha_recepcion' => now()->subDay()->toDateString(),
        ]);
        $recepcion->fecha_recepcion = now()->subDay()->startOfDay();

        $itemRecibido = new OrdenCompraItem([
            'cantidad_solicitada' => 10,
            'precio_unitario' => 9,
        ]);
        $itemRecibido->setRelation('ordenCompra', $ordenRecibida);
        $itemRecibido->setRelation('recepciones', collect([$recepcion]));

        $ordenCancelada = new OrdenCompra(['codigo' => 'OC-2026-0002', 'estado' => 'cancelada']);
        $itemCancelado = new OrdenCompraItem(['cantidad_solicitada' => 3, 'precio_unitario' => 8]);
        $itemCancelado->setRelation('ordenCompra', $ordenCancelada);
        $itemCancelado->setRelation('recepciones', collect());

        $ordenPendiente = new OrdenCompra(['codigo' => 'OC-2026-0003', 'estado' => 'aprobada']);
        $ordenPendiente->created_at = now()->subDays(2);
        $itemPendiente = new OrdenCompraItem([
            'cantidad_solicitada' => 4,
            'precio_unitario' => 20,
        ]);
        $itemPendiente->setRelation('ordenCompra', $ordenPendiente);
        $itemPendiente->setRelation('recepciones', collect());

        $insumo->setRelation('ordenCompraItems', collect([$itemRecibido, $itemCancelado, $itemPendiente]));

        $filas = $insumo->historialCompras();

        $this->assertCount(2, $filas);
        $this->assertSame('OC-2026-0001', $filas[0]['codigo']);
        $this->assertSame(12.5, $filas[0]['costo']);
        $this->assertNotNull($filas[0]['fecha']);
        $this->assertSame('OC-2026-0003', $filas[1]['codigo']);
        $this->assertSame(20.0, $filas[1]['costo']);
        $this->assertNull($filas[1]['fecha']);
    }
}
