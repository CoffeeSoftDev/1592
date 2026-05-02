
urls = 'controlador/mtto/admin/';


function doc_valor(id) {

  categoria  = $('#txtCategoria').val();
  udn        = $('#select').val();
  area       = $('#txtArea').val();
  valor      = 'reporte_mtto';

  $.ajax({
  type: "POST",
  url: urls + "reporte_almacen.php",
  data: "opc=1&categoria="+categoria+"&id="+id+"&area="+area,

  beforeSend: function () {
    $('#report_mtto').html('<h4> Cargando data... </h4>');
  },

  success: function (rp) {
    data = eval(rp);
    $('#report_mtto').html(data[0]);

  }

});

}
