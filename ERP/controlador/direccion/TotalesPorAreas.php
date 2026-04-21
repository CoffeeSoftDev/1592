<?php
include_once("../../modelo/SQL_PHP/_METAS.php"); // <---
$obj = new METAS; // <--

$fi=$_POST['anio1'];
$ff=$_POST['anio2'];
$tb1  ='';

$ct    = TOTALES_AREAS($fi,$ff);
$tb1  =$tb1.'<div class="col-xs-12 col-sm-12">'.$ct.'</div>';
// ===========================================
//     ENCODE JSON
// ===========================================
$encode = array(
 0=>$tb1);

 echo json_encode($encode);
 /*==========================================
 *		FUNCIONES / FORMULAS
 =============================================*/

 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="") {
   $res = '-';
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }

  return $res;

 }

 function evaluar_2($val){
  $res = '';
  if ($val==0 || $val=="") {
   $res = '#';
  }else {
   $res =''.number_format($val, 2, '.', ',').' %';
  }

  return $res;
 }

 function VS($v1,$v2){
  $VS =0;
  $txt="";
  if ($v1==0) {
   $VS = '#';
   $txt ='<strong><span class="text-danger">'.$VS.'</span></strong>';
  }else {
   $res =$v1-$v2;
   $formula = ($res/$v1)*100;
   $VS =evaluar_2($formula);

   if ($formula>0) {
    $txt ='<strong><span class="text-success">'.$VS.'</span></strong>';
   }else {
    $txt ='<strong><span class="text-danger">'.$VS.'</span></strong>';
   }


  }
  return $txt;
 }



 function TOTALES_AREAS($fi,$ff){
  $obj    = new METAS; // <--
  $tb1='';
  $CAT = $obj -> VER_CATEGORIAS();
  // ----
  $tb1=$tb1.'

  <div class="scrolling outer">
  <div class=" inner">
  <div class="table-responsive">
  <table class="table table-bordered" id="size1">
  <thead>
  <tr class="bg-primary" style="font-size:10px;">
  <th id="col_1" class="text-primary">AÑO</th>
  <td id="col_2" class="bg-green">TOTAL</td>';

  foreach ($CAT as $key) { // Categoria
   $tb1=$tb1.'<td id="td_col1"><strong>'.$key[1].'</strong></td>';
  }

  $tb1=$tb1.'</tr></thead><tbody>
  ';

  // ----------------------------
  for ($j=$fi; $j <= $ff; $j++) {
   $CAT = $obj -> VER_CATEGORIAS();
   $TOTAL_GENERAL = $obj -> TOTAL_GENERAL_SNIVA($j);
   $tb1=$tb1.'
   <tr>
   <th id="col_1" class="bg-light-blue"> '.$j.'</th>';
   $tb1=$tb1.'<td id="td_col2">'.evaluar($TOTAL_GENERAL).'</td>';
   foreach ($CAT as $keyx) { // Categoria
    $INGRESOS = $obj -> VER_INGRESOS($keyx[0],$j,12,1);
    $tb1=$tb1.'<td id="td_col1">'.evaluar($INGRESOS).'</td>';
   }
   $tb1=$tb1.' </tr>';
  }
  $tb1=$tb1.'</tbody></table></div></div></div>';

  /*==========================================
  *		AÑO VS AÑO
  =============================================*/
  $tb1=$tb1.'
  <div class="scrolling outer">
  <div class=" inner">
  <div class="table-responsive">
  <table class="table table-bordered" id="size1">
  <thead>
  <tr class="bg-primary" style="font-size:10px;">
  <th id="col_1" class="text-primary">AÑO</th>
  <td id="col_2" class="bg-green">TOTAL</td>';

  foreach ($CAT as $key) { // Categoria
   $tb1=$tb1.'<td id="td_col1"><strong>'.$key[1].'</strong></td>';
  }

  $tb1=$tb1.'</tr></thead><tbody>
  ';
  $contador =0;
  $año1 = 0;
  $año2 = 0;
  $valor1=0;
  $valor2=0;

  // IMPRIMIR AÑOS ---------------------------------
  for ($i=$fi; $i <= $ff; $i++) {
   $contador +=1;
   $obj    = new METAS; // <--
   $TOTAL_GRAL = $obj -> TOTAL_GENERAL_SNIVA($i);

   switch ($contador) {   // SEPARAR AÑOS
    case 2:
    $año2    = $i;
    $valor2  = $TOTAL_GRAL;
    $VS =VS($valor1,$valor2);
    // ---------------
    $tb1=$tb1.'
    <tr>
    <th id="col_1" class="bg-light-blue"> '.$año1.' vs '.$año2.'</th>
    <td id="col_2" class="bg-light-green" > '.$VS.' </td>';
    // ---- [ CATEGORIAS ] -------------------|
    foreach ($CAT as $key) { // Categoria

     $mes1 = $obj -> VER_INGRESOS($key[0],$año1,12,1);
     $mes2 = $obj -> VER_INGRESOS($key[0],$año2,12,1);
     $vsMES =VS($mes1,$mes2);
     $tb1=$tb1.'<td id="td_col1">'.$vsMES.'</td>';

    }
    // --------------------------------------|
    $tb1=$tb1.' </tr>';
    $contador =1;
    $año1    = $i;
    $valor1  = $TOTAL_GRAL;
    break;

    case 1:
    $año1    = $i;
    $valor1  = $TOTAL_GRAL;
    break;


   }

  }

  $tb1=$tb1.'</tbody></table></div></div></div>';
  /*==========================================
  *		TOTAL AÑO
  =============================================*/
  $totalAños = ($ff-$fi)+1;
  $tb1=$tb1.'

  <div class="scrolling outer">
  <div class=" inner">
  <div class="table-responsive">
  <table class="table table-bordered" id="size1">
  <thead>
  <tr class="bg-primary" style="font-size:10px;">
  <th id="col_1" class="text-primary">AÑO</th>
  <td id="col_2" class="bg-green">TOTAL</td>';
  $total = $obj -> VER_INGRESOS_RANGO_FECHA($fi,$ff,1);
  foreach ($total as $key) { // Categoria
   $tb1=$tb1.'<td id="td_col1"><strong>'.$key[0].'</strong></td>';
  }

  $tb1=$tb1.'</tr></thead><tbody>';



  $tb1=$tb1.'<th id="td_col1">'.$totalAños.' AÑOS </th>';
  $tb1=$tb1.'<td id="td_col2"></td>';
  foreach ($total as $key) { // Categoria

   $tb1=$tb1.'<td id="td_col1"><strong>'.evaluar($key[1]).'</strong></td>';
  }

  $tb1=$tb1.'</tbody></table></div></div></div><br>';

  return $tb1;
 }

 ?>
