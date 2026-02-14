# Design Document

## Overview

El módulo **Agregar Ventas (Add Sales)** es un componente crítico del sistema de Contabilidad que permite registrar las ventas diarias y formas de pago dentro del flujo de cierre diario (`daily_closure`). Este módulo se integra con el sistema existente de consulta de ventas y proporciona una interfaz de captura estructurada en dos tarjetas principales:

1. **Card 1: Ventas del Día** - Captura ventas por categoría con cálculo automático de impuestos
2. **Card 2: Formas de Pago** - Registra conceptos de efectivo, monedas extranjeras, bancos y créditos

El módulo está diseñado para:
- Prevenir duplicados mediante validación de `daily_closure` existente por fecha/UDN
- Calcular impuestos automáticamente basándose en relaciones `sale_category_tax`
- Mostrar diferencias en tiempo real entre ventas y pagos
- Validar fechas habilitadas mediante `monthly_module_lock`
- Persistir datos en múltiples tablas de detalle relacionadas con `daily_closure`

## Architecture

### Patrón de Diseño

El módulo sigue el patrón **MVC (Model-View-Controller)** del framework CoffeeSoft:

- **Model (MDL)**: `mdl-add-sales.php` - Gestiona operaciones CRUD con la base de datos
- **Controller (CTRL)**: `ctrl-add-sales.php` - Procesa lógica de negocio y validaciones
- **View (JS)**: `add-sales.js` - Clase `AddSales` que extiende `Templates` de CoffeeSoft

### Flujo de Datos

```
Usuario → AddSales (JS) → AJAX → ctrl-add-sales.php → mdl-add-sales.php → MySQL
                ↓                                                              ↓
         Actualización UI ← JSON Response ← Validaciones ← Consultas SQL
```

### Layout de Dos Tarjetas

El módulo utiliza un layout de dos columnas responsivo:

```
┌─────────────────────────────────────────────────────┐
│  FilterBar (UDN, Fecha, Botón Soft Restaurant)      │
├──────────────────────┬──────────────────────────────┤
│  Card 1: Ventas      │  Card 2: Formas de Pago      │
│  - Categorías        │  - Conceptos Efectivo        │
│  - Descuentos        │  - Monedas Extranjeras       │
│  - Cortesías         │  - Bancos                    │
│  - Impuestos (auto)  │  - Crédito Clientes          │
│  - SummaryCard       │  - SummaryCard               │
└──────────────────────┴──────────────────────────────┘
│  SummaryCard Global (Total Venta, Total Pagado, Diferencia) │
└─────────────────────────────────────────────────────┘
```


## Components and Interfaces

### AddSales Class (Frontend)

La clase `AddSales` extiende `Templates` del framework CoffeeSoft y gestiona toda la lógica de interfaz y comunicación con el backend.

**Estructura de la clase:**

```javascript
class AddSales extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "AddSales";
        this.dailyClosureId = null;
        this.saleCategories = [];
        this.cashConcepts = [];
        this.foreignCurrencies = [];
        this.bankAccounts = [];
        this.taxes = [];
    }
}
```

**Métodos principales:**

#### `init()`
- Carga datos iniciales desde el backend (`opc: "init"`)
- Obtiene: categorías de venta, conceptos de efectivo, monedas extranjeras, cuentas bancarias, impuestos
- Almacena datos en propiedades de la clase

#### `render()`
- Ejecuta secuencia de renderizado: `layout()` → `filterBar()` → `loadExistingData()`
- Orquesta la construcción completa de la interfaz

#### `layout()`
- Usa `primaryLayout()` para crear estructura de dos columnas
- Genera contenedores: `filterBarAddSales`, `containerAddSales`
- Crea dos tarjetas principales: `ventasCard` y `pagosCard`

#### `filterBar()`
- Usa `createfilterBar()` con componentes:
  - Select de UDN (si usuario tiene permisos)
  - Input de fecha con validación de `monthly_module_lock`
  - Botón "Soft Restaurant" (naranja) que abre nueva pestaña

#### `createVentasCard()`
- Genera Card 1 con título "Ventas del Día"
- Itera sobre `this.saleCategories` para crear inputs numéricos
- Crea campos separados para Descuentos y Cortesías
- Agrega SummaryCard con totales calculados
- Vincula eventos `onchange` a `calculateTaxes()` y `calculateDifference()`

#### `createPagosCard()`
- Genera Card 2 con título "Formas de Pago"
- Sección 1: Conceptos de Efectivo (itera sobre `this.cashConcepts`)
- Sección 2: Monedas Extranjeras (itera sobre `this.foreignCurrencies`)
  - Calcula automáticamente MXN usando tipo de cambio
- Sección 3: Cuentas Bancarias (itera sobre `this.bankAccounts`)
- Sección 4: Crédito a Clientes (solo lectura desde `detail_credit_customer`)
- Agrega SummaryCard con totales de pagos
- Vincula eventos `onchange` a `calculateDifference()`

#### `calculateTaxes()`
- Obtiene valores de ventas por categoría desde inputs del formulario
- **Para cada categoría con valor > 0:**
  1. Consulta `this.saleCategoryTaxCache` (caché de impuestos por categoría)
  2. Si no está en caché, llama a `getSaleCategoryTaxes(categoryId)` para obtener impuestos desde backend
  3. Itera sobre cada impuesto asociado a la categoría
  4. Calcula: `tax_amount = (sale * tax.percentage) / 100`
  5. Suma todos los impuestos de la categoría
- **Calcula impuestos para descuentos y cortesías:**
  - Usa los mismos porcentajes de impuestos de cada categoría
  - Calcula: `discount_tax = (discount * tax.percentage) / 100`
  - Calcula: `courtesy_tax = (courtesy * tax.percentage) / 100`
- Actualiza campos de impuestos en la interfaz
- Actualiza SummaryCard con totales calculados
- Retorna objeto con totales: `{ ventas, descuentos, cortesias, impuestos, total }`
- **Manejo de errores:**
  - Si una categoría no tiene impuestos asociados, registra advertencia en consola
  - Continúa procesamiento sin impuestos para esa categoría
  - No bloquea la operación de guardado

#### `getSaleCategoryTaxes(categoryId)`
- **Método helper para consultar impuestos de una categoría**
- Verifica si los impuestos ya están en `this.saleCategoryTaxCache[categoryId]`
- Si está en caché, retorna inmediatamente (optimización de rendimiento)
- Si no está en caché:
  1. Hace petición AJAX con `opc: "getSaleCategoryTaxes"` y `category_id`
  2. Backend consulta: `sale_category_tax` JOIN `tax` WHERE `sale_category_id = ?`
  3. Retorna array de objetos: `[{ tax_id, name, percentage }, ...]`
  4. Almacena resultado en caché para futuras consultas
- **Ejemplo de respuesta:**
  ```javascript
  // Para categoría "Hospedaje" (id=16):
  [
    { tax_id: 1, name: "IVA", percentage: 8.00 },
    { tax_id: 3, name: "HOSPEDAJE", percentage: 2.00 }
  ]
  ```
- Retorna array vacío si no hay impuestos asociados
- Maneja errores de red retornando array vacío y registrando error en consola

#### `calculateDifference()`
- Obtiene `totalVenta` de Card 1
- Obtiene `totalPagado` de Card 2
- Calcula: `diferencia = totalVenta - totalPagado`
- Aplica colores:
  - Verde si `diferencia >= 0`
  - Rojo si `diferencia < 0`
- Actualiza SummaryCard global

#### `saveSales()`
- Valida que exista `daily_closure_id` (crea uno si no existe)
- Recopila datos de todos los campos de ventas
- Estructura payload para `opc: "addSales"` o `opc: "editSales"`
- Envía petición AJAX con `useFetch()`
- Maneja respuesta:
  - Status 200: Muestra mensaje de éxito con `alert()`
  - Status != 200: Muestra mensaje de error

#### `savePayments()`
- Valida que exista `daily_closure_id`
- Recopila datos de conceptos de efectivo, monedas, bancos
- Estructura payload para `opc: "addPayments"` o `opc: "editPayments"`
- Envía petición AJAX con `useFetch()`
- Maneja respuesta similar a `saveSales()`

