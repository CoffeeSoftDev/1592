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
  $idUI = $_POST['Opc_Select'];

  $var = "AND Name_IC LIKE '%costo%'";
  if($idUI != 0){
    $var = 'AND id_UI = '.$idUI;
  }


  $array = array();
  if($date1 == $date2) {
    $array[0] = $idE;  $array[1] = $date1;
    $query1 = "SELECT Fecha_Compras FROM insumos_clase,insumos_udn,compras WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras = ? GROUP BY Fecha_Compras";
    $query2 = "SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) AS Saldo FROM gastos,gastos_udn,insumos_clase,insumos_udn,compras WHERE idGastos = id_Gastos AND idUG = id_UG AND idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras = ? GROUP BY Name_Gastos ORDER BY Saldo DESC";
  }
  else {
    $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
    $query1 = "SELECT Fecha_Compras FROM insumos_clase,insumos_udn,compras WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Fecha_Compras";
    $query2 = "SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) AS Saldo FROM gastos,gastos_udn,insumos_clase,insumos_udn,compras WHERE idGastos = id_Gastos AND idUG = id_UG AND idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Name_Gastos ORDER BY Saldo DESC";
  }
  
  $sql1 = $crud->_Select($query1,$array,"5");
  $fechas = array();
  foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
  $Count_Fechas = count($fechas);

  $sql2 = $crud->_Select($query2,$array,"5");
  $Nombres = array(); $idC = array(); $Cantidad = array();
  foreach ($sql2 as $key => $data_name) { $idC[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; $Cantidad[$key] = $data_name[2]; }
  $Count_Name = count($Nombres);

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
              <th class="text-center">TOTAL DE COSTOS</th>
              <?php
                $dato = $fin->Select_Suma_Total_Almacen_Gastos($var,$idE,$date1,$date2);
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                echo '<td id="th">'.$dato.'</td>';
                for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($idE,$fechas[$j]);
                  $dato = $fin->Select_Suma_Hoy_Almacen_Gastos($var,$array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                  echo '<td id="td" style="height:32px;"><strong>'.$dato.'</strong></td>';
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th style="background:#A9D08E;" class="text-center">COSTOS</th>
              <td id="th" style="background:#A9D08E;">TOTAL</td>
              <?php
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="background:#A9D08E; height:33px;"><strong>'.$fechas[$i].'</strong></td>';
                }
              ?>
            </tr>
            <?php
              for ($i=0; $i < $Count_Name; $i++) {
                echo '<tr>
                  <th style="height:60px;">'.$Nombres[$i].'</th>
                  <td id="th" style="height:60px;">$ '.number_format($Cantidad[$i],2,'.',', ').'</td>';

                  for ($j=0; $j < $Count_Fechas; $j++) {
                    $array = array($idC[$i],$fechas[$j]);
                    $dato = $fin->Select_Data_Hoy_Almacen_Gasto($array);
                    if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                    echo '<td id="td">'.$dato.'</td>';
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
