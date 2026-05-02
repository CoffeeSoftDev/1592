<?php
session_start();
include_once("../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

$txt='';
$id  = $_POST['id'];


$txt=$txt.'
<div class="form-horizontal">
<div class="form-group">
<label class="col-sm-12">Por motivos de seguridad escribe tu contraseña:</label>
<div class="col-sm-12">
<input type="password" class="form-control" id="pass" autocomplete="off">
<div class=" bg-default" id="Res_Pass"></div>
</div>
</div>


<div class="form-group" id="Resultado_baja"></div>
<br>

<div class="form-group">
<div class="col-sm-12 ">
	<button type="button" class="btn btn-primary col-sm-12 col-sm-3 col-sm-offset-5" onclick="Baja('.$id.')">Guardar</button>

';

$txt=$txt.'<button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-1" data-dismiss="modal" onclick="dataUsers();"> Salir </button>';


$txt=$txt.'</div></div></div>';

// ===========================================
//     ENCODE JSON
// ===========================================
$encode = array(
 0=>$txt);

 echo json_encode($encode);

?>
