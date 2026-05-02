$(function(){
 Select_Impuestos();
 Select_TMovimiento();
 Select_Categorias();
 Select_TbImpuestos(1);
 Select_TbCategorias(1);
 Select_TbSubCategorias(1);
 Select_TbFormasPago(1);
 setTimeout(inicio, 100);
 tabPane(1);
});

function inicio() {
 //Inputs Numericos
 $('#Valor_Imp').numeric();
 $('#Valor_Imp').numeric(".");
}
//MOVIMIENTOS
function Select_TMovimiento(){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=6',
  success:function(data){
   // alert(data);
   $('#Select_movimiento').html(data);
  }
 });
}


function tabPane(id) {
 if (id == 1) {
  $('#tab1').show();
  $('#tab2').hide();

 } else if (id == 2) {
  $('#tab2').show();
  $('#tab1').hide();
   verDestino(1);
   verCuentas(1);
 }
}


//IMPUESTOS
function Select_Impuestos(){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=0',
  success:function(data){
   // alert(data);
   $('#Select_Impuestos').html(data);
   $('#ImpIng').multipleSelect();
  }
 });
}
function Select_TbImpuestos(pag){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=2&pag='+pag,
  success:function(data){
   // alert(data);
   $('#tb_imp').html(data);
  }
 });
}
function Insert_Impuestos(){
 var valor = true;
 var name = '';
 var percent = '';

 if ( !$('#Impuesto').val() ) {
  valor = false;
  $('#Impuesto').focus();
  $('.inp_impuesto').addClass('has-error');
  $('.inp_valor').removeClass('has-error');
 }
 else if ( !$('#Valor_Imp').val() ) {
  valor = false;
  $('#Valor_Imp').focus();
  $('.inp_valor').addClass('has-error');
  $('.inp_impuesto').removeClass('has-error');
 }

 if ( valor ) {
  name = $('#Impuesto').val();
  percent = $('#Valor_Imp').val();
  $('.inp_valor').removeClass('has-error');
  $('.inp_impuesto').removeClass('has-error');
  $.ajax({
   type:'POST',
   url:'controlador/finanzas/admin/catalogo_c.php',
   data:'opc=1&name='+name+'&valor='+percent,
   success:function(data) {
    if(data == 1){
     $('#Impuesto').val('');
     $('#Valor_Imp').val('');
     $('#Res_Impuesto').html('<label class="text-succes"><span class="icon-ok"></span> El contenido se guardo correctamente. </label>');
    }
    else {
     $('#Res_Impuesto').html('<label class="text-danger"><span class="icon-attention"></span> El contenido ya existe. </label>');
    }
    Select_Impuestos();
    Select_TbImpuestos(1);
    setTimeout("$('#Res_Impuesto').html('')", 3500);
   }
  });
 }
}
function Delete_Impuestos(id){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_c.php',
  data:'opc=2&id='+id,
  success:function(data){
   Select_TbImpuestos(1);
   Select_Impuestos();
   $('#Res_Impuesto').html('');
   // setTimeout($('#Res_Impuesto').html(''), 1000);
  }
 });
}

