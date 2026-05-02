<?php
  include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  $fin = new Finanzas;
  $now = $fin->NOW();
  $cont_Moneda = $fin->Select_Count_Moneda();
  $sql = $fin->Select_Data_Moneda();
?>
<div >
  <!-- <div class="row">
    <div class="form-group col-sm-3 col-xs-12">
      <label class=" col-sm-12">Fecha de emisión *</label>
      <div class="col-sm-12">
        <div class='input-group date calendariopicker'>
          <input type='text' class='select_input form-control input-sm' value='<?php echo $now;?>' id='date_nota' />
          <span class='input-group-addon input-sm' id='basic-addon2'><label class='fa fa-calendar'></label> </span>
        </div>
      </div>
    </div>
  </div> -->

  <!-- RETIRO ESPECIAL - -->
  <div class="row">
    <div class="form-group col-sm-12">
      <h5 class="text-danger"><strong><span class="icon-edit-1"></span> Politica de retiro y rembolso:</strong></h5>
      <h5 class="text-danger"><strong><span class="icon-right-dir"></span> Los retiros (totales ó parciales) y rembolsos únicamente pueden ser realizados al acumulado del día anterior.</strong></h5>
      <h5 class="text-danger"><strong><span class="icon-right-dir"></span> Tesorería debe comprobar que los movimientos de venta y fondo del día anterior esten registrados en el ERP antes de realizar cualquier movimiento.</strong></h5>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <button type="button" class="btn btn-sm btn-success col-sm-2 col-sm-offset-10 col-xs-8 col-xs-offset-2" onclick="ver_reporte();"><span class="icon-file-pdf"></span> PDF</button>
    </div>
  </div>
  <hr>
  <label class="col-xs-12 col-sm-12 text-center" ><span class="icon-money"></span>Retiro de Ventas</label>
  <br>
  <hr>

  <!-- ./Efectivo -->
  <div class="row">
    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label>Acumulado Efectivo</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="SI_Efect" value="0" disabled>
        </div>
      </div>
    </div>

    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label> Saldo de Efectivo hoy</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="SH_Efect" value="0" disabled>
        </div>
      </div>
    </div>

    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label> Retiro Efectivo</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="Ret_Efect" value="0" onKeyUp="Moneda('Ret_Efect');NSI_Venta();">
        </div>
      </div>
    </div>

    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label> Saldo Final Efectivo</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="SF_Efect" value="0" disabled>
        </div>
      </div>
    </div>
  </div>
  <!-- .\Efectivo -->

  <div class="text-center" id="Res_Efect"></div>
  <input type="hidden" id="Cont_ME" value="<?php echo $cont_Moneda; ?>">
  <input type="hidden" id="okay_efect" value="0">

  <!-- ./Moneda Extranjera -->
  <div id="ME"></div>
  <!-- .\Moneda Extranjera -->

  <!-- ./Total -->
  <div class="row">
    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label>Acumulado Total</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="SI_Total" value="0" disabled>
        </div>
      </div>
    </div>

    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label> Saldo Total hoy</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="SH_Total" value="0" disabled>
        </div>
      </div>
    </div>

    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label> Retiro Total</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="Retiro_Total" value="0" disabled>
        </div>
      </div>
    </div>

    <div class="form-group col-sm-3 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <label> Saldo Final Total</label>
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
          <input type="text" class="form-control input-sm" id="SF_Total" value="" disabled>
        </div>
      </div>
    </div>
  </div>
  <!-- ./Total -->

  <div class="text-center col-sm-12" id="Res_Esp"></div>

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <button type="button" id="Btn_Ret" class="btn btn-sm btn-info col-sm-4 col-sm-offset-4 col-xs-12" data-toggle='modal' data-target='#Modal_TeSobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_retiro.php?opc=Ret&Cant=<?php echo $cont_Moneda; ?>','TeSobre_Modal');">Retirar de Venta</button>
    </div>
  </div>




    <!-- REMBOLSO -->
    <hr>
    <label class="col-xs-12 col-sm-12 text-center" ><span class="icon-money"></span>Reembolso de Fondo</label>
    <br>
    <div id="Hola"></div>
    <hr>

    <div class="row">
      <div class="form-group col-sm-4 col-xs-12">
        <label class="col-sm-12 col-xs-12"><strong>Gastos Fondo</strong></label>
        <div class="col-xs-12 col-sm-12">
          <div class="input-group">
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
            <input type="text" class="form-control input-sm" id="TG" value="0" disabled>
          </div>
        </div>
      </div>
      <div class="form-group col-sm-4 col-xs-12">
        <label class="col-sm-12 col-xs-12"><strong>Anticipos</strong></label>
        <div class="col-xs-12 col-sm-12">
          <div class="input-group">
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
            <input type="text" class="form-control input-sm" id="TA" value="0" disabled>
          </div>
        </div>
      </div>
      <div class="form-group col-sm-4 col-xs-12">
        <label class="col-sm-12 col-xs-12"><strong>Pagos de Proveedor</strong></label>
        <div class="col-xs-12 col-sm-12">
          <div class="input-group">
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
            <input type="text" class="form-control input-sm" id="TP" value="0" disabled>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <hr>
      <div class="form-group col-sm-3 col-xs-12">
        <label class="col-sm-12 col-xs-12"><strong>Saldo Inicial
        </strong></label>
        <div class="col-xs-12 col-sm-12">
          <div class="input-group">
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
            <input type="text" class="form-control input-sm" id="SI" value="0" disabled>
          </div>
        </div>
      </div>

      <div class="form-group col-sm-3 col-xs-12">
        <label class="col-sm-12 col-xs-12"><strong>Reembolso Sugerido</strong></label>
        <div class="col-xs-12 col-sm-12">
          <div class="input-group">
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
            <input type="text" class="form-control input-sm" id="Rem_Sug"  value="0" onKeyUp="Moneda('NSI');" disabled>
          </div>
        </div>
      </div>

      <div class="form-group col-sm-3 col-xs-12">
        <label class="col-sm-12 col-xs-12"><strong>Reembolso</strong></label>
        <div class="col-xs-12 col-sm-12">
          <div class="input-group">
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
            <input type="text" class="form-control input-sm" id="Rem" value="0" onKeyUp = "NSI();Moneda('Rem');">
          </div>
        </div>
      </div>

      <div class="form-group col-sm-3 col-xs-12">
        <label class="col-sm-12 col-xs-12"><strong>Saldo Final</strong></label>
        <div class="col-xs-12 col-sm-12">
          <input type="hidden" id="SF_Temp" value="">
          <div class="input-group">
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
            <input type="text" class="form-control input-sm" id="SF" value="0" disabled>
          </div>
        </div>
      </div>
    </div>


    <div id="Res_Rem" class="text-center col-sm-12"> </div>

    <div class="row">
      <div class="form-group col-sm-12 col-xs-12">
        <button type="button" id="Btn_Ret_Rem" class="btn btn-sm btn-info col-sm-4 col-sm-offset-4 col-xs-12" data-toggle='modal' data-target='#Modal_TeSobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_retiro.php?opc=Rem&Cant=0','TeSobre_Modal');">Rembolso de fondo</button>
      </div>
    </div>

</div>
