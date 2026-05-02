 <?php
include_once("../../modelo/SQL_PHP/_METAS.php"); // <---
$obj = new METAS; // <--

include_once('../../modelo/SQL_PHP/_INGRESOS.php');

include_once('../../modelo/SQL_PHP/_INGRESOS_TURISMO.php');

// $obj = '';


$encode = '';
// switch ($_POST['opc']) {
//     case 1:
     $encode =    ingresos_anuales($obj);
//     break;
    
   
// }

# =======================
#    Function 
# =======================
function ingresos_anuales($obj){
    $fi=$_POST['anio1'];
    $ff=$_POST['anio2'];

    $tabs ='<div style="margin-top:25px;" class="col-md-12"><ul class="nav nav-tabs">';
    
   for ($i=$fi; $i <= $ff; $i++) { // Recorrido de años
        if ($i==$fi) {
            $tabs .= '
            <li class="active">
            <a  class="text-danger" data-toggle="tab" href="#tab'.$i.'"><strong>'.$i.'</strong></a>
            </li>
            ';
        }else {
             $tabs .= '<li><a  href="#tab'.$i.'" data-toggle="tab" ><strong>'.$i.'</strong></a></li>';
        }
   }// end recorrido

   
   $tabs .='
    </ul>
    <div class="tab-content">';
    
    for ($j=$fi; $j <= $ff; $j++) {
        $rpt = _desgloze_ingresos($obj,$j);

        if ($j==$fi) {
            
            $tabs .='
                <div class="" id="tab'.$j.'">
                <div class="col-xs-12 col-sm-12">'.$rpt.' </div>
                </div>
            ';

        }
       
    }// end j

   $tabs .='</div></div>';
   $ingresos = array($tabs);
   
   return $ingresos;
}


function _desgloze_ingresos($obj,$anio){
    $txt   = '';  
    
    $mes   = 8;
    
    $txt  .= '

    <div class=" ">
    <table  class="table table-bordered table-xtra-condensed"  style="font-size:.9rem; font-weight:500; margin-bottom: 10px;"   Id="size1">
    <thead>
    <tr>
    <th>INGRESOS TURISMO </th>
    <th class="bg-primary"> TOTAL </th>';

    for($x = $mes-3; $x  <= $mes ; $x++ ){
        $txt  .= '<th class="text-center">'._MES($x).'</th>';
    }

    $txt  .= '</tr></thead><tbody>';

    /*------- INGRESOS GENERALES ---------*/
    $categorias        = $obj -> VER_CATEGORIAS(1);
    foreach ($categorias as $key ) { // ingresos ej. Hospedaje,Restaurant,Tours...

        $txt  .= '
            <tr>
            <td  class="pointer" > '.$key[1].'</td>
            <td></td>
            ';

             for($x = $mes-3 ; $x  <= $mes ; $x++ ){
                $mensual   = __DESGLOZE_MENSUAL($obj,$key[0],$x,$anio);
                $txt  .= '<td  class="text-end" > '.evaluar($mensual[1]).'</td>'; 
             }
    
        $txt  .= '</tr>';
    }

   
    // ========== FORMAS DE PAGO =======

    $txt .= '
    <tr class="bg-primary" >
    <td class="col-xs-3" colspan="6">FORMA DE PAGO</td>
    </tr>
    ';
    
    $miArreglo = [
    1 =>"EFECTIVO",
    2 => "TARJETA DE CREDITO O DEBITO" ,
    3 => "CxC OTROS SERVICIOS",
    4 => "ANTICIPOS",
    5 =>"CxC HABITACIONES",
    6 =>"CORTESIAS Y COMIDA DE EMPLEADOS" ];
    
   
    for($i=1; $i <= count($miArreglo) ;$i++){
        $txt .= '<tr>';
        $txt  .= '<td>'.$miArreglo[$i].'</td>'; 

        for($j = $mes-3; $j  <= $mes ; $j++ ){





           $txt  .= '<td  class="text-right" > </td>'; 
        }

        $txt .= '</tr>';
     }

    return $txt;
}


 function __DESGLOZE_MENSUAL($objx,$idCategoria,$mes,$anio){
  $frm           = '';
  $total_mensual = 0;
  $obj           = new INGRESOS;
  $tur           = new TURISMO;

  $sub      = $obj ->Select_group(array($idCategoria));
 
  foreach($sub as $_KEY){
   if($_KEY[1] == "SIN GRUPO"){

   }else{

   }
   # -------------
   $id_g   = 1;
   $total  = 0;
   $tb     = '';

   $sql    = $tur ->Select_Subcategoria_x_grupo($idCategoria,$_KEY[0]);
   $fpago  = $tur ->Select_formaspago_by_categoria(array($id_g));


   foreach($sql as $key => $value){ // vista de subcategorias

    $total  = 0;

    // # Formas de pago ---
    foreach ($fpago as $key => $valfpago) {
        $montoFP  = $tur->ver_reporte_mensual(array($mes,$anio,$value[0],$valfpago[0]));
         $total    = $total + $montoFP;
        // $frm  .= '$ '.$montoFP;
    }


    $total_mensual = $total_mensual + $total;
    // $frm  .= '/'.$value[1];
    // $frm  .= '<td class="text-right">'.evaluar($total).'</td>';
    // $frm  .= ' <br>';

   }

  }

  $data  = array(0=>$frm,1=>$total_mensual);
  return $data;
 }


