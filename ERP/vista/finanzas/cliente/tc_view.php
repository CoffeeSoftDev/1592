<?php
  include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  $fin = new Finanzas;

  $date = $_POST['date'];
  $disabled = $fin->Select_Folio_Now($date);
  // $disabled = '';

  $sql_terminal = $fin->Select_Terminal();
  $sql_tipo_terminal = $fin->Select_Tipo_Terminal();
?>

<div id="cont2">
  <div class="row">
    <hr>
    <div class="form-group col-sm-12 col-xs-12 text-center">
      <label class="col-xs-12 col-sm-12" style="border:2px solid #DCE4EC; border-radius:5px; padding:10px;"><span class="icon-print-1"></span> T.C. <span class="icon-clipboard"></span></label>
    </div>
  </div>

  <input type="hidden" Class="form-control input-sm" value="<?php echo $disabled; ?>" id="hide_id">

  <div class="row">
    <div class="form-group col-sm-4 col-xs-12 gb_monto">
      <label class="col-sm-12 col-xs-12 control-label" for="Ipt_Monto">Monto</label>
      <div class="col-sm-12 col-xs-12">
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="Ipt_Monto" <?php echo $disabled; ?>  />
        </div>
      </div>
    </div>

    <div class="form-group col-sm-4 col-xs-12 gb_terminal">
      <label class="col-sm-12 col-xs-12 control-label" for="terminal">Terminal</label>
      <div class="col-sm-12 col-xs-12">
        <select class="form-control input-sm" id="terminal" <?php echo $disabled; ?> >
          <option value="0">- terminal -</option>
          <?php
          foreach ($sql_terminal as $key => $value) {
            echo '<option value="'.$value[0].'" >'.$value[1].'</option>';
          }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group col-sm-4 col-xs-12 gb_tipo_terminal">
      <label class="col-sm-12 col-xs-12 control-label" for="tipo_terminal">Tipo de Terminal</label>
      <div class="col-sm-12 col-xs-12">
        <select class="form-control input-sm" id="tipo_terminal" <?php echo $disabled; ?>>
          <option value="0">- tipo terminal -</option>
          <?php
            foreach ($sql_tipo_terminal as $key => $value) {
              echo '<option value="'.$value[0].'">'.$value[1].'</option>';
            }
          ?>
        </select>
      </div>
    </div>

    <div class="form-group col-sm-4 col-xs-12 gb_ipt_concepto">
      <label class="col-sm-12 col-xs-12 control-label" for="ipt_concepto">Concepto de Pago</label>
      <div class="col-sm-12 col-xs-12">
        <input type="text" class="form-control input-sm" id="ipt_concepto" <?php echo $disabled; ?>/>
      </div>
    </div>

    <div class="form-group col-sm-4 col-xs-12 gb_ipt_especificacion">
      <label class="col-sm-12 col-xs-12 control-label" for="ipt_especificacion">Especificación</label>
      <div class="col-sm-12 col-xs-12">
        <input type="text" class="form-control input-sm" id="ipt_especificacion" <?php echo $disabled; ?>/>
      </div>
    </div>

    <div class="form-group col-sm-4 col-xs-12 gb_ipt_cliente">
      <label class="col-sm-12 col-xs-12 control-label" for="ipt_cliente">Nombre del Cliente</label>
      <div class="col-sm-12 col-xs-12">
        <input type="text" class="form-control input-sm" id="ipt_cliente" <?php echo $disabled; ?>/>
      </div>
    </div>

    <div class="form-group col-sm-4 col-xs-12 gb_ipt_autorizacion">
      <label class="col-sm-12 col-xs-12 control-label" for="ipt_autorizacion">Número de Autorización</label>
      <div class="col-sm-12 col-xs-12">
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-hash"></label></span>
          <input type="text" class="form-control input-sm" id="ipt_autorizacion" <?php echo $disabled; ?> />
        </div>
      </div>
    </div>

    <div class="form-group col-sm-8 col-xs-12 gb_Observaciones">
      <label class="col-sm-12 col-xs-12 control-label" for="Observaciones">Observaciones</label>
      <div class="col-sm-12 col-xs-12">
        <textarea class="form-control input-sm col-sm-12 col-xs-12" id="Observaciones" <?php echo $disabled;  ?> > </textarea>
      </div>
    </div>
  </div>
<br>
<div class="form-group col-sm-12 col-xs-12 Res_all">

</div>
<br>
<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="text-center" id="Respuesta_Gastos"></div>
    <button type="button" class="btn btn-sm btn-primary col-sm-offset-5 col-sm-2 col-xs-8 col-xs-offset-2" onClick="tc_save();" <?php  echo $disabled; ?>><span class="icon-ok"></span> Guardar movimiento</button>
  </div>
</div>

<div class="row">
  <div class="form-group col-sm-12 col-xs-12 tb_data">

  </div>
</div>
</div>
