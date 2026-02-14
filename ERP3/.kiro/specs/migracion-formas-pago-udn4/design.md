# Design - Migración de Formas de Pago UDN 4

## 1. Descripción General

Este documento describe la arquitectura técnica, patrones de diseño y flujos de datos para el proyecto de migración de formas de pago de la UDN 4 (Bonsai) desde la base de datos antigua hacia la base nueva.

**Bases de datos:**
- **Antigua:** `rfwsmqex_gvsl_finanzas` (solo lectura - fuente de verdad)
- **Nueva:** `rfwsmqex_gvsl_finanzas3` (lectura/escritura - destino de migración)

**Herramientas:**
- MCP MySQL para todas las operaciones de base de datos
- Documentos de rutinas: `routine-payment.md` y `routine-add-payment.md`

---

## 2. Arquitectura de Datos

### 2.1 Modelo de Tablas: Catálogos vs Detalles

El sistema usa un patrón de **catálogos + detalles** donde:
- **Catálogos:** Definen los tipos de conceptos disponibles (configuración por UDN)
- **Detalles:** Registran los valores específicos de cada cierre diario

```
CATÁLOGOS (configuración)          DETALLES (registros diarios)
─────────────────────────          ────────────────────────────

┌─────────────────────┐            ┌──────────────────────────┐
│ cash_concept         │            │ detail_cash_concept       │
│ ─────────────────── │            │ ────────────────────────  │
│ id (PK)             │◄───────────│ cash_concept_id (FK)     │
│ name                │            │ daily_closure_id (FK)    │
│ operation_type      │            │ amount                   │
│ udn_id              │            └──────────────────────────┘
│ active              │
└─────────────────────┘

┌─────────────────────┐            ┌──────────────────────────┐
│ foreing_currency     │            │ detail_foreing_currency   │
│ ─────────────────── │            │ ────────────────────────  │
│ id (PK)             │◄───────────│ foreing_currency_id (FK) │
│ name                │            │ exchange_rate            │
│ symbol              │            │ amount                   │
│ exchange_rate       │            │ amount_mxn               │
│ active              │            │ daily_closure_id (FK)    │
└─────────────────────┘            └──────────────────────────┘

┌─────────────────────┐            ┌──────────────────────────┐
│ bank_account         │            │ detail_bank_account       │
│ ─────────────────── │            │ ────────────────────────  │
│ id (PK)             │◄───────────│ bank_account_id (FK)     │
│ bank_id (FK)        │            │ daily_closure_id (FK)    │
│ account             │            │ amount                   │
│ udn_id              │            └──────────────────────────┘
│ active              │
└─────────────────────┘
```

**Características clave:**
- Los catálogos definen QUÉ conceptos están disponibles para cada UDN
- Los detalles registran CUÁNTO se recibió de cada concepto en cada cierre diario
- Relación 1:N entre catálogos y detalles

### 2.2 Relación con daily_closure

Todos los detalles se vinculan a un cierre diario específico:

```
┌──────────────────────────┐
│ daily_closure             │
│ ────────────────────────  │
│ id (PK)                  │◄─────┐
│ udn_id (FK)              │      │
│ created_at               │      │
│ total_sale               │      │
│ ...                      │      │
└──────────────────────────┘      │
                                  │
        ┌─────────────────────────┼─────────────────────────┐
        │                         │                         │
        ▼                         ▼                         ▼
┌──────────────────┐    ┌──────────────────┐    ┌──────────────────┐
│ detail_cash_     │    │ detail_foreing_  │    │ detail_bank_     │
│ concept          │    │ currency         │    │ account          │
└──────────────────┘    └──────────────────┘    └──────────────────┘
```

**Regla de negocio:** Un `daily_closure_id` representa un día específico de operación de una UDN.

---

## 3. Patrón Delete-and-Recreate

### 3.1 Descripción

El patrón **delete-and-recreate** es la estrategia principal para corregir/insertar datos:

