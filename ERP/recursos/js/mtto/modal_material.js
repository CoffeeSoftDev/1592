
/*-----------------------------------*/
/*	 AutoComplete
/*-----------------------------------*/

availableTags = [];
availableName = [];
availableCode = [];
availableProd = [];
availableMarc = [];

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
  data: 'opc=2',
  success:function(data) {
   res = eval(data);

   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableName[i] = res[i];
   }
  }
 });

 $( function() {

  $( "#Equipo" ).autocomplete({
   source: availableName,
   appendTo: "#Producto"  //Linea nueva, agrego el id del modal
  });

 });

 $( "#Equipo" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#Equipo').val();
  CodigoEquipo();
 } );

}

function Search_Code() {
 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=3',
  success:function(data) {
   res = eval(data);

   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableCode[i] = res[i];
   }
  }
 });

 $( function() {
  $( "#Codigo" ).autocomplete({
   source: availableCode,
   appendTo: "#Producto"  // id del modal

  });

 });

 $( "#Codigo" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#Codigo').val();

 } );

}

function Search_Prod() {
 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=6',
  success:function(data) {
   res = eval(data);
   console.log('Productos:' + data);
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableProd[i] = res[i];
   }
  }
 });

 $( function() {
  $( "#Proveedor" ).autocomplete({
   source: availableProd,
   appendTo: "#Producto"  // id del modal

  });

 });

 $( "#Proveedor" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#Proveedor').val();

 } );

}

function Search_Mark() {
 $.ajax({
  type: "POST",
  url: "controlador/mtto/busquedaRegistros.php",
  data: 'opc=5',
  success:function(data) {
   res = eval(data);
   console.log('Marca:' + data);
   cont = res.length;
   for (var i = 0; i < cont; i++) {
    availableMarc[i] = res[i];
   }
  }
 });

 $( function() {
  $( "#Marca" ).autocomplete({
   source: availableMarc,
   appendTo: "#Producto"  // id del modal

  });

 });

 $( "#Marca" ).on( "autocompleteclose", function( event, ui ) {
  tags=$('#Marca').val();

 } );

}

/*-----------------------------------*/
/*    carga de formulario
/*-----------------------------------*/
function verModal(){

 Busqueda();
 Search_Name();
 Search_Code();

 $.ajax({
  type: "POST",
  url: "controlador/mtto/FormularioRegistro.php",
  success:function(rp) {
   var data = eval(rp);
   $('#code').html(data[0]);
   var f      = new Date();
   $('#Equipo').focus();
   $('#date').combodate({
    minYear: f.getFullYear(),
    maxYear: f.getFullYear()+10
   });

   Search_Prod();
   Search_Mark();
   validate();
  }
 });
}

