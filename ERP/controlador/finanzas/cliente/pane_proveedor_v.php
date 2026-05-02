<?php
  session_start();
  include_once('../../../modelo/SQL_PHP/_Finanzas_Proveedor.php');
  include_once('../../../modelo/SQL_PHP/_Utileria.php');
  $fin = new Finanzas;
  $util = new Util;
  $idE = $_SESSION['udn'];
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0:
      ?>
      <div class="row">
        <hr>
        <div class="form-group col-sm-12 col-xs-12 text-center">
          <label class="col-xs-12 col-sm-12" style="border:2px solid #DCE4EC; border-radius:5px; padding:10px;"><span class="icon-truck"></span> Proveedor</label>
        </div>
      </div>
      <div class="row container">
        <div class="form-group col-sm-5 col-xs-12">
          <label class="col-sm-4 col-xs-12">Saldo Inicial</label>
          <div class="col-xs-12 col-sm-8">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SI_proveedor" value="0" disabled>
            </div>
          </div>
        </div>

        <div class="form-group col-sm-5 col-xs-12">
          <label class="col-sm-4 col-xs-12">Saldo Final</label>
          <div class="col-xs-12 col-sm-8">
            <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="SF_proveedor" value="0" disabled>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12 col-xs-12 tb_proveedor">

        </div>
      </div>
      <?php
      break;
    case 1://SALDOS DE PROVEEDOR
        $date = $_POST['date'];

        $array = array($idE,$date);
        $op = '<';
        $Gastos = $fin->Select_Saldo_Gasto_Proveedor($op,$array);
        $Pagos = $fin->Select_Saldo_Pagos_Proveedor($op,$array);
        $SI = $Gastos - $Pagos;
        //
        $array = array($idE,$date);
        $op = '<=';
        $Gastos2 = $fin->Select_Saldo_Gasto_Proveedor($op,$array);
        $Pagos2 = $fin->Select_Saldo_Pagos_Proveedor($op,$array);
        $SF = $Gastos2 - $Pagos2;

        $resultado = array(
          number_format($SI,2,'.',', '),
          number_format($SF,2,'.',', ')
        );
        echo json_encode($resultado);
      break;
  }
?>
