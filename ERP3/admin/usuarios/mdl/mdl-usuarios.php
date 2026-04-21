<?php
require_once '../../../conf/_CRUD.php';
require_once '../../../conf/_Utileria.php';

class mdl extends CRUD {

    public $util;
    public $bd;

    function __construct() {
        $this->util = new Utileria();
        $this->bd   = "hgpqgijw_usuarios.";
    }

    function listUsuarios($array) {
        $query = "
            SELECT
                u.idUsuario                            AS id,
                COALESCE(NULLIF(TRIM(u.Gerente), ''), u.Usuario) AS nombre,
                u.Usuario                              AS usuario,
                COALESCE(u.Email, '')                  AS email,
                NULL                                   AS telefono,
                COALESCE(a.Area, '')                   AS departamento,
                COALESCE(n.Nombre_Nivel, '')           AS rol,
                'activo'                               AS estado,
                0                                      AS two_fa_enabled,
                ''                                     AS avatar,
                '-'                                    AS fecha_creacion,
                u.Nivel                                AS nivel_id,
                u.Area_Usuario                         AS area_id,
                u.UDN                                  AS udn_id,
                u.Permiso                              AS permiso_id
            FROM hgpqgijw_usuarios.usuarios u
            LEFT JOIN hgpqgijw_usuarios.nivel n ON n.idNivel = u.Nivel
            LEFT JOIN hgpqgijw_usuarios.area  a ON a.idArea  = u.Area_Usuario
            ORDER BY u.idUsuario ASC
        ";
        return $this->_Read($query, null);
    }

    function getUsuarioById($array) {
        $query = "
            SELECT
                u.idUsuario                            AS id,
                COALESCE(NULLIF(TRIM(u.Gerente), ''), u.Usuario) AS nombre,
                u.Usuario                              AS usuario,
                COALESCE(u.Email, '')                  AS email,
                ''                                     AS telefono,
                COALESCE(a.Area, '')                   AS departamento,
                COALESCE(n.Nombre_Nivel, '')           AS rol,
                'activo'                               AS estado,
                0                                      AS two_fa_enabled,
                '-'                                    AS fecha_creacion,
                u.Nivel                                AS nivel_id,
                u.Area_Usuario                         AS area_id,
                u.UDN                                  AS udn_id,
                u.Permiso                              AS permiso_id
            FROM hgpqgijw_usuarios.usuarios u
            LEFT JOIN hgpqgijw_usuarios.nivel n ON n.idNivel = u.Nivel
            LEFT JOIN hgpqgijw_usuarios.area  a ON a.idArea  = u.Area_Usuario
            WHERE u.idUsuario = ?
        ";
        return $this->_Read($query, $array);
    }

    function getUsuariosCounts() {
        $query = "
            SELECT
                COUNT(*)                                          AS total,
                COUNT(*)                                          AS activos,
                0                                                 AS inactivos,
                SUM(CASE WHEN u.Permiso = 1 THEN 1 ELSE 0 END)    AS administradores
            FROM hgpqgijw_usuarios.usuarios u
        ";

        $result = $this->_Read($query, []);

        if (!empty($result)) {
            return [
                'total'           => (int) $result[0]['total'],
                'activos'         => (int) $result[0]['activos'],
                'inactivos'       => (int) $result[0]['inactivos'],
                'administradores' => (int) $result[0]['administradores']
            ];
        }

        return [
            'total'           => 0,
            'activos'         => 0,
            'inactivos'       => 0,
            'administradores' => 0
        ];
    }

    function existsUsuarioByEmail($array) {
        $query = "
            SELECT COUNT(*) AS count
            FROM hgpqgijw_usuarios.usuarios
            WHERE LOWER(Email) = LOWER(?)
        ";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function existsUsuarioByUsuario($array) {
        $query = "
            SELECT COUNT(*) AS count
            FROM hgpqgijw_usuarios.usuarios
            WHERE LOWER(Usuario) = LOWER(?)
        ";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function createUsuario($array) {
        return $this->_Insert([
            'table'  => "hgpqgijw_usuarios.usuarios",
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateUsuario($array) {
        return $this->_Update([
            'table'  => "hgpqgijw_usuarios.usuarios",
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function deleteUsuarioById($array) {
        return $this->_Delete([
            'table' => "hgpqgijw_usuarios.usuarios",
            'where' => $array['where'],
            'data'  => $array['data']
        ]);
    }

    function lsNivel() {
        return $this->_Read("SELECT idNivel AS id, Nombre_Nivel AS valor FROM hgpqgijw_usuarios.nivel ORDER BY Nombre_Nivel ASC", null);
    }

    function lsArea() {
        return $this->_Read("SELECT idArea AS id, Area AS valor FROM hgpqgijw_usuarios.area ORDER BY Area ASC", null);
    }

    function lsRoles() {
        return [
            ['id' => 'admin',  'valor' => 'Administrador'],
            ['id' => 'editor', 'valor' => 'Editor'],
            ['id' => 'viewer', 'valor' => 'Lector']
        ];
    }

    function lsEstados() {
        return [
            ['id' => 'activo',    'valor' => 'Activo'],
            ['id' => 'inactivo',  'valor' => 'Inactivo'],
            ['id' => 'pendiente', 'valor' => 'Pendiente']
        ];
    }

    function lsDepartamentos() {
        return [
            ['id' => 'Direccion General', 'valor' => 'Direccion General'],
            ['id' => 'Finanzas',          'valor' => 'Finanzas'],
            ['id' => 'Recursos Humanos',  'valor' => 'Recursos Humanos'],
            ['id' => 'Operaciones',       'valor' => 'Operaciones'],
            ['id' => 'Ventas',            'valor' => 'Ventas']
        ];
    }
}
