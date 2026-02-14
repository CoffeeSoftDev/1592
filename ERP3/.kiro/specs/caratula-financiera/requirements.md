# Especificación: Sistema de Carátula Financiera

## 📋 Información General

**Proyecto:** Sistema ERP CoffeeSoft - Módulo de Contabilidad  
**Componente:** Generador de Carátula Financiera  
**Versión:** 1.0  
**Fecha:** 2025-01-25  
**Estado:** ✅ Implementado y Documentado

---

## 🎯 Objetivo del Sistema

El sistema de **Carátula Financiera** es un generador de reportes consolidados que integra información de 12 secciones financieras diferentes para proporcionar una vista completa del estado financiero de una unidad de negocio en un período específico.

### Propósito Principal
Consolidar y presentar de manera estructurada:
- Flujo de efectivo y caja
- Saldos de fondos y retiros
- Ingresos por tipo de venta
- Descuentos e impuestos aplicados
- Movimientos bancarios
- Anticipos de nómina
- Gastos de fondo fijo
- Movimientos de almacén
- Costos de producción
- Créditos de clientes

---

## 👥 Usuarios del Sistema

### Usuario Principal
- **Rol:** Contador / Administrador Financiero
- **Necesidad:** Generar reportes financieros consolidados por período
- **Frecuencia de uso:** Diario / Semanal / Mensual

### Usuarios Secundarios
- **Gerentes de UDN:** Consulta de estado financiero de su unidad
- **Dirección General:** Análisis comparativo entre unidades de negocio
- **Auditores:** Revisión de movimientos financieros

---

## 📊 Historias de Usuario

### HU-01: Generar Carátula Financiera
**Como** contador del sistema  
**Quiero** generar una carátula financiera consolidada  
**Para** tener una vista completa del estado financiero de una UDN en un período específico

**Criterios de Aceptación:**
- ✅ El sistema debe solicitar: UDN, fecha inicial y fecha final
- ✅ Debe consolidar información de 12 secciones financieras
- ✅ Los totales deben calcularse automáticamente
- ✅ Debe mostrar diferencias entre ventas y efectivo en caja
- ✅ Los montos deben formatearse con separadores de miles y decimales

---

### HU-02: Visualizar Sección de Caja
**Como** contador  
**Quiero** ver el desglose de efectivo en caja vs ventas totales  
**Para** identificar diferencias y cuadrar el corte de caja

**Criterios de Aceptación:**
- ✅ Debe mostrar total de ventas (ventas - descuentos + impuestos)
- ✅ Debe listar efectivo por tipo (efectivo, propina, destajo)
- ✅ Debe incluir monedas extranjeras convertidas a MXN
- ✅ Debe mostrar depósitos bancarios del período
- ✅ Debe incluir créditos de clientes (deuda - pagos)
- ✅ Debe calcular y mostrar la diferencia entre total caja y total ventas

---

### HU-03: Visualizar Saldos (Fondo, Retiros, Proveedores)
**Como** contador  
**Quiero** ver los saldos de fondo fijo, retiros y proveedores  
**Para** controlar el flujo de efectivo y obligaciones

**Criterios de Aceptación:**
- ✅ **Fondo:** Debe mostrar saldo inicial, reembolsos, egresos y saldo final
- ✅ **Retiros:** Debe calcular saldo inicial, efectivo actual, retiros y saldo final
- ✅ **Proveedores:** Debe mostrar saldo inicial, ingresos (gastos), egresos (pagos) y saldo final
- ✅ Los cálculos deben considerar movimientos anteriores al período

---

### HU-04: Visualizar Ingresos por Tipo
**Como** contador  
**Quiero** ver el desglose de ventas por tipo  
**Para** analizar la composición de los ingresos

**Criterios de Aceptación:**
- ✅ Debe listar cada tipo de venta con su monto
- ✅ Debe sumar el total de ventas
- ✅ Los tipos de venta deben obtenerse dinámicamente de la BD

---

### HU-05: Visualizar Descuentos e Impuestos
**Como** contador  
**Quiero** ver el desglose de descuentos e impuestos aplicados  
**Para** validar los cálculos de ventas netas

**Criterios de Aceptación:**
- ✅ **Descuentos:** Debe listar cada tipo de descuento con su monto
- ✅ **Impuestos:** Debe listar cada porcentaje de impuesto con su monto
- ✅ Debe calcular totales de descuentos e impuestos

---

