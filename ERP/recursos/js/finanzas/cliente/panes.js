$(function () {
  Now();
  Saldos_Fondo();
  setTimeout(panel(1), 1000);
  // $('#addCont').html('');
});
/*-----------------------------------*/
/* Cuentas por cobrar ( CxC)
/*-----------------------------------*/
function cxc_view(){
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/cxc_view.php',
    data: 'date=' + $('#date').val()+'&opc=1',
    beforeSend:function(){
      $('.tab_content_subcategoria').html(Load_sm());
    },
    success: function (rp) {
    data = eval(rp);
      $('.tab_content_subcategoria').html(data[0]);
    }
  });
}

function actualizarPago(idBitacora){
  formas_pago =  $('#SelectFP'+idBitacora).val();
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/cxc_view.php',
    data: 'date=' + $('#date').val()+'&opc=2&idBitacora='+idBitacora+'&formas_pago='+formas_pago,
    beforeSend:function(){
      // $('.tab_content_subcategoria').html(Load_sm());
    },
    success: function (rp) {
    data = eval(rp);
    // cxc_view();
      // $('.tab_content_subcategoria').html(data[0]);
    }
  });
}

function actualizarFecha(idBitacora) {
    fechaPago = $('#fechaPago' + idBitacora).val();
    $.ajax({
        type: "POST",
        url: 'controlador/finanzas/cliente/cxc_view.php',
        data: 'date=' + $('#date').val() + '&opc=3&idBitacora=' + idBitacora + '&fechaPago=' + fechaPago,
        beforeSend: function () {
            // $('.tab_content_subcategoria').html(Load_sm());
        },
        success: function (rp) {
            data = eval(rp);
            // cxc_view();
            // $('.tab_content_subcategoria').html(data[0]);
        }
    });
}

function actualizarDatos(idBitacora) {
    observacion = $('#observacion' + idBitacora).val();
    $.ajax({
        type: "POST",
        url: 'controlador/finanzas/cliente/cxc_view.php',
        data: 'date=' + $('#date').val() + '&opc=4&idBitacora=' + idBitacora + '&observacion=' + observacion,
        beforeSend: function () {
            // $('.tab_content_subcategoria').html(Load_sm());
        },
        success: function (rp) {
            data = eval(rp);
            // cxc_view();
            // $('.tab_content_subcategoria').html(data[0]);
        }
    });
}

/*-----------------------------------*/
/* Modal CxC - Cobros
/*-----------------------------------*/
var cxcSeleccionado = null;

function abrirCobro(idFP, folio, descripcion, monto) {
  cxcSeleccionado = { idFP: idFP, folio: folio, descripcion: descripcion, monto: monto };

  var html = '<div style="display:flex; gap:30px; font-size:12px;">';
  html += '<div><span style="color:#9ca3af; font-size:11px;">Folio</span><br><strong style="color:#2563eb;">' + folio + '</strong></div>';
  html += '<div><span style="color:#9ca3af; font-size:11px;">Descripcion</span><br><span style="color:#374151;">' + descripcion + '</span></div>';
  html += '<div><span style="color:#9ca3af; font-size:11px;">Monto CxC</span><br><strong style="color:#1f2937;">' + monto + '</strong></div>';
  html += '</div>';

  $('#detalleCxcSeleccionado').html(html);
  $('#txtMontoCobro').val(parseFloat(monto.replace(/[$,]/g, '')));
  $('#txtObservacionCobro').val('');
  $('#modalCxC').fadeIn(200);
  $('body').css('overflow', 'hidden');
}

function cerrarModalCxC() {
  $('#modalCxC').fadeOut(200);
  $('body').css('overflow', 'auto');
  cxcSeleccionado = null;
}

