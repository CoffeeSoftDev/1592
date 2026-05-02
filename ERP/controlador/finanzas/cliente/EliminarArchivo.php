<?php
sleep(2);
include_once("../../../modelo/SQL_PHP/_Finanzas_Compras.php");
$obj     = new Compras_Fin;

$archivo =   $_POST['f'];
$array   =   array($archivo);
$r       =   $obj     ->  EliminarArchivo($array);

unlink("../../../".$r);
// --

echo $archivo;

?>
