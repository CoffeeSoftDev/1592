<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>
<title>Informe de ventas</title>
 <!-- Data tables -->
<link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.2.5/css/fixedColumns.bootstrap4.min.css"/>

 <?php include('stylesheet.php'); ?>
 <link rel="stylesheet" href="recursos/css/ui_invoice.css" />
 
 <!-- Boxicons CSS -->
 <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.4/css/boxicons.min.css' rel='stylesheet'>

  <!-- Autocompleter -->
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!--  JavaScript -->
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <style>
  body{
  zoom: 100%;

  }
  .ui-autocomplete {
    max-height: 100px;
    overflow-y: auto;
    overflow-x: hidden;
  }

  * html .ui-autocomplete {
    height: 100px;
  }
  .ui-autocomplete {
  z-index: 2147483647;
 }
  </style>

</head>

<body>
 <?php include_once('header.php'); ?>

 <div class="container-fluid">

  <div class="format-app">
   <div class="card format-doc">
    <div class="card-body">

   
     <ul class="nav nav-tabs">

      <li class="active"><a onclick="VerHistorialTicket()" data-toggle="tab" href="#ventas_tickets"><strong><span
          class=" icon-print-4">Historial de ventas</span> </strong></a>
      </li>

      <li><a class="text-info" data-toggle="tab" href="#clientes" onclick="ver_clientes()"><strong><span
          class=" icon-address-card-o"></span> Clientes
        </strong></a>
      </li>

      <li><a onclick="ver_facturas()" class="text-info" data-toggle="tab" href="#factura" onclick=""><strong>
         <span class="lnr lnr-book"></span> Facturas </strong></a>
      </li>

       <li><a onclick="ver_Reporte()" class="text-info" data-toggle="tab" href="#Reporte" onclick=""><strong>
         <span class="lnr lnr-calendar-full"></span> Reporte </strong></a>
      </li>

     </ul><!-- nav tabs -->


     <div class="tab-content">

      <div id="ventas_tickets" class="tab-pane fade in active">
       <div class=" col-sm-6 col-xs-12">

        <div id="reportrange" class="input-ranger">
         <i class="fa fa-calendar"></i>&nbsp;
         <span></span>
         <i class="fa fa-caret-down"></i>
        </div>


        <div id="col-sm-3 col-xs-12"></div>
        <br>
        <div class="" id="content-table"></div>
       </div>

       <div class="col-sm-6 col-xs-12" id="content-folio"></div>
      </div><!-- ./-->

      <div id="clientes" class="tab-pane fade">

       <div class="form-group col-sm-12 col-xs-12 text-right">
        <button class="btn btn-primary hide"><span class="lnr lnr-user"></span> Nuevo
         Cliente</button>
       </div>
       <div class="col-sm-12 col-xs-12" id="content-clientes"></div>
      </div>

      <div id="factura" class="tab-pane fade">
       <div id="txtDateFactura" class=" input-ranger">
        <i class="fa fa-calendar"></i>&nbsp;
        <span></span>
        <i class="fa fa-caret-down"></i>
       </div>
       <br>
       <div class="" id="content-factura"></div>
      </div>

      <div id="Reporte" class="tab-pane fade">
        <div id="txtDateReporte" class="hide input-ranger">
         <i class="fa fa-calendar"></i>&nbsp;
         <span></span>
         <i class="fa fa-caret-down"></i>
        </div>

       <div style="margin-top:10px;" class="" id="content-reporte"></div>

      </div>

     </div><!-- tab-content -->

    </div><!-- ./ body -->




    <?php include_once('footer.php'); ?>
    <!-- Json Form -->
    <!-- <script src="recursos/plugin/jsonform/jquery-jsonform.js"></script> -->
   
    <script type="text/javascript">
    $(function() {
     var start = moment().startOf('month');
     var end   = moment().endOf('month');

     function cb(start, end) {
      $('#txtDateFactura span').html(start.format('YYYY-MM-DD') + ' - ' + end.format(
       'YYYY-MM-DD'));


      $('.drp-buttons .cancelBtn').html('Cancelar');
      $('.drp-buttons .applyBtn ').html('Aceptar');
      $('.ranges li:last').html('Personalizado');
      ver_facturas(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
     }


     $('#txtDateFactura').daterangepicker({
      "showDropdowns": true,
      startDate: start,
      endDate: end,
      cancelClass: "btn-danger",
      applyClass: "btn-success",
      ranges: {
       'Hoy': [moment(), moment()],
       'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
       'Última Semana': [moment().subtract(6, 'days'), moment()],
      
       'Mes actual': [moment().startOf('month'), moment().endOf('month')],
       'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment()
        .subtract(1, 'month').endOf(
         'month')
       ],
       'Año actual': [moment().startOf('year'), moment().endOf('year')],
       'Año anterior': [moment().subtract(1, 'year').startOf('year'), moment()
        .subtract(1, 'year').endOf('year')
       ]
      },

     }, cb);

     cb(start, end);

 
    });
    </script>

    <script type="text/javascript">
    $(function() {
     var start = moment().subtract(29, 'days');
     //  var start = moment();
     var end = moment();

     function cb(start, end) {
      $('#reportrange span').html(start.format('YYYY-MM-DD') + ' - ' + end.format(
       'YYYY-MM-DD'));
      $('.drp-buttons .cancelBtn').html('Cancelar');
      $('.drp-buttons .applyBtn ').html('Aceptar');
      $('.ranges li:last').html('Personalizado');
      // VerHistorialTicket(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
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
       'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment()
        .subtract(1, 'month').endOf(
         'month')
       ],
       'Año actual': [moment().startOf('year'), moment().endOf('year')],
       'Año anterior': [moment().subtract(1, 'year').startOf('year'), moment()
        .subtract(1, 'year').endOf('year')
       ]
      },

     }, cb);

     cb(start, end);

     var data = {};
     $("#productos option").each(function(i, el) {
      data[$(el).data("value")] = $(el).val();
     });



    
    });
    </script>

<script src="recursos/js/dia/ticket_ventas.js?t=<?=time()?>"></script>
</body>

</html>