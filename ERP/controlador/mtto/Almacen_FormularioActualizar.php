<?php
session_start();
include_once("../../modelo/SQL_PHP/_ALMACEN.php");
$obj = new ALMACEN;


$idArt    = $_POST['idArt'];
$data     = $obj->verProductos($idArt);
foreach   ($data as $dataset);

/*===========================================
*									MAIN
=============================================*/
$txtModal='';

$txtModal=$txtModal.'
<form id="defaultForm" class="form-horizontal" onsubmit="return false" >


<ul class="nav nav-tabs">
<li class="active">
<a class="text-warning"
data-toggle="tab" href="#tab1x" onclick=""> <strong>General</strong></a>
</li>

<li>
<a data-toggle="tab" href="#tab2x" onclick=""><strong>Stock & Precios</strong></a>
</li>
</ul>

<div class="tab-content">
<!-- -->
<div class="form-group">
<label  class="col-xs-12 col-sm-4">Código: </label>

<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="text"
class="form-control input-xs"
id="Codigo"
onfocus="CodigoEquipo()" disabled
value="'.$dataset[1].'"
>

<div class="bg-default" id="Res_Codigo"></div>
</div>
</div><!-- ./-->


<div class="form-group">
<label class="col-xs-12 col-sm-4">Articulo:</label>

<div id="art_Group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="text"
class="form-control input-xs"
id="txtEquipo"
name="articulo"
value="'.$dataset[2].'"
onBlur="CodigoEquipo()"
onkeydown="Search_Name_U()"

>

</div>
</div><!-- ./-->
<hr>
<div id="tab1x" class="tab-pane fade in active">';

$gral = General($dataset[0],$dataset[5],$dataset[10],$dataset[12],$dataset[11],$dataset[14],$dataset[15]);
$txtModal=$txtModal.$gral;

$txtModal=$txtModal.'</div><!-- ./ PESTAÑA 1 -->

<!-- PESTAÑA 2 -->
<div id="tab2x" class="tab-pane fade">
';

$ps = PreciosStock($dataset[6],$dataset[7],$dataset[3],$dataset[4],$dataset[8],$dataset[13],$idArt);
$txtModal=$txtModal.$ps;

$txtModal=$txtModal.'</div></div></form>';

/*===========================================
*				      JSON- ENCODE
=============================================*/

