<?php
session_start();
include_once("../../../modelo/SQL_PHP/_Finanzas.php"); // <---
$obj = new Finanzas; // <--

$opc = $_POST['opc'];
// ===================================================
//  Funciones
// ===================================================
$cbFiltro = '';
$option   = '';
$_id      = '';
$_event   = '';

switch ($opc) {
 case 1: // De Provedores
 $cbf  = $obj-> verFormasPago();
 $_id  = '_txtCategoria';
 foreach ($cbf as $cbi ) {
  $option=$option.'<option value="'.$cbi[0].'">'.$cbi[1].'</option>';
 }

 break;


}


/* ComboBox */

$cbFiltro=$cbFiltro.'
<select class="form-control input-sm" id="'.$_id.'" onchange="'.$_event.'">
<option value="0"> ... </option>
';

$cbFiltro=$cbFiltro.$option;

$cbFiltro=$cbFiltro.'</select>';
// ===================================================
//  JSON ENCODE
// ===================================================

$encode = array(0=>$cbFiltro);
echo json_encode($encode);
?>
