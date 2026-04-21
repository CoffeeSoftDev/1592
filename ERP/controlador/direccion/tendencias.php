<?php
$udn   = $_SESSION['udn'];
include_once('../../modelo/SQL_PHP/_INGRESOS.php');
$obj = new INGRESOS;

include_once('../../modelo/SQL_PHP/_INGRESOS_TURISMO.php');
$tur  = new TURISMO;

include_once('../../modelo/complementos.php');
$com = new Complementos;

$opc  = $_POST['opc'];
$json = '';
# Libreria de graficos
require '../../recursos/class/ChartJS.php';
require '../../recursos/class/ChartJS_Bar.php';
require '../../recursos/class/ChartJS_Line.php';

switch ($opc) {

 case 1: // Tendencias
  $frm     = __FORMATO_BALANZAS($obj,$tur,$com,$udn);
  $json    = array(0=>$frm[0]);
 break;

 case 2: // Tendencias
 $frm     = __FORMATO_BALANZAS($obj,$tur,$com,$udn);
 $json    = $frm[1];
 break;

 case 3: // Comparativa por mes
  $titulo = '
  <div style="font-weight:500; height:40px; width:100%; padding-top: 10px; margin-bottom:10px;" class="text-center col" > Comparitiva mensual '.$anio.'</div>
  ';
  $frm     = ___FORMATO_MES($obj,$tur,$com,$udn);

  $json    = array(0=>$frm,1=>$titulo);
 break;

 case 4: // Cheque promedio
 $frm     = Cheque_promedio($obj,$tur);
 $json    = array(0=>$frm);
 break;

 case 5: //
  $frm     = reporte_conta($obj,$tur);
  $json    = array(0=>$frm);
 break;

}

/* JSON  ENCODE */
echo json_encode($json);
#------------------------------------#
# Reporte conta
#------------------------------------#
function reporte_conta($obj,$tur){

  $Ocupacion          = 0;
  $FechaPromedio  = $_POST['FechaPromedio'];
  $V =   $dias_transcurridos * 15;

  $fi   = '2022-11-01';
  $ff   = '2022-11-01';

  $dias_transcurridos = dias_transcurridos($fi,$ff);

  $frm  = '<div style="padding-top:10px;" class="mt-20 table-responsive">';
  $frm .= '<table style="font-size:.87em; font-weight:500;" class="table table-bordered">';

  $frm .= '<thead>
   <tr>
   <td colspan="2" class="text-center">REPORTE   '.$FechaPromedio.' </td>
   </tr>

  <tr>
  <td>Días</td>
  <td class="text-center">'.$dias_transcurridos.'</td>
  </tr>

  <tr>
  <td >No.Cuartos</td>
  <td class="text-center">15</td>
  </tr>

  <tr>
  <td >Cuartos Ocupados</td>
  <td class="text-center"></td>
  </tr>

  <tr>
  <td >Cuartos Disponibles</td>
  <td class="text-center">'.$V.'</td>
  </tr>

  <tr>
  <td > % de Ocupación de Cuartos</td>
  <td class="text-center"></td>
  </tr>

  <tr>
  <td >ADR Promedio</td>
  <td class="text-center"></td>
  </tr>

  <tr>
  <td >RevPar Promedio</td>
  <td class="text-center"></td>
  </tr>

  </thead>';


   $cat = Desgloze_area($obj,$tur,$com,1,$fi,$ff);
   $frm  .= $cat[0];

   $frm  .= '</table>';
   $frm   .= '</div>';

 return $frm;
}

#------------------------------------#
# Desgloze de reporte
#------------------------------------#
function Desgloze_area($obj,$tur,$com,$estado,$fi,$ff){

  $sql  = $obj -> VER_CATEGORIAS_INGRESOS(1);
  $tb = '';

 foreach($sql as $KEY){ // Consulta de categoria

   $subgrupo = _FOLD_SUBGRUPOS($tur,$KEY[0],$fecha_i,$fecha_f);


   $tb   .= '<tr style="font-weight:600;" class="bg-default">';
   $tb   .= '<td> ('.$KEY[0].') '.$KEY[1].'</td>';
   $tb   .= '<td class="text-right">'.evaluar($fold1[1]).'</td>';
   $tb   .= '</tr>';

   $tb   .= $subgrupo[0];




   // $tb   .= __Subgrupos($tur,$com,$KEY[0],$fold1[1],$TOTAL_GRAL_2);

 }

  $data = array(0=>$tb);
  return $data;

}




