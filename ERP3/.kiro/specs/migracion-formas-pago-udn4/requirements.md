# Requirements - Migración de Formas de Pago UDN 4

## 1. Descripción General

Este proyecto documenta la migración y corrección de datos de formas de pago (efectivo, monedas extranjeras y bancos) de la UDN 4 (Bonsai) desde la base de datos antigua (`rfwsmqex_gvsl_finanzas`) hacia la base de datos nueva (`rfwsmqex_gvsl_finanzas3`).

**Contexto:**
- La base antigua contiene los datos reales/originales de operación
- La base nueva es el sistema migrado que debe coincidir 100% con los datos reales
- Se detectaron discrepancias en datos migrados que requieren corrección

## 2. User Stories

### US-1: Como administrador del sistema, necesito corregir datos duplicados de efectivo
**Descripción:** Los días 18, 19 y 20 de enero 2026 tienen valores de efectivo duplicados o incorrectos en la base nueva que no coinciden con los datos reales de la base antigua.

**Acceptance Criteria:**
- Los montos de efectivo en la base nueva deben coincidir exactamente con los de la base antigua
- No debe haber duplicación de valores
- Los cambios deben aplicarse usando el patrón delete-and-recreate

### US-2: Como administrador del sistema, necesito corregir el concepto "Vales" mal clasificado
**Descripción:** El concepto "Vales" está guardado como concepto de efectivo en la base nueva, cuando en realidad es una moneda extranjera en la base antigua.

**Acceptance Criteria:**
- "Vales" debe eliminarse de la tabla `detail_cash_concept`
- "Vales" debe insertarse en la tabla `detail_foreing_currency` con `foreing_currency_id=3`
- El tipo de cambio debe ser 1.00 (equivalencia 1:1 con MXN)

### US-3: Como administrador del sistema, necesito eliminar dólares incorrectos
**Descripción:** Los días 18, 19 y 20 de enero 2026 tienen registros de dólares en la base nueva que no existen en la base antigua.

**Acceptance Criteria:**
- Los registros de dólares que no existen en la base antigua deben eliminarse
- Solo deben permanecer los registros de dólares que coincidan con la base antigua

### US-4: Como administrador del sistema, necesito corregir bancos con nombres y montos incorrectos
**Descripción:** Los días 18, 19 y 20 de enero 2026 tienen bancos con nombres incorrectos o montos que no coinciden con la base antigua.

**Acceptance Criteria:**
- Los bancos en la base nueva deben coincidir exactamente con los de la base antigua
- Debe usarse el mapeo correcto de IDs entre bases
- Aplicar patrón delete-and-recreate para cada día

### US-5: Como administrador del sistema, necesito ingresar conceptos de bancos faltantes
**Descripción:** Los días 1-7 de febrero 2026 no tienen conceptos de bancos en la base nueva, pero sí existen en la base antigua.

**Acceptance Criteria:**
- Todos los registros de bancos de la base antigua deben insertarse en la base nueva
- Debe usarse el mapeo correcto de IDs entre bases
- Los montos deben coincidir exactamente

### US-6: Como administrador del sistema, necesito ingresar conceptos de efectivo y monedas faltantes
**Descripción:** Los días 1-4 de febrero 2026 no tienen conceptos de efectivo en la base nueva. Los días 2 y 4 no tienen monedas extranjeras.

**Acceptance Criteria:**
- Todos los registros de efectivo de la base antigua deben insertarse en la base nueva
- Todos los registros de monedas extranjeras de la base antigua deben insertarse en la base nueva
- Los días 5, 6, 7 que ya tienen datos correctos deben preservarse sin modificación

## 3. Mapeo de IDs entre Bases

### 3.1 Conceptos de Efectivo (UDN 4)

| Base Antigua | Base Nueva | Concepto |
|--------------|------------|----------|
| id_Efectivo=1 | cash_concept_id=4 | Propina |
| id_Efectivo=2 | cash_concept_id=5 | Efectivo |

### 3.2 Monedas Extranjeras

| Base Antigua | Base Nueva | Moneda |
|--------------|------------|--------|
| id_Moneda=1 | foreing_currency_id=1 | Dólares |
| id_Moneda=3 | foreing_currency_id=3 | Vales |

### 3.3 Bancos (UDN 4)

| Base Antigua | Base Nueva | Banco | Cuenta |
|--------------|------------|-------|--------|
| id_UB=33 | bank_account_id=22 | SANTANDER | 2987 |
| id_UB=51 | bank_account_id=9 | BBVA | 6682 |
| id_UB=54 | bank_account_id=6 | BANORTE | 9167 |

## 4. Reglas de Negocio

### RN-1: Patrón Delete-and-Recreate
**Descripción:** Todas las operaciones de corrección/inserción deben seguir el patrón:
1. Eliminar todos los registros existentes del `daily_closure_id`
2. Insertar los datos correctos de la base antigua

**Justificación:** Este patrón garantiza idempotencia y simplifica la lógica de corrección.

### RN-2: Base Antigua como Fuente de Verdad
**Descripción:** La base antigua (`rfwsmqex_gvsl_finanzas`) contiene los datos reales y es la fuente de verdad para todas las correcciones.

**Justificación:** Los datos antiguos son los que se usaron en operación real del negocio.

