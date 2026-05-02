<?php
  session_start();
  include_once('../../modelo/SQL_PHP/_Movimientos.php');
  include_once('../../modelo/SQL_PHP/_Utileria.php');
  $obj = new Movimientos;
  $util = new Util;
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://TABLA PRODUCTOS
        usleep(1000000);
        $date1      = $_POST['date1'];
        $date2      = $_POST['date2'];
        $idProducto = $_POST['idProducto'];

        $sql = $obj->Select_Fechas_Movimientos($date1,$date2,$idProducto);
        $fechas = array();
        foreach ($sql as $key => $value) {
          $fechas[$key] = $value[0];
        }

        $cont_fechas = count($fechas);

        echo '
          <div class="col-sm-12 col-xs-12 text-right">
            <label>Movimientos: <span class="icon-hash"></span> '.$cont_fechas.' </label>
          </div>
          <div class="col-sm-12 col-xs-12">
            <table id="tb_movimiento_full" class="table table-bordered table-condensed table-striped table-hover">
              <thead>
                <tr>
                  <th class="text-center">FECHA</th>
                  <th class="text-center">ENTRADAS</th>
                  <th class="text-center">SALIDAS</th>
                </tr>
              </thead>
              <tbody>';
                for ($i=0; $i < $cont_fechas; $i++) {
                  $date = $fechas[$i];

                  $a = date("Y", strtotime("$date"));
                  $m = date("m", strtotime("$date"));
                  $d = date("d", strtotime("$date"));
                  $mes = $util->Mes_Letra($m);
                  $date2 = $d.'-'.$mes.'-'.$a;

                  $entrada  = $obj->Select_Movimientos_Entrada($date,$idProducto);
                  $salida   = $obj->Select_Movimientos_Salida($date,$idProducto);
                  echo
                  '<tr>
                    <td class="text-center">'.$date2.'</td>
                    <td class="text-center">'.$entrada.'</td>
                    <td class="text-center">'.$salida.'</td>
                  </tr>';
                }
              echo
              '</tbody>
            </table>
          </div>
        ';
      break;
    case 1://DATA PRODUCTOS
        $idProducto = $_POST['idProducto'];

        $min_inventario = $obj->Select_MinInventario_Productos($idProducto);

        $entrada = $obj->Select_Movimientos_Entrada_Inventario($idProducto);
        $salida = $obj->Select_Movimientos_Salida_Inventario($idProducto);
        $actual = $entrada - $salida;

        // ESTATUS DE INVENTARIO
        $status = '';
        if ( $min_inventario < $actual ) {
          $status = '<span class="icon-ok-circled-1 text-success"></span>';
        }
        else if ( $min_inventario >= $actual ) {
          $status = '<span class="text-danger icon-attention-3">PEDIR</span>';
        }

        $resultado = array($actual,$status);
        echo json_encode($resultado);
      break;
  }
?>
