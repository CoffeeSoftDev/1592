# Implementation Plan: Filtro por Categoría en Calendario de Fogaza

## Overview

Implementación del filtro interactivo por categoría en el calendario de ventas de Fogaza. Todas las modificaciones se realizan en un único archivo: `kpi/marketing/ventas/src/js/calendario.js`.

## Tasks

- [x] 1. Agregar estado de categoría seleccionada
  - Agregar propiedad `_selectedCategory = null` en el constructor de `SalesCalendar`
  - _Requirements: 5.1, 5.2_

- [x] 2. Implementar método selectCategory()
  - [x] 2.1 Crear método `selectCategory(categoryKey)` que maneje la selección/deselección
    - Toggle de categoría (si es la misma, deseleccionar)
    - Actualizar `_selectedCategory`
    - Llamar a recálculo de extremos y re-render
    - _Requirements: 1.1, 1.3, 3.2_

- [x] 3. Modificar método getWeeklyExtremes()
  - [x] 3.1 Agregar parámetro opcional `categoryKey` al método existente
    - Si `categoryKey` está definido, usar `dia.ventas[categoryKey]` en lugar de `dia.total`
    - Mantener compatibilidad con llamadas existentes (sin parámetro = total general)
    - _Requirements: 4.1, 4.3_

- [x] 4. Implementar método getDayValue()
  - [x] 4.1 Crear método `getDayValue(dia)` que retorne el valor según categoría seleccionada
    - Si hay categoría seleccionada, retornar `dia.ventas[_selectedCategory]`
    - Si no hay categoría, retornar `dia.total`
    - Manejar casos donde `dia.ventas` sea undefined
    - _Requirements: 6.1, 6.2, 6.3_

- [x] 5. Modificar renderFogazaBreakdown()
  - [x] 5.1 Agregar `ringColor` a cada categoría en el objeto `categorias`
    - _Requirements: 2.1_
  
  - [x] 5.2 Hacer las Category_Cards clickeables
    - Agregar `cursor-pointer` y `onclick="calendar.selectCategory('${key}')"`
    - Agregar `data-category="${key}"` para identificación
    - _Requirements: 1.1_
  
  - [x] 5.3 Agregar indicador visual de selección
    - Aplicar clases `ring-2 ${ringColor} shadow-lg scale-105` cuando `_selectedCategory === key`
    - _Requirements: 2.1, 2.2_
  
  - [x] 5.4 Agregar botón "Ver todas" condicional
    - Mostrar solo cuando `_selectedCategory` no es null
    - Onclick llama a `calendar.selectCategory(null)`
    - _Requirements: 3.1, 3.3_
  
  - [x] 5.5 Actualizar header con nombre de categoría seleccionada
    - Mostrar "Filtrando: {nombre}" cuando hay categoría activa
    - Mostrar "Desglose de Ventas - Fogaza" cuando no hay filtro
    - _Requirements: 2.3_

- [x] 6. Modificar renderDia()
  - [x] 6.1 Usar `getDayValue(dia)` en lugar de `dia.total` para obtener el valor a mostrar
    - Formatear con `formatPrice()` el valor obtenido
    - _Requirements: 6.1, 6.2_
  
  - [x] 6.2 Actualizar cálculo de `esTopVentaGlobal` para usar el valor de categoría
    - Comparar con `_maxVentaGlobal` recalculado según categoría
    - _Requirements: 4.2_

- [x] 7. Modificar renderCalendar()
  - [x] 7.1 Resetear `_selectedCategory` a null cuando UDN cambia de 6 a otra
    - Verificar UDN antes de renderizar
    - _Requirements: 5.2_
  
  - [x] 7.2 Pasar `_selectedCategory` a `getWeeklyExtremes()` y cálculo de `_maxVentaGlobal`
    - _Requirements: 4.1_

- [x] 8. Implementar método calculateMaxVenta()
  - [x] 8.1 Crear método auxiliar para calcular el máximo global según categoría
    - Si hay categoría, calcular max de `dia.ventas[category]`
    - Si no hay categoría, calcular max de `dia.total`
    - _Requirements: 4.1, 4.3_

- [x] 9. Checkpoint - Verificar funcionalidad completa
  - Probar selección de cada categoría
  - Probar botón "Ver todas"
  - Probar cambio de UDN
  - Verificar indicadores visuales de máximo/mínimo
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Todas las modificaciones se realizan en `kpi/marketing/ventas/src/js/calendario.js`
- No se requieren cambios en el backend (los datos ya están disponibles)
- Usar `formatPrice()` para formatear todos los valores monetarios
- Mantener compatibilidad con el comportamiento existente cuando no hay categoría seleccionada
