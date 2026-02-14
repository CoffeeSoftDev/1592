# Implementation Tasks

## Overview

Este documento define las tareas de implementación para el módulo **Agregar Ventas (Add Sales)** del sistema de Contabilidad. Las tareas están organizadas en 9 fases de desarrollo con una estimación total de **15 días**.

## Task Organization

Las tareas siguen la estructura MVC de CoffeeSoft:
- **Frontend (JS)**: Clase `AddSales` extendiendo `Templates`
- **Controller (PHP)**: `ctrl-add-sales.php` con métodos CRUD
- **Model (PHP)**: `mdl-add-sales.php` con consultas SQL

## Nomenclature Rules

**Frontend Methods:**
- `ls[Entidad]()` - Para listar/mostrar datos
- `add[Entidad]()` - Para agregar registros
- `edit[Entidad]()` - Para editar registros

**Controller Methods:**
- `init()` - Inicializar datos/filtros
- `ls()` - Listar registros
- `add[Entidad]()` - Agregar registros
- `edit[Entidad]()` - Editar registros
- `get[Entidad]()` - Obtener registro específico

**Model Methods:**
- `list[Entidad]()` - Listar registros
- `create[Entidad]()` - Crear registros
- `update[Entidad]()` - Actualizar registros
- `get[Entidad]ById()` - Obtener por ID

## Phase 1: Frontend Structure (2 days)

### Task 1.1: Create AddSales Class
**Requirements:** 1.1, 2.1, 10.1, 10.2

Create the main `AddSales` class in `finanzas/consulta_respaldo/js/add-sales.js`:
- Extend `Templates` from CoffeeSoft framework
- Define constructor with `link` and `div_modulo` parameters
- Set `PROJECT_NAME = "AddSales"`
- Initialize class properties: `dailyClosureId`, `saleCategories`, `cashConcepts`, `foreignCurrencies`, `bankAccounts`, `taxes`
- Implement `render()` method to orchestrate layout sequence

**Acceptance Criteria:**
- Class extends Templates correctly
- All properties initialized
- render() calls layout(), filterBar(), loadExistingData() in sequence


### Task 1.2: Implement Primary Layout
**Requirements:** 1.1, 2.1, 10.1, 10.2

Implement `layout()` method using `primaryLayout()` component:
- Create parent container with id `AddSales`
- Define filterBar container: `filterBarAddSales`
- Define main container: `containerAddSales`
- Use TailwindCSS classes for responsive design
- Ensure two-column layout on screens >= 768px
- Stack vertically on screens < 768px

**Acceptance Criteria:**
- primaryLayout() renders correctly
- Containers have correct IDs
- Responsive behavior works on mobile/tablet/desktop
- **Property 36**: Two cards side by side on large screens
- **Property 37**: Cards stacked on small screens

### Task 1.3: Create FilterBar Component
**Requirements:** 4.1, 4.4

Implement `filterBar()` method using `createfilterBar()`:
- Add UDN select (if user has permissions)
- Add date input with validation
- Add "Soft Restaurant" button (orange color) that opens new tab
- Integrate with `monthly_module_lock` for date validation
- Show visual indicator of date status (enabled/blocked)

**Acceptance Criteria:**
- FilterBar renders with all components
- UDN select loads dynamically
- Date validation works correctly
- Soft Restaurant button opens correct URL in new tab
- **Property 6**: Save button disabled when date blocked
- **Property 7**: Save button enabled when date valid

### Task 1.4: Create Two-Card Layout Structure
**Requirements:** 1.1, 2.1

Create container structure for two main cards:
- Card 1: "Ventas del Día" (Sales of the Day)
- Card 2: "Formas de Pago" (Payment Methods)
- Use grid layout with responsive breakpoints
- Add card headers with titles
- Prepare containers for dynamic content

**Acceptance Criteria:**
- Two cards render side by side on desktop
- Cards stack vertically on mobile
- Card headers display correctly
- Containers ready for dynamic content injection

### Task 1.5: Checkpoint - Frontend Structure Complete
**Requirements:** All Phase 1 requirements

Verify that all frontend structure components are working:
- Run manual tests on different screen sizes
- Verify all containers render correctly
- Test filterBar interactions
- Validate responsive behavior

**Acceptance Criteria:**
- All Phase 1 tasks completed
- No console errors
- Responsive design works on all breakpoints
- Ready for Phase 2 (Data Loading)


## Phase 2: Data Loading (1 day)

### Task 2.1: Implement init() Method - Frontend
**Requirements:** 1.4, 2.2, 2.4, 2.6

Implement `init()` method in AddSales class:
- Make AJAX call with `opc: "init"`
- Store response data in class properties:
  - `this.saleCategories` from response.saleCategories
  - `this.cashConcepts` from response.cashConcepts
  - `this.foreignCurrencies` from response.foreignCurrencies
  - `this.bankAccounts` from response.bankAccounts
  - `this.taxes` from response.taxes
- Handle loading states
- Handle errors gracefully

**Acceptance Criteria:**
- AJAX call executes successfully
- All data stored in class properties
- Loading indicator shown during request
- Error messages displayed if request fails
- **Property 31**: Sale categories loaded by UDN
- **Property 32**: Cash concepts loaded by UDN
- **Property 33**: Foreign currencies loaded with exchange rates
- **Property 34**: Bank accounts loaded with bank names

### Task 2.2: Create init() Method - Controller
**Requirements:** 1.4, 2.2, 2.4, 2.6

Create `init()` method in `ctrl-add-sales.php`:
- Call model methods to fetch catalogs:
  - `listSaleCategories()` filtered by UDN
  - `listCashConcepts()` filtered by UDN
  - `listForeignCurrencies()` with exchange rates
  - `listBankAccounts()` filtered by UDN with bank names
  - `listTaxes()` active taxes
- Return array with all catalog data
- Handle empty results

**Acceptance Criteria:**
- Method returns complete catalog data
- All arrays properly formatted
- Empty arrays handled gracefully
- Response structure matches frontend expectations


### Task 2.3: Create Model Methods for Catalogs
**Requirements:** 1.4, 2.2, 2.4, 2.6, 2.7

Create catalog query methods in `mdl-add-sales.php`:
- `listSaleCategories($array)` - Query `sale_category` by UDN and active=1
- `listCashConcepts($array)` - Query `cash_concept` by UDN and active=1
- `listForeignCurrencies($array)` - Query `foreing_currency` with exchange_rate where active=1
- `listBankAccounts($array)` - Query `bank_account` JOIN `bank` by UDN and active=1
- `listTaxes($array)` - Query `tax` where active=1
- Use `_Read()` method for all SELECT queries
- Return arrays with proper field mappings

**Acceptance Criteria:**
- All methods use _Read() correctly
- Queries filter by UDN where applicable
- Only active records returned
- Bank accounts include bank name from JOIN
- Foreign currencies include exchange_rate
- Field names match frontend expectations

### Task 2.4: Implement loadExistingData() Method
**Requirements:** 5.1, 5.2, 9.1, 9.2, 9.3, 9.4, 9.5

Implement `loadExistingData()` method in AddSales class:
- Query if `daily_closure` exists for selected date/UDN
- If exists:
  - Store `daily_closure_id` in `this.dailyClosureId`
  - Load all detail records (sales, taxes, payments)
  - Fill form fields with loaded data
  - Set mode to "edit"
- If not exists:
  - Set `this.dailyClosureId = null`
  - Initialize all fields to zero
  - Set mode to "create"