function registrarCobroCxC() {
  if (!cxcSeleccionado) { alert('Seleccione un registro CxC'); return; }

  var montoCobro  = $('#txtMontoCobro').val();
  var formaPago   = $('#selectFormaPagoCobro').val();
  var fechaCobro  = $('#txtFechaCobro').val();
  var observacion = $('#txtObservacionCobro').val();

  if (!montoCobro || montoCobro <= 0) { alert('Ingrese un monto valido'); return; }
  if (!fechaCobro) { alert('Seleccione una fecha de cobro'); return; }

  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/cxc_view.php',
    data: 'opc=7&idFP=' + cxcSeleccionado.idFP + '&montoCobro=' + montoCobro + '&formaPago=' + formaPago + '&fechaCobro=' + fechaCobro + '&observacion=' + observacion,
    success: function (rp) {
      var data = eval(rp);
      if (data[0] == 'ok') {
        cerrarModalCxC();
        cxc_view();
      } else {
        alert('Error: ' + data[0]);
      }
    }
  });
}




/*-----------------------------------*/
/* Fondo udn
/*-----------------------------------*/
function Saldos_Fondo() {
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo.php',
    data: 'opc=0',
    success: function (data) {
      res = eval(data);
      // alert(data);
      $('#S_I_sobres').val(res[0]);
      $('#S_F_sobres').val(res[1]);
    }
  });
}

function oculto() {
  pane = $('#Ipt_Oculto').val();
  panel(parseInt(pane));
}

function panel(opc) {
  switch (opc) {
    case 1:
      Categorias();
      $('#Ipt_Oculto').val('1');
      break;
    case 2:
      Archivos();
      $('#Ipt_Oculto').val('2');
      break;
    case 3:
      Panel_compras();
      $('#Ipt_Oculto').val('3');
      break;
    case 4:
      Pagos();
      $('#Ipt_Oculto').val('4');
      break;
    case 5:
      Proveedor();
      $('#Ipt_Oculto').val('5');
      break;
    case 6:
      ver_Caratula_Clientes();
      $('#Ipt_Oculto').val('6');
      break;
    case 7:
      verCompras();
      $('#Ipt_Oculto').val('7');
      setTimeout('Select_Pagador(0);', 500);
      setTimeout('Select_Categoria(0);', 500);
      break;
    case 8:
      ver_cheques();
      $('#Ipt_Oculto').val('8');
      break;

    case 9:
      add_panel();
    $('#Ipt_Oculto').val('9');
      break;

  }
}

function tc_view() {
  $.ajax({
    type: "POST",
    url: 'vista/finanzas/cliente/tc_view.php',
    data: 'date=' + $('#date').val(),
    success: function (data) {
      $('.tab_content_subcategoria').html(data);
      ver_tc();
    }
  });
}

function ver_tc() {
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/pane_tc_c.php',
    data: 'opc=1&date=' + $('#date').val(),
    success: function (rp) {
      data = eval(rp);
      $('.tb_data').html(data[0]);
      $('#txt-Total').html(data[1]);
      $('#tbtc').DataTable({
        destroy: true,
        "searching": true,
        "pageLength": 20,
        "lengthChange": false

      });
    }
  });
}

/*--CHEQUES--*/
function ver_cheques() {
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/pane_cheques_v.php',
    data: 'opc=0',
    success: function (data) {
      $('.tab_content').html(data);
      cbDestino();
    }
  });
}
function cbDestino() {
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/pane_cheques_v.php',
    data: 'opc=6',
    success: function (data) {
      $('#cbDestino').html(data);
    }
  });
}

/* ---------------------------------- */
/* Reportes                           */
/* ---------------------------------- */

function control_reportes() {

  data = obtener_fechas('reportrange');

  fi = data[0];
  ff = data[1];

  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/reporte.gral.php',
    data: 'opc=0&fi=' + fi + '&ff=' + ff,
    beforeSend:function () {
      $('.content-report-1').html('Cargando...');

    },
    success: function (rp) {
      data = eval(rp);
      $('.content-report-1').html(data[0]);
      // complete_data_table('#reporte_gral',20);
    }
  });


}

