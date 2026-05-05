<?php
session_start();
if (empty($_POST['opc'])) exit(0);

require_once '../mdl/mdl-usuarios.php';
require_once '../../../conf/coffeSoft.php';

class ctrl extends mdl {

    function init() {
        return [
            'roles'           => $this->lsNivel(),
            'estados'         => $this->lsEstados(),
            'departamentos'   => parent::lsDepartamentos(),
            'tiposMovimiento' => $this->lsTiposMovimiento()
        ];
    }

    function lsUsuarios() {
        $__row = [];
        $ls = $this->listUsuarios([$_POST['rol'], $_POST['active']]);

        foreach ($ls as $usuario) {
            $__row[] = [
                'id'           => $usuario['id'],
                'ID'           => '#' . str_pad($usuario['id'], 4, '0', STR_PAD_LEFT),
                'Usuario'      => userBadge($usuario['nombre'], '@' . $usuario['usuario']),
                'Email'        => $usuario['email'],
                'Rol'          => rolBadge($usuario['rol']),
                'Telefono'     => $usuario['telefono'] ?: '-',
                'Departamento' => $usuario['departamento'] ?: '-',
                'Fecha'        => formatSpanishDate($usuario['fecha_creacion']),
                'Estado'       => status($usuario['estado']),
                'a'            => actionButtons($usuario['id'])
            ];
        }

        return ['row' => $__row, 'thead' => ''];
    }

    function showUsuarios() {
        $counts = $this->getUsuariosCounts();

        return [
            'status' => 200,
            'counts' => $counts
        ];
    }

    function getUsuario() {
        $id      = $_POST['id'];
        $status  = 500;
        $message = 'Error al obtener los datos';
        $getData = $this->getUsuarioById([$id]);

        if ($getData) {
            $status  = 200;
            $message = 'Datos obtenidos correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $getData
        ];
    }

