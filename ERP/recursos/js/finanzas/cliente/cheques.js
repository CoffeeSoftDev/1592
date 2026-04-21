bancos    = [];
cuentas   = [];

$(function(){
 Tb_cheques(1);
 Select_Bancos();
 Select_Cuentas();
 $("#Banco_Cq").autocomplete({ source: bancos });
 $("#Cuenta_Cq").autocomplete({ source: cuentas });
 $( "#Cuenta_Cq" ).on( "autocompleteclose", function( event, ui ) {
  BuscarCuenta();
 });

 $('#Importe_Cq').numeric();
 $('#Importe_Cq').numeric(".");
 $('#Cuenta_Cq').numeric();
 $('#Cuenta_Cq').numeric(".");
 $('#Cheque_Cq').numeric();
 $('#Cheque_Cq').numeric(".");
});


function	BuscarCuenta(){
 cuenta = $("#Cuenta_Cq").val();
 $.ajax({
  type:	"POST",
  url:	"controlador/finanzas/cliente/pane_cheques_c.php",
  data:'opc=2&cuenta='+cuenta,
  success:function(rp)	{
   data = eval(rp);

   $('#txtCuenta').html(data[1]);
   if (data[0]=='0') {
    $('#btnCheque').addClass('disabled');
   }else {
    $('#btnCheque').removeClass('disabled');

   }
  }
 });
}


function Select_Bancos() {
 $.ajax({
  type: "POST",
  url: "controlador/finanzas/cliente/pane_cheques_v.php",
  data: 'opc=2',
  success:function(data) {
   // $('#Resul').html(data);
   res = eval(data);
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    bancos[i] = res[i];
   }
  }
 });
}

function Select_Cuentas() {
 $.ajax({
  type: "POST",
  url: "controlador/finanzas/cliente/pane_cheques_v.php",
  data: 'opc=3',
  success:function(data) {
   console.log(data);
   res  = eval(data);
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    cuentas[i] = res[i];
   }
  }
 });
}

function	viewCuenta(nom){
 $.ajax({
  type:	"POST",
  url:	"controlador/finanzas/cliente/pane_cheques_v.php",
  data:'opc=4&nom='+nom,
  success:function(rp)	{
   var	data	=	eval(rp);
   $('#').html(data[0]);
  }

 });
}


