proveedores = []; conceptos = [];
$(function() {
  ver_pago(1);
  Select_Proveedores();
  Select_Concepto();
  $("#Proveedor").autocomplete({ source: proveedores });
  $("#Insumo").autocomplete({ source: conceptos });
});

function Select_Proveedores() {
  $.ajax({
      type: "POST",
      url: "controlador/finanzas/cliente/pane_compras_c.php",
      data: 'opc=1',
      success:function(data) {
        // $('#Respuesta_Gastos').html(data);
        res = eval(data);
        cont = res.length;
        for (var i = 0; i < cont; i++) {
          proveedores[i] = res[i];
        }
      }
  });
}

function Select_Concepto() {
  $.ajax({
      type: "POST",
      url: "controlador/finanzas/cliente/pane_compras_c.php",
      data: 'opc=2',
      success:function(data) {
        // $('#Respuesta_Gastos').html(data);
        res = eval(data);
        cont = res.length;
        for (var i = 0; i < cont; i++) {
          conceptos[i] = res[i];
        }
      }
  });
}

function nuevo_pago(){
  valor = true;

  if(!$('#Proveedor').val() && !$('#Insumo').val()){
    valor = false;
    $('#Proveedor').focus();
    $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> El proveedor ó el insumo son necesarios</label>');
  }
  else if($('#Insumo').val() && $('#Clase_Insumo').val() == 0){
    valor = false;
    $('#Clase_Insumo').focus();
    $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Seleccionar el tipo de almacén</label>');
  }
  else if(!$('#Pago').val() || parseFloat($('#Pago').val()) == 0){
    valor = false;
    $('#Pago').focus();
    $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> La cantidad de pago es requerida</label>');
  }
  else if($('#Proveedor').val() && $('#Clase_Gasto').val() == 0){
    valor = false;
    $('#Clase_Gasto').focus();
    $('#Respuesta_Gastos').html('<label class="text-danger"><span class="icon-cancel"></span> Seleccionar el tipo de pago</label>');
  }

  if(valor){
    valores = "&Proveedor="+$('#Proveedor').val()+"&Insumo="+$('#Insumo').val()+"&Clase_Insumo="+$('#Clase_Insumo').val()+"&Pago="+$('#Pago').val()+"&Clase_Gasto="+$('#Clase_Gasto').val()+"&Observaciones="+$('#Observaciones').val()+'&date='+$('#date').val();

    $.ajax({
      type: "POST",
      url:'controlador/finanzas/cliente/pane_pagos_c.php',
      data: 'opc=0'+valores,
      beforeSend: function() {
        $('#Respuesta_Gastos').html("<label class='text-success col-sm-12 col-xs-12'><span class='icon-spin6 animate-spin'></span> Guardando movimientos...</label>");
      },
      success:function(data) {
        ver_pago(1);
        $('#Proveedor').val('');
        $('#Proveedor').removeAttr('disabled');
        $('#Insumo').val('');
        $('#Insumo').removeAttr('disabled');
        $('#Clase_Insumo > option[value="0"]').attr('selected','selected');
        $('#Clase_Insumo').removeAttr('disabled');
        $('#Pago').val('');
        $('#Clase_Gasto > option[value="0"]').attr('selected','selected');
        $('#Clase_Gasto').removeAttr('disabled');
        $('#Observaciones').val('');
        $('#Respuesta_Gastos').html('');
        Saldos_Fondo();
      }
    });
  }
}

function PS_bloq(opc){
  if(opc == 1){
    if($('#Proveedor').val().length > 0){
      $('#Insumo').attr('disabled','disabled');
      $('#Clase_Insumo').attr('disabled','disabled');
    }
    else{
      $('#Insumo').removeAttr('disabled');
      $('#Clase_Insumo').removeAttr('disabled');
    }
  }
  else if(opc == 2){
    if($('#Insumo').val().length > 0){
      $('#Proveedor').attr('disabled','disabled');
      $('#Clase_Gasto').attr('disabled','disabled');
    }
    else{
      $('#Proveedor').removeAttr('disabled');
      $('#Clase_Gasto').removeAttr('disabled');
    }
  }
}

function ver_pago(pag) {
  valores = 'pag='+pag+'&date='+$('#date').val()+'&Cb=0';

  $.ajax({
    type: "POST",
    url:'controlador/finanzas/cliente/tabla_pagos.php',
    data:valores,
    success:function(data) {
      $('#table_data').html(data);
    }
  });
}
function Eliminar_Pagos(id,opc) {
  valores = '&id='+id+'&opcc='+opc;
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/pane_compras_c.php',
    data: 'opc=6'+valores,
    success:function(data_val) {
      $('.modal-content').html(data_val);
    }
  });
}
function Eliminar_Compras_Pagos(id,opc) {
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/pane_compras_c.php',
    data: 'opc=7&id='+id,
    success:function(data) {
      if(opcc == 2){
        ver_pago(1);
        $("[data-dismiss=modal]").trigger({ type: "click" });
      }
      Saldos_Fondo();
    }
  });
}

