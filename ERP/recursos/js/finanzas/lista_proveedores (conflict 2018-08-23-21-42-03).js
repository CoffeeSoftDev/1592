$(document).ready(main);

function main() {
 verProveedores();
}



function addComponent(){
 limpiarModal();

 $.ajax({
  type: "POST",
  url: "controlador/finanzas/admin/_cbSELECT.php",
  data:'opc=1',
  success:function(rp) {
   var data = eval(rp);
   $('#cbFormasPago').html(data[0]);

  }
 });
}

function limpiarModal() {
 $('#_txtProveedor').val('');
 $('#_txtDireccion').val('');
 $('#_txtCiudad').val('');
 $('#_txtTelefono').val('');
 $('#_txtRFC').val('');
 $('#_txtRes').html('');
}

function SavePro(idPro){

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

 }else if( !$('#_txtCiudad').val() ) {
  valor = false;
  $('#_txtDireccion').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').addClass('has-error');
 }else if( !$('#_txtTelefono').val() ) {
  valor = false;
  $('#_txtTelefono').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').addClass('has-error');
 }else if( !$('#_txtRFC').val() ) {
  valor = false;
  $('#_txtRFC').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').removeClass('has-error');

  $('._Group_05').addClass('has-error');
 }else if($('#_txtFormasPago').val()=0 ) {
  valor = false;
  $('#_txtFormasPago').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').removeClass('has-error');
  $('._Group_05').removeClass('has-error');

  $('._Group_06').addClass('has-error');
 }


 /*   ---   */


if (valor) {
 datos = 'proveedor='+$('#txtProveedor').val()+
 '&dir='      +   $('#txtDireccion').val()+
 '&ciudad='   +  $('#txtCiudad').val()+
 '&telefono=' +  $('#txtTelefono').val()+
 '&rfc='      +  $('#txtRFC').val()+
 '&id='        + idPro;

 $.ajax({
  type: "POST",
  url: "controlador/finanzas/admin/SavePro.php",
  data: datos,
  beforeSend: function( rp ) {
   $('#_txtRes').html('<i  class="fa fa-spinner fa-spin fa-2x"> </i> Espere...');
  },
  success:function(rp) {
   var data = eval(rp);

   $('#_txtRes').html(data[0]);
   limpiarModal();
   main();
  }
 });
}

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

 }else if( !$('#_txtCiudad').val() ) {
  valor = false;
  $('#_txtDireccion').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').addClass('has-error');
 }else if( !$('#_txtTelefono').val() ) {
  valor = false;
  $('#_txtTelefono').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').addClass('has-error');
 }else if( !$('#_txtRFC').val() ) {
  valor = false;
  $('#_txtRFC').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').removeClass('has-error');

  $('._Group_05').addClass('has-error');
 }else if($('#_txtFormasPago').val()=0 ) {
  valor = false;
  $('#_txtFormasPago').focus();
  $('._Group_01').removeClass('has-error');
  $('._Group_02').removeClass('has-error');
  $('._Group_03').removeClass('has-error');
  $('._Group_04').removeClass('has-error');
  $('._Group_05').removeClass('has-error');

  $('._Group_06').addClass('has-error');
 }


 /*   ---   */
if (valor) {

 datos = 'id=0&opc=1&proveedor='+$('#_txtProveedor').val()+
 '&dir='      +   $('#_txtDireccion').val()+
 '&ciudad='   +  $('#_txtCiudad').val()+
 '&telefono=' +  $('#_txtTelefono').val()+
 '&rfc='      +  $('#_txtRFC').val()+
 '&FormasPago='        + $('#_txtFormasPago').val();

 $.ajax({
  type: "POST",
  url: "controlador/finanzas/admin/SavePro.php",
  data: datos,
  beforeSend: function( rp ) {
   $('#_txtRes').html('<i  class="fa fa-spinner fa-spin fa-2x"> </i> Espere...');
  },
  success:function(rp) {
   var data = eval(rp);

   $('#_txtRes').html(data[0]);
   main();
  }
 });
}


}


function EditPro(idProveedor) {
 // alert(idProveedor);
 $('#txtRes').html('');
 $.ajax({
  type: "POST",
  url: "controlador/finanzas/admin/EditPro.php",
  data:'id='+idProveedor,

  success:function(rp) {
   var data = eval(rp);
   $('#txtProveedor').val(data[0]);
   $('#txtDireccion').val(data[1]);
   $('#txtCiudad').val(data[2]);
   $('#txtTelefono').val(data[3]);
   $('#txtRFC').val(data[5]);
   $('#btnOk').html(data[6]);
   // alert(data[6]);
   $('#cbFormas').html(data[7]);


  }
 });
}

function verProveedores() {

 $('#tbProovedores').DataTable({

  destroy: true,
  responsive: true,
  "scrollX": true,
  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/finanzas/admin/verProveedores.php"
  },
  "columnDefs": [
   { className: "text-right", "targets": [4] }
  ],
  "columns":[
   {"data":"#"},
   {"data":"proveedor"},
   {"data":"direccion"},
   {"data":"ciudad"},
   {"data":"telefono"},
   {"data":"formas"},
   {"data":"rfc"},
   {"data":"modificar"},
   {"data":"eliminar"}


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
