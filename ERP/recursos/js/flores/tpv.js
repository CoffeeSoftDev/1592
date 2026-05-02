$(document).ready(main);

function main() {
 _menu_folios();
 _menu_categorias();
 _menu_productos();
}

function _menu_categorias() {
 $.ajax({
  type: "POST",
  url: "controlador/flores/user/tpv.php",
  data: "opc=1" ,
  success: function(rp) {
   var data = eval(rp);

   $("#btn_Categorias").html(data[0]);
  }
 });
}

function _menu_productos(id) {
 $.ajax({
  type: "POST",
  url: "controlador/flores/user/tpv.php",
  data: "opc=2" ,
  success: function(rp) {
   var data = eval(rp);

   $("#productList2").html(data[0]);
  }
 });
}

function _menu_folios() {
 $.ajax({
  type: "POST",
  url: "controlador/flores/user/tpv.php",
  data: "opc=3" ,
  success: function(rp) {
   var data = eval(rp);

   $("#pane-folios").html(data[0]);
  }
 });
}

function modal() {
 $.ajax({
  type: "POST",
  url: 'controlador/flores/user/tpv.php',
  data: 'opc=4',
  success:function(rp) {
   data = eval(rp);
   dialog = bootbox.dialog({
    title: 'Crear Remisión',
    // size:'small',
    message:data

   });

  }
 });
}

function CrearRemision() {
 array = ['Destino','Cliente','Remision','Clave','Nota','Lote','Factura'];
 obj   = get_data(array,'data');
 $('#txt_rp').html(obj);
 // $.ajax({
 //  type: "POST",
 //  url: 'controlador/flores/user/tpv.php',
 //  data: 'opc=4',
 //  success:function(rp) {
 //   data = eval(rp);
 //
 //  }
 // });
}


/*    Recursos   */

function get_data(multiple,return_name) {
 url ='';

 for (var i = 0; i < multiple.length; i++) {
  url = url + return_name + i + '=' + $('#txt'+multiple[i]).val() + '&' ;
 }
 return url;
}
