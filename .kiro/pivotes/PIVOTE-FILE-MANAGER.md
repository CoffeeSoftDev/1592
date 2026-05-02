# 🗂️ PIVOTE FILE MANAGER — Sistema de Gestión de Archivos

---

## 🎯 Propósito General
El **módulo de gestión de archivos** centraliza la administración, visualización y control de documentos digitales dentro del sistema ERP CoffeeSoft.  
Permite subir, descargar, visualizar y eliminar archivos organizados por secciones (Ventas, Compras, Almacén, Proveedores, etc.), con control de permisos por nivel de usuario y registro de auditoría completo.

---

## ⚙️ Key Features

- 🔹 **Dashboard con KPIs** mostrando contadores por sección y totales
- 🔹 **Gestión de archivos** con operaciones CRUD completas
- 🔹 **Organización por secciones** (Ventas, Compras, Almacén, Proveedores, Costos)
- 🔹 **Control de permisos** basado en nivel de usuario (1-3)
- 🔹 **Filtros inteligentes** por fecha, UDN y sección
- 🔹 **Visualización de archivos** con preview en nueva pestaña
- 🔹 **Sistema de descarga seguro** con tokens temporales
- 🔹 **Registro de auditoría** (file_logs) para todas las operaciones
- 🔹 **Badges visuales** para tipos de archivo y secciones
- 🔹 **Formato de tamaño** automático (B, KB, MB, GB)
- 🔹 **Diseño responsive** con TailwindCSS y tema light

---

## 🧩 Estructura Técnica

| Clase / Archivo | Descripción | Función Principal |
|------------------|-------------|-------------------|
| `App` | Clase principal que define estructura, tabs y filtros globales | `app = new App(api, "root")` |
| `Files` | Controla listado, visualización, descarga y eliminación de archivos | `files.lsFiles()` |
| `ctrl-archivos.php` | Controlador PHP para operaciones CRUD y gestión de archivos | `opc: "init", "ls", "downloadFile", "deleteFile"` |
| `mdl-archivos.php` | Modelo de base de datos para consultas y operaciones | Métodos de lectura y escritura SQL |

---

## 🧭 Notas de Diseño

- 🧩 El módulo usa **tabs** para separar diferentes secciones (Archivos, Ventas, Clientes, Compras, etc.)
- 💡 Sistema de **permisos por nivel**: Nivel 1 (básico), Nivel 2 (intermedio), Nivel 3 (admin)
- 🖌️ **Paleta de colores** por sección:
  - Ventas: Verde (`bg-green-100`, `text-green-700`)
  - Compras: Azul (`bg-blue-100`, `text-blue-700`)
  - Almacén: Púrpura (`bg-purple-100`, `text-purple-700`)
  - Proveedores: Amarillo (`bg-yellow-100`, `text-yellow-700`)
- 🔒 **Seguridad**: Tokens temporales para descargas (5 min de expiración)
- 📊 **KPIs dinámicos** que se actualizan automáticamente tras operaciones


---

## 💻 Código del Pivote

### FRONT-JS [archivos.js]

