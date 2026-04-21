<?php
session_start();

include_once("../../modelo/SQL_PHP/_Utileria.php");
include_once("../../modelo/SQL_PHP/_DIRECCION.php");

$obj  = new direccion;
$util = new Util;

//---
$opc      = $_POST['opc'];
$txt    = "";
$array  = array(null);


switch ($opc) {

 case 1: // Usuarios

 $sql    = $obj -> dataUsers($array);
 $cont  =0;
 // [ ] -----------------------------
 foreach ($sql as $x ) {

    $a='<button data-toggle=\"modal\" data-target=\"#M2\" class=\"btn btn-info btn-xs col-xs-12 col-sm-12 \" onclick=\"EditUsuario('.$x[7].')\"><span class=\"fa fa-pencil\"></span></button>';
    $b='<button data-toggle=\"modal\" data-target=\"#M2\" class=\"btn btn-danger btn-xs col-xs-12 col-sm-12 \" onclick=\"bajaUsuario('.$x[7].')\"><span class=\"fa fa-times-circle\"></span></button>';

  $cont  +=1;
  $txt=$txt.'{
   "#"          :"'.$cont.'",
   "Usuario"          :"'.$x[0].'",
   "Nivel":"'.$x[1].'",
   "Correo":"'.$x[2].'",
   "Gerente":"'.$x[3].'",
   "Permiso":"'.$x[4].'",
   "UDN":"'.''.$x[5].'",
   "modificar":"'.$a.'",
   "eliminar":"'.$b.'"

  },';
 }



 $txt = substr($txt,0,strlen($txt)-1);

 echo '{"data":['.$txt.']}';

 break;

 case 2: // Usuarios

 $sql    = $obj -> dataArea($array);
 $cont  =0;
 // [ ] -----------------------------
 foreach ($sql as $x ) {
  $cont  +=1;
  $txt=$txt.'{
   "#"          :"'.$cont.'",
   "Area":"'.''.$x[1].'"

  },';
 }



 $txt = substr($txt,0,strlen($txt)-1);

 echo '{"data":['.$txt.']}';


 break;


 case 3: // area
 $sql    = $obj -> dataNivel($array);
 $cont  =0;
 // [ ] -----------------------------
 foreach ($sql as $x ) {
  $cont  +=1;
  $txt=$txt.'{
   "#"          :"'.$cont.'",
   "Nivel":"'.''.$x[1].'"

  },';
 }



 $txt = substr($txt,0,strlen($txt)-1);

 echo '{"data":['.$txt.']}';


 break;

}






// [ ] -----------------------------
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
