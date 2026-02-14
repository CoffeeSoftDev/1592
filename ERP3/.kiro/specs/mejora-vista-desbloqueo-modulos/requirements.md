# Requirements Document

## Introduction

Este documento define los requisitos para mejorar la interfaz de visualización del módulo de "Desbloqueo de Secciones" en el sistema de administración financiera. La mejora busca transformar la vista actual de tabla tradicional a una vista más visual y moderna que muestre los módulos desbloqueados como badges/pills de colores, facilitando la identificación rápida del estado de cada sección.

## Glossary

- **UDN**: Unidad de Negocio - Entidad organizacional que agrupa operaciones específicas (ej: Quinta Tabachines, Finca Zaragoza)
- **Sección/Módulo**: Componente funcional del sistema que puede ser bloqueado o desbloqueado (ej: Ventas, Clientes, Almacén, Compras)
- **Desbloqueo**: Acción de habilitar temporalmente el acceso a una sección específica del sistema
- **Badge/Pill**: Elemento visual tipo etiqueta con forma de píldora que muestra información de manera compacta
- **Estado de bloqueo**: Indicador binario que determina si una sección está activa (desbloqueada) o inactiva (bloqueada)
- **FilterBar**: Barra de filtros que contiene controles para filtrar y acciones principales
- **Sistema**: Aplicación web de administración financiera CoffeeSoft

## Requirements

### Requirement 1

**User Story:** Como administrador del sistema, quiero visualizar las solicitudes de desbloqueo en un formato más visual con badges de colores, para identificar rápidamente qué módulos están desbloqueados en cada UDN.

#### Acceptance Criteria

1. WHEN el sistema renderiza la tabla de desbloqueos THEN el Sistema SHALL mostrar los módulos como badges/pills con colores distintivos en lugar de texto plano
2. WHEN un registro contiene múltiples módulos desbloqueados THEN el Sistema SHALL mostrar cada módulo como un badge individual dentro de la misma celda
3. WHEN se muestra un badge de módulo THEN el Sistema SHALL aplicar un color específico según el tipo de módulo (Verde para Ventas, Naranja para Clientes, Azul para Almacén, Rojo para Compras)
4. WHEN la tabla se renderiza THEN el Sistema SHALL mantener las columnas: UDN, Fecha, Motivo, Módulos (con badges), y Opciones
5. WHEN se muestra la columna de opciones THEN el Sistema SHALL incluir un botón de acción para bloquear/desbloquear con icono de candado

### Requirement 2

**User Story:** Como administrador, quiero filtrar las solicitudes de desbloqueo por UDN, para enfocarme en una unidad de negocio específica.

#### Acceptance Criteria

1. WHEN el usuario accede a la vista de desbloqueos THEN el Sistema SHALL mostrar un filtro tipo select con todas las UDN disponibles
2. WHEN el usuario selecciona una UDN del filtro THEN el Sistema SHALL actualizar la tabla mostrando solo los registros de esa UDN
3. WHEN el filtro de UDN cambia THEN el Sistema SHALL mantener el formato visual de badges en los módulos
4. WHEN no hay registros para la UDN seleccionada THEN el Sistema SHALL mostrar un mensaje indicando que no hay datos
5. WHERE el filtro incluye una opción "Todas" THEN el Sistema SHALL mostrar registros de todas las UDN cuando esta opción esté seleccionada

### Requirement 3

**User Story:** Como administrador, quiero que los badges de módulos tengan colores consistentes y distintivos, para identificar rápidamente el tipo de módulo sin leer el texto.

#### Acceptance Criteria

