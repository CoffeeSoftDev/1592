<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas_Caratula.php');
$fin = new Caratula;

$idE = $_SESSION['udn'];
$date = $_POST['date'];

echo '
<br>

<div class="row">
<div class="col-xs-12 text-right">
<button class="btn btn-sm btn-info" onClick="Print_caratula_cliente();"><span class="icon-file-pdf"></span> Imprimir </button>
</div>

</div>

<br>
<div class="">
<div class="col-xs-3 col-sm-3">
<img src="recursos/img/logo_c.png" width="100px" class="img-rounded center-block">
</div>

<div class="col-xs-3 col-sm-6 text-center">
<!-- <h2 class="">Diversificados Argovia S.A. de C.V</h2> -->
</div>

<div class="col-xs-3 col-sm-3 text-right">
<h4 class="col-sm-12"><strong> '.$date.'</strong></h4>
</div>
</div>
<!-- <div class="row">
<div class="form-group col-sm-12 col-xs-12 text-right">
<button class="btn btn-sm btn-warning" onClick="ver_pdf_caratula_cliente();"><span class="icon-file-pdf"></span></button>
</div>
</div> -->



<br>
<div class="">
<div class="col-sm-12 col-xs-12 " >
<table class="table  table-condensed table-stripped">
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
<td class="col-sm-6 col-xs-6">TOTAL DE INGRESOS</td>';
$tv = $fin->Select_TotalVenta($idE,$date);
if ( $tv == 0 ) { $tv = '-'; } else { $tv = '$ '.number_format($tv,2,'.',','); }
echo
'<td class="col-sm-6 col-xs-6 text-right"> '.$tv.'</td>';
echo
'</tr>
</tbody>
</table>
</div>
</div>

<div class="">
<div class="col-sm-12 col-xs-12">
<table class="table  table-condensed table-stripped">
<thead>
<tr>
<th colspan="2" style="font-size:1.5rem;">
<strong>GASTOS</strong>
</th>
</tr>
</thead>
<tbody>
<tr>
<td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>TIPO</strong></td>
<td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>MONTO</strong></td>
</tr>';
$sql = $fin->Select_GastoClase(1);
$gasto_t = 0;

foreach ($sql as $row) {

 $gasto   = $fin->Select_GastoClase_Compras($idE,$row[0],$date);

 if ($gasto!=0) {

  echo
  '<tr>
  <td class="col-sm-6 col-xs-6">'.$row[1].'</td>';

  $gasto_t = $gasto_t + $gasto;

  echo '<td class="col-sm-6 col-xs-6 text-right">'.evaluar($gasto).'</td>';
  echo'</tr>';

 }// end if


} // end for

echo
'
</tbody>
<tfood >
<tr style="font-size:1.3rem; font-weight:bold;">
<td class="col-sm-6 col-xs-6 ">TOTAL</td>
<td class="col-sm-6 col-xs-6  text-right">'.evaluar($gasto_t).'</td>
</tr>
</tfood>


</table>
</div>
</div>';


// Compras ---

$com   = compras($fin,$idE,$date);
echo   $com;


//  PAGO ---
echo '<div class="">
<div class="col-sm-12 col-xs-12">
<table class="table  table-condensed table-stripped">
<thead>
<tr>
<th colspan="2" style="font-size:1.5rem;">
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
 echo
 '<tr>
 <td class="col-sm-6 col-xs-6">'.$row[1].'</td>';
 $pago = $fin->Select_GastoClase_Pagos($idE,$row[0],$date);
 $pago_t = $pago_t + $pago;
 if ( $pago == 0 ) { $pago = '-'; } else { $pago = '$ '.number_format($pago,2,'.',','); }
 echo
 '<td class="col-sm-6 col-xs-6 text-right">'.$pago.'</td>';
 echo
 '</tr>';
}
if ( $pago_t == 0 ) { $pago_t = '-'; } else { $pago_t = number_format($pago_t,2,'.',','); }
echo
'<tr>
<td class="col-sm-6 col-xs-6 ">TOTAL</td>
<td class="col-sm-6 col-xs-6  text-right">'.$pago_t.'</td>
</tr>
</tbody>
</table>
</div>
</div>

<div class="">
<div class="col-sm-12 col-xs-12">
<table class="table  table-condensed table-stripped">
<thead>
<tr>
<th colspan="4" class="" style="font-size:1.5rem;">
<strong>DETALLE DE GASTOS</strong>
</th>
</tr>
</thead>
<tbody>
';

$sql = $fin->Select_GastoClase(1);
$gasto_t = 0;

