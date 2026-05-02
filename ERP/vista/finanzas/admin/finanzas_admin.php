<?php
session_start();
$nivel     = $_SESSION['nivel'];

if ($nivel ==3 || $nivel ==1) {

 include_once('../../../modelo/SQL_PHP/_Finanzas.php');
 include_once('../../../modelo/SQL_PHP/_Perfil.php');
 $fin    = new Finanzas;
 $perfil = new PERFIL;
 $idE    = $_SESSION['udn'];
 // Fecha Final

 $now = $fin->NOW();
 $udn = $perfil->Select_UDN();
 // Fecha inicial
 $a      = date("Y", strtotime("$now"));
 $m      = date("m", strtotime("$now"));
 $d      = date("d", strtotime("$now"));
 $inicio = $a.'-'.$m.'-1';

 ?>


 <!DOCTYPE html>
 <html lang="es">
 <head>
 <title>Argovia Finca Resort</title>

  <meta charset="utf-8">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">
  <?php include_once('stylesheet.php'); ?>

 </head>


 <body>


  <?php include_once('header.php'); ?>
  <?php include_once('nav_tabs.php'); ?>

  <!--
  Modal
 -->
 <div class="modal fade" id="Modal_Sobre" role="dialog" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="mdialTamanio">
   <div class="modal-content">
    <div id="Sobre_Modal"></div>
   </div>
  </div>
 </div>




 <!-- js -->
 <script src="recursos/js/jquery-1.12.3.js" ></script>
 <script src="recursos/js/bootstrap/bootstrap.min.js" charset="utf-8"></script>

<!-- select2 -->
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
 <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>


 <!-- DataTables -->
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" charset="utf-8"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"      charset="utf-8"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"      charset="utf-8"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"       charset="utf-8"></script>
  <script src="recursos/js/complementos.js"></script>
 <!--  dataPicker -->
 <script src="recursos/js/moment.js" charset="utf-8"></script>
 <script src="recursos/js/es_moment.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>

 <script type="text/javascript">
 $(document).ready(function(){
  $(function () {
   $('.calendariopicker').datetimepicker({
    format: 'YYYY-MM-DD',
    useCurrent: false,
    defaultDate: new Date(),
    // minDate: moment().add(-1, 'd').toDate(-40, 'd'),
    widgetPositioning: {
     horizontal: 'right',
     vertical: 'bottom'
    },
   });

   $(".calendariopicker").on("dp.change", function (e) {});
  });

  $('.calendariopicker').keypress(function (evt) {  return false; });
 });
</script>


<!-- boot box -->
<script src="recursos/plugin/bootbox.min.js" charset="utf-8"></script>

<script src="recursos/js/finanzas/contabilidad/sobres_admin.js?t=<?=time()?>"></script>

<!-- <script src="recursos/js/finanzas/contabilidad/ingresos_turismo.js"></script> -->




</body>
</html>
<?php

}else {
 $area = $_SESSION['area'];

 echo '
 <script src="recursos/js/jquery.js"></script>
 <script src="recursos/js/index.js"></script>
 <script> nivel(\''.$nivel.'\',\''.$area.'\',\'\'); </script>
 <div class="res"></div>
 ';

}

?>
