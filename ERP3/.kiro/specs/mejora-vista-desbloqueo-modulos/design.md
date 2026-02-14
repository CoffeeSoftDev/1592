# Design Document

## Overview

Este diseño transforma la vista de "Desbloqueo de Secciones" de una tabla tradicional a una interfaz moderna con badges visuales de colores. La solución mantiene la arquitectura MVC existente de CoffeeSoft, modificando únicamente la capa de presentación (controlador PHP) para generar HTML enriquecido con badges, mientras el frontend (JavaScript) permanece sin cambios significativos.

**Enfoque principal:** Modificar el método `lsUnlocks()` del controlador para generar badges HTML con colores distintivos por tipo de módulo, agregando un filtro por UDN en el frontend.

## Architecture

### Componentes Afectados

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend (JavaScript)                     │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  class Modules extends Templates                       │ │
│  │  - filterBarDesbloqueo() [MODIFICAR: agregar filtro]  │ │
│  │  - lsUnlocks() [SIN CAMBIOS]                          │ │
│  │  - toggleLockStatus() [SIN CAMBIOS]                   │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              ↓ AJAX
┌─────────────────────────────────────────────────────────────┐
│              Backend (PHP - Controlador)                     │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  class ctrl extends mdl                                │ │
│  │  - lsUnlocks() [MODIFICAR: generar badges HTML]       │ │
│  │  - renderModuleBadges() [NUEVO: función auxiliar]     │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
                              ↓ SQL
┌─────────────────────────────────────────────────────────────┐
│                  Backend (PHP - Modelo)                      │
│  ┌────────────────────────────────────────────────────────┐ │
│  │  class mdl extends CRUD                                │ │
│  │  - listUnlocks() [SIN CAMBIOS]                        │ │
│  └────────────────────────────────────────────────────────┘ │
└─────────────────────────────────────────────────────────────┘
```

### Flujo de Datos

1. **Usuario selecciona UDN** → FilterBar envía petición AJAX con `udn_id`
2. **Controlador recibe petición** → Llama a `listUnlocks()` con filtro de UDN
3. **Modelo consulta BD** → Retorna registros con módulos concatenados
4. **Controlador procesa datos** → Genera badges HTML con colores por módulo
5. **Frontend recibe JSON** → DataTables renderiza tabla con badges
6. **Usuario ve badges** → Identifica visualmente módulos por color

## Components and Interfaces

### 1. Función Auxiliar: `renderModuleBadges()`

**Ubicación:** `finanzas/administrador/ctrl/ctrl-admin.php`

**Propósito:** Generar HTML de badges con colores distintivos para cada módulo.

**Firma:**
```php
function renderModuleBadges($modulesString) {
    // Retorna: string HTML con badges
}
```

**Entrada:**
- `$modulesString` (string): Módulos separados por comas (ej: "Ventas, Clientes, Almacén")

**Salida:**
- HTML string con badges estilizados usando TailwindCSS

**Mapeo de Colores:**
```php
$colorMap = [
    'Ventas'   => 'bg-green-100 text-green-800 border-green-300',
    'Clientes' => 'bg-orange-100 text-orange-800 border-orange-300',
    'Almacén'  => 'bg-blue-100 text-blue-800 border-blue-300',
    'Compras'  => 'bg-red-100 text-red-800 border-red-300'
];
```

**Estructura HTML del Badge:**
```html
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {color-classes} mr-1 mb-1">
    {nombre_modulo}
