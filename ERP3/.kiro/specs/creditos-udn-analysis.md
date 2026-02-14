# Especificación: Sistema de Créditos por UDN

## Descripción General
Sistema para gestionar créditos de clientes por Unidad de Negocio (UDN), permitiendo consultar deudas, pagos y consumos en un rango de fechas específico.

## Arquitectura Actual

### Flujo de Datos
```
ctrl-sobres.php (Delegador)
    ↓
_Contabilidad.php (Router)
    ↓
_Clientes.php (Lógica de Negocio)
    ↓
mdl-clientes.php (Acceso a Datos)
```

## User Stories

### US-1: Consultar Deuda Total por UDN
**Como** usuario del sistema de contabilidad  
**Quiero** consultar la deuda total de clientes en una UDN específica  
**Para** conocer el saldo pendiente en un período determinado

**Criterios de Aceptación:**
- ✅ El sistema debe filtrar por UDN específica
- ✅ El sistema debe filtrar por rango de fechas
- ✅ El sistema debe calcular: Deuda Total = Consumos Pendientes + Consumos en Bitácora
- ✅ El sistema debe retornar montos formateados con `evaluar()`
- ✅ El sistema debe considerar solo registros activos (`Stado = 1`)

### US-2: Consultar Pagos Realizados por UDN
**Como** usuario del sistema de contabilidad  
**Quiero** consultar los pagos realizados por clientes en una UDN  
**Para** conocer el flujo de efectivo en un período

**Criterios de Aceptación:**
- ✅ El sistema debe sumar pagos de `creditos_bitacora` donde `Tipo = 'Pago'`
- ✅ El sistema debe filtrar por UDN y rango de fechas
- ✅ El sistema debe retornar el total de pagos formateado

## Estructura de Base de Datos

### Tabla: `creditos`
```sql
- id_Credito (PK)
- Nombre
- Stado (1 = Activo, 0 = Inactivo)
```

### Tabla: `creditos_udn`
```sql
- id_Credito (FK → creditos)
- id_UDN (FK → udn)
- Stado
```

### Tabla: `creditos_consumo`
```sql
- id_Credito (FK → creditos)
- Monto
- Fecha_Credito
- Stado
```

### Tabla: `creditos_bitacora`
```sql
- id_Credito (FK → creditos)
- Monto
- Tipo ('Pago' | 'Consumo')
- Fecha_Credito
- Stado
```

## Métodos Principales

### `payDebtUDN()`
**Ubicación:** `contabilidad/ctrl/_Clientes.php`

**Entrada:**
- `$this->udn` - ID de la UDN
- `$this->date1` - Fecha inicial (YYYY-MM-DD)
- `$this->date2` - Fecha final (YYYY-MM-DD)

**Proceso:**
1. Obtiene pagos y consumos de bitácora → `payUDN()`
2. Obtiene consumos pendientes → `debtUDN()`
3. Calcula deuda total: `debtUDN + payUDN['consumo']`

**Salida:**
```php
[
    'debt' => "$ X,XXX.XX",  // Deuda total formateada
    'pay'  => "$ X,XXX.XX"   // Pagos totales formateados
]
```

### `payUDN()`
**Ubicación:** `contabilidad/mdl/mdl-clientes.php`

**Query:**
```sql
SELECT 
    SUM(CASE WHEN Tipo = 'Pago' THEN Monto ELSE 0 END) AS pago,
    SUM(CASE WHEN Tipo = 'Consumo' THEN Monto ELSE 0 END) AS consumo
FROM creditos_bitacora cb
INNER JOIN creditos_udn cu ON cb.id_Credito = cu.id_Credito
WHERE cu.id_UDN = ?
AND cb.Fecha_Credito BETWEEN ? AND ?
AND cb.Stado = 1
```

**Retorna:**
```php
[
    'pago'    => float,  // Total de pagos
    'consumo' => float   // Total de consumos en bitácora
]
```

### `debtUDN()`
**Ubicación:** `contabilidad/mdl/mdl-clientes.php`

