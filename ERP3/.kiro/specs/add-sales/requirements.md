# Requirements Document

## Introduction

El módulo **Agregar Ventas (Add Sales)** permite registrar las ventas diarias y formas de pago dentro del sistema de Contabilidad del ERP CoffeeSoft. Este módulo forma parte del flujo de cierre diario (`daily_closure`) y está diseñado para capturar información de ventas por categoría, impuestos aplicables, y las diferentes formas de pago recibidas por unidad de negocio (UDN).

## Glossary

- **System**: Sistema de Contabilidad (Contabilidad)
- **AddSales**: Clase principal del módulo de Agregar Ventas
- **UDN**: Unidad de Negocio (Business Unit)
- **daily_closure**: Registro principal de cierre diario que agrupa todas las ventas y pagos de una fecha específica
- **sale_category**: Categorías de venta por UDN (Cortes, Bebidas, Guarniciones, etc.)
- **detail_sale_category**: Detalle de ventas por categoría (venta, descuento, cortesía)
- **tax**: Tipos de impuesto (IVA 8%, IEPS 8%, HOSPEDAJE 2%)
- **sale_category_tax**: Relación entre categorías de venta e impuestos aplicables
- **detail_sale_category_tax**: Detalle de impuestos calculados por venta
- **cash_concept**: Conceptos de efectivo por UDN (Efectivo, Propinas, Destajo, Vales)
- **detail_cash_concept**: Detalle de conceptos de efectivo registrados
- **foreing_currency**: Monedas extranjeras (Dólar, Quetzal) con tipo de cambio
- **detail_foreing_currency**: Detalle de pagos en moneda extranjera
- **bank_account**: Cuentas bancarias por UDN vinculadas a bancos
- **detail_bank_account**: Detalle de pagos por cuenta bancaria
- **detail_credit_customer**: Detalle de créditos a clientes (solo lectura)
- **monthly_module_lock**: Configuración de horarios de bloqueo por mes
- **SummaryCard**: Componente visual que muestra totales en tiempo real

## Requirements

### Requirement 1: Visualización de Ventas del Día

**User Story:** As a usuario de contabilidad, I want to ver y registrar las ventas del día por categoría, so that I can capturar la información de ventas de manera organizada.

#### Acceptance Criteria

1. WHEN el usuario accede al módulo de Agregar Ventas, THE AddSales SHALL mostrar una tarjeta (Card 1) con el título "Ventas del Día"
2. WHEN la tarjeta de Ventas del Día se renderiza, THE AddSales SHALL mostrar un botón naranja "Soft Restaurant" en la barra de título
3. WHEN el usuario hace clic en el botón "Soft Restaurant", THE AddSales SHALL abrir una nueva pestaña con la URL del sistema Soft Restaurant
4. THE AddSales SHALL cargar dinámicamente las categorías de venta desde la tabla `sale_category` filtradas por la UDN seleccionada
5. WHEN se cargan las categorías de venta, THE AddSales SHALL mostrar un campo de entrada numérico para cada categoría (sin impuestos incluidos)
6. THE AddSales SHALL mostrar campos separados para Descuentos y Cortesías (sin impuestos)
7. THE AddSales SHALL calcular automáticamente los impuestos basándose en la relación `sale_category_tax` y la tabla `tax`
8. WHEN el usuario modifica cualquier valor de venta, THE AddSales SHALL recalcular los impuestos en tiempo real
9. THE AddSales SHALL mostrar un SummaryCard con los totales de: Ventas, Descuentos, Cortesías, Impuestos y Total de Venta

### Requirement 2: Registro de Formas de Pago

**User Story:** As a usuario de contabilidad, I want to registrar las diferentes formas de pago recibidas, so that I can documentar cómo se recibió el dinero de las ventas.

#### Acceptance Criteria

