<?php
require_once '../../conf/_CRUD.php';
require_once '../../conf/_Utileria.php';

class mdl extends CRUD {

    public $util;
    public $bd;

    function __construct() {
        $this->util = new Utileria();
        $this->bd = "hgpqgijw_erp.";
    }

    function getUserByCredentials($array) {
        $query = "
            SELECT
                u.idUser AS id,
                u.usser AS username,
                u.usr_perfil AS role_id,
                d.dir_ruta AS path
            FROM {$this->bd}usuarios u
            INNER JOIN {$this->bd}permisos p ON p.id_Perfil = u.usr_perfil
            INNER JOIN {$this->bd}directorios d ON d.idDirectorio = p.idDirectorio
            WHERE u.usser = ?
                AND (u.keey = MD5(?) OR u.keey2 = MD5(?))
                AND u.usr_estado = 1
                AND d.dir_estado = 1
                AND d.dir_visible = 1
            ORDER BY d.dir_orden ASC
            LIMIT 1
        ";
        return $this->_Read($query, $array);
    }

    function getUserById($array) {
        $query = "
            SELECT
                idUser AS id,
                usser AS username,
                usr_perfil AS role_id,
                usr_estado AS active
            FROM {$this->bd}usuarios
            WHERE idUser = ?
            LIMIT 1
        ";
        return $this->_Read($query, $array);
    }

    function existsUserByName($array) {
        $query = "
            SELECT idUser
            FROM {$this->bd}usuarios
            WHERE usser = ?
            LIMIT 1
        ";
        return $this->_Read($query, $array);
    }
}
