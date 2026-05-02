<?php
session_start();
$nivel = $_SESSION['nivel'];
$area = $_SESSION['area'];
if ( $_SESSION['nivel'] == 3 || $nivel ==1 ) {

 // echo '
 //   <script src="recursos/js/jquery.js"></script>
 //   <script src="recursos/js/index.js"></script>
 //   <script> nivel(\''.$nivel.'\',\''.$area.'\',\'\'); </script>
 //   <div class="res"></div>
 //   ';

  include_once('../../../modelo/SQL_PHP/_Finanzas.php');
  include_once('../../../modelo/SQL_PHP/_Perfil.php');

  // --
  $fin    = new Finanzas;
  $perfil = new PERFIL;
  //

  $idUDN  = $_POST['udn'];

  $udn = $perfil->Select_Name_UDN($idUDN);//Obtener el nombre de la empresa
  $now = $fin->NOW();//Obtener la fecha de hoy




?>

<div class="col-xs-12 col-sm-12 text-center" id="Res"></div>

<div class="row">
  <div class="form-group col-sm-3 col-sm-offset-9 col-xs-12">
    <div class="col-sm-12 col-xs-12">
      <button class="btn btn-info col-sm-12 btn-sm" onClick="ver_tabla_files(1);"> <span class=""></span> Actualizar Tabla</button>
    </div>
  </div>
</div>

<div id="tbArchivos">

</div>

<?php
  }
?>
