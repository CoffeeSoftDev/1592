<?php


include_once("../../../modelo/SQL_PHP/_FLORES.php");
$obj       = new _PRODUCTOS;
// $udn    = 1; // cambiar por sessión !!!!


switch ($_POST['opc']) {

 case 0: // Agregar nuevo Producto
 $a = $_POST['Producto'];
 $b = $_POST['Costo'];
 $c = $_POST['Venta'];
 $d = $_POST['Clase'];
 $e = $_POST['StockIni'];
 $f = $_POST['StockMin'];


 $array = array($a,$b,$c,$d,$e,$f);
 $title = array(
  'NombreProducto',
  'Costo',
  'Venta',
  'id_subcategoria',
  'Stock_Inicial',
  'Stock_Minimo');

  $ok    = $obj ->SAVE_FORM($array,$title,'hgpqgijw_ventas.venta_productos');

echo 'OK';
  break;


 }
 ?>
