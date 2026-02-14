# Diseño Técnico: Módulo Details-Sale

## 📋 Información del Documento

**Proyecto:** Details-Sale (Detalle de Ventas Diarias)  
**Versión:** 1.0  
**Fecha:** 2025-01-28  
**Estado:** 📝 En Desarrollo

---

## 🏗️ Arquitectura General

### Diagrama de Componentes

```
┌─────────────────────────────────────────────────────────────────────┐
│                         FRONTEND (JS)                               │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    DetailsSale Class                         │   │
│  │  extends Templates                                           │   │
│  │                                                              │   │
│  │  ├── render()                                                │   │
│  │  ├── layout()                                                │   │
│  │  ├── filterBar()                                             │   │
│  │  ├── lsVentas()                                              │   │
│  │  ├── renderCaratula(data, info)                              │   │
│  │  ├── createVentasCard(data)                                  │   │
│  │  ├── createPagosCard(data)                                   │   │
│  │  └── createDiferenciaCard(diferencia)                        │   │
│  └─────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
                                │
                                │ useFetch (AJAX)
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                      CONTROLADOR (PHP)                              │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    ctrl extends mdl                          │   │
│  │                                                              │   │
│  │  ├── init()           → Retorna UDNs para filtros            │   │
│  │  └── showSale()       → Consolida datos de ventas/pagos      │   │
│  └─────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
                                │
                                │ Herencia
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                        MODELO (PHP)                                 │
│  ┌─────────────────────────────────────────────────────────────┐   │
│  │                    mdl extends CRUD                          │   │
│  │                                                              │   │
│  │  ├── lsUDN()                                                 │   │
│  │  ├── getVentas($array)                                       │   │
│  │  ├── getDescuentos($array)                                   │   │
│  │  ├── getImpuestos($array)                                    │   │
│  │  ├── getConceptosVenta($array)                               │   │
│  │  ├── getMonedasExtranjeras($array)                           │   │
│  │  ├── getBancos($array)                                       │   │
│  │  └── getCreditPayments($array)                               │   │
│  └─────────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────────┘
                                │
                                │ _Read()
                                ▼
┌─────────────────────────────────────────────────────────────────────┐
│                    BASE DE DATOS (MySQL)                            │
│                  rfwsmqex_gvsl_finanzas3                            │
│                                                                     │
│  ├── daily_closure                                                  │
│  ├── detail_sale_category                                           │
│  ├── detail_sale_category_tax                                       │
│  ├── detail_cash_concept                                            │
│  ├── cash_concept                                                   │
│  ├── detail_foreign_currency                                        │
│  ├── foreign_currency                                               │
│  ├── detail_bank_account                                            │
│  └── detail_credit_customer                                         │
└─────────────────────────────────────────────────────────────────────┘
```

---

## 📁 Estructura de Archivos

```
finanzas/consulta_respaldo/
├── details-sale.php                    # Vista principal (index)
├── js/
│   └── details-sale.js                 # Clase DetailsSale (Frontend)
├── ctrl/
│   └── ctrl-details-sale.php           # Controlador
└── mdl/
    └── mdl-details-sale.php            # Modelo
```

---

## 🔧 Componentes e Interfaces

### 1. Frontend: `DetailsSale` (details-sale.js)

```javascript
class DetailsSale extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "DetailsSale";
    }
}
```

#### Métodos Públicos

| Método | Descripción | Parámetros | Retorno |
|--------|-------------|------------|---------|
| `render()` | Inicializa el módulo | - | void |
| `layout()` | Crea estructura HTML base | - | void |
| `filterBar()` | Genera barra de filtros | - | void |
| `lsVentas()` | Consulta y muestra datos | - | void |
| `renderCaratula(data, info)` | Renderiza cards con datos | `data: Object, info: Object` | void |
| `createVentasCard(data)` | Crea card de ventas | `data: Object` | void |
| `createPagosCard(data)` | Crea card de pagos | `data: Object` | void |
| `createDiferenciaCard(diferencia)` | Crea card de diferencia | `diferencia: float` | void |

#### Estructura de Datos Esperada

