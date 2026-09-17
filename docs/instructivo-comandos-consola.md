# Instructivo de comandos de consola

Todos los comandos se ejecutan desde la raíz del proyecto (`c:\wamp64\www\gestionMobiliario`) con Artisan:

```bash
php artisan nombre:del-comando
```

Para ver la lista completa (incluye los de Laravel):

```bash
php artisan list
```

Para ver la ayuda de un comando puntual:

```bash
php artisan help nombre:del-comando
```

En Windows, si `php` no está en el PATH, usá el PHP de WAMP, por ejemplo:

```bash
c:\wamp64\bin\php\php8.2.0\php.exe artisan list
```

Los comandos con `--dry-run` **no guardan cambios**: muestran qué harían. Conviene correrlos primero así y, si el resultado está bien, repetir sin esa opción.

---

## Resumen

| Comando | Qué hace | ¿Modifica datos? |
|---|---|---|
| `backup:database` | Backup MySQL en `storage/app/backups` | No (solo archivos) |
| `presupuestos:congelar-precios-lista` | Copia precio de lista a ítems **sin** precio unitario | Sí |
| `stock:recalcular-reservado` | Regenera reservas de stock de insumos | Sí |
| `cascos:sincronizar` | Crea lotes de cascos y recalcula reservas | Sí |
| `cascos:simular` | Analiza stock/fabricación de cascos de un mobiliario | No |
| `presupuesto:simular-confirmacion` | Muestra lotes y OC que saldrían al confirmar | No |
| `informe:presupuestos-insumos` | Informe de presupuestos que usan ciertos insumos | No |
| `informe:pendientes-entrega` | Mobiliarios pendientes de entrega según un insumo en la BOM | No |

---

## 1. Backup de base de datos

```bash
php artisan backup:database
php artisan backup:database --keep=14
```

**Qué hace**

- Genera un dump MySQL (`mysqldump`) de la base configurada en `.env`.
- Lo guarda en `storage/app/backups/backup_YYYY_MM_DD_HHMMSS.sql`.
- Borra backups viejos y deja solo los últimos N (por defecto **7**).

**Programación automática**

En `routes/console.php` está agendado todos los días a las **02:00**, conservando 14 copias:

```bash
backup:database --keep=14
```

Para que corra solo, el scheduler de Laravel tiene que estar activo (tarea de Windows o cron con `php artisan schedule:run`).

**Requisito:** `mysqldump` tiene que estar disponible en el PATH (viene con MySQL/WAMP).

---

## 2. Congelar precios de lista

```bash
php artisan presupuestos:congelar-precios-lista --dry-run
php artisan presupuestos:congelar-precios-lista
php artisan presupuestos:congelar-precios-lista --codigo=PRES-2026-0001
```

**Qué hace**

Copia el precio de lista del catálogo a `precio_unitario` **solo** en ítems que todavía no tienen precio propio:

- Mobiliarios: `mobiliarios.precio`
- Sillas: precio de la marca del proyecto (`insumo_marcas_silla`)

**Estados que procesa:** `en_revision`, `aprobado`, `confirmado`, `pagado`, `entregado`.

**No toca** ítems que ya tienen `precio_unitario` cargado.

Si no usás `--dry-run`, pide confirmación antes de guardar.

**Diferencia con el botón en editar presupuesto:** el botón *Actualizar precio de lista* sobrescribe **todos** los ítems (también los que ya tenían precio). Este comando solo completa los que están vacíos.

---

## 3. Recalcular stock reservado

```bash
php artisan stock:recalcular-reservado --dry-run
php artisan stock:recalcular-reservado
```

**Qué hace**

Reconstruye la tabla `reservas_stock` desde cero:

1. Borra todas las reservas actuales.
2. Recorre presupuestos en estado `confirmado`, `pagado` o `entregado_parcial`.
3. Toma ítems **no finalizados** y **no entregados**.
4. Calcula la demanda de insumos (BOM del mobiliario o silla directa).
5. Crea una reserva activa por insumo y presupuesto.

Muestra un resumen (reservas anteriores, presupuestos, ítems) y una tabla con el total reservado por insumo.

Con `--dry-run` hace el cálculo y después revierte la transacción.

Usalo si las reservas quedaron desfasadas respecto de los presupuestos activos.

