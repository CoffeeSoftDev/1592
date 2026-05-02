<?php
include_once("../../modelo/SQL_PHP/_DIRECCION.php");
$obj  = new direccion;

// --------------
$opc     = $_POST['opc'];
$id      = $_POST['id'];
$user    = strtoupper($_POST['usuario']);
$gerente = strtoupper($_POST['gerente']);
$pass    = $_POST['pass'];
$nivel   = $_POST['nivel'];
$udn     = $_POST['udn'];
$area    = $_POST['area'];
$mail    = $_POST['mail'];


// Funciones -----
if ($opc==0) {
 $array = array($user,$pass,$nivel,$mail,$gerente,$udn,$area );
 $sql    = $obj -> newUsers($array);
}else if ($opc==1){
 $array = array($user,$nivel,$mail,$gerente,$udn,$area,$id );
 $sql    = $obj -> UpdateUsers($array);
}


echo '<label><span class="fa fa-check-circle"></span> Operación Exitosa </label>';

?>