```
┌─────────────────────────────────────────────────────────┐
│ PATRÓN DELETE-AND-RECREATE                              │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  1. DELETE: Eliminar TODOS los registros existentes    │
│     WHERE daily_closure_id = X                          │
│                                                         │
│  2. INSERT: Insertar datos correctos de base antigua   │
│     VALUES (daily_closure_id, concept_id, amount)       │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### 3.2 Ventajas

| Ventaja | Descripción |
|---------|-------------|
| **Idempotencia** | Ejecutar la operación múltiples veces produce el mismo resultado |
| **Simplicidad** | No requiere lógica compleja de comparación registro por registro |
| **Limpieza** | Elimina datos incorrectos o duplicados automáticamente |
| **Verificable** | Fácil de verificar: comparar totales antes/después |

### 3.3 Implementación por Tabla

#### Efectivo (detail_cash_concept)

```sql
-- Paso 1: DELETE
DELETE FROM rfwsmqex_gvsl_finanzas3.detail_cash_concept
WHERE daily_closure_id = ?;

-- Paso 2: INSERT
INSERT INTO rfwsmqex_gvsl_finanzas3.detail_cash_concept
(daily_closure_id, cash_concept_id, amount)
VALUES 
(?, 5, ?),  -- Efectivo
(?, 4, ?);  -- Propina
```

#### Monedas Extranjeras (detail_foreing_currency)

```sql
-- Paso 1: DELETE
DELETE FROM rfwsmqex_gvsl_finanzas3.detail_foreing_currency
WHERE daily_closure_id = ?;

-- Paso 2: INSERT
INSERT INTO rfwsmqex_gvsl_finanzas3.detail_foreing_currency
(daily_closure_id, foreing_currency_id, amount, exchange_rate, amount_mxn)
VALUES 
(?, 1, ?, ?, ?),  -- Dólares
(?, 3, ?, 1.00, ?);  -- Vales
```

#### Bancos (detail_bank_account)

```sql
-- Paso 1: DELETE
DELETE FROM rfwsmqex_gvsl_finanzas3.detail_bank_account
WHERE daily_closure_id = ?;

-- Paso 2: INSERT
INSERT INTO rfwsmqex_gvsl_finanzas3.detail_bank_account
(daily_closure_id, bank_account_id, amount)
VALUES 
(?, 6, ?),   -- BANORTE
(?, 9, ?),   -- BBVA
(?, 22, ?);  -- SANTANDER
```

---

## 4. Flujo de Verificación de Datos

### 4.1 Proceso de Verificación

```
┌──────────────────────────────────────────────────────────────┐
│ FLUJO DE VERIFICACIÓN                                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  1. Consultar datos reales (Base Antigua)                   │
│     ↓                                                        │
│  2. Consultar datos migrados (Base Nueva)                   │
│     ↓                                                        │
│  3. Comparar registro por registro                          │
│     ↓                                                        │
│  4. Identificar discrepancias                               │
│     ├─ Datos duplicados                                     │
│     ├─ Datos incorrectos                                    │
│     ├─ Datos faltantes                                      │
│     └─ Datos extra                                          │
│     ↓                                                        │
│  5. Documentar hallazgos                                    │
│     ↓                                                        │
│  6. Aplicar correcciones (delete-and-recreate)              │
│     ↓                                                        │
│  7. Verificar post-corrección (debe ser 100% coincidente)   │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

### 4.2 Criterios de Comparación

| Criterio | Descripción | Acción si no coincide |
|----------|-------------|-----------------------|
| **Existencia** | ¿El registro existe en ambas bases? | Insertar o eliminar |
| **Monto** | ¿El monto es exactamente igual? | Actualizar |
| **Concepto** | ¿El concepto/banco es el mismo? | Corregir mapeo |
| **Clasificación** | ¿Está en la tabla correcta? | Mover a tabla correcta |

### 4.3 Tipos de Discrepancias

#### Tipo 1: Datos Duplicados
**Síntoma:** Monto en base nueva es ~2x el monto real  
**Causa:** Migración ejecutada dos veces  
**Solución:** UPDATE con monto correcto

#### Tipo 2: Datos Incorrectos
**Síntoma:** Banco/concepto diferente al real  
**Causa:** Mapeo de IDs incorrecto  
**Solución:** DELETE + INSERT con datos correctos

