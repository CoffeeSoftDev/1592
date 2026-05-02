$(document).ready(main);

function main() {
    //  verMesxMes();
    //  Comparar_Totales();
    init();

    setTimeout(function () {
        ingresos_anuales();
    }, 500);

}

/* ===============================
*       Formato
=================================*/
function verFormato() {
    var opc = $("#tipoReporte").val();
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();

    if (txtAnio1 > txtAnio2) {
        $("#ViewPanel").html(
            '<br><br><center><i class=" icon-calendar-outlilne fa-5x"></i>' +
                "<h4><span>El año debe ser menor para poder realizar la busqueda. </span></h4></center>"
        );
        $("#txtAnio1").focus();
    } else {
        switch (opc) {
            case "0":
                ingresos_anuales();
                break;
            //    case '1': Comparar_Totales(); break;
            //    case '2': verGraficas(1);     break;
            //    case '3': Comparar_Areas();   break;
            //    case '4': TOTALES_AREAS();    break;
            //    case '5': GraficosxAreas(1);  break;
        }
    }
}

/* ===============================
componentes
=================================*/
function init() {
    $.ajax({
        type: "POST",
        url: "controlador/direccion/_cbSELECT.php",
        data: "opc=1",
        success: function (rp) {
            var data = eval(rp);
            $("#cbAnio1").html(data[0]);
        },
    });

    $.ajax({
        type: "POST",
        url: "controlador/direccion/_cbSELECT.php",
        data: "opc=2",
        success: function (rp) {
            var data = eval(rp);
            $("#cbAnio2").html(data[0]);
        },
    });
}
/* ===============================
*    Reportes
=================================*/
function bloqueo(IdContainer) {
    $("#" + IdContainer).html(
        '<div class="row"><div class="col-sm-12 text-center">' +
            '<i class="d-block  fa fa-5x fa-calendar text-default"></i></div>' +
            '<div class="col-sm-12 text-center"><h4 class=""><p class="">Debes seleccionar un' +
            " intervalo de años para continuar...</p></h4></div></div>"
    );
}

function verMesxMes() {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();

    $.ajax({
        type: "POST",
        url: "controlador/direccion/verMesxMes2.php",
        data: "anio1=" + txtAnio1 + "&anio2=" + txtAnio2,
        beforeSend: function (send) {
            $("#ViewPanel").html(
                '<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                    "<h4><span>Cargando datos...</span></h4></center>"
            );
        },
        success: function (rp) {
            var data = eval(rp);
            $("#ViewPanel").html('<div id="tabsPane"></div>');
            // alert(data[1]);
            //---------------------------
            // $('#tbMESaMes').html(data[0]);
            // $('#tbFP').html(data[1]);
            // $('#tbPP').html(data[2]);
            // $('#tbTotal').html(data[3]);
            $("#tabsPane").html(data[4]);
        },
    });
}

function Comparar_Totales() {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();
    if (txtAnio1 == 0 || txtAnio2 == 0) {
        bloqueo("tbComparar");
        $("#txtAnio1").focus();
        $("#tbComparar2").html("");
    } else {
        $.ajax({
            type: "POST",
            url: "controlador/direccion/CompararTotales.php",
            data: "anio1=" + txtAnio1 + "&anio2=" + txtAnio2,
            beforeSend: function (send) {
                $("#ViewPanel").html(
                    '<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                        "<h4><span>Cargando datos...</span></h4></center>"
                );
            },
            success: function (rp) {
                var data = eval(rp);

                $("#ViewPanel").html(
                    '<div id="tbComparar"></div><div id="tbComparar2"></div>'
                );
                $("#tbComparar").html(data[0]);
                $("#tbComparar2").html(data[1]);
            },
        });
    }
}

function verGraficas(opc) {
    if (opc == 0) {
        enviar = parseInt($("#txtGrafica").val());
        GRAFICAS_TOTAL(enviar);
    } else {
        GRAFICAS_TOTAL(opc);
        $("#ViewTablero").html(
            '<div class="form-group row">' +
                '<div class="col-sm-2 col-sm-offset-10">' +
                '<select class="form-control input-xs" onchange="verGraficas(0)" id="txtGrafica">' +
                '<option value="1">VENTAS POR MES(BARRA) </option>' +
                '<option value="2">VENTAS POR AÑO</option>' +
                '<option value="3"> VENTAS POR MES</option></select>' +
                "</div></div><br>"
        );
    }
}

