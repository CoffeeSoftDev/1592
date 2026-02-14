# Tasks Document

## Implementation Checklist

### ✅ COMPLETED TASKS

#### Task 1: Declare Fogaza Category Variables
**Status**: ✅ DONE  
**Location**: `kpi/marketing/ventas/ctrl/ctrl-ventas2.php`  
**Lines**: ~390-397, ~640-647

**Description**: Declared all 8 Fogaza category variables in both sync methods.

**Variables Declared**:
```php
$abarrotes = 0;
$bizcocho = 0;
$bocadillos = 0;
$frances = 0;
$pasteleria_normal = 0;
$pasteleria_premium = 0;
$refrescos = 0;
$velas = 0;
```

**Verification**:
- [x] Variables declared in `syncToFolio()` method
- [x] Variables declared in `syncMonthToFolio()` method
- [x] All 8 categories covered
- [x] Initialized to 0

---

#### Task 2: Implement Switch Cases in syncToFolio()
**Status**: ✅ DONE  
**Location**: `kpi/marketing/ventas/ctrl/ctrl-ventas2.php`  
**Lines**: ~440-470

**Description**: Added all missing switch case statements for Fogaza categories in the individual sync method.

**Cases Implemented**:
```php
case 'abarrotes':
    $abarrotes += $cantidadConImpuestos;
    break;

case 'bizcocho':
    $bizcocho += $cantidadConImpuestos;
    break;

case 'bocadillos':
    $bocadillos += $cantidadConImpuestos;
    break;

case 'frances':
case 'francés':
    $frances += $cantidadConImpuestos;
    break;

case 'pasteleria normal':
case 'pastelería normal':
    $pasteleria_normal += $cantidadConImpuestos;
    break;

case 'pasteleria premium':
case 'pastelería premium':
    $pasteleria_premium += $cantidadConImpuestos;
    break;

case 'refrescos':
    $refrescos += $cantidadConImpuestos;
    break;

case 'velas':
    $velas += $cantidadConImpuestos;
    break;
```

**Verification**:
- [x] All 8 Fogaza categories have case statements
- [x] Accent variations handled (francés/frances, pastelería/pasteleria)
- [x] Accumulation pattern consistent (`+=` operator)
- [x] Break statements present
- [x] Tax calculation applied before accumulation

---

#### Task 3: Implement Switch Cases in syncMonthToFolio()
**Status**: ✅ DONE  
**Location**: `kpi/marketing/ventas/ctrl/ctrl-ventas2.php`  
**Lines**: ~690-720

**Description**: Added all missing switch case statements for Fogaza categories in the monthly sync method.

**Cases Implemented**: Same structure as Task 2

**Verification**:
- [x] All 8 Fogaza categories have case statements
- [x] Accent variations handled
- [x] Accumulation pattern consistent
- [x] Break statements present
- [x] Tax calculation applied before accumulation
- [x] Matches syncToFolio() implementation

---

#### Task 4: Create Requirements Documentation
**Status**: ✅ DONE  
**Location**: `.kiro/specs/sync-fogaza-categories/requirements.md`

**Description**: Created comprehensive requirements document with 7 main requirements covering all aspects of the Fogaza categories sync functionality.

**Requirements Documented**:
1. Captura de Ventas por Categoría Fogaza
2. Sincronización Individual de Ventas
3. Sincronización Mensual de Ventas
4. Cálculo de Impuestos
5. Manejo de Variaciones de Nombres
6. Integración con Sistema de Folios
7. Respuesta del Sistema

**Verification**:
- [x] All 8 Fogaza categories documented
- [x] Tax calculation rules specified (8% IVA, no IEPS)
- [x] Accent variations documented
- [x] User stories included
- [x] Acceptance criteria defined
- [x] Response formats specified

---

#### Task 5: Create Design Documentation
**Status**: ✅ DONE  
**Location**: `.kiro/specs/sync-fogaza-categories/design.md`

**Description**: Created detailed design document with system architecture, data flow diagrams, and technical specifications.

**Sections Included**:
- System Architecture with component diagram
- Data Flow for individual and monthly sync
- Tax Calculation Logic with formulas
- Category Mapping tables
- Switch Statement Pattern
- Database Schema
- Error Handling scenarios
- Performance Considerations
- Security Considerations
- Integration Points
- Testing Strategy
- Future Enhancements

**Verification**:
- [x] Architecture diagrams included
- [x] Data flow documented
- [x] Tax formulas specified
- [x] Category mapping complete
- [x] Database schema documented
- [x] Error scenarios covered
- [x] Testing strategy defined

---

### 📋 PENDING TASKS

