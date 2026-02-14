# Sistema de Desglose Detallado - Carátula Financiera

## 📋 Información del Proyecto

**Módulo:** Carátula Financiera (Contabilidad)  
**Tipo:** Mejora de funcionalidad existente  
**Estado:** ✅ Implementado  
**Fecha:** 2025-02-09  
**Versión:** 1.0

---

## 🎯 Objetivo

Implementar un sistema de desglose detallado que permita a los usuarios visualizar el origen exacto de cada concepto en el Fondo de Caja de la Carátula Financiera mediante botones expandibles y modales informativos.

---

## 👥 Usuarios Objetivo

- **Contadores:** Necesitan validar y auditar los montos mostrados
- **Gerentes Financieros:** Requieren transparencia en los movimientos de caja
- **Auditores:** Necesitan trazabilidad completa de cada concepto

---

## 📖 Historias de Usuario

### Historia 1: Visualizar Desglose de Gastos
**Como** contador  
**Quiero** ver el detalle de los gastos del fondo de caja  
**Para** validar que los montos sean correctos y estén bien clasificados

**Criterios de Aceptación:**
- ✅ Debe haber un botón de información (ℹ️) junto al concepto "Gastos"
- ✅ Al hacer clic, debe abrir un modal con tabla detallada
- ✅ La tabla debe mostrar: Fecha, Proveedor, Cuenta, Subcuenta, Descripción, Subtotal, IVA, Total
- ✅ Debe mostrar un total general en el footer
- ✅ Los montos deben estar formateados como moneda

### Historia 2: Visualizar Desglose de Pagos a Proveedores
**Como** gerente financiero  
**Quiero** ver el detalle de los pagos realizados a proveedores  
**Para** verificar que los pagos estén correctamente registrados

**Criterios de Aceptación:**
- ✅ Debe haber un botón de información junto al concepto "Pago Proveedor"
- ✅ Al hacer clic, debe abrir un modal con tabla detallada
- ✅ La tabla debe mostrar: Fecha, Proveedor, Cuenta, Subcuenta, Descripción, Subtotal, IVA, Total
- ✅ Debe filtrar solo pagos a proveedores (supplier_id IS NOT NULL)
- ✅ Debe mostrar un total general en el footer

### Historia 3: Visualizar Desglose de Reembolsos
**Como** contador  
**Quiero** ver el detalle de los reembolsos registrados  
**Para** validar que los reembolsos estén correctamente aplicados

**Criterios de Aceptación:**
- ✅ Debe haber un botón de información junto al concepto "Reembolso"
- ✅ Al hacer clic, debe abrir un modal con tabla detallada
- ✅ La tabla debe mostrar: Fecha, Monto, Observaciones
- ✅ Debe mostrar un total general en el footer
- ✅ Solo debe mostrar reembolsos activos (Stado = 1)

### Historia 4: Visualizar Desglose de Anticipos
**Como** gerente financiero  
**Quiero** ver el detalle de los anticipos otorgados a empleados  
**Para** verificar que los anticipos estén correctamente registrados

**Criterios de Aceptación:**
- ✅ Debe haber un botón de información junto al concepto "Anticipos"
- ✅ Al hacer clic, debe abrir un modal con tabla detallada
- ✅ La tabla debe mostrar: Fecha, Empleado, Monto, Observaciones
- ✅ Debe mostrar el nombre completo del empleado (Nombre + Apellido)
- ✅ Debe mostrar un total general en el footer

### Historia 5: Interfaz Consistente
**Como** usuario del sistema  
**Quiero** que todos los desgloses tengan una interfaz consistente  
**Para** facilitar la navegación y comprensión de la información

**Criterios de Aceptación:**
- ✅ Todos los botones de información deben tener el mismo estilo
- ✅ Todos los modales deben tener el mismo formato
- ✅ Las tablas deben ser responsive con scroll horizontal
- ✅ Los headers deben ser dinámicos según los datos
- ✅ Debe mostrar mensaje cuando no hay datos disponibles

---

## 🔧 Requisitos Técnicos

### Backend (PHP)

