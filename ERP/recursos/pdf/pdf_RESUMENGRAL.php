<?php
session_start();


include_once("../../modelo/SQL_PHP/_METAS.php"); // <---
$obj = new METAS; // <--

$fi         = $_GET['date1'];
$ff         = $_GET['date2'];
$udn        = $_GET['udn'];
$txtFecha   = "Del ".$fi." al ".$ff;

if ($fi==$ff) {
$txtFecha   = "".$fi;
}

$txt='';
// ===========================================
//     FUNCIONES-PHP
// ===========================================
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

<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <title></title>

 <link rel="stylesheet" href="../../recursos/css/formato_impresion.css">
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
  padding: 3px;
  line-height: 1.32857143;
  vertical-align: top;
  border-top: 1.4px solid #ecf0f1;
 }
 </style>

</head>


<body onload="imprimir();">

 <?php
 $txt=$txt.'
 <div class="">
 <div class="row">
 <div class="col-xs-6 col-sm-6 ">
 <h3 class="">Diversificados Argovia S.A. de C.V</h3>
 </div>
 <div class="col-xs-6 col-sm-6 text-right">
 <h4 class=""><strong> '.$txtFecha.'</strong></h4>
 </div>

 <br><br>
 <div class="col-xs-12 col-sm-12 text-center">
 <h3 class="">RESUMEN GENERAL DE VENTAS</h3>
 </div>
<br>


 <div class="col-xs-12 col-sm-12 table-responsive">
 <table class="table table-bordered">
 <thead>
 <tr class="bg-primary">
 <th>INGRESO TURISMO</th>
 <th>SUBTOTAL</th>
 <th>IVA</th>
 <th>2% HOSP</th>
 <th>TOTAL</th>
 </tr>
 </thead>
 <tbody>';

 $categorias        = $obj -> VER_CATEGORIAS();
 $subtotal          = 0;
 $graltotal         = 0;
 $TotalFormasPago   = 0;
 $TotalPropinas     = 0;


 foreach ($categorias as $key ) { // ingresos ej. Hospedaje,Restaurant,Tours...
  $ingreso_categoria = $obj -> VER_INGRESOS_FECHA($key[0],$fi,$ff);
  $IVA16=($ingreso_categoria/100)* 16;
  $IVA2 =0;
  if ($key[1]=="HOSPEDAJE") {
   $IVA2=($ingreso_categoria/100)* 2;
  }

  $TOTAL =$ingreso_categoria+$IVA16+$IVA2;
  $txt=$txt.'
  <tr>
  <td id="col_1">'.$key[1].'</td>
  <td class="text-right">'.evaluar($ingreso_categoria).'</td>
  <td class="text-right">'.evaluar($IVA16).'</td>
  <td class="text-right">'.evaluar($IVA2).'</td>
  <td class="text-right">'.evaluar($TOTAL).'</td>
  ';
  $subtotal += $ingreso_categoria;
  $graltotal+= $TOTAL;
 }

 $txt=$txt.'</tr>';
 $txt=$txt.'
 </tbody>
 <tfoot>
 <tr>
 <td>
 <strong>TOTAL NETO:</strong>
 </td>
 <td class="text-right">'.evaluar($subtotal).'</td>
 <td colspan="4"></td>
 </tr>
 <tr class="bg-info">
 <td colspan="3">
 <strong>TOTAL INGRESOS:</strong>
 </td>
 <td colspan="3" class="text-right">'.evaluar($graltotal).'</td>
 </tr>
 </tfoot>
 </table>
 </div>';

 $obj    = new METAS; // <--
 $formas = $obj -> VER_FORMAS_PAGO();

 $txt=$txt.'<!-- -->
 <div class="col-sm-12 col-xs-12">
 <br>
 <table class="table table-bordered" Id="size1">
 <thead>
 <tr class="bg-info">
 <th class="col-sm-6">FORMA DE PAGO</th>
 <th class="bg-primary">TOTAL</th>
 <th>OBSERVACIONES</th>
 </tr>
 </thead>


 <tbody>';
 $contara =0;

 $verInfo =$obj -> verFolio($fi,1);
 $idF     = "";
 $Obs     = "";
 foreach ($verInfo as $key ) {
  $idF = $key[0];
  $Obs = $key[3];
 }

 $descripcion = _td($idF,"desc",$Obs,"rowspan='4' colspan = '3' ");

 foreach ($formas as $key ) { // CXC,T.P,
  $contara +=1;
  $Ok      = $obj -> VER_TIPOSPAGOS_FECHA($key[0],$fi,$ff);

  $TotalFormasPago +=$Ok;
  $txt=$txt.'<tr>
  <td id="col_1">'.$key[1].'</td>';
  $txt=$txt.'<td class="text-right" >'.evaluar($Ok).'</td>';
  if ($contara==1) {
   $txt=$txt.''.$descripcion.'</tr>';
  }
 }

 $txt=$txt.'


 </tbody>
 <tfoot>
 <tr class="bg-info">
 <td>TOTAL FORMAS DE PAGO:</td>
 <td class="text-right">'.evaluar($TotalFormasPago).'</td>
 <td></td>
 </tr>
 </tfoot>
 </table>
 </div>
 <!-- -->';


 $txt=$txt.'
 <div class="col-sm-8 col-xs-12">
 <br><br>
 <table class="table table-bordered" Id="size1">
 <thead>
 <tr class="bg-info">
 <th class="col-sm-8">FORMA DE PAGO PROPINA</th>
 <th class="bg-primary">TOTAL</th>
 </tr>
 </thead><tbody>';


 foreach ($formas as $key ) { // CXC,T.P,
  $Ok      = $obj -> VER_PROPINA_FECHA($key[0],$fi,$ff,'PROPINAS');
  $TotalPropinas += $Ok;
  $txt=$txt.'<tr>
  <th id="col_1">'.$key[1].'</th>';
  $txt=$txt.'<td class="text-right" >'.evaluar($Ok).'</td></tr>';

 }



 $txt=$txt.'
 </tbody>



 <tfoot>
 <tr class="bg-info">
 <td>TOTAL FORMAS DE PAGO PROPINA:</td>
 <td class="text-right">'.evaluar($TotalPropinas).'</td>
 </tr>
 </tfoot>
 </table>
 </div>

 <div class="col-sm-12 col-xs-12 text-right">
 <label> <h3>TOTAL GENERAL: </h3></label>
 <label><h3>'.evaluar($TotalPropinas+$TotalFormasPago).'</h3></label>
 </div>

 </div>
 </div>
 <!-- ./ container -->
 ';
 // ===========================================
 //     PRINT
 // ===========================================
 echo $txt;
 /*==========================================
 *		FUNCIONES / FORMULAS
 =============================================*/

 function _td($id,$campo,$valor,$conf){
   $txt='
   <td  id="txt'.$campo.$id.'" '.$conf.'>

   <div >
   <span id="lbl'.$campo.$id.'">'.$valor.'</span>
   </div>
   </td>
   ';
   return $txt;
  }


 ?>
</body>
</html>
