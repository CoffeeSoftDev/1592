<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
include_once('../../../modelo/SQL_PHP/_Utileria.php');
$fin = new Finanzas;
$util = new Util;
$idE = $_SESSION['udn'];
$opc = $_POST['opc'];

switch ($opc) {


 case 1://GUARDAR SUBTOTAL Y CALCULAR IMPUESTOS
 $monto      = $_POST['valor'];
 $date       = $_POST['date'];
 $idC        = $_POST['idC'];
 $idS        = $_POST['idS'];

 //=============================================
 //           Subtotal
 //=============================================

 $fp         = $fin ->Select_FP(array($idS));
 $subTotal   = 0;
 $sub        = 0;

 switch ($idC) {
  case 1:
  $fps         = $fin ->Select_Total2($date,$idS);// suma de formas de pago
  $sub         = $fps/1.18;
  break;


  case 9:
  $fps        = $fin  -> Select_Total2($date,$idS);
  $sub        = $fps;
  break;

  default:
  $fps        = $fin  -> Select_Total2($date,$idS);  //1000
  $subTotal   = $fps/1.16;
  $sub    = $subTotal;
  break;
 }




 //  if ($idC == '1' ||$idC == 1) { // categoria de hospedaje
 //
 //   $fps         = $fin ->Select_Total2($date,$idS);// suma de formas de pago
 //   // $sub         = $fps/1.18;
 //   $sub        = 9999;
 //  }if ($idC == '9') {
 //   $fps        = $fin  -> Select_Total2($date,$idS);
 //   $sub        = $fps;
 //
 //  }else {
 //   $fps        = $fin  -> Select_Total2($date,$idS); //1000
 //   // $subTotal   = $fps/1.16;
 // $subTotal  = $idC;
 //   // foreach ($fp as $key) {
 //   // $subTotal = $subTotal + (($monto)/100) * $key[0];
 //   // }
 //   // $sub    = $monto - $subTotal;
 //   $sub    = $subTotal;
 //  }

 //==================================================
 //           Obtener folio
 //==================================================
 $idF = $fin->Select_idFolio($date);

 if ( $idF == 0) {
  $Folio  = $fin->Select_FolioDesc($date);
  $nFolio = $Folio + 1;
  $fin->Insert_Folio($nFolio,$date,$idE);
  $idF    = $fin->Select_idFolio($date);
 }

 //==================================================
 // Buscar,inserta o actualizar la bitacora subtotal
 //==================================================


 $array  = array($idF,$idS);
 $idSBit = $fin->Select_idSubBitacora($array);


 if ( $idSBit == 0) {

  $array  = array($idF,$idS,$sub);
  $fin->Insert_SubBitacora($array);
  $array  = array($idF,$idS);
  $idSBit = $fin->Select_idSubBitacora($array);

 }else {

  $array = array($sub,$idSBit);
  $fin->Update_SubBitacora($array);

 }

 //==================================================
 //   Buscar , inserta /o actualizar impuestos
 //==================================================

 $total = $monto;

 $res1  = array();
 $res2  = array();
 $data  = array();

 $imp   = $fin->Select_Impuestos($idC); // Traeme todos los impuestos



 foreach ($imp as $val) {  // idImpuesto,Impuesto,Valor

  $res1[]   = $val[0];
  $vimp     = $val[2] / 100 ; // valor impuesto 16 % , 8 %

  if ($idC==1) { // Hospedaje .
   $impuesto = $sub * $vimp;

  }else { // Pertene a todas las categorias

   $impuesto = $sub * $vimp;

  }

  $data[]   = $impuesto;
  $res2[]   = evaluar($impuesto);
  $total    = $total + $impuesto;


  // $data[]   = $impuesto;
  // $res2[]   = number_format($impuesto,2,'.',',');
  // $total    = $total + $impuesto;

  $idImpBit = $fin->Select_idImpBitacora($idSBit,$val[0]);

  if ( $idImpBit == 0) {
   $array = array($idSBit,$val[0],$impuesto);
   $fin->Insert_ImpBitacora($array);

  }else {
   $array = array($impuesto,$idSBit,$val[0]);
   $fin->Update_ImpBitacora($array);
  }


 } // end Impuestos



 $res0 = array(evaluar($monto),evaluar($total));
 /*
 res0 : monto & total

 */

 $resultado = array_merge($res0,$res1,$res2);



 echo json_encode($resultado);
 break;

 case 4:
 $idS      = $_POST['idS'];
 $date     = $_POST['date'];
 $idFP     = $_POST['idFP'];
 $valor    = $_POST['valor'];
 $idC      = $_POST['idC'];
 $total    = 0;
 $subTotal = 0;



 // BUSCAR Y/O INSERTAR EL FOLIO
 $idF = $fin->Select_idFolio($date);

 if ( $idF == 0) {
  $Folio  = $fin->Select_FolioDesc($date);
  $nFolio = $Folio + 1;

  $fin->Insert_Folio($nFolio,$date,$idE);
  $idF = $fin->Select_idFolio($date);
 }
/*-----------------------------------*/
/*		Buscar si existe en bitacora ventas
/*-----------------------------------*/

 $array  = array($idF,$idS);
 $idSBit = $fin->Select_idSubBitacora($array); // obtener valor de bitacora_ventas



 if ( $idSBit == 0) { // aun no se ha creado idVentasBit
  $subTotal = get_subtotal($valor,$idC);
  $insert   = $fin ->Insert_SubBitacora(array($idF,$idS,$subTotal));
 // Se ha guarda por primera vez el subtotal !!!! OJO REVISAR

  $array  = array($idF,$idS);
  $idSBit = $fin->Select_idSubBitacora($array);


  $array  = array($idSBit,$idFP);
  $idFPBit = $fin->Select_idFPBitacora($array);

  if ( $idFPBit == 0) { // nO EXISTE
   $array = array($idSBit,$idFP,$valor);
   $fin->Insert_FPBitacora($array);
  }

  echo '1.vez SUB TOTAL: '.$subTotal.' | idFORMA'.$idFPBit.' valor:'.$valor;

 }else { // Ya existe en la base de datos

  $array   = array($idSBit,$idFP);
  $idFPBit = $fin->Select_idFPBitacora($array);




  if ( $idFPBit == 0) { // No existe forma de pago
   $array  = array($idSBit,$idFP,$valor);
   $fin->Insert_FPBitacora($array);

   $t_fp     = $fin ->Select_Total2($date,$idS);
   $total    = $t_fp ;
   // echo $idSBit.' t:'.$t_fp.'+'.$valor;
   $subTotal = get_subtotal($total,$idC);
   $update   = $fin-> BitacoraUpdate($date,$idS,$subTotal,'Subtotal');

  }else {

   $fin->Update_FPBitacora(array($valor,$idSBit,$idFP));
   $t_fp     = $fin ->Select_Total2($date,$idS);
   $total    = $t_fp ;
   // echo $idSBit.' Ya existe ->'.$t_fp;
   $subTotal = get_subtotal($t_fp,$idC);
   $update   = $fin-> BitacoraUpdate($date,$idS,$subTotal,'Subtotal');
  }



 }


 // Actualizar o agregar impuestos
 $imp = $fin->Select_Impuestos($idC);
 $res1 = array();
 $res2 = array();
 $data = array();

 foreach ($imp as $val) {
  $res1[]   = $val[0];
  $vimp     = $val[2] / 100;// valor impuesto
  $impuesto = $subTotal * $vimp;


  $idImpBit = $fin->Select_idImpBitacora($idSBit,$val[0]);


  if ( $idImpBit == 0) {
   $array = array($idSBit,$val[0],$impuesto);
   $fin->Insert_ImpBitacora($array);
   // echo "IMPUESTOS ESTA VACIO";

  }else{
   $array = array($impuesto,$idSBit,$val[0]);
   $fin->Update_ImpBitacora($array);
   // echo "agregar";
  }

  // echo $total.'|'.$impuesto.'/'.$vimp.' ]';
  // $impuesto = $valor * $vimp;
  echo $idC;
 }



 break;



 case 2://GUARDAR FORMAS DE PAGO

 $idS   = $_POST['idS'];
 $date  = $_POST['date'];
 $idFP  = $_POST['idFP'];
 $valor = $_POST['valor'];

 // BUSCAR Y/O INSERTAR EL FOLIO
 $idF = $fin->Select_idFolio($date);

 if ( $idF == 0) {
  $Folio = $fin->Select_FolioDesc($date);
  $nFolio = $Folio + 1;
  $fin->Insert_Folio($nFolio,$date,$idE);
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



 case 3://CALCULAR DIFERENCIAS
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
}


/*-----------------------------------*/
/*		 FUNCIONES
/*-----------------------------------*/

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res =''.number_format($val, 2, '.', ',');
 }

 return $res;
}

function get_subtotal($fps,$idC){
 $val = 0;
 switch ($idC) {
  case 1:
  case 11:
   $val  = $fps/1.18; break;

  case 9:   $val  = $fps;      break;

  default:  $val  = $fps/1.16; break;
 }

 $r_val = _Round($val, 4);
 return $r_val;
}

function _Round($numero, $decimales) {
 $factor = pow(10, $decimales);
 return (round($numero*$factor)/$factor);
}

?>
