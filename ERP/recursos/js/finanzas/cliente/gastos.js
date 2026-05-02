proveedores = []; conceptos = [];

$(function(){
 ver_gastos(1);
 Select_Proveedores();
 Select_Concepto();
 $("#Proveedor").autocomplete({ source: proveedores });
 $("#Insumo").autocomplete({ source: conceptos });
});

function Select_Proveedores() {
 $.ajax({
  type: "POST",
  url: "controlador/finanzas/cliente/pane_compras_c.php",
  data: 'opc=1',
  success:function(data) {
   // $('#Respuesta_Gastos').html(data);
   res = eval(data);
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    proveedores[i] = res[i];
   }
  }
 });
}

function Select_Concepto() {
 $.ajax({
  type: "POST",
  url: "controlador/finanzas/cliente/pane_compras_c.php",
  data: 'opc=2',
  success:function(data) {
   // $('#Respuesta_Gastos').html(data);
   res = eval(data);
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    conceptos[i] = res[i];
   }
  }
 });
}

function nuevos_gastos(){
 valor = true;
 if(!$('#Proveedor').val() && !$('#Insumo').val()){
  valor = false;
  $('#Proveedor').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> El proveedor y el Concepto son necesarios</label>');
 }
 else if( (!$('#Gastos').val() || $('#Gastos').val() == 0) ){
  valor = false;
  $('#Gastos').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Es necesaria una cantidad</label>');
 }
 else if($('#Clase_Insumo').val() == 0){
  valor = false;
  $('#Clase_Insumo').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Seleccionar una clase de insumo</label>');
 }
 else if($('#Clase_Gasto').val() == 0){
  valor = false;
  $('#Clase_Gasto').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Seleccionar una clase de gasto</label>');
 }


 if(valor){
  valores = "&Proveedor="+$('#Proveedor').val()+"&Insumo="+$('#Insumo').val()+
  "&Clase_Insumo="+$('#Clase_Insumo').val()+"&Gastos="+$('#Gastos').val()+
  "&Clase_Gasto="+$('#Clase_Gasto').val()+
  "&Observaciones="+$('#Observaciones').val()+'&date='+$('#date').val();

  $.ajax({
   type: "POST",
   url: 'controlador/finanzas/cliente/pane_compras_c.php',
   data: 'opc=0'+valores,
   beforeSend: function() {
    $('#Respuesta_Gastos').html("<label class='text-success col-sm-12 col-xs-12'><span class='icon-spin6 animate-spin'></span> Guardando movimientos...</label>");
   },
   success:function(data) {
    $('#Proveedor').val('');
    $('#Insumo').val('');
    $('#Clase_Insumo > option[value="0"]').attr('selected','selected');
    $('#Gastos').val('');
    $('#GastosIVA').val('');
    $('#Clase_Gasto > option[value="0"]').attr('selected','selected');
    $('#Observaciones').val('');
    $('#Respuesta_Gastos').html('');
    ver_gastos(1);
    Saldos_Fondo();
   }
  });
 }
}

/*-----------------------------------*/
/* Gastos compras
/*-----------------------------------*/

function ver_gastos(pag){
 valores = 'pag='+pag+'&date='+$('#date').val()+'&Cb=0';

 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/tabla_gastos.php',
  data:valores,
  success:function(data) {
   $('#table_data').html(data);
  }
 });
}

function Convertir_input(id,id_num,valor) {
 switch (id) {
  case 'Prov_Compras':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="Prov_Compras2'+id_num+'" value="'+valor+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\'); }"; >');
  $('#Prov_Compras2'+id_num).focus();
  break;
  case 'prov_pagos':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="prov_pagos2'+id_num+'" value="'+valor+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\'); }"; >');
  $('#prov_pagos2'+id_num).focus();
  break;
  case 'Insumo_Compras':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="Insumo_Compras2'+id_num+'" value="'+valor+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\'); }"; >');
  $('#Insumo_Compras2'+id_num).focus();
  break;
  case 'Insumo_pagos':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="Insumo_pagos2'+id_num+'" value="'+valor+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\'); }"; >');
  $('#Insumo_pagos2'+id_num).focus();
  break;
  case 'ClaseInsumo_Compras':
  Clase_Insumos(1,id,id+'2',id_num,valor);
  break;
  case 'ClaseInsumo_pagos':
  Clase_Insumos(2,id,id+'2',id_num,valor);
  break;
  case 'Cant_Compras':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html(
   '<div class="input-group">'+
   '<span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>'+
   '<input type="text" class="form-control input-sm" id="Cant_Compras2'+id_num+'" value="'+valor+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\'); }"; >'+
   '</div>'
  );
  $('#Cant_Compras2'+id_num).focus();
  break;
  case 'Cant_pagos':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html(
   '<div class="input-group">'+
   '<span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>'+
   '<input type="text" class="form-control input-sm" id="Cant_pagos2'+id_num+'" value="'+valor+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\'); }"; >'+
   '</div>'
  );
  $('#Cant_pagos2'+id_num).focus();
  break;
  case 'TipoGasto_Compras':
  Tipo_Pago(id,id+'2',id_num,valor);
  break;
  case 'TipoGasto_pagos':
  Tipo_Pago(id,id+'2',id_num,valor);
  break;
  case 'Observaciones_Compras':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html('<textarea class="form-control input-sm" id="Observaciones_Compras2'+id_num+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\'); }"; >'+valor+'</textarea>');
  $('#Observaciones_Compras2'+id_num).focus();
  break;
  case 'Observaciones_pagos':
  $('#'+id+id_num).html('');
  $('#'+id+id_num).html('<textarea class="form-control input-sm" id="Observaciones_pagos2'+id_num+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\'); }"; >'+valor+'</textarea>');
  $('#Observaciones_pagos2'+id_num).focus();
  break;
 }
}

