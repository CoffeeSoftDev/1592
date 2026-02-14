# Tasks Document - Módulo de Ingresos (Ventas)

## Task Group 1: Configuración Base del Módulo

### Task 1.1: Crear estructura de archivos del módulo
- [x] Crear archivo `finanzas/consulta_respaldo/ingresos.php` (vista principal)
- [x] Crear archivo `finanzas/consulta_respaldo/js/ingresos.js` (frontend)
- [x] Crear archivo `finanzas/consulta_respaldo/ctrl/ctrl-ingresos.php` (controlador)
- [x] Crear archivo `finanzas/consulta_respaldo/mdl/mdl-ingresos.php` (modelo)

**Acceptance Criteria Covered:** Estructura MVC del proyecto

---

## Task Group 2: Modelo (mdl-ingresos.php)

### Task 2.1: Implementar métodos de catálogos
- [x] Implementar `lsUDN()` - Lista de unidades de negocio
- [x] Implementar `lsSaleCategories($array)` - Categorías de venta por UDN
- [x] Implementar `lsCashConcepts($array)` - Conceptos de efectivo
- [x] Implementar `lsForeignCurrencies()` - Monedas extranjeras
- [x] Implementar `lsBankAccounts($array)` - Cuentas bancarias por UDN
- [x] Implementar `lsEmployees($array)` - Empleados por UDN

**Acceptance Criteria Covered:** REQ-2.5, REQ-2.6, REQ-7.2

### Task 2.2: Implementar métodos de Daily Closure
- [x] Implementar `getDailyClosureById($array)` - Obtener corte por ID
- [x] Implementar `getDailyClosureByDate($array)` - Obtener corte por fecha y UDN
- [x] Implementar `createDailyClosure($array)` - Crear nuevo corte
- [x] Implementar `updateDailyClosure($array)` - Actualizar corte existente
- [x] Implementar `getDailyClosureTotals($array)` - Obtener totales del día

**Acceptance Criteria Covered:** REQ-1.1, REQ-1.2, REQ-2.8, REQ-3.7

### Task 2.3: Implementar métodos de detalles de ventas
- [x] Implementar `createDetailSaleCategory($array)` - Crear detalle de categoría
- [x] Implementar `updateDetailSaleCategory($array)` - Actualizar detalle de categoría
- [x] Implementar `listDetailSaleCategory($array)` - Listar detalles por corte
- [x] Implementar `createDetailSaleCategoryTax($array)` - Crear detalle de impuestos
- [x] Implementar `updateDetailSaleCategoryTax($array)` - Actualizar detalle de impuestos

**Acceptance Criteria Covered:** REQ-2.2, REQ-2.3, REQ-2.4, REQ-3.3, REQ-3.4

### Task 2.4: Implementar métodos de formas de pago
- [x] Implementar `createDetailCashConcept($array)` - Crear detalle de efectivo
- [x] Implementar `updateDetailCashConcept($array)` - Actualizar detalle de efectivo
- [x] Implementar `listDetailCashConcept($array)` - Listar detalles de efectivo
- [x] Implementar `createDetailForeignCurrency($array)` - Crear detalle de moneda extranjera
- [x] Implementar `updateDetailForeignCurrency($array)` - Actualizar detalle de moneda
- [x] Implementar `listDetailForeignCurrency($array)` - Listar detalles de moneda
- [x] Implementar `createDetailBankAccount($array)` - Crear detalle de banco
- [x] Implementar `updateDetailBankAccount($array)` - Actualizar detalle de banco
- [x] Implementar `listDetailBankAccount($array)` - Listar detalles de banco

**Acceptance Criteria Covered:** REQ-2.5, REQ-2.6, REQ-2.7, REQ-3.5

### Task 2.5: Implementar métodos de archivos y validaciones
- [x] Implementar `createFile($array)` - Crear registro de archivo
- [x] Implementar `listFilesByDailyClosure($array)` - Listar archivos por corte
- [x] Implementar `getModuleLock($array)` - Obtener configuración de bloqueo

**Acceptance Criteria Covered:** REQ-5.4, REQ-9.1

---

## Task Group 3: Controlador (ctrl-ingresos.php)

### Task 3.1: Implementar método init()
- [x] Cargar catálogos de UDN, categorías, conceptos, monedas, bancos, empleados
- [x] Obtener configuración de permisos del usuario
- [x] Obtener configuración de horario de captura
- [x] Retornar JSON con todos los datos de inicialización