```javascript
// Respuesta de showSale()
{
    status: 200,
    message: "Información obtenida correctamente",
    data: {
        ventas: {
            ventas: 15000.00,
            descuentos: 500.00,
            impuestos: 1200.00,
            total: 15700.00
        },
        pagos: {
            conceptos: {
                "Efectivo": 8000.00,
                "Propina": 200.00,
                "Vales": 1500.00
            },
            monedas: {
                "Dólar": 500.00
            },
            bancos: 3000.00,
            credito: 2000.00,
            pagos_credito: 500.00,
            total: 14700.00
        },
        diferencia: 1000.00
    }
}
```

---

### 2. Controlador: `ctrl` (ctrl-details-sale.php)

```php
class ctrl extends mdl {
    function init() { }
    function showSale() { }
}
```

#### Método `init()`

**Propósito:** Inicializar filtros del módulo

**Entrada:** Ninguna

**Salida:**
```php
[
    'udn' => array  // Lista de UDNs activas
]
```

#### Método `showSale()`

**Propósito:** Consolidar datos de ventas y pagos del día

**Entrada (POST):**
```php
$_POST['fi']  // Fecha inicial (YYYY-MM-DD)
$_POST['ff']  // Fecha final (YYYY-MM-DD)
$_POST['udn'] // ID de UDN
```

**Salida:**
```php
[
    'status'  => int,     // 200 = éxito, 500 = error
    'message' => string,  // Mensaje descriptivo
    'data'    => [
        'ventas' => [
            'ventas'     => float,
            'descuentos' => float,
            'impuestos'  => float,
            'total'      => float
        ],
        'pagos' => [
            'conceptos'     => array,  // ['nombre' => monto]
            'monedas'       => array,  // ['nombre' => monto]
            'bancos'        => float,
            'credito'       => float,
            'pagos_credito' => float,
            'total'         => float
        ],
        'diferencia' => float
    ]
]
```

**Lógica de Cálculo:**
```php
// Total de ventas
$totalVentas = $ventas + $impuestos - $descuentos;

// Total de pagos
$totalConceptos = array_sum($conceptos);
$totalMonedas   = array_sum($monedas);
$totalCreditos  = $credito - $pagos_credito;
$totalPagos     = $totalConceptos + $totalMonedas + $bancos + $totalCreditos;

// Diferencia
$diferencia = $totalVentas - $totalPagos;
```

---

### 3. Modelo: `mdl` (mdl-details-sale.php)

```php
class mdl extends CRUD {
    protected $util;
    public $bd;
    
    public function __construct() {
        $this->util = new Utileria;
        $this->bd = "rfwsmqex_gvsl_finanzas3.";
    }
}
```

#### Métodos del Modelo

| Método | Descripción | SQL Base |
|--------|-------------|----------|
| `lsUDN()` | Lista UDNs activas | `SELECT FROM udn WHERE Stado = 1` |
| `getVentas($array)` | Suma de ventas | `SUM(dsc.sale)` |
| `getDescuentos($array)` | Suma descuentos + cortesías | `SUM(dsc.discount + dsc.courtesy)` |
| `getImpuestos($array)` | Suma de impuestos | `SUM(dsct.sale_tax + dsct.discount_tax + dsct.courtesy_tax)` |
| `getConceptosVenta($array)` | Conceptos de efectivo agrupados | `GROUP BY cc.id, cc.name` |
| `getMonedasExtranjeras($array)` | Monedas extranjeras agrupadas | `GROUP BY fc.id, fc.name` |
| `getBancos($array)` | Total depósitos bancarios | `SUM(dba.amount)` |
| `getCreditPayments($array)` | Créditos y pagos | `SUM(credit_payment), SUM(total_payment)` |

---

## 📊 Modelo de Datos

### Diagrama Entidad-Relación (Simplificado)

