$(document).ready(main);

function main() {
 verLista();
 cbAnio();
}


/*===========================================
*									init Components
=============================================*/

function cbAnio(){
 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/dataLista.php",
  data:'opc=3',
  success:function(rp) {
   $('#cbAnio').html(rp);
  }
 });
}

function data() {
 var resultado =[];

 /* info */
 option = 2;
 rd =$('input:radio[name=rdExtra]:checked').val();
 if (rd!=null) { option=rd; }
  anio  = $('#txtAnio').val();
  mes   = $('#txtMes').val();


 /* data */
 resultado = resultado.concat(option);
 resultado = resultado.concat(mes);
 resultado = resultado.concat(anio);
 return resultado;
}

/*===========================================
*									data Components
=============================================*/
function verReporte(id){
arreglo    = data();
 tipo      = arreglo[0];
 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/dataLista.php",
  data:'opc=2&id='+id+'&tipo='+tipo,
  success:function(rp) {
   $('#formato-lista').html(rp);
  }
 });

}

/*===========================================
*									data Components
=============================================*/
function verTipoReporte() {
 setTimeout(function(){ verLista(); }, 500);
}


function verLista() {
 arreglo   = data();
 tipo      = arreglo[0];
 mes       = arreglo[1];
 anio      = arreglo[2];


 $('#formato-lista').html('');

 $('#tbLista').DataTable({
  destroy: true,
  responsive:true,
  scrollX:        true,
  "pageLength": 20,
  "ajax":{
   "method":"POST",
   "url":"controlador/mtto/admin/dataLista.php",
   "data": function ( d ) {
    d.opc  = 1;
    d.tipo = tipo;
    d.mes  = mes;
    d.year = anio;

   }
  },

  "columnDefs": [
   { className: "text-right", "targets": [1,2,4] },
   { className: "text-center", "targets": [0] }
  ],


  "columns":[
   {"data":"no"},
   {"data":"folio"},
   {"data":"fecha"},
   {"data":"hora"},
   {"data":"productos"},
   {"data":"autorizo"},
   {"data":"option" }


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


function Imprimir(id) {
 myWindow = window.open("recursos/pdf/pdf_productos.php?id="+id
 , "_blank", "width=650, height=800");
}
