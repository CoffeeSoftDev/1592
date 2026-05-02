$(document).ready(loading);
names = [];

function loading() {
  ver_clientes();
  VerHistorialTicket();
  date_range();
}

/* ------------------------------------ */
/*      Pestaña historial de ventas     */
/* ------------------------------------ */
dialog = null;
function VerHistorialTicket() {

  date = range_date('reportrange');
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=1&f_i=" + fecha_i + "&f_f=" + fecha_f,
    beforeSend: function (rp) {
      $("#content-table").html(Load_sm());
    },
    success: function (rp) {
      data = eval(rp);
      $("#content-table").html(data[0]);
      simple_data_table("#viewFolios");
    }
  });
}

function verFolios(id, folio, cliente, fecha) {
  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=2&id=" + id + "&fol=" + folio + "&cliente=" + cliente + "&fecha=" + fecha,
    success: function (rp) {
      data = eval(rp);
      $("#content-folio").html(data[0]);
    }
  });
}

function Factura(id) {
  dialog = bootbox.dialog({
    title: "<i class='ico-2x lnr lnr-file-empty'></i> Facturar ",
    size: "small",
    message: '<div class="">' + getFormFactura(id) + " </div>" + '<div style="margin-top:10px;" class="text-right">' + '<i class="msj"></i><button class="btn btn-primary" onclick="GuardarFactura(' + id + ')">Guardar</button></div>'
  });

  dialog.init(function () {
    setTimeout(function () {
      lista_clientes();
      autocomplet_txt(id);
    }, 200);
  });
}

function GuardarFactura(id) {
  factura = $("#txtFactura" + id).val();
  total = $("#txtTotalTicket").val();

  if (factura) {
    $.ajax({
      type: "POST",
      url: "controlador/dia/ticket_ventas.php",
      data: "opc=8&factura=" + factura + "&Total=" + total + "&id=" + id,
      success: function (rp) {
        data = eval(rp);
        VerHistorialTicket();
        verFolios(id, '', '', '');
        modal_close(dialog);
      }
    });
  } else {

  }
}

modal_cancelar = null;
function CancelarFolio(id, Folio) {
  modal_cancelar = bootbox.dialog({
    title: " Cancelar venta # " + Folio + " ",
    // size: "small",
    message: '<div class=""> <span style="font-weight:500; font-size:1em;">¿Esta seguro de querer cancelar la venta seleccionada ?</span></div>' +
      '<div style="margin-top:30px;" class="text-right">' +
      '<button class="btn btn-primary" onclick="Cancelar_Venta(' + id + ')">Si, cancelar venta </button></div>'
  });
}

function Cancelar_Venta(id) {
  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=11&id=" + id,
    success: function (rp) {
      data = eval(rp);
      VerHistorialTicket();
      modal_close(modal_cancelar);
    }
  });
}

function lista_clientes() {
  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: 'opc=12',
    success: function (data) {
      res = eval(data);
      cont = res.length;
      for (var i = 0; i < cont; i++) {
        names[i] = res[i];
      }
    }
  });
}

/* ------------------------------------ */
/*           Pestaña clientes           */
/* ------------------------------------ */

frm = null

function ver_clientes() {

  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=3",
    beforeSend: function () {
      $("#content-clientes").html(Load_sm());

    },
    success: function (rp) {
      data = eval(rp);
      $("#content-clientes").html(data[0]);
      // single_data_table("#tb_data");
    }
  });
}

function detallar_cuenta(idC) {
  c = $("#Name_Cliente" + idC).val();
  t = $("#Telefono" + idC).val();
  c1 = $("#Email" + idC).val();

  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=5&idCliente=" + idC + "&c=" + c + "&t=" + t + "&c1=" + c1,
    success: function (rp) {
      data = eval(rp);
      $("#content-clientes").html(data[0]);
    }
  });
}

