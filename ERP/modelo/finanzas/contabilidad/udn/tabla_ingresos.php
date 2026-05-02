<?php
  include_once("../../../SQL_PHP/_CRUD.php");
  include_once("../../../SQL_PHP/_Finanzas.php");
  include_once("../../../SQL_PHP/_Utileria.php");

  //Se declaran los utiletos para utilizar las funciones segun los archivos externos
  $crud = new CRUD;
  $util = new Util;
  $fin = new Finanzas;

  $date1 = $_POST['date1'];
  $date2 = $_POST['date2'];
  $idE = $_POST['udn'];

  $array = array();
  if($date1 == $date2){
    $array[0] = $idE;  $array[1] = $date1;
    $query1 = "SELECT Fecha_Folio FROM folio WHERE id_UDN = ? AND Fecha_Folio = ?  ORDER BY Fecha_Folio ASC";
  }
  else{
    $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
    $query1 = "SELECT Fecha_Folio FROM folio WHERE id_UDN = ? AND Fecha_Folio BETWEEN ? AND ? ORDER BY Fecha_Folio ASC";
  }
  $sql1 = $crud->_Select($query1,$array,"5");
  $fechas = array();
  foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
  $Count_Fechas = count($fechas);

  $sql2 = $fin->Select_Ingresos($idE);
  $ingresos = array(); $id_Ing = array(); $idE_Ing = array();
  foreach ($sql2 as $key => $data) { $id_Ing[$key] = $data[0]; $ingresos[$key] = $data[1]; $idE_Ing[$key] = $data[2]; }
  $Cont_Ing = count($ingresos);



  $sql3 = $fin->Select_Descuentos($idE);
  $descuentos = array(); $id_Desc = array(); $idE_Desc = array();
  foreach ($sql3 as $key => $data) { $id_Desc[$key] = $data[0]; $descuentos[$key] = $data[1]; $idE_Desc[$key] = $data[2];}
  $Cont_Desc = count($descuentos);

  $sql4 = $fin->Select_Impuestos($idE);
  $impuestos = array(); $id_Imp = array(); $idE_Imp = array();
  foreach ($sql4 as $key => $data) { $id_Imp[$key] = $data[0]; $impuestos[$key] = $data[1]; $idE_Imp[$key] = $data[2]; }
  $Cont_Imp = count($impuestos);

  $sql5 = $fin->Select_Propina();
  $propina = array(); $id_Prop = array();
  foreach ($sql5 as $key => $data) { $id_Prop[$key] = $data[0]; $propina[$key] = $data[1]; }
  $Cont_Prop = count($propina);

  $sql6 = $fin->Select_Data_Moneda();
  $moneda = array(); $id_Mon = array();
  foreach ($sql6 as $key => $data) { $id_Mon[$key] = $data[0]; $moneda[$key] = $data[1]; }
  $Cont_Mon = count($moneda);

  $sql7 = $fin->Select_Bancos($idE);
  $banco = array(); $id_Ban = array(); $idE_Ban = array();
  foreach ($sql7 as $key => $data) { $id_Ban[$key] = $data[0]; $banco[$key] = $data[1]; $idE_Ban[$key] = $data[2]; }
  $Cont_Ban = count($banco);

  $Subtotal_SJ = array(); $Subtotal = array(); $Total_Impuestos = array(); $Total_Impuestos_SJ = array(); $Total_Venta = array(); $Total_Venta_SJ = array();
  $Suma_Subtotal = 0; $Suma_Subtotal_SJ = 0; $Suma_Impuestos = 0; $Suma_Impuestos_SJ = 0; $Suma_Descuentos = 0; $Suma_Descuentos_SJ = 0; $Suma_Total = 0; $Suma_Total_SJ = 0;

  $Moneda_Caja = array(); $Moneda_Caja_SJ = array(); $Pago_Credito = array(); $Pago_Credito_SJ = array(); $Deuda_Credito = array(); $Deuda_Credito_SJ = array();
  $Caja_Total = array(); $Caja_Deuda = array(); $Caja_Pago = array();
  $Caja_Total_SJ = array(); $Caja_Deuda_SJ = array(); $Caja_Pago_SJ = array();
  $Suma_Caja_Bancos = 0; $Suma_Caja_Total = 0; $Suma_Caja_Diferencia = 0; $Suma_Caja_Pago = 0; $Suma_Caja_Deuda = 0; $Suma_Moneda = 0; $Suma_Caja_Propina = 0;

