<!DOCTYPE html>
<html>
<head>
 <!-- CSS -->
 <?php include_once('stylesheet.php'); ?>
</head>

<body>
 <?php include_once('header.php'); ?>

 <div class="container">
  <div class="panel panel-default">
   <div class="panel-body">
    <div class="form-horizontal">

     <div class="form-group">
      <div class="col-sm-12 text-left"><label id="Titulo">
       <strong> REPORTE DE INVENTARIO  </strong></label></div>
      </div>
      <hr>

      <!-- Tablero  -->

      <div class="form-group">

       <div class="col-xs-12 col-sm-2" id="cbAnio">
        <div class='input-group date' id='txtFi'>
         <input type='text' class="form-control input-xs" id="fi">
         <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
         </span>
        </div>
       </div>


       <div class="col-xs-12 col-sm-2">
        <div class='input-group date' id='txtFf'>
         <input type='text' class="form-control input-xs" id="ff">
         <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
         </span>
        </div>
       </div>



      <div class="col-sm-2 col-xs-12" id="cbZona">
       <select class="form-control input-xs" id="txtZona"></select>
      </div>

      <div class="col-sm-2 col-xs-12" id="cbArea">
       <select class="form-control input-xs" id="txtArea" disabled>
       </select>
      </div>


      <div class="col-sm-4 ">

       <a class="btn btn-secondary btn-xs" id="btnNuevoInventario" onclick="buscarLista()"><span class=" icon-search"></span> Buscar </a>

       <a class="btn btn-secondary btn-xs" id="btnNuevoInventario" onclick="ImprimirLista()"><span class=" fa fa-print"></span> Imprimir </a>

      </div>


     </div><!-- ./ Tablero -->



     <div class="col-xs-12 col-sm-12" id="tbFormato1">


     </div>

    </div><!-- ./Form-horizontal -->
   </div><!-- ./ panel body-->
  </div><!-- ./ panel panel-default -->
 </div><!-- ./container -->


 <!-- JavaScript -->

 <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
 <script src="recursos/js/formatoInventario.js?t=<?=time()?>"></script>
 <script src="recursos/js/bootstrap/bootstrap.js" charset="utf-8"></script>
 <script src="recursos/js/moment.js" charset="utf-8"></script>
 <script src="recursos/js/es_moment.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>
 <script src="recursos/plugin/bootbox.min.js" charset="utf-8"></script>

 <script type="text/javascript">
 $(function () {
  $('#txtFi').datetimepicker({ format: 'YYYY-MM-DD',defaultDate: new Date()});
  $('#txtFf').datetimepicker({
   format: 'YYYY-MM-DD',
   defaultDate: new Date(),
   useCurrent: false
  });
  $("#txtFi").on("dp.change", function (e) {
   $('#txtFf').data("DateTimePicker").minDate(e.date);
  });
  $("#txtFf").on("dp.change", function (e) {
   $('#txtFi').data("DateTimePicker").maxDate(e.date);
  });
 });
</script>
</body>
</html>
