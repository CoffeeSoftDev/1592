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

  $bg_entrada1 = '#DEEBF7';
  $bg_entrada2 = '#BDD7EE';
  $bg_salida1 = '#FBE5D6';
  $bg_salida2 = '#F8CBAD';
  $bg_categoria = "#ECF0F1";

  $array = array($idE,$date1,$date2);
  $query1 = "SELECT Fecha_Compras FROM insumos_clase,insumos_udn,compras WHERE idIC = id_IC AND idUI = id_UI AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Fecha_Compras";
  $query2 = "SELECT idUI,Name_IC AS Saldo FROM insumos_clase,insumos_udn,compras WHERE idIC = id_IC AND idUI = id_UI AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Name_IC ORDER BY Saldo DESC";


  $data_fecha = null;
  $sql1 = $crud->_Select($query1,$array,"5");
  $fechas = array();
  foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
  $Count_Fechas = count($fechas);

  $data_name = null;
  $sql2 = $crud->_Select($query2,$array,"5");
  $Nombres = array(); $id_UI = array();
  foreach ($sql2 as $key => $data_name) { $id_UI[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; }
  $Count_Name = count($Nombres);

  $Entrada_Total = 0; $Salida_Total = 0;
?>
<style media="screen">
  .scrolling #td{
    height:60px;
  }
</style>
<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="scrolling outer">
      <div class="inner">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th class="text-center"> </th>
              <?php
                $dato_s = $fin->Select_Suma_Total_Almacen_Pagos($idE,$date1,$date2);
                if($dato_s == 0){ $Salida_Total = '-'; } else { $Salida_Total = '$'.number_format($dato_s,2,'.',', '); }

                $dato_e = $fin->Select_Suma_Total_Almacen_Gastos($idE,$date1,$date2);
                if($dato_e == 0){ $Entrada_Total = '-'; } else { $Entrada_Total = '$'.number_format($dato_e,2,'.',', '); }

                $dato = $dato_e - $dato_s;
                if($dato == 0){ $dato = '-'; } else if($dato < 0){ $dato = '<span class="text-danger"><label class="icon-attention"></label></span> $ '.number_format($dato,2,'.',', '); } else { $dato = '$ '.number_format($dato,2,'.',', '); }

                echo '<td id="th"> </td>';
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="height:32px; background:'.$bg_entrada1.';"><strong>ENTRADAS</strong></td>';
                  echo '<td id="td" style="height:32px; background:'.$bg_salida1.';"><strong>SALIDAS</strong></td>';
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th class="text-center">SALIDAS</th>
              <?php
                echo '<td id="th">'.$Salida_Total.'</td>';

                for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($idE,$fechas[$j]);
                  $dato = $fin->Select_Suma_Hoy_Almacen_Pagos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }

                  echo '<td id="td" style="background:'.$bg_entrada2.'; height:32px;"><strong></strong></td>';
                  echo '<td id="td" style="height:32px; background:'.$bg_salida1.';"><strong>'.$dato.'</strong></td>';
                }
              ?>
            </tr>
            <tr>
              <th class="text-center">ENTRADAS</th>
              <?php

                echo '<td id="th">'.$Entrada_Total.'</td>';
                for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($idE,$fechas[$j]);
                  $dato = $fin->Select_Suma_Hoy_Almacen_Gastos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                  echo '<td id="td" style="height:32px; background:'.$bg_entrada1.';"><strong>'.$dato.'</strong></td>';
                  echo '<td id="td" style="background:'.$bg_salida2.'; height:32px;"><strong></strong></td>';
                }
              ?>
            </tr>
            <tr>
              <th style="background:#A9D08E;" class="text-center">ALMACÉN</th>
              <td id="th" style="background:#A9D08E; text-align:center;">TOTAL</td>
              <?php
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="background:#A9D08E; text-align:center; height:32px;"><strong>'.$fechas[$i].'</strong></td>';
                  echo '<td id="td" style="background:#A9D08E; text-align:center; height:32px;"><strong>'.$fechas[$i].'</strong></td>';
                }
              ?>
            </tr>
            <?php
            for ($j=0; $j < $Count_Name; $j++) {
              $dato = $fin->Select_Suma_Almacen_Saldo_Categoria($id_UI[$j],$date1,$date2);
              if($dato == 0){ $dato = '-'; } else if($dato < 0){ $dato = '<span class="text-danger"><label class="icon-attention"></label></span> $ '.number_format($dato,2,'.',', '); } else { $dato = '$ '.number_format($dato,2,'.',', '); }

              echo '<tr style="background:'.$bg_categoria.'; cursor:pointer;" onClick="almacen_detalles('.$Count_Name.','.$j.');">
                    <th style="height:60px;background:'.$bg_categoria.'; cursor:pointer;"><span class="icon-right-dir"></span>'.$Nombres[$j].'</th>
                    <td id="th" style="height:60px;background:'.$bg_categoria.'; cursor:pointer;">'.$dato.'</td>';

                    for ($i=0; $i < $Count_Fechas; $i++) {
                      $array = array($id_UI[$j],$fechas[$i]);
                      $pagos = $fin->Select_Data_Hoy_Almacen_Pagos_Categoria($array);
                      if($pagos == 0){ $pagos = '-'; } else { $pagos = '$'.number_format($pagos,2,'.',', '); }

                      $deuda = $fin->Select_Data_Hoy_Almacen_Gasto_Categoria($array);
                      if($deuda == 0){ $deuda = '-'; } else { $deuda = '$'.number_format($deuda,2,'.',', '); }
                      echo '<td id="td" style="background:'.$bg_entrada1.';">'.$deuda.'</td>';//deudas
                      echo '<td id="td" style="background:'.$bg_salida1.';">'.$pagos.'</td>';// pagos
                    }
              echo '</tr>';

              $array = array($id_UI[$j],$idE,$date1,$date2);
              $q_DA = "SELECT idUG,Name_Gastos AS Saldo FROM gastos,gastos_udn,compras WHERE idGastos = id_Gastos AND idUG = id_UG AND id_UI = ? AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Name_Gastos ORDER BY Saldo DESC";
              $sql_DA = $crud->_Select($q_DA,$array,"5");
              foreach ($sql_DA as $val) {
                $dato = $fin->Select_Suma_Almacen_Saldo($val[0],$date1,$date2);
                if($dato == 0){ $dato = '-'; } else if($dato < 0){ $dato = '<span class="text-danger"><label class="icon-attention"></label></span> $ '.number_format($dato,2,'.',', '); } else { $dato = '$ '.number_format($dato,2,'.',', '); }

                echo '<tr class="DA_'.$j.' hide">
                <th style="height:60px;">'.$val[1].'</th>
                <td id="th" style="height:60px;">'.$dato.'</td>';
                for ($i=0; $i < $Count_Fechas; $i++) {
                  $array = array($val[0],$fechas[$i]);
                  $pagos = $fin->Select_Data_Hoy_Almacen_Pagos($array);
                  if($pagos == 0){ $pagos = '-'; } else { $pagos = '$'.number_format($pagos,2,'.',', '); }

                  $deuda = $fin->Select_Data_Hoy_Almacen_Gasto($array);
                  if($deuda == 0){ $deuda = '-'; } else { $deuda = '$'.number_format($deuda,2,'.',', '); }
                  echo '<td id="td" style="background:'.$bg_entrada1.';">'.$deuda.'</td>';//deudas
                  echo '<td id="td" style="background:'.$bg_salida1.';">'.$pagos.'</td>';// pagos
                }
                echo '</tr>';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
