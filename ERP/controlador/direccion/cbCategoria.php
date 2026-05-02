<?php
session_start();

include_once("../../modelo/SQL_PHP/_DIRECCION.php");
$obj  = new direccion;
$opc = $_POST['opc'];

switch ($opc) {
 case 1:
 // CB ....................................

 $con1 =$obj->dataNivel(null);

 $cb1='';
 $cb1=$cb1.'
 <select class="form-control input-xs" id="txtNivel" >
 <option value="0">... </option> ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';

 // .................................................
 $encode = array(0=>$cb1);

 break;

 case 2:
 // CB ....................................

 $con1 =$obj->dataArea(null);

 $cb1='';
 $cb1=$cb1.'
 <select class="form-control input-xs" id="txtArea" >
 <option value="0">... </option> ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';

 // .................................................
 $encode = array(0=>$cb1);
 break;
 case 3:
 // CB ....................................

 $con1 =$obj->dataudn(null);

 $cb1='';
 $cb1=$cb1.'
 <select class="form-control input-xs" id="txtUDN" >
 <option value="0">... </option> ';

 foreach ($con1 as $cbNivel ) {
  $cb1=$cb1.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
 }
 $cb1=$cb1.'</select>';

 // .................................................
 $encode = array(0=>$cb1);
  break;
 break;
}

echo json_encode($encode);
?>
