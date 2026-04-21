<!DOCTYPE html>
<?php
session_start();

echo '!!!!'.$_SESSION['nivel'];
?>

<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=euc-jp">

 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

 <?php include_once('stylesheets.php'); ?>

 <style >

.bg-info {
 background-color: #d9edf7;
 color: #444444;
}

.bg-success {
 background-color: #dff0d8;
 color: #444444;
}
</style>

</head>
<body>

 <?php
include('navbar-custom.php');
  ?>
 <?php
include('sidebar.php');
 ?>


 <div class="container-fluid">

  <div class="row">
   <br>
   <div class="col-sm-10 col-sm-offset-2" >

    <div class="panel panel-default" id="content-pane">
     <div class="panel-body">
      <div class="form-horizontal">
       <div class="col-xs-6 col-sm-8">
        <h4>
         <i class='bx bxs-wallet bx-dm'></i><b>INGRESOS TURISMO</b>
        </h4>
       </div>
       <div class="col-xs-6 col-sm-4">

        <div id="reportrange" style="cursor: pointer; padding: 10px 10px; border: 1px solid #ccc; width: 100%">
         <i class="fa fa-calendar"></i>&nbsp;
         <span></span> <i class="fa fa-caret-down"></i>
        </div>
       </div>



       <hr>
       <div class="tab_content"></div>
       <div class="col-xs-12 col-sm-12 " >

       </div>


      </div><!-- ./Form-horizontal -->
     </div><!-- ./ panel body-->
    </div><!-- ./ panel panel-default -->



   </div><!-- col-sm-10 -->
  </div><!-- row -->


 </div> <!-- container-fluid -->


 <!-- JavaScript -->

 <script src="recursos/js/jquery.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap.min.js"></script>
 <!-- boot box -->
 <script src="recursos/plugin/bootbox.min.js" charset="utf-8"></script>

 <!-- Data Table JS -->
 <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

 <script src="recursos/js/moment.js" charset="utf-8"></script>
 <script src="recursos/js/es_moment.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>

 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
 <script src="recursos/js/finanzas/contabilidad/ingresos_turismo.js?t=<?=time()?>"></script>
 <script src="recursos/js/finanzas/cliente/tc_file.js?t=1561578499" charset="utf-8"></script>
 <script src="https://plugins.erp-varoch.com/complementos.js" charset="utf-8"></script>

<!-- dataTables -->
<script src="recursos/datatables/js/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js"></script>

 <!-- data Ranger Picker -->
 <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
 <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
 <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


 <script type="text/javascript">
 $(function() {
  // var start = moment().subtract(29, 'days');
  var start = moment();
  var end   = moment();
  function cb(start, end) {

   GRAL();
   $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
  }

  $('#reportrange').daterangepicker({
//   "showDropdowns": true,
   startDate: start,
   endDate: end,
   cancelClass: "btn-danger",
   ranges: {
    'Hoy': [moment(), moment()],
    'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
    //    'Ultimo 30 dias': [moment().subtract(29, 'days'), moment()],
    'Mes actual': [moment().startOf('month'), moment().endOf('month')],
    'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
    'Año actual': [moment().startOf('year'), moment().endOf('year')],
    'Año anterior': [moment().subtract(1, 'year').startOf('year'),moment().subtract(1, 'year').endOf('year')]
   }
  }, cb);

  cb(start, end);

 });
 </script>

</body>
</html>
