# Plan — CXR (Cuentas Recuperadas)

> Módulo: **ERP (legado) / Finanzas → Movimientos (cliente/UDN)**
> Base de datos viva: **`hgpqgijw_finanzas`**
> Servidor: **MySQL 8.0.31** · esquema en **`latin1_swedish_ci`** · tablas InnoDB (verificado 2026-06-21)
> Conexión: `ERP/modelo/SQL_PHP/_Conect.php` (PDO, host `localhost`, BD por defecto `hgpqgijw_usuarios`, acceso cross-database a `hgpqgijw_finanzas`)
> Estado: **Plan aprobado a nivel de negocio — pendiente de confirmaciones técnicas (ver §9)**
> Fecha: 2026-06-21

---

## 1. Resumen ejecutivo

Un **CXR (Cuenta Recuperada)** es una cuenta con **saldo vivo que se arrastra día a día** hasta saldarse. Se "cuelga" de una cuenta existente del catálogo (Categoría → Subcategoría, p. ej. una habitación de Hospedaje) a la que se le asigna un monto inicial. Ese saldo permanece visible **todos los días** hasta que, con uno o varios abonos, llega a cero.

**Ejemplo guía:**

```
Día 1:  CXR Habitación 12 ──► monto inicial 20,000   | saldo 20,000  | compromiso: Día 11
Día 1:  abono 10,000 (efectivo) ───────────────────► | saldo 10,000  | ← impacta Reporte Gral del Día 1
Día 2..10: (sin abonos) ─ sigue apareciendo en CXR ► | saldo 10,000  | (solo informativo)
Día 11: abono 10,000 (TC) ─────────────────────────► | saldo 0       | ← impacta Reporte Gral del Día 11
        estado = SALDADO → sale del listado activo
```

**Diferencia clave con el sistema actual:** hoy una venta (o una CxC) está anclada al folio del día en que se generó. El CXR, en cambio, modela un **saldo persistente** y **abonos con fecha propia**, de modo que el saldo se arrastra y cada abono impacta el reporte del día en que se paga.

---

## 2. Cómo funciona hoy el sistema de cuentas en ERP (contexto)

### 2.1 Arquitectura del módulo (cliente/UDN)

El módulo de Movimientos del cliente tiene **dos niveles de pestañas**:

**Nivel 1 — `ERP/vista/finanzas/cliente/tab_navs.php`** (las pestañas grandes):
`Ingresos` · `Archivos` · `Reportes` · (si `udn==2`: `Cheques`, `Gastos`, `Compras`, `Pagos`, `Proveedores`, `Caratula`).
Cada una llama a `panel(N)` en `ERP/recursos/js/finanzas/cliente/panes.js`, que hace AJAX a `controlador/finanzas/cliente/pane_*_v.php` e inyecta el HTML en `.tab_content`.

**Nivel 2 — dentro de "Ingresos" (`controlador/finanzas/cliente/pane_ingresos_v.php`, case 0):** una segunda barra `nav-tabs` con las **categorías** (Hospedaje, Restaurante, …) + `T.C.` + **`CxC`** + `REPORTE GRAL`. Cada categoría llama `Subcategoria(id)`; `CxC` llama `cxc_view()`. El contenido se inyecta en `.tab_content_subcategoria`.

> 🎯 **Aquí es donde vive CxC hoy, y donde irá la pestaña CXR** (ver §4).

### 2.2 Tablas del sistema de cuentas (BD `hgpqgijw_finanzas`)

| Tabla | Rol |
|---|---|
| `folio` | Un corte por día (`idFolio`, `Folio`, `Fecha`, `encargado`, `id_UDN`). |
| `categoria` | Categorías de venta (Hospedaje=1, etc.) por UDN. |
| `subcategoria` | **"Las cuentas"**: habitaciones, mesas, spa, etc. Ligadas a `categoria` y `grupo`. |
| `grupo` | Agrupador de subcategorías. |
| `bitacora_ventas` | Cada movimiento de venta del día (ligado a `folio` + `subcategoria`). |
| `bitacora_formaspago` | Forma de pago de cada movimiento. `id_FormasPago = 3` es **"CxC"**. |
| `formas_pago` | Catálogo de formas de pago (`idFormas_Pago`, `FormasPago`, `grupo`): Efectivo, TC, CxC, Anticipo… |
| `tipo_formaspago` | Tipos para la revisión de liquidación de CxC. |
| `revision_cxc` | Desglose de cómo se liquida una CxC actual (Efectivo / TC / Anticipo). |

