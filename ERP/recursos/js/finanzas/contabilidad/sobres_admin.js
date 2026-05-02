/*-----------------------------------*/
/*	Init Components
/*-----------------------------------*/

window.onload = function(){  Categorias_admin(); fondo_caja(); }

function fondo_caja(){

 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/admin/sobres_admin.php',
  data: 'opc=1'+'&udn='+$('#Empresas').val(),
  success:function(data) {
   res = eval(data);
   $('#SI_sobres').val(res[0]);
   $('#SF_sobres').val(res[1]);
  }
 });
}


var dialog = null;
function	verInfo(id){

 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/admin/actualizaciones.php',
  data: 'opc=0&id='+id,
  success:function(data) {
   dialog = bootbox.dialog({
    title: 'Detalle del gasto',
    size:'small',
    message:data

   });

  }
 });

}

function cerrarModal() {
 dialog.modal('hide');
}
/*-----------------------------------*/
/*	 Ingresos
/*-----------------------------------*/
function CierreHotel() {
 
      setTimeout('car_print();', 800);
 
}
function car_print() {
  var divToPrint = document.getElementById('resumen_gral');
  var html = '<html>' +
    '<head>' +
    '<link href="recursos/css/ui-ruler.css" rel="stylesheet" type="text/css">' +
    '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">' +
    '<style> .table { font-size:12px; }</style>' +
    '</head>' +
    '<body onload="window.print(); ">' + divToPrint.innerHTML +
    '</body>' +
    '</html>';
  var popupWin = window.open();
  popupWin.document.open();
  popupWin.document.write(html);
  popupWin.document.close();
}




function panel(opc) {
 switch (opc) {
  case 1:
  Categorias();
  break;
  case 2:
  Compras();
  break;
  case 3:
  Pagos();
  break;
 }
}

function Ver_Tc(){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/pane_ingresos.php',
  data:'opc=6&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val(),
  success: function (data) {
   $('.tab_content_subcategoria').html(data);
   table_tc();
  }
 });
}
function table_tc(){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/pane_ingresos.php',
  data:'opc=7&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val(),
  success: function (data) {
   $('.tb_data').html(data);
   $('#tbtc').DataTable({destroy: true});
  }
 });
}

function Now(){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/pane_ingresos.php',
  data:'opc=5'+'&udn='+$('#Empresas').val(),
  success: function (data) {
   alert(data);
   $('#date').html(data);
  }
 });
}

function Categorias(){

 valores = 'opc=0&date1='+$('#date1').val()+
 '&date2='+$('#date2').val()+'&udn='+$('#Empresas').val();

 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/pane_ingresos.php',
  data:valores,
  success: function (data) {
   $('#date').html(data);
   // Subcategoria(1);
  }
 });
}

// function Subcategoria(id){
//  valores = 'opc=1&date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&id='+id+'&udn='+$('#Empresas').val();
//
//  $.ajax({
//   type:'POST',
//   url:'controlador/finanzas/admin/pane_ingresos.php',
//   data:valores,
//   success: function (data) {
//    $('.tab_content_subcategoria').html(data);
//   }
//  });
// }

function number_format(amount, decimals) {

 amount += ''; // por si pasan un numero en vez de un string
 amount = parseFloat(amount.replace(/[^0-9\.]/g, '')); // elimino cualquier cosa que no sea numero o punto

 decimals = decimals || 0; // por si la variable no fue fue pasada

 // si no es un numero o es igual a cero retorno el mismo cero
 if (isNaN(amount) || amount === 0)
 return parseFloat(0).toFixed(decimals);

 // si es mayor o menor que cero retorno el valor formateado como numero
 amount = '' + amount.toFixed(decimals);

 var amount_parts = amount.split('.'),
 regexp = /(\d+)(\d{3})/;

 while (regexp.test(amount_parts[0]))
 amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');

 return amount_parts.join('.');
}

