# Design Document

## System Architecture

### Overview
El sistema de sincronización de categorías Fogaza implementa un patrón MVC (Model-View-Controller) dentro del módulo de ventas KPI del ERP Varoch. La sincronización se realiza mediante dos métodos principales que procesan ventas capturadas y las transfieren al sistema de folios de soft_restaurant.

### Component Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend (JavaScript)                     │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  app.syncToFolio(fecha, udn)                         │  │
│  │  app.syncMonthToFolio(anio, mes, udn)                │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            │
                            │ AJAX POST
                            ▼
┌─────────────────────────────────────────────────────────────┐
│              Controller (ctrl-ventas2.php)                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │  syncToFolio()                                        │  │
│  │  syncMonthToFolio()                                   │  │
│  │  - Validación de datos                               │  │
│  │  - Cálculo de impuestos                              │  │
│  │  - Acumulación por categoría                         │  │
│  │  - Orquestación de modelos                           │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                            │
                            │
                ┌───────────┴───────────┐
                ▼                       ▼
┌──────────────────────────┐  ┌──────────────────────────┐
│  Model (mdl-ventas2.php) │  │  Model (mdl-ventas.php)  │
│  ┌────────────────────┐  │  │  ┌────────────────────┐  │
│  │ listSales()        │  │  │  │ getFolioByFechaUdn │  │
│  │ getSaleById()      │  │  │  │ createFolio()      │  │
│  │ createSale()       │  │  │  │ getVentaByFolioId  │  │
│  │ updateSale()       │  │  │  │ createVenta()      │  │
│  └────────────────────┘  │  │  │ updateVenta()      │  │
└──────────────────────────┘  │  └────────────────────┘  │
                              └──────────────────────────┘
                                        │
                                        ▼
                              ┌──────────────────────────┐
                              │  Database (MySQL)        │
                              │  - soft_restaurant_folio │
                              │  - soft_restaurant_ventas│
                              │  - ventas_udn            │
                              │  - ventas_udn_detalle    │
                              └──────────────────────────┘
```

## Data Flow

### Individual Sync Flow (syncToFolio)

```
1. Frontend Request
   ├─ fecha: "2025-01-04"
   └─ udn: 1

2. Controller Processing
   ├─ Retrieve sales for date
   ├─ Initialize category accumulators
   │  ├─ Fogaza: abarrotes, bizcocho, bocadillos, frances
   │  │          pasteleria_normal, pasteleria_premium
   │  │          refrescos, velas
   │  └─ Hotel: alimentos, bebidas, hospedaje, otros, ayb, diversos
   │
   ├─ Process each sale
   │  ├─ Calculate taxes (8% IVA, 2% IEPS for hospedaje)
   │  ├─ Normalize category name (lowercase, trim)
   │  ├─ Handle accent variations
   │  └─ Accumulate to category variable
   │
   ├─ Calculate totals
   │  ├─ subtotal = sum of all categories
   │  └─ total = subtotal (taxes already included)
   │
   └─ Sync to folio system
      ├─ Check if folio exists
      ├─ Create folio if needed
      ├─ Check if venta exists
      └─ Create or update venta record

3. Response
   ├─ status: 200
   ├─ message: "Ventas actualizadas correctamente"
   ├─ folio_id: 12345
   ├─ Category breakdown
   └─ totals
```

### Monthly Sync Flow (syncMonthToFolio)

```
1. Frontend Request
   ├─ anio: 2025
   ├─ mes: 1
   └─ udn: 1

2. Controller Processing
   ├─ Retrieve all sales for month
   ├─ Extract unique dates
   │
   └─ For each date:
      ├─ Initialize category accumulators
      ├─ Process sales for that date
      ├─ Calculate taxes and totals
      ├─ Sync to folio system
      └─ Track result (success/failure)

3. Response
   ├─ status: 200
   ├─ message: "Sincronización completada: X exitosos, Y fallidos"
   ├─ exitosos: count
   ├─ fallidos: count
   └─ resultados: [
        { fecha, status, message, total },
        ...
      ]
```

## Tax Calculation Logic

### Formula

```
For all Fogaza categories:
  IVA = base_amount × 0.08
  IEPS = 0
  total_with_taxes = base_amount + IVA

For Hospedaje category:
  IVA = base_amount × 0.08
  IEPS = base_amount × 0.02
  total_with_taxes = base_amount + IVA + IEPS
```

### Implementation

```php
$cantidadSinImpuestos = floatval($venta['cantidad']);
$categoria = strtolower(trim($venta['categoria']));

$iva = $cantidadSinImpuestos * 0.08;
$ieps = 0;

if ($categoria === 'hospedaje') {
    $ieps = $cantidadSinImpuestos * 0.02;
}

