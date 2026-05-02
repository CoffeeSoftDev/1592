<?php
include_once('../../modelo/SQL_PHP/_METAS.php');

require '../../recursos/class/ChartJS.php';
require '../../recursos/class/ChartJS_Bar.php';
require '../../recursos/class/ChartJS_Line.php';

/*==========================================
*		MAIN
=============================================*/
$fi  = $_POST['f1'];
$ff  = $_POST['f2'];
$opc = $_POST['opc'];




switch ($opc) {
 case 1: echo VENTAS_AÑO($fi,$ff); break;
 case 2: echo ANUAL($fi,$ff); break;
 case 3: echo VENTAS_AÑO_LINE($fi,$ff); break;
}


function ANUAL($fi,$ff){
 $obj= new METAS;
 $nombres=  array('');
 $encode =  array('');
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
      // ----------------------
      $year_one   = array('');
      $ok  = $obj -> GRAFICA_AÑO($fi,$ff);
      // ----------------------
      foreach($ok as  $i => $data){
       $nombres[$i]  =$data[0];
       $encode[$i]   =$data[1];
      }
      // ----
      $array_values = array($encode);
      $Bar = new ChartJS_Bar('bar2', 800, 300);
      $Bar->addBars($array_values[0]);
      $Bar->addLabels($nombres);
      echo $Bar;
     }


     function VENTAS_AÑO($fi,$ff){
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

           $obj= new METAS;
           // ----------------------
           $year_one   = array('');
           $year_two   = array('');
           $year_trhee = array('');
           $year_four  = array('');
           $year_five  = array('');
           $contador =0;
           // -----------------------
           for ($j=$fi; $j <=$ff ; $j++) {      // RECORRIDO POR AÑOS
            $contador += 1;
            for ($mes=1; $mes <=12 ; $mes++) { // RECORRIDO POR MESES
             $ok  = $obj -> GRAFICA($mes,$j);

             switch ($contador) {
              case 1: $year_one[$mes]   = $ok;  break;
              case 2: $year_two[$mes]   = $ok;  break;
              case 3: $year_trhee[$mes] = $ok;  break;
              case 4: $year_four[$mes]  = $ok;  break;
              case 5: $year_five[$mes]  = $ok;  break;
             }
            }
           }
           // ------------------------
           $res=($ff-$fi)+1;

           $array_values = array($year_one,$year_two,$year_trhee,$year_four,$year_five );
           $array_labels = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");


           $Bar = new ChartJS_Bar('bar1', 800, 300);
           $Bar->addBars($array_values[0]);
           $Bar->addBars($array_values[1]);

           if ($res ==3) {
            $Bar->addBars($array_values[2]);
           }else if($res ==4){
            $Bar->addBars($array_values[2]);
            $Bar->addBars($array_values[3]);
           }else if ($res==5) {
            $Bar->addBars($array_values[2]);
            $Bar->addBars($array_values[3]);
            $Bar->addBars($array_values[4]);
           }

           $Bar->addLabels($array_labels);
           echo $Bar;

          }

          function VENTAS_AÑO_LINE($fi,$ff){
           $obj= new METAS;
           ChartJS::addDefaultColor(array(
            'fill'        => 'rgb(242, 178, 25,.1)',
            'stroke'      => '#e5801d',
            'point'       => '#e5801d',
            'pointStroke' => '#e5801d'));

            ChartJS::addDefaultColor(array(
             'fill' => 'rgba(28,116,190,.1)',
             'stroke' => '#1c74be',
             'point'       => '#1c74be',
             'pointStroke' => '#1c74be'));

             ChartJS::addDefaultColor(array(
              'fill'        => 'rgba(212,41,31,.1)',
              'stroke'      => '#d4291f',
              'point'       => '#d4291f',
              'pointStroke' => '#d4291f'));

              ChartJS::addDefaultColor(array(
               'fill'         => 'rgba(46,204,113,.1)',
               'stroke'       => '#2ecc71',
               'point'        => '#2ecc71',
               'pointStroke'  => '#2ecc71'));

               ChartJS::addDefaultColor(array(
                'fill'        => '#dc693c',
                'stroke'      => '#ff0000',
                'point'       => '#ff0000',
                'pointStroke' => '#ff0000'));
                // ----------------------
                $year_one   = array('');
                $year_two   = array('');
                $year_trhee = array('');
                $year_four  = array('');
                $year_five  = array('');
                $contador =0;
                // -----------------------
                for ($j=$fi; $j <=$ff ; $j++) {      // RECORRIDO POR AÑOS
                 $contador += 1;
                 for ($mes=1; $mes <=12 ; $mes++) { // RECORRIDO POR MESES
                  $ok  = $obj -> GRAFICA($mes,$j);

                  switch ($contador) {
                   case 1: $year_one[$mes]   = $ok;  break;
                   case 2: $year_two[$mes]   = $ok;  break;
                   case 3: $year_trhee[$mes] = $ok;  break;
                   case 4: $year_four[$mes]  = $ok;  break;
                   case 5: $year_five[$mes]  = $ok;  break;
                  }
                 }
                }
                // ------------------------
                $res=($ff-$fi)+1;

                $array_values = array($year_one,$year_two,$year_trhee,$year_four,$year_five );
                $array_labels = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");


                $Bar = new ChartJS_Line('bar3', 800, 300);
                $Bar->addLine($array_values[0]);
                $Bar->addLine($array_values[1]);

                if ($res ==3) {
                 $Bar->addLine($array_values[2]);
                }else if($res ==4){
                 $Bar->addLine($array_values[2]);
                 $Bar->addLine($array_values[3]);
                }else if ($res==5) {
                 $Bar->addLine($array_values[2]);
                 $Bar->addLine($array_values[3]);
                 $Bar->addLine($array_values[4]);
                }

                $Bar->addLabels($array_labels);
                echo $Bar;

               }



               ?>