1. WHEN se renderiza un badge de "Ventas" THEN el Sistema SHALL aplicar color verde (#10B981 o equivalente)
2. WHEN se renderiza un badge de "Clientes" THEN el Sistema SHALL aplicar color naranja (#F97316 o equivalente)
3. WHEN se renderiza un badge de "Almacén" THEN el Sistema SHALL aplicar color azul (#3B82F6 o equivalente)
4. WHEN se renderiza un badge de "Compras" THEN el Sistema SHALL aplicar color rojo (#EF4444 o equivalente)
5. WHEN se renderiza un badge THEN el Sistema SHALL aplicar estilos de TailwindCSS con padding, border-radius y texto centrado

### Requirement 4

**User Story:** Como administrador, quiero que la columna de opciones muestre claramente el estado de bloqueo, para saber si puedo bloquear o desbloquear una sección.

#### Acceptance Criteria

1. WHEN una sección está desbloqueada (active = 1) THEN el Sistema SHALL mostrar un icono de candado abierto en color verde
2. WHEN una sección está bloqueada (active = 0) THEN el Sistema SHALL mostrar un icono de candado cerrado en color rojo
3. WHEN el usuario hace clic en el icono de candado THEN el Sistema SHALL mostrar un diálogo de confirmación antes de cambiar el estado
4. WHEN el estado cambia exitosamente THEN el Sistema SHALL actualizar la tabla automáticamente sin recargar la página
5. WHEN el cambio de estado falla THEN el Sistema SHALL mostrar un mensaje de error descriptivo

### Requirement 5

**User Story:** Como administrador, quiero que la interfaz sea responsive y mantenga la legibilidad en diferentes tamaños de pantalla, para poder gestionar desbloqueos desde cualquier dispositivo.

#### Acceptance Criteria

1. WHEN la tabla se visualiza en pantallas grandes (>1024px) THEN el Sistema SHALL mostrar todas las columnas con espaciado adecuado
2. WHEN la tabla se visualiza en tablets (768px-1024px) THEN el Sistema SHALL ajustar el ancho de columnas manteniendo la visibilidad de badges
3. WHEN la tabla se visualiza en móviles (<768px) THEN el Sistema SHALL permitir scroll horizontal o colapsar columnas menos importantes
4. WHEN los badges se muestran en pantallas pequeñas THEN el Sistema SHALL mantener el tamaño de fuente legible (mínimo 12px)
5. WHEN hay múltiples badges en una celda THEN el Sistema SHALL permitir que se ajusten en múltiples líneas si es necesario

### Requirement 6

**User Story:** Como desarrollador, quiero que la lógica de renderizado de badges sea reutilizable, para mantener consistencia en otras partes del sistema que muestren módulos.

#### Acceptance Criteria

1. WHEN se implementa el renderizado de badges THEN el Sistema SHALL crear una función auxiliar reutilizable para generar badges
2. WHEN la función de badges recibe un array de módulos THEN el Sistema SHALL retornar HTML con todos los badges formateados
3. WHEN se define el mapeo de colores THEN el Sistema SHALL usar un objeto de configuración centralizado
4. WHEN se necesita agregar un nuevo tipo de módulo THEN el Sistema SHALL permitir extender el mapeo de colores sin modificar la lógica principal
5. WHERE se use la función de badges THEN el Sistema SHALL aceptar parámetros opcionales para personalizar estilos

### Requirement 7

**User Story:** Como administrador, quiero que los botones de acción principales estén claramente visibles, para acceder rápidamente a las funciones de desbloqueo y configuración de horarios.

#### Acceptance Criteria

1. WHEN se renderiza el filterBar THEN el Sistema SHALL mostrar el botón "Desbloquear módulo" con color azul primario
2. WHEN se renderiza el filterBar THEN el Sistema SHALL mostrar el botón "Horario de cierre mensual" con color naranja
3. WHEN los botones se muestran en desktop THEN el Sistema SHALL alinearlos horizontalmente con espaciado adecuado
4. WHEN los botones se muestran en móvil THEN el Sistema SHALL apilarlos verticalmente ocupando el ancho completo
5. WHEN el usuario hace clic en "Desbloquear módulo" THEN el Sistema SHALL abrir el modal de formulario de desbloqueo

### Requirement 8

**User Story:** Como administrador, quiero que la tabla mantenga la funcionalidad de DataTables, para poder buscar, ordenar y paginar los registros de manera eficiente.

#### Acceptance Criteria

1. WHEN la tabla se inicializa THEN el Sistema SHALL habilitar DataTables con paginación de 15 registros por página
2. WHEN el usuario escribe en el campo de búsqueda THEN el Sistema SHALL filtrar registros en tiempo real incluyendo el texto dentro de los badges
3. WHEN el usuario hace clic en un encabezado de columna THEN el Sistema SHALL ordenar los registros por esa columna
4. WHEN se ordena por la columna "Módulos" THEN el Sistema SHALL ordenar alfabéticamente por el primer módulo del array
5. WHEN se aplica paginación THEN el Sistema SHALL mantener el formato visual de badges en todas las páginas
