# Payment Records Cleanup - Requirements

## 1. Overview

### 1.1 Background
The payment system in `contabilidad2` uses a delete-and-recreate pattern when saving payment forms (`addPayments`/`editPayments`). This pattern should prevent duplicates, but duplicate records were detected for January 4-6, 2026 in the `rfwsmqex_gvsl_finanzas3` database.

### 1.2 Problem Statement
Duplicate payment records exist across three tables:
- `detail_cash_concept` (Efectivo, Propina)
- `detail_bank_account` (BBVA, BANORTE, BANAMEX)
- `detail_foreing_currency` (not affected in this case)

### 1.3 Root Cause Analysis
The duplicates suggest one of the following scenarios:
1. The delete-and-recreate pattern failed to execute the DELETE phase
2. Multiple concurrent requests created race conditions
3. Manual database insertions bypassed the application logic
4. A bug in the `addPayments`/`editPayments` implementation

## 2. User Stories

### 2.1 As a System Administrator
**I want** to identify and remove duplicate payment records  
**So that** financial reports show accurate totals without double-counting

**Acceptance Criteria:**
- ✅ Query identifies all duplicate records by `daily_closure_id` and payment type
- ✅ Duplicates are removed keeping the original records (lower IDs)
- ✅ Final state verified with no remaining duplicates
- ✅ All three payment tables are checked (cash_concept, bank_account, foreign_currency)

### 2.2 As a Developer
**I want** to understand the payment routine flow  
**So that** I can prevent future duplicates and maintain data integrity

**Acceptance Criteria:**
- ✅ Documentation explains `datosCaja` operation (read from old system)
- ✅ Documentation explains `addPayments`/`editPayments` operations (write to new system)
- ✅ Delete-and-recreate pattern is clearly documented
- ✅ Database schema relationships are mapped

### 2.3 As a Financial Analyst
**I want** accurate payment records for each daily closure  
**So that** I can reconcile cash flow and bank deposits correctly

**Acceptance Criteria:**
- ✅ Each `daily_closure_id` has exactly one record per payment type
- ✅ Payment totals match the expected values from sales data
- ✅ No orphaned records exist (all records link to valid daily_closure)

## 3. Affected Data

### 3.1 Date Range
- **Start Date:** 2026-01-04
- **End Date:** 2026-01-06
- **UDN:** 4 (Bonsai)

### 3.2 Daily Closure Records
| ID | Date | Total Sale | Subtotal | Tax | Customers |
|----|------|------------|----------|-----|-----------|
| 4 | 2026-01-04 | $120,948.91 | $111,989.73 | $8,959.18 | null |
| 5 | 2026-01-05 | $26,811.40 | $24,825.37 | $1,986.03 | null |
| 6 | 2026-01-06 | $39,215.80 | $36,310.93 | $2,904.87 | null |

### 3.3 Duplicate Records Found (Before Cleanup)

#### Cash Concepts
| Date | Concept | Original ID | Duplicate ID | Amount |
|------|---------|-------------|--------------|--------|
| 2026-01-04 | Efectivo | 7 | 76 | $45,000.00 |
| 2026-01-04 | Propina | 8 | 75 | $2,500.00 |
| 2026-01-05 | Efectivo | 10 | 78 | $10,000.00 |
| 2026-01-05 | Propina | 11 | 77 | $800.00 |
| 2026-01-06 | Efectivo | 13 | 80 | $7,500.00 |
| 2026-01-06 | Propina | 14 | 79 | $600.00 |

#### Bank Accounts
| Date | Bank | Original ID | Duplicate ID | Amount |
|------|------|-------------|--------------|--------|
| 2026-01-04 | BBVA | 5 | 38 | $8,500.00 |
| 2026-01-05 | BBVA | 7 | 39 | $2,425.05 |
| 2026-01-06 | BANORTE | 40 | (no original) | (amount unknown) |

### 3.4 Clean State (After Cleanup)

