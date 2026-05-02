<?php
session_start();
include_once("../../modelo/SQL_PHP/_ALMACEN_ADMIN.php");
$obj      = new ALMACEN_ADMIN;

include_once("../../modelo/SQL_PHP/_ALMACEN.php");
$almacen = new ALMACEN;



$opc      = $_POST['opc'];
$txt      ='';
/*=============================================
MAIN
===============================================*/

switch ($opc) {

 case 1: // ALMACEN POR ZONAS ----------------
 $sql    = $obj -> VER_TOTAL_GRUPOS(null);
 $cont  =0;


 foreach ($sql as $x ) {
  $txt=$txt.'{
   "zona":"'.$x[0].'",
   "productos":"'.$x[1].'",
   "total":"'.evaluar($x[2]).'"
  },';
 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;

 case 2: // ALMACEN POR PRODUCTOS -----------
 $sql    = $almacen -> verProductos(0);

 foreach ($sql as $x ) {
  $code='<span class=\"label label-indigo\">'.$x[0].'</span>';

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
   "tiempo":"'.$x[2].'"


  },';

 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;

 case 3: // ALMACEN COSTOS PRODUCTOS -------

 $sql    = $obj -> VER_TOTAL_COSTOS(null);
 $cont  =0;


 foreach ($sql as $x ) {
  $code='<span class=\"label label-indigo\">'.$x[1].'</span>';
  $costo='<span class=\"text-success\">'.evaluar($x[7]).'</span>';

  $txt=$txt.'{
   "zona":"'.$x[0].'",
   "codigo"          :"'.$code.'",
   "nombre":"'.$x[2].'",
   "clase":"'.$x[3].'",
   "familia":"'.$x[4].'",
   "cantidad":"'.$x[5].'",
   "costo":"'.evaluar($x[6]).'",
   "total"          :"'.$costo.'",
   "preciov":"'.evaluar($x[8]).'",
   "desc":"'.$x[9].'"


  },';

 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;

 case 4: // PRODUCTOS BAJOS -------

 $sql    = $obj -> VER_STOCKBAJOS(null);
 $cont  =0;


 foreach ($sql as $x ) {
  $code='<span class=\"label label-indigo\">'.$x[1].'</span>';
  $costo='<span class=\"text-success\">'.evaluar($x[7]).'</span>';

  $txt=$txt.'{
   "zona":"'.$x[0].'",
   "codigo"          :"'.$code.'",
   "nombre":"'.$x[2].'",
   "clase":"'.$x[3].'",
   "familia":"'.$x[4].'",
   "cantidad":"'.$x[5].'",
   "costo":"'.evaluar($x[6]).'",
   "total"          :"'.$costo.'",
   "preciov":"'.evaluar($x[8]).'",
   "desc":"'.$x[9].'"


  },';

 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;
 case 5: // ALMACEN COSTOS HOY -------

 $sql    = $obj -> VER_HOY(null);
 $cont  =0;


 foreach ($sql as $x ) {
  $code='<span class=\"label label-indigo\">'.$x[1].'</span>';
  $costo='<span class=\"text-success\">'.evaluar($x[7]).'</span>';

  $txt=$txt.'{
   "zona":"'.$x[0].'",
   "codigo"          :"'.$code.'",
   "nombre":"'.$x[2].'",
   "clase":"'.$x[3].'",
   "familia":"'.$x[4].'",
   "cantidad":"'.$x[5].'",
   "costo":"'.evaluar($x[6]).'",
   "total"          :"'.$costo.'",
   "preciov":"'.evaluar($x[8]).'",
   "desc":"'.$x[9].'"


  },';

 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;


	/*===========================================
	*									MATERIALES TOTAL
	=============================================*/
 case 6: // TOTAL ITEMS -------

 $sql    = $obj -> VER_TOTAL_MATERIALES(null);
 $cont  =0;


 foreach ($sql as $x ) {
  $code='<span class=\"label label-indigo\">'.$x[1].'</span>';
  // $costo='<span class=\"text-success\">'.evaluar($x[7]).'</span>';

  $txt=$txt.'{
   "codigo":"'.$x[0].'",
   "Equipo":"'.$x[1].'",
   "estado":"'.$x[3].'",
   "area":  "'.$x[2].'",
   "cantidad":"'.$x[4].'",
   "costo":"'.evaluar($x[5]).'",
   "total":"'.evaluar($x[6]).'",
   "tiempo":"'.$x[7].' meses",
   "fecha"  :"'.$x[8].'",
   "desc":"'.$x[9].'"


  },';

 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;

 case 7: // TOTAL COSTO -------

 $sql    = $obj -> VER_TOTAL_MATERIALES(null);
 $cont  =0;


 foreach ($sql as $x ) {
  $costo='<span class=\"text-success\">'.evaluar($x[6]).'</span>';

  $txt=$txt.'{
   "codigo":"'.$x[0].'",
   "area":  "'.$x[2].'",
   "Equipo":"'.$x[1].'",
   "cantidad":"'.$x[4].'",
   "costo":"'.evaluar($x[5]).'",
   "total":"'.$costo.'",
   "desc":"'.$x[9].'"
  },';

 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;

 case 8: //  -------

 $sql    = $obj -> VER_BAJA_MATERIALES(null);
 $cont  =0;


 foreach ($sql as $x ) {
  $costo='<span class=\"text-success\">'.evaluar($x[6]).'</span>';
  $est      = EstadoProducto($x[14]);
  $txt=$txt.'{
   "codigo":"'.$x[0].'",
   "Equipo":"'.$x[1].'",
   "categoria":  "'.$x[7].'",
   "area":  "'.$x[2].'",
   "cantidad":  "'.$x[5].'",
   "costo":"'.$x[10].'",
   "total":"'.$x[11].'",
   "baja":"'.$x[15].'",
   "Productos":"'.$x[18].'",
   "Observacion":"'.$x[19].'",
   "estado":"'.$est.'",
   "Motivo":"'.$x[16].'"
  },';

 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;
}



/*=============================================
Funciones
===============================================*/


function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }
 return $res;
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



?>
