<?php
session_start();

include_once('../../modelo/SQL_PHP/_DIA_VENTAS.php');
$obj = new DIA_VENTAS;

include_once('../../modelo/SQL_PHP/_REPORTE_DIA.php');
$REP = new REPORTE_VENTAS;

include_once( '../../modelo/complementos.php' );
$com    = new Complementos;

include_once('../../modelo/UI_TABLE.php');
$table = new Table_UI;

$idE    = $_SESSION['udn'];
$opc    = $_POST['opc'];
$encode = null;

switch ($opc) {

 case 1: // Reporte de ventas
 $f1     = $_POST['f_i'];
 $f2     = $_POST['f_f'];

//  $rp     = __REPORTE_DE_VENTAS($f1,$f2,$obj,$REP,$com);
 $rp     = __REPORTE_MENSUAL($f1,$f2,$obj,$REP,$com);
 $encode = array($rp);
 break;
}

/* JSON  ENCODE */
echo json_encode($encode);

function __REPORTE_MENSUAL($f1,$f2,$obj,$REP,$com){
  $frm  = '';
  $frm .= '<div class="row">';
  $frm .= '<div class=" col-sm-12 col-xs-12">';
  //  $frm .= '<h5> DE <strong>'.$f1.'</strong> a <strong>'.$f2.'</strong></h5>';
  $frm .= '<div class="table-responsive">';
  $frm .= __DESGLOZE_MENSUAL($com,$REP,$f1,$f2);
  $frm .= '</div>';
  $frm .= '</div>';
  $frm .= '</div>';

  return $frm;
}


function __REPORTE_DE_VENTAS($f1,$f2,$obj,$REP,$com){

  $frm = '';
  $frm .= '<div class="row">';
  
  $frm .= '<div class="col-sm-12 col-xs-12">';
  $frm .= '<h5> DE '.$f1.' a '.$f2.'</h5>';
  $total  =  Venta_total($obj,array($f1,$f2));
  $frm .= '<h3><span class="text-info">VENTA TOTAL :</span> '.evaluar($total).'</h3>';
  $frm .= '</div>';
  
  $frm .= '</div>';
  // $total  =  Venta_total($obj,array($f1,$f2));
  
  $frm   .= '<div class="row">';
  $frm   .= '<div class="col-sm-6 col-xs-6">';

  $frm   .= '';
  $frm   .= '<table class="table  table-condensed table-bordered">';

  $Efectivo = Venta_FormaPago($obj,array($f1,$f2),'Efectivo');
  $Credito = Venta_FormaPago($obj,array($f1,$f2),'Credito');
  $TDC = Venta_FormaPago($obj,array($f1,$f2),'TDC');
  $TOTAL = $Efectivo + $Credito + $TDC;
  $frm .= '<thead><tr><th class="text-info" colspan="2"><span style="font-size:1.5em;"> <i class="icon  icon-print-4"></i> VENTAS </span></th></tr></thead>';
  $frm .= '<tbody>';

  $frm .= '<tr><td><b>En efectivo : </b></td><td class="text-right">'.evaluar($Efectivo).'</td></tr>';
  $frm .= '<tr><td><b>A Credito : </b></td><td class="text-right">'.evaluar($Credito).'</td></tr>';
  $frm .= '<tr><td><b>Con TDC : </b></td><td class="text-right">'.evaluar($TDC).'</td></tr>';
  $frm .= '<tr><td><b>TOTAL  : </b></td><td class="text-right">'.evaluar($TOTAL).'</td></tr>';
  $frm .= '</tbody>';
  $frm .= '</table>';

  $frm .= '</div>';


  $frm .= '<div class=" col-sm-6 col-xs-6">';
  $frm .= ___VENTA_SUBCATEGORIA($REP,$f1,$f2);
  $frm .= '</div>';
  $frm .= '</div>';

  $frm .= '<div class="row">';
  $frm .= '<div class=" col-sm-12 col-xs-12">';
  $frm .= __VENTAS_DIARIAS($com,$REP);
  $frm .= '</div>';
  $frm .= '</div>';



  return $frm;
}

/* ---------------------------------- */
/* VENTAS FORMA DE PAGO               */
/* ---------------------------------- */

function Venta_total($obj,$array){
  $costo = '';
  
  $list_ticket = $obj->list_ticket($array);
  
  foreach($list_ticket as $key){ // TODOS LOS FOLIOS REGISTRADOS

    $sql_producto = $obj->Select_Productos(array($key[0]));
    foreach($sql_producto as $KEY){
     $costo = $costo + $KEY[3];
   }
 }

 return $costo;
}

function Venta_FormaPago($obj,$array,$campo){
  $costo = '';
  
  $list_ticket = $obj->list_ticket_forma_pago($array,$campo);
  
  foreach($list_ticket as $key){ // TODOS LOS FOLIOS REGISTRADOS

    $sql_producto = $obj->Select_Productos(array($key[0]));

    foreach($sql_producto as $KEY){
     $costo = $costo + $KEY[3];
   }
 }

 return $costo;
}
/* ---------------------------------- */
/* VENTAS POR PRESENTACION            */
/* ---------------------------------- */

