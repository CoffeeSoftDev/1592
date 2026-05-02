**Objetivo General**
Crear un archivo modelo PHP que extienda la clase `CRUD`, conforme a los estándares de desarrollo de CoffeeSoft. Este modelo debe incluir configuración base, estructura ordenada por módulos y funciones orientadas a la consulta de datos (incluyendo filtros tipo `<select>`).

**Reglas y Requisitos Técnicos**

### 1.Estructura Base del Modelo
Debe respetarse el formato de CoffeeSoft
- Nombre del archivo bajo la convención:
  `mdl-[nombre].php`

- Todos los modelos deben extender la clase `CRUD` y el modelo debe llamarse mdl:
  ```php
  class mdl extends CRUD {}
  ```
- Carga obligatoria de archivos de configuración:

  ```php
    require_once '../conf/_CRUD.php';
    require_once '../conf/_Utileria.php';

  ```

- Propiedades comunes a declarar en la clase:
  ```php
  public $util;
  public $bd;
  ```

- **Configuración de Base de Datos**
  - Si el usuario especifica el nombre de la base de datos, usar:
    ```php
    $this->bd = "rfwsmqex_[nombre_bd_especificado].";
    ```
  - Si NO se especifica la base de datos, usar el nombre del proyecto:
    ```php
    $this->bd = "rfwsmqex_[nombre_proyecto].";
    ```
  - Ejemplos:
    - Proyecto "contabilidad" → `$this->bd = "rfwsmqex_contabilidad.";`
    - Proyecto "ventas" → `$this->bd = "rfwsmqex_ventas.";`
    - BD especificada "erp" → `$this->bd = "rfwsmqex_erp.";`

- **Nomenclatura de campos**
  - Todos los nombres de columna deben estar en inglés.
  - La clave primaria siempre se llamará `id`.
  - Las claves foráneas seguirán el patrón `{tabla}_id` (ej. `user_id`, `order_id`).
  - Usar snake_case para nombres compuestos (ej. `created_at`, `first_name`).

  ### 2.Organización de Métodos por Módulo
-  Separar lógicamente las funciones según el módulo al que pertenecen, con comentarios ( Solo aplica si es diferente modulo):

```php
  // Finanzas:
  public function getFinanzasList() { ... }

  // Eventos:
  public function getEventList() { ... }
  ```

- Los nombres de las funciones deben:
  - Estar en inglés.
  - Usar notación `camelCase`.
  - **CRÍTICO:** NO pueden ser iguales a las funciones del controlador (ctrl)

### Nomenclatura Permitida para Modelos (MDL)

**✅ Nombres PERMITIDOS para funciones del modelo:**
- `list[Entidad]()` - Para listar registros
- `create[Entidad]()` - Para crear registros  
- `update[Entidad]()` - Para actualizar registros
- `get[Entidad]ById()` - Para obtener un registro por ID
- `delete[Entidad]ById()` - Para eliminar registros
- `ls[Entidad]()` - Para consultas de filtros/selects
- `exists[Entidad]ByName()` - Para validar existencia
- `getMax[Entidad]Id()` - Para obtener último ID

**❌ Nombres PROHIBIDOS (reservados para controlador):**
- `ls()`, `add()`, `edit()`, `get()`, `init()`, `status[Entidad]()`


### 3. Uso de Métodos CRUD Heredados

**CRÍTICO - Regla de Consultas:**
- **TODAS las consultas SELECT deben usar EXCLUSIVAMENTE el método `_Read()`**
- **PROHIBIDO usar `_Select()` para consultas**
- Solo se permiten los siguientes métodos CRUD:
  - `_Read` : **ÚNICO método permitido para consultas SELECT** (raw queries SQL)
  - `_Insert` : Insertar registros
  - `_Update` : Actualizar registros
  - `_Delete` : Eliminar registros

**IMPORTANTE:** consulta siempre CRUD.md para entender el funcionamiento de los metodos de consulta.

#### Ejemplos de Uso:

**<_Read> - ÚNICO método para consultas SELECT:**
```php
function listProducts($array) {
    $query = "
        SELECT id, name, price, category_id
        FROM {$this->bd}products
        WHERE active = ?
        ORDER BY name ASC
    ";
    return $this->_Read($query, $array);
}

function getProductById($array) {
    $query = "
        SELECT *
        FROM {$this->bd}products
        WHERE id = ?
    ";
    return $this->_Read($query, $array);
}
```

**<_Insert> - Para crear registros:**
```php
function createProduct($array){
    return $this->_Insert([
        'table' =>  "{$this->bd}products",
        'values' => $array['values'],
        'data' => $array['data']
    ]);
}
```

