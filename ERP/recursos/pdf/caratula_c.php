<?php
session_start();
include_once('../../modelo/SQL_PHP/_Finanzas_Caratula.php');
$fin = new Caratula;

$idE = $_SESSION['udn'];
$date = $_GET['date'];
?>

<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <title>IMPRIMIR</title>

 <!-- <link rel="stylesheet" href="../../recursos/css/formato_impresion.css"> -->
 <link rel="stylesheet" href="../../recursos/css/formato.css">
 <link rel="stylesheet" href="../../recursos/css/bootstrap/bootstrap.min.css">

 <script type="text/javascript">
 function imprimir() {
  if (window.print) {
   window.print();

  }
  else {
   alert("La función de impresión no esta disponible en este navegador, intentelo con otro diferente.");
  }
 }
 </script>


 <style type="text/css" media="print">
 @page{
  margin-top:  20px;
  margin-bottom:   20px;
  margin-left:   20px;
  margin-right:    30px;
 }
 </style>

 <style>
 .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
  padding: 2px;
  font-size: 1.2rem;
  line-height: 1.32857143;
  vertical-align: top;
  border-top: 1.4px solid #ecf0f1;
 }
 </style>
</head>

<body onload="imprimir();">
 <?php


 echo '
 <br>
 <div class="row">
 <div class="col-xs-4 ">
 <img src="recursos/img/logo.png" width="150px" class="img-rounded center-block">
 </div>

 <div class="col-xs-4 text-center">
 <!-- <h2 class="">Diversificados Argovia S.A. de C.V</h2> --> </div>

 <div class="col-xs-4 text-right">
 <h4 class="col-sm-12"><strong> '.$date.'</strong></h4>
 </div>
 </div>



 <br>
 <div class="">
 <div class="col-sm-12 col-xs-12">
 <table class="table table-condensed table-stripped">
 <thead>
 <tr>
 <th colspan="2" style="font-size:1.5rem;">
 <strong>RESUMEN GENERAL DE VENTAS</strong>
 </th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>CONCEPTO</strong></td>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>MONTO</strong></td>
 </tr>
 <tr>
 <td class="col-sm-6 col-xs-6">TOTAL INGRESOS</td>';
 $tv = $fin->Select_TotalVenta($idE,$date);
 if ( $tv == 0 ) { $tv = '-'; } else { $tv = '$ '.number_format($tv,2,'.',','); }
 echo
 '<td class="col-sm-6 col-xs-6 text-right">'.$tv.'</td>';
 echo
 '</tr>
 </tbody>
 </table>
 </div>
 </div>


 <br>

 <div class="">
 <div class="col-sm-12 col-xs-12">
 <table class="table table-condensed table-stripped">
 <thead>
 <tr>
 <th colspan="2" style="font-size:1.5rem;">
 <strong>GASTOS</strong>
 </th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>TIPO DE GASTO</strong></td>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>MONTO</strong></td>
 </tr>';
 $sql = $fin->Select_GastoClase(1);
 $gasto_t = 0;
 foreach ($sql as $row) {
  $gasto = $fin->Select_GastoClase_Compras($idE,$row[0],$date);
  if ($gasto !=0) {

   echo
   '<tr>
   <td class="col-sm-6 col-xs-6">'.$row[1].'</td>';
   $gasto_t = $gasto_t + $gasto;
   if ( $gasto == 0 ) { $gasto = '-'; } else { $gasto = '$ '.number_format($gasto,2,'.',','); }
   echo
   '<td class="col-sm-6 col-xs-6 text-right">'.$gasto.'</td>';
   echo
   '</tr>';
  }

 }
 if ( $gasto_t == 0 ) { $gasto_t = '-'; } else { $gasto_t = '$ '.number_format($gasto_t,2,'.',','); }
 echo
 '<tr>
 <td class="col-sm-6 col-xs-6 bg-success">TOTAL</td>
 <td class="col-sm-6 col-xs-6 bg-success text-right">'.$gasto_t.'</td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>


 <br>

 <div class="">
 <div class="col-sm-12 col-xs-12">
 <table class="table table-condensed table-stripped">
 <thead>
 <tr>
 <th colspan="2" class="text-center">
 <strong>PAGOS</strong>
 </th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>TIPO DE GASTO</strong></td>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>MONTO</strong></td>
 </tr>';
 $sql = $fin->Select_GastoClase(2);
 $pago_t = 0 ;
 foreach ($sql as $row) {
  $pago = $fin->Select_GastoClase_Pagos($idE,$row[0],$date);

  if ($pago!=0) {
   echo
   '<tr>
   <td class="col-sm-6 col-xs-6">'.$row[1].'</td>';
   $pago_t = $pago_t + $pago;
   if ( $pago == 0 ) { $pago = '-'; } else { $pago = '$ '.number_format($pago,2,'.',','); }
   echo
   '<td class="col-sm-6 col-xs-6 text-right">'.$pago.'</td>';
   echo
   '</tr>';

  }
 }


 if ( $pago_t == 0 ) { $pago_t = '-'; } else { $pago_t = number_format($pago_t,2,'.',','); }
 echo
 '<tr>
 <td class="col-sm-6 col-xs-6 bg-success">TOTAL</td>
 <td class="col-sm-6 col-xs-6 bg-success text-right">'.$pago_t.'</td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <div class="">
 <div class="col-sm-12 col-xs-12">
 <table class="table table-condensed table-stripped">
 <thead>
 <tr>
 <th colspan="3" class="text-center">
 <strong>DETALLE DE GASTOS</strong>
 </th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="col-sm-4 col-xs-4 text-center bg-grey"><strong>PROVEEDOR</strong></td>
 <td class="col-sm-4 col-xs-4 text-center bg-grey"><strong>CONCEPTO</strong></td>
 <td class="col-sm-4 col-xs-4 text-center bg-grey"><strong>MONTO</strong></td>
 </tr>';
 $sql = $fin->Select_Detalle_Gastos($idE,$date);
 $monto_t = 0;
 foreach ($sql as $row) {
  $nc = $fin->Select_Name_Gasto($row[1]);
  $np = $fin->Select_Name_Proveedor($row[2]);
  echo
  '<tr>
  <td class="col-sm-4 col-xs-4">'.$np.'</td>
  <td class="col-sm-4 col-xs-4">'.$nc.'</td>';
  $monto = $row[3];
  $monto_t = $monto_t + $monto;
  if ( $monto == 0 ) { $monto = '-'; } else { $monto = '$ '.number_format($monto,2,'.',','); }
  echo
  '<td class="col-sm-4 col-xs-4 text-right">'.$monto.'</td>';
  echo
  '</tr>';
 }
 if ( $monto_t == 0 ) { $monto_t = '-'; } else { $monto_t = '$ '.number_format($monto_t,2,'.',','); }
 echo
 '<tr>
 <td class="col-sm-4 col-xs-4 bg-success"> </td>
 <td class="col-sm-4 col-xs-4 bg-success text-right">TOTAL</td>
 <td class="col-sm-4 col-xs-4 bg-success text-right">'.$monto_t.'</td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <div class="">
 <div class="col-sm-12 col-xs-12">
 <table class="table table-condensed table-stripped">
 <thead>
 <tr>
 <th colspan="3" class="text-center">
 <strong>DETALLE DE PAGOS</strong>
 </th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="col-sm-4 col-xs-4 text-center bg-grey"><strong>PROVEEDOR</strong></td>
 <td class="col-sm-4 col-xs-4 text-center bg-grey"><strong>CONCEPTO</strong></td>
 <td class="col-sm-4 col-xs-4 text-center bg-grey"><strong>MONTO</strong></td>
 </tr>';
 $sql = $fin->Select_Detalle_Pagos($idE,$date);
 $monto_t = 0;
 foreach ($sql as $row) {
  $nc = $fin->Select_Name_Gasto($row[1]);
  $np = $fin->Select_Name_Proveedor($row[2]);
  echo
  '<tr>
  <td class="col-sm-4 col-xs-4">'.$np.'</td>
  <td class="col-sm-4 col-xs-4">'.$nc.'</td>';
  $monto = $row[3];
  $monto_t = $monto_t + $monto;
  if ( $monto == 0 ) { $monto = '-'; } else { $monto = '$ '.number_format($monto,2,'.',','); }
  echo
  '<td class="col-sm-4 col-xs-4 text-right">'.$monto.'</td>';
  echo
  '</tr>';
 }
 if ( $monto_t == 0 ) { $monto_t = '-'; } else { $monto_t = '$ '.number_format($monto_t,2,'.',','); }
 echo
 '<tr>
 <td class="col-sm-4 col-xs-4 bg-success">TOTAL</td>
 <td class="col-sm-4 col-xs-4 bg-success"> </td>
 <td class="col-sm-4 col-xs-4 bg-success text-right">'.$monto_t.'</td>
 </tr>
 </tbody>
 </table>
 </div>
 </div>

 <div class="">
 <div class="col-sm-12 col-xs-12">
 <table class="table table-condensed table-stripped">
 <thead>
 <tr>
 <th colspan="4" class="text-center">
 <strong>PROVEEDORES</strong>
 </th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="col-sm-3 col-xs-3 text-center bg-grey"><strong>PROVEEDOR</strong></td>
 <td class="col-sm-3 col-xs-3 text-center bg-grey"><strong>COMPRA ACTUAL</strong></td>
 <td class="col-sm-3 col-xs-3 text-center bg-grey"><strong>PAGO ACTUAL</strong></td>
 <td class="col-sm-3 col-xs-3 text-center bg-grey"><strong>DEUDA TOTAL</strong></td>
 </tr>';
 $row = $fin->Select_Detalle_Proveedor($idE,$date);
 $c = count($row);
 for ($i=0; $i < $c; $i++) {
  $id = $row[$i];
  $np = $fin->Select_Name_Proveedor($id);
  $ph = $fin->Sum_Pagos_Actual($id,$date);
  if ( $ph == 0) { $ph = '-'; } else { $ph = '$ '.number_format($ph,2,'.',','); }
  $pg = $fin->Sum_Gastos_Actual($id,$date);
  if ( $pg == 0) { $pg = '-'; } else { $pg = '$ '.number_format($pg,2,'.',','); }
  $dt = $fin->Deuda_Total_Prov($id,$date);
  if ( $dt == 0) { $dt = '-'; } else { $dt = '$ '.number_format($dt,2,'.',','); }
  echo
  '<tr>
  <td class="col-sm-3 col-xs-3">'.$np.'</td>
  <td class="col-sm-3 col-xs-3 text-right">'.$pg.'</td>
  <td class="col-sm-3 col-xs-3 text-right">'.$ph.'</td>
  <td class="col-sm-3 col-xs-3 text-right">'.$dt.'</td>
  </tr>';
 }
 echo
 '</tbody>
 </table>
 </div>
 </div>

 ';
 ?>
</body>
</html>
