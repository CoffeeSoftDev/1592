# Requirements Document

## Introduction

El módulo de Clientes permite administrar los movimientos de crédito de clientes dentro del sistema de Contabilidad. Incluye dos apartados principales: CRUD de movimientos (registrar, ver, editar, eliminar) y Concentrado de clientes (vista consolidada por rango de fechas). El módulo se integra con las tablas `detail_credit_customer`, `customer`, `movement_type`, `method_pay_customer` y `daily_closure` de la base de datos `rfwsmqex_gvsl_finanzas3`.

## Glossary

- **Sistema_Clientes**: Módulo de gestión de movimientos de crédito de clientes
- **Movimiento**: Registro de transacción de crédito (consumo, anticipo o pago total)
- **Consumo a crédito**: Tipo de movimiento donde el cliente adquiere productos/servicios a crédito
- **Anticipo**: Pago parcial realizado por el cliente
- **Pago total**: Liquidación completa de la deuda del cliente
- **Concentrado**: Vista consolidada de movimientos agrupados por cliente y fecha
- **UDN**: Unidad de Negocio
- **Daily_Closure**: Cierre diario que agrupa las operaciones por fecha y UDN
- **InfoCard**: Componente visual que muestra totales/KPIs

## Requirements

### Requirement 1

**User Story:** As a gerente/auxiliar, I want to register new client credit movements, so that I can track client debts and payments.

#### Acceptance Criteria

1. WHEN a user clicks "Registrar nuevo movimiento" button THEN the Sistema_Clientes SHALL display a modal form with fields: cliente (select), tipo de movimiento (select), método de pago (select), cantidad (input), descripción (textarea)
2. WHEN the user selects "Consumo a crédito" as movement type THEN the Sistema_Clientes SHALL disable the payment method field and set it to "N/A"
3. WHEN the user submits a valid movement form THEN the Sistema_Clientes SHALL create a new record in detail_credit_customer table and refresh the movements table
4. WHEN a movement is successfully created THEN the Sistema_Clientes SHALL update the InfoCards totals automatically
5. IF the user submits the form with empty required fields THEN the Sistema_Clientes SHALL display validation errors and prevent submission

### Requirement 2

**User Story:** As a gerente/auxiliar, I want to view movement details, so that I can review client transaction information.

#### Acceptance Criteria

1. WHEN a user clicks the view button on a movement row THEN the Sistema_Clientes SHALL display a modal with client information, movement details, and description
2. WHEN viewing a movement THEN the Sistema_Clientes SHALL show the current debt status of the client
3. WHEN the view modal is displayed THEN the Sistema_Clientes SHALL format monetary values with currency symbol and two decimal places

### Requirement 3

**User Story:** As a gerente/auxiliar, I want to edit existing movements, so that I can correct errors in client transactions.

#### Acceptance Criteria

1. WHEN a user clicks the edit button on a movement row THEN the Sistema_Clientes SHALL display the movement form pre-filled with existing data
2. WHEN editing a movement THEN the Sistema_Clientes SHALL show the current debt amount of the client
3. WHEN the user submits the edited form THEN the Sistema_Clientes SHALL update the record in detail_credit_customer table
4. WHEN a movement is successfully updated THEN the Sistema_Clientes SHALL refresh the table and update InfoCards totals

### Requirement 4

**User Story:** As a gerente/auxiliar, I want to delete movements, so that I can remove incorrect or cancelled transactions.

#### Acceptance Criteria

1. WHEN a user clicks the delete button on a movement row THEN the Sistema_Clientes SHALL display a confirmation dialog
2. WHEN the user confirms deletion THEN the Sistema_Clientes SHALL mark the record as inactive (soft delete) in detail_credit_customer table
3. WHEN a movement is successfully deleted THEN the Sistema_Clientes SHALL refresh the table and update InfoCards totals
4. IF the deletion fails THEN the Sistema_Clientes SHALL display an error message and maintain the current state

### Requirement 5

**User Story:** As a gerente/auxiliar, I want to view a consolidated report of client movements, so that I can analyze debts and payments across a date range.

#### Acceptance Criteria

1. WHEN a user clicks "Concentrado de clientes" toggle THEN the Sistema_Clientes SHALL switch to the consolidated view
2. WHEN displaying the consolidated view THEN the Sistema_Clientes SHALL show a table with columns: Cliente, Deuda, and dynamic date columns (Consumos/Pagos per date)
3. WHEN a date range is selected THEN the Sistema_Clientes SHALL generate columns for each date in the range with format "DÍA DD de MES" and sub-columns "CONSUMOS | PAGOS"
4. WHEN displaying client rows THEN the Sistema_Clientes SHALL calculate and show the total debt per client
5. WHEN the user clicks "Concentrado de clientes" toggle again THEN the Sistema_Clientes SHALL return to the CRUD view

### Requirement 6

**User Story:** As a gerente/auxiliar, I want to see summary totals in InfoCards, so that I can quickly understand the financial status.

#### Acceptance Criteria

1. WHEN the Clientes module loads THEN the Sistema_Clientes SHALL display three InfoCards: Total de consumos, Total de pagos y anticipos en efectivo, Total de pagos y anticipos a bancos
2. WHEN movements are created, edited, or deleted THEN the Sistema_Clientes SHALL recalculate and update all InfoCards values
3. WHEN filtering by date or movement type THEN the Sistema_Clientes SHALL update InfoCards to reflect filtered totals

### Requirement 7

**User Story:** As a gerente/auxiliar, I want to filter movements by type, so that I can focus on specific transaction categories.

#### Acceptance Criteria

1. WHEN the CRUD view is displayed THEN the Sistema_Clientes SHALL show a filter dropdown with options: "Mostrar todos los movimientos", "Consumos a crédito", "Pagos y anticipos"
2. WHEN a filter option is selected THEN the Sistema_Clientes SHALL reload the table showing only movements matching the selected type
3. WHEN "Mostrar todos los movimientos" is selected THEN the Sistema_Clientes SHALL display all movement types
