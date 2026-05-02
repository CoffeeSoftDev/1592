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

  $bg_pagos1 = '#C5E0B4';
  $bg_pagos2 = '#E7F2E0';
  $bg_deuda1 = '#FFE699';
  $bg_deuda2 = '#FFF2CC';

  $array = array();
  if($date1 == $date2) {
    $array = array($idE,$date1,$idE,$date1);
    $query1 = "SELECT Fecha_Credito FROM creditos_bitacora,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Credito = ? UNION SELECT Fecha_Consumo FROM creditos_consumo,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Consumo = ? ORDER BY Fecha_Credito ASC";
  }
  else {
    $array = array($idE,$date1,$date2,$idE,$date1,$date2);
    $query1 = "SELECT Fecha_Credito FROM creditos_bitacora,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Credito BETWEEN ? AND ? UNION SELECT Fecha_Consumo FROM creditos_consumo,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Consumo BETWEEN ? AND ? ORDER BY Fecha_Credito ASC";
  }

  $sql1 = $crud->_Select($query1,$array,"5");
  $fechas = array();
  foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
  $Count_Fechas = count($fechas);

  // $array = array();
  // if($date1 == $date2) {
    $array = array($idE,$date2);
    $query2 = "SELECT idUC,Name_Credito FROM creditos,creditos_udn,creditos_consumo WHERE idCredito = id_Credito AND idUC = id_UC AND id_UDN = ? AND Fecha_Consumo <= ? GROUP BY Name_Credito";
  // }
  // else {
  //   $array = array($idE,$date1,$date2,$idE,$date1,$date2);
  //   $query2 = "SELECT idUC,Name_Credito FROM creditos,creditos_udn,creditos_consumo WHERE idCredito = id_Credito AND idUC = id_UC AND creditos_udn.id_UDN = ? AND Fecha_Consumo BETWEEN ? AND ? UNION SELECT idUC,Name_Credito FROM creditos,creditos_udn,creditos_bitacora WHERE idCredito = id_Credito AND idUC = id_UC AND creditos_udn.id_UDN = ? AND Fecha_Credito BETWEEN ? AND ?";
  // }

  $sql2 = $crud->_Select($query2,$array,"5");
  $Nombres = array(); $idC = array(); $Cantidad = array();
  foreach ($sql2 as $key => $data_name) { $idUC[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; }
  $Count_Name = count($Nombres);

?>
<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="scrolling outer">
      <div class="inner">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th class="text-center"> </th>
              <td id="th" class="text-center"> </td>
              <?php
                for ($j=0; $j < $Count_Fechas; $j++) {
                  $dato = 0;
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                  echo '<td id="td" style="height:32px; text-align:center; background:'.$bg_pagos1.';"><strong>PAGOS</strong></td>';
                  echo '<td id="td" style="height:32px; text-align:center; background:'.$bg_deuda1.';"><strong>DEUDAS</strong></td>';
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="text-center">PAGOS</th>
              <?php
                if ($date1 == $date2) {
                  $array = array($idE,$date1);
                  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito = ?";
                }
                else {
                  $array = array($idE,$date1,$date2);
                  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito BETWEEN ? AND ?";
                }
                $row = null;
                $sql = $crud->_Select($query,$array,"5");
                foreach ($sql as $row);
                if ( !isset($row) ) { $row[0] = 0; }

                echo '<td id="th">$ '.number_format($row[0],2,'.',',').'</td>';

                for ($i=0; $i < $Count_Fechas; $i++) {
                  $row = null;
                  $array_pago = array($idE,$fechas[$i]);
                  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito = ?";
                  $sql = $crud->_Select($query,$array_pago,"5");
                  foreach ($sql as $row);
                  if ( !isset($row) ) { $row[0] = 0; }
                  $dato = '-';
                  if( $row[0] != 0) { $dato = '$ '.number_format($row[0],2,'.',','); }

                  echo '<td id="td" style="height:32px; background:'.$bg_pagos1.';"><strong>'.$dato.'</strong></td>';
                  echo '<td id="td" style="background:'.$bg_deuda2.'; height:32px;"><strong> </strong></td>';
                }
              ?>
            </tr>
            <tr>
              <th class="text-center">DEUDAS</th>

              <?php
                if ($date1 == $date2) {
                  $array = array($idE,$date1);
                  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo = ?";
                }
                else {
                  $array = array($idE,$date1,$date2);
                  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo BETWEEN ? AND ?";
                }
                $row = null;
                $sql = $crud->_Select($query,$array,"5");
                foreach ($sql as $row);
                if ( !isset($row) ) { $row[0] = 0; }

                echo '<td id="th">$ '.number_format($row[0],2,'.',',').'</td>';

                for ($i=0; $i < $Count_Fechas; $i++) {
                  $row = null;
                  $array_deuda = array($idE,$fechas[$i]);
                  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo = ?";
                  $sql = $crud->_Select($query,$array_deuda,"5");
                  foreach ($sql as $row);
                  if ( !isset($row) ) { $row[0] = 0; }
                  $dato = '-';
                  if( $row[0] != 0) { $dato = '$ '.number_format($row[0],2,'.',','); }


                  echo '<td id="td" style="height:32px; background:'.$bg_deuda1.';"><strong> </strong></td>';
                  echo '<td id="td" style="background:'.$bg_pagos2.'; height:32px;"><strong>'.$dato.'</strong></td>';
                }
              ?>
            </tr>
            <tr>
              <th style="background:#A9D08E;" class="text-center">CRÉDITOS</th>
              <td id="th" style="background:#A9D08E; text-align:center;">TOTAL</td>
              <?php
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="background:#A9D08E; text-align:center;"><strong>'.$fechas[$i].'</strong></td>';
                  echo '<td id="td" style="background:#A9D08E; text-align:center;"><strong>'.$fechas[$i].'</strong></td>';
                }
              ?>
            </tr>

            <?php
              for ($i=0; $i < $Count_Name; $i++) {
                echo '<tr>';
                  echo '<th style="font-size:13px;">'.$Nombres[$i].'</th>';

                  // if ($date1 == $date2) {
                  //   $array_pd = array($idUC[$i],$date1);
                  //   $query_pago = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UC = ? AND Fecha_Credito = ?";
                  //   $query_deuda = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UC = ? AND Fecha_Consumo = ?";
                  // }
                  // else{
                    $array_pd = array($idUC[$i],$date2);
                    $query_pago = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UC = ? AND Fecha_Credito <= ?";
                    $query_deuda = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UC = ? AND Fecha_Consumo <= ?";
                  // }
                  $row_pago = null;
                  $sql_pago = $crud->_Select($query_pago,$array_pd,"5");
                  foreach($sql_pago as $row_pago);
                  if ( !isset($row_pago) ) { $row_pago[0] = 0; }

                  $row_deuda = null;
                  $sql_deuda = $crud->_Select($query_deuda,$array_pd,"5");
                  foreach($sql_deuda as $row_deuda);
                  if ( !isset($row_deuda) ) { $row_deuda[0] = 0; }

                  $total_deuda = $row_deuda[0] - $row_pago[0];

                  echo '<td id="th">$ '.number_format($total_deuda,2,'.',',').'</td>';
                  for ($j=0; $j < $Count_Fechas; $j++) {
                    $array_pd = array($idUC[$i],$fechas[$j]);
                    $query_pago = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UC = ? AND Fecha_Credito = ?";
                    $query_deuda = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UC = ? AND Fecha_Consumo = ?";
                    $row_pago = null;
                    $sql_pago = $crud->_Select($query_pago,$array_pd,"5");
                    foreach($sql_pago as $row_pago);
                    if ( !isset($row_pago) ) { $row_pago[0] = 0; }

                    $row_deuda = null;
                    $sql_deuda = $crud->_Select($query_deuda,$array_pd,"5");
                    foreach($sql_deuda as $row_deuda);
                    if ( !isset($row_deuda) ) { $row_deuda[0] = 0; }

                    $dato_pago = '-';
                    if ( $row_pago[0] != 0 ) { $dato_pago = '$ '.number_format($row_pago[0],2,'.',','); }
                    $dato_deuda = '-';
                    if ( $row_deuda[0] != 0 ) { $dato_deuda = '$ '.number_format($row_deuda[0],2,'.',','); }

                    echo '<td id="td" style="background:'.$bg_pagos1.'; height:32px;"><strong>'.$dato_pago.'</strong></td>';
                    echo '<td id="td" style="height:32px; background:'.$bg_deuda1.';"><strong>'.$dato_deuda.'</strong></td>';
                  }
                echo '</tr>';
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