**<_Update> - Para actualizar registros:**
```php
function updateProduct($array){
    return $this->_Update([
        'table'  => "{$this->bd}products",
        'values' => $array['values'],
        'where'  => $array['where'],
        'data'   => $array['data'],
    ]);
}
```

**<_Delete> - Para eliminar registros:**
```php
  function deleteProduct($array){
      return $this->_Delete([
          'table' => "{$this->bd}products",
          'where' => $array['where'],
          'data'  => $array['data'],
      ]);
  }
```

**Notas sobre _Delete:**
- El parámetro de la función es `$array` (o el identificador necesario)
- Internamente usa `$array['where']` para la cláusula WHERE
- Internamente usa `$array['data']` para los valores del placeholder
- Esta estructura permite flexibilidad en las condiciones de eliminación
- Ejemplo: 

```php
 function deleteMaterialById($id) { 
  return $this->_Delete([
    'table' => "{$this->bd}mtto_almacen", 
    'where' => $array['where'], 
    'data' => $array['data']]);
 }
```

### 4. Estructura para Consultas con _Read

**TODAS las consultas SELECT deben usar `_Read()` con SQL raw:**

#### Consultas Básicas:

```php
// Listar registros con filtros
public function listProducts($array) {
    $query = "
        SELECT id, name, price, active
        FROM {$this->bd}products
        WHERE active = ?
        ORDER BY name ASC
    ";
    return $this->_Read($query, $array);
}

// Obtener un registro por ID
public function getProductById($array) {
    $query = "
        SELECT *
        FROM {$this->bd}products
        WHERE id = ?
    ";
    return $this->_Read($query, $array);
}
```

#### Consultas con JOINs:

```php
public function listProductsWithCategory($array) {
    $query = "
        SELECT 
            p.id,
            p.name,
            p.price,
            c.classification as category_name
        FROM {$this->bd}products p
        LEFT JOIN {$this->bd}categories c ON p.category_id = c.id
        WHERE p.active = ?
        ORDER BY p.name ASC
    ";
    return $this->_Read($query, $array);
}
```

#### Consultas para Filtros (select):

```php
public function lsCategories($array) {
    $query = "
        SELECT id, classification as valor
        FROM {$this->bd}categories
        WHERE active = ?
        ORDER BY classification ASC
    ";
    return $this->_Read($query, $array);
}
```

#### Consultas de Validación:

```php
public function existsProductByName($array) {
    $query = "
        SELECT COUNT(*) as count
        FROM {$this->bd}products
        WHERE LOWER(name) = LOWER(?)
        AND active = 1
    ";
    $result = $this->_Read($query, $array);
    return $result[0]['count'] > 0;
}
```

### 5. Separación de Consultas Complejas

**CRÍTICO:** Cuando una consulta involucra múltiples entidades o conceptos diferentes, DEBE separarse en métodos independientes.

#### ¿Cuándo Separar una Consulta?

Separar cuando la consulta tiene:
- Múltiples `LEFT JOIN` a tablas de diferentes dominios
- Agregaciones (`SUM`, `COUNT`) sobre entidades distintas
- Datos que se mostrarán en secciones diferentes del frontend
- Lógica de negocio independiente entre los datos consultados

#### Reglas de Separación

1. **Una consulta por entidad/concepto:** Cada método debe consultar UNA sola entidad o concepto relacionado
2. **Nombres descriptivos:** El nombre del método debe indicar claramente qué datos retorna
3. **Retorno consistente:** Usar arrays asociativos con claves descriptivas
4. **Validación obligatoria:** SIEMPRE validar el resultado antes de iterar

#### Patrón de Validación Obligatorio

```php
$result = $this->_Read($query, $array);
$data = [];

if (is_array($result) && !empty($result)) {
    foreach ($result as $row) {
        $data[$row['nombre']] = floatval($row['total']);
    }
}

return $data;
```

#### Ejemplo Práctico: Carátula de Ventas

**❌ INCORRECTO - Consulta Monolítica:**
```php
function getFormasDePago($array) {
    $query = "
        SELECT 
            IFNULL(SUM(CASE WHEN cc.name = 'Propina' THEN dcc.amount ELSE 0 END), 0) AS propina,
            IFNULL(SUM(CASE WHEN cc.name = 'Efectivo' THEN dcc.amount ELSE 0 END), 0) AS efectivo,
            IFNULL(SUM(CASE WHEN fc.name = 'Dólar' THEN dfc.amount_mxn ELSE 0 END), 0) AS dolar,
            IFNULL(SUM(dba.amount), 0) AS bancos
        FROM {$this->bd}daily_closure dc
        LEFT JOIN {$this->bd}detail_cash_concept dcc ON dc.id = dcc.daily_closure_id
        LEFT JOIN {$this->bd}cash_concept cc ON dcc.cash_concept_id = cc.id
        LEFT JOIN {$this->bd}detail_foreign_currency dfc ON dc.id = dfc.daily_closure_id
        LEFT JOIN {$this->bd}foreign_currency fc ON dfc.foreing_currency_id = fc.id
        LEFT JOIN {$this->bd}detail_bank_account dba ON dc.id = dba.daily_closure_id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) BETWEEN ? AND ?
    ";
    return $this->_Read($query, $array);
}
```