#### Task 6: Update Folio/Venta Records with Fogaza Data
**Status**: ⏳ PENDING  
**Priority**: HIGH  
**Estimated Effort**: 2-3 hours

**Description**: Currently, Fogaza category amounts are calculated but not stored in the folio/venta records. Need to add Fogaza fields to the database schema and update the sync logic to store these values.

**Subtasks**:
1. [ ] Add Fogaza category columns to `soft_restaurant_ventas` table
   ```sql
   ALTER TABLE soft_restaurant_ventas ADD COLUMN abarrotes DECIMAL(10,2) DEFAULT 0;
   ALTER TABLE soft_restaurant_ventas ADD COLUMN bizcocho DECIMAL(10,2) DEFAULT 0;
   ALTER TABLE soft_restaurant_ventas ADD COLUMN bocadillos DECIMAL(10,2) DEFAULT 0;
   ALTER TABLE soft_restaurant_ventas ADD COLUMN frances DECIMAL(10,2) DEFAULT 0;
   ALTER TABLE soft_restaurant_ventas ADD COLUMN pasteleria_normal DECIMAL(10,2) DEFAULT 0;
   ALTER TABLE soft_restaurant_ventas ADD COLUMN pasteleria_premium DECIMAL(10,2) DEFAULT 0;
   ALTER TABLE soft_restaurant_ventas ADD COLUMN refrescos DECIMAL(10,2) DEFAULT 0;
   ALTER TABLE soft_restaurant_ventas ADD COLUMN velas DECIMAL(10,2) DEFAULT 0;
   ```

2. [ ] Update `createVenta()` in `syncToFolio()` to include Fogaza fields
   ```php
   $create = $mdlVentas->createVenta($this->util->sql([
       'soft_ventas_fecha' => date('Y-m-d H:i:s'),
       'soft_folio'        => $folioExistente['id_folio'],
       'alimentos'         => $alimentos,
       'bebidas'           => $bebidas,
       // ... existing fields ...
       'abarrotes'         => $abarrotes,
       'bizcocho'          => $bizcocho,
       'bocadillos'        => $bocadillos,
       'frances'           => $frances,
       'pasteleria_normal' => $pasteleria_normal,
       'pasteleria_premium'=> $pasteleria_premium,
       'refrescos'         => $refrescos,
       'velas'             => $velas,
       'total'             => $total
   ]));
   ```

3. [ ] Update `updateVenta()` in `syncToFolio()` to include Fogaza fields

4. [ ] Update `createVenta()` in `syncMonthToFolio()` to include Fogaza fields

5. [ ] Update `updateVenta()` in `syncMonthToFolio()` to include Fogaza fields

6. [ ] Update response data to include Fogaza breakdown
   ```php
   $responseData = [
       'folio_id'  => $folioExistente['id_folio'],
       'alimentos' => $alimentos,
       // ... existing fields ...
       'fogaza' => [
           'abarrotes'          => $abarrotes,
           'bizcocho'           => $bizcocho,
           'bocadillos'         => $bocadillos,
           'frances'            => $frances,
           'pasteleria_normal'  => $pasteleria_normal,
           'pasteleria_premium' => $pasteleria_premium,
           'refrescos'          => $refrescos,
           'velas'              => $velas
       ],
       'total' => $total
   ];
   ```

**Acceptance Criteria**:
- [ ] Database schema updated with Fogaza columns
- [ ] All sync operations store Fogaza data
- [ ] Response includes Fogaza breakdown
- [ ] Existing functionality not affected
- [ ] Data integrity maintained

---

#### Task 7: Add Fogaza Totals to Subtotal Calculation
**Status**: ⏳ PENDING  
**Priority**: HIGH  
**Estimated Effort**: 30 minutes

**Description**: Currently, Fogaza category amounts are not included in the subtotal and total calculations. Need to add them to ensure accurate financial reporting.

**Changes Required**:

**In `syncToFolio()` (line ~475)**:
```php
// Current
$subtotal = $alimentos + $bebidas + $otros + $hospedaje + $ayb + $diversos;

// Should be
$subtotal = $alimentos + $bebidas + $otros + $hospedaje + $ayb + $diversos +
            $abarrotes + $bizcocho + $bocadillos + $frances +
            $pasteleria_normal + $pasteleria_premium + $refrescos + $velas;
```

**In `syncMonthToFolio()` (line ~730)**:
```php
// Current
$subtotal = $alimentos + $bebidas + $otros + $hospedaje + $ayb + $diversos;

// Should be
$subtotal = $alimentos + $bebidas + $otros + $hospedaje + $ayb + $diversos +
            $abarrotes + $bizcocho + $bocadillos + $frances +
            $pasteleria_normal + $pasteleria_premium + $refrescos + $velas;
```

