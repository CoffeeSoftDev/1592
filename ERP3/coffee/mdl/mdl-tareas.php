<?php
require_once('../../conf/_CRUD.php');
require_once('../../conf/_Utileria.php');

class mdl extends CRUD {
    public $util;
    public $bd;

    public function __construct() {
        $this->bd = "rfwsmqex_coffee.";
        $this->util = new Utileria;
    }

    function listTareas($array) {
        $query = "
            SELECT id, title, description, priority, status, created_at
            FROM {$this->bd}tareas
            WHERE status != 0
            ORDER BY created_at DESC
        ";
        return $this->_Read($query, null);
    }

    function getTareaById($array) {
        $query = "
            SELECT *
            FROM {$this->bd}tareas
            WHERE id = ?
        ";
        return $this->_Read($query, $array);
    }

    function createTarea($array) {
        return $this->_Insert([
            'table'  => "{$this->bd}tareas",
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateTarea($array) {
        return $this->_Update([
            'table'  => "{$this->bd}tareas",
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data']
        ]);
    }

    function deleteTareaById($array) {
        return $this->_Delete([
            'table' => "{$this->bd}tareas",
            'where' => $array['where'],
            'data'  => $array['data']
        ]);
    }

    function existsTareaByName($array) {
        $query = "
            SELECT COUNT(*) as count
            FROM {$this->bd}tareas
            WHERE LOWER(title) = LOWER(?)
            AND status != 0
        ";
        $result = $this->_Read($query, $array);
        return $result[0]['count'];
    }

    function lsPriority() {
        $query = "
            SELECT id, name as valor
            FROM {$this->bd}priority
            WHERE active = 1
            ORDER BY id ASC
        ";
        return $this->_Read($query, null);
    }

    function lsStatus() {
        $query = "
            SELECT id, name as valor
            FROM {$this->bd}task_status
            WHERE active = 1
            ORDER BY id ASC
        ";
        return $this->_Read($query, null);
    }
}
