<?php
require_once '../../conf/_CRUD.php';
require_once '../../conf/_Utileria.php';

class mdl extends CRUD {

    public $util;
    public $bd;

    function __construct() {
        $this->util = new Utileria();
        $this->bd = "rfwsmqex_coffee.";
    }

    function listShowcase($array) {
        $query = "
            SELECT id, name, category, description, status
            FROM {$this->bd}showcase_items
            WHERE category = ?
            ORDER BY name ASC
        ";
        return $this->_Read($query, $array);
    }

    function getShowcaseById($array) {
        $query = "
            SELECT *
            FROM {$this->bd}showcase_items
            WHERE id = ?
        ";
        return $this->_Read($query, $array);
    }

    function createShowcase($array) {
        return $this->_Insert([
            'table'  => "{$this->bd}showcase_items",
            'values' => $array['values'],
            'data'   => $array['data']
        ]);
    }

    function updateShowcase($array) {
        return $this->_Update([
            'table'  => "{$this->bd}showcase_items",
            'values' => $array['values'],
            'where'  => $array['where'],
            'data'   => $array['data'],
        ]);
    }

    function deleteShowcaseById($array) {
        return $this->_Delete([
            'table' => "{$this->bd}showcase_items",
            'where' => $array['where'],
            'data'  => $array['data'],
        ]);
    }

    function lsCategories() {
        $query = "
            SELECT id, classification as valor
            FROM {$this->bd}categories
            WHERE active = 1
            ORDER BY classification ASC
        ";
        return $this->_Read($query, []);
    }

    function lsStatus() {
        return [
            ['id' => 1, 'valor' => 'Activo'],
            ['id' => 2, 'valor' => 'Inactivo'],
        ];
    }
}
