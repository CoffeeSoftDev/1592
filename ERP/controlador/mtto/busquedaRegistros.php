<?php
session_start();

include_once("../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

$opc = $_POST['opc'];
$sql="";


switch ($opc) {

 case 1: // Area
 $sql = $obj -> SELECT_area(null);
 break;

 case 2: // Equipo
 $sql = $obj -> SELECT_NOMBRE(null);
 break;

 case 3: // Codigo
 $sql = $obj -> SELECT_CODIGO(null);
 break;

 case 4: // Articulo
 $sql = $obj -> SELECT_ARTICULO(null);
 break;

 case 5: // Marca
 $sql = $obj -> SELECT_MARCA(null);
 break;

 case 6:
 $sql = $obj -> SELECT_PROVEEDOR(null);
 break;


}



$nombres = array();
foreach ($sql as $key => $row) {
 $nombres[$key] = $row[0];
}
echo json_encode($nombres);




?>
