<?php
  $opc = $_GET['opc'];
?>
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong>Acceso Administrador</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-12 col-xs-12">Ingrese contraseña de administrador</label>
      <div class="col-sm-12 col-xs-12">
        <input type="password" class="form-control input-sm" placeholder="Contraseña" id="Pass" onkeypress="if(event.keyCode == 13){ Acceso_Admin();}";>
        <div id="Res_Pass"></div>
      </div>
    </div>
  </div>

  <div class="text-center" id="Res"></div>

  <div class="row">
    <br>
    <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
        <button type="button" class="btn btn-danger btn-sm col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
    </div>
    <?php

    ?>
    <div class="form-group col-sm-4 col-xs-12">
        <button type="button" class="btn btn-primary btn-sm col-sm-12 col-xs-12" onclick="Acceso_Admin();">Acceder</button>
    </div>
  </div>
</div>