```javascript
let api = 'ctrl/ctrl-archivos.php';
let app, files;
let sections, lsudn, counts, userLevel;

$(async () => {
    const data = await useFetch({ url: api, data: { opc: "init" } });
    sections  = data.sections;
    lsudn     = data.udn;
    counts    = data.counts;
    userLevel = data.userLevel;

    app   = new App(api, "root");
    files = new Files(api, "root");

    app.render();
});

class App extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "FileManager";
    }

    render() {
        this.layout();
        this.filterBar();
        files.render();
    }

    layout() {
        this.primaryLayout({
            parent: "root",
            id: this.PROJECT_NAME,
            class: "w-full",
            card: {
                filterBar: { class: "w-full mb-3 p-3", id: `filterBar${this.PROJECT_NAME}` },
                container: { class: "w-full h-full p-2", id: `container${this.PROJECT_NAME}` }
            }
        });

        this.tabLayout({
            parent: `container${this.PROJECT_NAME}`,
            id: `tabs${this.PROJECT_NAME}`,
            theme: "light",
            type: "short",
            json: [
                {
                    id: "archivos",
                    tab: "Archivos",
                    lucideIcon: 'folder-open',
                    active: true,
                    onClick: () => files.lsFiles()
                },
                {
                    id: "ventas",
                    tab: "Ventas",
                    lucideIcon: 'dollar-sign',
                    onClick: () => console.log('Ventas')
                },
                {
                    id: "compras",
                    tab: "Compras",
                    lucideIcon: 'shopping-cart',
                    onClick: () => console.log('Compras')
                }
            ]
        });

        setTimeout(() => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        }, 100);
    }

    filterBar() {
        const filterData = [];

        filterData.push({
            opc: "button",
            id: "btnMainMenu",
            text: "Menú principal",
            class: "col-sm-2",
            className: 'w-100',
            color_btn: "info",
            onClick: () => {
                window.location.href = '../index.php';
            }
        });

        if (userLevel >= 3) {
            filterData.push({
                opc: "select",
                id: "udn",
                lbl: "Unidad de Negocio:",
                class: "col-sm-2 offset-sm-5",
                data: [{ id: "", valor: "Todas las UDN" }, ...lsudn],
                onchange: () => files.lsFiles()
            });
        }

        filterData.push({
            opc: "input-calendar",
            class: "col-sm-3",
            id: `calendar${this.PROJECT_NAME}`,
            lbl: "Rango de fechas:"
        });

        this.createfilterBar({
            parent: `filterBar${this.PROJECT_NAME}`,
            data: filterData
        });

        const dateType = userLevel === 1 ? 'simple' : 'all';
        
        dataPicker({
            parent: `calendar${this.PROJECT_NAME}`,
            type: dateType,
            onSelect: () => files.lsFiles()
        });
    }
}

class Files extends Templates {
    constructor(link, div_modulo) {
        super(link, div_modulo);
        this.PROJECT_NAME = "Files";
    }

    render() {
        this.layoutFiles();
        this.renderTotalCards();
        this.filterBarFiles();
        this.lsFiles();
    }

    layoutFiles() {
        const container = $("<div>", {
            id: "filesContainer",
            class: "w-full"
        });

        const cardsContainer = $("<div>", {
            id: "cardsFiles",
            class: "mb-4"
        });

        const filterContainer = $("<div>", {
            id: "filterBarFiles",
            class: "mb-4"
        });

        const tableContainer = $("<div>", {
            id: "tableFiles"
        });

        container.append(cardsContainer, filterContainer, tableContainer);
        $("#container-archivos").html(container);
    }

    filterBarFiles() {
        this.createfilterBar({
            parent: "filterBarFiles",
            data: [
                {
                    opc: "select",
                    id: "section",
                    lbl: "Sección:",
                    class: "col-sm-3",
                    data: [
                        { id: "", valor: "Mostrar todos los archivos" },
                        ...sections
                    ],
                    onchange: `files.lsFiles()`
                }
            ]
        });
    }

    renderTotalCards() {
        this.infoCard({
            parent: "cardsFiles",
            theme: "light",
            style: "file",
            class: "mb-4",
            json: [
                {
                    id: "kpiTotal",
                    title: "Archivos totales",
                    bgColor: "bg-green-50",
                    data: { value: counts.total || 0, color: "text-[#8CC63F]" }
                },
                {
                    id: "kpiVentas",
                    title: "Archivos de ventas",
                    bgColor: "bg-blue-50",
                    data: { value: counts.ventas || 0, color: "text-blue-500" }
                },
                {
                    id: "kpiCompras",
                    title: "Archivos de compras",
                    bgColor: "bg-blue-50",
                    data: { value: counts.compras || 0, color: "text-blue-500" }
                }
            ]
        });
    }

    lsFiles() {
        const rangePicker = getDataRangePicker(`calendarFileManager`);
        const udn         = $(`#filterBarFileManager #udn`).val();
        const section     = $(`#filterBarFiles #section`).val();

        this.createTable({
            parent: `tableFiles`,
            idFilterBar: `filterBarFiles`,
            data: { 
                opc: 'ls', 
                fi: rangePicker.fi, 
                ff: rangePicker.ff,
                udn: udn,
                section: section
            },
            coffeesoft: true,
            conf: { datatable: true, pag: 25 },
            attr: {
                id: `tbFiles`,
                theme: 'light',
                center: [1, 2, 5],
                right: [6]
            }
        });
    }

    async downloadFile(id) {
        const response = await useFetch({
            url: api,
            data: { opc: 'downloadFile', id: id }
        });

        if (response.status === 200) {
            window.open(response.url, '_blank');
            alert({
                icon: "success",
                text: "Descarga iniciada correctamente",
                btn1: true,
                btn1Text: "Aceptar"
            });
        } else {
            alert({
                icon: "error",
                text: response.message,
                btn1: true,
                btn1Text: "Ok"
            });
        }
    }

    deleteFile(id) {
        this.swalQuestion({
            opts: {
                title: "¿Está seguro de querer eliminar el archivo?",
                text: "Esta acción no se puede deshacer y se registrará en el sistema.",
                icon: "warning"
            },
            data: {
                opc: "deleteFile",
                id: id
            },
            methods: {
                send: (response) => {
                    if (response.status === 200) {
                        alert({
                            icon: "success",
                            text: response.message,
                            btn1: true,
                            btn1Text: "Aceptar"
                        });
                        this.lsFiles();
                        this.updateCounts();
                    } else {
                        alert({
                            icon: "error",
                            text: response.message,
                            btn1: true,
                            btn1Text: "Ok"
                        });
                    }
                }
            }
        });
    }

    viewFile(id, path) {
        const fileUrl = '../../../' + path;
        window.open(fileUrl, '_blank');
    }

    async updateCounts() {
        const data = await useFetch({
            url: api,
            data: { opc: "getFileCounts" }
        });

        if (data.status === 200) {
            counts = data.data;
            this.renderTotalCards();
        }
    }

    infoCard(options) {
        const defaults = {
            parent: "root",
            id: "infoCardKPI",
            class: "",
            theme: "light",
            style: "default",
            borderColor: "border-[#8CC63F]",
            json: []
        };
        const opts = Object.assign({}, defaults, options);

        const renderCard = (card, i = "") => {
            if (opts.style === "file") {
                const bgColor = card.bgColor || "";
                const box = $("<div>", {
                    id: `${opts.id}_${i}`,
                    class: `${bgColor} border rounded-lg ${card.borderColor || opts.borderColor} p-4`
                });
                const title = $("<p>", {
                    class: "text-sm text-gray-500 mb-3",
                    text: card.title
                });
                const valueContainer = $("<div>", {
                    class: "flex items-center justify-end gap-2"
                });
                const value = $("<span>", {
                    id: card.id || "",
                    class: `text-2xl font-bold ${card.data?.color || "text-gray-700"}`,
                    text: card.data?.value
                });
                valueContainer.append(value);
                box.append(title, valueContainer);
                return box;
            }
        };

        const container = $("<div>", {
            id: opts.id,
            class: `grid grid-cols-2 md:grid-cols-5 gap-4 ${opts.class}`
        });

        opts.json.forEach((item, i) => {
            container.append(renderCard(item, i));
        });

        $(`#${opts.parent}`).html(container);
    }
}
```

---

### CTRL [ctrl-archivos.php]

```php
<?php

