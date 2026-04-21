<?php

$mod = $_POST['opc'];


$Modulo  = _MODULO($mod);

/*==========================================
*		ENCODE JSON
=============================================*/


$encode = array(
 0=>$Modulo);

 echo json_encode($encode);



	/*===========================================
	*									FUNCIONES PHP
	=============================================*/
function _MODULO($mod){
 $txt='';

 switch ($mod) {
  case 1: // tabla tesoreria
  $txt=$txt.'
  <table id="tb1" class="display cell-border compact nowrap" style="width:100%">
  <thead>';
  $txt=$txt.'
  <tr>
  <th> Codigo </th>
  <th> Zona </th>
  <th> Familia </th>
  <th> Nombre </th>
  <th> Costo Ent  </th>
  <th> Costo Sal  </th>
  <th> Stock min</th>
  <th> Disponible</th>
  <th> Fecha</th>
  ';
  break;

  case 2: // tabla maria barbara rocha
  $txt=$txt.'
  <table id="tb2" class="display cell-border compact nowrap" style="width:100%">
  <thead>';
  $txt=$txt.'
  <th>Zona</th>
  <th>Codigo</th>
  <th>Nombre</th>
  <th>Clase</th>
  <th>Familia</th>
  <th>Cantidad</th>
  <th>Costo</th>
  <th>Total</th>
  <th>Precio Venta</th>
  <th>Descripción</th>
  ';
  break;

  case 3: // table ticket
  $txt=$txt.'
  <table id="tb3" class="display cell-border compact nowrap" style="width:100%">
  <thead>';
  $txt=$txt.'
  <th>Zona</th>
  <th>Codigo</th>
  <th>Nombre</th>
  <th>Clase</th>
  <th>Familia</th>
  <th>Cantidad</th>
  <th>Costo</th>
  <th>Total</th>
  <th>Precio Venta</th>
  <th>Descripción</th>
  ';
  break;

  case 4: // table ticket
  $txt=$txt.'
  <table id="tb4" class="display cell-border compact nowrap" style="width:100%">
  <thead>';
  $txt=$txt.'
  <th>Zona</th>
  <th>Codigo</th>
  <th>Nombre</th>
  <th>Clase</th>
  <th>Familia</th>
  <th>Cantidad</th>
  <th>Costo</th>
  <th>Total</th>
  <th>Precio Venta</th>
  <th>Descripción</th>
  ';
  break;

	/*===========================================
	*									VISTA MATERIALES
	=============================================*/
 case 5: // tabla costos
 $txt=$txt.'
 <table id="tb5" class="display cell-border compact nowrap" style="width:100%">
 <thead>';
 $txt=$txt.'
 <tr>
 <th>Codigo</th>
 <th>Nombre</th>
 <th>Estado</th>
 <th>Area</th>

 <th>cantidad</th>
 <th>Costo</th>
 <th>Total</th>

 <th>Utilidad</th>
 <th>Ingreso</th>
 <th>Detalles</th>';
 break;

 case 6: // tabla costos
 $txt=$txt.'
 <table id="tb6" class="display cell-border compact nowrap" style="width:100%">
 <thead>';
 $txt=$txt.'
 <tr>
 <th>Codigo</th>
 <th>Area</th>
 <th>Nombre</th>

 <th>Cantidad</th>
 <th>Costo</th>
 <th>Total</th>
 <th>Descripción </th>

 ';
 break;
 case 7: // tabla bajas
 $txt=$txt.'
 <table id="tb7" class="display cell-border compact nowrap" style="width:100%">
 <thead>';
 $txt=$txt.'
 <tr>
 <th>Codigo</th>
 <th>Nombre</th>
 <th>Estado</th>
 <th>Categoria</th>
 <th>Area</th>
 <th>Cantidad</th>
 <th>Costo</th>
 <th>Total</th>

 <th>Fecha de baja</th>
 <th>Baja</th>
 <th>Observacion</th>

 <th>Motivo de baja </th>

 ';
 break;


 }



 $txt=$txt.'</tr></thead></table>';
 return $txt;
}



 ?>
