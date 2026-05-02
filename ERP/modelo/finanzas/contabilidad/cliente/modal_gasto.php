<?php
  include_once('../../../SQL_PHP/_Finanzas.php');
  $fis = new Finanzas;

  $sql1 = $fis->Select_Tipo_Gastos();
?>
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong>Agregar Gasto</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-3 col-xs-12">Concepto</label>
      <div class="col-sm-9 col-xs-12">
        <input type="text" class="form-control input-sm" placeholder="Concepto del gasto" id="Concepto_Gasto">
        <div id="Res_ConcG"></div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-3 col-xs-12">Clasificación</label>
      <div class="col-sm-9 col-xs-12">
        <select class="form-control input-sm" id="Clas_Gasto">
          <option value="0">Clasificación de la nota...</option>
          <?php foreach ($sql1 as $row1) {?>
          <option value="<?php echo $row1[0]; ?>" ><?php echo $row1[1]; ?></option>
          <?php } ?>
        </select>
        <div id="Res_ClasG"></div>
      </div>
    </div>
  </div>

  <div class="text-center" id="Res"></div>

  <div class="row">
    <br>
    <div class="form-group col-sm-6 col-xs-12">
        <button type="button" class="btn btn-danger btn-sm col-sm-12" data-dismiss="modal">Cancelar</button>
    </div>

    <div class="form-group col-sm-6 col-xs-12">
        <button type="button" class="btn btn-primary btn-sm col-sm-12" onclick="Insert_Gasto();">Agregar</button>
    </div>
  </div>
</div>
