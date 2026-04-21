<?php
  session_start();

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
      $query2 = "SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) AS Saldo FROM gastos,gastos_udn,insumos_clase,insumos_udn,compras WHERE idGastos = id_Gastos AND idUG = id_UG AND idIC = id_IC AND idUI = id_UI AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ? AND Fecha_Compras = ? GROUP BY Name_Gastos ORDER BY Saldo DESC";
    }
    else {
      $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
      $query2 = "SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) AS Saldo FROM gastos,gastos_udn,insumos_clase,insumos_udn,compras WHERE idGastos = id_Gastos AND idUG = id_UG AND idIC = id_IC AND idUI = id_UI AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Name_Gastos ORDER BY Saldo DESC";
    }

    $sql2 = $crud->_Select($query2,$array,"5");
    $Nombres = array(); $idA = array(); $Cantidad = array();
    foreach ($sql2 as $key => $data_name) { $idA[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; $Cantidad[$key] = $data_name[2]; }
    $Count_Name = count($Nombres);

?>
<div  id="table-conf">
  <div class="table table-responsive" >
    <!--CONTENIDO-->
    <table class="table table-striped table-hover table-bordered table-condensed">
      <thead>
        <tr class="text-center " id="titulo">
          <td colspan = "4"><strong>DETALLES DE ALMACÉN</strong></td>
        </tr>
      </thead>
      <tbody>
        <?php for ($i=0; $i < $Count_Name; $i++) {
          $Entrada = $fin->Select_SUM_Total_Entradas($idA[$i],$date1,$date2);
          if($Entrada == 0){ $Entrada = '-'; } else{ $Entrada = '$ '.number_format($Entrada,2,'.',', '); }
          $Salidas = $fin->Select_SUM_Total_Salidas($idA[$i],$date1,$date2);
          if($Salidas == 0){ $Salidas = '-'; } else{ $Salidas = '$ '.number_format($Salidas,2,'.',', '); }
        ?>
          <tr>
            <td class = "col-sm-3"><?php echo $Nombres[$i];?></td>
            <td class = "col-sm-3 text-right"><?php echo $Entrada; ?></td>
            <td class = "col-sm-3 text-right"><?php echo $Salidas; ?></td>
            <td class = "col-sm-3"></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
