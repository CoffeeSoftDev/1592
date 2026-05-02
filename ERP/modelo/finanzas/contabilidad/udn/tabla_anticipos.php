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
    $query1 = "SELECT DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') as date FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ? GROUP BY DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d')";
    $query2 = "SELECT Empleado_Anticipo,Nombres,ROUND(SUM(Saldo),2) AS SALDO FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ? GROUP BY Empleado_Anticipo ORDER BY SALDO DESC";
  }
  else {
    $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
    $query1 = "SELECT DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') as date FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') BETWEEN ? AND ? GROUP BY DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d')";
    $query2 = "SELECT Empleado_Anticipo,Nombres,ROUND(SUM(Saldo),2) AS SALDO FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') BETWEEN ? AND ? GROUP BY Empleado_Anticipo ORDER BY SALDO DESC";
  }

  $sql1 = $crud->_Select($query1,$array,"6");
  $fechas = array();
  foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
  $Count_Fechas = count($fechas);

  $sql2 = $crud->_Select($query2,$array,"6");
  $Nombres = array(); $idN = array(); $Cantidad = array();
  foreach ($sql2 as $key => $data_name) { $idN[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; $Cantidad[$key] = $data_name[2]; }
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
              <th class="text-center">TOTAL DE ANTICIPOS </th>
              <?php
                $date_now = $fin->NOW();

                //Comprobar en que quincena estamos
                $day = date("d", strtotime("$date_now"));
                $month = date("m", strtotime("$date_now"));
                $year = date("Y", strtotime("$date_now"));
                $dia_final = $util->Ultimo_dia_Mes($month,$year);
                $total_anticipos = '-';

                if($day > 4 && $day < 15){
                  $date1 = $year.'-'.$month.'-5';
                  $date2 = $year.'-'.$month.'-9';
                  $dato = $fin->Select_Suma_Total_Anticipos($idE,$date1,$date2);
                  if($dato == 0){ $total_anticipos = '$ 0.00'; } else { $total_anticipos = '$ '.number_format($dato,2,'.',', '); }
                }
                else if($day > 19 && $day < $dia_final){
                  $date1 = $year.'-'.$month.'-20';
                  $date2 = $year.'-'.$month.'-'.$dia_final;
                  $dato = $fin->Select_Suma_Total_Anticipos($idE,$date1,$date2);
                  if($dato == 0){ $total_anticipos = '-'; } else { $total_anticipos = '$ '.number_format($dato,2,'.',', '); }
                }


                // if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                echo '<td id="th">'.$total_anticipos.'</td>';
                for ($j=0; $j < $Count_Fechas; $j++) {
                  $array = array($idE,$fechas[$j]);
                  $dato = $fin->Select_Suma_Anticipos($array);
                  if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
                  echo '<td id="td" style="height:32px;"><strong>'.$dato.'</strong></td>';
                }
              ?>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th style="background:#A9D08E;" class="text-center">INGRESO</th>
              <td id="th" style="background:#A9D08E; text-align:center;">TOTAL</td>
              <?php
                for ($i=0; $i < $Count_Fechas; $i++) {
                  echo '<td id="td" style="background:#A9D08E; height:32px; text-align:center;"><strong>'.$fechas[$i].'</strong></td>';
                }
              ?>
            </tr>
            <?php

              for ($i=0; $i < $Count_Name; $i++) {
                //Comprobar en que quincena estamos
                $total_anticipos_x_colaborador = '-';

                if($day > 4 && $day < 15){
                  $date1 = $year.'-'.$month.'-5';
                  $date2 = $year.'-'.$month.'-9';
                  $dato = $fin->Select_Suma_Total_Anticipos_x_Colaborador($idN[$i],$idE,$date1,$date2);
                  if($dato == 0){ $total_anticipos = '$ 0.00'; } else { $total_anticipos = '$ '.number_format($dato,2,'.',', '); }
                }
                else if($day > 19 && $day < $dia_final){
                  $date1 = $year.'-'.$month.'-20';
                  $date2 = $year.'-'.$month.'-'.$dia_final;
                  $dato = $fin->Select_Suma_Total_Anticipos_x_Colaborador($idN[$i],$idE,$date1,$date2);
                  if($dato == 0){ $total_anticipos_x_colaborador = '-'; } else { $total_anticipos_x_colaborador = '$ '.number_format($dato,2,'.',', '); }
                }
                echo '<tr>
                  <th style="height:60px;">'.$Nombres[$i].'</th>
                  <td id="th" style="height:60px;">'.$total_anticipos_x_colaborador.'</td>';

                  for ($j=0; $j < $Count_Fechas; $j++) {
                    $array = array($idN[$i],$fechas[$j]);
                    $dato = $fin->Select_Data_Hoy_Colaborador($array);
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
