$(document).ready(main);


function main() {
 Dashboard();
}



function Dashboard(){
 $.ajax({
  type: "POST",
  url: "controlador/direccion/almacen_materiales.php",

  success:function(rp) {
   var data = eval(rp);
   $('#txtTotalProductos').html(data[0]);
   $('#txtTotal').html(data[1]);
   $('#txtBajo').html(data[2]);
   // $('#txtHoy').html(data[3]);
  }
 });
}

/*===========================================
*									 Vista -Tablas
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
    case 5:    verMateriales();  break;
    case 6:    verCosto();  break;
    case 7:    verBaja();  break;
   }
  }
 });
}

function verCosto(){
 $('#tb6').DataTable({
  destroy: true,
  "scrollX": true,
  "pageLength": 15,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 7;

   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [3,4,5] },
   { className: "text-center", "targets": [0] }
  ],
  "columns":[
   {"data":"codigo"},
   {"data":"area"},
   {"data":"Equipo"},
   {"data":"cantidad"},
   {"data":"costo" },
   {"data":"total"},
   {"data":"desc" }
  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'excel'
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

function verBaja(){


 $('#tb7').DataTable({
  destroy: true,
  "scrollX": true,
  "pageLength": 15,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 8;

   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [3,4,5] },
   { className: "text-center", "targets": [0,1,2] }
  ],
  "columns":[
   {"data":"codigo"},
   {"data":"Equipo"},
   {"data":"estado"},
   {"data":"categoria"},
   {"data":"area"},
   {"data":"cantidad"},
   {"data":"costo" },
   {"data":"total"},
   {"data":"baja"},
   {"data":"Productos"},
   {"data":"Observacion"},
   {"data":"Motivo" }
  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'excel'
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

function verMateriales(){


 $('#tb5').DataTable({
  destroy: true,
  "scrollX": true,
  "pageLength": 15,
  "ajax":{
   "method":"POST",
   "url":"controlador/direccion/data_productos.php",
   "data": function ( d ) {
    d.opc = 6;

   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [3,4,5] },
   { className: "text-center", "targets": [0] }
  ],
  "columns":[
   {"data":"codigo"},
   {"data":"Equipo"},
   {"data":"estado"},
   {"data":"area"},
   {"data":"cantidad"},
   {"data":"costo" },
   {"data":"total"},
   {"data":"tiempo"},
   {"data":"fecha" },
   {"data":"desc" }
  ],
  // bSort: false,
  dom: 'Bfrtip',
  buttons: [
   'excel'
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