- Show visual indicator of current mode

**Acceptance Criteria:**
- Correctly detects existing vs new records
- All detail data loaded when exists
- Fields initialized to zero when new
- Mode indicator visible to user
- **Property 8**: Loads existing data for editing
- **Property 9**: Creates new record when none exists
- **Property 25**: All detail tables loaded
- **Property 26**: Empty state initialized correctly
- **Property 27**: daily_closure_id stored in class


### Task 2.5: Create Dynamic Form Generation
**Requirements:** 1.4, 1.5, 2.2, 2.3

Implement methods to generate dynamic forms:
- `createVentasCard()` - Generate Card 1 with sale category inputs
  - Iterate over `this.saleCategories`
  - Create numeric input for each category
  - Add separate fields for Descuentos and Cortesías
  - Add SummaryCard for totals
  - Bind onchange events to `calculateTaxes()`
- `createPagosCard()` - Generate Card 2 with payment inputs
  - Section 1: Cash concepts (iterate `this.cashConcepts`)
  - Section 2: Foreign currencies (iterate `this.foreignCurrencies`)
  - Section 3: Bank accounts (iterate `this.bankAccounts`)
  - Section 4: Credit customers (read-only)
  - Add SummaryCard for payment totals
  - Bind onchange events to `calculateDifference()`

**Acceptance Criteria:**
- Both cards render with dynamic inputs
- All inputs have correct data attributes (category_id, concept_id, etc.)
- SummaryCards display initial values
- Events bound correctly
- **Property 35**: Credit customer data shown read-only

### Task 2.6: Checkpoint - Data Loading Complete
**Requirements:** All Phase 2 requirements

Verify data loading functionality:
- Test init() with different UDNs
- Test loadExistingData() with existing and new records
- Verify all catalogs load correctly
- Test dynamic form generation

**Acceptance Criteria:**
- All Phase 2 tasks completed
- Catalogs load without errors
- Existing data loads correctly
- New records initialize properly
- Forms generate dynamically
- Ready for Phase 3 (Tax Calculation)


## Phase 3: Tax Calculation (2 days)

### Task 3.1: Initialize Tax Cache in Constructor
**Requirements:** 3.2, 3.5

Add cache initialization in AddSales constructor:
- Add property: `this.saleCategoryTaxCache = {}`
- Cache will store tax data by category ID to optimize performance
- Cache structure: `{ categoryId: [{ tax_id, name, percentage }, ...] }`

**Acceptance Criteria:**
- Cache property initialized in constructor
- Cache structure documented
- Ready for use in calculateTaxes()

### Task 3.2: Implement calculateTaxes() Method
**Requirements:** 3.1, 3.2, 3.3, 3.4, 3.5

Implement `calculateTaxes()` method in AddSales class with dynamic tax lookup:
- Get values from all sale category inputs
- **For each category with value > 0:**
  1. Check if taxes are in `this.saleCategoryTaxCache[categoryId]`
  2. If not in cache, call `getSaleCategoryTaxes(categoryId)` to fetch from backend
  3. Store result in cache for future use
  4. Iterate over each tax associated with the category
  5. Calculate: `tax_amount = (sale * tax.percentage) / 100`
  6. Sum all taxes for the category
- **Calculate taxes for descuentos and cortesías:**
  - Use same tax percentages from each category
  - Calculate: `discount_tax = (discount * tax.percentage) / 100`
  - Calculate: `courtesy_tax = (courtesy * tax.percentage) / 100`
- **Error handling:**
  - If category has no taxes, log warning to console
  - Continue processing without blocking
  - Don't prevent save operation
- Update tax display fields in UI
- Update SummaryCard with totals
- Return object with: `{ ventas, descuentos, cortesias, impuestos, total }`

**Acceptance Criteria:**
- Taxes calculated correctly for single tax
- Multiple taxes summed correctly (e.g., IVA 8% + HOSPEDAJE 2%)
- Discount and courtesy taxes calculated separately
- Cache lookup works before backend query
- Missing taxes handled gracefully
- UI updates immediately
- SummaryCard shows correct totals
- **Property 1**: Tax calculation accuracy
- **Property 2**: Multiple tax summation
- **Property 3**: Discount tax calculation
- **Property 4**: Courtesy tax calculation
- **Property 5**: Real-time update within 100ms

### Task 3.2.1: Create getSaleCategoryTaxes() Helper Method - Frontend
**Requirements:** 3.2, 3.3

Create helper method in AddSales class to query tax associations:
- **Method signature:** `async getSaleCategoryTaxes(categoryId)`
- **Cache verification:**
  - Check if `this.saleCategoryTaxCache[categoryId]` exists
  - If exists, return cached data immediately (performance optimization)
- **Backend query (if not cached):**
  - Make AJAX call with `useFetch()`
  - Payload: `{ opc: "getSaleCategoryTaxes", category_id: categoryId }`
  - Backend queries: `sale_category_tax` JOIN `tax` WHERE `sale_category_id = ?`
- **Response format:**
  ```javascript
  [
    { tax_id: 1, name: "IVA", percentage: 8.00 },
    { tax_id: 3, name: "HOSPEDAJE", percentage: 2.00 }
  ]
  ```
- **Cache storage:**
  - Store result in `this.saleCategoryTaxCache[categoryId]`
  - Subsequent calls return cached data
- **Error handling:**
  - Return empty array if no taxes found
  - Return empty array on network error
  - Log error to console for debugging

**Acceptance Criteria:**
- Method queries backend correctly
- Cache lookup works before query
- Returns proper tax data structure: `[{ tax_id, name, percentage }]`
- Handles missing taxes gracefully (empty array)
- Caching prevents repeated queries
- Network errors handled without blocking

### Task 3.2.2: Create getSaleCategoryTaxes() Method - Controller
**Requirements:** 3.2, 3.3

Create controller method in `ctrl-add-sales.php`:
- **Method name:** `getSaleCategoryTaxes()`
- **Input:** `$_POST['category_id']`
- **Process:**
  - Call model method: `$this->getSaleCategoryTaxes([$_POST['category_id']])`
  - Return array of tax objects
- **Response structure:**
  ```php
  [
      'status' => 200,
      'data' => [
          ['tax_id' => 1, 'name' => 'IVA', 'percentage' => 8.00],
          ['tax_id' => 3, 'name' => 'HOSPEDAJE', 'percentage' => 2.00]
      ]
  ]
  ```
- **Error handling:**
  - Return empty array if no taxes found (not an error)
  - Return status 200 even with empty array

**Acceptance Criteria:**
- Method receives category_id from POST
- Calls model method correctly
- Returns proper response structure
- Empty array handled as valid response
- **NO** use of `??` or `isset()` with `$_POST`

### Task 3.2.3: Create getSaleCategoryTaxes() Method - Model
**Requirements:** 3.2, 3.3

Create model method in `mdl-add-sales.php`:
- **Method name:** `getSaleCategoryTaxes($array)`
- **Query:**
  ```sql
  SELECT 
      t.id AS tax_id,
      t.name,
      t.percentage
  FROM {$this->bd}sale_category_tax sct
  INNER JOIN {$this->bd}tax t ON sct.tax_id = t.id
  WHERE sct.sale_category_id = ?
  AND t.active = 1
  ORDER BY t.id ASC
  ```
- **Use `_Read()` method** for SELECT query
- **Return:** Array of tax objects or empty array if none found

