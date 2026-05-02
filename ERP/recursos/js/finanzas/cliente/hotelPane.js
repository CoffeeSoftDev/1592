/*-----------------------------------*/
/*	Registro y consulta de clientes
/*-----------------------------------*/
dialog_huesped = null;
function NuevoHuesped(idOcupacion) {

  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=10&idOcupacion=' + idOcupacion,
    success: function (rp) {


      dialog_huesped = bootbox.dialog({
        title: '<strong><i class="bx bx-calendar"></i> Agregar huesped </strong> ',
        message: rp
      });

      setTimeout(function () {
        date_dual('.calendarioEntrada', '.calendarioSalida');
        select2_clientes();
      }, 400);

    }
  });

}

function AgregarHuesped(idOcupacion) {
  huesped     = $('#txtHuesped').val();
  entrada     = $('#txtEntrada').val();
  salida      = $('#txtSalida').val();
  observacion = $('#txtObservacion').val();
  
  if (huesped == '') {
    alert('El nombre del huesped no puede quedar vacio.');
  } else { 

    $.ajax({
      type: "POST",
      url: "controlador/finanzas/cliente/argovia_mod.php",
      data: "opc=11&huesped=" + huesped + '&entrada=' + entrada + '&salida=' + salida + '&id=' + idOcupacion + '&observacion=' + observacion,

      success: function (rp) {
        // data = eval(rp);
        cerrar_Modal(dialog_huesped);
        select2_clientes();
      }
    });
    

  }

}

// ----
names = [];
function select2_clientes(){

  $.ajax({
   type:"POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
   data:"opc=12",
   success:function(rp){
     res = eval(rp);
     cont = res.length;
     
     for (var i = 0; i < cont; i++) {
       names[i] = res[i];
     }
     
   }
  });
}

function add_cliente_input() { 
  $("#txtHuesped" ).autocomplete({
    source: names
  });
}

/*-----------------------------------*/
/*		Reporte General
/*-----------------------------------*/
dialog_hotel = null;
function CrearFolioHotel() {
  dialog_hotel = bootbox.dialog({
    title: '<strong><i class="bx bx-calendar"></i> INICIAR REPORTE DIARIO</strong> ',
    message: '<div>' +
      '<label>OBSERVACIONES</label>' +
      '<textarea id="reporte_obs" class="form-control" placeholder="Agregar nota "></textarea>' +
      '</div><br><div class="text-right"> <a class="btn btn-primary" onclick="FolioHotel(' + dialog_hotel + ')">Continuar</a></div>'
  });

}

function FolioHotel(obj_modal) {
  observaciones = $('#reporte_obs').val();
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/admin/actualizaciones.php",
    data: 'opc=5&obs=' + observaciones,
    success: function (rp) {
      data = eval(rp);
      cerrar_Modal(obj_modal);
      GRAL();
    }
  });
}

function cerrar_Modal(obj_modal) {
  obj_modal.modal('hide');
}
/*-----------------------------------*/
/*		Categorias & Subcategorias
/*-----------------------------------*/
var dialog = null;

function Saldos(id_num, txt) {

  registro = $('#txt' + txt + id_num).val();
  f = $('#date').val();

  // alert(registro);


  $.ajax({
    type: "POST",
    url: "controlador/finanzas/admin/tdEdit.php",
    data: 'opc=6&txt=' + txt + '&id_num=' + id_num + '&valor=' + registro + '&date=' + f,
    beforeSend: function () {
      $('#txt' + txt + id_num).addClass('disabled');
      $('#' + txt + id_num).html(Load_xs());
    },
    success: function (rp) {
      var data = eval(rp);
      $('#' + txt + id_num).html(data[0]);
    }
  });

}


function agregarSubcategoria(id) {

  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/pane_ingresos_v.php",
    data: 'opc=6&id=' + id,
    success: function (rp) {
      data = eval(rp);
      dialog = bootbox.dialog({
        title: '',
        size: 'small',
        message: data[0]

      });
      dialog.init(function () {

        setTimeout(function () {

          $('#txtSub').focus();
        }, 600);
      });
    }
  });


}


function cerrarModal() {
  dialog.modal('hide');
}

