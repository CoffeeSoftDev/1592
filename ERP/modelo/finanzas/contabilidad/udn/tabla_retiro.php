<?php
include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$idE   = $_POST['udn'];
// ---

$array = array();
if($date1 == $date2){

 $array[0] = $idE;
 $array[1] = $date1;
 $array[2] = $idE;
 $array[3] = $date1;

 // ---


 $query1 =
 "SELECT Fecha_Rembolso
 FROM hgpqgijw_finanzas.retiros
 WHERE id_UDN = ? AND Fecha_Rembolso = ?
 UNION SELECT Fecha_Retiro FROM hgpqgijw_finanzas.retiros_venta
 WHERE id_UDN = ? AND Fecha_Retiro = ?
 ORDER BY Fecha_Rembolso ASC";
}
else{
 $array[0] = $idE;
 $array[1] = $date1;
 $array[2] = $date2;
 $array[3] = $idE;
 $array[4] = $date1;
 $array[5] = $date2;

 $query1 =
 "SELECT Fecha_Rembolso
 FROM hgpqgijw_finanzas.retiros
 WHERE id_UDN = ? AND Fecha_Rembolso BETWEEN ? AND ? UNION
 SELECT Fecha_Retiro FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ? ORDER BY Fecha_Rembolso ASC";
}

$sql1 = $crud->_Select($query1,$array,"5");
$fechas = array();
foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
$Count_Fechas = count($fechas);

$rembolso = $fin->Select_Total_Rembolso($idE,$date1,$date2);

$retiro_total = $fin->Select_Total_Retiros($idE,$date1,$date2);

$retiro_efectivo = $fin->Select_Total_efectivos($idE,$date1,$date2);

?>

<div class="row">
 <div class="col-sm-12 col-xs-12">
  <div class="scrolling outer">
   <div class="inner">



    <table class="table table-condensed table-bordered">

     <thead>
      <tr>
       <th  class="text-center">CONCEPTO</th>
       <td id="th" style=" text-align:center;">TOTAL</td>
       <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
        <td id="td" style="text-align:center;"><strong><?php echo $fechas[$j]; ?></strong></td>
       <?php } ?>
      </tr>
     </thead>


     <tbody>
      <tr>
       <th><strong>Rembolso</strong></th>
       <td id="th" class="text-right">

        <?php echo '$ '.number_format($rembolso,2,'.',', ');?> </td>


       <?php for ($j=0; $j < $Count_Fechas; $j++) {
        $rembolso_day = $fin->Select_Total_Rembolso($idE,$fechas[$j],$fechas[$j]);
        ?>
        <td id="td" class="text-right"><span title="Rembolso: "><?php echo '$ '.number_format($rembolso_day,2,'.',', ');?></span></td>
       <?php } ?>


      </tr>

      <tr>
       <th class="bg-info">-</th>
       <td id="th" class="bg-info">-</td>
       <?php for ($j=0; $j < $Count_Fechas; $j++) { ?>
        <td id="td" class="bg-info text-info">-</td>
       <?php } ?>
      </tr>

      <tr>
       <th><strong>Retiro Total</strong></th>
       <td id="th" class="text-right"><?php echo '$'.number_format($retiro_total,2,'.',', '); ?></td>
       <?php for ($j=0; $j < $Count_Fechas; $j++) {
        $retiro_day = $fin->Select_Total_Retiros($idE,$fechas[$j],$fechas[$j]);
        ?>
        <td id="td" class="text-right"><strong><?php echo '$'.number_format($retiro_day,2,'.',', '); ?></strong></td>
       <?php } ?>
      </tr>

      <tr>
       <th><strong>Efectivo</strong></th>
       <td id="th" class="text-right"><?php echo '$'.number_format($retiro_efectivo,2,'.',', ');?></td>
       <?php for ($j=0; $j < $Count_Fechas; $j++) {
        $efectivo_day = $fin->Select_Total_efectivos($idE,$fechas[$j],$fechas[$j]);
        ?>
        <td id="td" class="text-right"><strong><?php echo '$'.number_format($efectivo_day,2,'.',', ');?></strong></td>
       <?php } ?>
      </tr>




     </tbody>
    </table>
   </div>
  </div>
 </div>
</div>
