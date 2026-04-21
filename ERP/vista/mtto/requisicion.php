<?php
// session_start();
// $nivel     = $_SESSION['nivel'];
include_once('../../modelo/SQL_PHP/_Finanzas_Cliente.php');
$fin = new Finanzas;

$sql = $fin->Select_Zona();
$fol = $fin->Folio_Req();
$folio = $fin->convert_folio($fol,'R');
// $nivel     = $_SESSION['nivel'];
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
 <?php include_once('stylesheet.php'); ?>
 <!-- <link rel="shortcut icon" type="image/png" href="http://www.argovia.com.mx/img/logo.png"> -->
 <link rel="stylesheet" href="recursos/validator/css/bootstrapValidator.css"/>
</head>


<body>
 <?php include_once('header.php'); ?>


 <div class="container">
  <div class="panel panel-default">
   <div class="panel-body">



    <div class="form-horizontal">
     <div class="form-group">
      <div class="col-sm-12 text-left"><label id="Titulo"> <strong> REQUISICIÓN </strong></label>
       <hr>
      </div>
     </div>


     <div class="row form-group">
      <div class=" col-sm-2 col-xs-12 " id="cbCat">
       <select class="form-control input-sm control-label" id="Cb_Zona">
        <option value="0">Selecciona una zona</option>
        <?php
        foreach ($sql as $row) {
         echo '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
        ?>
       </select>
      </div>

      <div class="col-sm-3 col-xs-12">
       <div class="input-group">
        <input type="text" class="form-control input-sm" placeholder="EQUIPOS/MATERIALES" id="MatEq"  onkeypress="if(event.keyCode == 13) Buscar_Equipo();">
        <span class="input-group-addon" id="basic-addon1"><i class="icon-search-2"></i> </span>
       </div>
      </div>

      <div class="col-sm-2 col-xs-12">
       <button class="btn btn-default btn-sm col-xs-12 col-sm-12" title="Imprimir y Guardar" onclick="Save_Requisicion();">
        <span class="icon-print" style="font-size:14px;"></span>
        Crear requisicion
        <span class="icon-ok"></span>
       </button>
      </div>

      <div class="col-sm-2 col-xs-12">
       <button class="btn btn-default btn-sm col-xs-12 col-sm-12" title="Requisiciones Anteriores" data-toggle="modal" data-target="#Producto" onclick="tb_Requisiciones_printer(1);">
        <span class="icon-table"></span>
        Ver Requisiciones
       </button>
      </div>

      <div class="col-sm-3 col-xs-12 text-right">
       <label>Folio: <strong id="folio"><?php echo $folio; ?></strong> </label>
      </div>
     </div>



     <div class="row form-group ">
      <div class="col-sm-12 col-xs-12 addEquipo"></div>
     </div>

     <div class="row form-group">
      <div class="col-sm-12 col-xs-12 txt_obs">
       <label class="control-label" for="Obs_Req">Observaciones</label>
       <textarea class="form-control input-sm " id="Obs_Req" rows="4" cols="80"></textarea>
      </div>
     </div>



     <div class="row form-group">
      <div class="col-sm-12 col-xs-12 tb_materiales"></div>
     </div>
    </div><!-- ./Form-horizontal -->
   </div>
  </div><!-- ./ panel panel-default -->
 </div><!-- ./container -->

 <!--  Nuevo Material -->
 <div class="modal fade" id="Producto" role="dialog" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="mdialTamanio">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h3 class="modal-title text-center">R E Q U I S I C I O N E S</h3>
    </div>
    <div class="modal-body">
     <?php
     $sql1 = $fin->Select_Mes_Requisiciones();
     $sql2 = $fin->Select_Anio_Requisiciones();
     $mes = array(); $anio = array();
     foreach ($sql1 as $key => $value) {
      $mes[$key] = $value[0];
     }
     foreach ($sql2 as $key => $value) {
      $anio[$key] = $value[0];
     }
     ?>

     <div class="row form-group">
      <div class="col-sm-6 col-xs-12">
       <label class="">Mes</label>
       <select class="form-control input-sm" id="Mes" onChange="tb_Requisiciones_printer(1);">
        <?php
        for ($i=0; $i < count($mes); $i++) {
         $mes_letra = $fin->Meses($mes[$i]);
         echo '<option value="'.$mes[$i].'">'.$mes_letra.'</option>';
        }
        ?>
       </select>
      </div>
      <div class="col-sm-6 col-xs-12">
       <label class="">Año</label>
       <select class="form-control input-sm" id="Year" onChange="tb_Requisiciones_printer(1);">
        <?php
        for ($i=0; $i < count($anio); $i++) {
         echo '<option value="'.$anio[$i].'">'.$anio[$i].'</option>';
        }
        ?>
       </select>
      </div>
     </div>
     <div class="row form-group tb_requisiciones_print"></div>
    </div>
   </div>
  </div>
 </div>

 <script src="recursos/js/jquery.js" charset="utf-8"></script>
 <script src="recursos/js/jquery-ui.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap/bootstrap.min.js" charset="utf-8"></script>
 <script src="recursos/js/mtto/requisicion.js?t=<?=time()?>" charset="utf-8"></script>
 <script src="recursos/js/mtto/modal_material.js?t=<?=time()?>" charset="utf-8"></script>

 <script src="recursos/js/moment.js" charset="utf-8"></script>
 <script src="recursos/js/es_moment.js" ></script>

 <script src="recursos/js/combodate.js"></script>
 <script src="recursos/validator/js/bootstrapValidator.js"></script>

</body>
</html>
