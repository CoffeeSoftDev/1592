<?php
session_start();

include_once("../../../modelo/SQL_PHP/_ALMACEN.php");
$mtto = new ALMACEN;

sleep(1);

$idAlmacen    =  $_POST['idAl'];
$empresa    = 1; // udn por defecto
$codigo     = strtoupper($_POST['codigo']);
$articulo   = strtoupper($_POST['art']);
$zona       = $_POST['zona'];
$familia    = $_POST['familia'];
$clase      = $_POST['clase'];
$marca      = $_POST['marca'];
$unidad     = $_POST['unidad'];
$area       = $_POST['Area'];

// ----

$min        = $_POST['min'];
$stock      = $_POST['stock'];
$desc       = $_POST['desc'];
$costo1     = $_POST['costo1'];
$costo2     = $_POST['costo2'];
$hoy        = $_POST['hoy'];
$txt        = '';
$estado     = 1; //

/*===========================================
*						         		MAIN
=============================================*/




// OBTENER ID PRODUCTO   ..................
$arrayE = array($articulo);
$idEquipo = $mtto->Select_idEquipo($arrayE);

if ($idEquipo == 0) {
 $array = array($articulo,$min);
 $mtto->Insert_Equipo($array);
 $idEquipo = $mtto->Select_idEquipo($arrayE);
}

//COMPROBAR SI EXISTE EL CODIGO DEL EQUIPO
$array        = array($codigo);
$key          = 0;
$CodigoActual = '';
$cod          = $mtto->Select_idCodigo($array);
foreach ($cod as $key);
$CodigoActual = $key[2];


//Comprobar si existe Marca
if($marca==""){//opcional
 $idMarca =1;
}else {

 $array = array($marca);
 $idMarca = $mtto->Select_idMarca($array);

 if ($idMarca == 0) {
  $mtto->Insert_Marca($array);
  $idMarca = $mtto->Select_idMarca($array);
 }
}


/* ¿CODIGO PERTENECE AL MISMO EQUIPO? */

if ($codigo == $CodigoActual) {
 if ($marca=='') { $marca=1; }

 $array = array($codigo,$idEquipo,$zona,$clase,$idMarca,
 $costo1,$costo2,$desc,$hoy,$unidad,$area,$idAlmacen);
 $mtto->Update_Producto($array);
 $txt=$txt.' <div class="alert  alert-success alert-dismissible  show" role="alert">
  <span class="badge badge-pill badge-success"></span>
  El equipo se guardo con éxito.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">×</span>
  </button>
  </div>';
}else {

 $estado     = 2; // Invalid
 $txt=$txt."<center><b>Este codigo ".$codigo." ya existe con el articulo ".$key[1]."<b> Elija otro codigo <center>";

}



 /*===========================================
 *				      JSON- ENCODE
 =============================================*/

 $encode = array(0=>$txt,1=>$estado);
 echo json_encode($encode);

?>
