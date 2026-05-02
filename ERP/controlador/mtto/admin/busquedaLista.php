<?php
session_start();

include_once("../../../modelo/SQL_PHP/_MTTO_REQUISICION.php");

$obj    = new REQUISICION;
$opc    = $_POST['opc'];
$txt    = '';



switch ($opc) {
 case 1: // Busqueda

 $folio      = $obj -> FOLIO_ACTIVO();
 foreach     ($folio as $key);
 $idFolio    = $key[0];

 $search     = $_POST['busqueda'];
 $area       = $_POST['area'];
 $zona       = $_POST['zona'];
 $array      = array($zona,$area);

 $ok	        =	$obj	->	BUSQUEDA($search,$array);
 $total      = count($ok);


 if (count($ok)) {

  $txt=$txt.'
  <div class="text-right">
  <label>Coincidencias: '.$total.'</label>
  </div>

  <div class="table-responsive">
  <table class="table table-bordered">
  <thead>
  <tr class="text-center tr-title ">
  <td class="col-xs-5">Descripción</td>
  <td class="col-xs-2">Teorico</td>
  <td class="col-xs-2">Costo Unitario</td>
  <td class="col-xs-2">Fisico Almacen</td>

  <td><span class="fa fa-gear"></span></td>
  </tr>
  </thead>
  <tbody>';

  foreach ($ok as $key) {

   $block         = 'onclick="addMovimiento('.$key[15].','.$key[7].')"';
   $buscarEnlista = $obj ->existeEnLista($key[15],$idFolio);
   $BlockFisico   ='';
   $class='';



   if (count($buscarEnlista)) {
    $BlockFisico   ='disabled';
    $class ='class=" bg-default "';
    $block  = 'disabled';
   }

   $txt=$txt.'
   <tr id="dataCol'.$key[15].'" '.$class.'>

   <td class="">'.$key[2].'</td>

   <td>
   <div class="input-group">
   <input id="txtCantidad'.$key[15].'" type="text" class="form-control input-xs" value="'.$key[7].'" disabled>
   <span class="input-group-addon"><strong>'.$key[16].'</strong></span>
   </div>
   </td>


   <td class="text-right">
   <div class="input-group">
   <span class="input-group-addon input-xs"><i class=" icon-dollar"></i></span>
   <input  style="width:70px;" type="text" class="form-control input-xs text-right" disabled value="'.evaluar($key[3]).'">
   </div>

   </td>


   <td>

   <div class="input-group">
   <input id="mov'.$key[15].'" type="text" class="form-control input-xs" value="1" '.$BlockFisico.'>
   <span class="input-group-addon"><strong>'.$key[16].'</strong></span>
   </div>


   </td>
   <td><a '.$block.' class="btn btn-success btn-xxs" style="max-width:100%;" id="add'.$key[15].'"><span class=" icon-ok"></span></a></td>
   </tr>
   ';


  }
  $txt=$txt.'
  </tbody>
  </table>
  </div>
  ';

 }else {
  $txt=$txt.'
  <div class="text-center"><label><h3> Producto no encontrado, ¿Desea agregarlo?</h3></label>
  <br>
  <a data-toggle="modal" data-target="#Producto" onclick="verModal()" class="btn btn-default">Agregar productos</a>
  </div>
  ';
 }

 break;

 case 2:
 $ok	=	$obj	->	LISTA_INVENTARIO(null);
 $txt=$txt.'
 <div class="text-right">
 <label>No. Productos : '.count($ok).'</label>
 </div>

 <div class="table-responsive">
 <table class="table table-bordered">
 <thead>

 <tr class="text-center tr-title" >
 <td>Descripción</td>
 <td>Inicial</td>
 <td>Final</td>
 <td>Diferencia</td>
 <td><span class="fa fa-gear"></span></td>
 </tr>
 </thead>
 <tbody>';

 foreach ($ok as $key) {

  $teorico          = $key[1];
  $fisico           = $key[2];
  $movimiento       = 0;

  if ($teorico < $fisico) {
   $movimiento      = $fisico - $teorico;
  }else {
   $movimiento      = $teorico - $fisico;
  }


  $txt=$txt.'
  <tr>

  <td class="col-xs-6 " style="max-width:100%;">'.$key[0].'</td>

  <td class="col-xs-1 ">
  <div class="input-group">
  <input  style="width:30px;" type="text" class="form-control input-xs text-right" disabled value="'.$key[1].'" id="anterior'.$key[3].'">
  <span class="input-group-addon input-xs">'.$key[5].'</span>
  </div>
  </td>


  <td class="text-right">

  <div class="input-group">
  <input  style="width:30px;" type="text" class="form-control input-xs text-right" disabled value="'.$key[2].'" >
  <span class="input-group-addon input-xs">'.$key[5].'</span>
  </div>

  </td>

  <td class="text-right">
  <div class="input-group">
  <input  style="width:40px;" type="text" class="form-control input-xs text-right" disabled value="'.$movimiento.'" >
  <span class="input-group-addon input-xs">'.$key[5].'</span>
  </div>
  </td>

  <td><a class="btn btn-danger btn-xxs" id="delete'.$key[3].'" style="width:100%;" onclick="QuitarDeLista('.$key[3].','.$key[4].')"><span class="icon-cancel"></span></a></td>
  </tr>
  ';


 }



 $txt=$txt.' </tbody></table></div>';
 break;

}




/*	===========================================
//					ENCODE	JSON
//	===========================================*/
$encode	=	array(
 0=>$txt);
 echo	json_encode($encode);
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
