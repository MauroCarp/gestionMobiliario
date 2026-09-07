<?php

namespace App\Services;

/**
 * Planifica lotes de proceso externo y órdenes de compra al confirmar un presupuesto.
 *
 * Opera sobre un snapshot en memoria (sin Eloquent ni queries) para permitir
 * dry-run y tests unitarios que no tocan la base de datos.
 *
 * Vía A — proceso externo: plantilla activa y sin stock → lote (origen manual).
 * Vía B — orden de compra: insumo sin plantilla y con faltante → OC nueva o suma.
 */
class ConfirmacionPresupuestoPlanificador
{
    /**
     * @param  array{
     *     presupuesto: array{id:int, codigo:string, agencia?:string, marca?:string},
     *     omitir_oc?: bool,
     *     mobiliarios: list<array{
     *         id:int,
     *         nombre?:string,
     *         cantidad_a_fabricar:int,
     *         es_casco:bool,
     *         stock_casco?:int,
     *         demanda_activa?:int,
     *         lotes_abiertos?:int,
     *         ya_tiene_lote:bool,
     *         plantilla:?array{id:int, nombre?:string}
     *     }>,
     *     insumos: list<array{
     *         id:int,
     *         nombre?:string,
     *         codigo?:string,
     *         demanda:float,
     *         stock_disponible:float,
     *         proveedor_id:?int,
     *         precio_costo?:float,
     *         ya_tiene_lote:bool,
     *         plantilla:?array{id:int, nombre?:string}
     *     }>,
     *     ordenes_pendientes: list<array{
     *         id:int,
     *         proveedor_id:?int,
     *         estado?:string,
     *         prioridad?:string,
     *         insumo_ids: list<int>
     *     }>
     * }  $snapshot
     * @return array{lotes: list<array<string, mixed>>, ordenes_compra: list<array<string, mixed>>}
     */
    public function planificar(array $snapshot): array
    {
        $presupuesto = $snapshot['presupuesto'] ?? [];

        return [
            'lotes'          => array_merge(
                $this->planificarLotesMobiliario($snapshot['mobiliarios'] ?? [], $presupuesto),
                $this->planificarLotesInsumo($snapshot['insumos'] ?? [], $presupuesto),
            ),
            'ordenes_compra' => ($snapshot['omitir_oc'] ?? false)
                ? []
                : $this->planificarOrdenesCompra(
                    $snapshot['insumos'] ?? [],
                    $snapshot['ordenes_pendientes'] ?? [],
                ),
        ];
    }

    /**
     * @param  list<array<string, mixed>>  $mobiliarios
     * @param  array<string, mixed>        $presupuesto
     * @return list<array<string, mixed>>
     */
    private function planificarLotesMobiliario(array $mobiliarios, array $presupuesto): array
    {
        $lotes = [];

        foreach ($mobiliarios as $mobiliario) {
            $plantilla = $mobiliario['plantilla'] ?? null;

            if (! $plantilla || ($mobiliario['ya_tiene_lote'] ?? false)) {
                continue;
            }

            $esCasco = (bool) ($mobiliario['es_casco'] ?? false);
            $cantidad = $esCasco
                ? $this->cantidadCascoAFabricar($mobiliario)
                : (int) ($mobiliario['cantidad_a_fabricar'] ?? 0);

            if ($cantidad <= 0) {
                continue;
            }

            $codigo = (string) ($presupuesto['codigo'] ?? '');
            $agencia = (string) ($presupuesto['agencia'] ?? '');
            $marca = (string) ($presupuesto['marca'] ?? '');

            $lotes[] = [
                'entidad_tipo'  => 'mobiliario',
                'entidad_id'    => (int) $mobiliario['id'],
                'plantilla_id'  => (int) $plantilla['id'],
                'cantidad'      => $cantidad,
                'origen_tipo'   => 'manual',
                'es_casco'      => $esCasco,
                'nombre'        => $mobiliario['nombre'] ?? null,
                'observaciones' => $esCasco
                    ? "Cascos a fabricar al confirmar {$codigo} ({$cantidad} uds) - {$agencia} - {$marca}"
                    : "Generado al confirmar presupuesto {$codigo} - {$agencia} - {$marca}",
            ];
        }

        return $lotes;
    }

    /**
     * @param  array<string, mixed>  $mobiliario
     */
    private function cantidadCascoAFabricar(array $mobiliario): int
    {
        $demanda = (int) ($mobiliario['demanda_activa'] ?? 0);
        $cobertura = (int) ($mobiliario['stock_casco'] ?? 0)
            + (int) ($mobiliario['lotes_abiertos'] ?? 0);

        return max(0, $demanda - $cobertura);
    }