//CATEGORIAS
function Select_Categorias(){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=1',
  success:function(data){
   // alert(data);
   $('#Select_Categorias').html(data);
  }
 });
}
function Select_TbCategorias(pag) {
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=3&pag='+pag,
  success:function(data){
   // alert(data);
   $('#tb_Cat').html(data);
  }
 });
}
function Insert_Categorias(){
 var valor = true;
 var categoria = '';
 var impuesto = '';
 var movimiento = '';
 if ( !$('#Categoria').val() ) {
  $('#Categoria').focus();
  $('.inp_categoria').addClass('has-error');
  $('#Select_Impuestos').removeClass('has-error');
  valor = false;
 }

 if ( valor ) {
  cont = 0;
  impuesto = '';
  if ( $('#ImpIng').val() != null ) {
   //OBTENER EL VALOR DE TODAS LAS UDN
   res = eval($('#ImpIng').val());
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    impuesto = impuesto + '&impuesto'+i+'='+res[i];
   }
  }

  $('.inp_categoria').removeClass('has-error');
  $('#Select_Impuestos').removeClass('has-error');
  categoria = $('#Categoria').val();
  movimiento = $('#TMovimientos').val();

  $.ajax({
   type:'POST',
   url:'controlador/finanzas/admin/catalogo_c.php',
   data:'opc=3&cont='+cont+'&categoria='+categoria+'&movimiento='+movimiento+impuesto,
   success:function(data) {
    if(data == 1){
     $('.ms-drop ul li label input').removeAttr("checked");
     $('.ms-choice span').html('');
     $('#Categoria').val('');
     $('#ImpIng').val('');
     $('#Res_Categoria').html('<label class="text-succes"><span class="icon-ok"></span> El contenido se guardo correctamente. </label>');
    }
    else {
     $('#Res_Categoria').html('<label class="text-danger"><span class="icon-attention"></span> El contenido ya existe. </label>');
    }
    Select_TbCategorias(1);
    Select_Categorias();
    setTimeout("$('#Res_Categoria').html('')", 3500);
   }
  });
 }
}
function Delet_Categoria(id){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_c.php',
  data:'opc=4&id='+id,
  success:function(data){
   Select_TbCategorias(1);
   Select_TbSubCategorias(1);
   Select_Categorias();
  }
 });
}

//SUBCATEGORIAS
function Select_TbSubCategorias(pag) {
 $.ajax ({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=4&pag='+pag,
  success:function(data){
   $('#tb_SubCat').html(data);
  }
 });
}
function Insert_Subcategoria(){
 var valor = true;
 var subcategoria = '';
 var categoria = '';
 if ( !$('#subcategoria').val() ) {
  $('#subcategoria').focus();
  $('.inp_subcategoria').addClass('has-error');
  $('#Select_Categorias').removeClass('has-error');
  valor = false;
 }
 else if ( $('#ImpCat').val() == 0 ) {
  $('.inp_subcategoria').removeClass('has-error');
  $('#Select_Categorias').addClass('has-error');
  valor = false;
 }

 if ( valor ) {
  $('.inp_subcategoria').removeClass('has-error');
  $('#Select_Categorias').removeClass('has-error');
  subcategoria = $('#subcategoria').val();
  categoria = $('#ImpCat').val();
  $.ajax({
   type:'POST',
   url:'controlador/finanzas/admin/catalogo_c.php',
   data:'opc=5&idC='+categoria+'&subcategoria='+subcategoria,
   success:function(data) {
    if(data == 1){
     $('#subcategoria').val('');
     $('#Res_Subcategoria').html('<label class="text-succes"><span class="icon-ok"></span> El contenido se guardo correctamente. </label>');
    }
    else {
     $('#Res_Subcategoria').html('<label class="text-danger"><span class="icon-attention"></span> El contenido ya existe. </label>');
    }
    Select_TbSubCategorias(1);
    setTimeout("$('#Res_Subcategoria').html('')", 3500);
   }
  });
 }
}
function Delete_SubCategoria(id){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_c.php',
  data:'opc=6&id='+id,
  success:function(data){
   Select_TbSubCategorias(1);
  }
 });
}

/*-----------------------------------*/
/* Destino
/*-----------------------------------*/

function AgregarDestino(){
 var valor = true;
 var fp    = '';

 if ( !$('#Destino').val() ) {

  valor = false;
  $('.txtDestino').addClass('has-error');
  $('#Destino').focus();
 }

 if ( valor ) {

  des  = $('#Destino').val();

  $.ajax({
   type:'POST',
   url:'controlador/finanzas/admin/catalogo_c.php',
   data:'opc=9&des='+des,
   success:function(data) {
    if(data == 1){
     $('#Destino').val('');

     $('#Res_destino').html('<label class="text-succes">'+
     '<span class="icon-ok"></span>  El contenido se guardo correctamente. </label>');
    }
    else {
     $('#Res_destino').html('<label class="text-danger"><span class="icon-attention"></span> El contenido ya existe. </label>');
    }

    verDestino(1);
    // setTimeout("$('#Res_destino').html('')", 3500);
   }
  });

 }
}





