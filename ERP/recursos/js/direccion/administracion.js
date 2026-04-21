
$(document).ready(main);

function main() {
 dataUsers();
}



function CargarCB() {
 cbSELECT(1);
 cbSELECT(2);
 cbSELECT(3);
}
function cbSELECT(opc) {


 $.ajax({
  type: "POST",
  url: "controlador/direccion/cbCategoria.php",
  data:'opc='+opc,
  success:function(rp) {
   var data = eval(rp);
   switch (opc) {
    case 1: $('#cbNivel').html(data[0]);   break;
    case 2: $('#cbArea').html(data[0]);    break;
    case 3: $('#cbNegocio').html(data[0]); break;
   }

  }
 });
}





function EditData(opc,id){
 pass='***';
 pass2='***';

 usuario = $('#txtUsuarioX').val();
 gerente = $('#txtGerenteX').val();
 mail    = $('#txtEmailX').val();
 udn     = $('#txtUDNX').val();
 area    = $('#txtAreaX').val();
 nivel   = $('#txtNivelX').val();


 valor = true;
 if( !$('#txtGerenteX').val() ){
  valor = false;
  $('#txtGerenteX').focus();
  $('.Group_nameX').addClass('has-error');

 }
 else if( !$('#txtUsuarioX').val() ){
  valor = false;
  $('.Group_nameX').removeClass('has-error');
  $('#txtUsuarioX').focus();
  $('.Group_userX').addClass('has-error');

 }else if( !$('#txtEmailX').val() ){
  valor = false;
  $('.Group_mailX').removeClass('has-error');
  $('#txtEmailX').focus();
  $('.Group_mailX').addClass('has-error');

 }else if( udn==0){
  valor = false;
  $('.Group_udnX').removeClass('has-error');
  $('#txtUDNX').focus();
  $('.Group_udnX').addClass('has-error');

 }else if( area ==0 ){
  valor = false;

  $('.Group_area').removeClass('has-error');
  $('#txtArea').focus();
  $('.Group_area').addClass('has-error');

 }else if(nivel==0 ){
  valor = false;
  alert(nivel);
  $('.Group_nivelX').removeClass('has-error');
  $('#txtNivelX').focus();
  $('.Group_nivelX').addClass('has-error');
 }

 if (valor) {
  $('.Group_nameX').removeClass('has-error');
  $('.Group_userX').removeClass('has-error');
  $('.Group_mailX').removeClass('has-error');
  $.ajax({
   type: "POST",
   url: "controlador/direccion/NuevosUsuarios.php",
   data:'usuario='+usuario+'&pass='+pass+'&mail='+mail+'&udn='+udn+'&area='+area+'&nivel='+nivel+'&gerente='+gerente+'&opc='+opc+'&id='+id,
   beforeSend: function() {
    $('#txtResX').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
   },

   success:function(rp) {
    $('#txtResX').html(rp);
    dataUsers();
   }
  });
 }
}

function saveData(opc,id){
 pass='***';
 pass2='***';
 if (opc==0) {  pass    = $('#txtPass').val(); pass2 = $('#txtPass2').val(); }
 usuario = $('#txtUsuario').val();
 gerente = $('#txtGerente').val();
 mail    = $('#txtEmail').val();
 udn     = $('#txtUDN').val();
 area    = $('#txtArea').val();
 nivel   = $('#txtNivel').val();

 valor = true;
 if( !$('#txtGerente').val() ){
  valor = false;
  $('#txtGerente').focus();
  $('.Group_name').addClass('has-error');

 }
 else if( !$('#txtUsuario').val() ){
  valor = false;
  $('.Group_name').removeClass('has-error');
  $('#txtUsuario').focus();
  $('.Group_user').addClass('has-error');

 }else if (pass!=pass2) {
  $('#txtRes').html('<span>La contraseña no coincide , vuelva a ingresarla</span>');
  $('#txtPass2').focus();
  $('.Group_user').addClass('has-error');

 }else if( !$('#txtEmail').val() ){
  valor = false;
  $('.Group_mail').removeClass('has-error');
  $('#txtEmail').focus();
  $('.Group_mail').addClass('has-error');

 }else if( udn==0){
  valor = false;
  $('.Group_udn').removeClass('has-error');
  $('#txtUDN').focus();
  $('.Group_udn').addClass('has-error');

 }else if( area ==0 ){
  valor = false;

  $('.Group_area').removeClass('has-error');
  $('#txtArea').focus();
  $('.Group_area').addClass('has-error');

 }else if(nivel==0 ){
  valor = false;
  alert(nivel);
  $('.Group_nivel').removeClass('has-error');
  $('#txtNivel').focus();
  $('.Group_nivel').addClass('has-error');
 }

 if (valor) {
  $('.Group_name').removeClass('has-error');
  $('.Group_user').removeClass('has-error');
  $('.Group_mail').removeClass('has-error');
  $.ajax({
   type: "POST",
   url: "controlador/direccion/NuevosUsuarios.php",
   data:'usuario='+usuario+'&pass='+pass+'&mail='+mail+'&udn='+udn+'&area='+area+'&nivel='+nivel+'&gerente='+gerente+'&opc='+opc+'&id='+id,
   beforeSend: function() {
    $('#txtRes').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
   },

   success:function(rp) {
    $('#txtRes').html(rp);
    dataUsers();
    LimpiarTexto();
   }
  });
 }
}



