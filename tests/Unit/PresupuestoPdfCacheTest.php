<?php

namespace Tests\Unit;

use App\Models\Mobiliario;
use App\Models\Presupuesto;
use App\Models\PresupuestoItem;
use App\Services\PresupuestoPdfCache;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PresupuestoPdfCacheTest extends TestCase
{
    private string $directorio;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directorio = storage_path('framework/testing/pdf-cache-'.uniqid());
        File::ensureDirectoryExists($this->directorio);
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->directorio);

        parent::tearDown();
    }

    #[Test]
    public function reutiliza_el_pdf_cuando_la_huella_no_cambia(): void
    {
        $cache = new PresupuestoPdfCache($this->directorio);

        $this->assertNull($cache->rutaSiCoincide('10-comercial-con-precios', 'abc'));

        $path = $cache->guardar('10-comercial-con-precios', 'abc', '%PDF-1.4');

        $this->assertSame($path, $cache->rutaSiCoincide('10-comercial-con-precios', 'abc'));
        $this->assertNull($cache->rutaSiCoincide('10-comercial-con-precios', 'otra'));
        $this->assertSame('%PDF-1.4', file_get_contents($path));
    }

    #[Test]
    public function la_huella_cambia_si_cambia_un_dato_visible_del_mobiliario(): void
    {
        $cache = new PresupuestoPdfCache($this->directorio);
        $presupuesto = $this->presupuestoConMobiliario('Mesa');

        $original = $cache->huella($presupuesto, 'comercial', true, ['dpi' => 150]);

        $presupuesto->items->first()->mobiliario->nombre = 'Silla';
        $actualizada = $cache->huella($presupuesto, 'comercial', true, ['dpi' => 150]);

        $this->assertNotSame($original, $actualizada);
        $this->assertNotSame($original, $cache->huella($presupuesto, 'comercial', false, ['dpi' => 150]));
        $this->assertNotSame($original, $cache->huella($presupuesto, 'produccion', true, ['dpi' => 150]));
    }

    private function presupuestoConMobiliario(string $nombre): Presupuesto
    {
        $mobiliario = new Mobiliario(['nombre' => $nombre, 'codigo_interno' => 'M-1']);
        $mobiliario->id = 5;
        $mobiliario->updated_at = now();
        $mobiliario->setRelation('atributos', collect());
        $mobiliario->setRelation('media', collect());

        $item = new PresupuestoItem(['cantidad' => 1, 'orden' => 0]);
        $item->id = 9;
        $item->updated_at = now();
        $item->setRelation('mobiliario', $mobiliario);
        $item->setRelation('insumo', null);
        $item->setRelation('sector', null);

        $presupuesto = new Presupuesto(['codigo' => 'PRES-2026-0001']);
        $presupuesto->id = 10;
        $presupuesto->updated_at = now();
        $presupuesto->setRelation('items', collect([$item]));

        return $presupuesto;
    }
}