foreach ($sql as $row) {

 $gasto   = $fin->Select_GastoClase_Compras($idE,$row[0],$date);

 if ($gasto!=0) {
  echo
  '<tr><td class="col-sm-6 col-xs-6 bg-grey" colspan="3"><strong>'.$row[1].'</strong></td>';

  $gasto_t = $gasto_t + $gasto;

  echo '<td class="col-sm-6 col-xs-6 text-right bg-grey"><strong>'.evaluar($gasto).'</strong></td>';
  echo'</tr>';

  if ($gasto_t!=0) {
   echo '<tr>
   <td class="col-sm-2 col-xs-3 text- "><strong>DESTINO</strong></td>
   <td class="col-sm-4 col-xs-3 text- "><strong>PROVEEDOR</strong></td>
   <td class="col-sm-4 col-xs-3 text- "><strong>CONCEPTO</strong></td>
   <td class="col-sm-2 col-xs-3 text- "><strong>MONTO</strong></td>
   </tr>';
  }

  $array   = array($date,$row[0],$idE);
  $ok      = $fin->detalles_gastos($array);

  foreach ($ok as $x) {

   echo '<tr>
   <td>'.$x[0].' </td>
   <td>'.$x[1].' </td>
   <td>'.$x[2].' </td>
   <td class="text-right">'.evaluar($x[3]).'</td>
   </tr>';
  }

 } //end If
} // end for



echo
'
</tbody>
</table>
</div>
</div>

<div class="">
<div class="col-sm-12 col-xs-12">
<table class="table  table-condensed table-stripped">
<thead>
<tr>
<th colspan="3" class="text-center">
<strong>DETALLE DE PAGOS</strong>
</th>
</tr>
</thead>
<tbody>
<tr>
<td class="col-sm-4 col-xs-4 text-center bg-"><strong>PROVEEDOR</strong></td>
<td class="col-sm-4 col-xs-4 text-center bg-"><strong>CONCEPTO</strong></td>
<td class="col-sm-4 col-xs-4 text-center bg-"><strong>MONTO</strong></td>
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
<td class="col-sm-4 col-xs-4 ">TOTAL</td>
<td class="col-sm-4 col-xs-4 "> </td>
<td class="col-sm-4 col-xs-4  text-right">'.$monto_t.'</td>
</tr>
</tbody>
</table>
</div>
</div>

<div class="">
<div class="col-sm-12 col-xs-12">
<table class="table  table-condensed table-stripped">
<thead>
<tr>
<th colspan="4" class="text-center">
<strong>PROVEEDORES</strong>
</th>
</tr>
</thead>
<tbody>
<tr>
<td class="col-sm-3 col-xs-3 text-center bg-"><strong>PROVEEDOR</strong></td>
<td class="col-sm-3 col-xs-3 text-center bg-"><strong>COMPRA ACTUAL</strong></td>
<td class="col-sm-3 col-xs-3 text-center bg-"><strong>PAGO ACTUAL</strong></td>
<td class="col-sm-3 col-xs-3 text-center bg-"><strong>DEUDA TOTAL</strong></td>
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

/*-----------------------------------*/
/*	 Complementos **
/*-----------------------------------*/

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }

 return $res;
}

function compras($obj,$udn,$date){
 $tb         = '';
 $compras    = '';

 $tb = $tb.'
 <br></br>
 <div class="">
 <div class="col-sm-12 col-xs-12">
 <table class="table  table-condensed table-stripped">
 <thead>
 <tr>
 <th colspan="2" style="font-size:1.5rem;">
 <strong> COMPRAS</strong>
 </th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>TIPO</strong></td>
 <td class="col-sm-6 col-xs-6 text-center bg-grey"><strong>MONTO</strong></td>
 </tr>';

 $sql = $obj ->Select_GastoClase(1);

 foreach ($sql as $row) {
  $gasto   = $obj ->ver_Compras($udn,$row[0],$date);

  if ($gasto!=0) {
   $tb=$tb.
   '<tr> <td class="col-sm-6 col-xs-6">'.$row[1].'</td>';
   $compras = $compras + $gasto;

   $tb=$tb.
   '<td class="col-sm-6 col-xs-6 text-right">'.evaluar($gasto).'</td>
   </tr>';
  }

 } // end for

 $tb = $tb.'
 </tbody>
 <tfood >
 <tr style="font-size:1.3rem; font-weight:bold;">
 <td class="col-sm-6 col-xs-6 ">TOTAL</td>
 <td class="col-sm-6 col-xs-6  text-right">'.evaluar($compras).'</td>
 </tr>
 </tfood>
 </table>
 </div></div>';
 return $tb;


}

?>
