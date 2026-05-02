<?php
session_start();
include_once("../../modelo/SQL_PHP/_MTTO.php");	//	<---
$obj	=	new	MTTO;	//	<--

include_once("../../modelo/SQL_PHP/_ALMACEN.php");	//	<---
$almacen	=	new	ALMACEN;	//	<--

$area        = $_POST['area'];
$id          = $_POST['Equipo'];
$opc         = $_POST['opc'];
$txt         = '';
$CODE1       = '';
$CODE2       = '';
$CODE3       = '';

/*===========================================
//														MAIN
//	==========================================*/
switch ($opc) {
  case 1:

  /* Obtener clave de zona*/
  $zona       = (int) $_POST['zona'];

  if ($zona >= 1 && $zona <= 9) {
    $CODE1 ='0'.$zona;

  }else {  $CODE1 = $zona ; }

  /* Obtener clave del area  */

  $array        = array($area);
  $CODE2	    =	$obj	->	_area($array);

  if ($CODE2==null && $CODE2==0) {
    $allAreas    = $obj	->	SELECT_area(null);
    $CODE2    = count($allAreas) + 1;
  }

  /* Obtener clave del producto  */
  $array = array($CODE2,$zona);
  $all          = $obj -> ClaveProducto($array);
  $CODE3      = Folio($all);

  break;

  case 2: /* equipos articulos */
  // --
  $array    = array($id,$area);
  $CODE3  	=	$almacen	->	totalEquipos($array); // Cuantos Equipos hay
  // --
  $array    = array($id);
  $ID_EQ    = $almacen	->	Select_idEquipo($array);
  // --
  $CODE2   = Folio($CODE3);
  if ($ID_EQ==null) {
    $ID_EQ    = $almacen	->	contarProductos(null);
  }

  $CODE3   = $ID_EQ;
  break;

}




if ($CODE2 >= 0 && $CODE2 <= 9) {
  $CODE2 = '0'.$CODE2;
}




/*	===========================================
//					ENCODE	JSON
//	===========================================*/
$encode	=	array(
  0=>$CODE1,
  1=>$CODE2,
  2=>$CODE3);
  echo	json_encode($encode);
  /*	===========================================
  //					FUNCTIONS()
  //	===========================================*/
  function Folio($Folio) {
    $NewFolio = 0; $Folio = $Folio + 1;
    if($Folio >= 100){
      $NewFolio = $Folio;
    }else if($Folio >= 10){
      $NewFolio = "0".$Folio;
    }
    else if($Folio >= 1){
      $NewFolio = "00".$Folio;
    }
    return $NewFolio;
  }
  ?>
