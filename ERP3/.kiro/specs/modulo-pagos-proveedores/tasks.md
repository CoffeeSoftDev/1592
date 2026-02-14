# Implementation Plan

- [x] 1. Set up project structure and base files



  - Create directory structure in `finanzas/consulta/`
  - Create `proveedor.php` (index file) following PIVOTE INDEX pattern
  - Create `ctrl/ctrl-proveedor.php` base structure
  - Create `mdl/mdl-proveedor.php` base structure
  - Create `js/proveedor.js` base structure extending Templates
  - _Requirements: 1.1, 2.1, 3.1_

- [x] 2. Implement Model layer (mdl-proveedor.php)



  - [x] 2.1 Create base model class extending CRUD
    - Define `$bd` and `$util` properties
    - Implement constructor with database configuration

    - _Requirements: 6.1, 6.2_
  - [x] 2.2 Implement supplier listing methods
    - `lsSuppliers()` - List active suppliers
    - `lsPaymentTypes()` - List payment types

    - `lsUDN()` - List business units
    - _Requirements: 1.1, 3.1_
  - [x] 2.3 Implement payment CRUD methods
    - `listPayments()` - List payments by date and UDN
    - `getPaymentById()` - Get single payment
    - `createPayment()` - Insert new payment

    - `updatePayment()` - Update existing payment
    - `deletePaymentById()` - Delete payment
    - _Requirements: 1.4, 1.6, 1.7_
  - [x] 2.4 Implement consolidated view methods
    - `listConsolidated()` - Grouped data by supplier with balances
    - `getSupplierBalance()` - Calculate supplier balance



    - _Requirements: 2.2, 2.4_
  - [x]* 2.5 Write property test for data round-trip consistency

    - **Property 8: Data Round-Trip Consistency**
    - **Validates: Requirements 6.2**

- [x] 3. Implement Controller layer (ctrl-proveedor.php)
  - [x] 3.1 Create base controller class extending mdl
    - Implement `init()` method returning suppliers, payment types, UDN, user level

    - _Requirements: 1.1, 3.1_
  - [x] 3.2 Implement payment listing controller
    - `ls()` - Format payments for table display with action buttons
    - Include totals calculation (total, fondo fijo, corporativo)
    - _Requirements: 1.4, 1.5_
  - [ ]* 3.3 Write property test for total calculation
    - **Property 2: Total Calculation Consistency**
    - **Validates: Requirements 1.5**

  - [x] 3.4 Implement payment CRUD controllers
    - `getPayment()` - Get payment data for edit form
    - `addPayment()` - Validate and create payment
    - `editPayment()` - Validate and update payment
    - `deletePayment()` - Delete payment with confirmation
    - _Requirements: 1.2, 1.3, 1.6, 1.7_

  - [ ]* 3.5 Write property test for payment validation
    - **Property 1: Payment Validation - Required Fields**
    - **Validates: Requirements 1.2, 1.3**
  - [x] 3.6 Implement consolidated view controller
    - `lsConsolidated()` - Format consolidated data with expandable rows
    - Include balance calculations per supplier

    - _Requirements: 2.2, 2.4_
  - [ ]* 3.7 Write property test for balance calculation
    - **Property 4: Consolidated Balance Calculation**



    - **Validates: Requirements 2.4**
  - [x] 3.8 Implement access control logic
    - Check user level for edit/delete permissions

    - Hide action buttons for level >= 2
    - _Requirements: 3.3, 3.4, 5.1, 5.2_
  - [x]* 3.9 Write property test for access control

    - **Property 6: Access Control - Read Only Mode**
    - **Validates: Requirements 3.3, 3.4, 5.1, 5.2**

- [x] 4. Checkpoint - Ensure backend tests pass
  - Backend implementation complete and tested.

- [x] 5. Implement Frontend - App Class (proveedor.js)



  - [x] 5.1 Create App class structure
    - Constructor with PROJECT_NAME
    - `render()` method calling layout and filterBar

    - _Requirements: 1.1_
  - [x] 5.2 Implement layout with tabs
    - `layout()` using primaryLayout and tabLayout

    - Tabs: "Pagos a proveedor" (active), toggle for "Concentrado"
    - _Requirements: 2.7_
  - [x] 5.3 Implement filter bar

    - Date picker (simple for level 1, range for level 2+)
    - UDN selector (visible for level 3+)
    - Action buttons (Menú principal, Nuevo pago, Concentrado toggle)

    - _Requirements: 1.8, 3.1_
  - [x]* 5.4 Write property test for date filter

    - **Property 3: Date Filter Accuracy**
    - **Validates: Requirements 1.8**

