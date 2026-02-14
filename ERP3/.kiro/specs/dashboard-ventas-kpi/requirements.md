# Requirements Document - Dashboard de Ventas KPI

## Introduction

Este documento define los requerimientos para el módulo de Dashboard de Ventas KPI del sistema ERP. El módulo proporciona visualización de métricas de ventas, análisis comparativos año contra año, gráficos de tendencias y rankings de rendimiento por día de la semana, permitiendo a los usuarios tomar decisiones basadas en datos históricos y actuales.

## Glossary

- **UDN**: Unidad de Negocio (Hotel, Restaurante, Panadería, etc.)
- **Cheque Promedio**: Total de ventas dividido entre el número de clientes
- **Tarifa Efectiva**: Ingreso de hospedaje dividido entre habitaciones ocupadas
- **Período Actual**: Rango de fechas seleccionado por el usuario
- **Período Base**: Mismo rango de fechas del año anterior para comparación
- **KPI**: Key Performance Indicator (Indicador Clave de Rendimiento)
- **AyB**: Alimentos y Bebidas
- **Soft-Restaurant**: Sistema externo de punto de venta

## Requirements

### Requirement 1: Dashboard Principal con KPIs

**User Story:** As a gerente o analista, I want to ver los KPIs principales de ventas en tarjetas visuales, so that I can conocer rápidamente el estado del negocio.

#### Acceptance Criteria

1. WHEN el usuario accede al Dashboard, THE System SHALL mostrar 4 KPI cards: Venta del día de ayer, Venta del Mes, Clientes, y Cheque Promedio
2. THE System SHALL mostrar la variación porcentual comparando el período actual vs el período base (año anterior)
3. THE System SHALL indicar visualmente la tendencia (up/down/neutral) con colores: verde para positivo, rojo para negativo
4. THE System SHALL formatear los valores monetarios con formato de moneda mexicana ($X,XXX.XX)
5. WHEN no hay datos para el período, THE System SHALL mostrar $0.00 con mensaje "Sin datos del año anterior"

### Requirement 2: Filtros de Consulta por Período y UDN

**User Story:** As a usuario, I want to filtrar los datos por rango de fechas y unidad de negocio, so that I can analizar períodos específicos.

#### Acceptance Criteria

1. THE System SHALL mostrar selector de UDN con las unidades de negocio activas
2. THE System SHALL mostrar selector de rango de fechas con DateRangePicker
3. WHEN el usuario cambia el rango de fechas, THE System SHALL calcular automáticamente el período base (mismo rango del año anterior)
4. WHEN el usuario cambia cualquier filtro, THE System SHALL actualizar todos los componentes del dashboard
5. THE System SHALL mantener los filtros seleccionados durante la sesión

### Requirement 3: Tabla Comparativa de Promedios Diarios

**User Story:** As a analista, I want to ver una tabla comparativa de métricas por concepto, so that I can analizar el rendimiento detallado.

#### Acceptance Criteria

1. THE System SHALL mostrar tabla con columnas: Concepto, Período Anterior, Período Actual, Diferencia
2. FOR UDN Hotel (id=1), THE System SHALL mostrar conceptos: Suma de ingresos, Hospedaje, AyB, Diversos, Habitaciones, % Ocupación, Tarifa efectiva, Cheque Promedio por categoría
3. FOR UDN Panadería (id=6), THE System SHALL mostrar conceptos: Clientes, Ventas por categoría (Fogaza, Abarrotes, Bizcocho, Francés, Bocadillos, Pastelería Normal/Premium, Refrescos, Velas), Cheque Promedio
4. FOR otras UDN (Restaurantes), THE System SHALL mostrar conceptos: Clientes, Ventas AyB, Alimentos, Bebidas, Cheque Promedio por categoría
5. THE System SHALL calcular la diferencia como: Período Actual - Período Anterior
6. THE System SHALL formatear valores monetarios y porcentajes según el tipo de concepto

### Requirement 4: Gráfico Comparativo de Cheque Promedio

**User Story:** As a gerente, I want to ver un gráfico de barras comparando el cheque promedio por categoría, so that I can identificar tendencias de consumo.

#### Acceptance Criteria

