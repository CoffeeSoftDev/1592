<?php
session_start();

include_once("../../../modelo/SQL_PHP/_ALMACEN.php");

$obj = new ALMACEN;

$txt      = "";
$opc      = $_POST['opc'];


/*=============================================
MAIN
===============================================*/

switch ($opc) {

 case 1: // ALMACEN----------
   $sql    = $obj -> verProductos(0);
   $cont  =0;

   foreach ($sql as $x ) {
    $opc=1;

    $v       =empty($x[8]);
    if ($v=="") { $opc=0;}
    $ico      = ICOpoliza($x[8]);
    // $est      = EstadoProducto($x[14]);
    $cont    +=1;
    // ----

    $code='<span class=\"label label-indigo\">'.$x[1].'</span>';

    $b='<a data-toggle=\"modal\" data-target=\"#baja\" class=\"btn btn-outline-danger btn-xss  \" onclick=\"bajaProductos('.$x[9].',3)\"><span class=\"'.$ico.'\"></span></a>';

    $a='<a data-toggle=\"modal\" onclick=\"almacen_modal_edit('.$x[9].')\" data-target=\"#ModProducto\"  class=\"btn btn-outline-info btn-xss  \"><span class=\"icon-pencil\"  ></span></a>';
    //---

    $txt=$txt.'{
     "zona":"'.$x[0].'",
     "Codigo"          :"'.$code.'",
     "articulo":"'.$x[2].'",
     "costoENT":"'.evaluar($x[3]).'",
     "costoSAL":"'.evaluar($x[4]).'",
     "cantidad":"'.$x[7].'",
     "familia":"'.$x[5].'",
     "stockMIN"          :"'.$x[6].'",
     "stock"          :"'.$x[7].'",
     "area":"'.$x[2].'",
     "fecha":"'.$x[8].'",
     "tiempo":"'.$x[2].'",
     "unidad":"'.$x[14].'",
     "conf":" '.$a.' '.$b.' "

    },';
   }



   $txt = substr($txt,0,strlen($txt)-1);
   echo '{"data":['.$txt.']}';

 break;

 case 2: // Almacen baja
 $sql    = $obj -> Show_DATA($array);
 $cont  =0;


 foreach ($sql as $x ) {
  $opc=1;

  $v       =empty($x[8]);
  if ($v=="") { $opc=0;}
  $ico      = ICOpoliza($x[8]);
  $est      = EstadoProducto($x[14]);
  $cont    +=1;
  // ----
  $fechainicial = new DateTime($x[12]);
  $fechafinal = new DateTime($x[13]);

  $diferencia = $fechainicial->diff($fechafinal);
  $meses    = ( $diferencia->y * 12 ) + $diferencia->m;
  $txtMeses = $meses.' Meses';

  $code='<span class=\"label label-indigo\">'.$x[0].'</span>';

  $a='<a data-toggle=\"modal\" data-target=\"#M1\" class=\"btn btn-warning btn-xss  \" onclick=\"ModalPoliza('.$x[4].','.$opc.')\"><span class=\"'.$ico.'\"></span></a>';

  //---

  $txt=$txt.'{
   "Codigo"          :"'.$code.'",
   "udn"          :"'.$x[3].'",
   "equipo":"'.$x[1].'",
   "categoria":"'.$x[7].'",
   "area":"'.$x[2].'",
   "cantidad":"'.$x[5].'",
   "costo":"'.evaluar($x[10]).'",
   "tiempo":"'.$txtMeses.'",
   "fecha":"'.$est.'",
   "conf":" '.$a.'"

  },';
 }



 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 //
 break;




}



/*=============================================
Funciones
===============================================*/

function ICOpoliza($value){
 $rp="";
 if ($value=="") {
  $rp=" icon-cancel-circled";
 }else {
  $rp=" icon-cancel-circled";
 }
 return $rp;
}

function EstadoProducto($value)
{
 $estado = '';
 switch ($value) {
  case 1: $estado ='<span class=\"label label-success\">Bueno</span>'; break;
  case 2: $estado ='<span class=\"label label-warning\">Regular</span>'; break;
  case 3: $estado ='<span class=\"label label-danger\">Malo</span>'; break;

 }
 return $estado ;
}

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
