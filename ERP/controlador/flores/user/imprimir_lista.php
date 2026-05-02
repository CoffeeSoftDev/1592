<?php
session_start();
include_once("../../../modelo/UI_TABLE.php");
$table = new Table_UI;

include_once("../../../modelo/complementos.php");
$com = new Complementos;

include_once("../../../modelo/SQL_PHP/_SQL.php");
$sql = new SQL;

include_once("../../../modelo/SQL_PHP/_PEDIDOS.php");
$obj = new PEDIDOS;

$opc   = $_POST['opc'];
$idE   = $_SESSION['udn'];
$json  = '';


switch ($opc) {
  case 1: // Formato pedidos
  $frm = '';
  $now  = $sql -> NOW();
  $frm  = imprimir_lista($now,$com,$obj);
  $json = array($frm);
  break;
  
  case 2:
  sleep(2);
  $frm = '<h3>Se han cerrado los pedidos</h3> ';
  $now  = $sql -> NOW();
  

  // $frm  = imprimir_lista($now,$com,$obj);
  $json = array($frm);
  break;
  
}

/* JSON ENCODE  -----------*/  
echo json_encode($json);

function imprimir_lista($now,$com,$obj){
   $f1      = $_POST['fi'];
   $hoy     = $com-> dia_format($now);
   $tb      = '';
   $adjunto = '';

  $tb .= '
  <div class="col-sm-6 ">
  
  <strong><i class="bx bxs-info-circle bx-md text-warning"></i> No se han cerrado los pedidos el dia de hoy!!</strong></div>
  <div class="col-sm-6 text-right">
  
  <a class="btn btn-info btn-lg" onclick="ExportarEnvios()">Cerrar pedidos</a>
  <br>
  </div>';

  // Encabezado  
  $tb .= '<div style="margin-top:30px;" class="" id="contenedor_ticket" >';
  $tb .= '<div class="row">';
  $tb .= '<div class="col-xs-12 col-sm-5 "></div>';
  $tb .= '<div class="col-xs-12 col-sm-2 text-center" ><h4 class="ls-2"><strong>PEDIDOS</strong></h4>  </div>';
  $tb .= '<div class="col-xs-12 col-sm-5  " > <label style="font-size:.89em;">Tapachula, Chiapas a '.$hoy.'</label></div>';
  $tb .= '</div>';
  
  $list = $obj ->Consultar_folio(null);

  foreach($list as $cont => $key){

    $fecha_pedido = $com->dia_format($key[3]);
    $tb .= '<div style="margin-top:10px;" class="row">';
    $tb .= '<div class="col-xs-12 col-sm-12 "><b>'.$key[2].' '.$fecha_pedido.'</b></div>';
    $tb .= '</div>';

    $Files  =  $obj->select_idFichero(array($key[0]));
   
    


    $sql_producto = $obj->row_data(array($key[0]));
     
    $contar = count($sql_producto);

    if(count($sql_producto)){
      $tb .= '<div class="row">';
      $tb .= '<div class="col-sm-12 col-xs-12">';

        foreach($sql_producto as $num => $list){
        //   $tb .= '<div class="col-sm-6 ">';
          $plus = '';
          if($list[6] != '' ){$plus = '('.$list[6].')';}

          $tb .= $list[2].' '.$list[5].'. '.$list[1].' '.$plus.'<br>';
        }


      $tb .= '</div></div>';
    }// End if



    
    $tb .= '<div class="row">';
    
    if($key[5]){
     $tb .= '<div class="col-xs-12 col-sm-12 "><strong style="text-decoration: underline ;">NOTA:-</strong>'.$key[5].'</div>';
    }
    $contar  = count($Files);
    if(count($Files)){
     $tb .= '<div class="col-xs-12 col-sm-12 "><strong style="text-decoration: underline ;">NOTA:-</strong> Pedido adjunto </div>';
    }

    $tb .= '</div>';


    
    


  }// end Foreach

  
  $tb .= '</div>';


   return $tb;
}

?>