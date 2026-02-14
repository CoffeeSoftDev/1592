# Design Document - Módulo Clientes Consolidado

## Architecture Overview

### System Components

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend (JavaScript)                     │
│  ┌──────────────────────┐  ┌──────────────────────────────┐ │
│  │  CustomersConsolidated│  │   Summary Cards Component    │ │
│  │  - render()           │  │   - renderSummaryCards()     │ │
│  │  - lsConsolidated()   │  │   - calculateTotals()        │ │
│  └──────────────────────┘  └──────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              ↓ AJAX
┌─────────────────────────────────────────────────────────────┐
│                   Controller (ctrl-clientes.php)             │
│  ┌──────────────────────────────────────────────────────────┐│
│  │  lsConsolidated()                                        ││
│  │  - Calculate initial balance (debt before period)       ││
│  │  - Insert special control rows                          ││
│  │  - Generate customer rows with movements                ││
│  │  - Calculate summary card totals                        ││
│  │  - Return: { cards: {...}, thead: '', row: [...] }     ││
│  └──────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
                              ↓ SQL
┌─────────────────────────────────────────────────────────────┐
│                    Model (mdl-clientes.php)                  │
│  ┌──────────────────────────────────────────────────────────┐│
│  │  New Methods:                                            ││
│  │  - getCustomerInitialBalance($customer_id, $udn, $fi)   ││
│  │  - getTotalInitialBalance($udn, $fi)                    ││
│  │  - getPeriodTotals($udn, $fi, $ff)                      ││
│  │  - getCustomerPeriodTotals($customer_id, $udn, $fi, $ff)││
│  └──────────────────────────────────────────────────────────┘│
└─────────────────────────────────────────────────────────────┘
```

## Data Flow

### 1. Initial Balance Calculation

**Purpose:** Calculate debt accumulated before the period start date

**Formula:** 
```
initial_balance = SUM(consumos before fi) - SUM(pagos before fi)
```

**Implementation:**
```php
// Model method
function getCustomerInitialBalance($array) {
    // $array = ['customer_id', 'udn', 'fi']
    // Returns: float (debt before period)
}

function getTotalInitialBalance($array) {
    // $array = ['udn', 'fi']
    // Returns: float (total debt of all customers before period)
}
```

### 2. Period Totals Calculation

**Purpose:** Calculate consumos and pagos within the period

**Formula:**
```
period_consumos = SUM(consumos WHERE date BETWEEN fi AND ff)
period_pagos = SUM(pagos WHERE date BETWEEN fi AND ff)
```

**Implementation:**
```php
// Model method
function getPeriodTotals($array) {
    // $array = ['udn', 'fi', 'ff']
    // Returns: ['total_consumos' => float, 'total_pagos' => float]
}

