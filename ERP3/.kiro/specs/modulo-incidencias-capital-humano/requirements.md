# Especificación: Módulo de Incidencias - Capital Humano

## 📋 Información General

**Módulo:** Capital Humano - Incidencias  
**Archivos principales:**
- `capital-humano/src/js/_Incidencias.js`
- `capital-humano/src/js/_CH.js`
- `capital-humano/src/js/incidencias.js`
- `src/js/complementos.js`

**Estado:** Documentación de arquitectura existente  
**Fecha:** 2025-01-08

---

## 🎯 Objetivo

Documentar la arquitectura actual del módulo de Incidencias, específicamente la implementación de DataTable a través de una cadena de plugins personalizados de jQuery, para establecer las bases de futuras mejoras y mantenimiento.

---

## 🏗️ Arquitectura Actual

### Cadena de Ejecución de DataTable

El módulo implementa DataTable de forma indirecta a través de una arquitectura de plugins encadenados:

```
tbIncidencias() 
  → this.table.fixed_inc(2)
    → table_format()
      → $(this).DataTable(json_data)
```

### Componentes Clave

#### 1. **_Incidencias.js** (Clase Principal)
- **Método:** `tbIncidencias()`
- **Responsabilidad:** Genera la tabla de incidencias usando `create_table()` del framework CoffeeSoft
- **Configuración:**
  ```javascript
  this.create_table({
      parent: "container-incidencias",
      data: { opc: "tbIncidencias" },
      conf: { datatable: false, pag: 15 },
      attr: {
          id: "tbIncidencias",
          theme: "light",
          center: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]
      }
  });
  ```
- **Post-procesamiento:** Aplica `this.table.fixed_inc(2)` después de crear la tabla

#### 2. **incidencias.js** (Plugin fixed_inc)
- **Plugin:** `$.fn.fixed_inc()`
- **Parámetros:** `left` (número de columnas fijas a la izquierda)
- **Responsabilidad:** Configura columnas fijas y llama a `table_format()`
- **Configuración DataTable:**
  ```javascript
  {
      fixedColumns: { left: left },
      keys: true,
      scrollY: 400,
      scrollX: true,
      scrollCollapse: true,
      paging: false,
      ordering: true,
      searching: true,
      info: false
  }
  ```

#### 3. **complementos.js** (Plugin table_format)
- **Plugin:** `$.fn.table_format()`
- **Responsabilidad:** Inicializa DataTable con configuración personalizada
- **Características:**
  - Soporte para múltiples idiomas (español por defecto)
  - Configuración de búsqueda, ordenamiento y paginación
  - Manejo de columnas fijas
  - Integración con FixedColumns plugin

#### 4. **_CH.js** (Utilidades)
- **Método:** `getValueCell()`
- **Responsabilidad:** Buscar valores en celdas usando DataTable API
- **Uso:** Obtiene valores de celdas específicas por índice de columna

---

## 📊 Características Implementadas

### ✅ Funcionalidades Actuales

1. **Columnas Fijas**
   - Las primeras 2 columnas ("Departamento" y "Colaborador") permanecen fijas al hacer scroll horizontal
   - Implementado mediante FixedColumns plugin de DataTable

2. **Navegación por Teclado**
   - Habilitada mediante `keys: true`
   - Permite navegar entre celdas con flechas del teclado

3. **Scroll Bidireccional**
   - Scroll vertical: 400px de altura
   - Scroll horizontal: habilitado
   - Scroll collapse: activado

4. **Búsqueda y Ordenamiento**
   - Búsqueda global habilitada
   - Ordenamiento por columnas habilitado
   - Paginación deshabilitada (vista completa)

5. **Centrado de Columnas**
   - Todas las columnas (0-31) están centradas
   - Configurado en el atributo `center` del método `create_table()`

---

## 🔧 Configuración Técnica

### DataTable Settings

```javascript
{
    fixedColumns: { left: 2 },           // Columnas fijas
    keys: true,                          // Navegación por teclado
    scrollY: 400,                        // Altura de scroll vertical
    scrollX: true,                       // Scroll horizontal
    scrollCollapse: true,                // Colapsar scroll
    paging: false,                       // Sin paginación
    ordering: true,                      // Ordenamiento habilitado
    searching: true,                     // Búsqueda habilitada
    info: false,                         // Sin información de registros
    language: {                          // Idioma español
        url: "//cdn.datatables.net/plug-ins/1.10.15/i18n/Spanish.json"
    }
}
```

### Plugins Requeridos

- **DataTables Core:** Funcionalidad base de tablas
- **FixedColumns:** Para columnas fijas
- **KeyTable:** Para navegación por teclado

---

## 📝 User Stories