function ___VENTA_SUBCATEGORIA($obj,$f1,$f2){
  $SQL   =  $obj-> subcategoria();
  $total = 0;
  $frm  = '';
  $frm .= '<table  class="table table-striped table-condensed table-bordered">';
  $frm .= '<thead><tr>';
  $frm .= '<th class="text-info" colspan="2"><span style="font-size:1.5em;"> <i class="icon  icon-cubes "></i> VENTAS PRESENTACION</span></th></tr></thead>';
  $frm .='<tbody>';
  
  foreach($SQL as $X){ // TODAS LAS CATEGORIAS
    $sub  = array();
    $sub  = __DETALLE_SUBCATEGORIA($obj,array($X[0],$f1,$f2)); // TODOS LOS REGISTROS X SUBCATEGORIA
    $frm .='<tr>';
    $frm .='<td class="text-success"> <b>'.$X[1].'</b></td>';
    $frm .='<td class="text-right">'.evaluar($sub[1]).'</td>';
    $frm .='</tr>';

    $frm .=$sub[0];
    
    $total += $sub[1];
  }
  $frm .='<tr>';
  $frm .='<td><b>TOTAL</b></td>';
  $frm .='<td class="text-right">'.evaluar($total).'</td>';
  $frm .='</tr>';
  $frm .= '</tbody></table>';
  return $frm;
}

function __DETALLE_SUBCATEGORIA($obj,$array){

	$frm                = '';
  $total_subcategoria = 0;  
  $sub_categoria      = $obj ->subcategoria_productos(array($array[0]));
  
  foreach($sub_categoria as $X){ // productos por subcategoria
    $total        = 0;
    $data         = array($array[0],$array[1],$array[2],$X[0]);
    
    // $frm .='<tr>';
    // $frm .='<td class="text-danger" ><b>'.$X[1].'  ('.$X[2].')</b></td>';
    
    $sub_desglozar = $obj ->detalle_por_subcategoria($data);
    
    foreach($sub_desglozar as $Y){
      // $frm .='<tr>';
      // $frm .='<td> '.$Y[8].'  /// '.$Y[0].' /// '.$Y[5].'  ('.$Y[7].')</td>';
      // $frm .='<td class="text-right"> '.$Y[1].' x '.$Y[2].' = '.evaluar($Y[3]).'</td>';
      // $frm .='</tr>';
      $total = $total + $Y[3];
    }

    $total_subcategoria = $total_subcategoria + $total;
    //  $frm .='<td colspan="2" class="text-right">'.evaluar($total).'</td>';
    //  $frm .='</tr>';
  }
  

  $array  = array(0=>$frm, 1=> $total_subcategoria);
  return $array;
}

/* ---------------------------------- */
/* Reporte de ventas diarias         */
/* ---------------------------------- */
function __DESGLOZE_MENSUAL($com,$ventas,$f1,$f2){
 
 $tb   = '';
 $anio = 2021;

 $th[]          = '#';
 $th[]          = 'Productos 2021';
 $tb  .= '<table style="width:100%" id="allVentas" style="font-size:.75em;" class="table table-bordered table-condensed">';
 for($x = 1; $x  <= 12 ; $x++ ){ 
  $th[]         = __MES($x); 
 }
 $tb  .=  __THEAD_ALIGN($th);

 $tb  .='<tbody>';
 $sql     = $ventas ->Select_tabla_productos();
 
 
 $cont   = 0;
 


 foreach($sql as $KEY){
  $cont   = $cont +1;
 

  $tb  .= '<tr>';
  $tb  .= '<td>'.$cont.'</td>';
  $tb  .= '<td>'.$KEY[1].' ('.$KEY[2].')  </td>';


  # Recorrido por meses

  for($mes = 1; $mes  <= 12 ; $mes++ ){ 
   
   $total     = 0;
   $sumar     = 0;
   $productos = 0; 

   $list_folio  =    $ventas -> obtener_folios(array($mes , $anio, $KEY[0]));

   $contar = count($list_folio);
   $txt = '';

   foreach ($list_folio as $key_folio ) {
     $total     =  $key_folio[5] * $key_folio[6];
     $sumar     =  $sumar + $total;
     $productos =  $productos + $key_folio[6];

     $txt .= '<p>    '.$key_folio[5].' * '.$key_folio[6].' =  '.$total.' </p>';

   } 


    $separador = '-';
    // if( $productos ==0) { $productos = ''; } else { $productos = $productos.' productos';  $separador = '';} 
    // if( $sumar == 0) { $sumar= ''; } else { $sumar= '$ '.number_format($sumar,2,'.',',').' venta'; } 
    if($productos==0){
  $tb  .= '<td class="text-center">-</td>';
    
    }else{
    $tb  .= '<td class="text-center"> '.evaluar3($productos).'pzas / '.number_format($sumar,2,'.',',').' </td>';

    }

    // $tb  .= '<td> '.number_format($sumar,2,'.',',').'</td>';
    // $tb  .= '<td> '.number_format($sumar,2,'.',',').'</td>';
  } 




  $tb  .= '</tr>';

 }

 $tb  .= '</table>';
 return $tb;
}







