<?php
require_once '../../../conf/_CRUD.php';
require_once '../../../conf/_Utileria.php';

class mdl extends CRUD {

    public $util;
    public $bd;
    public $bdFinanzas;

    function __construct() {
        $this->util       = new Utileria();
        $this->bd         = "hgpqgijw_usuarios.";
        $this->bdFinanzas = "hgpqgijw_finanzas.";
    }

    function listUsuarios($array) {
        $where = '1=1';
        $data  = [];

        if (!empty($array[0])) {
            $where .= ' AND u.Nivel = ?';
            $data[] = $array[0];
        }

        if (isset($array[1]) && $array[1] !== '') {
            $where .= ' AND u.active = ?';
            $data[] = $array[1];
        }

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
            WHERE {$where}
            ORDER BY u.idUsuario ASC
        ";
        return $this->_Read($query, !empty($data) ? $data : null);
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

    function disableUsuarioById($array) {
        return $this->_Update([
            'table'  => "hgpqgijw_usuarios.usuarios",
            'values' => 'active = 0',
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function lsNivel() {
        return $this->_Read("SELECT idNivel AS id, Nombre_Nivel AS valor FROM hgpqgijw_usuarios.nivel WHERE active = 1 ORDER BY Nombre_Nivel ASC", null);
    }

    function lsArea() {
        return $this->_Read("SELECT idArea AS id, Area AS valor FROM hgpqgijw_usuarios.area WHERE active = 1 ORDER BY Area ASC", null);
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
        return $this->_Read("SELECT idArea AS id, Area AS valor FROM {$this->bd}area WHERE active = 1 ORDER BY Area ASC", null);
    }

    function listDepartamentos($active = '1') {
        $where = '';
        $data  = null;
        if ($active === '1' || $active === '0') {
            $where = 'WHERE active = ?';
            $data  = [$active];
        }
        return $this->_Read("SELECT idArea AS id, Area AS nombre FROM {$this->bd}area {$where} ORDER BY Area ASC", $data);
    }

    // === ROLES CRUD (tabla: nivel) ===

    function listRoles($active = '1') {
        $where = '';
        $data  = null;
        if ($active === '1' || $active === '0') {
            $where = 'WHERE active = ?';
            $data  = [$active];
        }
        return $this->_Read("SELECT idNivel AS id, Nombre_Nivel AS nombre FROM {$this->bd}nivel {$where} ORDER BY idNivel ASC", $data);
    }

    function getRolById($array) {
        $query = "SELECT idNivel AS id, Nombre_Nivel AS nombre FROM {$this->bd}nivel WHERE idNivel = ?";
        return $this->_Read($query, $array);
    }

    function existsRolByName($array) {
        $query = "SELECT COUNT(*) AS count FROM {$this->bd}nivel WHERE Nombre_Nivel = ?";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function existsOtherRolByName($array) {
        $query = "SELECT COUNT(*) AS count FROM {$this->bd}nivel WHERE Nombre_Nivel = ? AND idNivel != ?";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function createRol($array) {
        return $this->_Insert([
            'table'  => "{$this->bd}nivel",
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateRol($array) {
        return $this->_Update([
            'table'  => "{$this->bd}nivel",
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function disableRolById($array) {
        return $this->_Update([
            'table'  => "{$this->bd}nivel",
            'values' => 'active = 0',
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    // === DEPARTAMENTOS CRUD (tabla: area) ===

    function getDepartamentoById($array) {
        $query = "SELECT idArea AS id, Area AS nombre FROM {$this->bd}area WHERE idArea = ?";
        return $this->_Read($query, $array);
    }

    function existsDepartamentoByName($array) {
        $query = "SELECT COUNT(*) AS count FROM {$this->bd}area WHERE Area = ?";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function existsOtherDepartamentoByName($array) {
        $query = "SELECT COUNT(*) AS count FROM {$this->bd}area WHERE Area = ? AND idArea != ?";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function createDepartamento($array) {
        return $this->_Insert([
            'table'  => "{$this->bd}area",
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateDepartamento($array) {
        return $this->_Update([
            'table'  => "{$this->bd}area",
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function disableDepartamentoById($array) {
        return $this->_Update([
            'table'  => "{$this->bd}area",
            'values' => 'active = 0',
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    // === CATEGORÍAS FINANZAS (tabla: hgpqgijw_finanzas.categoria) ===

    function lsTiposMovimiento() {
        $query = "SELECT idTMovimiento AS id, TipoMovimiento AS valor FROM {$this->bdFinanzas}tipo_movimiento ORDER BY idTMovimiento ASC";
        return $this->_Read($query, null);
    }

    function listCategorias($tipoMovimiento = '', $active = '1') {
        $where = '1=1';
        $data  = [];

        if (!empty($tipoMovimiento)) {
            $where .= ' AND c.id_TMovimiento = ?';
            $data[] = $tipoMovimiento;
        }

        if ($active === '1' || $active === '0') {
            $where .= ' AND c.Stado = ?';
            $data[] = $active;
        }

        $query = "
            SELECT
                c.idCategoria AS id,
                c.Categoria AS nombre,
                COALESCE(tm.TipoMovimiento, '') AS tipo_movimiento,
                c.id_TMovimiento AS tipo_movimiento_id,
                c.Stado AS activo
            FROM {$this->bdFinanzas}categoria c
            LEFT JOIN {$this->bdFinanzas}tipo_movimiento tm ON tm.idTMovimiento = c.id_TMovimiento
            WHERE {$where}
            ORDER BY c.idCategoria ASC
        ";

        return $this->_Read($query, !empty($data) ? $data : null);
    }

    function getCategoriaById($array) {
        $query = "
            SELECT
                c.idCategoria AS id,
                c.Categoria AS nombre,
                c.id_TMovimiento AS tipoMovimiento,
                c.Stado AS activo
            FROM {$this->bdFinanzas}categoria c
            WHERE c.idCategoria = ?
        ";
        $result = $this->_Read($query, $array);
        return !empty($result) ? $result[0] : null;
    }

    function existsCategoriaByName($array) {
        $query = "SELECT COUNT(*) AS count FROM {$this->bdFinanzas}categoria WHERE LOWER(Categoria) = LOWER(?)";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function existsOtherCategoriaByName($array) {
        $query = "SELECT COUNT(*) AS count FROM {$this->bdFinanzas}categoria WHERE LOWER(Categoria) = LOWER(?) AND idCategoria != ?";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function createCategoria($array) {
        return $this->_Insert([
            'table'  => "{$this->bdFinanzas}categoria",
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateCategoria($array) {
        return $this->_Update([
            'table'  => "{$this->bdFinanzas}categoria",
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function disableCategoriaById($array) {
        return $this->_Update([
            'table'  => "{$this->bdFinanzas}categoria",
            'values' => 'Stado = 0',
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    // === SUBCATEGORÍAS (tabla: hgpqgijw_finanzas.subcategoria) ===

    function listSubcategorias($idCategoria, $active = '1') {
        $where = 'id_Categoria = ?';
        $data  = [$idCategoria];

        if ($active === '1' || $active === '0') {
            $where .= ' AND activo = ?';
            $data[] = $active;
        }

        $query = "
            SELECT
                idSubcategoria AS id,
                Subcategoria AS nombre,
                tarifa,
                activo,
                Stado
            FROM {$this->bdFinanzas}subcategoria
            WHERE {$where}
            ORDER BY idSubcategoria ASC
        ";

        return $this->_Read($query, $data);
    }

    function getSubcategoriaById($array) {
        $query = "
            SELECT
                idSubcategoria AS id,
                Subcategoria AS nombre,
                tarifa,
                activo,
                id_Categoria AS idCategoria
            FROM {$this->bdFinanzas}subcategoria
            WHERE idSubcategoria = ?
        ";
        $result = $this->_Read($query, $array);
        return !empty($result) ? $result[0] : null;
    }

    function existsSubcategoriaByName($array) {
        $query = "SELECT COUNT(*) AS count FROM {$this->bdFinanzas}subcategoria WHERE LOWER(Subcategoria) = LOWER(?) AND id_Categoria = ?";
        $result = $this->_Read($query, $array);
        return $result[0]['count'] > 0;
    }

    function createSubcategoria($array) {
        return $this->_Insert([
            'table'  => "{$this->bdFinanzas}subcategoria",
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateSubcategoria($array) {
        return $this->_Update([
            'table'  => "{$this->bdFinanzas}subcategoria",
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function disableSubcategoriaById($array) {
        return $this->_Update([
            'table'  => "{$this->bdFinanzas}subcategoria",
            'values' => 'activo = 0',
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }
}