function add_panel() {
  txt = '<div style="margin-top:10px;" class="row">';
  txt += '<div class="col-xs-6 col-sm-3">';
  txt += '<div id="reportrange" class="form-control input-sm">';
  txt += '<i class="fa fa-calendar"></i>&nbsp;';
  txt += '<span></span> <i class="fa fa-caret-down"></i>';
  txt += '</div>';
  txt += '</div>';


  txt += '<div class="col-sm-2"> <label style="margin-top:10px;" ></label>';
  txt += '<button class=" btn btn-primary btn-" onclick="control_reportes()">Buscar</button>';
  txt += '</div>';

  txt += '<div class="col-sm-2"> <label style="margin-top:10px;" ></label>';
  txt += '<button class=" btn btn-primary btn-" onclick="control_reportes()">Buscar</button>';
  txt += '</div>';


  txt += '</div>';

  txt += '<div class="content-report-1">';
  txt += '</div>';


  $('.tab_content').html(txt);
  fecha_calendario();
  // return txt;
}

function fecha_calendario() {
  $(function () {
    // var start = moment().subtract(29, 'days');
    var start = moment();
    var end = moment();
    function cb(start, end) {

      //  GRAL();
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
        'Año anterior': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
      }
    }, cb);

    cb(start, end);

  });
}

function obtener_fechas(id) {
  var startDate = $("#" + id).data("daterangepicker").startDate.format("YYYY-MM-DD");
  var endDate = $("#" + id).data("daterangepicker").endDate.format("YYYY-MM-DD");

  fi = startDate;
  ff = endDate;

  array = [fi, ff];
  return array;
}

/*-----------------------------------*/
/*       Compras
/*-----------------------------------*/
function verCompras() {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_compras_v.php',
    data: 'opc=3&date=' + $('#date').val(),
    success: function (data) {
      datx = eval(data);
      $('.tab_content').html(datx[0]);
      $('#Gastos').numeric();
      $('#Gastos').numeric(".");
      verGastoCompras();
      Select_Pagador(0);
      Select_Categoria(0);
    }
  });

}

/*-----------------------------------*/
/*		Caratula Ingresos
/*-----------------------------------*/
function ver_Caratula_Clientes() {
  valores = 'date=' + $('#date').val();
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/caratula_v.php',
    data: valores,
    beforeSend: function () {
      $('.tab_content').html("<h4 class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando datos...</h4>");
    },
    success: function (data) {
      $('.tab_content').html(data);
    }
  });
}

function Print_caratula_cliente() {
  myWindow = window.open("recursos/pdf/caratula_c.php?date=" + $('#date').val(), "_blank", "width=750, height=700");
}

function verCaratula() {
  valores = 'date1=' + $('#date').val() + '&date2=' + $('#date').val() + '&udn=1';
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/admin/CARATULA_GRAL.php',
    data: valores,
    beforeSend: function () { $('.tab_content').html("<h4><label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando datos...</label></h4>"); },
    success: function (data) {
      var datos = eval(data);
      $('.tab_content').html(datos[0]);
    }
  });
}

/*-----------------------------------*/
/*		Formato Gral
/*-----------------------------------*/

function GRAL() {

  // $('#table_data').addClass('hide');
  valores = 'date1=' + $('#date').val() + '&date2=' + $('#date').val() + '&udn=1';
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/admin/RESUMEN_GRAL.php',
    data: valores,
    beforeSend: function () {
      $('.tab_content_subcategoria').html(Load_sm());
    },
    success: function (data) {
      var datos = eval(data);
      $('.tab_content_subcategoria').html(datos[0]);
    }
  });

}

function Now() {

  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_ingresos_v.php',
    data: 'opc=5',
    success: function (data) {
      $('#date').val(data);
    }
  });

}

function Categorias() {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_ingresos_v.php',
    data: 'opc=0',
    success: function (data) {
      $('.tab_content').html(data);
      bgImagen();
    }
  });
}

function bgImagen() {
  $('.tab_content_subcategoria').html('<center><br><br><img src="recursos/img/logo_c.png" style=" max-width:30%; " class="img-res"></center>');
}

function Subcategoria(id) {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_ingresos_v.php',
    data: 'opc=1&id=' + id + '&date=' + $('#date').val(),
    success: function (data) {
      $('.tab_content_subcategoria').html(data);
    }
  });
}

function Archivos() {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_file_v.php',
    data: 'opc=0',
    success: function (data) {
      $('.tab_content').html(data);

      Select_tbarchivos(1);
    }
  });
}