### RN-3: Coincidencia 100%
**Descripción:** Después de cada operación, los datos en la base nueva deben coincidir exactamente (100%) con los datos de la base antigua.

**Justificación:** Garantiza integridad de datos y confiabilidad del sistema migrado.

### RN-4: Mapeo de IDs Obligatorio
**Descripción:** Siempre debe usarse el mapeo de IDs documentado entre bases antigua y nueva.

**Justificación:** Los IDs cambiaron durante la migración y deben traducirse correctamente.

### RN-5: Concepto "Vales" es Moneda Extranjera
**Descripción:** El concepto "Vales" debe tratarse como moneda extranjera (`foreing_currency_id=3`), NO como concepto de efectivo.

**Justificación:** Así está clasificado en la base antigua y representa la realidad operativa.

## 5. Períodos de Datos

### 5.1 Enero 18-24, 2026 (Corrección)
- **Estado inicial:** Datos con discrepancias (duplicados, incorrectos)
- **Acción:** Corrección de datos existentes
- **Estado final:** Datos corregidos y verificados

### 5.2 Febrero 1-7, 2026 (Ingreso)
- **Estado inicial:** Datos faltantes o incompletos
- **Acción:** Ingreso de datos desde base antigua
- **Estado final:** Datos completos y verificados

## 6. Criterios de Aceptación Globales

### CA-1: Integridad de Datos
- ✅ Todos los registros de la base antigua deben existir en la base nueva
- ✅ No debe haber registros en la base nueva que no existan en la base antigua
- ✅ Los montos deben coincidir exactamente (sin diferencias de centavos)

### CA-2: Mapeo Correcto
- ✅ Todos los IDs deben traducirse usando el mapeo documentado
- ✅ No debe haber referencias a IDs incorrectos

### CA-3: Clasificación Correcta
- ✅ "Vales" debe estar en `detail_foreing_currency`, NO en `detail_cash_concept`
- ✅ Cada concepto debe estar en su tabla correspondiente

### CA-4: Verificación Post-Operación
- ✅ Después de cada operación debe ejecutarse una consulta de verificación
- ✅ La verificación debe confirmar coincidencia 100% con base antigua

## 7. Restricciones

### R-1: No Modificar Base Antigua
**Descripción:** La base antigua (`rfwsmqex_gvsl_finanzas`) es de solo lectura y NO debe modificarse.

### R-2: Usar MCP MySQL
**Descripción:** Todas las consultas y operaciones deben ejecutarse usando el MCP MySQL.

### R-3: Documentar Todas las Operaciones
**Descripción:** Cada operación debe documentarse con:
- Queries ejecutadas
- Registros afectados
- Resultados de verificación

### R-4: UDN Específica
**Descripción:** Este proyecto es específico para UDN 4 (Bonsai). Otras UDNs requieren análisis separado.

## 8. Dependencias

### D-1: Acceso a Bases de Datos
- Base antigua: `rfwsmqex_gvsl_finanzas` (lectura)
- Base nueva: `rfwsmqex_gvsl_finanzas3` (lectura/escritura)

### D-2: MCP MySQL
- Servidor MCP MySQL configurado y funcional
- Permisos de lectura en base antigua
- Permisos de escritura en base nueva

### D-3: Documentación de Rutinas
- `routine-payment.md` (consulta de datos reales)
- `routine-add-payment.md` (patrón delete-and-recreate)

## 9. Riesgos

### Riesgo-1: Pérdida de Datos
**Probabilidad:** Baja  
**Impacto:** Alto  
**Mitigación:** Usar patrón delete-and-recreate con verificación post-operación

### Riesgo-2: Mapeo Incorrecto de IDs
**Probabilidad:** Media  
**Impacto:** Alto  
**Mitigación:** Documentar y validar mapeo antes de cada operación

### Riesgo-3: Datos Inconsistentes en Base Antigua
**Probabilidad:** Baja  
**Impacto:** Medio  
**Mitigación:** Validar datos de base antigua antes de migrar

## 10. Métricas de Éxito

### M-1: Coincidencia de Datos
- **Objetivo:** 100% de coincidencia entre bases
- **Medición:** Consultas de verificación post-operación

### M-2: Registros Procesados
- **Objetivo:** Todos los registros del período procesados
- **Medición:** Conteo de registros insertados/actualizados

### M-3: Errores
- **Objetivo:** 0 errores en operaciones
- **Medición:** Logs de ejecución

## 11. Glosario

- **Base antigua:** `rfwsmqex_gvsl_finanzas` - Base de datos original con datos reales
- **Base nueva:** `rfwsmqex_gvsl_finanzas3` - Base de datos migrada
- **UDN:** Unidad de Negocio (UDN 4 = Bonsai)
- **daily_closure_id:** ID del cierre diario en base nueva
- **Patrón delete-and-recreate:** Eliminar registros existentes + insertar datos correctos
- **Vales:** Concepto que representa vales de despensa, clasificado como moneda extranjera
- **MCP:** Model Context Protocol - herramienta para ejecutar queries SQL

---

**Versión:** 1.0  
**Fecha:** 2026-02-08  
**Autor:** CoffeeIA ☕  
**Estado:** Aprobado