function insertar_sub(id) {
  // alert(id);
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'idcat=' + id + '&opc=4&nombre=' + $('#txtSub').val() + '&grupo=' + $('#txtGrupo').val(),
    success: function (rp) {
      var data = eval(rp);
      cerrarModal();
      Subcategoria(id);
      // $('#code').html(data[0]);
    }
  });

}
/*-----------------------------------*/
/*		Tabla ingresos (edit and insert)
/*-----------------------------------*/

function Existe(id) {

  if ($('#txtSub').val() != "") {

    $.ajax({
      type: "POST",
      url: "controlador/finanzas/cliente/argovia_mod.php",
      data: 'idcat=' + id + '&opc=5&nombre=' + $('#txtSub').val(),
      success: function (rp) {
        var data = eval(rp);

        if (data[0] == 0) {
          $('#txtExiste').html('<i class="text-success icon-ok-circled"></i> OK');
          $('#btnAgregar').removeClass('disabled');
        } else {
          $('#txtExiste').html(data[0]);
          $('#btnAgregar').addClass('disabled');
        }
      }
    });
  } else {
    $('#txtExiste').html('<b><i class="text-warning icon-warning-empty"></i> Campo vacio</b>');
    $('#btnAgregar').addClass('disabled');
  }


}


function tdEdit(txt, id_num, valor) {

  f = $('#date').val();

  $.ajax({
    type: "POST",
    url: "controlador/finanzas/admin/tdEdit.php",
    data: 'txt=' + txt + '&id_num=' + id_num + '&valor=' + valor + '&opc=3&date=' + f,
    success: function (rp) {
      var data = eval(rp);
      $('#' + txt + '' + id_num).html(data[0]);
      $('#Edit' + txt + id_num).focus();
    }
  });

}

function Edit(id_num, txt) {

  registro = $('#Edit' + txt + id_num).val();
  f = $('#date').val();

  $.ajax({
    type: "POST",
    url: "controlador/finanzas/admin/tdEdit.php",
    data: 'opc=4&txt=' + txt + '&id_num=' + id_num + '&valor=' + registro + '&date=' + f,
    beforeSend: function () {
      $('#Edit' + txt + id_num).addClass('disabled');
    },
    beforeSend: function (rp) {
      $('#Edit' + txt + id_num).addClass('disabled');
    },
    success: function (rp) {
      var data = eval(rp);
      $('#' + txt + id_num).html(data[0]);

      Subcategoria(data[1]);
    }
  });

}

function Insert(id_num, txt) {
  registro = $('#Edit' + txt + id_num).val();


  f = $('#date').val();
  $('#' + txt + '' + id_num).addClass('disabled');
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/admin/tdEdit.php",

    data: 'opc=5&txt=' + txt + '&id_num=' + id_num + '&valor=' + registro + '&date=' + f,
    beforeSend: function (rp) {
      $('#Edit' + txt + id_num).addClass('disabled');
    },
    success: function (rp) {
      var data = eval(rp);
      $('#' + txt + id_num).html(data[0]);
    }
  });

}

//-----------------------------------


function SubTotal() {

}


/* ** Subir backup de Poliza
/*-----------------------------------*/

function file_poliza(id) {
  $('#SubirIMG').html('');
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=0&id=' + id,
    success: function (rp) {
      var data = eval(rp);
      $('#SubirIMG').html(data[0]);
    }
  });

}

function RespaldoPoliza(id) {

  if ($('#txtArchivos').val() == "") {
    alert('Debes agregar un archivo para continuar...');
  } else {
    var InputFile = document.getElementById('txtArchivos');
    var file = InputFile.files;
    array = ['Detalles'];
    data = dataReturn(array, '1');

    data.append('opc', '1');
    data.append('idFile', id);
    data.append('files', file[0]);
    // console.log(...data);
  }// end else



  $.ajax({
    url: 'controlador/finanzas/cliente/argovia_mod.php',
    type: 'POST',
    contentType: false,
    data: data,
    processData: false,
    cache: false,
    beforeSend: function () {
      $('#SubirIMG').html("<label class='text-success'><span class='icon-spin6 animate-spin fa-2x'></span>Guardando ...</label>");
    },
    success: function (data) {
      $dat = eval(data);
      file_poliza(id);
    }
  });

}
/*-----------------------------------*/
/* ** Eliminar BackUp
/*-----------------------------------*/
function EliminarRespaldo(id) {

  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=2&id=' + id,
    beforeSend: function () {
      $('#SubirIMG').html("<center><label class='text-success'><span class='icon-spin6 animate-spin fa-2x'></span>Espere ...</label></center>");
    },
    success: function (rp) {
      file_poliza(id);
    }
  });
}

