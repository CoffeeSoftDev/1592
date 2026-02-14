# Especificación: Módulo Details-Sale (Detalle de Ventas Diarias)

## 📋 Información General

**Proyecto:** Sistema ERP CoffeeSoft - Módulo de Finanzas  
**Componente:** Detalle de Ventas Diarias (Details-Sale)  
**Versión:** 1.0  
**Fecha:** 2025-01-28  
**Estado:** 📝 En Desarrollo  
**Ubicación:** `finanzas/consulta_respaldo/`

---

## 🎯 Objetivo del Sistema

El módulo **Details-Sale** es un sistema de consulta y visualización de resumen financiero diario que integra información de ventas, formas de pago, fondos de caja, movimientos y anticipos por unidad de negocio (UDN).

### Propósito Principal
Consolidar y presentar de manera estructurada:
- Ventas del día (ventas, descuentos, cortesías, impuestos)
- Formas de pago (efectivo, bancos, créditos a clientes)
- Resumen de diferencias entre ventas y pagos
- Acceso rápido a funciones de captura y carga de archivos

---

## 👥 Usuarios del Sistema

### Usuario Principal
- **Rol:** Contador / Capturista Financiero
- **Necesidad:** Consultar el resumen financiero diario por UDN
- **Frecuencia de uso:** Diario

### Usuarios Secundarios
- **Gerentes de UDN:** Consulta de estado financiero de su unidad
- **Dirección General:** Análisis de ventas por unidad de negocio

---

## 🗄️ Estructura de Base de Datos

### Base de Datos: `rfwsmqex_gvsl_finanzas3`

### Tablas Principales

| Tabla | Descripción | Campos Clave |
|-------|-------------|--------------|
| `daily_closure` | Cierre diario principal | `id`, `udn_id`, `created_at` |
| `detail_sale_category` | Ventas por categoría | `daily_closure_id`, `sale`, `discount`, `courtesy`, `sale_category_id` |
| `sale_category` | Categorías de venta | `id`, `name`, `udn_id` |
| `detail_sale_category_tax` | Impuestos por venta | `detail_sale_category_id`, `sale_tax`, `discount_tax`, `courtesy_tax`, `tax_id` |
| `tax` | Tipos de impuesto | `id`, `name`, `percentage` (IVA 8%, IEPS 8%, HOSPEDAJE 2%) |
| `detail_cash_concept` | Conceptos de efectivo | `daily_closure_id`, `cash_concept_id`, `amount` |
| `cash_concept` | Definición de conceptos | `id`, `name`, `operation_type` (suma/resta) |
| `detail_foreign_currency` | Moneda extranjera | `daily_closure_id`, `foreing_currency_id`, `amount`, `amount_mxn` |
| `foreign_currency` | Tipos de moneda | `id`, `name`, `exchange_rate` |
| `detail_bank_account` | Depósitos bancarios | `daily_closure_id`, `bank_account_id`, `amount` |
| `bank_account` | Cuentas bancarias | `id`, `bank_id`, `udn_id` |
| `detail_credit_customer` | Créditos de clientes | `daily_closure_id`, `customer_id`, `amount` |
| `customer` | Clientes | `id`, `name`, `udn_id` |

---

## 📊 Historias de Usuario

### HU-01: Visualizar Resumen de Ventas del Día
**Como** capturista financiero  
**Quiero** ver el resumen de ventas del día por UDN  
**Para** conocer el total de ingresos y sus componentes

**Criterios de Aceptación:**
- [ ] El sistema debe mostrar una card "Ventas del día" con:
  - Ventas (suma de `detail_sale_category.sale`)
  - Descuentos y Cortesías (suma de `discount` + `courtesy`)
  - Impuestos (suma de `detail_sale_category_tax.sale_tax`)
  - Total de venta (Ventas - Descuentos + Impuestos)
- [ ] Los montos deben formatearse con separadores de miles y decimales
- [ ] Debe filtrar por UDN y fecha seleccionada

---

### HU-02: Visualizar Formas de Pago
**Como** capturista financiero  
**Quiero** ver el desglose de formas de pago del día  
**Para** validar que los pagos cuadren con las ventas

**Criterios de Aceptación:**
- [ ] El sistema debe mostrar una card "Formas de pago" con:
  - Efectivo (suma de `detail_cash_concept.amount` donde `cash_concept.name` = 'Efectivo')
  - Bancos (suma de `detail_bank_account.amount`)
  - Créditos a clientes (suma de `detail_credit_customer.amount`)
  - Total pagado (suma de todos los conceptos)
- [ ] Los conceptos de efectivo deben obtenerse dinámicamente de `cash_concept`
- [ ] Debe considerar el `operation_type` (suma/resta) de cada concepto

---

### HU-03: Visualizar Diferencia entre Ventas y Pagos
**Como** capturista financiero  
**Quiero** ver la diferencia entre el total de ventas y el total pagado  
**Para** identificar descuadres en el cierre diario

