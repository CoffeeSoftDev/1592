<?php
session_start();
include_once("../../../modelo/SQL_PHP/_Finanzas.php"); // <---
include_once("../../../modelo/SQL_PHP/_Perfil.php"); // <---
$obj     = new Finanzas; // <--
$perfil  = new PERFIL; // <--

	$id     = $_POST['id'];
	$pass   = $_POST['pass'];
 $user   = $_SESSION['user'];
 $estado = $_POST['estado'];
	$txt    ='';
 $respuesta = 1;
 /* ===========================================
              FUNCIONES-PHP
 // ===========================================*/
 if($pass == "" || $pass == null ){
  $txt = "<label class='col-xs-12 col-sm-12 text-center text-danger'><span class='fa fa-warning'></span> Error campos vacíos </label>";
 }
 else{

  $array  = array($user,$pass);
  $existe = $perfil->Select_Login($array);

  if ($existe[0] != null) {
   $array = array($estado,$id);
   $obj->autorizarBaja($array);

   $txt = "<label class='col-xs-12 col-sm-12 text-center text-success'><span class='fa fa-check-circle'></span>  El proveedor ha sido dado de baja </label>";
  }else {
   $respuesta=2;
  $txt = "<label class='col-xs-12 col-sm-12 text-center text-danger'><span class='fa fa-warning'></span> La contraseña es incorrecta </label>";
  }


 }


 // ===========================================
 //     ENCODE JSON
 // ===========================================
	$encode = array(
		0=>$txt,1=>$respuesta);
	echo json_encode($encode);
?>
