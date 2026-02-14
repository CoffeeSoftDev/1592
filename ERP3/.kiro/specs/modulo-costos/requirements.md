# Requirements Document - Módulo de Costos

## Introduction

El módulo de Costos es un sistema de consulta diseñado para visualizar el concentrado diario de costos directos y salidas de almacén dentro del sistema ERP CoffeeSoft. Permite a los usuarios del área contable y gerencial analizar la operación diaria sin modificar información, con filtros por fecha y unidad de negocio.

## Glossary

- **Sistema**: Módulo de Costos del ERP CoffeeSoft
- **UDN**: Unidad de Negocio
- **Costos Directos**: Gastos operativos directamente relacionados con la producción
- **Salidas de Almacén**: Movimientos de inventario registrados en el sistema
- **Concentrado**: Reporte consolidado que agrupa información por categorías y fechas
- **Usuario**: Gerente, auxiliar del gerente, equipo contable o developer con acceso al sistema

## Requirements

### Requirement 1

**User Story:** Como gerente, quiero visualizar el concentrado diario de costos directos y salidas de almacén dentro de un rango de fechas, para analizar la operación diaria sin modificar información.

#### Acceptance Criteria

1. WHEN el usuario accede al módulo de Costos THEN el Sistema SHALL mostrar una interfaz con barra de filtros y tabla de concentrado
2. WHEN el usuario selecciona un rango de fechas THEN el Sistema SHALL generar columnas dinámicas para cada fecha del rango seleccionado
3. WHEN el usuario selecciona una unidad de negocio THEN el Sistema SHALL filtrar los datos correspondientes a la UDN seleccionada
4. WHEN el Sistema muestra la tabla THEN el Sistema SHALL agrupar los costos por categorías (Alimentos, Bebidas, Diversos)
5. WHEN el Sistema muestra la tabla THEN el Sistema SHALL calcular automáticamente los totales por día y por categoría

### Requirement 2

**User Story:** Como usuario del sistema, quiero que la tabla tenga scroll horizontal, para poder visualizar rangos de fechas amplios sin perder información.

#### Acceptance Criteria

1. WHEN el rango de fechas excede el ancho visible THEN el Sistema SHALL habilitar scroll horizontal en la tabla
2. WHEN el usuario hace scroll horizontal THEN el Sistema SHALL mantener visible la columna de categorías
3. WHEN el Sistema renderiza la tabla THEN el Sistema SHALL aplicar estilos responsive para diferentes tamaños de pantalla

### Requirement 3

**User Story:** Como usuario del sistema, quiero que el módulo respete el diseño visual existente, para mantener consistencia con otros módulos del ERP.

#### Acceptance Criteria

1. WHEN el Sistema renderiza la interfaz THEN el Sistema SHALL usar el tema "light" de CoffeeSoft
2. WHEN el Sistema muestra la tabla THEN el Sistema SHALL aplicar los colores corporativos definidos en el sistema
3. WHEN el Sistema muestra totales THEN el Sistema SHALL usar formato de moneda con separadores de miles
4. WHEN el Sistema muestra fechas THEN el Sistema SHALL usar formato español (DD/MM/YYYY)

### Requirement 4

**User Story:** Como usuario del sistema, quiero alternar entre la vista de costos y otras vistas, para acceder rápidamente a diferentes reportes.

#### Acceptance Criteria

1. WHEN el usuario hace clic en el botón "Concentrado" THEN el Sistema SHALL alternar entre vista de costos y vista consolidada
2. WHEN el Sistema cambia de vista THEN el Sistema SHALL actualizar el texto del botón toggle
3. WHEN el Sistema cambia de vista THEN el Sistema SHALL limpiar el contenedor y renderizar la nueva vista

### Requirement 5

**User Story:** Como usuario del sistema, quiero que los datos se obtengan de las tablas correctas de la base de datos, para asegurar la precisión de la información.

#### Acceptance Criteria

1. WHEN el Sistema consulta costos directos THEN el Sistema SHALL obtener datos de la tabla "purchase" con gl_account_id correspondiente
2. WHEN el Sistema consulta salidas de almacén THEN el Sistema SHALL obtener datos de la tabla "warehouse_output"
3. WHEN el Sistema agrupa información THEN el Sistema SHALL usar la tabla "subaccount" para categorizar los costos
4. WHEN el Sistema filtra por UDN THEN el Sistema SHALL validar que la UDN existe en la tabla "udn"

---

**Versión:** 1.0  
**Fecha:** 2025-01-14  
**Estado:** Aprobado