function Select_tbarchivos(pag) {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_file_v.php',
    data: 'opc=1&pag=' + pag + '&date=' + $('#date').val(),
    success: function (data) {
      $('.tb_files').html(data);
    }
  });
}



function Panel_compras() {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_compras_v.php',
    data: 'opc=0&date=' + $('#date').val(),
    success: function (data) {
      $('.tab_content').html(data);
      //Inputs Numericos
      $('#Gastos').numeric();
      $('#Gastos').numeric(".");
      Select_Pagador(0);
      Select_Categoria(0);
    }
  });
}
function Select_Pagador(caso) {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_compras_v.php',
    data: 'opc=1&caso=' + caso,
    success: function (data) {
      $('.Pagador').html(data);
    }
  });
}
function Select_Categoria() {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_compras_v.php',
    data: 'opc=2',
    success: function (data) {
      $('.Categoria').html(data);
    }
  });
}

function Pagos() {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_pagos_v.php',
    data: 'opc=0&date=' + $('#date').val(),
    success: function (data) {
      $('.tab_content').html(data);
      $('#Pago').numeric();
      $('#Pago').numeric(".");
      Select_Pagador(1);
      Select_Categoria(1);
    }
  });
}

function Proveedor() {
  $.ajax({
    type: 'POST',
    url: 'controlador/finanzas/cliente/pane_proveedor_v.php',
    data: 'opc=0',
    success: function (data) {
      $('.tab_content').html(data);
      ver_proveedor(1);
      Saldos_Proveedor();
    }
  });
}

function Saldos_Proveedor() {
  date = $('#date').val();
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/pane_proveedor_v.php',
    data: 'opc=1&date=' + date,
    success: function (data) {
      res = eval(data);
      $('#SI_proveedor').val(res[0]);
      $('#SF_proveedor').val(res[1]);
    }
  });
}

function ver_proveedor(pag) {
  valores = 'pag=' + pag + '&date=' + $('#date').val();
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/tabla_proveedor.php',
    data: valores,
    success: function (data) {
      $('.tb_proveedor').html(data);
    }
  });
}

function modal_acceso() {
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo.php',
    data: 'opc=2',
    success: function (data) {
      $('.modal-content').html(data);
    }
  });
}

function Login_administracion() {
  valores = 'pass=' + $('#pass').val();
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo_c.php',
    data: 'opc=0&' + valores,
    success: function (data) {
      // alert(data);
      if (data == 1) {
        window.location = "retiros_rembolsos";
      }
      else {
        $('#Res_Finanzas').html('<label class="text-danger col-sm-12 col-xs-12 text-center"><span class="icon-attention"></span> Acceso no autorizado!</label>');
      }
    }
  });
}

/*===========================================
*									EDIT OBSERVACIONES INFORME GRAL
=============================================*/
function col(id, desc) {
  val = $('#lbldesc' + id).html();

  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/admin/modColx.php',
    data: 'id=' + id + '&txt=' + desc + '&Cant=' + val,

    success: function (respuesta) {
      var data = eval(respuesta);
      $('#txtdesc' + id).html(data[0]);
    }
  });

}

function _ENTER(id, txt) {

  var keyPressed = event.keyCode || event.which;
  if (keyPressed == 13) {
    keyPressed = null;
    Loadx(id, txt);
  }
  else {
    return false;
  }
}

function Loadx(id, txt) {
  Cant = $('#Editdesc' + id).val();
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/admin/Loadx.php',
    data: 'Cant=' + Cant + '&id=' + id + '&txt=' + txt,
    success: function (respuesta) {
      var data = eval(respuesta);
      $('#txt' + txt + id).html(data[0]);

    }
  });
}


function form_sub(id) {
  form =
    '<form>'
    + '<div class="form-group">'
    + '<label>Subcategoria</label>' + '<input onInput="Existe(' + id + ')"  type="text" class="form-control" id="txtSub"' + ' placeholder="" ><b><span class="" id="txtExiste"></span></b></div>'
    + '<div class="form-group">'
    + '<a style="width:100%" id="btnAgregar" class="btn btn-success btn-sm" onclick="insertar_sub(' + id + ')">Guardar</a>'
    + '</div>'
    + '</form>';



  return form;
}
/*-----------------------------------*/
/* Imprimir caratula gral
/*-----------------------------------*/