function LimpiarTexto() {
 $('#txtUsuario').val('');
 $('#txtGerente').val('');
 $('#txtPass').val('');
 $('#txtPass2').val('');

 $('#txtEmail').val('');
 $('#txtUDN').val();
 $('#txtArea').val();
 $('#txtNivel').val();
}












function ComprobrarPass(){
 pass = $('#txtPass').val();
 pass2 = $('#txtPass2').val();

 if (pass!=pass2) {
  $('#txtRes').html('<span>La contraseña no coincide , vuelva a ingresarla</span>');
  $('#txtPass').focus();
 }else {
  $('#txtRes').html('');
 }

}




// ====================================================



function dataArea() {

 $('#tbArea').DataTable({

  destroy: true,
  responsive: true,

  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/dataUsers.php",
   "data": function ( d ) {
    d.opc = 2;

   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [0] }
  ],
  "columns":[
   {"data":"#"},
   {"data":"Area"}

  ],
  // bSort: false,
  // dom: 'Bfrtip',
  // buttons: [
  //  'copy', 'excel','pdf'
  // ],
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

function dataUsers() {

 $('#tbUsuarios').DataTable({

  destroy: true,
  responsive: true,
  "scrollX": true,
  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/dataUsers.php",
   "data": function ( d ) {

    d.opc = 1;

   }
  },
  "columnDefs": [
   { className: "text-right", "targets": [4,5,6] }
  ],
  "columns":[
   {"data":"#"},
   {"data":"Usuario"},
   {"data":"Nivel"},
   {"data":"Correo"},
   {"data":"Gerente"},
   {"data":"Permiso"},
   {"data":"UDN"},
   {"data":"modificar"},
   {"data":"eliminar"}


  ],
  bSort: false,
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


function dataNivel() {

 $('#tbNivel').DataTable({

  destroy: true,
  responsive: true,

  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/dataUsers.php",
   "data": function ( d ) {
    d.opc = 3;

   }
  },
  "columnDefs": [
   { className: "text-left", "targets": [1] }
  ],
  "columns":[
   {"data":"#"},
   {"data":"Nivel"}

  ],
  // bSort: false,
  // dom: 'Bfrtip',
  // buttons: [
  //  'copy', 'excel','pdf'
  // ],
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


function Nivel_Area() {
 dataNivel();
 dataArea();
}

// Baja -
function bajaUsuario(id){
 $.ajax({
  type: "POST",
  url: "controlador/direccion/modal_user_baja.php",
  data:'id='+id,
  success:function(rp) {
   var data = eval(rp);
   $('#bajaUser').html(data[0]);
  }
 });
}


function Baja(id){
 if(!$("#pass").val()){
  $("#Res_Motivo").html("");
  $('#Res_Pass').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
  $("#pass").focus();
 }

 else{

  var pass = $("#pass").val();

  $.ajax({
   type: "POST",
   url: 'controlador/direccion/bajaUsuario.php',
   data: ('pass='+pass+'&id='+id),

   beforeSend: function() {
    $('#Resultado_baja').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
   },

   success:function(respuesta) {
    $('#Resultado_baja').html(respuesta);
    $('#Res_Pass').html('');
    dataUsers();
   }

  });
 }
}

function EditUsuario(id){

 $.ajax({
  type: "POST",
  url: "controlador/direccion/actualizar_usuario.php",
  data:'id='+id,

  success:function(rp) {
   var data = eval(rp);
   $('#bajaUser').html(data[0]);

  }
 });
}
