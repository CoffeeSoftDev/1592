<?php
  include_once('../../../modelo/SQL_PHP/_Finanzas_Compras.php');
  include_once('../../../modelo/SQL_PHP/_Utileria.php');
  $fin = new Compras_Fin;
  $util = new Util;

  $opc = $_POST['opc'];

  switch ($opc) {
    case 0:
      ?>
      <br>
      <div class="row">
        <div class="form-group col-sm-12 col-xs-12">
          <h3 class="text-center"><strong><span class="icon-basket"></span>PAGOS</strong></h3>
          <hr>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 co-xs-12">
          <div class="form-group col-sm-4 col-xs-12">
            <label>Proveedor</label>
            <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="Proveedor" onkeyup="PS_bloq(1);" id="Proveedor">
          </div><!-- ./Proveedor-->

          <div class="form-group col-sm-4 col-xs-12">
            <label>Concepto</label>
            <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="Concepto" onkeyup="PS_bloq(2);" id="Insumo">
          </div><!-- ./Concepto -->

          <div class="form-group col-sm-4 col-xs-12">
            <label>Monto</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
              <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="0.00" id="Pago">
            </div>
          </div><!-- ./Monto -->

          <div class="form-group col-sm-4 col-xs-12 Categoria" >
            <label>Categoría</label>
            <select class="form-control input-sm col-sm-12 col-xs-12" id="Clase_Gasto">
              <option value="0">Categoría</option>
            </select>
          </div><!-- ./Categoría-->

          <div class="form-group col-sm-4 col-xs-12 Pagador">
            <label>Pagador</label>
            <select class="form-control input-sm col-sm-12 col-xs-12" id="Clase_Insumo">
              <option value="0">Argovía</option>
              <option value="0">Crédito</option>
              <option value="0">Caja Chica</option>
            </select>
          </div><!-- ./Pagador-->

          <div class="form-group col-sm-4 col-xs-12">
            <label>Observaciones</label>
            <textarea class="form-control input-sm" id="Observaciones"></textarea>
          </div><!-- ./Pagador-->
        </div><!-- ./Div Contenedor -->
      </div><!--./Row-->

      <div class="row">
        <div class="col-sm-12 col-xs-12">
          <div class="text-center" id="Respuesta_Gastos"></div>
          <div class="form-group col-sm-12 col-xs-12">
            <button type="button" class="col-sm-4 col-sm-offset-4 col-xs-10 col-xs-offset-1 btn btn-primary" onClick="nuevo_pago();">Guardar Compra</button>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 col-xs-12" id="table_data">

        </div>
      </div>

      <script type="text/javascript" src="recursos/js/finanzas/cliente/pagos.js?t=<?=time()?>"></script>

      <?php
      break;
    case 1://Categoria
        $sql = $fin->Select_Categoria();
        $select = '<label  class="control-label" for="Clase_Insumo">Destino</label>
        <select class="form-control input-sm col-sm-12 col-xs-12" id="Clase_Insumo">
        <option value="0">Seleccionar Destino...</option>';
        foreach ($sql as $key => $value) {
          $select = $select.'<option value="'.$value[0].'">'.$value[1].'</option>';
        }
        $select = $select.'</select>';

        echo $select;
      break;
    case 2://Pagador
      $sql = $fin->Select_Pagadores(1);
      $select = '<label class="control-label" for="Clase_Gasto">Pagador</label>
      <select class="form-control input-sm col-sm-12 col-xs-12" id="Clase_Gasto">
      <option value="0">Seleccionar Pagador...</option>';
      foreach ($sql as $key => $value) {
        $select = $select.'<option value="'.$value[0].'">'.$value[1].'</option>';
      }
      $select = $select.'</select>';

      echo $select;
      break;
  }
?>