function _MES($NoMes){
    $mes = '';
    switch ($NoMes) {

        case 1:
            $mes = 'ENERO';
        break;

        case 2:
            $mes = 'FEBRERO';
        break;

        case 3:
            $mes = 'MARZO';
        break;
       
        case 4:
            $mes = 'ABRIL';
        break;
        
        case 5:
            $mes = 'MAYO';
        break;

        case 6:
            $mes = 'JUNIO';
        break;
        
        case 7:
            $mes = 'JULIO';
        break;
      
        case 8:
            $mes = 'AGOSTO';
        break;
        case 9:
            $mes = 'SEPTIEMBRE';
        break;
       
        case 10:
            $mes = 'OCTUBRE';
        break;

        case 11:
            $mes = 'NOVIEMBRE';
        break;

        case 8:
           $mes = 'DICIEMBRE';
        break;
    }
    
    return $mes;
}

 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="" || $val == null) {
   $res = '-';
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }

  return $res;
 }

# ------------------------
#    ENCODE JSON
# ------------------------


 echo json_encode($encode);















// $tb1  ='';
// $tb2  ='';
// $tb3  ='';
// $tb4  ='';
// $tabs ='';

$tabs .='
<div class="col-12">
<ul class="nav nav-tabs">';

for ($i=$fi; $i <= $ff; $i++) { // Recorrido de años


//   if ($i==$fi) {

//   $tabs =$tabs.'
//     <li class="active">
//     <button class="nav-link active" 
//     id="home-tab" data-bs-toggle="tab"
//     data-bs-target="#tab'.$i.'" type="button" role="tab" aria-controls="home"
//     aria-selected="true"><strong>'.$i.'</strong>
//     </button>

//     </li>';

//  }else {
//   $tabs =$tabs.'
//   <li>
//    <a  href="#tab'.$i.'" data-toggle="tab" ><strong>'.$fi.'</strong></a>
//   </li>';
//  }
// }


// $tabs .='
// </ul> 
// </div>


// <div class="tab-content" id="myTabContent">';

// for ($j=$fi; $j <= $ff; $j++) {


//  $INGRESOS_TURISMO = INGRESOS_TURISMO($j);
//  $FORMA_PAGO       = formas_pago_desgloze($j);

// //  $FORMA_PROPINA    = FORMA_PROPINA($j);
// //  $TOTAL_GENERAL    = TOTAL_GENERAL($j);

// //  if ($j==$fi) {
// //   $tabs =$tabs.'
// //   <div class="tab-pane fade in active" id="tab'.$j.'">
// //   '.$j.'


// //    
// //   </div>
// //   ';



// //  }else {
// //   <div class="col-xs-12 col-sm-12">'.$TOTAL_GENERAL.'</div>
// //   <div class="col-xs-12 col-sm-12">'.$INGRESOS_TURISMO.'</div>
// //   <div class="col-xs-12 col-sm-12">'.$FORMA_PAGO.'</div>
// //   <div class="col-xs-12 col-sm-12">'.$FORMA_PROPINA.'</div>
// // tab-pane fade
// // '.$INGRESOS_TURISMO.'
//   $tabs =$tabs.'
//   <div class="tab-pane fade show active" id="#tab'.$j.'">
  
//   '.$FORMA_PAGO.'
//   </div>
//   ';
// //  }

}

