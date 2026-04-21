<?php
include_once("../../modelo/SQL_PHP/_METAS.php"); // <---


require '../../recursos/class/ChartJS.php';
require '../../recursos/class/ChartJS_Bar.php';
require '../../recursos/class/ChartJS_Pie.php';
/*==========================================
*		MAIN
=============================================*/

$fi   = $_POST['anio1'];
$ff   = $_POST['anio2'];
// $fi   = '2016';
// $ff   = '2017';
$opc = $_POST['opc'];
// $opc = 2;
ChartJS::addDefaultColor(array(
 'fill'        => '#f2b21a',
 'stroke'      => '#e5801d',
 'point'       => '#e5801d',
 'pointStroke' => '#e5801d'));

 ChartJS::addDefaultColor(array(
  'fill' => 'rgba(28,116,190,.8)',
  'stroke' => '#1c74be',
  'point'       => '#1c74be',
  'pointStroke' => '#1c74be'));

  ChartJS::addDefaultColor(array(
   'fill'        => 'rgba(212,41,31,.7)',
   'stroke'      => '#d4291f',
   'point'       => '#d4291f',
   'pointStroke' => '#d4291f'));

   ChartJS::addDefaultColor(array(
    'fill'         => 'rgba(46,204,113,.8)',
    'stroke'       => '#2ecc71',
    'point'        => '#2ecc71',
    'pointStroke'  => '#2ecc71'));

    ChartJS::addDefaultColor(array(
     'fill'        => '#dc693c',
     'stroke'      => '#ff0000',
     'point'       => '#ff0000',
     'pointStroke' => '#ff0000'));


     switch ($opc) {
      case 1: echo TOTAL_X_AÑOS($fi,$ff);    break;
      case 2: echo TOTAL_X_AREAS($fi,$ff);   break;
      case 3: echo VENTAS_AÑO($fi,$ff);    break;

     }


     function TOTAL_X_AÑOS($fi,$ff){
      $obj = new METAS; // <--
      // CATEGORIAS

      $categoria = $obj ->VER_CATEGORIAS();
      $labels    =  array('');
      $year_one   = array('');
      $year_two   = array('');
      $year_trhee = array('');
      $year_four  = array('');
      $year_five  = array('');
      $contador =0;

      foreach ($categoria as $i => $data) {
       $labels[$i]  =$data[1];
      }

      // ----------------------------------
      for ($i=$fi; $i <= $ff; $i++) { // RECORRIDO POR AÑOS
       $contador += 1;
       foreach ($categoria as  $j => $data) { // RECORRIDO POR CATEGORIA ANUAL
        $ok  = $obj -> GRAFICAxAREA($data[0],$i);
        switch ($contador) {
         case 1: $year_one[$j]    = $ok;  break;
         case 2: $year_two[$j]    = $ok;  break;
         case 3: $year_trhee[$j]  = $ok;  break;
         case 4: $year_four[$j]   = $ok;  break;
         case 5: $year_five[$j]   = $ok;  break;
        }
       }
      } // ./ End Recorrido años

      $array_values = array($year_one,$year_two,$year_trhee,$year_four,$year_five );
      // ----------------------------------
      $res=($ff-$fi)+1;

      $Bar = new ChartJS_Bar('a1', 750, 300);
      $Bar->addBars($array_values[0]);
      if ($res ==2) {
       $Bar->addBars($array_values[1]);
      }else  if ($res ==3) {
       $Bar->addBars($array_values[1]);
       $Bar->addBars($array_values[2]);
      }else if($res ==4){
       $Bar->addBars($array_values[2]);
       $Bar->addBars($array_values[3]);
      }else if ($res==5) {
       $Bar->addBars($array_values[2]);
       $Bar->addBars($array_values[3]);
       $Bar->addBars($array_values[4]);
      }

      $Bar->addLabels($labels);

      echo $Bar;

     }// End Function


     /*==========================================
     *	TOTAL POR AREAS
     =============================================*/

     function TOTAL_X_AREAS($fi,$ff){
      $obj = new METAS; // <--
      // CATEGORIAS

      $categoria = $obj ->VER_CATEGORIAS();
      $labels    =  array('');
      $year_one   = array('');
      $year_two   = array('');
      $year_trhee = array('');
      $year_four  = array('');
      $year_five  = array('');
      $contador =0;
      $contador2 =0;

      for ($i=$fi;    $i <=  $ff;  $i++) { // RECORRIDO POR AÑOS
       $labels[$contador]  = $i;
       $contador += 1;

      }
      // ----------------------------|
      for ($i=$fi;    $i <=  $ff;  $i++) { // RECORRIDO POR AÑOS

       foreach ($categoria as $j => $data) {// RECORRIDO POR CATEGORIA
        $ok  = $obj -> GRAFICAxAREA($data[0],$i);
        $contador2 += 1;
        switch ($contador2) {
         case 1: $year_one[$j]    = $ok;           break;
         case 2: $year_two[$j]    = $ok;           break;
         case 3: $year_trhee[$j]  = $ok;           break;
         case 4: $year_four[$j]   = $ok;           break;
         case 5: $year_five[$j]   = $ok;           break;
        }

       }

      }

      // ----------------------------------

      $array_values = array($year_one,$year_two,$year_trhee,$year_four,$year_five );
      // ----------------------------------
      $res=($ff-$fi)+1;

      $Bar = new ChartJS_Bar('a2', 500, 300);
      $Bar->addBars($array_values[0]);
      if ($res ==2) {
       $Bar->addBars($array_values[1]);
      }else  if ($res ==3) {
       $Bar->addBars($array_values[1]);
       $Bar->addBars($array_values[2]);
      }else if($res ==4){
       $Bar->addBars($array_values[2]);
       $Bar->addBars($array_values[3]);
      }else if ($res==5) {
       $Bar->addBars($array_values[2]);
       $Bar->addBars($array_values[3]);
       $Bar->addBars($array_values[4]);
      }

      $Bar->addLabels($labels);
      echo $Bar;

     }
     /*==========================================
     *	TOTAL GLOBAL POR AÑOS
     =============================================*/

     function VENTAS_AÑO($fi,$ff){
      // $colors=  array('#ED5565','#4FC1E9','#4FC1E9','#4FC1E9','#4FC1E9');
      //
      // $obj= new METAS;
      // $label =  array('');
      // $datax  =  array('');
      // // ----------------------
      // // $categoria = $obj ->VER_CATEGORIAS();
      // $Pie = new ChartJS_Pie('a3', 600, 300);
      //
      // $categoria = $obj ->VENTAS_x_AÑO($fi,$ff);
      // foreach ($categoria as $j => $data) {// RECORRIDO POR CATEGORIA
      //  $label[$j]  =$data[0];
      //  $datax[$j]  =$data[1];
      //
      //
      //
      // }
      // $array =  array(0=>$label,1=> $datax,2=>$colors);
      // print json_encode($array);
      //


     } // End Function
     ?>
