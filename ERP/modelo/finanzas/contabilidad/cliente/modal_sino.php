<?php
  $id = $_GET['id'];
  $opc = $_GET['opc'];
?>
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center text-danger"><strong><span class="icon-attention"></span>Eliminar Movimiento</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12 text-center">
      <label class="col-sm-12 col-xs-12">Esta seguro de realizar este movimiento, al eliminarlo no podrá recuperarse</label>
    </div>
  </div>
  <div class="row">
    <br>
    <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
        <button type="button" class="btn btn-danger btn-xs col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
    </div>
    <?php

    ?>
    <div class="form-group col-sm-4 col-xs-12">
        <button type="button" class="btn btn-primary btn-xs col-sm-12 col-xs-12" onclick="Eliminar_Compras_Pagos(<?php echo $id.','.$opc; ?>);">Continuar</button>
    </div>
  </div>
</div>