**Acceptance Criteria**:
- [ ] Fogaza amounts included in subtotal calculation
- [ ] Total reflects all categories (Hotel + Fogaza)
- [ ] Financial reports show accurate totals
- [ ] Both sync methods updated consistently

---

#### Task 8: Uncomment Monthly Sync Database Operations
**Status**: ⏳ PENDING  
**Priority**: HIGH  
**Estimated Effort**: 15 minutes

**Description**: The `syncMonthToFolio()` method has database operations commented out (lines ~745-810). These need to be uncommented and tested to enable monthly sync functionality.

**Changes Required**:

**Location**: `kpi/marketing/ventas/ctrl/ctrl-ventas2.php` lines ~745-810

**Action**: Uncomment the following sections:
1. `$ventaExistente = $mdlVentas->getVentaByFolioId(...)` 
2. Update venta block (if exists)
3. Create venta block (if not exists)
4. Success/failure tracking

**Before Uncommenting**:
- [ ] Verify Task 6 is completed (Fogaza fields added to database)
- [ ] Verify Task 7 is completed (totals calculation updated)
- [ ] Review error handling logic
- [ ] Ensure transaction safety

**Acceptance Criteria**:
- [ ] Monthly sync creates/updates venta records
- [ ] Success/failure tracking works correctly
- [ ] Error handling prevents data corruption
- [ ] Results array populated with accurate data

---

#### Task 9: Add Unit Tests for Tax Calculation
**Status**: ⏳ PENDING  
**Priority**: MEDIUM  
**Estimated Effort**: 2 hours

**Description**: Create unit tests to verify tax calculation logic for all Fogaza categories.

**Test Cases**:
```php
// Test 1: Basic IVA calculation (8%)
Input: categoria='abarrotes', cantidad=100
Expected: cantidadConImpuestos=108

// Test 2: Accent variation handling
Input: categoria='francés', cantidad=200
Expected: frances variable += 216

Input: categoria='frances', cantidad=200
Expected: frances variable += 216 (same result)

// Test 3: Multiple categories accumulation
Input: [
    ['categoria' => 'abarrotes', 'cantidad' => 100],
    ['categoria' => 'bizcocho', 'cantidad' => 200]
]
Expected: 
    abarrotes = 108
    bizcocho = 216
    total = 324

// Test 4: Case insensitivity
Input: categoria='ABARROTES', cantidad=100
Expected: abarrotes variable += 108

// Test 5: Whitespace handling
Input: categoria=' abarrotes ', cantidad=100
Expected: abarrotes variable += 108
```

**Acceptance Criteria**:
- [ ] All test cases pass
- [ ] Edge cases covered (empty strings, null values)
- [ ] Accent variations tested
- [ ] Case insensitivity verified
- [ ] Accumulation logic validated

---

#### Task 10: Add Integration Tests for Sync Operations
**Status**: ⏳ PENDING  
**Priority**: MEDIUM  
**Estimated Effort**: 3 hours

**Description**: Create integration tests to verify end-to-end sync functionality.

**Test Scenarios**:

1. **Individual Sync - New Folio**
   - [ ] Create test sales data
   - [ ] Call syncToFolio()
   - [ ] Verify folio created
   - [ ] Verify venta created
   - [ ] Verify category amounts correct

2. **Individual Sync - Existing Folio**
   - [ ] Create existing folio
   - [ ] Create test sales data
   - [ ] Call syncToFolio()
   - [ ] Verify folio not duplicated
   - [ ] Verify venta updated

3. **Monthly Sync - Multiple Dates**
   - [ ] Create sales for 5 different dates
   - [ ] Call syncMonthToFolio()
   - [ ] Verify all dates processed
   - [ ] Verify success count = 5
   - [ ] Verify failure count = 0

4. **Monthly Sync - Partial Failure**
   - [ ] Create sales with one invalid date
   - [ ] Call syncMonthToFolio()
   - [ ] Verify partial success
   - [ ] Verify error tracking
   - [ ] Verify valid dates still processed

5. **Error Handling**
   - [ ] Test with no sales data (404 response)
   - [ ] Test with database connection failure
   - [ ] Test with invalid UDN
   - [ ] Verify rollback on failure

**Acceptance Criteria**:
- [ ] All test scenarios pass
- [ ] Database state verified after each test
- [ ] Error responses validated
- [ ] Performance benchmarks met
- [ ] Test data cleanup automated

---

