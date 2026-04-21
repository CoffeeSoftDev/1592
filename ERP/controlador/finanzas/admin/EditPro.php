<?php
include_once("../../../modelo/SQL_PHP/_Finanzas.php"); // <---
$obj = new Finanzas; // <--

$id=$_POST['id'];
$txt='';
// ===========================================
//     FUNCIONES-PHP
// ===========================================
$key=array(null);
$ok        = $obj -> verProveedores($id);
$select    = $obj -> verFormasPago();
foreach ($ok as $key);

$provedor    = $key[1];
$direccion   = $key[2];
$contacto    = $key[3];
$telefono    = $key[4];
$formas      = $key[7];
$RFC         = $key[5];
$FormasPag   = $key[8];

$btnOk       = '  <button onclick="SavePro('.$key[0].')" type="button" class="btn btn-success"><span class="fa fa-check"></span></span>Guargar</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>

';

$cb='';
$cb=$cb.'<select class="form-control input-xs" required="" id="txtCategoria">
  <option value="0">Selecciona</option>';

foreach ($select as $keyx) {
 if ($formas==$keyx[0]) {
  $cb=$cb.'<option value="'.$keyx[0].'" selected>'.$keyx[1].'</option>';
 }else {
  $cb=$cb.'<option value="'.$keyx[0].'">'.$keyx[1].'</option>';
 }
}

$cb=$cb.'</select>';
// ===========================================
//     ENCODE JSON
// ===========================================

$encode = array(
 0=>$provedor,
 1=>$direccion,
 2=>$contacto,
 3=>$telefono,
 4=>$FormasPag,
 5=>$RFC,
 6=>$btnOk,
 7=>$cb

);
echo json_encode($encode);
?>
