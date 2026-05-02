<?php
session_start();
include_once("../../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

$idObject = $_POST['id'];

$c1       = $obj->dataCat();
$c2       = $obj->dataUDN();
$almacen       = $obj->Show_DATA_SINGLE($idObject);

foreach ($almacen as $key3) ;

// ----
$txtModal='';

$txtModal=$txtModal.'
<div class="form-horizontal" >
<div class="form-group">

<label class="col-xs-12 col-sm-4">Categoria: </label>
<div class="col-xs-12 col-sm-8">
<select class="form-control input-xs" name="tipoEmpresa" id="A">

';
foreach ($c1 as $key) {
$txtModal=$txtModal.'<option value="'.$key[0].'"> '.$key[1].'</option> ';
}

$txtModal=$txtModal.'
</select>
</div>
</div><!-- ./-->

<div class="form-group">
<label class="col-xs-12 col-sm-4">Empresa:</label>
<div class="col-xs-12 col-sm-8">
<select class="form-control input-xs" name="tipoContrato" id="Empresa">';
foreach ($c2 as $key2) {
$txtModal=$txtModal.
'<option value="'.$key2[0].'"> '.$key2[1].'</option> ' ;

}

$txtModal=$txtModal.'
</select>
</div>
</div><!-- ./-->


<div class="form-group">
<label class="col-xs-12 col-sm-4">Nombre del Equipo:</label>

<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="text"
class="form-control input-xs"
id="Equipo"
value="'.$key3[0].'"
onkeydown="Search_Name()"
>

<ul id="Equipo_list" class="autocomplete"></ul>

<div class=" bg-default" id="Res_Equipo"></div>
</div>
</div><!-- ./-->

<div class="form-group">
<label  class="col-xs-12 col-sm-4">Area:</label>
<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="text"
class="form-control input-xs"
id="Area"
onkeydown="Busqueda()">

<ul id="Area_list" class="autocomplete"></ul>
<div class="bg-default" id="Res_Area"></div>
</div>
</div><!-- ./-->

<div class="form-group">
<label  class="col-xs-12 col-sm-4">Código del Equipo: <a class="text-danger" onclick="show()">[Ver]</a>

</label>

<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="hidden" id="hidden" value="">

<input type="text"
class="form-control input-xs"
id="Codigo"
onkeydown="Search_Code()"
>


<ul id="Codigo_list" class="autocomplete"></ul>

<div class="bg-default" id="Res_Codigo"></div>

<div class="" id="Info" style="display:none;" >
<br>
<pre>Criterio para codificar<h5><strong>BA-01-TA-01</strong></h5><h6>
[BA] 2 digitos que indican la UDN donde se encuentra.
[01] 2 digitos que indican el número del área.
[TA] 2 letras que identifican al equipo.
[01] 2 digitos que indican cuantos equipos similares existen
en la UDN (en caso de ser equipo unico
esta indicado como 00)</h6></pre>
</div>

</div>
</div><!-- ./-->

<div class="form-group">
 <div class="col-xs-12 col-sm-4">
  <label>Cantidad:</label>
 </div>

 <div class="col-xs-12 col-sm-8">
  <input type="text" class="form-control input-xs" name="" value="1" id="txtCantidad" disabled onkeyup="Numero()">
 </div>


</div><!-- ./ -->

<div class="form-group" id="Resultado"></div>

<div class="form-group">
 <div class="col-xs-12 ">
  <button type="button" class="btn btn-success col-xs-12 col-sm-3 col-sm-offset-5" onclick="Nuevo_Codigo(1);">Guardar</button>


  <button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-1" data-dismiss="modal" onclick="ver_tabla(1);">Salir</button>
 </div>
</div>

</div><!-- ./Form-Horizontal -->
';



// ENCODE...............................................
$encode = array(0=>$txtModal);
echo json_encode($encode);

?>
