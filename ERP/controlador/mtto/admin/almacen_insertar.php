<?php
session_start();
sleep(1);

include_once("../../../modelo/SQL_PHP/_MTTO.php");
$mtto = new MTTO;

// POST & VAR

$Proveedor = $_POST['Proveedor'];
$empresa   = $_POST['empresa'];
$equipo    = strtoupper($_POST['equipo']);
$area      = strtoupper($_POST['area']);
$codigo    = strtoupper($_POST['codigo']);
$categoria = $_POST['categoria'];
$cantidad  = $_POST['cant'];
$costo     = $_POST['costo'];
$duracion  = $_POST['Tiempo'];
$detalles  = $_POST['Detalles'];
$estado    = $_POST['estado'];
$hoy       = $_POST['hoy'];
$marca     = $_POST['Marca'];
$txtM      = $_POST['txtArea'];
$emp       = substr($codigo, 0, 2);
$code      = "";


/*-------- Input file ---------*/
$ruta    = 'recursos/img/productos/'.$area.'/';
foreach ($_FILES as $cont => $key) {

 if($key['error'] == UPLOAD_ERR_OK ){
  //Obtener la extensión del archivo
  $trozos = explode(".", $key['name']);
  $extension = end($trozos);

  $NombreOriginal = $codigo.'.'.$extension;
  $temporal       = $key['tmp_name'];

  $carpeta        = '../../../'.$ruta;

  if (!file_exists($carpeta)) {
   mkdir($carpeta, 0777, true);
  }

  $files          = $ruta.$NombreOriginal;

  move_uploaded_file($temporal, '../../../'.$files);

  /* -- Data Producto -- */

  // Existe equipo ¿?
  $array = array($equipo);
  $idEquipo = $mtto->Select_idEquipo($array);

  //SI EXISTE GUARDAMOS EL ID
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

  //COMPROBAR SI EXISTE EL CODIGO DEL EQUIPO
  $array = array($codigo);
  $cod = $mtto->Select_idCodigo($array);

  //SI EXISTE ENVIAMOS UN MENSAJE DE ALERTA QUE ESTE CODIGO YA EXISTE
  // if($cod != 0){
  //  echo '<div class="alert  alert-danger alert-dismissible  show" role="alert">
  //  <span class="badge badge-pill badge-danger"></span>
  //  El código ya se encuentra en existencia.
  //  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
  //  <span aria-hidden="true">×</span>
  //  </button>
  //  </div>';
  // }
  //SI NO EXISTE LO INSERTAMOS Y ENVIAMOS MENSAJE DE ÉXITO
  // else {
   $array = array($codigo,$idEquipo,$idArea,1,1,$categoria,$cantidad,$costo,$duracion,$detalles,$estado,'2021-08-01',$empresa,$files,$idPro,$idMarca);
  
   $cadena = '';

   for ($i=0; $i < count($array) ;  $i++) { 
     $cadena .= ''.$array[$i].' <br>';
   }

  
   $mtto->Insert_Codigo($array);
  
   echo '<div class="alert  alert-success alert-dismissible  show" role="alert">
   <span class="badge badge-pill badge-success"></span>
   El equipo se guardo con éxito.

   '.$cadena.'
   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
   <span aria-hidden="true">×</span>
   </button>
   </div>';
  // }



 }else {
  echo "<label class='text-danger'><span class='icon-cancel'></span>Ah ocurrido un error al subir el archivo</label>";
 }

}// end For Each

?>