    /**
     * @param  list<array<string, mixed>>  $insumos
     * @param  array<string, mixed>        $presupuesto
     * @return list<array<string, mixed>>
     */
    private function planificarLotesInsumo(array $insumos, array $presupuesto): array
    {
        $lotes = [];
        $codigo = (string) ($presupuesto['codigo'] ?? '');

        foreach ($insumos as $insumo) {
            $plantilla = $insumo['plantilla'] ?? null;

            if (! $plantilla || ($insumo['ya_tiene_lote'] ?? false)) {
                continue;
            }

            $faltante = $this->faltanteInsumo($insumo);

            if ($faltante <= 0) {
                continue;
            }

            $lotes[] = [
                'entidad_tipo'  => 'insumo',
                'entidad_id'    => (int) $insumo['id'],
                'plantilla_id'  => (int) $plantilla['id'],
                'cantidad'      => $faltante,
                'origen_tipo'   => 'manual',
                'es_casco'      => false,
                'nombre'        => $insumo['nombre'] ?? null,
                'observaciones' => "Generado al confirmar {$codigo}",
            ];
        }

        return $lotes;
    }

    /**
     * Solo insumos sin plantilla (se compran, no se procesan afuera).
     *
     * @param  list<array<string, mixed>>  $insumos
     * @param  list<array<string, mixed>>  $ordenesPendientes
     * @return list<array<string, mixed>>
     */
    private function planificarOrdenesCompra(array $insumos, array $ordenesPendientes): array
    {
        $agregarPorOc = [];
        $nuevasPorProveedor = [];

        foreach ($insumos as $insumo) {
            if ($insumo['plantilla'] ?? null) {
                continue;
            }

            $faltante = $this->faltanteInsumo($insumo);

            if ($faltante <= 0) {
                continue;
            }

            $item = [
                'insumo_id'       => (int) $insumo['id'],
                'nombre'          => $insumo['nombre'] ?? null,
                'cantidad'        => $faltante,
                'precio_unitario' => $insumo['precio_costo'] ?? null,
                'prioridad'       => ($insumo['stock_disponible'] ?? 0) <= 0 ? 'critica' : 'alta',
            ];

            $ocExistenteId = $this->buscarOcPendienteDelInsumo(
                (int) $insumo['id'],
                $ordenesPendientes,
            );

            if ($ocExistenteId !== null) {
                $agregarPorOc[$ocExistenteId][] = $item;

                continue;
            }

            $proveedorKey = $insumo['proveedor_id'] ?? 'sin_proveedor';
            $nuevasPorProveedor[$proveedorKey][] = $item;
        }

        $ordenes = [];

        foreach ($agregarPorOc as $ocId => $items) {
            $ordenes[] = [
                'accion'          => 'agregar',
                'orden_compra_id' => (int) $ocId,
                'proveedor_id'    => $this->proveedorDeOc((int) $ocId, $ordenesPendientes),
                'prioridad'       => $this->prioridadMaxima($items),
                'items'           => $items,
            ];
        }

        foreach ($nuevasPorProveedor as $proveedorKey => $items) {
            $ordenes[] = [
                'accion'          => 'crear',
                'orden_compra_id' => null,
                'proveedor_id'    => $proveedorKey === 'sin_proveedor' ? null : (int) $proveedorKey,
                'prioridad'       => $this->prioridadMaxima($items),
                'items'           => $items,
            ];
        }

        return $ordenes;
    }

    /**
     * @param  array<string, mixed>  $insumo
     */
    private function faltanteInsumo(array $insumo): float
    {
        $demanda = (float) ($insumo['demanda'] ?? 0);
        $disponible = (float) ($insumo['stock_disponible'] ?? 0);

        return max(0, $demanda - $disponible);
    }

    /**
     * @param  list<array<string, mixed>>  $ordenesPendientes
     */
    private function buscarOcPendienteDelInsumo(int $insumoId, array $ordenesPendientes): ?int
    {
        foreach ($ordenesPendientes as $oc) {
            if (in_array($insumoId, $oc['insumo_ids'] ?? [], true)) {
                return (int) $oc['id'];
            }
        }

        return null;
    }

    /**
     * @param  list<array<string, mixed>>  $ordenesPendientes
     */
    private function proveedorDeOc(int $ocId, array $ordenesPendientes): ?int
    {
        foreach ($ordenesPendientes as $oc) {
            if ((int) $oc['id'] === $ocId) {
                $proveedorId = $oc['proveedor_id'] ?? null;

                return $proveedorId === null ? null : (int) $proveedorId;
            }
        }

        return null;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    private function prioridadMaxima(array $items): string
    {
        foreach ($items as $item) {
            if (($item['prioridad'] ?? '') === 'critica') {
                return 'critica';
            }
        }

        return 'alta';
    }
}
