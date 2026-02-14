# Requirements Document - Módulo de Compras

## Introduction

El módulo de Compras es un sistema integral destinado a la captura, consulta y administración de compras por unidad de negocio dentro del sistema ERP de Finanzas. Permite control financiero, validaciones operativas y visualización de información en tiempo real, incluyendo gestión de fondo fijo, compras corporativas y a crédito.

## Glossary

- **System**: El módulo de compras del sistema ERP de Finanzas
- **User**: Cualquier usuario autorizado del sistema (Gerente, Auxiliar, Contable, Developer)
- **Purchase**: Registro de compra con información financiera y operativa
- **UDN**: Unidad de Negocio
- **Fondo Fijo**: Tipo de compra con presupuesto asignado y control de saldo
- **Compra Corporativa**: Compra realizada con recursos corporativos
- **Compra a Crédito**: Compra realizada a crédito con proveedor
- **Cuenta Mayor**: Categoría principal de clasificación contable
- **Subcuenta**: Clasificación específica dentro de una cuenta mayor
- **Concentrado**: Vista consolidada de compras por cuenta y día

## Requirements

### Requirement 1

**User Story:** As a user, I want to access the purchases module interface, so that I can register, query and manage purchases for my business unit.

#### Acceptance Criteria

1. WHEN the user accesses the purchases module THEN the System SHALL display InfoCards showing total purchases, petty cash purchases, credit purchases and corporate purchases
2. WHEN the user interacts with the interface THEN the System SHALL keep InfoCards visible at all times
3. WHEN the user changes the date filter THEN the System SHALL update the purchases table in real time
4. WHEN the user adds a new purchase THEN the System SHALL refresh the table and update InfoCards automatically
5. WHEN the user is Gerente or Auxiliar THEN the System SHALL provide date range filters and UDN selection
6. WHEN the user is Contabilidad or Developer THEN the System SHALL provide single date input with UDN selection

### Requirement 2

**User Story:** As a Gerente or Auxiliar, I want to register a new purchase, so that I can keep financial information updated.

#### Acceptance Criteria

1. WHEN the user clicks "Registrar nueva compra" THEN the System SHALL display a modal form with all required fields
2. WHEN the user selects a Cuenta Mayor THEN the System SHALL populate the Subcuenta dropdown with related subcategories
3. WHEN the user selects Tipo de compra as "Corporativo" THEN the System SHALL display payment method options
4. WHEN the user selects Tipo de compra as "Crédito" THEN the System SHALL hide payment method options
5. WHEN the user submits the form with valid data THEN the System SHALL save the purchase and update the table in real time
6. WHEN the user submits the form with invalid data THEN the System SHALL display validation errors
7. WHEN the purchase is saved successfully THEN the System SHALL update InfoCards with new totals

### Requirement 3

**User Story:** As a Gerente, Auxiliar or Developer, I want to edit or delete purchases, so that I can correct errors or update information.

#### Acceptance Criteria

1. WHEN the user clicks edit on a purchase THEN the System SHALL display a modal form with pre-filled data
2. WHEN the purchase is locked THEN the System SHALL prevent modification of amount and purchase type
3. WHEN the user clicks delete on a purchase THEN the System SHALL display a confirmation dialog
4. WHEN the user confirms deletion THEN the System SHALL remove the purchase and update the table
5. WHEN the purchase date is not equal to current date THEN the System SHALL prevent edit and delete operations
6. WHEN the current time is after 12:00 hrs of the next day THEN the System SHALL prevent edit and delete operations for previous day purchases
7. WHEN a petty cash purchase has associated reimbursements THEN the System SHALL prevent deletion

### Requirement 4

**User Story:** As a system user, I want to view complete purchase details, so that I can validate amounts, payment method and general information.

#### Acceptance Criteria

1. WHEN the user clicks on purchase details THEN the System SHALL display a modal with complete purchase information
2. WHEN the modal is displayed THEN the System SHALL show last update date and user who updated
3. WHEN the modal is displayed THEN the System SHALL show account information including Cuenta, Subcuenta, Tipo de compra and Método de pago
4. WHEN the modal is displayed THEN the System SHALL show description or "Sin descripción" if empty
5. WHEN the modal is displayed THEN the System SHALL show financial summary with Subtotal, Impuesto and Total in monetary format
6. WHEN the modal is displayed THEN the System SHALL highlight the Total amount visually

