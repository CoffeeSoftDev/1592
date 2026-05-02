<?php
  session_start();

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

  $row = null; $array = array();
  if($date1 == $date2){
    $array = array($idE,$date1,$idE,$date1);
    $query = "SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND id_CG = 2 AND compras.id_UDN = ? AND Fecha_Compras = ? UNION SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND Pago IS NOT NULL AND compras.id_UDN = ? AND Fecha_Compras = ?";
  }
  else{
    $array = array($idE,$date1,$date2,$idE,$date1,$date2);
    $query = "SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND id_CG = 2 AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? UNION SELECT id_UP,Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND Pago IS NOT NULL AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $crud->_Select($query,$array,"5");
  $Nombres = array(); $idP = array();
  foreach ($sql as $key => $data_name) { $idP[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; }
  $Count_Name = count($Nombres);
?>

<div  id="table-conf">
  <div class="table table-responsive" >
    <!--CONTENIDO-->
    <table class="table table-striped table-hover table-bordered table-condensed">
      <thead>
        <tr class="text-center " id="titulo">
            <td colspan = "4"><strong>DETALLES DE PROVEEDOR</strong></td>
        </tr>
      </thead>
      <tbody>
      <?php for ($i=0; $i < $Count_Name; $i++) {
        //Gasto
        $gasto = $fin->Select_Total_Deuda_Proveedor($idP[$i],$date1,$date2);
        if($gasto == 0){ $gasto = '-'; } else { $gasto = '$ '.number_format($gasto,2,'.',', '); }
        //Pagos
        $pagos = $fin->Select_Total_Pago_Proveedor($idP[$i],$date1,$date2);
        if($pagos == 0){ $pagos = '-'; } else { $pagos = '$ '.number_format($pagos,2,'.',', '); }
      ?>
        <tr>
          <td class = "col-sm-3"><?php echo $Nombres[$i];?></td>
          <td class = "col-sm-3 text-right"><?php echo $gasto; ?></td>
          <td class = "col-sm-3 text-right"><?php echo $pagos; ?></td>
          <td class = "col-sm-3"></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