**Criterios de Aceptación:**
- [ ] El sistema debe mostrar 3 InfoCards:
  - Total de venta (verde)
  - Total pagado (azul)
  - Diferencia (rojo si negativo, verde si positivo)
- [ ] Diferencia = Total de venta - Total pagado
- [ ] Los colores deben indicar visualmente el estado

---

### HU-04: Filtrar por UDN y Fecha
**Como** capturista financiero  
**Quiero** filtrar los datos por unidad de negocio y fecha  
**Para** consultar información específica de cada día y UDN

**Criterios de Aceptación:**
- [ ] El sistema debe mostrar un filterBar con:
  - Selector de UDN (dropdown con UDNs activas)
  - Selector de fecha (datepicker)
- [ ] Al cambiar cualquier filtro, debe actualizar automáticamente los datos
- [ ] Debe recordar la última UDN seleccionada

---

### HU-05: Acceder a Funciones de Captura
**Como** capturista financiero  
**Quiero** tener acceso rápido a las funciones de captura  
**Para** registrar información del día de manera eficiente

**Criterios de Aceptación:**
- [ ] El sistema debe mostrar botones en el filterBar:
  - "Concentrado de Ventas" (toggle para vista alternativa)
  - "Subir archivos de ventas" (abre modal de carga)
  - "Registrar corte del día" (abre formulario de captura)
- [ ] Los botones deben estar visibles y accesibles

---

## 🏗️ Arquitectura del Sistema

### Estructura de Archivos

```
finanzas/consulta_respaldo/
├── js/
│   └── details-sale.js          # Frontend (extiende Templates)
├── ctrl/
│   └── ctrl-details-sale.php    # Controlador
├── mdl/
│   └── mdl-details-sale.php     # Modelo
└── details-sale.php             # Vista (index)
```

### Estructura de Clases

```
DetailsSale (Frontend JS)
├── Constructor
│   ├── this._link (API endpoint)
│   └── this.PROJECT_NAME = "DetailsSale"
│
└── Métodos
    ├── render()
    ├── layout()
    ├── filterBar()
    ├── lsVentas()
    ├── createVentasCard()
    ├── createPagosCard()
    └── createInfoCards()
```

### Flujo de Datos

```
Usuario → FilterBar → lsVentas() → AJAX → ctrl::showSale() → mdl::getVentas() → BD
                                                           → mdl::getDescuentos()
                                                           → mdl::getImpuestos()
                                                           → mdl::getConceptosVenta()
                                                           → mdl::getBancos()
                                                           → mdl::getCreditos()
                                        ↓
                                   Consolidación
                                        ↓
                                  JSON Response
                                        ↓
                                   Frontend (Cards)
```

---

## 📐 Especificaciones Técnicas

### Método Principal: `showSale()` (CTRL)

**Entrada (POST):**
- `fi` (string): Fecha inicial (YYYY-MM-DD)
- `ff` (string): Fecha final (YYYY-MM-DD)
- `udn` (int): ID de la Unidad de Negocio

**Salida:**
```php
[
    'status'  => 200,
    'message' => 'Información obtenida correctamente',
    'data'    => [
        'ventas' => [
            'ventas'     => float,  // Suma de ventas
            'descuentos' => float,  // Suma de descuentos + cortesías
            'impuestos'  => float,  // Suma de impuestos
            'total'      => float   // Ventas - Descuentos + Impuestos
        ],
        'pagos' => [
            'conceptos' => array,   // ['Efectivo' => float, 'Propina' => float, ...]
            'bancos'    => float,   // Total depósitos bancarios
            'creditos'  => float,   // Total créditos clientes
            'total'     => float    // Suma de todos los pagos
        ],
        'diferencia' => float       // Total ventas - Total pagos
    ]
]
```

---

### Métodos del Modelo (MDL)

| Método | Descripción | Parámetros |
|--------|-------------|------------|
| `getVentas($array)` | Suma de ventas del período | `[$fi, $ff, $udn]` |
| `getDescuentos($array)` | Suma de descuentos y cortesías | `[$fi, $ff, $udn]` |
| `getImpuestos($array)` | Suma de impuestos | `[$fi, $ff, $udn]` |
| `getConceptosVenta($array)` | Conceptos de efectivo agrupados | `[$fi, $ff, $udn]` |
| `getBancos($array)` | Total depósitos bancarios | `[$fi, $ff, $udn]` |
| `getCreditos($array)` | Total créditos de clientes | `[$fi, $ff, $udn]` |
| `lsUDN()` | Lista de UDNs activas | `[]` |

---

### Cálculos de Negocio

```
// Ventas del día
Total Venta = Ventas - Descuentos + Impuestos

// Formas de pago
Total Pagado = Σ(Conceptos Efectivo) + Bancos + Créditos

// Diferencia
Diferencia = Total Venta - Total Pagado
```

---

## 🎨 Especificaciones de UI/UX

