$(document).ready(main);

function main() {

 initComponents();
}

/*-----------------------------------*/
/*   Inicial
/*-----------------------------------*/

function initComponents(){
 var f = new Date();
 $('#fechaActual').html(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());


 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/NuevoInventario.php",
  data:'opc=1',
  success:function(rp) {
   var data = eval(rp);
   $('#txtTitulo').html(data[0]);

   if (data[1]==1) { // estado es "PROCESO"
   $('#btnNuevoInventario').addClass('disabled');
   $('#txtZona').attr('disabled','disabled');;
   $('#txtArea').attr('disabled','disabled');;

   $('#btnNuevoInventario').addClass('disabled');
   $('#nombre').removeAttr('disabled');
   $('#btnSave').removeClass('disabled');
   $('#btnCancel').removeClass('disabled');
   $('#txtLoad').html('');

   $('#txtZona').append(data[2]);
   $('#txtArea').append(data[3]);
   LISTA_INVENTARIO();
  }else {
   cbSELECT(5);
   $('#txtZona').removeAttr('disabled');
   $('#txtArea').removeAttr('disabled');

   $('#nombre').attr('disabled','disabled');
   $('#btnNuevoInventario').removeClass('disabled');
   $('#btnSave').addClass('disabled');
   $('#btnCancel').addClass('disabled');
   bgImagen();
  }
 }
});
}

function NuevoInventario(){

 area = $('#txtArea').val();
 zona = $('#txtZona').val();

 if (zona == 0 || zona== '0') {

  $('#cbZona').addClass('has-error');

  alert('Debes elegir una zona para continuar..');

 }else if (area == 0 || area== '0'){
  $('#cbZona').removeClass('has-error');
  $('#cbArea').addClass('has-error');
  alert('Debes elegir una área para continuar..');

 }else{
  $('#cbZona').removeClass('has-error');
  $('#cbArea').removeClass('has-error');
  $.ajax({
   type: "POST",
   url: "controlador/mtto/admin/NuevoInventario.php",
   data:'opc=2&area='+area+'&zona='+zona,
   beforeSend:function(rp) {
    $('#txtLoad').html('<center>'+
    '<h4><span><i class="icon-spin6 animate-spin fa-2 fa-fw text-primary"></i> Creando reporte de inventario...</span></h4></center><br>');
   },
   success:function(rp) {
    var data = eval(rp);
    $('#txtLoad').html('')
    initComponents();
   }
  });

 }
}

function cbSELECT(opc) {
 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/cbAlmacen.php",
  data:'opc='+opc,
  success:function(rp) {
   var data = eval(rp);

   switch (opc) {
    case 5: $('#cbZona').html(data[0]);   break;
   }

  }
 });
}

function cbArea(opc) {
 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/cbAlmacen.php",
  data:'opc='+opc+'&zona='+$('#txtZona').val(),
  success:function(rp) {
   var data = eval(rp);

   $('#cbArea').html(data[0]);

  }
 });
}

/*===========================================
*  Busqueda
=============================================*/

function buscarLista(){
 area     =  $('#txtArea').val();
 zona     =  $('#txtZona').val();

 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/busquedaLista.php",
  data:'opc=1&busqueda='+$('#nombre').val()+'&area='+area+'&zona='+zona,
  success:function(rp) {
   var data = eval(rp);
   $('#tbFormato1').html(data[0]);


  }
 });


}


function addMovimiento(id,teorico,stock){
 var fisico  = $('#mov'+id).val(); // Fisico en almacen.
 actual      = 0;
 // actual          =  teorico + parseFloat(movimiento);

 if (teorico < fisico ) { //INGRESO DE ALMACEN
  actual  = fisico  - teorico;
 }else {
  actual  = teorico - fisico;
 }

 $.ajax({
  url: "controlador/mtto/admin/NuevoInventario.php",
  type: "POST",
  data:'opc=3&fisicoAlmacen='+fisico+'&id='+id+'&cantidad='+teorico,

  beforeSend:function () {
   $('#add'+id).addClass('disabled');
   $('#add'+id).html('<span class="icon-spin6 animate-spin"></span>');
  },

  success:function(rp) {
   var data = eval(rp);

   $('#dataCol'+data[0]).addClass('bg-default');
   $('#add'+data[0]).addClass('disabled');
   $('#mov'+data[0]).attr('disabled','disabled');
   $('#txtCantidad'+data[0]).val(data[1]);
   $('#add'+id).html('<span>OK</span>');
   LISTA_INVENTARIO();
  }
 });
 // }

}