var dialog = null;

function Cierre_Dialogo(idFolio) {
  dialog = bootbox.dialog({
    title: "<i class='bx bx-calendar-alt'></i> CIERRE DIARIO",
    message: '<H1>'
  });

  dialog.init(function () {

    setTimeout(function () {
      formulario_acceso(idFolio);
    }, 50);
  });

}

function formulario_acceso(id) {

  date = $('#date').val();
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=8&idFolio=' + id + '&date=' + date,
    success: function (rp) {
      data = eval(rp);
      $('.bootbox-body').html(data[0]);
    }
  });

}

function view_motivo(id, opc) {
  if (opc == 1) { // ver motivo
    $('#content-btn' + id).addClass('hide');
    $('#content-motivo' + id).removeClass('hide');
  } else { // grupo de botones
    $('#content-motivo' + id).addClass('hide');
    $('#content-btn' + id).removeClass('hide');

  }
}


function agregar_motivo(id, idCheck) {
  motivo = $('#file_motivo' + id).val();
  fecha = $('#date').val();

  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=9&motivo=' + motivo + '&date=' + fecha + '&idCheck=' + id,
    success: function (rp) {
      $('#load' + id).html('Espere ...');
      formulario_acceso();
    }
  });

}

function subir_fichero(idFichero, id) {

  var archivos = document.getElementById("archivos" + idFichero);
  var archivo = archivos.files;
  cant_fotos = archivo.length;
  var filarch = new FormData();

  for (i = 0; i < archivo.length; i++) {
    filarch.append('archivo' + i, archivo[i]);
  }
  filarch.append('date', $('#date').val());
  filarch.append('Detalles', 'fichero');
  filarch.append('link', idFichero);

  console.log(...filarch);

  $.ajax({
    url: 'controlador/finanzas/cliente/pane_file_c.php',
    type: 'POST',
    contentType: false,
    data: filarch,
    processData: false,
    cache: false,
    beforeSend: function () {
      $('#txtLoad' + idFichero).html("<label class='text-success'><span class='icon-spin6 animate-spin'></span>subiendo...</label>");
    },
    success: function (data) {
      formulario_acceso();
      $('#load' + idFichero).html(data);
    }
  });


  // }
  // else{
  // 	$('#load'+idFichero).html("<label class='text-danger'><span class='icon-cancel'></span>Seleccionar por lo menos un archivo</label>");
  // }

}

function CierreHotel(idFolio) {
  usuario = $('#txtCargo').val();
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=6&usuario=' + usuario + '&id=' + idFolio,
    success: function (rp) {
      GRAL();
      setTimeout('car_print();', 800);

    }
  });
}

function car_print() {
  var divToPrint = document.getElementById('resumen_gral');
  var html = '<html>' +
    '<head>' +
    '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">' +
    '<body onload="window.print(); window.close(); cerrarModal(); ">' + divToPrint.innerHTML + '</body>' +
    '</html>';


  var popupWin = window.open();
  popupWin.document.open();
  popupWin.document.write(html);
  popupWin.document.close();
}

function cerrarModal() {
  dialog.modal('hide');
}

/*-----------------------------------*/
/* Tarifa - Sub
/*-----------------------------------*/
function sub_tarifa(idC, idS, val) {
  $('#precio' + idC + '-' + idS).html('<input value="' + val + '" autofocus id="txtPrecio' + idC + '-' + idS + '" type="text" class="form-control input-xs" onkeypress="if(event.keyCode == 13) Edit_sub(' + idC + ',' + idS + ');">');
}

function Edit_sub(idC, idS) {
  Precio = $('#txtPrecio' + idC + '-' + idS).val();

  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=7&idC=' + idC + '&idS=' + idS + '&Precio=' + Precio,
    success: function (rp) {
      $('#precio' + idC + '-' + idS).html(rp);
    }
  });

}
