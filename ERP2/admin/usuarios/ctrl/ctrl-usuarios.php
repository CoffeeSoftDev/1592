<?php
session_start();
if (empty($_POST['opc'])) exit(0);

require_once '../mdl/mdl-usuarios.php';
require_once '../../../conf/coffeSoft.php';

class ctrl extends mdl {

    function init() {
        return [
            'roles'         => $this->lsNivel(),
            'estados'       => $this->lsEstados(),
            'departamentos' => $this->lsDepartamentos()
        ];
    }

    function lsUsuarios() {
        $__row = [];
        $ls = $this->listUsuarios([$_POST['rol']]);

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

    function statusUsuario() {
        return [
            'status'  => 200,
            'message' => 'La tabla legacy no maneja estado; operacion omitida'
        ];
    }

    function deleteUsuario() {
        $status  = 500;
        $message = 'Error al eliminar usuario';

        $id     = $_POST['id'];
        $delete = $this->deleteUsuarioById([
            'where' => ['idUsuario'],
            'data'  => [$id]
        ]);

        if ($delete === true) {
            $status  = 200;
            $message = 'Usuario eliminado correctamente';
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
            'html'    => '<i data-lucide="eye" class="w-4 h-4"></i>',
            'onclick' => "usuarios.getUsuario($id)"
        ],
        [
            'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-blue-100 hover:bg-blue-200 text-blue-500 ',
            'html'    => '<i data-lucide="pencil" class="w-4 h-4"></i>',
            'onclick' => "usuarios.editUsuario($id)"
        ],
        [
            'class'   => 'inline-flex items-center px-2 py-2 text-sm rounded bg-red-100 hover:bg-red-200 text-red-500',
            'html'    => '<i data-lucide="trash-2" class="w-4 h-4"></i>',
            'onclick' => "usuarios.deleteUsuario($id)"
        ]
    ];
}

$obj = new ctrl();
$fn  = $_POST['opc'];
echo json_encode($obj->$fn());
