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
  if($date1 == $date2) {
    $array[0] = $idE;  $array[1] = $date1;
    $query1 = "SELECT Fecha_Banco FROM bancos_bitacora,bancos_udn WHERE idUB = id_UB AND id_UDN = ? AND Fecha_Banco = ? GROUP BY Fecha_Banco";
    $query2 = "SELECT id_UB,Name_Bancos,ROUND(SUM(Pago),2) as Saldo FROM bancos,bancos_udn,bancos_bitacora WHERE idBancos = id_Bancos AND idUB = id_UB AND id_UDN = ? AND Fecha_Banco = ? GROUP BY Name_Bancos ORDER BY Saldo DESC";
  }
  else {
    $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
    $query1 = "SELECT Fecha_Banco FROM bancos_bitacora,bancos_udn WHERE idUB = id_UB AND id_UDN = ? AND Fecha_Banco BETWEEN ? AND ? GROUP BY Fecha_Banco";
    $query2 = "SELECT id_UB,Name_Bancos,ROUND(SUM(Pago),2) as Saldo FROM bancos,bancos_udn,bancos_bitacora WHERE idBancos = id_Bancos AND idUB = id_UB AND id_UDN = ? AND Fecha_Banco BETWEEN ? AND ? GROUP BY Name_Bancos ORDER BY Saldo DESC";
  }

  $sql1 = $crud->_Select($query1,$array,"5");
  $fechas = array();
  foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
  $Count_Fechas = count($fechas);

  $sql2 = $crud->_Select($query2,$array,"5");
  $Nombres = array(); $idB = array(); $Cantidad = array();
  foreach ($sql2 as $key => $data_name) { $idB[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; $Cantidad[$key] = $data_name[2]; }
  $Count_Name = count($Nombres);

?>
<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="scrolling outer">
      <div class="inner">
        <table class="table table-condensed table-bordered">
          <thead>
            <tr>
              <th class="text-center">TOTAL DE BANCOS</th>
              <?php
                $dato = $fin->Select_Suma_Total_Bancos($idE,$date1,$date2);
                if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                echo '<td id="th">'.$dato.'</td>';
                for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($idE,$fechas[$j]);
                  $dato = $fin->Select_Suma_Bancos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                  echo '<td id="td" style="height:32px;"><strong>'.$dato.'</strong></td>';
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th style="background:#A9D08E;" class="text-center">BANCOS</th>
              <td id="th" style="background:#A9D08E; text-align:center;">TOTAL</td>
              <?php
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="background:#A9D08E; text-align:center;"><strong>'.$fechas[$i].'</strong></td>';
                }
              ?>
            </tr>
            <?php
              for ($i=0; $i < $Count_Name; $i++) {
                echo '<tr>
                  <th ">'.$Nombres[$i].'</th>
                  <td id="th">$ '.number_format($Cantidad[$i],2,'.',', ').'</td>';

                  for ($j=0; $j < $Count_Fechas; $j++) {
                    $array = array($idB[$i],$fechas[$j]);
                    $dato = $fin->Select_Data_Hoy_Bancos($array);
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