### 2.3 Capa de datos (importante para implementar)

- **Modelos**: `_CXC.php` (clase `CXC`), `_Finanzas_Cliente.php` (clase `Finanzas`), ambos en `ERP/modelo/SQL_PHP/`.
- **CRUD base** `_CRUD.php` expone **solo** dos métodos: `_Select($query,$array)` (lectura) y `_DIU($query,$array)` (insert/update/delete). → **No hay** `_Read`/`_Insert`/`_Update` con arrays como en ERP3: aquí **las queries SQL se escriben a mano** y la BD se referencia con prefijo (`hgpqgijw_finanzas.tabla`).
- **Controladores** tipo `cxc_view.php`: reciben `$_POST['opc']`, ejecutan el caso y devuelven `echo json_encode($encode)`.

### 2.4 El reporte del día

El "REPORTE GRAL" se arma vía `GRAL()` → `controlador/finanzas/admin/RESUMEN_GRAL.php` (y la carátula vía `caratula_v.php` / `CARATULA_GRAL.php`). Estos suman ingresos del día a partir de `bitacora_*`. → El abono CXR vive fuera de esa cadena, así que **no aparecerá** salvo que se integre explícitamente (ver §6).

---

## 3. Requerimiento y decisiones de negocio confirmadas

| # | Decisión | Confirmado |
|---|---|---|
| 1 | **Origen:** el CXR se cuelga de una cuenta existente. El usuario elige Categoría → Subcategoría (p. ej. habitación de Hospedaje) y le asigna el monto inicial. | ✅ |
| 2 | **Vista:** pestaña **CXR** propia; lista todas las cuentas con `saldo > 0` (arrastre), sin filtrar por folio del día. | ✅ |
| 3 | **Abonos:** el saldo arrastrado es **informativo** (no toca caja). Pero **el día en que se abona, el abono SÍ impacta el Reporte General de ese día** (vía su folio y forma de pago). | ✅ |
| 4 | **Vencimiento:** cada CXR lleva **fecha compromiso** (semáforo: abierto / por vencer / vencido) y estado (Abierto / Saldado / Vencido). | ✅ |
| 5 | **Ubicación:** se trabaja en la carpeta **ERP** (legado); ahí se crea la pestaña. | ✅ |

---

## 4. Dónde va la pestaña CXR

**Recomendado — Nivel 2, junto a CxC** (mínima fricción, misma naturaleza):
En `ERP/controlador/finanzas/cliente/pane_ingresos_v.php` (case 0), agregar un `<li>` junto al de CxC:

```php
<li class="">
  <a class="text-warning" data-toggle="tab" href="#tab" onClick="cxr_view()"> <strong>CXR</strong></a>
</li>
```

`cxr_view()` (JS) hace AJAX a `controlador/finanzas/cliente/cxr_view.php` y pinta en `.tab_content_subcategoria`, igual que `cxc_view()`.

**Alternativa — Nivel 1**, como pestaña grande en `tab_navs.php` con `onClick="panel(10)"` + `case 10` en `panes.js`. Útil si CXR debe ser un módulo independiente de "Ingresos". (No recomendado de inicio: rompe la analogía con CxC.)

---

## 5. Modelo de datos

Dos tablas nuevas (**cabecera + abonos**) más un catálogo de estado opcional, en **`hgpqgijw_finanzas`**. **No se toca** `bitacora_ventas`. Naming en estilo *legacy* del proyecto (PK `idTabla`, FK `id_Tabla`, columnas PascalCase) para casar con `bitacora_*` / `revision_cxc`.

### 5.1 Diagrama de relaciones