#------------------------------------#
# Cheque promedio
#------------------------------------#




function Cheque_promedio($obj,$tur){
 $frm      = '<div class="table-responsive">';
 $data     = '';
 # Head ----------
 $frm .= '<table id="ChequePromedio" class="table table-bordered table-xtra-condensed"  style="font-size:..7em; " >';
 $frm .= '<thead><tr><th class="col-sm-3">'.$_POST['FechaPromedio'].'</th>';

 for ($x=1; $x <=12 ; $x++) {
  $frm .= '<th colspan="3" class="text-center">'.__MES($x).'</th>';
 }

 $frm .= '</tr><tr><th></th>';
 for ($x=1; $x <=12 ; $x++) {
  $frm .= '<th>Total</th>';
  $frm .= '<th>Ocup</th>';
  $frm .= '<th>Cheque prom.</th>';
 }


 $frm .= '</tr></thead><tbody>';

 # Body --------------
 $sub       = $obj ->Select_group(array(1));

 $total     = array();
 $ocup      = array();
 $prom      = array();

 for ($j=0; $j < 12 ; $j++) {
  $total[$j]  = 0;
  $ocup[$j]   = 0;
  $prom[$j]   = 0;
 }


 foreach($sub as $_KEY){
  $sql    = $tur ->Select_Subcategoria_x_grupo(1,$_KEY[0]);

  foreach($sql as $key => $value){// Recorrido por habitaciones

   $frm .= '<tr>';
   $frm .= '<td >'.$value[1].'</td>';

   for ($i=1; $i <=12 ; $i++) { // Recorrido mensual

    $cheque  = __ChequePromedio($obj,$tur,$value[0],$i);

    $frm .= '
    <td class="text-right">'.evaluar($cheque[2]).'</td>
    <td class="text-center">'.$cheque[1].'</td>
    <td class="text-right">'.evaluar($cheque[3]).'</td>';



    switch ($i) {
     case 1:  $ocup[0] += $cheque[1];  $prom[0] += $cheque[3];  $total[0]   +=   $cheque[2] ;   break;
     case 2:  $ocup[1] += $cheque[1];  $prom[1] += $cheque[3];  $total[1]   +=   $cheque[2] ;   break;
     case 3:  $ocup[2] += $cheque[1];  $prom[2] += $cheque[3];  $total[2]   +=   $cheque[2] ;   break;
     case 4:  $ocup[3] += $cheque[1];  $prom[3] += $cheque[3];  $total[3]   +=   $cheque[2] ;   break;
     case 5:  $ocup[4] += $cheque[1];  $prom[4] += $cheque[3];  $total[4]   +=   $cheque[2] ;   break;
     case 6:  $ocup[5] += $cheque[1];  $prom[5] += $cheque[3];  $total[5]   +=   $cheque[2] ;   break;
     case 7:  $ocup[6] += $cheque[1];  $prom[6]   += $cheque[3];  $total[6]   +=   $cheque[2] ;   break;
     case 8:  $ocup[7] += $cheque[1];  $prom[7]   += $cheque[3];  $total[7]   +=   $cheque[2] ;   break;
     case 9:  $ocup[8]  += $cheque[1];  $prom[8]   += $cheque[3];  $total[8]   +=   $cheque[2] ;   break;
     case 10: $ocup[9]  += $cheque[1];  $prom[9]   += $cheque[3];  $total[9]   +=   $cheque[2] ;   break;
     case 11: $ocup[10] += $cheque[1];  $prom[10] += $cheque[3];  $total[10]  +=   $cheque[2] ;   break;
     case 12: $ocup[11] += $cheque[1];  $prom[11] += $cheque[3];  $total[11]  +=   $cheque[2] ;   break;

    }

    // $frm .= '</td>';

   }// end recorrido

   $frm .= '</tr>';

  }//end recorrido
 }

 # Total General

 $frm   .= '</tbody>';
 $frm   .= '<tfoot><tr class="bg-info-light">';
 $frm   .= '<th>Ocupación</th>';


 for ($i=0; $i < count($total) ; $i++) {
  $frm   .= '<th class="text-center">'.evaluar($total[$i]).'</th>';
  $frm   .= '<th class="text-center">'.evaluar2($ocup[$i]).'</th>';
  $frm   .= '<th class="text-center">'.evaluar($prom[$i]).'</th>';
 }

 $frm   .= '</tr></tfoot>';




 $frm   .= '</table></div>';

 $data .= '
 <div class="row">
 <div class="mt-10 col-sm-12 col-xs-12">'.$frm.'</div>

 </div>
 ';

 // echo $frm;




 return $data;
}

