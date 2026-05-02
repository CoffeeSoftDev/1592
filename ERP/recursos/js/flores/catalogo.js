$(document).ready(main);


function main() {
 ver_categorias();
 ver_sub();
 VerGrupos();
}


/* Grupos & SubGrupos */
function VerGrupos() {
 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/lista_productos_v.php",
  data: 'opc=1',
  success: function(rp) {
   var data = eval(rp);
   $('#cbGrupos').html(data[0]);
  }
 });
}

function GuardarCategoria() {

 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/catalogo.php",
  data:'opc=0&categoria='+$('#txtCategoria').val(),
  beforeSend: function() {
   $('#tbCategoria').html(
    "<label class='text-success'><span class='icon-spin6 animate-spin'></span>Guardando ...</label>"
   );
  },
  success: function(rp) {
   var data = eval(rp);
   ver_categorias();
   // VerDetalles();
  }
 });
}

function GuardarSub() {

 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/catalogo.php",
  data:'opc=3&sub='+$('#txtSub').val()+'&cat='+$('#txtCat').val(),
  beforeSend: function() {
   $('#tbSub').html(
    "<label class='text-success'><span class='icon-spin6 animate-spin'></span>Guardando ...</label>"
   );
  },
  success: function(rp) {
   var data = eval(rp);
   ver_sub();
   // VerDetalles();
  }
 });
}


function ver_categorias() {
 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/catalogo.php",
  data:'opc=1',
  success: function(rp) {
   var data = eval(rp);
   $('#tbCategoria').html(data[0]);
   // VerDetalles();
  }
 });
}

function ver_sub() {
 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/catalogo.php",
  data:'opc=4',
  success: function(rp) {
   var data = eval(rp);
   $('#tbSub').html(data[0]);
   // VerDetalles();
  }
 });
}

function EliminarCat(id) {

 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/catalogo.php",
  data: "opc=5&id=" + id,
  success: function(rp) {
   ver_categorias();
  }
 });
}