```
┌─────────────────┐       ┌──────────────────────┐
│  daily_closure  │───────│ detail_sale_category │
│  (id, udn_id,   │  1:N  │ (sale, discount,     │
│   created_at)   │       │  courtesy)           │
└─────────────────┘       └──────────────────────┘
        │                           │
        │                           │ 1:N
        │                           ▼
        │                 ┌──────────────────────────┐
        │                 │ detail_sale_category_tax │
        │                 │ (sale_tax, discount_tax, │
        │                 │  courtesy_tax)           │
        │                 └──────────────────────────┘
        │
        │ 1:N    ┌─────────────────────┐
        ├────────│ detail_cash_concept │
        │        │ (amount)            │
        │        └─────────────────────┘
        │                  │
        │                  │ N:1
        │                  ▼
        │        ┌─────────────────────┐
        │        │    cash_concept     │
        │        │ (name, operation)   │
        │        └─────────────────────┘
        │
        │ 1:N    ┌────────────────────────┐
        ├────────│ detail_foreign_currency│
        │        │ (amount, amount_mxn)   │
        │        └────────────────────────┘
        │
        │ 1:N    ┌─────────────────────┐
        ├────────│ detail_bank_account │
        │        │ (amount)            │
        │        └─────────────────────┘
        │
        │ 1:N    ┌───────────────────────┐
        └────────│ detail_credit_customer│
                 │ (amount)              │
                 └───────────────────────┘
```

### Consultas SQL Principales

#### Ventas del Día
```sql
SELECT IFNULL(SUM(dsc.sale), 0) AS ventas
FROM daily_closure dc
INNER JOIN detail_sale_category dsc ON dc.id = dsc.daily_closure_id
WHERE dc.udn_id = ?
AND DATE(dc.created_at) BETWEEN ? AND ?
```

#### Conceptos de Efectivo (Dinámico)
```sql
SELECT cc.name AS concepto, IFNULL(SUM(dcc.amount), 0) AS monto
FROM daily_closure dc
LEFT JOIN detail_cash_concept dcc ON dc.id = dcc.daily_closure_id
LEFT JOIN cash_concept cc ON dcc.cash_concept_id = cc.id
WHERE dc.udn_id = ?
AND DATE(dc.created_at) BETWEEN ? AND ?
AND cc.name IS NOT NULL
GROUP BY cc.id, cc.name
```

---

## ✅ Propiedades de Correctitud

### 1. Integridad de Datos

| Propiedad | Descripción | Validación |
|-----------|-------------|------------|
| **Consistencia de Totales** | Total Venta = Ventas - Descuentos + Impuestos | Cálculo en CTRL |
| **Consistencia de Pagos** | Total Pagado = Σ(Conceptos) + Σ(Monedas) + Bancos + Créditos | Cálculo en CTRL |
| **Diferencia Correcta** | Diferencia = Total Venta - Total Pagado | Cálculo en CTRL |
| **Valores No Nulos** | Todos los montos deben ser >= 0 | IFNULL en SQL |

### 2. Validaciones de Entrada

| Campo | Validación | Acción si Falla |
|-------|------------|-----------------|
| `fi` | Formato YYYY-MM-DD | Usar fecha actual |
| `ff` | Formato YYYY-MM-DD | Usar fecha actual |
| `udn` | Entero > 0 | Usar UDN por defecto |

### 3. Invariantes del Sistema

```
INVARIANTE 1: Total Venta >= 0
INVARIANTE 2: Total Pagado >= 0
INVARIANTE 3: Diferencia puede ser positiva, negativa o cero
INVARIANTE 4: Conceptos de efectivo son dinámicos (no hardcodeados)
INVARIANTE 5: Monedas extranjeras son dinámicas (no hardcodeadas)
```

---

## ⚠️ Manejo de Errores

### Errores del Backend

| Código | Descripción | Respuesta |
|--------|-------------|-----------|
| 200 | Éxito | `{ status: 200, message: "...", data: {...} }` |
| 500 | Error de servidor | `{ status: 500, message: "Error al obtener información" }` |

### Errores del Frontend

| Escenario | Manejo |
|-----------|--------|
| Sin datos | Mostrar cards con valores en $0.00 |
| Error AJAX | Mostrar mensaje de error con botón "Reintentar" |
| UDN no seleccionada | Usar UDN por defecto (5) |

### Validación de Resultados SQL

