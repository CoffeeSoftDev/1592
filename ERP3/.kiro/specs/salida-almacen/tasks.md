# Tasks Document - Salida de Almacén

## Task 1: Create Model (mdl-salida-almacen.php) ✅ COMPLETED

### Description
Crear el modelo PHP con todos los métodos de acceso a datos para el módulo de salidas de almacén.

### Requirements Addressed
- REQ-1, REQ-2, REQ-3, REQ-4, REQ-5, REQ-7, REQ-8, REQ-10, REQ-11

### Acceptance Criteria
- [x] Archivo creado en `finanzas/consulta/mdl/mdl-salida-almacen.php`
- [x] Clase `mdl` extiende `CRUD`
- [x] Propiedad `$bd` configurada como `"rfwsmqex_gvsl_finanzas3."`
- [x] Método `listWarehouseOutputs()` implementado con filtros por fecha y UDN
- [x] Método `getWarehouseOutputById()` implementado
- [x] Método `createWarehouseOutput()` implementado
- [x] Método `updateWarehouseOutput()` implementado
- [x] Método `deleteWarehouseOutputById()` implementado (soft delete)
- [x] Método `getWarehouseOutputCounts()` implementado
- [x] Método `lsSubaccounts()` filtra por cuenta mayor "Almacén"
- [x] Método `lsUDN()` implementado
- [x] Método `getDailyClosureByDate()` implementado
- [x] Método `createDailyClosure()` implementado
- [x] Método `getInitialBalance()` implementado
- [x] Método `getEntriesByDateRange()` implementado
- [x] Método `getOutputsByDateRange()` implementado
- [x] Método `listSubaccountsWithMovements()` implementado
- [x] Método `getEntriesBySubaccountAndDate()` implementado
- [x] Método `getOutputsBySubaccountAndDate()` implementado
- [x] Método `createFile()` implementado
- [x] Todos los métodos usan `_Read()` para consultas SELECT
- [x] No se usa try-catch

### Additional Methods Implemented
- `getInitialBalanceBySubaccount()` - Saldo inicial por subcuenta específica
- `getEntriesBySubaccountDateRange()` - Entradas por subcuenta en rango de fechas
- `getOutputsBySubaccountDateRange()` - Salidas por subcuenta en rango de fechas
- `getPurchaseById()` - Obtener detalle de compra (entrada)

---

## Task 2: Create Controller (ctrl-salida-almacen.php) ✅ COMPLETED

### Description
Crear el controlador PHP con la lógica de negocio para el módulo de salidas de almacén.

### Requirements Addressed
- REQ-1, REQ-2, REQ-3, REQ-4, REQ-5, REQ-6, REQ-7, REQ-8, REQ-9, REQ-10, REQ-11

### Acceptance Criteria
- [x] Archivo creado en `finanzas/consulta/ctrl/ctrl-salida-almacen.php`
- [x] Clase `ctrl` extiende `mdl`
- [x] Método `init()` retorna subaccounts, udn, dailyClosure
- [x] Método `ls()` lista salidas con formato de tabla
- [x] Método `showOutput()` retorna totales para KPIs
- [x] Método `addOutput()` crea nueva salida con validaciones
- [x] Método `editOutput()` actualiza salida existente
- [x] Método `deleteOutput()` ejecuta soft delete
- [x] Método `getOutput()` obtiene salida por ID
- [x] Método `lsConsolidated()` genera tabla concentrado
- [x] Método `showConsolidatedKPIs()` retorna 4 KPIs
- [x] Método `uploadWarehouseFiles()` maneja subida de archivos
- [x] Método `getEntryDetail()` obtiene detalle de compra
- [x] Funciones auxiliares: `actionButtons()`, `renderSubaccount()`
- [x] No se usa `??` ni `isset()` con `$_POST`
- [x] No se usa try-catch

### Additional Methods Implemented
- `getDailyClosure()` - Obtiene o crea cierre diario
- `getOrCreateDailyClosure()` - Método privado para gestión de cierre diario
- `getDateRange()` - Genera rango de fechas
- `formatDateColumn()` - Formatea columnas de fecha para concentrado

---

