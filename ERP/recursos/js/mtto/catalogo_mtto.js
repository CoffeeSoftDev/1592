$(function(){
  pane(1);
});

function pane(p_opc){
  switch(p_opc) {
    case 1://MATERIALES
      materiales();
      break;
    case 2:
      consumibles();
      break;
  }
}

function materiales() {
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=0',
    success:function(data) {
      $('.content_body').html(data);
      tb_materiales_categoria(1);
      tb_materiales_productos(1);
      tb_materiales_areas(1);
    }
  });
}
function modal_eliminar(dat,id){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=9&dat='+dat+'&id='+id,
    success:function(data) {
      $('.modal-content').html(data);
    }
  });
}
function Delete_Registro(dat,id){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/ctrl_pane.php',
    data:'opc=3&dat='+dat+'&id='+id,
    success:function(data) {
      // $('.content_body').html(data);
      if ( dat == 3 ) {
        tb_materiales_categoria(1);
      }
      else if ( dat == 4 ) {
        tb_materiales_productos(1);
      }
      else if ( dat == 5 ) {
        tb_materiales_areas(1);
      }
      $("[data-dismiss=modal]").trigger({ type: "click" });
    }
  });
}

function tb_materiales_categoria(pag){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=1&pag='+pag,
    success:function(data){
      $('.tb_categoria').html(data);
    }
  });
}
function Save_Categoria(){
  var valor = true;
      if ( !$('#Ipt_Categoria').val() ) {
        valor = false;
        $('#Ipt_Categoria').focus();
        $('.control_categoria').addClass('has-error');
      }

      if ( valor ) {
        $('.control_categoria').removeClass('has-error');
        valores = '&ipt_categoria=' + $('#Ipt_Categoria').val();
        $.ajax({
          type:'POST',
          url:'controlador/mtto/catalogo/ctrl_pane.php',
          data:'opc=0'+valores,
          success:function(data) {
            if ( data == 0 ) {
              $('#Ipt_Categoria').val('');
              $('.Res_Categoria').html('');
              tb_materiales_categoria(1);
            }
            else {
               $('.Res_Categoria').html('<label class="text-warning"><span class="icon-attention"></span> El concepto ya existe. </label>');
            }
          }
        });
      }
}

function tb_materiales_productos(pag){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=2&pag='+pag,
    success:function(data){
      $('.tb_productos').html(data);
    }
  });
}
function Save_Productos(){
  var valor = true;
      if ( !$('#ipt_producto').val() ) {
        valor = false;
        $('#ipt_producto').focus();
        $('.control_stock').removeClass('has-error');
        $('.control_productos').addClass('has-error');
      }
      else if ( !$('#inp_stock').val() ) {
        valor = false;
        $('#inp_stock').focus();
          $('.control_productos').removeClass('has-error');
        $('.control_stock').addClass('has-error');
      }

      if ( valor ) {
        $('.control_productos').removeClass('has-error');
        $('.control_stock').removeClass('has-error');
        valores = '&ipt_producto='+$('#ipt_producto').val()+'&inp_stock='+$('#inp_stock').val();
        $.ajax({
          type:'POST',
          url:'controlador/mtto/catalogo/ctrl_pane.php',
          data:'opc=1'+valores,
          success:function(data) {
            // $('.Res_Productos').html(data);
            if ( data == 0 ) {
              $('#ipt_producto').val('');
              $('#inp_stock').val('');
              $('.Res_Productos').html('');
              tb_materiales_productos(1);
            }
            else {
               $('.Res_Productos').html('<label class="text-warning"><span class="icon-attention"></span> El concepto ya existe. </label>');
            }
          }
        });
      }
}

function tb_materiales_areas(pag){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=3&pag='+pag,
    success:function(data){
      $('.tb_areas').html(data);
    }
  });
}
function Save_areas(){
  var valor = true;
      if ( !$('#ipt_area').val() ) {
        valor = false;
        $('#ipt_area').focus();
        $('.control_area').addClass('has-error');
      }

      if ( valor ) {
        $('.control_area').removeClass('has-error');
        valores = '&ipt_area='+$('#ipt_area').val();
        $.ajax({
          type:'POST',
          url:'controlador/mtto/catalogo/ctrl_pane.php',
          data:'opc=2'+valores,
          success:function(data) {
            $('.Res_area').html(data);
            if ( data == 0 ) {
              $('#ipt_area').val('');
              $('.Res_area').html('');
              tb_materiales_areas(1);
            }
            else {
               $('.Res_area').html('<label class="text-warning"><span class="icon-attention"></span> El concepto ya existe. </label>');
            }
          }
        });
      }
}


function consumibles() {
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=4',
    success:function(data) {
      $('.content_body').html(data);
      tb_consumibles_clases(1);
      tb_consumibles_familias(1);
      tb_consumibles_insumo(1);
      tb_consumibles_marca(1);
    }
  });
}
function modal_eliminar2(dat,id){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=10&dat='+dat+'&id='+id,
    success:function(data) {
      $('.modal-content').html(data);
    }
  });
}
function Delete_Registro2(dat,id){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/ctrl_pane.php',
    data:'opc=8&dat='+dat+'&id='+id,
    success:function(data) {
      // $('.content_body').html(data);
      if ( dat == 5 ) {
        tb_consumibles_clases(1);
      }
      else if ( dat == 6 ) {
        tb_consumibles_familias(1);
      }
      else if ( dat == 7 ) {
        tb_consumibles_insumo(1);
      }
      else if ( dat ==  8 ) {
        tb_consumibles_marca(1);
      }
      $("[data-dismiss=modal]").trigger({ type: "click" });
    }
  });
}

