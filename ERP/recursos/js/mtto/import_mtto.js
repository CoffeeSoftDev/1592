$(document).ready(main);
function main() {
 // btnEXCEL();
 cbSELECT(2);
 cbSELECT(3);
 // tbBalanzas();
}


/*-----------------------------------*/
/*		Init Components
/*-----------------------------------*/

function limpiarFile(){
   $('#excel_file').val('');
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
    case 2: $('#cbArea').html(data[0]);  break;
    case 3: $('#cbZona').html(data[0]);   break;

   }

  }
 });
}


function cargaMasiva(opc) {
 area           = $('#txtArea').val();
 zona           = $('#txtUDN').val();
 var InputFile  = document.getElementById('excel_file');
 var file       = InputFile.files;
 var data       = new FormData();

 cant_file     = file.length;

 if (zona==0) {

  bootbox.alert({
   size:"small",
   message: "Debes elegir una <b>zona </b> para continuar",
   callback: function () {
    $('#txtUDN').focus();
   }
  })

 }else if (area==0) {

  bootbox.alert({
   size:"small",
   message: "Debes elegir una <b>area </b> para continuar",
   callback: function () {
    $('#txtArea').focus();
   }
  })

 }else if(cant_file==0){
  msj_box("Debes elegir un <b>archivo </b> para continuar","excel_file");
 } else {


  var valZona    = $("#txtUDN  option:selected").text();
  var valArea    = $("#txtArea option:selected").text();

  data.append('excel_file',file[0]);
  data.append('area',area);
  data.append('zona',zona);
  data.append('valArea',valArea);
  data.append('valZona',valZona);
  data.append('opc',opc);

  console.log(...data);

  $.ajax({

   url:"controlador/mtto/admin/import_mtto.php",
   method:"POST",
   data:data,
   contentType:false,
   processData:false,
   cache:false,
   beforeSend: function() {
    $('#btnSubir').addClass('disabled');
    $('#txtData').html('');
    $('#result').html('<br><br><center>'+
    '<i class="fa fa-spinner fa-pulse fa-fw text-success"></i><span> Subiendo archivo ...</span></center>');
   },

   success:function(rp){
    data = eval(rp);

    $('#btnSubir').removeClass('disabled');
    $('#result').html(data[0]);
    $('#txtData').html(data[1]);
    if (opc==2) {
     $('#excel_file').val('');


    }
   }



  }).done( function() {



  }).fail( function() {

   alert( 'Error!!' );

  }).always( function() {



  });
 }//end else




}


function msj_box(msj,txtID) {
 bootbox.alert({
  size:"small",
  message: msj,
  callback: function () {
   $('#txt'+txtID).focus();
  }
 })
}




function exportTableToExcel(tableID, filename = ''){
 var downloadLink;
 var dataType = 'application/vnd.ms-excel';
 var tableSelect = document.getElementById(tableID);
 var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');

 // Specify file name
 filename = filename?filename+'.xls':'excel_data.xls';

 // Create download link element
 downloadLink = document.createElement("a");

 document.body.appendChild(downloadLink);

 if(navigator.msSaveOrOpenBlob){
  var blob = new Blob(['ufeff', tableHTML], {
   type: dataType
  });
  navigator.msSaveOrOpenBlob( blob, filename);
 }else{
  // Create a link to the file
  downloadLink.href = 'data:' + dataType + ', ' + tableHTML;

  // Setting the file name
  downloadLink.download = filename;

  //triggering the function
  downloadLink.click();
 }
}

function tbBalanzas (){
 $.ajax({
  type: "POST",
  url: "../controlador/direccion/balanzas.php",
  data:'opc=2',
  success:function(rp) {
   var data = eval(rp);
   $('#tbBalanzas').html(data[0]);
  }
 });

}