```
┌────────────────────────────────────────────────────────────────────────────┐
│  hgpqgijw_finanzas  (tablas LEGACY reusadas)                               │
│  subcategoria        formas_pago        folio              udn (*)         │
│  • idSubcategoria    • idFormas_Pago    • idFolio          • idUDN         │
└─────────┬─────────────────┬─────────────────┬─────────────────┬───────────┘
          │ id_Subcategoria │ id_FormasPago   │ id_Folio        │ id_UDN
          ▼ N:1             ▼ N:1             ▼ N:1             ▼ N:1
╔════════════════════════════════════════════════════════════════════════════╗
║  TABLAS NUEVAS (CXR) — en hgpqgijw_finanzas                                ║
║  ┌────────────────┐  N:1   ┌──────────────────────────────┐                ║
║  │ cxr_estado     │◄───────┤ cxr                  [NUEVO] │                ║
║  │ • idEstado  PK │        │ • idCXR            PK        │                ║
║  └────────────────┘        │ • Cliente / Concepto         │                ║
║                            │ • MontoInicial / Saldo       │                ║
║                            │ • FechaCreacion              │                ║
║                            │ • FechaCompromiso            │                ║
║                            │ • id_estado / id_UDN         │                ║
║                            │ • id_Subcategoria            │                ║
║                            │ • id_Folio_origen (NULLable) │                ║
║                            └──────────────┬───────────────┘                ║
║                                           │ 1:N                            ║
║                                           ▼                                ║
║                            ┌──────────────────────────────┐                ║
║                            │ cxr_abono            [NUEVO] │                ║
║                            │ • idAbono          PK        │                ║
║                            │ • MontoAbono / FechaAbono    │                ║
║                            │ • id_CXR (CASCADE)           │                ║
║                            │ • id_Folio (día del abono)   │                ║
║                            │ • id_FormasPago              │                ║
║                            └──────────────────────────────┘                ║
╚════════════════════════════════════════════════════════════════════════════╝
(*) udn: verificar en qué BD reside (posiblemente hgpqgijw_usuarios). Ver §9.
```

### 5.2 Cardinalidades

| Origen | → | Destino | Cardinalidad |
|---|---|---|---|
| `cxr` | → | `cxr_abono` | 1 : N |
| `cxr` | → | `cxr_estado` | N : 1 |
| `cxr` | → | `subcategoria` | N : 1 |
| `cxr` | → | `udn` | N : 1 |
| `cxr` | → | `folio` (origen, opcional) | N : 1 |
| `cxr_abono` | → | `folio` (día del abono) | N : 1 |
| `cxr_abono` | → | `formas_pago` | N : 1 |

### 5.3 Regla de saldo

```
saldo = MontoInicial − SUM(cxr_abono.MontoAbono donde active = 1)
```

Se **materializa** en `cxr.Saldo` y se **recalcula** en cada alta/edición/cancelación de abono (ver §7; riesgo de *drift* en §9).

---

## 6. DDL ejecutable (`hgpqgijw_finanzas`)

> ✅ **Verificado contra la base viva (2026-06-21):**
> - **MySQL 8.0.31.**
> - Las tablas a referenciar (`subcategoria`, `formas_pago`, `folio`, `categoria`, `grupo`, `bitacora_*`, `revision_cxc`) son **todas InnoDB** → las FKs se crean sin problema.
> - **Todas usan `latin1_swedish_ci`** (no utf8mb4). → Las 3 tablas nuevas se crean en **`latin1 / latin1_swedish_ci`** para alinear con el esquema vivo y evitar *"illegal mix of collations"* en cualquier JOIN/comparación. (Excepción consciente a db-rules, que pide utf8mb4; aquí manda la consistencia con el legacy y la conexión `SET NAMES utf8` ya existente.)
> - PK referenciadas: `idSubcategoria`, `idFormas_Pago`, `idFolio` → todas `INT` *signed* → las columnas FK son `INT` y casan exactamente.
> - **`udn` NO está en `hgpqgijw_finanzas`**: vive en `hgpqgijw_usuarios` (y otras BD multi-tenant). → `id_UDN` se deja **sin FK**, solo `KEY` + integridad por aplicación (igual que el legacy).