**Acceptance Criteria:**
- Query uses INNER JOIN correctly
- Filters by sale_category_id
- Only returns active taxes
- Uses `_Read()` method (not `_Select()`)
- Returns empty array if no taxes found
- Field names match frontend expectations


### Task 3.3: Bind Real-time Tax Calculation Events
**Requirements:** 1.8, 3.5

Bind onchange events to trigger tax recalculation with debouncing:
- Attach event listeners to all sale category inputs
- Attach event listeners to discount and courtesy inputs
- **Implement debouncing:**
  - Use 50ms delay to prevent excessive calculations
  - Clear previous timeout on new input
  - Execute calculation after delay
- **Performance requirements:**
  - Ensure calculations complete within 100ms
  - Use cached tax data to optimize speed
  - Update UI smoothly without flickering
- **Event binding pattern:**
  ```javascript
  let taxCalculationTimeout;
  $('.sale-input, .discount-input, .courtesy-input').on('input', function() {
      clearTimeout(taxCalculationTimeout);
      taxCalculationTimeout = setTimeout(() => {
          addSales.calculateTaxes();
      }, 50);
  });
  ```

**Acceptance Criteria:**
- Events fire on input change
- Debouncing prevents excessive calls (50ms delay)
- Calculations complete within 100ms
- UI updates smoothly without flickering
- No performance issues with multiple rapid changes
- Cache optimization reduces backend calls
- **Property 5**: Real-time update within 100ms
- **Property 22**: Difference updates within 100ms

### Task 3.4: Update SummaryCard Components with Tax Data
**Requirements:** 1.9, 3.5

Update SummaryCard to show calculated totals including taxes:
- **Card 1 SummaryCard items:**
  - Ventas (sum of all category sales)
  - Descuentos (total discounts)
  - Cortesías (total courtesies)
  - Impuestos (total calculated taxes from all categories)
  - Total de Venta (ventas - descuentos - cortesías + impuestos)
- **Formatting:**
  - Use `formatPrice()` for all amounts
  - Use appropriate colors for each item:
    - Ventas: text-gray-700
    - Descuentos: text-red-600
    - Cortesías: text-orange-600
    - Impuestos: text-blue-600
    - Total: text-green-600 (bold)
- **Real-time updates:**
  - Update immediately when calculateTaxes() completes
  - Smooth transitions without flickering
  - Maintain visual consistency

**Acceptance Criteria:**
- SummaryCard displays all required items
- Tax calculations are correct and visible
- Formatting is consistent with formatPrice()
- Colors applied correctly
- Updates in real-time as values change
- **Property 28**: Summary card updates correctly

### Task 3.5: Implement Cache Invalidation Strategy
**Requirements:** 3.2, 3.5

Implement cache invalidation for tax data:
- **When to invalidate:**
  - When UDN changes (different categories may have different taxes)
  - When user explicitly refreshes data
  - On module re-initialization
- **Invalidation method:**
  ```javascript
  clearTaxCache() {
      this.saleCategoryTaxCache = {};
  }
  ```
- **Integration points:**
  - Call `clearTaxCache()` in UDN change handler
  - Call `clearTaxCache()` in `init()` method
  - Provide manual refresh option if needed

**Acceptance Criteria:**
- Cache cleared when UDN changes
- Cache cleared on module initialization
- Fresh data loaded after cache clear
- No stale tax data displayed

### Task 3.6: *Write Unit Tests for Tax Calculation
**Requirements:** 3.1, 3.2, 3.3, 3.4

Write unit tests for tax calculation logic:
- **Test 1:** Single tax calculation (IVA 8%)
  - Input: sale=1000, tax=8%
  - Expected: 80
- **Test 2:** Multiple taxes (IVA 8% + HOSPEDAJE 2%)
  - Input: sale=1000, taxes=[8%, 2%]
  - Expected: 100
- **Test 3:** Zero sale amount edge case
  - Input: sale=0, tax=8%
  - Expected: 0
- **Test 4:** Discount tax calculation
  - Input: discount=100, tax=8%
  - Expected: 8
- **Test 5:** Courtesy tax calculation
  - Input: courtesy=50, tax=8%
  - Expected: 4
- **Test 6:** Cache lookup functionality
  - Verify cache hit returns data without backend call
  - Verify cache miss triggers backend call
- **Test 7:** Missing taxes handling
  - Verify empty array returned for category without taxes
  - Verify calculation continues without blocking

**Acceptance Criteria:**
- All unit tests pass
- Edge cases covered (zero amounts, missing taxes)
- Cache functionality tested
- Test coverage >= 95% for tax calculation code

### Task 3.7: *Write Property-Based Tests for Tax Calculation
**Requirements:** 3.1, 3.2

Write property-based tests using fast-check:
- **Property Test 1**: Tax calculation accuracy for any sale amount and percentage
  - Generate random sale amounts (0 to 1,000,000)
  - Generate random tax percentages (0 to 100)
  - Verify: `calculated_tax = (sale * percentage) / 100`
  - Run 100 iterations minimum
  - Tolerance: 0.01 for floating point precision
- **Property Test 2**: Multiple tax summation for any number of taxes
  - Generate random sale amount
  - Generate array of random tax percentages (1-5 taxes)
  - Verify: `total_tax = sum of all individual taxes`
  - Run 100 iterations minimum
  - Tolerance: 0.01 for floating point precision
- **Property Test 3**: Tax lookup accuracy
  - Generate random category IDs
  - Verify: returned taxes match database records
  - Verify: cache returns same data as fresh query
  - Run 100 iterations minimum

**Acceptance Criteria:**
- Property tests run with 100+ iterations
- All iterations pass
- Tests cover universal properties
- Floating point tolerance handled correctly
- **Property 1**: Validated with property test
- **Property 2**: Validated with property test

### Task 3.8: Checkpoint - Tax Calculation Complete
**Requirements:** All Phase 3 requirements

Verify tax calculation functionality:
- Test with single tax categories (e.g., Cortes with IVA 8%)
- Test with multiple tax categories (e.g., Hospedaje with IVA 8% + HOSPEDAJE 2%)
- Test with discount and courtesy values
- Verify real-time updates work smoothly
- Verify cache optimization reduces backend calls
- Run all unit and property tests
- Verify performance within 100ms

**Acceptance Criteria:**
- All Phase 3 tasks completed
- Tax calculations accurate for all scenarios
- Multiple taxes summed correctly
- Discount/courtesy taxes calculated separately
- Real-time updates working within 100ms
- Cache optimization functional
- All tests passing (unit + property)
- Performance benchmarks met
- Ready for Phase 4 (Payment Calculations)


## Phase 4: Payment Calculations (1 day)

### Task 4.1: Implement Foreign Currency MXN Calculation
**Requirements:** 2.5

Implement automatic MXN conversion for foreign currencies:
- Bind onchange event to foreign currency amount inputs
- Get exchange rate from `this.foreignCurrencies` data
- Calculate: `amount_mxn = amount * exchange_rate`
- Display calculated MXN amount in read-only field
- Update payment totals immediately

**Acceptance Criteria:**
- MXN calculation is accurate
- Calculation happens in real-time
- Read-only MXN field updates correctly
- **Property 23**: MXN = amount × exchange_rate

### Task 4.2: Implement calculateDifference() Method
**Requirements:** 8.1, 8.2, 8.3, 8.4, 8.5

