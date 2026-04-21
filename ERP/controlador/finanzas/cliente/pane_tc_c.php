<?php
session_start();
$area_sesion  = $_SESSION['area'];
include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
$fin = new Finanzas;

$opc = $_POST['opc'];
switch ($opc) {
 case 0:

 $id_folio       = $_POST['id_folio'];
 $monto          = $_POST['monto'];
 $terminal       = $_POST['terminal'];
 $tipo_terminal  = $_POST['tipo_terminal'];
 $concepto       = $_POST['concepto'];
 $especificación = $_POST['especificación'];
 $cliente        = $_POST['cliente'];
 $autorizacion   = $_POST['autorizacion'];
 $observaciones  = $_POST['observaciones'];

 $array = array($monto,$terminal,$tipo_terminal,$concepto,$especificación,$autorizacion,$observaciones,$id_folio,$cliente);
 $fin->Save_TC($array);
 break;

 case 1:
 $tb   = '';
 $date = $_POST['date'];
 $sql  = $fin->Select_TC_Data($date);
 $t    = 0;
 $tb .='
 <div class="col-sm-12 col-xs-12">
 <div id="txt-Total"></div>
 <table class="table table-bordered table-condensed table-strippped table-responsive table-hover" style="font-size:.8em;" id="tbtc">
 <thead>
 <tr>
 <th class="text-center">MONTO</th>
 <th class="text-center">TERMINAL</th>
 <th class="text-center">TIPO DE TC</th>
 <th class="text-center">CONCEPTO DE PAGO</th>
 <th class="text-center">ESPECIFICACIÓN</th>
 <th class="text-center">NOMBRE DEL CLIENTE</th>
 <th class="text-center"># AUTORIZACIÓN</th>
 <th class="text-center">OBSERVACIONES</th>
 <th class="text-center"><span class="fa fa-gear"></span></th>
 </tr>
 </thead>
 <tbody>';
 foreach ($sql as $key => $value) {
  $t  +=$value[0];
  $tb .= '
  <tr>
  <td class="text-right"><span class="icon-dollar"></span> '.number_format($value[0],2,'.',',').'</td>
  <td class="text-center">'.$value[1].'</td>
  <td>'.$value[2].'</td>
  <td>'.$value[3].'</td>
  <td>'.$value[4].'</td>
  <td>'.$value[5].'</td>
  <td class="text-right">'.$value[6].'</td>
  <td>'.$value[7].'</td>
  <td class="text-center">
  ';
  if ($area_sesion!=1) {

   $tb .= '

   <button type="button" title="Eliminar" class="btn btn-xs btn-danger" onClick="Delete_TC('.$value[9].');"><span class="icon-cancel"></span></button>

   ';

  }
 $tb .= '</td>
 </tr>';

 }
 $tb .=
 '</tbody>
 </table>
 </div>
 ';
 $encode = array(0=>$tb,1=>'<b style="font-size:1.2em; color:#09467E;">Total : '.number_format($t,2,'.',',').'</b>');
 echo json_encode($encode);

// echo json_encode(array($tb,21));



 break;
 case 2:
 $idTC = $_POST['idTC'];
 $fin->Delete_FROM($idTC);
 break;
}
?>