if (empty($_POST['opc'])) exit(0);

require_once '../mdl/mdl-archivos.php';
require_once '../../../conf/coffeSoft.php';

class ctrl extends mdl {

    function init() {
        $userLevel = 3;

        return [
            'sections'  => $this->lsSections(),
            'udn'       => $this->lsUDN(),
            'counts'    => $this->getFileCountsBySection(),
            'userLevel' => $userLevel
        ];
    }

    function ls() {
        $fi         = $_POST['fi'];
        $ff         = $_POST['ff'];
        $section_id = $_POST['section'];
        $udn_id     = $_POST['udn'];
        
        $data = $this->listFiles([
            'fi'         => $fi,
            'ff'         => $ff,
            'section_id' => $section_id,
            'udn_id'     => $udn_id
        ]);
        
        $rows = [];

        if (is_array($data) && !empty($data)) {
            foreach ($data as $item) {
                $fileSize     = formatFileSize($item['size_bytes']);
                $uploadDate   = formatSpanishDate($item['upload_date'], 'normal');
                $sectionBadge = renderSectionBadge($item['section_name']);
                
                $rows[] = [
                    'id'                 => $item['id'],
                    'Fecha subida'       => $uploadDate,
                    'Sección'            => [
                        'html'  => $sectionBadge,
                        'class' => 'text-center'
                    ],
                    'Subido por'         => htmlspecialchars($item['uploaded_by']),
                    'Nombre del archivo' => '<span class="font-medium">' . htmlspecialchars($item['file_name']) . '</span>',
                    'Tipo / Tamaño'      => [
                        'html'  => renderFileType($item['extension'], $fileSize),
                        'class' => 'text-center'
                    ],
                    'a'                  => actionButtons($item['id'], $item['path'])
                ];
            }
        }

        return [
            'row' => $rows,
            'ls'  => $data ?? []
        ];
    }