#### `loadExistingData()`
- Consulta si existe `daily_closure` para fecha/UDN seleccionadas
- Si existe:
  - Almacena `daily_closure_id` en `this.dailyClosureId`
  - Carga datos de `detail_sale_category` en campos de ventas
  - Carga datos de `detail_sale_category_tax` para impuestos
  - Carga datos de `detail_cash_concept` en conceptos de efectivo
  - Carga datos de `detail_foreing_currency` en monedas extranjeras
  - Carga datos de `detail_bank_account` en cuentas bancarias
  - Carga datos de `detail_credit_customer` en crédito (solo lectura)
- Si no existe:
  - Inicializa todos los campos en cero
  - Prepara interfaz para nuevo registro

### SummaryCard Component

Componente reutilizable que muestra totales en formato de tarjeta.

**Estructura:**

```javascript
summaryCard(options) {
    const defaults = {
        parent: "root",
        id: "summaryCard",
        title: "Resumen",
        items: [],  // [{ label, value, color }]
        total: { label, value, color }
    };
    const opts = Object.assign({}, defaults, options);
    
    // Genera HTML con TailwindCSS
    // Itera sobre items para crear filas
    // Agrega fila de total con estilo destacado
}
```

**Uso en AddSales:**

- Card 1: Muestra Ventas, Descuentos, Cortesías, Impuestos, Total de Venta
- Card 2: Muestra Efectivo, Moneda Extranjera, Bancos, Crédito, Total Pagado
- Global: Muestra Total de Venta, Total Pagado, Diferencia

### Integración con Patrones Existentes

El módulo sigue los patrones establecidos en:

**`details-sale.js`:**
- Usa `summaryCard()` para mostrar totales
- Estructura de dos tarjetas (Ventas y Pagos)
- Método `createVentasCard()` y `createPagosCard()`
- Cálculo de diferencia con colores dinámicos

**`caratula-venta.js`:**
- Usa `createLayout()` para estructura de contenedores
- Método `showLoadingState()` durante carga de datos
- Formato de fechas con `formatSpanishDate()`
- Uso de `infoCard()` para KPIs

**Diferencias clave:**
- AddSales es **editable** (inputs), mientras que details-sale y caratula-venta son **solo lectura**
- AddSales incluye **validación de fecha** mediante `monthly_module_lock`
- AddSales tiene **cálculo automático de impuestos** en tiempo real
- AddSales persiste datos en **múltiples tablas de detalle**


## Data Models

### Database Tables and Relationships

#### Central Table: `daily_closure`

Tabla principal que agrupa todas las ventas y pagos de una fecha específica por UDN.

**Campos:**
- `id` (PK) - Identificador único del cierre diario
- `udn_id` (FK → `udn.idUDN`) - Unidad de negocio
- `employee_id` (FK → `empleados.idEmpleado`) - Empleado responsable
- `total_sale_without_tax` - Total de ventas sin impuestos
- `total_sale` - Total de ventas con impuestos
- `subtotal` - Subtotal de ventas
- `tax` - Total de impuestos
- `cash` - Total de efectivo
- `bank` - Total de bancos
- `credit_consumer` - Total de crédito a clientes
- `credit_payment` - Total de pagos de crédito
- `total_payment` - Total de pagos recibidos
- `difference` - Diferencia entre ventas y pagos
- `created_at` - Fecha del cierre
- `turn` - Turno (Matutino, Vespertino, Nocturno)

**Relaciones:**
- Uno a muchos con `detail_sale_category`
- Uno a muchos con `detail_cash_concept`
- Uno a muchos con `detail_foreing_currency`
- Uno a muchos con `detail_bank_account`
- Uno a muchos con `detail_credit_customer`

#### Ventas: `detail_sale_category`

Detalle de ventas por categoría vinculadas a un cierre diario.

**Campos:**
- `id` (PK)
- `sale` - Monto de venta (sin impuestos)
- `net_sale` - Venta neta (calculada)
- `discount` - Descuento aplicado
- `courtesy` - Cortesía aplicada
- `daily_closure_id` (FK → `daily_closure.id`)
- `sale_category_id` (FK → `sale_category.id`)

**Relaciones:**
- Muchos a uno con `daily_closure`
- Muchos a uno con `sale_category`
- Uno a muchos con `detail_sale_category_tax`

#### Categorías de Venta: `sale_category`

Categorías de venta configuradas por UDN.

**Campos:**
- `id` (PK)
- `udn_id` (FK → `udn.idUDN`)
- `name` - Nombre de la categoría (Cortes, Bebidas, Guarniciones, etc.)
- `discount` - Permite descuentos (0/1)
- `courtesy` - Permite cortesías (0/1)
- `active` - Estado activo (0/1)

**Relaciones:**
- Muchos a uno con `udn`
- Uno a muchos con `detail_sale_category`
- Muchos a muchos con `tax` (via `sale_category_tax`)

#### Impuestos: `tax`

Tipos de impuestos aplicables.

**Campos:**
- `id` (PK)
- `name` - Nombre del impuesto (IVA, IEPS, HOSPEDAJE)
- `percentage` - Porcentaje del impuesto (8.00, 8.00, 2.00)
- `active` - Estado activo (0/1)

**Datos actuales:**
- IVA: 8%
- IEPS: 8%
- HOSPEDAJE: 2%

#### Relación Categoría-Impuesto: `sale_category_tax`

Tabla intermedia que relaciona categorías de venta con impuestos (many-to-many).

**Campos:**
- `id` (PK)
- `sale_category_id` (FK → `sale_category.id`)
- `tax_id` (FK → `tax.id`)

**Ejemplo:**
- Categoría "Hospedaje" (id=16) tiene dos impuestos:
  - IVA 8% (tax_id=1)
  - HOSPEDAJE 2% (tax_id=3)

#### Detalle de Impuestos: `detail_sale_category_tax`

Detalle de impuestos calculados por venta.

**Campos:**
- `id` (PK)
- `sale_tax` - Impuesto sobre venta
- `discount_tax` - Impuesto sobre descuento
- `courtesy_tax` - Impuesto sobre cortesía
- `detail_sale_category_id` (FK → `detail_sale_category.id`)
- `sale_category_tax_id` (FK → `sale_category_tax.id`)

#### Conceptos de Efectivo: `cash_concept`

Conceptos de efectivo configurados por UDN.

**Campos:**
- `id` (PK)
- `udn_id` (FK → `udn.idUDN`)
- `name` - Nombre del concepto (Efectivo, Propinas, Destajo, Vales)
- `operation_type` - Tipo de operación ('suma' o 'resta')
- `active` - Estado activo (0/1)

**Nota:** `operation_type` determina si el concepto suma o resta del total de efectivo.

#### Detalle de Conceptos de Efectivo: `detail_cash_concept`

Detalle de conceptos de efectivo registrados.

**Campos:**
- `id` (PK)
- `daily_closure_id` (FK → `daily_closure.id`)
- `cash_concept_id` (FK → `cash_concept.id`)
- `amount` - Monto del concepto

#### Monedas Extranjeras: `foreing_currency`

**Nota:** El nombre de la tabla tiene un typo intencional (`foreing` en lugar de `foreign`). Este typo debe respetarse en todo el código.

**Campos:**
- `id` (PK)
- `name` - Nombre de la moneda (Dolar, Quetzal)
- `symbol` - Símbolo (USD, QTZ)
- `exchange_rate` - Tipo de cambio a MXN
- `active` - Estado activo (0/1)

#### Detalle de Monedas Extranjeras: `detail_foreing_currency`

**Campos:**
- `id` (PK)
- `foreing_currency_id` (FK → `foreing_currency.id`)
- `exchange_rate` - Tipo de cambio usado en la transacción
- `amount` - Monto en moneda extranjera
- `amount_mxn` - Monto equivalente en MXN (calculado)
- `daily_closure_id` (FK → `daily_closure.id`)

**Cálculo:** `amount_mxn = amount * exchange_rate`

#### Bancos: `bank`

Catálogo de bancos.

**Campos:**
- `id` (PK)
- `name` - Nombre del banco (BBVA, BANORTE, SANTANDER)
- `active` - Estado activo (0/1)

#### Cuentas Bancarias: `bank_account`