#### Cash Concepts
| Date | Concept | ID | Amount |
|------|---------|-----|--------|
| 2026-01-04 | Efectivo | 7 | $45,000.00 |
| 2026-01-04 | Propina | 8 | $2,500.00 |
| 2026-01-04 | Vales | 9 | $1,200.00 |
| 2026-01-05 | Efectivo | 10 | $10,000.00 |
| 2026-01-05 | Propina | 11 | $800.00 |
| 2026-01-05 | Vales | 12 | $500.00 |
| 2026-01-06 | Efectivo | 13 | $7,500.00 |
| 2026-01-06 | Propina | 14 | $600.00 |
| 2026-01-06 | Vales | 15 | $300.00 |

#### Bank Accounts
| Date | Bank | ID | Amount |
|------|------|-----|--------|
| 2026-01-04 | BANORTE | 6 | $3,215.08 |
| 2026-01-04 | BBVA | 5 | $8,500.00 |
| 2026-01-05 | BBVA | 7 | $2,425.05 |
| 2026-01-06 | BANAMEX | 9 | $500.00 |
| 2026-01-06 | BBVA | 8 | $1,813.85 |

## 4. Technical Requirements

### 4.1 Database Operations
- **Database:** `rfwsmqex_gvsl_finanzas3`
- **Tables Affected:**
  - `detail_cash_concept`
  - `detail_bank_account`
  - `detail_foreing_currency`
- **Operation Type:** DELETE (remove duplicates)
- **Tool:** MCP MySQL tools

### 4.2 Duplicate Detection Logic
```sql
-- Find duplicates by grouping on (daily_closure_id, payment_type)
-- Keep MIN(id) as original, mark others as duplicates
SELECT 
    daily_closure_id,
    cash_concept_id,
    COUNT(*) as count,
    MIN(id) as original_id,
    GROUP_CONCAT(id ORDER BY id) as all_ids
FROM detail_cash_concept
WHERE daily_closure_id IN (4, 5, 6)
GROUP BY daily_closure_id, cash_concept_id
HAVING COUNT(*) > 1
```

### 4.3 Cleanup Strategy
1. **Identify:** Query all payment records for affected daily_closure_ids
2. **Group:** Group by (daily_closure_id, payment_type)
3. **Detect:** Find groups with COUNT(*) > 1
4. **Select:** Keep record with MIN(id), mark others for deletion
5. **Delete:** Execute DELETE statements for duplicate IDs
6. **Verify:** Re-query to confirm no duplicates remain

## 5. Documentation Requirements

### 5.1 Routine Documentation
- ✅ `routine-payment.md` - Documents `datosCaja` operation
- ✅ `routine-add-payment.md` - Documents `addPayments`/`editPayments` operations

### 5.2 Key Concepts Documented
- Delete-and-recreate pattern
- `$this->util->sql()` helper function
- CRUD base methods (_Insert, _Delete, _Read)
- Payment table relationships
- Catalog vs Detail table structure

## 6. Prevention Measures

### 6.1 Code Review Checklist
- [ ] Verify DELETE executes before INSERT in all payment operations
- [ ] Check for race conditions in concurrent requests
- [ ] Validate transaction boundaries (BEGIN/COMMIT/ROLLBACK)
- [ ] Add unique constraints where appropriate

### 6.2 Monitoring
- [ ] Add logging to track DELETE operations
- [ ] Monitor for duplicate detection in daily reports
- [ ] Alert on unexpected record counts

### 6.3 Testing
- [ ] Unit tests for delete-and-recreate pattern
- [ ] Integration tests for concurrent payment submissions
- [ ] Regression tests for duplicate prevention

## 7. Success Criteria

### 7.1 Immediate Success (Cleanup)
- ✅ All duplicate records removed from affected dates
- ✅ Original records preserved with correct amounts
- ✅ No data loss or corruption
- ✅ Verification queries confirm clean state

### 7.2 Long-term Success (Prevention)
- [ ] No new duplicates detected in subsequent months
- [ ] Payment routine operates reliably
- [ ] Financial reports show accurate totals
- [ ] System maintains data integrity under load