### HU-06: Visualizar Totales Consolidados
**Como** contador  
**Quiero** ver los totales consolidados (subtotal, impuestos, total)  
**Para** validar el cálculo final de ventas

**Criterios de Aceptación:**
- ✅ Subtotal = Total Ventas - Total Descuentos
- ✅ Total = Subtotal + Total Impuestos
- ✅ Los montos deben coincidir con los cálculos de secciones anteriores

---

### HU-07: Visualizar Movimientos Bancarios
**Como** contador  
**Quiero** ver los depósitos bancarios por institución  
**Para** conciliar los movimientos bancarios del período

**Criterios de Aceptación:**
- ✅ Debe listar cada banco con el total depositado
- ✅ Los bancos deben obtenerse dinámicamente de la BD

---

### HU-08: Visualizar Anticipos de Nómina
**Como** contador  
**Quiero** ver los anticipos otorgados por empleado  
**Para** controlar los adelantos de nómina

**Criterios de Aceptación:**
- ✅ Debe listar cada empleado con el total de anticipos
- ✅ Solo debe mostrar empleados con anticipos en el período

---

### HU-09: Visualizar Gastos de Fondo Fijo
**Como** contador  
**Quiero** ver las compras pagadas con fondo fijo  
**Para** controlar los gastos menores

**Criterios de Aceptación:**
- ✅ Debe mostrar tabla con columnas: Concepto, Subtotal, Impuesto, Total
- ✅ Debe listar cada concepto de gasto con sus montos
- ✅ Total = Subtotal + Impuesto

---

### HU-10: Visualizar Movimientos de Almacén
**Como** contador  
**Quiero** ver las entradas y salidas de almacén  
**Para** controlar el inventario y su valuación

**Criterios de Aceptación:**
- ✅ Debe mostrar tabla con columnas: Concepto, Entradas (Subtotal, Impuesto), Salidas
- ✅ Debe agrupar por categoría de insumo
- ✅ Debe mostrar subtotales por categoría
- ✅ Debe listar insumos individuales dentro de cada categoría

---

### HU-11: Visualizar Costos de Producción
**Como** contador  
**Quiero** ver los costos de producción por categoría  
**Para** analizar la estructura de costos

**Criterios de Aceptación:**
- ✅ Debe mostrar tabla con columnas: Concepto, Compras (Subtotal, Impuesto), Salidas Almacén
- ✅ Debe listar cada categoría de costo con sus montos

---

### HU-12: Visualizar Créditos de Clientes
**Como** contador  
**Quiero** ver el estado de créditos de clientes  
**Para** controlar las cuentas por cobrar

**Criterios de Aceptación:**
- ✅ Debe mostrar tabla con columnas: Concepto, Consumo, Pagos, Total
- ✅ Debe listar cada cliente con crédito activo
- ✅ Total = Consumo Acumulado - Pagos Acumulados
- ✅ Solo debe mostrar clientes con saldo diferente de cero

---

## 🏗️ Arquitectura del Sistema

### Estructura de Clases

```
Caratula (Controlador Principal)
├── Constructor
│   ├── $this->c (Objeto de contexto)
│   ├── $this->util (Utilidades de formato)
│   └── $this->obj (Modelo MCaratula)
│
├── Variables de Clase
│   ├── $totalVentas (acumulador)
│   ├── $totalDescuentos (acumulador)
│   └── $totalImpuestos (acumulador)
│
└── Métodos Públicos (13 métodos)
    ├── caratula() [PRINCIPAL]
    ├── caja()
    ├── fondo()
    ├── retiros()
    ├── proveedores()
    ├── ingresos()
    ├── descuentos()
    ├── impuestos()
    ├── totales()
    ├── bancos()
    ├── anticipos()
    ├── gastosFondo()
    ├── almacen()
    ├── costos()
    └── creditos()
```

### Flujo de Datos

```
Usuario → Frontend → caratula() → [12 métodos] → Modelo (MCaratula) → Base de Datos
                                        ↓
                                   Consolidación
                                        ↓
                                  Array Asociativo
                                        ↓
                                    Frontend
```

---

## 📐 Especificaciones Técnicas

### Método Principal: `caratula()`

**Entrada:**
- `$idE` (int): ID de la Unidad de Negocio
- `$date1` (string): Fecha inicial (YYYY-MM-DD)
- `$date2` (string): Fecha final (YYYY-MM-DD)

