$(document).ready(main);
function main() {
 ver_tabla(1);

}

/*-----------------------------------*/
/*		Init Components
/*-----------------------------------*/

availableName   = [];
availableMarca  = [];
availableNameU  = [];
availableMarcaU = [];
availableTags   = [];

function Busqueda() {

 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=1',
  success:function(data) {
   res = eval(data);
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableTags[i] = res[i];
   }
  }
 });

 $( function() {
  $( "#Area" ).autocomplete({
   source: availableTags,
   appendTo: "#Producto"  //Linea nueva, agrego el id del modal
  });

 });

 $( "#Area" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#Area').val();
  CodigoEquipo();
 } );
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

/*-----------------------------------*/
/*		  Productos
/*-----------------------------------*/

function ver_tabla(id) {

 $('#tbProductos').DataTable({
  destroy: true,
  scrollY:        "400px",
  scrollX:        true,
  scrollCollapse: true,
  paging:         false,
  fixedColumns:   {
   leftColumns: 1,
   rightColumns: 1
  },
  "aaSorting": [],
  processing: true,
  "ajax":{
   "method":"POST",
   "url":"controlador/mtto/admin/data_productos.php",
   "data": function ( d ) {
    d.opc = 1;

   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [5,6,7] },
   { className: "text-center", "targets": [0,1,2,4,8,9] }
  ],

  "columns":[
   {"data":"Codigo"},
   {"data":"zona"},
   {"data":"familia"},

   {"data":"articulo"},
   {"data":"unidad"},
   {"data":"costoENT" },

   {"data":"costoSAL"},
   {"data":"stockMIN"},
   {"data":"cantidad" },

   {"data":"fecha" },
   {"data":"conf"}


  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'copy', 'excel','pdf'
  ],
  "oLanguage": {
   "sSearch":         "Buscar:",
   "sInfo":           "Mostrando del (_START_ al _END_) de un total de _TOTAL_ registros",
   "sInfoEmpty":      "Mostrando del 0 al 0 de un total de 0 registros",
   "sLoadingRecords": "Por favor espere - cargando...",
   "oPaginate": {
    "sFirst":    "Primero",
    "sLast":     "Último",
    "sNext":     "Siguiente",
    "sPrevious": "Anterior"
   }

  }


 });

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



/*=============================================
Modal Agregar Productos
===============================================*/
function verModal(){

 Search_Name();
 Search_Marca();
 Busqueda();
 $('#okRegistroU').html('');

 $.ajax({
  type: "POST",
  url: "controlador/mtto/Almacen_FormularioRegistro.php",
  success:function(rp) {
   var data = eval(rp);
   var f      = new Date();
   $('#code').html(data[0]);
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
   area: {
    group: '#area_Group',
    validators: {
     notEmpty: {
      message: 'El campo area no puede quedar vacio'
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
/*-----------------------------------*/
/* Guardar o Editar Producto
/*-----------------------------------*/

function blockData() {
 $('#txtEquipo').prop('disabled', true);
 $('#txtZona').prop('disabled', true);
 $('#txtFamilia').prop('disabled', true);
 $('#txtMarca').prop('disabled', true);
 // --
 $('#txtStockMin').prop('disabled', true);
 $('#txtStock').prop('disabled', true);
 $('#txtDesc').prop('disabled', true);
 $('#txtCosto1').prop('disabled', true);
 $('#txtCosto2').prop('disabled', true);
 $('#date').prop('disabled', true);
}

function NUEVO(opc,idAl){

 $("#defaultForm").bootstrapValidator().on('success.form.bv', function(e) {

  Codigo     = $('#Codigo').val();
  articulo   = $('#txtEquipo').val();
  zona       = $('#txtZona').val();
  area       = $('#Area').val();
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
  unidad     = $('#txtUnidad').val();

  var f      = new Date();
  hoy        = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();

  gral ='art='+articulo+
  '&zona='+zona+'&Area='+area+'&familia='+familia+
  '&clase='+clase+'&unidad='+unidad+'&marca='+marca+'&codigo='+Codigo+'&hoy='+hoy;

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

function sleep(milliseconds) {
 var start = new Date().getTime();
 for (var i = 0; i < 1e7; i++) {
  if ((new Date().getTime() - start) > milliseconds) {
   break;
  }
 }
}

/*===========================================
*									Modal edit
=============================================*/

function almacen_modal_edit(idArt) {
 Search_Name_U();
 // Busqueda();

 $('#okRegistroU').html('');
 $.ajax({
  type: "POST",
  url: "controlador/mtto/Almacen_FormularioActualizar.php",
  data:'idArt='+idArt,

  success:function(rp) {
   var data = eval(rp);
   var   f  = new Date();
   $('#codeUp').html(data[0]);

   $('#date').combodate({
    minYear: f.getFullYear(),
    maxYear: f.getFullYear()+10
   });

   validate();
  }
 });
}

function Search_Name_U() {

 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=4',
  success:function(data) {
   res = eval(data);

   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableNameU[i] = res[i];
   }
  }
 });

 $( function() {

  $( "#txtEquipo" ).autocomplete({
   source: availableNameU,
   appendTo: "#ModProducto"  //Linea nueva, agrego el id del modal
  });
 });

 $( "#txtEquipo" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#txtEquipo').val();
  CodigoEquipo();
 } );

}

function Search_Marca_U() {

 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=5',
  success:function(data) {
   res = eval(data);

   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableMarcaU[i] = res[i];
   }
  }
 });

 $( function() {

  $( "#txtMarca" ).autocomplete({
   source: availableMarcaU,
   appendTo: "#ModProducto"  //Linea nueva, agrego el id del modal
  });

 });



}

function TiempoVida() {
 // alert($('#date').val());
}

/*===========================================
*									BAJA PRODUCTO
=============================================*/

function bajaProductos(id,opc) {

 $.ajax({
  type: "POST",
  url: 'controlador/mtto/admin/almacen_modal_ab.php',
  data: 'id='+id+'&opc='+opc,
  beforeSend: function() {
   $('#baja_code').html("<label class='text-primary'><span class='icon-spin6 animate-spin'></span> Espere...</label>");
  },
  success:function(rp) {
   var data = eval(rp);
   $('#baja_code').html(data[0]);
  }
 });
}

/*===========================================
*								MODIFICAR PRODUCTO
=============================================*/

function Alta_Baja(opc,id){

 baja   = $("#motivo").val();
 pass   = $("#pass").val();

 if(!$("#pass").val()){
  $("#Res_Motivo").html("");
  $('#Res_Pass').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
  $("#pass").focus();

 }
 else if(!$("#motivo").val()){
  $('#Res_Motivo').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
  $("Res_Pass").html("");
  $("#motivo").focus();
 }
 else{

  $.ajax({
   type: "POST",
   url: 'controlador/mtto/admin/almacen_insertar_ba.php',
   data: ('baja='+baja+'&pass='+pass+'&id='+id+'&opc='+opc+'&actual=0'+'&cantidad=0'+'&tipo=0'),

   beforeSend: function() {
    $('#Resultado_baja').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
   },

   success:function(respuesta) {
    $('#Resultado_baja').html(respuesta);
    $('#btnZone').html('');
    ver_tabla(0);

   }
  });
 }
}