function QuitarDeLista(id,idProducto){
 anterior   = $('#anterior'+id).val();


 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/NuevoInventario.php",
  data:'opc=4&id='+id+'&anterior='+anterior+'&idProducto='+idProducto,
  beforeSend:function () {
   $('#delete'+id).addClass('disabled');
   $('#delete'+id).html('<span class="icon-spin6 animate-spin"></span>');
  },

  success:function(rp) {

   LISTA_INVENTARIO();
   buscarLista();
  }
 });
}



function LISTA_INVENTARIO(){
 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/busquedaLista.php",
  data:'opc=2',
  success:function(rp) {
   var data = eval(rp);
   $('#tbFormato2').html(data[0]);
  }
 });
}


/*===========================================
*									ver Modal
=============================================*/

function verModal(){

 Search_Name();
 Search_Marca();

 $('#okRegistroU').html('');

 $.ajax({
  type: "POST",
  url: "controlador/mtto/Almacen_FormularioRegistro.php",
  success:function(rp) {
   console.log(rp);
   var data = eval(rp);
   var f      = new Date();
   $('#codeFormulario').html(data[0]);
   $('#date').combodate({
    minYear: f.getFullYear(),
    maxYear: f.getFullYear()+10
   });
   validate();
  }

 });


}

function validate(){
 $('#defaultForm').bootstrapValidator({
  //        live: 'disabled',
  message: 'Este valor no es valido',
  feedbackIcons: {
   valid: 'fa fa-check-circle fa-2x',
   invalid: 'fa fa-times-circle fa-2x',
   validating: 'glyphicon glyphicon-refresh'
  },
  fields: {

   articulo: {
    group: '#art_Group',
    validators: {
     notEmpty: {
      message: 'El campo articulo no puede quedar vacio'
     }
    }
   },

   minimo: {
    group: '#min_Group',
    validators: {
     notEmpty: {
      message: '* El campo es requerido '
     }
    }
   },

   stock: {
    group: '#stock_group',
    validators: {
     notEmpty: {
      message: '* El campo es requerido '
     }
    }
   },

   costoEnt: {
    group: '#cEnt',
    validators: {
     notEmpty: {
      message: '* El campo es requerido '
     }
    }
   },
   costoSal: {
    group: '#cSal',
    validators: {
     notEmpty: {
      message: '* El campo es requerido '
     }
    }
   },





  }// fields


 });

}



// add Modal ----

availableName = [];
availableMarca = [];


function TiempoVida() {
 // alert($('#date').val());
}

function Search_Name() {

 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=4',
  success:function(data) {
   res = eval(data);

   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableName[i] = res[i];
   }
  }
 });

 $( function() {

  $( "#txtEquipo" ).autocomplete({
   source: availableName,
   appendTo: "#Producto"  //Linea nueva, agrego el id del modal

  });

 });

 $( "#txtEquipo" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#txtEquipo').val();
  CodigoEquipo();
 } );

}

function Search_Marca() {

 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=5',
  success:function(data) {
   res = eval(data);

   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableMarca[i] = res[i];
   }
  }
 });

 $( function() {

  $( "#txtMarca" ).autocomplete({
   source: availableMarca,
   appendTo: "#Producto"  //Linea nueva, agrego el id del modal
  });

 });

 $( "#txtMarca" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#txtMarca').val();

 } );

}

function CodigoEquipo(){
 /* Para obtener el texto */
 var combo = document.getElementById("txtZona");
 var zona = combo.options[combo.selectedIndex].text;
 // alert('Codigo');
 Equipo    = $('#txtEquipo').val();
 area      = $('#txtFamilia').val();
 clase      = $('#txtClase').val();

 data1     = zona.substr(0,2);
 data2     = area.substr(0,2);
 data3     = Equipo.substr(0,2);
 // ---
 $('#Codigo').val(data1+''+data2+''+data3);
 // if (Equipo!='' && area!='') {
 //
 $.ajax({
  type: "POST",
  url: "controlador/mtto/CodigoEquipos.php",
  data:'area='+clase+'&Equipo='+Equipo+'&opc=2',
  success:function(rp) {
   var data = eval(rp);

   // alert(data);
   $('#Codigo').val(data1+'-'+area+'-'+data3.toUpperCase()+data[2]+'-'+data[1]);
  }
 });
 // }

}

function verClase(){
 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/cbAlmacen.php",
  data:'Familia='+$('#txtFamilia').val()+'&opc=4',
  success:function(rp) {
   var data = eval(rp);
   document.getElementById('txtClase').disabled=false;
   $('#cbClase').html(data[0]);
  }
 });
}

