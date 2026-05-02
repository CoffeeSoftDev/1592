<?php
session_start();
include_once("../../modelo/SQL_PHP/_DIRECCION.php");
$obj = new direccion;

$idObject = $_POST['id'];
$array = array($idObject);
$data = $obj -> dataUsers_1($array);
foreach ($data as $key );

$con1 =$obj->dataNivel(null);
$con2 =$obj->dataArea(null);
$con3 =$obj->dataudn(null);

// ----

$txt='';

$txt=$txt.'
<div class="form-horizontal">

<div class="form-group Group_nameX">
<label  class="col-sm-4 ">Nombre Completo:</label>
<div class="col-sm-8">
<input type="text" class="form-control input-xs" id="txtGerenteX" value="'.$key[5].'" required="">
</div>
</div>

<!---->


<div class="form-group Group_userX">
<label  class="col-sm-4 ">Usuario:</label>
<div class="col-sm-8">
<input type="text" class="form-control input-xs" id="txtUsuarioX" value="'.$key[1].'" required="">
</div>
</div>

<!---->
<!--<div class="form-group">
<label  class="col-sm-4">Contraseña *:</label>
<div class="col-sm-8">
<input type="password" class="form-control input-xs" id="txtPass">
</div>
</div>-->
<!---->

<!--
<div class="form-group Group_pass">
<label  class="col-sm-4">Ingresa nuevamente la contraseña* :</label>
<div class="col-sm-8">
<input type="password" class="form-control input-xs" id="txtPass2">
</div>
</div> -->

<!---->

<div class="form-group Group_mailX">
<label  class="col-sm-4 ">Correo:</label>
<div class="col-sm-8">
<input type="text" class="form-control input-xs" id="txtEmailX" value="'.$key[4].'" required="">
</div>
</div>

<div class="form-group">
<label class="col-sm-4 ">Negocio :</label>

<div class="col-sm-8 Group_udn"  id="cbNegocioX">
<select class="form-control input-xs" required="" id="txtUDNX">
';
foreach ($con3 as $cbNegocio ) {
 if ($cbNegocio[0]==$key[3]) {
  $txt=$txt.'<option value="'.$cbNegocio[0].'" selected>'.$cbNegocio[1].'</option>';
 }
 $txt=$txt.'<option value="'.$cbNegocio[0].'">'.$cbNegocio[1].'</option>';
}
$txt=$txt.'</select>
</div>
</div>


<div class="form-group">
<label class="col-sm-4 ">Área :</label>

<div class="col-sm-8 Group_areaX"  >
<select class="form-control input-xs" required="" id="txtAreaX">
';
foreach ($con2 as $cbArea ) {
 if ($cbArea[0]==$key[3]) {
  $txt=$txt.'<option value="'.$cbArea[0].'" selected>'.$cbArea[1].'</option>';
 }
 $txt=$txt.'<option value="'.$cbArea[0].'">'.$cbArea[1].'</option>';
}



$txt=$txt.'</select>
</div>
</div>


<div class="form-group">
<label for="status" class="col-sm-4 ">Nivel</label>

<div class="col-sm-8 Group_nivelX" >
<select class="form-control input-xs" name="status" id="txtNivelX">
';

foreach ($con1 as $cbNivel ) {
 if ($cbNivel[0]==$key[3]) {
  $txt=$txt.'<option value="'.$cbNivel[0].'" selected>'.$cbNivel[1].'</option>';
 }
 $txt=$txt.'<option value="'.$cbNivel[0].'">'.$cbNivel[1].'</option>';
}


$txt=$txt.'</select>
</div>
<!---->

</div>	<!--./ form-group -->




<div class="form-group">
<div class="col-xs-12 col-sm-12 text-success text-center" id="txtResX" >

</div>
</div>


<div class="form-group">
<!-- <label for="image" class="col-sm-2 control-label">Imagen</label> -->

<!-- <div class="col-sm-8">
<input type="file" name="imagefile" id="imagefile" onchange="">
</div> -->
</div>

<div class="form-group" >

<!-- <div class="col-xs-12 col-sm-2 col-sm-offset-8">
<a type="button" class="btn btn-xs btn-danger  col-xs-12 " ></a>
</div> -->

<div class="col-xs-12 col-sm-4 col-sm-offset-8">
<a type="button" onclick="EditData(1,'.$idObject.')" class="btn btn-xs btn-success  col-xs-12 ">Guardar</a>
</div>
</div>




</div>


';



// ENCODE...............................................
$encode = array(0=>$txt);
echo json_encode($encode);

?>
