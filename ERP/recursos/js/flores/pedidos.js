$(document).ready(main);
names = [];
dest = [];

function main() {
  Formato_de_pedidos();
   Historial_de_pedidos();
}

/* ------------------------------------ */
/* Complementos iniciales               */
/* ------------------------------------ */

function Formato_de_pedidos() {
  fecha_agenda();
  lista_destino();
  consultar_datos();
  lista_productos();
  autocomplet_txt();
}

function Historial_de_pedidos() {
  input_range('reportrange');
  input_range('txtFecha');

  consultar_lista();
}

function ImprimirLista() {
  consultar_lista();
}

function actualizar() {
  lista_productos();
}

function Opciones() {
  txt = $("#txtOperacion").val();
  switch (txt) {
    case "1":
      $("#btn-area").removeClass("hide");
      $("#content-pedidos").removeClass("hide");
      $("#content-pedidos").html(default_img("logo"));
      $("#content-files").addClass("hide");
      break;
    case "2":
      $("#btn-area").addClass("hide");
      $("#content-pedidos").addClass("hide");
      $("#content-files").removeClass("hide");
      break;
  }
}

function BuscarLugar() {
  dir = $("#txtDestino").val();

  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: 'opc=1&dir=' + dir,
    success: function (rp) {

      data = eval(rp);
      $('#txtLugarDir').html(data[1]);

      if (data[0] == '0') { // no se encontro un cliente
        $('#txtNuevo').addClass('disabled');
      } else {
        $('#txtNuevo').removeClass('disabled');
      }
    }
  });

}

function CalcularTotal() {
  costo    =    $('#complemento_venta').val();
  cantidad =    $('#complemento_cantidad').val();

  total    = costo * cantidad;

  $('#complemento_total').val(total);

}

function cxc_view() {
 $('.tab_content_subcategoria').html('HOLA MUNDO');
}

/* ------------------------------------ */
/* Formato de pedidos> Lista de pedidos */
/* ------------------------------------ */
modal_opciones = null;
function opciones_flores(){

  // $.ajax({
  //  type:"POST",
  //  url: url_file + ".php",
  //  data:"opc=",
  //  beforeSend:function(){
  //    $('').html(Load_sm());
  //  },
  //  success:function(rp){
  //   data = eval(rp);
  modal_complementos = bootbox.dialog({
    title: "Opciones para productos",
    // size: "small",
    message: '<a class="btn btn-success">Mostrar opciones</a>'
  });



  //  }
  // });

}


function GuardarDatos(id) {
  fila     = $('#txtItem' + id).val();
  idFila   = $('#idItem' + id).val();
  fol      = $("#txtFolio").val();

  if (fila != "") {
    $.ajax({
      type: "POST",
      url: "controlador/flores/user/formato_pedidos.php",
      data: "opc=3&fila=" + fila + '&idFila=' + idFila + '&fol=' +fol,
      success: function (rp) {
        data = eval(rp);

        $('#idItem' + id).val(data[0]);
        $('#idEliminar' + id).html(data[2]);
        $('#idCosto' + id).val(data[3]);

        disabled_inputs(id);
      }
    });
  }

}



function get_cant(id_col) {
  fol = $("#txtFolio").val();
  id = $("#idItem" + id_col).val();

  item = $("#Cant" + id_col).val();
  costo = $("#idCosto" + id_col).val();

  // _console("opc=4&fol=" + fol + "&id=" + id + "&item=" + item +
  //   ' &id_col' + id_col);


  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: "opc=4&fol=" + fol + "&id=" + id + "&item=" + item,
    success: function (rp) {
      var data = eval(rp);

      total = item * costo;

      // $("#idCosto" + id_col).val(costo);
      $("#costoFlor" + id_col).val(total);

      _console('cantidad: '+item + ' * costo ='+costo);
      // consultar_datos();
      // $('#Cant'+id).focus();
    }
  });
}

function ActualizarUnidad(id_col) {
  fol = $("#txtFolio").val();
  id = $("#idItem" + id_col).val();
  item = $("#txtUnidad" + id_col).val();


  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: "opc=5&fol=" + fol + "&id=" + id + "&item=" + item,
    success: function (rp) {
      var data = eval(rp);

      // consultar_datos();
      _console('el id de la unidad es; ' + data[0]);
    }
  });

}

