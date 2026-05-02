<?php
session_start();
?>


<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
 <meta http-equiv="Content-Type" content="text/html; charset=gb18030">

 <meta name="viewport"
  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <title>Movimientos</title>
 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.min.css">
 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap-datetimepicker.css">
 <link rel="stylesheet" href="recursos/css/bootstrap/css/font-awesome.css">
 <link rel="stylesheet" href="recursos/icon-font/fontello.css">
 <link rel="stylesheet" href="recursos/icon-font/animation.css">
 <link rel="stylesheet" href="recursos/css/formato.css">
 <link rel="stylesheet" href="recursos/css/style.css">
 <link rel="stylesheet" href="recursos/css/argovia_base.css">
 <link rel="stylesheet" href="recursos/css/jquery-ui.css">
 <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'>
 <link rel="shortcut icon" href="https://www.argovia.com.mx/img/favicon.ico">

 <!--dataTables-->

 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">

 <style media="screen">
 .form-group {
  margin-bottom: 5px;
 }

 .bg-warning-1 {

  background: #FFFF00;
 }

 .text-primary-1 {
  color: #1F345F;
 }
 </style>

 <style>
 .table tbody tr>td,
 .table tbody tr>th,
 .table tfoot tr>td,
 .table tfoot tr>th,
 .table thead tr>td,
 .table thead tr>th {
  /* border: 1px solid #ccc5b9; */
  border: 1px solid #323231;
 }

 .table-bordered {
  /* border: 1px solid #ccc5b9; */
  border: 1px solid #323231;
 }
 </style>

 <style>
 .ui-autocomplete {
  max-height: 100px;
  overflow-y: auto;
  overflow-x: hidden;
 }

 * html .ui-autocomplete {
  height: 100px;
 }

 .ui-autocomplete {
  z-index: 9999 !important;
 }
 </style>


</head>

<body style="-webkit-print-color-adjust: exact; ">
 <?php include_once('header.php'); ?>
 <?php include_once('tab_navs.php'); ?>
<!--  Subir File -->
 <div class="modal fade " id="M1" role="dialog">
  <div class="modal-dialog ">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal">&times;</button>
     <h4 class="modal-title text-center">
      <strong><span class=" icon-upload"></span> Subir archivo </strong>
     </h4>
     <p class="text-center"></p>

    </div>
    <div class="modal-body">
     <div id="SubirIMG">
     </div><!-- row-->
    </div><!-- ./ modal-body -->
   </div>
  </div>
 </div>




 <script src="recursos/js/jquery.js" charset="utf-8"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



 <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.min.js"></script>
 <!-- boot box -->
 <script src="recursos/plugin/bootbox.min.js" charset="utf-8"></script>

 <!-- Data Table JS -->
 <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js">
 </script>
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<!-- data Ranger Picker -->
 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


 <script src="recursos/js/jquery.numeric.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap/bootstrap.js" charset="utf-8"></script>
 <script src="recursos/js/moment.js" charset="utf-8"></script>
 <script src="recursos/js/es_moment.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>
 <script src="recursos/js/finanzas/cliente/panes.js?t=<?=time()?>" charset="utf-8"></script>
 <script src="recursos/js/finanzas/cliente/tc_file.js?t=<?=time()?>" charset="utf-8"></script>
 <script src="recursos/js/finanzas/cliente/hotelPane.js?t=<?=time()?>" charset="utf-8"></script>
 <script src="recursos/js/utilerias.js?t=<?=time()?>" charset="utf-8"></script>
 <!-- <script src="https://bersalfenix.com/CoffeSoft/plugins/complementos.js?t=<?=time()?>" charset="utf-8"></script> -->

 <script src="recursos/js/perfil.js?t=<?=time()?>" charset="utf-8"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


 <script type="text/javascript">

 </script>

<!-- date picker -->
 <script type="text/javascript">
 $(document).ready(function() {
  $(function() {
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

   $(".calendariopicker").on("dp.change", function(e) {
    oculto();
   });
  });

  $('.calendariopicker').keypress(function(evt) {
   return false;
  });
 });
 </script>