<?php

namespace Tests\Unit;

use App\Exports\MobiliariosExport;
use App\Models\AtributoMobiliario;
use App\Models\CategoriaMobiliario;
use App\Models\ComposicionTecnica;
use App\Models\Insumo;
use App\Models\Marca;
use App\Models\Mobiliario;
use App\Models\PlantillaEtapa;
use App\Models\PlantillaFlujoExterno;
use App\Models\UnidadMedida;
use Illuminate\Database\Eloquent\Builder;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class MobiliarioCostoDirectoTest extends TestCase
{
    #[Test]
    public function suma_insumos_y_etapas_de_plantilla_activa(): void
    {
        $mobiliario = $this->mobiliarioCon(
            composicion: [
                ['cantidad' => 3, 'precio_costo' => 29330],
                ['cantidad' => 1, 'precio_costo' => 218040],
                ['cantidad' => 4.5, 'precio_costo' => 9027],
                ['cantidad' => 4.02, 'precio_costo' => 9153],
            ],
            etapas: [330000],
        );

        $this->assertSame(713446.56, $mobiliario->costoDirecto());
    }

    #[Test]
    public function suma_solo_insumos_si_no_hay_plantilla(): void
    {
        $mobiliario = $this->mobiliarioCon(
            composicion: [
                ['cantidad' => 2, 'precio_costo' => 100],
                ['cantidad' => 1.5, 'precio_costo' => 40],
            ],
        );

        $this->assertSame(260.0, $mobiliario->costoDirecto());
    }

    #[Test]
    public function trata_precio_costo_nulo_como_cero(): void
    {
        $mobiliario = $this->mobiliarioCon(
            composicion: [
                ['cantidad' => 2, 'precio_costo' => null],
                ['cantidad' => 1, 'precio_costo' => 50],
            ],
            etapas: [null, 10],
        );

        $this->assertSame(60.0, $mobiliario->costoDirecto());
    }

    #[Test]
    public function excluye_plantilla_inactiva(): void
    {
        $mobiliario = $this->mobiliarioCon(
            composicion: [
                ['cantidad' => 1, 'precio_costo' => 100],
            ],
        );

        $inactiva = new PlantillaFlujoExterno(['nombre' => 'Flujo viejo', 'activo' => false]);
        $inactiva->setRelation('etapas', collect([
            new PlantillaEtapa(['ultimo_precio' => 999]),
        ]));

        $mobiliario->setRelation('plantillaFlujos', collect([$inactiva]));

        $this->assertSame(100.0, $mobiliario->costoDirecto());
        $this->assertSame([], $mobiliario->desgloseCostoDirecto()['etapas']);
        $this->assertSame(['Flujo viejo'], $mobiliario->desgloseCostoDirecto()['plantillas_inactivas']);
    }

    #[Test]
    public function desglose_lista_lineas_y_coincide_con_costo_directo(): void
    {
        $mobiliario = $this->mobiliarioCon(
            composicion: [
                ['cantidad' => 3, 'precio_costo' => 29330, 'codigo' => 'INS-0001', 'nombre' => 'Tela', 'unidad' => 'm'],
                ['cantidad' => 1, 'precio_costo' => 218040, 'codigo' => 'INS-0002', 'nombre' => 'Madera', 'unidad' => 'un'],
                ['cantidad' => 4.5, 'precio_costo' => 9027, 'codigo' => 'INS-0003', 'nombre' => 'Espuma', 'unidad' => 'kg'],
                ['cantidad' => 4.02, 'precio_costo' => 9153, 'codigo' => 'INS-0004', 'nombre' => 'Pegamento', 'unidad' => 'l', 'es_casco' => true],
            ],
            etapas: [330000],
        );

        $desglose = $mobiliario->desgloseCostoDirecto();

        $this->assertCount(4, $desglose['insumos']);
        $this->assertSame('INS-0001', $desglose['insumos'][0]['codigo']);
        $this->assertSame('Tela', $desglose['insumos'][0]['nombre']);
        $this->assertSame(3.0, $desglose['insumos'][0]['cantidad']);
        $this->assertSame('m', $desglose['insumos'][0]['unidad']);
        $this->assertSame(87990.0, $desglose['insumos'][0]['subtotal']);
        $this->assertFalse($desglose['insumos'][0]['es_casco']);
        $this->assertTrue($desglose['insumos'][3]['es_casco']);

        $this->assertCount(1, $desglose['etapas']);
        $this->assertSame('Plantilla activa', $desglose['etapas'][0]['plantilla']);
        $this->assertSame(1, $desglose['etapas'][0]['orden']);
        $this->assertSame(330000.0, $desglose['etapas'][0]['ultimo_precio']);

        $this->assertSame([], $desglose['plantillas_inactivas']);
        $this->assertSame(383446.56, $desglose['subtotal_insumos']);
        $this->assertSame(330000.0, $desglose['subtotal_etapas']);
        $this->assertSame(713446.56, $desglose['total']);
        $this->assertSame($desglose['total'], $mobiliario->costoDirecto());
    }

    #[Test]
    public function exporta_costo_directo_despues_de_precio(): void
    {
        $export = new MobiliariosExport($this->createMock(Builder::class));

        $this->assertSame('Costo Directo', $export->headings()[8]);
        $this->assertSame('Precio', $export->headings()[7]);

        $mobiliario = $this->mobiliarioCon(
            composicion: [
                ['cantidad' => 2, 'precio_costo' => 10],
            ],
            etapas: [5],
        );
        $mobiliario->fill([
            'codigo_interno' => 'CH02',
            'nombre' => 'Sillon',
            'estado' => 'activo',
            'version_actual' => 1,
            'precio' => 1500,
            'descripcion' => 'Desc',
            'observaciones' => 'Obs',
        ]);
        $mobiliario->setRelation('categoria', new CategoriaMobiliario(['nombre' => 'Sillas']));
        $mobiliario->setRelation('marcas', collect([new Marca(['nombre' => 'Chery'])]));
        $mobiliario->setRelation('atributos', collect([
            new AtributoMobiliario(['clave' => 'Color', 'valor' => 'Negro']),
        ]));

        $fila = $export->map($mobiliario);

        $this->assertSame(1500.0, $fila[7]);
        $this->assertSame(25.0, $fila[8]);
        $this->assertSame('Desc', $fila[9]);
    }

    /**
     * @param  list<array{cantidad: float, precio_costo: float|null, codigo?: ?string, nombre?: ?string, unidad?: string, es_casco?: bool}>  $composicion
     * @param  list<float|null>  $etapas
     */
    private function mobiliarioCon(array $composicion, array $etapas = []): Mobiliario
    {
        $mobiliario = new Mobiliario();

        $lineas = collect($composicion)->map(function (array $linea): ComposicionTecnica {
            $comp = new ComposicionTecnica([
                'cantidad' => $linea['cantidad'],
                'activo' => true,
                'es_componente_casco' => $linea['es_casco'] ?? false,
            ]);
            $insumo = new Insumo([
                'codigo' => $linea['codigo'] ?? null,
                'nombre' => $linea['nombre'] ?? null,
                'precio_costo' => $linea['precio_costo'],
            ]);
            $unidad = isset($linea['unidad'])
                ? new UnidadMedida(['abreviatura' => $linea['unidad'], 'nombre' => $linea['unidad']])
                : null;
            $insumo->setRelation('unidadMedida', $unidad);
            $comp->setRelation('insumo', $insumo);

            return $comp;
        });

        $mobiliario->setRelation('composicionTecnica', $lineas);

        if ($etapas === []) {
            $mobiliario->setRelation('plantillaFlujos', collect());

            return $mobiliario;
        }

        $plantilla = new PlantillaFlujoExterno(['nombre' => 'Plantilla activa', 'activo' => true]);
        $plantilla->setRelation(
            'etapas',
            collect($etapas)->values()->map(function ($precio, int $index): PlantillaEtapa {
                $etapa = new PlantillaEtapa([
                    'orden' => $index + 1,
                    'ultimo_precio' => $precio,
                ]);
                $etapa->setRelation('tipoProceso', null);
                $etapa->setRelation('tercero', null);

                return $etapa;
            }),
        );
        $mobiliario->setRelation('plantillaFlujos', collect([$plantilla]));

        return $mobiliario;
    }
}
