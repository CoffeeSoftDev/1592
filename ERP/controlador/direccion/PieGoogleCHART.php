<?php
include_once("../../modelo/SQL_PHP/_METAS.php"); // <---
$obj= new METAS;

$ff = $_POST['ff'];
$fi = $_POST['fi'];
$opc =$_POST['opc'];
// ==================+

switch($opc) {

case 1: echo venta_pie($fi,$ff,$opc); break;

case 2: echo  venta_pie($fi,$ff,$opc); break;
}

function venta_pie($fi,$ff,$opc) {
$obj= new METAS;
$categoria = $obj ->VENTAS_x_AÑO($fi,$ff,$opc);
foreach($categoria as $row){
 $output[] = array(
  'month'   => $row[0],
  'profit'  => floatval($row[1])
 );
}
echo json_encode($output);

}


//===============
function bar($fi,$ff) {
$obj= new METAS;

$ok  = $obj -> GRAFICAxAREA($data[0],$i);

foreach($ok as $row){
 $output[] = array(
  'month'   => $row[0],
  'profit'  => floatval($row[1])
 );
}
echo json_encode($output);

}

?>