1. WHEN el usuario accede al módulo de Agregar Ventas, THE AddSales SHALL mostrar una tarjeta (Card 2) con el título "Formas de Pago"
2. THE AddSales SHALL cargar dinámicamente los conceptos de efectivo desde la tabla `cash_concept` filtrados por la UDN seleccionada
3. WHEN se cargan los conceptos de efectivo, THE AddSales SHALL mostrar campos para: Propina, Efectivo, Vales y otros conceptos configurados
4. THE AddSales SHALL cargar las monedas extranjeras desde la tabla `foreing_currency` con sus tipos de cambio
5. WHEN el usuario ingresa un monto en moneda extranjera, THE AddSales SHALL calcular automáticamente el equivalente en MXN usando el tipo de cambio
6. THE AddSales SHALL cargar las cuentas bancarias desde la tabla `bank_account` filtradas por la UDN seleccionada
7. WHEN se muestran las cuentas bancarias, THE AddSales SHALL mostrar el nombre del banco asociado desde la tabla `bank`
8. THE AddSales SHALL mostrar una sección de "Crédito a Clientes" en modo solo lectura desde `detail_credit_customer`
9. THE AddSales SHALL mostrar un SummaryCard con los totales de: Efectivo, Moneda Extranjera, Bancos, Crédito y Total Pagado

### Requirement 3: Cálculo de Impuestos

**User Story:** As a usuario de contabilidad, I want que los impuestos se calculen automáticamente, so that I can evitar errores de cálculo manual.

#### Acceptance Criteria

1. WHEN el usuario ingresa un monto de venta por categoría, THE AddSales SHALL consultar dinámicamente los impuestos aplicables desde `sale_category_tax` JOIN `tax`
2. THE AddSales SHALL aplicar el porcentaje de impuesto correspondiente desde la tabla `tax` (IVA 8%, IEPS 8%, HOSPEDAJE 2%)
3. WHEN una categoría tiene múltiples impuestos asociados (ej: Hospedaje con IVA 8% + HOSPEDAJE 2%), THE AddSales SHALL calcular y sumar todos los impuestos aplicables
4. THE AddSales SHALL calcular los impuestos sobre descuentos y cortesías de manera separada usando los mismos porcentajes de la categoría
5. WHEN se modifica cualquier valor, THE AddSales SHALL actualizar el total de impuestos en el SummaryCard inmediatamente (< 100ms)
6. THE AddSales SHALL cachear los datos de `sale_category_tax` durante la sesión para optimizar el rendimiento
7. IF una categoría no tiene impuestos asociados en `sale_category_tax`, THE AddSales SHALL registrar advertencia en consola y continuar sin impuestos para esa categoría

### Requirement 4: Validación de Fecha Habilitada

**User Story:** As a administrador del sistema, I want que las ventas solo se puedan registrar en fechas habilitadas, so that I can mantener el control del cierre contable.

#### Acceptance Criteria

1. WHEN el usuario intenta registrar ventas, THE AddSales SHALL verificar si la fecha está habilitada consultando `monthly_module_lock`
2. IF la fecha no está habilitada, THEN THE AddSales SHALL mostrar un mensaje de error y deshabilitar el botón de guardar
3. IF la fecha está habilitada, THEN THE AddSales SHALL permitir el registro de ventas normalmente
4. THE AddSales SHALL mostrar visualmente el estado de la fecha (habilitada/bloqueada)

### Requirement 5: Prevención de Duplicados

**User Story:** As a usuario de contabilidad, I want que el sistema prevenga registros duplicados, so that I can evitar errores de doble captura.

#### Acceptance Criteria

1. WHEN el usuario intenta guardar ventas, THE AddSales SHALL verificar si ya existe un registro para la misma fecha y UDN en `daily_closure`
2. IF existe un registro previo, THEN THE AddSales SHALL cargar los datos existentes para edición en lugar de crear uno nuevo
3. IF no existe registro previo, THEN THE AddSales SHALL crear un nuevo registro en `daily_closure`
4. THE AddSales SHALL mostrar el folio del `daily_closure` actual en la interfaz

### Requirement 6: Persistencia de Ventas del Día

**User Story:** As a usuario de contabilidad, I want guardar las ventas del día, so that I can registrar la información en la base de datos.

#### Acceptance Criteria

