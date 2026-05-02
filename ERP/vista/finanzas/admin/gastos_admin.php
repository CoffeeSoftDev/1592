<?php
session_start();

include_once('../../../modelo/SQL_PHP/_Finanzas.php');
include_once('../../../modelo/SQL_PHP/_Perfil.php');

$fin      = new Finanzas;
$perfil   = new PERFIL;
$idUDN    = $_POST['udn'];

?>

<div id="Res"></div>

<br>


<div class="form-group row">

 <div class="col-xs-12 col-sm-3">
  <div class="input-group">
  <input onkeyup="ver_tabla_gastos(1)" type="text" class="form-control input-xs" id="txtBusqueda">

  <div class="input-group-addon">
   <i class="icon-search"></i>
  </div>
 </div>
 </div><!-- ./-->

<div class="col-xs-12 col-sm-3">
 <select class="form-control  input-sm" id="Clase_Insumo">
  <option value="0">Seleccionar clase de insumo</option>

  <?php
  $sql = $fin->Select_Group_Gastos();

  foreach ($sql as $row) {
   echo '<option value='.$row[0].'>'.$row[1].'</option>';
  }
  ?>

 </select>
</div><!-- ./-->

<div class="col-xs-12 col-sm-4">
  <button class="btn btn-info btn-sm"
  onClick="ver_tabla_gastos(1)"> <span class=""></span> Actualizar Tabla</button>

  <button class="btn btn-default btn-sm"
  onClick="ConsultarMovimientos()"> <span class="icon-doc-text"></span>Consultar movimientos </button>

</div><!-- ./-->




</div>



<div class="form-group col-sm-2 col-sm-offset-4 col-xs-12 text-center">


</div>

<div class="form-group col-sm-3  col-xs-12">
 <div class="col-sm-12 col-xs-12">

 </div>
</div>

<div class="form-group col-sm-2 col-xs-12">

</div>



<div class="form-group col-sm-1 col-xs-12">

</div>


<div class="row"></div>
<div id="tbGastos"></div>
<!--

</div>

-->
