<?php
session_start();
include_once("../../../modelo/SQL_PHP/_MTTO_REQUISICION.php");
$obj    = new REQUISICION;

/*==========================================
*		MAIN
=============================================*/
$fi   = $_POST['fi'];
$ff   = $_POST['ff'];

$zona = $_POST['zona'];
$area = $_POST['area'];

$array = array($zona,$area);

//
$folio   = $obj -> FechaActiva($array,$fi,$ff);

$txt     = '';

$txt     = $txt.'
<table class="table table-bordered">
<thead>
<tr class=" bg-default">
<th class="text-center" rowspan="2"> Abarrotes </th>
<th class="text-center" rowspan="2"> Stock min</th>
';

foreach ($folio as $a) {
 $txt     = $txt.'
 <th class="text-center" colspan="2" > '.$a[0].'</th>
 ';
}



$txt  = $txt.'
<th class="text-center" rowspan="2"> Costo </th>
<th class="text-center" rowspan="2"> Total </th>
<th class="text-center" rowspan="2"> Sugerencia </th>
</tr><tr class="bg-default">';

foreach ($folio as $a) {
 $txt     = $txt.'
 <th class="text-center"> INV. INICIAL</th>
 <th class="text-center"> INV. FINAL</th>
 ';
}

$txt     = $txt.'</tr></thead><tbody>';

$ok   = $obj -> NombreProducto($array);

foreach ($ok as $key) {
 $txt  = $txt.'
 <tr >
 <td > '.$key[0].' </td>
 <td class="text-right"> '.$key[1].' </td>';


 foreach ($folio as $a) {

  $L1        = 0 ;
  $L2        = 0 ;

  $arrayZ    = array($a[2],$key[3]);
  $Lista     = $obj -> ProductoList($arrayZ);
  foreach ($Lista as $keyX) {
   $L1        = $keyX[0];
   $L2        = $keyX[1];
  }


  $txt     = $txt.'
  <td class="text-right"> '.$L1.' </td>
  <td class="text-right"> '.$L2.'</td>
  ';



 }


 $txt  = $txt.'
 <td class="text-right"> '.evaluar($key[2]).' </td>
 <td class="text-right"> '.$key[4].' </td>
 <td class="text-right">  </td>
 </tr>
 ';
}

$txt  = $txt.'</tbody></table>';




/* ===========================================
*     ENCODE JSON
// ===========================================*/

$encode = array(
 0=>$txt);
 echo json_encode($encode);

 /*==========================================
 *		FUNCIONES / FORMULAS
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