function getCustomerPeriodTotals($array) {
    // $array = ['customer_id', 'udn', 'fi', 'ff']
    // Returns: ['consumos' => float, 'pagos' => float]
}
```

### 3. Final Balance Calculation

**Purpose:** Calculate debt at the end of the period

**Formula:**
```
final_balance = initial_balance + period_consumos - period_pagos
```

## Table Structure

### Row Types

1. **Special Control Rows** (opc: 1, non-collapsible)
   - Saldo inicial
   - Consumo a crédito
   - Pagos y anticipos
   - Saldo final

2. **Customer Rows** (opc: 0, collapsible)
   - Individual customer data with movements by date

3. **Total Rows** (opc: 1, non-collapsible)
   - Total de consumos a crédito
   - Total de pagos y anticipos

### Column Structure

```
┌──────────┬──────────────────────────────────────────────────────────┬────────┐
│ Cliente  │  DÍA 01/ENE        DÍA 02/ENE        DÍA 03/ENE ...      │ DEUDA  │
│          │  CONSUMOS | PAGOS  CONSUMOS | PAGOS  CONSUMOS | PAGOS    │        │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Saldo    │  -        | -      -        | -      -        | -        │ $X,XXX │
│ inicial  │           |                  |                  |         │        │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Consumo  │  $XXX     | -      $XXX     | -      $XXX     | -        │ $X,XXX │
│ a crédito│           |                  |                  |         │        │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Pagos y  │  -        | $XXX   -        | $XXX   -        | $XXX     │ $X,XXX │
│ anticipos│           |                  |                  |         │        │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Saldo    │  -        | -      -        | -      -        | -        │ $X,XXX │
│ final    │           |                  |                  |         │        │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Cliente A│  $XXX     | $XXX   $XXX     | $XXX   $XXX     | $XXX     │ $X,XXX │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Cliente B│  $XXX     | $XXX   $XXX     | $XXX   $XXX     | $XXX     │ $X,XXX │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Total de │  $XXX     | -      $XXX     | -      $XXX     | -        │ $X,XXX │
│ consumos │           |                  |                  |         │        │
├──────────┼──────────────────────────────────────────────────────────┼────────┤
│ Total de │  -        | $XXX   -        | $XXX   -        | $XXX     │ $X,XXX │
│ pagos    │           |                  |                  |         │        │
└──────────┴──────────────────────────────────────────────────────────┴────────┘
```

## Controller Logic (lsConsolidated)

### Step-by-Step Process

```php
function lsConsolidated() {
    // 1. Get parameters
    $fi  = $_POST['fi'];
    $ff  = $_POST['ff'];
    $udn = $_POST['udn'];

    // 2. Initialize data structures
    $rows = [];
    $dateColumns = $this->getDateRange($fi, $ff);
    $dateColumnTotals = []; // ['date' => ['consumos' => 0, 'pagos' => 0]]

    // 3. Calculate summary card totals
    $initialBalance = $this->getTotalInitialBalance([$udn, $fi]);
    $periodTotals = $this->getPeriodTotals([$udn, $fi, $ff]);
    $finalBalance = $initialBalance + $periodTotals['total_consumos'] - $periodTotals['total_pagos'];

    // 4. Insert "Saldo inicial" row
    $rows[] = [
        'id' => 'saldo_inicial',
        'Cliente' => ['html' => '<strong>Saldo inicial</strong>', 'class' => 'font-bold bg-yellow-50'],
        // ... date columns with '-'
        'DEUDA' => ['html' => evaluar($initialBalance), 'class' => 'text-end font-bold'],
        'opc' => 1
    ];

    // 5. Insert "Consumo a crédito" row
    // Calculate consumos by date for the period
    
    // 6. Insert "Pagos y anticipos" row
    // Calculate pagos by date for the period

    // 7. Insert "Saldo final" row
    $rows[] = [
        'id' => 'saldo_final',
        'Cliente' => ['html' => '<strong>Saldo final</strong>', 'class' => 'font-bold bg-yellow-50'],
        // ... date columns with '-'
        'DEUDA' => ['html' => evaluar($finalBalance), 'class' => 'text-end font-bold'],
        'opc' => 1
    ];

    // 8. Insert customer rows (existing logic)
    // ...

    // 9. Insert total rows
    // ...

    // 10. Return data with cards
    return [
        'cards' => [
            'saldo_inicial' => $initialBalance,
            'consumos_credito' => $periodTotals['total_consumos'],
            'pagos_anticipos' => $periodTotals['total_pagos'],
            'saldo_final' => $finalBalance
        ],
        'thead' => '',
        'row' => $rows
    ];
}
```

## Model Methods Design

### getCustomerInitialBalance

**Purpose:** Calculate individual customer's debt before period

```php
function getCustomerInitialBalance($array) {
    // $array = ['customer_id', 'udn', 'fi']
    $query = "
        SELECT 
            IFNULL(SUM(CASE WHEN mt.operation_type = 0 THEN dcc.amount ELSE 0 END), 0) -
            IFNULL(SUM(CASE WHEN mt.operation_type = 1 THEN dcc.amount ELSE 0 END), 0) AS initial_balance
        FROM {$this->bd}detail_credit_customer dcc
        INNER JOIN {$this->bd}daily_closure dc ON dcc.daily_closure_id = dc.id
        INNER JOIN {$this->bd}movement_type mt ON dcc.movement_type_id = mt.id
        WHERE dcc.customer_id = ?
        AND dc.udn_id = ?
        AND DATE(dc.created_at) < ?
        AND dcc.active = '1'
    ";
    $result = $this->_Read($query, $array);
    return floatval($result[0]['initial_balance']);
}
```

### getTotalInitialBalance

**Purpose:** Calculate total debt of all customers before period

```php
function getTotalInitialBalance($array) {
    // $array = ['udn', 'fi']
    $query = "
        SELECT 
            IFNULL(SUM(CASE WHEN mt.operation_type = 0 THEN dcc.amount ELSE 0 END), 0) -
            IFNULL(SUM(CASE WHEN mt.operation_type = 1 THEN dcc.amount ELSE 0 END), 0) AS total_initial_balance
        FROM {$this->bd}detail_credit_customer dcc
        INNER JOIN {$this->bd}daily_closure dc ON dcc.daily_closure_id = dc.id
        INNER JOIN {$this->bd}movement_type mt ON dcc.movement_type_id = mt.id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) < ?
        AND dcc.active = '1'
    ";
    $result = $this->_Read($query, $array);
    return floatval($result[0]['total_initial_balance']);
}
```

### getPeriodTotals

**Purpose:** Calculate total consumos and pagos within period

```php
function getPeriodTotals($array) {
    // $array = ['udn', 'fi', 'ff']
    $query = "
        SELECT 
            IFNULL(SUM(CASE WHEN mt.operation_type = 0 THEN dcc.amount ELSE 0 END), 0) AS total_consumos,
            IFNULL(SUM(CASE WHEN mt.operation_type = 1 THEN dcc.amount ELSE 0 END), 0) AS total_pagos
        FROM {$this->bd}detail_credit_customer dcc
        INNER JOIN {$this->bd}daily_closure dc ON dcc.daily_closure_id = dc.id
        INNER JOIN {$this->bd}movement_type mt ON dcc.movement_type_id = mt.id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) BETWEEN ? AND ?
        AND dcc.active = '1'
    ";
    $result = $this->_Read($query, $array);
    return $result[0];
}
```

### getCustomerPeriodTotals

**Purpose:** Calculate individual customer's consumos and pagos within period

```php
function getCustomerPeriodTotals($array) {
    // $array = ['customer_id', 'udn', 'fi', 'ff']
    $query = "
        SELECT 
            IFNULL(SUM(CASE WHEN mt.operation_type = 0 THEN dcc.amount ELSE 0 END), 0) AS consumos,
            IFNULL(SUM(CASE WHEN mt.operation_type = 1 THEN dcc.amount ELSE 0 END), 0) AS pagos
        FROM {$this->bd}detail_credit_customer dcc
        INNER JOIN {$this->bd}daily_closure dc ON dcc.daily_closure_id = dc.id
        INNER JOIN {$this->bd}movement_type mt ON dcc.movement_type_id = mt.id
        WHERE dcc.customer_id = ?
        AND dc.udn_id = ?
        AND DATE(dc.created_at) BETWEEN ? AND ?
        AND dcc.active = '1'
    ";
    $result = $this->_Read($query, $array);
    return $result[0];
}
```

### getConsumosByDate

**Purpose:** Get total consumos for a specific date

```php
function getConsumosByDate($array) {
    // $array = ['udn', 'fecha']
    $query = "
        SELECT 
            IFNULL(SUM(dcc.amount), 0) AS total
        FROM {$this->bd}detail_credit_customer dcc
        INNER JOIN {$this->bd}daily_closure dc ON dcc.daily_closure_id = dc.id
        INNER JOIN {$this->bd}movement_type mt ON dcc.movement_type_id = mt.id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) = ?
        AND mt.operation_type = 0
        AND dcc.active = '1'
    ";
    $result = $this->_Read($query, $array);
    return floatval($result[0]['total']);
}
```

### getPagosByDate

**Purpose:** Get total pagos for a specific date

```php
function getPagosByDate($array) {
    // $array = ['udn', 'fecha']
    $query = "
        SELECT 
            IFNULL(SUM(dcc.amount), 0) AS total
        FROM {$this->bd}detail_credit_customer dcc
        INNER JOIN {$this->bd}daily_closure dc ON dcc.daily_closure_id = dc.id
        INNER JOIN {$this->bd}movement_type mt ON dcc.movement_type_id = mt.id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) = ?
        AND mt.operation_type = 1
        AND dcc.active = '1'
    ";
    $result = $this->_Read($query, $array);
    return floatval($result[0]['total']);
}
```

## Frontend Component Design

### Summary Cards Component

```javascript
renderSummaryCards(cards) {
    this.infoCard({
        parent: `cards${this.PROJECT_NAME}`,
        theme: "light",
        style: "file",
        class: "mb-4",
        json: [
            {
                id: "kpiSaldoInicial",
                title: "Saldo inicial",
                bgColor: "bg-yellow-100",
                borderColor: "border-yellow-300",
                data: { 
                    value: formatPrice(cards.saldo_inicial || 0), 
                    color: cards.saldo_inicial > 0 ? "text-red-700" : "text-green-700"
                }
            },
            {
                id: "kpiConsumosCredito",
                title: "Consumos a crédito",
                bgColor: "bg-red-100",
                borderColor: "border-red-300",
                data: { 
                    value: formatPrice(cards.consumos_credito || 0), 
                    color: "text-red-700" 
                }
            },
            {
                id: "kpiPagosAnticipos",
                title: "Pagos y anticipos",
                bgColor: "bg-green-100",
                borderColor: "border-green-300",
                data: { 
                    value: formatPrice(cards.pagos_anticipos || 0), 
                    color: "text-green-700" 
                }
            },
            {
                id: "kpiSaldoFinal",
                title: "Saldo final",
                bgColor: "bg-blue-100",
                borderColor: "border-blue-300",
                data: { 
                    value: formatPrice(cards.saldo_final || 0), 
                    color: cards.saldo_final > 0 ? "text-red-700" : "text-green-700"
                }
            }
        ]
    });
}
```

### Modified lsConsolidated Method

```javascript
async lsConsolidated() {
    const rangePicker = getDataRangePicker(`calendarContabilidad`);
    const udn = $(`#filterBarContabilidad #udn`).val() || udn_id;

    $(`#container${this.PROJECT_NAME}`).html('<div class="flex justify-center items-center py-8"><i class="icon-spin4 animate-spin text-2xl text-gray-500"></i></div>');

    const response = await useFetch({
        url: api_clientes,
        data: {
            opc: 'lsConsolidated',
            fi: rangePicker.fi,
            ff: rangePicker.ff,
            udn: udn
        }
    });

    // Render summary cards
    if (response.cards) {
        this.renderSummaryCards(response.cards);
    }

    // Render table
    this.createCoffeTable2({
        parent: `container${this.PROJECT_NAME}`,
        id: `tbCustomersConsolidated`,
        theme: 'light',
        title: `Concentrado de Clientes`,
        subtitle: `Reporte consolidado de consumos y pagos por cliente`,
        data: response,
        collapsed: false,
        color_group: 'bg-blue-50',
        fixed: [1],
        folding: false,
        f_size: 12,
        selectable: true
    });
}
```

## Correctness Properties

### Property 1: Balance Consistency
```
∀ customer: final_balance = initial_balance + period_consumos - period_pagos
```

### Property 2: Total Consistency
```
SUM(customer_consumos) = total_consumos_credito
SUM(customer_pagos) = total_pagos_anticipos
```

### Property 3: Date Range Completeness
```
∀ date ∈ [fi, ff]: date appears in table columns
```

### Property 4: Non-Negative Amounts
```
∀ amount: amount >= 0
```

### Property 5: Special Row Positioning
```
Row order: [Saldo inicial, Consumo a crédito, Pagos y anticipos, Saldo final, ...customers..., Total consumos, Total pagos]
```

## Visual Styling

### Color Scheme

| Element | Background | Text Color | Border |
|---------|-----------|------------|--------|
| Consumos columns | `bg-red-50` | `text-red-600` | - |
| Pagos columns | `bg-green-50` | `text-green-600` | - |
| Special control rows | `bg-yellow-50` | `text-gray-800` | - |
| Total rows | `bg-gray-200` | `text-gray-800` | - |
| Customer rows | `bg-white` | `text-gray-800` | - |
| Positive debt | - | `text-red-700` | - |
| Negative debt | - | `text-green-700` | - |

### Typography

- **Special rows:** `font-bold`
- **Total rows:** `font-bold`
- **Customer names:** `font-semibold`
- **Amounts:** Right-aligned, formatted with `evaluar()`

## Implementation Checklist

### Backend (PHP)

- [ ] Add `getCustomerInitialBalance()` to model
- [ ] Add `getTotalInitialBalance()` to model
- [ ] Add `getPeriodTotals()` to model
- [ ] Add `getCustomerPeriodTotals()` to model
- [ ] Add `getConsumosByDate()` to model
- [ ] Add `getPagosByDate()` to model
- [ ] Modify `lsConsolidated()` in controller to:
  - [ ] Calculate initial balance
  - [ ] Insert "Saldo inicial" row
  - [ ] Insert "Consumo a crédito" row with date breakdown
  - [ ] Insert "Pagos y anticipos" row with date breakdown
  - [ ] Insert "Saldo final" row
  - [ ] Return cards data structure
- [ ] Update customer row calculation to use new methods

### Frontend (JavaScript)

- [ ] Add `renderSummaryCards()` method
- [ ] Modify `lsConsolidated()` to:
  - [ ] Render summary cards from response
  - [ ] Handle new data structure
- [ ] Add cards container to layout
- [ ] Update styling for special rows

### Testing

- [ ] Verify initial balance calculation
- [ ] Verify period totals calculation
- [ ] Verify final balance formula
- [ ] Verify special row positioning
- [ ] Verify customer row data
- [ ] Verify total row calculations
- [ ] Verify summary cards display
- [ ] Verify Excel export includes all data

## Performance Considerations

1. **Query Optimization:**
   - Use indexed columns (customer_id, udn_id, created_at)
   - Minimize number of queries (batch calculations where possible)
   - Consider caching for frequently accessed data

2. **Data Volume:**
   - Limit date range to reasonable periods (e.g., max 31 days)
   - Implement pagination if customer count is high
   - Use lazy loading for customer details

3. **Frontend Rendering:**
   - Use virtual scrolling for large tables
   - Debounce filter changes
   - Show loading indicators during data fetch

## Error Handling

1. **Missing Data:**
   - Return 0 for null/empty calculations
   - Show "-" for zero values in table
   - Display message if no customers found

2. **Invalid Date Range:**
   - Validate fi < ff
   - Show error message for invalid ranges
   - Default to current month if not specified

3. **Database Errors:**
   - Log errors to server
   - Show user-friendly error message
   - Provide retry option

## Future Enhancements

1. **Drill-down capability:** Click on customer to see detailed transactions
2. **Date grouping options:** Daily, weekly, monthly views
3. **Export formats:** PDF, CSV in addition to Excel
4. **Filters:** By customer, by amount range, by payment method
5. **Charts:** Visual representation of consumos vs pagos trends
6. **Notifications:** Alert when customer debt exceeds threshold

---

**Document Version:** 1.0  
**Last Updated:** 2025-01-02  
**Author:** CoffeeIA ☕  
**Status:** Ready for Implementation