#### Task 11: Update Frontend to Display Fogaza Data
**Status**: ⏳ PENDING  
**Priority**: LOW  
**Estimated Effort**: 2 hours

**Description**: Update the frontend JavaScript to display Fogaza category breakdown in the sync response.

**Changes Required**:

**Location**: `kpi/marketing/ventas/js/ventas2.js` (assumed)

**Add Fogaza Display**:
```javascript
async syncToFolio(fecha, udn) {
    const response = await useFetch({
        url: this._link,
        data: { opc: 'syncToFolio', fecha: fecha, udn: udn }
    });

    if (response.status === 200) {
        // Display success message with breakdown
        let message = `
            <div class="sync-result">
                <h4>Sincronización Exitosa</h4>
                <p><strong>Folio ID:</strong> ${response.data.folio_id}</p>
                
                <h5>Categorías Hotel:</h5>
                <ul>
                    <li>Alimentos: ${formatPrice(response.data.alimentos)}</li>
                    <li>Bebidas: ${formatPrice(response.data.bebidas)}</li>
                    <li>Hospedaje: ${formatPrice(response.data.hospedaje)}</li>
                </ul>
                
                <h5>Categorías Fogaza:</h5>
                <ul>
                    <li>Abarrotes: ${formatPrice(response.data.fogaza.abarrotes)}</li>
                    <li>Bizcocho: ${formatPrice(response.data.fogaza.bizcocho)}</li>
                    <li>Bocadillos: ${formatPrice(response.data.fogaza.bocadillos)}</li>
                    <li>Francés: ${formatPrice(response.data.fogaza.frances)}</li>
                    <li>Pastelería Normal: ${formatPrice(response.data.fogaza.pasteleria_normal)}</li>
                    <li>Pastelería Premium: ${formatPrice(response.data.fogaza.pasteleria_premium)}</li>
                    <li>Refrescos: ${formatPrice(response.data.fogaza.refrescos)}</li>
                    <li>Velas: ${formatPrice(response.data.fogaza.velas)}</li>
                </ul>
                
                <p><strong>Total:</strong> ${formatPrice(response.data.total)}</p>
            </div>
        `;
        
        alert({ icon: "success", html: message });
    }
}
```

**Acceptance Criteria**:
- [ ] Fogaza breakdown displayed in sync response
- [ ] Amounts formatted as currency
- [ ] UI is user-friendly and clear
- [ ] Works for both individual and monthly sync
- [ ] Responsive design maintained

---

#### Task 12: Add Audit Logging
**Status**: ⏳ PENDING  
**Priority**: LOW  
**Estimated Effort**: 2 hours

**Description**: Implement comprehensive audit logging for all sync operations.

**Log Requirements**:
- User ID who triggered sync
- Timestamp of operation
- Operation type (individual/monthly)
- Date range processed
- Categories synced
- Amounts per category
- Success/failure status
- Error messages if any

**Implementation**:
```php
function logSyncOperation($data) {
    $this->createSyncLog($this->util->sql([
        'user_id'       => $_COOKIE['IDU'],
        'operation_type'=> $data['type'], // 'individual' or 'monthly'
        'fecha_inicio'  => $data['fecha_inicio'],
        'fecha_fin'     => $data['fecha_fin'],
        'udn_id'        => $data['udn'],
        'categories'    => json_encode($data['categories']),
        'status'        => $data['status'],
        'error_message' => $data['error'] ?? null,
        'created_at'    => date('Y-m-d H:i:s')
    ]));
}
```

**Acceptance Criteria**:
- [ ] All sync operations logged
- [ ] Log table created in database
- [ ] User tracking implemented
- [ ] Error details captured
- [ ] Log retention policy defined

---

## Summary

### Completion Status
- **Completed**: 5 tasks (Core functionality + Documentation)
- **Pending**: 7 tasks (Database updates, Testing, Enhancements)
- **Total**: 12 tasks

### Priority Breakdown
- **HIGH**: 3 tasks (Database updates, Uncomment operations, Totals calculation)
- **MEDIUM**: 2 tasks (Unit tests, Integration tests)
- **LOW**: 2 tasks (Frontend display, Audit logging)

### Next Steps
1. Complete Task 6 (Database schema updates) - CRITICAL
2. Complete Task 7 (Totals calculation) - CRITICAL
3. Complete Task 8 (Uncomment operations) - CRITICAL
4. Test thoroughly before production deployment
5. Implement remaining tasks based on priority

### Estimated Total Effort
- High Priority: 3-4 hours
- Medium Priority: 5 hours
- Low Priority: 4 hours
- **Total**: 12-13 hours
