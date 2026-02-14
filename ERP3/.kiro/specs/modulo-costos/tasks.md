# Implementation Plan - Módulo de Costos

## Completed Tasks

- [x] 1. Crear estructura de archivos del módulo
  - Crear archivo `finanzas/consulta/js/costos.js` con clase Costs
  - Crear archivo `finanzas/consulta/ctrl/ctrl-costos.php` con controlador
  - Crear archivo `finanzas/consulta/mdl/mdl-costos.php` con modelo
  - _Requirements: 1.1, 1.2_

- [x] 2. Implementar interfaz de usuario
  - Crear layout con filterBar y container
  - Implementar botón de toggle para alternar vistas
  - Configurar scroll horizontal en la tabla
  - _Requirements: 1.1, 2.1, 2.2, 4.1_

- [x] 3. Implementar lógica de consulta de datos
  - Crear método `ls()` en controlador para obtener concentrado
  - Implementar método `listCosts()` en modelo para consultar BD
  - Generar columnas dinámicas por rango de fechas
  - Calcular totales por día y por categoría
  - _Requirements: 1.2, 1.4, 1.5, 5.1, 5.2, 5.3_

- [x] 4. Integrar módulo con sistema existente
  - Actualizar `app.js` para incluir instancia de Costs
  - Agregar tab "Costos" en tabLayout
  - Incluir script `costos.js` en `compras.php`
  - _Requirements: 1.1, 4.1_

- [x] 5. Aplicar estilos y formato
  - Usar tema "light" de CoffeeSoft
  - Aplicar formato de moneda con `formatPrice()`
  - Aplicar formato de fecha con `formatSpanishDate()`
  - Configurar colores para totales (amarillo, verde, azul)
  - _Requirements: 3.1, 3.2, 3.3, 3.4_

- [x] 6. Agregar funciones auxiliares
  - Implementar función `formatPrice()` en controlador
  - Verificar disponibilidad de `formatSpanishDate()` desde coffeSoft.php
  - _Requirements: 3.2, 3.4_

## Pending Tasks

- [ ] 7. Implementar filas de totales específicos
  - Agregar fila "Total en compras (costo directo)" con datos reales
  - Agregar fila "Total en salidas de almacén" con datos reales
  - Conectar con tablas `purchase` y `warehouse_output`
  - _Requirements: 5.1, 5.2_

- [ ] 8. Optimizar consultas de base de datos
  - Revisar índices en tablas `purchase` y `warehouse_output`
  - Optimizar query UNION ALL para mejor rendimiento
  - Implementar caché de resultados si es necesario
  - _Requirements: 5.1, 5.2, 5.3_

- [ ] 9. Agregar funcionalidad de detalle por día
  - Implementar método `viewCostDetail()` para mostrar desglose
  - Crear modal con información detallada por fecha
  - Mostrar origen de cada costo (compra o salida de almacén)
  - _Requirements: 1.4, 5.1, 5.2_

- [ ] 10. Implementar exportación a Excel
  - Agregar botón "Exportar a Excel" en filterBar
  - Generar archivo Excel con formato del concentrado
  - Incluir totales y subtotales en la exportación
  - _Requirements: 1.1_

- [ ] 11. Pruebas y validación
  - Verificar cálculos de totales con datos reales
  - Probar con diferentes rangos de fechas
  - Validar comportamiento con múltiples UDN
  - Verificar scroll horizontal en diferentes resoluciones
  - _Requirements: 1.2, 1.3, 1.5, 2.1, 2.2, 2.3_

---

**Estado del Proyecto:** En Desarrollo  
**Progreso:** 55% completado (6/11 tareas)  
**Última actualización:** 2025-01-14

---

## 📋 Resumen del Plan de Implementación

Este plan de implementación cubre todas las tareas necesarias para desarrollar el módulo de Costos, desde la estructura base hasta las funcionalidades avanzadas de exportación y optimización.

### ✅ Tareas Completadas (6)
- Estructura de archivos MVC
- Interfaz de usuario con scroll horizontal
- Lógica de consulta y agrupación de datos
- Integración con sistema existente
- Estilos y formato corporativo
- Funciones auxiliares (formatPrice, formatSpanishDate)

### 🔄 Tareas Pendientes (5)
- Implementar totales específicos por tipo de costo
- Optimizar consultas de base de datos
- Agregar funcionalidad de detalle por día
- Implementar exportación a Excel
- Pruebas y validación completa

**Nota:** Todas las tareas están enfocadas en actividades de código y son accionables por el equipo de desarrollo.
