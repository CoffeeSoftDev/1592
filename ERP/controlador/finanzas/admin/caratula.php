<?php
// session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas.php');
include_once("../../../modelo/SQL_PHP/_Utileria.php");
include_once('../../../modelo/SQL_PHP/_Perfil.php');

$util = new Util;
$fin = new Finanzas;
$perfil = new PERFIL;

$idE = $_POST['udn'];
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$udn = $perfil->Select_Name_UDN($idE);//Obtener el nombre de la empresa

//Total de Venta -----------------------------------------------
$TV = $fin->Select_Total_Venta($idE,$date1,$date2);
$TV_SJ = $fin->Select_Total_Venta(9,$date1,$date2); // porque 9 ?
$TV_Total = $TV + $TV_SJ;
// -------------------------------------------------------------

//Propina Actual --------------------------------------------------
$propina_actual = $fin->Select_Total_Efectivo(1,$idE,$date1,$date2);
$propina_actual_sj = $fin->Select_Total_Efectivo(1,9,$date1,$date2);
$propina_total = $propina_actual + $propina_actual_sj;
// -------------------------------------------------------------

//Efectivo Actual ----------------------------------------------
$Efectivo_actual = $fin->Select_Total_Efectivo(2,$idE,$date1,$date2);
$Efectivo_actual_SJ = $fin->Select_Total_Efectivo(2,9,$date1,$date2);
$Efectivo_actual_Total = $Efectivo_actual + $Efectivo_actual_SJ;
// -------------------------------------------------------------

//Bancos -------------------------------------------------------
$Bancos = $fin->Select_Total_Bancos($idE,$date1,$date2);
$Bancos_SJ = $fin->Select_Total_Bancos(9,$date1,$date2);
$Bancos_Total = $Bancos + $Bancos_SJ;
// -------------------------------------------------------------


//Deuda Creditos -----------------------------------------------
$DC = $fin->Select_Total_DeudaCredito($idE,$date1,$date2);
// -------------------------------------------------------------


//Pagos Creditos
$PC = $fin->Select_Total_PagosCredito($idE,$date1,$date2);
$Total = $DC + $PC;
// -------------------------------------------------------------

$sql_moneda = $fin->Select_Total_Monedas($idE,$date1,$date2);

$mon = array(); $val_mon = array();

foreach ($sql_moneda as $key => $value) {
 $mon[$key] = $value[0];
 if(!isset($value[1])){$vl_mon = 0;}else{ $vl_mon = $value[1] * $value[2];}
 $val_mon[$key] = $vl_mon;
}

$cnt = count($mon);
$total_monedas = 0;
// --------------------------------------------------------------------

$sql_moneda_sj = $fin->Select_Total_Monedas(9,$date1,$date2);
$mon_sj = array(); $val_mon_sj = array();
foreach ($sql_moneda as $key => $value) {
 $mon_sj[$key] = $value[0];
 if(!isset($value[1])){$vl_mon = 0;}else{ $vl_mon = $value[1] * $value[2];}
 $val_mon_sj[$key] = $vl_mon;
}
$cnt_sj = count($mon_sj);
$total_monedas_sj = 0;


/*******************************************
SALDO INICIAL Y FINAL DEL FONDO DE CAJA
*******************************************/

//CONSULTAR SALDO INICIAL Y FECHA DE INICIO
$row = null;
$sql = $fin->Select_Fondo_Fijo($idE,$date1);
foreach ($sql as $row);

//CONSULTAR GASTOS  ANTICIPOS & PROVEEDORES DE FONDO PARA CALCULAR SALDO INICIAL
$gasto_si = $fin->Select_Gasto_Remaster($idE,$row[0],$date1);
$anticipo_si = 0; // temporal var <-----
// $anticipo_si = $fin->Select_Ancitipo_Remaster($idE,$row[0],$date1);

