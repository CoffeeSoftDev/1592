
$(document).ready(main);

$(document).ready(function(){});

function main() {
 ver_tabla(1);
 cbSELECT(1);
 cbSELECT(2);
 cbSELECT(3);

 // $(".base-style").DataTable(),$(".no-style-no").DataTable(),$(".compact").DataTable(),$(".bootstrap-3").DataTable();
}

/*-----------------------------------*/
/*		Init Components
/*-----------------------------------*/

function cbSELECT(opc) {
 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/cbAlmacen.php",
  data:'opc='+opc,
  success:function(rp) {
   var data = eval(rp);

   switch (opc) {
    case 1: $('#cbCat').html(data[0]);   break;
    case 2: $('#cbArea').html(data[0]);  break;
    case 3: $('#cbZona').html(data[0]);   break;

   }

  }
 });
}

function viewer() {
 const viewer = new Viewer(document.getElementById('image'), {
   inline: true,
   viewed() {
     viewer.zoomTo(1);
   },
 });

}
/*-----------------------------------*/
/*		View Activos & No activos
/*-----------------------------------*/
function ver_tabla(id) {
 if (id == 1) {  verActivos(id);}
 else if(id == 0){  verNoActivos(id);}
}

url_file = 'controlador/mtto/admin/';

function verActivos(id) {

  categoria  = $('#txtCategoria').val();
  udn        = $('#select').val();
  area       = $('#txtArea').val();

  $.ajax({
  type: "POST",
  url: url_file + "data.php",
  data: "opc=21&categoria="+categoria+"&id="+id+"&area="+area,

  beforeSend: function () {
    $('#tbUsuarios').html('<h4> Cargando data... </h4>');
  },

  success: function (rp) {
    data = eval(rp);
    $('#tbUsuarios').html(data[0]);

    simple_data_table_no('#tbUsuarios',15);
  }

});

}



