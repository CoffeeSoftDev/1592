# Design Document - Salida de Almacén

## Overview

Sistema de gestión de salidas de almacén que permite registrar, editar, dar de baja y consultar las salidas de almacén. El módulo cuenta con dos apartados principales: CRUD de salidas de almacén y concentrado de salidas de almacén con entradas y salidas por subcuenta.

## Architecture

### System Components

```
finanzas/consulta/
├── salida-almacen.php          # Vista principal (index)
├── js/
│   └── salida-almacen.js       # Frontend (Clases: WarehouseOutput, Consolidated)
├── ctrl/
│   └── ctrl-salida-almacen.php # Controlador
└── mdl/
    └── mdl-salida-almacen.php  # Modelo
```

### Class Hierarchy (Frontend)

```
Templates (CoffeeSoft)
    └── WarehouseOutput         # CRUD de salidas de almacén
    └── Consolidated            # Concentrado de almacén
```

### Database Tables

| Table | Description |
|-------|-------------|
| `warehouse_output` | Registros de salidas de almacén |
| `subaccount` | Subcuentas relacionadas a cuenta mayor "Almacén" |
| `daily_closure` | Cierre diario que agrupa operaciones por fecha y UDN |
| `purchase` | Compras (entradas de almacén cuando gl_account = "Almacén") |
| `gl_account` | Cuentas mayores (filtrar por name = "Almacén") |

## Component Design

### 1. Frontend (salida-almacen.js)

#### Class: WarehouseOutput

**Properties:**
- `PROJECT_NAME`: "WarehouseOutput"
- `currentView`: "outputs" | "consolidated"
- `udn`: UDN from cookie IDE

**Methods:**

| Method | Description | Requirement |
|--------|-------------|-------------|
| `render()` | Renderiza layout, cards, filterBar y tabla | - |
| `layout()` | Crea estructura con createLayout | - |
| `filterBar()` | Barra de filtros con toggle, upload, nuevo | REQ-6 |
| `showCards()` | Obtiene y muestra KPIs | REQ-1 |
| `renderInfoCards(counts)` | Renderiza InfoCard con total salidas | REQ-1 |
| `lsOutputs()` | Lista salidas de almacén en tabla | REQ-3 |
| `addOutput()` | Modal para nueva salida | REQ-2 |
| `editOutput(id)` | Modal para editar salida | REQ-4 |
| `deleteOutput(id)` | Confirmación y eliminación | REQ-5 |
| `viewDetail(id)` | Modal con detalle de salida | REQ-11 |
| `uploadFiles()` | Subida de archivos | REQ-10 |
| `jsonOutput()` | JSON del formulario add | REQ-2 |
| `jsonOutputEdit(output)` | JSON del formulario edit | REQ-4 |
| `toggleView()` | Cambia entre CRUD y Concentrado | REQ-6 |
| `showConcentrado()` | Muestra vista concentrado | REQ-6 |
| `showOutputs()` | Muestra vista CRUD | REQ-6 |

#### Class: Consolidated

**Properties:**
- `PROJECT_NAME`: "Consolidated"
- `udn`: UDN from cookie IDE

**Methods:**

| Method | Description | Requirement |
|--------|-------------|-------------|
| `render()` | Renderiza layout, filterBar, KPIs y tabla | - |
| `layoutConsolidated()` | Crea estructura con primaryLayout | - |
| `filterBarConsolidated()` | Barra de filtros con toggle y export | REQ-6 |
| `showKPIs()` | Obtiene y muestra 4 KPIs | REQ-7 |
| `renderKPIs(data)` | Renderiza 4 InfoCards | REQ-7 |
| `lsConsolidated()` | Tabla concentrado con entradas/salidas | REQ-8 |
| `viewEntryDetail(id)` | Modal detalle de entrada (compra) | REQ-11 |
| `viewOutputDetail(id)` | Modal detalle de salida | REQ-11 |
| `exportToExcel()` | Exporta tabla a Excel | REQ-8 |

### 2. Controller (ctrl-salida-almacen.php)

**Methods:**

| Method | Description | Model Method |
|--------|-------------|--------------|
| `init()` | Retorna subaccounts, udn, dailyClosure | `lsSubaccounts()`, `lsUDN()`, `getOrCreateDailyClosure()` |
| `ls()` | Lista salidas de almacén | `listWarehouseOutputs()` |
| `showOutput()` | Obtiene totales para KPIs | `getWarehouseOutputCounts()` |
| `addOutput()` | Crea nueva salida | `createWarehouseOutput()` |
| `editOutput()` | Actualiza salida existente | `updateWarehouseOutput()` |
| `deleteOutput()` | Soft delete (active=0) | `deleteWarehouseOutputById()` |
| `getOutput()` | Obtiene salida por ID | `getWarehouseOutputById()` |
| `lsConsolidated()` | Genera tabla concentrado | Multiple queries |
| `showConsolidatedKPIs()` | Obtiene 4 KPIs del concentrado | `getInitialBalance()`, `getEntries()`, `getOutputs()` |
| `uploadWarehouseFiles()` | Sube archivos de almacén | `createFile()` |
| `getEntryDetail()` | Detalle de entrada (compra) | `getPurchaseById()` |

