<?php
session_start();

include_once("../modelo/SQL_PHP/_Login.php");
$log = new LOGIN;

$opc = $_POST['opc'];

switch ($opc) {

 case 0: // LOGIN
 sleep(1);
 $user = strtoupper($_POST['user']);	//usuario con mayuscula
 $pass = $_POST['pass'];

 $array = array($user,$pass);
 $sql = $log->Select_Login($array);
 foreach ($sql as $row);

 if ( !isset($row[0]) ) {
  echo '<label class="text-danger">
  <span class="icon-cancel"></span>
   El usuario y/o contraseña son incorrectos.</label>';
 }
 else {

  $_SESSION['user']    = $user;
  $_SESSION['nivel']   = $row[1];
  $_SESSION['gerente'] = $row[2];
  $_SESSION['email']   = $row[3];
  $_SESSION['udn']     = $row[4];
  $_SESSION['area']    = $row[5];
  $_SESSION['permiso'] = $row[6];



  switch ($row[1]) {
   case 0://NIVEL 0, ACCESO A ROOT ADMIN
   case 1://NIVEL 1, DIRECCION GENERAL
//    echo "<script> window.location = 'admin'</script>";
   echo "<script> window.location = 'inicio'</script>";
   break;
   case 2://NIVEL 2, ACCESO A SUPERVISION Y SOCIOS
   break;
   case 3://NIVEL 3, ACCESO A FINANZAS
   echo "<script> window.location = 'finanzas'</script>";
   break;
   case 4://NIVEL 4, ACCESO A MANTENIMIENTO
   echo "<script> window.location = 'mtto'</script>";
   break;
   case 5://NIVEL 5, ACCESO A ADMINISTRATIVO
   switch ($row[5]) {//REDIRIGIR POR AREA ESPECIFICA
    case 6://HOTEL
    echo "<script> window.location = 'movimientos'</script>";
    break;
   }
   break;
   
   case 7:
    echo "<script> window.location = 'contabilidad'</script>";
   break;       
   
   case 8:
   echo "<script> window.location = 'inventario'</script>";
   break;

   case 9:
   echo "<script> window.location = 'pedidos'</script>";
   break;

   case 10:
   echo "<script> window.location = 'control_ventas'</script>";
   break;

    case 11:
   echo "<script> window.location = 'almacen_tiendita'</script>";
   break;

  }
 }
 break;


 case 1:// VALIDACION DE SEGURIDAD

 $slash  = $_POST['slash'];
 $nivel  = $_SESSION['nivel'];
 $area   = $_SESSION['area'];

 switch ($nivel) {
  case 0://NIVEL 0, ACCESO A ROOT ADMIN
  case 1://NIVEL 1, DIRECCION GENERAL
//   echo "<script> window.location = '".$slash."admin'</script>";
  echo "<script> window.location = '".$slash."inicio'</script>";
  break;
  case 2://NIVEL 2, ACCESO A SUPERVISION Y SOCIOS
  break;
  case 3://NIVEL 3, ACCESO A FINANZAS
  echo "<script> window.location = '".$slash."finanzas'</script>";
  break;
  case 4://NIVEL 4, ACCESO A MANTENIMIENTO
  echo "<script> window.location = '".$slash."mtto'</script>";
  break;
  case 5://NIVEL 5, ACCESO A ADMINISTRATIVO
  switch ($area) {//REDIRIGIR POR AREA ESPECIFICA
   case 6://HOTEL
   echo "<script> window.location = '".$slash."movimientos'</script>";
   break;
  }
  break;

  case 8://NIVEL 8 acceso a flores
  echo "<script> window.location = '".$slash."inventario'</script>";
  break;
  
  case 9:
   echo "<script> window.location = 'pedidos'</script>";
  break;

  case 10://NIVEL 8 acceso a flores
  echo "<script> window.location = '".$slash."control_ventas'</script>";
  break;
  
  case 11://NIVEL 8 acceso a flores
  echo "<script> window.location = '".$slash."almacen_tiendita'</script>";
  break;

  // case 11://NIVEL 8 acceso a flores
  // echo "<script> window.location = '".$slash."flores_admin'</script>";
  // break;
 }
 break;
}
?>
