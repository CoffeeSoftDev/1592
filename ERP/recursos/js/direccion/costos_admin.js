$(document).ready(main);

function main() {
 // valores = 'date='+$('#date').val();
 valores = 'date = 2019-10-29';
  $.ajax({
   type: "POST",
   url: 'controlador/finanzas/cliente/caratula_v.php',
   data: 'date=2018-10-29',
   beforeSend: function() {
     $('.tab_content').html("<h4 class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando datos...</h4>");
   },
   success:function(data) {
    $('.tab_content').html(data);
   }
  });
}
