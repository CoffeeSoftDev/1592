<?php
session_start();
include_once("../../../modelo/SQL_PHP/_METAS.php");
$obj        = new METAS;

include_once("../../../modelo/SQL_PHP/_CXC.php");
$cxc        = new CXC;

$fi         = $_POST['date1'];
$ff         = $_POST['date2'];
$udn        = $_POST['udn'];
$user       = $_SESSION['user'];

$encargado  = '';
$txt        = '';
$id_fol     = '';
$txtFecha   = "De ".date("d/m/Y", strtotime($fi))." a ".date("d/m/Y", strtotime($ff));

if ($fi==$ff) {
 $txtFecha   = "".date("d/m/Y", strtotime($fi));
}

$sql_fol = $obj -> verFolio($fi,$udn);
foreach ($sql_fol as $v_fol){
 $id_fol    = $v_fol[0];
 $encargado = $v_fol[5];
}

// -------
$caratula_gral = '';
$ingresos_tur  = '';
$formas_pago   = '';
$formas_pago_p = '';


/*------- INGRESOS GENERALES ---------*/
$categorias        = $obj -> VER_CATEGORIAS($udn);
$subtotal          = 0;
$graltotal         = 0;
$TotalFormasPago   = 0;
$TotalPropinas     = 0;
$Cargos_hab_des    = 0;
$contador          = 5;

$ingresos_tur  .= '

<div class="">
<table  class="table table-bordered"  style="font-size:.89em margin-bottom: 10px;"   Id="size1">
<thead>
<tr>
<th>INGRESOS TURISMO </th>
<th class="bg-primary">SUBTOTAL</th>
<th class="bg-primary">IVA</th>
<th class="bg-primary">2% HOSP</th>
<th class="bg-primary">TOTAL</th>
</tr>
</thead>
<tbody>';

foreach ($categorias as $key ) { // ingresos ej. Hospedaje,Restaurant,Tours...
 $bgIva  = '';
 $bgHosp = '';


 $IVA2              = 0;
 // $ingreso_categoria = $obj -> VER_INGRESOS_FECHA($key[0],$fi,$ff);
 $ingreso_categoria    = $obj -> ver_ingresos_turismo($fi,$ff,$key[0]);

 $subT              = $ingreso_categoria;

 switch ($key[0]) {
  case 1:  // HOSPEDAJE cargo del 16 % & 2 %
  $subT  = $subT / 1.18;


  $IVA16  = $subT * .16;
  $IVA2   = $subT * .02;
  break;

  case 9:
  $IVA16 = 0;
  $bgIva = '';
  break;

  case 11:
  $subT   = 0;
  break;

  default:
  $IVA16  = ($ingreso_categoria/100)* 16;
   $subT  = $subT / 1.16;
  break;
 }

 $TOTAL   = $ingreso_categoria+$IVA16+$IVA2;
 //

 //  $ingresos_tur  .= '<tr><td>'.$key[1].'</td>
 // <td>'.$monto_total.'</td>
 //  </tr>';


 $ingresos_tur  .= '
 <tr>
 <td id="col_1" class="pointer" onclick="Subcategoria('.$key[0].'); activarTab()">'.$key[1].'</td>
 <td class="text-right">'.evaluar($subT).'</td>
 <td class="text-right '.$bgIva.'">'.evaluar($IVA16).'</td>
 <td class="text-right '.$bgHosp.'">'.evaluar($IVA2).'</td>
 <td class="text-right">'.evaluar($ingreso_categoria).'</td>
 ';

 $subtotal  += $subT;
 $graltotal += $ingreso_categoria;
 $ingresos_tur .='</tr>';
 //
 // /*--------- cargos de habitacion -----------*/
 //
//  if ($contador < 11) { // Solo existen 10 fp de pago 18062019

  if ($contador != 10 && $contador !=8) {
   $cargos = $obj -> ver_cargos_hab($contador,$fi,$ff);

   if ($cargos[2] != 0) {
      $n_fp   = $obj -> ver_nombre_fp(array($contador));
      $graltotal+= $cargos[2];
      $Cargos_hab_des += $cargos[2];

      
      $ingresos_tur.='
      <tr class="bg-default">
      <td id="col_1">'.$n_fp.' CARGO HABITACION </td>
      <td></td>
      <td></td>
      <td></td>
      <td class="text-right">'.evaluar($cargos[2]).'</td>
      </tr> ';
   }
  }

//  }

 $contador += 1;
}


/*--------- Cortesia y empleados -----------*/
$cortesias_empleados = 0;
$cortesias = $obj ->Select_empleadosCortesia(array($fi,$ff));


foreach ($cortesias as $key) {
 $cortesias_empleados = $cortesias_empleados + $key[2];
 $graltotal+= $key[2];
 $ingresos_tur .='<tr>
 <td>'.$key[1].'</td>
 <td></td>
 <td></td>
 <td></td>
 <td class="text-right">'.evaluar($key[2]).'</td>
 </tr>';
}