**✅ CORRECTO - Consultas Separadas:**

```php
// Conceptos de efectivo (Propina, Efectivo, Vales, etc.)
function getConceptosVenta($array) {
    $query = "
        SELECT 
            cc.name AS nombre,
            IFNULL(SUM(dcc.amount), 0) AS total
        FROM {$this->bd}daily_closure dc
        LEFT JOIN {$this->bd}detail_cash_concept dcc ON dc.id = dcc.daily_closure_id
        LEFT JOIN {$this->bd}cash_concept cc ON dcc.cash_concept_id = cc.id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) BETWEEN ? AND ?
        AND cc.name IS NOT NULL
        GROUP BY cc.id, cc.name
    ";
    
    $result = $this->_Read($query, [$array[2], $array[0], $array[1]]);
    $conceptos = [];
    
    if (is_array($result) && !empty($result)) {
        foreach ($result as $row) {
            $conceptos[$row['nombre']] = floatval($row['total']);
        }
    }
    
    return $conceptos;
}

// Monedas extranjeras (Dólar, Quetzal, etc.)
function getMonedasExtranjeras($array) {
    $query = "
        SELECT 
            fc.name AS nombre,
            IFNULL(SUM(dfc.amount_mxn), 0) AS total
        FROM {$this->bd}daily_closure dc
        LEFT JOIN {$this->bd}detail_foreign_currency dfc ON dc.id = dfc.daily_closure_id
        LEFT JOIN {$this->bd}foreign_currency fc ON dfc.foreing_currency_id = fc.id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) BETWEEN ? AND ?
        AND fc.name IS NOT NULL
        GROUP BY fc.id, fc.name
    ";
    
    $result = $this->_Read($query, [$array[2], $array[0], $array[1]]);
    $monedas = [];
    
    if (is_array($result) && !empty($result)) {
        foreach ($result as $row) {
            $monedas[$row['nombre']] = floatval($row['total']);
        }
    }
    
    return $monedas;
}

// Total de bancos (suma general sin desglose)
function getBancos($array) {
    $query = "
        SELECT IFNULL(SUM(dba.amount), 0) AS total
        FROM {$this->bd}daily_closure dc
        LEFT JOIN {$this->bd}detail_bank_account dba ON dc.id = dba.daily_closure_id
        WHERE dc.udn_id = ?
        AND DATE(dc.created_at) BETWEEN ? AND ?
    ";
    
    $result = $this->_Read($query, [$array[2], $array[0], $array[1]]);
    return floatval($result[0]['total']);
}
```

#### Ventajas de la Separación

| Aspecto | Consulta Monolítica | Consultas Separadas |
|---------|---------------------|---------------------|
| **Mantenibilidad** | Difícil de modificar | Fácil de actualizar |
| **Reutilización** | No reutilizable | Cada método es independiente |
| **Performance** | JOINs innecesarios | Solo consulta lo necesario |
| **Debugging** | Complejo de depurar | Errores aislados por método |
| **Escalabilidad** | Agregar campos es riesgoso | Agregar nuevos métodos es seguro |
| **Dinamismo** | Valores hardcodeados | Retorna todos los registros existentes |

#### Tipos de Retorno según Caso de Uso

```php
// Desglose dinámico (array asociativo)
return ['Efectivo' => 1500.00, 'Propina' => 200.00, 'Vales' => 300.00];

// Total único (float)
return 5000.00;

// Registro único (array)
return ['credito' => 1000.00, 'pagos_credito' => 500.00];
```

#### Integración con el Controlador

```php
// En el controlador (CTRL)
$conceptos = $this->getConceptosVenta([$fi, $ff, $udn]);
$monedas   = $this->getMonedasExtranjeras([$fi, $ff, $udn]);
$bancos    = $this->getBancos([$fi, $ff, $udn]);

$totalConceptos = array_sum($conceptos);  // Suma de array asociativo
$totalMonedas   = array_sum($monedas);    // Suma de array asociativo
$totalBancos    = $bancos;                // Ya es float
```

**Consideraciones Finales**
- Este prompt puede ser reutilizado para todos los módulos que requieran interacción con datos tipo filtro `<mdl>`.
- Se puede integrar fácilmente en controladores PHP que inicialicen datos para vistas en JS.
- Puedes usar de referencias los <pivotes>


