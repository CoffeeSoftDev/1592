
function tb_movimientos(){
  var date1 = $('#reportrange').data('daterangepicker').startDate.format('YYYY-MM-DD');
  var date2 = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');

  valores = 'date1='+date1+'&date2='+date2+'&idProducto=0';
  if ( !$('#ipt_Producto').val() ) {
   $('#ipt_Producto').focus();
   $('.gb_Producto').addClass('has-error');
  }
  else{
    $('.gb_Producto').removeClass('has-error');
    var value = $('#ipt_Producto').val();
    var valor = $('#productos [value="' + value + '"]').data('value');
    valores = 'date1='+date1+'&date2='+date2+'&idProducto='+valor;
  }


  $.ajax({
   type: "POST",
   url: 'controlador/dia/movimientos.php',
   data: 'opc=0&'+valores,
   beforeSend: function() {
     $('.tb_movimientos').html('<br><h4 class="text-info text-center"><span class="icon-spin6 animate-spin"></span> Espere un momento.</h4>');
   },
   success:function(data) {
    $('.tb_movimientos').html(data);
    simple_data_table('#tb_movimiento_full');
    $('.previous').html('Anterior');
    $('.next').html('Siguiente');
    $('.dataTables_filter').addClass('hide');
   }
  });
}

function Buscar_Datos() {
  var value = $('#ipt_Producto').val();
  var valor = $('#productos [value="' + value + '"]').data('value');

  var date = $('#reportrange').data('daterangepicker').endDate.format('YYYY-MM-DD');

  $.ajax({
   type: "POST",
   url: 'controlador/dia/movimientos.php',
   data: 'opc=1&idProducto='+valor+'&date='+date,
   success:function(data) {
     res = eval(data);
     $('#disponible').val(res[0]);
     $('#status').html(res[1]);
     tb_movimientos();
   }
  });
}
