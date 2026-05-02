<?php
session_start();
include_once("../../modelo/SQL_PHP/_METAS.php");
$obj = new METAS;

$opc      = $_POST['opc'];

$cbFiltro = '';
$option   = '';
$_id      = '';
$_event   = '';
$_msj     = '';

switch ($opc) {

 case 1: // CB1
 $cbf  = $obj-> AÑO_VIGENTE();
 $_id  = 'txtAnio1';
 $_msj = 'Elige un año';
  $actual  = date("Y")-1;
 foreach ($cbf as $cbi ) {
  // if ($actual==$cbi[0]) {
  //  $option=$option.'<option value="'.$cbi[0].' " selected>'.$cbi[0].'</option>';
  // }else {
   $option=$option.'<option value="'.$cbi[0].'">'.$cbi[0].'</option>';
  // }
 }
 break;

 case 2: // CB2
 $cbf     = $obj-> AÑO_VIGENTE();
 $_id     = 'txtAnio2';
 $_msj    = 'Elige un año';
 $actual  = date("Y");

 foreach ($cbf as $cbi ) {
  // if ($actual==$cbi[0]) {
  //  $option=$option.'<option value="'.$cbi[0].' " selected>'.$cbi[0].'</option>';
  // }else {
   $option=$option.'<option value="'.$cbi[0].'">'.$cbi[0].'</option>';
  // }
 }
 break;


}


/* ComboBox */

$cbFiltro=$cbFiltro.'
<select class="form-control input-sm" id="'.$_id.'" onchange="'.$_event.'">
';

$cbFiltro=$cbFiltro.$option;

$cbFiltro=$cbFiltro.'</select>';
// ===================================================
//  JSON ENCODE
// ===================================================

$encode = array(0=>$cbFiltro);
echo json_encode($encode);
?>