```php
// Patrón obligatorio para consultas
$result = $this->_Read($query, $array);

if (is_array($result) && !empty($result)) {
    // Procesar datos
} else {
    return 0; // o array vacío según el caso
}
```

---

## 🧪 Estrategia de Testing

### Casos de Prueba Unitarios

| ID | Caso | Entrada | Resultado Esperado |
|----|------|---------|-------------------|
| T01 | Día con ventas | UDN=5, fecha=hoy | Cards con datos > 0 |
| T02 | Día sin ventas | UDN=5, fecha=futuro | Cards con $0.00 |
| T03 | Diferencia positiva | Ventas > Pagos | Card verde |
| T04 | Diferencia negativa | Ventas < Pagos | Card roja |
| T05 | Diferencia cero | Ventas = Pagos | Card verde con $0.00 |

### Casos de Prueba de Integración

| ID | Flujo | Pasos | Resultado |
|----|-------|-------|-----------|
| I01 | Consulta completa | 1. Seleccionar UDN 2. Seleccionar fecha 3. Ver datos | Datos correctos |
| I02 | Cambio de filtros | 1. Cambiar UDN 2. Verificar actualización | Datos actualizados |
| I03 | Múltiples conceptos | 1. Consultar día con varios conceptos | Todos los conceptos visibles |

---

## 🎨 Especificaciones de UI

### Layout Principal

```
┌─────────────────────────────────────────────────────────────┐
│ FilterBar                                                   │
│ [UDN ▼] [Fecha: ___________]                               │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  ┌─────────────────────┐  ┌─────────────────────┐          │
│  │ 📊 Ventas del día   │  │ 💳 Formas de pago   │          │
│  │                     │  │                     │          │
│  │ Ventas:      $X,XXX │  │ Efectivo:    $X,XXX │          │
│  │ Descuentos:  $X,XXX │  │ Propina:     $X,XXX │          │
│  │ Impuestos:   $X,XXX │  │ Vales:       $X,XXX │          │
│  │ ─────────────────── │  │ Dólar:       $X,XXX │          │
│  │ Total:       $X,XXX │  │ Bancos:      $X,XXX │          │
│  └─────────────────────┘  │ Créditos:    $X,XXX │          │
│                           │ ─────────────────── │          │
│  ┌─────────────────────┐  │ Total:       $X,XXX │          │
│  │ 📈 Diferencia       │  └─────────────────────┘          │
│  │      $X,XXX         │                                   │
│  └─────────────────────┘                                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

### Componentes CoffeeSoft Utilizados

| Componente | Uso |
|------------|-----|
| `primaryLayout()` | Estructura base del módulo |
| `createfilterBar()` | Barra de filtros (UDN, fecha) |
| `summaryCard()` | Cards de ventas y pagos |
| `createLayout()` | Layout personalizado |

### Clases TailwindCSS

```css
/* Card container */
.card-container: "bg-white border rounded-lg p-6"

/* Diferencia positiva */
.diferencia-positiva: "text-green-800"

/* Diferencia negativa */
.diferencia-negativa: "text-red-800"

/* Loading state */
.loading: "animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600"
```

---

## 📝 Notas de Implementación

### Reglas de Código CoffeeSoft

1. **Variables POST:** Asignación directa sin `??` ni `isset()`
   ```php
   $fi  = $_POST['fi'];
   $ff  = $_POST['ff'];
   $udn = $_POST['udn'];
   ```

2. **Consultas SQL:** Usar exclusivamente `_Read()`
   ```php
   $result = $this->_Read($query, $array);
   ```

3. **Nomenclatura CTRL vs MDL:**
   - CTRL: `showSale()` (no `getSale()`)
   - MDL: `getVentas()`, `getDescuentos()`, etc.

4. **Validación de resultados:**
   ```php
   if (is_array($result) && !empty($result)) {
       // procesar
   }
   ```

### Dependencias

- jQuery 3.x
- TailwindCSS 2.x
- CoffeeSoft Framework (coffeSoft.js, plugins.js)
- PHP 7.4+
- MySQL 5.7+

---

**Fin del Documento de Diseño**