function Save_Cheque(){
 var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
 var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput

 cant_fotos = archivo.length;

 valor = true;

 if(!$('#Name_Cq').val()){
  $('.Importe_Cq').removeClass('has-error');
  $('.Banco_Cq').removeClass('has-error');
  $('.Cuenta_Cq').removeClass('has-error');
  $('.Cheque_Cq').removeClass('has-error');
  $('.Concepto_Cq').removeClass('has-error');
  $('.Name_Cq').removeClass('has-error');
  valor = false;
  $('.Name_Cq').addClass('has-error');
  $('#Name_Cq').focus();
 }
 else if(!$('#Importe_Cq').val()){
  $('.Importe_Cq').removeClass('has-error');
  $('.Banco_Cq').removeClass('has-error');
  $('.Cuenta_Cq').removeClass('has-error');
  $('.Cheque_Cq').removeClass('has-error');
  $('.Concepto_Cq').removeClass('has-error');
  $('.Name_Cq').removeClass('has-error');
  valor = false;
  $('.Importe_Cq').addClass('has-error');
  $('#Importe_Cq').focus();
 }
 else if(!$('#Banco_Cq').val()){
  $('.Importe_Cq').removeClass('has-error');
  $('.Banco_Cq').removeClass('has-error');
  $('.Cuenta_Cq').removeClass('has-error');
  $('.Cheque_Cq').removeClass('has-error');
  $('.Concepto_Cq').removeClass('has-error');
  $('.Name_Cq').removeClass('has-error');
  valor = false;
  $('.Banco_Cq').addClass('has-error');
  $('#Banco_Cq').focus();
 }
 else if(!$('#Cuenta_Cq').val()){
  $('.Importe_Cq').removeClass('has-error');
  $('.Banco_Cq').removeClass('has-error');
  $('.Cuenta_Cq').removeClass('has-error');
  $('.Cheque_Cq').removeClass('has-error');
  $('.Concepto_Cq').removeClass('has-error');
  $('.Name_Cq').removeClass('has-error');
  valor = false;
  $('.Cuenta_Cq').addClass('has-error');
  $('#Cuenta_Cq').focus();
 }
 else if(!$('#Cheque_Cq').val()){
  $('.Importe_Cq').removeClass('has-error');
  $('.Banco_Cq').removeClass('has-error');
  $('.Cuenta_Cq').removeClass('has-error');
  $('.Cheque_Cq').removeClass('has-error');
  $('.Concepto_Cq').removeClass('has-error');
  $('.Name_Cq').removeClass('has-error');
  valor = false;
  $('.Cheque_Cq').addClass('has-error');
  $('#Cheque_Cq').focus();
 }
 else if(!$('#Concepto_Cq').val()){
  $('.Importe_Cq').removeClass('has-error');
  $('.Banco_Cq').removeClass('has-error');
  $('.Cuenta_Cq').removeClass('has-error');
  $('.Cheque_Cq').removeClass('has-error');
  $('.Concepto_Cq').removeClass('has-error');
  $('.Name_Cq').removeClass('has-error');
  valor = false;
  $('.Concepto_Cq').addClass('has-error');
  $('#Concepto_Cq').focus();
}
// }else if($('#txtDestinos').val()==0){
//   $('.Importe_Cq').removeClass('has-error');
//   $('.Banco_Cq').removeClass('has-error');
//   $('.Cuenta_Cq').removeClass('has-error');
//   $('.Cheque_Cq').removeClass('has-error');
//   $('.Concepto_Cq').removeClass('has-error');
//   $('.Name_Cq').removeClass('has-error');
//   valor = false;
//   $('.Concepto_Cq').addClass('has-error');
//   $('#txtDestinos').focus();
//  }

 if(valor){
  if(cant_fotos > 0){
   $('.Importe_Cq').removeClass('has-error');
   $('.Banco_Cq').removeClass('has-error');
   $('.Cuenta_Cq').removeClass('has-error');
   $('.Cheque_Cq').removeClass('has-error');
   $('.Concepto_Cq').removeClass('has-error');
   $('.Name_Cq').removeClass('has-error');
   // alert(cant_fotos);

   //Creamos una instancia del Objeto FormDara.
   var filarch = new FormData();
   /* Como son multiples archivos creamos un ciclo for que recorra la el arreglo de los archivos seleccionados en el input
   Este y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como
   indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
   for(i=0; i<archivo.length; i++){
    filarch.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice diferente
   }
   filarch.append('opc','0');
   filarch.append('Name_Cq',$('#Name_Cq').val());
   filarch.append('Importe_Cq',$('#Importe_Cq').val());
   filarch.append('Banco_Cq',$('#Banco_Cq').val());
   filarch.append('Cuenta_Cq',$('#Cuenta_Cq').val());
   filarch.append('Cheque_Cq',$('#Cheque_Cq').val());
   filarch.append('Concepto_Cq',$('#Concepto_Cq').val());
   filarch.append('date',$('#date').val());
   filarch.append('Destino',$('#txtDestinos').val());

   /*Ejecutamos la función ajax de jQuery*/
   $.ajax({
    url:'controlador/finanzas/cliente/pane_cheques_c.php', //Url a donde la enviaremos
    type:'POST', //Metodo que usaremos
    contentType:false, //Debe estar en false para que pase el objeto sin procesar
    data:filarch, //Le pasamos el objeto que creamos con los archivos
    processData:false, //Debe estar en false para que JQuery no procese los datos la enviar
    cache:false, //Para que el formulario no guarde cache
    beforeSend: function() {
     $('#Resul').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span> Guardando...</label>");
    },
    success:function(data) {
     // alert(data);
     Tb_cheques(1);
     Select_Bancos();

     $('#Name_Cq').val('');
     $('#Importe_Cq').val('');
     $('#Banco_Cq').val('');
     $('#Cuenta_Cq').val('');
     $('#Cheque_Cq').val('');
     $('#Concepto_Cq').val('');
     $('#txtCuenta').html('');
     $('#Resul').html(data);

     setTimeout('$(\'#Resul\').html(\'\');',3000);
    }
   });
  }
  else{
   $('#Resul').html("<label class='text-danger'><span class='icon-cancel'></span> Seleccionar el cheque escaneado</label>");
  }
 }
}

function Tb_cheques(pag){
 date = $('#date').val();
// alert(pag);
 $.ajax({
  type: "POST",
  url:'controlador/finanzas/cliente/pane_cheques_v.php',
  data:'opc=1&pag='+pag+'&date='+$('#date').val()+'&date2=',
  success:function(data) {
   $('.tb_cheques').html(data);
  }
 });
}

function Delete_Cheque(id) {
 $.ajax({
  type: "POST",
  url:'controlador/finanzas/cliente/pane_cheques_c.php',
  data:'opc=1&id='+id,
  success:function(data) {
   Tb_cheques(1);
  }
 });
}

function Print_cheques(id){
 myWindow = window.open("recursos/pdf/cheques_pdf.php?top="+id, "_blank", "width=750, height=700");
}
