<?php
include_once("../../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;


// ===========================================
//     FUNCIONES-PHP
// ===========================================
// $opc      = $_POST['opc'];
$var      =$_POST['id'];
$array    = array($var);
$txt      = "";
/*==========================================
*   MAIN
=============================================*/
$sql    = $obj -> SELECT_POLIZA($var);

foreach ($sql as $x ) {
 $ruta =$x[5].$x[0];
 $peso =0;
 if($x[2] > 1024 ){
  $size = $x[2]/1024;
  $peso = Round($size,2)." Mb";
 }
 else {
  $peso = $x[2]." Kb";
 }

 $a='<button target=\"_blank\" href=\"'.$ruta.'\" download=\"'.$x[0].'\" class=\"btn btn-info btn-xss  \" ><span class=\"fa fa-cloud-download\"></span></button>';

 $b='<button  onclick=\"RemoverPoliza('.$x[7].')\" class=\"btn btn-danger btn-xss  \" ><span class=\"fa fa-pencil \"></span></button>';

 $txt=$txt.'{
  "archivo":"'.$x[0].'",
  "descripcion":"'.$x[1].'",
  "peso":"'.$peso.'",
  "fecha":"'.$x[3].'",
  "tipo":"'.$x[4].'",
  "desc":"'.$a.' '.$b.' "

 },';
}

$txt = substr($txt,0,strlen($txt)-1);
echo '{"data":['.$txt.']}';

/*==========================================
*   FUNCIONES
=============================================*/
function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '';
 }else {
  $res =''.number_format($val, 2, '.', ',');
 }

 return $res;
}




?>