---

## 4. Sincronizar cascos

```bash
php artisan cascos:sincronizar --dry-run
php artisan cascos:sincronizar
```

**Qué hace**

Para **todos** los mobiliarios con plantilla de casco activa:

- Analiza stock de casco, lotes abiertos y demanda de presupuestos activos.
- Si hace falta fabricar, **crea el lote** de proceso externo.
- Recalcula reservas de insumos de los presupuestos involucrados.

En dry-run solo informa qué lote crearía y en qué presupuestos recalcularía reservas.

---

## 5. Simular cascos de un mobiliario

```bash
php artisan cascos:simular {mobiliario_id}
```

Ejemplo:

```bash
php artisan cascos:simular 42
```

**Qué hace (solo lectura)**

Analiza un mobiliario concreto y muestra:

- Datos del mobiliario y plantilla activa
- Stock de casco, lotes abiertos, cobertura, demanda activa, cuánto sale de stock y cuánto hay que fabricar
- Presupuestos activos que incluyen ese mobiliario
- Lotes abiertos existentes
- Insumos marcados como componente de casco y el descuento hipotético si se completara el lote

No crea lotes ni cambia stock.

---

## 6. Simular confirmación de presupuesto

```bash
php artisan presupuesto:simular-confirmacion {codigo}
```

Ejemplo:

```bash
php artisan presupuesto:simular-confirmacion PRES-2026-0001
```

**Qué hace (solo lectura)**

Muestra lo que se generaría al **confirmar** ese presupuesto:

- Lotes de proceso externo (tipo, entidad, cantidad, origen, plantilla)
- Órdenes de compra: si crearía una OC nueva o sumaría a una existente, proveedor, prioridad e insumos

No crea lotes, no arma órdenes de compra y no toca stock.

---

## 7. Informe de presupuestos por insumos

```bash
php artisan informe:presupuestos-insumos
php artisan informe:presupuestos-insumos INS-0145 INS-0146
php artisan informe:presupuestos-insumos INS-0062 --csv
php artisan informe:presupuestos-insumos INS-0062 --csv --output=informe.csv
php artisan informe:presupuestos-insumos INS-0062 --output=-
```

**Qué hace (solo lectura)**

Lista presupuestos que usan los insumos indicados:

- **Directo:** el ítem es esa silla/insumo
- **BOM:** el mobiliario los tiene en la composición técnica

Por cada línea muestra presupuesto, estado, agencia, ítem, origen, cantidades y si está entregado o pendiente. Al final, un resumen de pendientes vs entregadas.

Si no pasás códigos, usa por defecto `INS-0145` e `INS-0146`.

**CSV**

- `--csv` o `--output=...` genera CSV (separador `;`, con BOM UTF-8).
- Sin ruta: `storage/app/informe-presupuestos-insumos-YYYY-mm-dd-His.csv`
- `--output=informe.csv`: `storage/app/informe.csv`
- `--output=-`: imprime el CSV en consola
- `--output=C:\ruta\archivo.csv`: ruta absoluta

---

## 8. Informe de pendientes de entrega por insumo

```bash
php artisan informe:pendientes-entrega
php artisan informe:pendientes-entrega INS-0062
```

**Qué hace (solo lectura)**

Toma ítems de mobiliario **aún no entregados** de presupuestos no finalizados y los parte en dos grupos según si la composición técnica incluye el insumo:

- Con el insumo en la BOM (y cantidad de ese insumo por unidad)
- Sin el insumo en la BOM

Por fila: mobiliario, marca, agencia, cantidad del ítem y cantidad de insumo en la BOM.

Si no pasás código, usa `INS-0062`.

---

## Notas

- Los comandos de **simulación** e **informe** no escriben en la base.
- `backup:database`, `cascos:sincronizar`, `stock:recalcular-reservado` y `presupuestos:congelar-precios-lista` sí tienen efecto (salvo `--dry-run`).
- Si un comando pide confirmación (`yes/no`), en scripts no interactivos se puede saltar con `--no-interaction` (en congelar precios, eso **cancela** el guardado porque no hay confirmación afirmativa).
- Para listar solo los comandos de esta app: `php artisan list` y buscar los grupos `backup`, `cascos`, `informe`, `presupuesto(s)` y `stock`.