### US-1: Visualización de Incidencias
**Como** usuario del módulo de Capital Humano  
**Quiero** ver una tabla de incidencias con columnas fijas  
**Para** poder revisar la información sin perder de vista el departamento y colaborador

**Criterios de Aceptación:**
- ✅ La tabla muestra todas las incidencias del período seleccionado
- ✅ Las columnas "Departamento" y "Colaborador" permanecen fijas al hacer scroll horizontal
- ✅ Todas las columnas están centradas
- ✅ El scroll vertical está limitado a 400px de altura

### US-2: Navegación Eficiente
**Como** usuario del módulo  
**Quiero** navegar por la tabla usando el teclado  
**Para** agilizar la revisión de datos

**Criterios de Aceptación:**
- ✅ Puedo moverme entre celdas usando las flechas del teclado
- ✅ La navegación respeta las columnas fijas
- ✅ El foco visual es claro y visible

### US-3: Búsqueda y Filtrado
**Como** usuario del módulo  
**Quiero** buscar y ordenar incidencias  
**Para** encontrar información específica rápidamente

**Criterios de Aceptación:**
- ✅ Puedo buscar texto en cualquier columna
- ✅ Puedo ordenar por cualquier columna haciendo clic en el encabezado
- ✅ La búsqueda es instantánea y reactiva

---

## 🚀 Mejoras Propuestas (Futuro)

### Prioridad Alta

1. **Optimización de Rendimiento**
   - Implementar paginación para tablas con muchos registros
   - Considerar virtualización de filas para mejorar performance
   - Lazy loading de datos

2. **Exportación de Datos**
   - Agregar botón para exportar a Excel
   - Exportar a PDF con formato
   - Copiar al portapapeles

### Prioridad Media

3. **Filtros Avanzados**
   - Filtros por columna individual
   - Filtros por rango de fechas
   - Filtros por departamento/colaborador

4. **Personalización de Vista**
   - Permitir al usuario seleccionar qué columnas mostrar
   - Guardar preferencias de usuario
   - Ajustar ancho de columnas

### Prioridad Baja

5. **Mejoras Visuales**
   - Indicadores visuales para diferentes tipos de incidencias
   - Tooltips con información adicional
   - Resaltado de filas al pasar el mouse

---

## 🔍 Consideraciones Técnicas

### Dependencias
- jQuery 3.x
- DataTables 1.10.x
- FixedColumns plugin
- KeyTable plugin
- CoffeeSoft Framework

### Compatibilidad
- Navegadores modernos (Chrome, Firefox, Safari, Edge)
- Responsive design pendiente de implementar

### Performance
- Tabla actual maneja bien hasta ~500 registros
- Para más registros, considerar paginación o virtualización

---

## 📚 Referencias

### Archivos Relacionados
- `capital-humano/incidencias.php` - Vista principal
- `capital-humano/ctrl/ctrl-incidencias.php` - Controlador
- `capital-humano/mdl/mdl-incidencias.php` - Modelo

### Documentación Externa
- [DataTables Documentation](https://datatables.net/)
- [FixedColumns Plugin](https://datatables.net/extensions/fixedcolumns/)
- [KeyTable Plugin](https://datatables.net/extensions/keytable/)

---

## ✅ Checklist de Implementación Actual

- [x] Tabla de incidencias funcional
- [x] Columnas fijas implementadas
- [x] Navegación por teclado habilitada
- [x] Búsqueda global funcional
- [x] Ordenamiento por columnas
- [x] Scroll bidireccional
- [x] Centrado de columnas
- [ ] Exportación de datos
- [ ] Filtros avanzados
- [ ] Personalización de vista
- [ ] Responsive design
- [ ] Optimización de performance

---

## 🎨 Notas de Diseño

### Arquitectura de Plugins
La implementación actual usa una arquitectura de plugins encadenados que proporciona:
- **Modularidad:** Cada plugin tiene una responsabilidad específica
- **Reutilización:** Los plugins pueden usarse en otras tablas del sistema
- **Mantenibilidad:** Cambios en un plugin no afectan a otros

### Trade-offs
- **Pro:** Flexibilidad y reutilización de código
- **Con:** Cadena de ejecución puede ser difícil de seguir para nuevos desarrolladores
- **Con:** Debugging más complejo debido a múltiples capas

---

## 📌 Próximos Pasos

1. **Documentación de API**
   - Documentar todos los métodos públicos de `_Incidencias.js`
   - Crear guía de uso de plugins personalizados

2. **Testing**
   - Implementar tests unitarios para plugins
   - Tests de integración para la cadena completa

3. **Refactoring (Opcional)**
   - Evaluar si simplificar la cadena de plugins
   - Considerar migración a componentes más modernos

---

**Última actualización:** 2025-01-08  
**Responsable:** CoffeeIA ☕  
**Estado:** Documentación completada
