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
    $array = array($idE,$date1,$idE,$date1);
    $query1 = "SELECT Fecha_Compras FROM compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras = ? UNION SELECT Fecha_Compras FROM compras WHERE id_UP IS NOT NULL AND Pago IS NOT NULL AND id_UDN = ? AND Fecha_Compras = ?";
    $query2 = "SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND id_CG = 2 AND compras.id_UDN = ? AND Fecha_Compras = ? UNION SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND Pago IS NOT NULL AND compras.id_UDN = ? AND Fecha_Compras = ?";
  }
  else {
    $array = array($idE,$date1,$date2,$idE,$date1,$date2);
    $query1 = "SELECT Fecha_Compras FROM compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? UNION SELECT Fecha_Compras FROM compras WHERE id_UP IS NOT NULL AND Pago IS NOT NULL AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? ORDER BY Fecha_Compras";
    $query2 = "SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND id_CG = 2 AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? UNION SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND Pago IS NOT NULL AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql1 = $crud->_Select($query1,$array,"5");
  $fechas = array();
  foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
  $Count_Fechas = count($fechas);

  $sql2 = $crud->_Select($query2,$array,"5");
  $Nombres = array(); $idP = array();
  foreach ($sql2 as $key => $data_name) { $idP[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; }
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
              <th>TOTAL</th>
              <?php
                $dato_s = $fin->Select_Suma_Total_Proveedor_Pagos($idE,$date1,$date2);
                if(!isset($dato_s)){ $Salida_Total = '-'; } else { $Salida_Total = '$'.number_format($dato_s,2,'.',', '); }

                $dato_e = $fin->Select_Suma_Total_Proveedor_Gastos($idE,$date1,$date2);
                if(!$dato_e){ $Entrada_Total = '-'; } else { $Entrada_Total = '$'.number_format($dato_e,2,'.',', '); }

                $dato = $dato_e - $dato_s;
                if($dato == 0){ $dato = '-'; } else if($dato < 0){ $dato = '<span class="text-danger"><label class="icon-attention"></label></span> $ '.number_format($dato,2,'.',', '); } else { $dato = '$'.number_format($dato,2,'.',', '); }

                echo '<td id="th">'.$dato.'</td>';
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="height:32px;"><strong>PAGOS</strong></td>';
                  echo '<td id="td" style="height:32px;"><strong>DEUDAS</strong></td>';
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th>PAGOS</th>
              <?php
                echo '<td id="th">'.$Salida_Total.'</td>';
                for ($i=0; $i < $Count_Fechas; $i++) {
                  $array = array($idE,$fechas[$i]);
                  $dato = $fin->Select_Suma_Hoy_Proveedor_Pagos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$'.number_format($dato,2,'.',', '); }
                  echo '<td id="td" style="height:32px;"><strong>'.$dato.'</strong></td>';
                  echo '<td id="td" style="background:#ECF0F1; color:#ECF0F1; height:32px;"><strong>0</strong></td>';
                }
              ?>
            </tr>
            <tr>
              <th>DEUDAS</th>
              <?php
                echo '<td id="th">'.$Entrada_Total.'</td>';
                for ($i=0; $i < $Count_Fechas; $i++) {
                  $array = array($idE,$fechas[$i]);
                  $dato = $fin->Select_Suma_Hoy_Proveedor_Gastos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$'.number_format($dato,2,'.',', '); }
                  echo '<td id="td" style="background:#ECF0F1; color:#ECF0F1; height:32px;"><strong>0</strong></td>';
                  echo '<td id="td" style="height:32px;"><strong>'.$dato.'</strong></td>';
                }
              ?>
            </tr>
            <tr>
              <th style="background:#A9D08E;">PROVEEDOR</th>
              <td id="th" style="background:#A9D08E;">TOTAL</td>
              <?php
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="background:#A9D08E; height:32px;"><strong>'.$fechas[$i].'</strong></td>';
                  echo '<td id="td" style="background:#A9D08E; height:32px;"><strong>'.$fechas[$i].'</strong></td>';
                }
              ?>
            </tr>
            <?php
            for ($j=0; $j < $Count_Name; $j++) {
              $dato = $fin->Select_Suma_Total_Proveedor($idP[$j],$date1,$date2);
              if($dato == 0){ $dato = '-'; } else if($dato < 0){ $dato = '<span class="text-danger"><label class="icon-attention"></label></span> $ '.number_format($dato,2,'.',', '); }  else { $dato = '$'.number_format($dato,2,'.',', '); }

              echo '<tr>
                    <th style="height:60px;">'.$Nombres[$j].'</th>
                    <td id="th" style="height:60px;">'.$dato.'</td>';

              for ($i=0; $i < $Count_Fechas; $i++) {
                $array = array($idP[$j],$fechas[$i]);
                $pagos = $fin->Select_Hoy_Proveedor_Pagos($array);
                if($pagos == 0){ $pagos = '-'; } else { $pagos = '$'.number_format($pagos,2,'.',', '); }

                $deuda = $fin->Select_Hoy_Proveedor_Gastos($array);
                if($deuda == 0){ $deuda = '-'; } else { $deuda = '$'.number_format($deuda,2,'.',', '); }
                echo '<td id="td">'.$pagos.'</td>';// pagos
                echo '<td id="td">'.$deuda.'</td>';//deudas
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