function readURL(input) {

 if (input.files && input.files[0]) {
  var reader = new FileReader();

  reader.onload = function(e) {
   $('#blah').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
 }
}
/*-----------------------------------*/
/* Nuevo Producto & Actualizar Producto
/*-----------------------------------*/

function Nuevo_Codigo(opc,idEquipo){

 $("#defaultForm").bootstrapValidator().on('success.form.bv', function(e) {

  empresa       = document.getElementById("Empresa");
  categoria     = $('#A').val();

  equipo	       = document.getElementById("Equipo");
  area	         = document.getElementById("Area");
  codigo 	      = document.getElementById("Codigo");
  cantidad      = $('#txtCantidad').val();
  Proveedor     = $('#Proveedor').val();
  marca         = $('#Marca').val();
  costo         = $('#txtCosto').val();
  Tiempo        = $('#date').val();
  Detalles      = $('#txtDetalles').val();
  rd            = $('input:radio[name=rdExtra]:checked').val();
  txtArea       = $("#txtArea option:selected").text();
  valor         = true;
  estado        = 0;

  if (rd        ==null) { estado = 1; }
  else { estado =rd; }

  var f         = new Date();
  hoy           = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();
  var fecha1    = moment(hoy);
  var fecha2    = moment(Tiempo);
  vida          = fecha2.diff(fecha1, 'months');

  /* img -data */
  var InputFile = document.getElementById('imgInp');
  var file      = InputFile.files;
  var data      = new FormData();

  switch (opc) {

   case 1:

   data.append('files',file[0]);
   data.append('empresa',empresa.value);
   data.append('equipo',equipo.value);
   data.append('area',area.value);
   data.append('codigo',codigo.value);
   data.append('categoria',categoria);
   data.append('cant',cantidad);
   data.append('costo',costo);
   data.append('Tiempo',Tiempo);
   data.append('Detalles',Detalles);
   data.append('estado',estado);
   data.append('hoy',hoy);
   data.append('Proveedor',Proveedor);
   data.append('Marca',marca);
   data.append('txtArea',txtArea);



   $.ajax({
    type: "POST",
    url: 'controlador/mtto/admin/almacen_insertar.php',
    contentType:false,
    data:data,
    processData:false,
    cache:false,

    beforeSend: function() {
     $('#Resultado').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
    },
    success:function(respuesta) {
     $('#okRegistro').html(respuesta);
     verModal();
     ver_tabla(1);

    }
   });

   break;



   case 2:

   data = getData();
   data.append('opc',2);
   data.append('idAlmacen',idEquipo);
   console.log(... data);

   $.ajax({
    type: "POST",
    url: 'controlador/mtto/admin/almacen_modificar.php',
    contentType:false,
    data:data,
    processData:false,
    cache:false,

    beforeSend: function() {
     $('#Registro').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
    },
    success:function(respuesta) {
       data  = eval(respuesta);
       $('#Registro').html(data[0]);

    }
   });

   // $.ajax({
   //  type: "POST",
   //  url: 'controlador/mtto/admin/almacen_modificar.php',
   //  data: ('empresa='+empresa.value+'&equipo='+equipo.value+'&area='+area.value+'&codigo='+codigo.value+'&categoria='+categoria+'&cant='+cantidad+'&costo='+costo+'&Tiempo='+Tiempo+'&opc=2&estado='+estado+'&idAlmacen='+idEquipo+'&Detalles='+Detalles),
   //
   //  beforeSend: function() {
   //   $('#Resultado').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
   //  },
   //
   //  success:function(respuesta) {
   //
   //   data  = eval(respuesta);
   //   $('#Resultado').html(data[0]);
   //
   //
   //  }
   //
   // });
   break;


  }

 });// End Validator

}

/*-----------------------------------*/
/*	 Plugin BootstrapValidator
/*-----------------------------------*/



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
      message: 'El campo Equipo no puede quedar vacio'
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



  }// fields


 });

}


/*-----------------------------------*/
/* Codigo Equipo
/*-----------------------------------*/
function CodigoEquipo(){

 Negocio   = 'AR';
 zona      = $('#Empresa').val();
 Equipo    = $('#Equipo').val();
 area      = $('#Area').val();

 $.ajax({
  type: "POST",
  url: "controlador/mtto/CodigoEquipos.php",
  data:'area='+area+'&Equipo='+Equipo+'&opc=1&zona='+zona,
  success:function(rp) {
   var data = eval(rp);
   console.log(data);
   $('#Codigo').val(Negocio+'-'+data[0]+'-'+data[1]+'-'+data[2]);
   // 009
  }
 });

}

function TiempoVida(){
}
 /*-----------------------------------*/
 /*		Obtener datos de los formularios
 /*-----------------------------------*/

 function getData() {
  var data      = new FormData();

  empresa       = document.getElementById("Empresa");
  categoria     = $('#A').val();

  equipo	       = document.getElementById("Equipo");
  area	         = document.getElementById("Area");
  codigo 	      = document.getElementById("Codigo");
  cantidad      = $('#txtCantidad').val();
  Proveedor     = $('#Proveedor').val();
  marca         = $('#Marca').val();
  costo         = $('#txtCosto').val();
  Tiempo        = $('#date').val();
  Detalles      = $('#txtDetalles').val();
  rd            = $('input:radio[name=rdExtra]:checked').val();
  txtArea       = $("#txtArea option:selected").text();
  valor         = true;
  estado        = 0;

  if (rd        ==null) { estado = 1; }
  else { estado =rd; }

  var f         = new Date();
  hoy           = f.getFullYear() + "-" + (f.getMonth() +1) + "-" + f.getDate();
  var fecha1    = moment(hoy);
  var fecha2    = moment(Tiempo);
  vida          = fecha2.diff(fecha1, 'months');

  /* img -data */
  var InputFile = document.getElementById('imgInp');
  var file      = InputFile.files;

  data.append('files',file[0]);
  data.append('empresa',empresa.value);
  data.append('equipo',equipo.value);
  data.append('area',area.value);
  data.append('codigo',codigo.value);
  data.append('categoria',categoria);
  data.append('cant',cantidad);
  data.append('costo',costo);
  data.append('Tiempo',Tiempo);
  data.append('Detalles',Detalles);
  data.append('estado',estado);
  data.append('hoy',hoy);
  data.append('Proveedor',Proveedor);
  data.append('Marca',marca);
  data.append('txtArea',txtArea);

  return data;
 }