- [x] 6. Implement Frontend - Payments Class
  - [x] 6.1 Create Payments class structure



    - Constructor and render method
    - Layout for cards and table containers
    - _Requirements: 1.1_

  - [x] 6.2 Implement KPI cards
    - `renderTotalCards()` - Display totals (total, fondo fijo, corporativo)
    - Use infoCard component with light theme

    - _Requirements: 1.5_
  - [x] 6.3 Implement payments table
    - `lsPayments()` - Table with proveedor, tipo pago, monto, descripción, acciones

    - Conditional action buttons based on user level
    - _Requirements: 1.4, 5.1_
  - [x] 6.4 Implement add payment modal
    - `addPayment()` - Modal form with supplier, payment type, amount, description

    - Validation before submit
    - _Requirements: 1.1, 1.2, 1.3_



  - [x] 6.5 Implement edit payment modal
    - `editPayment(id)` - Fetch data and display pre-filled modal
    - _Requirements: 1.6_
  - [x] 6.6 Implement delete payment confirmation
    - `deletePayment(id)` - swalQuestion confirmation dialog
    - _Requirements: 1.7_
  - [ ]* 6.7 Write property test for monetary format
    - **Property 9: Monetary Format Consistency**
    - **Validates: Requirements 6.3**

- [x] 7. Implement Frontend - Consolidated Class
  - [x] 7.1 Create Consolidated class structure
    - Constructor and render method
    - Layout for consolidated view
    - _Requirements: 2.1_
  - [x] 7.2 Implement consolidated table
    - `lsConsolidated()` - Expandable table grouped by supplier




    - Columns: Proveedor, Deuda, Compras (green), Pagos (red) per date
    - _Requirements: 2.2, 2.3_

  - [x] 7.3 Implement detail modals
    - `showPurchaseDetails(supplierId, date)` - Modal with purchase details
    - `showPaymentDetails(supplierId, date)` - Modal with payment details

    - _Requirements: 2.6_
  - [x] 7.4 Implement export functionality
    - `exportToExcel()` - Generate and download Excel file
    - _Requirements: 2.5_
  - [ ]* 7.5 Write property test for UDN filter
    - **Property 5: UDN Filter Isolation**
    - **Validates: Requirements 3.2**

- [x] 8. Checkpoint - Ensure frontend integration works
  - Frontend integration complete and tested.

- [x] 9. Implement Supplier Administration (Level 4)
  - [x] 9.1 Add supplier management methods to model
    - `createSupplier()` - Insert new supplier
    - `updateSupplier()` - Update supplier
    - `existsSupplierByName()` - Check for duplicates
    - _Requirements: 4.1, 4.2, 4.3_
  - [x] 9.2 Add supplier management controllers
    - `addSupplier()` - Create with duplicate validation
    - `editSupplier()` - Update supplier
    - `statusSupplier()` - Toggle active status
    - _Requirements: 4.1, 4.3, 4.4_
  - [ ]* 9.3 Write property test for supplier uniqueness
    - **Property 7: Supplier Uniqueness**
    - **Validates: Requirements 4.3**

- [x] 10. Final integration and polish
  - [x] 10.1 Integrate with existing tab system in archivos.js
    - Add onClick handler for "Pagos a proveedor" tab
    - Load proveedor.js module on tab click
    - _Requirements: 1.1_
  - [x] 10.2 Implement view toggle functionality
    - Toggle between daily capture and consolidated view
    - Maintain filter state across views
    - _Requirements: 2.7_
  - [x] 10.3 Add helper functions
    - `formatPrice()` for monetary display
    - `renderStatus()` for status badges
    - `dropdown()` for action menus
    - _Requirements: 6.3_

- [x] 11. Final Checkpoint - Ensure all tests pass
  - All core tasks completed. Optional property tests remain for future implementation.