function ActualizarDetalles(id_col) {
  fol = $("#txtFolio").val();
  id = $("#idItem" + id_col).val();
  item = $("#detalles" + id_col).val();

  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: "opc=6&fol=" + fol + "&id=" + id + "&item=" + item,
    success: function (rp) {
      var data = eval(rp);
      console.log(data[0]);
    }
  });
}

function actualizar_cliente() {
  fol     = $("#txtFolio").val();
  cliente = $('#txtDestino').val();

  arreglo = ['Folio','Destino'];

  datos   = get_name_data(arreglo);

  $.ajax({
  type:"POST",
  url: "controlador/flores/user/formato_pedidos.php",
  data: "opc=2&"+datos,
  beforeSend:function(){
  $('').html(Load_sm());
  },
  success:function(rp){
  data = eval(rp);
  }
  });
}

function disabled_inputs(id_col) {
  $('#txtUnidad' + id_col).removeAttr('disabled', 'disabled');
  $('#Cant' + id_col).removeAttr('disabled', 'disabled');
  $('#detalles' + id_col).removeAttr('disabled', 'disabled');


  // $('#Cant' + id_col).focus();
}

function CrearPedido() {
  array = ["Destino", "Date"];
  data = get_data(array, "data");
  if ($("#txtDestino").val() == "") {
    bootbox_msj_null("", "Lugar o Destino");
  } else {
    $.ajax({
      type: "POST",
      url: "controlador/flores/user/pedidos.php",
      data: data + "opc=1",
      beforeSend: function () {
        $("#content-pedidos").html(Load("Espere un momento..."));
      },
      success: function (rp) {
        consultar_datos();
      }
    });
  }
}

function nuevaFila() {
  UltimaFila = $('#UltimaFila').val();
  lastFila = parseInt(UltimaFila) + 1;

  txt = '';
  txt += '<tr class="item-row">';
  txt += '<td class="text-center">'+lastFila+'</td>';
  txt += '<td class="col-sm-5">' + input_txt(lastFila)+'</td>';
  txt += '<td>' + input_cant(lastFila)+'</td>';
  txt += '<td></td>';
  txt += '<td>' + text_area(lastFila)+'</td>';
  txt += '<td class="text-center" id="idEliminar' + lastFila + '">Eliminar' + lastFila +'</td>';

  txt += '</tr>';

  $(".item-row:last").after(txt);
  $('#UltimaFila').val(lastFila);
}


function input_txt(lastFila) {
  input = '';

  input += '<input type="hidden" class="cell bg-warning" id="idItem'+lastFila+'" value="0">';


  input += '<input class="cell input-xs ui-autocomplete-input" ';
  input += 'type="text" id="txtItem' + lastFila +'" ';
  input += 'data-bv-field="area" autocomplete="off" ';
  input += 'onFocus="addBusqueda(' + lastFila + ');"  ';
  input += 'onblur="GuardarDatos(' + lastFila +')"';
  input += '>';

  return input;
}

function input_cant(id) {
  input = '';

  input += '<input min="1" type="number" class="cell input-xs" ';
  input += 'onblur="get_cant(' + id+');" id="Cant'+id+'"  disabled>';

  return input;
}

function text_area(id) {
  txt = '';
  txt += '<input class="cell input-xs" ';
  txt += 'id="detalles' + id + '" onblur="ActualizarDetalles(' + id +')" disabled>';

  return txt;
}



function __input_flores() {
  $("#modal_txt").autocomplete({
    source: names,
    appendTo: $('#divName')
  });
}



function BuscarCosto() {
  nombreProducto = $('#modal_txt').val();

  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: "opc=12&Producto=" + nombreProducto,
    success: function (rp) {
        data = eval(rp);

        $('#complemento_venta').val(data[0]);
        // modal_close(modal_cancelar);
    }
  });
}

/* ------------------------------------ */
/* Historial de pedidos                 */
/* ------------------------------------ */
function verTicket(id) {
  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: "opc=9&id=" + id,
    beforeSend:function () {
      $("#content-folio").html(Load_xs('Cargando datos..'));
    },
    success: function (rp) {
      data = eval(rp);
      $("#content-folio").html(data[0]);
    }
  });
}

modal_cancelar = null;
function CancelarFolio(id, Folio) {
  modal_cancelar = bootbox.dialog({
    title: " Cancelar pedido # " + Folio + " ",
    // size: "small",
    message: '<div class=""> <span style="font-weight:500; font-size:1.2em;">¿Esta seguro de cancelar el pedido ?</span></div>' +
      '<div style="margin-top:30px;" class="text-right">' +
      '<button class="btn btn-primary" onclick="Cancelar_Venta(' + id + ')"> Aceptar </button></div>'
  });
}

