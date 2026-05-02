$(function(){
  ver_datos();
  fondo_detalle();
});

function Print_RetRem(){
  myWindow = window.open("recursos/pdf/retrem.php?date="+$('#date').val(), "_blank", "width=750, height=700");
}

function ver_datos(){
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo_c.php',
    data: 'opc=1&date='+$('#date').val(),
    success:function(data) {
      res = eval(data);
      // $('.Res_General').html(data);

      if ( res[0] == 1 ) {
        $('#Ret_Efect').attr('disabled','disabled');
        $('#Ret_Prop').attr('disabled','disabled');
        $('#Btn_Ret').attr('disabled','disabled');
      }

      if ( res[1] == 1 ) {
        // $('#Reem').attr('disabled','disabled');
        // $('#Btn_Ret_Rem').attr('disabled','disabled');
        // $('#Obs_Rem').attr('disabled','disabled');
      }

      $('#SI_Reem').val(res[2]);
      $('#TG').val(res[3]);
      $('#TA').val(res[4]);
      $('#TP').val(res[5]);
      $('#Reem_Sug').val(res[6]);
      // $('#Reem').val(res[7]);
      $('#SF_Reem').val(res[8]);

      $('#SI_Efect').val(res[9]);
      $('#SH_Efect').val(res[10]);
      $('#Ret_Efect').val(res[11]);
      $('#SF_Efect').val(res[12]);

      $('#SI_Prop').val(res[13]);
      $('#SH_Prop').val(res[14]);
      $('#Ret_Prop').val(res[15]);
      $('#SF_Prop').val(res[16]);

      $('#SI_Total').val(res[17]);
      $('#SH_Total').val(res[18]);
      $('#Retiro_Total').val(res[19]);
      $('#SF_Total').val(res[20]);
      // $('#Obs_Rem').val(res[21]);
    }
  });
}

function retiro_ventas(){
  si_efect = $('#SI_Efect').val();
  sh_efect = $('#SH_Efect').val();
  ret_efect = $('#Ret_Efect').val();
  si_prop = $('#SI_Prop').val();
  sh_prop = $('#SH_Prop').val();
  ret_prop = $('#Ret_Prop').val();

  valores = 'SI_Efect='+si_efect.replace(',','')+
            '&SH_Efect='+sh_efect.replace(',','')+
            '&Ret_Efect='+ret_efect.replace(',','')+
            '&SI_Prop='+si_prop.replace(',','')+
            '&SH_Prop='+sh_prop.replace(',','')+
            '&Ret_Prop='+ret_prop.replace(',','');
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo_c.php',
    data: 'opc=2&'+valores,
    success:function(data) {
      res = eval(data);
      $('.Res_Efect').html('');
      $('.Res_Prop').html('');
      if ( res[0] == 1 ) { $('.Res_Efect').html('<label class="text-danger"><span class="icon-attention"></span> El retiro no puede ser mayor al saldo inicial</label>'); }
      if ( res[1] == 1 ) { $('.Res_Prop').html('<label class="text-danger"><span class="icon-attention"></span> El retiro no puede ser mayor al saldo inicial</label>'); }
      $('#SF_Efect').val(res[2]);
      $('#SF_Prop').val(res[3]);
      $('#SF_Prop').val(res[3]);
      $('#Retiro_Total').val(res[4]);
      $('#SF_Total').val(res[5]);
    }
  });
}

function Modal_retiro(){
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo.php',
    data: 'opc=3',
    success:function(data) {
      $('.modal-content').html(data);
    }
  });
}

