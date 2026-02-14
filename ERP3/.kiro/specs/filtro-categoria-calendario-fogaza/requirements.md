# Requirements Document

## Introduction

Este documento define los requisitos para implementar un filtro interactivo por categoría en el calendario de ventas de Fogaza (UDN 6). La funcionalidad permitirá al usuario hacer clic en una categoría del desglose existente para visualizar los totales de esa categoría específica en el calendario, en lugar del total general.

## Glossary

- **Calendar**: Componente visual que muestra las ventas diarias en formato de calendario semanal
- **Category_Card**: Tarjeta visual que muestra el total acumulado de una categoría específica de Fogaza
- **Fogaza_Breakdown**: Sección que muestra el desglose de ventas por las 9 categorías de Fogaza
- **Selected_Category**: Categoría actualmente seleccionada para filtrar la vista del calendario
- **Extremes**: Valores máximos y mínimos calculados para resaltar días destacados
- **Day_Card**: Tarjeta individual que representa un día en el calendario con sus métricas

## Requirements

### Requirement 1: Cards de categoría clickeables

**User Story:** As a usuario de Fogaza, I want hacer clic en una categoría del desglose, so that puedo ver las ventas específicas de esa categoría en el calendario.

#### Acceptance Criteria

1. WHEN el usuario hace clic en una Category_Card, THE Calendar SHALL actualizar su visualización para mostrar los valores de la Selected_Category
2. WHEN una Category_Card es seleccionada, THE Fogaza_Breakdown SHALL mostrar un indicador visual (borde destacado y sombra) en la tarjeta activa
3. WHEN el usuario selecciona una categoría diferente, THE Calendar SHALL recalcular y mostrar los nuevos valores de la nueva Selected_Category

### Requirement 2: Indicador visual de categoría seleccionada

**User Story:** As a usuario, I want ver claramente qué categoría está seleccionada, so that sepa qué datos estoy visualizando en el calendario.

#### Acceptance Criteria

1. WHEN una Category_Card está seleccionada, THE Category_Card SHALL mostrar un borde de color distintivo (ring-2) y sombra elevada
2. WHEN ninguna categoría está seleccionada (vista total), THE Fogaza_Breakdown SHALL mostrar todas las cards con estilo normal sin indicador de selección
3. THE Fogaza_Breakdown SHALL mostrar el nombre de la categoría seleccionada en el encabezado cuando hay un filtro activo

### Requirement 3: Opción para ver todas las categorías

**User Story:** As a usuario, I want tener una opción para resetear el filtro, so that pueda volver a ver el total general de ventas.

#### Acceptance Criteria

1. WHEN existe una Selected_Category activa, THE Fogaza_Breakdown SHALL mostrar un botón "Ver todas" visible
2. WHEN el usuario hace clic en "Ver todas", THE Calendar SHALL resetear la vista para mostrar el total general de ventas
3. WHEN el usuario hace clic en "Ver todas", THE Fogaza_Breakdown SHALL remover el indicador visual de selección de todas las Category_Cards

### Requirement 4: Recálculo de extremos por categoría

**User Story:** As a usuario, I want que los indicadores de máximo y mínimo se recalculen según la categoría seleccionada, so that pueda identificar los mejores y peores días para esa categoría específica.

#### Acceptance Criteria

1. WHEN una categoría es seleccionada, THE Calendar SHALL recalcular los Extremes basándose únicamente en los valores de la Selected_Category
2. WHEN los Extremes son recalculados, THE Day_Card SHALL actualizar los indicadores visuales (verde para máximo, rojo para mínimo)
3. WHEN se selecciona "Ver todas", THE Calendar SHALL recalcular los Extremes usando el total general de ventas

### Requirement 5: Persistencia del estado de selección

**User Story:** As a usuario, I want que mi selección de categoría se mantenga mientras navego en la misma UDN, so that no tenga que volver a seleccionar cada vez.

#### Acceptance Criteria

1. WHILE el usuario permanece en UDN 6, THE Calendar SHALL mantener la Selected_Category activa
2. WHEN el usuario cambia de UDN, THE Calendar SHALL resetear la Selected_Category a null (vista total)
3. WHEN el calendario se re-renderiza por cambio de filtros, THE Calendar SHALL preservar la Selected_Category si sigue siendo UDN 6

### Requirement 6: Actualización de valores en Day_Cards

**User Story:** As a usuario, I want ver los valores específicos de la categoría seleccionada en cada día del calendario, so that pueda analizar el rendimiento diario de esa categoría.

#### Acceptance Criteria

1. WHEN una categoría está seleccionada, THE Day_Card SHALL mostrar el valor de ventas de la Selected_Category en lugar del total general
2. WHEN una categoría está seleccionada, THE Day_Card SHALL formatear el valor usando formatPrice()
3. IF un día no tiene ventas para la Selected_Category, THEN THE Day_Card SHALL mostrar "$0.00" o "-" según corresponda