function GRAFICAS_TOTAL(opc) {
    var f1 = $("#txtAnio1").val();
    var f2 = $("#txtAnio2").val();

    $.ajax({
        type: "POST",
        url: "controlador/direccion/barraTotal.php",
        data: "f1=" + f1 + "&f2=" + f2 + "&opc=" + opc,
        beforeSend: function (send) {
            $("#ViewPanel").html(
                '<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                    "<h4><span>Cargando datos...</span></h4></center>"
            );
        },
        success: function (data) {
            $("#ViewPanel").html(
                '<div class="form-group row ">' +
                    '<div class="col-sm-12 text-center" id="ventasGraf' +
                    opc +
                    '" ></div>' +
                    "</div>"
            );

            switch (opc) {
                case 1:
                    $("#ventasGraf1").html(data);
                    loadChartJsPhp("bar1");
                    break;
                case 2:
                    $("#ventasGraf2").html(data);
                    loadChartJsPhp("bar2");
                    break;
                case 3:
                    $("#ventasGraf3").html(data);
                    loadChartJsPhp("bar3");
                    break;
                //
            }
        },

        error: function (data) {
            // console.log(data);
        },
    });

    // }// End else
}

function Comparar_Areas() {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();

    $.ajax({
        type: "POST",
        url: "controlador/direccion/CompararAreas.php",
        data: "anio1=" + txtAnio1 + "&anio2=" + txtAnio2,
        beforeSend: function (send) {
            $("#ViewPanel").html(
                '<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                    "<h4><span>Cargando datos...</span></h4></center>"
            );
        },
        success: function (rp) {
            var data = eval(rp);
            $("#ViewPanel").html(data[0]);
        },
    });
}

function TOTALES_AREAS() {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();
    $.ajax({
        type: "POST",
        url: "controlador/direccion/TotalesPorAreas.php",
        data: "anio1=" + txtAnio1 + "&anio2=" + txtAnio2,
        success: function (rp) {
            var data = eval(rp);
            $("#ViewPanel").html(data[0]);
        },
    });
}

function GraficosxAreas(opc) {
    if (opc == 0) {
        enviar = parseInt($("#txtGrafica").val());
        // GRAFICAS_TOTAL(enviar);
        if (enviar == 1) {
            GOOGLE();
        } else {
            if (enviar == 2) {
                div_pie();
            } else {
                TOTAL_AREAS();
            }
        }
    } else {
        // GRAFICAS_TOTAL(opc);

        $("#ViewTablero").html(
            '<div class="form-group row">' +
                '<div class="col-sm-2 col-sm-offset-10">' +
                '<select class="form-control input-xs" onchange="GraficosxAreas(0)" id="txtGrafica">' +
                '<option value="1"> TOTAL POR AÑOS </option>' +
                '<option value="2"> TOTAL ANUAL </option>' +
                '<option value="3"> TOTAL POR AREAS </option></select>' +
                '</div></div><br><div class="col-sm-12" id="chart_area"></div>'
        );
        GOOGLE();
        // PIE_ANUAL(2,"");
    }
}

function div_pie() {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();
    $.ajax({
        type: "POST",
        url: "controlador/direccion/chart_area_gral.php",
        data: "anio1=" + txtAnio1 + "&anio2=" + txtAnio2,
        beforeSend: function (send) {
            $("#ViewPanel").html(
                '<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                    "<h4><span>Cargando datos...</span></h4></center>"
            );
        },
        success: function (rp) {
            var data = eval(rp);

            $("#ViewPanel").html(data[0]);

            recorrido_anual(txtAnio1, txtAnio2);
        },
    });
}

function recorrido_anual(fi, ff) {
    for (var i = fi; i <= ff; i++) {
        PIE_ANUAL(2, i);
    }
}

/* ===============================
PESTAÑA GRAFICO X AREAS
=================================*/

// function verGraficoAreas() {
//  GRAFICO_AREAS(1);
//  // GRAFICO_AREAS(2);
//  PIE_ANUAL(1,"");
//  // GRAFICO_AREAS(3);
// }