/*-----------------------------------*/
/*	 TAB GASTOS
/*-----------------------------------*/

function ConsultarMovimientos(){
 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/admin/actualizaciones.php',
  data: 'opc=2',
  success:function(data) {

   var dialog = bootbox.dialog({
    title: 'Consulta de movimientos',
    // size:"small",
    message:data
   });

   dialog.init(function(){
    $('#txtCuenta').select2({
     width: '100%',
     closeOnSelect:false
    });
   });



  }

 });

}

function PDF_MOVIMIENTOS() {

 cbx = $('#txtCuenta').val();
 obj = url_pdf(cbx, 'data');

 f1  = $('#date1').val();
 f2  = $('#date2').val();

 window.open("recursos/pdf/pdf_movimientos.php"+obj+'&f1='+f1+'&f2='+f2);
}


function	dataTB(){

 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/admin/actualizaciones.php',
  data: 'opc=1&date1='+$('#date1').val()+'&date2='+$('#date2').val(),
  success:function(data) {

   $('#tbGastos').html(data);

  }
 });
}

function EXCEL_MOVIMIENTOS() {
 cbx = $('#txtCuenta').val();
 obj = url_pdf(cbx, 'data');

 f1  = $('#date1').val();
 f2  = $('#date2').val();
 window.open("recursos/excel/excel_movimientos.php"+obj+'&f1='+f1+'&f2='+f2);
}



function ver_tabla_gastos(pag){

 // cbx          = $('#Clase_Insumo').val();
 // Clase_Insumo = $('#Clase_Insumo').val();

 // if (cbx!=null) {
 // obj = Obtener_arreglo(cbx, 'data');
 // var obj   = new FormData();
 //  obj.append('udn',      $('#Empresas').val());
 //  obj.append('date1',    $('#date1').val());
 //  obj.append('date2',    $('#date2').val());
 //  obj.append('busqueda', $('#txtBusqueda').val());
 //  obj.append('clase',    $('#Clase_Insumo').val());
 //  obj.append('pag', pag);

 data = 'udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+
 $('#date2').val()+'&busqueda='+$('#txtBusqueda').val()+'&clase='+
 $('#Clase_Insumo').val()+'&pag='+pag;


 $.ajax({
  type: "POST",
  url: 'modelo/finanzas/contabilidad/udn/tabla_gastos.php',
  // contentType: false,
  data: data,
  // processData: false,
  // cache: false,
  // dataType: "JSON",

  beforeSend: function() {
   $('#tbGastos').html('<center><h2><i class="fa fa-spinner fa-pulse fa-fw text-success"></i> Cargando datos...</h2><center>');
  },
  success:function(data) {  $('#tbGastos').html(data); }
 });
 // }
}

function Load() {
 load   = '<center><h2><i class="fa fa-spinner fa-pulse fa-fw text-success"></i> Cargando datos...</h2><center>';
 return load;
}

function ver_tabla_files(pag){

 valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

 $.ajax({
  type: "POST",
  url: 'modelo/finanzas/contabilidad/udn/tabla_files.php',
  data: 'pag='+pag+valores,
  beforeSend: function() {
   $('#table_data').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
  },
  success:function(data) {
   $('#tbArchivos').html(data);
  }
 });
}

function download_files(file,ruta){
 alert('file:'+file + 'ruta:'+ruta);
 window.open("controlador/finanzas/contabilidad/cliente/download_file.php?file="+file+"&ruta="+ruta);
}
/*==========================================
*		Proveedores
=============================================*/
function data_proveedor(){
 SI_SF_proveedor();
 ver_tabla_proveedor(1);
}


function SI_SF_proveedor(){
 valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/admin/sobres_admin.php',
  data: 'opc=7'+valores,
  success:function(data) {
   res = eval(data);
   $('#SI_local').val(res[0]);
   $('#Gastos_local').val(res[1]);
   $('#Pagos_local').val(res[2]);
   $('#SF_local').val(res[3]);
   // $('#Res').html(data);
  }
 });
}


