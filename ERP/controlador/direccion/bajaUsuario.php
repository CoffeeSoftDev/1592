<?php
session_start();
include_once("../../modelo/SQL_PHP/_DIRECCION.php");
include_once("../../modelo/SQL_PHP/_Perfil.php");

$mtto = new direccion;
 $perfil = new PERFIL;
// -------------

$pass = $_POST['pass'];
$id	  = $_POST['id'];
$user = $_SESSION['user'];
$hoy  = date('Y-m-d');

if($pass == ""){
 echo "<label class='col-xs-12 col-sm-12 text-center text-danger'><span class='fa fa-warning'></span> Error campos vacíos </label>";
}
else{
 $array = array($user,$pass);
 $uno = $perfil->Select_Login($array);

 if ($uno[0] != null) {

  $array = array($id);

  $mtto->Delete_User($array);


  echo "<label class='col-xs-12 col-sm-12 text-center text-success'><span class='fa fa-check'></span> Se dio de baja correctamente </label>";
 }
 else {
  echo "<label class='col-xs-12 col-sm-12 text-center text-danger'><span class='fa fa-warning'></span> La contraseña es incorrecta </label>";
 }
}
?>