### 3. Model (mdl-salida-almacen.php)

**Properties:**
- `$bd`: "rfwsmqex_gvsl_finanzas3."
- `$util`: Utileria instance

**Methods:**

| Method | Description | Returns |
|--------|-------------|---------|
| `listWarehouseOutputs($array)` | Lista salidas con filtros | Array of outputs |
| `getWarehouseOutputById($array)` | Obtiene salida por ID | Single output |
| `createWarehouseOutput($array)` | Inserta nueva salida | Insert ID |
| `updateWarehouseOutput($array)` | Actualiza salida | Boolean |
| `deleteWarehouseOutputById($array)` | Soft delete | Boolean |
| `getWarehouseOutputCounts($array)` | Total de salidas | Array with total |
| `lsSubaccounts($array)` | Subcuentas de cuenta "Almacén" | Array of subaccounts |
| `lsUDN()` | Lista de UDNs | Array of UDNs |
| `getDailyClosureByDate($array)` | Cierre diario por fecha | Single closure |
| `createDailyClosure($array)` | Crea cierre diario | Insert ID |
| `getInitialBalance($array)` | Saldo inicial (entradas - salidas hasta fecha anterior) | Float |
| `getEntriesByDateRange($array)` | Entradas (compras de almacén) en rango | Float |
| `getOutputsByDateRange($array)` | Salidas en rango de fechas | Float |
| `listSubaccountsWithMovements($array)` | Subcuentas con movimientos | Array |
| `getEntriesBySubaccountAndDate($array)` | Entradas por subcuenta y fecha | Float |
| `getOutputsBySubaccountAndDate($array)` | Salidas por subcuenta y fecha | Float |
| `createFile($array)` | Registra archivo subido | Insert ID |

## Data Models

### warehouse_output Table

```sql
CREATE TABLE warehouse_output (
    id INT PRIMARY KEY AUTO_INCREMENT,
    amount DECIMAL(10,2) NOT NULL,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    reason VARCHAR(255),
    active TINYINT(1) DEFAULT 1,
    subaccount_id INT NOT NULL,
    daily_closure_id INT NOT NULL,
    FOREIGN KEY (subaccount_id) REFERENCES subaccount(id),
    FOREIGN KEY (daily_closure_id) REFERENCES daily_closure(id)
);
```

### Key Relationships

- `warehouse_output.subaccount_id` → `subaccount.id` (Subcuenta de almacén)
- `warehouse_output.daily_closure_id` → `daily_closure.id` (Cierre diario)
- `subaccount.gl_account_id` → `gl_account.id` (Cuenta mayor "Almacén")
- `purchase.gl_account_id` → `gl_account.id` (Entradas cuando cuenta = "Almacén")

## UI Components

### CRUD View

```
┌─────────────────────────────────────────────────────────────┐
│ [InfoCard: Total Salidas de Almacén]                        │
├─────────────────────────────────────────────────────────────┤
│ [Toggle: Concentrado] [Subir archivos] [Nueva salida]       │
├─────────────────────────────────────────────────────────────┤
│ Table: Almacén | Monto | Descripción | Acciones             │
│ ─────────────────────────────────────────────────────────── │
│ Alimentos    | $1,500.00 | Insumos cocina | [✏️] [🗑️]       │
│ Bebidas      | $800.00   | Refrescos      | [✏️] [🗑️]       │
└─────────────────────────────────────────────────────────────┘
```

### Consolidated View

```
┌─────────────────────────────────────────────────────────────┐
│ [Saldo Inicial] [Entradas] [Salidas] [Saldo Final]          │
├─────────────────────────────────────────────────────────────┤
│ [Toggle: Concentrado] [Exportar Excel]                      │
├─────────────────────────────────────────────────────────────┤
│ Almacén      │ Total    │ Lun 01/Ene      │ Mar 02/Ene      │
│              │          │ Ent.  │ Sal.    │ Ent.  │ Sal.    │
│ ─────────────┼──────────┼───────┼─────────┼───────┼─────────│
│ ▼ Alimentos  │ $5,000   │       │         │       │         │
│   Saldo Ini. │ $2,000   │ -     │ -       │ -     │ -       │
│   Entrada    │ $2,000   │ $1,000│ -       │ $1,000│ -       │
│   Salida     │ $1,500   │ -     │ $800    │ -     │ $700    │
│   Saldo Fin. │ $2,500   │ -     │ -       │ -     │ -       │
│ ─────────────┼──────────┼───────┼─────────┼───────┼─────────│
│ TOTAL ENT.   │ $4,000   │ $2,000│         │ $2,000│         │
│ TOTAL SAL.   │ $3,000   │       │ $1,500  │       │ $1,500  │
└─────────────────────────────────────────────────────────────┘
```

