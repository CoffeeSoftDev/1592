$(document).ready(main);

function main() {
 cbSELECT(5);
}

/*-----------------------------------*/
/* init Components
/*-----------------------------------*/
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
/*-----------------------------------*/
/* Ver Inventario
/*-----------------------------------*/

function buscarLista(){
 area     =  $('#txtArea').val();
 zona     =  $('#txtZona').val();
 fi       =  $('#fi').val();
 ff       =  $('#ff').val();

 if (zona==0 || zona=='0') {
  bootbox.alert({

   size: "small",
   title: "<center><span class='icon-info-circled fa-2x text-primary'></span> </center>",
   message: "<center><strong> Debes seleccionar una zona para continuar...</strong></center>"
  });


 }else if (area==0 || area=='0') {
  bootbox.alert({

   size: "small",
   title: "<center><span class='icon-info-circled fa-2x text-primary'></span> </center>",
   message: "<center><strong> Debes seleccionar un área para continuar...</strong></center>"
  });


 }else {



 $.ajax({
  type: "POST",
  url: "controlador/mtto/admin/ReporteInventario.php",
  data:'fi='+fi+'&ff='+ff+'&area='+area+'&zona='+zona,

  beforeSend:function (send) {
   $('#tbFormato1').html('<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>'+
   '<h4><span>Cargando datos...</span></h4></center>');
  },
  success:function(rp) {
   var data = eval(rp);
   $('#tbFormato1').html(data[0]);
  }
 });
}
}

/*-----------------------------------*/
/* init Components
/*-----------------------------------*/

function Imprimir(id) {
 myWindow = window.open("recursos/pdf/pdf_reporteInv.php?id="+id
 , "_blank", "width=850, height=600");
}