function verActivos_(id) {
 categoria  = $('#txtCategoria').val();
 udn        = $('#select').val();
 area       = $('#txtArea').val();

 $('#tbUsuarios').DataTable({

  destroy: true,

  "ajax":{
   "method":"POST",
   "url":"controlador/mtto/admin/data.php",
   "data": function ( d ) {
    d.opc       = id;
    d.categoria = categoria;
    d.area      = area;
   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [5,6,7] },
   { className: "text-center", "targets": [0,1,3,4,8,10] },
   { width: 90, targets: 10 }
  ],

  "columns":[
   {"data":"Codigo"},
   {"data":"udn"},
   {"data":"equipo"},
   {"data":"categoria"},
   {"data":"area"},
   {"data":"cantidad"},
   {"data":"costo"},
   {"data":"tiempo"},
   {"data":"fecha"},
   {"data":"Desc"},
   {"data":"conf"}


  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'copy', 'excel'
  ],
  "oLanguage": {
   "sSearch":         "Buscar:",
   "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
   "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
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

function verNoActivos(id) {
 categoria  = $('#txtCategoria').val();
 udn        = $('#select').val();
 area       = $('#txtArea').val();

 $('#tbNoActivos').DataTable({

  destroy: true,
  responsive: true,
  "autoWidth": false,
  // "scrollX": true,
  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/mtto/admin/data.php",
   "data": function ( d ) {
    d.opc       = id;
    d.categoria = categoria;
    d.area      = area;
   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [5,6] },
   { className: "text-center", "targets": [0,1,9] }
  ],

  "columns":[
   {"data":"Codigo", "width": "10%"},
   {"data":"udn"},
   {"data":"equipo", "width": "15%"},
   {"data":"categoria"},
   {"data":"area", "width": "10%"},
   {"data":"cantidad", "width": "3%"},
   {"data":"res", "width": "3%"},
   {"data":"Baja", "width": "15%"},
   {"data":"costo", "width": "3%"},
   {"data":"obs", "width": "5%"},
   {"data":"conf", "width": "8%"}


  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'copy', 'excel','pdf'
  ],
  "oLanguage": {
   "sSearch":         "Buscar:",
   "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
   "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
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

/*-----------------------------------*/
/*		 Polizas o  Comprobantes
/*-----------------------------------*/

function verPoliza(id) {

 var table = $('#tbPoliza').DataTable({

  destroy: true,
  "searching": false,
  // "scrollX": true,
  // responsive: true,
  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/mtto/admin/img_poliza.php",
   "data": function ( d ) {
    d.id = id;
   }
  },
  "columnDefs": [
   { className: "text-center", "targets": [0,1,2] }
  ],
  "columns":[

   {"data":"archivo"},
   {"data":"descripcion"},
   {"data":"peso"},
   {"data":"fecha"},
   {"data":"tipo"},
   {"data":"desc"}


  ],

  "oLanguage": {
   "sSearch":         "Buscar:",
   "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
   "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
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

function ModalPoliza(id,opc){
 if (opc==1) {
  $('#SubirIMG').html('<div class="SubirDATA">'+
  '<div class="row">' +
  '<div class="form-group col-sm-12">'+
  ' <label class="col-sm-12 text-center">'+
  '<strong>Seleccionar archivo</strong>'+
  '</label>'+
  '<div class="col-sm-8 col-sm-offset-2">'+
  '<input type="file" class="form-control input-sm" id="archivos"> </div>'+
  '</div>'+
  '</div>'+
  '<div class="row">'+
  '<div class="form-group col-sm-12" >'+
  '<label class="col-sm-12 text-center">'+
  '<strong>Detalles</strong>'+
  '</label>'+
  '<div class="col-sm-8 col-sm-offset-2">'+
  ' <textarea name="name" rows="4" class="col-sm-12 col-xs-12 form-control input-sm" id="Detalles"></textarea>'+
  '</div></div></div>'+
  '<div id="Resul" class="text-center"> </div>'+
  '<div class="row">'+
  '<div class="form-group col-sm-12 col-xs-12">'+
  '<label class="col-sm-12 text-center"> Limite máximo 20Mb * </label>'+
  '<button type="button" class="btn btn-sm btn-info col-sm-4 col-sm-offset-4" onclick="Up_Files('+id+');">'+
  '<span class="icon-upload"></span> Subir Archivos</button>'+
  '</div></div></div>');
 }else {

  $('#SubirIMG').html('<div class="row"><div class="col-xs-12 col-sm-12 ">'+
  '<table id="tbPoliza" class="compact nowrap" style="width:100%">'+
  '<thead><tr><th>Archivo</th><th>Descripción</th><th>Peso</th><th>Fecha</th>'+
  '<th>Tipo</th><th><span class="fa fa-gear"></span></th></tr></thead></table></div></div>');

  verPoliza(id);
 }


}


/*-----------------------------------*/
/*		Carga de archivos
/*-----------------------------------*/

function Up_Files(idEquipo){
 var archivos = document.getElementById("archivos");
 // alert(idEquipo);
 var archivo = archivos.files;
 cant_fotos  = archivo.length;
 valor = true;

 if ( !$('#Detalles').val() ) {
  valor = false;
  $('#Resul').html("<label class='text-danger'><span class='icon-attention'></span>Escribir un detalle u observación.</label>");
 }

 if ( valor ) {
  if(cant_fotos > 0){

   var filarch = new FormData();

   for(i=0; i<archivo.length; i++){
    filarch.append('archivo'+i,archivo[i]);
   }

   filarch.append('date',$('#date').val());
   filarch.append('Detalles',$('#Detalles').val());
   filarch.append('idEquipo',idEquipo);


   $.ajax({
    url:'controlador/mtto/admin/subir_poliza.php',
    type:'POST', //Metodo que usaremos
    contentType:false, //Debe estar en false para que pase el objeto sin procesar
    data:filarch, //Le pasamos el objeto que creamos con los archivos
    processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
    cache:false, //Para que el formulario no guarde cache
    beforeSend: function() {
     $('#Resul').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span>Cargando archivos...</label>");
    },
    success:function(data) {
     $('#Resul').html(data);
     $('#archivos').val('');
     $('#Detalles').val('');
     ver_tabla(1);
     $('#SubirIMG').html('<div class="row"><div class="col-xs-12 col-sm-12 ">'+
     '<table id="tbPoliza" class="table table-bordered compact nowrap" style="width:100%">'+
     '<thead><tr><th>Archivo</th><th>Descripción</th><th>Peso</th><th>Fecha</th>'+
     '<th>Tipo</th><th><span class="fa fa-gear"></span></th></tr></thead></table></div></div>');
     verPoliza(idEquipo);
    }
   });
  }
  else{
   $('#Resul').html("<label class='text-danger'><span class='icon-cancel'></span>Seleccionar por lo menos un archivo</label>");
  }
 }

}

function SubirImagen(id_mtto) {
 var archivos = document.getElementById("img");
 var archivo = archivos.files;
 cant_fotos = archivo.length;

 if(cant_fotos > 0){
  var filarch = new FormData();

  for(i=0; i<archivo.length; i++){
   filarch.append('img'+i,archivo[i]);
  }
  filarch.append('id',id_mtto); //AÃ±adimos cada

  $.ajax({
   url:'controlador/mtto/admin/subir_poliza.php',
   type:'POST',
   contentType:false,
   data:filarch,
   processData:false,
   cache:false, //Para que el formulario no guarde cache
   beforeSend: function() {
    $('#rs').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span>Cargando imagenes...</label>");
   },
   success:function(data) {
    $('#SubirIMG').html(data);
    ver_tabla(1);
   }
  });
 }
}

function RemoverPoliza(id) {

 $('#SubirIMG').html('<div class="form-horizontal"><div class="alert alert-info alert-dismissible show" role="alert">Para dar de baja es necesario tu contraseña para autorizarla. </div>'+
 '<div class="form-group">'+
 ' <label class="col-sm-4">Contraseña:</label> '+
 '<div class="col-sm-8"> <input type="password"  class="form-control input-xs" id="pass" autocomplete="off" autofocus>'+
 ' <div class=" bg-default" id="Res_Pass"></div></div></div>'+
 '<div class="form-group"> '+
 ' <div class="col-sm-12 " id="btnZone"> '+
 '<button type="button" class="btn btn-danger col-xs-12 col-sm-3  col-sm-offset-5" onclick="ModalPoliza('+id+',0)">Regresar</button> '+
 '<button type="button" class="btn btn-success col-xs-12 col-sm-3 col-sm-offset-1" onclick="autorizarQuitarPoliza('+id+')"> OK </button></div></div></div>');


}

function autorizarQuitarPoliza(id) {
 alert(id);
 var pass  = $('#pass').val();

 if(!$("#pass").val()){
  $('#Res_Pass').html("<label class='col-xs-12 col-sm-12 text-center text-danger'> * El campo es requerido </label>");
  $("#pass").focus();
 }else {
  $.ajax({
   type: "POST",
   url: 'controlador/mtto/admin/RemoverPoliza.php',
   data: 'id='+id+'&pass='+pass,
   beforeSend: function() {
    $('#SubirIMG').html("<label class='text-primary'><span class='icon-spin6 animate-spin'></span> Espere...</label>");
   },
   success:function(rp) {
    var data = eval(rp);
    $('#SubirIMG').html(data[0]);
    ver_tabla(1);
   }
  });

 }
}

/*-----------------------------------*/
/*		Baja y Alta
/*-----------------------------------*/

function Alta_Baja(opc,id,tipo){
 actual = $('#txtCantidad1').val();
 cant   = $('#txtCantidad2').val();
 baja   = $("#motivo").val();
 pass   = $("#pass").val();
 res    = (actual-cant);

 if (res<0) {
  $('#Res_Cantidad2').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* La cantidad supera al inventario actual </label>");
  $("#txtCantidad2").focus();

 }else if(!$("#pass").val()){
  $("#Res_Motivo").html("");
  $('#Res_Pass').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
  $("#pass").focus();
  $('#Res_Cantidad2').html('');
 }
 else if(!$("#motivo").val()){
  $("Res_Pass").html("");
  $('#Res_Motivo').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
  $("#motivo").focus();
 }
 else{

  $.ajax({
   type: "POST",
   url: 'controlador/mtto/admin/almacen_insertar_ba.php',
   data: ('baja='+baja+'&pass='+pass+'&id='+id+'&opc='+opc+'&actual='+actual+'&cantidad='+cant+'&tipo='+tipo),

   beforeSend: function() {
    $('#Resultado_baja').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
   },

   success:function(respuesta) {
    $('#Resultado_baja').html(respuesta);
    $('#btnZone').html('');

   }
  });
 }
}

function bajaProductos(id,opc) {

 $.ajax({
  type: "POST",
  url: 'controlador/mtto/admin/almacen_modal_ab.php',
  data: 'id='+id+'&opc='+opc,
  beforeSend: function() {
   $('#SubirIMG').html("<label class='text-primary'><span class='icon-spin6 animate-spin'></span> Espere...</label>");
  },
  success:function(rp) {
   var data = eval(rp);
   $('#baja_code').html(data[0]);
   // ver_tabla(1);
  }
 });
}

/*-----------------------------------*/
/*		Modales
/*-----------------------------------*/

function almacen_modal_tabla(id){

 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/almacen_modal_tabla.php",
  data:'id='+id,

  success:function(rp) {
   var data = eval(rp);
   $('#code').html(data[0]);

  }
 });
}

function verModalActualizar(idEq){
 $('#code').html('');
 $('#okRegistro').html('');


 $.ajax({
  type: "POST",
  url: "controlador/mtto/FormularioActualizar.php",
  data:'idEquipo='+idEq,
  beforeSend: function() {
   $('#code').html('<br><br><center>'+
   '<i class="fa fa-spinner fa-spin fa-2x fa-fw text-info"></i><span> Espere...</span></center>');
  },
  success:function(rp) {
   var data = eval(rp);


   $('#code').html(data[0]);

   var f      = new Date();
   $('#Equipo').focus();

   $('#date').combodate({
    minYear: f.getFullYear(),
    maxYear: f.getFullYear()+10
   });
   Search_Prod();
   Search_Mark();
   validate();
  }
 });


}


/*-----------------------------------*/
/*		se usan?
/*-----------------------------------*/

function calcularVida(){
 var        f = new Date();
 dataPICKER = $('#Duracion').val();
 f2         =f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();
 var fecha1 = moment(f2);
 var fecha2 = moment(dataPICKER);
 vida = fecha2.diff(fecha1, 'months');
 $('#TimeLife').html(vida+ ' Meses');
}

function habilitarPieza() {
 cbCategoria = $('#A').val();
 // alert(cbCategoria);
 if(cbCategoria==3){
  document.getElementById('txtCantidad').disabled=false;

 }else {
  document.getElementById('txtCantidad').disabled=true;
 }
}

function LimpiarModal() {
 var        f = new Date();
 f2         =f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();
 $('#Equipo').val('');
 $('#Area').val('');
 $('#Codigo').val('');
 $('#txtCosto').val('');
 $('#Duracion').val(f2);
 $('#txtDetalles').val('');
 $('#Resultado').html('');
 $('#TimeLife').html('');
 $('#txtCantidad').val(1);
 document.getElementById('txtCantidad').disabled=true;
 $('#Equipo').focus();
}

function Moneda(){
 if(/^([a-zA-Z])*$/.test($('#txtCosto').val())){
  $('#txtCosto').val('');
 }
}


function NoMod(){
 $('#Resultado').html("");
 $('#opcion').hide();
 $('#btn_mod').show();
}