// $tabs =$tabs.'</div></div>';




//  /*==========================================
//  *		FUNCIONES / FORMULAS
//  =============================================*/



// #
//  function INGRESOS_TURISMO($AÑO){
//    $obj = new INGRESOS;
//    $tur  = new TURISMO;

//    $frm .='

//    <div style="margin-top:15px;" class="">
//    <table class="table-report table-bordered"  style="font-size:.82rem; margin-bottom: 2px; margin-top:2px;">
//    <thead>
//    <tr class="bg-default-gv">
//    <th class="">INGRESOS  ('.$AÑO.')</th>
//    <th class="">TOTAL</td>
//    <th>ENERO</td>
//    <th>FEBRERO</td>
//    <th>MARZO</td>
//    <th>ABRIL</td>
//    <th>MAYO</td>
//    <th>JUNIO</td>
//    <th>JULIO</td>
//    <th>AGOSTO</td>
//    <th>SEPTIEMBRE</td>
//    <th>OCTUBRE</td>
//    <th>NOVIEMBRE</td>
//    <th>DICIEMBRE</td>
//    </tr>
//    </thead>
//    <tbody>';

//    # BODY ----------
//    $sql  = $obj -> VER_CATEGORIAS_INGRESOS(1);
// //    $contador     = 0;

//    foreach($sql as $KEY){ // Consulta de categoria
// //      $total_categoria = 0;
// //      $contador = $contador + 1;

//      $frm .= '<tr>';
//      $frm .= '<td class="">'.$KEY[1].'</td>';
//      $frm .= '<td></td>';

//      for($x = 1; $x  <= 12 ; $x++ ){
//        $mensual = __DESGLOZE_MENSUAL($obj,$tur,$KEY[0],$x,$AÑO);


//        $frm .= '<td class="text-right"> '.evaluar($mensual[1]).' </td>';

//      }



//      $frm .= '</tr>';
//    }



//    #--
//    $frm .='</tbody></table></div>';
//    return $frm;
//  }

//  function __DESGLOZE_MENSUAL($obj,$tur,$idCategoria,$mes,$anio){
//   $frm              = '';
//   $total_mensual    = 0;


//  }


//  function _INGRESOS_TURISMO($AÑO){
//   $obj      = new METAS;
//   $udn      = 1;
//   $contador = 5;
//   $categorias = $obj -> VER_CATEGORIAS($udn);
//   $TotalxTotal = 0;
//   $mes1 = 0; $mes4 = 0;  $mes7 = 0;
//   $mes2 = 0; $mes5 = 0;  $mes8 = 0;
//   $mes3 = 0; $mes6 = 0;  $mes9 = 0;
//   $mes10 = 0; $mes11 = 0;  $mes12 = 0;
//   $tb1='';
//   $tb1=$tb1.'
//   <div class="scrolling outer">
//   <div class=" inner">
//   <div class="table-responsive">
//   <table class="table table-bordered" id="size1" style="font-size:.82em; margin-bottom: 2px; margin-top:2px;">
//   <thead>
//   <tr class="bg-primary" id="table-title">
//   <th id="col_1" class="text-primary">INGRESOS  ('.$AÑO.')</th>
//   <td id="col_2" class="bg-green">TOTAL</td>
//   <td>ENERO</td>
//   <td>FEBRERO</td>
//   <td>MARZO</td>
//   <td>ABRIL</td>
//   <td>MAYO</td>
//   <td>JUNIO</td>
//   <td>JULIO</td>
//   <td>AGOSTO</td>
//   <td>SEPTIEMBRE</td>
//   <td>OCTUBRE</td>
//   <td>NOVIEMBRE</td>
//   <td>DICIEMBRE</td>
//   </tr>
//   </thead>
//   <tbody>
//   ';

//   foreach ($categorias as $key ) { // ingresos ej. Hospedaje,Restaurant,Tours...

//    $ok          = $obj -> VER_INGRESOS($key[0],$AÑO,0,1);

//    $TotalxTotal += $ok;



//    $tb1  =$tb1.'
//    <tr>
//    <th id="col_1">'.$key[1].'</th>';
//    $tb1.='<td class="bg-light-green" >'.evaluar($ok).' ??</td>';

//    for ($i=1; $i <= 12 ; $i++) {
//     $mes  = $obj -> VER_INGRESOS($key[0],$AÑO,$i,2);

