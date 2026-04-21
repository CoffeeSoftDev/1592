<?php
session_start();
include_once("../../../modelo/SQL_PHP/_MTTO.php");
include_once("../../../modelo/SQL_PHP/_Perfil.php");
include_once("../../../modelo/SQL_PHP/_Utileria.php");

$mtto   = new MTTO;
$perfil = new PERFIL;
$util   = new Util;


include_once("../../../modelo/SQL_PHP/_ALMACEN.php");

// -------------
$actual = $_POST['actual'];
$cant   = $_POST['cantidad'];
$tipo   = $_POST['tipo'];
$baja   = $_POST['baja'];
$pass   = $_POST['pass'];
$id	    = $_POST['id'];
$opc    = $_POST['opc'];
$user   = $_SESSION['user'];
$hoy    = date('Y-m-d');

if($pass == ""){
 echo "<label class='col-xs-12 col-sm-12 text-center text-danger'><span class='fa fa-warning'></span> Error campos vacíos </label>";
}


else{
 $array = array($user,$pass);
 $uno = $perfil->Select_Login($array);

 if ($uno[0] != null) {  // Contraseña Correcta



  switch ($opc) {
   case 0:
   $array = array($hoy,$baja,$id,$actual,$cant,1);
   $mtto->Insert_Almacen_Bitacora($array);

   if ($tipo!=3) {
    $array = array($opc,$id);
    $mtto->Update_Almacen_Estado($array);
   }else { // SI ES PIEZA
    $res = $actual - $cant;
    $array = array($res,$id);
    $mtto-> Update_Registro($array);

    if ($cant==$actual) { // si el total en almacen es dado de baja entonces se va todo
     $array = array($opc,$id);
     $mtto->Update_Almacen_Estado($array);
    }else {
     $array = array(3,$id);
     $mtto->Update_Almacen_Estado($array);
    }


   }

   break;

   case 1: // ActivarMTTO
   $array = array($hoy,$baja,$id,$actual,$cant,2);
   $mtto->Insert_Almacen_Bitacora($array);

   $array = array($actual,$id);
   $mtto-> Update_Registro($array);

   $array = array($opc,$id);
   $mtto->Update_Almacen_Estado($array);
   break;


   // Dar de baja Producto ------------------------------------------

   case 3:

   $obj              = new ALMACEN;
   $ExisteEnBitacora = $obj->ExisteEnLista($id);

   foreach            ($ExisteEnBitacora as $key ) ;

   //Obtener info del producto dado de baja ........
   $ver              = $obj ->verProductos($id);
   foreach            ($ver as $data ) ;

   //Comprobar si es posible eliminarlo.....
   if (count($ExisteEnBitacora)==0) {

    $array = array($id);
    $array2 = array(
     'CÓDIGO: '.$data[1].' PRODUCTO:'.$data[1],
     $baja,
     $hoy);


    $Eliminar = $obj->QuitarArticulo($array,$array2);


    echo '
    <div class="text-center text-success">
    <i class="fa fa-check-circle fa-4x mb-3 "></i>
    <p>Se ha dado de baja correctamente.</p>
    </div>

    <div class="form-group">
    <div class="col-sm-12 col-xs-12" id="txtBTN">
    <button type="button" class="btn btn-xs btn-default col-xs-12 col-sm-8 col-sm-offset-2 " data-dismiss="modal" onclick="verActivos(1);"> Salir </button>
    </div>
    </div>';

   }else {

    $arreglo = array(
     'El producto ['.$key[0].'] se encuentra dentro del inventario es imposible darlo de baja.',
     ''
    );

    echo  $util -> MSG_ERROR($arreglo);
   }

   break;

  }



 }
 else {
  echo "<label class='col-xs-12 col-sm-12 text-center text-danger'><span class='fa fa-warning'></span> La contraseña es incorrecta </label>";
 } // Contraseña incorrecta
}// End else
?>