#============================
# Cheque promedio
#============================
function __ChequePromedio($obj,$tur,$idSubCategoria,$mes){
 $frm      = '';
 $total    = 0;
 $subtotal = 0;
 $anio     = $_POST['FechaPromedio'];


 $fpago  = $tur ->Select_formaspago_by_categoria(array(1));


 foreach ($fpago as $key => $valfpago) {
  $montoFP  = $tur->ver_reporte_mensual(array($mes,$anio,$idSubCategoria,$valfpago[0]));

  $subtotal = $subtotal + $montoFP;
 }

 $promedio = $obj -> CONSULTAR_CHEQUE_PROMEDIO(array($mes,$anio,$idSubCategoria));

 foreach($promedio as $_KEY){

  $total    = $total + $_KEY[1];

  // $frm .= '<p style="font-size:.7em;">'.$_KEY[5].' ('.$_KEY[0].') ->  '.$_KEY[1].' / $'.$_KEY[3].'<p>';
  // $subtotal = $_KEY[3];

 }
 $frm .= '<p>'.$subtotal.' /  '.$total.'<p>';
 if($subtotal == 0){
  $cheque_promedio = 0;
 }else{
  $cheque_promedio = $subtotal / $total ;

 }

 $data = array(
  0 => $frm,
  1 => $total,
  2 => $subtotal,
  3 => $cheque_promedio
 );

 return $data;
}

/* ------------------------------------ */
/*     Balanzas                     */
/* ------------------------------------ */
function Categorias($obj,$tur,$com,$udn,$fecha_i,$fecha_f){
   $sql  = $obj -> VER_CATEGORIAS_INGRESOS(1);
   $tb = '';
   foreach($sql as $KEY){ // Consulta de categoria

        $subgrupo = _FOLD_SUBGRUPOS($tur,$KEY[0],$fecha_i,$fecha_f);

        $tb   .= '<tr style="font-weight:600;" class="bg-default">';
        $tb   .= '<td> ('.$KEY[0].') '.$KEY[1].'</td>';
        $tb   .= '</td>'; // Categoria
        $tb   .= '<td class="text-right">'.evaluar($fold1[1]).'</td>';
        $tb   .= '</tr>';

      # sub categoria
        $tb   .= $subgrupo[0];



        $tb   .= __Subgrupos($tur,$com,$KEY[0],$fold1[1],$TOTAL_GRAL_2);

   }

   $data = array(0=>$tb);
   return $data;

}