//     $tb1=$tb1.'<td>'.evaluar($mes).'</td>';

//     switch ($i) {
//      case 1: $mes1 +=$mes; break;
//      case 2: $mes2 +=$mes; break;
//      case 3: $mes3 +=$mes; break;
//      case 4: $mes4 +=$mes; break;
//      case 5: $mes5 +=$mes; break;
//      case 6: $mes6 +=$mes; break;
//      case 7: $mes7 +=$mes; break;
//      case 8: $mes8 +=$mes; break;
//      case 9: $mes9 +=$mes; break;
//      case 10:$mes10 +=$mes; break;
//      case 11:$mes11 +=$mes; break;
//      case 12:$mes12 +=$mes; break;
//     }

//     /*--------- cargos de habitacion -----------*/

//     // if ($contador < 11) { // Solo existen 10 fp de pago 18062019
//     //
//     //  if ($contador != 10 && $contador !=8) {
//     //   $cargos = $obj -> ver_cargos_hab($contador,$fi,$ff);
//     //   $n_fp   = $obj -> ver_nombre_fp(array($contador));
//     //   $graltotal+= $cargos[2];
//     //   $Cargos_hab_des += $cargos[2];
//     //
//     //   $ingresos_tur.='
//     //   <tr class="bg-default">
//     //   <td id="col_1">'.$n_fp.' CARGO HABITACION </td>
//     //   <td></td>
//     //   <td></td>
//     //   <td></td>
//     //   <td class="text-right">'.evaluar($cargos[2]).'</td>
//     //   </tr>';
//     //  }
//     //
//     // }

//     $contador += 1;



//    }


//    $tb1=$tb1.'</tr>';
//   }

//   $tb1=$tb1.'
//   </tbody>
//   <tfoot>
//   <tr class="bg-info" id="table-title">
//   <th id="col_1 " class="text-primary">TOTAL INGRESOS</th>
//   <td id="col_2" class="bg-green"> '.evaluar($TotalxTotal).' </td>
//   <td>'.evaluar($mes1).'</td>
//   <td>'.evaluar($mes2).'</td>
//   <td>'.evaluar($mes3).'</td>
//   <td>'.evaluar($mes4).'</td>
//   <td>'.evaluar($mes5).'</td>
//   <td>'.evaluar($mes6).'</td>
//   <td>'.evaluar($mes7).'</td>
//   <td>'.evaluar($mes8).'</td>
//   <td>'.evaluar($mes9).'</td>
//   <td>'.evaluar($mes10).'</td>
//   <td>'.evaluar($mes11).'</td>
//   <td>'.evaluar($mes12).'</td>

//   </tr>
//   </tfoot>
//   </table>
//   </div></div></div>
//   ';

//   return $tb1;
//  }



//  function formas_pago_desgloze($AÑO){
//   $obj    = new METAS;
//   $formas = $obj -> VER_FORMAS_PAGO();

//   $tb ='
//   <table class="table-report" style="font-size:.82em; margin-bottom: 2px; margin-top:15px;">
//   <thead>
//   ';

//   $tb .= '
//   <tr class="bg-info-1592">
//   <th>FORMA DE PAGO</th>
//   <th>TOTAL</th>';
//   for ($i=1; $i <= 12 ; $i++) {
//    $tb .= '<th class="text-right"> '.$i.' </th>';
//   }// End   
//   $tb .= '
//   </tr>
//   </thead>
//    <tbody>
//   ';

//  foreach ($formas as $key ) {
//    $tb .= '
//    <tr>
//    <th id="col_1">'.$key[1].'</th>';
//  }

//   return $tb;
// }




//  function FORMA_PAGO($AÑO){
//   $tb2='';
//   $obj    = new METAS;
//   $formas = $obj -> VER_FORMAS_PAGO();

//   $tb2=$tb2.'
//   <div class="scrolling outer">
//   <div class=" inner">
//   <div class="table-responsive">
//   <table class="table table-bordered" id="size1" style="font-size:.82em; margin-bottom: 2px; margin-top:15px;">
//   <thead>
//   <tr class="bg-primary">
//   <th id="col_1" class="text-primary">FORMA DE PAGO</th>
//   <td id="col_2" class="bg-green">TOTAL</td>
//   <td>ENERO</td>
//   <td>FEBRERO</td>
//   <td>MARZO</td>
//   <td>ABRIL</td>
//   <td>MAYO</td>
//   <td>JUNIO</td>
//   <td>JULIO</td>
//   <td>AGOSTO</td>
//   <td>SEPTIEMBRE</td>
//   <td>OCTUBRE</td>
//   <td>NOVIEMBRE</td>
//   <td>DICIEMBRE</td>
//   </tr>
//   </thead>
//   <tbody>
//   ';