Cuentas bancarias configuradas por UDN.

**Campos:**
- `id` (PK)
- `name` - Nombre de la cuenta
- `account` - Últimos 4 dígitos de la cuenta
- `udn_id` (FK → `udn.idUDN`)
- `bank_id` (FK → `bank.id`)
- `active` - Estado activo (0/1)

#### Detalle de Cuentas Bancarias: `detail_bank_account`

**Campos:**
- `id` (PK)
- `daily_closure_id` (FK → `daily_closure.id`)
- `bank_account_id` (FK → `bank_account.id`)
- `amount` - Monto depositado

#### Clientes: `customer`

Catálogo de clientes por UDN.

**Campos:**
- `id` (PK)
- `udn_id` (FK → `udn.idUDN`)
- `name` - Nombre del cliente
- `active` - Estado activo (0/1)

#### Detalle de Crédito a Clientes: `detail_credit_customer`

**Campos:**
- `id` (PK)
- `amount` - Monto del crédito/pago
- `created_at` - Fecha de creación
- `description` - Descripción
- `reason` - Razón del movimiento
- `active` - Estado activo
- `customer_id` (FK → `customer.id`)
- `movement_type_id` (FK → `movement_type.id`)
- `method_pay_id` (FK → `method_pay_customer.id`)
- `daily_closure_id` (FK → `daily_closure.id`)

**Nota:** Esta tabla es **solo lectura** en el módulo AddSales. Los créditos se gestionan en otro módulo.

#### Bloqueo de Módulos: `monthly_module_lock`

Configuración de horarios de bloqueo por mes.

**Campos:**
- `id` (PK)
- `month` - Número del mes (1-12)
- `lock_time` - Hora de bloqueo (formato TIME)

**Lógica de validación:**
- Si la hora actual > `lock_time` del mes actual, la fecha está bloqueada
- Si la hora actual <= `lock_time`, la fecha está habilitada

### Data Flow Diagrams

#### Flujo de Carga de Datos Existentes

```
Usuario selecciona fecha/UDN
         ↓
loadExistingData()
         ↓
AJAX: opc="getSales", fecha, udn
         ↓
CTRL: getSales()
         ↓
MDL: getSalesById() → Consulta daily_closure
         ↓
¿Existe daily_closure?
    ├─ SÍ → Cargar detalles:
    │        - detail_sale_category
    │        - detail_sale_category_tax
    │        - detail_cash_concept
    │        - detail_foreing_currency
    │        - detail_bank_account
    │        - detail_credit_customer
    │        ↓
    │   Llenar campos de interfaz
    │
    └─ NO → Inicializar campos en cero
```

#### Flujo de Guardado de Ventas

```
Usuario ingresa ventas → calculateTaxes() → Actualiza UI
         ↓
Usuario hace clic en "Guardar"
         ↓
saveSales()
         ↓
Validar campos requeridos
         ↓
¿Existe daily_closure_id?
    ├─ NO → Crear daily_closure
    │        ↓
    │   AJAX: opc="createDailyClosure"
    │        ↓
    │   Almacenar daily_closure_id
    │
    └─ SÍ → Continuar
         ↓
AJAX: opc="addSales" o "editSales"
         ↓
CTRL: addSales() o editSales()
         ↓
MDL: createSales() o updateSales()
         ↓
Insertar/Actualizar:
  - detail_sale_category (por cada categoría)
  - detail_sale_category_tax (por cada impuesto)
         ↓
Actualizar totales en daily_closure
         ↓
Respuesta: status 200 / 500
         ↓
Mostrar mensaje de éxito/error
```

#### Flujo de Guardado de Formas de Pago

```
Usuario ingresa pagos → calculateDifference() → Actualiza UI
         ↓
Usuario hace clic en "Guardar"
         ↓
savePayments()
         ↓
Validar daily_closure_id existe
         ↓
AJAX: opc="addPayments" o "editPayments"
         ↓
CTRL: addPayments() o editPayments()
         ↓
MDL: createPayments() o updatePayments()
         ↓
Insertar/Actualizar:
  - detail_cash_concept (por cada concepto)
  - detail_foreing_currency (por cada moneda)
  - detail_bank_account (por cada cuenta)
         ↓
Actualizar totales en daily_closure
         ↓
Respuesta: status 200 / 500
         ↓
Mostrar mensaje de éxito/error
```

#### Flujo de Cálculo de Impuestos

```
Usuario modifica valor de venta
         ↓
Evento onchange
         ↓
calculateTaxes()
         ↓
Por cada categoría con valor > 0:
    ↓
    Consultar sale_category_tax
    ↓
    Obtener tax_id(s) asociados
    ↓
    Por cada tax_id:
        ↓
        Obtener percentage de tabla tax
        ↓
        Calcular: impuesto = (venta * percentage) / 100
        ↓
        Sumar a total de impuestos
    ↓
Actualizar campo de impuestos en UI
         ↓
calculateDifference()
         ↓
Actualizar SummaryCard global
```

### Field Mappings

#### Mapeo: Interfaz → `detail_sale_category`

| Campo UI | Campo DB | Tipo | Cálculo |
|----------|----------|------|---------|
| Input categoría | `sale` | decimal(12,2) | Valor ingresado |
| - | `net_sale` | decimal(12,2) | `sale - discount - courtesy` |
| Input descuento | `discount` | decimal(12,2) | Valor ingresado |
| Input cortesía | `courtesy` | decimal(12,2) | Valor ingresado |
| - | `daily_closure_id` | int(11) | FK desde `daily_closure` |
| - | `sale_category_id` | int(11) | FK desde `sale_category` |

#### Mapeo: Interfaz → `detail_sale_category_tax`

| Campo UI | Campo DB | Tipo | Cálculo |
|----------|----------|------|---------|
| Impuesto calculado | `sale_tax` | decimal(12,2) | `(sale * tax.percentage) / 100` |
| Impuesto descuento | `discount_tax` | decimal(12,2) | `(discount * tax.percentage) / 100` |
| Impuesto cortesía | `courtesy_tax` | decimal(12,2) | `(courtesy * tax.percentage) / 100` |
| - | `detail_sale_category_id` | int(11) | FK desde `detail_sale_category` |
| - | `sale_category_tax_id` | int(11) | FK desde `sale_category_tax` |

#### Mapeo: Interfaz → `detail_cash_concept`

| Campo UI | Campo DB | Tipo | Cálculo |
|----------|----------|------|---------|
| Input concepto | `amount` | decimal(12,2) | Valor ingresado |
| - | `daily_closure_id` | int(11) | FK desde `daily_closure` |
| - | `cash_concept_id` | int(11) | FK desde `cash_concept` |

#### Mapeo: Interfaz → `detail_foreing_currency`

| Campo UI | Campo DB | Tipo | Cálculo |
|----------|----------|------|---------|
| Input monto moneda | `amount` | decimal(12,2) | Valor ingresado |
| Tipo de cambio | `exchange_rate` | decimal(12,2) | Desde `foreing_currency.exchange_rate` |
| Calculado automático | `amount_mxn` | decimal(12,2) | `amount * exchange_rate` |
| - | `foreing_currency_id` | int(11) | FK desde `foreing_currency` |
| - | `daily_closure_id` | int(11) | FK desde `daily_closure` |

#### Mapeo: Interfaz → `detail_bank_account`

| Campo UI | Campo DB | Tipo | Cálculo |
|----------|----------|------|---------|
| Input cuenta bancaria | `amount` | decimal(12,2) | Valor ingresado |
| - | `daily_closure_id` | int(11) | FK desde `daily_closure` |
| - | `bank_account_id` | int(11) | FK desde `bank_account` |


## Correctness Properties

Las siguientes propiedades formales garantizan el comportamiento correcto del módulo AddSales. Cada propiedad ha sido derivada del análisis de requisitos y validada mediante el proceso de prework.

### Tax Calculation Properties

**Property 1: Tax Calculation Accuracy**
For any sale category with associated taxes, the calculated tax amount SHALL equal the sum of (sale amount × tax percentage / 100) for each associated tax.

**Validates: Requirements 3.2, 3.3**

**Property 2: Multiple Tax Summation**
For any sale category with multiple associated taxes (e.g., IVA 8% + HOSPEDAJE 2%), the total tax SHALL equal the sum of all individual tax calculations.

