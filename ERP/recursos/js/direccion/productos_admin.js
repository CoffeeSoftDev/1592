
$(document).ready(main);


function main() {
 Dashboard();
 ver_tabla();
}

function Dashboard(){

 $.ajax({
  type: "POST",
  url: "controlador/direccion/almacen_productos.php",

  success:function(rp) {
   var data = eval(rp);
   $('#txtTotalProductos').html(data[0]);
   $('#txtTotal').html(data[1]);
   $('#txtBajo').html(data[2]);
   $('#txtHoy').html(data[3]);
  }
 });
}


function ver_tabla(id) {

 $('#tbProductos').DataTable({
  destroy: true,
  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 1;

   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [1,2] }
  //  { className: "text-center", "targets": [0,1,2,8,9] }
  ],

  "columns":[
   {"data":"zona"},
   {"data":"productos"},
   {"data":"total" }


  ],
  // bSort: false,
  dom: 'Bfrtip',
  // buttons: [
  //  'copy', 'excel','pdf'
  // ],
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

/*===========================================
*									 Ver Productos
=============================================*/
function verProductos(){


 $('#tb1').DataTable({
  destroy: true,
   "scrollX": true,
  "pageLength": 15,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 2;

   }
  },

  // "columnDefs": [
  //  { className: "text-right", "targets": [4,5,6,7] },
  //  { className: "text-center", "targets": [0,1,2,8,9] }
  // ],
  "columns":[
   {"data":"Codigo"},
   {"data":"zona"},
   {"data":"familia"},
   {"data":"articulo"},
   {"data":"costoENT" },
   {"data":"costoSAL"},
   {"data":"stockMIN"},
   {"data":"cantidad" },
   {"data":"fecha" }
  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'copy', 'excel'
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

function verCostos(){


 $('#tb2').DataTable({
  destroy: true,
   "scrollX": true,
  "pageLength": 15,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 3;

   }
  },

  // "columnDefs": [
  //  { className: "text-right", "targets": [4,5,6,7] },
  //  { className: "text-center", "targets": [0,1,2,8,9] }
  // ],
  "columns":[
   {"data":"zona"},
   {"data":"codigo"},
   {"data":"nombre"},
   {"data":"clase"},
   {"data":"familia" },
   {"data":"cantidad"},
   {"data":"costo"},
   {"data":"total" },
   {"data":"preciov" },
   {"data":"desc" }
  ],
  // bSort: false,
  dom: 'Bfrtip',
  // buttons: [
  //  'copy', 'excel'
  // ],
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

function verProductosBajos(){


 $('#tb3').DataTable({
  destroy: true,
   "scrollX": true,
  "pageLength": 15,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 4;

   }
  },

  // "columnDefs": [
  //  { className: "text-right", "targets": [4,5,6,7] },
  //  { className: "text-center", "targets": [0,1,2,8,9] }
  // ],
  "columns":[
   {"data":"zona"},
   {"data":"codigo"},
   {"data":"nombre"},
   {"data":"clase"},
   {"data":"familia" },
   {"data":"cantidad"},
   {"data":"costo"},
   {"data":"total" },
   {"data":"preciov" },
   {"data":"desc" }
  ],
  // bSort: false,
  dom: 'Bfrtip',
  // buttons: [
  //  'copy', 'excel'
  // ],
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

function verHoy(){


 $('#tb4').DataTable({
  destroy: true,
   "scrollX": true,
  "pageLength": 15,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 5;

   }
  },

  // "columnDefs": [
  //  { className: "text-right", "targets": [4,5,6,7] },
  //  { className: "text-center", "targets": [0,1,2,8,9] }
  // ],
  "columns":[
   {"data":"zona"},
   {"data":"codigo"},
   {"data":"nombre"},
   {"data":"clase"},
   {"data":"familia" },
   {"data":"cantidad"},
   {"data":"costo"},
   {"data":"total" },
   {"data":"preciov" },
   {"data":"desc" }
  ],
  // bSort: false,
  dom: 'Bfrtip',
  // buttons: [
  //  'copy', 'excel'
  // ],
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

/*===========================================
*									 Vista de datos
=============================================*/
function cargarTabla(opc){
 $.ajax({
  type: "POST",
  url: "controlador/direccion/data_tablas.php",
  data:'opc='+opc,
  success:function(rp) {
   var data = eval(rp);
   $('#ZonaDatos').html(data[0]);
   switch (opc) {
    case 1:    verProductos();  break;
    case 2:    verCostos();  break;
    case 3:    verProductosBajos();  break;
    case 4:    verHoy();  break;

   }
  }
 });
}
