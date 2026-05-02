<?php
session_start();

include_once("../../../modelo/SQL_PHP/_CRUD.php");
include_once("../../../modelo/SQL_PHP/_Finanzas_Proveedor.php");
include_once("../../../modelo/SQL_PHP/_Utileria.php");


//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$idE = $_SESSION['udn'];
$date = $_POST['date'];


$row = null; $array = array($idE); $Pago = array(); $Deuda = array(); $Total = 0;
$query = "SELECT idUP,Name_Proveedor FROM hgpqgijw_finanzas.proveedor,hgpqgijw_finanzas.proveedor_udn,hgpqgijw_finanzas.compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND id_CG = 2 AND compras.id_UDN = ? GROUP BY Name_Proveedor";
$sql = $crud->_Select($query,$array,"5");
$id_array = array(); $Name_array = array(); $vuelta = 0;
foreach ($sql as $key => $value) {

  $array = array($idE,$value[0],$date);
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UDN = ? AND id_UP = ? AND Fecha_Compras <= ?";
  $sql_prov = $crud->_Select($query,$array,"5");
  foreach($sql_prov as $gasto_proveedor);
  if ( !isset($gasto_proveedor[0]) ) { $gasto_proveedor[0] = 0; }

  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UDN = ? AND id_UP = ? AND Fecha_Compras <= ?";
  $sql_prov = $crud->_Select($query,$array,"5");
  foreach($sql_prov as $pago_proveedor);
  if ( !isset($pago_proveedor[0]) ) { $pago_proveedor[0] = 0; }
  $Total_Deuda_proveedor = $gasto_proveedor[0] - $pago_proveedor[0];

  if ($Total_Deuda_proveedor != 0) {
    $id_array[$vuelta] = $value[0];
    $Name_array[$vuelta] = $value[1];
    $vuelta = $vuelta + 1;
  }
}

$array = array($idE,$date);
$query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UDN = ? AND Fecha_Compras <= ?";
$tsql = $crud->_Select($query,$array,"5");
foreach($tsql as $total_gasto);
if ( !isset($total_gasto[0]) ) { $total_gasto[0] = 0; }

$query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UDN = ? AND Fecha_Compras <= ? AND id_UP IS NOT NULL";
$tsql = $crud->_Select($query,$array,"5");
foreach($tsql as $total_pago);
if ( !isset($total_pago[0]) ) { $total_pago[0] = 0; }

$Total = $total_gasto[0] - $total_pago[0];

$Cont = count($id_array);
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">
    <h5><label> <?php echo "Acumulado de la Deuda: $ ".number_format($Total,2,'.',','); ?> </label></h5>
  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Proveedor"></div>
  <div class="col-sm-4 col-xs-12 text-right">
    <h5><label><?php echo "Proveedores activos: ".$Cont; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-6">Proveedores</td>
                  <td class="col-sm-2 text-center">Consumo actual</td>
                  <td class="col-sm-2 text-center">Pago actual</td>
                  <td class="col-sm-2 text-center">Deuda acumulada</td>
                  <!-- <td colspan="1">Opciones</td> -->
              </tr>
            </thead>
            <tbody>
              <?php
                for ($i = 0; $i < $Cont; $i++) {
                  $array = array($id_array[$i],$date);
                  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UP = ? AND Fecha_Compras <= ?";
                  $sql = $crud->_Select($query,$array,"5");
                  foreach($sql as $gasto);
                  if ( !isset($gasto[0]) ) { $gasto[0] = 0; }

                  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras <= ?";
                  $sql = $crud->_Select($query,$array,"5");
                  foreach($sql as $pago);
                  if ( !isset($pago[0]) ) { $pago[0] = 0; }

                  $Total_Deuda = 0;
                  $Total_Deuda = $gasto[0] - $pago[0];

                  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UP = ? AND Fecha_Compras = ?";
                  $sql = $crud->_Select($query,$array,"5");
                  foreach($sql as $DG);
                  if ( !isset($DG[0]) ) {
                    $DG[0] = 0;
                  }

                  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras = ?";
                  $sql = $crud->_Select($query,$array,"5");
                  foreach($sql as $DP);
                  if ( !isset($DP[0]) ) {
                    $DP[0] = 0;
                  }

              ?>
              <tr class='text-center'>
                <td class='col-sm-6 text-left'><?php echo $Name_array[$i]; ?></td>
                <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' value='<?php echo number_format($DG[0],2,'.',','); ?>' disabled>
                  </div>
                </td>
                <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' value='<?php echo number_format($DP[0],2,'.',','); ?>' disabled>
                  </div>
                </td>
                <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' value='<?php echo number_format($Total_Deuda,2,'.',','); ?>' disabled>
                  </div>
                </td>
              </tr>
            <?php } ?>
        </tbody>
    </table>
  </div>
</div>
