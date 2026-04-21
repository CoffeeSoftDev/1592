$(document).ready(loading);

function loading() {
 VerFormulario();
 VerGrupos();
 verRegistros();
}

/*Table*/
function verRegistros() {
 $.ajax({
  type:	"POST",
  url:	"controlador/flores/admin/lista_productos_v.php",
  data:'opc=2&tipo=1',
  success:function(rp)	{
   var	data	=	eval(rp);
   $('#content-table').html(data[0]);
   data_table_productos();
  }

 });
}

function data_table_productos() {


 $('#tbProductos').DataTable({
  destroy: true,
  "searching": true,
  "pageLength": 20,
  "lengthChange": false,

  "displayLength": 25,

  "ajax":{
   "method": "POST",
   "url": "controlador/flores/admin/lista_productos_v.php",
   "data": function(d){
    d.opc = 2;
    d.tipo = 2;

   }
  },
  "columnDefs": [
   // {
   //  className: "text-center",
   //  "targets": [0]
   // },
   {
    className: "text-right",
    "targets": [3, 4, 5]
   }
  ],
  "columns": [
   {"data": "a"},
   {"data": "b"},
   {"data": "c"},
   {"data": "d"},
   {"data": "e"},
   {"data": "f"},
   {"data": "opc"}

  ],
  // bExpandableGrouping: true,
  dom: 'Bfrtip',
  buttons: [
   'copy', 'excel'
  ],
  "oLanguage":
  {
   "sSearch": "Buscar:",
   "sInfo": "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
   "sInfoEmpty": "Mostrando del 0 al 0 de un total de 0 registros",
   "sLoadingRecords": "Por favor espere - cargando...",
   "oPaginate":
   {
    "sFirst": "Primero",
    "sLast": "ultimo",
    "sNext": "Siguiente",
    "sPrevious": "Anterior"
   }
  }
 });
}

/* Grupos & SubGrupos */

function	VerGrupos(){
 $.ajax({
  type:	"POST",
  url:	"controlador/flores/admin/lista_productos_v.php",
  data:'opc=1',
  success:function(rp)	{
   var	data	=	eval(rp);
   $('#cbGrupos').html(data[0]);

  }

 });
}


function VerSubs() {

if ($('#txtCategoria').val()==''){
 $('#txtSub').attr('disabled','disabled');
}else {


 $.ajax({
  type:	"POST",
  url:	"controlador/flores/admin/lista_productos_v.php",
  data:'opc=3&grupo='+$('#txtCategoria').val(),
  success:function(rp)	{
   var	data	=	eval(rp);
   $('#tagSub').html(data[0]);

  }

 });
}
}

/* Forms*/
function	VerFormulario(){
 $.ajax({
  type:	"POST",
  url:	"controlador/flores/admin/lista_productos_v.php",
  data: 'opc=0',
  beforeSend:function () {
   $('#content-form').html('Cargando...');
  },
  success:function(rp)	{
   var	data	=	eval(rp);
   $('#content-form').html(data[0]);
   validate_vac();
  }

 });
}

function validate_vac() {
 $('#Form').bootstrapValidator({
  message: 'Este valor no es valido',
  feedbackIcons: {
   valid: 'icon-ok-circled  ico-1x',
   invalid: ' icon-cancel-circled ico-1x',
   validating: 'glyphicon glyphicon-refresh'
  },
  fields: {

   Flores: {
    group: '#tagFlores',
    validators: {
     notEmpty: {
      message: 'El campo Producto no puede quedar vacio.'
     }
    }
   },

   Costo: {
    group: '#tagCosto',
    validators: {
     notEmpty: {
      message: 'El campo Costo no puede quedar vacio.'
     }
    }
   },

   Venta: {
    group: '#tagVenta',
    validators: {
     notEmpty: {
      message: 'El campo Costo no puede quedar vacio.'
     }
    }
   },

  }// fields


 });
}



function	Guardar(){
 $("#Form").bootstrapValidator().on('success.form.bv', function(e) {
  saveForm();
 });
}

function saveForm() {

 array  = ['Producto','Costo','Venta','Clase','StockIni','StockMin'];
 data  = dataReturn(array);

 data.append('opc','0');
 console.log(...data);

 $.ajax({
  type:	"POST",
  url:	"controlador/flores/admin/lista_productos_c.php",
  contentType:false,
  data:data,
  processData:false,
  cache:false,
  beforeSend: function() {
   $('#content-rp').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span>Guardando ...</label>");
  },

  success:function(rp)	{
   // var data = eval(rp);
   
   // $('#content-load').html('ESPERA');
   // $('#content-rp').html(rp);
   data_table_productos();
   VerFormulario();
  }
 });

}


// Obtener datos ------------|


function dataReturn(arreglo){
 var data = new FormData();

 for (var i = 0; i < array.length; i++) {

  data.append( array[i],$('#txt'+ array[i]).val());
 }

 return data;
}
