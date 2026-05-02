$(function(){
  tabla_productos();
});

function tabla_productos(){
  $.ajax({
    type: "POST",
    url: "controlador/dia/control_ventas.php",
    data: 'opc=0',
    success:function(data) {
      $('.tabla_productos').html(data);
    }
  });
}