$ingresos_tur.='
</tbody>
<tfoot>
<tr>
<td>
<strong>TOTAL NETO:</strong>
</td>
<td class="text-right">'.evaluar($subtotal).'</td>
<td colspan="4"></td>
</tr>
<tr class="bg-info">
<td colspan="3">
<strong>TOTAL INGRESOS:</strong>
</td>
<td colspan="3" class="text-right">'.evaluar($graltotal).'</td>
</tr>
</tfoot>
</table>
</div>';





$obj     = new METAS;
$formas  = $obj -> VER_FORMAS_PAGO();
$contara = 0;
$verInfo = $obj -> verFolio($fi,$udn);
$idF     = "";
$Obs     = "";

$formas_pago   .= '
    <table class="table table-bordered" Id="size1">
    <thead>
    <tr class="bg-info">
    <th class="col-xs-3">FORMA DE PAGO</th>
    <th class="col-xs-4 bg-primary">TOTAL</th>
    <th class="col-xs-5"><span class="fa fa-pencil"></span> OBSERVACIONES</th>
    </tr>
    </thead>
    <tbody>
';

foreach ($verInfo as $key ) {
 $idF = $key[0];
 $Obs = $key[4];
}

$descripcion = _td($idF,"desc",$Obs,"rowspan='6' colspan = '3' ");

// F.P. MONTOS

$Efec      = $obj -> VER_TIPOSPAGOS_FECHA(1,$fi,$ff);
$TC        = $obj -> VER_TIPOSPAGOS_FECHA(2,$fi,$ff);
$cxcOtros  = $obj -> formas_pago(3,$fi,$ff,1);
$Anticipos = $obj -> VER_TIPOSPAGOS_FECHA(4,$fi,$ff);
$cxcHab    = $obj -> formas_pago(3,$fi,$ff,2);
$cxcOtros  = $cxcOtros + $Cargos_hab_des;

$formas_pago   .=  '
<tr><td>EFECTIVO</td><td class="text-right">'.evaluar($Efec).'</td>'.$descripcion.'</tr>

<tr><td>TARJETA DE CREDITO O DEBITO</td><td class="text-right">'.evaluar($TC).'</td></tr>
<tr><td>CxC OTROS SERVICIOS</td><td class="text-right">'.evaluar($cxcOtros).'</td></tr>
<tr><td>ANTICIPOS</td><td class="text-right">'.evaluar($Anticipos).'</td></tr>
<tr><td>CxC HABITACIONES </td><td class="text-right">'.evaluar($cxcHab).'</td></tr>
<tr><td>CORTESIA Y COMIDA DE EMPLEADOS</td><td class="text-right">'.evaluar($cortesias_empleados).'</td></tr>';

$TotalFormasPago = $Efec + $TC + $cxcOtros + $Anticipos + $cxcHab + $cortesias_empleados;
$formas_pago .='
</tbody>
<tfoot>
<tr class="bg-info">
<td>TOTAL FORMAS DE PAGO:</td>
<td class="text-right">'.evaluar($TotalFormasPago).'</td>
<td></td>
</tr>
</tfoot>
</table>
';

/*--------- Cortesia y empleados -----------*/

$formas_pago_p .= '
<table class="table table-bordered" Id="size1">
<thead>
<tr class="bg-info">
<th class="col-sm-8">FORMA DE PAGO PROPINA</th>
<th class="bg-primary">TOTAL</th>
</tr>
</thead><tbody>';

$fp = $obj -> Select_formaspago_by_categoria(array(1));

foreach ($fp as $key ) { // CXC,T.P,
 $Ok      = $obj -> VER_PROPINA_FECHA($key[0],$fi,$ff,9);
 $TotalPropinas += $Ok;
 $formas_pago_p .= '<tr>
 <td id="col_1">'.$key[1].'</td>';
 $formas_pago_p .='<td class="text-right" > '.evaluar($Ok).'</td></tr>';

}

$formas_pago_p .='
</tbody>
<tfoot>
<tr class="bg-info">
<td>TOTAL FORMAS DE PAGO PROPINA:</td>
<td class="text-right">'.evaluar($TotalPropinas).'</td>
</tr>
</tfoot>
</table>';


$pie_pagina= '

<div style="" class="col-xs-12 ">
<div style=" width: 50%; float: left; position: relative;">
<h5>  <strong>ENTREGO</strong></h5>
<p style="font-size:.8em">'.$encargado.'</p>

</div>

<div style=" width: 50%; float: left; position: relative;" >

<h5>  <strong>RECIBIO </strong></h5>
<span></span>

</div>

</div>

';


/*-----------------------------------*/
/*		Caratula gnral
/*-----------------------------------*/
$block = '';

if ($udn==2) {

 if ($encargado==null || $encargado == '') {
  $block = 'disabled';
 }

}

$caratula_gral .= '<br><div class=" col-sm-12 text-right">';