$proveedor_si = $fin->Select_Pago_Proveedor_Remaster ($idE,$row[0],$date1);
$SI = $row[1] - ($gasto_si + $anticipo_si + $proveedor_si);

//CONSULTAR REMBOLSO
$rembolso = $fin->Select_Rembolso_Remaster($idE,$date1,$date2);

// --------------------------------------------------------------------------------


//CONSULTAR GASTO ACTUAL
$gasto_actual = $fin->Select_Gasto_Remaster_actual($idE,$date1,$date2);

$anticipo_actual = 0; // <-- Variable Temporal
// $anticipo_actual = $fin->Select_Ancitipo_Remaster_actual($idE,$date1,$date2);

$proveedor_actual = $fin->Select_Pago_Proveedor_Remaster_actual($idE,$date1,$date2);
$fondo_actual = $gasto_actual + $anticipo_actual + $proveedor_actual;
//Saldo Final del Rembolso
$SF = $SI + $rembolso - $fondo_actual;

//Anticipos ----------------------------------------------------------
// $Ant = $fin->Select_Total_Anticipo($idE,$date1,$date2);
$Ant = 0; // <-- Variable Temporal
// -------------------------------------------------------------------




//RETIROS NORMALES ---------------------------------------------------------------------
$row_retiro_normal = null;
$sql_retiro = $fin->Select_SI_Retiro_Efectivo($idE,$date1);
foreach ($sql_retiro as $row_retiro_normal);

if ( !isset($row_retiro_normal[1]) ) { $row_retiro_normal[1] = 0; }

$propina_remaster_1 = $fin->Select_Efectivo_Remaster($idE,$row_retiro_normal[0],$date1,1);
$efectivo_remaster_2 = $fin->Select_Efectivo_Remaster($idE,$row_retiro_normal[0],$date1,2);
$Efectivo_Retiro_SI = $efectivo_remaster_2 - $propina_remaster_1;

$Propina_remaster_SJ = $fin->Select_Efectivo_Remaster(9,$row_retiro_normal[0],$date1,1);
$Efectivo_remaster_SJ = $fin->Select_Efectivo_Remaster(9,$row_retiro_normal[0],$date1,2);
$Efectivo_Retiro_SI_SJ = $Efectivo_remaster_SJ - $Propina_remaster_SJ;

$Efectivo_Total_SI = $Efectivo_Retiro_SI;
if ( $idE == 6 ) { $Efectivo_Total_SI = $Efectivo_Retiro_SI + $Efectivo_Retiro_SI_SJ; }

// -----------------------------------------------------------------------------



//CONSULTAR LAS MONEDAS EXTRAJERAS INGRESADAS
$sql_moneda_remaster = $fin->Select_Data_Moneda(); $Moneda_Remaster_SI = 0;
foreach ($sql_moneda_remaster as $key => $value) {
 //idMoneda $value[0]
 $Mon_temporal = $fin->Select_Moneda_Remaster($idE,$row_retiro_normal[0],$date1,$value[0]);
 $Mon_temporal_SJ = $fin->Select_Moneda_Remaster(9,$row_retiro_normal[0],$date1,$value[0]);
 $Moneda_Remaster_SI = $Moneda_Remaster_SI + $Mon_temporal;
 if ($idE == 6) { $Moneda_Remaster_SI = $Moneda_Remaster_SI + $Mon_temporal + $Mon_temporal_SJ; }
}
$SI_Retiro = $row_retiro_normal[1] + $Efectivo_Total_SI + $Moneda_Remaster_SI;
//RETIRO TOTAL DE EFECTIVO
$Retiro_Total = $fin->Select_Retiro_Remaster($idE,$date1,$date2);

//Datos de proveedor
$array = array($idE,$date1);
$si_prov = $fin->Select_SI_Proveedor($array);
$gasto_proveedor = $fin->Select_Suma_Total_Proveedor_Gastos($idE,$date1,$date2);
$pagos_proveedor = $fin->Select_Suma_Total_Proveedor_Pagos($idE,$date1,$date2);
$sf_prov = $si_prov + $gasto_proveedor - $pagos_proveedor;