**Salida:**
```php
[
    'caja'        => array,  // Desglose de caja
    'saldo'       => array,  // Fondo + Retiros + Proveedores
    'ingreso'     => array,  // Ventas por tipo
    'descuentos'  => array,  // Descuentos por tipo
    'impuestos'   => array,  // Impuestos por porcentaje
    'totales'     => array,  // Subtotal, Impuestos, Total
    'bancos'      => array,  // Depósitos por banco
    'anticipos'   => array,  // Anticipos por empleado
    'gastosFondo' => array,  // Gastos de fondo fijo
    'almacen'     => array,  // Movimientos de almacén
    'costos'      => array,  // Costos de producción
    'creditos'    => array   // Créditos de clientes
]
```

---

### Método: `caja()`

**Propósito:** Comparar total de ventas vs efectivo en caja

**Tablas Consultadas:**
1. `ventas_diarias` - Total de ventas
2. `efectivo_diario` - Efectivo y propinas
3. `moneda_extranjera` - Divisas convertidas a MXN
4. `bancos_diarios` - Depósitos bancarios
5. `creditos_clientes` - Deuda y pagos de créditos

**Cálculos:**
```
Total Venta = Ventas - Descuentos + Impuestos
Total Caja = Efectivo + Moneda Extranjera + Bancos + Créditos
Diferencia = Total Caja - Total Venta
```

**Salida:**
```php
[
    'Total de venta' => "$X,XXX.XX",
    'Efectivo'       => "$X,XXX.XX",
    'Propina'        => "$X,XXX.XX",
    'Dólares'        => "$X,XXX.XX",
    'Bancos'         => "$X,XXX.XX",
    'Créditos'       => ["$deuda", "$pagos", [$idE, $date1, $date2]],
    'Diferencia'     => "$X,XXX.XX"
]
```

---

### Método: `fondo()`

**Propósito:** Calcular saldo de fondo fijo

**Cálculos:**
```
Saldo Inicial = Saldo Final Anterior - (Gastos SI + Anticipos SI + Proveedores SI)
Egreso = Gastos + Anticipos + Proveedores
Saldo Final = Saldo Inicial + Reembolso - Egreso
```

**Salida:**
```php
[
    'FONDO' => [
        "Saldo Inicial",
        "Reembolso",
        "Egreso",
        "Saldo Final"
    ],
    'ANTICIPO' => ["-", "-", "Anticipos", "Anticipos"]
]
```

---

### Método: `retiros()`

**Propósito:** Calcular saldo de retiros de efectivo

**Cálculos:**
```
SI Retiro = Retiro Total + (SI Efectivo - SI Propina) + SI Moneda Extranjera
Efectivo Actual = (Efectivo - Propina) + Moneda Extranjera
SF Retiro = SI Retiro + Efectivo Actual - Retiro
```

**Salida:**
```php
[
    'RETIROS' => [
        "SI Retiro",
        "Efectivo Actual",
        "Retiro",
        "SF Retiro"
    ]
]
```

---

### Método: `proveedores()`

**Propósito:** Calcular saldo de cuentas por pagar a proveedores

**Cálculos:**
```
Ingreso = Gasto + Gasto IVA
SF Proveedor = (SI Proveedor + Ingreso) - Egreso
```

**Salida:**
```php
[
    'PROVEEDORES' => [
        "SI Proveedor",
        "Ingreso",
        "Egreso",
        "SF Proveedor"
    ]
]
```

---

### Método: `ingresos()`

**Propósito:** Desglosar ventas por tipo

**Salida:**
```php
[
    'Venta Tipo 1' => "$X,XXX.XX",
    'Venta Tipo 2' => "$X,XXX.XX",
    ...
]
```

**Efecto Secundario:**
- Acumula en `$this->totalVentas`

---

### Método: `descuentos()`

**Propósito:** Desglosar descuentos por tipo

**Salida:**
```php
[
    'Descuento Tipo 1' => "$X,XXX.XX",
    'Descuento Tipo 2' => "$X,XXX.XX",
    ...
]
```

**Efecto Secundario:**
- Acumula en `$this->totalDescuentos`

---

### Método: `impuestos()`

**Propósito:** Desglosar impuestos por porcentaje

**Salida:**
```php
[
    '16%' => "$X,XXX.XX",
    '8%'  => "$X,XXX.XX",
    ...
]
```

**Efecto Secundario:**
- Acumula en `$this->totalImpuestos`

---

### Método: `totales()`

**Propósito:** Calcular totales consolidados

**Cálculos:**
```
Subtotal = Total Ventas - Total Descuentos
Total = Subtotal + Total Impuestos
```

