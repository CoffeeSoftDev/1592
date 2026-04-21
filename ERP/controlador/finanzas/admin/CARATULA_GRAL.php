<?php
include_once("../../../modelo/SQL_PHP/_METAS.php"); // <---
$obj = new METAS; // <--

$fi         = $_POST['date1'];
$ff         = $_POST['date2'];

$udn        = $_POST['udn'];
$txtFecha   = "Del ".$fi." al ".$ff;


if ($fi==$ff) {
 $txtFecha   = "".$fi;
}

$txt='';

// ===========================================
//    MAIN
// ===========================================
$ingreso = $obj-> caratula_ingresos($fi);


$txt=$txt.'
<div class="">
<div class="row">
<div class="col-xs-6 col-sm-6 ">
<h3 class=""> Diversificados Argovia S.A. de C.V</h3>
</div>
<div class="col-xs-6 col-sm-6 text-right">
<h5 class=""><strong> '.$txtFecha.'</strong></h5>
</div>
<div class="col-xs-12 col-sm-12 text-center">
<h3 class="form-control"><strong>RESUMEN GENERAL DE VENTAS</strong></h3>
</div>


<div class="col-xs-12 col-sm-12 table-responsive">
<table class="table table-bordered" Id="size1">
<thead>
<tr class="bg-info">
<th class="col-xs-6"></th>
<th class="col-xs-6 text-center">MONTO</th>
</tr>
</thead>
<tbody>
<tr>
<th class="col-xs-6">TOTAL INGRESOS</th>
<th class="col-xs-6 text-right">'.evaluar($ingreso).'</th>
</tr>
</tbody>
</table>
</div>


<div class="col-xs-12 col-sm-12 text-center">
<h3 class="form-control"><strong> GASTOS</strong></h3+
</div>
<div class="col-xs-12 col-sm-12 table-responsive">
<table class="table table-bordered" Id="size1">
<thead>
<tr class="bg-info">
<th class="col-xs-6 text-center">TIPO DE GASTO </th>
<th class="col-xs-6 text-center">MONTO</th>
</tr>
</thead>
<tbody>
<tr>
<th class="col-xs-6"> </th>
<th class="col-xs-6 text-right">$0.00</th>
</tr>
</tbody>

<tfoot>
<tr>
<th class="col-xs-6">TOTAL </th>
<th class="col-xs-6 text-right">$0.00</th>
</tr>
</tfoot>

</table>
</div>

<div class="col-xs-12 col-sm-12 text-center">
<h3 class="form-control"><strong> PAGOS </strong></h3>
</div>
<div class="col-xs-12 col-sm-12 table-responsive">
<table class="table table-bordered" Id="size1">
<thead>
<tr class="bg-info">
<th class="col-xs-6 text-center">PAGADOR </th>
<th class="col-xs-6 text-center">MONTO</th>
</tr>
</thead>
<tbody>
<tr>
<th class="col-xs-6"> </th>
<th class="col-xs-6 text-right">$0.00</th>
</tr>
</tbody>

<tfoot>
<tr>
<th class="col-xs-6">TOTAL </th>
<th class="col-xs-6 text-right">$0.00</th>
</tr>
</tfoot>

</table>
</div>

<div class="col-xs-12 col-sm-12 text-center">
<h3 class="form-control"><strong> PROVEEDORES </strong></h3>
</div>

';


