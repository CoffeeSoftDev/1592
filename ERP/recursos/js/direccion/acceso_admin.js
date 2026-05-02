


function	acceso_finanzas(){

 var dialog = bootbox.dialog({
  title: '',
  message:'<center><img src="recursos/img/finanzas.png"></img></center>'+
  '<br<br><br><p class="text-center" style="font-size:1.8rem"><i class="fa fa-spinner fa-pulse fa-fw text-success"></i>Accediendo al apartado de finanzas...</p>'

 });


 dialog.init(function(){

  setTimeout(function(){

   dialog.modal('hide');
   window.location="finanzas";
   // dialog.find('.bootbox-body').html('I was loaded after the dialog was shown!');
  }, 2000);
 });




}


function acceso_mtto() {
 var dialog = bootbox.dialog({
  title: '',
  message:'<center><img src="recursos/img/mtto.png"></img></center>'+
  '<br<br><br><p class="text-center" style="font-size:1.8rem"><i class="fa fa-spinner fa-pulse fa-fw text-success"></i>Accediendo al apartado de Mantenimiento...</p>'

 });

 dialog.init(function(){

  setTimeout(function(){

   dialog.modal('hide');
   window.location="mtto";
   // dialog.find('.bootbox-body').html('I was loaded after the dialog was shown!');
  }, 2000);
 });


}