**Acceptance Criteria Covered:** REQ-8.1, REQ-8.2, REQ-8.3, REQ-8.4

### Task 3.2: Implementar método showDailyClosure()
- [x] Obtener datos del corte del día actual por UDN
- [x] Calcular totales de ventas, descuentos, impuestos
- [x] Calcular totales de formas de pago
- [x] Calcular diferencia
- [x] Retornar estructura para KPI cards y resumen

**Acceptance Criteria Covered:** REQ-1.1, REQ-1.2, REQ-1.3, REQ-1.4

### Task 3.3: Implementar método getDailyClosure()
- [x] Obtener corte por ID con todos sus detalles
- [x] Cargar detalles de categorías de venta
- [x] Cargar detalles de conceptos de efectivo
- [x] Cargar detalles de monedas extranjeras
- [x] Cargar detalles de cuentas bancarias
- [x] Retornar estructura completa para edición

**Acceptance Criteria Covered:** REQ-3.2

### Task 3.4: Implementar método addDailyClosure()
- [x] Validar horario de captura
- [x] Validar que no exista corte para la fecha
- [x] Crear registro principal en daily_closure
- [x] Crear detalles de categorías de venta
- [x] Crear detalles de impuestos
- [x] Crear detalles de conceptos de efectivo
- [x] Crear detalles de monedas extranjeras
- [x] Crear detalles de cuentas bancarias
- [x] Retornar status y mensaje

**Acceptance Criteria Covered:** REQ-2.1, REQ-2.8, REQ-2.9

### Task 3.5: Implementar método editDailyClosure()
- [x] Validar horario de captura
- [x] Actualizar registro principal en daily_closure
- [x] Actualizar detalles de categorías de venta
- [x] Actualizar detalles de impuestos
- [x] Actualizar detalles de conceptos de efectivo
- [x] Actualizar detalles de monedas extranjeras
- [x] Actualizar detalles de cuentas bancarias
- [x] Retornar status y mensaje

**Acceptance Criteria Covered:** REQ-3.6, REQ-3.7, REQ-3.8

### Task 3.6: Implementar método uploadFile()
- [x] Validar extensión de archivo permitida
- [x] Generar nombre único para el archivo
- [x] Mover archivo al directorio de uploads
- [x] Crear registro en tabla file
- [x] Retornar status y mensaje

**Acceptance Criteria Covered:** REQ-5.2, REQ-5.3, REQ-5.4, REQ-5.5, REQ-5.6

### Task 3.7: Implementar método validateSchedule()
- [ ] Consultar monthly_module_lock
- [ ] Comparar hora actual con rango permitido
- [ ] Retornar boolean de validación

**Acceptance Criteria Covered:** REQ-9.1, REQ-9.2, REQ-9.3

---

## Task Group 4: Frontend (ingresos.js)

### Task 4.1: Implementar clase App
- [x] Crear constructor con PROJECT_NAME
- [x] Implementar método render()
- [x] Implementar método layout() con primaryLayout y tabLayout
- [x] Implementar método filterBar() con selector de UDN y calendario

**Acceptance Criteria Covered:** REQ-10.1, REQ-10.2, REQ-10.3

### Task 4.2: Implementar clase Ingresos
- [x] Crear constructor heredando de Templates
- [x] Implementar método render()
- [x] Implementar método layoutIngresos() con estructura de dos columnas
- [x] Implementar método filterBarIngresos() con botones de acción

**Acceptance Criteria Covered:** REQ-1.5, REQ-2.1

### Task 4.3: Implementar método showResumen()
- [x] Crear KPI cards para Total de venta, Total pagado, Diferencia
- [x] Crear sección "Ventas del día" con tabla de categorías
- [x] Crear sección "Formas de ingreso" con tabla de pagos
- [x] Implementar actualización dinámica de datos

**Acceptance Criteria Covered:** REQ-1.1, REQ-1.2, REQ-1.3, REQ-1.4

### Task 4.4: Implementar método addDailyClosure()
- [x] Crear formulario con createForm() para ventas
- [x] Crear formulario con createForm() para formas de pago
- [x] Implementar selectores de turno y jefe de turno
- [x] Implementar campo de total de suites
- [x] Implementar botón de guardar con validación