Implement `calculateDifference()` method:
- Get `totalVenta` from Card 1 SummaryCard
- Get `totalPagado` from Card 2 SummaryCard
- Calculate: `diferencia = totalVenta - totalPagado`
- Apply color coding:
  - Green if `diferencia >= 0`
  - Red if `diferencia < 0`
  - Neutral if `diferencia === 0`
- Update Global SummaryCard with difference
- Format with `formatPrice()`

**Acceptance Criteria:**
- Difference calculation is correct
- Color coding works properly
- Updates in real-time
- **Property 18**: Difference = Total Venta - Total Pagado
- **Property 19**: Positive difference shown in green
- **Property 20**: Negative difference shown in red
- **Property 21**: Zero difference shown in neutral color
- **Property 22**: Updates within 100ms


### Task 4.3: Update Payment SummaryCard
**Requirements:** 2.9, 8.1

Update Card 2 SummaryCard to show payment totals:
- Efectivo (sum of all cash concepts)
- Moneda Extranjera (sum of all foreign currency MXN amounts)
- Bancos (sum of all bank account amounts)
- Crédito (sum from credit customer details - read only)
- Total Pagado (sum of all payment types)
- Update in real-time as payment values change

**Acceptance Criteria:**
- SummaryCard displays all payment types
- Calculations are correct
- Updates in real-time
- **Property 29**: Payment summary card updates correctly

### Task 4.4: Create Global SummaryCard
**Requirements:** 8.1, 8.5

Create global SummaryCard showing overall totals:
- Total de Venta (from Card 1)
- Total Pagado (from Card 2)
- Diferencia (calculated with color coding)
- Position below both cards
- Update whenever either total changes

**Acceptance Criteria:**
- Global SummaryCard renders correctly
- Shows all three values
- Difference has correct color
- Updates in real-time
- **Property 30**: Global summary card updates correctly

### Task 4.5: *Write Unit Tests for Payment Calculations
**Requirements:** 8.1, 8.2, 8.3

Write unit tests for payment calculation logic:
- Test positive difference calculation
- Test negative difference calculation
- Test zero difference edge case
- Test foreign currency MXN conversion
- Verify color coding for each case

**Acceptance Criteria:**
- All unit tests pass
- Edge cases covered
- Test coverage >= 95% for payment calculation code


### Task 4.6: *Write Property-Based Tests for Calculations
**Requirements:** 8.1, 2.5

Write property-based tests for universal calculation properties:
- **Property Test 18**: Difference calculation for any sale/payment values
  - Generate random totalVenta (0 to 1,000,000)
  - Generate random totalPagado (0 to 1,000,000)
  - Verify: `difference = totalVenta - totalPagado`
  - Run 100 iterations minimum
- **Property Test 23**: Foreign currency MXN for any amount/rate
  - Generate random amount (0 to 100,000)
  - Generate random exchange_rate (1 to 100)
  - Verify: `amount_mxn = amount * exchange_rate`
  - Run 100 iterations minimum
- **Property Test 24**: Net sale calculation
  - Generate random sale, discount, courtesy
  - Verify: `net_sale = sale - discount - courtesy`
  - Run 100 iterations minimum

**Acceptance Criteria:**
- Property tests run with 100+ iterations
- All iterations pass
- **Property 18**: Validated with property test
- **Property 23**: Validated with property test
- **Property 24**: Validated with property test

### Task 4.7: Checkpoint - Payment Calculations Complete
**Requirements:** All Phase 4 requirements

Verify payment calculation functionality:
- Test foreign currency conversions
- Test difference calculations with various scenarios
- Verify color coding
- Test real-time updates
- Run all unit and property tests

**Acceptance Criteria:**
- All Phase 4 tasks completed
- Payment calculations accurate
- Difference calculation working
- Color coding correct
- All tests passing
- Ready for Phase 5 (Persistence)


## Phase 5: Persistence (3 days)

### Task 5.1: Implement saveSales() Method - Frontend
**Requirements:** 6.1, 6.2, 6.3, 6.4, 6.5

Implement `saveSales()` method in AddSales class:
- Validate all required fields are complete
- Check if `this.dailyClosureId` exists
  - If not, call `createDailyClosure()` first
- Collect data from all sale category inputs
- Collect data from discount and courtesy inputs
- Collect calculated tax data
- Structure payload for AJAX request
- Determine operation: `opc: "addSales"` or `opc: "editSales"`
- Send request with `useFetch()`
- Handle response:
  - Status 200: Show success message with `alert()`
  - Status != 200: Show error message with retry option
- Reload data after successful save

**Acceptance Criteria:**
- Validation prevents saving incomplete data
- daily_closure created if needed
- Correct operation (add vs edit) determined
- Payload structured correctly
- Success/error messages shown
- Data reloaded after save
- **Property 10**: Required field validation
- **Property 11**: Sales detail persistence
- **Property 12**: Tax detail persistence
- **Property 16**: Success message displayed
- **Property 17**: Error message displayed

### Task 5.2: Implement savePayments() Method - Frontend
**Requirements:** 7.1, 7.2, 7.3, 7.4, 7.5, 7.6

Implement `savePayments()` method in AddSales class:
- Validate `this.dailyClosureId` exists
- Collect data from cash concept inputs
- Collect data from foreign currency inputs (with MXN amounts)
- Collect data from bank account inputs
- Structure payload for AJAX request
- Determine operation: `opc: "addPayments"` or `opc: "editPayments"`
- Send request with `useFetch()`
- Handle response similar to saveSales()
- Reload data after successful save

**Acceptance Criteria:**
- daily_closure_id validation works
- All payment data collected correctly
- Foreign currency includes MXN amounts
- Correct operation determined
- Success/error messages shown
- Data reloaded after save
- **Property 13**: Cash concept persistence
- **Property 14**: Bank account persistence
- **Property 15**: Foreign currency persistence


### Task 5.3: Create addSales() Method - Controller
**Requirements:** 6.2, 6.3

Create `addSales()` method in `ctrl-add-sales.php`:
- Validate `daily_closure_id` exists
- Initialize transaction tracking array
- For each sale category in `$_POST['sales']`:
  - Call `createSaleDetail()` from model
  - Store created ID for rollback if needed
  - If creation fails:
    - Rollback: delete all previously created records
    - Return error status with descriptive message
- For each tax in calculated taxes:
  - Call `createSaleTaxDetail()` from model
  - Handle failures with rollback
- Update totals in `daily_closure` table
- Return success status and message

**Acceptance Criteria:**
- Method validates daily_closure_id
- All sales details created correctly
- All tax details created correctly
- Rollback works on partial failure
- Totals updated in daily_closure
- Proper status/message returned
- **NO** use of `??` or `isset()` with `$_POST`
- **NO** try-catch blocks

### Task 5.4: Create editSales() Method - Controller
**Requirements:** 6.2, 6.3

Create `editSales()` method in `ctrl-add-sales.php`:
- Validate `daily_closure_id` exists
- Delete existing detail records for this daily_closure
- Call `addSales()` logic to recreate records with new data
- Update totals in `daily_closure` table
- Return success status and message

**Acceptance Criteria:**
- Method validates daily_closure_id
- Existing records deleted before update
- New records created correctly
- Totals updated
- Proper status/message returned


### Task 5.5: Create addPayments() Method - Controller
**Requirements:** 7.2, 7.3, 7.4

Create `addPayments()` method in `ctrl-add-sales.php`:
- Validate `daily_closure_id` exists
- Initialize transaction tracking
- For each cash concept in `$_POST['cash_concepts']`:
  - Call `createCashConceptDetail()` from model
  - Handle failures with rollback
