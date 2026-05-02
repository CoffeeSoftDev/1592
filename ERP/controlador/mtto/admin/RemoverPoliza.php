<?php
session_start();

include_once("../../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

include_once("../../../modelo/SQL_PHP/_Perfil.php");
$perfil = new PERFIL;


if (!isset($_SESSION['nivel'])) {
 echo "<script> window.location = '../../index.php'</script>";
}

else {
 $var       =  $_POST['id'];
 $pass      =  $_POST['pass'];
 $txt       =  '';
 $user      =  $_SESSION['user'];


 // ===========================================
 //     FUNCIONES-PHP
 // ===========================================
 $array     = array($user,$pass);
 $uno       = $perfil->Select_Login($array);

 if ($uno[0] != null) {  // Contraseña Correcta

  $ref = $obj ->RemoverPoliza ($var);
  $txt=$txt.'
  <div class="SubirDATA">
  <div class="row">
  <div class="form-group col-sm-12">
  <label class="col-sm-12 text-center">
  <strong>Seleccionar archivo</strong>
  </label>
  <div class="col-sm-8 col-sm-offset-2">
  <input type="file" class="form-control input-sm" id="archivos"> </div>
  </div>
  </div>
  <div class="row">
  <div class="form-group col-sm-12" >
  <label class="col-sm-12 text-center">
  <strong>Detalles</strong>
  </label>
  <div class="col-sm-8 col-sm-offset-2">
  <textarea name="name" rows="4" class="col-sm-12 col-xs-12 form-control input-sm" id="Detalles"></textarea>
  </div></div></div>
  <div id="Resul" class="text-center"> </div>
  <div class="row">
  <div class="form-group col-sm-12 col-xs-12">
  <label class="col-sm-12 text-center"> Limite máximo 20Mb * </label>
  <button type="button" class="btn btn-sm btn-info col-sm-4 col-sm-offset-4" onclick="Up_Files('.$var.');">
  <span class="icon-upload"></span> Subir Archivos</button>
  </div></div></div>
  ';

 }else {
  $txt=$txt.'
  <div class="form-horizontal">
  <div id="Resultado_baja">
  <div class="text-center text-danger">
  <i class="icon-cancel-circle fa-5x mb-3 "></i>
  <p>Error de contraseña intentar nuevamente.</p>
  </div>

  <div class="form-group">
  <div class="col-sm-12 col-xs-12" id="txtBTN">
  <button type="button" class="btn btn-xs btn-default col-xs-12 col-sm-8 col-sm-offset-2 "  onclick="RemoverPoliza(1);"><span class="fa fa-undo"></span> Volver a intentar </button>
  </div>
  </div></div>


  <div class="form-group">
  <div class="col-sm-12 " id="btnZone"></div></div></div>
  ';

 }







 // ===========================================
 //     ENCODE JSON
 // ===========================================
 $encode = array(
  0=>$txt);

  echo json_encode($encode);
 }

 ?>