    function getFile() {
        $id      = $_POST['id'];
        $status  = 404;
        $message = 'Archivo no encontrado';
        $data    = null;

        $file = $this->getFileById([$id]);

        if ($file) {
            $status  = 200;
            $message = 'Archivo encontrado';
            $data    = $file;
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ];
    }

    function downloadFile() {
        $status  = 500;
        $message = 'Error al descargar el archivo';
        $id      = $_POST['id'];
        
        if (!isset($_SESSION['user_id'])) {
            return [
                'status'  => 401,
                'message' => 'Sesión no válida'
            ];
        }
        
        $file = $this->getFileById([$id]);
        
        if (!$file) {
            return [
                'status'  => 404,
                'message' => 'Archivo no encontrado'
            ];
        }
        
        $token  = bin2hex(random_bytes(32));
        $expiry = time() + 300;
        
        $_SESSION['download_tokens'][$token] = [
            'file_id' => $id,
            'user_id' => $_SESSION['user_id'],
            'expiry'  => $expiry,
            'path'    => $file['path']
        ];
        
        $this->createFileLog($this->util->sql([
            'file_id'     => $id,
            'user_id'     => $_SESSION['user_id'],
            'action'      => 'download',
            'action_date' => date('Y-m-d H:i:s'),
            'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null
        ]));
        
        $status  = 200;
        $message = 'Token generado correctamente';
        $url     = '../../../' . $file['path'];
        
        return [
            'status'  => $status,
            'message' => $message,
            'url'     => $url ?? null,
            'token'   => $token ?? null
        ];
    }