### Requirement 5

**User Story:** As a managerial user, I want to view a consolidated purchases report by date range, so that I can analyze expenses and petty cash balance.

#### Acceptance Criteria

1. WHEN the user activates the toggle "Concentrado de compras" THEN the System SHALL display a comparative table by Account and Day
2. WHEN the consolidated view is active THEN the System SHALL show subtotals, taxes and daily totals
3. WHEN the consolidated view is active THEN the System SHALL provide filters by date range and purchase type
4. WHEN the consolidated view is active THEN the System SHALL display initial balance, petty cash outflows and final balance
5. WHEN the user clicks export THEN the System SHALL generate an Excel file with the consolidated data
6. WHEN a cell has detail information THEN the System SHALL display a detail icon

### Requirement 6

**User Story:** As an administrative or accounting user, I want to view administrative expenses by date, so that I can validate concepts, methods and amounts.

#### Acceptance Criteria

1. WHEN the user selects a date THEN the System SHALL display a modal titled "GASTOS ADMINISTRATIVOS" with the selected date
2. WHEN the modal is displayed THEN the System SHALL show each expense with Subcuenta mayor, Tipo de compra, Método de pago, Proveedor and invoice reference
3. WHEN the modal is displayed THEN the System SHALL show description or "Sin descripción" if empty
4. WHEN the modal is displayed THEN the System SHALL show Subtotal and Impuesto for each expense in monetary format
5. WHEN the modal is displayed THEN the System SHALL calculate taxes correctly ensuring Subtotal plus Impuesto equals Total

### Requirement 7

**User Story:** As a user, I want to filter purchases by type and payment method, so that I can analyze specific categories of expenses.

#### Acceptance Criteria

1. WHEN the user selects "Tipo de pago" filter THEN the System SHALL display only purchases matching the selected type (Fondo fijo, Corporativo, Crédito)
2. WHEN the user selects "Método de pago" filter THEN the System SHALL display only purchases matching the selected payment method
3. WHEN multiple filters are applied THEN the System SHALL apply all filters simultaneously
4. WHEN filters are changed THEN the System SHALL update the table and InfoCards in real time
5. WHEN filters are cleared THEN the System SHALL display all purchases for the selected date range

### Requirement 8

**User Story:** As a system administrator, I want to upload purchase files, so that I can import bulk purchase data.

#### Acceptance Criteria

1. WHEN the user clicks "Subir archivos de compras" THEN the System SHALL display a file upload interface
2. WHEN the user selects a valid file THEN the System SHALL validate the file format
3. WHEN the file is valid THEN the System SHALL process and import the purchases
4. WHEN the import is successful THEN the System SHALL update the table and InfoCards
5. WHEN the file is invalid THEN the System SHALL display validation errors

### Requirement 9

**User Story:** As a user, I want the system to validate petty cash balance, so that I can ensure expenses do not exceed available funds.

#### Acceptance Criteria

1. WHEN a petty cash purchase is registered THEN the System SHALL validate that the amount does not exceed available balance
2. WHEN the balance is insufficient THEN the System SHALL prevent the purchase and display an error message
3. WHEN the purchase is successful THEN the System SHALL update the petty cash balance
4. WHEN viewing the consolidated report THEN the System SHALL display current petty cash balance

### Requirement 10

**User Story:** As a user, I want to view purchases organized by account categories, so that I can analyze expenses by classification.

#### Acceptance Criteria

1. WHEN the consolidated view is active THEN the System SHALL group purchases by Cuenta Mayor
2. WHEN a group is displayed THEN the System SHALL show subtotals for each Subcuenta
3. WHEN a group is displayed THEN the System SHALL calculate and display group totals
4. WHEN a group is displayed THEN the System SHALL show daily breakdown for each subcategory
5. WHEN a cell contains multiple purchases THEN the System SHALL display a detail icon to view individual transactions
