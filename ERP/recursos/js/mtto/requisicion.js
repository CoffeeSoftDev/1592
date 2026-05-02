materiales = [];

$(function() {
  Select_Materiales();
  $("#MatEq").autocomplete({ source: materiales });
  Tb_Materiales(0);
  $("#MatEq" ).on( "autocompletechange", function( event, ui ) { Buscar_Equipo(); });
});


function Select_Materiales() {
  $.ajax({
    type: "POST",
    url: "controlador/mtto/requisicion/requisicion_v.php",
    data: 'opc=0',
    success:function(data) {
      // $('.Res_All').html(data);
      res = eval(data);
      cont = res.length;
      for (var i = 0; i < cont; i++) {
        materiales[i] = res[i];
      }
    }
  });
}

function Tb_Materiales(caso){
  $.ajax({
      type: "POST",
      url: "controlador/mtto/requisicion/requisicion_v.php",
      data: 'opc=1&caso='+caso,
      success:function(data) {
        $('.tb_materiales').html(data);
      }
  });
}

function Buscar_Equipo(){
  valor = true;

  if ( !$('#MatEq').val() ) {
    valor = false;
  }


  if ( valor ) {

    $.ajax({
        type: "POST",
        url: "controlador/mtto/requisicion/requisicion_c.php",
        data: 'opc=0&ipt='+$('#MatEq').val(),
        success:function(data) {
          if (data == '0') {
            $('.addEquipo').html(
            '<i class="col-sm-4 col-xs-4 text-default">'+
              '<span class="icon-attention"></span> No existe el equipo o material (<strong> '+$('#MatEq').val()+' </strong>), ¿Desea agregarlo? '+
            '</i>'+
              '<a class="pointer" onclick="Agregar_Equipo(1);"><strong>Si</strong></a>'+
              '<strong> / </strong>'+
              '<a class="pointer" onclick="Agregar_Equipo(0);"><strong>No</strong></a>'
            );
          }
          else{
            $('.addEquipo').html('');
            $('#MatEq').val('');
          }
          Tb_Materiales(0);
        }
    });
  }
}

function Agregar_Equipo(type){
  if ( type == 1) {
    $.ajax({
        type: "POST",
        url: "controlador/mtto/requisicion/requisicion_c.php",
        data: 'opc=8&ipt='+$('#MatEq').val(),
        success:function(data) {
          Buscar_Equipo();
        }
    });
  }
  else {
    $('.addEquipo').html('');
    $('#MatEq').val('');
  }

}

function Save_Requisicion(){
  valor = true;
  if ( $('#Cb_Zona').val() == 0) {
    valor = false;
    $('#Cb_Zona').focus();
    $('#cbCat').addClass('has-error');
    $('#txt_obs').removeClass('has-error');
  }
  else if ( !$('#Obs_Req').val() ) {
    valor = false;
    $('#Obs_Req').focus();
    $('#cbCat').removeClass('has-error');
    $('.txt_obs').addClass('has-error');
  }

  if(valor){
    $.ajax({
        type: "POST",
        url: "controlador/mtto/requisicion/requisicion_c.php",
        data: 'opc=1&ipt='+$('#Obs_Req').val()+'&cb='+$('#Cb_Zona').val(),
        success:function(data) {
          if ( data != '0') {
            Tb_Materiales(0);
            $('#Obs_Req').val('');
            $('#Cb_Zona').val(0);
            $('#folio').html(data);
            $('#txt_obs').removeClass('has-error');
            $('#cbCat').removeClass('has-error');
            $('.ERROR').html('');
            Print_requisicion('u');
          }
          else {
            Tb_Materiales(1);
            setTimeout('$(\'.ERROR\').html(\'<label class="text-danger"><span class="icon-attention"></span>Faltan campos en la tabla por rellenar</label>\');',500);
          }
        }
    });
  }
}

function Remover_Equipo(id){
  $.ajax({
      type: "POST",
      url: "controlador/mtto/requisicion/requisicion_c.php",
      data: 'opc=2&idTbR='+id,
      success:function(data) {
        Tb_Materiales();
      }
  });
}

function Convert_Input(td,id,valor,caso){
  $('#td_'+td+id).html('<input type="text" class="form-control input-sm" id="Input_'+td+id+'" onkeypress="if(event.keyCode == 13) Convert_Label(\''+td+'\','+id+',\''+valor+'\','+caso+');" >');
  $('#Input_'+td+id).focus();
}

function Convert_Label(td,id,valores,caso){
  valor = true;
  if ( !$('#Input_'+td+id).val() ) {
    $('#td_'+td+id).html('<label class="pointer text-center" style="width:100%; height:30px; padding:5px;" onClick="Convert_Input(\''+td+'\','+id+',\''+valores+'\','+caso+');">'+valores+'</label>');
  }

  if(valor) {
    ipt = $('#Input_'+td+id).val();
    $.ajax({
        type: "POST",
        url: "controlador/mtto/requisicion/requisicion_c.php",
        data: 'opc='+caso+'&ipt='+ipt+'&id='+id,
        success:function(data) {
          $('#td_'+td+id).html('<label class="pointer text-center" style="width:100%; height:30px; padding:5px;" onClick="Convert_Input(\''+td+'\','+id+',\''+data+'\','+caso+');">'+data+'</label>');
        }
    });
  }
}

function Convert_CB(td,id){
  $.ajax({
      type: "POST",
      url: "controlador/mtto/requisicion/requisicion_c.php",
      data: 'opc=6&id='+id,
      success:function(data) {
        $('#td_'+td+id).html(data);
        $('#td_'+td+id).focus();
      }
  });
}

function Convert_label_CB(td,id){
  $.ajax({
      type: "POST",
      url: "controlador/mtto/requisicion/requisicion_c.php",
      data: 'opc=7&idJ='+$('#CB_'+id).val()+'&id='+id,
      success:function(data) {
        $('#td_'+td+id).html(data);
      }
  });
}

function tb_Requisiciones_printer(pag){
  valores ='&Mes='+$('#Mes').val()+'&Year='+$('#Year').val();
  // alert(valores);
  $.ajax({
      type: "POST",
      url: "controlador/mtto/requisicion/requisicion_v.php",
      data: 'opc=2&pag='+pag+valores,
      success:function(data) {
        $('.tb_requisiciones_print').html(data);
      }
  });
}

function Print_requisicion(id){
  myWindow = window.open("recursos/pdf/requisicion.php?geto="+id, "_blank", "width=750, height=700");
}