function formulario_abonos(id, idC, Saldo, opc) {
  frm = bootbox.dialog({
    title: "<i class='icon icon-calc ico-1x'></i> Abono a cuenta ",

    message: '<div class="form-group">' + getMonto(id, Saldo, opc) + '</div>' +
      '<div class="form-group"><div class="col-xs-12 col-sm-12 "> <div id="msj-alert"></div></div> ' +
      '<div style="margin-top:10px;" class="text-right">' +
      '<button class="btn btn-primary" id="btn-abono" onclick="Abono_ticket(' + id + ',' + idC + ')">Guardar</button></div>'
  });
}

function CalcularSaldo(id, Saldo) {
  monto = $("#txtMonto" + id).val();
  ob = $("#txtObservacion" + id);
  btn = $("#btn-abono");
  if (monto > Saldo) {
    ob.prop('disabled', true);
    btn.prop('disabled', true);
    $('#msj-alert').html('<p style="font-weigh:500"><i class="bx bx-check-circle ico-1x text-success"></i> El abono es mayor al saldo actual.</p>');
  } else {
    ob.prop('disabled', false);
    btn.prop('disabled', false);
    $('#msj-alert').html('');
  }

}

function Abono_ticket(idFactura, idC) {
  array = [
    "Monto" + idFactura,
    "Observacion" + idFactura
  ];
  ok = get_name_data(array);
  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=6&" + ok + "id=" + idFactura,
    success: function (rp) {
      detallar_cuenta(idC);
      frm.modal('hide');
    }
  });
}

function ver_abonos(id, Factura, Cliente, Importe) {

  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=10&idFactura=" + id + '&Factura=' + Factura + '&Cliente=' + Cliente + '&Importe=' + Importe,
    success: function (rp) {
      data = eval(rp);
      bootbox.dialog({
        title: "<i class='ico-2x lnr lnr-file-empty'></i> Detalles de abonos ",
        // size: "small",
        message: '<div class="">' + data[0] + '</div>' +
          '<div style="margin-top:10px;" class="text-right">' +
          ''
      });
    }
  });



}


/* ------------------------------------ */
/*           Pestaña facturas           */
/* ------------------------------------ */

function ver_facturas(fecha_i, fecha_f) {
  date = range_date('txtDateFactura');
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    type: "POST",
    url: "controlador/dia/ticket_ventas.php",
    data: "opc=7&f_i=" + fecha_i + "&f_f=" + fecha_f,
    beforeSend: function (rp) {
      $("#content-factura").html(Load_sm());
    },
    success: function (rp) {
      data = eval(rp);
      $("#content-factura").html(data[0]);
      export_data_table("#tbFacturas");
    }
  });
}

function subir_fichero(idFichero) {

  var archivos = document.getElementById("archivos" + idFichero);
  var archivo = archivos.files;
  cant_file = archivo.length;
  var filarch = new FormData();

  for (i = 0; i < archivo.length; i++) {
    filarch.append('archivo' + i, archivo[i]);
  }

  filarch.append('link', idFichero);
  filarch.append('opc', 9);
  filarch.append('files', cant_file);

  console.log(...filarch);

  $.ajax({
    url: 'controlador/dia/ticket_ventas.php',
    type: 'POST',
    contentType: false,
    data: filarch,
    processData: false,
    cache: false,
    beforeSend: function () {
      $('#txtLoad' + idFichero).html("<label class='text-success'><span class='icon-spin6 animate-spin'></span>subiendo...</label>");
    },
    success: function (rp) {
      // data = eval(rp);
      $('#txtLoad' + idFichero).html(rp);
    }
  });


  // }
  // else{
  // 	$('#load'+idFichero).html("<label class='text-danger'><span class='icon-cancel'></span>Seleccionar por lo menos un archivo</label>");
  // }

}