// function GRAFICO_AREAS(opc){
//  txtAnio1 = $('#txtAnio1').val();
//  txtAnio2 = $('#txtAnio2').val();
//  if (txtAnio1==0 || txtAnio2==0) {
//   bloqueo('area1');
//   bloqueo('area2');
//
//  }else {
//   $.ajax({
//    type: "POST",
//    url: "controlador/direccion/GraficosxArea.php",
//    data:"anio1="+txtAnio1+"&anio2="+txtAnio2+"&opc="+opc,
//    success:function(data) {
//     switch (opc) {
//      case 1:
//      $('#area1').html(data);
//      loadChartJsPhp("a1");
//      break;
//      case 2:
//      $('#area2').html(data);
//      loadChartJsPhp("a2");
//      break;
//     }
//
//    }
//   });
//  }
//
// }

// google.charts.load('current', {packages: ['corechart']});
// google.charts.setOnLoadCallback();

function PIE_ANUAL(opc, f1) {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();
    var temp_title = "Del " + txtAnio1 + " al " + txtAnio2 + "";

    if (opc == 2) {
        txtAnio1 = f1;
        txtAnio2 = f1;
        temp_title = "TOTAL ANUAL " + txtAnio1;
    }

    $.ajax({
        type: "POST",
        url: "controlador/direccion/PieGoogleCHART.php",
        dataType: "JSON",
        data: "fi=" + txtAnio1 + "&ff=" + txtAnio2 + "&opc=" + opc,
        success: function (rp) {
            alert(rp);
            console.log(rp);
            div = "chart_area" + f1;
            drawMonthwiseChart(rp, temp_title, div);
        },
    });
}

function drawMonthwiseChart(chart_data, chart_main_title, div) {
    alert(chart_data + "" + chart_main_title + "" + div);
    var jsonData = chart_data;
    var data = new google.visualization.DataTable();

    data.addColumn("string", "Month");
    data.addColumn("number", "Profit");

    $.each(jsonData, function (i, jsonData) {
        var month = jsonData.month;
        var profit = parseFloat($.trim(jsonData.profit));

        data.addRows([[month, profit]]);
    });

    var options = {
        title: chart_main_title,
        width: 500,
        height: 260,
    };

    var chart = new google.visualization.PieChart(document.getElementById(div));
    chart.draw(data, options);
}

/*===========================================
*									BAR - TOTAL ANUAL
=============================================*/
google.charts.load("current", { packages: ["bar"] });

function GOOGLE() {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();

    $.ajax({
        type: "POST",
        url: "controlador/direccion/googleBar.php",
        dataType: "json",
        async: false,
        data: "fi=" + txtAnio1 + "&ff=" + txtAnio2 + "&opc=2",
        beforeSend: function (send) {
            $("#ViewPanel").html(
                '<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                    "<h4><span>Cargando datos...</span></h4></center>"
            );
        },
        success: function (rp) {
            console.log(rp);

            $("#ViewPanel").html('<div id="TotalAnual"></div>');

            var options = {
                chart: {
                    subtitle: "Sales, Expenses, and Profit: 2014-2017",
                },
                title: "TOTAL POR AÑOS",
                width: 900,
                height: 400,
                bars: "vertical", // Required for Material Bar Charts.
            };

            var data = new google.visualization.arrayToDataTable(rp);
            var chart = new google.visualization.ColumnChart(
                document.getElementById("TotalAnual")
            );
            chart.draw(data, google.charts.Bar.convertOptions(options));
        },
    });
}

function TOTAL_AREAS() {
    txtAnio1 = $("#txtAnio1").val();
    txtAnio2 = $("#txtAnio2").val();

    $.ajax({
        type: "POST",
        url: "controlador/direccion/googleBar.php",
        dataType: "json",
        async: false,
        data: "fi=" + txtAnio1 + "&ff=" + txtAnio2 + "&opc=1",
        beforeSend: function (send) {
            $("#ViewPanel").html(
                '<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                    "<h4><span>Cargando datos...</span></h4></center>"
            );
        },
        success: function (rp) {
            $("#ViewPanel").html('<div id="TotalAreasGraf"></div>');

            console.log(rp);
            var options = {
                chart: {
                    title: "Company Performance",
                    subtitle: "Sales, Expenses, and Profit: 2014-2017",
                },
                bars: "vertical",
            };

            var data = new google.visualization.arrayToDataTable(rp);
            var chart = new google.visualization.ColumnChart(
                document.getElementById("TotalAreasGraf")
            );
            chart.draw(data, google.charts.Bar.convertOptions(options));
        },
    });
}
