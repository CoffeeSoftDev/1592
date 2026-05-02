<?php
session_start();
include_once("../../modelo/SQL_PHP/_MTTO.php");

$obj	=	new	MTTO;

$cat   = $_GET['categoria'];
$area  = $_GET['area'];
$opc   = $_GET['opc'];
$txt='';

?>

<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <title>Formato de impresión</title>

 <link rel="stylesheet" href="../css/formato.css">
 <link rel="stylesheet" href="../css/bootstrap/bootstrap.min.css">
 <script type="text/javascript">
 function imprimir() {
  if (window.print) {
   window.print();
  }
  else {
   alert("La función de impresión no esta disponible en este navegador, intentelo con otro diferente.");
  }
 }
 </script>


 <style type="text/css" media="print">
 @page{
  margin-top:  20px;
  margin-bottom:   20px;
  margin-left:   20px;
  margin-right:    30px;
 }

 </style>
 <style>
 .table-bordered {
  border: 1px solid #000000;
 }
 .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
  padding: 2px;
  line-height: 1.32857143;
  vertical-align: top;
  border-top: 1.4px solid #ecf0f1;
  /* border: 1px solid #000000; */
 }
 </style>



</head>


<body onload="imprimir();">

 <?php
 /* -------------------------------------
 *         DATA - AREA
 --------------------------------------- */

 $txt     = $txt.'
 <div class="row">
 <div class="col-xs-offset-4 col-xs-4 text-center ">
 <h3><strong>  FOLIO DE EQUIPOS </strong> </h3>
 </div>


 </div>';


 if($area=='0' && $cat=='0'){
  $array = array('', '', $opc);
  $folio = ALL_FOLIOS($array);
  $txt   = $txt.$folio;

 }else if($area!='0' && $cat=='0'){

  $array = array('', $area, $opc);
  $folio = ONLY_AREA($array);
  $txt   = $txt.$folio;


 }
 else if($area=='0' && $cat!='0'){
  $array = array($cat, '', $opc);
  $folio = ONLY_CAT($array);
  $txt   = $txt.$folio;
 }
 else if($area!='0' && $cat!='0'){
  $array = array($cat, $area, $opc);
  $folio = CAT_AREA($array);
  $txt   = $txt.$folio;


 }







 $txt= $txt.'</tbody></table>';

 // ===========================================
 //     PRINT
 // ===========================================
 echo $txt;
 ?>

</body>
</html>
<?php

/*===========================================
*				Funciones php
=============================================*/
function ALL_FOLIOS($data){
 $txt     = '';
 $obj	    =	new	MTTO;
 $cat     = $obj ->dataCat();
 $contar  = 0;
 $view	=	$obj	->	Show_DATA($data[0],$data[1],$data[2]);
 $txt     = $txt.'
 <div class="row">
 <div class="col-xs-12  text-right">
 <h5><strong> EQUIPOS & MATERIALES: '.count($view).' </strong> </h5>
 </div>
 </div>
 <br><table class="table table-bordered"><tbody>

 ';
 foreach ($cat as $a ) {
  $view	=	$obj	->	Show_DATA($a[0],$data[1],$data[2]);

  $txt= $txt.'
  <tr><td class="" colspan="6"><strong>'.$a[1].'</strong> [ '.count($view).' ]</td></tr>';

  /* Recorrido por categoria  */
  foreach ($view as $key ) {
   if($contar==0){
    $txt= $txt.'<tr>';
   }

   $contar += 1;

   $txt= $txt.'
   <td class="col-xs-2 col-sm-2">
   <p>
   <div style="border-color: #92a8d1; padding-bottom:5px padding-top:100px"  class="col-md-12 text-center">
   <p style="font-size:7px">'.$key[1].'</p>
   <img  style="max-width:80px; height:20px"  src="http://www.argovia.com.mx/img/logox.png">
   <p><strong>'.$key[0].'</strong></p>
   </div>
   </td>';


   if ($contar==4) {
    $txt= $txt.' </tr>';
    $contar = 0;
   }

  }


 } // END FOREACH CAT


 return $txt;
}

