
var fi = null;
var ff = null;
$(document).ready(main);

function main() {
 Categorias();
 logo('.tab_content_subcategoria');

}

function Categorias(){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/pane_ingresos_v.php',
  data:'opc=3',
  success: function (data) {
   $('.tab_content').html(data);
  }
 });
}

function Subcategoria(id){

 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/pane_ingresos_v.php',
  data:'opc=2&id='+id+'&date='+ff+'&date0='+fi,
  success: function (data) {
   $('.tab_content_subcategoria').html(data);
  }
 });
}

function GRAL(){
 var startDate = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
 var endDate = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');
 fi           = startDate;
 ff           = endDate;

 valores = 'date1='+startDate+'&date2='+endDate+'&udn=1';

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

function Cierre_Dialogo(id) {
 var divToPrint = document.getElementById('resumen_gral');
 var html =  '<html>'+
 '<head>'+
 '<link href="recursos/css/print.css" rel="stylesheet" type="text/css">'+
 '<body onload="window.print(); ">' + divToPrint.innerHTML + '</body>'+
 '</html>';
 // window.close();
 var popupWin = window.open();
 popupWin.document.open();
 popupWin.document.write(html);
 popupWin.document.close();
}

function ver_tc(){

 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/pane_ingresos.php',
  data:'opc=7&udn=2'+'&date1='+fi+'&date2='+ff,
  success: function (data) {
   $('.tab_content_subcategoria').html(data);
   $('#tbtc').DataTable({destroy: true});
  }
 });

}

function logo(id){
 logo = '<center><br><br><br><img src="recursos/img/logo_c.png" style=" max-width:30%; " class="img-res"></center>';

 $(''+id).html(logo);
}

function archivos_hotel(pag){
 valores = 'udn=1&date1='+fi+'&date2='+ff;

 $.ajax({
  type: "POST",
  url: 'modelo/finanzas/contabilidad/udn/tabla_files_dir.php',
  data: 'pag=1&'+valores,
  beforeSend: function() {
   $('.tab_content_subcategoria').html("<h3><label class='text-warning'>"+"<span class='icon-spin6 animate-spin'></span> Espere un momento, el internet esta un poco lento...</label></h3>");
  },
  success:function(data) {
   $('.tab_content_subcategoria').html(data);
   export_data_table("#viewFolios");
  }
 });

}

function Select_tbarchivos(pag){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/pane_file_v.php',
  data:'opc=1&pag='+pag+'&date='+$('#date').val(),
  success: function (data) {
   $('.tb_files').html(data);
  }
 });
}

function ver_archivos(fi,ff,id) {

 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/actualizaciones.php',
  data:'opc=3&fi='+fi+'&ff='+ff+'&idC='+id,
  success: function (data) {
   dialog = bootbox.dialog({
    className: "dialogWide",
    title: "<i class='bx bx-paperclip'></i> Archivos adjuntos",
    // size:'large',
    message:data });
   }
  });

  // dialog = bootbox.dialog({
  //  title: "<i class='bx bx-paperclip'></i> Archivos adjuntos "+fi+"   "+ff+" "+id+" ",
  //  size:'large',
  //  message:'not found'
  // });
 }


 // function adjuntos() {
 //  alert('');
 //  // dialog = bootbox.dialog({
 //  //  title: "<i class='bx bx-paperclip'></i> Archivos adjuntos",
 //  //  size:'small',
 //  //  message:'not found'});
 //
 // }
