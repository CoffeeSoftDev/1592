<?php
include_once("_CRUD.php");

Class LOGIN extends CRUD {
  function Select_Login($array) {
    $query = "SELECT idUsuario,Nivel,Gerente,Email,UDN,Area_Usuario,Permiso FROM usuarios WHERE Usuario = ? AND Password = SHA(?)";
    $sql = $this->_Select($query,$array);
    return $sql;
  }
}
?>
