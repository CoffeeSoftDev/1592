<?php
session_start();

if (empty($_POST['opc'])) {
    exit(0);
}

require_once('../mdl/mdl-tareas.php');

class ctrl extends mdl {

    function init() {
        $lsPriority = $this->lsPriority();
        $lsStatus   = $this->lsStatus();

        return [
            'priority' => $lsPriority,
            'status'   => $lsStatus
        ];
    }

    function ls() {
        $__row = [];
        $ls = $this->listTareas([]);

        foreach ($ls as $key) {
            $__row[] = [
                'id'          => $key['id'],
                'Titulo'      => $key['title'],
                'Descripcion' => $key['description'],
                'Prioridad'   => priority($key['priority']),
                'Estado'      => status($key['status']),
                'Creacion'    => $key['created_at'],
                'a'           => btns($key['id'])
            ];
        }

        return [
            'row'   => $__row,
            'thead' => ''
        ];
    }

    function getTarea() {
        $status  = 500;
        $message = 'Error al obtener los datos';
        $getTarea = $this->getTareaById([$_POST['id']]);

        if ($getTarea) {
            $status  = 200;
            $message = 'Datos obtenidos correctamente.';
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $getTarea
        ];
    }

    function addTarea() {
        $status  = 500;
        $message = 'No se pudo agregar la tarea';
        $_POST['created_at'] = date('Y-m-d H:i:s');

        $exists = $this->existsTareaByName([$_POST['title']]);

        if ($exists === 0) {
            $create = $this->createTarea($this->util->sql($_POST));
            if ($create) {
                $status  = 200;
                $message = 'Tarea agregada correctamente';
            }
        } else {
            $status  = 409;
            $message = 'Ya existe una tarea con ese titulo.';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function editTarea() {
        $id      = $_POST['id'];
        $status  = 500;
        $message = 'Error al editar tarea';
        $edit    = $this->updateTarea($this->util->sql($_POST, 1));

        if ($edit) {
            $status  = 200;
            $message = 'Tarea editada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function deleteTarea() {
        $status  = 500;
        $message = 'Error al eliminar tarea';

        $values = $this->util->sql(['id' => $_POST['id']], 1);
        $delete = $this->deleteTareaById($values);

        if ($delete) {
            $status  = 200;
            $message = 'Tarea eliminada correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function statusTarea() {
        $status  = 500;
        $message = 'No se pudo actualizar el estado de la tarea';

        $update = $this->updateTarea($this->util->sql($_POST, 1));

        if ($update) {
            $status  = 200;
            $message = 'El estado de la tarea se actualizo correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }
}

// Complements
function priority($priorityId) {
    $priorities = [
        1 => '<span class="badge bg-green-500 text-white px-2 py-1 rounded">Baja</span>',
        2 => '<span class="badge bg-yellow-500 text-white px-2 py-1 rounded">Media</span>',
        3 => '<span class="badge bg-red-500 text-white px-2 py-1 rounded">Alta</span>'
    ];
    return $priorities[$priorityId] ?? '<span class="badge bg-gray-500 text-white px-2 py-1 rounded">Sin definir</span>';
}

function status($statusId) {
    $statuses = [
        1 => '<span class="badge bg-blue-500 text-white px-2 py-1 rounded">Pendiente</span>',
        2 => '<span class="badge bg-yellow-500 text-white px-2 py-1 rounded">En progreso</span>',
        3 => '<span class="badge bg-green-500 text-white px-2 py-1 rounded">Completada</span>'
    ];
    return $statuses[$statusId] ?? '<span class="badge bg-gray-500 text-white px-2 py-1 rounded">Desconocido</span>';
}

function btns($id) {
    return [
        [
            'color' => 'primary',
            'icon'  => 'icon-edit',
            'fn'    => "app.editTarea($id)",
            'id'    => 'btnEdit' . $id
        ],
        [
            'color' => 'danger',
            'icon'  => 'icon-trash',
            'fn'    => "app.deleteTarea($id)",
            'id'    => 'btnDelete' . $id
        ]
    ];
}

$obj    = new ctrl();
$fn     = $_POST['opc'];
$encode = $obj->$fn();

echo json_encode($encode);