function NUEVO(opc,idAl){

 $("#defaultForm").bootstrapValidator().on('success.form.bv', function(e) {

  Codigo     = $('#Codigo').val();
  articulo   = $('#txtEquipo').val();
  zona       = $('#txtZona').val();
  familia    = $('#txtFamilia').val();
  clase      = $('#txtClase').val();
  marca      = $('#txtMarca').val();
  // --
  min        = $('#txtStockMin').val();
  stock      = $('#txtStock').val();
  desc       = $('#txtDesc').val();
  costo1     = $('#txtCosto1').val();
  costo2     = $('#txtCosto2').val();
  util       = $('#date').val();
  var f      = new Date();
  hoy        = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();

  gral ='art='+articulo+
  '&zona='+zona+'&familia='+familia+
  '&clase='+clase+'&marca='+marca+'&codigo='+Codigo+'&hoy='+hoy;

  ps ='&min='+min+
  '&stock='+stock+'&desc='+desc+
  '&costo1='+costo1+'&costo2='+costo2+'&util='+util ;


  switch (opc) {

   case 1:// Agregar Info

   // alert(gral+ps);
   $.ajax({
    type: "POST",
    url: 'controlador/mtto/admin/almacen_insertar_producto.php',
    data:gral+ps ,
    beforeSend: function() {
     $('#Resultado').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
    },
    success:function(respuesta) {
     data = eval(respuesta);
     // LimpiarModal();
     $('#okRegistro').html(data[0]);

     if (data[1]!=2) {
      verModal();
     }
    }
   });

   break;

   case 2: // Modificar

   $.ajax({
    type: "POST",
    url: 'controlador/mtto/admin/almacen_producto_modificar.php',
    data:gral+ps+'&idAl='+idAl ,
    beforeSend: function() {
     $('#Resultado').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
    },
    success:function(respuesta) {
     data = eval(respuesta);
     // LimpiarModal();
     $('#okRegistroU').html(data[0]);
     blockData();
     if (data[1]!=2) {
      $('#Resultado').html('');
      // verModal();
     }
    }
   });

   break;

  }


 });

}



/*===========================================
*									GUARDAR O CANCELAR FOLIO
=============================================*/

function UImodal(opc){

 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/UI_MODAL.php",
  data:'opc='+opc,
  success:function(rp) {
   var data = eval(rp);
   $('#code').html(data[0]);
  }

 });
}

function EliminarFolio(id){
 var motivo = $('#txtMotivo').val();

 if (motivo==null || motivo == "") {
  $('#txtMot').html('<span class="text-danger">Debes indicar un motivo para continuar..</span>');
  $('#txtMotivo').focus();
  $('#ErrorMotivo').addClass('has-error');
 }else {
  $('#txtMot').html('');
  $('#ErrorMotivo').removeClass('has-error');

  $.ajax({
   type: "POST",
   url: "controlador/mtto/admin/NuevoInventario.php",
   data:'opc=5&id='+id+'&motivo='+motivo,
   success:function(rp) {
    var data = eval(rp);
    $('#code').html(data[0]);
    initComponents();
    $('#tbFormato2').html('');
   }
  });
 }
}

function GuardarFolio(id){
 var detalles = $('#txtDetalles').val();
 var autorizo = $('#txtAutorizar').val();

 if (autorizo == "") {
  $('#txtAutorizar').focus();
  $('#ErrorPass').addClass('has-error');
  $('#txtRP').html('<p class="text-danger">Es necesario indicar el responsable del inventario. </p>');
 }else {
  $('#ErrorPass').addClass('has-error');
  $('#txtRP').html('');
  $.ajax({
   type: "POST",
   url: "controlador/mtto/admin/NuevoInventario.php",
   data:'opc=6&id='+id+'&nota='+detalles+'&autorizo='+autorizo,
   success:function(rp) {
    data = eval(rp);
    $('#code').html(data[0]);
    bgImagen();
    // var data = eval(rp);

    // initComponents();
   }
  });
 }
}

function Imprimir(id) {
 myWindow = window.open("recursos/pdf/pdf_productos.php?id="+id
 , "_blank", "width=350, height=500");
}

function bgImagen() {
 $('#tbFormato1').html('');
 $('#tbFormato2').html('');
 $('#txtLoad').html('<center><br><img src="recursos/img/logo.png" style=" max-width:30%; padding-bottom: 30px; " class="img-res"></center>');
}