```sql
-- ============================================================
--  CXR — Cuentas Recuperadas   |   Schema: hgpqgijw_finanzas
-- ============================================================

-- 1) CATÁLOGO DE ESTADO (opción A — recomendada)
CREATE TABLE `hgpqgijw_finanzas`.`cxr_estado` (
  `idEstado`  INT NOT NULL AUTO_INCREMENT,
  `Estado`    VARCHAR(30) NOT NULL,
  `Stado`     TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idEstado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;  -- alineado al esquema vivo hgpqgijw_finanzas

INSERT INTO `hgpqgijw_finanzas`.`cxr_estado` (`idEstado`,`Estado`) VALUES
  (1,'Abierto'), (2,'Saldado'), (3,'Vencido');

-- 2) TRANSACCIÓN RAÍZ — cxr
CREATE TABLE `hgpqgijw_finanzas`.`cxr` (
  `idCXR`            INT NOT NULL AUTO_INCREMENT,
  -- negocio
  `Cliente`          VARCHAR(150) NOT NULL,
  `Concepto`         VARCHAR(255) DEFAULT NULL,
  `encargado`        VARCHAR(100) DEFAULT NULL,
  -- montos
  `MontoInicial`     DOUBLE NOT NULL DEFAULT 0,
  `Saldo`            DOUBLE NOT NULL DEFAULT 0,
  -- fechas de negocio
  `FechaCreacion`    DATE NOT NULL,
  `FechaCompromiso`  DATE DEFAULT NULL,
  -- auditoría
  `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  -- status
  `id_estado`        INT NOT NULL DEFAULT 1,
  -- FKs / referencias
  `id_UDN`           INT DEFAULT NULL,
  `id_Subcategoria`  INT DEFAULT NULL,
  `id_Folio_origen`  INT DEFAULT NULL,
  -- soft-delete
  `active`           TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idCXR`),
  KEY `id_estado` (`id_estado`),
  KEY `id_UDN` (`id_UDN`),
  KEY `id_Subcategoria` (`id_Subcategoria`),
  KEY `id_Folio_origen` (`id_Folio_origen`),
  CONSTRAINT `cxr_ibfk_1` FOREIGN KEY (`id_estado`)
    REFERENCES `cxr_estado` (`idEstado`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `cxr_ibfk_2` FOREIGN KEY (`id_Subcategoria`)
    REFERENCES `subcategoria` (`idSubcategoria`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cxr_ibfk_3` FOREIGN KEY (`id_Folio_origen`)
    REFERENCES `folio` (`idFolio`) ON DELETE SET NULL ON UPDATE CASCADE
  -- Nota: la FK a `udn` se omite si la tabla reside en otra BD (ver §9);
  --       dejar solo KEY `id_UDN` + integridad por aplicación.
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;  -- alineado al esquema vivo hgpqgijw_finanzas

-- 3) DETALLE — cxr_abono
CREATE TABLE `hgpqgijw_finanzas`.`cxr_abono` (
  `idAbono`       INT NOT NULL AUTO_INCREMENT,
  -- negocio
  `Observacion`   VARCHAR(255) DEFAULT NULL,
  -- montos
  `MontoAbono`    DOUBLE NOT NULL DEFAULT 0,
  -- fecha de negocio
  `FechaAbono`    DATE NOT NULL,
  -- auditoría
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  -- FKs
  `id_CXR`        INT NOT NULL,
  `id_Folio`      INT DEFAULT NULL,
  `id_FormasPago` INT DEFAULT NULL,
  -- soft-delete
  `active`        TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`idAbono`),
  KEY `id_CXR` (`id_CXR`),
  KEY `id_Folio` (`id_Folio`),
  KEY `id_FormasPago` (`id_FormasPago`),
  CONSTRAINT `cxr_abono_ibfk_1` FOREIGN KEY (`id_CXR`)
    REFERENCES `cxr` (`idCXR`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cxr_abono_ibfk_2` FOREIGN KEY (`id_Folio`)
    REFERENCES `folio` (`idFolio`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `cxr_abono_ibfk_3` FOREIGN KEY (`id_FormasPago`)
    REFERENCES `formas_pago` (`idFormas_Pago`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;  -- alineado al esquema vivo hgpqgijw_finanzas
```

> ✅ **FKs cross-tabla verificadas:** `subcategoria`, `formas_pago`, `folio` son **InnoDB** con PK `INT` → las `CONSTRAINT` se crean tal cual. La única referencia omitida es `udn` (vive en otra BD): queda como `KEY id_UDN` + integridad por aplicación.

---

## 7. Integración con el Reporte General — `RESUMEN_GRAL.php` (regla #3) ✅ definido

**Archivo de referencia confirmado:** `ERP/controlador/finanzas/admin/RESUMEN_GRAL.php` (es el que abre el botón "REPORTE GRAL" vía `GRAL()` en `panes.js`).

### Cómo está armado hoy ese reporte
1. **Tabla "INGRESOS TURISMO"** — ingresos por categoría (Hospedaje, Restaurante…).
2. **Tabla "FORMA DE PAGO"** — renglones `EFECTIVO`, `TARJETA`, `CxC OTROS SERVICIOS`, `ANTICIPOS`, `CxC HABITACIONES`, `CORTESÍA` → acumula `$TotalFormasPago` (líneas ~216-243).
3. **Tabla "FORMA DE PAGO PROPINA"** → `$TotalPropinas`.
4. **TOTAL GENERAL** = `$TotalPropinas + $TotalFormasPago` (línea ~346).
5. Bloque final **"Cuentas por cobrar"** (detalle CxC, líneas ~356-371).

> ⚠️ Nota: el reporte trabaja por **rango** `$fi..$ff` (`date1`/`date2`) y por **`$udn`**, no por una sola fecha. La query de integración debe respetar ambos.

### Opción A — recomendada (mínimo acoplamiento)

**a) Sumar los abonos del periodo** y agregarlos como **renglón propio "ABONOS CXR (recuperaciones)"** en la tabla de FORMA DE PAGO (tras la fila de cortesías, ~línea 230), incluyéndolo en `$TotalFormasPago` (~línea 232):