//   $TotalFormasPago = 0;
//   $mes1 = 0; $mes4 = 0;    $mes7 = 0;
//   $mes2 = 0; $mes5 = 0;    $mes8 = 0;
//   $mes3 = 0; $mes6 = 0;    $mes9 = 0;
//   $mes10 = 0; $mes11 = 0;  $mes12 = 0;

//   foreach ($formas as $key ) { // CXC,T.P,
//    $Tipos   = $obj -> VER_TIPOSPAGOS($key[0],$AÑO,0,1); // 1.- año 2.- mes y año
//    $TotalFormasPago += $Tipos;
//    $tb2=$tb2.'
//    <tr>
//    <th id="col_1">'.$key[1].'</th>';
//    $tb2=$tb2.'<td class="bg-light-green" >'.evaluar($Tipos).'</td>';


//    for ($i=1; $i <= 12 ; $i++) {
//     $mes      = $obj -> VER_TIPOSPAGOS($key[0],$AÑO,$i,2,9);

//     $tb2=$tb2.'<td> '.evaluar($mes).'</td>';
//     switch ($i) {
//      case 1:$mes1   +=$mes; break;
//      case 2:$mes2   +=$mes; break;
//      case 3:$mes3   +=$mes; break;
//      case 4:$mes4   +=$mes; break;
//      case 5:$mes5   +=$mes; break;
//      case 6:$mes6   +=$mes; break;
//      case 7:$mes7   +=$mes; break;
//      case 8:$mes8   +=$mes; break;
//      case 9:$mes9   +=$mes; break;
//      case 10:$mes10 +=$mes; break;
//      case 11:$mes11 +=$mes; break;
//      case 12:$mes12 +=$mes; break;
//     }

//    }


//    $tb2=$tb2.'</tr>';
//   }



//   $tb2=$tb2.'
//   </tbody>
//   <tfoot>
//   <tr class="bg-info" id="table-title">
//   <th id="col_1 " class="text-primary" >TOTAL FORMA DE PAGO</th>
//   <td id="col_2" class="bg-green""> '.evaluar($TotalFormasPago).'</td>
//   <td>'.evaluar($mes1).'</td>
//   <td>'.evaluar($mes2).'</td>
//   <td>'.evaluar($mes3).'</td>
//   <td>'.evaluar($mes4).'</td>
//   <td>'.evaluar($mes5).'</td>
//   <td>'.evaluar($mes6).'</td>
//   <td>'.evaluar($mes7).'</td>
//   <td>'.evaluar($mes8).'</td>
//   <td>'.evaluar($mes9).'</td>
//   <td>'.evaluar($mes10).'</td>
//   <td>'.evaluar($mes11).'</td>
//   <td>'.evaluar($mes12).'</td>
//   </tr>
//   </tfoot>
//   </table></div></div></div>
//   ';
//   return $tb2;
//  }

//  function FORMA_PROPINA($AÑO){
//   $tb3='';
//   $obj    = new METAS; // <--
//   $TotalxPropina = 0;
//   $formas = $obj -> VER_FORMAS_PAGO();
//   $mes1  = 0; $mes4  = 0;    $mes7   = 0;
//   $mes2  = 0; $mes5  = 0;    $mes8   = 0;
//   $mes3  = 0; $mes6  = 0;    $mes9   = 0;
//   $mes10 = 0; $mes11 = 0;    $mes12  = 0;

//   // ------------------------------------

//   $tb3=$tb3.'
//   <div class="scrolling outer">
//   <div class=" inner">
//   <div class="table-responsive">
//   <table class="table table-bordered" id="size1" style="font-size:.82em; margin-bottom: 2px; margin-top:15px;">
//   <thead>
//   <tr class="bg-primary">
//   <th id="col_1" class="text-primary">FORMA DE PAGO PROPINA </th>
//   <td id="col_2" class="bg-green">TOTAL</td>
//   <td>ENERO</td>
//   <td>FEBRERO</td>
//   <td>MARZO</td>
//   <td>ABRIL</td>
//   <td>MAYO</td>
//   <td>JUNIO</td>
//   <td>JULIO</td>
//   <td>AGOSTO</td>
//   <td>SEPTIEMBRE</td>
//   <td>OCTUBRE</td>
//   <td>NOVIEMBRE</td>
//   <td>DICIEMBRE</td>