**Salida:**
```php
[
    'Subtotal'  => "$X,XXX.XX",
    'Impuestos' => "$X,XXX.XX",
    'Total'     => "$X,XXX.XX"
]
```

---

### Método: `bancos()`

**Propósito:** Listar depósitos bancarios por institución

**Salida:**
```php
[
    'Banco 1' => "$X,XXX.XX",
    'Banco 2' => "$X,XXX.XX",
    ...
]
```

---

### Método: `anticipos()`

**Propósito:** Listar anticipos de nómina por empleado

**Salida:**
```php
[
    'Empleado 1' => "$X,XXX.XX",
    'Empleado 2' => "$X,XXX.XX",
    ...
]
```

---

### Método: `gastosFondo()`

**Propósito:** Listar gastos pagados con fondo fijo

**Estructura de Tabla:**
```
| CONCEPTO | COMPRAS (Subtotal, Impuesto) | TOTAL |
```

**Salida:**
```php
[
    'tr1' => [
        ['html' => 'CONCEPTO', 'rowspan' => '2', 'class' => 'bg-thead text-center'],
        ['html' => 'COMPRAS', 'colspan' => '2', 'class' => 'bg-thead text-center'],
        ['html' => 'TOTAL', 'rowspan' => '2', 'class' => 'bg-thead text-center']
    ],
    'tr2' => [
        ['html' => 'SUBTOTAL', 'class' => 'bg-thead text-center'],
        ['html' => 'IMPUESTO', 'class' => 'bg-thead text-center']
    ],
    '0' => [
        ['html' => 'Concepto 1'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end']
    ],
    ...
]
```

---

### Método: `almacen()`

**Propósito:** Listar movimientos de almacén (entradas y salidas)

**Estructura de Tabla:**
```
| CONCEPTO | ENTRADAS (Subtotal, Impuesto) | SALIDAS |
```

**Lógica:**
1. Agrupa por categoría de insumo
2. Lista insumos individuales dentro de cada categoría
3. Muestra subtotal por categoría

**Salida:**
```php
[
    'tr1' => [...],  // Encabezado principal
    'tr2' => [...],  // Subencabezado
    '10' => [...],   // Insumo individual
    '11' => [...],   // Insumo individual
    '0'  => [...],   // Total de categoría
    ...
]
```

---

### Método: `costos()`

**Propósito:** Listar costos de producción por categoría

**Estructura de Tabla:**
```
| CONCEPTO | COMPRAS (Subtotal, Impuesto) | SALIDAS ALMACEN |
```

**Salida:**
```php
[
    'tr1' => [...],  // Encabezado principal
    'tr2' => [...],  // Subencabezado
    '0' => [
        ['html' => 'Categoría 1'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end']
    ],
    ...
]
```

---

### Método: `creditos()`

**Propósito:** Listar estado de créditos de clientes

**Estructura de Tabla:**
```
| CONCEPTO | CONSUMO | PAGOS | TOTAL |
```

**Cálculos:**
```
Total = Consumo Acumulado - Pagos Acumulados
```

**Filtro:**
- Solo muestra clientes con `Total != 0`

**Salida:**
```php
[
    'tr1' => [...],  // Encabezado
    '0' => [
        ['html' => 'Cliente 1'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end'],
        ['html' => '$X,XXX.XX', 'class' => 'text-end']
    ],
    ...
]
```

---

## 🔧 Dependencias del Sistema

### Archivos Requeridos
- `contabilidad/mdl/mdl-caratula.php` - Modelo de datos
- `conf/_Utileria.php` - Utilidades de formato

### Métodos del Modelo (MCaratula)
```php
// Ventas y Descuentos
totalVentas($params)
totalDescuentos($params)
totalImpuestos($params)
sumaVentas($params)
sumaDescuentos($params)
sumaImpuestos($params)

// Efectivo y Monedas
lsEfectivo()
valorEfectivo($params)
lsMonedasExtranjeras()
valorMonedaExtranjera($params)

// Bancos y Créditos
valorTotalBancos($params)
sumaBancos($params)
totalDeudaCredito($params)
totalPagoCredito($params)
listCreditos($params)
consumoCreditos($params)
pagoCreditos($params)

// Fondo y Retiros
retiro_venta($params)
sumatoria_gastos_fondo($params)
sumatoria_anticipos($params)
sumatoria_pago_proveedor($params)
reembolso($params)
retiro($params)
retiroEfectivo($params)
retiroVenta($params)

// Proveedores
siProveedor($params)
sumaGastoProveedor($params)
sumaGastoIVAProveedor($params)
sumaPagoProveedor($params)

// Anticipos y Gastos
sumaAnticipos($params)
sumaComprasFondo($params)

// Almacén y Costos
listAlmacen($idE, $date1, $date2)
listInsumosAlmacen($params)
subtotalAlmacen($params)
impuestosAlmacen($params)
pagosAlmacen($params)
listCostos($params)
subtotalCosto($params)
impuestosCosto($params)
```