    function addUsuario() {
        $status  = 500;
        $message = 'No se pudo agregar el usuario';

        $email    = $_POST['email'];
        $usuario  = $_POST['usuario'];
        $nombre   = $_POST['nombre'];
        $password = $_POST['password'];
        $rol      = $_POST['rol'];
        $depto    = $_POST['departamento'];

        if (!empty($email) && $this->existsUsuarioByEmail([$email])) {
            return [
                'status'  => 409,
                'message' => 'El email ya esta registrado'
            ];
        }

        if ($this->existsUsuarioByUsuario([$usuario])) {
            return [
                'status'  => 409,
                'message' => 'El nombre de usuario ya esta en uso'
            ];
        }

        $payload = [
            'Usuario'      => $usuario,
            'Password'     => sha1($password),
            'Nivel'        => resolveNivelId($rol),
            'Email'        => $email,
            'Gerente'      => $nombre,
            'Permiso'      => ($rol === 'admin') ? 1 : null,
            'UDN'          => 1,
            'Area_Usuario' => resolveAreaId($depto)
        ];

        $create = $this->createUsuario($this->util->sql($payload));

        if ($create === true) {
            $status  = 200;
            $message = 'Usuario creado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function editUsuario() {
        $status  = 500;
        $message = 'Error al editar usuario';

        $id       = $_POST['id'];
        $email    = $_POST['email'];
        $usuario  = $_POST['usuario'];
        $nombre   = $_POST['nombre'];
        $password = $_POST['password'];
        $rol      = $_POST['rol'];
        $depto    = $_POST['departamento'];

        $payload = [
            'Usuario'      => $usuario,
            'Nivel'        => resolveNivelId($rol),
            'Email'        => $email,
            'Gerente'      => $nombre,
            'Permiso'      => ($rol === 'admin') ? 1 : null,
            'Area_Usuario' => resolveAreaId($depto)
        ];

        if (!empty($password)) {
            $payload['Password'] = sha1($password);
        }

        $payload['idUsuario'] = $id;

        $edit = $this->updateUsuario($this->util->sql($payload, 1));

        if ($edit === true) {
            $status  = 200;
            $message = 'Usuario actualizado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function disableUsuario() {
        $status  = 500;
        $message = 'Error al desactivar usuario';

        $id      = $_POST['id'];
        $disable = $this->disableUsuarioById([
            'where' => ['idUsuario'],
            'data'  => [$id]
        ]);

        if ($disable === true) {
            $status  = 200;
            $message = 'Usuario desactivado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    // === ROLES ===

    function lsRoles() {
        $__row = [];
        $ls    = $this->listRoles($_POST['active']);

        foreach ($ls as $rol) {
            $a = [];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-blue-100 hover:bg-blue-200 text-blue-500',
                'html'    => '<i class="icon-pencil"></i>',
                'onclick' => 'roles.editRol(' . $rol['id'] . ')'
            ];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-red-100 hover:bg-red-200 text-red-500',
                'html'    => '<i class="icon-block"></i>',
                'onclick' => 'roles.disableRol(' . $rol['id'] . ')'
            ];

            $__row[] = [
                'id'  => $rol['id'],
                'ID'  => '#' . str_pad($rol['id'], 4, '0', STR_PAD_LEFT),
                'Rol' => '<span class="font-semibold text-primary">' . $rol['nombre'] . '</span>',
                'a'   => $a
            ];
        }

        return ['row' => $__row, 'thead' => ''];
    }

    function getRol() {
        $id      = $_POST['id'];
        $status  = 500;
        $message = 'Error al obtener los datos';
        $getData = $this->getRolById([$id]);

        if ($getData) {
            $status  = 200;
            $message = 'Datos obtenidos correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $getData
        ];
    }

    function addRol() {
        $status  = 500;
        $message = 'No se pudo agregar el rol';
        $nombre  = $_POST['nombre'];

        if ($this->existsRolByName([$nombre])) {
            return [
                'status'  => 409,
                'message' => 'Ya existe un rol con ese nombre'
            ];
        }

        $payload = [
            'Nombre_Nivel' => $nombre
        ];

        $create = $this->createRol($this->util->sql($payload));

        if ($create === true) {
            $status  = 200;
            $message = 'Rol creado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function editRol() {
        $status  = 500;
        $message = 'Error al editar rol';
        $id      = $_POST['id'];
        $nombre  = $_POST['nombre'];

        if ($this->existsOtherRolByName([$nombre, $id])) {
            return [
                'status'  => 409,
                'message' => 'Ya existe otro rol con ese nombre'
            ];
        }

        $payload = [
            'Nombre_Nivel' => $nombre,
            'idNivel'      => $id
        ];

        $edit = $this->updateRol($this->util->sql($payload, 1));

        if ($edit === true) {
            $status  = 200;
            $message = 'Rol actualizado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function disableRol() {
        $status  = 500;
        $message = 'Error al desactivar rol';
        $id      = $_POST['id'];

        $disable = $this->disableRolById([
            'where' => ['idNivel'],
            'data'  => [$id]
        ]);

        if ($disable === true) {
            $status  = 200;
            $message = 'Rol desactivado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    // === DEPARTAMENTOS ===

    function lsDepartamentos() {
        $__row = [];
        $ls    = $this->listDepartamentos($_POST['active']);

        foreach ($ls as $depto) {
            $a = [];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-blue-100 hover:bg-blue-200 text-blue-500',
                'html'    => '<i class="icon-pencil"></i>',
                'onclick' => 'departamentos.editDepartamento(' . $depto['id'] . ')'
            ];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-red-100 hover:bg-red-200 text-red-500',
                'html'    => '<i class="icon-block"></i>',
                'onclick' => 'departamentos.disableDepartamento(' . $depto['id'] . ')'
            ];

            $__row[] = [
                'id'           => $depto['id'],
                'ID'           => '#' . str_pad($depto['id'], 4, '0', STR_PAD_LEFT),
                'Departamento' => '<span class="font-semibold text-primary">' . $depto['nombre'] . '</span>',
                'a'            => $a
            ];
        }

        return ['row' => $__row, 'thead' => ''];
    }

    function getDepartamento() {
        $id      = $_POST['id'];
        $status  = 500;
        $message = 'Error al obtener los datos';
        $getData = $this->getDepartamentoById([$id]);

        if ($getData) {
            $status  = 200;
            $message = 'Datos obtenidos correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $getData
        ];
    }

    function addDepartamento() {
        $status  = 500;
        $message = 'No se pudo agregar el departamento';
        $nombre  = $_POST['nombre'];

        if ($this->existsDepartamentoByName([$nombre])) {
            return [
                'status'  => 409,
                'message' => 'Ya existe un departamento con ese nombre'
            ];
        }

        $payload = [
            'Area' => $nombre
        ];

        $create = $this->createDepartamento($this->util->sql($payload));

        if ($create === true) {
            $status  = 200;
            $message = 'Departamento creado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function editDepartamento() {
        $status  = 500;
        $message = 'Error al editar departamento';
        $id      = $_POST['id'];
        $nombre  = $_POST['nombre'];

        if ($this->existsOtherDepartamentoByName([$nombre, $id])) {
            return [
                'status'  => 409,
                'message' => 'Ya existe otro departamento con ese nombre'
            ];
        }

        $payload = [
            'Area'   => $nombre,
            'idArea' => $id
        ];

        $edit = $this->updateDepartamento($this->util->sql($payload, 1));

        if ($edit === true) {
            $status  = 200;
            $message = 'Departamento actualizado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function disableDepartamento() {
        $status  = 500;
        $message = 'Error al desactivar departamento';
        $id      = $_POST['id'];

        $disable = $this->disableDepartamentoById([
            'where' => ['idArea'],
            'data'  => [$id]
        ]);

        if ($disable === true) {
            $status  = 200;
            $message = 'Departamento desactivado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    // === CATEGORÍAS FINANZAS ===

    function lsCategorias() {
        $__row = [];
        $tipoMovimiento = $_POST['tipoMovimiento'];
        $activeCat = $_POST['activeCat'];
        $ls    = $this->listCategorias($tipoMovimiento, $activeCat);

        foreach ($ls as $cat) {
            $a = [];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-amber-100 hover:bg-amber-200 text-amber-700',
                'html'    => '<i class="icon-folder-open"></i>',
                'onclick' => 'categorias.selectCategoria(' . $cat['id'] . ', "' . addslashes($cat['nombre']) . '")'
            ];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-blue-100 hover:bg-blue-200 text-blue-500',
                'html'    => '<i class="icon-pencil"></i>',
                'onclick' => 'categorias.editCategoria(' . $cat['id'] . ')'
            ];

            $toggleIcon = $cat['activo'] == '1' ? '<i class="icon-toggle-on text-emerald-600"></i>' : '<i class="icon-toggle-off text-gray-400"></i>';
            $toggleClass = $cat['activo'] == '1' ? 'bg-emerald-100 hover:bg-emerald-200 text-emerald-700' : 'bg-gray-100 hover:bg-gray-200 text-gray-500';

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded ' . $toggleClass,
                'html'    => $toggleIcon,
                'onclick' => 'categorias.disableCategoria(' . $cat['id'] . ')'
            ];

            $__row[] = [
                'id'             => $cat['id'],
                'ID'             => '#' . str_pad($cat['id'], 4, '0', STR_PAD_LEFT),
                'Categoría'      => '<span class="font-semibold text-primary">' . $cat['nombre'] . '</span>',
                'Tipo Movimiento'=> tipoMovimientoBadge($cat['tipo_movimiento']),
                'a'              => $a
            ];
        }

        return ['row' => $__row, 'thead' => ''];
    }

    function getCategoria() {
        $id      = $_POST['id'];
        $status  = 500;
        $message = 'Error al obtener los datos';
        $getData = $this->getCategoriaById([$id]);

        if ($getData) {
            $status  = 200;
            $message = 'Datos obtenidos correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $getData
        ];
    }

    function addCategoria() {
        $status  = 500;
        $message = 'No se pudo agregar la categoría';
        $nombre  = $_POST['nombre'];
        $tipo    = $_POST['tipoMovimiento'];

        if ($this->existsCategoriaByName([$nombre])) {
            return [
                'status'  => 409,
                'message' => 'Ya existe una categoría con ese nombre'
            ];
        }

        $payload = [
            'Categoria'     => $nombre,
            'id_TMovimiento'=> $tipo,
            // 'Stado'         => 1,
            // 'id_UDN'        => 1
        ];

        $create = $this->createCategoria($this->util->sql($payload));

        if ($create == true) {
            $status  = 200;
            $message = 'Categoría creada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function editCategoria() {
        $status  = 500;
        $message = 'Error al editar categoría';
        $id      = $_POST['id'];
        $nombre  = $_POST['nombre'];
        $tipo    = $_POST['tipoMovimiento'];

        if ($this->existsOtherCategoriaByName([$nombre, $id])) {
            return [
                'status'  => 409,
                'message' => 'Ya existe otra categoría con ese nombre'
            ];
        }

        $payload = [
            'Categoria'      => $nombre,
            'id_TMovimiento' => $tipo,
            'idCategoria'    => $id
        ];

        $edit = $this->updateCategoria($this->util->sql($payload, 1));

        if ($edit === true) {
            $status  = 200;
            $message = 'Categoría actualizada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function disableCategoria() {
        $status  = 500;
        $message = 'Error al desactivar categoría';
        $id      = $_POST['id'];

        $disable = $this->disableCategoriaById([
            'where' => ['idCategoria'],
            'data'  => [$id]
        ]);

        if ($disable === true) {
            $status  = 200;
            $message = 'Categoría desactivada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    // === SUBCATEGORÍAS ===

    function lsSubcategorias() {
        $__row       = [];
        $idCategoria = $_POST['idCategoria'];
        $active      = $_POST['activeSub'];
        $ls          = $this->listSubcategorias($idCategoria, $active);

        foreach ($ls as $sub) {
            $a = [];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-blue-100 hover:bg-blue-200 text-blue-500',
                'html'    => '<i class="icon-pencil"></i>',
                'onclick' => 'categorias.editSubcategoria(' . $sub['id'] . ')'
            ];

            $a[] = [
                'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-red-100 hover:bg-red-200 text-red-500',
                'html'    => '<i class="icon-block"></i>',
                'onclick' => 'categorias.disableSubcategoria(' . $sub['id'] . ')'
            ];

            $__row[] = [
                'id'            => $sub['id'],
                'ID'            => '#' . str_pad($sub['id'], 4, '0', STR_PAD_LEFT),
                'Subcategoría'  => '<span class="font-semibold">' . $sub['nombre'] . '</span>',
                'Tarifa'        => $sub['tarifa'] ? evaluar($sub['tarifa']) : '-',
                'a'             => $a
            ];
        }

        return ['row' => $__row, 'thead' => ''];
    }

    function getSubcategoria() {
        $id      = $_POST['id'];
        $status  = 500;
        $message = 'Error al obtener los datos';
        $getData = $this->getSubcategoriaById([$id]);

        if ($getData) {
            $status  = 200;
            $message = 'Datos obtenidos correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $getData
        ];
    }

    function addSubcategoria() {
        $status      = 500;
        $message     = 'No se pudo agregar la subcategoría';
        $nombre      = $_POST['nombre'];
        $idCategoria = $_POST['idCategoria'];
        $tarifa      = $_POST['tarifa'];
        $activo      = $_POST['activo'];

        if ($this->existsSubcategoriaByName([$nombre, $idCategoria])) {
            return [
                'status'  => 409,
                'message' => 'Ya existe una subcategoría con ese nombre en esta categoría'
            ];
        }

        $payload = [
            'Subcategoria' => $nombre,
            'id_Categoria' => $idCategoria,
            'tarifa'       => $tarifa ?: 0,
            'activo'       => $activo,
            'Stado'        => 1,
            'id_grupo'     => 1
        ];

        $create = $this->createSubcategoria($this->util->sql($payload));

        if ($create === true) {
            $status  = 200;
            $message = 'Subcategoría creada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function editSubcategoria() {
        $status  = 500;
        $message = 'Error al editar subcategoría';
        $id      = $_POST['id'];
        $nombre  = $_POST['nombre'];
        $tarifa  = $_POST['tarifa'];
        $activo  = $_POST['activo'];

        $payload = [
            'Subcategoria'   => $nombre,
            'tarifa'         => $tarifa ?: 0,
            'activo'         => $activo,
            'idSubcategoria' => $id
        ];

        $edit = $this->updateSubcategoria($this->util->sql($payload, 1));

        if ($edit === true) {
            $status  = 200;
            $message = 'Subcategoría actualizada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function disableSubcategoria() {
        $status  = 500;
        $message = 'Error al desactivar subcategoría';
        $id      = $_POST['id'];

        $disable = $this->disableSubcategoriaById([
            'where' => ['idSubcategoria'],
            'data'  => [$id]
        ]);

        if ($disable === true) {
            $status  = 200;
            $message = 'Subcategoría desactivada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }
}

// Complements
function resolveNivelId($rol) {
    $map = [
        'admin'  => 1,
        'editor' => 5,
        'viewer' => 3
    ];
    return isset($map[$rol]) ? $map[$rol] : 5;
}

function resolveAreaId($departamento) {
    $map = [
        'Direccion General' => 1,
        'Finanzas'          => 4,
        'Recursos Humanos'  => 6,
        'Operaciones'       => 5,
        'Ventas'            => 5
    ];
    return isset($map[$departamento]) ? $map[$departamento] : 1;
}

function userBadge($fullname, $subtitle) {
    $colors = [
        'bg-blue-600',
        'bg-emerald-600',
        'bg-purple-600',
        'bg-rose-600',
        'bg-amber-600',
        'bg-cyan-600',
        'bg-indigo-600',
        'bg-teal-600'
    ];

    $parts = explode(' ', trim($fullname));
    $initials = strtoupper(substr($parts[0], 0, 1));
    if (count($parts) > 1) {
        $initials .= strtoupper(substr(end($parts), 0, 1));
    }

    $bgColor = $colors[abs(crc32($fullname)) % count($colors)];

    return '<div class="flex items-center gap-3">'
         . '<div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold ' . $bgColor . '">'
         . $initials
         . '</div>'
         . '<div>'
         . '<div class="text-sm font-semibold ">' . $fullname . '</div>'
         . '<div class="text-xs text-gray-500">' . $subtitle . '</div>'
         . '</div>'
         . '</div>';
}

function rolBadge($rol) {
    $map = [
        'admin'             => ['bg' => 'bg-purple-200', 'text' => 'text-purple-700', 'label' => 'Administrador'],
        'editor'            => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'label' => 'Editor'],
        'viewer'            => ['bg' => 'bg-gray-100',   'text' => 'text-gray-700',   'label' => 'Lector'],
        'Direccion General' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Direccion General'],
        'Finanzas'          => ['bg' => 'bg-emerald-100','text' => 'text-emerald-700','label' => 'Finanzas'],
        'Mantenimiento'     => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'label' => 'Mantenimiento'],
        'Administrativo'    => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'label' => 'Administrativo'],
        'Cultivo'           => ['bg' => 'bg-teal-100',   'text' => 'text-teal-700',   'label' => 'Cultivo']
    ];
    $style = $map[$rol] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => ($rol ?: '-')];
    return '<span class="px-3 py-1 rounded-full text-xs font-bold ' . $style['bg'] . ' ' . $style['text'] . '">' . $style['label'] . '</span>';
}

function status($statusId) {
    $statuses = [
        'activo'    => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'label' => 'Activo'],
        'inactivo'  => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'label' => 'Inactivo'],
        'pendiente' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Pendiente']
    ];

    $style = $statuses[$statusId] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'label' => 'Desconocido'];

    return '<span class="px-3 py-1 rounded-full text-xs font-bold ' . $style['bg'] . ' ' . $style['text'] . '">' . $style['label'] . '</span>';
}

function actionButtons($id) {
    return [
        [
            'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-green-100 hover:bg-green-200 text-green-700',
            'html'    => '<i class="icon-eye"></i>',
            'onclick' => "usuarios.getUsuario($id)"
        ],
        [
            'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-blue-100 hover:bg-blue-200 text-blue-500 ',
            'html'    => '<i class="icon-pencil"></i>',
            'onclick' => "usuarios.editUsuario($id)"
        ],
        [
            'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-red-100 hover:bg-red-200 text-red-500',
            'html'    => '<i class="icon-block"></i>',
            'onclick' => "usuarios.disableUsuario($id)"
        ]
    ];
}

function tipoMovimientoBadge($tipo) {
    $map = [
        'Ingreso' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'icon' => '↑'],
        'Egreso'  => ['bg' => 'bg-red-100',   'text' => 'text-red-700',   'icon' => '↓'],
        'Neutro'  => ['bg' => 'bg-gray-100',  'text' => 'text-gray-700',  'icon' => '↔']
    ];
    
    $style = $map[$tipo] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => '•'];
    
    return '<span class="px-3 py-1 rounded-full text-xs font-bold ' . $style['bg'] . ' ' . $style['text'] . '">' 
         . $style['icon'] . ' ' . ($tipo ?: 'Sin tipo') 
         . '</span>';
}

$obj = new ctrl();
$fn  = $_POST['opc'];
echo json_encode($obj->$fn());
