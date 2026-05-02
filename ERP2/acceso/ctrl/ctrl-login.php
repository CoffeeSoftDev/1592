<?php
session_start();
if (empty($_POST['opc'])) exit(0);

require_once '../mdl/mdl-login.php';
require_once '../../conf/coffeSoft.php';

class ctrl extends mdl {

    function init() {
        return [
            'status'  => 200,
            'project' => '15-92'
        ];
    }

    function addSession() {
        $status  = 401;
        $message = 'Usuario o contraseña incorrectos';
        $data    = null;

        $username = mb_strtoupper(str_replace("'", "", $_POST['usuario']), 'UTF-8');
        $password = str_replace("'", "", $_POST['clave']);

        $credentials = $this->getUserByCredentials([$username, $password, $password]);

        if (!empty($credentials) && count($credentials) > 0) {
            $user = $credentials[0];

            $expira = time() + (365 * 24 * 60 * 60);
            setcookie("IDU", $user['id'], $expira, "/");
            setcookie("IDP", $user['role_id'], $expira, "/");

            unset($user['id']);
            unset($user['role_id']);

            $status  = 200;
            $message = 'Bienvenido';
            $data    = $user;
        }

        return [
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ];
    }

    function deleteSession() {
        setcookie("IDU", "", time() - 3600, "/");
        setcookie("IDP", "", time() - 3600, "/");

        return [
            'status'  => 200,
            'message' => 'Sesión cerrada correctamente'
        ];
    }
}

$obj = new ctrl();
echo json_encode($obj->{$_POST['opc']}());
