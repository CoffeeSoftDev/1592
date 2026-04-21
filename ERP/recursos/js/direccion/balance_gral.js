$(document).ready(loading);

function loading() {
  date_range("txtFecha");

//   mostrar_tendencias();
//   ventas_pastel();
}

/* ------------------------------------ */
/* Reporte conta                     */
/* ------------------------------------ */
function Contable() {
  FechaPromedio = $('#OcupacionCB').val();

  $.ajax({
    url: "controlador/direccion/tendencias.php",
    type: "POST",
    data: "opc=5&FechaPromedio=" + FechaPromedio,
    beforeSend: function () {
      $('.reporte_gral').html(loader('Cargando...'));
    },
    success: function (r) {
      data = eval(r);
      $('.reporte_gral').html(data[0]);
      // export_data_table('#ChequePromedio', 40);
    },
    error: function (jqXHR, exception) {
// reporte_gral

     }
  });

}


/* ------------------------------------ */
/* Cheque promedio                     */
/* ------------------------------------ */

function ChequePromedio(){
    FechaPromedio   =  $('#txtFechaPromedio').val();

    $.ajax({
    url: "controlador/direccion/tendencias.php",
    type: "POST",
    data: "opc=4&FechaPromedio=" + FechaPromedio ,
    beforeSend:function(){
        $('.content-cheque-promedio').html(loader('Cargando...'));
    },
    success: function (r) {
     data = eval(r);
     $('.content-cheque-promedio').html(data[0]);
      export_data_table('#ChequePromedio', 40);
    },
    error: function (jqXHR, exception) {
    }
  });
}

/* ------------------------------------ */
/* Pestaña Ingresos diarios             */
/* ------------------------------------ */
function mostrar_tendencias() {
  date = obtener_fechas("txtFecha");
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    url: "controlador/direccion/tendencias.php",
    type: "POST",
    data: "opc=1&date1=" + fecha_i + "&date2=" + fecha_f,
    beforeSend: function () {
    //   $(".content-tendencias").html(Load_sm("Cargando datos..."));
    },
    success: function (r) {
      data = eval(r);
      $(".content-tendencias").html(data[0]);
      ventas_pastel();
    },
    error: function (jqXHR, exception) {
      $(".content-tendencias").html("Error de conexión -> " + jqXHR.status);
    }
  });
}

function ventas_pastel() {
  date = obtener_fechas("txtFecha");
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    url: "controlador/direccion/tendencias.php",
    type: "POST",
    dataType:"JSON",
    data: "opc=2&date1=" + fecha_i + "&date2=" + fecha_f,
    beforeSend:function(){
        $('#chart_area').html();
    },
    success: function (r) {
        div = 'chart_area';
        drawMonthwiseChart(r, 'VENTAS '+fecha_i+' - '+fecha_f,div);

    },
    error: function (jqXHR, exception) {
      $(".content-tendencias").html("Error de conexión -> " + jqXHR.status);
    }
  });
}

/* ------------------------------------ */
/* Pestaña Tendencias              */
/* ------------------------------------ */

function Tendencias() {
//   date = obtener_fechas("txtFecha");
//   fecha_i = date[0];
//   fecha_f = date[1];

  input_anio = $('#txtAnio2').val();
  $.ajax({
    url: "controlador/direccion/tendencias.php",
    type: "POST",
    data: "opc=3&select_anio=" + input_anio,
    beforeSend: function () {
        $(".content-mes").html('Cargando...');
    //   $(".content-mes").html(Load_sm("Cargando datos..."));
    },
    success: function (r) {
      data = eval(r);
     $(".content-mes").html(data[0]);


    //   loadChartJsPhp("bar1");
    //   loadChartJsPhp("bar2");
    //   loadChartJsPhp("bar3");
    //   loadChartJsPhp("bar4");
    //   loadChartJsPhp("bar5");
    //   loadChartJsPhp("bar6");
    //   loadChartJsPhp("bar7");
    //   loadChartJsPhp("bar8");

      export_d("#IngresosMes");

    },
    error: function (jqXHR, exception) {
      $(".content-tendencias").html("Error de conexión -> " + jqXHR.status);
    }
  });
}


function loader(txt) {
  load =
    '<div style="padding-top:130px;"><center><h2><i class="icon-spin5 animate-spin text-success"></i> ' + txt + ' </h2><center></div>';
  return load;
}


function drawMonthwiseChart(chart_data, chart_main_title,div){
//  alert(chart_data+ ''+ chart_main_title +'' +div );
 var jsonData = chart_data;
 var data = new google.visualization.DataTable();

 data.addColumn('string', 'Month');
 data.addColumn('number', 'Profit');

 $.each(jsonData, function(i, jsonData){
  var month = jsonData.month;
  var profit = parseFloat($.trim(jsonData.profit));

  data.addRows([[month, profit]]);
 });

 var options = {
  title:chart_main_title,
  width:500,
  height:460
 };

 var chart = new google.visualization.PieChart(document.getElementById(div));
 chart.draw(data, options);
}

function date_range(input) {
  var start = moment().startOf('month');
  var end = moment();

  $('#' + input).daterangepicker({
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

  }, select);

  select(start, end);
}

function select(start, end) {
  $("#txtFecha span").html(start.format("YYYY-MM-DD") + " - " + end.format("YYYY-MM-DD"));
  $(".drp-buttons .cancelBtn").html("Cancelar");
  $(".drp-buttons .applyBtn ").html("Aceptar");
  $(".ranges li:last").html("Personalizado");
}

function obtener_fechas(id) {
  var startDate = $("#" + id).data("daterangepicker").startDate.format("YYYY-MM-DD");
  var endDate = $("#" + id).data("daterangepicker").endDate.format("YYYY-MM-DD");

  fi = startDate;
  ff = endDate;

  array = [fi, ff];

  return array;
}

function export_d(table) {
  $(table).DataTable({
    destroy: true,
    dom: 'Bfrtip',
    bFilter: false,
    ordering: false,
     "order": [],
    buttons: [
      'copy', 'excel'
    ],
    "oLanguage": {
      "sSearch": "Buscar:",
      "sInfo": "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
      "sLoadingRecords": "Por favor espere - cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Ultimo",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      }

    }
  });
}

function unfold_fold(id) {

  $(".unfold" + id).toggleClass("hide");
  $(".ico" + id).toggleClass("bx bx-caret-right");
  $(".ico" + id).toggleClass("bx bx-caret-down");
}