$encode = array(
 0=>$txtModal,
 1=>$dataset[2],
 2=>$dataset[1],
 3=>$dataset[1],
 4=>$dataset[1]);
 echo json_encode($encode);
 /*===========================================
 *									FuncionesPHP
 =============================================*/

 function General($zona,$familia,$clase,$marca,$idMarca,$unidad){
  $obj     = new ALMACEN;
  $c1      = $obj->zona();
  $c2      = $obj->Familia();
  $c3      = $obj->Marca();
  $c4      = $obj->Unidad();
  $txt = '';

  $txt = '
  <div class="form-horizontal" >





  <div class="form-group">

  <label class="col-xs-12 col-sm-4">Lugar: </label>
  <div class="col-xs-12 col-sm-8">
  <select class="form-control input-xs"  id="txtZona" onchange="CodigoEquipo()">';

  foreach ($c1 as $key) {

   if ($zona==$key[1]) {
    $txt=$txt.'<option value="'.$key[0].'" selected> '.$key[1].'</option> ';
   }else {
    $txt=$txt.'<option value="'.$key[0].'"> '.$key[1].'</option> ';
   }
  }

  $txt=$txt.'</select></div>
  </div><!-- ./-->



  <div class="form-group">
  <label  class="col-xs-12 col-sm-4">Area:</label>
  <div id="area_Group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

  <input type="text"
  class="form-control input-xs"
  id="Area"
  name="area"
  style="text-transform:uppercase;"
  onkeydown="Busqueda()">
  value="'.$key[15].'"
  </div>
  </div><!-- ./-->

  <div class="form-group">

  <label class="col-xs-12 col-sm-4">Familia: </label>
  <div class="col-xs-12 col-sm-8">
  <select class="form-control input-xs"  onchange="verClase(); CodigoEquipo();" id="txtFamilia">
  <option value="0">Selecciona una familia</option>
  ';

  foreach ($c2 as $key) {
   if ($familia==$key[1]) {
    $txt=$txt.'<option value="'.$key[0].'" selected> '.$key[1].'</option> ';
   }else {
    $txt=$txt.'<option value="'.$key[0].'"> '.$key[1].'</option> ';
   }
  }

  $txt=$txt.'</select></div>
  </div><!-- ./-->

  <div class="form-group">

  <label class="col-xs-12 col-sm-4">Clase: </label>
  <div class="col-xs-12 col-sm-8" id="cbClase">
  <select class="form-control input-xs"  id="txtClase" disabled>
  <option value="'.$idMarca.'">'.$clase.'</option>';
  $txt=$txt.'</select>
  </div>
  </div><!-- ./-->

  <div class="form-group">

  <label class="col-xs-12 col-sm-4">Unidad/ Venta : </label>
  <div class="col-xs-12 col-sm-8">
  <select class="form-control input-xs" id="txtUnidad">
  ';

  foreach ($c4 as $key) {

   if ($unidad==$key[1]) {
    $txt=$txt.'<option value="'.$key[0].'" selected> '.$key[1].'</option> ';
   }else {
    $txt=$txt.'<option value="'.$key[0].'"> '.$key[1].'</option> ';
   }


  }

  $txt=$txt.'</select></div>
  </div><!-- ./-->



  <div class="form-group">
  <label  class="col-xs-12 col-sm-4">Marca (opcional):</label>
  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

  <input type="text"
  class="form-control input-xs"
  id="txtMarca"
  value="'.$marca.'"
  onkeydown="Search_Marca_U()"
  >

  <div class="bg-default" id="Res_Marca"></div>
  </div>
  </div><!-- ./-->


  </div><!-- ./ form-horizontal -->



  ';


  return $txt;
 }

 function PreciosStock($min,$inicial,$cEnt,$cSal,$Util,$Desc,$idArt){
  $txt   ='';
  $dates  = date("d") ."-". date("m") . "-" . date("Y");
  $txt=$txt.'

  <div class="form-horizontal" >

  <div class="form-group">
  <label class="col-xs-12 col-sm-4">Stock-min: </label>

  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="min_Group">

  <input type="number"
  name="minimo"
  class="form-control input-xs"
  id="txtStockMin" placeholder=" Stock minimo de 10"
  min="10" autofocus="true"
  value="'.$min.'"
  disabled
  >

  <div class=" bg-default" id="Res_Stock_min"></div>
  </div>
  </div><!-- ./-->

  <div class="form-group">
  <label class="col-xs-12 col-sm-4">Stock Inicial: </label>

  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="stock_group">

  <input type="number"
  class="form-control input-xs"
  id="txtStock"
  min="10"
  name="stock"
  value="'.$inicial.'"
  disabled
  onfocus="">


  </div>
  </div><!-- ./-->





  <div class="form-group">
  <label class="col-xs-12 col-sm-4">Costo Entrada: </label>

  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="cEnt">

  <div class="input-group">
  <span class="input-group-addon">$</span>
  <input type="number" class="form-control input-xs" id="txtCosto1"
  min="1" step="any" name="costoEnt"
  value="'.$cEnt.'"
  >
  </div>

  <div class=" bg-default" id="Res_CostoEnt"></div>
  </div>
  </div><!-- ./-->


  <div class="form-group">
  <label class="col-xs-12 col-sm-4">Costo Salida: </label>

  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8" id="cSal">

  <div class="input-group">
  <span class="input-group-addon">$</span>
  <input type="number" step="any" class="form-control input-xs" id="txtCosto2" min="1"
  value="'.$cSal.'"
  aria-label="Amount (to the nearest dollar)" name="costoSal">
  </div>

  <div class=" bg-default" id="Res_CostoSal"></div>
  </div>
  </div><!-- ./-->

  <div class="form-group">
  <label class="col-xs-12 col-sm-4">Utilidad: </label>

  <div class="col-xs-12 col-sm-8" style="position:relative;">
  <input type="text" class="form-control input-xs" id="date" data-format="DD-MM-YYYY" data-template="D MMM YYYY" name="date" value="'.$Util.'" >
  <p class="text-info" style="font-size:1.2rem; font-weight=bold;" > * Debes indicar la vida util del producto </p>
  </div>
  </div><!-- ./ -->


  <div class="form-group">
  <div class="col-xs-12 col-sm-4">
  <label>Descripción (opcional):</label>
  </div>

  <div class="col-xs-12 col-sm-8">
  <textarea class="form-control " name=""  id="txtDesc" >'.$Desc.'</textarea>
  </div>


  </div><!-- ./ -->

  <div class="form-group" id="Resultado"></div>

  <div class="form-group">
  <div class="col-xs-12 ">
  <button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-5" data-dismiss="modal" onclick="ver_tabla(1);">Salir</button>

  <button  type="submit"  class="btn btn-success col-xs-12 col-sm-3 col-sm-offset-1" onclick="NUEVO(2,'.$idArt.');">Guardar</button>



  </div>
  </div>

  </div><!-- ./Form-Horizontal -->



  ';

  return $txt;
 }
 ?>
