<?php

include_once('../../../modelo/SQL_PHP/_Finanzas.php');
include_once('../../../modelo/SQL_PHP/_INGRESOS_TURISMO.php');
include_once('../../../modelo/UI_TABLE.php');
include_once('../../../modelo/UI_FORM.php');

$fin   = new Finanzas;
$tur   = new TURISMO;
$table = new Table_UI;
$form  = new FORM_UI;

$opc = $_POST['opc'];
$txt = '';

switch ($opc) {

 case 0:
 $data = $fin -> content_data(array($_POST['id']));
 $txt = $txt.'
 <div class="row form-vertical">

 <div style="margin-bottom:5px" class="form-group col-xs-12 ">
 <label >Proveedor </label>
 <input type="text" class="form-control input-xs" value="'.$data[0].'" disabled>
 </div>

 <div style="margin-bottom:5px" class="form-group col-xs-12 ">
 <label >Concepto </label>
 <input type="text" class="form-control input-xs" value="'.$data[1].'" disabled>
 </div>

 <div style="margin-bottom:5px" class="form-group col-xs-12 ">
 <label >Monto </label>
 <input type="text" class="form-control input-xs" value="'.evaluar($data[2]).'" disabled>
 </div>

 <div style="margin-bottom:5px" class="form-group col-xs-12 ">
 <label >Destino </label>
 <input type="text" class="form-control input-xs" value="'.$data[3].'" disabled>
 </div>

 <div style="margin-bottom:5px" class="form-group col-xs-12 ">
 <label >Pagador </label>
 <input type="text" class="form-control input-xs" value="'.$data[4].'" disabled>
 </div>

 <div style="margin-bottom:5px" class="form-group col-xs-12 ">
 <label >Observaciones </label>
 <textarea class="form-control " disabled> '.$data[5].'</textarea>
 </div><br><hr>';


 if ($data[8]!=null) {
  $txt = $txt.'<div  style="margin-bottom:5px; margin-top:5px " class="form-group col-xs-12 ">
  <a style="width:100%" class="btn btn-info btn-xs" href="'.$data[8].''.$data[7].'" download="'.$data[7].'" >Descargar comprobante</a>
  </div>';
 }



 $txt = $txt.'
 <div style="margin-bottom:2 px" class="form-group col-xs-12 ">
 <a style="width:100%" class="btn btn-danger btn-xs" onclick="cerrarModal()"> Salir </a>
 </div>

 </div>
 ';
 echo $txt;


 break;


 case 1: // Tabla gastos
 $date1        = $_POST['date1'];
 $date2        = $_POST['date2'];
 $title = array('Gastos','Total',$date1,$date2);
 $ok = $table -> VIEW_DATA_TABLE($title,'tbGastos');
 echo $ok;
 break;


 case 2: // Formulario de detalle gastos.
 $sql     = $fin  -> Select_Group_Gastos();
 $cbClase = $form -> input_multiple_select(array('Cuentas','Cuenta',null),$sql);
 echo    '
 <div class="row form-group ">
 '.$cbClase.'
 </div>

 <div class="row form-group">
 <div class="col-sm-6 col-xs-6"></div>
 <div class="col-sm-6 col-xs-6">
 <button type="button" class="col-sm-5 col-sm-offset-1 col-xs-5 col-xs-offset-1 btn btn-xs btn-success" onclick="EXCEL_MOVIMIENTOS()">EXCEL</button>
 <button type="button" class="col-sm-5 col-sm-offset-1 col-xs-5 col-xs-offset-1 btn btn-xs btn-danger" onclick="PDF_MOVIMIENTOS()">PDF</button>
 </div>
 </div>';
 break;

 /*-----------------------------------*/
 /*		Archivos adjuntos / direccion
 /*-----------------------------------*/

 case 3:
 $fi   = $_POST['fi'];
 $ff   = $_POST['ff'];
 $idC  = $_POST['idC'];

 $tb   = '';
 $tb  .= '<div class="table-responsive">';
 $tb  .= ' <table style="font-size:.8em" class="table table-bordered table-condensed">
 <thead>
 <tr>';

 $tb .= '
 <th></th>
 <th>Nombre</th>
 <th>Fichero</th>
 <th>Fecha</th>
 <th>Hr</th>
 <th class="col-sm-2"></th>
 </tr>';
 $tb .= '</tr></thead><tbody>';
 // $files       = $tur -> archivo_adjunto(array($idC,$fi,$ff));
 $files       = $fin -> ver_check_list(array($idC,$fi,$ff));

 foreach ($files as $key) {

  $motivo    = $key[5];
  $motivo    = $key[5];

  $check    = '<i class="icon-ok-circled text-success" style="font-size:1.2em"></i>';
  if ($motivo!='') {
   $check = '<i class=" icon-cancel-circled text-danger" style="font-size:1.2em"></i>';
  }

  $tb .= '<tr>';
  $tb .= '<td class="text-center">'.$check.'</td>';
  $tb .= '<td>'.$key[0].'</td>';
  $tb .= '<td>'.file_empty($key[1]).'</td>';
  $tb .= '<td>'.$key[2].'</td>';
  $tb .= '<td>'.$key[3].'</td>';
  $tb .= '<td>'.btn_file($key[4],$key[1],$key[5]).'</td>';
  $tb .= '</tr>';
 }
 $tb .= '</tbody></table>';
 $tb  .= '</div>';

 //  $tb .= '
 // <div class="table-responsive">
 //

 //  </thead><tbody>';
 //
 //
 //  foreach ($files as $key) {

 //   <td>'.$key[1].'</td>
 //   <td>'.$key[4].'</td>
 //   <td>'.$key[3].'</td>
 //   <td class="text-center">
 //   </td>
 //   </tr>
 //   ';
 //  }

 //
 echo $tb;
 break;


 case 5: // 07-04-20 Boton nuevo folio
  $Observaciones = $_POST['obs'];
  $hoy    = $fin -> NOW();


  $Consultar_si  = $fin->ExisteFolio(array($hoy));

  if($Consultar_si !=1){
    $folio  = $fin -> ContarFolio();
    $array  = array($folio,$hoy,1,$Observaciones);
    $ok     =  $fin -> Nuevo_folio($array);
  }else{
   echo 'Ya existe folio';
  }
 
 break;

}

/*-----------------------------------*/
/* Funciones & Complementos
/*-----------------------------------*/

function file_empty($fichero){

 if ($fichero == '') {
  $fichero = '** Fichero no encontrado.';
 }

 return $fichero;
}

function btn_file($ruta,$file,$motivo){
$btn = '<a class="btn btn-xs btn-primary" href="'.$ruta.''.$file.'" target="_blank"> Descargar</a>';
if ($file=='') {
 $btn = ''.$motivo;
}
return $btn;
}
?>