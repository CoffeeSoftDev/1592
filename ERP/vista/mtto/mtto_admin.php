<?php

?>
<!DOCTYPE html>
<html lang="es" >
<head>
 <?php include_once('stylesheet.php'); ?>
  <title>Argovia Finca Resort</title>
 <link href = "https://code.jquery.com/ui/1.10.4/themes/Start/jquery-ui.css" rel = "stylesheet">
 <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
 <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 <!--validator-->
 <link rel="stylesheet" href="recursos/validator/css/bootstrapValidator.css"/>
  <link rel="shortcut icon" href="http://www.argovia.com.mx/img/favicon.ico">

 <!-- dataTables -->
 <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.5/css/fixedColumns.bootstrap4.min.css"/>


 <link rel="stylesheet" href="recursos/plugin/zoom/zoom.css">
 <script src = "recursos/plugin/zoom/zoom.js"></script>

</head>

<style media="screen">
@media (max-width: 991.98px){

 .dataTables_wrapper table {
  display: block;
  width: 100%;
  min-height: .01%;
  overflow-x: auto;
 }

}
</style>

<body>


 <?php include_once('header.php'); ?>
 <?php include_once('tab_navs.php'); ?>


 <!--  Nuevo Material -->
 <div class="modal fade" id="Producto" role="dialog" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="mdialTamanio">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close form-horizontal" data-dismiss="modal" onclick="ver_tabla();">&times;</button>
     <h4 class="modal-title text-center"><span class="icon-wrench-1"></span> Nuevo Material</h4>
    </div>
    <div class="modal-body">
     <div id="okRegistro"></div>
     <div id="code"></div>
    </div>
   </div>
  </div>

 </div>



 <!--  Baja   -->
 <div class="modal fade" id="baja" role="dialog" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="mdialTamanio">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close form-horizontal" data-dismiss="modal" onclick="ver_tabla();">&times;</button>
     <h4 class="modal-title text-center"><span class="icon-wrench-1"></span> Baja de material</h4>
    </div>
    <div class="modal-body">
     <div  id="baja_code">

     </div>
    </div>
   </div>
  </div>
 </div>


 <!--./ Modal -->
 <div class="modal fade " id="M1" role="dialog">
  <div class="modal-dialog modal-max">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title text-center">
      <strong>Poliza de garantia</strong></h4>
      <p class="text-center"></p>

     </div>
     <div class="modal-body">
      <div id="SubirIMG">
      </div><!-- row-->
     </div><!-- ./ modal-body -->
    </div>
   </div>
  </div>



  <!-- JS -->

  <script src="recursos/js/mtto/mtto.js?t=<?=time()?>"></script>
  <script src="recursos/js/mtto/mtto_reporte.js?t=<?=time()?>"></script>
  <script src="https://plugins.erp-varoch.com/complementos.js"></script>
  <script src="recursos/js/mtto/modal_material.js?t=<?=time()?>"></script>

  <!-- dataTables -->
   <script src="recursos/datatables/js/datatables.min.js"></script>

   <script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>




  <!-- Form Validator -->
  <script src="recursos/validator/js/bootstrapValidator.js"></script>

  <script src="recursos/js/moment.js" charset="utf-8"></script>
  <script src="recursos/js/es_moment.js" ></script>
  <script src="recursos/js/combodate.js"></script>
  <script src="recursos/js/bootstrap.min.js"></script>


 </body>
 </html>