```sql
-- Total de abonos CXR del periodo, por la UDN del reporte
SELECT fp.idFormas_Pago, fp.FormasPago, SUM(a.MontoAbono) AS suma_monto
FROM   hgpqgijw_finanzas.cxr_abono a
INNER JOIN hgpqgijw_finanzas.cxr c          ON a.id_CXR = c.idCXR
INNER JOIN hgpqgijw_finanzas.formas_pago fp ON a.id_FormasPago = fp.idFormas_Pago
WHERE  a.FechaAbono BETWEEN ? AND ?     -- $fi, $ff
  AND  c.id_UDN = ?                      -- $udn
  AND  a.active = 1
GROUP BY fp.idFormas_Pago;
```

> El saldo arrastrado **no** toca caja (informativo); solo el **abono** entra al corte, en el periodo en que se pagó (cumple la regla #3). Se muestra desglosado por forma de pago para trazabilidad, sin mezclarse con las ventas de `bitacora_*`.

**b) Opcional — bloque de detalle "Cuentas Recuperadas (CXR)"** al final, análogo al de "Cuentas por cobrar" (líneas ~356-371): listar las CXR con `Saldo > 0` (arrastre) y/o los abonos del periodo.

**Opción B — descartada:** insertar el abono también en `bitacora_ventas`/`bitacora_formaspago`. Duplica el monto (drift) y mezcla semánticas (un abono CXR no es una venta).

---

## 8. Lógica de recálculo (saldo + estado)

Con el CRUD de ERP (`_Select`/`_DIU`, SQL a mano), tras cada alta/edición/cancelación de abono ejecutar:

```sql
UPDATE hgpqgijw_finanzas.cxr c
SET c.Saldo = c.MontoInicial - (
      SELECT COALESCE(SUM(a.MontoAbono),0)
      FROM hgpqgijw_finanzas.cxr_abono a
      WHERE a.id_CXR = c.idCXR AND a.active = 1
    ),
    c.id_estado = CASE
      WHEN c.MontoInicial - (SELECT COALESCE(SUM(a.MontoAbono),0)
            FROM hgpqgijw_finanzas.cxr_abono a WHERE a.id_CXR = c.idCXR AND a.active = 1) <= 0 THEN 2  -- Saldado
      WHEN c.FechaCompromiso IS NOT NULL AND c.FechaCompromiso < CURDATE() THEN 3                       -- Vencido
      ELSE 1                                                                                            -- Abierto
    END
WHERE c.idCXR = ?;
```

El semáforo "por vencer" (p. ej. compromiso dentro de 3 días) se calcula en frontend; no se persiste.

---

## 9. Mapa de implementación (archivos ERP)

| Capa | Archivo | Acción |
|---|---|---|
| **BD** | `hgpqgijw_finanzas` | crear `cxr_estado`, `cxr`, `cxr_abono` (§6) |
| **Modelo** | nuevo `ERP/modelo/SQL_PHP/_CXR.php` (clase `CXR extends CRUD`) | métodos con `_Select`/`_DIU` y SQL a mano: alta CXR, listar saldos>0, alta abono, recálculo, historial, catálogos (categoría/subcategoría/formas_pago) |
| **Controlador** | nuevo `ERP/controlador/finanzas/cliente/cxr_view.php` | patrón `cxc_view.php`: `switch($_POST['opc'])`, `echo json_encode($encode)`. Casos: `1` listar, `2` alta CXR, `3` alta abono, `4` historial, `5` catálogos para el alta |
| **Vista / pestaña** | `ERP/controlador/finanzas/cliente/pane_ingresos_v.php` (case 0) | agregar `<li onClick="cxr_view()">CXR</li>` junto a CxC (§4) |
| **JS** | `ERP/recursos/js/finanzas/cliente/panes.js` **o** nuevo `recursos/js/finanzas/cliente/cxr.js` | `cxr_view()`, modal de alta, modal de abono, refresco del listado (patrón de `cxc_view()` / `abrirCobro()` / `registrarCobroCxC()` en `panes.js`) |
| **Reporte** | `ERP/controlador/finanzas/admin/RESUMEN_GRAL.php` (o carátula) | sumar abonos CXR de la fecha (§7, Opción A) |