</span>
```

### 2. Método Modificado: `lsUnlocks()`

**Cambios:**
1. Recibir parámetro opcional `udn_id` desde `$_POST`
2. Pasar `udn_id` al modelo para filtrar registros
3. Procesar campo `sections` con `renderModuleBadges()`
4. Retornar HTML enriquecido en lugar de texto plano

**Estructura de Retorno:**
```php
[
    'row' => [
        [
            'id' => 1,
            'UDN' => 'Quinta Tabachines',
            'Fecha solicitada' => '01 de diciembre del 2025',
            'Módulos' => [
                'html' => '<span class="...">Ventas</span><span class="...">Clientes</span>',
                'class' => 'py-2'
            ],
            'Motivo' => 'Error de captura',
            'Bloquear' => [
                'html' => '<i class="icon-lock-open text-green-500..."></i>',
                'class' => 'text-center'
            ]
        ]
    ],
    'ls' => [...] // datos originales
]
```

### 3. FilterBar Modificado

**Cambios en `filterBarDesbloqueo()`:**

Agregar select de UDN antes de los botones existentes:

```javascript
{
    opc: "select",
    id: "filter_udn",
    lbl: "Filtrar por UDN:",
    class: "col-12 col-md-3",
    data: [
        { id: 'all', valor: 'Todas las UDN' },
        ...lsudn
    ],
    text: "valor",
    value: "id",
    onchange: "modules.lsUnlocks()"
}
```

### 4. Método `lsUnlocks()` Frontend

**Modificación mínima:**

Agregar parámetro `filter_udn` al data de la petición:

```javascript
lsUnlocks() {
    const udnFilter = $('#filter_udn').val() || 'all';
    
    this.createTable({
        parent: "table-desbloqueo",
        idFilterBar: "filterbar-desbloqueo",
        data: { 
            opc: "lsUnlocks",
            udn_id: udnFilter !== 'all' ? udnFilter : null
        },
        coffeesoft: true,
        conf: { datatable: true, pag: 15 },
        attr: {
            id: "tbUnlocks",
            theme: 'corporativo',
            center: [1, 2, 5],
            right: []
        }
    });
}
```

## Data Models

### Estructura de Datos Existente

**Tabla: `section_unlocks`**
```sql
CREATE TABLE section_unlocks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    udn_id INT NOT NULL,
    unlock_date DATE NOT NULL,
    reason TEXT,
    active TINYINT(1) DEFAULT 1,
    date_created DATETIME,
    FOREIGN KEY (udn_id) REFERENCES udn(id)
);
```

**Tabla: `section_unlock_details`**
```sql
CREATE TABLE section_unlock_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    section_unlock_id INT NOT NULL,
    section_id INT NOT NULL,
    FOREIGN KEY (section_unlock_id) REFERENCES section_unlocks(id),
    FOREIGN KEY (section_id) REFERENCES sections(id)
);
```

**Tabla: `sections`**
```sql
CREATE TABLE sections (
    id INT PRIMARY KEY AUTO_INCREMENT,
    valor VARCHAR(100) NOT NULL -- 'Ventas', 'Clientes', 'Almacén', 'Compras'
);
```

### Query Modificado en Modelo

**Método `listUnlocks()` debe aceptar filtro opcional:**

```php
function listUnlocks($array) {
    $whereClause = "1=1";
    $params = [];
    
    if (!empty($array[0])) {
        $whereClause .= " AND su.udn_id = ?";
        $params[] = $array[0];
    }
    
    $query = "
        SELECT 
            su.id,
            su.udn_id,
            u.valor as udn_name,
            su.unlock_date,
            su.reason,
            su.active,
            GROUP_CONCAT(s.valor ORDER BY s.valor SEPARATOR ', ') as sections
        FROM {$this->bd}section_unlocks su
        LEFT JOIN {$this->bd}udn u ON su.udn_id = u.id
        LEFT JOIN {$this->bd}section_unlock_details sud ON su.id = sud.section_unlock_id
        LEFT JOIN {$this->bd}sections s ON sud.section_id = s.id
        WHERE $whereClause
        GROUP BY su.id
        ORDER BY su.unlock_date DESC, su.id DESC
    ";
    
    return $this->_Read($query, $params);
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Badge Color Consistency
*For any* módulo name in the system, when rendered as a badge, the color SHALL always match the predefined color map (Ventas=green, Clientes=orange, Almacén=blue, Compras=red)
**Validates: Requirements 3.1, 3.2, 3.3, 3.4**

### Property 2: Badge HTML Structure Validity
*For any* string of módulos, the `renderModuleBadges()` function SHALL return valid HTML with properly closed tags and TailwindCSS classes
**Validates: Requirements 1.1, 1.2, 3.5**

### Property 3: UDN Filter Correctness
*For any* UDN selection in the filter, the table SHALL display only records matching that UDN, or all records if "Todas" is selected
**Validates: Requirements 2.2, 2.3, 2.5**

### Property 4: Multiple Badges Rendering
*For any* record with multiple módulos, each módulo SHALL be rendered as a separate badge with appropriate spacing
**Validates: Requirements 1.2, 1.3**

### Property 5: Lock Icon State Consistency
*For any* record, when active=1 the icon SHALL be lock-open with green color, when active=0 the icon SHALL be lock-closed with red color
**Validates: Requirements 4.1, 4.2**

### Property 6: DataTables Search Inclusion
*For any* search query in DataTables, the search SHALL include text content within badges (módulo names)
**Validates: Requirements 8.2**

### Property 7: Responsive Badge Wrapping
*For any* screen size, when multiple badges exceed container width, they SHALL wrap to multiple lines maintaining readability
**Validates: Requirements 5.4, 5.5**

### Property 8: Badge Function Reusability
*For any* array of módulo names, the `renderModuleBadges()` function SHALL accept the array and return formatted HTML without side effects
**Validates: Requirements 6.1, 6.2, 6.3**

## Error Handling

### 1. Módulo Desconocido

**Escenario:** Un módulo no está en el mapeo de colores

**Manejo:**
```php
$defaultColor = 'bg-gray-100 text-gray-800 border-gray-300';
$color = $colorMap[$moduleName] ?? $defaultColor;
```

**Resultado:** Badge con color gris neutro

### 2. String de Módulos Vacío

**Escenario:** Campo `sections` es NULL o vacío

**Manejo:**
```php
if (empty($modulesString)) {
    return '<span class="text-gray-400 italic">Sin módulos</span>';
}
```

**Resultado:** Texto indicativo en lugar de badges

### 3. UDN No Encontrada

**Escenario:** Usuario selecciona UDN que no existe

**Manejo:**
- Modelo retorna array vacío
- Frontend muestra mensaje "No hay registros disponibles" (DataTables default)

### 4. Caracteres Especiales en Nombres

**Escenario:** Nombre de módulo contiene HTML o caracteres especiales

**Manejo:**
```php
$moduleName = htmlspecialchars(trim($module), ENT_QUOTES, 'UTF-8');
```

**Resultado:** Caracteres escapados correctamente

### 5. Fallo en Toggle de Estado

**Escenario:** Error al cambiar estado de bloqueo

**Manejo:**
- Controlador retorna `status: 500` con mensaje descriptivo
- Frontend muestra alert de error
- Tabla NO se actualiza (mantiene estado anterior)

## Testing Strategy

### Unit Tests

**Archivo:** `finanzas/administrador/tests/AdminControllerTest.php`

**Tests a implementar:**

1. **testRenderModuleBadgesWithSingleModule**
   - Input: "Ventas"
   - Expected: Badge verde con clase correcta

2. **testRenderModuleBadgesWithMultipleModules**
   - Input: "Ventas, Clientes, Almacén"
   - Expected: 3 badges con colores correctos

3. **testRenderModuleBadgesWithUnknownModule**
   - Input: "ModuloDesconocido"
   - Expected: Badge gris por defecto

4. **testRenderModuleBadgesWithEmptyString**
   - Input: ""
   - Expected: Mensaje "Sin módulos"

5. **testLsUnlocksWithUdnFilter**
   - Input: `udn_id = 1`
   - Expected: Solo registros de UDN 1

6. **testLsUnlocksWithAllUdnFilter**
   - Input: `udn_id = null`
   - Expected: Todos los registros

### Integration Tests

**Archivo:** `finanzas/administrador/tests/UnlockModuleIntegrationTest.php`

**Tests a implementar:**

1. **testFilterByUdnUpdatesTable**
   - Acción: Cambiar select de UDN
   - Verificar: AJAX se dispara con parámetro correcto
   - Verificar: Tabla se actualiza con registros filtrados

2. **testBadgesRenderInDataTable**
   - Acción: Cargar tabla con registros
   - Verificar: Badges HTML presentes en columna "Módulos"
   - Verificar: Colores aplicados correctamente

3. **testDataTablesSearchIncludesBadges**
   - Acción: Buscar "Ventas" en DataTables
   - Verificar: Registros con badge "Ventas" aparecen en resultados

4. **testToggleLockStatusUpdatesIcon**
   - Acción: Click en icono de candado
   - Verificar: Confirmación aparece
   - Verificar: Estado cambia en BD
   - Verificar: Icono se actualiza en tabla

### Manual Testing Checklist

- [ ] Badges se muestran con colores correctos en desktop
- [ ] Badges se ajustan correctamente en tablet (768px)
- [ ] Badges son legibles en móvil (375px)
- [ ] Filtro de UDN actualiza tabla sin errores
- [ ] DataTables search encuentra texto dentro de badges
- [ ] Ordenamiento por columna "Módulos" funciona
- [ ] Paginación mantiene formato de badges
- [ ] Toggle de estado muestra confirmación
- [ ] Iconos de candado cambian según estado
- [ ] Módulos desconocidos muestran badge gris

### Performance Testing

**Métricas a validar:**

1. **Tiempo de renderizado de badges**
   - Objetivo: < 50ms para 100 registros
   - Método: Medir tiempo de ejecución de `renderModuleBadges()`

2. **Tiempo de carga de tabla**
   - Objetivo: < 500ms para 100 registros
   - Método: Medir desde AJAX request hasta render completo

3. **Memoria utilizada**
   - Objetivo: < 10MB adicionales por badges HTML
   - Método: Comparar uso de memoria antes/después

### Browser Compatibility Testing

**Navegadores a probar:**
- Chrome 120+ ✓
- Firefox 120+ ✓
- Safari 17+ ✓
- Edge 120+ ✓

**Funcionalidades críticas:**
- Renderizado de badges con TailwindCSS
- Eventos onclick en iconos de candado
- Select de filtro UDN con onchange
- DataTables con búsqueda y ordenamiento
