<?php
include_once("../../modelo/SQL_PHP/_METAS.php"); // <---
$obj = new METAS; // <--

$fi=$_POST['anio1'];
$ff=$_POST['anio2'];

$tb1  ='';
$tb2  ='';
$ct    = COMPARAR_TOTALES($fi,$ff);
$ctx   = CRECIMIENTO($fi,$ff);
$tb1  =$tb1.'<div class="col-xs-12 col-sm-12">'.$ct.'</div>';
$tb2  =$tb2.'<div class="col-xs-12 col-sm-12">'.$ctx.'</div>';

// ===========================================
//     ENCODE JSON
// ===========================================
$encode = array(
 0=>$tb1,1=>$tb2);

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


 function COMPARAR_TOTALES($fi,$ff){
  $tb1='';
  $tb1=$tb1.'
  <div class="scrolling outer">
  <div class=" inner">
  <div class="table-responsive">
  <table class="table table-bordered" id="size1">
  <thead>
  <tr class="bg-primary">
  <th id="col_1" class="text-primary">AÑO</th>
  <td id="col_2" class="bg-green">TOTAL</td>
  <td>ENERO</td>
  <td>FEBRERO</td>
  <td>MARZO</td>
  <td>ABRIL</td>
  <td>MAYO</td>
  <td>JUNIO</td>
  <td>JULIO</td>
  <td>AGOSTO</td>
  <td>SEPTIEMBRE</td>
  <td>OCTUBRE</td>
  <td>NOVIEMBRE</td>
  <td>DICIEMBRE</td>
  </tr>
  </thead>
  <tbody>
  ';

  for ($i=$fi; $i <= $ff ; $i++) {
   $obj    = new METAS; // <--
   $TOTAL_GRAL = $obj -> TOTAL_GENERAL($i,0,1);

   $tb1=$tb1.'
   <tr>
   <th id="col_1"> '.$i.'</th>
   <td id="col_2" class="bg-light-green" >'.evaluar($TOTAL_GRAL).'</td>';
   // ---- [ M E S E S ] -------------------|
   for ($x=1; $x <= 12 ; $x++) {
    $mes = $obj -> TOTAL_GENERAL($i,$x,2);
    $tb1=$tb1.'<td>'.evaluar($mes).'</td>';
   }
   // --------------------------------------|
   $tb1=$tb1.' </tr>';

  }

  $tb1=$tb1.'</tbody></table></div></div></div>';
  return $tb1;
 }


 function CRECIMIENTO($fi,$ff){
  $tb1='';
  $tb1=$tb1.'
  <div class="scrolling outer">
  <div class=" inner">
  <div class="table-responsive">
  <table class="table table-bordered" id="size1">
  <thead>
  <tr class="bg-primary">
  <th id="col_1" class="text-primary">AÑO</th>
  <td id="col_2" class="bg-green">TOTAL</td>
  <td>ENERO</td>
  <td>FEBRERO</td>
  <td>MARZO</td>
  <td>ABRIL</td>
  <td>MAYO</td>
  <td>JUNIO</td>
  <td>JULIO</td>
  <td>AGOSTO</td>
  <td>SEPTIEMBRE</td>
  <td>OCTUBRE</td>
  <td>NOVIEMBRE</td>
  <td>DICIEMBRE</td>
  </tr>
  </thead>
  <tbody>
  ';
  $contador =0;
  $año1 = 0;
  $año2 = 0;
  $valor1=0;
  $valor2=0;
  // IMPRIMIR AÑOS -----------------|
  for ($i=$fi; $i <= $ff ; $i++) {
   $contador +=1;
   $obj    = new METAS; // <--
   $TOTAL_GRAL = $obj -> TOTAL_GENERAL($i,0,1);

   if($contador==2){
    $año2    = $i;
    $valor2  = $TOTAL_GRAL;
    $vs      = VS($valor1,$valor2);
    $tb1=$tb1.'
    <tr>
    <th id="col_1"> '.$año1.' vs '.$año2.'</th>
    <td id="col_2" class="bg-light-green" > '.$vs.' </td>';
    
    // ---- [ M E S E S ] -------------------|
    for ($x=1; $x <= 12 ; $x++) {
     $mes1 = $obj -> TOTAL_GENERAL($año1,$x,2);
     $mes2 = $obj -> TOTAL_GENERAL($año2,$x,2);
     // ---
     $vsmes = VS($mes1,$mes2);
     $tb1=$tb1.'<td>'.$vsmes.'</td>';
    }
    // --------------------------------------|
    $tb1=$tb1.' </tr>';

    $contador =1;
    $año1     = $i;
    $valor1   = $TOTAL_GRAL;

   }else if($contador==1){
    $año1    = $i;
    $valor1  = $TOTAL_GRAL;
   }


  }


  $tb1=$tb1.'
  </tbody>
  </table>
  </div></div></div>
  ';

  return $tb1;
 }

 ?>