**Validates: Requirements 3.3**

**Property 3: Discount Tax Calculation**
For any discount amount entered, the discount tax SHALL be calculated separately using the same tax percentages as the sale category.

**Validates: Requirements 3.4**

**Property 4: Courtesy Tax Calculation**
For any courtesy amount entered, the courtesy tax SHALL be calculated separately using the same tax percentages as the sale category.

**Validates: Requirements 3.4**

**Property 5: Real-time Tax Update**
For any change in sale, discount, or courtesy values, the tax amounts SHALL be recalculated and displayed within 100ms.

**Validates: Requirements 3.5, 1.8**

### Validation Properties

**Property 6: Date Validation**
For any date selected, if the current time is greater than the `lock_time` configured in `monthly_module_lock` for that month, the save button SHALL be disabled.

**Validates: Requirements 4.1, 4.2, 4.3**

**Property 7: Date Enabled State**
For any date selected, if the current time is less than or equal to the `lock_time`, the save button SHALL be enabled and the date SHALL be visually marked as enabled.

**Validates: Requirements 4.3, 4.4**

**Property 8: Duplicate Prevention**
For any combination of date and UDN, if a `daily_closure` record already exists, the system SHALL load existing data for editing instead of creating a new record.

**Validates: Requirements 5.2**

**Property 9: New Record Creation**
For any combination of date and UDN, if no `daily_closure` record exists, the system SHALL create a new record when the user saves data.

**Validates: Requirements 5.3**

**Property 10: Required Field Validation**
For any save operation, if any required field is empty, the system SHALL display an error message and prevent the save operation.

**Validates: Requirements 6.1, 7.1**

### Persistence Properties

**Property 11: Sales Detail Persistence**
For any sale category with a non-zero value, the system SHALL insert or update a record in `detail_sale_category` with fields: sale, net_sale, discount, courtesy, daily_closure_id, sale_category_id.

**Validates: Requirements 6.2**

**Property 12: Tax Detail Persistence**
For any sale category with associated taxes, the system SHALL insert or update records in `detail_sale_category_tax` for each tax with fields: sale_tax, discount_tax, courtesy_tax, detail_sale_category_id, sale_category_tax_id.

**Validates: Requirements 6.3**

**Property 13: Cash Concept Persistence**
For any cash concept with a non-zero value, the system SHALL insert or update a record in `detail_cash_concept` with fields: daily_closure_id, cash_concept_id, amount.

**Validates: Requirements 7.2**

**Property 14: Bank Account Persistence**
For any bank account with a non-zero value, the system SHALL insert or update a record in `detail_bank_account` with fields: daily_closure_id, bank_account_id, amount.

**Validates: Requirements 7.3**

**Property 15: Foreign Currency Persistence**
For any foreign currency with a non-zero value, the system SHALL insert or update a record in `detail_foreing_currency` with fields: foreing_currency_id, exchange_rate, amount, amount_mxn, daily_closure_id.

**Validates: Requirements 7.4**

**Property 16: Success Message Display**
For any successful save operation (status 200), the system SHALL display a success message using the `alert()` component.

**Validates: Requirements 6.4, 7.5**

**Property 17: Error Message Display**
For any failed save operation (status != 200), the system SHALL display an error message with a descriptive text using the `alert()` component.

**Validates: Requirements 6.5, 7.6**

### Calculation Properties

**Property 18: Difference Calculation**
For any state of the system, the difference SHALL always equal: Total de Venta - Total Pagado.

**Validates: Requirements 8.1**

**Property 19: Positive Difference Display**
For any difference value greater than or equal to zero, the difference SHALL be displayed in green color.

**Validates: Requirements 8.2**

**Property 20: Negative Difference Display**
For any difference value less than zero, the difference SHALL be displayed in red color.

**Validates: Requirements 8.3**

**Property 21: Zero Difference Display**
For any difference value equal to zero, the difference SHALL be displayed in neutral color (gray or black).

**Validates: Requirements 8.4**

**Property 22: Real-time Difference Update**
For any change in sale or payment values, the difference SHALL be recalculated and displayed within 100ms.

**Validates: Requirements 8.5**

**Property 23: Foreign Currency MXN Calculation**
For any foreign currency amount entered, the equivalent MXN amount SHALL equal: amount × exchange_rate.

**Validates: Requirements 2.5**

**Property 24: Net Sale Calculation**
For any sale category, the net_sale SHALL equal: sale - discount - courtesy.

**Validates: Requirements 6.2**

### Data Loading Properties

**Property 25: Existing Data Load**
For any existing `daily_closure` record, when the module is opened, the system SHALL load all associated detail records from: detail_sale_category, detail_sale_category_tax, detail_cash_concept, detail_foreing_currency, detail_bank_account, detail_credit_customer.

**Validates: Requirements 9.2, 9.3, 9.4**

**Property 26: Empty State Initialization**
For any non-existing `daily_closure` record, when the module is opened, the system SHALL display all fields with zero values.

**Validates: Requirements 9.5**

**Property 27: Daily Closure ID Storage**
For any existing `daily_closure` record loaded, the system SHALL store the `daily_closure_id` in the class property `this.dailyClosureId`.

**Validates: Requirements 5.4**

### UI Update Properties

**Property 28: Summary Card Update - Sales**
For any change in sale, discount, courtesy, or tax values, the Sales SummaryCard SHALL update to show: Ventas, Descuentos, Cortesías, Impuestos, Total de Venta.

**Validates: Requirements 1.9**

**Property 29: Summary Card Update - Payments**
For any change in cash concept, foreign currency, bank account, or credit values, the Payments SummaryCard SHALL update to show: Efectivo, Moneda Extranjera, Bancos, Crédito, Total Pagado.

**Validates: Requirements 2.9**

**Property 30: Global Summary Card Update**
For any change in total sale or total payment values, the Global SummaryCard SHALL update to show: Total de Venta, Total Pagado, Diferencia.

**Validates: Requirements 8.1, 8.5**

### Dynamic Loading Properties

**Property 31: Sale Categories Loading**
For any UDN selected, the system SHALL load sale categories from `sale_category` table filtered by `udn_id` and `active = 1`.

**Validates: Requirements 1.4**

**Property 32: Cash Concepts Loading**
For any UDN selected, the system SHALL load cash concepts from `cash_concept` table filtered by `udn_id` and `active = 1`.

**Validates: Requirements 2.2**

**Property 33: Foreign Currencies Loading**
For any module initialization, the system SHALL load foreign currencies from `foreing_currency` table with their exchange rates where `active = 1`.

**Validates: Requirements 2.4**

**Property 34: Bank Accounts Loading**
For any UDN selected, the system SHALL load bank accounts from `bank_account` table filtered by `udn_id` and `active = 1`, including the associated bank name from `bank` table.

**Validates: Requirements 2.6, 2.7**

**Property 35: Credit Customer Display**
For any existing `daily_closure` record, the system SHALL display credit customer data from `detail_credit_customer` in read-only mode.

**Validates: Requirements 2.8**

### Responsive Design Properties

**Property 36: Two-Column Layout**
For any screen width greater than or equal to 768px, the system SHALL display the two cards (Ventas and Pagos) side by side.

**Validates: Requirements 10.1**

**Property 37: Stacked Layout**
For any screen width less than 768px, the system SHALL stack the two cards vertically.

**Validates: Requirements 10.2**

**Property 38: Numeric Field Readability**
For any screen size, numeric input fields SHALL maintain readability with appropriate font size and spacing.

**Validates: Requirements 10.4**

### Property Reflection

After analyzing all properties, the following redundancies were identified and eliminated:

- **Eliminated:** "For any tax calculation, the result SHALL be a non-negative number" - This is implicit in the calculation formula and doesn't add testable value.
- **Eliminated:** "For any save operation, the system SHALL use AJAX" - This is an implementation detail, not a correctness property.
- **Merged:** Properties about individual field updates were consolidated into summary card update properties (28-30) to avoid redundancy.

The final set of 38 properties provides comprehensive coverage of all requirements without redundancy.


## Error Handling

### Date Validation Failures

**Scenario:** Usuario intenta guardar ventas en una fecha bloqueada.