function Convertir_input(id,id_num,valor) {
  switch (id) {
    case 'Prov_Compras':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="Prov_Compras2'+id_num+'" value="'+valor+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\'); }"; >');
      $('#Prov_Compras2'+id_num).focus();
      break;
    case 'prov_pagos':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="prov_pagos2'+id_num+'" value="'+valor+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'1\'); }"; >');
      $('#prov_pagos2'+id_num).focus();
      break;
    case 'Insumo_Compras':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="Insumo_Compras2'+id_num+'" value="'+valor+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\'); }"; >');
      $('#Insumo_Compras2'+id_num).focus();
      break;
    case 'Insumo_pagos':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html('<input type="text" class="form-control input-sm" id="Insumo_pagos2'+id_num+'" value="'+valor+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'2\'); }"; >');
      $('#Insumo_pagos2'+id_num).focus();
      break;
    case 'ClaseInsumo_Compras':
      Clase_Insumos(1,id,id+'2',id_num,valor);
      break;
    case 'ClaseInsumo_pagos':
      Clase_Insumos(2,id,id+'2',id_num,valor);
      break;
    case 'Cant_Compras':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html(
        '<div class="input-group">'+
        '<span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>'+
          '<input type="text" class="form-control input-sm" id="Cant_Compras2'+id_num+'" value="'+valor+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\'); }"; >'+
        '</div>'
      );
      $('#Cant_Compras2'+id_num).focus();
      break;
    case 'Cant_pagos':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html(
        '<div class="input-group">'+
        '<span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>'+
          '<input type="text" class="form-control input-sm" id="Cant_pagos2'+id_num+'" value="'+valor+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'4\'); }"; >'+
        '</div>'
      );
      $('#Cant_pagos2'+id_num).focus();
      break;
    case 'TipoGasto_Compras':
    Tipo_Pago(id,id+'2',id_num,valor);
    break;
    case 'TipoGasto_pagos':
    Tipo_Pago(id,id+'2',id_num,valor);
    break;
    case 'Observaciones_Compras':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html('<textarea class="form-control input-sm" id="Observaciones_Compras2'+id_num+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\');" onkeypress="if(event.keyCode == 13){ Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\'); }"; >'+valor+'</textarea>');
      $('#Observaciones_Compras2'+id_num).focus();
      break;
    case 'Observaciones_pagos':
      $('#'+id+id_num).html('');
      $('#'+id+id_num).html('<textarea class="form-control input-sm" id="Observaciones_pagos2'+id_num+'" onBlur="Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\');" onkeypress="if(event.keyCode == 13){ Modificar_Pagos(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'6\'); }"; >'+valor+'</textarea>');
      $('#Observaciones_pagos2'+id_num).focus();
      break;
  }
}

function Modificar_Pagos(id,id2,id_num,valor,tipo){
  acceso = true;

  if(!$('#'+id2+id_num).val() || parseFloat($('#'+id2+id_num).val()) == 0){
    acceso = false;
    if(id == 'Cant_pagos'){
      $('#'+id+id_num).html('<label class="text-right input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">$ '+valor+'</label>');
    }
    else if(id == 'Observaciones_pagos'){
      acceso = true;
    }
    else{
      $('#'+id+id_num).html('<label class="text-left input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">'+valor+'</label>');
    }
  }

  if(acceso){
    valor = $('#'+id2+id_num).val();
    valores = '&id='+id_num+'&valor='+valor+'&tipo='+tipo;
    // alert(valores);
    $.ajax({
      type: "POST",
      url: 'controlador/finanzas/cliente/pane_pagos_c.php',
      data: 'opc=1'+valores,
      success:function(data) {
        valor = data.trim();
        if(id == 'Cant_pagos'){
          $('#'+id+id_num).html('<label class="text-right input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">$ '+valor+'</label>');
        }
        else if(id == 'Observaciones_pagos'){
          $('#'+id+id_num).html(
          '<label class="text-left input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">'+
            '<textarea class="input-xs label-form pointer" readonly style="background:none; border:none; font-weight:bold;">'+valor+'</textarea>'+
          '</label>'
          );
        }
        else{
          $('#'+id+id_num).html('<label class="text-left input-sm label-form pointer" onClick="Convertir_input(\''+id+'\',\''+id_num+'\',\''+valor+'\');">'+valor+'</label>');
        }
        Saldos_Fondo();
        ver_pago();
      }
    });
  }
}
function Clase_Insumos(opc,id,id2,id_num,valor) {
  valores = '&pc='+opc+'&valor='+valor;
  // alert(valores);
  $.ajax({
    type: "POST",
    url: 'controlador/finanzas/cliente/pane_pagos_c.php',
    data: 'opc=2'+valores,
    success:function(data) {
      $('#'+id+id_num).html(
        '<select class="form-control input-sm" id="'+id2+id_num+'" onBlur="Modificar_Compras(\''+id+'\',\''+id+'2\',\''+id_num+'\',\''+valor+'\',\'3\');" >'+
          '<option value="0">Seleccionar clase de insumo...</option>'+
          data+
        '</select>'
      );
      $('#'+id2+id_num).focus();
    }
  });
}