/* ------------------------------------ */
/*           Pestaña Reporte            */
/* ------------------------------------ */
function date_range() {
  var start = moment().subtract(7, 'days');
  var end = moment();

  $('#txtDateReporte').daterangepicker({
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

  $('#txtDateReporte span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
  $('.drp-buttons .cancelBtn').html('Cancelar');
  $('.drp-buttons .applyBtn ').html('Aceptar');
  $('.ranges li:last').html('Personalizado');

}

function ver_Reporte() {
  date = range_date('txtDateReporte');
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    type: "POST",
    url: "controlador/dia/reporte_ventas.php",
    data: "opc=1&f_i=" + fecha_i + "&f_f=" + fecha_f,
    beforeSend: function (rp) {
      $("#content-reporte").html(Load_sm());
    },
    success: function (rp) {
      data = eval(rp);
      $("#content-reporte").html(data[0]);
    
      left_and_right_fixed_table('#allVentas');
    }
  });

}



// Complementos **
function actualizar_datos(txt, id_table) {
  val = $("#" + txt + id_table).val();
  // _console(val);
  if (id_table != "") {
    $.ajax({
      type: "POST",
      url: "controlador/dia/ticket_ventas.php",
      data: "opc=4&txt=" + txt + "&id=" + id_table + "&val=" + val,
      beforeSend: function (rp) { // $('#content-form').html(Load_xs());
      },
      success: function (rp) {
        data = eval(rp);
        // $('#content-form').html('');
      }
    });
  } // end if
}

function activarCredito(val, id_table) {
  txt = 'credito_activo';
  if (id_table != "") {
    $.ajax({
      type: "POST",
      url: "controlador/dia/ticket_ventas.php",
      data: "opc=4&txt=" + txt + "&id=" + id_table + "&val=" + val,
      beforeSend: function (rp) { // $('#content-form').html(Load_xs());
      },
      success: function (rp) {
        ver_clientes();

      }
    });
  } // end if
}

function getMonto(id, Saldo, opc) {
  input = "<label>Saldo actual </label>";

  input += '<div class="input-group">';

  input += '<span class = "input-group-addon" ><i class="ico-2x icon-dollar"></i></span>';
  input += '<input id="txtSaldo' + id + '" type="number" class = "form-control" value="' + Saldo + '" disabled>';
  input += "  </div>";

  if (opc == 0) {
    input += '<label style="padding-top: 10px;"> Abono </label>';
    input += '<div  class="input-group">';
    input += '<span class = "input-group-addon" ><i class="ico-2x icon-dollar"></i></span>';
    input += '<input id="txtMonto' + id + '" type="number" class = "form-control" min="1" onkeyup="CalcularSaldo(' + id + ',' + Saldo + ')">';
    input += "  </div>";
  } else {
    input += '<label style="padding-top: 10px;"> Liquidar </label>';
    input += '<div  class="input-group">';
    input += '<span class = "input-group-addon" ><i class="ico-2x icon-dollar"></i></span>';
    input += '<input disabled value="' + Saldo + '" id="txtMonto' + id + '" type="number" class = "form-control" min="1" onkeyup="CalcularSaldo(' + id + ',' + Saldo + ')">';
    input += "  </div>";
  }

  input += '<div style="padding-top:10px;">';
  input += "<label>Observación</label>";
  input += '<textarea id="txtObservacion' + id + '" class = "form-control" ></textarea>';

  input += "  </div>";
  return input;
}

function getFormFactura(id) {
  input = '<div class="form-group">';
  input += "<label>Número de factura </label>";
  input += '<input id="txtFactura' + id + '" type="text" class = "form-control" >';
  input += "</div>";


  return input;
}

function range_date(id) {
  var startDate = $('#' + id).data('daterangepicker').startDate.format('YYYY-MM-DD');
  var endDate = $('#' + id).data('daterangepicker').endDate.format('YYYY-MM-DD');

  fi = startDate;
  ff = endDate;

  array = [fi, ff];

  return array;
}

function autocomplet_txt(id) {
  $("#txtFactura" + id).autocomplete({
    source: names
  });

  $('#txtFactura' + id).on("autocompleteclose", function (event, ui) {

  });
}