- For each foreign currency in `$_POST['foreign_currencies']`:
  - Call `createForeignCurrencyDetail()` from model
  - Ensure MXN amount is included
  - Handle failures with rollback
- For each bank account in `$_POST['bank_accounts']`:
  - Call `createBankAccountDetail()` from model
  - Handle failures with rollback
- Update payment totals in `daily_closure` table
- Return success status and message

**Acceptance Criteria:**
- Method validates daily_closure_id
- All payment details created correctly
- Rollback works on partial failure
- Totals updated in daily_closure
- Proper status/message returned

### Task 5.6: Create editPayments() Method - Controller
**Requirements:** 7.2, 7.3, 7.4

Create `editPayments()` method in `ctrl-add-sales.php`:
- Validate `daily_closure_id` exists
- Delete existing payment detail records
- Call `addPayments()` logic to recreate with new data
- Update payment totals in `daily_closure` table
- Return success status and message

**Acceptance Criteria:**
- Method validates daily_closure_id
- Existing records deleted before update
- New records created correctly
- Totals updated
- Proper status/message returned


### Task 5.7: Create Model Methods for Sales Persistence
**Requirements:** 6.2, 6.3

Create persistence methods in `mdl-add-sales.php`:
- `createSaleDetail($array)` - Insert into `detail_sale_category`
  - Fields: sale, net_sale, discount, courtesy, daily_closure_id, sale_category_id
  - Use `_Insert()` method
  - Return inserted ID
- `createSaleTaxDetail($array)` - Insert into `detail_sale_category_tax`
  - Fields: sale_tax, discount_tax, courtesy_tax, detail_sale_category_id, sale_category_tax_id
  - Use `_Insert()` method
  - Return inserted ID
- `deleteSaleDetails($array)` - Delete from `detail_sale_category` by daily_closure_id
  - Use `_Delete()` method
- `deleteSaleTaxDetails($array)` - Delete from `detail_sale_category_tax` by detail_sale_category_id
  - Use `_Delete()` method

**Acceptance Criteria:**
- All methods use correct CRUD methods
- Field mappings are correct
- Methods return proper values
- Delete methods work correctly

### Task 5.8: Create Model Methods for Payment Persistence
**Requirements:** 7.2, 7.3, 7.4

Create persistence methods in `mdl-add-sales.php`:
- `createCashConceptDetail($array)` - Insert into `detail_cash_concept`
  - Fields: daily_closure_id, cash_concept_id, amount
  - Use `_Insert()` method
- `createForeignCurrencyDetail($array)` - Insert into `detail_foreing_currency`
  - Fields: foreing_currency_id, exchange_rate, amount, amount_mxn, daily_closure_id
  - Use `_Insert()` method
- `createBankAccountDetail($array)` - Insert into `detail_bank_account`
  - Fields: daily_closure_id, bank_account_id, amount
  - Use `_Insert()` method
- `deleteCashConceptDetails($array)` - Delete by daily_closure_id
- `deleteForeignCurrencyDetails($array)` - Delete by daily_closure_id
- `deleteBankAccountDetails($array)` - Delete by daily_closure_id

**Acceptance Criteria:**
- All methods use correct CRUD methods
- Field mappings match database schema
- Foreign currency table name uses `foreing_currency` (with typo)
- Delete methods work correctly


### Task 5.9: Create createDailyClosure() Method
**Requirements:** 5.3

Create method to create new `daily_closure` record:
- Frontend: `createDailyClosure()` method in AddSales class
  - Make AJAX call with `opc: "createDailyClosure"`
  - Pass date, UDN, user_id
  - Store returned `daily_closure_id` in `this.dailyClosureId`
- Controller: `createDailyClosure()` method in ctrl
  - Get date, UDN, user_id from `$_POST`
  - Call model method to insert record
  - Return status and daily_closure_id
- Model: `createDailyClosure($array)` method in mdl
  - Insert into `daily_closure` table
  - Fields: udn_id, employee_id, created_at, turn
  - Initialize all totals to 0
  - Return inserted ID

**Acceptance Criteria:**
- Frontend method calls backend correctly
- Controller validates and processes request
- Model inserts record successfully
- daily_closure_id returned and stored
- **Property 9**: New record created when needed

### Task 5.10: Create getSales() Method for Loading
**Requirements:** 9.1, 9.2, 9.3, 9.4

Create method to load existing sales data:
- Controller: `getSales()` method in ctrl
  - Get date and UDN from `$_POST`
  - Call model to query `daily_closure`
  - If exists, load all detail records
  - Return complete data structure
- Model: `getSalesById($array)` method in mdl
  - Query `daily_closure` by date and UDN
  - If found, query all detail tables:
    - `detail_sale_category`
    - `detail_sale_category_tax`
    - `detail_cash_concept`
    - `detail_foreing_currency`
    - `detail_bank_account`
    - `detail_credit_customer`
  - Return structured data with all details

**Acceptance Criteria:**
- Method queries daily_closure correctly
- All detail tables queried when record exists
- Data structured properly for frontend
- Returns null/empty when no record exists
- **Property 25**: All detail records loaded


### Task 5.11: *Write Integration Tests for Persistence
**Requirements:** 6.1, 6.2, 6.3, 7.1, 7.2, 7.3, 7.4

Write integration tests for complete save flows:
- **Test: Complete Sales Registration Flow**
  - Load module
  - Select date and UDN
  - Enter sales data
  - Verify tax calculation
  - Enter payment data
  - Verify difference calculation
  - Save sales
  - Verify persistence
- **Test: Edit Existing Sales Flow**
  - Create initial sales
  - Load module
  - Verify data loaded
  - Modify data
  - Save changes
  - Verify changes persisted

**Acceptance Criteria:**
- Integration tests cover end-to-end flows
- Tests verify database persistence
- Tests check data integrity
- All tests pass

### Task 5.12: Checkpoint - Persistence Complete
**Requirements:** All Phase 5 requirements

Verify persistence functionality:
- Test creating new sales
- Test editing existing sales
- Test creating new payments
- Test editing existing payments
- Verify rollback on failures
- Run all integration tests

**Acceptance Criteria:**
- All Phase 5 tasks completed
- Sales persistence working
- Payment persistence working
- Rollback mechanism functional
- All tests passing
- Ready for Phase 6 (Validation)


## Phase 6: Validation (1 day)

### Task 6.1: Implement Date Validation
**Requirements:** 4.1, 4.2, 4.3, 4.4

Implement date validation using `monthly_module_lock`:
- Query `monthly_module_lock` table for current month
- Get `lock_time` for the month
- Compare current time with lock_time
- If current time > lock_time:
  - Disable save buttons
  - Show warning message
  - Mark date as blocked visually
- If current time <= lock_time:
  - Enable save buttons
  - Mark date as enabled visually
- Update validation when date changes

**Acceptance Criteria:**
- Validation queries correct table
- Time comparison works correctly
- Save buttons disabled/enabled appropriately
- Visual indicators show date status
- **Property 6**: Save disabled when time > lock_time
- **Property 7**: Save enabled when time <= lock_time

### Task 6.2: Implement Duplicate Prevention
**Requirements:** 5.1, 5.2, 5.3

Implement logic to prevent duplicate daily_closure records:
- When loading data, check if daily_closure exists for date/UDN
- If exists:
  - Load data for editing
  - Store daily_closure_id
  - Show "Editing" mode indicator
- If not exists:
  - Initialize empty form
  - Set daily_closure_id to null
  - Show "Creating" mode indicator
