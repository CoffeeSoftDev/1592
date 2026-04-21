<?php
session_start();

$idE = $_SESSION['udn'];
$opc = $_POST['opc'];
include_once('../../../modelo/SQL_PHP/_Finanzas_Cheques.php');
$obj = new Files_Cheq;

switch ($opc) {
  
  case 0: // Consultar lista de cheques
    $f1     = $_POST['f_i'];
    $f2     = $_POST['f_f'];
    $th     = array('Fecha', 'Nombre', 'Banco', '#Cuenta', '# Cheque', 'Importe','Concepto', '');

    $sql    = $obj ->verCheques(array(2,$f1,$f2));
    $tb     = list_data($sql,$th);
    $encode = array(0=>$tb);
  break;
}


/* JSON  ENCODE */
echo json_encode($encode);

function list_data($sql,$th){
 
  $tb = '<div class="col-xs-12 col-sm-12"><div class="table-responsive">';
  $tb .= '<table id="tb_list" class="table table-bordered  table-condensed table-hover">';

  /*----------THEAD------------*/
  $tb .= '<thead><tr>';
  for ($i = 0; $i < count($th); $i++) {
    $tb .= '<th class="text-center">' . $th[$i] . '</th>';
  }

  $tb .= '</tr></thead>';

  /*----------TBODY------------*/
  $tb .= '<tbody>';


  foreach ($sql as $key) {

    $btn_active = '<a class="btn btn-primary btn-sm" onclick="Print_cheques('.$key[7].')"><span class="icon-file-pdf"></span></a>';

    $tb .= '<tr>';

    $tb .= '<td class="text-center col-sm-1" >'.$key[0].'</td>';
    $tb .= '<td class="">' . $key[1] . '</td>';
    $tb .= '<td class="">' . $key[2] . '</td>';
    $tb .= '<td class="text-center ">' . $key[3] . '</td>';
    $tb .= '<td class="text-center ">' .$key[4].'</td>';
    $tb .= '<td class="text-center ">' .$key[5].'</td>';
    $tb .= '<td class="col-sm-2">' .$key[6].'</td>';
    $tb .= '<td class="text-center">' . $btn_active . '</td>';
    $tb .= '</tr>';
  }

  $tb .= '</tbody>';

  $tb .= '</table></div></div>';

  $tb .= '';
  return $tb;
}

?>