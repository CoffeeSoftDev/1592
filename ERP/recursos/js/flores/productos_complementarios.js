
function btn_nuevo_producto() {
  producto     = $("#modal_txt").val();
  venta        = $('#complemento_venta').val();
  cantidad        = $('#complemento_cantidad').val();

  $.ajax({
  type:"POST",
  url: "controlador/flores/user/productos_complementarios.php",
  data: "opc=2&"+"producto="+producto+"&venta="+venta+"&cantidad="+cantidad,
  beforeSend:function(){
  // $('').html(Load_sm());
  },
  success:function(rp){
  // data = eval(rp);
  }
  });

}

modal_complementos = null;

function __modal(id,$key) {
  nombre_producto  = $('#txtItem'+$key).val();

  $.ajax({
    type: "POST",
    url: "controlador/flores/user/pedidos.php",
    data: "opc=12&id=" + id ,

    success: function (rp) {
      data = eval(rp);

        modal_complementos = bootbox.dialog({
    title: " "+nombre_producto+" ",
    // size: "small",
          message: '' + data
  });
    }
  });
}
