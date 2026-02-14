# Tasks - Dashboard de Ventas KPI

## Task 1: Validación y Corrección de Cálculos de KPIs

### Description
Revisar y validar que los cálculos de los 4 KPIs principales (Venta del día, Venta del mes, Clientes, Cheque Promedio) sean correctos y consistentes.

### Requirements Addressed
- REQ-1: Dashboard Principal con KPIs

### Acceptance Criteria
- [ ] Verificar que `getVentasDelDia()` retorna datos del día anterior correctamente
- [ ] Verificar que `ingresosMensuales()` calcula el total del mes actual
- [ ] Verificar que el cheque promedio se calcula como Total/Clientes
- [ ] Verificar que la tendencia (up/down) se calcula correctamente vs año anterior
- [ ] Verificar formato de moneda MXN en todos los valores

### Files to Modify
- `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php` - Método `apiDashBoard()`
- `kpi/marketing/ventas/mdl/mdl-dashboard.php` - Métodos de consulta

---

## Task 2: Implementar Manejo de Datos Vacíos

### Description
Agregar manejo adecuado cuando no hay datos para el período seleccionado, mostrando mensajes informativos en lugar de errores.

### Requirements Addressed
- REQ-1: Dashboard Principal con KPIs (AC5)
- REQ-12: Rendimiento y Carga de Datos (AC4)

### Acceptance Criteria
- [ ] Mostrar "$0.00" cuando no hay ventas en el período
- [ ] Mostrar mensaje "Sin datos del año anterior" cuando no hay datos de comparación
- [ ] Mostrar mensaje "No hay información disponible" en rankings vacíos
- [ ] Evitar errores de división por cero en cheque promedio

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - Métodos de renderizado
- `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php` - Validaciones

---

## Task 3: Optimizar Adaptación por Tipo de UDN

### Description
Asegurar que las métricas y categorías se adapten correctamente según el tipo de UDN (Hotel, Panadería, Restaurantes).

### Requirements Addressed
- REQ-3: Tabla Comparativa de Promedios Diarios
- REQ-10: Adaptación por Tipo de UDN

### Acceptance Criteria
- [ ] Hotel (id=1): Mostrar Hospedaje, AyB, Diversos, % Ocupación, Tarifa Efectiva
- [ ] Panadería (id=6): Mostrar categorías de pan (Fogaza, Abarrotes, Bizcocho, etc.)
- [ ] Restaurantes (id=2,3,4,5): Mostrar Alimentos, Bebidas, Guarniciones
- [ ] Verificar que `handleCategoryChange()` filtra correctamente por UDN
- [ ] Verificar que los gráficos se adaptan a las categorías disponibles

### Files to Modify
- `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php` - Lógica de adaptación
- `kpi/marketing/ventas/src/js/dashboard.js` - `handleCategoryChange()`

---

## Task 4: Mejorar Gráfico Comparativo de Cheque Promedio

### Description
Optimizar el gráfico de barras comparativo para mostrar correctamente los datos de cheque promedio por categoría.

### Requirements Addressed
- REQ-4: Gráfico Comparativo de Cheque Promedio