function _DESGLOZE_CONCEPTOS($fin,$id,$idGrupo,$fi,$ff){
  $id_g   = 1;
  $total  = 0;
  $ocup   = 0;
  $tb     = '';
  # Obtener las formas de pago
  $fpago  = $fin ->Select_formaspago_by_categoria(array($id_g));

  #----

  $sql    = $fin ->Select_Subcategoria_x_grupo($id,$idGrupo);

  foreach($sql as $key => $value){ // vista de subcategorias

   $tb  .= '<tr class="text-success" style="font-weight:400; font-size:.8em;">';
   $tb  .= '<td><i class="bx bx-right-arrow"></i> '.$value[1].'</td>';
   $tb  .= '<td> </td>';

   $tb  .= '</tr>';
  }



  #----
  $data = array(0=>$tb);
  return $data;
}




 function _FOLD_CONCEPTOS($fin,$id,$idGrupo,$fi,$ff){
  $id_g   = 1;
  $total  = 0;
  $tb     = '';
  $ocup   = 0;

  $sql    = $fin ->Select_Subcategoria_x_grupo($id,$idGrupo);




  $fpago  = $fin ->Select_formaspago_by_categoria(array($id_g));

  foreach($sql as $key => $value){ // vista de subcategorias

   $tb  .= '<tr class="bg-success">';

   //   $pax  = $fin->ExisteEnBitacora($fi,$ff,$value[0],'pax');
   $tb  .= '<td><i class="bx bx-"></i> ++ '.$value[1].'</td>';

     /*-----------------------------------*/
     /* Formas de Pago
     /*-----------------------------------*/
   if ( $id != 12) {

    $Cuartos_Ocupados = 0;

    foreach ($fpago as $key => $valfpago) {
     $montoFP = $fin->Select_MontoFPago($fi,$ff,$value[0],$valfpago[0]);

      if($montoFP != 0){
        $tb   .= '<td>'.$valfpago[1].' : '.evaluar($montoFP).'</td>';
        $Cuartos_Ocupados = $Cuartos_Ocupados +1;
      }



     $total = $total + $montoFP;
     $Cuartos_Ocupados = $Cuartos_Ocupados +1 ;
    }

   }


   $tb  .= '</tr>';

  }

  $data = array(0=>$tb,1=>$total,2=> $Ocupacion, 3=>$Cuartos_Ocupados);
  return $data;
}

function _FOLD_SUBGRUPOS($obj,$id,$fi,$ff){
 $tb       = '';
 $total    = 0;
 $sub      = $obj ->Select_group(array($id));

 foreach($sub as $KEY){ // sub grupo de categorias


  $desgloze    = _DESGLOZE_CONCEPTOS($obj,$id,$KEY[0],$fi,$ff);

  if($KEY[0] != 1){
    $tb  .= '<tr style="font-size:.8em;">';

    $tb  .= '<td><i class="icon-right-hand"></i>  ['.$KEY[0].'] '.$KEY[1].' </td>';
    $tb  .= '<td class="text-right">'.evaluar($d[1]).'  Ocup = '.$d[3].'</td>';

    $tb  .= '</tr>';
  }
   #-------
   $tb    .= $desgloze[0]; // formas de pago
   #-------


  // $total  = $total + $d[1];
 }

 $data = array(0=>$tb,1=>$total,2=>$d[3]);
 return $data;
}

function __FORMATO_BALANZAS($obj,$tur,$com,$udn){
 $tb          = '<br>';
 $anios_atras  = 1;

 $fi  = $com-> separar_fecha($_POST['date1']);
 $ff  = $com-> separar_fecha($_POST['date2']);

 $fmi = $com-> obtener_mes_corto($fi[1]);
 $fmf = $com-> obtener_mes_corto($ff[1]);

 $anios_inicial =$fi[0]-$anios_atras;

 $dates = _returnDates($com);

 $tb   = '<br><div class = "table-responsive ">';
 $tb  .= '<table style="font-size:.87em;" class="table table-bordered">';


 $sql  = $obj -> VER_CATEGORIAS_INGRESOS(1);

 $TOTAL_GRAL_1  = 0;
 $TOTAL_GRAL_2  = 0;

 foreach($sql as $key){
  $fold  = FOLD_SUBGRUPOS($tur,$key[0],$dates[0][0],$dates[0][1]);
  $fold1 = FOLD_SUBGRUPOS($tur,$key[0],$dates[1][0],$dates[1][1]);

  $TOTAL_GRAL_1 = $TOTAL_GRAL_1 + $fold1[1];
  $TOTAL_GRAL_2 = $TOTAL_GRAL_2 + $fold[1];
 }


 $DIF_TOTAL = $TOTAL_GRAL_1 -$TOTAL_GRAL_2;


 //   <img src="recursos/img/logo_c.png" alt="Logo de la empresa" style="padding-left:10px; height:40px;">
 $tb .= '<thead>
 <tr>
 <td rowspan="2" class="text-center">

 </td>
 <td class="text-center">DESDE: </td>
 <td class="text-center">'.$dates[1][0].'</td>
 </tr>


 <tr>
 <td class="text-center">HASTA:ss</td>
 <td class="text-center">'.$dates[1][1].'</td>

 </tr>
 </thead>';


 $title = array('INGRESOS','',evaluar($TOTAL_GRAL_1));
 $tb   .= __THEAD($title);


 foreach($sql as $KEY){ // Consulta de categoria

  $fold  = FOLD_SUBGRUPOS($tur,$KEY[0],$dates[0][0],$dates[0][1]);
  $fold1 = FOLD_SUBGRUPOS($tur,$KEY[0],$dates[1][0],$dates[1][1]);
  $t_i   = $fold1[1];
  $t_f   = $fold[1];

  $diferencia = _dif($t_i,$t_f);

  $tb   .= '<tr style="font-weight:600;" class="bg-default">';

  $tb   .= '<td onclick="unfold_fold('.$KEY[0].')" colspan="2">';
  $tb   .= '<i class="bx bx-caret-right ico'.$KEY[0].'" ></i>'.$KEY[1].'</td>';
  $tb   .= '</td>'; // Categoria
  $tb   .= '<td class="text-right"> '.evaluar($fold1[1]).' </td>';
  $tb   .= '</tr>';

  $tb   .= __Subgrupos($tur,$com,$KEY[0],$fold1[1],$TOTAL_GRAL_2);

  $output[] = array(
   'month'   => $KEY[1],
   'profit'  => $fold1[1]
  );

 }

 $tb  .= '</tbody>';
 $tb  .= '</table>';
 $tb  .= '</div>';

 $arreglo = array(0=>$tb,1=>$output);
 return $arreglo;
}

