$(document).ready(main);

function main() {
 verProveedores();
}



function limpiarModal() {
 $('#_txtProveedor').val('');
 $('#_txtDireccion').val('');
 $('#_txtContacto').val('');
 $('#_txtTelefono').val('');
 $('#_txtRFC').val('');
 $('#_txtRes').html('');
 $('#_txtFormasPago').val('');
 $('#_txtProveedor').focus();
 // ---
 $('.Group_01').removeClass('has-error');
 $('.Group_02').removeClass('has-error');
 $('.Group_03').removeClass('has-error');
 $('.Group_04').removeClass('has-error');
 $('.Group_05').removeClass('has-error');
 $('.Group_06').removeClass('has-error');
 $('.Group_06').removeClass('has-error');
}

function limpiarEdit() {
 $('#txtProveedor').val('');
 $('#txtDireccion').val('');
 $('#txtContacto').val('');
 $('#txtTelefono').val('');
 $('#txtRFC').val('');
 $('#txtRes').html('');
 $('#txtFormasPago').val('');
 $('#txtProveedor').focus();
 // ---
 $('.Group_01').removeClass('has-error');
 $('.Group_02').removeClass('has-error');
 $('.Group_03').removeClass('has-error');
 $('.Group_04').removeClass('has-error');
 $('.Group_05').removeClass('has-error');
 $('.Group_06').removeClass('has-error');
 $('.Group_07').removeClass('has-error');
}

function AutorizarBaja(id){
 $('#txtPass').val('');
 $('#txtBaja').html('');

 $('#btnBaja').html('  <button style="width: 100%; " class="btn btn-success btn-flat m-b-15" onclick="bajaProveedor('+id+')">Autorizar</button>');
}


function bajaProveedor(id){
 pass = $('#txtPass').val();

 $.ajax({
  type: "POST",
  url: "controlador/finanzas/admin/bajaProveedor.php",
  data:'id='+id+'&pass='+pass+'&estado=2',
  success:function(rp) {
   var data = eval(rp);
   $('#txtBaja').html(data[0]);
   if (data[0]!=2) {
    main();
   }
  }
 });
}


/* Editar */


function EditPro(idProveedor) { // Datos
 limpiarEdit();

 $.ajax({
  type: "POST",
  url: "controlador/finanzas/admin/EditPro.php",
  data:'id='+idProveedor,

  success:function(rp) {
   var data = eval(rp);
   $('#txtProveedor').val(data[0]);
   $('#txtDireccion').val(data[1]);
   $('#txtContacto').val(data[2]);
   $('#txtTelefono').val(data[3]);
   $('#txtFormasPago').val(data[4]);
   $('#txtRFC').val(data[5]);
   $('#btnOk').html(data[6]);
   $('#cbCategoria').html(data[7]);
  }
 });
}

function SavePro(idPro){ // Editar Campo
 /*  validate  */
 valor = true;
 if( !$('#txtProveedor').val() ){
  valor = false;
  $('#txtProveedor').focus();
  $('.Group_01').addClass('has-error');

 }else if( !$('#txtDireccion').val() ) {
  valor = false;
  $('#txtDireccion').focus();
  $('.Group_01').removeClass('has-error');
  $('.Group_02').addClass('has-error');

 }else if( !$('#txtTelefono').val() ) {
  valor = false;
  $('#txtTelefono').focus();
  $('.Group_01').removeClass('has-error');
  $('.Group_02').removeClass('has-error');
  $('.Group_03').addClass('has-error');

 }else if( !$('#txtRFC').val() ) {
  valor = false;
  $('#txtRFC').focus();
  $('.Group_01').removeClass('has-error');
  $('.Group_02').removeClass('has-error');
  $('.Group_03').removeClass('has-error');
  $('.Group_04').addClass('has-error');


 }else if(!$('#txtFormasPago').val()) {
  valor = false;
  $('#txtFormasPago').focus();
  // ---
  $('.Group_01').removeClass('has-error');
  $('.Group_02').removeClass('has-error');
  $('.Group_03').removeClass('has-error');
  $('.Group_04').removeClass('has-error');
  // ---
  $('.Group_05').addClass('has-error');

 }


 else if($('#txtCategoria').val()==0 ) {
  valor = false;
  $('#txtCategoria').focus();
  $('.Group_01').removeClass('has-error');
  $('.Group_02').removeClass('has-error');
  $('.Group_03').removeClass('has-error');
  $('.Group_04').removeClass('has-error');
  $('.Group_05').removeClass('has-error');

  $('.Group_06').addClass('has-error');

 }else if( !$('#txtContacto').val() ) {
  valor = false;
  $('#txtContacto').focus();
  $('.Group_01').removeClass('has-error');
  $('.Group_02').removeClass('has-error');
  $('.Group_03').removeClass('has-error');
  $('.Group_04').removeClass('has-error');
  $('.Group_05').removeClass('has-error');
  $('.Group_06').removeClass('has-error');
  $('.Group_07').addClass('has-error');
 }


 /*   ---   */


 if (valor) {
  $('.Group_06').removeClass('has-error');
  datos = 'opc=2&proveedor='+$('#txtProveedor').val()+
  '&dir='             +   $('#txtDireccion').val()+
  '&contacto='          +  $('#txtContacto').val()+
  '&telefono='        +  $('#txtTelefono').val()+
  '&rfc='             +  $('#txtRFC').val()+
  '&Categoria='      +  $('#txtCategoria').val()+
  '&FormasPago='      +  $('#txtFormasPago').val()+
  '&id='        + idPro;

  $.ajax({
   type: "POST",
   url: "controlador/finanzas/admin/SavePro.php",
   data: datos,
   beforeSend: function( rp ) {
    $('#txtRes').html('<i  class="fa fa-spinner fa-spin fa-2x"> </i> Espere...');
   },
   success:function(rp) {
    var data = eval(rp);

    $('#txtRes').html(data[0]);
    // limpiarModal();
    main();
   }
  });
 }

}