1. WHEN el usuario hace clic en el botón "Guardar" de la tarjeta de Ventas, THE AddSales SHALL validar que todos los campos requeridos estén completos
2. THE AddSales SHALL insertar o actualizar registros en la tabla `detail_sale_category` con los campos: sale, net_sale, discount, courtesy, daily_closure_id, sale_category_id
3. THE AddSales SHALL insertar o actualizar registros en la tabla `detail_sale_category_tax` con los campos: sale_tax, discount_tax, courtesy_tax, detail_sale_category_id, sale_category_tax_id
4. IF la operación es exitosa, THEN THE AddSales SHALL mostrar un mensaje de confirmación
5. IF la operación falla, THEN THE AddSales SHALL mostrar un mensaje de error descriptivo

### Requirement 7: Persistencia de Formas de Pago

**User Story:** As a usuario de contabilidad, I want guardar las formas de pago, so that I can registrar cómo se recibió el dinero.

#### Acceptance Criteria

1. WHEN el usuario hace clic en el botón "Guardar" de la tarjeta de Formas de Pago, THE AddSales SHALL validar que todos los campos requeridos estén completos
2. THE AddSales SHALL insertar o actualizar registros en la tabla `detail_cash_concept` con los campos: daily_closure_id, cash_concept_id, amount
3. THE AddSales SHALL insertar o actualizar registros en la tabla `detail_bank_account` con los campos: daily_closure_id, bank_account_id, amount
4. THE AddSales SHALL insertar o actualizar registros en la tabla `detail_foreing_currency` con los campos: foreing_currency_id, exchange_rate, amount, amount_mxn, daily_closure_id
5. IF la operación es exitosa, THEN THE AddSales SHALL mostrar un mensaje de confirmación
6. IF la operación falla, THEN THE AddSales SHALL mostrar un mensaje de error descriptivo

### Requirement 8: Cálculo de Diferencia

**User Story:** As a usuario de contabilidad, I want ver la diferencia entre ventas y pagos, so that I can identificar discrepancias rápidamente.

#### Acceptance Criteria

1. THE AddSales SHALL calcular la diferencia como: Total de Venta - Total Pagado
2. WHEN la diferencia es positiva, THE AddSales SHALL mostrar el valor en color verde
3. WHEN la diferencia es negativa, THE AddSales SHALL mostrar el valor en color rojo
4. WHEN la diferencia es cero, THE AddSales SHALL mostrar el valor en color neutro
5. THE AddSales SHALL actualizar la diferencia en tiempo real cuando se modifique cualquier valor

### Requirement 9: Carga de Datos Existentes

**User Story:** As a usuario de contabilidad, I want que se carguen los datos existentes al abrir el módulo, so that I can ver y editar información previamente registrada.

#### Acceptance Criteria

1. WHEN el usuario abre el módulo de Agregar Ventas, THE AddSales SHALL consultar si existe un `daily_closure` para la fecha y UDN seleccionadas
2. IF existe un registro, THEN THE AddSales SHALL cargar los datos de `detail_sale_category` en los campos de ventas
3. IF existe un registro, THEN THE AddSales SHALL cargar los datos de `detail_sale_category_tax` para mostrar los impuestos
4. IF existe un registro, THEN THE AddSales SHALL cargar los datos de `detail_cash_concept`, `detail_bank_account` y `detail_foreing_currency` en los campos de formas de pago
5. IF no existe registro, THEN THE AddSales SHALL mostrar todos los campos vacíos con valor cero

### Requirement 10: Interfaz Responsiva

**User Story:** As a usuario, I want que la interfaz sea responsiva, so that I can usar el módulo en diferentes dispositivos.

#### Acceptance Criteria

1. THE AddSales SHALL mostrar las dos tarjetas (Ventas y Pagos) lado a lado en pantallas grandes
2. WHEN la pantalla es menor a 768px, THE AddSales SHALL apilar las tarjetas verticalmente
3. THE AddSales SHALL usar TailwindCSS para los estilos siguiendo el patrón del framework CoffeeSoft
4. THE AddSales SHALL mantener la legibilidad de los campos numéricos en todos los tamaños de pantalla