**Query:**
```sql
SELECT SUM(cc.Monto) AS deuda
FROM creditos_consumo cc
INNER JOIN creditos_udn cu ON cc.id_Credito = cu.id_Credito
WHERE cu.id_UDN = ?
AND cc.Fecha_Credito BETWEEN ? AND ?
AND cc.Stado = 1
```

**Retorna:**
```php
float  // Total de deuda pendiente
```

## Reglas de Negocio

### RN-1: Cálculo de Deuda Total
```
Deuda Total = Consumos Pendientes (creditos_consumo) + 
              Consumos en Bitácora (creditos_bitacora WHERE Tipo='Consumo')
```

### RN-2: Filtrado de Datos
- Solo considerar registros con `Stado = 1` (activos)
- Filtrar por UDN específica mediante `creditos_udn`
- Filtrar por rango de fechas en `Fecha_Credito`

### RN-3: Formato de Salida
- Todos los montos deben formatearse con `evaluar()` → "$ X,XXX.XX"
- Retornar siempre estructura con claves `debt` y `pay`

## Mejoras Potenciales

### MP-1: Optimización de Consultas
**Problema:** Se realizan 2 consultas separadas (`payUDN` + `debtUDN`)  
**Solución:** Unificar en una sola consulta con múltiples INNER JOINs

### MP-2: Validación de Entrada
**Problema:** No se validan fechas ni UDN antes de consultar  
**Solución:** Agregar validaciones en el controlador

### MP-3: Manejo de Errores
**Problema:** No hay manejo explícito de errores si las consultas fallan  
**Solución:** Implementar validaciones de respuesta

### MP-4: Nomenclatura
**Problema:** Nombres de métodos no siguen convención CoffeeSoft  
**Solución:** 
- `payUDN()` → `getPaymentsByUDN()`
- `debtUDN()` → `getDebtByUDN()`

## Dependencias

### Archivos Relacionados
- `contabilidad/ctrl/ctrl-sobres.php` - Punto de entrada
- `contabilidad/ctrl/_Contabilidad.php` - Router
- `contabilidad/ctrl/_Clientes.php` - Controlador
- `contabilidad/mdl/mdl-clientes.php` - Modelo

### Funciones Auxiliares
- `evaluar()` - Formato de moneda (ubicación: `conf/_Utileria.php`)

## Testing

### Casos de Prueba

#### TC-1: Consulta Exitosa
**Entrada:**
- UDN: 1
- Fecha Inicial: 2024-01-01
- Fecha Final: 2024-12-31

**Resultado Esperado:**
```php
[
    'debt' => "$ 5,000.00",
    'pay'  => "$ 3,000.00"
]
```

#### TC-2: Sin Datos en Rango
**Entrada:**
- UDN: 1
- Fecha Inicial: 2025-01-01
- Fecha Final: 2025-01-31

**Resultado Esperado:**
```php
[
    'debt' => "$ 0.00",
    'pay'  => "$ 0.00"
]
```

#### TC-3: UDN Inexistente
**Entrada:**
- UDN: 9999
- Fecha Inicial: 2024-01-01
- Fecha Final: 2024-12-31

**Resultado Esperado:**
```php
[
    'debt' => "$ 0.00",
    'pay'  => "$ 0.00"
]
```

## Notas Técnicas

### Patrón de Arquitectura
El sistema usa un **patrón delegador** donde:
1. `ctrl-sobres.php` recibe la petición
2. Delega a `_Contabilidad.php` según el módulo
3. `_Contabilidad.php` instancia la clase específica (`Clientes`)
4. La clase ejecuta la lógica de negocio
5. El modelo realiza las consultas SQL

### Consideraciones de Performance
- Las consultas usan INNER JOIN para filtrar eficientemente
- Se agregan datos con SUM() en la base de datos (no en PHP)
- Se filtran registros inactivos en la consulta SQL

---

**Versión:** 1.0  
**Fecha:** 2025-01-02  
**Autor:** CoffeeIA ☕  
**Estado:** Documentación Inicial
