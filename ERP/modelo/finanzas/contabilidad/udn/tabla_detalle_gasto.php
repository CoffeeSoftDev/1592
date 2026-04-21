<?php
  include_once("../../../SQL_PHP/_CRUD.php");
  include_once("../../../SQL_PHP/_Finanzas.php");
  include_once("../../../SQL_PHP/_Utileria.php");

  //Se declaran los utiletos para utilizar las funciones segun los archivos externos
  $crud = new CRUD;
  $util = new Util;
  $fin = new Finanzas;

  $idE = $_POST['udn'];
  $date1 = $_POST['date1'];
  $date2 = $_POST['date2'];
  $array = array();
  if($date1 == $date2){
    $array = array($idE,$date1);
    $query = "SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) as Saldo FROM gastos,gastos_udn,compras WHERE
    idGastos = id_Gastos AND idUG = id_UG AND id_CG = 3 AND gastos_udn.id_UDN = ? AND Fecha_Compras = ? GROUP BY Name_Gastos ORDER BY Saldo DESC";
  }
  else{
    $array = array($idE,$date1,$date2);
    $query = "SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) as Saldo FROM gastos,gastos_udn,compras WHERE
    idGastos = id_Gastos AND idUG = id_UG AND id_CG = 3 AND gastos_udn.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Name_Gastos ORDER BY Saldo DESC";
  }

  $sql = $crud->_Select($query,$array,"5");
  $Nombres = array(); $idB = array(); $Cantidad = array();
  foreach ($sql as $key => $data_name) { $idB[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; $Cantidad[$key] = $data_name[2]; }
  $Count_Name = count($Nombres);

?>

<div  id="table-conf">
  <div class="table table-responsive" >
    <!--CONTENIDO-->
    <table class="table table-striped table-hover table-bordered table-condensed">
      <thead>
        <tr class="text-center " id="titulo">
          <td colspan = "4"><strong>DETALLES DE GASTOS</strong></td>
        </tr>
      </thead>
      <tbody>
        <?php for ($i=0; $i < $Count_Name; $i++) { ?>
        <tr>
          <td class = "col-sm-3"><?php echo $Nombres[$i]; ?></td>
          <td class = "col-sm-3"></td>
          <td class = "col-sm-3 text-right">$ <?php echo number_format($Cantidad[$i],2,'.',', '); ?></td>
          <td class = "col-sm-3"></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