#### Tipo 3: Datos Faltantes
**Síntoma:** Registro existe en base antigua pero no en nueva  
**Causa:** Migración incompleta  
**Solución:** INSERT datos faltantes

#### Tipo 4: Datos Extra
**Síntoma:** Registro existe en base nueva pero no en antigua  
**Causa:** Datos de prueba o error de migración  
**Solución:** DELETE datos extra

#### Tipo 5: Clasificación Incorrecta
**Síntoma:** Concepto en tabla incorrecta (ej: "Vales" en efectivo)  
**Causa:** Malinterpretación del tipo de concepto  
**Solución:** DELETE de tabla incorrecta + INSERT en tabla correcta

---

## 5. Diagramas de Secuencia

### 5.1 Flujo de Consulta de Datos Reales

```
Usuario          MCP MySQL       Base Antigua
   │                 │                 │
   │─────────────────>│                 │
   │  Consultar      │                 │
   │  datos reales   │                 │
   │                 │─────────────────>│
   │                 │  SELECT ...     │
   │                 │  FROM base_ant  │
   │                 │<─────────────────│
   │                 │  Resultados     │
   │<─────────────────│                 │
   │  Datos reales   │                 │
   │                 │                 │
```

### 5.2 Flujo de Corrección de Datos

```
Usuario          MCP MySQL       Base Nueva
   │                 │                 │
   │─────────────────>│                 │
   │  Aplicar        │                 │
   │  corrección     │                 │
   │                 │─────────────────>│
   │                 │  DELETE WHERE   │
   │                 │  closure_id=X   │
   │                 │<─────────────────│
   │                 │  N rows deleted │
   │                 │─────────────────>│
   │                 │  INSERT VALUES  │
   │                 │  (...)          │
   │                 │<─────────────────│
   │                 │  M rows inserted│
   │<─────────────────│                 │
   │  Confirmación   │                 │
   │                 │                 │
```

### 5.3 Flujo de Verificación Post-Corrección

```
Usuario          MCP MySQL       Base Antigua    Base Nueva
   │                 │                 │              │
   │─────────────────>│                 │              │
   │  Verificar      │                 │              │
   │                 │─────────────────>│              │
   │                 │  SELECT ...     │              │
   │                 │<─────────────────│              │
   │                 │  Datos reales   │              │
   │                 │─────────────────────────────────>│
   │                 │  SELECT ...                     │
   │                 │<─────────────────────────────────│
   │                 │  Datos migrados                 │
   │<─────────────────│                 │              │
   │  Comparación    │                 │              │
   │  (100% match)   │                 │              │
   │                 │                 │              │
```

---

## 6. Templates de Queries SQL

### 6.1 Consulta de Efectivo (Base Antigua)

```sql
SELECT 
    e.Fecha AS fecha,
    e.id_Efectivo,
    CASE 
        WHEN e.id_Efectivo = 1 THEN 'Propina'
        WHEN e.id_Efectivo = 2 THEN 'Efectivo'
    END AS concepto,
    e.Efectivo AS monto
FROM rfwsmqex_gvsl_finanzas.efectivo e
WHERE e.id_UDN = ?
AND e.Fecha BETWEEN ? AND ?
ORDER BY e.Fecha ASC, e.id_Efectivo ASC
```

### 6.2 Consulta de Monedas Extranjeras (Base Antigua)

```sql
SELECT 
    m.Fecha AS fecha,
    m.id_Moneda,
    CASE 
        WHEN m.id_Moneda = 1 THEN 'Dólares'
        WHEN m.id_Moneda = 3 THEN 'Vales'
    END AS moneda,
    m.Cantidad AS cantidad,
    m.Tipo_Cambio AS tipo_cambio,
    m.Moneda AS monto_mxn
FROM rfwsmqex_gvsl_finanzas.moneda m
WHERE m.id_UDN = ?
AND m.Fecha BETWEEN ? AND ?
ORDER BY m.Fecha ASC, m.id_Moneda ASC
```

### 6.3 Consulta de Bancos (Base Antigua)

