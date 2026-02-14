# Implementation Plan

- [x] 1. Crear función auxiliar para renderizado de badges


  - Crear función `renderModuleBadges()` en `ctrl-admin.php`
  - Implementar mapeo de colores para cada tipo de módulo
  - Generar HTML de badges con TailwindCSS
  - Manejar casos edge: módulos desconocidos, strings vacíos
  - _Requirements: 1.1, 1.2, 1.3, 3.1, 3.2, 3.3, 3.4, 3.5_


- [ ] 2. Modificar método lsUnlocks() en controlador
  - Agregar soporte para recibir parámetro `udn_id` desde POST
  - Pasar filtro de UDN al método del modelo
  - Procesar campo `sections` con `renderModuleBadges()`
  - Retornar HTML enriquecido en estructura de array con `html` y `class`


  - _Requirements: 1.4, 2.2, 2.3_

- [ ] 3. Actualizar método listUnlocks() en modelo
  - Modificar query para aceptar filtro opcional de UDN


  - Mantener GROUP_CONCAT para concatenar módulos
  - Agregar validación de parámetros
  - _Requirements: 2.2, 2.3, 2.5_



- [ ] 4. Agregar filtro de UDN en filterBar
  - Modificar `filterBarDesbloqueo()` en `admin.js`
  - Agregar select con opción "Todas las UDN" y lista de UDN
  - Configurar evento `onchange` para llamar `modules.lsUnlocks()`

  - _Requirements: 2.1, 2.2, 2.5_

- [ ] 5. Actualizar método lsUnlocks() en frontend
  - Capturar valor del select `filter_udn`
  - Agregar parámetro `udn_id` al data de la petición AJAX

  - Mantener configuración existente de DataTables
  - _Requirements: 2.2, 2.3, 2.4_

- [ ] 6. Ajustar estilos responsive para badges
  - Verificar que badges se ajusten en múltiples líneas



  - Agregar padding vertical a celda de módulos
  - Probar en diferentes tamaños de pantalla
  - _Requirements: 5.1, 5.2, 5.3, 5.4, 5.5_

- [ ] 7. Verificar funcionalidad de DataTables con badges
  - Probar búsqueda incluyendo texto de badges
  - Verificar ordenamiento por columna "Módulos"
  - Confirmar que paginación mantiene formato
  - _Requirements: 8.1, 8.2, 8.3, 8.4, 8.5_

- [ ] 8. Checkpoint - Asegurar que todas las funcionalidades existentes sigan operando
  - Verificar que toggle de estado funcione correctamente
  - Confirmar que modal de agregar desbloqueo funcione
  - Probar modal de horarios de cierre
  - Validar que no haya errores en consola
  - Ensure all tests pass, ask the user if questions arise.
