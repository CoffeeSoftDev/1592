<?php
session_start();

include_once("../../../modelo/SQL_PHP/_MTTO_REQUISICION.php");
$obj    = new REV;
// include_once("../../../modelo/SQL_PHP/_MTTO.php");
// $obj = new MTTO;
// // --
include_once("../../../modelo/SQL_PHP/_Utileria.php");
$util   = new Util;

$opc    = $_POST['opc'];
/*==========================================
*		MAIN
=============================================*/
$txtFol       = 0;
$estadoFolio  = 1;
$zona         = '';
$area         = '';
/*-----------------------------------*/
/* init Componente
/*-----------------------------------*/

// switch ($opc) {
//  case 1:
//  $opt1 = ''; $opt2= '';
//  $val1 = ''; $val2= '';
//  $folio       = $obj -> obtenerFolio();
//  $estadoFolio = 0 ;
//  $activo      = $obj -> FOLIO_ACTIVO();



//  foreach      ($activo as $key ){
//   $estadoFolio  = $key[1];
//   $opt1         = $key[3];
//   $opt2         = $key[4];
//   $val1         = $key[5];
//   $val2         = $key[6];
//  }

//  $zona = '<option value="'.$val1.'">'.$opt1.'</option>’';
//  $area = '<option value="'.$val2.'">'.$opt2.'</option>’';

//  $txtFol        = 'N°  '.Folio($folio-1,'P');
//  if ($estadoFolio==0) {
//   $txtFol       = 'N°  '.Folio($folio,'P');
//  }

//  break;


// /*-----------------------------------*/
// /* Nuevo
// /*-----------------------------------*/


//  case 2:
//  sleep(1);
//  $zona     = $_POST['zona'];
//  $area     = $_POST['area'];
//  $fecha    =  date("Y-m-d H:i:s");

//  $folio    = $obj -> obtenerFolio();
//  $txtFol   = Folio($folio,'P');

//  /* CREAR FOLIO PARA INVENTARIO */

//  $array = array(1,$txtFol,$fecha,$zona,$area);
//  $ok    = $obj -> nuevoFolio($array);


//  break;
//  /*-----------------------------------*/
//  /*	agregar Movimiento
//  /*-----------------------------------*/


//  case 3:
//  /* data activo */
//  $folio      = $obj -> FOLIO_ACTIVO();
//  foreach     ($folio as $key);
//  $idFolio    = $key[0];
//  /* data POST */
//  $idProducto = $_POST['id'];
//  $fisico     = $_POST['fisicoAlmacen'];
//  $teorico    = $_POST['cantidad'];

//  $actual     = 0 ;

//  if ($teorico < $fisico) {
//   $actual    = $fisico - $teorico;
//  }else {
//   $actual    = $teorico - $fisico;
//  }

//  /* Cargar registro */
//  $array      = array($idFolio,$idProducto,$teorico,$fisico);
//  $ok         = $obj -> addMoviento($array);

//  /* Actualizar registro de producto */

//  $registro   = array($fisico,$idProducto);
//  $update     = $obj -> nuevoStock($registro);

//  $txtFol     = $idProducto;
//  $estadoFolio = $fisico;
//  break;

//  /*--------------------------------------------*/
//  /* Quitar Movimiento
//  /*---------------------------------------------*/
//  case 4:

//  $idProducto = $_POST['id'];
//  $anterior   = $_POST['anterior'];
//  $Producto   = $_POST['idProducto'];

//  $array      = array($idProducto);
//  $ok    = $obj -> DeleteMovimiento($array);
//  // --
//  $array      = array($anterior,$Producto);
//  $ok    = $obj -> RestaurarValor($array);

//  $txtFol  =  $array;
//  break;


//  /*--------------------------------------------*/
//  /* Cancelar folio
//  /*---------------------------------------------*/
//  case 5:
//  $idProducto = $_POST['id'];
//  $motivo     = $_POST['motivo'];

//  /* Restaurar productos */

//  $ok          = $obj -> LISTA_PDF($idProducto);

//  foreach ($ok as $key ) {
//   $array      = array($key[1],$key[4]);
//   $ok    = $obj -> RestaurarValor($array);
//  }


//  $array      = array($motivo,3,$idProducto);
//  $ok    = $obj -> DeleteFolio($array);

//  /* Mensaje  */

//  $info       ='
//  <h4 class="text-success"> Folio cancelado exitosamente </h4>
//  <span class="text-success icon-ok-circle fa-4x" style="" ></span>
//  ';

//  $array      = array('','initComponents',$info,'',0);
//  $txtFol     =$util-> MODAL_SUCCESS($array) ;


//  break;

//  /*--------------------------------------------*/
//  /*Guardar lista de inventario
//  /*---------------------------------------------*/
//  case 6:
//  /* data  */

//  $idProducto = $_POST['id'];
//  $nota       = $_POST['nota'];
//  $autorizo   = $_POST['autorizo'];

//  /* Process */

//  $productos   = $obj -> LISTA_PDF($idProducto);
//  $existenPro  = count($productos);


//  if($existenPro==0){
//   $info       ='
//   <h3 class="text-danger">Error no se puede guardar un inventario vacio</h3>
//   <span class="text-danger  icon-cancel-circled fa-4x" ></span>
//   ';

//  }else {
//   $array      = array($nota,$autorizo,2,$idProducto);
//   $ok    = $obj -> GuardarFolio($array);
//   // ----
//   $info       ='
//   <h3> Se ha concluido el inventario, Imprimir para finalizar. </h3>
//   <span class="text-success icon-print-6 fa-4x" style="cursor:pointer;" onclick="Imprimir('.$idProducto.')"></span>
//   ';
//  }

//  $array      = array('','initComponents',$info,'',0);
//  $txtFol     =$util-> MODAL_SUCCESS($array) ;

//  break;
// }


/* ===========================================
*     ENCODE JSON
// ===========================================*/
$encode = array(
 0=>$txtFol,
 1=>$estadoFolio,
 2=>$zona,
 3=>$area);
 echo json_encode($encode);

 /*==========================================
 *		FUNCIONES / FORMULAS
 =============================================*/

 function Folio($Folio,$Area) {
  $NewFolio = 0; $Folio = $Folio + 1;
  if($Folio >= 1000){
  $NewFolio = $Area."-".$Folio;
  }
  else if($Folio >= 100){
  $NewFolio = $Area."-0".$Folio;
  }
  else if($Folio >= 10){
  $NewFolio = $Area."-00".$Folio;
  }
  else if($Folio >= 1){
  $NewFolio = $Area."-000".$Folio;
  }
  return $NewFolio;
 }
 ?>