```sql
SELECT 
    bb.Fecha_Banco AS fecha,
    b.Name_Bancos AS banco,
    bu.idUB AS id_ub,
    bu.Name_Bancos AS cuenta,
    bb.Pago AS monto
FROM rfwsmqex_gvsl_finanzas.bancos_bitacora bb
INNER JOIN rfwsmqex_gvsl_finanzas.bancos_udn bu ON bb.id_UB = bu.idUB
INNER JOIN rfwsmqex_gvsl_finanzas.bancos b ON bu.id_Bancos = b.idBancos
WHERE bu.id_UDN = ?
AND bb.Fecha_Banco BETWEEN ? AND ?
ORDER BY bb.Fecha_Banco ASC, b.Name_Bancos ASC
```

### 6.4 Consulta de Efectivo (Base Nueva)

```sql
SELECT 
    dc.id AS daily_closure_id,
    DATE(dc.created_at) AS fecha,
    cc.name AS concepto,
    dcc.amount
FROM rfwsmqex_gvsl_finanzas3.detail_cash_concept dcc
INNER JOIN rfwsmqex_gvsl_finanzas3.daily_closure dc ON dcc.daily_closure_id = dc.id
INNER JOIN rfwsmqex_gvsl_finanzas3.cash_concept cc ON dcc.cash_concept_id = cc.id
WHERE dc.udn_id = ?
AND DATE(dc.created_at) BETWEEN ? AND ?
ORDER BY dc.created_at ASC, cc.name ASC
```

### 6.5 Consulta de Monedas Extranjeras (Base Nueva)

```sql
SELECT 
    dc.id AS daily_closure_id,
    DATE(dc.created_at) AS fecha,
    fc.name AS moneda,
    dfc.amount,
    dfc.exchange_rate,
    dfc.amount_mxn
FROM rfwsmqex_gvsl_finanzas3.detail_foreing_currency dfc
INNER JOIN rfwsmqex_gvsl_finanzas3.daily_closure dc ON dfc.daily_closure_id = dc.id
INNER JOIN rfwsmqex_gvsl_finanzas3.foreing_currency fc ON dfc.foreing_currency_id = fc.id
WHERE dc.udn_id = ?
AND DATE(dc.created_at) BETWEEN ? AND ?
ORDER BY dc.created_at ASC, fc.name ASC
```

### 6.6 Consulta de Bancos (Base Nueva)

```sql
SELECT 
    dc.id AS daily_closure_id,
    DATE(dc.created_at) AS fecha,
    b.name AS banco,
    ba.account AS cuenta,
    dba.amount
FROM rfwsmqex_gvsl_finanzas3.detail_bank_account dba
INNER JOIN rfwsmqex_gvsl_finanzas3.daily_closure dc ON dba.daily_closure_id = dc.id
INNER JOIN rfwsmqex_gvsl_finanzas3.bank_account ba ON dba.bank_account_id = ba.id
INNER JOIN rfwsmqex_gvsl_finanzas3.bank b ON ba.bank_id = b.id
WHERE dc.udn_id = ?
AND DATE(dc.created_at) BETWEEN ? AND ?
ORDER BY dc.created_at ASC, b.name ASC
```

---

## 7. Mapeo Técnico de IDs

### 7.1 Conceptos de Efectivo (UDN 4)

```
Base Antigua                    Base Nueva
─────────────                   ──────────
efectivo.id_Efectivo            detail_cash_concept.cash_concept_id
                                cash_concept.id
┌──────────────────┐            ┌──────────────────┐
│ id_Efectivo = 1  │───────────>│ cash_concept_id = 4 │
│ (Propina)        │            │ name = "Propina"    │
└──────────────────┘            └──────────────────┘

┌──────────────────┐            ┌──────────────────┐
│ id_Efectivo = 2  │───────────>│ cash_concept_id = 5 │
│ (Efectivo)       │            │ name = "Efectivo"   │
└──────────────────┘            └──────────────────┘
```

**Regla de conversión:**
```javascript
const cashConceptMap = {
  1: 4,  // Propina
  2: 5   // Efectivo
};
```