if($id_fol== "" || $id_fol==null){
   if($user == 'FINANZAS'){
     $caratula_gral .= '<a class="btn btn-primary" onclick="CierreHotel(\'resumen_gral\')"><i class=" icon-print"></i> Imprimir caratula </a> ';
   }else{
    $caratula_gral .= '<a class="btn btn-default" onclick="CrearFolioHotel()"><i class="bx bx-file ico-1x"></i> Crear formato de reporte </a>';
   }
}else{
if($user == 'FINANZAS'){
     $caratula_gral .= '<a class="btn btn-default" onclick="CierreHotel(\'resumen_gral\')"><i class=" icon-print"></i> Imprimir caratula </a> ';
}else{
  $caratula_gral .= '<a class="btn btn-primary" onclick="Cierre_Dialogo('.$id_fol.')"><i class=" icon-print"></i> Imprimir caratula </a> ';
  $caratula_gral .= '<a class="btn btn-default '.$block.'" onclick="All_reports();"><i class=" icon-print"></i> Todos los reportes </a>';
}

}

$caratula_gral .='
</div>

<div style="" id="resumen_gral" class="col-sm-12 col-xs-12">
<div style="width:100%; font-size:  1.2em; font-weight:700; text-align:center;" > REPORTE GENERAL</div>
<div style="margin-top:10px; margin-bottom:10px;" class="text-right">
<label style="font-size:  1em;"> <strong> </strong> '.$txtFecha.'</label>
</div>

'.$ingresos_tur.'
'.$formas_pago.'
'.$formas_pago_p.'


<div class="text-right">

<label><h4>TOTAL GENERAL: '.evaluar($TotalPropinas+$TotalFormasPago).'</h4></label>
</div>

'.$pie_pagina.'


<div  class="col Res col-sm-12 col-xs-12 hide"></div>
<div id="tb_data" class="tb_data"></div>

</div>';
# Reporte de cxc
$caratula_gral .='
<div class="row">
<div class="col-sm-12">
________________________________
<h5>Cuentas por cobrar</h5>

<div>';

$title = array('Folio', 'Fecha','Descripcion', 'CxC',  'Opción');
$tb    = lista_ticket($cxc,$title);

$caratula_gral .= $tb;

$caratula_gral .='</div>
';





// ===========================================
//     ENCODE JSON
// ===========================================
$encode = array(
 0=>$caratula_gral);
 echo json_encode($encode);

function lista_ticket($obj,$th){
$f1         = $_POST['date1'];
$ff         = $_POST['date2'];

$fechaEntera = strtotime($f1);
 $anio      = date("Y", $fechaEntera);
 $mes = date("m", $fechaEntera);
 $dia = date("d", $fechaEntera);

 $f_i = $anio.'-'.$mes.'-01';
 $f_f = $anio.'-'.$mes.'-'.$dia;
//
$ListFolio = $obj->Ver_Folio(array($f_i,$f_f));


$tb  =  '<br> Folio:  | '.$f_i.'  >> '.$f_f;


$tb .= '<div style="margin-top:20px;" class="table-">';
$tb .= '<table id="viewFolios" class="table table-bordered  table-xtra-condensed table-hover pd-10"  style="width:100%; font-size:.8em; font-weight:700;">';

/*----------THEAD------------*/
$tb .= '<thead><tr>';

for ($i = 0; $i < count($th); $i++) {
  $tb .= '<th class="text-center">' . $th[$i] . ' </th>';
}

$tb .= '</tr></thead>';

/*----------TBODY------------*/
$tb .= '<tbody>';

foreach ($ListFolio as $key) {
  #Recorrido por folios

    #Recorrido por movimientos
    $List_ticket = $obj->ver_bitacora_ventas(array($key[0]));
    foreach ($List_ticket as $_key) {

      # -----
      $List_formas_pago = $obj-> bitacora_formas_pago_full(array($_key[0]));
    //
      foreach ($List_formas_pago as $__key) {
        if($__key[3] !=0) {

          $tb .= '<tr>';
          $tb .= '<td class="text-center text-danger">' . $key[1] . '  / '.$__key[0].' </td>';
          $tb .= '<td class="text-center">' . $key[2] . '</td>';
          $tb .= '<td >' . $_key[1] . '   ('.$__key[1].')</td>';
          $tb .= '<td class="text-right">' .evaluar($__key[3]). '</td>';

        # Formas de pago
          $tb .= '
          <td class="text-center col-sm-2">
          '.$__key[5].'
          </td>';

          $tb .= '</tr>';
      }
      }//END LIST FORMAS DE PAGO
    }

}// END FOLIOS

$tb .= '</tbody>';
$tb .= '</table></div>';


return $tb;

}


 /*==========================================
 *		FUNCIONES / FORMULAS
 =============================================*/
 //

 function _td($id,$campo,$valor,$conf){
  $txt='
  <td  class="point" id="txt'.$campo.$id.'" '.$conf.'>

  <div  onclick="col('.$id.',\''.$campo.'\' )">
  <label style="width:100%; height:120px;" id="lbl'.$campo.$id.'">'.$valor.'</label>
  </div>
  </td>
  ';
  return $txt;
 }



 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="" || $val == null) {
   $res = '$ '.number_format(0, 2, '.', ',');
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }
  return $res;
 }



 ?>
