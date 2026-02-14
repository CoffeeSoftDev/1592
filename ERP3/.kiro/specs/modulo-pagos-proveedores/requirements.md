# Requirements Document

## Introduction

El módulo de **Pagos a Proveedores** centraliza la gestión de pagos realizados a proveedores por cada unidad de negocio dentro del sistema ERP CoffeeSoft. Permite la captura, modificación, consulta y visualización de balances detallados según el nivel de acceso del usuario, así como la exportación de información en formato Excel.

El sistema maneja cuatro niveles de acceso:
- **Nivel 1 (Captura):** Registro y edición de pagos diarios
- **Nivel 2 (Gerencia):** Consulta de concentrados sin edición
- **Nivel 3 (Contabilidad/Dirección):** Filtrado por UDN sin edición
- **Nivel 4 (Administración):** Gestión de catálogo de proveedores

## Glossary

- **Supplier_Payment:** Sistema de gestión de pagos a proveedores
- **UDN:** Unidad de Negocio (Business Unit)
- **Proveedor:** Entidad externa que provee bienes o servicios
- **Pago a Proveedor:** Registro de un pago realizado a un proveedor
- **Concentrado:** Vista consolidada de compras y pagos por proveedor
- **Saldo Inicial:** Balance inicial del proveedor al inicio del período
- **Compras a Crédito:** Total de compras realizadas a crédito en el período
- **Pagos de Crédito:** Total de pagos realizados en el período
- **Saldo Final:** Balance resultante (Saldo Inicial + Compras - Pagos)
- **Tipo de Pago:** Clasificación del método de pago (Corporativo, Fondo Fijo)

## Requirements

### Requirement 1

**User Story:** As a usuario de captura (nivel uno), I want to registrar pagos a proveedores mediante un formulario sencillo, so that pueda llevar el control diario de los pagos efectuados por la unidad de negocio.

#### Acceptance Criteria

1. WHEN a user accesses the supplier payment module THEN the Supplier_Payment system SHALL display a form with fields for supplier selection, payment type, amount, and description
2. WHEN a user attempts to save a payment without selecting a supplier THEN the Supplier_Payment system SHALL prevent the save operation and display a validation message
3. WHEN a user attempts to save a payment without selecting a payment type THEN the Supplier_Payment system SHALL prevent the save operation and display a validation message
4. WHEN a user successfully saves a payment THEN the Supplier_Payment system SHALL update the daily payments table immediately
5. WHEN a user saves a payment THEN the Supplier_Payment system SHALL recalculate and display the total payments in real-time
6. WHEN a user clicks the edit button on a payment record THEN the Supplier_Payment system SHALL display a modal form pre-filled with the payment data
7. WHEN a user clicks the delete button on a payment record THEN the Supplier_Payment system SHALL display a confirmation dialog before deletion
8. WHEN a user changes the selected date THEN the Supplier_Payment system SHALL refresh the payments list for the new date

### Requirement 2

**User Story:** As a usuario de segundo nivel (Gerencia), I want to consultar el concentrado de compras y pagos por proveedor, so that pueda visualizar balances, movimientos y totales generales dentro de un rango de fechas.

#### Acceptance Criteria

1. WHEN a user accesses the consolidated view THEN the Supplier_Payment system SHALL display a date range selector
2. WHEN a user selects a date range THEN the Supplier_Payment system SHALL display the supplier balance in an expandable format grouped by supplier
3. WHEN displaying the consolidated view THEN the Supplier_Payment system SHALL separate purchases (green columns) and payments (red columns) visually
4. WHEN displaying the consolidated view THEN the Supplier_Payment system SHALL show totals for initial balance, credit purchases, credit payments, and final balance
5. WHEN a user clicks the export button THEN the Supplier_Payment system SHALL generate and download an Excel file with the consolidated data
6. WHEN a user clicks on a supplier row THEN the Supplier_Payment system SHALL display a detail modal with complete descriptions of purchases and payments
7. WHEN a user toggles the "Concentrado de proveedores" switch THEN the Supplier_Payment system SHALL switch between daily capture view and consolidated view

### Requirement 3

**User Story:** As a usuario de contabilidad o dirección (nivel tres), I want to filtrar la información por unidad de negocio, so that pueda analizar los movimientos financieros específicos de cada unidad.

#### Acceptance Criteria

1. WHEN a level 3 user accesses the module THEN the Supplier_Payment system SHALL display a UDN selector in the filter bar
2. WHEN a user selects a UDN THEN the Supplier_Payment system SHALL filter all listings to show only data from the selected business unit
3. WHEN a level 2 or higher user accesses the module THEN the Supplier_Payment system SHALL hide edit and delete buttons from the interface
4. WHEN a level 2 or higher user attempts to access capture functions via URL THEN the Supplier_Payment system SHALL deny access and redirect to read-only view

### Requirement 4

**User Story:** As a usuario de contabilidad (nivel cuatro), I want to administrar la lista de proveedores del sistema, so that pueda mantener actualizada la información utilizada en el módulo de pagos.

#### Acceptance Criteria

1. WHEN a level 4 user accesses the supplier administration THEN the Supplier_Payment system SHALL display options to add, edit, and delete suppliers
2. WHEN adding or editing a supplier THEN the Supplier_Payment system SHALL display fields for name, status (active/inactive), and type
3. WHEN a user attempts to add a supplier with a duplicate name THEN the Supplier_Payment system SHALL prevent the operation and display a validation message
4. WHEN a user changes a supplier status THEN the Supplier_Payment system SHALL maintain a history of changes

### Requirement 5

**User Story:** As a usuario de consulta (gerencia, contabilidad o dirección), I want to consultar los movimientos sin poder modificar datos, so that pueda asegurar la integridad de la información financiera.

#### Acceptance Criteria

1. WHEN a level 2, 3, or 4 user views the payments list THEN the Supplier_Payment system SHALL hide edit and delete action buttons
2. WHEN a level 2, 3, or 4 user attempts to access edit functions via direct URL THEN the Supplier_Payment system SHALL deny the request and maintain read-only mode
3. WHEN displaying data to read-only users THEN the Supplier_Payment system SHALL maintain full visibility of all payment information without modification capabilities

### Requirement 6

**User Story:** As a system administrator, I want to ensure data integrity through proper serialization, so that payment records can be reliably stored and retrieved.

#### Acceptance Criteria

1. WHEN storing payment records to the database THEN the Supplier_Payment system SHALL encode them using proper SQL parameterization
2. WHEN retrieving payment records from the database THEN the Supplier_Payment system SHALL parse and format them correctly for display
3. WHEN displaying monetary values THEN the Supplier_Payment system SHALL format them consistently with currency symbol and two decimal places
