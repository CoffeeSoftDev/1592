<?php
include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

$crud         = new CRUD;
$util         = new Util;
$fin          = new Finanzas;
// -----------------------------

$date1        = $_POST['date1'];
$date2        = $_POST['date2'];
$idE          = $_POST['udn'];
$bg_categoria = "#ECF0F1";
$id_Clase     = $_POST['clase'];
$busqueda     = $_POST['busqueda'];
$search       = "";
/*-----------------------------------*/
/* SQL - QUERYS
/*-----------------------------------*/

$array = array($idE,$date1,$date2);
$id_CG = ""; // 3

if ($id_Clase==0) {
 $id_CG="";
}else {
 $id_CG = "and id_CG = ".$id_Clase." "; // 3
}

/*  QUERY LIKE  */

if ($busqueda!="") {
$search       = "AND Name_Gastos LIKE '%".$busqueda."%' ";
}




$query1 =
"SELECT
Fecha_Compras
FROM
hgpqgijw_finanzas.compras
WHERE
STATUS = 1
".$id_CG."
AND id_UDN = ?
AND Fecha_Compras BETWEEN ?
AND ? and factura is null
GROUP BY Fecha_Compras";

// ---

$query2 = "SELECT
ROUND( SUM( Gasto ), 2 )
FROM
hgpqgijw_finanzas.compras,
hgpqgijw_finanzas.gastos_udn,
hgpqgijw_finanzas.gastos
WHERE
compras.id_UDN = ?
AND idUG = id_UG
AND id_Gastos = idGastos
".$search."
".$id_CG."
and factura is null
AND Fecha_Compras BETWEEN ?
AND ?";

/*-- OBTENER LA CLASE DE GASTO ---*/

$query3 = "SELECT
idUI,
Name_IC,
ROUND( SUM( Gasto ), 2 ) AS Saldo
FROM
hgpqgijw_finanzas.insumos_clase,
hgpqgijw_finanzas.insumos_udn,
hgpqgijw_finanzas.compras,
hgpqgijw_finanzas.gastos_udn,
hgpqgijw_finanzas.gastos

WHERE
idIC = id_IC
AND idUI = id_UI
AND idUG = id_UG
AND id_Gastos = idGastos

".$search."
".$id_CG."
and factura is null
AND insumos_udn.id_UDN = ?
AND Fecha_Compras BETWEEN ? AND ?
GROUP BY
Name_IC
ORDER BY
Saldo DESC";

//Fechas ----------------------------------------------------------------
$sql1 = $crud->_Select($query1,$array,"5");
$fechas = array();
foreach ($sql1 as $key => $data_fecha) { $fechas[$key] = $data_fecha[0]; }
$Count_Fechas = count($fechas);

//Suma Total de todos los días --------------------------------------------
$sql2 = $crud->_Select($query2,$array,"5");
$Sum = null;
foreach($sql2 as $Sum);
if( !isset($Sum[0]) ) { $Sum[0] = 0; }

//idUG Y Name_Gastos ----------------------------------------------------------
$sql3 = $crud->_Select($query3,$array,"5");

$Nombres = array();
$idUG = array();
$Cantidad = array();

foreach ($sql3 as $key => $data_name) {
 $idUG[$key] = $data_name[0];
 $Nombres[$key] = $data_name[1];
 $Cantidad[$key] = $data_name[2];
}


$Count_Name = count($Nombres);
// echo $id_Clase;
?>
<style media="screen">
.scrolling #td{
 /* height:60px; */
}
</style>

<!--  -->
<div class="row">
 <div class="col-sm-12 col-xs-12">
  <div class="scrolling outer">
   <div class="inner">
    <table class="table table-condensed table-bordered" id="tbGastos">

     <thead>
      <tr>
       <th class="text-center" style="border-top:1px solid #ecf0f1">TOTAL DE GASTOS</th>

       <?php
       if($Sum[0] == 0){ $dato = '-'; } else { $dato = '$ '.number_format($Sum[0],2,'.',', '); }
       echo '<td id="th" class="text-right" style="border-top:1px solid #ecf0f1" >'.$dato.'</td>';


       for ($j=0; $j < $Count_Fechas; $j++) {
        $array = array($idE,$fechas[$j],$id_Clase);
        $dato = $fin->_SUMA_GASTOS($array,$search);
        if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }
        echo '<td id="td" class="text-right"><strong>'.$dato.'</strong></td>';
       }

       ?>
      </tr>
     </thead>


     <tbody>

      <tr> <!-- Sub-->
       <th  class="text-center bg-danger">GASTOS </th>
       <td id="th" class="bg-danger text-center">TOTAL</td>
       <?php
       for ($i=0; $i < $Count_Fechas; $i++) {
        echo '<td id="td" class="bg-info text-center" ><strong>'.$fechas[$i].'</strong></td>';
       }
       ?>
      </tr>


      <?php

      /*  Todos los gastos que se encuentran dentro del rango de fechas  */

      for ($i=0; $i < $Count_Name; $i++) {
       $array   = array($idUG[$i],$idE,$date1,$date2);

       $q_gasto =
       "SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) as Saldo
       FROM hgpqgijw_finanzas.gastos,
       hgpqgijw_finanzas.gastos_udn,
       hgpqgijw_finanzas.compras
       WHERE idGastos = id_Gastos AND
       idUG = id_UG
       ".$search."
       ".$id_CG." AND
       id_UI = ? AND
       compras.id_UDN = ? AND
       Fecha_Compras BETWEEN ? AND ? and factura is null
       GROUP BY Name_Gastos ORDER BY Saldo DESC";

       $sql_qg = $crud->_Select($q_gasto,$array,"5");



       echo
       '<tr style="background:'.$bg_categoria.'; cursor:pointer;" onClick="gastos_detalles('.$Count_Name.','.$i.');">
       <th style=" background:'.$bg_categoria.'; cursor:pointer; " >
       <span class="Name_Cat'.$i.' icon-right-dir"></span> <span style="font-size: 1rem;">'.$Nombres[$i].'</span></th>
       <td id="th" class="text-right" style=" background:'.$bg_categoria.';
       cursor:pointer;" ">$ '.number_format($Cantidad[$i],2,'.',', ').'</td>';

       for ($j=0; $j < $Count_Fechas; $j++) {
        $array = array($idUG[$i],$fechas[$j],$id_Clase);

        $dato = $fin->_Data_Hoy_Gastos_Categoria($array,$search);

        if($dato == 0){ $dato = '-'; } else { $dato = '$ '.number_format($dato,2,'.',', '); }

        echo '<td id="td" class="text-right " >'.$dato.'</td>';
       }
       echo '</tr>';



       foreach ($sql_qg as $value) {
        echo '
        <tr class="GD_'.$i.' hide ">
        <th ><span  style="font-size:1rem;">'.$value[1].'</span></th>
        <td id="th" class="text-right">$ '.number_format($value[2],2,'.',', ').'</td>';

        for ($j=0; $j < $Count_Fechas; $j++) {
         $array = array($value[0],$fechas[$j],$id_Clase);
         $dato  = $fin->_Data_Hoy_Gastos($array,$search);

         echo '<td id="td" onclick="verInfo('.$dato[2].')" class="text-right" > '.evaluar($dato[0]).'</td>';
        }

        echo  '</tr>';
       } // end foreach


      }
      ?>
     </tbody>
    </table>
   </div>
  </div>
 </div>
</div>


<?php
/*==========================================
*		FUNCIONES / FORMULAS
=============================================*/

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }

 return $res;
}


?>
