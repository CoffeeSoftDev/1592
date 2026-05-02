<?php
include_once("../../../modelo/SQL_PHP/_Finanzas.php"); // <---
$obj = new Finanzas; // <--
sleep(1);


/*=============================================
  Data
===============================================*/

$id          = $_POST['id'];
$opc         = $_POST['opc'];
$provedor    = $_POST['proveedor'];
$direccion   = $_POST['dir'];
$contacto    = $_POST['contacto'];
$telefono    = $_POST['telefono'];
$formas      = $_POST['Categoria'];
$RFC         = $_POST['rfc'];
$Formas      = $_POST['FormasPago'];
$indicador   = 0;


$txt = '<label class="text-success"><i class="fa fa-check-circle fa-1x"></i>Se ha agregado correctamente</label>';

/*=============================================*/




/* ===========================================
     MAIN
 ===========================================*/


switch ($opc) {
 case 1: // INSERT DATA
 $existe = $obj -> ExisteProveedor($provedor);

 if (count($existe)) {
  $txt         = '<label class="text-danger">Ya existe un registro con este proveedor ['.$provedor.']</label>';
 }else {
  $arreglo=array($provedor,$direccion,$contacto,$telefono,$formas,$RFC,$Formas);
  $ok = $obj -> insertProveedor($arreglo);
 }
 break;



 case 2: // UPDATE DATA
 $txt         = '<label class="text-success"><span class="fa fa-check-circle fa-1x"></span> Se ha actualizado correctamente</label>';
 $key=array($provedor,$direccion,$contacto,$telefono,$formas,$RFC,$Formas,$id);
 $ok = $obj -> updateProveedores($key);
 break;

}


// ===========================================
//     ENCODE JSON
// ===========================================
$encode = array(
 0=>$txt,
 1=>$indicador
);
echo json_encode($encode);
?>