### Métodos de Utilería
```php
format_number($value) // Formatea números con separadores de miles y decimales
```

---

## 🎨 Consideraciones de UX/UI

### Formato de Montos
- Todos los montos deben mostrarse con formato: `$X,XXX.XX`
- Usar separador de miles (coma)
- Usar dos decimales
- Incluir símbolo de moneda ($)

### Tablas Complejas
- Usar `rowspan` y `colspan` para encabezados multinivel
- Aplicar clase `bg-thead` para encabezados
- Aplicar clase `text-end` para columnas numéricas
- Aplicar clase `text-center` para encabezados

### Casos Especiales por UDN
- **UDN 6:** "Propina" se muestra como "Fichas de depósito"
- **UDN 5:** "Destajo" se muestra como "Comisión por Uber"
- **UDN 5:** No mostrar efectivo con `id != 4`

---

## 🧪 Casos de Prueba

### CP-01: Carátula Completa
**Entrada:**
- idE: 1
- date1: "2025-01-01"
- date2: "2025-01-31"

**Resultado Esperado:**
- Array con 12 claves
- Todos los montos formateados
- Diferencia de caja calculada correctamente
- Saldos de fondo, retiros y proveedores correctos

---

### CP-02: Período Sin Movimientos
**Entrada:**
- idE: 1
- date1: "2025-12-01"
- date2: "2025-12-31"

**Resultado Esperado:**
- Array con 12 claves
- Montos en cero o vacíos
- Sin errores de división por cero

---

### CP-03: UDN con Casos Especiales
**Entrada:**
- idE: 5 (UDN con comisión Uber)
- date1: "2025-01-01"
- date2: "2025-01-31"

**Resultado Esperado:**
- "Destajo" se muestra como "Comisión por Uber"
- Solo se muestra efectivo con id = 4

---

### CP-04: Créditos con Saldo Cero
**Entrada:**
- idE: 1
- date1: "2025-01-01"
- date2: "2025-01-31"
- Cliente con consumo = pagos

**Resultado Esperado:**
- Cliente NO aparece en la lista de créditos

---

## 📝 Notas de Implementación

### Variables de Clase
Las variables `$totalVentas`, `$totalDescuentos` y `$totalImpuestos` se usan como acumuladores para calcular los totales consolidados. Se inicializan en 0 y se actualizan en los métodos `ingresos()`, `descuentos()` e `impuestos()`.

### Orden de Ejecución
El método `totales()` debe ejecutarse DESPUÉS de `ingresos()`, `descuentos()` e `impuestos()` para que los acumuladores tengan los valores correctos.

### Saldos Iniciales
Los métodos `fondo()`, `retiros()` y `proveedores()` calculan saldos iniciales considerando movimientos anteriores al período consultado (usando el parámetro `'si'` en las consultas).

### Tablas Complejas
Los métodos `gastosFondo()`, `almacen()` y `costos()` retornan arrays con estructura especial para renderizar tablas con `rowspan` y `colspan`.

---

## 🚀 Mejoras Futuras

### Corto Plazo
- [ ] Agregar exportación a PDF
- [ ] Agregar exportación a Excel
- [ ] Implementar caché de resultados
- [ ] Agregar gráficas de tendencias

### Mediano Plazo
- [ ] Comparativa entre períodos
- [ ] Comparativa entre UDNs
- [ ] Alertas de diferencias significativas
- [ ] Dashboard interactivo

### Largo Plazo
- [ ] Análisis predictivo
- [ ] Integración con BI
- [ ] API REST para consultas externas
- [ ] Versión móvil

---

## 📚 Referencias

### Documentación Relacionada
- `docs/explicacion-caratula-tabs.html` - Documentación visual completa
- `contabilidad/ctrl/_Caratula.php` - Código fuente
- `contabilidad/mdl/mdl-caratula.php` - Modelo de datos

### Estándares de Código
- Framework: CoffeeSoft
- Lenguaje: PHP 7.4+
- Base de Datos: MySQL 5.7+
- Estilo de Código: PSR-12

---

**Fin de la Especificación**
