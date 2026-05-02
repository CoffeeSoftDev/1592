<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
 <?php include('stylesheet.php'); ?>
</head>
<body>
 <?php include_once('header.php'); ?>

 <div class="container-fluid">

  <div class="col-xs-12 col-sm-12">
   <div class="card format-doc">
    <div class="card-body">


     <ul class="nav nav-tabs">

      <li class="active" ><a class="text-info"
       data-toggle="tab" href="#ventas_tickets" ><strong><span class=" icon-print-4">Historial de tickets</span> </strong></a>
      </li>

      <li><a class="text-info"
       data-toggle="tab" href="#tab3" onclick="verHistorico()"><strong><span class=" icon-address-card-o"></span>Clientes </strong></a>
      </li>

<!--

      <li><a class="text-info"
       data-toggle="tab" href="#tab4" onclick="loading()"><strong><span class=" icon-box"></span>Productos </strong></a>
      </li>

      <li><a class="text-info"
       data-toggle="tab" href="#tab5" onclick="FormatoProduccion()"><strong><span class=" icon-box"></span> Destajo </strong></a>
      </li> -->

     </ul><!-- nav tabs -->


     <div class="tab-content">

      <div id="ventas_tickets" class="tab-pane fade in active">
       <div class="col-sm-7 col-xs-12">

        <div id="reportrange" class="input-ranger">
         <i class="fa fa-calendar"></i>&nbsp;
         <span></span>
         <i class="fa fa-caret-down"></i>
        </div>



        <div id="col-sm-3 col-xs-12"></div>
        <br>
        <div class="line" id="content-table"></div>
       </div>

       <div class="col-sm-5 col-xs-12" id="content-folio"></div>
      </div>
     </div>

    </div><!-- ./ body -->

    <?php include_once('footer.php'); ?>

     <script src="recursos/js/dia/ticket_ventas.js?t=<?=time()?>"></script>
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
      VerHistorialTicket(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
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