## Task 3: Create Frontend (salida-almacen.js) ✅ COMPLETED

### Description
Crear el archivo JavaScript con las clases WarehouseOutput y Consolidated para la interfaz de usuario.

### Requirements Addressed
- REQ-1, REQ-2, REQ-3, REQ-4, REQ-5, REQ-6, REQ-7, REQ-8, REQ-9, REQ-10, REQ-11

### Acceptance Criteria
- [x] Archivo creado en `finanzas/consulta/js/salida-almacen.js`
- [x] Clase `WarehouseOutput` extiende `Templates`
- [x] Clase `Consolidated` extiende `Templates`
- [x] Variables globales: `api_almacen`, `warehouseOutput`, `consolidated`, `subaccounts`, `daily_closure_id`
- [x] Método `render()` ejecuta layout, showCards, filterBar, lsOutputs
- [x] Método `layout()` usa `createLayout()` con estructura correcta
- [x] Método `filterBar()` incluye toggle, upload, nuevo
- [x] Método `showCards()` obtiene y muestra KPIs
- [x] Método `renderInfoCards()` renderiza InfoCard con total
- [x] Método `lsOutputs()` usa `createTable()` con tema corporativo
- [x] Método `addOutput()` usa `createModalForm()` con jsonOutput
- [x] Método `editOutput()` usa `createModalForm()` con autofill
- [x] Método `deleteOutput()` usa `swalQuestion()`
- [x] Método `viewDetail()` muestra modal con descripción
- [x] Método `uploadFiles()` maneja subida múltiple
- [x] Método `toggleView()` cambia entre vistas
- [x] Clase Consolidated con métodos: render, layout, filterBar, showKPIs, lsConsolidated
- [x] Método `lsConsolidated()` usa `createCoffeTable2()` con folding
- [x] Método `exportToExcel()` exporta tabla

---

## Task 4: Create View (salida-almacen.php) ✅ COMPLETED

### Description
Crear la vista PHP principal que carga el módulo de salidas de almacén.

### Requirements Addressed
- REQ-9 (Control de permisos)

### Acceptance Criteria
- [x] Archivo creado en `finanzas/consulta/salida-almacen.php`
- [x] Incluye layout/head.php y layout/core-libraries.php
- [x] Incluye navbar.php
- [x] Incluye coffeSoft.js y plugins.js
- [x] Contenedor `<div id="root"></div>` con `<div id="container-almacen"></div>`
- [x] Script `js/salida-almacen.js` incluido
- [x] Lucide icons inicializados

---

## Task 5: Integration Testing ⏳ PENDING

### Description
Verificar que todas las funcionalidades del módulo funcionan correctamente.

### Requirements Addressed
- Todos los requisitos

### Acceptance Criteria
- [ ] CRUD de salidas funciona correctamente
- [ ] KPIs se actualizan al agregar/editar/eliminar
- [ ] Toggle entre CRUD y Concentrado funciona
- [ ] Concentrado muestra datos correctos
- [ ] 4 KPIs del concentrado calculan correctamente
- [ ] Subida de archivos funciona
- [ ] Modales de detalle muestran información correcta
- [ ] Permisos por nivel de usuario funcionan
- [ ] Filtros de fecha funcionan correctamente
- [ ] Exportación a Excel funciona

### Notes
Este task requiere pruebas manuales en el navegador para verificar la integración completa.

---

## Implementation Order

1. **Task 1**: Model (mdl-salida-almacen.php) - Base de datos
2. **Task 2**: Controller (ctrl-salida-almacen.php) - Lógica de negocio
3. **Task 3**: Frontend (salida-almacen.js) - Interfaz de usuario
4. **Task 4**: View (salida-almacen.php) - Vista principal
5. **Task 5**: Integration Testing - Verificación final

## Notes

- Seguir el pivote de compras (PIVOTE-COMPRAS.md) como referencia
- Usar la base de datos `rfwsmqex_gvsl_finanzas3`
- Las entradas de almacén provienen de la tabla `purchase` donde `gl_account.name = 'Almacén'`
- Las salidas de almacén se registran en la tabla `warehouse_output`
- El saldo inicial se calcula como entradas - salidas hasta el día anterior
