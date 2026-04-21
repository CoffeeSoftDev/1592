<?php
  //Llammar las clases exteriores
  include_once('../../../modelo/SQL_PHP/_Finanzas.php');
  include_once('../../../modelo/SQL_PHP/_Perfil.php');
  //Declarar los objetos de las clases exteriores
  $fin = new Finanzas;
  $perfil = new PERFIL;
  //Obtener por post el id de la empresa
  $idUDN = $_POST['udn'];

  $udn = $perfil->Select_Name_UDN($idUDN);//Obtener el nombre de la empresa
  $now = $fin->NOW();//Obtener la fecha de hoy
  $a = date("Y", strtotime("$now"));
  $m = date("m", strtotime("$now"));
  $d = date("d", strtotime("$now"));
  $inicio = $a.'-'.$m.'-1';
?>

<div id="Res"></div>
<hr>

<div class="row">

  <div class="form-group  col-xs-12">
    <div class="pull-right col-sm-3">
      <button class="btn btn-info col-sm-12 col-xs-12 btn-sm" onClick="ver_rembolso_retiro();"> <span class=" "></span> Actualizar Tabla</button>
    </div>
  </div>
</div>
<br>

<div id="data_retiro"></div>