echo '
<div class="row">
<div class="form-group col-sm-12 col-xs-12 ">
<div class="col-sm-12 col-xs-12">
<hr>

<label class="col-xs-12 col-sm-12" ><h3><span class=""></span>REPORTE GENERAL DE '.$udn.'</h3></label>
</div>
</div>
</div>
';

if ($idE != 6) {
 echo '
 <div class="form-group col-sm-12 col-xs-12">
 <table class="table table-bordered table-striped table-condensed table-hover">
 <thead>
 <tr>
 <th class="col-sm-5 text-center">CONCEPTO</th>
 <th class="col-sm-2 text-center">INGRESOS</th>
 <th class="col-sm-2 text-center">EGRESO</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <th>Total de Venta</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($TV,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 </tr>
 <tr>
 <th>Propina</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($propina_actual,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 </tr>
 <tr>
 <th>Efectivo</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Efectivo_actual,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 </tr>';
 foreach ($sql_moneda as $key => $value) {
  $total_monedas = $total_monedas + ($value[1]*$value[2]);
  echo '
  <tr>
  <th>'.$value[0].'</th>
  <td>
  <div class="input-group">
  <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
  <input type="text" class="form-control input-sm" value="'.number_format($value[1]*$value[2],2,'.',', ').'" disabled/>
  </div>
  </td>
  <td></td>
  </tr>
  ';
 }
 echo
 '<tr>
 <th>Bancos</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Bancos,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 </tr>
 <tr>
 <th>Creditos</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($DC,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($PC,2,'.',', ').'" disabled/>
 </div>
 </td>
 </tr>
 <tr>';
 $TCaja = $Bancos + $Efectivo_actual + $total_monedas - $propina_actual;
 $Dif = ($TV * -1) + $TCaja + $DC - $PC;
 echo
 '<th>Diferencia</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Dif,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 </tr>
 </tbody>
 </table>
 </div>
 ';
}
else {
 echo '
 <div class="form-group col-sm-12 col-xs-12">
 <table class="table table-bordered table-striped table-condensed table-hover">
 <thead>
 <tr>
 <th class="col-sm-2 text-center">CONCEPTO</th>
 <th class="col-sm-1 text-center">INGRESOS</th>
 <th class="col-sm-1 text-center">EGRESO</th>
 <th class="col-sm-1 text-center">SAN JUAN</th>
 <th class="col-sm-1 text-center">TOTALES</th>
 </tr>
 </thead>
 <tbody>
 <tr>
 <th>Total de Venta</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($TV,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($TV_SJ,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($TV_Total,2,'.',', ').'" disabled/>
 </div>
 </td>
 </tr>
 <tr>
 <th>Propina</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($propina_actual,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($propina_actual_sj,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($propina_total,2,'.',', ').'" disabled/>
 </div>
 </td>
 </tr>
 <tr>
 <th>Efectivo</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Efectivo_actual,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Efectivo_actual_SJ,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Efectivo_actual_Total,2,'.',', ').'" disabled/>
 </div>
 </td>
 </tr>';
 $cont = 0;

 if ($cnt > $cnt_sj) { $cont = $cnt; }
 else if ($cnt_sj > $cnt) { $cont = $cnt_sj; }
 for ($i=0; $i < $cont; $i++) {
  $total_monedas = $total_monedas + $val_mon[$i];
  $total_monedas_sj = $total_monedas_sj + $val_mon_sj[$i];
  $total = $val_mon_sj[$i] + $val_mon[$i];
  echo '<tr>';
  if ($cnt > $cnt_sj) {
   echo '<td>'.$mon[$i].'</td>';
  }
  else if ($cnt_sj > $cnt) {
   echo '<td>'.$mon_sj[$i].'</td>';
  }

  echo '
  <td>
  <div class="input-group">
  <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
  <input type="text" class="form-control input-sm" value="'.number_format($val_mon[$i],2,'.',', ').'" disabled/>
  </div>
  </td>
  <td></td>
  <td>
  <div class="input-group">
  <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
  <input type="text" class="form-control input-sm" value="'.number_format($val_mon_sj[$i],2,'.',', ').'" disabled/>
  </div>
  </td>
  <td>
  <div class="input-group">
  <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
  <input type="text" class="form-control input-sm" value="'.number_format($total,2,'.',', ').'" disabled/>
  </div>
  </td>
  ';
 }
 echo
 '<tr>
 <th>Bancos</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Bancos,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Bancos_SJ,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Bancos_Total,2,'.',', ').'" disabled/>
 </div>
 </td>
 </tr>
 <tr>
 <th>Creditos</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($DC,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($PC,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Total,2,'.',', ').'" disabled/>
 </div>
 </td>
 </tr>';
 //Calcular diferencia
 $TCaja = $Bancos + $Efectivo_actual + $total_monedas - $propina_actual;
 $Dif = ($TV * -1) + $TCaja + $DC - $PC;

 $TCaja_SJ = $Bancos_SJ + $Efectivo_actual_SJ + $total_monedas_sj - $propina_actual_sj;
 $Dif_SJ = ($TV_SJ * -1) + $TCaja_SJ;

 $Dif_Total = $Dif + $Dif_SJ;
 echo
 '<tr>
 <th>Diferencias</th>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Dif,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td></td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Dif_SJ,2,'.',', ').'" disabled/>
 </div>
 </td>
 <td>
 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text" class="form-control input-sm" value="'.number_format($Dif_Total,2,'.',', ').'" disabled/>
 </div>
 </td>
 </tr>
 </tbody>
 </table>
 </div>
 ';
}

/*******************************************
SALDO INICIAL Y FINAL DEL FONDO DE CAJA
*******************************************/
$Total_Actual_Efectivo = $Efectivo_actual + $total_monedas - $propina_actual;
if ($idE == 6) {
 $Total_Actual_Efectivo = $Efectivo_actual + $Efectivo_actual_SJ + $total_monedas + $total_monedas_sj - $propina_actual - $propina_actual_sj;
}
$SF_Retiro = $SI_Retiro + $Total_Actual_Efectivo - $Retiro_Total;

echo '
<div class="form-group col-sm-12 col-xs-12">
<table class="table table-bordered table-striped table-hover">
<thead>
<tr>
<th class="text-center">SALDO INICIAL</th>
<th class="text-center">INGRESOS</th>
<th class="text-center">EGRESOS</th>
<th class="text-center">SALDO FINAL</th>
</tr>
</thead>
<tbody>
<tr>
<th colspan="4" class="text-center">FONDO</th>
</tr>
<tr>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($SI,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($rembolso,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($fondo_actual,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($SF,2,'.',', ').'" disabled/>
</div>
</td>
</tr>
<tr>
<th colspan="4" class="text-center">ANTICIPOS</th>
</tr>
<tr>
<td></td>
<td></td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($Ant,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($Ant,2,'.',', ').'" disabled/>
</div>
</td>
</tr>
<tr>
<th colspan="4" class="text-center">RETIROS</th>
</tr>
<tr>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($SI_Retiro,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($Total_Actual_Efectivo,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($Retiro_Total,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($SF_Retiro,2,'.',', ').'" disabled/>
</div>
</td>
</tr>
<tr>
<th colspan="4" class="text-center">PROVEEDORES</th>
</tr>
<tr>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($si_prov,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($gasto_proveedor,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($pagos_proveedor,2,'.',', ').'" disabled/>
</div>
</td>
<td>
<div class="input-group">
<span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
<input type="text" class="form-control input-sm" value="'.number_format($sf_prov,2,'.',', ').'" disabled/>
</div>
</td>
</tr>
</tbody>
</table>
</div>
';
?>