**Detection:**
- Verificar `monthly_module_lock` al cargar el módulo
- Comparar hora actual con `lock_time` del mes correspondiente

**Handling:**
```javascript
if (currentTime > lockTime) {
    // Deshabilitar botón de guardar
    $('#btnSaveSales').prop('disabled', true);
    $('#btnSavePayments').prop('disabled', true);
    
    // Mostrar mensaje visual
    alert({
        icon: "warning",
        title: "Fecha bloqueada",
        text: "No se pueden registrar ventas después de las " + lockTime,
        btn1: true,
        btn1Text: "Entendido"
    });
}
```

**Recovery:**
- Usuario debe seleccionar una fecha habilitada
- Sistema reactiva botones automáticamente al cambiar fecha

### Duplicate Prevention Logic

**Scenario:** Usuario intenta crear un registro para una fecha/UDN que ya existe.

**Detection:**
```javascript
async loadExistingData() {
    const response = await useFetch({
        url: this._link,
        data: {
            opc: 'getSales',
            fecha: selectedDate,
            udn: selectedUdn
        }
    });
    
    if (response.status === 200 && response.data) {
        // Registro existe - modo edición
        this.dailyClosureId = response.data.daily_closure_id;
        this.fillFormWithData(response.data);
    } else {
        // Registro no existe - modo creación
        this.dailyClosureId = null;
        this.initializeEmptyForm();
    }
}
```

**Handling:**
- Si existe: Cargar datos automáticamente en modo edición
- Si no existe: Inicializar formulario vacío para nuevo registro
- Mostrar indicador visual del modo actual (Editar vs Crear)

**Recovery:**
- No requiere acción del usuario
- Sistema maneja transparentemente

### Persistence Failures and Rollback

**Scenario:** Falla al guardar datos en una o más tablas de detalle.

**Detection:**
```javascript
async saveSales() {
    const response = await useFetch({
        url: this._link,
        data: {
            opc: 'addSales',
            daily_closure_id: this.dailyClosureId,
            sales: this.collectSalesData()
        }
    });
    
    if (response.status !== 200) {
        // Error detectado
        this.handleSaveError(response);
    }
}
```

**Handling:**
```javascript
handleSaveError(response) {
    alert({
        icon: "error",
        title: "Error al guardar",
        text: response.message || "No se pudieron guardar los datos. Intente nuevamente.",
        btn1: true,
        btn1Text: "Reintentar",
        btn2: true,
        btn2Text: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            this.saveSales(); // Reintentar
        }
    });
}
```

**Backend Rollback (CTRL):**
```php
function addSales() {
    $status = 500;
    $message = 'Error al guardar ventas';
    
    // Iniciar transacción implícita
    $salesCreated = [];
    
    foreach ($_POST['sales'] as $sale) {
        $result = $this->createSaleDetail($this->util->sql($sale));
        
        if (!$result) {
            // Rollback: eliminar registros creados
            foreach ($salesCreated as $saleId) {
                $this->deleteSaleDetail([$saleId]);
            }
            
            return [
                'status' => 500,
                'message' => 'Error al guardar categoría: ' . $sale['name']
            ];
        }
        
        $salesCreated[] = $result;
    }
    
    $status = 200;
    $message = 'Ventas guardadas correctamente';
    
    return [
        'status' => $status,
        'message' => $message
    ];
}
```

**Recovery:**
- Usuario puede reintentar la operación
- Sistema elimina registros parciales en caso de falla
- Datos permanecen en la interfaz para corrección

### Network Errors

**Scenario:** Pérdida de conexión durante petición AJAX.

**Detection:**
```javascript
async useFetch(options) {
    try {
        const response = await fetch(options.url, {
            method: 'POST',
            body: new FormData(...)
        });
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        return await response.json();
    } catch (error) {
        return {
            status: 0,
            message: 'Error de conexión. Verifique su conexión a internet.'
        };
    }
}
```

**Handling:**
```javascript
if (response.status === 0) {
    alert({
        icon: "error",
        title: "Error de conexión",
        text: "No se pudo conectar con el servidor. Verifique su conexión a internet.",
        btn1: true,
        btn1Text: "Reintentar"
    }).then((result) => {
        if (result.isConfirmed) {
            this.saveSales(); // Reintentar
        }
    });
}
```

**Recovery:**
- Usuario verifica conexión a internet
- Usuario reintenta la operación
- Datos permanecen en la interfaz

### Invalid Input Handling

**Scenario:** Usuario ingresa valores no numéricos o negativos.

**Detection:**
```javascript
// Validación en tiempo real
$('input[type="number"]').on('input', function() {
    let value = parseFloat($(this).val());
    
    if (isNaN(value) || value < 0) {
        $(this).addClass('border-red-500');
        $(this).val(0);
    } else {
        $(this).removeClass('border-red-500');
    }
});
```

**Handling:**
```javascript
collectSalesData() {
    const salesData = [];
    
    $('.sale-input').each(function() {
        let value = parseFloat($(this).val());
        
        // Sanitizar valor
        if (isNaN(value) || value < 0) {
            value = 0;
        }
        
        salesData.push({
            category_id: $(this).data('category-id'),
            amount: value
        });
    });
    
    return salesData;
}
```

**Recovery:**
- Sistema corrige automáticamente valores inválidos a 0
- Usuario recibe feedback visual (borde rojo)
- No requiere acción adicional del usuario

### Missing Daily Closure ID

**Scenario:** Usuario intenta guardar pagos sin haber creado primero el daily_closure.

**Detection:**
```javascript
async savePayments() {
    if (!this.dailyClosureId) {
        // Crear daily_closure primero
        const createResponse = await this.createDailyClosure();
        
        if (createResponse.status !== 200) {
            alert({
                icon: "error",
                text: "No se pudo crear el registro de cierre diario"
            });
            return;
        }
        
        this.dailyClosureId = createResponse.daily_closure_id;
    }
    
    // Continuar con guardado de pagos
    // ...
}
```

**Handling:**
- Sistema crea automáticamente `daily_closure` si no existe
- Operación es transparente para el usuario
- Si falla la creación, se muestra error y se detiene el proceso

**Recovery:**
- Sistema maneja automáticamente
- Usuario puede reintentar si falla

### Tax Calculation Errors

**Scenario:** No se encuentran impuestos asociados a una categoría.

**Detection:**
```javascript
calculateTaxes() {
    const taxes = {};
    
    $('.sale-input').each(function() {
        const categoryId = $(this).data('category-id');
        const amount = parseFloat($(this).val()) || 0;
        
        if (amount > 0) {
            const categoryTaxes = this.getTaxesForCategory(categoryId);
            
            if (!categoryTaxes || categoryTaxes.length === 0) {
                console.warn(`No taxes found for category ${categoryId}`);
                // Continuar sin impuestos para esta categoría
                return;
            }
            
            // Calcular impuestos...
        }
    });
}
```

**Handling:**
- Sistema registra advertencia en consola
- Continúa procesamiento sin impuestos para esa categoría
- No bloquea la operación de guardado

**Recovery:**
- Administrador debe configurar impuestos en catálogo
- Usuario puede guardar datos sin impuestos temporalmente

### Concurrent Modification

**Scenario:** Dos usuarios intentan editar el mismo registro simultáneamente.

**Detection:**
```php
function editSales() {
    $id = $_POST['daily_closure_id'];
    
    // Verificar última modificación
    $current = $this->getDailyClosureById([$id]);
    $lastModified = strtotime($current['updated_at']);
    $clientLastModified = strtotime($_POST['last_modified']);
    
    if ($lastModified > $clientLastModified) {
        return [
            'status' => 409,
            'message' => 'El registro fue modificado por otro usuario. Recargue los datos.'
        ];
    }
    
    // Continuar con actualización...
}
```

**Handling:**
```javascript
if (response.status === 409) {
    alert({
        icon: "warning",
        title: "Conflicto de edición",
        text: response.message,
        btn1: true,
        btn1Text: "Recargar datos"
    }).then((result) => {
        if (result.isConfirmed) {
            this.loadExistingData(); // Recargar datos actuales
        }
    });
}
```

**Recovery:**
- Usuario recarga datos actuales
- Usuario reaplica sus cambios
- Sistema previene sobrescritura de datos

