# Requirements Document

## Introduction

Este documento define los requerimientos para el módulo de Ingresos (Ventas) del sistema de Contabilidad. El módulo permite la captura, edición y consulta de ventas diarias por unidad de negocio, incluyendo registro de cortes del día, formas de pago, impuestos, descuentos, cortesías y créditos a clientes.

## Glossary

- **Daily_Closure**: Registro del cierre diario de ventas por UDN
- **Sale_Category**: Categorías de venta (Alimentos, Bebidas, Diversos, Descorche)
- **Cash_Concept**: Conceptos de efectivo (Efectivo, Propina, Vales)
- **Foreign_Currency**: Monedas extranjeras (Dólar, Quetzal)
- **Bank_Account**: Cuentas bancarias para registro de pagos
- **Credit_Consumer**: Crédito otorgado a clientes
- **Credit_Payment**: Pagos recibidos de créditos
- **UDN**: Unidad de Negocio
- **System**: Módulo de Ingresos

## Requirements

### Requirement 1: Visualización del Resumen de Ventas del Día

**User Story:** As a gerente o auxiliar, I want to ver el resumen de ventas del día actual, so that I can conocer el estado financiero del cierre.

#### Acceptance Criteria

1. WHEN el usuario accede al módulo de Ventas, THE System SHALL mostrar el resumen de ventas del día con totales de: Ventas, Descuentos y cortesías, Impuestos, y Total de venta
2. WHEN el usuario accede al módulo de Ventas, THE System SHALL mostrar el resumen de formas de ingreso con totales de: Efectivo, Bancos, Créditos a clientes, y Total pagado
3. THE System SHALL calcular y mostrar tres KPI cards: Total de venta, Total pagado, y Diferencia
4. WHEN no existe registro de ventas para el día, THE System SHALL mostrar todos los valores en $0.00
5. THE System SHALL mostrar la fecha de captura actual en el header del módulo

### Requirement 2: Registro de Corte del Día (Agregar Ventas)

**User Story:** As a usuario autorizado, I want to registrar las ventas del día, so that I can cerrar correctamente el día con toda la información financiera.

#### Acceptance Criteria

1. WHEN el usuario hace clic en "Registrar corte del día", THE System SHALL mostrar el formulario de captura con dos secciones: Ventas del día y Formas de pago
2. THE System SHALL permitir capturar ventas por categoría (sin impuestos): Alimentos, Bebidas, Diversos, Descorche
3. THE System SHALL permitir capturar descuentos y cortesías (sin impuestos): Descuento Alimentos, Descuento Bebidas, Cortesía Alimentos, Cortesía Bebidas
4. THE System SHALL calcular automáticamente el IVA (8%) sobre las ventas netas
5. THE System SHALL permitir capturar pagos en efectivo: Propina, Efectivo, Vales, Dólar, Quetzal (GTQ)
6. THE System SHALL permitir capturar pagos por bancos dinámicamente según las cuentas configuradas para la UDN
7. THE System SHALL permitir capturar crédito a clientes: Consumos y Pagos o abonos
8. WHEN el usuario guarda el corte, THE System SHALL crear el registro en daily_closure con todos los detalles asociados
9. IF el horario está fuera del permitido según monthly_module_lock, THEN THE System SHALL mostrar mensaje de error y no permitir el registro

### Requirement 3: Edición de Ventas del Día

**User Story:** As a usuario autorizado, I want to editar las ventas del día actual, so that I can corregir errores antes del cierre definitivo.

#### Acceptance Criteria

1. WHEN existe un registro de ventas para el día actual, THE System SHALL mostrar el botón "Editar corte del día"
2. WHEN el usuario hace clic en "Editar corte del día", THE System SHALL cargar el formulario con los datos existentes precargados
3. THE System SHALL permitir modificar todos los campos de ventas por categoría
4. THE System SHALL permitir modificar todos los campos de descuentos y cortesías
5. THE System SHALL permitir modificar todos los campos de formas de pago
6. THE System SHALL recalcular automáticamente los totales e impuestos al modificar valores
7. WHEN el usuario guarda los cambios, THE System SHALL actualizar el registro en daily_closure y sus detalles
8. IF el horario está fuera del permitido, THEN THE System SHALL mostrar mensaje de error y no permitir la edición

### Requirement 4: Cálculo Automático de Totales

**User Story:** As a usuario, I want to que el sistema calcule automáticamente los totales, so that I can evitar errores de cálculo manual.

#### Acceptance Criteria