function FOLD_SUBGRUPOS($obj,$id,$fi,$ff){
 $tb       = '';
 $total    = 0;

 $sub      = $obj ->Select_group(array($id));

 foreach($sub as $KEY){

  $d    = FOLD_CONCEPTOS($obj,$id,$KEY[0],$fi,$ff);

  $tb  .= '<tr class="unfold'.$id.' hxide">';
  $tb  .= '<td><i class="icon-plus-1"></i> asda '.$KEY[1].' </td>';
  $tb  .= '<td></td>';
  $tb  .= '<td></td>';
  $tb  .= '<td class="text-right">'.evaluar($d[1]).'</td>';


  $tb  .= '</tr>';

  $total  = $total + $d[1];
  $tb .= $d[0];
 }

 $data = array(0=>$tb,1=>$total);
 return $data;
}

function __Subgrupos($fin,$com,$idCategoria,$t1,$t2){ //Retorna la comparativa anual por grupo
 $tb       = '';
 $dates    = _returnDates($com);

 $sub      = $fin ->Select_group(array($idCategoria));
 $fpago    = $fin ->Select_formaspago_by_categoria(array(1));

 foreach($sub as $KEY){// SUB GRUPO
  $_tb        = '';
  $total_sub1 = 0;
  $total_sub2 = 0;

  // -------------
  $sql       = $fin ->Select_Subcategoria_x_grupo($idCategoria,$KEY[0]);

  foreach($sql as $key){
   $total_1  = 0;
   $total_2  = 0;

   $_tb   .= '<tr  style="font-weight:600;" class="hide">';
   $_tb   .= '<td>'.$key[1].'</td>';


   /*-----------------------------------*/
   /* Formas de Pago
   /*-----------------------------------*/
   if ( $idCategoria != 12) {

    foreach ($fpago as $OK => $valfpago) {
     $montoF1 = $fin->Select_MontoFPago($dates[1][0],$dates[1][1],$key[0],$valfpago[0]);
     $montoF2 = $fin->Select_MontoFPago($dates[0][0],$dates[0][1],$key[0],$valfpago[0]);

     $total_1   = $total_1 + $montoF1;
     $total_2   = $total_2 + $montoF2;
    }
   }
   /*-----------------------------------*/

   $total_sub1  = $total_sub1 + $total_1;
   $total_sub2  = $total_sub2 + $total_2;


   $diferencia  = _dif($total_1,$total_2);

   $_tb   .= '<td class="text-right">|||'.__porc($diferencia,$total_2).'</td>';
   //   $_tb   .= '<td class="text-right">      '.__porc($total_1,$t1).' %</td>';

   $_tb   .= '<td class="text-right">'.evaluar($total_1).'</td>';
   //   $_tb   .= '<td class="text-right"></td>';
   //   $_tb   .= '<td class="text-right">'.evaluar($total_2).'</td>';

   //   $_tb   .= '<td class="text-right">'.evaluar(_numX($diferencia)).'</td>';
   $_tb   .= '</tr>';
  }

  $diferencia_sub  = _dif($total_sub1,$total_sub2);


  $tb   .= '<tr class="unfold'.$idCategoria.' hide " style="font-weight:500;" >';
  $tb   .= '<td>'.$KEY[1].'</td>';
  //   $tb   .= '<td class="text-right">'.__porc($total_sub1,$t1).' %  </td>';
  $tb   .= '<td class="text-right">'.evaluar($total_sub1).'</td>';

  //   $tb   .= '<td class="text-right"> '.$total_sub2.'  '.__porc($t2,$total_sub2).' %</td>';
  //   $tb   .= '<td class="text-right">'.evaluar($total_sub2).'</td>';


  //   $tb   .= '<td class="text-right">'.__porc($diferencia_sub,$total_sub2).' %</td>';
  //   $tb   .= '<td class="text-right">'.evaluar(_dif($total_sub1,$total_sub2)).'</td>';
  $tb   .= '</tr>';
  $tb   .= $_tb;

 }

 return $tb;
}

