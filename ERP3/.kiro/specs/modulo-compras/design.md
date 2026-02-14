# Design Document - Módulo de Compras

## Overview

El módulo de Compras es una aplicación web basada en el framework CoffeeSoft que permite la gestión integral de compras por unidad de negocio. El sistema implementa una arquitectura MVC (Modelo-Vista-Controlador) con componentes reutilizables, validaciones en tiempo real y visualización dinámica de datos financieros.

El módulo se integra con el sistema ERP de Finanzas existente y utiliza la base de datos `rfwsmqex_gvsl_finanzas3` para almacenar y consultar información de compras, proveedores, cuentas contables y métodos de pago.

## Architecture

### Technology Stack

- **Frontend**: JavaScript (jQuery), CoffeeSoft Framework, TailwindCSS
- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Framework**: CoffeeSoft (Complements → Components → Templates)

### System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                        Frontend Layer                        │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐      │
│  │   App Class  │  │ Purchases    │  │ Consolidated │      │
│  │  (Templates) │  │    Class     │  │    Class     │      │
│  └──────────────┘  └──────────────┘  └──────────────┘      │
│         │                  │                  │              │
│         └──────────────────┴──────────────────┘              │
│                            │                                 │
│                    CoffeeSoft Framework                      │
│              (Components, Templates, Utilities)              │
└─────────────────────────────────────────────────────────────┘
                             │
                    AJAX (useFetch)
                             │
┌─────────────────────────────────────────────────────────────┐
│                       Backend Layer                          │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              ctrl-compras.php                        │   │
│  │  (Controller - Business Logic & Validation)          │   │
│  └──────────────────────────────────────────────────────┘   │
│                            │                                 │
│  ┌──────────────────────────────────────────────────────┐   │
│  │              mdl-compras.php                         │   │
│  │  (Model - Database Operations)                       │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
                             │
┌─────────────────────────────────────────────────────────────┐
│                       Database Layer                         │
│                  rfwsmqex_gvsl_finanzas3                    │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐   │
│  │ purchase │  │ supplier │  │gl_account│  │method_pay│   │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘   │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                  │
│  │subaccount│  │daily_    │  │payment_  │                  │
│  │          │  │closure   │  │type      │                  │
│  └──────────┘  └──────────┘  └──────────┘                  │
└─────────────────────────────────────────────────────────────┘
```

### Module Structure

```
finanzas/consulta/
├── compras.php                 # Entry point (index)
├── js/
│   └── compras.js             # Frontend logic
├── ctrl/
│   └── ctrl-compras.php       # Controller
└── mdl/
    └── mdl-compras.php        # Model
