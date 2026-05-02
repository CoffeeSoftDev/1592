<?php
session_start();
if (empty($_POST['opc'])) exit(0);

require_once '../mdl/mdl-usuarios.php';
require_once '../../../conf/coffeSoft.php';

class ctrl extends mdl {

    function init() {
        return [
            'roles'         => $this->lsRoles(),
            'estados'       => $this->lsEstados(),
            'departamentos' => $this->lsDepartamentos()
        ];
    }

    function lsUsuarios() {
        $__row = [];
        $ls    = $this->listUsuarios([]);

        foreach ($ls as $usuario) {
            $iniciales = initialsFromName($usuario['nombre']);
            $avatar    = avatarGradient($usuario['id']);

            $__row[] = [
                'id'            => $usuario['id'],
                'ID'            => '<span class="text-gray-500">#' . str_pad($usuario['id'], 4, '0', STR_PAD_LEFT) . '</span>',
                'Usuario'       => userCell($iniciales, $avatar, $usuario['nombre'], $usuario['usuario']),
                'Email'         => '<span class="text-gray-500">' . htmlspecialchars($usuario['email']) . '</span>',
                'Rol'           => rolBadge($usuario['rol']),
                'Telefono'      => '<span class="text-gray-500">' . htmlspecialchars($usuario['telefono'] ?: '-') . '</span>',
                'Departamento'  => '<span class="text-gray-500">' . htmlspecialchars($usuario['departamento'] ?: '-') . '</span>',
                'Fecha'         => '<span class="text-gray-500">' . $usuario['fecha_creacion'] . '</span>',
                'Estado'        => estadoBadge($usuario['estado']),
                'a'             => actionButtons($usuario['id'])
            ];
        }

        return [
            'row'   => $__row,
            'thead' => ''
        ];
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

function initialsFromName($nombre) {
    $parts = preg_split('/\s+/', trim($nombre));
    $a = isset($parts[0][0]) ? mb_strtoupper($parts[0][0]) : '';
    $b = isset($parts[1][0]) ? mb_strtoupper($parts[1][0]) : '';
    return $a . $b;
}

function avatarGradient($id) {
    $gradients = [
        'from-purple-600 to-blue-600',
        'from-pink-500 to-orange-500',
        'from-emerald-500 to-cyan-500',
        'from-amber-500 to-red-500',
        'from-indigo-500 to-purple-500',
        'from-teal-500 to-green-500'
    ];
    return $gradients[$id % count($gradients)];
}

function userCell($iniciales, $gradient, $nombre, $usuario) {
    return '
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gradient-to-br ' . $gradient . ' flex items-center justify-center text-white text-[11px] font-semibold">' . $iniciales . '</div>
            <div>
                <div class="font-medium text-primary">' . htmlspecialchars($nombre) . '</div>
                <div class="text-[11px] text-gray-500">@' . htmlspecialchars($usuario) . '</div>
            </div>
        </div>';
}

function rolBadge($rol) {
    $map = [
        'admin'             => ['bg' => 'bg-purple-500/15', 'text' => 'text-purple-400', 'label' => 'Administrador'],
        'editor'            => ['bg' => 'bg-blue-500/15',   'text' => 'text-blue-400',   'label' => 'Editor'],
        'viewer'            => ['bg' => 'bg-gray-500/15',   'text' => 'text-gray-400',   'label' => 'Lector'],
        'Direccion General' => ['bg' => 'bg-purple-500/15', 'text' => 'text-purple-400', 'label' => 'Direccion General'],
        'Finanzas'          => ['bg' => 'bg-emerald-500/15','text' => 'text-emerald-400','label' => 'Finanzas'],
        'Mantenimiento'     => ['bg' => 'bg-amber-500/15',  'text' => 'text-amber-400',  'label' => 'Mantenimiento'],
        'Administrativo'    => ['bg' => 'bg-blue-500/15',   'text' => 'text-blue-400',   'label' => 'Administrativo'],
        'Cultivo'           => ['bg' => 'bg-teal-500/15',   'text' => 'text-teal-400',   'label' => 'Cultivo']
    ];
    $label = $rol !== '' ? $rol : '-';
    $style = isset($map[$rol]) ? $map[$rol] : ['bg' => 'bg-gray-500/15', 'text' => 'text-gray-400', 'label' => $label];
    return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium ' . $style['bg'] . ' ' . $style['text'] . '">' . htmlspecialchars($style['label']) . '</span>';
}

function estadoBadge($estado) {
    $map = [
        'activo'    => ['bg' => 'bg-green-500/15',  'text' => 'text-green-400',  'border' => 'border-green-500/30', 'label' => 'Activo'],
        'inactivo'  => ['bg' => 'bg-red-500/15',    'text' => 'text-red-400',    'border' => 'border-red-500/30',   'label' => 'Inactivo'],
        'pendiente' => ['bg' => 'bg-yellow-500/15', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/30','label' => 'Pendiente']
    ];
    $style = isset($map[$estado]) ? $map[$estado] : ['bg' => 'bg-gray-500/15', 'text' => 'text-gray-400', 'border' => 'border-gray-500/30', 'label' => ucfirst($estado)];
    return '<span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium border ' . $style['bg'] . ' ' . $style['text'] . ' ' . $style['border'] . '">' . $style['label'] . '</span>';
}

function actionButtons($id) {
    return [
        [
            'class'   => 'inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-purple-500/15 hover:text-purple-400 me-1',
            'html'    => '<i class="icon-eye"></i>',
            'onclick' => "usuarios.getUsuario($id)"
        ],
        [
            'class'   => 'inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-purple-500/15 hover:text-purple-400 me-1',
            'html'    => '<i class="icon-pencil"></i>',
            'onclick' => "usuarios.editUsuario($id)"
        ],
        [
            'class'   => 'inline-flex items-center justify-center w-8 h-8 rounded-md text-gray-400 hover:bg-red-500/15 hover:text-red-400',
            'html'    => '<i class="icon-trash"></i>',
            'onclick' => "usuarios.deleteUsuario($id)"
        ]
    ];
}

$obj = new ctrl();
$fn  = $_POST['opc'];
echo json_encode($obj->$fn());
