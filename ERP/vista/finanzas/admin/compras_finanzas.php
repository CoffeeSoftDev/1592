<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas.php');
include_once('../../../modelo/SQL_PHP/_Perfil.php');

// ------
$fin      = new Finanzas;
$perfil   = new PERFIL;
$idUDN    = $_POST['udn'];
// ------

?>

<div id="Res"></div>

<br>

<div class="form-group col-sm-3 col-xs-12 text-center"></div>

<div class="form-group col-sm-3  col-sm-offset-3 col-xs-12">
 <div class="col-sm-12 col-xs-12">
  <select class="form-control input-sm" id="Clase_Insumo">
   <option value="0">Seleccionar clase de insumo</option>

   <?php
   $sql = $fin->Select_Group_Gastos();

   foreach ($sql as $row) {
    echo '<option value='.$row[0].'>'.$row[1].'</option>';
   }
   ?>

  </select>
 </div>
</div>

<div class="form-group col-sm-3 col-xs-12">
 <div class="col-sm-12 col-xs-12">
  <button class="btn btn-info col-sm-12 col-xs-12 btn-sm"
  onClick="ver_compras_admin(1);"> <span class=""></span> Actualizar Tabla</button>
 </div>
</div>


<div class="row"></div>
<div id="tbCompras"></div>