### Layout Principal

```
┌─────────────────────────────────────────────────────────────┐
│ Tab: Ventas                                                 │
├─────────────────────────────────────────────────────────────┤
│ FilterBar:                                                  │
│ [Toggle Concentrado] [Subir archivos] [Registrar corte]     │
│ [Selector UDN] [Fecha de captura: ___________]              │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────┐  ┌─────────────────────┐          │
│  │ Ventas del día      │  │ Formas de pago      │          │
│  │                     │  │                     │          │
│  │ Ventas:      $X,XXX │  │ Efectivo:    $X,XXX │          │
│  │ Descuentos:  $X,XXX │  │ Bancos:      $X,XXX │          │
│  │ Impuestos:   $X,XXX │  │ Créditos:    $X,XXX │          │
│  │ ─────────────────── │  │ ─────────────────── │          │
│  │ Total:       $X,XXX │  │ Total:       $X,XXX │          │
│  └─────────────────────┘  └─────────────────────┘          │
│                                                             │
│  ┌─────────────┐ ┌─────────────┐ ┌─────────────┐           │
│  │ Total Venta │ │ Total Pagado│ │ Diferencia  │           │
│  │   $X,XXX    │ │   $X,XXX    │ │   $X,XXX    │           │
│  │   (verde)   │ │   (azul)    │ │ (rojo/verde)│           │
│  └─────────────┘ └─────────────┘ └─────────────┘           │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### Paleta de Colores

| Elemento | Color | Clase TailwindCSS |
|----------|-------|-------------------|
| Card Ventas | Blanco con borde | `bg-white border rounded-lg` |
| Card Pagos | Blanco con borde | `bg-white border rounded-lg` |
| InfoCard Total Venta | Verde | `bg-green-50 text-green-800` |
| InfoCard Total Pagado | Azul | `bg-blue-50 text-blue-800` |
| InfoCard Diferencia (+) | Verde | `bg-green-50 text-green-800` |
| InfoCard Diferencia (-) | Rojo | `bg-red-50 text-red-800` |

### Formato de Montos
- Todos los montos con formato: `$X,XXX.XX`
- Usar `formatPrice()` o `evaluar()` para formateo
- Separador de miles: coma
- Decimales: 2

---

## 🧪 Casos de Prueba

### CP-01: Consulta de Ventas del Día
**Entrada:**
- UDN: 5
- Fecha: 2025-01-28

**Resultado Esperado:**
- Card "Ventas del día" con datos correctos
- Card "Formas de pago" con conceptos dinámicos
- InfoCards con totales y diferencia calculada

---

### CP-02: Día Sin Movimientos
**Entrada:**
- UDN: 5
- Fecha: 2025-12-31 (fecha futura)

**Resultado Esperado:**
- Todos los montos en $0.00
- Diferencia = $0.00
- Sin errores de visualización

---

### CP-03: Diferencia Negativa
**Entrada:**
- UDN con Total Pagado > Total Venta

**Resultado Esperado:**
- InfoCard Diferencia en color rojo
- Valor negativo mostrado correctamente

---

## 📝 Notas de Implementación

### Nomenclatura de Funciones

**Controlador (CTRL):**
- `init()` - Inicializa filtros (UDN)
- `showSale()` - Obtiene datos consolidados de ventas

**Modelo (MDL):**
- `getVentas()` - Consulta ventas
- `getDescuentos()` - Consulta descuentos
- `getImpuestos()` - Consulta impuestos
- `getConceptosVenta()` - Consulta conceptos de efectivo
- `getBancos()` - Consulta depósitos bancarios
- `getCreditos()` - Consulta créditos de clientes
- `lsUDN()` - Lista UDNs

### Reglas de Código

1. **NO usar `??` ni `isset()` con `$_POST`** - Asignación directa
2. **Usar `_Read()` para todas las consultas SELECT** en el modelo
3. **Validar resultados** antes de iterar con `is_array() && !empty()`
4. **Separar consultas** por entidad/concepto (no consultas monolíticas)

---

## 🚀 Mejoras Futuras

### Corto Plazo
- [ ] Agregar exportación a PDF
- [ ] Agregar exportación a Excel
- [ ] Implementar gráfica de tendencias

### Mediano Plazo
- [ ] Comparativa entre días
- [ ] Comparativa entre UDNs
- [ ] Alertas de diferencias significativas

---

## 📚 Referencias

### Documentación Relacionada
- `.kiro/specs/caratula-financiera/requirements.md` - Módulo de referencia
- `finanzas/consulta_respaldo/js/caratula-venta.js` - Pivote de código
- `finanzas/sql/rfwsmqex_gvsl_finanzas3.sql` - Esquema de BD

### Estándares de Código
- Framework: CoffeeSoft
- Lenguaje: PHP 7.4+ / JavaScript ES6+
- Base de Datos: MySQL 5.7+
- Estilos: TailwindCSS

---

**Fin de la Especificación**