1. THE System SHALL calcular Total de Ventas como: Suma de (Alimentos + Bebidas + Diversos + Descorche)
2. THE System SHALL calcular Total Descuentos y Cortesías como: Suma de todos los descuentos y cortesías
3. THE System SHALL calcular Impuestos (IVA 8%) como: (Ventas - Descuentos) * 0.08
4. THE System SHALL calcular Total de Venta como: Ventas - Descuentos + Impuestos
5. THE System SHALL calcular Total Efectivo como: Propina + Efectivo + Vales + Monedas extranjeras
6. THE System SHALL calcular Total Bancos como: Suma de todos los pagos por banco
7. THE System SHALL calcular Créditos a Clientes como: Consumos - Pagos
8. THE System SHALL calcular Total Pagado como: Efectivo + Bancos - Créditos a clientes
9. THE System SHALL calcular Diferencia como: Total Pagado - Total de Venta
10. WHEN cualquier campo de entrada cambia, THE System SHALL recalcular todos los totales en tiempo real

### Requirement 5: Subida de Archivos de Ventas

**User Story:** As a usuario, I want to adjuntar archivos a las ventas del día, so that I can tener respaldo documental del cierre.

#### Acceptance Criteria

1. THE System SHALL mostrar el botón "Subir archivos de ventas" cuando existe un registro de ventas
2. WHEN el usuario hace clic en "Subir archivos de ventas", THE System SHALL abrir un modal para seleccionar archivos
3. THE System SHALL permitir subir múltiples archivos en formatos: PDF, PNG, JPG, JPEG, XLSX
4. WHEN se sube un archivo, THE System SHALL registrar en la tabla file: file_name, size_bytes, path, extension, created_at, section_id=1, user_id, udn_id, daily_closure_id
5. THE System SHALL mostrar mensaje de confirmación al completar la subida exitosamente
6. IF ocurre un error en la subida, THEN THE System SHALL mostrar mensaje de error descriptivo

### Requirement 6: Integración con Soft-Restaurant

**User Story:** As a gerente, I want to importar datos desde Soft-Restaurant, so that I can agilizar la captura de ventas.

#### Acceptance Criteria

1. THE System SHALL mostrar el botón "Soft-Restaurant" en el formulario de captura
2. WHEN el usuario hace clic en "Soft-Restaurant", THE System SHALL consultar los datos de ventas del día desde el sistema externo
3. THE System SHALL precargar automáticamente los campos de ventas por categoría con los datos importados
4. THE System SHALL permitir al usuario modificar los valores importados antes de guardar
5. IF no hay conexión con Soft-Restaurant, THEN THE System SHALL mostrar mensaje de error

### Requirement 7: Selección de Turno y Jefe de Turno

**User Story:** As a gerente, I want to registrar el turno y jefe de turno, so that I can identificar quién realizó el cierre.

#### Acceptance Criteria

1. THE System SHALL mostrar selector de Turno con opciones: Matutino, Vespertino
2. THE System SHALL mostrar selector de Jefe de turno con lista de empleados de la UDN
3. THE System SHALL permitir capturar el Total de suites ocupadas como campo numérico
4. WHEN se guarda el corte, THE System SHALL registrar turno, employee_id y total_suite en daily_closure

### Requirement 8: Control de Permisos por Rol

**User Story:** As a administrador del sistema, I want to que los usuarios solo accedan a funciones según su rol, so that I can mantener la seguridad del sistema.

#### Acceptance Criteria

1. WHEN el usuario es Gerente o Auxiliar, THE System SHALL permitir acceso a CRU de ventas sin selector de UDN
2. WHEN el usuario es Equipo Contable, THE System SHALL permitir solo consulta de concentrado con selector de UDN
3. WHEN el usuario es Developer, THE System SHALL permitir acceso completo a CRU y concentrado con selector de UDN
4. THE System SHALL validar permisos antes de mostrar botones de acción (Registrar, Editar, Subir archivos)

### Requirement 9: Validación de Horario de Captura

**User Story:** As a administrador, I want to que el sistema valide el horario permitido para captura, so that I can controlar cuándo se pueden registrar ventas.

#### Acceptance Criteria

1. THE System SHALL consultar la tabla monthly_module_lock para obtener el horario permitido
2. IF la hora actual está fuera del rango permitido, THEN THE System SHALL deshabilitar los botones de Registrar y Editar
3. WHEN el usuario intenta guardar fuera del horario, THE System SHALL mostrar mensaje: "Fuera del horario permitido para captura"
4. THE System SHALL mostrar indicador visual cuando el módulo está bloqueado por horario

### Requirement 10: Navegación por Tabs del Módulo de Captura

**User Story:** As a usuario, I want to navegar entre los diferentes módulos de captura, so that I can acceder rápidamente a cada sección.

#### Acceptance Criteria

1. THE System SHALL mostrar tabs de navegación: Ventas, Clientes, Compras, Salidas de almacén, Pagos a proveedor, Archivos
2. WHEN el usuario hace clic en un tab, THE System SHALL cargar el contenido correspondiente
3. THE System SHALL resaltar visualmente el tab activo
4. THE System SHALL mantener el estado del formulario al cambiar de tab y regresar
