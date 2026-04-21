<?php

session_start();
include_once("../../modelo/SQL_PHP/_ALMACEN_ADMIN.php");
$obj	=	new	ALMACEN_ADMIN;	//

$txt='';
/*===========================================
//														MAIN
//	==========================================*/

// -----------------------------------------
$DataMateriales  	=	$obj	->	VER_TOTAL_MATERIALES(null);
$Materiales       = count($DataMateriales);
// -----------------------------------------
$CostoMateriales  = $obj	->	VER_COSTO_MATERIALES(null);
$Costo            = evaluar($CostoMateriales);
// -----------------------------------------
$verBaja          = $obj	->	VER_BAJA_MATERIALES(null);
$BajaEquipos      = count($verBaja);
// -----------------------------------------

/*	===========================================
//					ENCODE	JSON
//	===========================================*/

 $encode	=	array(
 0=>$Materiales,
 1=>$Costo,
 2=>$BajaEquipos);

 echo	json_encode($encode);
	/*===========================================
	*									FUnciones
	=============================================*/
 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="") {
   $res = '';
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }
  return $res;
 }


?>
