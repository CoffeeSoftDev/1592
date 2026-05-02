<?php
session_start();
include_once("../../../modelo/SQL_PHP/_MTTO_REQUISICION.php");
$obj    = new REQUISICION;


$opc  = $_POST['opc'];
/*===========================================
//														MAIN
//	==========================================*/

$txt = '';
switch ($opc) {
 case 1:
 $folio   = $obj -> FOLIO_ACTIVO();
 foreach    ($folio as $key);
 $Folio    = $key[2];


 $title   = 'Indica el motivo de cancelación';
 $onclick = 'EliminarFolio';
 $info    = '<div class="form-group" id="ErrorMotivo">
 <textarea class="form-control input-sm"  autofocus id="txtMotivo"></textarea>
 </div>
 <div id="txtMot"></div>
 ';

 $header  = '<div class="location text-center" ><h4 class="modal-title Folio"><strong>
 N° '.$Folio.'</strong></h4></div>';

 $id      = $key[0];
 $array   = array($title,$onclick,$info,$header,$id);
 $txt =  MODAL_WARNING($array);
 break;



 case 2:   /* Modal Guardar Inventario */
 $folio   = $obj -> FOLIO_ACTIVO();
 foreach   ($folio as $key);


 $header  = '<div class="location text-center" ><h4 class="modal-title"><strong>
 FOLIO: '.$key[2].'</strong></h4></div>';


 $title   = '';
 $onclick = 'GuardarFolio';


 $info    = '
 <div class="form-group">
 <label class="">Detalles de inventario: </label>
 <textarea class="form-control input-sm"  autofocus id="txtDetalles"></textarea>
 </div>

 <div class="form-group">
 <label class=""> Autorizó: </label>


 <div class="input-group" id="ErrorPass">
 <span class="input-group-addon inpu-xs"><i class="glyphicon glyphicon-user"></i></span>
 <input id="txtAutorizar" type="text" class="form-control input-xs"  placeholder="Indica la persona encargada de autorizar el inventario.">
 </div>
 <div id="txtRP" class="text-center"></div>
 </div>

 ';
 $id      = $key[0];

 // data add ---
 $array   = array($title,$onclick,$info,$header,$id);
 $txt =  MODAL_INFO($array);
 break;


 case 3:
 $folio   = $obj -> FOLIO_ACTIVO();
 foreach ($folio as $key);
 $Folio    = $key[2];
 $title   = '';
 $onclick = 'GuardarFolio';
 $info    = '<h4 class="text-success">Se ha agregado correctamente </h4> ';
 $header  = '<div class="location text-center " ><h4 class="modal-title Folio"><strong>
 FOLIO: '.$Folio.'</strong></h4></div>';
 $id      = $key[0];
 $array   = array($title,$onclick,$info,$header,$id);
 $txt =  MODAL_SUCCESS($array);
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

 function MODAL_WARNING($array){

  $title          = $array[0];
  $onclick        = $array[1];
  $info           = $array[2];
  $header         = $array[3];
  $id             = $array[4];

  $modal = '
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  '.$header.'
  </div>

  <div class="modal-body">

  <div class="mx-auto d-block">
  <center>
  <span class="text-warning icon-warning-empty fa-4x"></span>

  <h4 class="text-sm-center mt-2 mb-1">'.$title.'</h4>
  '.$info.'
  </center>
  </div>

  </div>


  <div class="modal-footer">
  <button class="btn btn-primary btn-xs" onclick="'.$onclick.'('.$id.')">Aceptar</button>
  <button class="btn btn-danger btn-xs" data-dismiss="modal">Salir</button>
  </div>

  ';

  return  $modal;
 }

 function MODAL_INFO($array){

  $title          = $array[0];
  $onclick        = $array[1];
  $info           = $array[2];
  $header         = $array[3];
  $id             = $array[4];

  $modal = '
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  '.$header.'
  </div>

  <div class="modal-body">
  <h3 class="text-sm-center mt-2 mb-1">'.$title.'</h3>
  '.$info.'
  </div>

  <div class="modal-footer">

  <button class="btn btn-primary btn-xs" onclick="'.$onclick.'('.$id.')"> <span class="fa fa-check-circle"></span> Finalizar inventario</button>
  <button class="btn btn-danger btn-xs" data-dismiss="modal">Salir</button>

  </div>

  ';

  return  $modal;
 }


 ?>
