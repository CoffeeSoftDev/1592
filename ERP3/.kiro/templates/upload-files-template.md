# 📤 Template: Función para Subir Archivos

## Descripción
Función template reutilizable para subir múltiples archivos al servidor, validarlos, moverlos a un directorio específico y registrarlos en la base de datos.

## Características
- ✅ Soporte para múltiples archivos simultáneos
- ✅ Validación de errores de carga
- ✅ Generación de nombres únicos para evitar conflictos
- ✅ Creación automática de directorios si no existen
- ✅ Registro en base de datos con metadatos
- ✅ Manejo de errores detallado
- ✅ Respuesta estandarizada con contadores

---

## Código Template

### Controlador (CTRL)

```php
function uploadFiles() {
    $status   = 500;
    $message  = 'Error al subir archivos';
    $uploaded = 0;
    $total    = 0;
    $errors   = [];
    
    $section_id       = $_POST['section_id'];
    $daily_closure_id = $_POST['daily_closure_id'];
    $user_id          = $_COOKIE['IDU'];
    $udn_id           = $_POST['udn_id'];

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/[nombre_modulo]/';
    
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!isset($_FILES['files']) || empty($_FILES['files']['name'][0])) {
        return [
            'status'   => 400,
            'message'  => 'No se recibieron archivos',
            'uploaded' => 0,
            'total'    => 0,
            'errors'   => []
        ];
    }

    $files = $_FILES['files'];
    $total = count($files['name']);

    foreach ($files['name'] as $i => $fileName) {
        if ($files['error'][$i] !== UPLOAD_ERR_OK) {
            $errors[] = "Error al subir {$fileName}";
            continue;
        }

        $temporal      = $files['tmp_name'][$i];
        $fileSize      = $files['size'][$i];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
        if (!in_array($fileExtension, $allowedExtensions)) {
            $errors[] = "Extensión no permitida para {$fileName}";
            continue;
        }

        $maxSize = 5 * 1024 * 1024;
        if ($fileSize > $maxSize) {
            $errors[] = "Archivo {$fileName} excede el tamaño máximo permitido";
            continue;
        }

        $newFileName  = uniqid() . '_' . time() . '.' . $fileExtension;
        $destino      = $uploadDir . $newFileName;
        $relativePath = 'uploads/[nombre_modulo]/' . $newFileName;

        if (!move_uploaded_file($temporal, $destino)) {
            $errors[] = "Error al mover {$fileName} al directorio";
            continue;
        }

        $fileData = [
            'file_name'        => $fileName,
            'size_bytes'       => $fileSize,
            'path'             => $relativePath,
            'extension'        => $fileExtension,
            'created_at'       => date('Y-m-d H:i:s'),
            'section_id'       => $section_id,
            'user_id'          => $user_id,
            'udn_id'           => $udn_id,
            'daily_closure_id' => $daily_closure_id
        ];

        $create = $this->createFile($this->util->sql($fileData));

        if ($create) {
            $uploaded++;
        } else {
            $errors[] = "Error al registrar {$fileName} en la base de datos";
            unlink($destino);
        }
    }

    if ($uploaded > 0) {
        $status  = 200;
        $message = $uploaded === $total
            ? "Todos los archivos se subieron correctamente ({$uploaded}/{$total})"
            : "Se subieron {$uploaded} de {$total} archivos";
    } else {
        $message = "No se pudo subir ningún archivo";
    }

    return [
        'status'   => $status,
        'message'  => $message,
        'uploaded' => $uploaded,
        'total'    => $total,
        'errors'   => $errors
    ];
}
```

---

### Modelo (MDL)

```php
function createFile($array) {
    return $this->_Insert([
        'table'  => $this->bd . 'files',
        'values' => $array['values'],
        'data'   => $array['data']
    ]);
}

function getFileById($array) {
    $query = "
        SELECT *
        FROM {$this->bd}files
        WHERE id = ?
    ";
    
    $result = $this->_Read($query, $array);
    return !empty($result) ? $result[0] : null;
}

function listFilesBySection($array) {
    $query = "
        SELECT 
            f.id,
            f.file_name,
            f.size_bytes,
            f.path,
            f.extension,
            f.created_at,
            u.usser AS uploaded_by
        FROM {$this->bd}files f
        LEFT JOIN usuarios u ON f.user_id = u.idUser
        WHERE f.section_id = ?
        AND f.udn_id = ?
        ORDER BY f.created_at DESC
    ";
    
    return $this->_Read($query, $array);
}

function deleteFileById($array) {
    return $this->_Delete([
        'table' => $this->bd . 'files',
        'where' => 'id = ?',
        'data'  => $array
    ]);
}
```

---

### Frontend (JS)

```javascript
async uploadFiles(sectionId, dailyClosureId) {
    const fileInput = document.getElementById('fileInput');
    const files = fileInput.files;

    if (files.length === 0) {
        alert({
            icon: "warning",
            text: "Por favor selecciona al menos un archivo",
            btn1: true,
            btn1Text: "Ok"
        });
        return;
    }

    const formData = new FormData();
    formData.append('opc', 'uploadFiles');
    formData.append('section_id', sectionId);
    formData.append('daily_closure_id', dailyClosureId);
    formData.append('udn_id', $('#udn').val());

    for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i]);
    }

    const response = await fetch(this._link, {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (result.status === 200) {
        alert({
            icon: "success",
            text: result.message,
            btn1: true,
            btn1Text: "Aceptar"
        });

        if (result.errors.length > 0) {
            console.warn('Errores durante la carga:', result.errors);
        }

        fileInput.value = '';
        this.lsFiles();
    } else {
        alert({
            icon: "error",
            text: result.message,
            btn1: true,
            btn1Text: "Ok"
        });
    }
}
```

---

## Estructura de Base de Datos

```sql
CREATE TABLE files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255) NOT NULL,
    size_bytes INT NOT NULL,
    path VARCHAR(500) NOT NULL,
    extension VARCHAR(10) NOT NULL,
    created_at DATETIME NOT NULL,
    section_id INT NOT NULL,
    user_id INT NOT NULL,
    udn_id INT NOT NULL,
    daily_closure_id INT,
    active TINYINT(1) DEFAULT 1,
    INDEX idx_section (section_id),
    INDEX idx_user (user_id),
    INDEX idx_udn (udn_id),
    INDEX idx_closure (daily_closure_id)
);
```

---

## Uso Básico

### HTML Form

```html
<input type="file" id="fileInput" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.xls,.xlsx">
<button onclick="app.uploadFiles(1, 123)">Subir Archivos</button>
```

### Llamada desde JavaScript

```javascript
await this.uploadFiles(sectionId, dailyClosureId);
this.lsFiles();
```

---

## Personalización

### Cambiar directorio
```php
$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/[tu_modulo]/';
```

### Cambiar extensiones
```php
$allowedExtensions = ['jpg', 'png', 'pdf'];
```

### Cambiar tamaño máximo
```php
$maxSize = 10 * 1024 * 1024; // 10MB
```

---

**Versión:** 1.0  
**Autor:** CoffeeIA ☕  
**Última actualización:** 2025-01-23
