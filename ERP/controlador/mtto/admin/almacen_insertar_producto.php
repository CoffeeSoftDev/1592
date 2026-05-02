<?php
session_start();

include_once("../../../modelo/SQL_PHP/_ALMACEN.php");
$mtto = new ALMACEN;

sleep(1);

$empresa    = 1; // udn por defecto
$codigo     = strtoupper($_POST['codigo']);
$articulo   = strtoupper($_POST['art']);
$zona       = $_POST['zona'];
$area       = $_POST['Area'];
$familia    = $_POST['familia'];
$clase      = $_POST['clase'];
$marca      = $_POST['marca'];
$unidad     = $_POST['unidad'];
// ----
$min        = $_POST['min'];
$stock      = $_POST['stock'];
$desc       = $_POST['desc'];
$costo1     = $_POST['costo1'];
$costo2     = $_POST['costo2'];
$hoy        = $_POST['hoy'];
$txt        = '';
$estado     = 1; // OK
/*===========================================
*						         		MAIN
=============================================*/

// Obtener id -------------------------
$arrayE = array($articulo);
$idEquipo = $mtto->Select_idEquipo($arrayE);

if ($idEquipo == 0) {
 $array = array($articulo,$min);
 $mtto->Insert_Equipo($array);
 $idEquipo = $mtto->Select_idEquipo($arrayE);
}

//COMPROBAR SI EXISTE EL CODIGO DEL EQUIPO
$array = array($codigo);
$key=0;
$cod = $mtto->Select_idCodigo($array);
foreach ($cod as $key);


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
$existCodigo =count($cod);
if($existCodigo != 0){
 $estado     = 2; // Invalid
 $txt=$txt.$existCodigo."<center><b>Este codigo ".$codigo." ya existe con el articulo ".$key[1]."<b> Elija otro codigo <center>";
}else {

 if ($marca=='') { $marca=1; }
$arrays = array($area);
$idArea = $mtto ->Select_idArea($arrays);

 $array = array($codigo,$idEquipo,$zona,$clase,$idMarca,
 $stock,$costo1,$costo2,$hoy,$desc,1,$hoy,1,$unidad,$idArea,$empresa);
 $mtto->Insert_Codigo($array);
 $txt=$txt.'
 <div class="alert  alert-success alert-dismissible  show" role="alert">
 <span class="badge badge-pill badge-success"></span>
 El equipo se guardo con éxito.
 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
 <span aria-hidden="true">×</span>
 </button>
 </div>
';
}


/*===========================================
*				      JSON- ENCODE
=============================================*/

$encode = array(0=>$txt,1=>$estado);
echo json_encode($encode);


?>