- Prevent creating new record if one exists

**Acceptance Criteria:**
- Duplicate check works correctly
- Existing records loaded for editing
- New records created only when needed
- Mode indicators visible to user
- **Property 8**: Loads existing for editing
- **Property 9**: Creates new when none exists


### Task 6.3: Implement Required Field Validation
**Requirements:** 6.1, 7.1

Implement validation for required fields:
- Before saving, check all required fields have values
- Required fields:
  - At least one sale category with value > 0
  - Date selected
  - UDN selected
- Show validation errors for missing fields
- Prevent save operation if validation fails
- Highlight invalid fields visually

**Acceptance Criteria:**
- Validation checks all required fields
- Error messages are clear and specific
- Invalid fields highlighted
- Save prevented when validation fails
- **Property 10**: Required field validation works

### Task 6.4: Implement Input Sanitization
**Requirements:** 6.1, 7.1

Implement input sanitization and validation:
- Validate numeric inputs:
  - Only allow numbers
  - No negative values
  - Sanitize to 0 if invalid
- Add visual feedback for invalid inputs (red border)
- Sanitize on blur and before save
- Prevent non-numeric characters in numeric fields

**Acceptance Criteria:**
- Numeric validation works correctly
- Negative values prevented
- Invalid inputs sanitized to 0
- Visual feedback shown
- Data sanitized before save

### Task 6.5: *Write Unit Tests for Validation
**Requirements:** 4.1, 4.2, 5.1, 6.1

Write unit tests for validation logic:
- Test date validation with various times
- Test duplicate prevention logic
- Test required field validation
- Test input sanitization
- Verify error messages

**Acceptance Criteria:**
- All validation tests pass
- Edge cases covered
- Test coverage >= 90% for validation code


### Task 6.6: *Write Property-Based Test for Date Validation
**Requirements:** 4.1, 4.2

Write property-based test for date validation:
- **Property Test 6**: Date validation for any times
  - Generate random current hour (0-23) and minute (0-59)
  - Generate random lock hour (0-23) and minute (0-59)
  - Convert to minutes for comparison
  - Verify: `enabled = (currentMinutes <= lockMinutes)`
  - Run 100 iterations minimum

**Acceptance Criteria:**
- Property test runs with 100+ iterations
- All iterations pass
- **Property 6**: Validated with property test

### Task 6.7: Checkpoint - Validation Complete
**Requirements:** All Phase 6 requirements

Verify validation functionality:
- Test date validation with various times
- Test duplicate prevention
- Test required field validation
- Test input sanitization
- Run all validation tests

**Acceptance Criteria:**
- All Phase 6 tasks completed
- Date validation working
- Duplicate prevention working
- Required field validation working
- Input sanitization working
- All tests passing
- Ready for Phase 7 (Error Handling)


## Phase 7: Error Handling (1 day)

### Task 7.1: Implement Date Validation Error Handling
**Requirements:** 4.2

Implement error handling for blocked dates:
- Detect when date is blocked
- Disable save buttons
- Show warning message with `alert()`:
  - Icon: "warning"
  - Title: "Fecha bloqueada"
  - Text: "No se pueden registrar ventas después de las [lock_time]"
- Provide clear recovery instructions
- Re-enable buttons when valid date selected

**Acceptance Criteria:**
- Error detected correctly
- Save buttons disabled
- Warning message shown
- Recovery instructions clear
- Buttons re-enabled on valid date

### Task 7.2: Implement Persistence Failure Handling
**Requirements:** 6.5, 7.6

Implement error handling for save failures:
- Detect save operation failures (status != 200)
- Show error message with retry option:
  - Icon: "error"
  - Title: "Error al guardar"
  - Text: Response message or generic error
  - Button 1: "Reintentar"
  - Button 2: "Cancelar"
- Implement retry mechanism
- Preserve user data in form
- Log errors to console for debugging

**Acceptance Criteria:**
- Failures detected correctly
- Error messages shown
- Retry mechanism works
- User data preserved
- Errors logged to console


### Task 7.3: Implement Network Error Handling
**Requirements:** 6.5, 7.6

Implement error handling for network failures:
- Catch fetch exceptions in `useFetch()`
- Return status 0 for network errors
- Show connection error message:
  - Icon: "error"
  - Title: "Error de conexión"
  - Text: "No se pudo conectar con el servidor. Verifique su conexión a internet."
  - Button: "Reintentar"
- Implement retry mechanism
- Preserve user data

**Acceptance Criteria:**
- Network errors caught correctly
- Connection error message shown
- Retry mechanism works
- User data preserved

### Task 7.4: Implement Rollback on Partial Failures
**Requirements:** 6.5, 7.6

Implement rollback mechanism in controller:
- Track all created records during save operation
- If any operation fails:
  - Delete all previously created records
  - Return error status with descriptive message
- Ensure database remains consistent
- Log rollback operations

**Acceptance Criteria:**
- Rollback deletes partial records
- Database consistency maintained
- Error messages descriptive
- Rollback operations logged

### Task 7.5: Implement Invalid Input Error Handling
**Requirements:** 6.1, 7.1

Implement error handling for invalid inputs:
- Detect non-numeric or negative values
- Sanitize automatically to 0
- Show visual feedback (red border)
- Remove feedback when corrected
- No blocking errors for invalid inputs

**Acceptance Criteria:**
- Invalid inputs detected
- Automatic sanitization works
- Visual feedback shown
- Feedback removed when corrected
- No blocking errors


### Task 7.6: Implement Missing Daily Closure Handling
**Requirements:** 5.3, 7.6

Implement automatic daily_closure creation:
- Before saving payments, check if `this.dailyClosureId` exists
- If not exists:
  - Automatically call `createDailyClosure()`
  - Store returned ID
  - Continue with save operation
- If creation fails:
  - Show error message
  - Stop save operation
- Make process transparent to user

**Acceptance Criteria:**
- Missing daily_closure detected
- Automatic creation works
- ID stored correctly
- Failures handled gracefully
- Process transparent to user

### Task 7.7: Implement Tax Calculation Error Handling
**Requirements:** 3.2, 3.3

Implement error handling for missing taxes:
- Detect when no taxes found for category
- Log warning to console
- Continue processing without taxes
- Don't block save operation
- Show informational message if appropriate

**Acceptance Criteria:**
- Missing taxes detected
- Warning logged
- Processing continues
- Save not blocked
- User informed if needed

### Task 7.8: Checkpoint - Error Handling Complete
**Requirements:** All Phase 7 requirements

Verify error handling functionality:
- Test all error scenarios
- Verify error messages
- Test retry mechanisms
- Verify rollback functionality
- Test recovery procedures

**Acceptance Criteria:**
- All Phase 7 tasks completed
- All error scenarios handled
- Error messages clear and helpful
- Retry mechanisms working
- Rollback functional
- Ready for Phase 8 (Testing)


## Phase 8: Testing (3 days)

### Task 8.1: *Write Unit Tests for Tax Calculation
**Requirements:** 3.1, 3.2, 3.3, 3.4

Write comprehensive unit tests for tax calculation:
- Test single tax calculation (IVA 8%)
- Test multiple taxes (IVA 8% + HOSPEDAJE 2%)
- Test zero sale amount edge case
- Test discount tax calculation
- Test courtesy tax calculation
- Test tax summation
- Verify calculations match expected values

**Acceptance Criteria:**
- 5+ unit tests for tax calculation
- All tests pass
- Edge cases covered
- Test coverage >= 95%

