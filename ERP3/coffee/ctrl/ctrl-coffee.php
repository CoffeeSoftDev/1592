<?php
session_start();
if (empty($_POST['opc'])) exit(0);

require_once '../mdl/mdl-coffee.php';

class ctrl extends mdl {

    function init() {
        return [
            'status' => $this->lsStatus(),
        ];
    }

    function ls() {
        $__row = [];

        $ls = $this->listShowcase(['components']);

        foreach ($ls as $key) {
            $__row[] = [
                'id'          => $key['id'],
                'Nombre'      => $key['name'],
                'Categoria'   => $key['category'],
                'Descripcion' => $key['description'],
                'Estado'      => status($key['status']),
                'opc'         => 0
            ];
        }

        return ['row' => $__row];
    }

    function addShowcase() {
        $status  = 500;
        $message = 'No se pudo agregar el registro';

        $_POST['date_creation'] = date('Y-m-d H:i:s');

        $create = $this->createShowcase($this->util->sql($_POST));

        if ($create) {
            $status  = 200;
            $message = 'Registro agregado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message,
        ];
    }

    function getShowcase() {
        $status  = 500;
        $message = 'Error al obtener los datos';
        $getData = $this->getShowcaseById([$_POST['id']]);

        if ($getData) {
            $status  = 200;
            $message = 'Datos obtenidos correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $getData,
        ];
    }

    function editShowcase() {
        $id      = $_POST['id'];
        $status  = 500;
        $message = 'Error al editar registro';
        $edit    = $this->updateShowcase($this->util->sql($_POST, 1));

        if ($edit) {
            $status  = 200;
            $message = 'Registro editado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }

    function deleteShowcase() {
        $status  = 500;
        $message = 'Error al eliminar registro';

        $values = $this->util->sql(['id' => $_POST['id']], 1);
        $delete = $this->deleteShowcaseById($values);

        if ($delete) {
            $status  = 200;
            $message = 'Registro eliminado correctamente';
        }

        return [
            'status'  => $status,
            'message' => $message
        ];
    }
}

// Complements
function status($statusId) {
    $statuses = [
        1 => '<span class="badge bg-success">Activo</span>',
        2 => '<span class="badge bg-danger">Inactivo</span>',
    ];

    return $statuses[$statusId] ?? '<span class="badge bg-secondary">Desconocido</span>';
}

$obj = new ctrl();
$fn = $_POST['opc'];
$encode = $obj->$fn();
echo json_encode($encode);