function save_retiro_ventas(){
    si_efect = $('#SI_Efect').val();
    ret_efect = $('#Ret_Efect').val();
    si_prop = $('#SI_Prop').val();
    ret_prop = $('#Ret_Prop').val();
    date = $('#date').val();
    pass = $('#pass_re').val();

    valores = 'SI_Efect='+si_efect.replace(',','')+
              '&Ret_Efect='+ret_efect.replace(',','')+
              '&SI_Prop='+si_prop.replace(',','')+
              '&Ret_Prop='+ret_prop.replace(',','')+
              '&date='+date+
              '&pass='+pass;
    $.ajax({
      type: "POST",
      url: 'controlador/finanzas/cliente/fondo_c.php',
      data: 'opc=3&'+valores,
      success:function(data) {
        if ( data == 0 ) {
          $('#Res_Finanzas_RE').html('<label class="text-danger"><span class="icon-attention"></span> Acceso denegado</label>');
        }
        else if(data == 1){
          $('#Res_Esp').html('<label class="text-danger"><span class="icon-attention"></span> Existe un saldo negativo, por lo que no se puede continuar.</label>');
          $("[data-dismiss=modal]").trigger({ type: "click" });
        }
        else if(data == 2) {
          $('#Res_Esp').html('<label class="text-danger"><span class="icon-attention"></span> No se ha ingresado ningún valor de retiro.</label>');
          $("[data-dismiss=modal]").trigger({ type: "click" });
        }
        else {
          ver_datos();
          $('#Res_Esp').html('<label class="text-success"><span class="icon-ok"></span> El retiro se guardó correctamente.</label>');

          setTimeout(function(){ $('#Res_Esp').html(''); }, 6000);
          $("[data-dismiss=modal]").trigger({ type: "click" });
        }
      }
    });
}

function Modal_reembolso(){
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo.php',
    data: 'opc=4',
    success:function(data) {
      $('.modal-content').html(data);
    }
  });
}

function save_rembolso() {
  date = $('#date').val();
  pass = $('#pass_rem').val();
  obs_Rem = $('#Obs_Rem').val();
  gasto = $('#TG').val();
  prov = $('#TP').val();
  si_reem = $('#SI_Reem').val();
  reem = $('#Reem').val();
  sf_reem = $('#SF_Reem').val();

  valores =
    '&date='+date+
    '&pass='+pass+
    '&obs_Rem='+obs_Rem+
    '&gasto='+gasto.replace(',','')+
    '&prov='+prov.replace(',','')+
    '&si_reem='+si_reem.replace(',','')+
    '&reem='+reem.replace(',','')+
    '&sf_reem='+sf_reem.replace(',','');
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo_c.php',
    data: 'opc=4'+valores,
    success:function(data) {
      if ( data == 0) {
        $('#Res_RemFinanzas').html('<label class="text-danger"><span class="icon-attention"></span> Acceso denegado.</label>');
      }
      else{
        ver_datos();
        $("[data-dismiss=modal]").trigger({ type: "click" });
        $('#Res_Rem').html('<label class="text-success"><span class="icon-ok"></span> Reembolso guardado correctamente.</label>');
        setTimeout(function(){ $('#Res_Rem').html(''); }, 6000);
        fondo_detalle();
      }
    }
  });
}

function Calculo_Reembolso(){
  gasto = $('#TG').val();
  prov = $('#TP').val();
  si_reem = $('#SI_Reem').val();
  reem = $('#Reem').val();

  valores =
  '&gasto='+gasto.replace(',','')+
  '&prov='+prov.replace(',','')+
  '&si_reem='+si_reem.replace(',','')+
  '&reem='+reem.replace(',','');

  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo_c.php',
    data: 'opc=5'+valores,
    success:function(data) {
      $('#SF_Reem').val(data);
    }
  });
}

function fondo_detalle(){
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/fondo.php',
    data: 'opc=5&fecha='+$('#date').val(),
    beforeSend: function() {
     $('.content-table').html('<br><br><center>'+
     '<i class="fa fa-spinner fa-spin fa-2x fa-fw text-info"></i><span><br> Espere...</span></center>');
    },
    success:function(data) {
      $('.content-table').html(data);
    }
  });
}
