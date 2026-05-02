<?php
  $opc = $_GET['opc'];
  $id = $_GET['id'];

?>
<input type="hidden" id="H_id" value="<?php echo $id;?>">
<input type="hidden" id="H_opc" value="<?php echo $opc;?>">
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-danger text-center"><strong> <span class="icon-attention"></span> ADVERTENCIA</strong></h3>
</div>
<div class="modal-body">
  <div class="row">
    <div class="form-group col-sm-8 col-sm-offset-2 text-center col-xs-12">
      <label><strong>Ingrese la contraseña si esta seguro de eliminar este valor</strong></label>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <div class="col-xs-12 col-sm-12">
        <div class="input-group">
          <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-address-card"></label></span>
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
      <button type="button" class="btn btn-primary btn-sm col-sm-4 col-sm-offset-4 col-xs-12" onClick="Password();">Eliminar Valor</button>
    </div>
  </div>
</div>