function Modificar_Compras(id,id2,id_num,valor,tipo){
 acceso = true;

 if(id == 'Cant_Compras'){
  if(!$('#'+id2+id_num).val() || parseFloat($('#'+id2+id_num).val()) == 0){
   acceso = false;
   $('#'+id+id_num).html('<label class="form-control text-right input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">$ '+valor+'</label>');
  }
 }

 if(acceso){
  valor = $('#'+id2+id_num).val();
  valores = '&id='+id_num+'&valor='+valor+'&tipo='+tipo;
  // alert(valores);
  $.ajax({
   type: "POST",
   url: 'controlador/finanzas/cliente/pane_compras_c.php',
   data: 'opc=3'+valores,
   success:function(data) {
    $('#Res_Gastos').html(data);
    res = eval(data);
    valor = res[0];
    $('#Res_Gastos').html(res[1]);
    if(id == 'Cant_Compras'){
     $('#'+id+id_num).html('<label class="text-right input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">$ '+valor+'</label>');
    }
    else if(id == 'Observaciones_Compras'){
     $('#'+id+id_num).html(
      '<label class="text-left input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">'+
      '<textarea class="input-xs label-form pointer" readonly style="background:none; border:none; font-weight:bold;">'+valor+'</textarea>'+
      '</label>'
     );
    }
    else{
     $('#'+id+id_num).html('<label class="text-left input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">'+valor+'</label>');
    }
    Saldos_Fondo();
    ver_gastos();
   }
  });
 }
}

function Clase_Insumos(opc,id,id2,id_num,valor) {
 valores = '&pc='+opc+'&valor='+valor;
 // alert(valores);
 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/pane_compras_c.php',
  data: 'opc=4'+valores,
  success:function(data) {
   $('#'+id+id_num).html(
    '<select class="form-control input-sm" id="'+id2+id_num+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'3\');" >'+
    '<option value="0">Seleccionar clase de insumo...</option>'+
    data+
    '</select>'
   );
   $('#'+id2+id_num).focus();
  }
 });
}

function Tipo_Pago(id,id2,id_num,valor){
 valores = '&valor='+valor;
 // alert(valores);
 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/pane_compras_c.php',
  data: 'opc=5'+valores,
  success:function(data) {
   $('#'+id+id_num).html(
    '<select class="form-control input-sm" id="'+id2+id_num+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'5\');" >'+
    '<option value="0">Seleccionar tipo de pago...</option>'+
    data+
    '</select>'
   );
   $('#'+id2+id_num).focus();
  }
 });
}

function Eliminar_Compras(id,opc) {
 valores = '&id='+id+'&opcc='+opc;
 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/pane_compras_c.php',
  data: 'opc=6'+valores,
  success:function(data_val) {
   $('.modal-content').html(data_val);
  }
 });
}


function Eliminar_Compras_Pagos(id,opc){
 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/pane_compras_c.php',
  data: 'opc=7&id='+id,
  success:function(data) {
   if(opc == 1){
    ver_gastos(1);
   }
   else { ver_gastos(1); }
   Saldos_Fondo();
   $("[data-dismiss=modal]").trigger({ type: "click" });
  }
 });
}


/*-----------------------------------*/
/* PANEL DE COMPRAS
/*-----------------------------------*/