function tb_consumibles_clases(pag){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=5&pag='+pag+'&SL_Clase='+$('#SL_Clase').val(),
    success:function(data){
      $('.tb_clases').html(data);
    }
  });
}
function Save_Clases(){
  var valor = true;
  if ( !$('#Inp_Clases').val() ) {
    $('#Inp_Clases').focus();
    $('.Group_Clases').addClass('has-error');
    valor = false;
  }

  if ( valor ) {
    $('.Group_Clases').removeClass('has-error');
    valores = '&Inp_Clases='+$('#Inp_Clases').val()+'&idFam='+$('#SL_Clase').val();
    $.ajax({
      type:'POST',
      url:'controlador/mtto/catalogo/ctrl_pane.php',
      data:'opc=4&'+valores,
      success:function(data){
        if ( data == 0 ) {
          $('#Inp_Clases').val('');
          $('.Res_Clase').html('');
          tb_consumibles_clases(1);
        }
        else {
           $('.Res_Clase').html('<label class="text-warning"><span class="icon-attention"></span> El concepto ya existe. </label>');
        }
        consumibles();
      }
    });
  }
}

function tb_consumibles_familias(pag) {
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=6&categ='+$('#Sl_Familia').val()+'&pag='+pag,
    success:function(data){
      $('.tb_familias').html(data);
    }
  });
}
function Save_Familias(){
  var valor = true;
  if ( !$('#inp_familia').val() ) {
    $('#inp_familia').focus();
    $('.Group_Familia').addClass('has-error');
    valor = false;
  }

  if ( valor ) {
    $('.Group_Familia').removeClass('has-error');
    valores = '&inp_familia='+$('#inp_familia').val()+'&cat='+$('#Sl_Familia').val();
    $.ajax({
      type:'POST',
      url:'controlador/mtto/catalogo/ctrl_pane.php',
      data:'opc=5&'+valores,
      success:function(data) {
        // $('.Res_Familia').html(data);
        if ( data == 0 ) {
          $('#inp_familia').val('');
          $('.Res_Familia').html('');
          tb_consumibles_familias(1);
        }
        else {
           $('.Res_Familia').html('<label class="text-warning"><span class="icon-attention"></span> El concepto ya existe. </label>');
        }
        consumibles();
      }
    });
  }
}

function tb_consumibles_insumo(pag){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=7&pag='+pag,
    success:function(data){
      $('.tb_insumos').html(data);
    }
  });
}
function Save_Insumos(){
  var valor = true;
      if ( !$('#ipt_insumo').val() ) {
        valor = false;
        $('#ipt_insumo').focus();
        $('.control_stock').removeClass('has-error');
        $('.control_Insumos').addClass('has-error');
      }
      else if ( !$('#inp_stock').val() ) {
        valor = false;
        $('#inp_stock').focus();
          $('.control_Insumos').removeClass('has-error');
        $('.control_stock').addClass('has-error');
      }

      if ( valor ) {
        $('.control_Insumos').removeClass('has-error');
        $('.control_stock').removeClass('has-error');
        valores = '&ipt_insumo='+$('#ipt_insumo').val()+'&inp_stock='+$('#inp_stock').val();
        $.ajax({
          type:'POST',
          url:'controlador/mtto/catalogo/ctrl_pane.php',
          data:'opc=6'+valores,
          success:function(data) {
            // $('.Res_Insumo').html(data);
            if ( data == 0 ) {
              $('#ipt_producto').val('');
              $('#inp_stock').val('');
              $('.Res_Insumo').html('');
              tb_consumibles_insumo(1);
            }
            else {
               $('.Res_Insumo').html('<label class="text-warning"><span class="icon-attention"></span> El concepto ya existe. </label>');
            }
          }
        });
      }
}

function tb_consumibles_marca(pag){
  $.ajax({
    type:'POST',
    url:'controlador/mtto/catalogo/vista_panes.php',
    data:'opc=8&pag='+pag,
    success:function(data){
      $('.tb_marca').html(data);
    }
  });
}
function Save_Marcas(){
  var valor = true;
  if ( !$('#ipt_marca').val() ) {
    $('#ipt_marca').focus();
    $('.Group_Marca').addClass('has-error');
    valor = false;
  }

  if ( valor ) {
    $('.Group_Marca').removeClass('has-error');
    valores = '&ipt_marca='+$('#ipt_marca').val();
    $.ajax({
      type:'POST',
      url:'controlador/mtto/catalogo/ctrl_pane.php',
      data:'opc=7&'+valores,
      success:function(data) {
        if ( data == 0 ) {
          $('#ipt_marca').val('');
          $('.Res_Marca').html('');
          tb_consumibles_marca(1);
        }
        else {
           $('.Res_Marca').html('<label class="text-warning"><span class="icon-attention"></span> El concepto ya existe. </label>');
        }
      }
    });
  }
}