?>

<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="scrolling outer">
      <div class="inner">
        <table class="table table-condensed table-bordered">
          <thead>
            <?php if($idE != 6){ ?>
              <tr style="background:#A9D08E;" class="text-center">
                <th style="background:#A9D08E;" class="text-center">INGRESO</th>
                <td id="th" style="background:#A9D08E; text-align:center;">TOTAL</td>
                <?php for ($i=0; $i < $Count_Fechas; $i++) { ?>
                  <td id="td" style="background:#A9D08E; text-align:center;"><strong><?php echo $fechas[$i]; ?></strong></td>
                <?php } ?>
              </tr>
            <?php } else { ?>
              <tr style="background:#F8CBAD;" class="text-center">
                <th style="background:#F8CBAD;" class="text-center"> </th>
                <td id="th" style="background:#F8CBAD; text-align:center;"> </td>
                <?php for ($i=0; $i < $Count_Fechas; $i++) { ?>
                  <td id="td" style="background:#F8CBAD; text-align:center;"><strong>FOGAZA</strong></td>
                  <td id="td" style="background:#F8CBAD; text-align:center;"><strong>SAN JUAN</strong></td>
                <?php } ?>
              </tr>
            <?php } ?>
          </thead>
          <tbody>
            <?php if($idE == 6 ){ ?>
              <tr style="background:#A9D08E;" class="text-center">
                <th style="background:#A9D08E;" class="text-center">INGRESO</th>
                <td id="th" style="background:#A9D08E; text-align:center;">TOTAL</td>
                <?php for ($i=0; $i < $Count_Fechas; $i++) { ?>
                  <td id="td" style="background:#A9D08E; text-align:center;"><strong><?php echo $fechas[$i]; ?></strong></td>
                  <td id="td" style="background:#A9D08E; text-align:center;"><strong><?php echo $fechas[$i]; ?></strong></td>
                <?php } ?>
              </tr>
            <?php } ?>
            <!-- TOTAL DE VENTA SIN IMPUESTOS -->
            <?php
            $dato = 0;
              if($idE != 6){
                $Suma_Subtotal = $fin->Select_Suma_Venta_Sin_Impuestos($idE,$date1,$date2);
                if($Suma_Subtotal == 0){ $dato = '-'; } else { $dato = '$'.number_format($Suma_Subtotal,2,'.', ', '); }
              }
              else{
                $dato1 = $fin->Select_Suma_Venta_Sin_Impuestos($idE,$date1,$date2);
                $dato2 = $fin->Select_Suma_Venta_Sin_Impuestos(9,$date1,$date2);
                $Suma_Subtotal = $dato1 + $dato2;
                if($Suma_Subtotal == 0){ $dato = '-'; } else { $dato = '$'.number_format($Suma_Subtotal,2,'.', ', '); }
              }
             ?>
            <tr>
              <th><strong>VENTAS SIN IMPUESTOS</strong></th>
              <td id="th"><?php echo $dato; ?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) {
                $array = array($idE,$fechas[$j]);
                $Subtotal[$j] = $fin->Select_Venta_Sin_Impuestos($array);
                if($Subtotal[$j] == 0){ $dato = '-'; } else { $dato = '$'.number_format($Subtotal[$j],2,'.', ', '); }

                $array = array(9,$fechas[$j]);
                $Subtotal_SJ[$j] = $fin->Select_Venta_Sin_Impuestos($array);
                if($Subtotal_SJ[$j] == 0){ $dato2 = '-'; } else { $dato2 = '$'.number_format($Subtotal_SJ[$j],2,'.', ', '); }
              ?>
              <td id="td"><?php echo $dato; ?></td>
              <?php if($idE == 6 ) { ?>
                <td id="td"><?php echo $dato2; ?></td>
              <?php } } ?>
            </tr>

            <!-- ingresos -->
            <?php for ($i=0; $i < $Cont_Ing; $i++) {
              $dato = 0;
              if($idE == 6){
                $dato1 = $fin->Select_Suma_Ingresos_SJ($idE_Ing[$i],9,$date1,$date2);
                $dato2 = $fin->Select_Suma_Ingresos($id_Ing[$i],$date1,$date2);
                $dato = $dato1 + $dato2;
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }
              }
              else{
                $dato = $fin->Select_Suma_Ingresos($id_Ing[$i],$date1,$date2);
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }
              }
            ?>
              <tr>
                <th style="font-weight:normal;"><?php  echo $ingresos[$i]; ?></th>
                <td id="th"><?php echo $dato; ?></td>
                <?php for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($id_Ing[$i],$fechas[$j]);
                  $dato = $fin->Select_dia_Ingresos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', ');}

                  if($idE == 6){
                    $dato2 = $fin->Select_dia_Ingresos_SJ($idE_Ing[$i],9,$fechas[$j]);
                    if($dato2 == 0){ $dato2 = '-'; } else { $dato2 = '$ '.number_format($dato2,2,'.', ', ');}
                  }
                ?>
                <td id="td"><?php echo $dato; ?></td>
                <?php if($idE == 6){ ?>
                <td id="td"><?php echo $dato2; ?></td>
                <?php } } ?>
              </tr>
            <?php } ?>

            <tr>
              <th style="background:#ECF0F1; color:#ECF0F1;">-</th>
              <td id="th" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
              <td id="td" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php if($idE == 6){ ?>
                <td id="td" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php } } ?>
            </tr>

            <!-- descuentos -->
            <?php for ($i=0; $i < $Cont_Desc; $i++) {
              $dato = 0;
              if($idE != 6){
                $dato = $fin->Select_Suma_Descuentos($id_Desc[$i],$date1,$date2);
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }
              }
              else{
                $dato1 = $fin->Select_Suma_Descuentos($id_Desc[$i],$date1,$date2);
                $dato2 = $fin->Select_Suma_Descuentos_SJ($idE_Desc[$i],9,$date1,$date2);
                $dato = $dato1 + $dato2;
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }
              }
              ?>
              <tr>
                <th style="font-weight:normal;"><?php  echo $descuentos[$i]; ?></th>
                <td id="th"><?php echo $dato; ?></td>
                <?php for ($j=0; $j < $Count_Fechas; $j++) {

                  $array = array($id_Desc[$i],$fechas[$j]);
                  $dato = $fin->Select_dia_Descuentos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }

                  if($idE == 6){
                    $dato2 = $fin->Select_dia_Descuentos_SJ($idE_Desc[$i],9,$fechas[$j]);
                    if($dato2 == 0){ $dato2 = '-'; } else { $dato2 = '$ '.number_format($dato2,2,'.', ', '); }
                  }
                ?>
                <td id="td"><?php echo $dato; ?></td>
                <?php if($idE == 6) {?>
                  <td id="td"><?php echo $dato2; ?></td>
                <?php } } ?>
              </tr>
            <?php } ?>

            <tr>
              <th style="background:#ECF0F1; color:#ECF0F1;">-</th>
              <td id="th" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
              <td id="td" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php if($idE == 6){ ?>
              <td id="td" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php } } ?>
            </tr>

            <!-- impuestos -->
            <?php for ($i=0; $i < $Cont_Imp; $i++) {
              if($idE != 6){
                $dato = $fin->Select_Suma_Impuestos($id_Imp[$i],$date1,$date2);
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }
              }
              else{
                $dato1 = $fin->Select_Suma_Impuestos($id_Imp[$i],$date1,$date2);
                $dato2 = $fin->Select_Suma_Impuestos_SJ($idE_Imp[$i],9,$date1,$date2);
                $dato = $dato1 + $dato2;
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }
              }
              ?>
              <tr>
                <th style="font-weight:normal;"><?php  echo $impuestos[$i]; ?></th>
                <td id="th"><?php echo $dato;?></td>
                <?php for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($id_Imp[$i],$fechas[$j]);
                  $dato = $fin->Select_Dia_Impuestos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }

                  if($idE == 6){
                    $dato2 = $fin->Select_Dia_Impuestos_SJ($idE_Imp[$i],9,$fechas[$j]);
                    if($dato2 == 0){ $dato2 = '-'; } else { $dato2 = '$ '.number_format($dato2,2,'.', ', '); }
                  }
                ?>
                <td id="td"><?php echo $dato;?></td>
                <?php if($idE == 6){ ?>
                  <td id="td"><?php echo $dato2;?></td>
                <?php } } ?>
              </tr>
            <?php } ?>

            <tr>
              <th style="background:#ECF0F1; color:#ECF0F1;">-</th>
              <td id="th" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
              <td id="td" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php if($idE == 6){ ?>
                <td id="td" style="background:#ECF0F1; color:#ECF0F1;">-</td>
              <?php } } ?>
            </tr>

            <!-- Subtotal-->
            <tr>
              <th><strong>SUBTOTAL</strong></th>
              <td id="th"><?php echo '$ '.number_format($Suma_Subtotal,2,'.', ', ');?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
              <td id="td"><?php echo '$ '. number_format($Subtotal[$j],2,'.', ', ');?></td>
              <?php if($idE == 6){ ?>
                <td id="td"><?php echo '$ '. number_format($Subtotal_SJ[$j],2,'.', ', ');?></td>
              <?php } } ?>
            </tr>
            <!-- Impuestos -->
            <?php
              $dato = 0;
              if($idE == 6){
                $dato1 = $fin->Select_Suma_Total_Impuestos($idE,$date1,$date2);
                $dato2 = $fin->Select_Suma_Total_Impuestos(9,$date1,$date2);
                $Suma_Impuestos = $dato1 + $dato2;
                if($Suma_Impuestos == 0){ $dato = '-'; } else { $dato = '$ '.number_format($Suma_Impuestos,2,'.', ', '); }
              }
              else{
                $Suma_Impuestos = $fin->Select_Suma_Total_Impuestos($idE,$date1,$date2);
                if($Suma_Impuestos == 0){ $dato = '-'; } else { $dato = '$ '.number_format($Suma_Impuestos,2,'.', ', '); }
              }
             ?>
            <tr>
              <th><strong>IMPUESTOS</strong></th>
              <td id="th"><?php echo $dato; ?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) {
                $array = array($idE,$fechas[$j]);
                $Total_Impuestos[$j] = $fin->Select_Total_Impuestos($array);
                if($Total_Impuestos[$j] == 0){ $dato = '-'; } else { $dato = '$ '.number_format($Total_Impuestos[$j],2,'.',', '); }

                if($idE == 6){
                  $array = array(9,$fechas[$j]);
                  $Total_Impuestos_SJ[$j] = $fin->Select_Total_Impuestos($array);
                  if($Total_Impuestos_SJ[$j] == 0){ $dato2 = '-'; } else { $dato2 = '$ '.number_format($Total_Impuestos_SJ[$j],2,'.',', '); }
                }
              ?>
              <td id="td"><?php echo $dato;?></td>
              <?php if($idE == 6){ ?>
                <td id="td"><?php echo $dato2;?></td>
              <?php } } ?>
            </tr>
            <!-- Total de Venta-->
            <?php
              $SUMA = 0;
              if($idE == 6){
                $dato1 = $fin->Select_Suma_Total_Descuentos($idE,$date1,$date2);
                $dato2 = $fin->Select_Suma_Total_Descuentos(9,$date1,$date2);
                $Suma_Descuentos = $dato1 + $dato2;

                $Suma_Total = $Suma_Subtotal + $Suma_Impuestos - $Suma_Descuentos;

              }
              else{
                $Suma_Descuentos = $fin->Select_Suma_Total_Descuentos($idE,$date1,$date2);
                $Suma_Total = $Suma_Subtotal + $Suma_Impuestos - $Suma_Descuentos;
              }
             ?>
            <tr>
              <th><strong>TOTAL</strong></th>
              <td id="th"><?php echo '$ '.number_format($Suma_Total,2,'.', ', ');?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) {
                $array = array($idE,$fechas[$j]);
                $Desc_now = $fin->Select_Total_Dia_Descuentos($array);
                $Total_Venta[$j] =  $Subtotal[$j] + $Total_Impuestos[$j] - $Desc_now;

                if($idE == 6){
                  $array = array(9,$fechas[$j]);
                  $Desc_now_sj = $fin->Select_Total_Dia_Descuentos($array);
                  $Total_Venta_SJ[$j] =  $Subtotal_SJ[$j] + $Total_Impuestos_SJ[$j] - $Desc_now_sj;
                }

              ?>
              <td id="td"><?php echo '$ '.number_format($Total_Venta[$j], 2, '.', ', ');?></td>
              <?php if($idE == 6){ ?>
                <td id="td"><?php echo '$ '.number_format($Total_Venta_SJ[$j], 2, '.', ', ');?></td>
              <?php } } ?>
            </tr>

            <!-- CAJA -->
            <tr>
              <th style="background:#B4C6E7;" class="text-center"><strong>CAJA</strong></th>
              <td id="th" style="background:#B4C6E7; color:#B4C6E7;">0</td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
              <td id="td" style="background:#B4C6E7; color:#B4C6E7;">0</td>
              <?php if($idE == 6){?>
                <td id="td" style="background:#B4C6E7; color:#B4C6E7;">0</td>
              <?php } } ?>
            </tr>

            <!-- PROPINA -->
            <?php
              if($idE != 6 ){
                $Suma_Caja_Propina = $fin->Select_Sum_Total_Propina_Efectivo($idE,$date1,$date2);
                $Suma_Caja_Efectivo = $fin->Select_Dif_Total_Propina_Efectivo($idE,$date1,$date2);
              }
              else{
                $sin_propina1 = $fin->Select_Sum_Total_Propina_Efectivo($idE,$date1,$date2);
                $con_propina1 = $fin->Select_Dif_Total_Propina_Efectivo($idE,$date1,$date2);

                $sin_propina2 = $fin->Select_Sum_Total_Propina_Efectivo(9,$date1,$date2);
                $con_propina2 = $fin->Select_Dif_Total_Propina_Efectivo(9,$date1,$date2);

                $Suma_Caja_Propina = $sin_propina1 + $sin_propina2;
                $Suma_Caja_Efectivo = $con_propina1 + $con_propina2;
              }

              for ($i=0; $i < $Cont_Prop; $i++) {
                $dato = 0;
                if($idE != 6){
                  $dato = $fin->Select_Efectivo_Propina($id_Prop[$i],$idE,$date1,$date2);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                }
                else {
                  $dato1 = $fin->Select_Efectivo_Propina($id_Prop[$i],$idE,$date1,$date2);
                  $dato2 = $fin->Select_Efectivo_Propina($id_Prop[$i],9,$date1,$date2);
                  $dato = $dato1 + $dato2;
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                }
              ?>
              <tr>
                <th style="font-weight:normal;"><?php  echo $propina[$i]; ?></th>
                <td id="th"><?php echo $dato;?></td>
                <?php for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($id_Prop[$i],$idE,$fechas[$j]);
                  $dato = $fin->Select_Suma_Total_Efectivo_Propina($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }

                  if($idE == 6){
                    $array = array($id_Prop[$i],9,$fechas[$j]);
                    $dato2 = $fin->Select_Suma_Total_Efectivo_Propina($array);
                    if($dato2 == 0){ $dato2 = '-'; } else { $dato2 = '$ '.number_format($dato2,2,'.',', '); }
                  }
                ?>
                <td id="td"><?php echo $dato;?></td>
                <?php if($idE == 6){ ?>
                  <td id="td"><?php echo $dato2;?></td>
                <?php } } ?>
              </tr>
            <?php } ?>
            <!-- MONEDA EXTRANJERA -->
            <?php for ($i=0; $i < $Cont_Mon; $i++) {
              if($idE != 6){
                $dato_valor = $fin->Select_Suma_Total_Moneda_Extranjera_Valor($id_Mon[$i],$idE,$date1,$date2);
                $Suma_Moneda = $Suma_Moneda + $dato_valor;
                if($dato_valor == 0){ $dato_valor = '-'; } else { $dato_valor = '$ '.number_format($dato_valor,2,'.',', '); }
              }
              else{
                $dato1 = $fin->Select_Suma_Total_Moneda_Extranjera_Valor($id_Mon[$i],$idE,$date1,$date2);
                $dato2 = $fin->Select_Suma_Total_Moneda_Extranjera_Valor($id_Mon[$i],9,$date1,$date2);
                $dato_valor = $dato1 + $dato2;
                $Suma_Moneda = $Suma_Moneda + $dato_valor;

                if($dato_valor == 0){ $dato_valor = '-'; } else { $dato_valor = '$ '.number_format($dato_valor,2,'.',', '); }

              }
              // $dato = $fin->Select_Suma_Total_Moneda_Extranjera($id_Mon[$i],$idE,$date1,$date2);
              // if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
              ?>
              <tr>
                <th style="font-weight:normal;"><?php  echo $moneda[$i]; ?></th>
                <td id="th"><?php echo $dato_valor;?></td>
                <?php for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($idE,$fechas[$j]);
                  $Moneda_Caja[$j] = $fin->Select_Dia_Total_Moneda_Extranjera_Valor($array);

                  $array = array($id_Mon[$i],$idE,$fechas[$j]);
                  $dato = $fin->Select_Dia_Total_Moneda_Extranjera($array);
                  if($dato == 0 ){$dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }

                  if($idE == 6){
                    $array = array(9,$fechas[$j]);
                    $Moneda_Caja_SJ[$j] = $fin->Select_Dia_Total_Moneda_Extranjera_Valor($array);

                    $array = array($id_Mon[$i],9,$fechas[$j]);
                    $dato2 = $fin->Select_Dia_Total_Moneda_Extranjera($array);
                    if($dato2 == 0 ){$dato2 = '-'; } else { $dato2 = '$ '.number_format($dato2,2,'.',', '); }
                  }
                ?>
                <td id="td"><?php echo $dato;?></td>
                <?php if($idE == 6){ ?>
                  <td id="td"><?php echo $dato2;?></td>
                <?php } } ?>
              </tr>
            <?php } ?>
            <!-- BANCOS -->
            <?php for ($i=0; $i < $Cont_Ban; $i++) {
              $Suma_Caja_Pago = $fin->Select_Suma_Caja_Pago($idE,$date1,$date2);
              $Suma_Caja_Deuda = $fin->Select_Suma_Caja_Deuda($idE,$date1,$date2);

              if($idE != 6){
                $dato = $fin->Select_Caja_Bancos($id_Ban[$i],$date1,$date2);

                $Suma_Caja_Bancos = $Suma_Caja_Bancos + $dato;
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
              }
              else{
                $dato1 = $fin->Select_Caja_Bancos($id_Ban[$i],$date1,$date2);
                $dato2 = $fin->Select_Caja_Bancos_SJ($idE_Ban[$i],9,$date1,$date2);
                $dato = $dato1 + $dato2;
                $Suma_Caja_Bancos = $Suma_Caja_Bancos + $dato;
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }

              }
            ?>
              <tr>
                <th style="font-weight:normal;"><?php  echo $banco[$i]; ?></th>
                <td id="th"><?php echo $dato;?></td>
                <?php for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($idE,$fechas[$j]);
                  $dato = $fin->Select_Dia_Pagos_Clientes($array);
                  $Caja_Pago[$j] = $dato;
                  if($dato == 0){ $Pago_Credito[$j] = '-'; } else { $Pago_Credito[$j] = '$ '.number_format($dato,2,'.', ', '); }

                  $dato = $fin->Select_Dia_Deudas_Clientes($array);
                  $Caja_Deuda[$j] = $dato;
                  if($dato == 0){ $Deuda_Credito[$j] = '-'; } else { $Deuda_Credito[$j] = '$ '.number_format($dato,2,'.', ', '); }

                  $array = array($id_Ban[$i],$fechas[$j]); $row = null;
                  $dato = $fin->Select_Dia_Bancos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.', ', '); }

                  if($idE == 6){
                    $dato2 = $fin->Select_Dia_Bancos_SJ($idE_Ban[$i],9,$fechas[$j]);
                    if($dato2 == 0){ $dato2 = '-'; } else { $dato2 = '$ '.number_format($dato2,2,'.', ', '); }
                  }
                ?>
                <td id="td"><?php echo $dato;?></td>
                <?php if($idE == 6){ ?>
                  <td id="td"><?php echo $dato2;?></td>
                <?php } } ?>
              </tr>
            <?php } ?>


            <!-- SUBTOTAL -->
            <?php
                $Suma_Caja_SubTotal = $Suma_Caja_Efectivo + $Suma_Caja_Bancos + $Suma_Moneda;
                if($Suma_Caja_SubTotal == 0){ $dato = '-'; } else { $dato = '$ '.number_format($Suma_Caja_SubTotal,2,'.',', '); }
            ?>
            <tr>
              <th>SUBTOTAL (Con Propina)</th>
              <td id="th"><?php echo $dato; ?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) {

                $efectivo  = $fin->Select_Dia_Propina(2,$idE,$fechas[$j]);

                $array = array($idE,$fechas[$j]);
                $bancos = $fin->Select_Suma_Dia_Bancos($array);

                $Caja_Total[$j] = $efectivo + $bancos + $Moneda_Caja[$j];

                if($idE == 6){
                  $efectivo_sj  = $fin->Select_Dia_Propina(2,9,$fechas[$j]);

                  $array = array(9,$fechas[$j]);
                  $bancos_sj = $fin->Select_Suma_Dia_Bancos($array);

                  $Caja_Total_SJ[$j] = $efectivo_sj + $bancos_sj + $Moneda_Caja_SJ[$j];

                }

              ?>
              <td id="td"><?php echo '$ '.number_format($Caja_Total[$j],2,'.', ', '); ?></td>
              <?php if($idE == 6){ ?>
                <td id="td"><?php echo '$ '.number_format($Caja_Total_SJ[$j],2,'.', ', '); ?></td>
              <?php } } ?>
            </tr>
            <?php
                $Suma_Caja_Total = $Suma_Caja_Propina + $Suma_Caja_Bancos + $Suma_Moneda;
                if($Suma_Caja_Total == 0){ $dato = '-'; } else { $dato = '$ '.number_format($Suma_Caja_Total,2,'.',', '); }
            ?>
            <tr>
              <th>TOTAL (Sin Propina)</th>
              <td id="th"><?php echo $dato; ?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) {
                $array = array($idE,$fechas[$j]);
                $propina = $fin->Select_Dia_Propina(1,$idE,$fechas[$j]);
                $efectivo = $fin->Select_Dia_Propina(2,$idE,$fechas[$j]);

                $bancos = $fin->Select_Suma_Dia_Bancos($array);

                $Caja_Total[$j] = $efectivo + $bancos + $Moneda_Caja[$j] - $propina;
                if($idE == 6){
                  $propina = $fin->Select_Dia_Propina(1,9,$fechas[$j]);
                  $efectivo = $fin->Select_Dia_Propina(2,9,$fechas[$j]);

                  $array = array(9,$fechas[$j]);
                  $bancos = $fin->Select_Suma_Dia_Bancos($array);

                  $Caja_Total_SJ[$j] = $efectivo + $bancos + $Moneda_Caja_SJ[$j] - $propina;
                  if($Caja_Total_SJ[$j] == 0){ $dato2 = '-'; }else{ $dato2 = '$ '.number_format($Caja_Total_SJ[$j],2,'.',', '); }
                }

              ?>
              <td id="td"><?php echo '$ '.number_format($Caja_Total[$j],2,'.', ', '); ?></td>
              <?php if($idE == 6){ ?>
                <td id="td"><?php echo $dato2; ?></td>
              <?php } } ?>
            </tr>
            <?php
              $Suma_Caja_Diferencia = ($Suma_Total * -1) + $Suma_Caja_Total - $Suma_Caja_Pago + $Suma_Caja_Deuda;
              if($Suma_Caja_Diferencia == 0){ $Suma_Caja_Diferencia = '-'; } else { $Suma_Caja_Diferencia = '$ '.number_format($Suma_Caja_Diferencia,2,'.', ', '); }
              if($Suma_Caja_Diferencia == '$ -0.00'){ $Suma_Caja_Diferencia = '-'; }
            ?>
            <tr>
              <th>DIFERENCIA</th>
              <td id="th"><?php echo $Suma_Caja_Diferencia; ?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) {
                $Diferencia = ($Total_Venta[$j] * -1) + $Caja_Total[$j] + $Caja_Deuda[$j] - $Caja_Pago[$j];
                if($Diferencia == 0){ $dato = '-'; } else { $dato = '$ '.number_format($Diferencia,2 ,'.', ', '); }
                if($Diferencia == '$ -0.00'){ $dato = '-'; }

                if($idE == 6){
                  $dato2 = ($Total_Venta_SJ[$j] * -1) + $Caja_Total_SJ[$j];
                  if($dato2 == 0){ $dato2 = '-'; } else { $dato2 = '$ '.number_format($dato2,2 ,'.', ', '); }
                  if($dato2 == '$ -0.00'){ $dato2 = '-'; }
                }
              ?>
              <td id="td"><?php echo $dato; ?></td>
              <?php if($idE == 6){ ?>
                <td id="td"><?php echo $dato2; ?></td>
              <?php } } ?>
            </tr>
            <!-- CRÉDITO -->
            <tr>
              <th style="background:#F4B084;" class="text-center"><strong>CLIENTES</strong></th>
              <td id="th" style="background:#F4B084; color:#F4B084;">0</td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
              <td id="td" style="background:#F4B084; color:#F4B084;">0</td>
              <?php if($idE == 6){?>
                <td id="td" style="background:#F4B084; color:#F4B084;">0</td>
              <?php } }?>
            </tr>

            <!--PAGO -->
            <?php if($Suma_Caja_Pago == 0){ $Suma_Caja_Pago = '-'; } else { $Suma_Caja_Pago = '$ '.number_format($Suma_Caja_Pago,2 ,'.', ', '); }  ?>
            <tr>
              <th>PAGO</th>
              <td id="th"><?php echo $Suma_Caja_Pago; ?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
              <td id="td"><?php echo $Pago_Credito[$j]; ?></td>
              <?php if($idE == 6){?>
                <td id="td"><?php echo '-'; ?></td>
              <?php } }?>
            </tr>

            <!-- DEUDA -->
            <?php if($Suma_Caja_Deuda == 0){ $Suma_Caja_Deuda = '-'; } else { $Suma_Caja_Deuda = '$ '.number_format($Suma_Caja_Deuda,2 ,'.', ', '); }  ?>
            <tr>
              <th>DEUDA</th>
              <td id="th"><?php echo $Suma_Caja_Deuda; ?></td>
              <?php for ($j=0; $j < $Count_Fechas; $j++) {?>
              <td id="td"><?php echo $Deuda_Credito[$j]; ?></td>
              <?php if($idE == 6){?>
                <td id="td"><?php echo '-'; ?></td>
              <?php } }?>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