### Task 8.2: *Write Unit Tests for Validation
**Requirements:** 4.1, 4.2, 5.1, 6.1

Write unit tests for validation logic:
- Test date validation (blocked/enabled)
- Test duplicate prevention
- Test required field validation
- Test input sanitization
- Verify error messages

**Acceptance Criteria:**
- 4+ unit tests for validation
- All tests pass
- Edge cases covered
- Test coverage >= 90%

### Task 8.3: *Write Unit Tests for Calculations
**Requirements:** 8.1, 8.2, 8.3, 2.5

Write unit tests for payment calculations:
- Test positive difference calculation
- Test negative difference calculation
- Test zero difference edge case
- Test foreign currency MXN conversion
- Test net sale calculation
- Verify color coding

**Acceptance Criteria:**
- 6+ unit tests for calculations
- All tests pass
- Edge cases covered
- Test coverage >= 95%


### Task 8.4: *Write Property-Based Tests
**Requirements:** 3.1, 3.2, 8.1, 2.5

Write property-based tests for universal properties:
- **Property Test 1**: Tax calculation accuracy
  - Random sale amounts and percentages
  - Verify formula: `tax = (sale * percentage) / 100`
  - 100+ iterations
- **Property Test 2**: Multiple tax summation
  - Random sale and array of percentages
  - Verify sum of individual taxes
  - 100+ iterations
- **Property Test 6**: Date validation
  - Random current and lock times
  - Verify enable/disable logic
  - 100+ iterations
- **Property Test 18**: Difference calculation
  - Random sale and payment totals
  - Verify: `difference = sale - payment`
  - 100+ iterations
- **Property Test 23**: Foreign currency MXN
  - Random amounts and exchange rates
  - Verify: `mxn = amount * rate`
  - 100+ iterations
- **Property Test 24**: Net sale calculation
  - Random sale, discount, courtesy
  - Verify: `net = sale - discount - courtesy`
  - 100+ iterations

**Acceptance Criteria:**
- 6 property-based tests implemented
- Each test runs 100+ iterations
- All tests pass
- Properties validated correctly

### Task 8.5: *Write Integration Tests
**Requirements:** 6.1, 6.2, 6.3, 7.1, 7.2, 7.3, 7.4, 9.1, 9.2

Write integration tests for complete flows:
- **Test 1**: Complete sales registration flow
  - Load module → Select date/UDN → Enter sales → Verify taxes → Enter payments → Verify difference → Save → Verify persistence
- **Test 2**: Edit existing sales flow
  - Create initial sales → Load module → Verify data loaded → Modify data → Save → Verify changes persisted
- **Test 3**: Date validation flow
  - Select blocked date → Verify save disabled → Select valid date → Verify save enabled
- **Test 4**: Duplicate prevention flow
  - Create sales for date/UDN → Load module → Verify existing data loaded → Verify edit mode
- **Test 5**: Foreign currency flow
  - Enter foreign currency amount → Verify MXN calculation → Save → Verify persistence
- **Test 6**: Error handling flow
  - Simulate network error → Verify error message → Retry → Verify success
- **Test 7**: Rollback flow
  - Simulate partial save failure → Verify rollback → Verify database consistency
- **Test 8**: Tax calculation flow
  - Enter multiple categories → Verify individual taxes → Verify total → Save → Verify persistence
- **Test 9**: Payment totals flow
  - Enter various payment types → Verify totals → Verify difference → Save → Verify persistence

**Acceptance Criteria:**
- 9 integration tests implemented
- All tests pass
- End-to-end flows covered
- Database interactions verified


### Task 8.6: *Write Performance Tests
**Requirements:** 3.5, 8.5

Write performance tests for real-time calculations:
- Test tax calculation performance
  - Enter sale value
  - Measure time to update UI
  - Verify < 100ms
- Test difference calculation performance
  - Enter payment value
  - Measure time to update UI
  - Verify < 100ms
- Test multiple rapid changes
  - Simulate rapid input changes
  - Verify no performance degradation
  - Verify UI remains responsive

**Acceptance Criteria:**
- Performance tests implemented
- All calculations complete within 100ms
- UI remains responsive
- No performance degradation with rapid changes

### Task 8.7: *Verify Test Coverage
**Requirements:** All requirements

Verify overall test coverage:
- Run coverage report
- Verify >= 80% overall coverage
- Identify untested code paths
- Add tests for uncovered areas
- Document coverage results

**Acceptance Criteria:**
- Coverage report generated
- Overall coverage >= 80%
- Critical paths have >= 90% coverage
- Coverage documented

### Task 8.8: Checkpoint - Testing Complete
**Requirements:** All Phase 8 requirements

Verify all testing complete:
- Run all unit tests
- Run all property-based tests (100+ iterations each)
- Run all integration tests
- Run performance tests
- Verify coverage >= 80%
- Document test results

**Acceptance Criteria:**
- All Phase 8 tasks completed
- 31+ unit tests passing
- 6 property tests passing (100+ iterations each)
- 9 integration tests passing
- Performance tests passing
- Coverage >= 80%
- Ready for Phase 9 (UI Polish)


## Phase 9: UI Polish (1 day)

### Task 9.1: Responsive Design Testing
**Requirements:** 10.1, 10.2, 10.3, 10.4

Test and refine responsive design:
- Test on mobile devices (< 768px)
  - Verify cards stack vertically
  - Verify inputs are usable
  - Verify buttons are accessible
- Test on tablets (768px - 1024px)
  - Verify two-column layout
  - Verify proper spacing
- Test on desktop (> 1024px)
  - Verify optimal layout
  - Verify proper alignment
- Fix any responsive issues found
- Ensure numeric fields remain readable on all sizes

**Acceptance Criteria:**
- Module works on all screen sizes
- Cards stack properly on mobile
- Two-column layout on desktop
- All inputs usable on mobile
- Numeric fields readable on all sizes
- **Property 36**: Two-column layout on large screens
- **Property 37**: Stacked layout on small screens
- **Property 38**: Numeric field readability maintained

### Task 9.2: Loading States and Animations
**Requirements:** 2.1, 6.1, 7.1

Implement loading states and smooth animations:
- Add loading spinner during data fetch
- Add loading state during save operations
- Add smooth transitions for:
  - Card appearance
  - SummaryCard updates
  - Error message display
- Disable inputs during save operations
- Show progress indicators for long operations

**Acceptance Criteria:**
- Loading spinners shown appropriately
- Smooth transitions implemented
- Inputs disabled during saves
- Progress indicators visible
- No jarring UI changes


### Task 9.3: Accessibility Improvements
**Requirements:** 10.4

Implement accessibility features:
- Add ARIA labels to all form fields
- Ensure keyboard navigation works
  - Tab order is logical
  - All inputs accessible via keyboard
  - Save buttons accessible via keyboard
- Add ARIA live regions for:
  - Error messages
  - Success messages
  - Calculation updates
- Ensure color contrast meets WCAG standards
  - Green/red difference colors have sufficient contrast
  - All text readable
- Add focus indicators for keyboard navigation

**Acceptance Criteria:**
- ARIA labels on all inputs
- Keyboard navigation works completely
- Tab order is logical
- ARIA live regions implemented
- Color contrast meets WCAG AA
- Focus indicators visible

### Task 9.4: Cross-Browser Testing
**Requirements:** All requirements

Test module on all supported browsers:
- Chrome 90+
  - Test all functionality
  - Verify UI renders correctly
