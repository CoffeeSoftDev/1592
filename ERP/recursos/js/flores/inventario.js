$(document).ready(main);
url = 'controlador/flores/admin/';

function main() {
 consultar_datos();

}

/*-----------------------------------*/
/*		PEDIDOS **
/*-----------------------------------*/
function	verPedidosFlores(){
  
  $.ajax({
    type: "POST",
    url: url + "pedidos_flores.php",
    data: 'opc=0',

    beforeSend: function () { 
      $('#format_pedidos').html(Load_sm());
    },

    success: function (rp) {
      var data = eval(rp);
      $('#format_pedidos').html(data[0]);      
    }
    
  });

}
/*-----------------------------------*/
/*		DISPONIBILIDAD **
/*-----------------------------------*/
function	consultar_datos(){
 date = $('#date').val();

 $.ajax({
  type:	"POST",
  url: "controlador/flores/admin/disponibilidad.php",
  data:'opc=0&date='+date,
  success:function(rp)	{
   var	data	=	eval(rp);

   if (data[0]==0) {
    $('#area-folio').html(set_btn_form());
    $('#ultimo_formato').html(data[3]);

   }else {
    $('#ultimo_formato').html('');
    $('#area-folio').html('<h4 class="text-danger">Folio: '+data[1] +'</h4>');
    disponibilidad(data[2]);
   }
  }
 });
}


function crear() {
 date = $('#date').val();

 $.ajax({
  type:	"POST",
  url: "controlador/flores/admin/disponibilidad.php",
  data:'opc=1&date='+date,
  beforeSend:function(){

   $('#btn-formato').addClass('disabled');
   $('#tbDisponibilidad').html(Load_sm());
  },
  success:function(rp)	{

   consultar_datos();
  }
 });

}

function disponibilidad(date) {
// alert(date);
 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/disponibilidad.php",
  data:'opc=2&date='+date,
  beforeSend: function() {
   $('#tbDisponibilidad').html(Load_sm());
  },
  success: function(rp) {
   var data = eval(rp);
   $('#tbDisponibilidad').html(data[0]);

  }
 });
}

function sub(txt, id, val,costo) {
 val = $("#txt" + txt + id).val();

 total  = costo * val;
 $('#sub'+ txt +id).val(total);
}

function mod_col(txt, id, val) {
 date = $("#date").val();

// alert($("#txt" + txt + id).val());
 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/disponibilidad.php",
  data:"opc=3&val=" +$("#txt" + txt + id).val() + "&txt=" +txt +"&id=" +id +"&date=" +date,
  beforeSend: function() {
   $("#txt" + txt + id).addClass("disabled");
  },
  success: function(rp) {
   data = eval(rp);
  
   $("#cant" + txt + "-" + id).html(data[0]);
  }
 });

}

function	ultimo_inventario(fecha){

 // alert(fecha);
 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/disponibilidad.php",
  data:'opc=6&date='+fecha,
  beforeSend: function() {
   $('#tbDisponibilidad').html(Load_sm());
  },
  success: function(rp) {
   var data = eval(rp);
   $('#tbDisponibilidad').html(data[0]);

  }
 });
}


/*-----------------------------------*/
/*		HISTORICO  **
/*-----------------------------------*/
function ver_historico() {

	$.ajax({
		type: 'POST',
		url: 'controlador/flores/admin/disponibilidad.php',
		data: 'opc=5',
		success: function(rp) {
			var data = eval(rp);
   $('#tb_historico').html(data[0]);
			simple_data_table('#table_view');

		}
	});
}

function	ver_detalles(fecha){

 // alert(fecha);
 $.ajax({
  type: "POST",
  url: "controlador/flores/admin/disponibilidad.php",
  data:'opc=6&date='+fecha,
  beforeSend: function() {
   $('#tb_historico').html(Load_sm());
  },
  success: function(rp) {
   var data = eval(rp);
   $('#tb_historico').html(data[0]);

  }
 });
}

// PESTAÑA DE CONSULTA DE INVENTARIOS

function ver_inventario() {
 
   $.ajax({
    type:"POST",
    url: url_file + "inventario.php",
    data:"opc=1",
    beforeSend:function(){
      $('tb_inventarios').html(Load_sm());
    },
    success:function(rp){
     data = eval(rp);
     $('tb_inventarios').html('Table');
    }
   });
}