### Summary of Error Handling Strategy

| Error Type | Detection Method | Handling Strategy | Recovery Method |
|------------|------------------|-------------------|-----------------|
| Date Validation | Check `monthly_module_lock` | Disable save buttons, show warning | Select valid date |
| Duplicate Record | Query `daily_closure` | Load existing data for edit | Automatic |
| Persistence Failure | Check response status | Show error, rollback partial saves | Retry operation |
| Network Error | Catch fetch exceptions | Show connection error | Retry after connection restored |
| Invalid Input | Validate on input event | Sanitize to 0, show visual feedback | Automatic correction |
| Missing Daily Closure | Check `dailyClosureId` | Create automatically | Automatic |
| Tax Calculation | Check tax associations | Log warning, continue without taxes | Admin configures taxes |
| Concurrent Modification | Compare timestamps | Show conflict warning, reload data | User reapplies changes |

**General Principles:**
1. **Fail gracefully**: Nunca dejar la interfaz en estado inconsistente
2. **Provide feedback**: Siempre informar al usuario sobre errores
3. **Enable recovery**: Ofrecer opciones claras para recuperarse del error
4. **Preserve data**: Mantener datos del usuario en la interfaz cuando sea posible
5. **Log errors**: Registrar errores en consola para debugging


## Testing Strategy

### Overview

La estrategia de testing para el módulo AddSales combina **unit tests** (para ejemplos y casos edge) con **property-based tests** (para propiedades universales). Todos los tests deben ejecutarse con un mínimo de 100 iteraciones para property tests.

### Test Organization

Los tests se organizan por categoría funcional:

1. **Tax Calculation Tests** - Propiedades 1-5
2. **Validation Tests** - Propiedades 6-10
3. **Persistence Tests** - Propiedades 11-17
4. **Calculation Tests** - Propiedades 18-24
5. **Data Loading Tests** - Propiedades 25-27
6. **UI Update Tests** - Propiedades 28-30
7. **Dynamic Loading Tests** - Propiedades 31-35
8. **Responsive Design Tests** - Propiedades 36-38

### Unit Tests (Examples and Edge Cases)

#### Tax Calculation Examples

**Test: Single Tax Calculation**
```javascript
describe('Tax Calculation - Single Tax', () => {
    it('should calculate IVA 8% correctly for sale amount 1000', () => {
        const sale = 1000;
        const taxPercentage = 8;
        const expectedTax = 80;
        
        const result = calculateTax(sale, taxPercentage);
        
        expect(result).toBe(expectedTax);
    });
});
```
**Feature: add-sales, Property 1: Tax Calculation Accuracy**

**Test: Multiple Taxes Calculation**
```javascript
describe('Tax Calculation - Multiple Taxes', () => {
    it('should sum IVA 8% and HOSPEDAJE 2% for Hospedaje category', () => {
        const sale = 1000;
        const taxes = [
            { name: 'IVA', percentage: 8 },
            { name: 'HOSPEDAJE', percentage: 2 }
        ];
        const expectedTotal = 100; // 80 + 20
        
        const result = calculateMultipleTaxes(sale, taxes);
        
        expect(result).toBe(expectedTotal);
    });
});
```
**Feature: add-sales, Property 2: Multiple Tax Summation**

**Test: Zero Sale Amount**
```javascript
describe('Tax Calculation - Edge Case', () => {
    it('should return 0 tax for 0 sale amount', () => {
        const sale = 0;
        const taxPercentage = 8;
        const expectedTax = 0;
        
        const result = calculateTax(sale, taxPercentage);
        
        expect(result).toBe(expectedTax);
    });
});
```
**Feature: add-sales, Property 1: Tax Calculation Accuracy (Edge Case)**

#### Validation Examples

**Test: Date Blocked After Lock Time**
```javascript
describe('Date Validation', () => {
    it('should disable save button when current time > lock time', () => {
        const currentTime = '13:00:00';
        const lockTime = '12:00:00';
        
        const result = isDateEnabled(currentTime, lockTime);
        
        expect(result).toBe(false);
    });
});
```
**Feature: add-sales, Property 6: Date Validation**

**Test: Date Enabled Before Lock Time**
```javascript
describe('Date Validation', () => {
    it('should enable save button when current time <= lock time', () => {
        const currentTime = '11:00:00';
        const lockTime = '12:00:00';
        
        const result = isDateEnabled(currentTime, lockTime);
        
        expect(result).toBe(true);
    });
});
```
**Feature: add-sales, Property 7: Date Enabled State**

#### Calculation Examples

**Test: Positive Difference**
```javascript
describe('Difference Calculation', () => {
    it('should calculate positive difference correctly', () => {
        const totalSale = 1500;
        const totalPayment = 1200;
        const expectedDifference = 300;
        
        const result = calculateDifference(totalSale, totalPayment);
        
        expect(result).toBe(expectedDifference);
    });
});
```
**Feature: add-sales, Property 18: Difference Calculation**

**Test: Negative Difference**
```javascript
describe('Difference Calculation', () => {
    it('should calculate negative difference correctly', () => {
        const totalSale = 1000;
        const totalPayment = 1200;
        const expectedDifference = -200;
        
        const result = calculateDifference(totalSale, totalPayment);
        
        expect(result).toBe(expectedDifference);
    });
});
```
**Feature: add-sales, Property 18: Difference Calculation**

**Test: Zero Difference**
```javascript
describe('Difference Calculation - Edge Case', () => {
    it('should return 0 when sale equals payment', () => {
        const totalSale = 1000;
        const totalPayment = 1000;
        const expectedDifference = 0;
        
        const result = calculateDifference(totalSale, totalPayment);
        
        expect(result).toBe(expectedDifference);
    });
});
```
**Feature: add-sales, Property 21: Zero Difference Display (Edge Case)**

**Test: Foreign Currency MXN Calculation**
```javascript
describe('Foreign Currency Calculation', () => {
    it('should calculate MXN amount correctly', () => {
        const amount = 100; // USD
        const exchangeRate = 18.50;
        const expectedMXN = 1850;
        
        const result = calculateMXN(amount, exchangeRate);
        
        expect(result).toBe(expectedMXN);
    });
});
```
**Feature: add-sales, Property 23: Foreign Currency MXN Calculation**

### Property-Based Tests (Universal Properties)

#### Tax Calculation Properties

**Property Test 1: Tax Calculation Accuracy**
```javascript
describe('Property: Tax Calculation Accuracy', () => {
    it('should calculate tax correctly for any sale amount and percentage', () => {
        fc.assert(
            fc.property(
                fc.float({ min: 0, max: 1000000 }), // sale amount
                fc.float({ min: 0, max: 100 }),     // tax percentage
                (sale, percentage) => {
                    const expectedTax = (sale * percentage) / 100;
                    const result = calculateTax(sale, percentage);
                    
                    return Math.abs(result - expectedTax) < 0.01; // Tolerance for floating point
                }
            ),
            { numRuns: 100 }
        );
    });
});
```
**Feature: add-sales, Property 1: For any sale category with associated taxes, the calculated tax amount SHALL equal the sum of (sale amount × tax percentage / 100) for each associated tax.**

**Property Test 2: Multiple Tax Summation**
```javascript
describe('Property: Multiple Tax Summation', () => {
    it('should sum all taxes correctly for any number of taxes', () => {
        fc.assert(
            fc.property(
                fc.float({ min: 0, max: 1000000 }),
                fc.array(fc.float({ min: 0, max: 100 }), { minLength: 1, maxLength: 5 }),
                (sale, percentages) => {
                    const expectedTotal = percentages.reduce((sum, p) => sum + (sale * p / 100), 0);
                    const result = calculateMultipleTaxes(sale, percentages.map(p => ({ percentage: p })));
                    
                    return Math.abs(result - expectedTotal) < 0.01;
                }
            ),
            { numRuns: 100 }
        );
    });
});
```
**Feature: add-sales, Property 2: For any sale category with multiple associated taxes, the total tax SHALL equal the sum of all individual tax calculations.**

#### Validation Properties

