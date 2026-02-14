# Design Document - Módulo de Clientes

## Overview

El módulo de Clientes gestiona los movimientos de crédito de clientes dentro del sistema de Contabilidad. Implementa dos vistas principales: CRUD de movimientos (para operaciones diarias) y Concentrado (para análisis consolidado por rango de fechas). El módulo sigue la arquitectura MVC del framework CoffeeSoft y se integra con el sistema existente de contabilidad.

## Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                        Frontend (JS)                             │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐  │
│  │    Customers    │  │  Consolidated   │  │    InfoCards    │  │
│  │   (CRUD View)   │  │     (View)      │  │   (Totales)     │  │
│  └────────┬────────┘  └────────┬────────┘  └────────┬────────┘  │
│           │                    │                    │            │
│           └────────────────────┼────────────────────┘            │
│                                │                                 │
│                    ┌───────────▼───────────┐                     │
│                    │   clientes.js         │                     │
│                    │   (extends Templates) │                     │
│                    └───────────┬───────────┘                     │
└────────────────────────────────┼────────────────────────────────┘
                                 │ AJAX (useFetch)
┌────────────────────────────────┼────────────────────────────────┐
│                    ┌───────────▼───────────┐                     │
│                    │  ctrl-clientes.php    │                     │
│                    │   (Controller)        │                     │
│                    └───────────┬───────────┘                     │
│                                │                                 │
│                    ┌───────────▼───────────┐                     │
│                    │   mdl-clientes.php    │                     │
│                    │      (Model)          │                     │
│                    └───────────┬───────────┘                     │
└────────────────────────────────┼────────────────────────────────┘
                                 │
┌────────────────────────────────▼────────────────────────────────┐
│                         Database                                 │
│  ┌──────────────────────┐  ┌──────────────────────┐             │
│  │ detail_credit_customer│  │      customer        │             │
│  └──────────────────────┘  └──────────────────────┘             │
│  ┌──────────────────────┐  ┌──────────────────────┐             │
│  │    movement_type     │  │  method_pay_customer │             │
│  └──────────────────────┘  └──────────────────────┘             │
│  ┌──────────────────────┐                                       │
│  │    daily_closure     │                                       │
│  └──────────────────────┘                                       │
└─────────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### Frontend Components

#### 1. Customers Class (clientes.js)
```javascript
class Customers extends Templates {
    constructor(link, div_modulo)
    render()           // Renderiza layout, cards, filterBar y tabla
    layout()           // Crea estructura del contenedor
    showCards()        // Obtiene y muestra InfoCards
    filterBar()        // Crea barra de filtros con toggle y botones
    lsCustomers()      // Lista movimientos en tabla
    addMovement()      // Modal para nuevo movimiento
    editMovement(id)   // Modal para editar movimiento
    viewMovement(id)   // Modal para ver detalle
    deleteMovement(id) // Confirmación y eliminación
    toggleView()       // Cambia entre CRUD y Concentrado
    togglePaymentMethod() // Habilita/deshabilita método de pago
}
```

#### 2. CustomersConsolidated Class (clientes.js)
```javascript
class CustomersConsolidated extends Templates {
    constructor(link, div_modulo)
    render()              // Renderiza vista concentrado
    layoutConsolidated()  // Crea estructura del concentrado
    filterBarConsolidated() // Filtros del concentrado
    lsConsolidated()      // Genera tabla dinámica por fechas
    exportToExcel()       // Exporta tabla a Excel
}
```

### Backend Interfaces

#### Controller (ctrl-clientes.php)
```php
class ctrl extends mdl {
    function init()           // Retorna catálogos iniciales
    function ls()             // Lista movimientos filtrados
    function showCustomers()  // Retorna totales para InfoCards
    function getMovement()    // Obtiene un movimiento por ID
    function addMovement()    // Crea nuevo movimiento
    function editMovement()   // Actualiza movimiento existente
    function deleteMovement() // Soft delete de movimiento
    function lsConsolidated() // Genera datos del concentrado
}
```

#### Model (mdl-clientes.php)
```php
class mdl extends CRUD {
    function lsCustomers()           // Lista clientes activos
    function lsMovementTypes()       // Lista tipos de movimiento
    function lsPaymentMethods()      // Lista métodos de pago
    function listMovements($array)   // Lista movimientos con filtros
    function getMovementById($array) // Obtiene movimiento por ID
    function createMovement($array)  // Inserta nuevo movimiento
    function updateMovement($array)  // Actualiza movimiento
    function deleteMovementById($array) // Soft delete
    function getMovementCounts($array)  // Totales para InfoCards
    function getCustomerDebt($array)    // Calcula deuda de cliente
    function listCustomersWithMovements($array) // Para concentrado
    function getMovementsByDate($array) // Movimientos por fecha
}
```

## Data Models

### detail_credit_customer (Movimientos de Crédito)
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | int(11) | PK, auto_increment |
| amount | decimal(12,2) | Monto del movimiento |
| created_at | datetime | Fecha de creación |
| description | text | Descripción del movimiento |
| reason | text | Razón/motivo |
| active | text | Estado activo/inactivo |
| customer_id | int(11) | FK → customer.id |
| movement_type_id | int(11) | FK → movement_type.id |
| method_pay_id | int(11) | FK → method_pay_customer.id |
| daily_closure_id | int(11) | FK → daily_closure.id |

