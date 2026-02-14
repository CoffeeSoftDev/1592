# Requirements Document

## Introduction

Sistema de gestión de salidas de almacén que permite registrar, editar, dar de baja y consultar las salidas de almacén. El módulo cuenta con dos apartados principales: CRUD de salidas de almacén y concentrado de salidas de almacén con entradas y salidas por subcuenta.

## Glossary

- **Warehouse_Output_System**: Sistema principal de gestión de salidas de almacén
- **Warehouse_Output**: Registro de salida de almacén con monto, descripción y subcuenta
- **Subaccount**: Subcuenta relacionada a la cuenta mayor "Almacén" (ej: Alimentos, Bebidas, Diversos)
- **Daily_Closure**: Cierre diario que agrupa las operaciones por fecha y UDN
- **Warehouse_Entry**: Entrada de almacén proveniente de compras bajo la cuenta mayor "Almacén"
- **Initial_Balance**: Saldo inicial calculado como entradas menos salidas hasta la fecha anterior
- **Final_Balance**: Saldo final calculado como saldo inicial más entradas menos salidas
- **UDN**: Unidad de Negocio
- **User_Level**: Nivel de usuario que determina permisos (Gerente, Auxiliar, Contable, Developer)

## Requirements

### Requirement 1: Visualización de KPIs de Salidas de Almacén (CRUD)

**User Story:** As a gerente, I want to ver el total de salidas de almacén por fecha seleccionada, so that I can monitorear el movimiento de inventario.

#### Acceptance Criteria

1. WHEN el usuario accede al apartado CRUD de salidas, THE Warehouse_Output_System SHALL mostrar una InfoCard con el total de salidas de almacén por la fecha seleccionada
2. WHEN se registra, edita o elimina una salida, THE Warehouse_Output_System SHALL actualizar automáticamente el KPI de total de salidas

### Requirement 2: Registro de Nueva Salida de Almacén

**User Story:** As a gerente, I want to registrar nuevas salidas de almacén, so that I can mantener el control del inventario.

#### Acceptance Criteria

1. WHEN el usuario hace clic en "Registrar nueva salida de almacén", THE Warehouse_Output_System SHALL mostrar un modal con el título "Nueva salida de almacén"
2. THE Modal_Form SHALL contener un select de Almacén con las subcuentas relacionadas a la cuenta mayor "Almacén"
3. THE Modal_Form SHALL contener un input de Cantidad (monto) de tipo cifra
4. THE Modal_Form SHALL contener un textarea de Descripción
5. WHEN el usuario envía el formulario con todos los campos obligatorios, THE Warehouse_Output_System SHALL crear el registro de salida de almacén
6. IF algún campo obligatorio está vacío, THEN THE Warehouse_Output_System SHALL mostrar mensaje de validación y prevenir el envío
7. WHEN la salida se registra exitosamente, THE Warehouse_Output_System SHALL actualizar la tabla de salidas y los KPIs

### Requirement 3: Listado de Salidas de Almacén

**User Story:** As a gerente, I want to ver la lista de salidas de almacén filtradas por fecha, so that I can revisar los movimientos del día.

#### Acceptance Criteria

1. THE Warehouse_Output_System SHALL mostrar una tabla con las columnas: Almacén, Monto, Descripción y Acciones
2. WHEN el usuario selecciona una fecha, THE Warehouse_Output_System SHALL filtrar las salidas por esa fecha única
3. THE Warehouse_Output_System SHALL mostrar botones de editar y eliminar en cada fila
4. WHEN el usuario hace clic en una entrada o salida, THE Warehouse_Output_System SHALL abrir un modal mostrando el detalle (descripción para salidas, detalle de compra para entradas)

### Requirement 4: Edición de Salida de Almacén

**User Story:** As a gerente, I want to editar salidas de almacén existentes, so that I can corregir errores de captura.

#### Acceptance Criteria

1. WHEN el usuario hace clic en el botón editar, THE Warehouse_Output_System SHALL mostrar un modal con el título "Editar salida de almacén"
2. THE Modal_Form SHALL precargar los datos actuales de la salida (almacén, cantidad, descripción)
3. WHEN el usuario guarda los cambios, THE Warehouse_Output_System SHALL actualizar el registro y refrescar la tabla y KPIs

### Requirement 5: Eliminación de Salida de Almacén

**User Story:** As a gerente, I want to dar de baja salidas de almacén, so that I can corregir registros erróneos.

#### Acceptance Criteria

1. WHEN el usuario hace clic en el botón eliminar, THE Warehouse_Output_System SHALL mostrar un diálogo de confirmación con el mensaje "¿Está seguro de querer eliminar la salida de almacén?"
2. WHEN el usuario confirma la eliminación, THE Warehouse_Output_System SHALL desactivar el registro (soft delete con active=0)
3. WHEN la eliminación es exitosa, THE Warehouse_Output_System SHALL actualizar la tabla y los KPIs

