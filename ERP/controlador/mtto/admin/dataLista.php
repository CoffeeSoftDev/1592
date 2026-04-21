<?php

session_start();

include_once("../../../modelo/SQL_PHP/_MTTO_REQUISICION.php");
$obj         = new REQUISICION;
$txt         = "";
$opc         = $_POST['opc'];




switch ($opc) {
 /*--------------------------------------------*/
 /* Ver lista
 /*---------------------------------------------*/
 case 1:
 $tipo   = $_POST['tipo'];
 $mes    = $_POST['mes'];
 $y      = $_POST['year'];

 // ---

 $sql    = $obj -> verListaProductos($tipo,$mes,$y);
 $cont   = 0;

 foreach ($sql as $x ) {
  $cont   += 1;
  $a='<a class=\"btn btn-outline-info btn-xxs col-xs-12 col-sm-12 \" onclick=\"verReporte('.$x[0].')\"><span class=\"fa fa-pencil\"></span></a>';

  $txt=$txt.'{
   "no"          :"'.$cont.'",
   "folio"          :"'.$x[1].'",
   "fecha":"'.$x[2].'",
   "hora":"'.$x[3].'",
   "productos":"'.$x[5].'",
   "autorizo":"'.''.$x[4].'",
   "option":"'.$a.'"

  },';
 }

 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;
 /*--------------------------------------------*/
 /* Ver reporte
 /*---------------------------------------------*/
 case 2:
 $var        = $_POST['id'];
 $tipo        = $_POST['tipo'];
 $data       = $obj -> LISTA_PDF($var);
 $productos  = count($data);

 $INVENTARIO = $obj -> verFolio($var);

 $CANCELADO  = '<div class="col-sm-12 col-xs-12 text-danger text-center"><h2>FOLIO CANCELADO </h2></div>';
 $OBS         = $INVENTARIO[6];
 if ($tipo!=3) {
  $CANCELADO  = '';
  $OBS         = $INVENTARIO[4];
 }
 // ---
 $txt = $txt.'

 <div class="row">
 <br>
 <div class="col-xs-12 col-sm-12 text-right">
 <a class="btn btn-default" onclick="Imprimir('.$INVENTARIO[0].')"><span class="fa fa-print"></span> IMPRIMIR</a>
 </div>
 </div>

 <div class="row">
 <br>
 <div class="col-xs-3 col-sm-3 ">
 <img src="http://www.argovia.com.mx/img/logo.png" width="150px" class="img-rounded center-block">
 </div>

 <div class="col-xs-6 col-sm-6 text-center">
 <h4 class=""><strong>INVENTARIO FISICO</strong></h4>
 </div>

 <div class="col-xs-3 col-sm-3 text-right">
 <strong>'.$INVENTARIO[3].'</strong>
 </div>
 </div>
 <br>

 '.$CANCELADO.'
 <div class="row">
 <div class="col-xs-6 col-xs-offset-6 col-sm-offset-6 text-right ">
 <h4>
 <strong>'.$INVENTARIO[2].'</strong>
 </h4>
 </div>

 </div>
 <br>
 <br>
 <div class="row">
 <div class="col-md-12 text-right">
 <span> No.productos: '.$productos.'</span>
 </div>
 <br>
 <div class="col-md-12">
 <table class="table table-bordered ">
 <thead>
 <tr class="text-xs-center">
 <th>#</th>
 <th >Producto</th>
 <th>Precio</th>
 <th>Anterior</th>
 <th>Movimiento</th>
 <th>Actual</th>
 <th>Zona</th>
 </tr>
 </thead>
 <tbody>';
 $contador = 0;
 foreach ($data as $key ) {
  $movimiento = $key[2] - $key[1];
  $contador+=1;
  $txt= $txt.'
  <tr>
  <td class="text-right">'.$contador.'</td>
  <td>'.$key[0].'</td>
  <td class="text-right">'.evaluar($key[1]).'</td>
  <td class="text-right">'.$key[1].'</td>
  <td class="text-right">'.$movimiento.'</td>
  <td class="text-right">'.$key[2].'</td>
  <td class="text-right">'.$key[5].'</td>
  </tr>';
 }

 $txt= $txt.'</tbody></table></div></div>
 <br>
 <br>

 <table class="table table-bordered">
 <thead>
 <tr>
 <td class="text-center"> OBSERVACIONES </td>
 </tr>
 </thead>
 <tr>
 <td class="text-justified">
 '.$OBS.'
 <br>
 <br>
 </td>
 </tr>
 <tbody>
 </tbody>
 </table>


 <br>

 <div class="row">
 <div class="col-xs-12 col-sm-12 text-center">
 <label style="margin-bottom: 0px;">'.$INVENTARIO[5].'</label>
 <p>___________________________________</p>

 <p>AUTORIZÓ</p>



 </div>
 ';

 // ---
 echo $txt;
 break;

 /*--------------------------------------------*/
 /*  init components
 /*---------------------------------------------*/

 case 3:

 $cb    = $obj -> cb_year();
 $select = '<select class="form-control input-xs" id="txtAnio" >
 <option value="0">Todos los reportes </option>
 ';

 foreach ($cb as $key) {
  $select = $select.'<option value="'.$key[0].'">'.$key[0].'</option>';
 }

 $select=$select.'</select>';

 echo $select;
 break;

}








// //---
// $array    = array(null);

/*==========================================
*   MAIN
=============================================*/


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
