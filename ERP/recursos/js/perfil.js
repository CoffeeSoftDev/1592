function perfil() {
  $.ajax({
      type: "POST",
      url: 'controlador/admin.php',
      data: 'opc=1',
      success:function(data) {
        $('.modal-content').html(data);
      }
  });
}

function tarjeta() {
  $.ajax({
      type: "POST",
      url: 'controlador/tarjeta.php',
      // data: '',
      success:function(data) {
        $('.modal-content').html(data);
      }
  });
}

function Datos_User(){
  valor = true;
  if( !$('#Inpt_APass').val() ){
    valor = false;
    $('#Inpt_APass').focus();
    $('.Group_APass').addClass('has-error');
  }
  else if(!$('#Inpt_NPass').val() && !$('#Inpt_User').val()) {
    valor = false;
    $('#Inpt_User').focus();
    $('.Group_User').addClass('has-error');
    $('.Group_NPass').addClass('has-error');
    $('.Group_APass').removeClass('has-error');
  }

  if ( $('#Inpt_NPass').val() ) {
    if($('#Inpt_NPass').val() != $('#Inpt_NPass2').val()){
      valor = false;
      $('#Inpt_NPass2').focus();
      $('.Group_NPass2').addClass('has-error');
      $('.Group_User').removeClass('has-error');
      $('.Group_NPass').removeClass('has-error');
      $('.Group_APass').removeClass('has-error');
    }
  }

  if (valor) {
    $('.Group_User').removeClass('has-error');
    $('.Group_NPass').removeClass('has-error');
    $('.Group_NPass2').removeClass('has-error');
    $('.Group_APass').removeClass('has-error');
    valores = 'user='+$('#Inpt_User').val()+'&Apass='+$('#Inpt_APass').val()+'&NPass='+$('#Inpt_NPass').val();
    alert(valores);

    $.ajax({
        type: "POST",
        url: 'controlador/admin.php',
        data: 'opc=2&'+valores,
        success:function(data) {
          // $('#Res_Perfil').html(data);
          if (data == 0) {
            $('#Res_Perfil').html('<label class="text-danger"><span class="icon-cancel"></span> Contraseña no valida</label>');
          }
          else if (data == 1) {
            $('#Res_Perfil').html('<label class="text-success"><span class="icon-ok"></span> Ok</label>');
            $('.modal').modal('toggle');
          }
          else if (data == 2) {
            window.location = 'salir';
          }
        }
    });
  }
}
