function tc_save(){
  valor = true;

  if( !$('#Ipt_Monto').val() ){
    clean();
    valor = false;
    $('#Ipt_Monto').focus();
    $('.gb_monto').addClass('has-error');
  }
  else if ( $('#terminal').val() == 0 ) {
    clean();
    valor = false;
    $('#terminal').focus();
    $('.gb_terminal').addClass('has-error');
  }
  else if ( $('#tipo_terminal').val() == 0 ) {
    clean();
    valor = false;
    $('#tipo_terminal').focus();
    $('.gb_tipo_terminal').addClass('has-error');
  }
  else if ( !$('#ipt_concepto').val() ) {
    clean();
    valor = false;
    $('#ipt_concepto').focus();
    $('.gb_ipt_concepto').addClass('has-error');
  }
  else if ( !$('#ipt_especificacion').val() ) {
    clean();
    valor = false;
    $('#ipt_especificacion').focus();
    $('.gb_ipt_especificacion').addClass('has-error');
  }
  else if ( !$('#ipt_cliente').val() ) {
    clean();
    valor = false;
    $('#ipt_cliente').focus();
    $('.gb_ipt_cliente').addClass('has-error');
  }
  else if ( !$('#ipt_autorizacion').val() ) {
    clean();
    valor = false;
    $('#ipt_autorizacion').focus();
    $('.gb_ipt_autorizacion').addClass('has-error');
  }

  if (valor) {
    valores = 'opc=0&id_folio='+$('#hide_id').val()+'&monto='+$('#Ipt_Monto').val()+'&terminal='+$('#terminal').val()+
    '&tipo_terminal='+$('#tipo_terminal').val()+'&concepto='+$('#ipt_concepto').val()+'&especificaci¨®n='+$('#ipt_especificacion').val()+
    '&cliente='+$('#ipt_cliente').val()+'&autorizacion='+$('#ipt_autorizacion').val()+'&observaciones='+$('#Observaciones').val();

    $.ajax({
     type: "POST",
     url:'controlador/finanzas/cliente/pane_tc_c.php',
     data: valores,
     success:function(rp) {
      data = eval(rp);
      $('.Res_all').html(rp);
      // $('#txt-Total').html(data[1]);
      clean();
      ver_tc();
     }
    });
  }
}

function clean(){
  $('.gb_monto').removeClass('has-error');
  $('.gb_terminal').removeClass('has-error');
  $('.gb_tipo_terminal').removeClass('has-error');
  $('.gb_ipt_concepto').removeClass('has-error');
  $('.gb_ipt_especificacion').removeClass('has-error');
  $('.gb_ipt_cliente').removeClass('has-error');
  $('.gb_ipt_autorizacion').removeClass('has-error');
  $('.gb_Observaciones  ').removeClass('has-error');
}

function Delete_TC(id){
  $.ajax({
   type: "POST",
   url:'controlador/finanzas/cliente/pane_tc_c.php',
   data: 'opc=2&idTC='+id,
   success:function(data) {
    ver_tc();
   }
  });
}
