# Implementation Plan - Módulo de Compras

## Task List

- [x] 1. Set up project structure and database



  - Create directory structure: `finanzas/consulta/compras/`
  - Create subdirectories: `js/`, `ctrl/`, `mdl/`
  - Verify database tables exist: `purchase`, `gl_account`, `subaccount`, `supplier`, `payment_type`, `method_pay`, `daily_closure`
  - _Requirements: 1.1, 1.2_


- [x] 2. Create entry point (index)
  - [x] 2.1 Create `compras.php` file
    - Include session validation
    - Include layout files (head.php, core-libraries.php)
    - Include CoffeeSoft framework scripts
    - Include navbar
    - Create main container with `id="root"`


    - Include `compras.js` script

    - _Requirements: 1.1_

- [x] 3. Implement Model (mdl-compras.php)
  - [x] 3.1 Create base model structure
    - Extend CRUD class

    - Configure database connection (`rfwsmqex_gvsl_finanzas3`)
    - Initialize utilities
    - _Requirements: All_

  - [x] 3.2 Implement filter data methods
    - `lsAccounts()`: Get active accounts
    - `lsSubaccounts($array)`: Get subaccounts by account
    - `lsSuppliers($array)`: Get active suppliers

    - `lsPaymentTypes()`: Get payment types
    - `lsPaymentMethods()`: Get payment methods
    - `lsUDN()`: Get business units
    - _Requirements: 1.5, 1.6, 2.1, 2.2_

  - [x] 3.3 Implement purchase CRUD methods
    - `listPurchases($array)`: Query purchases with filters (fi, ff, udn, payment_type, method_pay)

    - `getPurchaseById($array)`: Get single purchase by ID
    - `createPurchase($array)`: Insert new purchase
    - `updatePurchase($array)`: Update existing purchase

    - `deletePurchaseById($array)`: Delete purchase by ID
    - _Requirements: 1.3, 2.5, 3.1, 3.4_



  - [x] 3.4 Implement calculation methods
    - `getPurchaseCounts($array)`: Get totals by payment type
    - `getPettyCashBalance($array)`: Calculate petty cash balance
    - `listConsolidated($array)`: Generate consolidated report data
    - `getAdministrativeExpenses($array)`: Get daily expenses by date
    - _Requirements: 1.1, 5.1, 5.4, 6.1, 9.1, 9.4_

  - [x] 3.5 Implement validation methods
    - `validateEditWindow($array)`: Check if edit/delete is allowed
    - `hasReimbursements($array)`: Check for associated reimbursements
    - `existsPurchaseByFolio($array)`: Validate unique folio
    - _Requirements: 3.5, 3.6, 3.7_

- [x] 4. Implement Controller (ctrl-compras.php)
  - [x] 4.1 Create base controller structure
    - Extend model class
    - Implement `init()` method for filter data
    - _Requirements: 1.5, 1.6_

  - [x] 4.2 Implement list purchases method
    - `ls()`: Get purchases with filters
    - Format data for table display
    - Calculate totals for InfoCards
    - Generate action buttons (view, edit, delete)
    - _Requirements: 1.1, 1.3, 1.4, 7.1, 7.2, 7.4_

  - [x] 4.3 Implement create purchase method
    - `addPurchase()`: Validate and create purchase
    - Validate petty cash balance for Fondo Fijo
    - Calculate total (subtotal + tax)
    - Set created_at timestamp
    - Return status and message
    - _Requirements: 2.1, 2.5, 2.6, 2.7, 9.1, 9.2_

  - [x] 4.4 Implement edit purchase method
    - `editPurchase()`: Validate and update purchase
    - Validate edit window (same day or before 12:00 next day)
    - Prevent modification of locked fields
    - Set updated_at timestamp and updated_by user
    - Return status and message
    - _Requirements: 3.1, 3.2, 3.5, 3.6_

  - [x] 4.5 Implement delete purchase method
    - `deletePurchase()`: Validate and delete purchase
    - Validate edit window
    - Check for reimbursement associations
    - Soft delete (set active = 0)
    - Return status and message
    - _Requirements: 3.3, 3.4, 3.5, 3.6, 3.7_

  - [x] 4.6 Implement get purchase method
    - `getPurchase()`: Get purchase details by ID
    - Include related data (account, subaccount, supplier, payment info)
    - Format amounts as currency
    - Return complete purchase object
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6_

  - [x] 4.7 Implement consolidated report method
    - `lsConsolidated()`: Generate consolidated data
    - Group by account and day
    - Calculate subtotals, taxes, and totals
    - Calculate petty cash balances
    - Format data for group table display
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 10.1, 10.2, 10.3, 10.4, 10.5_

  - [x] 4.8 Implement administrative expenses method
    - `getAdministrativeExpenses()`: Get daily expenses
    - Filter by date and UDN
    - Format data for modal display
    - Calculate totals per expense
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

  - [x] 4.9 Implement file upload method
    - `uploadPurchaseFile()`: Process uploaded file
    - Validate file format (Excel/CSV)
    - Parse and validate data
    - Import valid purchases
    - Return import summary with errors
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

  - [x] 4.10 Implement helper functions
    - `renderPurchaseType($type)`: Format purchase type badge
    - `renderPaymentMethod($method)`: Format payment method badge
    - `formatCurrency($amount)`: Format amounts as currency
    - `actionButtons($id, $date)`: Generate action buttons with validations

