/*-----------------------------------*/
/*		Complementos **
/*-----------------------------------*/
function get_data(multiple, return_name) {
  url = "";

  for (var i = 0; i < multiple.length; i++) {
    url = url + return_name + i + "=" + $("#txt" + multiple[i]).val() + "&";
  }
  return url;
}

function get_name_data(multiple) {
  url = "";

  for (var i = 0; i < multiple.length; i++) {
    url = url + multiple[i] + "=" + $("#txt" + multiple[i]).val() + "&";
  }
  return url;
}

function Load_sm() {
  load =
    '<div style="padding-top:130px;"><center><h2><i class="fa fa-spinner fa-pulse fa-fw text-success"></i> Cargando datos...</h2><center></div>';
  return load;
}

function Load(txt) {
  load =
    '<div style="padding-top:80px;"><center><h2><i class="fa fa-spinner fa-pulse fa-fw text-success"></i> ' +
    txt +
    "</h2><center></div>";
  return load;
}

function Load_xs(txt) {
  load =
    '<div><h4><i class="fa fa-spinner fa-pulse fa-fw text-success"></i> ' +
    txt +
    "</h4></div>";
  return load;
}

function set_btn_form() {
  load =
    '<br><a  id="btn-formato" class="btn btn-info " onclick="crear()">Nuevo Formato</a>';
  return load;
}

function bx_load_xs() {
  load = "<i class='bx bx-loader-circle bx-spin' style='color:#20ff43'></i>";
  return load;
}

/* ------------------------------------ */
/*          tables y datatables         */
/* ------------------------------------ */

function simple_data_table(table) {
  $(table).DataTable({
    pageLength: 9,
    // showNEntries: false,
    searching: true,
    bLengthChange: false,
    bFilter: false,
    "order": [],
    bInfo: false,
    "oLanguage": {
      "sSearch": "Buscar:",
      "sInfo": "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
      "sLoadingRecords": "Por favor espere - cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Ãšltimo",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      }

    }
  });
}

function single_data_table(table) {
  $(table).DataTable({
    pageLength: 15,
    showNEntries: false,
    searching: false,
    bLengthChange: false,
    bFilter: false,
    bInfo: true,
    ordering: false,
    "order": [],
    "oLanguage": {
      "sSearch": "Buscar:",
      "sInfo": "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
      "sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
      "sLoadingRecords": "Por favor espere - cargando...",
      "oPaginate": {
        "sFirst": "Primero",
        "sLast": "Ãšltimo",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      }

    }
  });
}

function export_data_table(table) {
  $(table).DataTable({
    destroy: true,
    dom: 'Bfrtip',

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
        "sLast": "Ãšltimo",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      }

    }
  });
}

function left_and_right_fixed_table(table) {
  $(table).DataTable({
    destroy: true,
    dom: 'Bfrtip',
    scrollY: "400px",
    scrollX: true,
    scrollCollapse: true,
    paging: false,
    fixedColumns: {
      leftColumns: 1,
      rightColumns: 1
    },


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
        "sLast": "Ãšltimo",
        "sNext": "Siguiente",
        "sPrevious": "Anterior"
      }

    }
  });
}


/* ------------------------------------ */
/*                 Print                */
/* ------------------------------------ */

function print_f1(element_by_id) {
  var divToPrint = document.getElementById(element_by_id);
  var html =
    "<html>" +
    "<head>" +
    // '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">'+
    '<link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.min.css">' +
    '<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">' +
    ' <link rel="stylesheet" href="recursos/css/disponibilidad_print.css"/>' +
    '<body onload="window.print(); ">' +
    divToPrint.innerHTML +
    "</body>" +
    "</html>";
  //  window.close();

  var popupWin = window.open();
  popupWin.document.open();
  popupWin.document.write(html);
  popupWin.document.close();
}

function print_formato(element_by_id) {

  var divToPrint = document.getElementById(element_by_id);
  var html =
    "<html>" +
    "<head>" +
    '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">' +
    '<link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.min.css">' +
    '<link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">' +

    '<style type="text/css" media="print"> @page{  margin-top:  20px;' +
    "margin-bottom:   20px;" +
    "margin-left:   20px;" +
    "margin-right:    30px;" +
    "} </style>" +
    '<body onload="window.print(); ">' +
    divToPrint.innerHTML +
    "</body>" +
    "</html>";
  //  window.close();

  var popupWin = window.open();
  popupWin.document.open();
  popupWin.document.write(html);
  popupWin.document.close();
}

function fecha_agenda() {
  $(".calendariopicker").datetimepicker({
    format: "YYYY-MM-DD",
    useCurrent: false,
    defaultDate: new Date(),
    widgetPositioning: {
      horizontal: "right",
      vertical: "bottom"
    }
  });
}

function default_img(txt) {
  img =
    '<div style="padding-top:40px;padding-bottom:40px;" class="col-sm-12 col-xs-12">';
  img += "<center>";
  img += '<img width="260px" src="recursos/img/' + txt + '.png">';
  img += "</center>";
  img += "</div>";
  return img;
}

function bootbox_msj_null(title, msj) {
  var dialog = bootbox.dialog({
    title: title,
    // size: 'small',
    message:
      '<div class="row"><center>' +
      '<div style="padding:30px;" class=" col-xs-12 col-sm-12">' +
      '<span style="font-size:1.2em;">El campo <b>' +
      msj +
      "</b> no puede quedar vacio. <span>" +
      "</div><center></div>"
  });
}

function _console(txt) {
  $(".console").html(txt);
}

function dataReturn(arreglo) {
  var data = new FormData();

  for (var i = 0; i < array.length; i++) {
    console.log("#txt" + array[i]);
    data.append(array[i], $("#txt" + array[i]).val());
  }

  return data;
}

function modal_close(d) {
  d.modal("hide");
}