#### Controlador (`ctrl-caratula-venta.php`)
1. **Método `formatDebugInfo()`**
   - Debe aceptar parámetro `$detailId` para identificar la sección
   - Debe generar botón de información cuando `hasDetail = true`
   - El botón debe ejecutar `caratula.showDetail(detailId, label)`

2. **Método `getFondoCaja()`**
   - Debe marcar conceptos con `hasDetail: true` cuando tengan desglose disponible
   - Debe pasar `detailId` a cada llamada de `formatDebugInfo()`

3. **Método `getDetailFondoCaja()` (NUEVO)**
   - Debe recibir: `fi`, `ff`, `udn`, `section`, `concept`
   - Debe usar switch para determinar qué consulta ejecutar
   - Debe retornar: `status`, `data`, `title`

#### Modelo (`mdl-caratula-venta.php`)
1. **Método `getDetalleGastosFondo()`**
   - Consulta: Compras con `payment_type_id = 3`
   - Retorna: fecha, proveedor, cuenta, subcuenta, descripción, subtotal, tax, total

2. **Método `getDetallePagoProveedor()`**
   - Consulta: Compras con `payment_type_id = 3` y `supplier_id IS NOT NULL`
   - Retorna: fecha, proveedor, cuenta, subcuenta, descripción, subtotal, tax, total

3. **Método `getDetalleReembolso()`**
   - Consulta: Reembolsos con `Stado = 1`
   - Retorna: fecha, monto, observaciones

4. **Método `getDetalleAnticipos()`**
   - Consulta: Anticipos con empleado
   - Retorna: fecha, empleado, monto, observaciones

### Frontend (JavaScript)

#### Archivo `caratula-venta.js`
1. **Método `showDetail(section, concept)`**
   - Debe obtener filtros actuales (fecha, UDN)
   - Debe realizar petición AJAX a `getDetailFondoCaja`
   - Debe llamar a `renderDetailModal()` con la respuesta

2. **Método `renderDetailModal(data, title)`**
   - Debe generar headers dinámicos según datos
   - Debe formatear montos con `formatPrice()`
   - Debe calcular y mostrar total en footer
   - Debe mostrar mensaje cuando no hay datos
   - Debe usar `bootbox.dialog()` para el modal

---

## 📊 Conceptos con Desglose

| Sección | Concepto | Método Modelo | Campos Mostrados |
|---------|----------|---------------|------------------|
| Saldo Inicial | Gastos | `getDetalleGastosFondo()` | Fecha, Proveedor, Cuenta, Subcuenta, Descripción, Subtotal, IVA, Total |
| Saldo Inicial | Pago Proveedor | `getDetallePagoProveedor()` | Fecha, Proveedor, Cuenta, Subcuenta, Descripción, Subtotal, IVA, Total |
| Ingreso | Reembolso | `getDetalleReembolso()` | Fecha, Monto, Observaciones |
| Egreso | Gastos Fondo | `getDetalleGastosFondo()` | Fecha, Proveedor, Cuenta, Subcuenta, Descripción, Subtotal, IVA, Total |
| Egreso | Anticipos | `getDetalleAnticipos()` | Fecha, Empleado, Monto, Observaciones |
| Egreso | Pago Proveedor | `getDetallePagoProveedor()` | Fecha, Proveedor, Cuenta, Subcuenta, Descripción, Subtotal, IVA, Total |

---

## 🎨 Especificaciones de Diseño

### Botón de Información
- **Icono:** `icon-info-circled` (Fontello)
- **Color:** Azul (`text-blue-500`)
- **Hover:** Azul oscuro (`hover:text-blue-700`)
- **Tamaño:** Extra pequeño (`text-[10px]`)
- **Posición:** Al lado derecho del concepto

### Modal de Detalle
- **Título:** `📄 Detalle: [Nombre del Concepto]`
- **Tamaño:** Large (`size: 'large'`)
- **Tabla:**
  - Headers dinámicos según datos
  - Hover en filas (`hover:bg-gray-50`)
  - Scroll horizontal para muchas columnas
  - Footer con total general
- **Botones:**
  - Cerrar (btn-primary)

---

## 🔄 Flujo de Datos

