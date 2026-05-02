$(document).ready(main);


function main() {
 cbSELECT(1);
 cbSELECT(2);
 verFolios()
}

function verFolios(){
 categoria  = $('#txtCategoria').val();
 udn        = '';
 area       = $('#txtArea').val();
 id         = 1 ;
 //----

 $('#tbFolios').DataTable({

  destroy: true,
  scrollY:        "400px",
  scrollX:        true,
  scrollCollapse: true,
  paging:         false,
  // fixedColumns:   {
  //  leftColumns: 1,
  //  rightColumns: 1
  // },
  processing: true,
  // responsive: true,
  // "autoWidth": false,
  //
  // "scrollX": true,
  // "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/mtto/admin/tb_folios.php",
   "data": function ( d ) {
    d.opc       = id;
    d.categoria = categoria;
    d.area      = area;
   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [4] },
   { className: "text-center", "targets": [0,5] }
  ],

  "columns":[
   {"data":"Codigo"},
   {"data":"equipo"},
   {"data":"categoria"},
   {"data":"area"},
   {"data":"cantidad"},
   {"data":"conf"}

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


function ImprimirFolios() {
 categoria = $('#txtCategoria').val();
 // udn       = $('#select').val();
 area       = $('#txtArea').val();

 myWindow = window.open("recursos/pdf/all_folios.php?categoria="+categoria+"&area="+area+"&opc=1", "_blank", "width=950, height=700");
}

function cbSELECT(opc) {


 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/cbAlmacen.php",
  data:'opc='+opc,
  success:function(rp) {
   var data = eval(rp);
   switch (opc) {
    case 1: $('#cbCat').html(data[0]);   break;
    case 2: $('#cbArea').html(data[0]);    break;
    case 3: $('#cb').html(data[0]); break;



   }

  }
 });
}
