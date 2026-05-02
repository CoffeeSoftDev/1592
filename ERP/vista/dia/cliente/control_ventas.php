<?php
session_start();

$nivel     = $_SESSION['nivel'];
if ($nivel == 10  || $nivel == 1) {
 ?>
 <!DOCTYPE html>
 <html lang="es" dir="ltr">
 <head><meta http-equiv="Content-Type" content="text/html; charset=gb18030">
  <?php include('stylesheet.php'); ?>


  <!--  dataTables -->
  <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">
  <!-- <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'> -->
  <link rel="stylesheet" href="recursos/css/tpv/card.css"/>
  <link rel="stylesheet" href="recursos/css/argovia_base.css"/>

 </head>
 <body>
  <?php include_once('header.php'); ?>

  <div class="container-fluid">
   <div class="col-sm-12">
    <div class="card content-pane">
     <div class="card-body">

     <div class="row">
      <div class="form-group col-sm-3 col-xs-12">
       <label class="control-label">Fecha de emisión</label>
       <div class="input-group date calendariopicker">
        <input type="text" class="select_input form-control input-sm" value="" id="date">
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
       </div>
      </div>
     </div>

     <div class="row tabla_productos"></div>
    </div><!--./ card body -->
   </div>
  </div>
 </div>

 <!-- JavaScript -->
 <script src = "recursos/js/jquery-1.12.3.js"></script>
 <script src = "recursos/js/jquery.numeric.js"></script>
 <script src="recursos/js/bootstrap.min.js"></script>
 <script src = "recursos/js/complementos.js"></script>
 <!-- datetime picker -->
 <script src="recursos/js/moment.js" charset="utf-8"></script>
 <script src="recursos/js/es_moment.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>
 <script src="recursos/js/dia/control_ventas.js?t=<?=time()?>" charset="utf-8"></script>

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

   $(".calendariopicker").on("dp.change", function (e) {

   });
  });

  $('.calendariopicker').keypress(function (evt) {  return false; });
 });
 </script>
</body>
</html>
<?php
}
?>
