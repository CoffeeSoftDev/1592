<?php

include_once("../../../modelo/UI_FORM.php");
$fx    = new FORM_UI;

include_once("../../../modelo/UI_TABLE.php");
$table = new Table_UI;

include_once("../../../modelo/SQL_PHP/_FLORES.php");
$obj   = new _PRODUCTOS;

$udn   = 1; // cambiar por sessión !!!!

switch ($_POST['opc']) {

 case 0: // Formulario de productos
 $frm       = '';
 $sql       = $obj ->_Categoria(array($udn));
 $Producto  = $fx -> input_text(array('Producto','Producto',''),'','');
 $Costo     = $fx -> input_number(array('Precio Costo ','Costo',''),'','');

 $Venta     = $fx -> input_number(array('Precio Venta ','Venta',''),'','');
 $Mayoreo   = $fx -> input_number(array('Precio Mayoreo ','Mayoreo',''),'','');

 $Stock_ini = $fx -> input_number(array('Stock Inicial ','StockIni',''),'','');
 $Stock_min = $fx -> input_number(array('Stock Minimo ','StockMin',''),'','');

 $Categoria = $fx -> input_select(array('Categoria ','Categoria',''),$sql,'','VerSubs()');
 $SubCat    = $fx -> input_select(array('Clase ','Sub',''),array(null),'disabled','VerSub');


 $frm = $frm.'
 <form id="Form" class="form-horizontal" onsubmit="return false" >
 '.$Producto.'
 '.$Costo.'
 '.$Venta.'
 <hr>
 '.$Categoria.'
 '.$SubCat.'
 <hr>
 '.$Stock_ini.'
 '.$Stock_min.'

 ';

 $frm = $frm.'
 <div class="form-group">
 <div class="col-xs-12 col-sm-12 text-right">

 <button type="submit" class="btn btn-info " onclick="Guardar()"><span class="icon-floppy"></span> Guardar </button>
 </div>
 </div>

 </form>';




 /*--------- JSON ENCODE -----------*/
 $encode	=	array(0=>$frm);
 echo	json_encode($encode);

 break;

 case 1: // select Grupo
 $cbFiltro     = '';
 $cbf          = $obj ->_Categoria(array($udn));

 $cbFiltro     = $cbFiltro.'
 <select class = "form-control input-sm" id="" onchange="">
 <option value = "0">...</option>
 ';

 foreach ($cbf as $cbi ) {
  $cbFiltro   = $cbFiltro.'<option value="'.$cbi[0].'">'.$cbi[1].'</option>';
 }

 $cbFiltro     = $cbFiltro.'</select>';
 /*--------- JSON ENCODE -----------*/
 $encode	=	array(0=>$cbFiltro);
 echo	json_encode($encode);
 break;


 case 2: // TABLE
 switch ($_POST['tipo']) {
  case 1:

  $title = array('Producto','Costo','Venta','Clase','Inicial','minimo','<span class="fa fa-gear"></span>');
  $tb= $table->VIEW_DATA_TABLE($title,'tbProductos');
  echo	json_encode(array($tb));
  break;

  case 2:
  $productos = $obj ->VerProductos(array($udn));
  $title     = array('a','b','c','d','e','f');
  $tb        = $table-> DATA_TABLE($title,$productos);

  echo $tb;
  break;


 }

 break;


 case 3: // select Grupo
 $cbFiltro     = '';
 $cbf          = $obj ->_SubCategoria(array($_POST['grupo']));

 $cbFiltro     = $cbFiltro.'
 <select class = "form-control input-sm" id="txtClase" onchange="">
 <option value = "0">Seleccionar clase</option>
 ';

 foreach ($cbf as $cbi ) {
  $cbFiltro   = $cbFiltro.'<option value="'.$cbi[0].'">'.$cbi[1].'</option>';
 }

 $cbFiltro     = $cbFiltro.'</select>';
 /*--------- JSON ENCODE -----------*/
 $encode	=	array(0=>$cbFiltro);
 echo	json_encode($encode);
 break;
 
 case 4: // Lista de productos
  $tb = '';

  $tb = '<div class="table-responsive">';
  $tb .= '<table id="viewFolios" class="table table-bordered  table-condensed table-hover pd-10"  style="width:100%; font-size:.78em;">';

  /*----------THEAD------------*/
  $tb .= '<thead><tr>';


  $encode	=	array(0=>$tb);
  echo	json_encode($encode);
 break;

}



?>