**Property Test 6: Date Validation**
```javascript
describe('Property: Date Validation', () => {
    it('should disable save when current time > lock time for any times', () => {
        fc.assert(
            fc.property(
                fc.integer({ min: 0, max: 23 }), // current hour
                fc.integer({ min: 0, max: 59 }), // current minute
                fc.integer({ min: 0, max: 23 }), // lock hour
                fc.integer({ min: 0, max: 59 }), // lock minute
                (currH, currM, lockH, lockM) => {
                    const currentTime = `${currH}:${currM}:00`;
                    const lockTime = `${lockH}:${lockM}:00`;
                    const currentMinutes = currH * 60 + currM;
                    const lockMinutes = lockH * 60 + lockM;
                    
                    const result = isDateEnabled(currentTime, lockTime);
                    const expected = currentMinutes <= lockMinutes;
                    
                    return result === expected;
                }
            ),
            { numRuns: 100 }
        );
    });
});
```
**Feature: add-sales, Property 6: For any date selected, if the current time is greater than the lock_time configured in monthly_module_lock for that month, the save button SHALL be disabled.**

#### Calculation Properties

**Property Test 18: Difference Calculation**
```javascript
describe('Property: Difference Calculation', () => {
    it('should always equal totalSale - totalPayment for any values', () => {
        fc.assert(
            fc.property(
                fc.float({ min: 0, max: 1000000 }),
                fc.float({ min: 0, max: 1000000 }),
                (totalSale, totalPayment) => {
                    const expectedDifference = totalSale - totalPayment;
                    const result = calculateDifference(totalSale, totalPayment);
                    
                    return Math.abs(result - expectedDifference) < 0.01;
                }
            ),
            { numRuns: 100 }
        );
    });
});
```
**Feature: add-sales, Property 18: For any state of the system, the difference SHALL always equal: Total de Venta - Total Pagado.**

**Property Test 23: Foreign Currency MXN Calculation**
```javascript
describe('Property: Foreign Currency MXN Calculation', () => {
    it('should always equal amount × exchange_rate for any values', () => {
        fc.assert(
            fc.property(
                fc.float({ min: 0, max: 100000 }),
                fc.float({ min: 1, max: 100 }),
                (amount, exchangeRate) => {
                    const expectedMXN = amount * exchangeRate;
                    const result = calculateMXN(amount, exchangeRate);
                    
                    return Math.abs(result - expectedMXN) < 0.01;
                }
            ),
            { numRuns: 100 }
        );
    });
});
```
**Feature: add-sales, Property 23: For any foreign currency amount entered, the equivalent MXN amount SHALL equal: amount × exchange_rate.**

**Property Test 24: Net Sale Calculation**
```javascript
describe('Property: Net Sale Calculation', () => {
    it('should always equal sale - discount - courtesy for any values', () => {
        fc.assert(
            fc.property(
                fc.float({ min: 0, max: 100000 }),
                fc.float({ min: 0, max: 10000 }),
                fc.float({ min: 0, max: 10000 }),
                (sale, discount, courtesy) => {
                    const expectedNetSale = sale - discount - courtesy;
                    const result = calculateNetSale(sale, discount, courtesy);
                    
                    return Math.abs(result - expectedNetSale) < 0.01;
                }
            ),
            { numRuns: 100 }
        );
    });
});
```
**Feature: add-sales, Property 24: For any sale category, the net_sale SHALL equal: sale - discount - courtesy.**

### Integration Tests

#### End-to-End Flow Tests

**Test: Complete Sales Registration Flow**
```javascript
describe('Integration: Complete Sales Registration', () => {
    it('should register sales from start to finish', async () => {
        // 1. Load module
        await addSales.init();
        
        // 2. Select date and UDN
        await addSales.selectDate('2025-12-27');
        await addSales.selectUDN(5);
        
        // 3. Enter sales data
        await addSales.enterSaleCategory('Cortes', 1000);
        await addSales.enterSaleCategory('Bebidas', 500);
        await addSales.enterDiscount(50);
        
        // 4. Verify tax calculation
        const taxes = addSales.getTotalTaxes();
        expect(taxes).toBeGreaterThan(0);
        
        // 5. Enter payment data
        await addSales.enterCashConcept('Efectivo', 1200);
        await addSales.enterBankAccount('BBVA', 300);
        
        // 6. Verify difference calculation
        const difference = addSales.getDifference();
        expect(difference).toBe(0); // Assuming balanced
        
        // 7. Save sales
        const response = await addSales.saveSales();
        expect(response.status).toBe(200);
        
        // 8. Verify persistence
        const saved = await addSales.loadExistingData();
        expect(saved.dailyClosureId).toBeDefined();
    });
});
```

**Test: Edit Existing Sales Flow**
```javascript
describe('Integration: Edit Existing Sales', () => {
    it('should load and edit existing sales', async () => {
        // 1. Create initial sales
        await createTestSales({ date: '2025-12-27', udn: 5 });
        
        // 2. Load module
        await addSales.init();
        await addSales.selectDate('2025-12-27');
        await addSales.selectUDN(5);
        
        // 3. Verify data loaded
        const loaded = addSales.getSaleCategory('Cortes');
        expect(loaded).toBeGreaterThan(0);
        
        // 4. Modify data
        await addSales.enterSaleCategory('Cortes', 1500);
        
        // 5. Save changes
        const response = await addSales.saveSales();
        expect(response.status).toBe(200);
        
        // 6. Verify changes persisted
        const updated = await addSales.loadExistingData();
        expect(updated.sales.Cortes).toBe(1500);
    });
});
```

### Performance Tests

**Test: Real-time Calculation Performance**
```javascript
describe('Performance: Real-time Calculations', () => {
    it('should update taxes within 100ms', async () => {
        await addSales.init();
        
        const startTime = performance.now();
        await addSales.enterSaleCategory('Cortes', 1000);
        const endTime = performance.now();
        
        const duration = endTime - startTime;
        expect(duration).toBeLessThan(100);
    });
    
    it('should update difference within 100ms', async () => {
        await addSales.init();
        
        const startTime = performance.now();
        await addSales.enterCashConcept('Efectivo', 1000);
        const endTime = performance.now();
        
        const duration = endTime - startTime;
        expect(duration).toBeLessThan(100);
    });
});
```

### Test Coverage Requirements

| Category | Unit Tests | Property Tests | Integration Tests | Total Coverage |
|----------|------------|----------------|-------------------|----------------|
| Tax Calculation | 5 | 2 | 1 | 95% |
| Validation | 4 | 1 | 1 | 90% |
| Persistence | 3 | 0 | 2 | 85% |
| Calculations | 6 | 3 | 1 | 95% |
| Data Loading | 2 | 0 | 2 | 80% |
| UI Updates | 3 | 0 | 1 | 75% |
| Dynamic Loading | 5 | 0 | 1 | 80% |
| Responsive Design | 3 | 0 | 0 | 70% |
| **Total** | **31** | **6** | **9** | **84%** |

### Test Execution

**Command to run all tests:**
```bash
npm test -- --testPathPattern=add-sales
```

**Command to run property tests only:**
```bash
npm test -- --testPathPattern=add-sales --testNamePattern="Property:"
```

**Command to run with coverage:**
```bash
npm test -- --coverage --testPathPattern=add-sales
```

### Continuous Integration

Los tests deben ejecutarse automáticamente en:
- **Pre-commit**: Unit tests y property tests
- **Pre-push**: Todos los tests incluyendo integration tests
- **CI Pipeline**: Todos los tests con reporte de coverage

**Criterios de aceptación para merge:**
- Todos los tests pasan (100%)
- Coverage mínimo: 80%
- Property tests ejecutados con mínimo 100 iteraciones
- No errores de linting


## Implementation Notes

### Technology Stack

- **Frontend Framework**: CoffeeSoft (jQuery-based)
- **CSS Framework**: TailwindCSS
- **Backend Language**: PHP 7.4+
- **Database**: MySQL 5.7+
- **AJAX Library**: Fetch API (via `useFetch()` wrapper)

### File Structure

```
finanzas/consulta_respaldo/
├── index.php                          # Entry point (includes add-sales.js)
├── js/
│   ├── add-sales.js                   # Main AddSales class
│   ├── details-sale.js                # Reference for read-only view
│   └── caratula-venta.js              # Reference for summary view
├── ctrl/
│   └── ctrl-add-sales.php             # Controller (CRUD operations)
└── mdl/
    └── mdl-add-sales.php              # Model (database queries)
```

