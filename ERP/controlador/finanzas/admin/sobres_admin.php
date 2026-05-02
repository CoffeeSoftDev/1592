<?php

include_once('../../../modelo/SQL_PHP/_Finanzas.php');
include_once("../../../modelo/SQL_PHP/_Utileria.php");
$util = new Util;
$fin = new Finanzas;

$date_now   = $fin->NOW();
$opc        = $_POST['opc'];
$idE        = $_POST['udn'];


switch ($opc) {

 case 1: // Fondo de caja actual

 $SI       = 0;
 $sql      = $fin->Select_Retiro($idE);
 $date_SI  = null;

 foreach($sql as $row){
  $SI      = $row[1];
  $date_SI = $row[2];
 }

 if ( isset($date_SI) ) {

  /*Gastos Fondo */
  $g  = $fin->Select_SUM_Gastos_Fondo($idE,$date_SI,$date_now);

  /*Anticipos*/
  // $a = $fin->Select_SUM_Anticipo_Fondo($idE,$date_SI,$date_now); if( !isset($a) ){ $TA = 0; } else { $TA = $a; }
  $TA = 0;

  //Pagos de proveedor
  $p  = $fin->Select_Pago_Proveedor_Fondo($idE,$date_SI,$date_now);

  /*Saldo Final*/
  $SF = $SI - $g - $TA - $p;

  $resultado = array( number_format($SI,2,".",", "), number_format($SF,2,".",", ") );

 } else{
  $resultado = array('0.00','0.00');
 }

 echo json_encode($resultado);
 break;


 case 7: // Saldo Proveedor
 $idE = $_POST['udn'];
 $date1 = $_POST['date1'];
 $date2 = $_POST['date2'];

 //Datos de proveedor
 $array = array($idE,$date1);
 $si_prov = $fin->Select_SI_Proveedor($array);
 $gasto_proveedor = $fin->Select_Suma_Total_Proveedor_Gastos($idE,$date1,$date2);
 $pagos_proveedor = $fin->Select_Suma_Total_Proveedor_Pagos($idE,$date1,$date2);
 $sf_prov = $si_prov + $gasto_proveedor - $pagos_proveedor;

 $resultado = array(
  number_format($si_prov,2,'.',', '),
  number_format($gasto_proveedor,2,'.',', '),
  number_format($pagos_proveedor,2,'.',', '),
  number_format($sf_prov,2,'.',', ')
 );
 echo json_encode($resultado);
 break;


}


?>
