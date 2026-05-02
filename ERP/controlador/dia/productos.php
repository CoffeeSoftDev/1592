<?php
session_start();
include_once('../../modelo/SQL_PHP/_Productos.php');
include_once('../../modelo/SQL_PHP/_Utileria.php');
include_once('../../modelo/complementos.php');
$obj      = new Productos;
$util     = new Util;
$function = new Complementos;

$opc  = $_POST['opc'];

switch ($opc) {
  case 0://TABLA PRODUCTOS
      $date = $_POST['date'];
      $cont = $obj->Select_count_productos(array(1));
      echo '
      <div class="form-group col-sm-4 col-sm-offset-4 col-xs-12 text-center respuestas"></div>
      <div class="form-group col-sm-4 col-xs-12 text-right">
        <label><span class="icon-hash"></span>'.$cont.' Productos</label>
      </div>
      <div class="form-group col-sm-12 col-xs-12 table-responsive">
      <table class="table table-bordered table-condensed table-hover">
        <thead>
          <tr>
            <th rowspan="2">#</th>
            <th class="col-sm-2 text-center" rowspan="2"><span class="icon-pencil"></span> PRODUCTO</th>
            <th class="col-sm-1 text-center" rowspan="2"><span class="icon-pencil"></span> PRESENTACIÓN</th>
            <th class="col-sm-1 text-center" rowspan="2"><span class="icon-pencil"></span> PRECIO MENUDEO</th>
            <th class="col-sm-1 text-center" colspan="2"><span class="icon-pencil"></span> MAYOREO</th>
            <th class="col-sm-2 text-center" colspan="6"> INVENTARIO</th>
            <th class="col-sm-2 text-center" rowspan="2"> TOTAL</th>
          </tr>
          <tr>
            <th class="col-sm-1 col-xs-1 text-center"> MIN CANTIDAD</th>
            <th class="col-sm-1 col-xs-1 text-center"> PRECIO</th>
            <th class="col-sm-1 col-xs-1 text-center"> FECHA</th>
            <th class="col-sm-1 col-xs-1 text-center"><span class="icon-pencil"></span> INICIAL</th>
            <th class="col-sm-1 col-xs-1 text-center"><span class="icon-pencil"></span> MÍNIMO</th>
            <th class="col-sm-1 col-xs-1 text-center"> EXISTENTE</th>
            <th class="col-sm-1 col-xs-1 text-center"> ESTATUS</th>
            <th class="col-sm-1 col-xs-1 text-center"> CONTENIDO</th>
          </tr>
        </thead>
        <tbody>';

        $total = 0;
        $sql = $obj->Select_tabla_productos(array(1));
        foreach ($sql as $key => $value) {
          $hash = $key + 1;
          $idProducto = $value[0];
          $name_producto = $value[1];
          $presentacion = $value[2];
          $precio = $value[3];
          $min_inventario = $value[4];
          $min_mayoreo = $value[5];
          $precio_mayoreo = $value[6];

          //INVENTARIO INICIAL
          $sql_almacen = $obj->Select_Inventario_Inicial2($idProducto);
          $row = null; foreach($sql_almacen as $row);
          $fecha_inventario = $row[0];
          $inventario_inicial = $row[1];

          $entrada = $obj->Select_Movimientos_Entrada_Inventario($idProducto);
          $salida  = $obj->Select_Movimientos_Salida_Inventario($idProducto);
          $actual  = $entrada - $salida;
          $costo   = $actual * $precio;
          $total   = $total + $costo;

          $contenido_letter = $util->solo_letras($presentacion);
          $contenido_number = $util->solo_numeros($presentacion);
          $contenido_full = 0;
          if ( $contenido_letter == 'gr' || $contenido_letter == 'GR') {
            $contenido_full = ($contenido_number / 1000) * $actual;

            $contenido_full = $contenido_full.' Kg';
          }
          else {
            $contenido_full = $contenido_number * $actual;

            $contenido_full = $contenido_full.' '.$contenido_letter;
          }

          // ESTATUS DE INVENTARIO
          $status = '';
          if ( $min_inventario < $actual ) {
            $status = '<label class="text-success"><span style="font-size:1.2em;" class="icon-ok-circled-1"></span></label>';
          }
          else if ( $min_inventario >= $actual ) {
            $status = '<label class="text-danger"><span class=" icon-attention-3"></span> PEDIR </label>';
          }

          echo '
          <tr class="producto_canasta">
            <!--HASH-->
            <td style="padding:0;" class="text-center">
              <span class="span-btn text-danger hide"  title="Eliminar" onclick="Delete_producto('.$idProducto.');"><i class="icon-trash-empty"></i></span>
            </td>

            <!--NOMBRE PRODUCTO-->
            <td style="padding:0;" id="Name'.$idProducto.'">
              <label title="clave producto '.$idProducto.'" class="input-label pointer" onclick="Convertir_input(\'Name\','.$idProducto.',\''.$name_producto.'\',1,\''.$presentacion.'\')">'.$name_producto.'</label>
            </td>

            <!--PRESENTACIÓN-->
            <td style="padding:0;" class="text-center" id="Presentacion'.$idProducto.'">
              <label class="input-label pointer" onclick="Convertir_input(\'Presentacion\','.$idProducto.',\''.$presentacion.'\',2);">'.$presentacion.'</label>
            </td>

            <!--PRECIO MENUDEO-->
            <td style="padding:0;" class="text-right bg-info" id="Precio'.$idProducto.'">
              <label class="input-label pointer" onclick="Convertir_input(\'Precio\','.$idProducto.',\''.$precio.'\',3);">$ '.number_format($precio,2,'.',',').'</label>
            </td>

            <!--CANTIDAD MAYOREO-->
            <td style="padding:0;" class="text-center" id="Min_Mayoreo'.$idProducto.'">
              <label class="input-label pointer" onclick="Convertir_input(\'Min_Mayoreo\','.$idProducto.',\''.$min_mayoreo.'\',6);">'.$min_mayoreo.'</label>
            </td>
            <!--PRECIO MAYOREO-->
            <td style="padding:0;" class="text-right bg-info" id="Precio_Mayoreo'.$idProducto.'">
              <label class="input-label pointer" onclick="Convertir_input(\'Precio_Mayoreo\','.$idProducto.',\''.$precio_mayoreo.'\',7);">$ '.number_format($precio_mayoreo,2,'.',',').'</label>
            </td>

            <!--FECHA INVENTARIO-->
            <td style="padding:0;" class="text-center" id="Fecha'.$idProducto.'">
              <label class="input-label">'.$fecha_inventario.'<label>
              </td>

              <!--INVENTARIO INICIAL-->
              <td style="padding:0;" class="text-center" id="Inventario_Inicial'.$idProducto.'">
                <label class="input-label pointer" onclick="Convertir_input(\'Inventario_Inicial\','.$idProducto.',\''.$inventario_inicial.'\',4);">'.$inventario_inicial.'</label>
              </td>

              <!--INVENTARIO MINIMO-->
              <td style="padding:0;" class="text-center" id="Inventario_Minimo'.$idProducto.'">
                <label class="input-label pointer" onclick="Convertir_input(\'Inventario_Minimo\','.$idProducto.',\''.$min_inventario.'\',5);">'.$min_inventario.'</label>
              </td>

              <!--INVENTARIO ACTUAL-->
              <td style="padding:0;" class="text-center" id="Inventario_Actual'.$idProducto.'">
                <label class="input-label">'.$actual.'</label>
              </td>

              <!--INVENTARIO ESTATUS-->
              <td style="padding:0;" class="text-center" id="Inventario_Status'.$idProducto.'">'.$status.'</td>

              <!--CONTENIDO-->
              <td style="padding:0;" class="text-center" id="Contenido'.$idProducto.'">
                <label class="input-label">'.$contenido_full.'</label>
              </td>

              <!--COSTO-->
              <td style="padding:0;" class="text-right" id="Costo'.$idProducto.'"> $ '.number_format($costo,2,'.',',').'</td>
            </tr>
            ';
          }

          echo
          '</tbody>
          <tfoot>
            <tr>
              <th class="text-right" colspan="12"> GRAN TOTAL</th>
              <th class="text-right">$ '.number_format($total,2,'.',',').'</th>
            </tr>
          </tfoot>
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
      $mayoreo        = $_POST['mayoreo'];
      $cant_mayoreo   = $_POST['cant_mayoreo'];
      $tipo           = $_POST['tipo'];

      $idProducto = $obj->Select_idProducto($producto,$presentacion);
      if ( $idProducto == 0) {
        $array = array($date,$producto,$presentacion,$min_inventario,$precio,$mayoreo,$cant_mayoreo,$tipo);
        $obj->Insert_Productos($array);
      }

      echo $idProducto;
    break;
  case 2://ACTUALIZAR DATOS DE PRODUCTOS
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
        case 6://ACTUALIZAR MINIMO MAYOREO
            $obj->Update_Mayoreo_Minimo($valor2,$idProducto);
            $respuesta = array(1,$valor2);
          break;
        case 7://ACTUALIZAR PRECIO MAYOREO
            $obj->Update_Precio_Mayoreo($valor2,$idProducto);
            $respuesta = array($valor2,number_format($valor2,2,'.',','));
          break;
        case 8://CANTIDAD DE CANASTA
            $idCanasta = $idProducto;
            $Cantidad = $valor2;
            $obj->Insert_Almacen_Canasta($Cantidad,$idCanasta);
            $respuesta = array(1,$Cantidad);
          break;
      }
      echo json_encode($respuesta);
    break;
  case 3://ESTATUS ALMACEN
      $idProducto = $_POST['idProducto'];

      $sql_data = $obj->Select_data_productos($idProducto);
      foreach($sql_data as $row);
      $presentacion = $row[1];

      $min_inventario = $obj->Select_Inventario_Minimo($idProducto);

      $sql_almacen = $obj->Select_Inventario_Inicial($idProducto);
      $row = null; foreach($sql_almacen as $row);
      $fecha_inventario = $row[0];
      $inventario_inicial = $row[1];

      // INVENTARIO EXISTENTE
      $entrada = $obj->Select_Movimientos_Entrada_Inventario($idProducto);
      $salida  = $obj->Select_Movimientos_Salida_Inventario($idProducto);
      $actual  = $entrada - $salida;

      // ESTATUS DE INVENTARIO
      $status = ''; $limite_alerta = $min_inventario + 15;
      if ( $min_inventario < $actual ) {
        $status = '<label class="text-success"><span style="font-size:1.2em;" class="icon-ok-circled-1"></span></label>';
      }
      else if ( $min_inventario >= $actual ) {
        $status = '<label class="text-danger"><span class=" icon-attention-3"></span> PEDIR </label>';
      }

      $contenido_letter = $util->solo_letras($presentacion);
      $contenido_number = $util->solo_numeros($presentacion);
      $contenido_full = 0;
      if ( $contenido_letter == 'gr' || $contenido_letter == 'GR') {
        $contenido_full = ($contenido_number / 1000) * $actual;
        $contenido_full = $contenido_full.' Kg';
      }
      else {
        $contenido_full = $contenido_number * $actual;
        $contenido_full = $contenido_full.' '.$contenido_letter;
      }

      $respuesta = array($fecha_inventario,$actual,$status,$contenido_full);
      echo json_encode($respuesta);
    break;
  case 4://TABLA PRODUCTO CANASTAS
    $idTipo = $_POST['idTipo'];
    $cont   = $obj->Select_count_productos(array($idTipo));
    echo '
    <style>
      .producto_canasta:hover {

      }

      .lista_pc {
        background:#E9E6D3;
        /* color:#000;*/
      }

      .lista_pc:hover {
        background-color:rgb(50, 50, 49,.5);
        /* color:#fff; */
      }
    </style>

    <div class="form-group col-sm-12 col-xs-12 text-right">
      <label class="icon-hash"> '.$cont.' Productos</label>
    </div>
    <div class="form-group col-sm-12 col-xs-12">
      <table class="table table-bordered table-responsive table-condensed">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="col-sm-7 col-xs-7 text-center">PRODUCTO</th>
            <th class="col-sm-3 col-xs-3 text-center">PRESENTACIÓN</th>
            <th class="col-sm-2 col-xs-2 text-center">PRECIO MENUDEO</th>
            <th class="col-sm-3 col-xs-3 text-center">PRECIO MAYOREO</th>
          </tr>
        </thead>
        <tbody>';
          $sql = $obj->Select_tabla_productos(array($idTipo));
          foreach ($sql as $key => $value) {
            $key            = $key + 1;
            $idProducto     = $value[0];
            $name_producto  = $value[1];
            $presentacion   = $value[2];
            $precio         = $value[3];
            $min_inventario = $value[4];
            $precio_mayoreo = $value[6];
            echo '
            <tr class="producto_canasta linea'.$idProducto.' pointer" onClick="nuevo_producto_canasta('.$idProducto.');">
              <td class="text-center">'.$key.'</td>
              <td >'.$name_producto.'</td>
              <td class="text-center">'.$presentacion.'</td>
              <td class="text-right">$ '.number_format($precio,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($precio_mayoreo,2,'.',',').'</td>
            </tr>';
          }
          echo
          '</tbody>
        </table>
      </div>
      ';
    break;
  case 5://TABLA CANASTAS
    $canasta = $_POST['canasta'];
    $count = $obj->Select_Count();
    echo '
    <div class="form-group col-sm-12 col-xs-12 text-right">
      <label class="icon-hash"> '.$count.' Canastas</label>
    </div>
    <div class="form-group col-sm-12 col-xs-12">
      <table class="table table-bordered table-responsive table-condensed">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="col-sm-6 col-xs-8 text-center">CANASTAS</th>
            <th class="col-sm-2 col-xs-2 text-center">MENUDEO</th>
            <th class="col-sm-2 col-xs-2 text-center">CANT MAYOREO</th>
            <th class="col-sm-2 col-xs-2 text-center">MAYOREO</th>
            <th class="col-sm-2 col-xs-2 text-center">CANTIDAD</th>
          </tr>
        </thead>
        <tbody class="canastas_tbody">';
          $sql = $obj->Select_Tb_Canasta();
          foreach ($sql as $key => $row) {
            $key = $key + 1;
            $idCanasta        = $row[0];
            $Canasta          = $row[1];

            $cantidad         = 0;
            $productos        = $obj->Select_count_productos_canastas($idCanasta);
            $menudeo          = $obj->Select_cantidad_productos_canastas($idCanasta);
            $mayoreo          = $obj->Select_cantidad_productos_canastas_mayoreo($idCanasta);
            if ( $menudeo == 0 ) { $menudeo = '-'; } else { $menudeo = '$ '.number_format($menudeo,2,'.',','); }
            if ( $mayoreo == 0 ) { $mayoreo = '-'; } else { $mayoreo = '$ '.number_format($mayoreo,2,'.',','); }

            $entrada    = $obj->Select_Canasta_Entrada_Inventario($idCanasta);
            $salida     = $obj->Select_Canasta_Salida_Inventario($idCanasta);
            $actual = $entrada - $salida;

            echo
            '<tr class="producto_canasta pointer">
              <td class="text-center">
                <span class="span-btn text-danger" title="Eliminar" onclick="Delete_Canasta('.$idCanasta.');"><i class="icon-trash-empty"></i></span>
              </td>
              <th onClick="Canasta('.$idCanasta.');">'.$Canasta.'</th>
              <th class="text-right" >'.$menudeo.'</th>
              <th class="text-right" >'.$menudeo.'</th>
              <th class="text-center" >'.$mayoreo.'</th>
              <th class="text-center" id="Cant_Canasta'.$idCanasta.'">
                <label class=" pointer" onclick="Convertir_input(\'Cant_Canasta\','.$idCanasta.',\''.$cantidad.'\',8,\'\')">'.$actual.'</label>
              </th>
            </tr>';

            $sql2 = $obj->Select_Producto_canasta($idCanasta);
            foreach ($sql2 as $key => $value) {
              $idProductoCanasta = $value[0];
              $idProducto        = $value[1];
              $Producto          = $value[2];
              $Presentacion      = $value[3];
              $Precio            = $value[4];
              $Precio_Mayoreo    = $value[5];

              $icon = 'icon-right-dir';
              $onclick = '';
              $hide = 'hide';
              if ( $canasta != '' ) {
                $icon = 'text-danger icon-cancel-circled-1';
                $onclick = 'onClick="delete_producto_canasta('.$idProductoCanasta.','.$idProducto.');"';
              }

              if ( $canasta == $row[1]) {
                $hide = '';
              }

              $cantidad = $obj->Select_Cantidad_Producto_Canasta($idCanasta,$idProducto);
              echo
              '<tr '.$onclick.' class="pointer lista_pc lista_productos_canasta'.$idCanasta.' '.$hide.'">
                <td class="text-center"><span class="'.$icon.'"></span></td>
                <td colspan="2">'.$Producto.'</td>
                <td class="text-center">'.$Presentacion.'</td>
                <td class="text-right">$ '.number_format($Precio,2,'.',',').'</td>
                <td class="text-center">'.$cantidad.'</td>
              </tr>';
            }
          }
          echo
          '</tbody>
        </table>
      </div>
      ';
    break;
  case 6://INSERTAR CANASTA
      $canasta = $_POST['canasta'];
      $idCanasta = $obj->Select_idCanasta($canasta);
      if ( $idCanasta == 0 ) {
        $obj->Insert_Canasta($canasta);
      }
      $idCanasta = $obj->Select_idCanasta($canasta);
      echo $idCanasta;
    break;
  case 7://ULTIMA CANASTA
      $sql = $obj->Select_Ultima_Canasta();
      $row = null; foreach($sql as $row);
      $idCanasta = $row[0];
      $canasta = $row[1];
      $respuesta = array($idCanasta,$canasta);
      echo json_encode($respuesta);
    break;
  case 8://INSERTAR PRODUCTOS
      $canasta = $_POST['canasta'];
      $idCanasta = $_POST['idCanasta'];
      $idProducto = $_POST['idProducto'];

      $obj->Insert_Producto_Canasta($idProducto,$idCanasta);

      $costo = $obj->Select_cantidad_productos_canastas($idCanasta);
      $obj->Update_Precio_Canasta($costo,$idCanasta);
    break;
  case 9://ELIMINAR PRODUCTOS CANASTA
      $idProductoCanasta = $_POST['canasta'];
      $obj->Delete_Producto_canasta($idProductoCanasta);
    break;
  case 10://ELIMINAR PRODUCTO
      $obj->Delete_Producto($_POST['idProducto']);
    break;
  case 11://ELIMINAR CANASTA
      $obj->Delete_Canasta($_POST['idCanasta']);
    break;
  case 12:// agregar Producto
      $fecha    = $function  -> fecha_servidor();
      $Producto = $_POST['data0'];
      $Costo    = $_POST['data1'];

      $array    = array($fecha,$Producto,$Costo);
      $obj ->Insert_Productos_Insumos($array);
    break;
  case 13://terminar canasta
      $idCanasta = $_POST['idCanasta'];
      $obj->Update_stado_canasta($idCanasta);
    break;
}
?>