function ONLY_AREA($data){
 $obj	    =	new	MTTO;
 $txt     = '';
 $view	   =	$obj	->	Show_DATA($data[0],$data[1],$data[2]);
 $contar  = 0;

 $area	   =	$obj	->	NOMBRE_AREA($data[1]);
 $cont    = count($view);
 $txt     = $txt.'
 <div class="row">
 <div class="col-xs-12  text-right">
 <h5><strong> EQUIPOS & MATERIALES: '.$cont.' </strong> </h5>
 </div>
 </div>
 <br><table class="table table-bordered"><tbody>
 <tr >
 <td colspan="4" class="text-center"><strong>'.$area.'</strong></td>
 </tr>';


 foreach ($view as $key) {
  if($contar==0){
   $txt= $txt.'<tr>';
  }

  $contar += 1;

  $txt= $txt.'
  <td class="col-xs-2 col-sm-2">
  <p>
  <div style="border-color: #92a8d1; padding-bottom:5px padding-top:100px"  class="col-md-12 text-center">
  <p style="font-size:7px">'.$key[1].'</p>
  <img  style="max-width:80px; height:20px"  src="http://www.argovia.com.mx/img/logox.png">
  <p><strong>'.$key[0].'</strong></p>
  </div>
  </td>';


  if ($contar==4) {
   $txt= $txt.' </tr>';
   $contar = 0;
  }


 } // END FOR EACH

 return $txt;
}

function ONLY_CAT($data){
 $obj	    =	new	MTTO;
 $txt     = '';
 $view	   =	$obj	->	Show_DATA($data[0],$data[1],$data[2]);
 $contar  = 0;

 $area	   =	$obj	->	NOMBRE_CAT($data[0]);
 $cont    = count($view);
 $txt     = $txt.'
 <div class="row">
 <div class="col-xs-12  text-right">
 <h5><strong> EQUIPOS & MATERIALES: '.$cont.' </strong> </h5>
 </div>
 </div>
 <br><table class="table table-bordered"><tbody>
 <tr >
 <td colspan="4" class="text-center"><strong>'.$area.' </strong></td>
 </tr>';


 foreach ($view as $key) {
  if($contar==0){
   $txt= $txt.'<tr>';
  }

  $contar += 1;

  $txt= $txt.'
  <td class="col-xs-2 col-sm-2">
  <p>
  <div style="border-color: #92a8d1; padding-bottom:5px padding-top:100px"  class="col-md-12 text-center">
  <p style="font-size:7px">'.$key[1].'</p>
  <img  style="max-width:80px; height:20px"  src="http://www.argovia.com.mx/img/logox.png">
  <p><strong>'.$key[0].'</strong></p>
  </div>
  </td>';


  if ($contar==4) {
   $txt= $txt.' </tr>';
   $contar = 0;
  }


 } // END FOR EACH

 return $txt;
}

function CAT_AREA($data){
 $obj	    =	new	MTTO;
 $txt     = '';
 $view	   =	$obj	->	Show_DATA($data[0],$data[1],$data[2]);
 $contar  = 0;

 $cat 	   =	$obj	->	NOMBRE_CAT($data[0]);
 $area	   =	$obj	->	NOMBRE_AREA($data[0]);
 $cont    = count($view);
 $txt     = $txt.'
 <div class="row">
 <div class="col-xs-12  text-right">
 <h5><strong> EQUIPOS & MATERIALES: '.$cont.' </strong> </h5>
 </div>
 </div>
 <br><table class="table table-bordered"><tbody>
 <tr >
 <td colspan="4" class="text-center"><strong>'.$cat.' / '.$area.' </strong></td>
 </tr>';


 foreach ($view as $key) {
  if($contar==0){
   $txt= $txt.'<tr>';
  }

  $contar += 1;

  $txt= $txt.'
  <td class="col-xs-2 col-sm-2">
  <p>
  <div style="border-color: #92a8d1; padding-bottom:5px padding-top:100px"  class="col-md-12 text-center">
  <p style="font-size:7px">'.$key[1].'</p>
  <img  style="max-width:80px; height:20px"  src="http://www.argovia.com.mx/img/logox.png">
  <p><strong>'.$key[0].'</strong></p>
  </div>
  </td>';


  if ($contar==4) {
   $txt= $txt.' </tr>';
   $contar = 0;
  }


 } // END FOR EACH

 return $txt;
}




?>
