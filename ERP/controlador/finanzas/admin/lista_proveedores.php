<?php
session_start();

include_once("../../../modelo/SQL_PHP/_Finanzas.php"); // <---
$obj = new Finanzas; // <--

//---
// $opc      = $_POST['opc'];
$array    = array(null);
$txt      = "";
/*==========================================
*   MAIN
=============================================*/
$sql    = $obj -> verProveedores(0);
$cont=0;
foreach ($sql as $x ) {
$cont+=1;
 $a='<a data-toggle=\"modal\" data-target=\"#modal01\" class=\"btn btn-primary btn-xs \" onclick=\"EditPro('.$x[0].')\"><span class=\"fa fa-pencil\"></span> </a>';
 $b='<a data-toggle=\"modal\" data-target=\"#modal03\" class=\"btn btn-danger btn-xs text-danger \" onclick=\"AutorizarBaja('.$x[0].')\"><span class=\"fa fa-close\"></span> </a>';

 $txt=$txt.'{
  "#"          :"'.$cont.'",
  "proveedor"          :"'.$x[1].'",
  "direccion":"'.$x[2].'",
  "ciudad":"'.$x[3].'",
  "telefono":"'.$x[4].'",
  "formas":"'.$x[8].'",
  "rfc":"'.''.$x[5].'",
  "eliminar":"'.$a.'  '.$b.' ",
  "categoria":"-"

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