//
//
//
// $txt=$txt.'
// <div class="">
// <div class="row">
// <div class="col-xs-6 col-sm-6 ">
// <h2 class="">Diversificados Argovia S.A. de C.V</h2>
// </div>
// <div class="col-xs-6 col-sm-6 text-right">
// <h4 class=""><strong> '.$txtFecha.'</strong></h4>
// </div>
// <div class="col-xs-12 col-sm-12 text-center">
// <h3 class="">RESUMEN GENERAL DE VENTAS</h3>
// </div>
//
//
// <div class="col-xs-12 col-sm-12 table-responsive">
// <table class="table table-bordered" Id="size1">
// <thead>
// <tr class="bg-primary">
// <th>INGRESO TURISMO</th>
// <th>SUBTOTAL</th>
// <th>IVA</th>
// <th>2% HOSP</th>
// <th>TOTAL</th>
// </tr>
// </thead>
// <tbody>';
//
// $categorias        = $obj -> VER_CATEGORIAS();
// $subtotal          = 0;
// $graltotal         = 0;
// $TotalFormasPago   = 0;
// $TotalPropinas     = 0;
//
//
// foreach ($categorias as $key ) { // ingresos ej. Hospedaje,Restaurant,Tours...
//  $ingreso_categoria = $obj -> VER_INGRESOS_FECHA($key[0],$fi,$ff);
//  $IVA16=($ingreso_categoria/100)* 16;
//  $IVA2 =0;
//  if ($key[1]=="HOSPEDAJE") {
//   $IVA2=($ingreso_categoria/100)* 2;
//  }
//
//  $TOTAL =$ingreso_categoria+$IVA16+$IVA2;
//  $txt=$txt.'
//  <tr>
//  <td id="col_1">'.$key[1].'</td>
//  <td class="text-right">'.evaluar($ingreso_categoria).'</td>
//  <td class="text-right">'.evaluar($IVA16).'</td>
//  <td class="text-right">'.evaluar($IVA2).'</td>
//  <td class="text-right">'.evaluar($TOTAL).'</td>
//  ';
//  $subtotal += $ingreso_categoria;
//  $graltotal+= $TOTAL;
// }
// $txt=$txt.'</tr>';
// $txt=$txt.'
// </tbody>
// <tfoot>
// <tr>
// <td>
// <strong>TOTAL NETO:</strong>
// </td>
// <td class="text-right">'.evaluar($subtotal).'</td>
// <td colspan="4"></td>
// </tr>
// <tr class="bg-info">
// <td colspan="3">
// <strong>TOTAL INGRESOS:</strong>
// </td>
// <td colspan="3" class="text-right">'.evaluar($graltotal).'</td>
// </tr>
// </tfoot>
// </table>
// </div>';
//
// $obj    = new METAS; // <--
// $formas = $obj -> VER_FORMAS_PAGO();
//
// $txt=$txt.'<!-- -->
// <div class="col-sm-12 col-xs-12">
// <table class="table table-bordered" Id="size1">
// <thead>
// <tr class="bg-info">
// <th class="col-sm-6">FORMA DE PAGO</th>
// <th class="bg-primary">TOTAL</th>
// <th>OBSERVACIONES</th>
// </tr>
// </thead>
//
//
// <tbody>';
// $contara =0;
// $verInfo =$obj -> verFolio($fi,1);
// $idF     = "";
// $Obs     = "";
// foreach ($verInfo as $key ) {
//  $idF = $key[0];
//  $Obs = $key[3];
// }
//
// $descripcion = _td($idF,"desc",$Obs,"rowspan='4' colspan = '3' ");
// foreach ($formas as $key ) { // CXC,T.P,
//  $contara +=1;
//  $Ok      = $obj -> VER_TIPOSPAGOS_FECHA($key[0],$fi,$ff);
//
//  $TotalFormasPago +=$Ok;
//  $txt=$txt.'<tr>
//  <td id="col_1">'.$key[1].'</td>';
//  $txt=$txt.'<td class="text-right" >'.evaluar($Ok).'</td>';
//  if ($contara==1) {
//   $txt=$txt.''.$descripcion.'</tr>';
//  }
// }
//
// $txt=$txt.'
//
// </tbody>
// <tfoot>
// <tr class="bg-info">
// <td>TOTAL FORMAS DE PAGO:</td>
// <td class="text-right">'.evaluar($TotalFormasPago).'</td>
// <td></td>
// </tr>
// </tfoot>
// </table>
// </div>
// <!-- -->';
//
//
// $txt=$txt.'
// <div class="col-sm-8 col-xs-12">
// <table class="table table-bordered" Id="size1">
// <thead>
// <tr class="bg-info">
// <th class="col-sm-8">FORMA DE PAGO PROPINA</th>
// <th class="bg-primary">TOTAL</th>
// </tr>
// </thead><tbody>';
//
//
// foreach ($formas as $key ) { // CXC,T.P,
//  $Ok      = $obj -> VER_PROPINA_FECHA($key[0],$fi,$ff,'PROPINAS');
//  $TotalPropinas += $Ok;
//  $txt=$txt.'<tr>
//  <th id="col_1">'.$key[1].'</th>';
//  $txt=$txt.'<td class="text-right" >'.evaluar($Ok).'</td></tr>';
//
// }
//
//
//
// $txt=$txt.'
// </tbody>
//
//
//
// <tfoot>
// <tr class="bg-info">
// <td>TOTAL FORMAS DE PAGO PROPINA:</td>
// <td class="text-right">'.evaluar($TotalPropinas).'</td>
// </tr>
// </tfoot>
// </table>
// </div>
//
// <div class="col-sm-12 col-xs-12 text-right">
// <label> <h3>TOTAL GENERAL: </h3></label>
// <label><h3>'.evaluar($TotalPropinas+$TotalFormasPago).'</h3></label>
// </div>
//
// </div>
// </div>
// <!-- ./ container -->
// ';
// ===========================================
//     ENCODE JSON
// ===========================================
$encode = array(
 0=>$txt);
 echo json_encode($encode);

 /*==========================================
 *		FUNCIONES / FORMULAS
 =============================================*/
 //

 function _td($id,$campo,$valor,$conf){
  $txt='
  <td  id="txt'.$campo.$id.'" '.$conf.'>

  <div  onclick="col('.$id.',\''.$campo.'\' )">
  <span id="lbl'.$campo.$id.'">'.$valor.'</span>
  </div>
  </td>
  ';
  return $txt;
 }

 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="" || $val == null) {
   $res = '-';
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }
  return $res;
 }

 ?>
