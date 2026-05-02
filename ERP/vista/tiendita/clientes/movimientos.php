<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Movimientos.php');
$obj = new Movimientos;
$nivel     = $_SESSION['nivel'];
if ($nivel == 10) {


 ?>
 <!DOCTYPE html>
 <html lang="es" dir="ltr">
 <head>
  <?php include('stylesheet.php'); ?>
  <!--  dataTables -->
  <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">
  <!-- <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'> -->
  <link rel="stylesheet" href="recursos/css/tpv/card.css"/>
  <link rel="stylesheet" href="recursos/css/argovia_base.css"/>
  <!-- <link rel="shortcut icon" href="http://www.argovia.com.mx/img/favicon.ico"> -->
 </head>
 <body>
  <?php include_once('header.php'); ?>
  <div class="container">
   <div class="col-sm-12">
    <div class="card content-pane" style="width: 100%;">
     <div class="card-body">
      <!-- <div class="form-group col-sm-3">

     </div> -->
     <div class="container">
       <div class="row">
         <div class="form-group col-sm-5 col-xs-12 gb_Producto">
           <label class="control-label" for="ipt_Producto">Producto</label>
           <input id="ipt_Producto"  class="form-control input-sm" list="productos" name="productos" onblur="Buscar_Datos();">
           <datalist id="productos" >
           <?php
             $sql = $obj->Select_ListaNameProductos();
             foreach ($sql as $value) {
               echo '<option data-value="'.$value[0].'" value="'.$value[1].' ('.$value[2].')" ></option>';
             }
           ?>
           </datalist>

         </div>
         <div class="form-group col-sm-2 col-xs-12">
           <label class="control-label" for="ipt_Producto">Disponible</label>
           <input type="text" Class="form-control input-sm text-right" id="disponible" placeholder="0" disabled>
         </div>
         <div class="form-group col-sm-2 col-xs-12">
           <label class="control-label" for="ipt_Producto">Estatus</label>
           <button type="button" class="btn btn-xs col-sm-12 col-xs-12" disabled id="status" style="opacity:1;"><span style="font-size:1.2em;" class="icon-ok-circled-1 text-success"></span></button>
         </div>
         <div class="form-group col-sm-3 col-xs-12">
           <label>Fecha de consulta</label>
           <div id="reportrange" class="form-control input-sm">
             <i class="icon-calendar"></i>&nbsp;
             <span></span><i class="icon-dir-down"></i>
           </div>
         </div>

         <div class="tb_movimientos"></div>
       </div>
     </div>


    </div><!--./ card body -->
   </div>
  </div>
 </div>

 <!-- JavaScript -->
 <script src = "recursos/js/jquery-1.12.3.js"></script>
 <script src = "recursos/js/jquery.numeric.js"></script>
 <script src="recursos/js/bootstrap.min.js"></script>

 <!-- dataTables-->
 <!-- DataTables -->
 <link rel="stylesheet"  href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
 <link rel="stylesheet"  href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">
 <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" charset="utf-8"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"      charset="utf-8"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"      charset="utf-8"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"       charset="utf-8"></script>
 <script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"      ></script>
 <script src = "recursos/js/complementos.js"></script>



 <script src="recursos/js/dia/movimientos.js?t=<?=time()?>" charset="utf-8"></script>

 <!-- autocomplete -->
  <script src="recursos/autocomplete/jquery-ui.js" charset="utf-8"></script>
  <link rel="stylesheet" href="recursos/autocomplete/jquery-ui.css">

 <!-- data Ranger Picker -->
 <script type="text/javascript" src="recursos/range_calendar/moment.min.js"></script>
 <script type="text/javascript" src="recursos/range_calendar/daterangepicker.min.js"></script>
 <link rel="stylesheet" type="text/css" href="recursos/range_calendar/daterangepicker.css" />

 <script type="text/javascript">
 $(function() {
   // var start = moment().subtract(29, 'days');
   var start = moment();
   var end   = moment();
   function cb(start, end) {
     $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
     $('.drp-buttons .cancelBtn').html('Cancelar');
     $('.drp-buttons .applyBtn ').html('Aceptar');
     $('.ranges li:last').html('Personalizado');
     tb_movimientos();
   }

   $('#reportrange').daterangepicker({
     "showDropdowns": true,
     startDate: start,
     endDate: end,
     cancelClass: "btn-danger",
     applyClass: "btn-success",
     ranges: {
       'Hoy': [moment(), moment()],
       'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Última Semana': [moment().subtract(6, 'days'), moment()],
       //    'Ultimo 30 dias': [moment().subtract(29, 'days'), moment()],
       'Mes actual': [moment().startOf('month'), moment().endOf('month')],
       'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
       'Año actual': [moment().startOf('year'), moment().endOf('year')],
       'Año anterior': [moment().subtract(1, 'year').startOf('year'),moment().subtract(1, 'year').endOf('year')]
     },

   }, cb);

   cb(start, end);

   var data = {};
   $("#productos option").each(function(i,el) {
     data[$(el).data("value")] = $(el).val();
   });



   // $('#submit').click(function()
   // {
   //   var value = $('#ipt_Producto').val();
   //   var valor = $('#productos [value="' + value + '"]').data('value');
   //   alert(valor);
   // });

 });
 </script>

</body>
</html>
<?php
}
?>