### Development Phases

#### Phase 1: Frontend Structure (Estimated: 2 days)
- Create `AddSales` class extending `Templates`
- Implement `layout()` and `filterBar()` methods
- Create two-card layout structure
- Integrate with existing navigation

#### Phase 2: Data Loading (Estimated: 1 day)
- Implement `init()` method to load catalogs
- Implement `loadExistingData()` method
- Create dynamic form generation for categories, concepts, currencies, banks
- Handle empty state vs edit mode

#### Phase 3: Tax Calculation (Estimated: 2 days)
- Implement `calculateTaxes()` method
- Query `sale_category_tax` and `tax` tables
- Handle multiple taxes per category
- Real-time calculation on input change
- Update SummaryCard components

#### Phase 4: Payment Calculations (Estimated: 1 day)
- Implement foreign currency MXN calculation
- Implement `calculateDifference()` method
- Real-time updates on payment changes
- Color-coded difference display

#### Phase 5: Persistence (Estimated: 3 days)
- Implement `saveSales()` and `savePayments()` methods
- Create controller methods: `addSales()`, `editSales()`, `addPayments()`, `editPayments()`
- Create model methods for all detail tables
- Handle daily_closure creation/update
- Implement rollback on partial failures

#### Phase 6: Validation (Estimated: 1 day)
- Implement date validation with `monthly_module_lock`
- Implement duplicate prevention logic
- Add required field validation
- Add input sanitization

#### Phase 7: Error Handling (Estimated: 1 day)
- Implement error handling for all scenarios
- Add user-friendly error messages
- Implement retry mechanisms
- Add logging for debugging

#### Phase 8: Testing (Estimated: 3 days)
- Write unit tests for all calculations
- Write property-based tests (minimum 100 iterations each)
- Write integration tests for complete flows
- Achieve 80%+ code coverage

#### Phase 9: UI Polish (Estimated: 1 day)
- Responsive design testing
- Loading states and animations
- Accessibility improvements
- Cross-browser testing

**Total Estimated Time: 15 days**

### Code Style Guidelines

#### JavaScript (Frontend)

```javascript
// ✅ CORRECT: Use camelCase for methods
calculateTaxes() { }

// ✅ CORRECT: Use arrow functions for callbacks
onClick: () => this.saveSales()

// ✅ CORRECT: Use async/await for AJAX
async loadData() {
    const response = await useFetch({ ... });
}

// ❌ INCORRECT: Don't use var
var data = [];

// ❌ INCORRECT: Don't use function expressions for methods
this.calculateTaxes = function() { }
```

#### PHP (Backend)

```php
// ✅ CORRECT: Direct assignment for $_POST
$udn = $_POST['udn'];

// ✅ CORRECT: Use _Read() for SELECT queries
$result = $this->_Read($query, $array);

// ✅ CORRECT: Return status and message
return [
    'status' => 200,
    'message' => 'Success'
];

// ❌ INCORRECT: Don't use ?? with $_POST
$udn = $_POST['udn'] ?? 'all';

// ❌ INCORRECT: Don't use _Select() for queries
$result = $this->_Select([...]);

// ❌ INCORRECT: Don't use try-catch
try {
    $result = $this->_Read(...);
} catch (Exception $e) { }
```

### Database Considerations

#### Indexes

Ensure the following indexes exist for optimal performance:

```sql
-- daily_closure
CREATE INDEX idx_daily_closure_date_udn ON daily_closure(created_at, udn_id);

-- detail_sale_category
CREATE INDEX idx_detail_sale_closure ON detail_sale_category(daily_closure_id);

-- detail_cash_concept
CREATE INDEX idx_detail_cash_closure ON detail_cash_concept(daily_closure_id);

-- detail_foreing_currency
CREATE INDEX idx_detail_foreign_closure ON detail_foreing_currency(daily_closure_id);

-- detail_bank_account
CREATE INDEX idx_detail_bank_closure ON detail_bank_account(daily_closure_id);
```

#### Foreign Key Constraints

All foreign keys should have `ON DELETE SET NULL ON UPDATE CASCADE` to prevent orphaned records:

```sql
CONSTRAINT `fk_name` FOREIGN KEY (`column`) 
REFERENCES `table` (`id`) 
ON DELETE SET NULL 
ON UPDATE CASCADE
```

### Security Considerations

1. **Session Validation**: Always validate `$_COOKIE['IDU']` before processing requests
2. **SQL Injection Prevention**: Use parameterized queries via `_Read()`, `_Insert()`, `_Update()`, `_Delete()`
3. **XSS Prevention**: Sanitize all user inputs before displaying in UI
4. **CSRF Protection**: Implement CSRF tokens for all POST requests
5. **Permission Checks**: Verify user has permission to modify data for selected UDN

### Performance Optimization

1. **Lazy Loading**: Load catalogs only when needed
2. **Debouncing**: Debounce real-time calculations to avoid excessive updates
3. **Caching**: Cache catalog data (categories, concepts, currencies) in class properties
4. **Batch Operations**: Save all detail records in a single transaction
5. **Minimize DOM Manipulation**: Update UI elements in batches

### Accessibility

1. **Keyboard Navigation**: Ensure all inputs are keyboard accessible
2. **Screen Reader Support**: Add ARIA labels to all form fields
3. **Focus Management**: Maintain logical focus order
4. **Error Announcements**: Use ARIA live regions for error messages
5. **Color Contrast**: Ensure sufficient contrast for difference colors (green/red)

### Browser Compatibility

- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+

### Deployment Checklist

- [ ] All tests passing (unit, property, integration)
- [ ] Code coverage >= 80%
- [ ] No console errors or warnings
- [ ] Database indexes created
- [ ] Foreign key constraints verified
- [ ] Session validation implemented
- [ ] Error handling tested
- [ ] Responsive design verified on mobile/tablet/desktop
- [ ] Cross-browser testing completed
- [ ] Accessibility audit passed
- [ ] Performance benchmarks met (< 100ms for calculations)
- [ ] Documentation updated
- [ ] User training materials prepared

### Known Limitations

1. **Concurrent Editing**: System warns but doesn't prevent concurrent modifications
2. **Offline Support**: No offline capability - requires active internet connection
3. **Bulk Operations**: No bulk import/export functionality
4. **Audit Trail**: Limited audit logging (only in `accounting_log` table)
5. **Mobile Optimization**: Optimized for tablet and desktop, limited mobile support

### Future Enhancements

1. **Bulk Import**: Import sales data from CSV/Excel
2. **Export Functionality**: Export sales data to PDF/Excel
3. **Advanced Reporting**: Generate custom reports with filters
4. **Audit Trail**: Enhanced audit logging with detailed change tracking
5. **Mobile App**: Native mobile app for on-the-go data entry
6. **Real-time Collaboration**: WebSocket-based real-time updates for concurrent users
7. **Automated Backups**: Scheduled backups of daily_closure data
8. **Data Validation Rules**: Configurable validation rules per UDN
9. **Approval Workflow**: Multi-level approval for high-value transactions
10. **Integration with Soft Restaurant**: Direct API integration for automatic data sync

---

## Conclusion

This design document provides a comprehensive blueprint for implementing the AddSales module. It covers all aspects from architecture and data models to error handling and testing strategies. The design follows CoffeeSoft framework patterns and integrates seamlessly with existing modules (details-sale.js, caratula-venta.js).

**Key Success Factors:**
- Strict adherence to CoffeeSoft patterns (MDL.md, CTRL.md, FRONT JS.md)
- Comprehensive testing with property-based tests (100+ iterations)
- Robust error handling with graceful degradation
- Real-time calculations with performance optimization
- Responsive design with accessibility support

**Next Steps:**
1. Review and approve this design document
2. Create tasks.md with detailed implementation tasks
3. Begin Phase 1 development (Frontend Structure)
4. Iterate through phases with continuous testing
5. Deploy to staging environment for user acceptance testing

---

**Document Version**: 1.0  
**Last Updated**: 2025-12-28  
**Author**: CoffeeIA ☕  
**Status**: Ready for Review