```

## Components and Interfaces

### Frontend Components

#### 1. App Class (Main Controller)
Extends `Templates` from CoffeeSoft framework.

**Responsibilities:**
- Initialize module and render layout
- Manage navigation between views (List/Consolidated)
- Handle global state and filters
- Coordinate between Purchases and Consolidated classes

**Key Methods:**
- `render()`: Initialize main layout
- `layout()`: Create primary layout with filterBar and container
- `filterBar()`: Render filter controls (date, UDN, toggle)
- `toggleView()`: Switch between list and consolidated views

#### 2. Purchases Class
Extends `App` class.

**Responsibilities:**
- Display purchases table
- Handle CRUD operations (Create, Read, Update, Delete)
- Manage InfoCards with totals
- Handle file uploads

**Key Methods:**
- `lsPurchases()`: List purchases with filters
- `addPurchase()`: Display modal form for new purchase
- `editPurchase(id)`: Display modal form for editing
- `deletePurchase(id)`: Confirm and delete purchase
- `viewDetail(id)`: Display purchase detail modal
- `viewAdministrativeExpenses(date)`: Display daily expenses modal
- `updateInfoCards()`: Refresh KPI cards
- `uploadFiles()`: Handle file upload interface

#### 3. Consolidated Class
Extends `App` class.

**Responsibilities:**
- Display consolidated report by account and day
- Calculate totals and balances
- Handle Excel export
- Show detail modals for grouped data

**Key Methods:**
- `lsConsolidated()`: Generate consolidated table
- `exportToExcel()`: Export data to Excel
- `viewGroupDetail(account, date)`: Show detail for specific cell

### Backend Components

#### 1. Controller (ctrl-compras.php)

**Class:** `ctrl extends mdl`

**Methods:**

- `init()`: Initialize filters and dropdown data
  - Returns: `{ udn, accounts, subaccounts, suppliers, paymentTypes, paymentMethods }`

- `ls()`: List purchases with filters
  - Input: `fi, ff, udn, payment_type_id, method_pay_id`
  - Returns: `{ row, ls, counts }`

- `addPurchase()`: Create new purchase
  - Input: `gl_account_id, subaccount_id, payment_type_id, supplier_id, method_pay_id, subtotal, tax, description`
  - Validates: Petty cash balance, required fields
  - Returns: `{ status, message }`

- `editPurchase()`: Update existing purchase
  - Input: `id, gl_account_id, subaccount_id, supplier_id, method_pay_id, subtotal, tax, description`
  - Validates: Edit window (same day or before 12:00 next day), locked fields
  - Returns: `{ status, message }`

- `deletePurchase()`: Delete purchase
  - Input: `id`
  - Validates: Edit window, reimbursement associations
  - Returns: `{ status, message }`

- `getPurchase()`: Get purchase details
  - Input: `id`
  - Returns: `{ status, message, data }`

- `lsConsolidated()`: Generate consolidated report
  - Input: `fi, ff, udn, payment_type_id`
  - Returns: `{ row, thead, totals, balances }`

- `getAdministrativeExpenses()`: Get daily expenses
  - Input: `date, udn`
  - Returns: `{ status, data }`

- `uploadPurchaseFile()`: Process uploaded file
  - Input: `file, udn`
  - Validates: File format, data integrity
  - Returns: `{ status, message, imported, errors }`

- `getPettyCashBalance()`: Get current petty cash balance
  - Input: `udn, date`
  - Returns: `{ status, balance }`

#### 2. Model (mdl-compras.php)

**Class:** `mdl extends CRUD`

**Methods:**

- `listPurchases($array)`: Query purchases with filters
- `getPurchaseById($array)`: Get single purchase
- `createPurchase($array)`: Insert new purchase
- `updatePurchase($array)`: Update purchase
- `deletePurchaseById($array)`: Delete purchase
- `lsAccounts($array)`: Get accounts for dropdown
- `lsSubaccounts($array)`: Get subaccounts by account
- `lsSuppliers($array)`: Get active suppliers
- `lsPaymentTypes($array)`: Get payment types
- `lsPaymentMethods($array)`: Get payment methods
- `lsUDN()`: Get business units
- `getPurchaseCounts($array)`: Get totals by type
- `listConsolidated($array)`: Generate consolidated data
- `getPettyCashBalance($array)`: Calculate petty cash balance
- `getAdministrativeExpenses($array)`: Get daily expenses
- `validateEditWindow($array)`: Check if edit is allowed
- `hasReimbursements($array)`: Check for associated reimbursements

## Data Models

### Database Schema

#### purchase
```sql
CREATE TABLE purchase (
    id INT PRIMARY KEY AUTO_INCREMENT,
    udn_id INT NOT NULL,
    gl_account_id INT NOT NULL,
    subaccount_id INT NOT NULL,
    supplier_id INT,
    payment_type_id INT NOT NULL,
    method_pay_id INT,
    subtotal DECIMAL(12,2) NOT NULL,
    tax DECIMAL(12,2) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    reason TEXT,
    description TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    updated_by INT,
    active TINYINT DEFAULT 1,
    daily_closure_id INT,
    FOREIGN KEY (udn_id) REFERENCES udn(idUDN),
    FOREIGN KEY (gl_account_id) REFERENCES gl_account(id),
    FOREIGN KEY (subaccount_id) REFERENCES subaccount(id),
    FOREIGN KEY (supplier_id) REFERENCES supplier(id),
    FOREIGN KEY (payment_type_id) REFERENCES payment_type(id),
    FOREIGN KEY (method_pay_id) REFERENCES method_pay(id),
    FOREIGN KEY (daily_closure_id) REFERENCES daily_closure(id)
);
```

#### gl_account (Cuenta Mayor)
```sql
CREATE TABLE gl_account (
    id INT PRIMARY KEY AUTO_INCREMENT,
    udn_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    active TINYINT DEFAULT 1
);
```

#### subaccount (Subcuenta)
```sql
CREATE TABLE subaccount (
    id INT PRIMARY KEY AUTO_INCREMENT,
    gl_account_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    active TINYINT DEFAULT 1,
    FOREIGN KEY (gl_account_id) REFERENCES gl_account(id)
);
```

#### supplier (Proveedor)
```sql
CREATE TABLE supplier (
    id INT PRIMARY KEY AUTO_INCREMENT,
    udn_id INT NOT NULL,
    name VARCHAR(50) NOT NULL,
    active TINYINT DEFAULT 1
);
```

#### payment_type (Tipo de Compra)
```sql
CREATE TABLE payment_type (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    active TINYINT DEFAULT 1
);
-- Values: Fondo Fijo, Corporativo, Crédito
```

#### method_pay (Método de Pago)
```sql
CREATE TABLE method_pay (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    active TINYINT DEFAULT 1
);
-- Values: Efectivo, Tarjeta de crédito, Transferencia, etc.
```

#### daily_closure (Cierre Diario)
```sql
CREATE TABLE daily_closure (
    id INT PRIMARY KEY AUTO_INCREMENT,
    udn_id INT NOT NULL,
    employee_id INT NOT NULL,
    total_sale_without_tax DECIMAL(12,2),
    total_sale DECIMAL(12,2),
    subtotal DECIMAL(12,2),
    tax DECIMAL(12,2),
    cash DECIMAL(12,2),
    bank DECIMAL(12,2),
    credit_consumer DECIMAL(12,2),
    credit_payment DECIMAL(12,2),
    total_payment DECIMAL(12,2),
    difference DECIMAL(12,2),
    operation_date DATE NOT NULL,
    turn ENUM('morning', 'afternoon'),
    total_suite TINYINT
);
```

### Data Flow

#### Create Purchase Flow
```
User Input → Validation → Controller → Model → Database
                ↓
         Update InfoCards
                ↓
         Refresh Table