function verProveedores() {

 $('#tbProovedores').DataTable({

  destroy: true,
  responsive: true,
  "scrollX": true,
  "pageLength": 20,

  "ajax":{
   "method":"POST",
   "url":"controlador/finanzas/admin/lista_proveedores.php"
  },
  "columnDefs": [
   { className: "text-right", "targets": [3] }
  ],
  "columns":[
   {"data":"#"},
   {"data":"proveedor","width": "15%"},
   {"data":"direccion","width": "20%"},
   {"data":"telefono","width": "10%"},
   {"data":"formas","width": "20%"},
   {"data":"rfc"},
   {"data":"ciudad"},
   // {"data":"categoria"},
   {"data":"eliminar","width": "6%"}


  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'excel','pdf'
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

/* Nuevo Proveedor */

function addComponent(){
 limpiarModal();

 $.ajax({
  type: "POST",
  url: "controlador/finanzas/admin/_cbSELECT.php",
  data:'opc=1',
  success:function(rp) {
   var data = eval(rp);
   $('#_cbCategoria').html(data[0]);

  }
 });
}

function ModalData() {

 /*  validate  */
 valor = true;
 if( !$('#_txtProveedor').val() ){
  valor = false;
  $('#_txtProveedor').focus();
  $('._Group_01').addClass('has-error');

 }else if( !$('#_txtDireccion').val() ) {
  valor = false;
  $('#_txtDireccion').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').addClass('has-error');

 }else if( !$('#_txtTelefono').val() ) {
  valor = false;
  $('#_txtTelefono').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').addClass('has-error');

 }else if( !$('#_txtRFC').val() ) {
  valor = false;
  $('#_txtRFC').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').addClass('has-error');


 }else if(!$('#_txtFormasPago').val()) {
  valor = false;
  $('#_txtFormasPago').focus();
  // ---
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').removeClass('has-error');
  // ---
  $('._Group_05').addClass('has-error');

 }


 else if($('#_txtCategoria').val()==0 ) {
  valor = false;
  $('#_txtCategoria').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').removeClass('has-error');
  $('._Group_05').removeClass('has-error');

  $('._Group_06').addClass('has-error');

 }else if( !$('#_txtContacto').val() ) {
  valor = false;
  $('#_txtContacto').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').removeClass('has-error');
  $('._Group_05').removeClass('has-error');
  $('._Group_06').removeClass('has-error');
  $('._Group_07').addClass('has-error');
 }


 /*   ---   */
 if (valor) {

  datos = 'id=0&opc=1&proveedor='+$('#_txtProveedor').val()+
  '&dir='      +   $('#_txtDireccion').val()+
  '&contacto='   +  $('#_txtContacto').val()+
  '&telefono=' +  $('#_txtTelefono').val()+
  '&rfc='      +  $('#_txtRFC').val()+
  '&FormasPago='      +  $('#_txtFormasPago').val()+
  '&Categoria='        + $('#_txtCategoria').val();

  $.ajax({
   type: "POST",
   url: "controlador/finanzas/admin/SavePro.php",
   data: datos,
   beforeSend: function( rp ) {
    $('#_txtRes').html('<i  class="fa fa-spinner fa-spin fa-2x"> </i> Espere...');
   },
   success:function(rp) {
    var data = eval(rp);
    limpiarModal();
    $('#_txtRes').html(data[0]);
    main();
   }
  });
 }


}