### Flujo funcional resumido

1. **Crear CXR** → modal: Categoría → Subcategoría (catálogos `Select_Categoria` / `Select_Subcategoria_x_grupo`), monto inicial, fecha compromiso, cliente/concepto. Inserta en `cxr` con `Saldo = MontoInicial`, estado Abierto.
2. **Listar (arrastre)** → la pestaña CXR muestra todas las cuentas con `Saldo > 0`, sin filtrar por fecha. Semáforo por `FechaCompromiso`.
3. **Abonar** → modal: monto, forma de pago (`formas_pago`), fecha (default hoy), observación. Inserta en `cxr_abono`, recalcula saldo/estado. Si llega a 0 → Saldado (sale del listado activo, queda en histórico).
4. **Reporte General** → al construirse para una fecha, suma los `cxr_abono` de esa fecha como ingreso del día (§7).

---

## 10. Pendientes por confirmar (antes de codear)

**Técnicos (BD) — ✅ verificados contra la base viva (2026-06-21):**
1. ~~Versión de MySQL~~ → **MySQL 8.0.31.**
2. ~~¿`subcategoria`/`formas_pago`/`folio` son InnoDB?~~ → **Sí, todas InnoDB.** Las FKs se crean.
3. ~~¿Dónde reside `udn`?~~ → **En `hgpqgijw_usuarios`** (no en finanzas) + otras BD multi-tenant. **Decisión:** `id_UDN` sin FK, solo `KEY` + integridad por aplicación.
4. ~~Collation~~ → el esquema vivo es **`latin1_swedish_ci`**; las tablas nuevas se crean igual para evitar *mix of collations*.

**Decisiones cerradas:**
5. ~~Catálogo vs. columna suelta~~ → **Opción A: catálogo `cxr_estado`** (ya en el DDL §6).
6. ~~Reporte de referencia~~ → **`RESUMEN_GRAL.php`** (integración detallada en §7, Opción A).

**De negocio / casos borde:**
- **Abono que excede el saldo** → bloquear (recomendado) o permitir saldo negativo.
- **Editar/eliminar un abono** ya reflejado en un reporte de día cerrado → permitir solo si el folio de ese día está abierto (recomendado).
- **Ubicación de la pestaña** → Nivel 2 junto a CxC (recomendado) vs. Nivel 1 en `tab_navs.php`.
- **Reporte General** → mostrar abono CXR como renglón propio "Abonos CXR" (recomendado) vs. fundirlo en su forma de pago.

---

## 11. Fases de entrega

| Fase | Alcance |
|---|---|
| **F1 – Datos** | Crear las 3 tablas (§6) + modelo `_CXR.php` (alta, listar, catálogos). |
| **F2 – Captura y arrastre** | Pestaña CXR junto a CxC + alta + listado con saldo vivo y semáforo. |
| **F3 – Abonos** | Modal de abono + recálculo de saldo/estado + historial. |
| **F4 – Reporte** | Integrar abonos del día al Reporte General (§7, Opción A). |

---

### Notas de diseño

- **Carpeta de trabajo: ERP (legado)**, BD `hgpqgijw_finanzas`. El CRUD base solo ofrece `_Select`/`_DIU`: las queries van escritas a mano con prefijo `hgpqgijw_finanzas.` (no usar el patrón de arrays `_Read`/`_Insert` de ERP3).
- **Naming legacy** (`idCXR` / `id_Subcategoria` / `Cliente`) para casar con `bitacora_*` / `revision_cxc`.
- **`cxr_estado` es opcional**: para replicar el patrón `folio.id_estado` (columna `TINYINT` sin catálogo físico), eliminar la tabla, su `INSERT` y la `CONSTRAINT cxr_ibfk_1`, dejando solo el `KEY id_estado`.
- **`id_Folio_origen` es NULLable** a propósito: un CXR puede crearse sin colgar de un folio concreto (solo Categoría → Subcategoría + monto).
- **Saldo materializado** (`cxr.Saldo`) implica riesgo de *drift*: mitigado con el recálculo controlado de §8.