```
1. Usuario hace clic en botón ℹ️
   ↓
2. Frontend ejecuta showDetail(section, concept)
   ↓
3. Petición AJAX a getDetailFondoCaja
   ↓
4. Controlador determina qué consulta ejecutar
   ↓
5. Modelo ejecuta consulta SQL específica
   ↓
6. Respuesta JSON con datos
   ↓
7. Frontend renderiza modal con tabla
   ↓
8. Usuario visualiza desglose detallado
```

---

## ✅ Criterios de Aceptación Generales

### Funcionalidad
- ✅ Todos los conceptos marcados deben tener botón de información
- ✅ Los botones deben ser visibles y accesibles
- ✅ Los modales deben abrirse correctamente
- ✅ Los datos deben corresponder al rango de fechas seleccionado
- ✅ Los datos deben corresponder a la UDN seleccionada

### Rendimiento
- ✅ Las consultas deben ejecutarse en menos de 2 segundos
- ✅ El modal debe abrirse sin retraso perceptible
- ✅ La tabla debe renderizarse correctamente con hasta 100 registros

### Usabilidad
- ✅ Los botones deben tener hover effect
- ✅ Los modales deben ser responsive
- ✅ Las tablas deben tener scroll horizontal si es necesario
- ✅ Los montos deben estar formateados correctamente
- ✅ Debe mostrar mensaje claro cuando no hay datos

### Seguridad
- ✅ Las consultas deben usar prepared statements
- ✅ Los datos deben filtrarse por UDN del usuario
- ✅ No debe exponer información sensible en errores

---

## 🚀 Beneficios

1. **Transparencia Total:** Los usuarios pueden ver el origen exacto de cada monto
2. **Trazabilidad:** Cada concepto tiene su desglose detallado para auditoría
3. **Confianza:** Los usuarios pueden validar los datos por sí mismos
4. **Eficiencia:** Reduce el tiempo de validación y auditoría
5. **Escalabilidad:** Fácil agregar más conceptos con desglose
6. **UX Mejorada:** Botones discretos que no saturan la interfaz

---

## 📝 Notas de Implementación

### Framework y Tecnologías
- **Backend:** PHP 7.4+
- **Base de datos:** MySQL
- **Frontend:** jQuery + TailwindCSS
- **Framework:** CoffeeSoft
- **Modales:** Bootbox
- **Iconos:** Fontello

### Convenciones
- Usar `_Read()` para todas las consultas SELECT
- NO usar try-catch en ningún archivo
- NO usar `??` ni `isset()` con variables `$_POST`
- Seguir nomenclatura: CTRL usa `show[Entidad]()`, MDL usa `get[Entidad]Counts()`
- Mantener código limpio sin comentarios innecesarios

---

## 🔮 Mejoras Futuras

### Fase 2 (Propuesta)
- [ ] Agregar desglose para más conceptos (Efectivo, Bancos, etc.)
- [ ] Implementar exportación a Excel del desglose
- [ ] Agregar filtros adicionales en el modal (por proveedor, cuenta, etc.)
- [ ] Implementar gráficas visuales del desglose
- [ ] Agregar comparativa con períodos anteriores

### Fase 3 (Propuesta)
- [ ] Implementar drill-down multinivel (desglose de desglose)
- [ ] Agregar notas y comentarios en el desglose
- [ ] Implementar alertas cuando los montos no cuadren
- [ ] Agregar historial de cambios en el desglose

---

## 📚 Referencias

- **Documentación Técnica:** `RESUMEN-DESGLOSE-CARATULA.md`
- **Archivos Modificados:**
  - `contabilidad2/ctrl/ctrl-caratula-venta.php`
  - `contabilidad2/mdl/mdl-caratula-venta.php`
  - `contabilidad2/js/caratula-venta.js`

---

## ✅ Estado del Proyecto

**Implementación:** ✅ Completada  
**Testing:** ✅ Validado  
**Documentación:** ✅ Completa  
**Despliegue:** ✅ En producción

---

**Versión:** 1.0  
**Última actualización:** 2025-02-09  
**Autor:** CoffeeIA ☕