- [x] 5. Implement Frontend - App Class (compras.js)
  - [x] 5.1 Create base App class structure
    - Extend Templates class
    - Set PROJECT_NAME = "Compras"
    - Initialize API endpoint
    - Create global variables for filter data
    - _Requirements: 1.1_

  - [x] 5.2 Implement render method
    - Call layout()
    - Call filterBar()
    - Initialize Purchases class
    - Call purchases.render()
    - _Requirements: 1.1_

  - [x] 5.3 Implement layout method
    - Use primaryLayout with filterBar and container
    - Create toggle button for consolidated view
    - Set up container for purchases/consolidated views
    - _Requirements: 1.1, 1.2, 5.1_

  - [x] 5.4 Implement filterBar method
    - Create date range picker (for Gerente/Auxiliar)
    - Create single date input (for Contable/Developer)
    - Create UDN selector
    - Create "Tipo de pago" filter
    - Create "Método de pago" filter
    - Create "Registrar nueva compra" button
    - Create "Subir archivos" button
    - _Requirements: 1.5, 1.6, 7.1, 7.2, 8.1_

  - [x] 5.5 Implement toggleView method
    - Switch between purchases list and consolidated view
    - Update toggle button state
    - Clear and re-render active view
    - _Requirements: 5.1_

- [x] 6. Implement Frontend - Purchases Class
  - [x] 6.1 Create Purchases class structure
    - Extend App class
    - Initialize component
    - _Requirements: 1.1_

  - [x] 6.2 Implement render method
    - Create InfoCards container
    - Call updateInfoCards()
    - Call lsPurchases()
    - _Requirements: 1.1, 1.2_

  - [x] 6.3 Implement updateInfoCards method
    - Fetch purchase counts from backend
    - Display total purchases card
    - Display Fondo Fijo card (bg-green-100)
    - Display Crédito card (bg-red-100)
    - Display Corporativo card (bg-green-100)
    - _Requirements: 1.1, 1.2, 1.4, 2.7_

  - [x] 6.4 Implement lsPurchases method
    - Get filter values (date, UDN, payment type, method)
    - Call createTable with filters
    - Display folio, cuenta mayor, subcuenta, tipo de compra, método de pago
    - Add action buttons (view, edit, delete)
    - _Requirements: 1.3, 1.4, 7.3, 7.4, 7.5_

  - [x] 6.5 Implement addPurchase method
    - Create modal form with createModalForm
    - Add fields: cuenta mayor, subcuenta, tipo de compra, proveedor, método de pago, subtotal, impuesto, descripción
    - Implement dynamic field visibility (corporativo shows payment method, crédito hides it)
    - Implement cascading dropdown (cuenta mayor → subcuenta)
    - Validate form on submit
    - Call backend addPurchase
    - Refresh table and InfoCards on success
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6, 2.7_

  - [x] 6.6 Implement editPurchase method
    - Fetch purchase data by ID
    - Create modal form with pre-filled data
    - Lock amount and purchase type fields if locked
    - Validate edit window before allowing edit
    - Call backend editPurchase
    - Refresh table on success
    - _Requirements: 3.1, 3.2, 3.5, 3.6_

  - [x] 6.7 Implement deletePurchase method
    - Show confirmation dialog with swalQuestion
    - Validate edit window
    - Check for reimbursement associations
    - Call backend deletePurchase
    - Refresh table and InfoCards on success
    - _Requirements: 3.3, 3.4, 3.5, 3.6, 3.7_

  - [x] 6.8 Implement viewDetail method
    - Fetch purchase details by ID
    - Create bootbox modal
    - Display title "DETALLE DE COMPRA"
    - Show last update date and user
    - Show account information section
    - Show description (or "Sin descripción")
    - Show financial summary (subtotal, impuesto, total)
    - Highlight total amount
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5, 4.6_

  - [x] 6.9 Implement viewAdministrativeExpenses method
    - Fetch expenses by date
    - Create bootbox modal
    - Display title "GASTOS ADMINISTRATIVOS" with date
    - Show each expense with subcuenta, tipo, método, proveedor, factura
    - Show description or "Sin descripción"
    - Show subtotal and impuesto per expense
    - Format amounts as currency
    - _Requirements: 6.1, 6.2, 6.3, 6.4, 6.5_

  - [x] 6.10 Implement uploadFiles method
    - Create file upload interface
    - Validate file format (Excel/CSV)
    - Show upload progress
    - Call backend uploadPurchaseFile
    - Display import summary (success/errors)
    - Refresh table and InfoCards on success
    - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [x] 7. Implement Frontend - Consolidated Class
  - [x] 7.1 Create Consolidated class structure
    - Extend App class
    - Initialize component
    - _Requirements: 5.1_

  - [x] 7.2 Implement render method
    - Create consolidated filterBar
    - Call lsConsolidated()
    - _Requirements: 5.1, 5.3_

  - [x] 7.3 Implement filterBar method
    - Create date range picker
    - Create payment type filter
    - Create export to Excel button
    - _Requirements: 5.3, 5.5_

  - [x] 7.4 Implement lsConsolidated method
    - Get filter values (date range, payment type)
    - Call backend lsConsolidated
    - Use template_group_table for display
    - Group by Cuenta Mayor (account)
    - Show daily columns
    - Display subtotals, taxes, and totals
    - Show petty cash balances (initial, outflows, final)
    - Add detail icons for cells with data
    - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.6, 10.1, 10.2, 10.3, 10.4, 10.5_

  - [x] 7.5 Implement exportToExcel method
    - Get current consolidated data
    - Format data for Excel export
    - Generate Excel file
    - Trigger download
    - _Requirements: 5.5_

  - [x] 7.6 Implement viewGroupDetail method
    - Fetch detail data for specific account and date
    - Create modal with purchase list
    - Show individual transactions
    - Display totals
    - _Requirements: 5.6, 10.5_

