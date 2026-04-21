<?php
include_once("../../../modelo/SQL_PHP/_FLORES_FOLLAJES.php");
$obj       = new _PRODUCTOS;

include_once("../../../modelo/UI_TABLE.php");
$table = new Table_UI;

include_once("../../../modelo/SQL_PHP/_Utileria.php");
$util  = new Util;

switch ($_POST['opc']) {

 /*-----------------------------------*/
 /* ** Disponibilidad **
 /*-----------------------------------*/

 case 0:

 $date    = $_POST['date'];
 $idFolio = 0;
 $Folio   = 0;
 $label    = '';

 if ($date=="" && $date == null) {
  $date   = $obj-> NOW();
 }

 $tb_sql  = $obj ->Select_Folio($date);

 foreach ($tb_sql as $key) {
  $idFolio = $key[0];
  $Folio   = $key[1];
 }
 /*Ultimo Registro -----------*/
 $tb_rg    = $obj ->Select_LastFolio($date);

 if (count($tb_rg)!=0) {

  foreach ($tb_rg as $key) {
   $label    = '<a class="text-link" onclick="ultimo_inventario(\''.$key[2].'\')"><i class="icon-calendar"></i> Ultimo inventario ('.$key[2].')</a>';
  }

 }

 /*JSON ENCODE -----------*/
 $encode	=	array(0=>$idFolio,1=>$Folio,2=>$date,3=>$label);
 echo	json_encode($encode);
 break;




//  /*-----------------------------------*/
//  /* ** Nuevo Formato **
//  /*-----------------------------------*/

 case 1:
 sleep(1);
 $date   = $_POST['date'];

 $Folio  = $obj->Select_FolioDesc($date);
 $nFolio = $Folio + 1;


 $f      = $util->Folio($nFolio,'');
 $obj->Insert_Folio($f,$date);

 $tb ='';
 /*JSON ENCODE -----------*/

 $encode	=	array(0=>$tb);
 echo	json_encode($encode);
 break;




 /*-----------------------------------*/
 /* Lista de Requisicion
 /*-----------------------------------*/
 case 2:
 $sql       = $obj ->_Categoria(array(1));
 $date      = $_POST['date'];
 $grantotal = 0;

 $d         = strtotime($date);
 $semana    = i_f($date);

 $tb   = ' <div class="" style="height:30px;"><div class=" text-center col-sm-12 " style="font-size:1em;" class="col-sm-12">
 <b> DISPONIBILIDAD '.date("Y", $d).' FLORES & FOLLAJES </b> '.$semana.'</div> </div>';

 $cont = 0;
 foreach ($sql as $key ) {
  $cont +=1;
  $tb_sub = tb_sub($obj,$key[0],$key[1],$date);

  if ($cont ==0) {
   $tb   .= '<div class="row ">';
  }
  $tb   .= '<div class="formato col-xs-6 col-sm-6 col-lg-6">'.$tb_sub[0].'</div>';

  $grantotal +=$tb_sub[1];


  if ($cont==2) {
   $cont = 0;
   $tb   .= '</div>';
  }
 }

 $tb   .= '
 <div class="">
 <div class=" col-xs-12 col-sm-6">
 <table class="table table-bordered">
 <tr>
 <td class="col-xs-8"><h4><b>TOTAL</b></h4></td>
 <td class="text-right"><h4><b>'.evaluar($grantotal).'<b></h4></td>
 </tr>
 </table>

 </div></div>';

 /*--------- JSON ENCODE -----------*/
 $encode	=	array(0=>$tb);
 echo	json_encode($encode);

 break;

 /*-----------------------------------*/
 /* ** Actualizar o insertar cantidad
 /*-----------------------------------*/

 case 3:
 $txt  = $_POST['txt'];
 $id   = $_POST['id'];
 $val  = $_POST['val'];
 $date = $_POST['date'];

 $idF  = $obj-> Select_idFolio($date);


 $array  = array($idF,$id);
 $idSBit = $obj->Select_idBit($array);

 if ( $idSBit == 0) { // aun no se ha creado idVentasBit
  $array = array($idF,$id,$val);

  $obj->SAVE_FORM(
   $array,
   array('id_disponibilidad','id_productos','cantidad_disponible'),
   'hgpqgijw_ventas.ventas_disponibilidad_productos');

  }else {
   $update = $obj->update_col(array($val,$idSBit));

  }

  $d= '';
  // /*--------- JSON ENCODE -----------*/

  $encode	=	array(0=>$d);

  echo	json_encode($encode);

  break;


  case 5:

  $tb    = '';
  $title = array('Folio','Fecha','No. de insumos','Total','');
  $tdM   = array(3);
  $sql   = $obj -> ver_formatos(null);
  $tb    = $table -> table_view($title,null,$tdM,$sql);

  /*--------- JSON ENCODE -----------*/
  $encode	=	array(0=>$tb);
  echo	json_encode($encode);

  break;
  /*-----------------------------------*/
  /* **
  /*-----------------------------------*/

  case 6:
  $sql       = $obj ->_Categoria(array(1));
  $date      = $_POST['date'];
  $grantotal = 0;
  $cont      = 0;
  $d         = strtotime($date);
  $semana    = i_f($date);

  $tb   = '
   <div class="" style="margin-bottom:35px">
    <div class=" text-center col-sm-12 " style="font-size:1em;" class="col-sm-12">
    <b> DISPONIBILIDAD '.date("Y", $d).' FLORES & FOLLAJES </b> '.$semana.'</div>
   </div>';

  foreach ($sql as $key ) {
   $cont +=1;
   $tb_sub = tb_admin($obj,$key[0],$key[1],$date);

   if ($cont ==0) {
    $tb   .= '<div class="col row ">';
   }

   $tb   .= '<div class="formato col-xs-12 col-sm-6 col-lg-6 ">'.$tb_sub[0].'</div>';

   $grantotal +=$tb_sub[1];


   if ($cont==2) {
    $cont = 0;
    $tb   .= '</div>';
   }
  }



  
  $tb   .= '
  <div class="">
  <div class=" col-xs-12 col-sm-6">
  <table class="table table-bordered">
  <tr>
  <td class="col-xs-8"><h4><b>TOTAL</b></h4></td>
  <td class="text-right"><h4><b>'.evaluar($grantotal).'<b></h4></td>
  </tr>
  </table>

  </div></div>';

  /*--------- JSON ENCODE -----------*/
  $encode	=	array(0=>$tb);
  echo	json_encode($encode);
  break;

 }






 /*-----------------------------------*/
 /*	Complementos
 /*-----------------------------------*/

 function tb_sub($obj,$idCat,$cat,$date){
  $tb    = '';
  $t     = 0;

  $idF = $obj-> Select_idFolio($date);


  $sql = $obj-> _SubCategoria(array($idCat));

  $tb .='<table  id="size1" class="tb tb-bordered ">';
  $tb .='<tr class="text-center"><th colspan="6"> '.$cat.'</th></tr>';
  $tb .='</table>';

  foreach ($sql as $subCategoria) {


   $tb .='<table  id="size1" class="tb tb-bordered ">';

   if ($subCategoria[1]!='SIN ESPECIE') {
    $tb .='<tr><th colspan="6">'.$subCategoria[1].'</th></tr>';
   }


   $tb   .= '
   <tr>
   <td>N°</td>
   <td>Especie</td>
   <td>Costo unitario</td>
   <td>U  Venta</td>
   <td> Cantidad </td>
   <td>Sub Total </td>
   </tr>
   </thead>';

   $sql_productos = $obj ->ver_productos_sub(1,$subCategoria[0]);



   foreach ($sql_productos as $key ) { // Recorrido por las categorias

    $monto     = $obj->Select_MontoSubtotal($idF,$key[7]);
    $total     = $key[1] * $monto;
    $t         += $total;

    $tb .='<tr>
    <td></td>
    <td class="col-xs-4">'.$key[0].'</td>
    <td  class="text-right">'.evaluar($key[1]).'</td>
    <td>Pieza</td>


    <td id="cant'.$subCategoria[0].''.$key[7].'">'.input_col($subCategoria[0],$key[7],$monto,$key[1]).'</td>


    <td>'.input_sub($subCategoria[0],$key[7],$total).'</td>

    </tr>';
   }

   $tb .='</table>';
  }

  $tb .='<table  id="size1" class="tb tb-bordered ">';
  $tb .='<tr class="text-center"><th class="col-xs-8">Subtotal</th><th class="text-right">'.evaluar($t).'</th></tr>';
  $tb .='</table>';


  $array = array($tb,$t);
  return $array;
 }

 function tb_admin($obj,$idCat,$cat,$date){
  $tb    = '';
  $t     = 0;

  $idF = $obj-> Select_idFolio($date);

//   $tb .='IDFOLIO: '.$idF;
 
  $sql = $obj-> _SubCategoria(array($idCat));

  $tb .='<table  id="size1" class="tb tb-bordered ">';
  $tb .='<tr class="text-center"><th colspan="6"> '.$cat.'</th></tr>';
  $tb .='</table>';

  foreach ($sql as $subCategoria) {


   $tb .='<table  id="size1" class="tb tb-bordered ">';

   if ($subCategoria[1]!='SIN ESPECIE') {
    $tb .='<tr><td colspan="6"><b>'.$subCategoria[1].' '.$subCategoria[0].' </b></td></tr>';
   }
   $tb   .= '
   <tr>
   <td>N°</td>
   <td>Especie</td>
   <td>Costo unitario</td>
   <td>U / Venta</td>
   <td>Cantidad </td>
   <td>Sub Total </td>
   </tr>
   </thead>';

   $sql_productos = $obj ->ver_productos_sub(1,$subCategoria[0]);



   foreach ($sql_productos as $key ) { // Recorrido por las categorias

    $monto     = $obj->Select_MontoSubtotal($idF,$key[7]);
    $total     = $key[1] * $monto;
    $t         += $total;

    $tb .='<tr>
    <td></td>
    <td class="col-xs-4">'.$key[0].'</td>
    <td  class="text-right">'.evaluar($key[1]).'</td>
    <td> '.$key[3].'</td>
    <td class="bg-danger"><input class="input-xs" value="'.$monto.'"  ></td>
    <td>'.$total.'</td>

    </tr>';
   }

   $tb .='</table>';
  }

  $tb .='<table  id="size1" class="tb tb-bordered ">';
  $tb .='<tr class="text-center"><th class="col-xs-8">Subtotal</th><th class="text-right">'.evaluar($t).'</th></tr>';
  $tb .='</table>';


  $array = array($tb,$t);
  return $array;
 }


 function input_col($idCol,$id,$val,$costo) {
  $td ='
  <input style="width:100%; height: 20px; text-align:right;"
  class="text-right input-xs total-input " value="'.$val.'"
  onkeypress="if(event.keyCode == 13)mod_col('.$idCol.','.$id.',\''.$val.'\')"
  onkeyup="sub('.$idCol.','.$id.',\''.$val.'\','.$costo.'); mod_col('.$idCol.','.$id.',\''.$val.'\');"
  id="txt'.$idCol.$id.'">
  ';
  return $td;
 }

 function input_sub($idCol,$id,$val) {
  $td ='
  <input  disabled style="width:100%; height: 20px; text-align:right;"
  class="text-right input-xs total-input " value="'.$val.'"
  value="'.$val.'"
  id="sub'.$idCol.$id.'">
  ';
  return $td;
 }

 function i_f($fecha){

  $diaInicio="Monday";
  $diaFin="Sunday";

  $strFecha = strtotime($fecha);

  $fechaInicio = date('Y-m-d',strtotime('last '.$diaInicio,$strFecha));
  $fechaFin = date('Y-m-d',strtotime('next '.$diaFin,$strFecha));

  if(date("l",$strFecha)==$diaInicio){
   $fechaInicio= date("Y-m-d",$strFecha);
  }
  if(date("l",$strFecha)==$diaFin){
   $fechaFin= date("Y-m-d",$strFecha);
  }

  $dia_i =  date("d", strtotime($fechaInicio));
  $dia_f =  date("d", strtotime($fechaFin));
  $anio  =  date("Y", strtotime($fechaFin));
  $mes   =  date("m", strtotime($fechaFin));
  // $array = array($dia_i,$dia_f);

  $text = 'Semana del '.$dia_i.' al '.$dia_f.' de '.mes_spa($mes).' del '.$anio;

  return $text;
 }

 function mes_spa($mes){

  $v ='';
  switch ($mes) {


   case 0: $v  ='ENERO';      break;
   case 1: $v  ='FEBRERO';    break;
   case 2: $v  ='MARZO';      break;
   case 3: $v  ='ABRIL';      break;

   case 4: $v  ='MAYO';       break;
   case 5: $v  ='JUNIO';      break;
   case 6: $v  ='JULIO';      break;

   case 7: $v  ='AGOSTO';     break;
   case 8: $v  ='SEPTIEMBRE'; break;
   case 9: $v  ='OCTUBRE';    break;
   case 10: $v ='NOVIEMBRE';  break;
   case 11: $v ='DICIEMBRE';  break;


  }
  return $v;

 }

 ?>
