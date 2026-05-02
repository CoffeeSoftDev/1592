<?php
include_once("../../../modelo/SQL_PHP/_Finanzas_ADMIN.php");
$obj = new Finanzas;

$var      =$_POST['id'];
$array    = array($var);
$txt      = "";
/*==========================================
*   MAIN
=============================================*/
$sql    = $obj -> SELECT_POLIZA($var);

foreach ($sql as $x ) {
 $ruta =$x[8].$x[0];
 $peso =0;
 if($x[2] > 1024 ){
  $size = $x[2]/1024;
  $peso = Round($size,2)." Mb";
 }
 else {
  $peso = evaluar($x[2])." Kb";
 }

 $a='<a title=\"Descargar \" target=\"_blank\" href=\"'.$ruta.'\"  class=\"btn btn-info btn-sm  \" ><span class=\"icon-download\"></span></a>';

 $b='<a  title=\"Eliminar archivo \" onclick=\"EliminarArchivo('.$x[9].')\" class=\"btn btn-danger btn-sm  \" ><span class=\" icon-cancel \"></span></a>';

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