    function deleteFile() {
        $status  = 500;
        $message = 'Error al eliminar el archivo';
        $id      = $_POST['id'];
        
        if (!isset($_SESSION['user_id'])) {
            return [
                'status'  => 401,
                'message' => 'Sesión no válida'
            ];
        }
        
        $userLevel = $this->getUserLevel([$_SESSION['user_id']]);
        
        if ($userLevel < 1) {
            return [
                'status'  => 403,
                'message' => 'No tiene permisos para eliminar archivos'
            ];
        }
        
        $file = $this->getFileById([$id]);
        
        if (!$file) {
            return [
                'status'  => 404,
                'message' => 'Archivo no encontrado'
            ];
        }
        
        $filePath = '../../../' . $file['path'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $this->createFileLog($this->util->sql([
            'file_id'     => $id,
            'user_id'     => $_SESSION['user_id'],
            'action'      => 'delete',
            'action_date' => date('Y-m-d H:i:s'),
            'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? null
        ]));
        
        $delete = $this->deleteFileById([$id]);
        
        if ($delete) {
            $status  = 200;
            $message = 'Archivo eliminado correctamente';
        }
        
        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function getFileCounts() {
        $counts = $this->getFileCountsBySection();
        
        return [
            'status' => 200,
            'data'   => $counts
        ];
    }
}

// Complements

function actionButtons($id, $path) {
    return [
        [
            'class'   => 'btn btn-sm btn-success me-1',
            'html'    => '<i class="icon-eye"></i>',
            'onclick' => "files.viewFile($id, '$path')"
        ],
        [
            'class'   => 'btn btn-sm btn-primary me-1',
            'html'    => '<i class="icon-download"></i>',
            'onclick' => "files.downloadFile($id)"
        ],
        [
            'class'   => 'btn btn-sm btn-danger',
            'html'    => '<i class="icon-trash"></i>',
            'onclick' => "files.deleteFile($id)"
        ]
    ];
}

function renderFileType($extension, $size) {
    $ext    = strtoupper($extension);
    $colors = [
        'PDF'  => ['bg' => 'bg-red-100',    'text' => 'text-red-700'],
        'XLS'  => ['bg' => 'bg-green-100',  'text' => 'text-green-700'],
        'XLSX' => ['bg' => 'bg-green-100',  'text' => 'text-green-700'],
        'DOC'  => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
        'DOCX' => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
        'PNG'  => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
        'JPG'  => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
        'JPEG' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700']
    ];
    
    $style = $colors[$ext] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
    
    return '
        <div class="flex items-center justify-center gap-2">
            <span class="px-2 py-1 rounded text-xs font-bold ' . $style['bg'] . ' ' . $style['text'] . '">' . $ext . '</span>
            <div class="text-left">
                <div class="text-sm font-medium text-gray-700">' . $ext . '</div>
                <div class="text-xs text-gray-400">' . $size . '</div>
            </div>
        </div>';
}

function renderSectionBadge($section) {
    $colors = [
        'Ventas'      => ['bg' => 'bg-green-100',  'text' => 'text-green-700'],
        'Compras'     => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
        'Almacén'     => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
        'Tesorería'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
        'Proveedores' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700']
    ];
    
    $style = $colors[$section] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700'];
    
    return '<span class="inline-block px-3 py-1 rounded text-xs font-semibold min-w-[100px] text-center ' . $style['bg'] . ' ' . $style['text'] . '">' . htmlspecialchars($section) . '</span>';
}

function formatFileSize($bytes) {
    if ($bytes == 0) return '0 B';
    
    $units = ['B', 'KB', 'MB', 'GB'];
    $i     = floor(log($bytes) / log(1024));
    
    return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}

$obj = new ctrl();
echo json_encode($obj->{$_POST['opc']}());
```

---

### MDL [mdl-archivos.php]

```php
<?php
require_once '../../../conf/_CRUD.php';
require_once '../../../conf/_Utileria.php';
session_start();

class mdl extends CRUD {
    protected $util;
    public    $bd;

    public function __construct() {
        $this->util = new Utileria;
        $this->bd   = "rfwsmqex_gvsl_finanzas3.";
    }

    function listFiles($array) {
        $where = '1=1';
        $data  = [];

        if (!empty($array['fi']) && !empty($array['ff'])) {
            $where  .= ' AND f.operation_date BETWEEN ? AND ?';
            $data[]  = $array['fi'];
            $data[]  = $array['ff'];
        }

        if (!empty($array['section_id'])) {
            $where  .= ' AND f.section_id = ?';
            $data[]  = $array['section_id'];
        }

        if (!empty($array['udn_id'])) {
            $where  .= ' AND f.udn_id = ?';
            $data[]  = $array['udn_id'];
        }

        $query = "
            SELECT 
                f.id,
                f.udn_id,
                f.user_id,
                f.file_name,
                f.upload_date,
                f.size_bytes,
                f.path,
                f.extension,
                DATE_FORMAT(f.operation_date, '%d/%m/%Y') AS operation_date,
                f.section_id,
                s.name AS section_name,
                u.usser AS uploaded_by,
                udn.UDN AS udn_name
            FROM {$this->bd}file f
            LEFT JOIN {$this->bd}section s ON f.section_id = s.id
            LEFT JOIN usuarios u ON f.user_id = u.idUser
            LEFT JOIN udn ON f.udn_id = udn.idUDN
            WHERE {$where}
            ORDER BY f.upload_date DESC
        ";

        return $this->_Read($query, $data);
    }

    function getFileById($array) {
        $query = "
            SELECT 
                f.*,
                s.name AS section_name,
                udn.UDN AS udn_name
            FROM {$this->bd}file f
            LEFT JOIN {$this->bd}section s ON f.section_id = s.id
            LEFT JOIN udn ON f.udn_id = udn.idUDN
            WHERE f.id = ?
        ";
        
        $result = $this->_Read($query, $array);
        return !empty($result) ? $result[0] : null;
    }

    function deleteFileById($array) {
        return $this->_Delete([
            'table' => $this->bd . 'file',
            'where' => 'id = ?',
            'data'  => $array
        ]);
    }

    function createFileLog($array) {
        return $this->_Insert([
            'table'  => $this->bd . 'file_logs',
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function lsSections() {
        $query = "
            SELECT id, name AS valor
            FROM {$this->bd}section
            ORDER BY name ASC
        ";
        return $this->_Read($query, []);
    }

    function lsUDN() {
        $query = "
            SELECT idUDN AS id, UDN AS valor
            FROM udn
            WHERE Stado = 1 AND idUDN NOT IN (8, 10, 7)
            ORDER BY UDN DESC
        ";
        return $this->_Read($query, null);
    }

    function getUserLevel($array) {
        $query = "
            SELECT usr_perfil
            FROM usuarios
            WHERE idUser = ?
        ";
        
        $result = $this->_Read($query, $array);
        
        if (!empty($result)) {
            $perfil = $result[0]['usr_perfil'];
            if ($perfil == 1) return 3;
            if ($perfil == 2) return 2;
            return 1;
        }
        
        return 1;
    }

    function getFileCountsBySection() {
        $query = "
            SELECT 
                s.name AS section_name,
                COUNT(f.id) AS count
            FROM {$this->bd}section s
            LEFT JOIN {$this->bd}file f ON s.id = f.section_id
            GROUP BY s.id, s.name
        ";
        
        $results = $this->_Read($query, []);
        $counts  = ['total' => 0];
        
        if (is_array($results) && !empty($results)) {
            foreach ($results as $row) {
                $key              = strtolower(str_replace(['á','é','í','ó','ú',' '], ['a','e','i','o','u','_'], $row['section_name']));
                $counts[$key]     = (int)$row['count'];
                $counts['total'] += (int)$row['count'];
            }
        }
        
        return $counts;
    }

    function createFile($array) {
        return $this->_Insert([
            'table'  => $this->bd . 'file',
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateFile($array) {
        return $this->_Update([
            'table'  => $this->bd . 'file',
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function existsFileByName($array) {
        $query = "
            SELECT COUNT(*) as count
            FROM {$this->bd}file
            WHERE LOWER(file_name) = LOWER(?)
            AND udn_id = ?
        ";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function getFilesByDateRange($array) {
        $query = "
            SELECT 
                f.id,
                f.file_name,
                f.upload_date,
                f.operation_date,
                f.size_bytes,
                f.extension,
                s.name AS section_name,
                udn.UDN AS udn_name
            FROM {$this->bd}file f
            LEFT JOIN {$this->bd}section s ON f.section_id = s.id
            LEFT JOIN udn ON f.udn_id = udn.idUDN
            WHERE f.operation_date BETWEEN ? AND ?
            AND f.udn_id = ?
            ORDER BY f.operation_date DESC
        ";
        return $this->_Read($query, $array);
    }

    function getTotalFileSize($array) {
        $query = "
            SELECT 
                SUM(size_bytes) AS total_size,
                COUNT(*) AS total_files
            FROM {$this->bd}file
            WHERE udn_id = ?
        ";
        $result = $this->_Read($query, $array);
        return $result[0] ?? ['total_size' => 0, 'total_files' => 0];
    }
}
```

---

### INDEX.PHP [archivos.php]

```php
<?php
    require_once('layout/head.php');
    require_once('layout/core-libraries.php');
?>

<?php require_once('../../layout/navbar.php'); ?>

<!-- CoffeeSoft Framework -->
<script src="../../src/js/coffeeSoft.js"></script>
<script src="https://rawcdn.githack.com/SomxS/Grupo-Varoch/refs/heads/main/src/js/plugins.js"></script>
<script src="https://www.plugins.erp-varoch.com/ERP/JS/complementos.js"></script>

<body class="bg-gray-100">
    <main>
        <section id="sidebar"></section>
        <div id="main__content">
            <div id="root"></div>
        </div>
    </main>
   
    <script src="js/archivos.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
```

---

## 🔧 Reglas de Implementación

### ✅ OBLIGATORIO

1. **Estructura MVC:**
   - SIEMPRE usar la arquitectura MVC (Modelo-Vista-Controlador)
   - Frontend extiende `Templates` de CoffeeSoft
   - Controlador extiende `mdl` (modelo)
   - Modelo extiende `CRUD`

2. **Nomenclatura:**
   - Controlador: `ctrl-[nombre].php`
   - Modelo: `mdl-[nombre].php`
   - Frontend: `[nombre].js`
   - Métodos del controlador: `init()`, `ls()`, `getFile()`, `downloadFile()`, `deleteFile()`
   - Métodos del modelo: `listFiles()`, `getFileById()`, `deleteFileById()`, `createFileLog()`

3. **Seguridad:**
   - SIEMPRE validar sesión de usuario
   - SIEMPRE verificar permisos antes de operaciones críticas
   - SIEMPRE registrar acciones en `file_logs`
   - Usar tokens temporales para descargas (5 min)

4. **Componentes CoffeeSoft:**
   - `primaryLayout()` para estructura principal
   - `tabLayout()` para pestañas
   - `createfilterBar()` para filtros
   - `createTable()` para tablas
   - `swalQuestion()` para confirmaciones
   - `useFetch()` para peticiones AJAX

### ❌ PROHIBIDO

1. **NO** usar `try-catch` en ningún archivo
2. **NO** usar `??` o `isset()` con variables `$_POST`
3. **NO** modificar la estructura del pivote
4. **NO** cambiar nombres de métodos establecidos
5. **NO** omitir validaciones de seguridad
6. **NO** usar `_Select()` en modelos (usar `_Read()`)

---

## 📊 Flujo de Operaciones

### Flujo de Listado
```
Usuario → filterBar → lsFiles() → AJAX → ctrl::ls() → mdl::listFiles() → SQL → Respuesta → Tabla
```

### Flujo de Descarga
```
Usuario → downloadFile(id) → AJAX → ctrl::downloadFile() → Validar sesión → Generar token → 
Registrar log → Retornar URL → Abrir en nueva pestaña
```

### Flujo de Eliminación
```
Usuario → deleteFile(id) → SweetAlert → Confirmar → AJAX → ctrl::deleteFile() → 
Validar permisos → Eliminar archivo físico → Registrar log → mdl::deleteFileById() → 
Actualizar tabla → Actualizar KPIs
```

---

## 🎨 Componentes Visuales

### KPI Cards
- Estilo: `style: "file"`
- Tema: `theme: "light"`
- Grid: `grid-cols-2 md:grid-cols-5`
- Colores: Verde para total, Azul para secciones

### Tabla de Archivos
- Tema: `theme: 'light'`
- Paginación: 25 registros por página
- DataTables: Habilitado
- Columnas centradas: [1, 2, 5]
- Columnas alineadas a derecha: [6]

### Badges de Sección
- Ventas: `bg-green-100 text-green-700`
- Compras: `bg-blue-100 text-blue-700`
- Almacén: `bg-purple-100 text-purple-700`
- Proveedores: `bg-yellow-100 text-yellow-700`

### Badges de Tipo de Archivo
- PDF: `bg-red-100 text-red-700`
- Excel: `bg-green-100 text-green-700`
- Word: `bg-blue-100 text-blue-700`
- Imágenes: `bg-purple-100 text-purple-700`

---

## 📝 Notas Importantes

1. **Permisos por Nivel:**
   - Nivel 1: Solo puede ver archivos de su UDN
   - Nivel 2: Puede ver múltiples UDN
   - Nivel 3: Acceso completo + eliminar archivos

2. **Auditoría:**
   - Todas las operaciones se registran en `file_logs`
   - Se guarda: `file_id`, `user_id`, `action`, `action_date`, `ip_address`

3. **Tokens de Descarga:**
   - Expiran en 5 minutos (300 segundos)
   - Se almacenan en `$_SESSION['download_tokens']`
   - Incluyen: `file_id`, `user_id`, `expiry`, `path`

4. **Formato de Tamaño:**
   - Automático: B → KB → MB → GB
   - Función: `formatFileSize($bytes)`

5. **Extensiones Soportadas:**
   - Documentos: PDF, DOC, DOCX, XLS, XLSX
   - Imágenes: PNG, JPG, JPEG
   - Otros: Personalizable en `renderFileType()`

---

## 🔄 Mantenimiento

Este pivote es **INMUTABLE**. Cualquier cambio debe:
1. Ser discutido con el equipo
2. Actualizarse en TODOS los proyectos que lo usen
3. Documentarse en este archivo

---

**Versión:** 1.0  
**Última actualización:** 2025-01-11  
**Autor:** CoffeeIA ☕  
**Estado:** Aprobado para producción  
**Proyecto Base:** finanzas/captura/archivos

---

## 📚 Referencias

- **CoffeeSoft Framework:** `src/js/coffeSoft.js`
- **Plugins jQuery:** `src/js/plugins.js`
- **CRUD Base:** `conf/_CRUD.php`
- **Utilidades:** `conf/_Utileria.php`
- **Documentación:** `DOC-COFFEESOFT.md`

---

**Fin del Pivote File Manager** 🗂️☕
