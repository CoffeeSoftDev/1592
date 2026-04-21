<?php
  include_once("../../../SQL_PHP/_CRUD.php");
  include_once("../../../SQL_PHP/_Finanzas.php");
  $crud = new CRUD;
  $fin = new Finanzas;

  date_default_timezone_set('America/Mexico_City');

  $Name = $_POST['Name'];
  $idUC = $_POST['idUC'];
  $date = $_POST['date'];

  $array = array($idUC,$date);
  $query = "SELECT Pago,idBC FROM creditos_bitacora WHERE id_UC = ? AND Fecha_Credito = ?";
  $sql = $crud->_Select($query,$array,"5");
  $pago = array(); $idP = array();
  foreach ($sql as $key => $row) {
    $pago[$key] = $row[0];
    $idP[$key] = $row[1];
  }
  $cont_pago = count($pago);

  $query2 = "SELECT Cantidad,IdCC FROM creditos_consumo WHERE id_UC = ? AND Fecha_Consumo = ?";
  $sql2 = $crud->_Select($query2,$array,"5");
  $deuda = array(); $idC = array();
  foreach ($sql2 as $key => $row2) {
    $deuda[$key] = $row2[0];
    $idC[$key] = $row2[1];
  }
  $count_deuda = count($deuda);

  $Registros = $cont_pago + $count_deuda;

  $disable_date = ''; $btn_color = 'btn-danger';
  $hoy = $fin->NOW();
  $ayer = $fin->Ayer();
  $date = $_POST['date'];
  if($date != $hoy && $date != $ayer){
    $disable_date = 'disabled';
    $btn_color = 'btn-default';
  }

?>

<div class="row">
  <hr>
  <div class="form-group col-sm-12 col-xs-12 text-center">
    <label class="col-xs-12 col-sm-12" style="border:2px solid #DCE4EC; border-radius:5px; padding:10px;"><span class="icon-user"></span>Desglose Movimientos | <?php echo $Name; ?></label>
  </div>
</div>

<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">

  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Movimientos"></div>
  <div class="col-sm-4 col-xs-12 text-right">
    <h5><label><?php echo "Registros: ".$Registros; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
  <div class="table table-responsive" >
    <!--CONTENIDO-->
    <table class="table table-striped table-hover table-bordered table-condensed">
      <thead>
        <tr class="text-center " id="titulo">
            <td class="col-sm-4"><strong>CONCEPTO</strong></td>
            <td class="col-sm-4 text-center"><strong>CANTIDAD</strong></td>
            <td class="col-sm-4 text-center"><strong>CANCELACIÓN</strong></td>
        </tr>
      </thead>
      <tbody>
        <?php for ($i=0; $i < $cont_pago; $i++) { ?>
        <tr class="text-center " id="titulo">
            <td class="col-sm-4">Pago</td>
            <td class="col-sm-4 text-center">$ <?php echo number_format($pago[$i],2,'.',', '); ?></td>
            <td class="col-sm-4 text-center">
              <button type="button" class="btn btn-sm <?php echo $btn_color; ?>" title="Cancelar Movimiento" onclick="Eliminar_Mov_Credito(<?php echo $idUC.",'".$Name."','".$idP[$i]."',1"; ?>)"
              <?php echo $disable_date; ?>><span class="icon-cancel"></span></button>
            </td>
        </tr>
        <?php } ?>
        <tr class="text-center " id="titulo">
            <td class="col-sm-4 bg-success"></td>
            <td class="col-sm-4 text-center bg-success"></td>
            <td class="col-sm-4 text-center bg-success"></td>
        </tr>
        <?php for ($i=0; $i < $count_deuda; $i++) { ?>
        <tr class="text-center " id="titulo">
            <td class="col-sm-4">Deuda</td>
            <td class="col-sm-4 text-center">$ <?php echo number_format($deuda[$i],2,'.',', '); ?></td>
            <td class="col-sm-4 text-center">
              <button type="button" class="btn btn-sm <?php echo $btn_color; ?>" title="Cancelar Movimiento" onclick="Eliminar_Mov_Credito(<?php echo $idUC.",'".$Name."','".$idC[$i]."',2"; ?>)"
              <?php echo $disable_date; ?>><span class="icon-cancel"></span></button>
            </td>
        </tr>
        <?php } ?>

      </tbody>
    </table>
  </div>
</div>
