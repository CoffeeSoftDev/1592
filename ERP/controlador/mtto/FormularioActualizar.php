<?php
session_start();
include_once("../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

$idEquipo   = $_POST['idEquipo'];
$arrayData  = array($idEquipo );
$data       = $obj->Show_DATA_SINGLE($arrayData);


/*===========================================
*						main()
=============================================*/
$c1 =$obj->dataCat();
$c2 =$obj->cbZona();
$habilitarCategoria = 'enabled';

if ($data[5]!=1) {
 $habilitarCategoria = 'disabled';

}


$txtModal='';

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
<label  class="col-xs-12 col-sm-4">C贸digo del Equipo: </label>

<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="text"
style="font-size:1.5em;"
class="form-control input-xs"
id         = "Codigo"
value      = "'.$data[0].'"
onfocus    = "CodigoEquipo()"
onkeydown  = "Search_Code()" disabled>
</div>
</div><!-- ./-->

<div  class="form-group">
<label class="col-xs-12 col-sm-4">Nombre del Equipo:</label>

<div id="art_Group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

<input type="text"
class="form-control input-xs"
id          = "Equipo"
name        = "articulo"
value       = "'.$data[1].'"
onBlur      = "CodigoEquipo()"
onkeydown   = "Search_Name()">

</div>
</div><!-- ./-->

<div id="tab1x" class="tab-pane fade in active">';
$gral      = form_gral($habilitarCategoria,$c1,$c2,$data);
$txtModal  = $txtModal.$gral.'</div><!-- ./tab1-->


<div id="tab2x" class="tab-pane fade">';

$costos      = form_costos($data,$idEquipo);
$txtModal    = $txtModal.$costos.'</div>


</div> <!-- ./tab-content -->
</form><!-- ./Form-Horizontal -->

<div  id="Registro">Hola mundillo </div>
';

/*-----------------------------------*/
/* Funciones & Complementos
/*-----------------------------------*/
function form_costos($data,$idEquipo){
  $txtModal = '';
  $txtModal=$txtModal.'
  <div class="form-group">
  <div class="col-xs-12 col-sm-4">
  <label>Cantidad:</label>
  </div>

  <div id="min_Group" class="col-xs-12 col-sm-8">
  <input type="text" class="form-control input-xs" name="minimo"
   value="'.$data[5].'" id="txtCantidad" disabled onkeyup="Numero()">
  </div>

  </div><!-- ./ -->


  <div class="form-group">
  <label class="col-xs-12 col-sm-4">Costo: </label>

  <div id="cEnt" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

  <input type="text"
  class="form-control input-xs"
  id="txtCosto"
  name="costoEnt"
  value="'.$data[10].'"
  onfocus="">

  <div class=" bg-default" id="Res_Costo"></div>
  </div>
  </div><!-- ./-->



    <div class="form-group">
    <label class="col-xs-12 col-sm-4">Utilidad: </label>

    <div class="col-xs-12 col-sm-4" style="position:relative;">

    <input type="text" class="form-control input-xs" id="date" data-format="YYYY-MM-DD" data-template="D MMM YYYY" name="date" value="'.$data[11].'" onchange="TiempoVida()" >
    <p class="text-info" style="font-size:1.2rem; font-weight=bold;" > * Debes indicar la vida util del producto </p>

    <span  class="text-info"></span>
    </div>


    <div class="col-xs-12 col-sm-2 " style="position:relative;">

    <input class="form-control input-xxs" id="TimeLife" disabled style="width:100%" value="0">
    </div>

    <div class="col-xs-12 col-sm-2 " style="position:relative;" >
    <label>MESES</label>
    </div>
    </div>

    <div class="form-group">
    <label class="col-xs-12 col-sm-4">Detalles: </label>

    <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

    <textarea
    class="form-control "
    id="txtDetalles"
    >'.$data[12].'</textarea>

    <div class=" bg-default" id="Res_Detalles"></div>
    </div>
    </div><!-- ./-->
  ';

  $bueno   = '';
  $malo    = '';
  $regular = '';

  $buenoClass   = '';
  $maloClass    = '';
  $regularClass = '';

  switch ($data[13]) {
   case 1:$bueno    ='autofocus="true"' ; $buenoClass   = 'active'; break;
   case 2:$regular  ='autofocus="true"' ; $regularClass    = 'active';break;
   case 3:$malo     ='autofocus="true"' ; $maloClass = 'active';break;


  }

  $txtModal=$txtModal.
  '<div class="form-group">
  <label class="col-xs-12 col-sm-4">Estado: </label>

  <div class="col-sm-8 col-xs-12">

  <div class="btn-group" data-toggle="buttons">
  <button class="'.$buenoClass.' btn btn-outline-success btn-xss " '.$bueno.' >
  <input type="radio" value="1" name="rdExtra"><strong>
  <!-- <span class="fa fa-pagelines"></span>  -->
  BUENO
  </strong>
  </button>

  <button class="'.$regularClass.' btn btn-outline-warning btn-xss " '.$regular.'>
  <input type="radio" value="2" name="rdExtra"><strong>
  <!-- <span class="fa fa-pagelines"></span> --> REGULAR  </strong>
  </button>

  <button class="'.$maloClass.' btn btn-outline-danger btn-xss btn-outline" '.$malo.'>
  <input type="radio" value="3" name="rdExtra"><strong>
  <!-- <span class="fa fa-pagelines"></span> --> MALO  </strong>
  </button>

  </div>

  </div>
  </div><!-- -->

  <div class="form-group" id="Resultado"></div>
  <div class="form-group">
  <div class="col-xs-12 ">
  <button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-5" data-dismiss="modal" onclick="ver_tabla(1);">Salir</button>

  <button type="submit" class="btn btn-success col-xs-12 col-sm-3 col-sm-offset-1" onclick="Nuevo_Codigo(2,'.$idEquipo.');">Guardar</button>


  </div>
  </div>

  ';
return $txtModal;
}

function form_gral($habilitarCategoria,$c1,$c2,$data){
  $txtModal = '';
  $txtModal=$txtModal.'
<div class="form-group">
  <label class="col-xs-12 col-sm-4">Categoria: </label>
  <div class="col-xs-12 col-sm-8">
  <select class="form-control input-xs" name="tipoEmpresa" id="A" '.$habilitarCategoria.'>
  ';

  foreach ($c1 as $key) {
   if ($data[7]==$key[1]) {
    $txtModal=$txtModal.'<option value="'.$key[0].'" selected> '.$key[1].'</option> ';
   }else {
    $txtModal=$txtModal.'<option value="'.$key[0].'"> '.$key[1].'</option> ';

   }
  }
  $txtModal=$txtModal.'
  </select>
  </div>
  </div><!-- ./-->
  <div class="form-group">
  <label class="col-xs-12 col-sm-4">Zona:</label>
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
  <label  class="col-xs-12 col-sm-4">Area:</label>
  <div id="area_Group" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

  <input type="text"
  class="form-control input-xs"
  id="Area"
  value="'.$data[2].'"
  name="area"
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
  value="'.$data[14].'"
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
  value="'.$data[16].'"
  style="text-transform:uppercase;"
  >

  </div>
  </div><!-- ./-->

  <div class="form-group">
  <div class="col-xs-12 col-sm-7">


  <input type="file" class="form-control btn btn-default" id="imgInp" onchange="readURL(this)">
  </div>

  <div class="col-xs-12 col-sm-5 text-left">
  <img id="blah" src="'.$data[15].'"  alt="" width="180px"/>
  </div>
  </div>


';

  return $txtModal;
}

// ENCODE...............................................
$encode = array(0=>$txtModal);
echo json_encode($encode);

?>
