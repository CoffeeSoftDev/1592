# Design Document - Dashboard de Ventas KPI

## Overview

El Dashboard de Ventas KPI es un módulo de análisis y visualización de métricas de ventas que permite a gerentes y analistas comparar el rendimiento del negocio entre dos períodos (año actual vs año anterior). El sistema se adapta dinámicamente según el tipo de Unidad de Negocio (Hotel, Panadería, Restaurantes) mostrando métricas relevantes para cada tipo.

## Architecture

### Patrón MVC con CoffeeSoft Framework

```
┌─────────────────────────────────────────────────────────────────┐
│                        FRONTEND (JS)                            │
│  ┌─────────────────┐    ┌──────────────────────────────────┐   │
│  │    Dashboard    │    │       FinanceDashboard           │   │
│  │   (Base Class)  │◄───│    (extends Dashboard)           │   │
│  │                 │    │                                  │   │
│  │ - infoCard()    │    │ - renderDashboard()              │   │
│  │ - linearChart() │    │ - showCards()                    │   │
│  │ - barChart()    │    │ - comparativaByCategory()        │   │
│  │ - topDiasMes()  │    │ - ventasPorDiaSemana()           │   │
│  │ - topDiasSemana │    │ - renderChequePromedioCategory() │   │
│  └─────────────────┘    └──────────────────────────────────┘   │
│                                    │                            │
│                                    ▼                            │
│                            useFetch(api)                        │
└────────────────────────────────────┼────────────────────────────┘
                                     │
                                     ▼
┌─────────────────────────────────────────────────────────────────┐
│                    CONTROLLER (PHP)                             │
│                  ctrl-ingresos-dashboard.php                    │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Métodos Principales:                                     │   │
│  │ - apiPromediosDiarios()    → Dashboard principal         │   │
│  │ - apiDashBoard()           → KPIs y métricas             │   │
│  │ - comparativaChequePromedio() → Gráfico comparativo      │   │
│  │ - comparativaByCategory()  → Comparativa por categoría   │   │
│  │ - getDailyCheck()          → Cheque promedio diario      │   │
│  │ - getClientesPorSemana()   → Clientes por día semana     │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                    │                            │
│                                    ▼                            │
└────────────────────────────────────┼────────────────────────────┘
                                     │
                                     ▼
┌─────────────────────────────────────────────────────────────────┐
│                      MODEL (PHP)                                │
│                    mdl-dashboard.php                            │
│                                                                 │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Métodos de Consulta:                                     │   │
│  │ - lsUDN()                  → Lista de unidades negocio   │   │
│  │ - lsClasificacion()        → Categorías por UDN          │   │
│  │ - getVentasDelDia()        → Ventas del día anterior     │   │
│  │ - ingresosMensuales()      → Ingresos del mes            │   │
│  │ - ingresosPorRango()       → Ingresos por rango fechas   │   │
│  │ - getIngresosDayOfWeekByRange() → Ventas por día semana  │   │
│  │ - getComparativaChequePromedioPorRango()                 │   │
│  └─────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
                                     │
                                     ▼
┌─────────────────────────────────────────────────────────────────┐
│                      DATABASE                                   │
│                 rfwsmqex_gvsl_finanzas                          │
│                                                                 │
│  ┌──────────────┐  ┌─────────────────────────┐  ┌───────────┐  │
│  │  soft_folio  │  │ soft_restaurant_ventas  │  │    udn    │  │
│  │              │  │                         │  │           │  │
│  │ - id         │  │ - id                    │  │ - idUDN   │  │
│  │ - udn        │  │ - folio_id              │  │ - UDN     │  │
│  │ - fecha      │  │ - clasificacion         │  │ - Stado   │  │
│  │ - clientes   │  │ - total                 │  └───────────┘  │
│  │ - total      │  │ - cantidad              │                 │
│  └──────────────┘  └─────────────────────────┘                 │
│                                                                 │
│  ┌───────────────┐                                             │
│  │ clasificacion │                                             │
│  │               │                                             │
│  │ - id          │                                             │
│  │ - clasificacion│                                            │
│  │ - udn         │                                             │
│  └───────────────┘                                             │
└─────────────────────────────────────────────────────────────────┘
```

## Components / Interfaces

### Frontend Classes

#### Dashboard (Base Class)
Clase base que proporciona componentes visuales reutilizables.

| Método | Descripción | Parámetros |
|--------|-------------|------------|
| `dashboardComponent(options)` | Layout principal del dashboard | parent, id, title, subtitle, json[] |
| `infoCard(options)` | Tarjetas KPI con valores y tendencias | parent, theme, json[] |
| `linearChart(options)` | Gráfico de líneas para tendencias | parent, id, title, data |
| `barChart(options)` | Gráfico de barras comparativo | parent, labels, dataA, dataB, yearA, yearB |
| `topDiasMes(options)` | Ranking de mejores días del mes | parent, title, data[] |
| `topDiasSemana(options)` | Ranking por día de la semana | parent, title, data[] |
| `handleCategoryChange(idudn)` | Actualiza categorías según UDN | idudn |
| `renderSelectCategory(options)` | Renderiza select de categorías | parent, udn, data |