- Firefox 88+
  - Test all functionality
  - Verify UI renders correctly
- Safari 14+
  - Test all functionality
  - Verify UI renders correctly
- Edge 90+
  - Test all functionality
  - Verify UI renders correctly
- Fix any browser-specific issues

**Acceptance Criteria:**
- Module works on Chrome 90+
- Module works on Firefox 88+
- Module works on Safari 14+
- Module works on Edge 90+
- No browser-specific bugs
- UI consistent across browsers


### Task 9.5: Final UI Refinements
**Requirements:** 1.1, 2.1, 8.2, 8.3, 8.4

Implement final UI refinements:
- Refine color coding for difference display
  - Green for positive: `text-green-600`
  - Red for negative: `text-red-600`
  - Neutral for zero: `text-gray-700`
- Improve button styling and hover states
- Add tooltips for complex fields
- Improve error message styling
- Add success animations for save operations
- Polish SummaryCard appearance
- Ensure consistent spacing and alignment

**Acceptance Criteria:**
- Color coding refined and consistent
- Button hover states smooth
- Tooltips helpful and clear
- Error messages well-styled
- Success animations pleasant
- SummaryCards polished
- Spacing and alignment consistent

### Task 9.6: Checkpoint - UI Polish Complete
**Requirements:** All Phase 9 requirements

Verify UI polish complete:
- Test responsive design on all devices
- Verify loading states work
- Test accessibility features
- Verify cross-browser compatibility
- Review final UI refinements
- Get user feedback if possible

**Acceptance Criteria:**
- All Phase 9 tasks completed
- Responsive design working
- Loading states implemented
- Accessibility features working
- Cross-browser compatible
- UI polished and professional
- Module ready for deployment


## Final Deployment

### Task 10.1: Pre-Deployment Checklist
**Requirements:** All requirements

Complete pre-deployment checklist:
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

**Acceptance Criteria:**
- All checklist items completed
- Module ready for production deployment

### Task 10.2: Database Setup
**Requirements:** All requirements

Set up database for production:
- Create required indexes:
  - `idx_daily_closure_date_udn` on `daily_closure(created_at, udn_id)`
  - `idx_detail_sale_closure` on `detail_sale_category(daily_closure_id)`
  - `idx_detail_cash_closure` on `detail_cash_concept(daily_closure_id)`
  - `idx_detail_foreign_closure` on `detail_foreing_currency(daily_closure_id)`
  - `idx_detail_bank_closure` on `detail_bank_account(daily_closure_id)`
- Verify foreign key constraints
- Test database performance
- Create backup before deployment

**Acceptance Criteria:**
- All indexes created
- Foreign keys verified
- Performance tested
- Backup created


### Task 10.3: Deploy to Production
**Requirements:** All requirements

Deploy module to production:
- Upload files to production server:
  - `finanzas/consulta_respaldo/js/add-sales.js`
  - `finanzas/consulta_respaldo/ctrl/ctrl-add-sales.php`
  - `finanzas/consulta_respaldo/mdl/mdl-add-sales.php`
- Update `index.php` to include new module
- Clear server cache
- Test module in production environment
- Monitor for errors in first 24 hours

**Acceptance Criteria:**
- Files uploaded successfully
- Module accessible in production
- No errors in production
- Monitoring in place

### Task 10.4: User Training and Documentation
**Requirements:** All requirements

Prepare user training and documentation:
- Create user guide with screenshots
- Document common workflows:
  - Creating new sales
  - Editing existing sales
  - Handling blocked dates
  - Understanding difference calculations
- Create troubleshooting guide
- Conduct user training session
- Gather initial user feedback

**Acceptance Criteria:**
- User guide created
- Workflows documented
- Troubleshooting guide available
- Training session conducted
- Feedback collected


## Summary

### Total Tasks: 60
- Phase 1 (Frontend Structure): 5 tasks
- Phase 2 (Data Loading): 6 tasks
- Phase 3 (Tax Calculation): 7 tasks
- Phase 4 (Payment Calculations): 7 tasks
- Phase 5 (Persistence): 12 tasks
- Phase 6 (Validation): 7 tasks
- Phase 7 (Error Handling): 8 tasks
- Phase 8 (Testing): 8 tasks
- Phase 9 (UI Polish): 6 tasks
- Final Deployment: 4 tasks

### Estimated Timeline: 15 days
- Phase 1: 2 days
- Phase 2: 1 day
- Phase 3: 2 days
- Phase 4: 1 day
- Phase 5: 3 days
- Phase 6: 1 day
- Phase 7: 1 day
- Phase 8: 3 days
- Phase 9: 1 day

### Test Coverage Goals
- Unit Tests: 31+ tests
- Property-Based Tests: 6 tests (100+ iterations each)
- Integration Tests: 9 tests
- Overall Coverage: >= 80%
- Critical Path Coverage: >= 90%

### Key Properties Validated
- Property 1-5: Tax Calculation (5 properties)
- Property 6-10: Validation (5 properties)
- Property 11-17: Persistence (7 properties)
- Property 18-24: Calculations (7 properties)
- Property 25-27: Data Loading (3 properties)
- Property 28-30: UI Updates (3 properties)
- Property 31-35: Dynamic Loading (5 properties)
- Property 36-38: Responsive Design (3 properties)

**Total: 38 properties validated**


### Requirements Coverage

All 10 requirements fully covered across implementation tasks:

1. **Requirement 1**: Visualización de Ventas del Día
   - Tasks: 1.4, 2.5, 3.1-3.4
   
2. **Requirement 2**: Registro de Formas de Pago
   - Tasks: 1.4, 2.5, 4.1-4.4

3. **Requirement 3**: Cálculo de Impuestos
   - Tasks: 3.1-3.7

4. **Requirement 4**: Validación de Fecha Habilitada
   - Tasks: 1.3, 6.1, 7.1

5. **Requirement 5**: Prevención de Duplicados
   - Tasks: 2.4, 5.9, 6.2

6. **Requirement 6**: Persistencia de Ventas del Día
   - Tasks: 5.1, 5.3, 5.4, 5.7, 5.9, 5.10

7. **Requirement 7**: Persistencia de Formas de Pago
   - Tasks: 5.2, 5.5, 5.6, 5.8

8. **Requirement 8**: Cálculo de Diferencia
   - Tasks: 4.2-4.4

9. **Requirement 9**: Carga de Datos Existentes
   - Tasks: 2.4, 5.10

10. **Requirement 10**: Interfaz Responsiva
    - Tasks: 1.2, 1.4, 9.1

### Notes

- Tasks marked with `*` are optional testing tasks
- Property-based tests must run minimum 100 iterations
- All tests must pass before deployment
- Follow CoffeeSoft nomenclature strictly:
  - Frontend: `ls[Entidad]()`, `add[Entidad]()`, `edit[Entidad]()`
  - Controller: `init()`, `ls()`, `add[Entidad]()`, `edit[Entidad]()`, `get[Entidad]()`
  - Model: `list[Entidad]()`, `create[Entidad]()`, `update[Entidad]()`, `get[Entidad]ById()`
- NO use `??` or `isset()` with `$_POST` variables
- NO use try-catch blocks
- Use `_Read()` for all SELECT queries in model
- Database table name: `foreing_currency` (with intentional typo)

---

**Document Version:** 1.0  
**Last Updated:** 2025-12-28  
**Status:** Ready for Implementation  
**Estimated Completion:** 15 working days