// Obtener datos de un formulario
function dataReturn(arreglo) {
  var data = new FormData();

  for (var i = 0; i < array.length; i++) {
    // console.log('#txt' + array[i]);
    data.append(array[i], $('#txt' + array[i]).val());
  }

  return data;
}



function Load_xs() {
  load = '<i style="font-size: 1em; " class="fa fa-spinner fa-pulse fa-fw text-success"></i>';

  return load;
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

function printDiv(idFolio) {

  var dialog = bootbox.dialog({
    title: "<i class='bx bx-calendar-alt'></i> CIERRE DIARIO",
    message: "<input style'' id='txtCargo' class='form-control input-sm' autofocus placeholder='Nombre de la persona a cargo'>",
    size: 'small',
    buttons: {
      cancel: {
        label: "<i class='bx bx-left-arrow-circle bx-sm' ></i> Salir",
        className: 'btn-danger',
        callback: function () {

        }
      },

      ok: {
        label: "Continuar <i class='bx bx-right-arrow-circle bx-sm'></i>",
        className: 'btn-primary',
        callback: function () {

          CierreDiario($('#txtCargo').val(), idFolio);

        }
      }
    }
  });


  // var divToPrint = document.getElementById('resumen_gral');
  // var html =  '<html>'+
  // '<head>'+
  // '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">'+
  // '<body onload="window.print(); ">' + divToPrint.innerHTML + '</body>'+
  // '</html>';
  // // window.close();
  // var popupWin = window.open();
  // popupWin.document.open();
  // popupWin.document.write(html);
  // popupWin.document.close();
}

function CierreDiario(usuario, id) {
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/cliente/argovia_mod.php",
    data: 'opc=6&usuario=' + usuario + '&id=' + id,
    success: function (rp) {

    }
  });
}


function All_reports() {
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/admin/all_reports.php",
    data: 'opc=0&date1=' + $('#date').val() + '&date2=' + $('#date').val(),
    beforeSend: function () {
      $('.tb_data').html("<center><label class='text-success'><span class='icon-spin6 animate-spin fa-2x'></span> Espere un momento...</label></center>");
    },
    success: function (data) {
      $('.tb_data').html(data);
      printDiv();
    }
  });
  // myWindow = window.open("../../../recursos/pdf/anticipo_ticket.php?date="+date, "_blank", "width=350, height=500");
  // $('.tb_data').html('<h1>'+date+'</h1><br><br>');
}

function PDF_Reports() {
  $.ajax({
    type: "POST",
    url: "controlador/finanzas/admin/all_reports.php",
    data: 'opc=1&date=' + $('#date').val(),
    beforeSend: function () {
      $('.Res').html("<center><label class='text-success'><span class='icon-spin6 animate-spin fa-2x'></span> Espere un momento...</label></center>");
    },
    success: function (data) {
      $('.Res').html(data);
    }
  });
}

// Complementos 


function printDiv() {
  var divToPrint = document.getElementById('resumen_gral');
  var html =
    '<html>' +
    '<head>' +
    '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">' +
    '<body onload="window.print(); window.close(); cerrarModal(); ">' + divToPrint.innerHTML + '</body>' +
    '</head>' +
    '</html>';

  var popupWin = window.open();
  popupWin.document.open();
  popupWin.document.write(html);
  popupWin.document.close();

}

function activarTab(tab) {
  $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};



function input_date(id_input) {
  $(id_input).datetimepicker({
    format: 'YYYY-MM-DD',
    useCurrent: false,
    defaultDate: new Date(),
    // minDate: moment().add(-1, 'd').toDate(-40, 'd'),
    widgetPositioning: {
      horizontal: 'right',
      vertical: 'bottom'
    },
  });
}


function date_dual(fi, ff) {

  $(fi).datetimepicker({ format: 'YYYY-MM-DD', defaultDate: new Date() });
  $(ff).datetimepicker({
    format: 'YYYY-MM-DD',
    defaultDate: new Date(),
    useCurrent: false
  });

  $(fi).on("dp.change", function (e) {
    $(ff).data("DateTimePicker").minDate(e.date);
  });

  $(ff).on("dp.change", function (e) {
    $(fi).data("DateTimePicker").maxDate(e.date);
  });
}


