<?php
include_once("../../modelo/SQL_PHP/_METAS.php"); // <---
$obj= new METAS;

$ff = $_POST['ff'];
$fi = $_POST['fi'];
$opc =$_POST['opc'];
$udn  = 1;
// ==================+

switch($opc) {

 case 1: echo bar($fi,$ff,$opc); break;
 case 2: echo bar_año($fi,$ff,$udn); break;

}


function bar_año($fi,$ff,$udn){


 $obj             = new METAS;
 $categoria       = $obj ->VER_CATEGORIAS($udn);
 $axis  = 0;
 $axisX = 0;
 $res             =($ff-$fi)+1;

 $ContarAÑOS      = 0;

 // --------------------
 $año1           = 0;
 $año2           = 0;
 $año3           = 0;
 $año4           = 0;
 $año5           = 0;
 // --------------------
 $AllCategory      = array('YEARS');


 foreach ($categoria as  $i => $data) { // RECORRIDO POR CATEGORIAS
  $axis += 1;
  $AllCategory[$axis]  = $data[1];
 }

 $datax[] = $AllCategory;

 //
 for ($i=$fi; $i <= $ff; $i++) { // RECORRIDO POR AÑOS
 //
  $all    = array($i);
  $axisX  = 0;
  foreach ($categoria as  $j => $data) {
   $ventas = $obj -> VENTAS_AÑO($i,$data[0]);
   $axisX        += 1;
   $all[$axisX]   = floatval($ventas);

  } // id_cat
 //
 //
  $datax[] = $all;
  $all     = null;
 }// end FECHA



 echo json_encode($datax);


}






// ------------------------------------------

function bar($fi,$ff) {

 $obj             = new METAS;
 $categoria       = $obj ->VER_CATEGORIAS();
 $AñosExistentes  = 0;
 $res             =($ff-$fi)+1;

 $ContarAÑOS      = 0;

 // --------------------
 $año1           = 0;
 $año2           = 0;
 $año3           = 0;
 $año4           = 0;
 $año5           = 0;
 // --------------------

 switch ($res) {
  case 1:  $datax[]  = array('categoria',$fi);                            break;
  case 2:  $datax[]  = array('categoria',$fi,$ff);                        break;
  case 3:  $datax[]  = array('categoria',$fi,'2016',$ff);                 break;
  case 4:  $datax[]  = array('categoria',$fi,'2016','2017',$ff);          break;
  case 5:  $datax[]  = array('categoria',$fi,'2016','2017','2018',$ff);   break;

 }

 foreach ($categoria as  $j => $data) { // RECORRIDO POR CATEGORIAS
  $ContarAÑOS=0;
  for ($i=$fi; $i <= $ff; $i++) { // RECORRIDO POR AÑOS
   $ContarAÑOS +=  1;
   $YEAR_DATA  = $obj -> GRAFICAxAREA($data[0],$i);

   switch ($ContarAÑOS) {
    case 1:   $año1 =  floatval($YEAR_DATA);     break;
    case 2:   $año2 =  floatval($YEAR_DATA);     break;
    case 3:   $año3 =  floatval($YEAR_DATA);     break;
    case 4:   $año4 =  floatval($YEAR_DATA);     break;
    case 5:   $año5 =  floatval($YEAR_DATA);     break;
   }


  }// End Recorrido años


  switch ($res) {
   case 1:    $datax[] = array($data[1],$año1);           break;
   case 2:    $datax[] = array($data[1],$año1,$año2);     break;
   case 3:    $datax[] = array($data[1],$año1,$año2,$año3);     break;
  }

 } // End recorrido por categorias

 echo json_encode($datax);

}

?>
