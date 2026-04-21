
function label_input_subtotal(idC,idS) {
 $('#sub'+idC+'-'+idS).html('<input id="InpSub_'+idC+'-'+idS+'" type="text" class="form-control input-xs" onkeypress="if(event.keyCode == 13) input_label_subtotal('+idC+','+idS+');">');
 $('#InpSub_'+idC+'-'+idS).focus();
}

function input_label_subtotal(idC,idS){
 $('#InpSub_'+idC+'-'+idS).attr('disabled','disabled');

 var valor = $('#InpSub_'+idC+'-'+idS).val();

 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/pane_ingresos_c.php',
  data:'opc=1&idC='+idC+'&idS='+idS+'&date='+$('#date').val()+'&valor='+valor,
  success:function(data){

   // $('#Group_Res').html(data);
   var res = eval(data);
   con = res.length;
   cont = (con-2)/2;

   // $('#Group_Res').append('cont='+cont);
   $('#sub'+idC+'-'+idS).html('<label class="pointer" style=" width:100%; height:100%;" onClick="label_input_subtotal('+idC+','+idS+');">$ '+res[0]+'</label>');
   $('#total1'+idS).html('<label >$ '+res[1]+'</label>');
   for (var i = 0; i < cont; i++) {
    // $('#Group_Res').append('&id='+(res[i+2])+' & valor='+(res[i+(cont+2)]));
    $('#Imp'+idS+'-'+res[i+2]).html('<label>$ '+res[i+(cont+2)]+'</label>');
   }
   Calcular_diferencia(idS);
  }
 });
}

/*-----------------------------------*/
/* FORMAS DE PAGO INPUT
/*-----------------------------------*/

function label_input_formaspago(idS,idFP,idC){

 $('#fp'+idS+'-'+idFP).html('<input id="InpImp_'+idS+'-'+idFP+'" type="text" class="form-control input-xs" onkeypress="if(event.keyCode == 13) input_label_formaspago('+idS+','+idFP+','+idC+');">');

 $('#InpImp_'+idS+'-'+idFP).focus();
}



function input_label_formaspago(idS,idFP,idC){

 $('#InpImp_'+idS+'-'+idFP).attr('disabled','disabled');
 var valor = $('#InpImp_'+idS+'-'+idFP).val();

 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/pane_ingresos_c.php',
  data:'opc=4&idS='+idS+'&idFP='+idFP+'&date='+$('#date').val()+'&valor='+valor+'&idC='+idC,
  success:function(data){

   // $('#fp'+idS+'-'+idFP).html('<label class=" pointer" style=" width:100%; height:100%;" onClick="label_input_formaspago('+idS+','+idFP+');">'+data+'</label>');

   Subcategoria(idC);

   // $('#btnGral').html(data);
   // if ( data != 'L') {
   //
   //
   //
   //
   //  Z_formasPago(idC,idS);
   //
   //
   // }else {
   //  $('#Group_Res').html('<h4 class="text-danger"><strong><span class="icon-attention"></span> Es necesario ingresar la tarifa para continuar...</strong></h4>');
   //  $('#fp'+idS+'-'+idFP).html('<label class="pointer" style=" width:100%; height:100%;" onClick="label_input_formaspago('+idS+','+idFP+');">-</label>');
   // }
   //
   // Calcular_diferencia(idS);
   // var res = eval(data);
  }
 });
}

function Calcular_diferencia(idS){
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/pane_ingresos_c.php',
  data:'opc=3&idS='+idS+'&date='+$('#date').val(),
  success:function(data) {
   res = eval(data);

   //    $('#total1'+idS).html('<label>|||'+res[0]+'||||</label>');
   $('#total2'+idS).html('<label>'+res[1]+'</label>');
   $('#diferencia'+idS).html('<label>'+res[2]+'</label>');
   // Subcategoria(idS);
  }
 });
}

/*-----------------------------------*/
/*		Mod por desarrollo
/*-----------------------------------*/

function Z_formasPago(idC,idS) {

 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/argovia_mod.php',
  data:'opc=3&idS='+idS+'&date='+$('#date').val(),
  success:function(data) {
   res = eval(data);

   formasPago = res[0];

   input_formasPago(idC,idS,formasPago);


  }
 });
}

function input_formasPago(idC,idS,valor) {
 $('#InpSub_'+idC+'-'+idS).attr('disabled','disabled');
 $.ajax({
  type:'POST',
  url:'controlador/finanzas/cliente/pane_ingresos_c.php',
  data:'opc=1&idC='+idC+'&idS='+idS+'&date='+$('#date').val()+'&valor='+valor,
  success:function(data){

   var res = eval(data);
   con = res.length;

   cont = (con-2)/2;
   // $('#Group_Res').html('Formas pago '+ data+'con:'+con);
   // $('#Group_Res').append('cont='+cont);
   // $('#sub'+idC+'-'+idS).html('<label class="pointer text-primary" style=" width:100%; height:100%;" onClick="label_input_subtotal('+idC+','+idS+');">$ '+res[0]+'</label>');
   // $('#total1'+idS).html('<label >$ '+res[1]+'</label>');

   $('#sub'+idC+'-'+idS).html('<label class="pointer text-primary" style=" width:100%; height:100%;" >$  '+res[0]+'</label>');



   for (var i = 0; i < cont; i++) { // determina cuantos datos cuenta

    $('#Imp'+idS+'-'+res[i+2]).html('<label>$ '+res[i+(cont+2)]+'</label>');

   }

   Calcular_diferencia(idS);
  }

 });
}
