<?php

namespace App\Services;

use App\Exceptions\OrdenProduccionException;
use App\Models\ComposicionTecnica;
use App\Models\Insumo;
use App\Models\LoteProcesoExterno;
use App\Models\Mobiliario;
use App\Models\OrdenCompra;
use App\Models\OrdenProduccion;
use App\Models\OrdenProduccionHistorial;
use App\Models\OrdenProduccionItem;
use App\Models\OrdenProduccionItemEtapa;
use App\Models\OrdenProduccionItemInsumo;
use App\Models\OrdenProduccionMovimiento;
use App\Models\PlantillaFlujoExterno;
use App\Models\ReservaStock;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class OrdenProduccionService
{
    /**
     * @return array{
     *     puede_iniciar: bool,
     *     insumos: list<array<string, mixed>>,
     *     faltantes: list<array<string, mixed>>,
     *     generara_reposicion: bool
     * }
     */
    public function analizarDisponibilidad(OrdenProduccion $orden): array
    {
        $demanda = $this->calcularDemandaViva($orden);
        $insumos = $this->construirAnalisisInsumos($demanda);
        $faltantes = array_values(array_filter(
            $insumos,
            fn (array $fila): bool => $fila['faltante'] > 0,
        ));

        return [
            'puede_iniciar' => $orden->items()->exists(),
            'insumos' => $insumos,
            'faltantes' => $faltantes,
            'generara_reposicion' => $faltantes !== [],
        ];
    }

    public function iniciar(OrdenProduccion $orden): OrdenProduccion
    {
        return DB::transaction(function () use ($orden): OrdenProduccion {
            /** @var OrdenProduccion $orden */
            $orden = OrdenProduccion::query()->lockForUpdate()->findOrFail($orden->id);

            if (! $orden->puedeIniciar()) {
                throw new OrdenProduccionException('Solo se puede iniciar una orden en borrador.');
            }

            $orden->load(['items.mobiliario.composicionTecnica', 'items.mobiliario.marcas']);

            if ($orden->items->isEmpty()) {
                throw new OrdenProduccionException('La orden no tiene líneas para fabricar.');
            }

            $this->validarLineas($orden);
            $this->congelarComposiciones($orden);

            $demanda = $this->calcularDemandaSnapshot($orden->fresh(['items.insumos']));
            $analisis = $this->construirAnalisisInsumos($demanda, bloquear: true);
            $plan = $this->planificador()->planificar(
                $this->construirSnapshotPlan($orden, $analisis)
            );

            $this->reservarDemanda($orden, $demanda);
            $persistido = $this->persistirPlanReposicion($orden, $plan);

            foreach ($orden->items as $item) {
                $this->crearEtapasParaItem($item);
            }

            $comentario = $this->comentarioInicio($persistido);
            $this->cambiarEstado($orden, 'en_proceso', $comentario);

            $orden->update([
                'fecha_inicio' => $orden->fecha_inicio ?: now()->toDateString(),
                'iniciado_at' => now(),
                'iniciado_por' => auth()->id(),
            ]);

            return $orden->fresh([
                'items.insumos',
                'items.etapas',
                'reservasStock',
                'ordenesCompra',
                'lotesProcesoExterno',
            ]);
        });
    }

    public function pausar(OrdenProduccion $orden): OrdenProduccion
    {
        return DB::transaction(function () use ($orden): OrdenProduccion {
            $orden = OrdenProduccion::query()->lockForUpdate()->findOrFail($orden->id);

            if (! $orden->puedePausar()) {
                throw new OrdenProduccionException('Solo se puede pausar una orden en proceso.');
            }

            $this->cambiarEstado($orden, 'pausada', 'Orden pausada.');

            return $orden->fresh();
        });
    }

    public function reanudar(OrdenProduccion $orden): OrdenProduccion
    {
        return DB::transaction(function () use ($orden): OrdenProduccion {
            $orden = OrdenProduccion::query()->lockForUpdate()->findOrFail($orden->id);

            if (! $orden->puedeReanudar()) {
                throw new OrdenProduccionException('Solo se puede reanudar una orden pausada.');
            }

            $this->cambiarEstado($orden, 'en_proceso', 'Orden reanudada.');

            return $orden->fresh();
        });
    }

    public function cancelar(OrdenProduccion $orden, ?string $comentario = null): OrdenProduccion
    {
        return DB::transaction(function () use ($orden, $comentario): OrdenProduccion {
            $orden = OrdenProduccion::query()->lockForUpdate()->findOrFail($orden->id);

            if (! $orden->puedeCancelar()) {
                throw new OrdenProduccionException('La orden no se puede cancelar en su estado actual.');
            }

            $orden->reservasStock()
                ->where('estado', 'activa')
                ->lockForUpdate()
                ->get()
                ->each(fn (ReservaStock $reserva) => $reserva->update(['estado' => 'liberada']));

            LoteProcesoExterno::query()
                ->where('origen_tipo', 'orden_produccion')
                ->where('origen_id', $orden->id)
                ->whereIn('estado', StockCascoService::LOTES_ABIERTOS)
                ->update(['estado' => 'cancelado']);

            $this->cambiarEstado($orden, 'cancelada', $comentario ?: 'Orden cancelada. Reservas pendientes liberadas.');

            $orden->update([
                'cancelado_at' => now(),
                'cancelado_por' => auth()->id(),
            ]);

            return $orden->fresh(['reservasStock']);
        });
    }

    public function registrarIngreso(
        OrdenProduccionItem $item,
        int $cantidad,
        ?string $observaciones = null,
    ): OrdenProduccionMovimiento {
        if ($cantidad <= 0) {
            throw new InvalidArgumentException('La cantidad a ingresar debe ser mayor a cero.');
        }

        return DB::transaction(function () use ($item, $cantidad, $observaciones): OrdenProduccionMovimiento {
            $orden = OrdenProduccion::query()->lockForUpdate()->findOrFail($item->orden_produccion_id);

            if (! $orden->puedeRegistrarIngreso()) {
                throw new OrdenProduccionException('Solo se puede registrar producción en una orden en proceso.');
            }

            /** @var OrdenProduccionItem $item */
            $item = OrdenProduccionItem::query()->lockForUpdate()->findOrFail($item->id);
            $item->setRelation('ordenProduccion', $orden);
            $item->load('insumos');

            if ($cantidad > $item->cantidadPendiente()) {
                throw new OrdenProduccionException(
                    "La cantidad supera el pendiente ({$item->cantidadPendiente()})."
                );
            }

            $this->consumirInsumosProporcional($orden, $item, $cantidad);

            $movimiento = OrdenProduccionMovimiento::create([
                'orden_produccion_item_id' => $item->id,
                'cantidad' => $cantidad,
                'registrado_at' => now(),
                'registrado_por' => auth()->id(),
                'observaciones' => $observaciones,
            ]);

            $nuevaIngresada = (int) $item->cantidad_ingresada + $cantidad;
            $completada = $nuevaIngresada >= (int) $item->cantidad;

            $item->update([
                'cantidad_ingresada' => $nuevaIngresada,
                'estado' => $completada ? 'completada' : 'en_proceso',
                'finalizado_at' => $completada ? now() : null,
            ]);

            if ($this->todasLasLineasCompletas($orden)) {
                $this->cambiarEstado($orden, 'completada', 'Producción completa.');
                $orden->update(['completado_at' => now()]);
            }

            return $movimiento->fresh(['item']);
        });
    }

    /**
     * @param  list<array<string, mixed>>  $etapas
     */
    public function actualizarEtapas(OrdenProduccionItem $item, array $etapas): void
    {
        $item->loadMissing('ordenProduccion');

        if (! $item->ordenProduccion?->puedeGestionarEtapas()) {
            throw new OrdenProduccionException('Las etapas solo se pueden actualizar con la orden en proceso o pausada.');
        }

        DB::transaction(function () use ($item, $etapas): void {
            $this->crearEtapasParaItem($item);

            foreach ($etapas as $etapaData) {
                if (empty($etapaData['id'])) {
                    continue;
                }

                $etapa = $item->etapas()->find($etapaData['id']);

                if (! $etapa) {
                    continue;
                }

                $estado = $etapaData['estado'] ?? $etapa->estado;
                $fechaInicio = $etapaData['fecha_inicio'] ?? null;
                $fechaFin = $etapaData['fecha_fin'] ?? null;
                $userId = auth()->id();

                if ($this->esEtapaInicio($etapa)) {
                    $estado = in_array($estado, ['pendiente', 'completado'], true) ? $estado : 'pendiente';

                    $etapa->update([
                        'estado' => $estado,
                        'fecha_inicio' => $estado === 'completado' ? ($fechaInicio ?: now()->toDateString()) : null,
                        'fecha_fin' => null,
                        'iniciado_por' => null,
                        'completado_por' => null,
                        'observaciones' => null,
                    ]);

                    continue;
                }

                $data = [
                    'estado' => array_key_exists($estado, OrdenProduccionItemEtapa::ESTADOS) ? $estado : $etapa->estado,
                    'fecha_inicio' => $fechaInicio ?: null,
                    'fecha_fin' => $fechaFin ?: null,
                    'observaciones' => $etapaData['observaciones'] ?? null,
                ];

                if ($data['estado'] === 'en_proceso') {
                    $data['fecha_inicio'] = $data['fecha_inicio'] ?: now()->toDateString();
                    $data['iniciado_por'] = $etapa->iniciado_por ?: $userId;
                }

                if ($data['estado'] === 'completado') {
                    $data['fecha_inicio'] = $data['fecha_inicio'] ?: now()->toDateString();
                    $data['fecha_fin'] = $data['fecha_fin'] ?: now()->toDateString();
                    $data['iniciado_por'] = $etapa->iniciado_por ?: $userId;
                    $data['completado_por'] = $etapa->completado_por ?: $userId;
                }

                $etapa->update($data);
            }
        });
    }

    public function crearEtapasParaItem(OrdenProduccionItem $item): void
    {
        foreach (PresupuestoItemProduccionService::ETAPAS_PREDETERMINADAS as $orden => $nombre) {
            OrdenProduccionItemEtapa::firstOrCreate(
                [
                    'orden_produccion_item_id' => $item->id,
                    'orden' => $orden,
                ],
                [
                    'nombre' => $nombre,
                    'estado' => 'pendiente',
                ],
            );
        }
    }

    public function esEtapaInicio(OrdenProduccionItemEtapa $etapa): bool
    {
        return $etapa->orden === 1 || $etapa->nombre === PresupuestoItemProduccionService::ETAPA_INICIO;
    }

    /**
     * @return array<int, float>
     */
    public function calcularDemandaViva(OrdenProduccion $orden): array
    {
        $orden->loadMissing('items.mobiliario.composicionTecnica');

        $demanda = [];

        foreach ($orden->items as $item) {
            foreach ($this->composicionSeleccionada($item) as $comp) {
                $demanda[$comp->insumo_id] = ($demanda[$comp->insumo_id] ?? 0)
                    + ((float) $comp->cantidad * (int) $item->cantidad);
            }
        }

        return $demanda;
    }

    /**
     * @return array<int, float>
     */
    public function calcularDemandaSnapshot(OrdenProduccion $orden): array
    {
        $orden->loadMissing('items.insumos');

        $demanda = [];

        foreach ($orden->items as $item) {
            foreach ($item->insumos as $snapshot) {
                $demanda[$snapshot->insumo_id] = ($demanda[$snapshot->insumo_id] ?? 0)
                    + (float) $snapshot->cantidad_total;
            }
        }

        return $demanda;
    }

    private function validarLineas(OrdenProduccion $orden): void
    {
        foreach ($orden->items as $item) {
            if ((int) $item->cantidad <= 0) {
                throw new OrdenProduccionException('Cada línea debe tener una cantidad mayor a cero.');
            }

            $mobiliario = $item->mobiliario;

            if (! $mobiliario) {
                throw new OrdenProduccionException('Hay líneas sin mobiliario válido.');
            }

            $pertenece = $mobiliario->marcas
                ->contains(fn ($marca): bool => (int) $marca->id === (int) $item->marca_id);

            if (! $pertenece) {
                throw new OrdenProduccionException(
                    "El mobiliario {$mobiliario->nombre} no pertenece a la marca seleccionada."
                );
            }

            $this->validarSeleccionInsumos($item, $mobiliario);
        }
    }

    private function congelarComposiciones(OrdenProduccion $orden): void
    {
        foreach ($orden->items as $item) {
            $mobiliario = $item->mobiliario;
            $version = (int) ($mobiliario->version_actual ?? 1);

            $item->update(['version_composicion' => $version]);

            foreach ($this->composicionSeleccionada($item) as $comp) {
                OrdenProduccionItemInsumo::updateOrCreate(
                    [
                        'orden_produccion_item_id' => $item->id,
                        'insumo_id' => $comp->insumo_id,
                    ],
                    [
                        'cantidad_unitaria' => (float) $comp->cantidad,
                        'cantidad_total' => (float) $comp->cantidad * (int) $item->cantidad,
                        'cantidad_consumida' => 0,
                    ],
                );
            }
        }
    }

    /**
     * @return iterable<ComposicionTecnica>
     */
    private function composicionFabricable(?Mobiliario $mobiliario): iterable
    {
        if (! $mobiliario) {
            return [];
        }

        return $mobiliario->composicionFabricable();
    }

    /**
     * @return iterable<ComposicionTecnica>
     */
    private function composicionSeleccionada(OrdenProduccionItem $item): iterable
    {
        $composicion = collect($this->composicionFabricable($item->mobiliario));
        $seleccionados = $item->idsInsumosSeleccionados();

        if ($seleccionados === null) {
            return $composicion;
        }

        return $composicion->filter(
            fn (ComposicionTecnica $comp): bool => in_array((int) $comp->insumo_id, $seleccionados, true)
        );
    }

    private function validarSeleccionInsumos(OrdenProduccionItem $item, Mobiliario $mobiliario): void
    {
        $fabricables = collect($this->composicionFabricable($mobiliario));
        $idsFabricables = $fabricables->pluck('insumo_id')->map(fn ($id): int => (int) $id)->all();
        $seleccionados = $item->idsInsumosSeleccionados();

        if ($fabricables->isEmpty()) {
            return;
        }

        if ($seleccionados === []) {
            throw new OrdenProduccionException(
                "La línea de {$mobiliario->nombre} debe tener al menos un insumo seleccionado."
            );
        }

        if ($seleccionados === null) {
            return;
        }

        $ajenos = array_values(array_diff($seleccionados, $idsFabricables));

        if ($ajenos !== []) {
            throw new OrdenProduccionException(
                "La línea de {$mobiliario->nombre} tiene insumos que no pertenecen a su composición."
            );
        }
    }

    /**
     * @param  array<int, float>  $demanda
     * @return list<array<string, mixed>>
     */
    private function construirAnalisisInsumos(array $demanda, bool $bloquear = false): array
    {
        if ($demanda === []) {
            return [];
        }

        $insumoIds = array_map('intval', array_keys($demanda));

        $query = Insumo::query()->whereIn('id', $insumoIds);

        if ($bloquear) {
            $query->lockForUpdate();
        }

        $insumos = $query->get()->keyBy('id');

        $reservas = ReservaStock::query()
            ->where('estado', 'activa')
            ->whereIn('insumo_id', $insumoIds);

        if ($bloquear) {
            $reservas->lockForUpdate();
        }

        $reservadoPorInsumo = $reservas
            ->get()
            ->groupBy('insumo_id')
            ->map(fn ($grupo) => (float) $grupo->sum('cantidad_reservada'));

        $resultado = [];

        foreach ($demanda as $insumoId => $requerido) {
            $insumoId = (int) $insumoId;
            $insumo = $insumos->get($insumoId);
            $stockActual = (float) ($insumo?->stock_actual ?? 0);
            $reservado = (float) ($reservadoPorInsumo->get($insumoId) ?? 0);
            $disponible = max(0, $stockActual - $reservado);
            $faltante = max(0, round((float) $requerido - $disponible, 4));

            $resultado[] = [
                'insumo_id' => $insumoId,
                'nombre' => $insumo?->nombre ?? "Insumo #{$insumoId}",
                'codigo' => $insumo?->codigo,
                'requerido' => (float) $requerido,
                'stock_actual' => $stockActual,
                'reservado' => $reservado,
                'disponible' => $disponible,
                'stock_disponible' => $disponible,
                'faltante' => $faltante,
                'proveedor_id' => $insumo?->proveedor_id ? (int) $insumo->proveedor_id : null,
                'precio_costo' => $insumo?->precio_costo,
            ];
        }

        return $resultado;
    }

    /**
     * @param  array<int, float>  $demanda
     */
    private function reservarDemanda(OrdenProduccion $orden, array $demanda): void
    {
        if ($orden->reservasStock()->where('estado', 'activa')->exists()) {
            return;
        }

        foreach ($demanda as $insumoId => $cantidad) {
            if ((float) $cantidad <= 0) {
                continue;
            }

            ReservaStock::create([
                'orden_produccion_id' => $orden->id,
                'presupuesto_id' => null,
                'insumo_id' => (int) $insumoId,
                'cantidad_reservada' => (float) $cantidad,
                'estado' => 'activa',
            ]);
        }
    }

    private function consumirInsumosProporcional(
        OrdenProduccion $orden,
        OrdenProduccionItem $item,
        int $cantidad,
    ): void {
        foreach ($item->insumos as $snapshot) {
            $consumo = round((float) $snapshot->cantidad_unitaria * $cantidad, 4);

            if ($consumo <= 0) {
                continue;
            }

            $pendienteSnapshot = $snapshot->cantidadPendiente();

            if ($consumo - $pendienteSnapshot > 0.0001) {
                throw new OrdenProduccionException(
                    "El consumo de {$snapshot->insumo_id} supera el snapshot pendiente."
                );
            }

            Insumo::query()
                ->where('id', $snapshot->insumo_id)
                ->lockForUpdate()
                ->decrement('stock_actual', $consumo);

            $this->reducirReservaOrden($orden->id, (int) $snapshot->insumo_id, $consumo);

            $snapshot->update([
                'cantidad_consumida' => round((float) $snapshot->cantidad_consumida + $consumo, 4),
            ]);
        }
    }

    private function reducirReservaOrden(int $ordenId, int $insumoId, float $cantidad): void
    {
        $reserva = ReservaStock::query()
            ->where('orden_produccion_id', $ordenId)
            ->where('insumo_id', $insumoId)
            ->where('estado', 'activa')
            ->lockForUpdate()
            ->first();

        if (! $reserva) {
            return;
        }

        $nuevaCantidad = max(0, round((float) $reserva->cantidad_reservada - $cantidad, 4));

        if ($nuevaCantidad <= 0) {
            $reserva->update([
                'cantidad_reservada' => 0,
                'estado' => 'consumida',
            ]);
        } else {
            $reserva->update(['cantidad_reservada' => $nuevaCantidad]);
        }
    }

    private function todasLasLineasCompletas(OrdenProduccion $orden): bool
    {
        $items = OrdenProduccionItem::query()
            ->where('orden_produccion_id', $orden->id)
            ->lockForUpdate()
            ->get();

        return $items->isNotEmpty()
            && $items->every(fn (OrdenProduccionItem $item): bool => $item->cantidadPendiente() <= 0);
    }

    private function cambiarEstado(OrdenProduccion $orden, string $estado, ?string $comentario = null): void
    {
        $anterior = $orden->estado;

        if ($anterior === $estado) {
            return;
        }

        $orden->update(['estado' => $estado]);

        OrdenProduccionHistorial::create([
            'orden_produccion_id' => $orden->id,
            'estado_anterior' => $anterior,
            'estado_nuevo' => $estado,
            'comentario' => $comentario,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * @param  list<array<string, mixed>>  $analisis
     * @return array<string, mixed>
     */
    private function construirSnapshotPlan(OrdenProduccion $orden, array $analisis): array
    {
        $insumoIds = array_map(
            fn (array $fila): int => (int) $fila['insumo_id'],
            $analisis,
        );

        $plantillas = $insumoIds === []
            ? collect()
            : PlantillaFlujoExterno::query()
                ->where('entidad_tipo', 'insumo')
                ->whereIn('entidad_id', $insumoIds)
                ->where('activo', true)
                ->get()
                ->keyBy(fn (PlantillaFlujoExterno $plantilla): int => (int) $plantilla->entidad_id);

        $lotesExistentes = LoteProcesoExterno::query()
            ->where('entidad_tipo', 'insumo')
            ->where('origen_tipo', 'orden_produccion')
            ->where('origen_id', $orden->id)
            ->whereNotIn('estado', ['cancelado'])
            ->pluck('entidad_id')
            ->map(fn ($id): int => (int) $id)
            ->all();

        $insumos = [];

        foreach ($analisis as $fila) {
            $insumoId = (int) $fila['insumo_id'];
            $plantilla = $plantillas->get($insumoId);

            $insumos[] = [
                'id' => $insumoId,
                'nombre' => $fila['nombre'] ?? null,
                'codigo' => $fila['codigo'] ?? null,
                'demanda' => (float) $fila['requerido'],
                'stock_disponible' => (float) ($fila['stock_disponible'] ?? $fila['disponible'] ?? 0),
                'proveedor_id' => $fila['proveedor_id'] ?? null,
                'precio_costo' => $fila['precio_costo'] ?? null,
                'ya_tiene_lote' => in_array($insumoId, $lotesExistentes, true),
                'plantilla' => $plantilla
                    ? ['id' => (int) $plantilla->id, 'nombre' => $plantilla->nombre]
                    : null,
            ];
        }

        return [
            'presupuesto' => [
                'id' => (int) $orden->id,
                'codigo' => (string) $orden->codigo,
            ],
            'omitir_oc' => OrdenCompra::query()
                ->where('orden_produccion_id', $orden->id)
                ->where('generado_automaticamente', true)
                ->exists(),
            'mobiliarios' => [],
            'insumos' => $insumos,
            'ordenes_pendientes' => $this->stockReserva()->snapshotOrdenesPendientesParaPlan(),
        ];
    }

    /**
     * @param  array{lotes: list<array<string, mixed>>, ordenes_compra: list<array<string, mixed>>}  $plan
     * @return array{lotes: list<LoteProcesoExterno>, ordenes: list<OrdenCompra>}
     */
    private function persistirPlanReposicion(OrdenProduccion $orden, array $plan): array
    {
        $stockReserva = $this->stockReserva();
        $lotes = [];

        foreach ($plan['lotes'] ?? [] as $lotePlan) {
            if (($lotePlan['entidad_tipo'] ?? '') !== 'insumo') {
                continue;
            }

            $lote = $stockReserva->crearLoteDesdeOrigen(
                'orden_produccion',
                (int) $orden->id,
                'insumo',
                (int) $lotePlan['entidad_id'],
                (float) $lotePlan['cantidad'],
                (string) ($lotePlan['observaciones'] ?? "Generado al iniciar {$orden->codigo}"),
                isset($lotePlan['plantilla_id']) ? (int) $lotePlan['plantilla_id'] : null,
            );

            if ($lote) {
                $lotes[] = $lote;
            }
        }

        $ordenes = $stockReserva->persistirOrdenesDelPlan(
            $plan['ordenes_compra'] ?? [],
            ordenProduccionId: (int) $orden->id,
            observacion: "Generada automáticamente para orden {$orden->codigo}",
        );

        return [
            'lotes' => $lotes,
            'ordenes' => $ordenes,
        ];
    }

    /**
     * @param  array{lotes: list<LoteProcesoExterno>, ordenes: list<OrdenCompra>}  $persistido
     */
    private function comentarioInicio(array $persistido): string
    {
        $partes = ['Orden iniciada. Insumos reservados.'];

        $codigosOc = collect($persistido['ordenes'] ?? [])
            ->map(fn (OrdenCompra $oc): string => (string) $oc->codigo)
            ->filter()
            ->values();

        $codigosLote = collect($persistido['lotes'] ?? [])
            ->map(fn (LoteProcesoExterno $lote): string => (string) ($lote->codigo ?: "#{$lote->id}"))
            ->filter()
            ->values();

        if ($codigosOc->isNotEmpty()) {
            $partes[] = 'OC: '.$codigosOc->implode(', ').'.';
        }

        if ($codigosLote->isNotEmpty()) {
            $partes[] = 'Lotes: '.$codigosLote->implode(', ').'.';
        }

        return implode(' ', $partes);
    }

    private function planificador(): ConfirmacionPresupuestoPlanificador
    {
        return app(ConfirmacionPresupuestoPlanificador::class);
    }

    private function stockReserva(): StockReservaService
    {
        return app(StockReservaService::class);
    }
}
