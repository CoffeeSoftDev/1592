<?php
  $opc = $_GET['opc'];
  $id = $_GET['Cant'];
?>
<input type="hidden" id="H_id" value="<?php echo $id; ?>">
<input type="hidden" id="H_opc" value="<?php echo $opc; ?>">
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong><?php if($opc == 1){ echo 'REMBOLSO DE FONDO'; } else { echo 'RETIRO DE VENTA';}?> </strong></h3>
</div>
<div class="modal-body">
  <div class="row">
    <div class="form-group col-sm-8 col-sm-offset-2 text-center col-xs-12">
      <label><strong>Verifique que los datos sean correctos, de ser así ingrese su contraseña para poder guardar los cambios</strong></label>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-12 col-xs-12 text-center"><strong>Identificación por contraseña</strong></label>
      <div class="col-xs-12 col-sm-12">
        <div class="input-group">
          <span class="input-group-addon input-sm" id="basic-addon2"><label style="font-size:16px;" class="icon-lock"></label></span>
          <input type="password" class="form-control input-sm" id="Pass" autocomplete="off" onkeypress="if(event.keyCode == 13){ Password(); }";>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12 text-center" id="Res">
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <button type="button" class="btn btn-primary btn-sm col-sm-4 col-sm-offset-4 col-xs-12" onClick="Password();">Guardar Retiro</button>
    </div>
  </div>
</div>
