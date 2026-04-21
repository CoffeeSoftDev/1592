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
    $query2 = "SELECT id_UB,Name_Bancos,ROUND(SUM(Pago),2) as Saldo FROM bancos,bancos_udn,bancos_bitacora WHERE idBancos = id_Bancos AND idUB = id_UB AND id_UDN = ? AND Fecha_Banco = ? GROUP BY Name_Bancos ORDER BY Saldo DESC";
  }
  else {
    $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
    $query2 = "SELECT id_UB,Name_Bancos,ROUND(SUM(Pago),2) as Saldo FROM bancos,bancos_udn,bancos_bitacora WHERE idBancos = id_Bancos AND idUB = id_UB AND id_UDN = ? AND Fecha_Banco BETWEEN ? AND ? GROUP BY Name_Bancos ORDER BY Saldo DESC";
  }

  $sql2 = $crud->_Select($query2,$array,"5");
  $Nombres = array(); $idB = array(); $Cantidad = array();
  foreach ($sql2 as $key => $data_name) { $idB[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; $Cantidad[$key] = $data_name[2]; }
  $Count_Name = count($Nombres);
?>

<div class="col-sm-12 col-xs-12">
  <div class="col-sm-12 col-xs-12 text-right">
    <!-- <h5><label><?php echo "Registros: ".$Paginas; ?></label></h5> -->
  </div>
</div>

<div  id="table-conf">
  <div class="table table-responsive" >
    <!--CONTENIDO-->
    <table class="table table-striped table-hover table-bordered table-condensed">
      <thead>
        <tr class="text-center " id="titulo">
            <td colspan = "4"><strong>DETALLES DE BANCOS</strong></td>
        </tr>
      </thead>
      <tbody>
        <?php for ($i=0; $i < $Count_Name; $i++) { ?>
          <tr>
            <td class = "col-sm-3"><?php echo $Nombres[$i];?></td>
            <td class = "col-sm-3 text-right">$ <?php echo number_format($Cantidad[$i],2,'.',', ');?></td>
            <td class = "col-sm-3"></td>
            <td class = "col-sm-3"></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