### Modal: Nueva/Editar Salida

```
┌─────────────────────────────────────────┐
│ Nueva salida de almacén            [X]  │
├─────────────────────────────────────────┤
│ Almacén: [Select: Subcuentas]           │
│                                         │
│ Cantidad: [Input: Cifra]                │
│                                         │
│ Descripción: [Textarea]                 │
│                                         │
│              [Guardar] [Cancelar]       │
└─────────────────────────────────────────┘
```

## Business Logic

### KPI Calculations (Consolidated)

```
Saldo Inicial = SUM(Entradas hasta fecha_anterior) - SUM(Salidas hasta fecha_anterior)
Entradas = SUM(Compras con gl_account="Almacén" en rango de fechas)
Salidas = SUM(warehouse_output.amount en rango de fechas)
Saldo Final = Saldo Inicial + Entradas - Salidas
```

### User Permissions

| Role | CRUD | Concentrado | UDN Selector | Date Filter |
|------|------|-------------|--------------|-------------|
| Gerente | ✅ | ✅ | ❌ (cookie IDE) | Fecha única |
| Auxiliar | ✅ | ✅ | ❌ (cookie IDE) | Fecha única |
| Contable | ❌ | ✅ | ✅ | Rango fechas |
| Developer | ✅ | ✅ | ✅ | Ambos |

### Subaccount Filter

Las subcuentas mostradas en el select deben pertenecer a la cuenta mayor "Almacén":

```sql
SELECT s.id, s.name AS valor
FROM subaccount s
INNER JOIN gl_account g ON s.gl_account_id = g.id
WHERE g.name = 'Almacén'
AND s.active = 1
ORDER BY s.name ASC
```

## Correctness Properties

### Property 1: Data Integrity
- **Invariant**: Toda salida de almacén debe tener un `subaccount_id` válido que pertenezca a la cuenta mayor "Almacén"
- **Verification**: Validar en `addOutput()` y `editOutput()` que la subcuenta existe y pertenece a "Almacén"

### Property 2: Balance Consistency
- **Invariant**: Saldo Final = Saldo Inicial + Entradas - Salidas
- **Verification**: Los KPIs deben recalcularse cada vez que se modifica una entrada o salida

### Property 3: Soft Delete
- **Invariant**: Las eliminaciones son lógicas (active=0), nunca físicas
- **Verification**: `deleteOutput()` solo actualiza el campo `active`, no ejecuta DELETE

### Property 4: Date Filter Consistency
- **Invariant**: CRUD usa fecha única, Concentrado usa rango de fechas
- **Verification**: Al cambiar de vista, el datePicker debe cambiar de tipo

### Property 5: Permission Enforcement
- **Invariant**: Usuarios Contable solo pueden ver Concentrado
- **Verification**: Validar `userLevel` antes de mostrar opciones CRUD

### Property 6: Daily Closure Association
- **Invariant**: Toda salida debe estar asociada a un `daily_closure_id` válido
- **Verification**: Si no existe cierre para la fecha, crear uno automáticamente

## Error Handling

| Scenario | Response |
|----------|----------|
| Subcuenta no válida | `status: 400, message: "Subcuenta no válida"` |
| Monto <= 0 | `status: 400, message: "El monto debe ser mayor a 0"` |
| Salida no encontrada | `status: 404, message: "Salida no encontrada"` |
| Error de BD | `status: 500, message: "Error al procesar la solicitud"` |

## File Structure

```
finanzas/consulta/
├── salida-almacen.php
├── js/
│   └── salida-almacen.js
├── ctrl/
│   └── ctrl-salida-almacen.php
├── mdl/
│   └── mdl-salida-almacen.php
└── layout/
    └── (shared layouts)
```

## Dependencies

- **CoffeeSoft Framework**: Templates, Components, Complements
- **jQuery**: DOM manipulation
- **TailwindCSS**: Styling
- **SweetAlert2**: Confirmations
- **Bootbox**: Modals
- **DataTables**: Table pagination
- **Moment.js**: Date handling
