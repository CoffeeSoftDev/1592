<?php 

include_once("../../../modelo/SQL_PHP/_METAS.php");
$obj        = new METAS;

$fi         = $_POST['fi'];
$ff         = $_POST['ff'];

#----
$caratula_gral = '';
$ingresos_tur  = '';
$formas_pago   = '';
$formas_pago_p = '';

/*------- INGRESOS GENERALES ---------*/
$categorias        = $obj -> VER_CATEGORIAS(1);
$subtotal          = 0;
$graltotal         = 0;
$TotalFormasPago   = 0;
$TotalPropinas     = 0;
$Cargos_hab_des    = 0;
$contador          = 5;



$ingresos_tur  .= '

<div id="Reporte1592" style="margin-top:20px" class="col-sm-offset-1 col-sm-10">
<table  class="table table-bordered table-xtra-condensed" 
style="font-size:.89em margin-bottom: 10px;"   Id="reporte_gral">
<thead>

<tr class="bg-default">
<td class="text-center" colspan="5">REPORTE GENERAL</td>

</tr>

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

 $ingreso_categoria = $obj -> ver_ingresos_turismo($fi,$ff,$key[0]);
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
 
 
 # ---- Caratula
 $ingresos_tur  .= '
 <tr>
 <td id="col_1" class="pointer" onclick="Subcategoria('.$key[0].'); activarTab()">'.$key[1].' </td>
 
 <td class="text-right">'.evaluar($subT).'</td>
 <td class="text-right '.$bgIva.'">'.evaluar($IVA16).'</td>
 <td class="text-right '.$bgHosp.'">'.evaluar($IVA2).'</td>
 <td class="text-right">'.evaluar($ingreso_categoria).'</td>
 
 ';

 $subtotal  += $subT;
 $graltotal += $ingreso_categoria;
 $ingresos_tur .='</tr>';


 #Cargo Habiracion ----------------
if ($contador < 11) { // Solo existen 10 fp de pago 18062019

  if ($contador != 10 && $contador !=8) {
   $cargos = $obj -> ver_cargos_hab($contador,$fi,$ff);
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

 $contador += 1;

}


/*--------- Cortesia y empleados -----------*/
$cortesias_empleados = 0;
$cortesias = $obj ->Select_empleadosCortesia(array($fi,$ff));


foreach ($cortesias as $key) {
 $cortesias_empleados = $cortesias_empleados + $key[2];
 $graltotal+= $key[2];
 

}

if(count($cortesias)){
$ingresos_tur .='<tr>
 <td>'.$key[1].'</td>
 <td></td>
 <td></td>
 <td></td>
 <td class="text-right">'.evaluar($cortesias_empleados).'</td>
 </tr>';

}




$ingresos_tur.='
<tr>
<td>TOTAL NETO:</td>

<td class="text-right">'.evaluar($subtotal).'</td>
<td></td>
<td></td>
<td></td>

</tr>


<tr class="bg-primary">
<td colspan="3">
<strong>TOTAL INGRESOS:</strong>
</td>
<td colspan="3" class="text-right">'.evaluar($graltotal).'</td>
</tr>';


/*     --- Formas de Pago ---   */

$obj     = new METAS;
$formas  = $obj -> VER_FORMAS_PAGO();
$contara = 0;
$verInfo = $obj -> verFolio($fi,$udn);
$idF     = "";
$Obs     = "";

$ingresos_tur   .= '
<tr class="bg-info">
<td >FORMA DE PAGO</td>
<td class="">TOTAL</td>
<td><span class="fa fa-pencil"></span> OBSERVACIONES</td>
<td></td>
<td></td>
</tr>
';

foreach ($verInfo as $key ) {
 $idF = $key[0];
 $Obs = $key[4];
}

$descripcion = _td($idF,"desc",$Obs,"rowspan='6' colspan = '3' ");


$Efec      = $obj -> VER_TIPOSPAGOS_FECHA(1,$fi,$ff);
$TC        = $obj -> VER_TIPOSPAGOS_FECHA(2,$fi,$ff);
$cxcOtros  = $obj -> formas_pago(3,$fi,$ff,1);
$Anticipos = $obj -> VER_TIPOSPAGOS_FECHA(4,$fi,$ff);
$cxcHab    = $obj -> formas_pago(3,$fi,$ff,2);
$cxcOtros  = $cxcOtros + $Cargos_hab_des;


$ingresos_tur   .=  '
<tr><td>EFECTIVO</td><td class="text-right">'.evaluar($Efec).'</td>'.$descripcion.'</tr>

<tr><td>TARJETA DE CREDITO O DEBITO</td><td class="text-right">'.evaluar($TC).'</td></tr>
<tr><td>CxC OTROS SERVICIOS</td><td class="text-right">'.evaluar($cxcOtros).'</td></tr>
<tr><td>ANTICIPOS</td><td class="text-right">'.evaluar($Anticipos).'</td></tr>
<tr><td>CxC HABITACIONES </td><td class="text-right">'.evaluar($cxcHab).'</td></tr>
<tr><td>CORTESIA Y COMIDA DE EMPLEADOS</td><td class="text-right">'.evaluar($cortesias_empleados).'</td></tr>';

$TotalFormasPago = $Efec + $TC + $cxcOtros + $Anticipos + $cxcHab + $cortesias_empleados;


$ingresos_tur .='

<tr class="bg-info">
<td>TOTAL FORMAS DE PAGO:</td>
<td class="text-right">'.evaluar($TotalFormasPago).'</td>
<td></td>
<td></td>
<td></td>
</tr>
';

$ingresos_tur .='
<tr>
<td colspan="5">&nbsp;</td>
</tr>
';

#------------------------------------#
# Formas de pago propina
#------------------------------------#

$ingresos_tur .= '
<tr class="bg-info">
<td>FORMA DE PAGO PROPINA</td>
<td>TOTAL</td>
<td></td>
<td></td>
<td></td>
</tr>
';

$fp = $obj -> Select_formaspago_by_categoria(array(1));

foreach ($fp as $key ) { // CXC,T.P
 $Ok      = $obj -> VER_PROPINA_FECHA($key[0],$fi,$ff,9);
 $TotalPropinas += $Ok;
 
 $ingresos_tur .= '
 <tr>
 <td>'.$key[1].'</td>
 <td class="text-right" > '.evaluar($Ok).'</td>

 <td></td>
 <td></td>
 <td></td>
 </tr>';

}


$ingresos_tur .='
<tr class="bg-info">
<td>TOTAL FORMAS DE PAGO PROPINA:</td>
<td class="text-right">'.evaluar($TotalPropinas).'</td>
<td></td>
<td></td>
<td></td>

</tr>';




$ingresos_tur .='
<tr>
<td>TOTAL GENERAL </td>
<td></td>
<td></td>
<td></td>

<td class>'.evaluar($TotalPropinas+$TotalFormasPago).'</td>


</tr>
';



// Fin de caratula

$ingresos_tur .='
</tbody>
</table>
</div>';



# --------------------------------
# Funciones
# --------------------------------

$encode = array(
 0=>$ingresos_tur);


 echo json_encode($encode);

 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="" || $val == null) {
   $res = '-';
  //  $res = '- '.number_format(0, 2, '.', ',');
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }
  return $res;
 }

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



 ?> 