**Acceptance Criteria Covered:** REQ-2.1, REQ-2.2, REQ-2.3, REQ-2.5, REQ-2.6, REQ-2.7, REQ-7.1, REQ-7.2, REQ-7.3

### Task 4.5: Implementar método editDailyClosure()
- [x] Cargar datos existentes con useFetch()
- [x] Precargar formulario con autofill
- [x] Implementar actualización de datos
- [x] Implementar botón de guardar cambios

**Acceptance Criteria Covered:** REQ-3.1, REQ-3.2, REQ-3.3, REQ-3.4, REQ-3.5

### Task 4.6: Implementar método calculateTotals()
- [x] Calcular Total de Ventas (suma de categorías)
- [x] Calcular Total Descuentos y Cortesías
- [x] Calcular IVA (8%)
- [x] Calcular Total de Venta
- [x] Calcular Total Efectivo
- [x] Calcular Total Bancos
- [x] Calcular Créditos a Clientes
- [x] Calcular Total Pagado
- [x] Calcular Diferencia
- [x] Actualizar campos en tiempo real

**Acceptance Criteria Covered:** REQ-4.1, REQ-4.2, REQ-4.3, REQ-4.4, REQ-4.5, REQ-4.6, REQ-4.7, REQ-4.8, REQ-4.9, REQ-4.10

### Task 4.7: Implementar método uploadFiles()
- [ ] Crear modal para selección de archivos
- [ ] Implementar validación de tipos de archivo
- [ ] Implementar subida múltiple de archivos
- [ ] Mostrar progreso y confirmación

**Acceptance Criteria Covered:** REQ-5.1, REQ-5.2, REQ-5.3, REQ-5.5

### Task 4.8: Implementar jsonFormVentas() y jsonFormPagos()
- [x] Definir estructura JSON para formulario de ventas
- [x] Definir estructura JSON para formulario de pagos
- [x] Incluir campos dinámicos para bancos según UDN

**Acceptance Criteria Covered:** REQ-2.2, REQ-2.3, REQ-2.5, REQ-2.6

---

## Task Group 5: Vista (ingresos.php)

### Task 5.1: Crear archivo de vista
- [x] Implementar validación de sesión
- [x] Incluir layouts (head, core-libraries, navbar)
- [x] Incluir CoffeeSoft Framework
- [x] Incluir script ingresos.js
- [x] Crear contenedor root

**Acceptance Criteria Covered:** Estructura base del módulo

---

## Task Group 6: Integración y Pruebas

### Task 6.1: Integración con Soft-Restaurant
- [ ] Implementar método importSoftRestaurant() en controlador
- [ ] Implementar llamada desde frontend
- [ ] Manejar errores de conexión

**Acceptance Criteria Covered:** REQ-6.1, REQ-6.2, REQ-6.3, REQ-6.4, REQ-6.5

### Task 6.2: Validación de permisos
- [ ] Implementar lógica de permisos por rol
- [ ] Ocultar/mostrar botones según permisos
- [ ] Validar permisos en backend

**Acceptance Criteria Covered:** REQ-8.1, REQ-8.2, REQ-8.3, REQ-8.4

### Task 6.3: Validación de horario
- [ ] Implementar consulta de monthly_module_lock
- [ ] Deshabilitar botones fuera de horario
- [ ] Mostrar indicador visual de bloqueo

**Acceptance Criteria Covered:** REQ-9.1, REQ-9.2, REQ-9.3, REQ-9.4

---

## Resumen de Archivos a Crear

| Archivo | Descripción | Task Group |
|---------|-------------|------------|
| `finanzas/consulta_respaldo/ingresos.php` | Vista principal | 5 |
| `finanzas/consulta_respaldo/js/ingresos.js` | Frontend JavaScript | 4 |
| `finanzas/consulta_respaldo/ctrl/ctrl-ingresos.php` | Controlador PHP | 3 |
| `finanzas/consulta_respaldo/mdl/mdl-ingresos.php` | Modelo PHP | 2 |

## Orden de Implementación Recomendado

1. **Modelo** (Task Group 2) - Base de datos y consultas
2. **Controlador** (Task Group 3) - Lógica de negocio
3. **Vista** (Task Group 5) - Estructura HTML
4. **Frontend** (Task Group 4) - Interfaz de usuario
5. **Integración** (Task Group 6) - Pruebas y ajustes finales