### customer (Clientes)
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | int(11) | PK, auto_increment |
| udn_id | int(11) | FK → udn.idUDN |
| name | varchar(100) | Nombre del cliente |
| active | tinyint(4) | Estado activo |

### movement_type (Tipos de Movimiento)
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | int(11) | PK |
| name | text | Nombre (Consumo, Anticipo, Pago total) |
| active | smallint(6) | Estado activo |
| operation_type | int(11) | 0=Consumo, 1=Pago |

### method_pay_customer (Métodos de Pago)
| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | int(11) | PK, auto_increment |
| name | text | Nombre (N/A, Efectivo, Banco) |
| active | smallint(6) | Estado activo |

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Payment method disabled for credit consumption
*For any* movement form where "Consumo a crédito" is selected as movement type, the payment method field should be disabled and set to "N/A"
**Validates: Requirements 1.2**

### Property 2: InfoCards update after CRUD operations
*For any* CRUD operation (create, update, delete) on movements, the InfoCards totals should be recalculated to reflect the current state of the data
**Validates: Requirements 1.4, 3.4, 4.3, 6.2**

### Property 3: Form validation prevents empty required fields
*For any* movement form submission with empty required fields (customer, movement_type, amount), the system should reject the submission and display validation errors
**Validates: Requirements 1.5**

### Property 4: Monetary values formatting
*For any* monetary value displayed in the system, it should be formatted with currency symbol ($) and exactly two decimal places
**Validates: Requirements 2.3**

### Property 5: Edit form pre-population
*For any* movement being edited, the form should be pre-filled with all existing data from the database record
**Validates: Requirements 3.1**

### Property 6: Soft delete preserves data
*For any* deleted movement, the record should remain in the database with active='0' (soft delete), not physically removed
**Validates: Requirements 4.2**

### Property 7: Consolidated table dynamic columns
*For any* date range selected in the consolidated view, the table should generate exactly one column group per date with "CONSUMOS" and "PAGOS" sub-columns
**Validates: Requirements 5.2, 5.3**

### Property 8: Client debt calculation
*For any* client displayed in the consolidated view, the debt should equal the sum of consumptions minus the sum of payments/advances
**Validates: Requirements 5.4**

### Property 9: Filter functionality
*For any* filter selection (Consumos a crédito, Pagos y anticipos, Todos), the table should display only movements matching the selected type, and "Todos" should display all movement types
**Validates: Requirements 7.2, 7.3**

## Error Handling

### Frontend Errors
- **Form Validation**: Campos requeridos vacíos muestran mensaje de error y previenen envío
- **Network Errors**: Mostrar mensaje de error con `alert({ icon: "error", text: message })`
- **Empty Results**: Mostrar mensaje "No hay movimientos registrados" en tabla vacía

### Backend Errors
- **Database Errors**: Retornar `status: 500` con mensaje descriptivo
- **Not Found**: Retornar `status: 404` cuando el registro no existe
- **Validation Errors**: Retornar `status: 400` con detalles de validación

### Response Format
```php
return [
    'status'  => 200|400|404|500,
    'message' => 'Mensaje descriptivo',
    'data'    => $data // opcional
];
```

## Testing Strategy

### Property-Based Testing Library
- **Library**: fast-check (JavaScript)
- **Minimum iterations**: 100 per property test

### Unit Tests
- Validación de formularios (campos requeridos)
- Cálculo de deuda de cliente
- Formateo de valores monetarios
- Generación de columnas dinámicas por fecha

### Property-Based Tests
Cada propiedad de correctitud debe implementarse como un test PBT:

1. **Property 1 Test**: Generar tipos de movimiento aleatorios, verificar estado del campo método de pago
2. **Property 2 Test**: Ejecutar operaciones CRUD aleatorias, verificar actualización de totales
3. **Property 3 Test**: Generar formularios con campos vacíos aleatorios, verificar rechazo
4. **Property 4 Test**: Generar valores monetarios aleatorios, verificar formato de salida
5. **Property 5 Test**: Crear movimientos aleatorios, editar y verificar pre-población
6. **Property 6 Test**: Eliminar movimientos aleatorios, verificar que active='0'
7. **Property 7 Test**: Generar rangos de fechas aleatorios, verificar estructura de columnas
8. **Property 8 Test**: Crear movimientos aleatorios por cliente, verificar cálculo de deuda
9. **Property 9 Test**: Aplicar filtros aleatorios, verificar resultados coincidentes

### Test Annotation Format
```javascript
// **Feature: modulo-clientes, Property 1: Payment method disabled for credit consumption**
// **Validates: Requirements 1.2**
```

### Integration Tests
- Flujo completo de creación de movimiento
- Flujo de edición con actualización de totales
- Cambio entre vista CRUD y Concentrado
- Filtrado por tipo de movimiento
