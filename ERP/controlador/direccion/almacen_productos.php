<?php
session_start();
include_once("../../modelo/SQL_PHP/_ALMACEN_ADMIN.php");	//	<---
$obj	=	new	ALMACEN_ADMIN;	//

// $id=$_POST[''];
$txt='';
/*===========================================
//														MAIN
//	==========================================*/
$Productos       = array(null);
$TotalProductos = 0;
$Total          = 0;
$StockBajo      = 0;
$Hoy            = 0;

$DataProductos 	=	$obj	->	VER_TOTAL_PRODUCTOS($Productos);

foreach ($DataProductos as $key) {
 $TotalProductos = $key[0];
 $Total          = evaluar($key[1]);
}

$StockBajo 	=	$obj	->	VER_TOTAL_STOCK_BAJO($Productos);

$StockBajo =$StockBajo.' Productos';

$Hoy 	=	$obj	->	VER_TOTAL_HOY($Productos);


/*	===========================================
//					ENCODE	JSON
//	===========================================*/

 $encode	=	array(
 0=>$TotalProductos,
 1=>$Total,
 2=>$StockBajo,
 3=>$Hoy);

 echo	json_encode($encode);
 /*	===========================================
 //					FUNCTIONS()
 //	===========================================*/
 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="") {
   $res = '-';
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }

  return $res;
 }



 ?>