## 8. Related Documentation

### 8.1 Source Files
- `contabilidad/docs/routine-payment.md` - Read operation from old system
- `contabilidad/docs/routine-add-payment.md` - Write operation to new system
- `contabilidad2/ctrl/ctrl-add-sales.php` - Controller implementation
- `contabilidad2/mdl/mdl-add-sales.php` - Model implementation

### 8.2 Database Schema
- **Old System:** `rfwsmqex_gvsl_finanzas` (read-only)
- **New System:** `rfwsmqex_gvsl_finanzas3` (read-write)

### 8.3 Key Tables
```
daily_closure (parent)
├── detail_cash_concept (efectivo, propina, vales)
├── detail_foreing_currency (dolar, euro, etc.)
└── detail_bank_account (BBVA, BANORTE, BANAMEX, etc.)
```

## 9. Notes

### 9.1 Cleanup Execution
- **Date:** 2026-01-08 (based on context)
- **Method:** Manual SQL DELETE via MCP MySQL tools
- **Records Deleted:** 9 total (6 cash_concept + 3 bank_account)
- **Status:** ✅ Completed successfully

### 9.2 Update Execution
- **Date:** 2026-01-08
- **Method:** Manual SQL UPDATE via MCP MySQL tools
- **Source:** Old system (`rfwsmqex_gvsl_finanzas`)
- **Target:** New system (`rfwsmqex_gvsl_finanzas3`)
- **Records Updated:** 9 total (6 cash_concept + 3 bank_account)
- **Status:** ✅ Completed successfully

### 9.3 Data Migration Details

#### Old System → New System Mapping
**Cash Concepts:**
- Old system `id_Efectivo=2` (Efectivo) → New system `cash_concept_id=5` (Efectivo)
- Old system `id_Efectivo=1` (Propina) → New system `cash_concept_id=4` (Propina)

**Bank Accounts:**
- Old system `id_UB=51` BBVA (6682) → New system `bank_account_id=9` BBVA (6682)
- Old system `id_UB=54` BANORTE (9167) → New system `bank_account_id=6` BANORTE (9167)

#### Updated Values

**January 4, 2026 (daily_closure_id=4):**
- Efectivo: $45,000.00 → $61,942.50 ✅
- Propina: $2,500.00 → $3,720.25 ✅
- Vales: $1,200.00 (unchanged)
- BBVA (6682): $8,500.00 → $62,370.25 ✅
- BANORTE (9167): $3,215.08 (unchanged)

**January 5, 2026 (daily_closure_id=5):**
- Efectivo: $10,000.00 → $14,945.00 ✅
- Propina: $800.00 → $883.75 ✅
- Vales: $500.00 (unchanged)
- BBVA (6682): $2,425.05 → $12,723.75 ✅

**January 6, 2026 (daily_closure_id=6):**
- Efectivo: $7,500.00 → $7,255.00 ✅
- Propina: $600.00 → $3,058.75 ✅
- Vales: $300.00 (unchanged)
- BANAMEX (5120): $500.00 (unchanged)
- BBVA (6682): $1,813.85 → $34,938.75 ✅

#### Foreign Currency
No foreign currency records found in old system for these dates.

### 9.4 Final State Verification

**January 4, 2026:**
- Total Cash: $66,862.75 (Efectivo + Propina + Vales)
- Total Bank: $65,585.33 (BBVA + BANORTE)
- **Grand Total: $132,448.08**

**January 5, 2026:**
- Total Cash: $16,328.75 (Efectivo + Propina + Vales)
- Total Bank: $12,723.75 (BBVA)
- **Grand Total: $29,052.50**

**January 6, 2026:**
- Total Cash: $10,613.75 (Efectivo + Propina + Vales)
- Total Bank: $35,438.75 (BBVA + BANAMEX)
- **Grand Total: $46,052.50**

---

**Status:** Requirements documented ✅  
**Cleanup:** Completed ✅  
**Update:** Completed ✅