//FORMAS PAGO
function Select_TbFormasPago(pag) {
 $.ajax ({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=5&pag='+pag,
  success:function(data){
   // alert(data);
   $('#tb_FormasPago').html(data);
  }
 });
}

function Insert_FormasPago(){
 var valor = true;
 var fp = '';
 if ( !$('#FPago').val() ) {
  valor = false;
  $('.inp_fpago').addClass();
  $('#FPago').focus();
 }

 if ( valor ) {
  fp = $('#FPago').val();
  $.ajax({
   type:'POST',
   url:'controlador/finanzas/admin/catalogo_c.php',
   data:'opc=7&fp='+fp,
   success:function(data) {
    if(data == 1){
     $('#FPago').val('');
     $('#Res_FP').html('<label class="text-succes"><span class="icon-ok"></span> El contenido se guardo correctamente. </label>');
    }
    else {
     $('#Res_FP').html('<label class="text-danger"><span class="icon-attention"></span> El contenido ya existe. </label>');
    }
    Select_TbFormasPago(1);
    setTimeout("$('#Res_FP').html('')", 3500);
   }
  });
 }
}

function Delete_FormasPago(id){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_c.php',
  data:'opc=8&idFP='+id,
  success:function(data) {
   Select_TbFormasPago(1);
  }
 });
}

/*-----------------------------------*/
/*	 Cuenta
/*-----------------------------------*/
function verCuentas(pag) {
 $.ajax ({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=7&pag='+pag,
  success:function(data){
   // alert(data);
   $('#tb_Cuenta').html(data);
  }
 });
}

function AgregarCuenta(){
 var valor = true;
 var fp    = '';
alert();
 if ( !$('#txtCuenta').val() ) {

  valor = false;
  $('.Cuenta').addClass('has-error');
  $('#txtCuenta').focus();
 }

 if ( valor ) {

  des  = $('#txtCuenta').val();
$('.Cuenta').removeClass('has-error');
  $.ajax({
   type:'POST',
   url:'controlador/finanzas/admin/tdEdit.php',
   data:'opc=2&nombre='+des,
   success:function(data) {
    if(data == 1){
     $('#txtCuenta').val('');

     $('#Res_Cuenta').html('<label class="text-succes">'+
     '<span class="icon-ok"></span>  El contenido se guardo correctamente. </label>');
    }
    else {
     $('#Res_Cuenta').html('<label class="text-danger"><span class="icon-attention"></span> El contenido ya existe. </label>');
    }

    verCuentas(1);
    // setTimeout("$('#Res_destino').html('')", 3500);
   }
  });

 }
}


/*-----------------------------------*/
/*	 Destino - new
/*-----------------------------------*/

function verDestino(pag) {
 $.ajax ({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_v.php',
  data:'opc=8&pag='+pag,
  success:function(data){

   $('#tb_des').html(data);
  }
 });
}

function EliminarDestino(id){
 alert('');
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/admin/catalogo_c.php',
  data:'opc=10&des='+id,
  success:function(data) {
   verDestino(1);
  }
 });
}


function	tdEdit(txt,id_num,valor){

 $.ajax({
  type:	"POST",
  url:	"controlador/finanzas/admin/tdEdit.php",
  data:'txt='+txt+'&id_num='+id_num+'&valor='+valor+'&opc=0',
  success:function(rp)	{
   var	data	=	eval(rp);
   $('#'+txt +''+id_num).html(data[0]);
  }
 });

}

function	Edit(id_num,txt){
 registro =  $('#Edit'+txt+id_num).val();

 $.ajax({
  type:	"POST",
  url:	"controlador/finanzas/admin/tdEdit.php",
  data:'opc=1&txt=' +txt+'&id_num='+id_num+'&valor='+registro,
  success:function(rp)	{
   var	data	=	eval(rp);
   $('#'+txt+id_num).html(data[0]);
  }
 });
}