function Cancelar_Venta(id) {
  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: "opc=10&id=" + id,
    success: function (rp) {
      data = eval(rp);
      modal_close(modal_cancelar);
      list_folio();
      // VerHistorialTicket();
      // modal_close(modal_cancelar);
    }
  });
}

/* ------------------------------------ */
/* Formato de pedidos> Pedidos activos */
/* ------------------------------------ */

function input_range(id_input) {
  var start = moment().startOf('month');
  var end = moment();

  $('#' + id_input).daterangepicker({
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

  }, init_select);

  init_select(start, end, id_input);
}

function init_select(start, end, txt) {
  $('#' + txt + ' span').html(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
  $('.drp-buttons .cancelBtn').html('Cancelar');
  $('.drp-buttons .applyBtn ').html('Aceptar');
  $('.ranges li:last').html('Personalizado');
}

function list_folio() {
  date = range_date('reportrange');
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    type: "POST",
    url: "controlador/flores/user/formato_pedidos.php",
    data: "opc=8&f_i=" + fecha_i + "&f_f=" + fecha_f,
    beforeSend: function (rp) {
      $("#tbTicket").html(Load_sm());
    },
    success: function (rp) {
      data = eval(rp);
      $("#tbTicket").html(data[0]);
      simple_data_table("#viewFolio");
    }
  });
}

function range_date(id) {
  var startDate = $('#' + id).data('daterangepicker').startDate.format('YYYY-MM-DD');
  var endDate = $('#' + id).data('daterangepicker').endDate.format('YYYY-MM-DD');

  fi = startDate;
  ff = endDate;

  array = [fi, ff];

  return array;
}



/* ------------------------------------ */
/* Formato de pedidos> Imprimir lista   */
/* ------------------------------------ */
function consultar_lista() {

  date = range_date('reportrange');
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    type: "POST",
    url: "controlador/flores/user/imprimir_lista.php",
    data: "opc=1&fi=" + fecha_i + '&ff=' + fecha_f,
    beforeSend: function () {
      $('#content-list-pdf').html(Load_sm());
    },
    success: function (rp) {
      data = eval(rp);

      $('#content-list-pdf').html(data[0]);
    }
  });

}

// fUNCION PROVISIONAL
function ExportarEnvios() {

  date = range_date('reportrange');
  fecha_i = date[0];
  fecha_f = date[1];

  $.ajax({
    type: "POST",
    url: "controlador/flores/user/imprimir_lista.php",
    data: "opc=2&fi=" + fecha_i + '&ff=' + fecha_f,
    beforeSend: function () {
      $('#content-list-pdf').html(Load_sm());
    },
    success: function (rp) {
      data = eval(rp);

      $('#content-list-pdf').html(data[0]);
    }
  });

}

function consultar_datos() {
  tipo = $("input[name=rdBtn]:checked").val();
  $.ajax({
    type: "POST",
    url: "controlador/flores/user/pedidos.php",
    data: "opc=0",
    beforeSend: function () {
      $("#content-pedidos").html(Load_sm('...'));
    },
    success: function (rp) {
      var data = eval(rp);
      if (data[0] != 0) { // Se creo un formato
        $("#content-pedidos").html(data[1]);
        $("#txtFolio").val(data[2]);
        $("#txtDestino").val(data[4]);
        $("#txtDate").val(data[3]);
        $("#txtNuevo").addClass("hide");
        $("#txtSubir").removeClass("hide");
        $(".btn-group button").attr("disabled", "disabled");

        $("#lblFolio").html(data[5]);

        $('#btnArchivo').removeAttr('disabled');
        $('#txtArchivos').removeAttr('disabled');

      } else {
        $("#content-pedidos").html(default_img("logo_c"));
        $("#txtNuevo").removeClass("hide");
        $("#txtSubir").addClass("hide");
        $('#btnArchivo').attr('disabled','disabled');
        $('#txtArchivos').attr('disabled','disabled');
      }
    }
  });
}


function conf_btn() {
  opc = $("input[name=rdBtn]:checked").val();
  if (opc == 1) {
    //Nueva Lista
    $("#btn-area").removeClass("hide");
    $("#content-pedidos").removeClass("hide");
    $("#content-pedidos").html(default_img("logo"));
    $("#content-files").addClass("hide");
  } else {
    // Subir archivo
    $("#btn-area").addClass("hide");
    $("#content-pedidos").addClass("hide");
    $("#content-files").removeClass("hide");
  }
}