/* ------------------------------------ */
/*     Tendencias                     */
/* ------------------------------------ */
function ___FORMATO_MES($obj,$tur,$com,$udn){
 $frm      = '';
 $data = '';
 ChartJS::addDefaultColor(array('fill'=>'#f2b21a',
  'stroke'      => '#e5801d',
  'point'       => '#e5801d',
  'pointStroke' => '#e5801d'));
  // $anio = 2021;
  $anio = $_POST['select_anio'];
  $sql  = $obj -> VER_CATEGORIAS_INGRESOS(1);
  $array_labels = array("ENERO", "FEBRERO", "MARZO", "ABRIL", "MAYO", "JUNIO", "JULIO","AGOSTO","SEPTIEMBRE","OCTUBRE","NOVIEMBRE","DICIEMBRE");
  $contador     = 0;

  $frm .= '<div class="table-responsixve col-sm-12">';
  $frm .= '<table id="IngresosMes" style="font-size:.1.2em; font-weight:500;" class="table table-bordered"  >';
  $frm .= '<thead>
  <tr>
  <th class="text-center">INGRESOS '.$anio.'</th>
  <th class="text-center">'.__MES(1).'</th>
  <th class="text-center">'.__MES(2).'</th>
  <th class="text-center">'.__MES(3).'</th>
  <th class="text-center">'.__MES(4).'</th>
  <th class="text-center">'.__MES(5).'</th>
  <th class="text-center">'.__MES(6).'</th>
  <th class="text-center">'.__MES(7).'</th>
  <th class="text-center">'.__MES(8).'</th>
  <th class="text-center">'.__MES(9).'</th>
  <th class="text-center">'.__MES(10).'</th>
  <th class="text-center">'.__MES(11).'</th>
  <th class="text-center">'.__MES(12).'</th>
  <th class="text-center">TOTAL</th>

  </tr>
  </thead>';

  foreach($sql as $KEY){ // Consulta de categoria
    $total_categoria = 0;
    $contador = $contador + 1;

    # CONSULTA MENSUAL
    $arreglo_datos = array('');
    $total = 0;

    $frm .= '<tr>';
    $frm .= '<td>'.$KEY[1].'</td>';

    for($x = 1; $x  <= 12 ; $x++ ){
      $mes                 = __MES($x);
      $mensual             = __DESGLOZE_MENSUAL($obj,$tur,$KEY[0],$x,$anio);
      $arreglo_datos[$x]   = $mensual[1];
      $total_categoria     = $total_categoria + $mensual[1];
      $frm .= '<td class="text-right"> '.evaluar($mensual[1]).' </td>';


      //   $frm .= $mensual[0];
    }// fin de recorrido por mes


    $frm .= '<td class="text-right">'.$total_categoria.'</td>';
    $frm .= '</tr>';



   #Grafico lineal =============
   $array_values        = array($arreglo_datos);
   $Bar                 = new ChartJS_Line('bar'.$KEY[0], 500, 250);
   $Bar->addLine($array_values[0]);
   $Bar->addLabels($array_labels);


   //  var_dump($arreglo_datos);

   //  echo $Bar;
   $grafico   = $Bar;


  }// fin de categorias

  $frm   .= '</tbody></table></div>';

  $data .= '
  <div class="row">
  <div class="col-sm-6 col-xs-12">'.$frm.'</div>
  <div class="col-sm-6 col-xs-12">'.$grafico.'</div>
  </div>
  ';

  // echo $frm;




  return $data;
 }


 function __DESGLOZE_MENSUAL($obj,$tur,$idCategoria,$mes,$anio){
  $frm              = '';
  $total_mensual    = 0;


  $sub      = $obj ->Select_group(array($idCategoria));
  foreach($sub as $_KEY){
   if($_KEY[1] == "SIN GRUPO"){

   }else{
    $frm .= '
    <tr class="bg-danger">
    <td>'.$_KEY[1].'</td>
    <td>'.$idCategoria.'</td>
    </tr>';
   }



   #=================================
   $id_g   = 1;
   $total  = 0;
   $tb     = '';

   $sql    = $tur ->Select_Subcategoria_x_grupo($idCategoria,$_KEY[0]);
   $fpago  = $tur ->Select_formaspago_by_categoria(array($id_g));


   foreach($sql as $key => $value){ // vista de subcategorias

    $total  = 0;

    # Formas de pago ---
    foreach ($fpago as $key => $valfpago) {
     $montoFP = $tur->ver_reporte_mensual(array($mes,$anio,$value[0],$valfpago[0]));
     $total = $total + $montoFP;
    }


    $total_mensual = $total_mensual + $total;
    $frm  .= '<tr class="unfolder'.$id.' " >';
    $frm  .= '<td><i class="bx bx-"></i> '.$value[1].'</td>';
    $frm  .= '<td class="text-right">'.evaluar($total).'</td>';
    $frm  .= '</tr>';

   }

  }

  $data  = array(0=>$frm,1=>$total_mensual);
  return $data;
 }

 function __MES($mes){
  $m = '';
  switch ($mes) {
   case 1:$m = "Enero";break;
   case 2:$m = "Febrero";break;
   case 3:$m = "Marzo";break;
   case 5:$m = "Mayo";break;
   case 4:$m = "Abril";break;
   case 6:$m = "Junio";break;
   case 7:$m = "Julio";break;
   case 8:$m = "Agosto";break;
   case 9:$m = "Septiembre";break;
   case 10:$m = "Octubre";break;
   case 11:$m = "Noviembre";break;
   case 12:$m = "Diciembre";break;
  }

  return $m;
 }

 function VENTAS_AÑO_LINE($fi,$ff){
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


 /* ------------------------------------ */
 /*     Complementos      */
 /* ------------------------------------ */

 function _dif($t1,$t2){
  $total  = $t1-$t2;
  $lbl    = '';
  if($total < 0){
   $lbl = $total;
  }else{
   $lbl = $total;
  }
  return $lbl;
 }

 function __porc($v1,$v2){
  $label = '';
  if($v2==0){
   $label = 'ERROR';
  }else{
   $porc = ($v1 / $v2) ;
   //  $label = $v1.'/'.$v2;
   $label =number_format($porc, 2, '.', '');
  }

  return $label;
 }

 function _numX($num){
  $label = '';
  if($num <= 0){
   $label = '<span class="text-danger">'.evaluar($num).'</span>';
  }else{
   $label = evaluar($num);
  }

  return $label;
 }

 function _returnDates($com){
  $fi  = $com-> separar_fecha($_POST['date1']);
  $ff  = $com-> separar_fecha($_POST['date2']);

  $anios_atras      = 1;
  $anio_inicial     = $fi[0] - $anios_atras;


  for ($x= $anio_inicial; $x <= $fi[0]; $x++) { // Recorrido de años

   $strDate1 = $x.'-'.$fi[1].'-'.$fi[2];
   $strDate2 = $x.'-'.$ff[1].'-'.$ff[2];

   $dates[]  = array($strDate1,$strDate2);
  }

  return $dates;
 }

 function FOLD_CONCEPTOS($fin,$id,$idGrupo,$fi,$ff){
  $id_g   = 1;
  $total  = 0;
  $tb     = '';

  $sql    = $fin ->Select_Subcategoria_x_grupo($id,$idGrupo);
  $fpago  = $fin ->Select_formaspago_by_categoria(array($id_g));

  foreach($sql as $key => $value){ // vista de subcategorias

   // $tb  .= '<tr class="unfolder'.$id.' hide">';

   //   $pax  = $fin->ExisteEnBitacora($fi,$ff,$value[0],'pax');
   // $tb  .= '<td><i class="bx bx-"></i> '.$value[1].'</td>';

   // // //   /*-----------------------------------*/
   // // //   /* Formas de Pago
   // // //   /*-----------------------------------*/
   if ( $id != 12) {

    foreach ($fpago as $key => $valfpago) {
     $montoFP = $fin->Select_MontoFPago($fi,$ff,$value[0],$valfpago[0]);
     //  $tb   .= '<td>'.$valfpago[1].' : '.evaluar($montoFP).'</td>';
     $total = $total + $montoFP;
    }
   }


   // $tb  .= '</tr>';

  }



  $data = array(0=>$tb,1=>$total );
  return $data;
 }

 function __THEAD($th){
  $tb = '';
  /*----------THEAD------------*/
  $tb .= '<thead>';



  // $tb .= '<tr class="">';
  // for ($x = 0; $x < count($a); $x++) {
  //  $tb .= '<th class="text-center"><b>' . $a[$x] . '</b></th>';
  // }
  // $tb .= '</tr>';
  //
  // $tb .= '<tr class="">';
  // for ($y = 0; $y < count($b); $y++) {
  //  $tb .= '<th class="text-center"><b>' . $b[$y] . '</b></th>';
  // }
  // $tb .= '</tr>';

  $tb .= '<tr  style="background:#0F243E; color:white; font-size:1.2em;">';
  for ($i = 0; $i < count($th); $i++) {
   $tb .= '<td class="text-center"><b>' . $th[$i] . '</b></td>';
  }
  $tb .= '</tr>';
  $tb .= '</thead>';

  /*----------THEAD------------*/




  return $tb;
 }

 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="") {
   $res = '-';
  }else {
   $res =''.number_format($val, 2, '.', ',');
  }

  return $res;
 }

 function evaluar2($val){
  $res = '';
  if ($val==0 || $val=="") {
   $res = '-';
  }else {
   $res = $val;
  }

  return $res;
 }

 function dias_transcurridos($fi, $ff){
   $dias_diferencia = 0;

   $datetime1 = date_create($fi);
   $datetime2 = date_create($ff);


   $contador = date_diff($datetime1, $datetime2);
   $differenceFormat = '%a';

   $dias_diferencia = $contador->format($differenceFormat);

   $dias_diferencia = $dias_diferencia + 1;
   return $dias_diferencia;


   // //separar fecha inicial
   //
   //  $separar_fi  = explode("/",$fi);
   //  $separar_ff  = explode("/",$ff);
   //
   //
   //  $ano1 = $separar_fi[0];
   //  $mes1 = $separar_fi[1];
   //  $dia1 = $separar_fi[2];
   //
   //  //defino fecha 2
   //  $ano2 = $separar_ff[0];
   //  $mes2 = $separar_ff[1];
   //  $dia2 = $separar_ff[2];
   //
   //
   //  //calculo timestam de las dos fechas
   //  $timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
   //  $timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);
   //
   //
   //
   //
   //  //resto a una fecha la otra
   //  $segundos_diferencia = $timestamp1 - $timestamp2;
   //
   //
   //
   //  //echo $segundos_diferencia;
   //  //
   //  //convierto segundos en días
   //  $dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
   //  // $dias_diferencia = $segundos_diferencia ;
   //
   //  // obtengo el valor absoulto de los días (quito el posible signo negativo)
   //  // $dias_diferencia = abs($dias_diferencia);
   //
   //  //quito los decimales a los días de diferencia
   //  // $dias_diferencia = floor($dias_diferencia);
   //
   // return $dias_diferencia;

    $data = array(0 => $separar_fi );

 }


 ?>
