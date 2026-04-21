<?php
  include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  $fin = new Finanzas;

  $date_now = $fin->NOW();
?>

<div class="container">
  <div class="panel panel-default">
    <div class="panel-body">

      <div class="row">
        <div class="form-group col-sm-3 col-xs-12"><!-- Fecha -->
         <label class=" col-sm-12">Fecha de acción</label>
         <div class="col-sm-12">
          <div class="input-group date calendariopicker">
           <input type="text" class="select_input form-control input-sm" value="<?php echo $date_now; ?>" id="date">
           <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
          </div>
         </div>
       </div>
      </div>

      <div class="row">
        <div class="form-group Res_General">
          <div class="form-group col-sm-12">
            <h5 class="text-danger"><strong><span class="icon-edit-1"></span> Politica de retiro y reembolso:</strong></h5>
            <h5 class="text-danger"><strong><span class="icon-right-dir"></span> Los retiros (totales ó parciales) y reembolsos únicamente pueden ser realizados al acumulado del día anterior.</strong></h5>
            <h5 class="text-danger"><strong><span class="icon-right-dir"></span> Tesorería debe comprobar que los movimientos de venta y fondo del día anterior esten registrados en el sistema antes de realizar cualquier movimiento.</strong></h5>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-12 col-xs-12">
          <button type="button" class="btn btn-sm btn-success col-sm-2 col-sm-offset-10 col-xs-8 col-xs-offset-2" onclick="Print_RetRem();"><span class="icon-file-pdf"></span> PDF</button>
        </div>
      </div>

      <hr>
      <label class="col-xs-12 col-sm-12 text-center" ><span class="icon-money"></span>Retiro de Efectivo</label>
      <br>
      <hr>

      <!-- ./Efectivo -->
      <div class="row">
        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label>Acumulado Ventas</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SI_Efect" value="0" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label> Saldo de Ventas hoy</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SH_Efect" value="0" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label> Retiro Ventas</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="Ret_Efect" value="0" onKeyUp="retiro_ventas();">
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label> Saldo Final Ventas</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SF_Efect" value="0" disabled>
            </div>
          </div>
        </div>
      </div>
      <div class="row text-center Res_Efect"></div>
      <!-- .\Efectivo -->

      <!-- ./Propina -->
      <div class="row">
        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label>Acumulado Propina</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SI_Prop" value="0" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label> Saldo de Propina hoy</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SH_Prop" value="0" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label> Retiro Propina</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="Ret_Prop" value="0" onKeyUp="retiro_ventas();">
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <div class="col-sm-12 col-xs-12">
            <label> Saldo Final Propina</label>
            <div class="input-group">
              <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SF_Prop" value="0" disabled>
            </div>
          </div>
        </div>
      </div>
      <div class="row text-center Res_Prop"></div>
      <!-- .\Propina -->

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


      <div class="row">
        <div class="form-group col-sm-12 col-xs-12">
          <div class="text-center col-sm-12" id="Res_Esp"></div>
          <button type="button" id="Btn_Ret" class="btn btn-sm btn-info col-sm-4 col-sm-offset-4 col-xs-12" data-toggle='modal' data-target='#exampleModal' onClick="Modal_retiro();">Retiro de Venta</button>
        </div>
      </div>

      <!-- REMBOLSO -->
      <hr>
      <label class="col-xs-12 col-sm-12 text-center" ><span class="icon-money"></span>Reembolso de Fondo</label>
      <br>
      <hr>

      <div class="row">
        <div class="form-group col-sm-3 col-xs-12">
          <label class="col-sm-12 col-xs-12"><strong>Gastos Fondo</strong></label>
          <div class="col-xs-12 col-sm-12">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="TG" value="0" disabled>
            </div>
          </div>
        </div>
        <!-- <div class="form-group col-sm-4 col-xs-12">
          <label class="col-sm-12 col-xs-12"><strong>Anticipos</strong></label>
          <div class="col-xs-12 col-sm-12">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="TA" value="0" disabled>
            </div>
          </div>
        </div> -->
        <div class="form-group col-sm-3 col-xs-12">
          <label class="col-sm-12 col-xs-12"><strong>Pagos de Proveedor</strong></label>
          <div class="col-xs-12 col-sm-12">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="TP" value="0" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-6 col-xs-12">
          <label class="col-sm-12 col-xs-12"><strong>Observaciones</strong></label>
          <div class="col-xs-12 col-sm-12">
            <textarea class="form-control input-sm" style="resize:none;" rows="4" id="Obs_Rem"></textarea>
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
              <input type="text" class="form-control input-sm" id="SI_Reem" value="0" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <label class="col-sm-12 col-xs-12"><strong>Reembolso Sugerido</strong></label>
          <div class="col-xs-12 col-sm-12">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="Reem_Sug"  value="0" onKeyUp="Moneda('NSI');" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <label class="col-sm-12 col-xs-12"><strong>Reembolso</strong></label>
          <div class="col-xs-12 col-sm-12">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="Reem" value="0" onKeyUp = "Calculo_Reembolso();">
            </div>
          </div>
        </div>

        <div class="form-group col-sm-3 col-xs-12">
          <label class="col-sm-12 col-xs-12"><strong>Saldo Final</strong></label>
          <div class="col-xs-12 col-sm-12">
            <input type="hidden" id="SF_Temp" value="">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SF_Reem" value="0" disabled>
            </div>
          </div>
        </div>
      </div>


      <div class="row">
        <div class="form-group col-sm-12 col-xs-12">
          <div id="Res_Rem" class="text-center col-sm-12"> </div>
          <button type="button" id="Btn_Ret_Rem" class="btn btn-sm btn-info col-sm-4 col-sm-offset-4 col-xs-12" data-toggle='modal' data-target='#exampleModal' onClick="Modal_reembolso();">Rembolso de fondo</button>
        </div>
      </div>

<div class="content-table">

</div>

    </div>
  </div>
</div>