1. THE System SHALL mostrar gráfico de barras agrupadas comparando año actual vs año anterior
2. THE System SHALL mostrar categorías: AyB, Alimentos, Bebidas (según la UDN)
3. THE System SHALL usar colores distintivos: Azul corporativo (#103B60) para año actual, Verde (#8CC63F) para año anterior
4. THE System SHALL mostrar valores formateados como moneda en las etiquetas
5. THE System SHALL mostrar leyenda con los años comparados

### Requirement 5: Gráfico Lineal de Tendencia de Ventas

**User Story:** As a analista, I want to ver un gráfico de líneas con la tendencia de ventas del mes, so that I can identificar patrones diarios.

#### Acceptance Criteria

1. THE System SHALL mostrar gráfico de líneas con ventas diarias del período seleccionado
2. THE System SHALL mostrar dos líneas: período actual y período base (año anterior)
3. THE System SHALL mostrar etiquetas de días en el eje X (formato: día del mes)
4. THE System SHALL mostrar tooltips con fecha completa y valor al hacer hover
5. THE System SHALL permitir filtrar por categoría de venta (Todas, Hospedaje, AyB, Diversos, etc.)

### Requirement 6: Gráfico de Ventas por Día de la Semana

**User Story:** As a gerente, I want to ver las ventas promedio por día de la semana, so that I can identificar los días de mayor y menor rendimiento.

#### Acceptance Criteria

1. THE System SHALL mostrar gráfico de barras con promedio de ventas por día de la semana
2. THE System SHALL calcular el promedio dividiendo el total de ventas entre el número de ocurrencias de cada día
3. THE System SHALL mostrar los 7 días de la semana en orden: Lunes a Domingo
4. THE System SHALL comparar año actual vs año anterior en barras agrupadas
5. THE System SHALL formatear valores como moneda mexicana

### Requirement 7: Ranking de Mejores Días de la Semana

**User Story:** As a analista, I want to ver un ranking de los días de la semana por promedio de ventas, so that I can identificar patrones de comportamiento.

#### Acceptance Criteria

1. THE System SHALL mostrar lista ordenada de días de la semana por promedio de ventas (mayor a menor)
2. THE System SHALL mostrar para cada día: posición, nombre del día, promedio de ventas, número de ocurrencias, total de clientes
3. THE System SHALL resaltar el mejor día con indicador visual (estrella o badge)
4. THE System SHALL usar colores distintivos para cada posición del ranking
5. THE System SHALL actualizar el ranking al cambiar los filtros de período

### Requirement 8: Ranking de Cheque Promedio Semanal

**User Story:** As a gerente, I want to ver el ranking de cheque promedio por día de la semana, so that I can identificar cuándo los clientes gastan más.

#### Acceptance Criteria

1. THE System SHALL mostrar lista ordenada de días por cheque promedio (mayor a menor)
2. THE System SHALL calcular cheque promedio como: Total ventas del día / Total clientes del día
3. THE System SHALL mostrar para cada día: posición, nombre, cheque promedio, número de clientes
4. THE System SHALL usar formato de moneda para los valores
5. THE System SHALL indicar visualmente el día con mejor cheque promedio

### Requirement 9: Navegación por Tabs del Módulo

**User Story:** As a usuario, I want to navegar entre diferentes vistas del dashboard, so that I can acceder a análisis específicos.

#### Acceptance Criteria

1. THE System SHALL mostrar tabs de navegación: Dashboard, Módulo Ventas, Comparativas Mensuales, Promedios Acumulados
2. WHEN el usuario hace clic en "Dashboard", THE System SHALL mostrar los KPIs y gráficos principales
3. WHEN el usuario hace clic en "Módulo Ventas", THE System SHALL mostrar la tabla de ventas diarias con filtros
4. WHEN el usuario hace clic en "Comparativas Mensuales", THE System SHALL mostrar comparativas mes a mes
5. WHEN el usuario hace clic en "Promedios Acumulados", THE System SHALL mostrar promedios acumulados del año
6. THE System SHALL resaltar visualmente el tab activo

### Requirement 10: Adaptación por Tipo de UDN

**User Story:** As a usuario, I want to que el dashboard se adapte según la unidad de negocio seleccionada, so that I can ver métricas relevantes para cada tipo de negocio.

#### Acceptance Criteria

1. FOR UDN Hotel (id=1), THE System SHALL mostrar métricas de: Hospedaje, AyB, Diversos, % Ocupación, Tarifa Efectiva
2. FOR UDN Panadería (id=6), THE System SHALL mostrar métricas de: Categorías de pan (Abarrotes, Bizcocho, Francés, Bocadillos, Pastelería, Refrescos, Velas)
3. FOR UDN Restaurantes (id=2,3,4,5), THE System SHALL mostrar métricas de: Alimentos, Bebidas, Guarniciones, Sales, Domicilio
4. THE System SHALL ajustar los gráficos y tablas según las categorías disponibles para cada UDN
5. THE System SHALL mantener consistencia visual independientemente de la UDN seleccionada

### Requirement 11: Exportación de Datos

**User Story:** As a analista, I want to exportar los datos del dashboard a Excel, so that I can realizar análisis adicionales.

#### Acceptance Criteria

1. THE System SHALL mostrar botón "Exportar a Excel" en las vistas de tabla
2. WHEN el usuario hace clic en exportar, THE System SHALL generar archivo Excel con los datos visibles
3. THE System SHALL incluir en el archivo: encabezados de columna, datos formateados, fecha de generación
4. THE System SHALL nombrar el archivo con formato: "Dashboard-ventas-[fecha].xlsx"

### Requirement 12: Rendimiento y Carga de Datos

**User Story:** As a usuario, I want to que el dashboard cargue rápidamente, so that I can consultar información sin demoras.

#### Acceptance Criteria

1. THE System SHALL mostrar indicador de carga mientras se obtienen los datos
2. THE System SHALL cargar los datos del dashboard en menos de 3 segundos para rangos de hasta 31 días
3. THE System SHALL cachear los datos de filtros (UDN, categorías) para evitar consultas repetidas
4. WHEN ocurre un error de conexión, THE System SHALL mostrar mensaje de error descriptivo
5. THE System SHALL permitir reintentar la carga de datos sin recargar la página

## Technical Notes

### Estructura de Archivos Existentes

- **Frontend**: `kpi/marketing/ventas/src/js/dashboard.js`
- **Controlador**: `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php`
- **Modelo**: `kpi/marketing/ventas/mdl/mdl-dashboard.php`
- **Vista**: `kpi/marketing/ventas/ventas.php`

### Tablas de Base de Datos Involucradas

- `soft_folio`: Folios de ventas diarias
- `soft_restaurant_ventas`: Detalle de ventas por categoría
- `udn`: Unidades de negocio
- `clasificacion`: Categorías de productos

### Dependencias

- CoffeeSoft Framework (Templates, Components)
- Chart.js para gráficos
- Moment.js para manejo de fechas
- TailwindCSS para estilos
