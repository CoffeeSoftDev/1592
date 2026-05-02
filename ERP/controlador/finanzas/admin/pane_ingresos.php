<?php

include_once('../../../modelo/SQL_PHP/_Finanzas_ADMIN.php');
include_once('../../../modelo/SQL_PHP/_Utileria.php');
$fin = new Finanzas;
$util = new Util;

$opc = $_POST['opc'];
$fi   =$_POST['date1'];
$ff   =$_POST['date2'];
$udn  =$_POST['udn'];

// ------------

$Sub       = 0;
$IVA       = 0;
$IVA2      = 0;
$TOTALGRAL = 0;
$DIF       = 0;
$t2        = 0;
$txt       ='';

// -------------
$Efectivo  = 0;
$TC        = 0;
$CxC       = 0;
$Anticipo  = 0;
// -------------

/*==========================================
*		FUNCIONES / FORMULAS
=============================================*/

function evaluar($val){
   $res = '';
   if ($val==0 || $val=="" || $val == null) {
      $res = '-';
   }else {
      $res ='$ '.number_format($val, 2, '.', ',');
   }

   return $res;
}


switch ($opc) {
 case 0://IMPRESION DE CATEGORIAS
     $sql = $fin->Select_Categoria($udn);
     echo '<br>

     <div class="row">
     <div class="form-group col-sm-12 col-xs-12">

     <ul class="nav nav-tabs">';
     foreach ($sql as $key => $value) {
      $active = '';
      if ( $key == 0){
       $active = 'active';
      }
      echo '
      <li class="'.$active.'">
      <a class="text-warning" data-toggle="tab" href="#tab" onClick="Subcategoria('.$value[0].');"> <strong>'.$value[1].'</strong></a>
      </li>
      ';
     }
     echo '
     <li class="'.$active.'">
     <a class="text-warning" data-toggle="tab" href="#tab" onClick="Ver_Tc();"> <strong>T.C.</strong></a>
     </li>
     </ul>

     <div class="tab_content_subcategoria"></div>
     </div>
     </div>
     ';
   break;
 case 1: //IMPRESION DE SUBCATEGORIAS Y CONSULTA DE TABLA
     // $date = $_POST['date'];
     $id = $_POST['id'];

     $sub = $fin->Select_Subcategoria($id);
     $imp = $fin->Select_Impuestos($id);
     $fpago = $fin->Select_formaspago();

     $txt=$txt.'
     <br>
     <div class="row">
     <div class="form-group col-sm-12 col-xs-12 text-center" id="Group_Res">
    <label>Del '.$fi.' al '.$ff.'</label>
     </div>
     <div class="form-group col-sm-12 col-xs-12 table-responsive">

     <table class="table  table-bordered table-hover table-stripped">
     <thead>
     <tr>
     <th class="text-center">SubCategoria</th>
     <th class="text-center col-sm-1 col-xs-1">Subtotal</th>';

     foreach ($imp as $key => $value) { // Impuestos
      $txt=$txt. '<th class="text-center col-sm-1 col-xs-1">'.$value[1].'</th>';
     }

     $txt=$txt. '<th class="text-center col-sm-1 col-xs-1">Total</th>';
     foreach ($fpago as $key => $value) { // FORMAS DE PAGO ------------

      $txt=$txt. '<th class="text-center col-sm-1 col-xs-1">'.$value[1].'</th>';
     }


     $txt=$txt.
     '<th class="text-center col-sm-1 col-xs-1">Total</th>
     <th class="text-center col-sm-1 col-xs-1">Diferencia</th>
     </tr></thead><tbody>';


     foreach ($sub as $key => $value) {
      $monto = $fin->Select_MontoSubtotal($value[0],$fi,$ff);
      $Sub +=$monto;
      $txt=$txt. '
      <tr>
      <td>'.$value[1].'</td>
      <td class="text-right">'.evaluar($monto).'</td>';

      foreach ($imp as $key => $valimp) {
       $monto = $fin->Select_MontoImpuesto($value[0],$valimp[0],$fi,$ff);
       switch ($key) {
        case 0:  $IVA  += $monto;   break;
        case 1:  $IVA2 += $monto;   break;

       }

       $IVA += $monto;
       $txt=$txt. '<td class="text-right"><label>'.evaluar($monto).'</label> </td>';
      }


      $total1 = $fin->Select_Total1($value[0],$fi,$ff);

      $total1_s = number_format($total1,2,'.','');
      $TOTALGRAL +=$total1;
      $txt=$txt.'<td  class="text-right bg-info">'.evaluar($total1).'</td>';



      foreach ($fpago as $key => $valfpago) {
       $monto = $fin->Select_MontoFPago($value[0],$valfpago[0],$fi,$ff);
       switch ($key) {

        case 0:  $Efectivo += $monto; break;
        case 1:  $TC       += $monto; break;
        case 2:  $CxC      += $monto; break;
        case 3:  $Anticipo += $monto; break;

       }
       $txt=$txt.'<td class=" text-right">'.evaluar($monto).'</td>';
      }

      $total2 = $fin->Select_Total2($value[0],$fi,$ff);
      $total2_s = number_format($total2,2,'.','');


      $diferencia = $total1_s - $total2_s;


      // ---
      $t2         += $total2;
      $DIF        += $diferencia;
      // ---
      $txt=$txt.
      '<td class="bg-info text-right" >'.evaluar($total2).'</td>
      <td class="bg-danger text-right" >'.evaluar($diferencia).'</td>
      </tr>';
     }

     $txt=$txt.
     '
     </tbody>
     <tfood>
     <tr class="text-right">
     <td></td>
     <td>'.evaluar($Sub).'</td>';

     for ($i=0; $i <  count($imp); $i++) {
      switch ($i) {
       case 0:  $txt=$txt.'<td>'.evaluar($IVA).'</td>';  break;
       case 1:  $txt=$txt.'<td>'.evaluar($IVA2).'</td>'; break;

      }

     }

     $txt=$txt.'<td>'.evaluar($TOTALGRAL).'</td>';

     for ($i=1; $i <=  count($fpago); $i++) {
      switch ($i) {

       case 1: $txt=$txt.'<td>'.evaluar($Efectivo).'</td>'; break;
       case 2: $txt=$txt.'<td>'.evaluar($TC).'</td>'; break;
       case 3: $txt=$txt.'<td>'.evaluar($CxC).'</td>'; break;
       case 4: $txt=$txt.'<td>'.evaluar($Anticipo).'</td>'; break;

      }

     }


     $txt=$txt.'
     <td>'.evaluar($t2).'</td>
     <td>'.evaluar($DIF).'</td>
     </tr>
     </tfood>
     </table></div></div>
     ';
     echo $txt;
   break;
 case 2://GUARDAR SUBTOTAL Y CALCULAR impuestos
     $valor = $_POST['valor'];
     $date = $_POST['date'];
     $idC = $_POST['idC'];
     $idS = $_POST['idS'];

     // BUSCAR Y/O INSERTAR EL FOLIO
     $idF = $fin->Select_idFolio($date);
     if ( $idF == 0) {
      $Folio = $fin->Select_FolioDesc($date);
      $nFolio = $Folio + 1;
      $fin->Insert_Folio($nFolio,$date);
      $idF = $fin->Select_idFolio($date);
     }

     //BUSCAR, INSERTAR Y/O ACTUALIZAR LA BITACORA SUBTOTAL
     $array = array($idF,$idS);
     $idSBit = $fin->Select_idSubBitacora($array);
     if ( $idSBit == 0) {
      $array = array($idF,$idS,$valor);
      $fin->Insert_SubBitacora($array);
      $array = array($idF,$idS);
      $idSBit = $fin->Select_idSubBitacora($array);
     }
     else {
      $array = array($valor,$idSBit);
      $fin->Update_SubBitacora($array);
     }

     $res1 = array();
     $res2 = array();
     $total = $valor;

     //BUSCAR, INSERTAR Y/O ACTUALIZAR IMPUESTOS
     $imp = $fin->Select_Impuestos($idC);
     foreach ($imp as $val) {
      $res1[] = $val[0];
      $vimp = $val[2] / 100;//valor
      $impuesto = $valor * $vimp;
      $res2[] = number_format($impuesto,2,'.',',');
      $total = $total + $impuesto;

      $idImpBit = $fin->Select_idImpBitacora($idSBit,$val[0]);
      if ( $idImpBit == 0) {
       $array = array($idSBit,$val[0],$impuesto);
       $fin->Insert_ImpBitacora($array);
      }
      else {
       $array = array($impuesto,$idSBit,$val[0]);
       $fin->Update_ImpBitacora($array);
      }
     }

     $res0 = array(number_format($valor,2,'.',','),number_format($total,2,'.',','));


     $resultado = array_merge($res0,$res1,$res2);

     echo json_encode($resultado);
   break;
 case 3://GUARDAR FORMAS DE PAGO
     $valor = $_POST['valor'];
     $date = $_POST['date'];
     $idS = $_POST['idS'];
     $idFP = $_POST['idFP'];

     // BUSCAR Y/O INSERTAR EL FOLIO
     $idF = $fin->Select_idFolio($date);
     if ( $idF == 0) {
      $Folio = $fin->Select_FolioDesc($date);
      $nFolio = $Folio + 1;
      $fin->Insert_Folio($nFolio,$date);
      $idF = $fin->Select_idFolio($date);
     }

     $array = array($idF,$idS);
     $idSBit = $fin->Select_idSubBitacora($array);
     if ( $idSBit == 0) {
      echo 'L';
     }
     else {
      $array = array($idSBit,$idFP);
      $idFPBit = $fin->Select_idFPBitacora($array);
      if ( $idFPBit == 0) {
       $array = array($idSBit,$idFP,$valor);
       $fin->Insert_FPBitacora($array);
      }
      else {
       $array = array($valor,$idSBit,$idFP);
       $fin->Update_FPBitacora($array);
      }
      if ( $valor == 0) { $valor = '-'; } else { $valor = '$ '.number_format($valor,2,'.',','); }
      echo $valor;
     }
   break;
 case 4://CALCULAR DIFERENCIAS
     $date = $_POST['date'];
     $idS = $_POST['idS'];

     $total1 = $fin->Select_Total1($date,$idS);
     $total1_s = $total1;
     if ( $total1 == 0 ) { $total1 = '-'; } else { $total1 = '$ '.number_format($total1,2,'.',','); }

     $total2 = $fin->Select_Total2($date,$idS);
     $total2_s = $total2;
     if ( $total2 == 0 ) { $total2 = '-'; } else { $total2 = '$ '.number_format($total2,2,'.',','); }

     $diferencia = $total1_s-$total2_s;
     if ( $diferencia == 0 ) { $diferencia = '-'; } else { $diferencia = '$ '.number_format($diferencia,2,'.',','); }

     $respuesta = array($total1,$total2,$diferencia);
     echo json_encode($respuesta);
   break;
 case 5://DATE NOW
     $date = $fin->NOW();
     echo $date;
   break;
 case 6://BOTON ACTUALIZAR
      echo '
      <div class="row">
        <br>
        <div class="form-group col-sm-12 col-xs-12 text-right">
          <button type="button" class="btn btn-sm btn-primary col-sm-2 col-sm-offset-10" onClick="table_tc();">Actualizar Tabla</button>
        </div>
        <br>
        <div class="form-group col-sm-12 col-xs-12 tb_data"></div>
      </div>
      ';
  break;
 case 7://TABLA DE TC
      $sql = $fin->Select_TC_Data($fi,$ff);

      echo '
      <div class="col-sm-12 col-xs-12">
        <table class="table table-responsive table-hover table-bordered table-striped table-condensed" id="tbtc">
          <thead>
            <tr>
              <th class="text-center">FECHA</th>
              <th class="text-center">MONTO</th>
              <th class="text-center">TERMINAL</th>
              <th class="text-center">TIPO DE TC</th>
              <th class="text-center">CONCEPTO DE PAGO</th>
              <th class="text-center">ESPECIFICACIÓN</th>
              <th class="text-center">NOMBRE DEL CLIENTE</th>
              <th class="text-center">NÚMERO DE AUTORIZACIÓN</th>
              <th class="text-center">NÚMERO DE AUTORIZACIÓN</th>
            </tr>
          </thead>
          <tbody>';
          foreach ($sql as $key => $value) {
            echo '
            <tr>
              <td class="text-right">'.$value[8].'</td>
              <td class="text-right"><span class="icon-dollar"></span>'.number_format($value[0],2,'.',',').'</td>
              <td class="text-center">'.$value[1].'</td>
              <td>'.$value[2].'</td>
              <td>'.$value[3].'</td>
              <td>'.$value[4].'</td>
              <td>'.$value[5].'</td>
              <td class="text-right">'.$value[6].'</td>
              <td>'.$value[7].'</td>
            </tr>
            ';
          }
          echo
          '</tbody>
        </table>
      </div>
      ';
   break;
}
?>