### Requirement 6: Toggle entre CRUD y Concentrado

**User Story:** As a gerente, I want to cambiar entre la vista CRUD y el concentrado, so that I can acceder a ambas funcionalidades fácilmente.

#### Acceptance Criteria

1. THE Warehouse_Output_System SHALL mostrar un botón toggle "Concentrado de Almacén" en el filterBar
2. WHEN el usuario activa el toggle, THE Warehouse_Output_System SHALL cambiar la vista al apartado de concentrado
3. WHEN el usuario desactiva el toggle, THE Warehouse_Output_System SHALL regresar a la vista CRUD
4. WHEN se cambia al concentrado, THE Warehouse_Output_System SHALL cambiar el filtro de fecha única a rango de fechas

### Requirement 7: Visualización de KPIs del Concentrado

**User Story:** As a usuario contable, I want to ver los KPIs de saldo inicial, entradas, salidas y saldo final, so that I can analizar el movimiento de almacén.

#### Acceptance Criteria

1. THE Warehouse_Output_System SHALL mostrar 4 InfoCards: Saldo Inicial, Entradas al Almacén, Salidas del Almacén, Saldo Final
2. THE Saldo_Inicial SHALL calcularse como todas las entradas menos salidas hasta el día anterior al rango seleccionado
3. THE Entradas_Almacen SHALL ser la suma de compras bajo la cuenta mayor "Almacén" en el rango de fechas
4. THE Salidas_Almacen SHALL ser la suma de salidas de almacén registradas en el rango de fechas
5. THE Saldo_Final SHALL calcularse como Saldo Inicial + Entradas - Salidas

### Requirement 8: Tabla de Concentrado de Almacén

**User Story:** As a usuario contable, I want to ver el concentrado de entradas y salidas por subcuenta y fecha, so that I can analizar el movimiento detallado del almacén.

#### Acceptance Criteria

1. THE Warehouse_Output_System SHALL mostrar una tabla con columnas: Almacén (subcuenta), Total, y por cada fecha del rango: Entradas y Salidas
2. THE Warehouse_Output_System SHALL agrupar las filas por subcuenta de la cuenta mayor "Almacén"
3. WHEN el usuario expande una subcuenta, THE Warehouse_Output_System SHALL mostrar: Saldo Inicial, Entrada de [Subcuenta], Salida de [Subcuenta], Saldo Final
4. THE Warehouse_Output_System SHALL mostrar al final dos filas: Total de Entradas y Total de Salidas
5. THE Entradas SHALL provenir de compras del módulo de compras con cuenta mayor "Almacén"
6. THE Salidas SHALL provenir de los registros del CRUD de salidas de almacén

### Requirement 9: Control de Permisos por Nivel de Usuario

**User Story:** As a administrador, I want to que los usuarios tengan acceso según su nivel, so that I can mantener la seguridad del sistema.

#### Acceptance Criteria

1. WHEN el usuario es Gerente o Auxiliar, THE Warehouse_Output_System SHALL mostrar el CRUD y el concentrado sin selector de UDN
2. WHEN el usuario es Gerente o Auxiliar, THE Warehouse_Output_System SHALL tomar la UDN de la cookie IDE
3. WHEN el usuario es Contable, THE Warehouse_Output_System SHALL mostrar solo el concentrado con selector de UDN y rango de fechas
4. WHEN el usuario es Developer, THE Warehouse_Output_System SHALL mostrar CRUD y concentrado con selector de UDN
5. WHILE el usuario está en CRUD, THE Warehouse_Output_System SHALL mostrar filtro de fecha única
6. WHILE el usuario está en Concentrado, THE Warehouse_Output_System SHALL mostrar filtro de rango de fechas

### Requirement 10: Subida de Archivos de Almacén

**User Story:** As a gerente, I want to subir archivos relacionados al almacén, so that I can mantener documentación de respaldo.

#### Acceptance Criteria

1. THE Warehouse_Output_System SHALL mostrar un botón "Subir archivos de almacén" en el filterBar del CRUD
2. WHEN el usuario hace clic en el botón, THE Warehouse_Output_System SHALL abrir un selector de archivos múltiples
3. THE Warehouse_Output_System SHALL aceptar formatos: PDF, Excel, Word, imágenes (PNG, JPG, JPEG)
4. WHEN los archivos se suben exitosamente, THE Warehouse_Output_System SHALL mostrar mensaje de confirmación

### Requirement 11: Detalle de Entradas y Salidas en Concentrado

**User Story:** As a usuario, I want to ver el detalle de cada entrada o salida, so that I can verificar la información.

#### Acceptance Criteria

1. WHEN el usuario hace clic en una celda de entrada, THE Warehouse_Output_System SHALL mostrar un modal con el detalle de la compra
2. WHEN el usuario hace clic en una celda de salida, THE Warehouse_Output_System SHALL mostrar un modal con la descripción de la salida
3. THE Modal_Detail SHALL mostrar la información relevante según el tipo de movimiento