### Acceptance Criteria
- [ ] Mostrar barras agrupadas: año actual vs año anterior
- [ ] Usar colores corporativos: Azul (#103B60) y Verde (#8CC63F)
- [ ] Mostrar valores formateados como moneda en etiquetas
- [ ] Mostrar leyenda con años comparados
- [ ] Responsive en diferentes tamaños de pantalla

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - `barChart()`, `renderComparativeSales()`

---

## Task 5: Implementar Gráfico Lineal de Tendencias

### Description
Mejorar el gráfico de líneas para mostrar tendencias de ventas diarias con tooltips informativos.

### Requirements Addressed
- REQ-5: Gráfico Lineal de Tendencia de Ventas

### Acceptance Criteria
- [ ] Mostrar dos líneas: período actual y período base
- [ ] Etiquetas de días en eje X (formato: día del mes)
- [ ] Tooltips con fecha completa y valor al hacer hover
- [ ] Filtro por categoría de venta funcional
- [ ] Leyenda clara con identificación de períodos

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - `linearChart()`, `comparativaByCategory()`
- `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php` - `comparativaByCategory()`

---

## Task 6: Optimizar Gráfico de Ventas por Día de Semana

### Description
Mejorar el gráfico de barras que muestra el promedio de ventas por día de la semana.

### Requirements Addressed
- REQ-6: Gráfico de Ventas por Día de la Semana

### Acceptance Criteria
- [ ] Calcular promedio dividiendo total entre número de ocurrencias
- [ ] Mostrar 7 días en orden: Lunes a Domingo
- [ ] Comparar año actual vs año anterior en barras agrupadas
- [ ] Formatear valores como moneda mexicana
- [ ] Actualizar al cambiar filtros

### Files to Modify
- `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php` - `apiIngresosComparativoSemana()`
- `kpi/marketing/ventas/mdl/mdl-dashboard.php` - `getIngresosDayOfWeekByRange()`

---

## Task 7: Mejorar Rankings de Días

### Description
Optimizar los componentes de ranking para mostrar información más completa y visualmente atractiva.

### Requirements Addressed
- REQ-7: Ranking de Mejores Días de la Semana
- REQ-8: Ranking de Cheque Promedio Semanal

### Acceptance Criteria
- [ ] Ordenar de mayor a menor por promedio/cheque
- [ ] Mostrar: posición, nombre del día, promedio, ocurrencias, clientes
- [ ] Resaltar mejor día con indicador visual (⭐ o 💎)
- [ ] Usar colores distintivos por posición
- [ ] Actualizar al cambiar filtros de período

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - `topDiasSemana()`, `topChequePromedioSemanal()`
- `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php` - `apiTopDiasSemanaPromedioRango()`

---

## Task 8: Implementar Navegación por Tabs

### Description
Asegurar que la navegación por tabs funcione correctamente y muestre/oculte los componentes apropiados.

### Requirements Addressed
- REQ-9: Navegación por Tabs del Módulo

### Acceptance Criteria
- [ ] Tab "Dashboard" muestra KPIs y gráficos principales
- [ ] Tab "Gráficas de venta" muestra gráficos de ingresos
- [ ] Tab "Gráficas de Cheque Promedio" muestra gráficos de cheque
- [ ] Resaltar visualmente el tab activo
- [ ] Mantener estado de filtros al cambiar de tab

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - `showGraphicsCategory()`

---

## Task 9: Implementar Exportación a Excel

### Description
Agregar funcionalidad para exportar los datos del dashboard a formato Excel.

### Requirements Addressed
- REQ-11: Exportación de Datos

### Acceptance Criteria
- [ ] Botón "Exportar a Excel" visible en vistas de tabla
- [ ] Generar archivo Excel con datos visibles
- [ ] Incluir encabezados, datos formateados, fecha de generación
- [ ] Nombrar archivo: "Dashboard-ventas-[fecha].xlsx"

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - Nuevo método `exportToExcel()`
- `kpi/marketing/ventas/ctrl/ctrl-ingresos-dashboard.php` - Endpoint de exportación (opcional)

---

## Task 10: Optimizar Rendimiento de Carga

### Description
Mejorar el tiempo de carga del dashboard y agregar indicadores de progreso.

### Requirements Addressed
- REQ-12: Rendimiento y Carga de Datos

### Acceptance Criteria
- [ ] Mostrar indicador de carga mientras se obtienen datos
- [ ] Cargar datos en menos de 3 segundos para rangos de 31 días
- [ ] Cachear datos de filtros (UDN, categorías)
- [ ] Mostrar mensaje de error descriptivo en caso de fallo
- [ ] Permitir reintentar carga sin recargar página

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - Agregar spinners y manejo de errores
- `kpi/marketing/ventas/mdl/mdl-dashboard.php` - Optimizar consultas SQL

---

## Task 11: Agregar Indicadores de Carga (Spinners)

### Description
Implementar indicadores visuales de carga para mejorar la experiencia de usuario.

### Requirements Addressed
- REQ-12: Rendimiento y Carga de Datos (AC1)

### Acceptance Criteria
- [ ] Mostrar spinner en cada componente mientras carga
- [ ] Ocultar spinner cuando los datos están listos
- [ ] Spinner consistente con el diseño del sistema

### Files to Modify
- `kpi/marketing/ventas/src/js/dashboard.js` - Todos los métodos de renderizado

---

## Task 12: Documentar API del Dashboard

### Description
Crear documentación técnica de los endpoints y métodos del dashboard.

### Requirements Addressed
- Mantenibilidad del código

### Acceptance Criteria
- [ ] Documentar parámetros de entrada de cada método del controlador
- [ ] Documentar estructura de respuesta de cada endpoint
- [ ] Documentar dependencias y configuración necesaria

### Files to Create
- `kpi/marketing/ventas/README.md` - Documentación del módulo

---

## Summary

| Task | Priority | Complexity | Status |
|------|----------|------------|--------|
| Task 1: Validación de KPIs | Alta | Media | Pending |
| Task 2: Manejo de Datos Vacíos | Alta | Baja | Pending |
| Task 3: Adaptación por UDN | Alta | Media | Pending |
| Task 4: Gráfico Cheque Promedio | Media | Baja | Pending |
| Task 5: Gráfico Tendencias | Media | Media | Pending |
| Task 6: Ventas por Día Semana | Media | Baja | Pending |
| Task 7: Rankings de Días | Media | Baja | Pending |
| Task 8: Navegación Tabs | Baja | Baja | Pending |
| Task 9: Exportación Excel | Baja | Media | Pending |
| Task 10: Optimizar Rendimiento | Media | Alta | Pending |
| Task 11: Indicadores de Carga | Baja | Baja | Pending |
| Task 12: Documentación | Baja | Baja | Pending |

## Execution Order Recommendation

1. **Fase 1 - Correcciones Críticas** (Tasks 1, 2, 3)
   - Validar cálculos existentes
   - Manejar casos de datos vacíos
   - Asegurar adaptación por UDN

2. **Fase 2 - Mejoras Visuales** (Tasks 4, 5, 6, 7)
   - Optimizar gráficos existentes
   - Mejorar rankings

3. **Fase 3 - Funcionalidades Adicionales** (Tasks 8, 9)
   - Navegación por tabs
   - Exportación a Excel

4. **Fase 4 - Optimización** (Tasks 10, 11, 12)
   - Rendimiento
   - Indicadores de carga
   - Documentación
