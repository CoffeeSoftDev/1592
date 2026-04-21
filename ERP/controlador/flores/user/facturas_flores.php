<?php 


$encode = ''; 

switch ($_POST['opc']) {

case 1: // Guardar Factura
$fol     = $_POST['fol'];
$cliente = $_POST['cliente'];
$fecha   = $_POST['fecha'];

$encode = array(0=>$fol);


break;

case 2:

break;
}

echo json_encode($encode);




?>