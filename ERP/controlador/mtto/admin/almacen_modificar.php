<?php
session_start();

include_once("../../../modelo/SQL_PHP/_MTTO.php");
$mtto      = new MTTO;

// POST & VAR
sleep(1);

$idEmpresa = $_POST['empresa'];
$idAlmacen = $_POST['idAlmacen'];
$equipo    = strtoupper($_POST['equipo']);
$area      = strtoupper($_POST['area']);
$codigo    = strtoupper($_POST['codigo']);
$categoria = $_POST['categoria'];
$cant      = $_POST['cant'];
$costo     = $_POST['costo'];
$Tiempo    = $_POST['Tiempo'];
$Estado    = $_POST['estado'];
$Detalles  = $_POST['Detalles'];
$opc       = $_POST['opc'];//2
$Proveedor = $_POST['Proveedor'];
$marca     = $_POST['Marca'];
$code      = "AR";
$hidden    = "00-00";
$txt       = '';
/*-------- Input file ---------*/
$ruta      = 'recursos/img/productos/'.$area.'/';


/*--------- data [] -----------*/


// Existe equipo ¿?

$array    = array($equipo);
$idEquipo = $mtto->Select_idEquipo($array);

// si existe guardar id

if ($idEquipo == 0) {
 $mtto->Insert_Equipo($array);
 $idEquipo = $mtto->Select_idEquipo($array);
}

 // Existe area ¿?
$array = array($area);
$idArea = $mtto->Select_idArea($array);

//SI EXISTE GUARDAMOS EL ID
if ($idArea == 0) {
 $mtto->Insert_Area($array);
 $idArea = $mtto->Select_idArea($array);
}

//COMPROBAMOS SI EXISTE EL CODIGO
$array = array($codigo);
$idCodigo = $mtto->Select_idCodigo($array);

// CODIGO ACTUAL DEL EQUIPO
$arrayData    = array($idAlmacen);
$data         = $mtto->Show_DATA_SINGLE($arrayData);
$CodigoActual = $data[0];

// Existe marca
if ($marca == "" ||$marca == null) {
 $idMarca = 1;
}else {
$array  = array($marca);
$idMarca = $mtto->Select_idMarca($array);

if ($idMarca == 0) {
 $mtto->Insert_Marca($array);
 $idMarca = $mtto->Select_idMarca($array);
}
}

// Existe Proveedor ¿?
if ($Proveedor == "" ||$Proveedor == null) {
 $idPro = 1;
}else {
 $array  = array($Proveedor);
 $idPro  = $mtto->Select_idProveedor($array);
 if ($idPro == 0) {
  $mtto->Insert_Proveedor($array);
  $idPro = $mtto->Select_idProveedor($array);
 }
}

$array = array($codigo,
$idEquipo,
$idArea,
1,
$cant,
$categoria,
$costo,
$Tiempo,
$Estado,
$Detalles,
$idEmpresa,
$idPro,
$idMarca,
$idAlmacen);


//  $mtto    -> Update_Codigo($array);
 $codigo = '';

 for ($i=0; $i < count($array) ; $i++) { 
  $codigo .= $array[$i].'<br>';
 }


 $txt= $txt.'<div  class="col-xs-12 col-sm-12 "><div class="alert  alert-success alert-dismissible  show" role="alert">
 <span class="badge badge-pill badge-success"></span>
 El equipo se guardo con éxito.
 '.$codigo.'
 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
 <span aria-hidden="true">×</span>
 </button>
 </div></div>';


// if ($codigo==$CodigoActual) {
//
//  $txt= $txt."
//  <label class='col-xs-12 col-sm-12 text-center text-primary'>
//  El producto se modificó correctamente
//  </label>";
// }else { // Se cambio el codigo al Modificar campos
//
//  if($idCodigo == ""){

  $mtto->Update_Codigo($array);

  
//  }
//  else{
//   $txt= $txt."
//   <label class='col-xs-12 col-sm-12 text-center text-danger'>
//   El codigo [".$CodigoActual."] del equipo  ya se encuentra en existencia
//   </label>";
//  }
//
//
// }



/*-----------------------------------*/
/*		JSON ENCODE
/*-----------------------------------*/

$encode = array(0=>$txt ,1=>$array);
echo json_encode($encode);

// echo $txt;

// }
?>