function subirPedidos() {
  archivo = $('#txtArchivos').val();
  if(archivo){
   pedido_con_fichero();
  }else {
   terminar_pedido();
  }

}

function pedido_con_fichero(){
 fol          = $("#txtFolio").val();
 var archivos = document.getElementById("txtArchivos");

 var archivo = archivos.files;
 var data    = new FormData();

 for (i = 0; i < archivo.length; i++) {
  data.append("archivo" + i, archivo[i]);
 }

 data.append("opc", "3");
 data.append("fol", fol);
 console.log(...data);

  $.ajax({
    url: "controlador/flores/user/pedidos.php",
    type: "POST",
    contentType: false,
    data: data,
    processData: false,
    cache: false,
    beforeSend: function () {
      $("#content-pedidos").html(Load_sm("Guardando archivos..."));
    },
    success: function (rp) {
      var data = eval(rp);
      $("#content-pedidos").html("");
      $('#txtArchivos').val('');
      main();
    }
  });

}

function terminar_pedido(){
 fol     = $("#txtFolio").val();
 $.ajax({
   type: "POST",
   url: "controlador/flores/user/pedidos.php",
   data: "opc=5&fol=" + fol,
   beforeSend: function () {
     $("#content-pedidos").html(Load('Guardando pedido...'));
   },
   success: function (rp) {
     var data = eval(rp);
     $("#content-pedidos").html("");
     $("#txtDestino").val("");
     $("#txtLugarDir").html("");
     main();
   }
 });
}




function Quitar(id) {
  $(".console").html(id);
  $.ajax({
    type: "POST",
    url: "controlador/flores/user/pedidos.php",
    data: "opc=9&id=" + id,
    success: function (rp) {
      var data = eval(rp);
      consultar_datos();
    }
  });
}

function lista_productos() {
  $.ajax({
    type: "POST",
    url: "controlador/flores/user/pedidos.php",
    data: "opc=2",
    success: function (data) {
      res = eval(data);
      cont = res.length;
      for (var i = 0; i < cont; i++) {
        names[i] = res[i];
      }
    }
  });
}
function lista_destino() {
  $.ajax({
    type: "POST",
    url: "controlador/flores/user/pedidos.php",
    data: "opc=6",
    success: function (data) {
      res = eval(data);
      cont = res.length;
      for (var i = 0; i < cont; i++) {
        dest[i] = res[i];
      }
    }
  });
}

function addBusqueda(id) {
  $("#txtItem" + id).autocomplete({
    source: names
  });

  // var accentMap = {
  //   á: "a",
  //   ö: "o"
  // };
  // var normalize = function (term) {
  //   var ret = "";
  //   for (var i = 0; i < term.length; i++) {
  //     ret += accentMap[term.charAt(i)] || term.charAt(i);
  //   }
  //   return ret;
  // };
  // // $("#txtItem" + id).autocomplete({
  // //   source: function (request, response) {
  // //     var matcher = new RegExp($.ui.autocomplete.escapeRegex(request.term), "i");
  // //     response($.grep(names, function (value) {
  // //       value = value.label || value.value || value;
  // //       return matcher.test(value) || matcher.test(normalize(value));
  // //     }));
  // //   }
  // // });
  // $("#txtItem" + id)
  //   // don't navigate away from the field on tab when selecting an item
  //   .on("keydown", function (event) {
  //     if (event.keyCode === $.ui.keyCode.TAB &&
  //       $(this).autocomplete("instance").menu.active) {
  //       event.preventDefault();
  //     }
  //   })
  //   .autocomplete({
  //     minLength: 0,
  //     source: function (request, response) {
  //       // delegate back to autocomplete, but extract the last term
  //       response($.ui.autocomplete.filter(
  //         names, extractLast(request.term)));
  //     },
  //     focus: function () {
  //       // prevent value inserted on focus
  //       return false;
  //     },
  //     select: function (event, ui) {
  //       var terms = split(this.value);
  //       // remove the current input
  //       terms.pop();
  //       // add the selected item
  //       terms.push(ui.item.value);
  //       // add placeholder to get the comma-and-space at the end
  //       terms.push("");
  //       this.value = terms.join("  ");
  //       return false;
  //     }
  //   });
  // $("#txtItem" + id).on("autocompleteclose", function (event, ui) {
  //   tag = $("#txtItem" + id).val();
  //   add_item(tag, id);
  // });
}

