<?php
  session_start();
  include_once('../../modelo/SQL_PHP/_Productos.php');
  $obj = new Productos;
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://TABLA PRODUCTOS

        $cont = $obj->Select_count_productos();
        echo '
        <div class="form-group col-sm-4 col-sm-offset-4 col-xs-12 text-center respuestas">

        </div>
        <div class="form-group col-sm-4 col-xs-12 text-right">
          <label><span class="icon-hash"></span>'.$cont.' Productos</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12 table-responsive">
          <table class="table table-bordered table-stripped table-condensed table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th class="col-sm-6 text-center"> PRODUCTO</th>
                <th class="col-sm-2 text-center"> PRESENTACIÓN</th>
                <th class="col-sm-2 text-center"> EXISTENCIA</th>
                <th class="col-sm-2 text-center"> ESTATUS</th>
              </tr>
            </thead>
            <tbody>';
            $sql = $obj->Select_tabla_productos();
            foreach ($sql as $key => $value) {
              $hash = $key + 1;
              $idProducto = $value[0];
              $name_producto = $value[1];
              $presentacion = $value[2];
              $precio = $value[3];
              $min_inventario = $value[4];

              //INVENTARIO INICIAL
              $sql_almacen = $obj->Select_Inventario_Inicial($idProducto);
              $row = null; foreach($sql_almacen as $row);
              $fecha_inventario = $row[0];
              $inventario_inicial = $row[1];

              // INVENTARIO EXISTENTE
              $vendidos = $obj->Select_Inventario_Vendido($idProducto,$fecha_inventario);
              $actual = $inventario_inicial - $vendidos;

              // ESTATUS DE INVENTARIO
              $status = ''; $limite_alerta = $min_inventario + 15;
              if ( $min_inventario < $actual ) {
                $status = '<label class="text-primary"><span style="font-size:1.2em;" class="icon-ok-circled-1"></span></label>';
              }
              else if ( $min_inventario >= $actual ) {
                $status = '<label class="text-danger"><span class=" icon-attention-3"></span> PEDIR </label>';
              }

              echo '
              <tr>
                <!--HASH-->
                <td class="text-center" style="padding:0 5px;">'.$hash.'</td>

                <!--NOMBRE PRODUCTO-->
                <td style="padding:0 5px;">'.$name_producto.'</td>

                <!--PRESENTACIÓN-->
                <td class="text-center" style="padding:0 5px;">'.$presentacion.'</td>

                <!--INVENTARIO ACTUAL-->
                <td class="text-center" style="padding:0 5px;">'.$actual.'</td>

                <!--INVENTARIO ESTATUS-->
                <td class="text-center" style="padding:0 5px;">'.$status.'</td>
              </tr>
              ';
            }

            echo
            '</tbody>
          </table>
        </div>
        ';
      break;
    case 1://INSERTAR PRODUCTOS
        usleep(500000);
        $date           = $_POST['date'];
        $producto       = $_POST['producto'];
        $presentacion   = $_POST['presentacion'];
        $min_inventario = $_POST['min_inventario'];
        $precio         = $_POST['precio'];

        $idProducto = $obj->Select_idProducto($producto,$presentacion);
        if ( $idProducto == 0) {
          $array = array($date,$producto,$presentacion,$min_inventario,$precio);
          $obj->Insert_Productos($array);
        }

        echo $idProducto;
      break;
    case 2://ACTUALIZAR DATOS DE PRODUCTOS
        usleep(500000);
        $idProducto = $_POST['idProducto'];
        $valor1 = $_POST['valor1'];
        $valor2 = $_POST['valor2'];
        $caso = $_POST['caso'];
        $extra = $_POST['extra'];
        $respuesta = array();
        switch ($caso) {
          case 1://ACTUALIZAR NOMBRE DEL PRODUCTO
              $idProducto = $obj->Select_idProducto($valor2,$extra);
              if ( $idProducto == 0) {
                $obj->Update_Presentacion($valor2,$idProducto);
                $respuesta = array(1,$extra);
              }
              else {
                $respuesta = array(0,$valor2);
              }
            break;
          case 2://ACTUALIZAR PRESENTACIÓN
              $obj->Update_Presentacion($valor2,$idProducto);
              $respuesta = array(1,$valor2);
            break;
          case 3://ACTUALIZAR PRECIO
              $obj->Update_Precio($valor2,$idProducto);
              $respuesta = array($valor2,number_format($valor2,2,'.',','));
            break;
          case 4://ACTUALIZAR NUEVO INVENTARIO
              $obj->Insert_Nuevo_Inventario($valor2,$idProducto);
              $respuesta = array(1,$valor2);
            break;
          case 5://ACTUALIZAR INVENTARIO MINIMO
              $obj->Update_Inventario_Minimo($valor2,$idProducto);
              $respuesta = array(1,$valor2);
            break;
        }
        echo json_encode($respuesta);
      break;
    case 3://ESTATUS ALMACEN
        $idProducto = $_POST['idProducto'];

        $min_inventario = $obj->Select_Inventario_Minimo($idProducto);

        $sql_almacen = $obj->Select_Inventario_Inicial($idProducto);
        $row = null; foreach($sql_almacen as $row);
        $fecha_inventario = $row[0];
        $inventario_inicial = $row[1];

        // INVENTARIO EXISTENTE
        $vendidos = $obj->Select_Inventario_Vendido($idProducto,$fecha_inventario);
        $actual = $inventario_inicial - $vendidos;

        // ESTATUS DE INVENTARIO
        $status = ''; $limite_alerta = $min_inventario + 15;
        if ( $min_inventario < $actual ) {
          $status = '<label class="text-primary"><span style="font-size:1.2em;" class="icon-ok-circled-1"></span></label>';
        }
        else if ( $min_inventario >= $actual ) {
          $status = '<label class="text-danger"><span class=" icon-attention-3"></span> PEDIR </label>';
        }

        $respuesta = array($fecha_inventario,$actual,$status);
        echo json_encode($respuesta);
      break;
  }
?>