#### FinanceDashboard (extends Dashboard)
Clase principal que implementa la lógica del dashboard de ventas.

| Método | Descripción | API Call |
|--------|-------------|----------|
| `renderDashboard()` | Renderiza todo el dashboard | `apiPromediosDiarios` |
| `showCards(data)` | Muestra KPI cards | - |
| `renderComparativeSales(options)` | Gráfico comparativo de ventas | - |
| `comparativaByCategory()` | Comparativa por categoría | `comparativaByCategory` |
| `ventasPorDiaSemana(data)` | Gráfico ventas por día semana | - |
| `renderRankingTop(data)` | Ranking de días | - |
| `renderChequePromedioCategory()` | Cheque promedio por categoría | `getPromediosDiariosRange` |
| `renderDailyAverageCheck()` | Cheque promedio diario | `getDailyCheck` |
| `renderClientesPorSemana()` | Clientes por día semana | `getClientesPorSemana` |
| `topChequePromedioSemanal(options)` | Ranking cheque promedio | - |

### Controller Methods (ctrl-ingresos-dashboard.php)

| Método | Descripción | Parámetros POST |
|--------|-------------|-----------------|
| `apiPromediosDiarios()` | Datos principales del dashboard | udn, fi, ff, fiBase, ffBase |
| `apiDashBoard()` | KPIs y métricas generales | udn, fi, ff, fiBase, ffBase |
| `comparativaChequePromedio()` | Comparativa cheque promedio | udn, fi, ff, fiBase, ffBase |
| `comparativaByCategory()` | Comparativa por categoría | udn, category, fi, ff, fiBase, ffBase |
| `apiIngresosComparativoSemana()` | Ingresos por día semana | udn, fi, ff, fiBase, ffBase |
| `apiLinearPromediosDiarioRango()` | Tendencia lineal | udn, fi, ff, fiBase, ffBase |
| `apiTopDiasSemanaPromedioRango()` | Top días por promedio | udn, fi, ff, fiBase, ffBase |
| `apiTopChequePromedioSemanalRango()` | Top cheque promedio | udn, fi, ff, fiBase, ffBase |
| `getDailyCheck()` | Cheque promedio diario | udn, category, fi, ff, fiBase, ffBase |
| `getClientesPorSemana()` | Clientes por día semana | udn, fi, ff, fiBase, ffBase |
| `getPromediosDiariosRange()` | Promedios por rango | udn, concepto, mes, anio, anioBase, rango |

### Model Methods (mdl-dashboard.php)

| Método | Descripción | Retorno |
|--------|-------------|---------|
| `lsUDN()` | Lista unidades de negocio activas | Array [id, valor] |
| `salesUDN()` | UDN con ventas | Array [id, valor] |
| `lsClasificacion()` | Categorías por UDN | Array [id, valor, udn] |
| `getVentasDelDia($array)` | Ventas del día anterior | Array [total, clientes] |
| `ingresosMensuales($array)` | Ingresos del mes | Array [total, clientes] |
| `ingresosPorRango($array)` | Ingresos por rango | Array [total, clientes] |
| `getIngresosDayOfWeekByRange($array)` | Ventas por día semana | Array [dia, total, clientes] |
| `getComparativaChequePromedioPorRango($array)` | Cheque promedio comparativo | Array [concepto, actual, anterior] |
| `getsoftVentas($array)` | Ventas por categoría | Array [clasificacion, total] |

## Data Models

### Tabla: soft_folio
Almacena los folios de ventas diarias por UDN.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único |
| udn | INT | FK a tabla udn |
| fecha | DATE | Fecha del folio |
| clientes | INT | Número de clientes |
| total | DECIMAL | Total de ventas |
| hospedaje | DECIMAL | Ventas hospedaje (Hotel) |
| ayb | DECIMAL | Ventas A&B |
| diversos | DECIMAL | Ventas diversos |
| habitaciones | INT | Habitaciones ocupadas (Hotel) |

### Tabla: soft_restaurant_ventas
Detalle de ventas por categoría.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único |
| folio_id | INT | FK a soft_folio |
| clasificacion | VARCHAR | Nombre de categoría |
| total | DECIMAL | Total de la categoría |
| cantidad | INT | Cantidad vendida |

### Tabla: udn
Unidades de negocio.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| idUDN | INT | Identificador único |
| UDN | VARCHAR | Nombre de la unidad |
| Stado | INT | Estado (1=activo) |

### Tabla: clasificacion
Categorías de productos por UDN.

| Campo | Tipo | Descripción |
|-------|------|-------------|
| id | INT | Identificador único |
| clasificacion | VARCHAR | Nombre de categoría |
| udn | INT | FK a tabla udn |

## Correctness Properties

