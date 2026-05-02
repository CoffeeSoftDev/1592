<?php
session_start();
include_once("../../../modelo/SQL_PHP/_MTTO.php");

$obj	=	new	MTTO;

$cat   = $_POST['categoria'];
$area  = $_POST['area'];
$opc   = $_POST['opc'];
$txt='';


/*===========================================
//														MAIN
//	==========================================*/
$sql    = $obj -> Show_DATA($cat,$area,$opc);
$cont  =0;


foreach ($sql as $x ) {
 $opc=1;

 $v       =empty($x[8]);
 if ($v=="") { $opc=0;}
 $ico      = '';
 $est      = '';
 $cont    +=1;
 // ----
 $fechainicial = new DateTime($x[12]);
 $fechafinal = new DateTime($x[13]);

 $diferencia = $fechainicial->diff($fechafinal);
 $meses    = ( $diferencia->y * 12 ) + $diferencia->m;
 $txtMeses = $meses.' Meses';

 $code='<span class=\"text-primary \">'.$x[0].'</span>';

 $a='<p style=\"font-size:7px\">'.$x[1].'</p> <img style=\"max-width:120px; height:20px\" src=\"http://www.argovia.com.mx/img/logo.png\" ></img><p><strong>'.$x[0].'</strong></p>';


 //---

 $txt=$txt.'{
  "Codigo"          :"'.$code.'",
  "equipo":"'.$x[1].'",
  "categoria":"'.$x[7].'",
  "area":"'.$x[2].'",
  "cantidad":"'.$x[5].'",
  "conf":" '.$a.'"

 },';
}



$txt = substr($txt,0,strlen($txt)-1);
echo '{"data":['.$txt.']}';


 /*	===========================================
 //					FUNCTIONS()
 //	===========================================*/
 function evaluar($val){
  $res = '';
  if ($val==0 || $val=="") {
   $res = '';
  }else {
   $res ='$ '.number_format($val, 2, '.', ',');
  }
  return $res;
 }


 ?>