### 7.2 Monedas Extranjeras

```
Base Antigua                    Base Nueva
─────────────                   ──────────
moneda.id_Moneda                detail_foreing_currency.foreing_currency_id
                                foreing_currency.id
┌──────────────────┐            ┌──────────────────────┐
│ id_Moneda = 1    │───────────>│ foreing_currency_id = 1 │
│ (Dólares)        │            │ name = "Dólares"        │
└──────────────────┘            └──────────────────────┘

┌──────────────────┐            ┌──────────────────────┐
│ id_Moneda = 3    │───────────>│ foreing_currency_id = 3 │
│ (Vales)          │            │ name = "Vales"          │
└──────────────────┘            └──────────────────────┘
```

**Regla de conversión:**
```javascript
const foreignCurrencyMap = {
  1: 1,  // Dólares
  3: 3   // Vales
};
```

### 7.3 Bancos (UDN 4)

```
Base Antigua                    Base Nueva
─────────────                   ──────────
bancos_udn.idUB                 detail_bank_account.bank_account_id
                                bank_account.id
┌──────────────────┐            ┌──────────────────────┐
│ idUB = 33        │───────────>│ bank_account_id = 22 │
│ SANTANDER (2987) │            │ account = "2987"     │
└──────────────────┘            └──────────────────────┘

┌──────────────────┐            ┌──────────────────────┐
│ idUB = 51        │───────────>│ bank_account_id = 9  │
│ BBVA (6682)      │            │ account = "6682"     │
└──────────────────┘            └──────────────────────┘

┌──────────────────┐            ┌──────────────────────┐
│ idUB = 54        │───────────>│ bank_account_id = 6  │
│ BANORTE (9167)   │            │ account = "9167"     │
└──────────────────┘            └──────────────────────┘
```

**Regla de conversión:**
```javascript
const bankAccountMap = {
  33: 22,  // SANTANDER 2987
  51: 9,   // BBVA 6682
  54: 6    // BANORTE 9167
};
```

---

## 8. Consideraciones de Performance

### 8.1 Optimizaciones Aplicadas

| Optimización | Descripción | Beneficio |
|--------------|-------------|-----------|
| **Batch DELETE** | Eliminar todos los registros de un día en una sola query | Reduce round-trips a BD |
| **Batch INSERT** | Insertar múltiples registros en un solo INSERT | Reduce transacciones |
| **Índices** | Usar índices en `daily_closure_id` | Acelera DELETE y SELECT |
| **Transacciones** | Agrupar DELETE+INSERT en transacción | Garantiza atomicidad |

### 8.2 Volumen de Datos

**Período enero 18-24, 2026:**
- 7 días procesados
- ~30 registros totales (efectivo + monedas + bancos)
- Tiempo de ejecución: <1 segundo por día

**Período febrero 1-7, 2026:**
- 7 días procesados
- ~25 registros totales
- Tiempo de ejecución: <1 segundo por día

**Conclusión:** El volumen de datos es bajo, no requiere optimizaciones adicionales.

---

## 9. Manejo de Errores

### 9.1 Errores Comunes

| Error | Causa | Solución |
|-------|-------|----------|
| **Foreign Key Constraint** | `daily_closure_id` no existe | Verificar que el cierre diario exista antes de insertar |
| **Duplicate Entry** | Intentar insertar registro duplicado | Usar patrón delete-and-recreate |
| **Invalid ID** | Usar ID incorrecto en mapeo | Verificar mapeo de IDs antes de ejecutar |
| **Data Type Mismatch** | Tipo de dato incorrecto | Validar tipos antes de INSERT |

### 9.2 Estrategia de Rollback

```sql
-- Iniciar transacción
START TRANSACTION;

-- Ejecutar DELETE
DELETE FROM rfwsmqex_gvsl_finanzas3.detail_cash_concept
WHERE daily_closure_id = ?;

-- Ejecutar INSERT
INSERT INTO rfwsmqex_gvsl_finanzas3.detail_cash_concept
(daily_closure_id, cash_concept_id, amount)
VALUES (?, ?, ?);

-- Si todo OK: COMMIT
COMMIT;

-- Si error: ROLLBACK
ROLLBACK;
```

