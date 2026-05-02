<?php
  include_once('../../modelo/SQL_PHP/_Clientes_Prod.php');
  $obj = new CLIENTES;
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://TABLA CLIENTES
        echo '
        <table class="table table-bordered table-condensed table-striped table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th class="col-sm-12 col-xs-12">CLIENTE</th>
            </tr>
          </thead>
          <tbody>';
            $sql = $obj->Select_Clientes();
            foreach ($sql as $key => $value) {
              $hash = $key + 1;
              $idCliente = $value[0];
              $Name = $value[1];
              
              echo '
              <tr class="pointer" onclick="tabla_productos_clientes('.$idCliente.',\''.$Name.'\');">
                <td class="text-center">'.$hash.'</td>
                <td>'.$Name.'</td>
              </tr>';
            }
          echo
          '</tbody>
        </table>
        ';
      break;
    case 1://TABLA PRODUCTOS
        $idCliente = $_POST['idCliente'];
        $Name = strtr(strtoupper($_POST['Name']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
        if ( $Name == '0' ) { $Name = ' '; }
        echo '
          <table class="table table-bordered table-condensed table-stripped">
            <thead>
              <tr>
                <th class="text-center" colspan="5">'.$Name.'</th>
              </tr>
              <tr>
                <th rowspan="2">#</th>
                <th rowspan="2" class="col-sm-6 col-xs-6 text-center">PRODUCTO</th>
                <th colspan="3" class="col-sm-6 col-xs-6 text-center">COSTO</th>
              </tr>
              <tr>
                <th class="col-sm-1 col-xs-1 text-center">NORMAL</th>
                <th class="col-sm-1 col-xs-1 text-center">MAYOREO</th>
                <th class="col-sm-4 col-xs-4 text-center">ESPECIAL</th>
              </tr>
            </thead>
            <tbody>';
            $sql = $obj->Select_Productos();
            foreach ($sql as $key => $value) {
              $hash = $key + 1;
              $idProducto = $value[0];
              $Name = $value[1];
              $Normal = $value[2];
              $Mayoreo = $value[3];
              $Presentacion = $value[4];
              $costo = '';
              $disabled = 'disabled';
              if ( $idCliente != '0' ) {
                $costo = $obj->Select_Precio_especial($idCliente,$idProducto);
                $disabled = '';
              }

              echo '
              <tr>
                <td class="text-center">'.$hash.'</td>
                <td>'.$Name.' '.$Presentacion.'</td>
                <td class="text-right">$ '.number_format($Normal,2,'.',',').'</td>
                <td class="text-right">$ '.number_format($Mayoreo,2,'.',',').'</td>
                <td>
                  <div class="input-group">
                    <span class="input-group-addon input-sm"><i class="icono'.$idCliente.''.$idProducto.' icon-dollar"></i></span>
                    <input type="text" class="form-control input-sm" onBlur="Save_PrecioEspecial('.$idCliente.','.$idProducto.')" id="PE'.$idCliente.''.$idProducto.'" value="'.$costo.'" '.$disabled.'/>
                  </div>
                </td>
              </tr>
              ';
            }
            echo
            '</tbody>
          </table>
        ';
      break;
    case 2://SAVE CLIENTE
        $Cliente = $_POST['cliente'];
        $obj->Insert_Cliente($Cliente);
      break;
    case 3://CANTIDAD
        sleep(1);
        $idCliente = $_POST['idCliente'];
        $idProducto = $_POST['idProducto'];
        $Costo = $_POST['costo'];

        if ( $Costo != '' || $Costo != 0 ) {
          $idEpecial = $obj->Select_idPrecioEspecial($idCliente,$idProducto);
          if ( $idEpecial == 0 ) {
            $obj->Insert_PrecioEspecial($idCliente,$idProducto,$Costo);
          }
          else {
            $obj->Update_PrecioEspecial($Costo,$idEpecial);
          }
        }
        else {
          $obj->Delete_PrecioEspecial($idCliente,$idProducto);
        }
      break;
  }
?>