- [x] 8. Implement validations and business rules
  - [x] 8.1 Implement petty cash balance validation
    - Check available balance before creating Fondo Fijo purchase
    - Display error if insufficient balance
    - Update balance after successful purchase
    - _Requirements: 9.1, 9.2, 9.3, 9.4_

  - [x] 8.2 Implement edit window validation
    - Allow edit/delete only on same day
    - Allow edit/delete before 12:00 hrs of next day
    - Display error message if outside window
    - _Requirements: 3.5, 3.6_

  - [x] 8.3 Implement reimbursement check
    - Check for associated reimbursements before delete
    - Prevent deletion if reimbursements exist
    - Display informative error message
    - _Requirements: 3.7_

  - [x] 8.4 Implement dynamic form fields
    - Show payment method field only for Corporativo
    - Hide payment method field for Crédito
    - Update subcuenta dropdown when cuenta mayor changes
    - _Requirements: 2.2, 2.3, 2.4_

- [x] 9. Checkpoint - Ensure all tests pass
  - All core functionality implemented
  - File upload functionality completed
  - Documentation and templates created
  - Ready for user testing

- [ ] 10. Final integration and testing
  - Test complete purchase registration flow
  - Test edit and delete with time restrictions
  - Test consolidated report generation
  - Test administrative expenses view
  - Test file upload and import
  - Test InfoCards real-time updates
  - Test filter combinations
  - Test role-based access
  - Verify all requirements are met
  - _Requirements: All_