function __VENTAS_DIARIAS($com,$ventas){

  $fechaInicio   = strtotime($_POST['f_i']);
  $fechaFin      = strtotime($_POST['f_f']);
  $total         = 0;
  $tb            = '';
  $th            = array();
  $th[]          = 'Productos';
  

  for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
   $date       = date("d-m-Y", $i);
   $fecha      =  $com ->separar_fecha ($date);
   $th_fecha   =  Mes_Letra($fecha[1]).'-'.$fecha[2];
   $th[]       =  $th_fecha ;
 }
  $th[]        = 'Total';
  
 $tb  .= '<table style="width:100%" id="allVentas" style="font-size:.75em;" class="table table-bordered table-condensed">';
 $tb  .=  __THEAD_ALIGN($th);
  /* --------------TBODY----------------- */
 $tb  .='<tbody>';
 $sql     = $ventas ->Select_tabla_productos();
 foreach($sql as $KEY){
    $total         = 0;
  
    $tb  .= '<tr>';
     // 
    $tb  .= '<td>'.$KEY[1].' ('.$KEY[2].')  </td>';

    for($x=$fechaInicio; $x<=$fechaFin; $x+=86400){
      $date   = date("Y-m-d", $x);
      $data   =   SUMAR_DIAS($ventas,$date,$KEY[0]);
      $total  =   $total + $data[0];
      $tb    .= '<td class="text-right">'.evaluar2($data[0]).'</td>';

    }
     $totalProducto = $totalProducto + $total;
     $tb  .= '<td class="text-right">'.evaluar2($total).'</td>';
     $tb  .='</tr>';

  }

   $tb  .= '</tbody>';
  //  $tb  .='<tfoot>'; 
  //  $tb  .= '<tr style="background:#F5F5F5;"><td> <b>TOTAL: </b></td>';
    
  //  for($x=$fechaInicio; $x<=$fechaFin; $x+=86400){
  //       $tb .= '<td class="text-right">'.evaluar($totalProducto).'</td>';
  //   }

  //   $tb  .= '<td> <b>$ </b></td></tr>'; 
  //  $tb  .='</tfoot>'; 


 $tb  .= '</table>';
 return $tb;
}

function __THEAD_ALIGN($th){
 $tb = '';
 $tb .= '<thead><tr >';
 
 for ($i = 0; $i < count($th); $i++) {
    $tb .= '<td class="text-center" style="background:#F5F5F5;" ><span><b>' . $th[$i] .'</b></span></td>';
 }
 $tb .= '</tr></thead>';
 return $tb;
}

function SUMAR_DIAS($ventas,$date,$idProducto){
  $total   = 0;
  $txt     = '';
  $sql     = $ventas-> SUMA_FECHA(array($date,$idProducto));
  
  foreach($sql as $KEY){

   $total  = $total + $KEY[3];
   $txt    .=  $KEY[3].' / ';
  }
  
  $array   = array(0=>$total,1=>$txt);
  return $array;          
}

function Mes_Letra($mes){
    switch ($mes) {
      case 1: return 'Ene'; break;
      case 2: return 'Feb'; break;
      case 3: return 'Mar'; break;
      case 4: return 'Abr'; break;
      case 5: return 'May'; break;
      case 6: return 'Jun'; break;
      case 7: return 'Jul'; break;
      case 8: return 'Ago'; break;
      case 9: return 'Sep'; break;
      case 10: return 'Oct'; break;
      case 11: return 'Nov'; break;
      case 12: return 'Dic'; break;
    }
}

function Venta_total_Tabla($obj,$array){
  $tb = '<table class="table table-bordered">';
  $total = 0;
  $list_ticket = $obj->list_ticket($array);
  
  foreach($list_ticket as $key){ // TODOS LOS FOLIOS REGISTRADOS

    $FORMA_DE_PAGO =   ico_tipo_pago($key[8], $key[9], $key[10], $key[11]);
    $tb .='<tr><td><h3>'.$key[0].'</h3></td><td>'.$key[2].'</td></tr>';
    $sql_producto = $obj->Select_Productos(array($key[0]));

    foreach($sql_producto as $KEY){
     $total += $KEY[3];
     $tb .='<tr><td>'.$KEY[0].'</td><td>'.$KEY[3].' / <b>'.$total.'  '.$FORMA_DE_PAGO.'</b></td></tr>';
   }
 }
 $tb .= '</table>';
 return $tb;
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

function ico_tipo_pago($Mixto, $Efectivo, $TDC, $Credito){
  $ico = '';
  if ($Mixto == 1) {
    $ico = ' Pago Mixto';
  } else {

    if ($Efectivo != 0) {
      $ico = 'Efectivo';
    } else if ($TDC != 0) {
      $ico = 'TDC';
    } else if ($Credito != 0) {
      $ico = 'Credito';
    }
  }
  // no Mixto

  return $ico;
}

function evaluar2($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res =''.number_format($val, 2, '.', ',');
 }

 return $res;
}

function evaluar3($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res =''.$val;
 }

 return $res;
}

?>