function add_item(tag, id) {
  var prollNos = $("#txtItem" + id).val();
  // _console(prollNos);
  // fol = $("#txtFolio").val();
  // $.ajax({
  //   type: "POST",
  //   url: "controlador/flores/user/pedidos.php",
  //   data: "opc=3&id=" + fol + "&tag=" + tag,
  //   success: function (rp) {
  //     var data = eval(rp);
  //     consultar_datos();
  //     setTimeout(function () {
  //       $("#Cant" + id).focus(); _console(id);
  //     }, 100);
  //   }
  // });
}
/* ----------------------------------- */
/* Cantidad
/*----------------------------------- */
function add_cantidad(id) {
  fol = $("#txtFolio").val();
  id = $("#idItem" + id).val();
  item = $("#txtCant" + id).val();
  $(".txt-rp").html("id: " + id);
  // $.ajax({
  //  type: "POST",
  //  url:  "controlador/flores/user/pedidos.php",
  //  data:'opc=4&fol='+fol+'&id='+id+'&item='+item,
  //  success:function(rp)  {
  //    var data  = eval(rp);
  //    $('.txt-rp').html('<h1>'+data[0]);
  //  }
  //  });
}
function actualizar_datos(txt) {
  id_table = $("#txtFolio").val();
  val = $("#txt" + txt).val();
  if (id_table != "") {
    $.ajax({
      type: "POST",
      url: "controlador/flores/user/pedidos.php",
      data: "opc=7&txt=" + txt + "&id=" + id_table + "&val=" +
        val,
      success: function (rp) {
        // var  data  = eval(rp);
        // main();
      }
    });
  } // end if
}
/* Historial */


function verFolios(id) {
  $.ajax({
    type: "POST",
    url: "controlador/flores/user/pedidos.php",
    data: "opc=11&id=" + id,
    success: function (rp) {
      data = eval(rp);
      $(".content-list").html(data[0]);
    }
  });
}
/* Segunda Pestaña */


// complementos
function autocomplet_txt() {
  $("#txtDestino").autocomplete({
    source: dest
  });
  $("#txtDestino").on("autocompleteclose", function (event, ui) {
    BuscarLugar();
    // $('#txtLugarDir').html('ENCONTRADO');
    actualizar_datos("Destino");
  });
}



function SubirArchivo() {
  var archivos = document.getElementById("archivos");
  var archivo = archivos.files;
  cant_fotos = archivo.length;
  array = ["Detalles", "Destino", "Date"];
  data = dataReturn(array);
  // if ( !$('#txtDetalles').val() ) {
  //  bootbox_msj_null('','');
  // }else
  if (!$("#txtDestino").val()) {
    bootbox_msj_null("", "destino ");
  } else {
    for (i = 0; i < archivo.length; i++) {
      data.append("archivo" + i, archivo[i]);
    }
    data.append("opc", "10");
    console.log(...data);
    if (cant_fotos > 0) {
      $.ajax({
        url: "controlador/flores/user/pedidos.php",
        type: "POST",
        contentType: false,
        data: data,
        processData: false,
        cache: false,
        beforeSend: function () {
          $(".console").html(Load_xs("Guardando archivos..."));
        },
        success: function (rp) {
          var data = eval(rp);
          $(".console").html("");
          $("#content-files").html(data[0]);
        }
      });
    }
  } // end Else
  // else {
  //   for(i=0; i<archivo.length; i++){
  //     filarch.append('archivo'+i,archivo[i]); AÃ±adimos cada archivo a el arreglo con un indice diferente
  //   }
  //   filarch.append('date',$('#Date').val());
  //  console.log(.. filearch);
  // }
  //
  // if ( valor ) {
  //   if(cant_fotos > 0){
  //
  //     /*Ejecutamos la funciÃ³n ajax de jQuery*/
  //
  //
  //   }
  //   else{
  //     $('#Resul').html("<label class='text-danger'><span class='icon-cancel'></span>Seleccionar por lo menos un archivo</label>");
  //   }
  // }
}



/*       ::: Complementos :::    */
function split(val) {
  return val.split(/ \s*/);
}
function extractLast(term) {
  return split(term).pop();
}

function head_tablas() {
  // $('.thead_productos').toggleClass('hide');
  // $('#txtDetalles
}