function ver_tabla_proveedor(pag){
 valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();
 // alert(valores);
 $.ajax({
  type: "POST",
  url: 'modelo/finanzas/contabilidad/udn/tabla_proveedor.php',
  data: 'pag='+pag+valores,
  beforeSend: function() {
   $('#data_proveedor').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
  },
  success:function(data) {

   $('#data_proveedor').html(data); }
  });
 }

 /*==========================================
 *		RETIROS & REEMBOLSOS
 =============================================*/
 function ver_rembolso_retiro(){
  valores = 'udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: 'modelo/finanzas/contabilidad/udn/tabla_retiro.php',
   data: valores,
   beforeSend: function() {
    $('#data_proveedor').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) {
    $('#data_retiro').html(data);
   }

  });
 }


 /*-----------------------------------*/
 /*	 Tab-pane opc
 /*-----------------------------------*/

 function Get(opc){
  $('#pane-btn').html('');
  var udn = $('#Empresas').val();
  valores = 'date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&udn='+$('#Empresas').val();



  switch (opc) {
   case 1: // REPORTE GENERAL

   $('#pane-btn').html('<button class="btn btn-info btn-xs"  style="width:100%" onclick="Get(1)">Buscar</button>');

   // $('#table_data').addClass('hide');
//   alert(valores);
   $.ajax({
    type: "POST",
    url: 'controlador/finanzas/admin/RESUMEN_GRAL.php',
    data: valores,
    beforeSend: function() { $('#capa').html("<h4><label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando datos...</label></h4>"); },
    success:function(data) {
     var datos = eval(data);

     $('#capa').html(datos[0]);
    }
   });
   break;

   case 2:
   // $('#table_data').removeClass('hide');

   udn = $('#Empresas').val();
   $.ajax({
    type: "POST",
    url: 'vista/finanzas/admin/gastos_admin.php',
    data: 'udn='+udn,
    beforeSend: function() { $('#gastos').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando... </label></h3>"); },
    success:function(data) {
     $('#gastos').html(data);
     // $('#Clase_Insumo').select2({
     //  width: '100%',
     //  closeOnSelect:false
     // });

     ver_tabla_gastos(1);
     // ver_tabla_files(1);
    }
   });
   break;

   case 3:// Ingresos
   Categorias_admin();
   // panel(1);
   break;

   case 4:// ARCHIVOS --------------------------
   // $('#table_data').removeClass('hide');

   $.ajax({
    type: "POST",
    url: 'vista/finanzas/admin/files_admin.php',
    data: 'udn='+udn,
    beforeSend: function() { $('#capa').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span>  Cargando...</label></h3>"); },
    success:function(data) { $('#archivos').html(data);
    ver_tabla_files(1);
   }
  });
  break;

  //GASTOS TABLA --------------------------------
  case 5:
  alert();

  $.ajax({
   type: "POST",
   url: 'vista/finanzas/udn/contabilidad/gastos_admin.php',
   // data: 'udn='+udn,
   beforeSend: function() { $('#capa').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>"); },
   success:function(data) {
    $('#capa').html(data);
    ver_tabla_gastos(1);
   }
  });
  break;


  case 6://PROVEEDOR ------------------------------
  // $('#table_data').removeClass('hide');

  $.ajax({
   type: "POST",
   url: 'vista/finanzas/admin/proveedor_admin.php',
   // data: 'udn='+udn,
   beforeSend: function() { $('#capa').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>"); },
   success:function(data) { $('#proveedores').html(data); data_proveedor(); }
  });
  break;

  case 7: // Retiros & Reembolsos

  $.ajax({
   type: "POST",
   url: 'vista/finanzas/admin/retiros_rembolso_admin.php',
   data: 'udn='+udn,
   beforeSend: function() {
    $('#Retiros').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando...</label></h3>"); },
    success:function(data) { $('#Retiros').html(data);

    ver_rembolso_retiro();
   }
  });

  break;

  case 8://CHEQUES
  Tb_cheques(1);
  break;

  case 9://CREDITOS ---------------------------------
  $('#table_data').removeClass('hide');

  $.ajax({
   type: "POST",
   url: '../../vista/finanzas/udn/contabilidad/creditos_admin.php',
   // data: 'udn='+udn,
   beforeSend: function() { $('#capa').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>"); },
   success:function(data) { $('#capa').html(data); datos_credito(); }
  });
  break;
  case 10://BANCOS ------------------------------------
  $('#table_data').removeClass('hide');

  $.ajax({
   type: "POST",
   url: '../../vista/finanzas/udn/contabilidad/bancos_admin.php',
   data: 'udn='+udn,
   beforeSend: function() { $('#capa').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>"); },
   success:function(data) { $('#capa').html(data); ver_tabla_bancos(1); }
  });
  break;

  case 11:
  $('#table_data').removeClass('hide');

  $.ajax({
   type: "POST",
   url: '../../vista/finanzas/udn/contabilidad/costos_admin.php',
   data: 'udn='+udn,
   beforeSend: function() {
    $('#capa').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>"); },
    success:function(data) { $('#capa').html(data); ver_tabla_costos(1); Select_Costo(); }
   });
   break;



   case 12:

   $.ajax({
    type: "POST",
    url: 'vista/finanzas/admin/compras_finanzas.php',
    data: 'udn='+udn,
    beforeSend: function() { $('#GastosCompras').html("<h3><label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando... </label></h3>"); },
    success:function(data){
     $('#GastosCompras').html(data);
     verGastosCompras(1);
    }
   });

   break;

  }
 }

 /*-----------------------------------*/
 /*	    Ver	Gastos Compras
 /*-----------------------------------*/

 function verGastosCompras(pag) {

  Clase_Insumo = $('#Clase_Insumo').val();

  valores =
  '&udn='   + $('#Empresas').val()+
  '&date1=' + $('#date1').val()+
  '&date2=' + $('#date2').val()+
  '&clase=' + Clase_Insumo;

  $.ajax({
   type: "POST",
   url: 'controlador/finanzas/admin/tabla_gastos_compras.php',
   data: 'pag='+pag+valores,
   beforeSend: function() {
    $('#tbGastos').html("");
   },

   success:function(data) {
    dat = eval(data);
    $('#tbCompras').html(dat[0]);
   }

  });

 }



 //  --------------------------------
 function Btn_Date(){
  if($('#Date_Btn').val() == 0){
   $('#Btn_Date').html('<button type="button" class="btn btn-sm btn-info" style="background:none;" onClick="Btn_Date();"><span class="icon-toggle-on" style="color:#217DBB;"></span></button>');
   $('#Date_Btn').val('1');
  }
  else{
   $('#Btn_Date').html('<button type="button" class="btn btn-sm btn-info" style="background:none;" onClick="Btn_Date();"><span class="icon-toggle-off" style="color:#000;"></span></button>');
   $('#Date_Btn').val('0');
  }
 }


 function Tb_cheques(pag) {
  date1 = $('#date1').val();
  date2 = $('#date2').val();
  $('#cheque').html('');
  $.ajax({
   type: "POST",
   url:'controlador/finanzas/cliente/pane_cheques_v.php',
   data:'opc=5&pag='+pag+'&date='+date1+'&date2='+date2,
   beforeSend:function () {
    $('#cheque').html(Load());
   },
   success:function(data) {
    $('#cheque').html(data);
    verCheques();

   }
  });
 }


 function	verCheques(){
  date1 = $('#date1').val();
  date2 = $('#date2').val();


  $('#tbCheques').DataTable({

   destroy: true,
   responsive: true,
   "scrollX": true,
   "pageLength": 20,

   "ajax":{
    "method":"POST",
    "url":"controlador/finanzas/cliente/pane_cheques_v.php",
    "data": function ( d ) {

     d.fi = date1;
     d.ff = date2;
     d.opc = 4;
    }

   },
   "columnDefs": [
    { className:  "text-center", "targets": [0,2,7] },
    { className:  "text-right", "targets": [3,5] }
   ],
   "columns":[
    {"data":"Fecha"},
    {"data":"Nombre"},
    {"data":"banco"},
    {"data":"NoCuenta"},
    {"data":"cuenta"},
    {"data":"cheque"},
    {"data":"importe"},
    {"data":"concepto"},
    {"data":"opc"}


   ],
   // bSort: false,
   dom: 'Bfrtip',
   buttons: [
    {
     extend: 'excel',
     messageBottom: 'Nombre & Firma '
    },
    'copy'
   ],
   "oLanguage": {
    "sSearch":         "Buscar:",
    "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
    "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros ",
    "sLoadingRecords": "Por favor espere - cargando...",
    "oPaginate": {
     "sFirst":    "Primero",
     "sLast":     "Último",
     "sNext":     "Siguiente",
     "sPrevious": "Anterior"
    }

   }


  });

 }





 function Delete_Cheque(id) {
  $.ajax({
   type: "POST",
   url:'controlador/finanzas/cliente/pane_cheques_c.php',
   data:'opc=1&id='+id,
   success:function(data) {
    $('#cheque').html(data);
   }
  });
 }

 function Print_cheques(id){
  myWindow = window.open("recursos/pdf/cheques_pdf.php?top="+id, "_blank", "width=750, height=700");
 }

 function Date_Lock(){
  if($('#Date_Btn').val() == 1){
   $('#date2').val($('#date1').val());
  }
 }




 function Llenar_Caratula(){ // Caratula-Ingresos

  valores = "&date1="+$('#date1').val()+"&date2="+$('#date2').val()+'&udn='+$('#Empresas').val();
  // alert(valores);
  $.ajax({
   type: "POST",
   url: 'controlador/finanzas/admin/caratula.php',
   data: 'opc=6'+valores,
   beforeSend: function() {
    $('#Res').html("<h4><label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando datos...</label></h4>");
   },
   success:function(data) {
    $('.tb_caratula').html(data);
    ver_tb_caratula();
    $('#Res').html('');
   }
  });
 }

 function ver_tb_caratula(){
  ver_detalle_gastos(1);
  ver_detalle_proveedor(1);
  ver_detalle_anticipos(1);
  ver_detalle_bancos(1);
  ver_detalle_almacen(1);
 }



 //detalles gastos
 function ver_detalle_gastos(){
  valores = 'date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&udn='+$('#Empresas').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_detalle_gasto.php',
   data:valores,
   success:function(data) {
    $('#detalle_gastos').html(data);
   }
  });
 }
 //detalles proveedores
 function ver_detalle_proveedor(pag){
  valores = 'date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&udn='+$('#Empresas').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_detalle_proveedor.php',
   data:valores,
   success:function(data) {
    $('#detalle_proveedor').html(data);
   }
  });
 }
 //detalles anticipos
 function ver_detalle_anticipos(pag){
  valores = 'date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&udn='+$('#Empresas').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_detalle_anticipos.php',
   data:valores,
   success:function(data) {
    $('#detalle_anticipos').html(data);
   }
  });
 }
 //detalles bancos
 function ver_detalle_bancos(pag){
  valores = 'date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&udn='+$('#Empresas').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_detalle_bancos.php',
   data:valores,
   success:function(data) {
    $('#detalle_bancos').html(data);
   }
  });
 }
 //detalles almacen
 function ver_detalle_almacen(pag){
  valores = 'date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&udn='+$('#Empresas').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_detalle_almacen.php',
   data:valores,
   success:function(data) {
    $('#detalle_almacen').html(data);
   }
  });
 }



 //detalle retiros rembolsos

 function ver_tabla_ingresos(pag){
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_ingresos.php',
   data: 'pag='+pag+valores,
   beforeSend: function() {
    $('#table_data').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) { $('#table_data').html(data); }
  });
 }

 function ver_tabla_anticipos(pag){
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_anticipos.php',
   data: 'pag='+pag+valores,
   beforeSend: function() {
    $('#table_data').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) { $('#table_data').html(data); }
  });
 }

 function Select_Almacen(){
  valores = '&udn='+$('#Empresas').val();

  $.ajax({
   type: "POST",
   url: '../../controlador/finanzas/contabilidad/udn/sobres_admin.php',
   data: 'opc=4'+valores,
   success:function(data) {
    $('#Opc_Select').html(data);
   }
  });
 }

 function datos_almacen(){
  ver_tabla_almacen(1);
  SI_SF_almacen();
 }

 function SI_SF_almacen(){
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&almacen='+$('#Opc_Select').val();

  $.ajax({
   type: "POST",
   url: '../../controlador/finanzas/contabilidad/udn/sobres_admin.php',
   data: 'opc=3'+valores,
   success:function(data) {
    res = eval(data);
    $('#SI_local').val(res[0]);
    $('#SF_local').val(res[1]);
    // $('#Res').html(data);
   }
  });
 }

 function ver_tabla_almacen(pag){
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_almacen.php',
   data: 'pag='+pag+valores,
   beforeSend: function() {
    $('#table_data').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) { $('#table_data').html(data); }
  });
 }

 function Select_Costo(){
  valores = '&udn='+$('#Empresas').val();

  $.ajax({
   type: "POST",
   url: '../../controlador/finanzas/contabilidad/udn/sobres_admin.php',
   data: 'opc=5'+valores,
   success:function(data) {
    $('#Opc_Select').html(data);
   }
  });
 }

 function ver_tabla_costos(pag){
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&Opc_Select='+$('#Opc_Select').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_costos.php',
   data: 'pag='+pag+valores,
   beforeSend: function() {
    $('#table_data').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) { $('#table_data').html(data); }
  });
 }


 function datos_credito(){
  SI_SF_creditos();
  ver_tabla_creditos(1);
 }

 function SI_SF_creditos(){
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: '../../controlador/finanzas/contabilidad/udn/sobres_admin.php',
   data: 'opc=2'+valores,
   success:function(data) {
    res = eval(data);
    $('#SI_local').val(res[0]);
    $('#SF_local').val(res[1]);
   }
  });
 }

 function ver_tabla_creditos(pag){
  // alert();
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_creditos.php',
   data: 'pag='+pag+valores,
   beforeSend: function() {
    $('#table_data').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) { $('#table_data').html(data); }
  });
 }

 function ver_tabla_bancos(pag){
  valores = '&udn='+$('#Empresas').val()+'&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: '../../modelo/finanzas/contabilidad/udn/tabla_bancos.php',
   data: 'pag='+pag+valores,
   beforeSend: function() {
    $('#table_data').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) { $('#table_data').html(data); }
  });
 }

 function gastos_detalles(cont,fijo){
  for (var i = 0; i < cont; i++) {
   if(i != fijo){
    $('.GD_'+i).addClass('hide');
   }
  }
  $('.GD_'+fijo).toggleClass('hide');
  $('.Name_Cat'+fijo).toggleClass('icon-up-dir');
 }

 function almacen_detalles(cont,fijo){
  for (var i = 0; i < cont; i++) {
   if(i != fijo){
    $('.DA_'+i).addClass('hide');
   }
  }
  $('.DA_'+fijo).toggleClass('hide');
  $('.Name_Cat'+fijo).toggleClass('icon-up-dir');
 }

 /*-------  Ingresos  -----*/
 function Categorias_admin(){
  $.ajax({
   type:'POST',
   url:'controlador/finanzas/cliente/pane_ingresos_v.php',
   data:'opc=3',
   success: function (data) {
    $('.tab_content').html(data);
   }
  });
 }

 function GRAL(){
  valores = 'date1='+$('#date1').val()+'&date2='+$('#date2').val()+'&udn=1';

  $.ajax({
   type: "POST",
   url: 'controlador/finanzas/admin/RESUMEN_GRAL.php',
   data: valores,
   beforeSend: function() { $('.tab_content_subcategoria').html("<h4><label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando datos...</label></h4>"); },
   success:function(data) {
    var datos = eval(data);
    $('.tab_content_subcategoria').html(datos[0]);
   }
  });
 }

 function Subcategoria(id){
  $.ajax({
   type:'POST',
   url:'controlador/finanzas/cliente/pane_ingresos_v.php',
   data:'opc=2&id='+id+'&date='+$('#date2').val()+'&date0='+$('#date1').val(),
   success: function (data) {
    $('.tab_content_subcategoria').html(data);
   }
  });
 }

 function archivos_hotel(){

  valores = 'udn=1&date1='+$('#date1').val()+'&date2='+$('#date2').val();

  $.ajax({
   type: "POST",
   url: 'modelo/finanzas/contabilidad/udn/tabla_files.php',
   data: 'pag=1&'+valores,
   beforeSend: function() {
    $('.tab_content_subcategoria').html("<h3><label class='text-warning'><span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
   },
   success:function(data) {
    $('.tab_content_subcategoria').html(data);
   }
  });
 }

 function ver_tc(){

  $.ajax({
   type:'POST',
   url:'controlador/finanzas/admin/pane_ingresos.php',
   data:'opc=7&udn=2'+'&date1='+$('#date1').val()+'&date2='+$('#date2').val(),
   success: function (data) {
    $('.tab_content_subcategoria').html(data);
    $('#tbtc').DataTable({
     destroy: true,
     "bLengthChange": false,
     "bInfo": false
    });
   }
  });

 }

 function All_reports() {

//   alert($('#date1').val());


   $.ajax({
     type: "POST",
     url: "controlador/finanzas/admin/all_reports.php",
     data: 'opc=0&date1=' + $('#date1').val() + '&date2='+$('#date2').val(),
     beforeSend: function() {
       $('.tb_data').html(
         "<center><label class='text-success'><span class='icon-spin6 animate-spin fa-2x'></span> Espere un momento...</label></center>"
       );
     },
     success: function(data) {
       $('.tb_data').html(data);
       printDiv();
     }
   });
 }



 function printDiv() {
   var divToPrint = document.getElementById('resumen_gral');
   var html =
   '<html>'+
   '<head>'+
     '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">'+
     '<body onload="window.print(); window.close(); cerrarModal(); ">' + divToPrint.innerHTML + '</body>'+
   '</head>'+
   '</html>';

   var popupWin = window.open();
   popupWin.document.open();
   popupWin.document.write(html);
   popupWin.document.close();

 }





 /*-------  Recursos  -----*/
 function Obtener_arreglo(multiple,return_name) {
  var data = new FormData();

  for (var i = 0; i < multiple.length; i++) {
   data.append(return_name+i,multiple[i]);
  }

  return data;
 }






 function url_pdf(multiple,return_name) {
  url ='?';

  for (var i = 0; i < multiple.length; i++) {
   url = url + return_name + i + '=' + multiple[i] + '&' ;
  }
  url = url + 'cont='+multiple.length;

  return url;
 }

function list_cheques() {

  date1 = $('#date1').val();
  date2 = $('#date2').val();

  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/admin/formato_cheques.php',
    data: 'opc=0&f_i=' + date1 + '&f_f=' + date2,
    beforeSend: function () {
      $('#content-cheques').html(Load('Cargando datos'));
    },
    success: function (r) {
      data = eval(r);
      $('#content-cheques').html(data[0]);
      export_data_table('#tb_list');
      // verCheques();

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