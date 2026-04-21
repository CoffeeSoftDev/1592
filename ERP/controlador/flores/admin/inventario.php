 <?php
session_start();

include_once("../../../modelo/SQL_PHP/_FLORES_FOLLAJES.php");
$obj       = new _PRODUCTOS;

$opc = $_POST['opc'];

switch ($opc) {
case 1: // Mostrar disponibilidad
 $tb    = lista_ticket($obj);
 /* JSON ENCODE  -----------*/
 $encode = array($tb);
break;
}

/* JSON  ENCODE */
echo json_encode($encode);

function lista_ticket($obj){

 
  $tb = '<div class="table-responsive">';
  $tb .= '<table id="viewFolios" class="table table-bordered  table-condensed table-hover pd-10"  style="width:100%; font-size:.78em;">';

  /*----------THEAD------------*/
  $tb .= '<thead><tr>';
  for ($i = 0; $i < 3; $i++) {
    $tb .= '<th class="text-center"></th>';
  }

  $tb .= '</tr></thead>';

  /*----------TBODY------------*/
  $tb .= '<tbody>';
  $list = $obj->VerProductos(array(1));

  foreach ($List_ticket as $key) {

    $tb .= '<tr>';

    $tb .= '<td class="">' . $key[2] . '</td>';
  
    $tb .= '</tr>';
  }

  $tb .= '</tbody>';

  $tb .= '</table></div>';

  $tb .= '';
  return $tb;
}



?>