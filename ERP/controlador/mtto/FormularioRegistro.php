<?php
session_start();
include_once("../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

$c1       = $obj->dataCat();
$c2       = $obj->cbZona();


/*-----------------------------------*/
/*		Formulario de registros nuevos
/*-----------------------------------*/

$txtModal = '';

$txtModal=$txtModal.'
<form id="defaultForm" class="form-horizontal" onsubmit="return false" >

<ul class="nav nav-tabs">
<li class="active">
<a class="text-warning"
data-toggle="tab" href="#tab1x" onclick=""> <strong>General</strong></a>
</li>

<li>
<a data-toggle="tab" href="#tab2x" onclick=""><strong>Costo & Detalles </strong></a>
</li>
</ul>


<div class="tab-content">

<div class="form-group">
<label  class="col-xs-12 col-sm-4">Código del Equipo: </label>
<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
<input style="font-size:1.5em;" type="text"
class="form-control input-xs"
id="Codigo" onkeydown="Search_Code()"
onBlur   = "CodigoEquipo()" disabled>
</div>
</div><!-- ./-->


<div class="form-group">
<label class="col-xs-12 col-sm-4">Nombre del Equipo:</label>

<div id="art_Group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="text"
class="form-control input-xs"
id="Equipo"
name="articulo"
style="text-transform:uppercase;"
onBlur    = "CodigoEquipo()"
onkeydown = "Search_Name()">

</div>
</div><!-- ./-->

<hr>

<div id="tab1x" class="tab-pane fade in active">';
$gral      = form_gral($c1,$c2);
$txtModal  = $txtModal.$gral.'</div><!-- ./tab1-->


<div id="tab2x" class="tab-pane fade">';

$costos      = form_costos();
$txtModal    = $txtModal.$costos.'</div>

</div> <!-- ./tab-content -->
</form><!-- ./Form-Horizontal -->
';

/*-----------------------------------*/
/* Funciones & Complementos
/*-----------------------------------*/
function form_gral($c1,$c2){
 $txtModal = '';
 $txtModal=$txtModal.'
 <div class="form-group">

 <label class="col-xs-12 col-sm-4">Categoria: </label>
 <div class="col-xs-12 col-sm-8">
 <select class="form-control input-xs" name="tipoEmpresa" id="A" onchange="habilitarPieza()">

 ';
 foreach ($c1 as $key) {
  $txtModal=$txtModal.'<option value="'.$key[0].'"> '.$key[1].'</option> ';
 }

 $txtModal=$txtModal.'
 </select>
 </div>

 </div><!-- ./-->

 <div class="form-group">
 <label class="col-xs-12 col-sm-4">Zona :</label>
 <div class="col-xs-12 col-sm-8">
 <select class="form-control input-xs" onchange="CodigoEquipo()" id="Empresa">';
 foreach ($c2 as $key2) {
  $txtModal=$txtModal.
  '<option value="'.$key2[0].'"> '.$key2[1].'</option> ' ;

 }

 $txtModal=$txtModal.'
 </select>
 </div>
 </div><!-- ./-->




 <div class="form-group">
 <label  class="col-xs-12 col-sm-4">Area:</label>
 <div id="area_Group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

 <input type="text"
 class="form-control input-xs"
 id="Area"
 name="area"
 style="text-transform:uppercase;"
 onkeydown="Busqueda() ">

 </div>
 </div><!-- ./-->

 <div class="form-group">
 <label  class="col-xs-12 col-sm-4">Proveedor:</label>
 <div id="proveedor_group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

 <input type="text"
 class="form-control input-xs"
 id="Proveedor"
 name="Proveedor"
 style="text-transform:uppercase;"
 >

 </div>
 </div><!-- ./-->

 <div class="form-group">
 <label  class="col-xs-12 col-sm-4">Marca:</label>
 <div id="proveedor_group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

 <input type="text"
 class="form-control input-xs"
 id="Marca"
 name="Marca"
 style="text-transform:uppercase;"
 >

 </div>
 </div><!-- ./-->


 <div class="form-group">
 <div class="col-xs-12 col-sm-7">


 <input type="file" class="form-control btn btn-default" id="imgInp" onchange="readURL(this)">
 </div>

 <div class="col-xs-12 col-sm-5 text-left">
 <img id="blah" src="recursos/img/box.png"  alt="" width="180px"/>
 </div>
 </div>


 ';

 return $txtModal;
}

function form_costos(){
 $dates    = date("Y") ."-". date("m") . "-" . date("d");

 $txtModal = '';
 $txtModal=$txtModal.'

 <div class="form-group">
 <div class="col-xs-12 col-sm-4">
 <label>Cantidad:</label>
 </div>

 <div id="min_Group" class="col-xs-12 col-sm-8">
 <input type="number" class="form-control input-xs" name="minimo" min="1" value="1" id="txtCantidad" disabled onkeyup="Moneda()">
 </div>


 </div><!-- ./ -->



 <div class="form-group">
 <label class="col-xs-12 col-sm-4">Costo: </label>

 <div id="cEnt" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

 <input type="number"
 class="form-control input-xs"
 id="txtCosto"
 step  = "any"
 name="costoEnt"
 onkeyup="Moneda()"
 >

 <div class=" bg-default" id="Res_Costo"></div>
 </div>
 </div><!-- ./-->


 <div class="form-group">
 <label class="col-xs-12 col-sm-4">Detalles: </label>

 <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

 <textarea
 class="form-control "
 id="txtDetalles"
 ></textarea>

 <div class=" bg-default" id="Res_Detalles"></div>
 </div>
 </div><!-- ./-->

 <div class="form-group">
 <label class="col-xs-12 col-sm-4">Estado: </label>

 <div class="col-sm-8 col-xs-12">
 <div class="btn-group" data-toggle="buttons">
 <button class="active btn btn-default btn-xss " autofocus="true" >
 <input type="radio" value="1" name="rdExtra"><strong>
 <!-- <span class="fa fa-pagelines"></span>  -->
 BUENO
 </strong>
 </button>

 <button class="btn btn-default btn-xss " >
 <input type="radio" value="2" name="rdExtra"><strong>
 <!-- <span class="fa fa-pagelines"></span> --> REGULAR  </strong>
 </button>

 <button class="btn btn-default btn-xss btn-outline" >
 <input type="radio" value="3" name="rdExtra"><strong>
 <!-- <span class="fa fa-pagelines"></span> --> MALO  </strong>
 </button>

 </div>

 </div>
 </div><!-- -->



 <div class="form-group">
 <label class="col-xs-12 col-sm-4">Utilidad: </label>

 <div class="col-xs-12 col-sm-8" style="position:relative;">

 <input type="text" class="form-control input-xs" id="date" data-format="YYYY-MM-DD" data-template="D MMM YYYY" name="date" value="'.$dates.'" onchange="TiempoVida()" >
 <p class="" style="font-size:1.4rem; font-weight=bold;" ><strong> <span class="fa fa-warning text-warning"></span> Debes indicar la vida útil del producto </strong></p>

 </div>
 </div><!-- -->





 <!-- -->
 <div class="form-group" id="Resultado"></div>

 <!-- Area boton-->
 <div class="form-group">
 <div class="col-xs-12 ">

 <button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-5" data-dismiss="modal" onclick="ver_tabla(1);">Salir</button>

 <button type="submit" class="btn btn-success col-xs-12 col-sm-3 col-sm-offset-1" onclick="Nuevo_Codigo(1,0);">Guardar</button>

 </div>
 </div>
 ';
 return $txtModal ;
}
/*------- JSON ENCODE---------------*/

$encode = array(0=>$txtModal);
echo json_encode($encode);

?>
