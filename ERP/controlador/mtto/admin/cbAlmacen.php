<?php
session_start();
include_once("../../../modelo/SQL_PHP/_MTTO.php");
$obj     = new MTTO;

include_once("../../../modelo/SQL_PHP/_ALMACEN.php");
$almacen = new ALMACEN;

$opc = $_POST['opc'];


switch ($opc) {
/*-----------------------------------*/
/* Categoria de productos
/*-----------------------------------*/

 case 1:

 $con1 = $obj->dataCat(null);

 $cb1  = '';

 $cb1  = $cb1.'
 <select class="form-control input-xs" id="txtCategoria" onchange="ver_tabla(1); ver_tabla(2);" >
 <option value="0">Seleccionar Categoria </option> ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';


 $encode = array(0=>$cb1);

 break;

/*-----------------------------------*/
/*		Area de productos
/*-----------------------------------*/


 case 2:


 $con1 =$obj->data_AREA(null);

 $cb1='';
 $cb1=$cb1.'
 <select class="form-control input-xs" id="txtArea" onchange="ver_tabla(1); ver_tabla(2);">
 <option value="0">Seleccionar Area </option> ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';

 // .................................................
 $encode = array(0=>$cb1);
 break;



 case 3:

 $con1 =$obj->cbZona();

 $cb1='';
 $cb1=$cb1.'
 <select class="form-control input-xs" id="txtUDN" name="nameZona" onchange="ver_tabla(1)">
 <option value="0">Seleccionar una zona</option> ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';

 $encode = array(0=>$cb1);
 break;


 case 4:
 $idFamilia  = $_POST['Familia'];
 $array      = array($idFamilia);
 $con1       = $almacen->_CLASE($array);
 $cb1='';
 $cb1=$cb1.'
 <select class="form-control input-sm" id="txtClase" onchange=" CodigoEquipo();">
 <option value="0"> Selecciona una clase </option>
 ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';

 $encode = array(0=>$cb1);

 break;


 /*-----------------------------------*/
 /* Inventario productos
 /*-----------------------------------*/


 case 5:
 $con1       = $almacen->cbInventario();
 $cb1='';

 $cb1=$cb1.'
 <select class="form-control input-sm" id="txtZona" onchange="cbArea(6);">
 <option value="0"> Elige una zona </option>
 ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>

 ';

 $encode = array(0=>$cb1);

 break;

 case 6:

 $array      = array( $_POST['zona']);
 $con1       = $almacen->cbAreaInventario($array);
 $cb1        = '';

 $cb1=$cb1.'
 <select class="form-control input-sm" id="txtArea" onchange="">
 <option value="0"> Elige un area </option>
 ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';

 $encode = array(0=>$cb1);

 break;



}


echo json_encode($encode);
?>