//   </tr>
//   </thead>
//   <tbody>
//   ';
//   $mes1  = 0; $mes4  = 0;    $mes7   = 0;
//   $mes2  = 0; $mes5  = 0;    $mes8   = 0;
//   $mes3  = 0; $mes6  = 0;    $mes9   = 0;
//   $mes10 = 0; $mes11 = 0;    $mes12  = 0;

//   foreach ($formas as $key ) { // CXC,T.P,
//    $Tipos    = $obj -> VER_PROPINA($key[0],$AÑO,0,1,9);
//    $TotalxPropina += $Tipos;
//    $tb3=$tb3.'
//    <tr>
//    <th id="col_1">'.$key[1].'</th>';
//    $tb3=$tb3.'<td class="bg-light-green" >'.evaluar($Tipos).'</td>';



//    // ---- [ M E S E S ] -------------------|
//    for ($i=1; $i <= 12 ; $i++) {
//     $mes      = $obj -> VER_PROPINA($key[0],$AÑO,$i,2,9);
//     $tb3=$tb3.'<td>'.evaluar($mes).'</td>';
//     switch ($i) {
//      case 1:$mes1   +=$mes; break;
//      case 2:$mes2   +=$mes; break;
//      case 3:$mes3   +=$mes; break;
//      case 4:$mes4   +=$mes; break;
//      case 5:$mes5   +=$mes; break;
//      case 6:$mes6   +=$mes; break;
//      case 7:$mes7   +=$mes; break;
//      case 8:$mes8   +=$mes; break;
//      case 9:$mes9   +=$mes; break;
//      case 10:$mes10 +=$mes; break;
//      case 11:$mes11 +=$mes; break;
//      case 12:$mes12 +=$mes; break;
//     }
//    }

//    //---------------------------------------|
//    $tb3=$tb3.'</tr>';
//   }


//   $tb3=$tb3.'
//   </tbody>
//   <tfoot>
//   <tr class="bg-info" id="table-title">
//   <th id="col_1 " class="text-primary" >TOTAL FORMA DE PAGO</th>
//   <td id="col_2" class="bg-green""> '.evaluar($TotalxPropina).'</td>
//   <td>'.evaluar($mes1).'</td>
//   <td>'.evaluar($mes2).'</td>
//   <td>'.evaluar($mes3).'</td>
//   <td>'.evaluar($mes4).'</td>
//   <td>'.evaluar($mes5).'</td>
//   <td>'.evaluar($mes6).'</td>
//   <td>'.evaluar($mes7).'</td>
//   <td>'.evaluar($mes8).'</td>
//   <td>'.evaluar($mes9).'</td>
//   <td>'.evaluar($mes10).'</td>
//   <td>'.evaluar($mes11).'</td>
//   <td>'.evaluar($mes12).'</td>
//   </tr>
//   </tfoot>
//   </table>
//   </div></div></div>
//   ';

//   return $tb3;

//  }

//  function TOTAL_GENERAL($AÑO){

//   $obj    = new METAS; // <--
//   $TOTAL_GRAL = $obj -> TOTAL_GENERAL($AÑO,12,1);


//   $tb4 = '';
//   $tb4=$tb4.'
//   <div class="scrolling outer">
//   <div class=" inner">
//   <div class="table-responsive">
//   <table class="table " id="table-title-1">
//   <thead>
//   <tr class="bg-primary">
//   <th id="col_1" class="text-primary">TOTAL GENERAL </th>
//   <td id="col_2" class="bg-green"> '.evaluar($TOTAL_GRAL).'</td>
//   ';

//   // ---- [ M E S E S ] -------------------|
//   for ($i=1; $i <= 12 ; $i++) {
//    $mes = $obj -> TOTAL_GENERAL($AÑO,$i,2);

//    $tb4=$tb4.'<td>'.evaluar($mes).'</td>';
//   }
//   return $tb4;

//   $tb4=$tb4.' </tr></thead>
//   <table></div></div></div>';

//  }


 ?>
