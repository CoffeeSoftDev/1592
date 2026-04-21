function login() {
 valor = true;
 if ( !$('#user').val() ) {
  $('#user').focus();
  $('.Group_user').addClass('has-error');
  $('.Group_pass').removeClass('has-error');
  valor = false;
 }
 else if ( !$('#pass').val() ) {
  $('#pass').focus();
  $('.Group_pass').addClass('has-error');
  $('.Group_user').removeClass('has-error');
  valor = false;
 }

 if ( valor ) {
  $('.Group_user').removeClass('has-error');
  $('.Group_pass').removeClass('has-error');

  valores = 'user='+$('#user').val()+'&pass='+$('#pass').val();

  $.ajax({
   type: "POST",
   url: 'controlador/login.php',
   data: 'opc=0&'+valores,
   beforeSend: function() {
    $('.Res_Login').html("<label class='col-xs-12 col-sm-12 text-center text-primary'><span class='icon-spin6 animate-spin'></span> Iniciando Sesión...</label>");
   },
   success:function(data) {

    // alert(data);
    $('.Res_Login').html(data);

   }
  });
 }
}

function nivel(nivel,area,slash) {
  $.ajax({
   type: "POST",
   url: 'controlador/login.php',
   data: 'opc=1&nivel='+nivel+'&area='+area+'&slash='+slash,
   success:function(data) {
    //  alert(data);
    $('.res').html(data);
   }
  });
}