**Nota:** MCP MySQL maneja transacciones automáticamente, pero es importante documentar la estrategia.

### 9.3 Validaciones Pre-Ejecución

Antes de ejecutar cualquier operación, validar:

1. ✅ **Existencia de daily_closure_id**
   ```sql
   SELECT id FROM rfwsmqex_gvsl_finanzas3.daily_closure WHERE id = ?
   ```

2. ✅ **Validez de IDs de conceptos**
   ```sql
   SELECT id FROM rfwsmqex_gvsl_finanzas3.cash_concept WHERE id = ?
   ```

3. ✅ **Datos en base antigua**
   ```sql
   SELECT COUNT(*) FROM rfwsmqex_gvsl_finanzas.efectivo WHERE Fecha = ? AND id_UDN = ?
   ```

---

## 10. Documentación de Operaciones

### 10.1 Estructura de Documentos

Cada operación debe documentarse en un archivo markdown con:

```
# Título de la Operación

## Resumen Ejecutivo
- Fecha de ejecución
- UDN
- Período
- Bases de datos
- Operación realizada

## Datos Originales (Base Antigua)
- Consultas ejecutadas
- Resultados obtenidos

## Mapeo de IDs
- Tablas de conversión

## Operaciones Ejecutadas
- Queries DELETE
- Queries INSERT
- Registros afectados

## Verificación Post-Operación
- Consultas de verificación
- Comparación con base antigua
- Estado final

## Conclusiones
- Resumen de resultados
- Confirmación de éxito
```

### 10.2 Archivos de Documentación Existentes

| Archivo | Descripción | Período |
|---------|-------------|---------|
| `analisis-formas-pago-udn4-ene18-24.md` | Corrección de datos con discrepancias | Enero 18-24, 2026 |
| `ingreso-bancos-udn4-feb1-7.md` | Ingreso de conceptos de bancos | Febrero 1-7, 2026 |
| `ingreso-efectivo-monedas-udn4-feb1-7.md` | Ingreso de efectivo y monedas | Febrero 1-7, 2026 |

---

## 11. Herramientas y Tecnologías

### 11.1 MCP MySQL

**Descripción:** Model Context Protocol para ejecutar queries SQL  
**Uso:** Todas las consultas y operaciones de base de datos  
**Ventajas:**
- Ejecución segura de queries
- Manejo automático de conexiones
- Formato de resultados estructurado

**Comandos disponibles:**
- `mysql_query` - Ejecutar SELECT, SHOW, DESCRIBE
- `mysql_execute` - Ejecutar INSERT, UPDATE, DELETE
- `mysql_list_databases` - Listar bases de datos
- `mysql_list_tables` - Listar tablas
- `mysql_describe_table` - Describir estructura de tabla

### 11.2 Documentos de Rutinas

**routine-payment.md:**
- Contiene queries para consultar datos reales de base antigua
- Estructura de tablas y relaciones
- Ejemplos de uso

**routine-add-payment.md:**
- Documenta el patrón delete-and-recreate
- Templates de queries INSERT/DELETE
- Mejores prácticas

---

## 12. Próximos Pasos

### 12.1 Períodos Pendientes

Si se requiere migrar más períodos:

1. **Identificar período:** Definir rango de fechas
2. **Consultar base antigua:** Obtener datos reales
3. **Verificar base nueva:** Identificar discrepancias
4. **Aplicar correcciones:** Usar patrón delete-and-recreate
5. **Verificar resultados:** Confirmar coincidencia 100%
6. **Documentar:** Crear archivo markdown con detalles

### 12.2 Otras UDNs

Para migrar otras UDNs:

1. **Obtener mapeo de IDs:** Cada UDN tiene IDs diferentes
2. **Adaptar queries:** Usar mapeo específico de la UDN
3. **Seguir mismo proceso:** Consultar → Verificar → Corregir → Documentar

---

**Versión:** 1.0  
**Fecha:** 2026-02-08  
**Autor:** CoffeeIA ☕  
**Estado:** Completo