```

#### Edit Purchase Flow
```
Get Purchase → Validate Edit Window → Pre-fill Form → User Edit → 
Validate → Controller → Model → Database → Refresh Table
```

#### Consolidated Report Flow
```
Date Range + Filters → Controller → Model → 
Group by Account/Day → Calculate Totals → 
Format Table → Display
```

## Error Handling

### Frontend Validation
- Required fields validation
- Numeric format validation for amounts
- Date range validation
- File format validation for uploads

### Backend Validation
- Edit window validation (same day or before 12:00 next day)
- Petty cash balance validation
- Reimbursement association check
- Data integrity validation
- SQL injection prevention (prepared statements)

### Error Messages
- User-friendly Spanish messages
- Specific error codes for debugging
- Validation errors displayed inline in forms
- System errors logged for admin review

## Testing Strategy

### Unit Testing
- Test CRUD operations for purchases
- Test validation functions (edit window, balance check)
- Test calculation functions (totals, taxes, balances)
- Test filter logic
- Test date range calculations

### Integration Testing
- Test form submission and table refresh
- Test InfoCards update after operations
- Test consolidated report generation
- Test file upload and import process
- Test modal interactions

### User Acceptance Testing
- Test complete purchase registration flow
- Test edit and delete operations with restrictions
- Test consolidated report with various filters
- Test administrative expenses view
- Test role-based access (Gerente, Auxiliar, Contable, Developer)

---

**Next Steps:**
1. Review and approve design document
2. Create implementation tasks
3. Develop frontend components
4. Develop backend controllers and models
5. Implement testing
6. Deploy and validate