$cantidadConImpuestos = $cantidadSinImpuestos + $iva + $ieps;
```

## Category Mapping

### Fogaza Categories

| Category Name | Variations | Tax Rate | Variable Name |
|---------------|-----------|----------|---------------|
| Abarrotes | abarrotes | 8% IVA | `$abarrotes` |
| Bizcocho | bizcocho | 8% IVA | `$bizcocho` |
| Bocadillos | bocadillos | 8% IVA | `$bocadillos` |
| Francés | frances, francés | 8% IVA | `$frances` |
| Pastelería Normal | pasteleria normal, pastelería normal | 8% IVA | `$pasteleria_normal` |
| Pastelería Premium | pasteleria premium, pastelería premium | 8% IVA | `$pasteleria_premium` |
| Refrescos | refrescos | 8% IVA | `$refrescos` |
| Velas | velas | 8% IVA | `$velas` |

### Hotel Categories

| Category Name | Variations | Tax Rate | Variable Name |
|---------------|-----------|----------|---------------|
| Alimentos | alimentos | 8% IVA | `$alimentos` |
| Bebidas | bebidas | 8% IVA | `$bebidas` |
| Hospedaje | hospedaje | 8% IVA + 2% IEPS | `$hospedaje` |
| Otros | otros, otros ingresos | 8% IVA | `$otros` |
| A&B | ayb, a&b | 8% IVA | `$ayb` |
| Diversos | diversos, misceláneos | 8% IVA | `$diversos` |

## Switch Statement Pattern

### Structure

```php
switch ($categoria) {
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
}
```

### Key Design Decisions

1. **Lowercase Comparison**: All category names are converted to lowercase before switch comparison
2. **Multiple Cases**: Categories with accent variations use multiple case statements
3. **Accumulation Pattern**: Each case adds to its respective variable using `+=` operator
4. **Break Statements**: Each case ends with `break` to prevent fall-through

## Database Schema

### Tables Involved

#### soft_restaurant_folio
```sql
- id_folio (PK)
- fecha_folio
- id_udn
- file_productos_vendidos
- file_ventas_dia
- monto_productos_vendidos
- monto_ventas_dia
```

#### soft_restaurant_ventas
```sql
- id_venta (PK)
- soft_ventas_fecha
- soft_folio (FK)
- alimentos
- bebidas
- AyB
- otros
- Diversos
- Hospedaje
- subtotal
- iva
- personas
- noHabitaciones
- total
```

#### ventas_udn_detalle
```sql
- id
- id_UV (FK)
- Fecha_Venta
- Cantidad
- (other fields)
```

## Error Handling

### Scenarios

1. **Folio Creation Failure**
   - Status: 500
   - Message: "Error al crear el folio"
   - Action: Stop processing, return error

2. **No Sales Found**
   - Status: 404
   - Message: "No se encontraron ventas para el mes seleccionado"
   - Action: Return empty data array

3. **Venta Update/Create Failure**
   - Status: 500
   - Message: "Error al actualizar/crear"
   - Action: Track as failed, continue with next date (monthly sync)

4. **Partial Success (Monthly)**
   - Status: 200
   - Message: "Sincronización completada: X exitosos, Y fallidos"
   - Action: Return detailed results array

## Performance Considerations

### Optimization Strategies

1. **Single Query for Month**: Retrieve all sales for the month in one query
2. **Date Grouping**: Process sales grouped by date to minimize database calls
3. **Batch Processing**: Monthly sync processes multiple dates in one request
4. **Minimal Updates**: Only update venta records if they exist, create if needed

### Scalability

- Current implementation handles up to 31 dates per monthly sync
- Each date processes all categories in a single loop
- Database operations are minimized through model abstraction

## Security Considerations

1. **Input Validation**: All POST parameters should be validated
2. **SQL Injection Prevention**: Using parameterized queries through CRUD class
3. **Access Control**: Should verify user permissions before sync operations
4. **Data Integrity**: Transactions should be used for folio/venta creation

## Integration Points

### Frontend Integration
```javascript
// Individual sync
app.syncToFolio(fecha, udn);

// Monthly sync
app.syncMonthToFolio(anio, mes, udn);
```

### Backend Integration
```php
// Controller methods
syncToFolio()
syncMonthToFolio()

// Model dependencies
mdl-ventas2.php: listSales(), getSaleById()
mdl-ventas.php: getFolioByFechaUdn(), createFolio(), 
                getVentaByFolioId(), createVenta(), updateVenta()
```

## Testing Strategy

### Unit Tests
- Tax calculation logic
- Category name normalization
- Accent variation handling
- Accumulation logic

### Integration Tests
- Folio creation flow
- Venta update flow
- Monthly sync with multiple dates
- Error handling scenarios

### Test Data
```php
// Sample test cases
[
    ['categoria' => 'Abarrotes', 'cantidad' => 100],
    ['categoria' => 'Francés', 'cantidad' => 200],
    ['categoria' => 'frances', 'cantidad' => 150],
    ['categoria' => 'Pastelería Normal', 'cantidad' => 300],
    ['categoria' => 'pasteleria premium', 'cantidad' => 500]
]
```

## Future Enhancements

1. **Transaction Support**: Wrap folio/venta operations in database transactions
2. **Async Processing**: Implement queue system for large monthly syncs
3. **Audit Trail**: Enhanced logging of all sync operations
4. **Rollback Capability**: Ability to undo sync operations
5. **Category Management**: Dynamic category configuration instead of hardcoded
6. **Validation Rules**: Configurable tax rates per category
7. **Notification System**: Alert users of sync completion/failures