### CP1: Consistencia de Datos Comparativos
- **Propiedad**: Los datos del período actual y período base deben calcularse con la misma lógica
- **Verificación**: El cálculo de fechas base usa `moment(fi).year(yearBase)` consistentemente
- **Impacto**: REQ-1, REQ-3, REQ-4, REQ-5, REQ-6

### CP2: Adaptación por Tipo de UDN
- **Propiedad**: Las métricas mostradas deben corresponder al tipo de UDN seleccionado
- **Verificación**: El controlador valida `$udn` y retorna categorías específicas
- **Impacto**: REQ-3, REQ-10

### CP3: Cálculo de Cheque Promedio
- **Propiedad**: Cheque Promedio = Total Ventas / Número de Clientes
- **Verificación**: División protegida contra división por cero
- **Impacto**: REQ-1, REQ-4, REQ-8

### CP4: Formato de Moneda
- **Propiedad**: Todos los valores monetarios deben mostrarse en formato MXN ($X,XXX.XX)
- **Verificación**: Uso consistente de `formatPrice()` en frontend
- **Impacto**: REQ-1, REQ-3, REQ-4, REQ-5, REQ-6

### CP5: Cálculo de Tendencias
- **Propiedad**: La tendencia (up/down/neutral) debe reflejar correctamente la comparación
- **Verificación**: `tendencia = actual > anterior ? 'up' : (actual < anterior ? 'down' : 'neutral')`
- **Impacto**: REQ-1

### CP6: Integridad de Rangos de Fecha
- **Propiedad**: El período base debe ser exactamente el mismo rango pero del año seleccionado
- **Verificación**: `fiBase = moment(fi).year(yearBase)`, `ffBase = moment(ff).year(yearBase)`
- **Impacto**: REQ-2, REQ-3, REQ-4, REQ-5, REQ-6

### CP7: Ordenamiento de Rankings
- **Propiedad**: Los rankings deben estar ordenados de mayor a menor
- **Verificación**: `ORDER BY promedio DESC` en consultas SQL
- **Impacto**: REQ-7, REQ-8

## Error Handling

### Frontend
- **Sin datos**: Mostrar mensaje "No hay información disponible" con icono
- **Error de conexión**: Mostrar alerta con opción de reintentar
- **Datos inválidos**: Mostrar $0.00 con mensaje descriptivo

### Backend
- **UDN no válido**: Retornar array vacío con estructura esperada
- **Rango de fechas inválido**: Usar fechas por defecto (mes actual)
- **División por cero**: Retornar 0 para cheque promedio cuando clientes = 0

### Validaciones
```php
// Ejemplo de validación en controlador
$clientes = $data['clientes'] > 0 ? $data['clientes'] : 1;
$chequePromedio = $data['total'] / $clientes;
```

## Testing Strategy

### Unit Tests (Backend)
1. **Cálculo de métricas**: Verificar fórmulas de cheque promedio, totales, porcentajes
2. **Filtrado por UDN**: Verificar que las categorías correspondan al tipo de UDN
3. **Rangos de fecha**: Verificar cálculo correcto de período base

### Integration Tests
1. **API Endpoints**: Verificar respuesta correcta de cada método del controlador
2. **Flujo de datos**: Verificar que los datos fluyan correctamente desde BD hasta frontend

### UI Tests
1. **Renderizado de componentes**: Verificar que todos los gráficos se rendericen
2. **Interacción de filtros**: Verificar actualización al cambiar UDN, fechas, año
3. **Responsive**: Verificar visualización en diferentes tamaños de pantalla

### Test Cases Prioritarios

| ID | Descripción | Tipo | Prioridad |
|----|-------------|------|-----------|
| TC1 | KPIs muestran valores correctos para período actual | Unit | Alta |
| TC2 | Comparativa año vs año calcula diferencias correctamente | Unit | Alta |
| TC3 | Cambio de UDN actualiza categorías disponibles | Integration | Alta |
| TC4 | Gráficos se renderizan sin errores con datos vacíos | UI | Media |
| TC5 | DateRangePicker actualiza todos los componentes | Integration | Media |
| TC6 | Exportación a Excel genera archivo válido | Integration | Baja |

## Performance Considerations

### Optimizaciones Implementadas
1. **Caché de filtros**: UDN y categorías se cargan una vez al inicio
2. **Consultas optimizadas**: Uso de índices en campos fecha y udn
3. **Lazy loading**: Gráficos se renderizan solo cuando el tab está activo

### Métricas Objetivo
- Tiempo de carga inicial: < 2 segundos
- Actualización de filtros: < 1 segundo
- Renderizado de gráficos: < 500ms

## Dependencies

### Frontend
- jQuery 3.x
- Chart.js 3.x
- Moment.js 2.x
- TailwindCSS 2.x
- DateRangePicker
- CoffeeSoft Framework (Templates, Components)

### Backend
- PHP 7.4+
- MySQL 5.7+
- Clase CRUD (conf/_CRUD.php)
- Clase Utileria (conf/_Utileria.php)