function addCompras() {
 valor = true;

 if(!$('#Pedido').val()){
  valor = false;
  $('#Pedido').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel">'+
  '</span> El campo Pedido es necesario</label>');

 }else if(!$('#Factura').val()){
  valor = false;
  $('#Factura').focus();

  $('#Respuesta_Gastos').html('<label class="text-danger">'+
  '<span class="icon-cancel"></span> El campo Factura es necesario</label>');

 }

 else if(!$('#Proveedor').val()){
  valor = false;
  $('#Proveedor').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> El campo proveedor es necesario</label>');

 }
 else if( !$('#Insumo').val()){
  valor = false;
  $('#Insumo').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> El campo concepto es necesario </label>');

 }
 else if( (!$('#Gastos').val() || $('#Gastos').val() == 0) ){
  valor = false;
  $('#Gastos').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Es necesaria una cantidad</label>');
 }
 else if($('#Clase_Insumo').val() == 0){
  valor = false;
  $('#Clase_Insumo').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Seleccionar una clase de insumo</label>');
 }
 else if($('#Clase_Gasto').val() == 0){
  valor = false;
  $('#Clase_Gasto').focus();
  $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Seleccionar una clase de gasto</label>');
 }

 if (valor) {
  datx = "&GastosIVA="+$('#GastosIVA').val() +
  "&Factura="+$('#Factura').val()+"&Pedido="+$('#Pedido').val()+
  "&dateFacture="+$('#dateFacture').val()+
  "&Proveedor="+$('#Proveedor').val()+
  "&Insumo="+$('#Insumo').val()+
  "&Clase_Insumo="+$('#Clase_Insumo').val()+
  "&Gastos="+$('#Gastos').val()+
  "&Clase_Gasto="+$('#Clase_Gasto').val()+
  "&Observaciones="+$('#Observaciones').val()+
  '&date='+$('#date').val();

  $.ajax({
   type: "POST",
   url: 'controlador/finanzas/cliente/pane_compras_c.php',
   data: 'opc=8'+datx,
   beforeSend: function() {

    $('#Respuesta_Gastos').html("<label class='text-success col-sm-12 col-xs-12'><span class='icon-spin6 animate-spin'></span> Guardando movimientos...</label>");
   },

   success:function(data) {

    $('#Proveedor').val('');
    $('#Insumo').val('');
    $('#Clase_Insumo > option[value="0"]').attr('selected','selected');
    $('#Gastos').val('');
    $('#GastosIVA').val('');
    $('#Clase_Gasto > option[value="0"]').attr('selected','selected');
    $('#Observaciones').val('');
    $('#Respuesta_Gastos').html('');
    verGastoCompras();
       Saldos_Fondo();
   }
  });



 } // End valor

}

function verGastoCompras(pag) {

 valores = 'pag='+pag+'&date='+$('#date').val()+'&Cb=0';

 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/tabla_gastos_compras.php',
  data:valores,
  success:function(data) {
   datx = eval(data);

   $('#tabla_compras').html(datx[0]);
  }
 });

}

function Eliminar_Compras_G(id,opc) {
 valores = '&id='+id+'&opcc='+opc;
 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/pane_compras_c.php',
  data: 'opc=9'+valores,
  success:function(data_val) {
   $('.modal-content').html(data_val);
  }
 });
}

function Eliminar_Compras_PG(id,opc){

 $.ajax({
  type: "POST",
  url: 'controlador/finanzas/cliente/pane_compras_c.php',
  data: 'opc=7&id='+id,
  success:function(data) {
   if(opc == 1){
    verGastoCompras(1);
   }
   else { verGastoCompras(1); }
   Saldos_Fondo();
   $("[data-dismiss=modal]").trigger({ type: "click" });
  }
 });
}


/*-----------------------------------*/
/*	 Subir Archivos ala nube.
/*-----------------------------------*/

function subirArchivo(id,opc){

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

function Up_Files(idEquipo){
 var archivos = document.getElementById("archivos");
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
    url:'controlador/finanzas/cliente/upFiles.php',
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
     $('#SubirIMG').html('<div class="row"><div class="col-xs-12 col-sm-12 ">'+
     '<table id="tbPoliza" class="compact nowrap" style="width:100%">'+
     '<thead><tr><th>Archivo</th><th>Descripción</th><th>Peso</th><th>Fecha</th>'+
     '<th>Tipo</th><th><span class="fa fa-gear"></span></th></tr></thead></table></div></div>');
     verPoliza(idEquipo);
     verGastoCompras(1);
     ver_gastos(1);
    }
   });
  }
  else{
   $('#Resul').html("<label class='text-danger'><span class='icon-cancel'></span>Seleccionar por lo menos un archivo</label>");
  }
 }

}

function verPoliza(id) {

 var table = $('#tbPoliza').DataTable({

  destroy: true,
  "searching": false,

  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/finanzas/cliente/img_poliza.php",
   "data": function ( d ) {
    d.id = id;
   }
  },
  "columnDefs": [
   { className: "text-center", "targets": [0,1,2,5] }
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

function	EliminarArchivo(f){

 $.ajax({
  type:	"POST",
  url:	"controlador/finanzas/cliente/EliminarArchivo.php",
  data:'f='+f,
  beforeSend:function () {
   $('#SubirIMG').html('<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>'+
   '<h4><span>Eliminando archivo...</span></h4></center>');
  },
  success:function(rp)	{
   subirArchivo(rp,1);
  }
 });
}


function Imprimir() {
 var date = $('#date').val();
 myWindow = window.open("recursos/pdf/pdf_compras.php?date="+date
 , "_blank", "width=600, height=800");
}
