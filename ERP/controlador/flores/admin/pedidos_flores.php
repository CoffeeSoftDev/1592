<?php 
include_once("../../../modelo/SQL_PHP/_FLORES_FOLLAJES.php");
$obj       = new _PRODUCTOS;


$encode = '';

switch ($_POST['opc']) {
 
 case 0:

  $tb = '<div class="table-responsive">';
  $tb .= '<table id="tbPedidos" class="table table-striped table-bordered table-hover" 
   style="width:100%">';

  /*----------THEAD------------*/
  $tb .= '<thead><tr>';
  $tb .= '</tr><thead>';
 
  $tb .= '<tbody>';
  $List_ticket = $obj->verFacturas();
  $tb .= '</tbody></table></div>';

  $encode	=	array(0=>$tb);
 
 break;

}


 /*JSON ENCODE -----------*/
//  $encode	=	array(0=>$idFolio,1=>$Folio,2=>$date,3=>$label);


 echo	json_encode($encode);
 ?>