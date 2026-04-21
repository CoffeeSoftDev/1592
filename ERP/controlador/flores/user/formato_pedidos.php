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

 case 1: // Buscar direccion
    $id    = $_POST['dir'];
    $array = array($id);
    $rp    = $obj->DetalleDestino($array);
    $txt   = 1;

    if ($rp=='0') {
      $rp   ='<span class="icon-warning-empty text-warning"></span> Cliente no encontrado';
      $txt   = 0;
    }else {
      $rp ='<span class="icon-ok-circled text-success"></span> '.$rp;
    }

    $json = array(
      0=>$txt,
      1=>$rp);
 break;

 case 2:// Actualizar cliente/destino
  $Cliente = $_POST['Destino'];
  $Folio   = $_POST['Folio'];
  $idCliente     = $obj->get_id_cliente(array($Cliente));
  $obj-> ActualizarCliente(array($idCliente,$Folio));

 break;

 case 3: // Agregar descripcion


    $fila       = $_POST['fila']; // nombre producto
    $idFila     = $_POST['idFila']; // id Lista Producto
    $idFolio    = $_POST['fol']; //Ticket no
    $existe     = '';
    $btn        = '';

    $res          = $obj-> consutar_si_existe(array($idFila));
    $idProducto   = $obj-> __getProducto_name(array($fila));
    $Precio       = 0;
    if(count($res)){ // PRODUCTO ENCONTRADO

      $array = array($fila,$idFila);
      $where = array('idListaProductos');

      $upd = $sql-> _UPDATE($array,array('descripcion'),'hgpqgijw_ventas.listaproductos',$where);
      $_idFila = $idFila;

      $existe  = 'Actualizar';

    }else{ // AGREGAR PRODUCTO A LISTA

      if(count($idProducto) == 0){ // NO INSERTA EL PRODUCTO
       $existe = 'no existe';
      }else{

        foreach ($idProducto as $dat );

          $Precio       = $dat[1];
        $data  = array($idFolio,$fila,$dat[0],$dat[1]);

        $title = array('id_lista','descripcion','id_productos','costo');
        $sql -> _INSERT($data,$title,'hgpqgijw_ventas.listaproductos');

        // ---Consultar el ultimo registro
        $_idFila = $obj-> get_ultimo_registro();
        $existe = 'insertar';

      }
    }

    $btn      = '<a class="pointer" onclick="Quitar('.$_idFila.')"><i class="bx bx-trash bx-md text-warning"></i></a>';

   $json = array(0=>$_idFila,1=>$existe,2=>$btn,3=>$Precio);
 break;

 case 4: //Agregar cantidad
  $id_lista  = $_POST['fol'];
  $idFila    = $_POST['id'];
  $value     = $_POST['item'];
  $status    = '';
  $d         = '';

  $where  = array('idListaProductos','id_lista');
  $array  = array($idFila,$id_lista);

  $select =  $sql  ->Select_data('idListaProductos',
  'hgpqgijw_ventas.listaproductos',
  $where,$array,0);

  $count = count($select);

  if ($count) {// Se encuentran datos

   foreach ($select as $key);
   $array = array($value,$key[0]);

   $d  = $key[0];
   $where = array('idListaProductos');

   $upd = $sql-> _UPDATE($array,array('cantidad'),'hgpqgijw_ventas.listaproductos',$where);
   $status  = 'actualizo cantidad';
  }else {// agregar datos

   $data  = array($fol,$id,$val);
   $title = array('id_lista','id_productos','cantidad');
   $sql -> _INSERT($data,$title ,'hgpqgijw_ventas.listaproductos');
   $status = 'Inserto cantidad';
  }

  $json = array($count);
 break;

 case 5:
  $fol      = $_POST['fol'];
  $idFolio  = $_POST['id'];
  $item     = $_POST['item'];
  // -----------------------
  $id       = $obj-> get_IDUnidad(array($item));

  $campos  = array('id_unidad');
  $where   = array('idListaProductos');
  $array   = array($id,$idFolio);

  $sql -> _UPDATE($array,$campos,'hgpqgijw_ventas.listaproductos',$where);

  $json = array( 0=>$id);
 break;

 case 6: // Agregar una observación
  $id_lista = $_POST['fol'];
  $idFila   = $_POST['id'];
  $item    = $_POST['item'];

  $where    = array('idListaProductos');
  $array    = array($item,$idFila);

  $upd = $sql-> _UPDATE($array,array('observacion'),'hgpqgijw_ventas.listaproductos',$where);
  $status  = 'actualizo cantidad';

  $json    = array($status);
 break;

 case 8: // lista de pedidos

  $table = lista_tickets($obj);
   $json = array($table);

 break;

 case 9: // Ticket de pedido

  $th    = array('Producto','Cant','Precio','Total','detalles','');
  $table = ticket($obj,$_POST['id'],$th,$com);
  $json = array( 0=>$table);
 break;

 case 10: // Cancelar Folio
    $id           = $_POST['id'];
    $SET          = array('id_Estado');
    $WHERE        = array('idLista');

    $array        = array(3, $id);
    $sql ->_UPDATE($array, $SET, 'hgpqgijw_ventas.lista_productos', $WHERE);

  break;

  case 12: // Buscar precio de la flores
   $nombre     = $_POST['Producto'];
   $producto   = $obj-> __getProducto_name(array($nombre));

   foreach ($producto as $key) {
    $idProducto = $key[0];
    $Venta = $key[1];
   }


   $json = array(0=>$Venta);
  break;
}

/* JSON ENCODE  -----------*/
echo json_encode($json);

// Funciones & Complementos -------------------

function ticket($obj,$idFolio,$th,$c){
  sleep(1);
  $Total             = 0;
  $TotalCanasta      = 0;
  $btn_cancel        = '';
  $tb                = '';

  $TotalPedido       = 0;

  $sql  = $obj -> ver_folio(array($idFolio));


  foreach ($sql as $_TICKET );
  $dia   = $c-> dia_format($_TICKET[0]);

  $sql  = $obj -> InformacionCliente(array($_TICKET[1]));
  foreach ($sql as $_CLIENTE );


  $tb .= '<div class="mt-20" id="contenedor_ticket">';

  $tb .= '<div style="margin-top:10px;" class="row">';
  $tb .= '<div class="col-xs-6 col-sm-9 t_1" > '.$dia.' <br> CLIENTE: '.$_CLIENTE[1].' </div>';
  $tb .= '<div class="col-xs-6 col-sm-3 text-right text-danger" <b> ORDEN: #'.$idFolio.'</b></div>';
  $tb .= '</div>';

  $Files  =  $obj->select_idFichero(array($idFolio));
  $archivos = count($Files);

  if($archivos != 0){

    $tb .=  'ARCHIVOS ADJUNTOS  '.$archivos.'';

  }

  $tb .= '</div>';
  $tb .= '<div class="row"><div class="col-xs-12 col-sm-12 ">';
  $tb .= '<strong>NOTA.- </strong> <textarea class="form-control t_1" disabled>'.$_TICKET[5].'</textarea>';
  $tb .= '</div></div>';


  foreach ($Files as $file){
  $tb .= '<iframe src="'.$file[1].''.$file[2].'" style="min-width:100%; height:600px;" ></iframe>';
  }


  /*   BODY */
  $tb .= '<div class="row">';
  $sql_producto = $obj->row_data(array($idFolio));

  $tb .= '<div class="col-sm-12"><br>';
  $tb .= '<table style="font-size:.8em;" class="table table-bordered table-xtra-condensed table-hover table-movil">';
  $tb .='<thead><tr class="pointer">';

  for ($i = 0; $i < count($th); $i++) {
  $tb .='<th>'.$th[$i].'</th>';
  }

  $tb .='</tr></thead>';
  $tb .='<tbody>';



  foreach($sql_producto as $key){

  $tb .= '<tr>';

  $tb .= '<td><input class="form-control" value="'.$key[1].'"></td>';
  $tb .= '<td><input class="form-control" value="'.$key[2].'"></td>';



  $tb .= '<td class="text-right"> '.evaluar($key[7]).'</td>';

  $total =   $key[2] * $key[7];

  $TotalPedido  = $total + $TotalPedido;

  $tb .= '<td><input class="form-control text-right" value="'.$total.'"></td>';
  $tb .= '<td><input class="form-control" value="'.$key[6].'"></td>';
  $tb .= '
    <td>
      <a class="btn btn-warning btn-xs" onclick="opciones_flores()"><i class="bx bxs-star"></i></a>
    </td>';


  $tb .= '';
  $tb .= '</tr>';
  }
  $tb .='</tbody></table></div>';
  $tb .= '</div>';


 $tb .= '<div class="row"><div class="col-xs-12 col-sm-12 text-right">';
  $tb .= '<strong>TOTAL .- '.evaluar($TotalPedido).'</strong> ';
  $tb .= '<br><br></div></div>';


  $tb .= '


  </div>';

  return $tb;

}

/* ---------------------------------- */
/* Historial de pedidos               */
/* ---------------------------------- */

function lista_tickets($obj){
   $f1 = $_POST['f_i'];
   $f2 = $_POST['f_f'];

   $okTable    = $obj -> VerFormatos(array($f1,$f2));


   $table = '
   <table id="viewFolio" class="table table-bordered table-condensed" style="font-weight:400;">
   <thead>
   <tr>
    <th class="col-xs-1 text-center">('.count($okTable).')</th>
    <th class="text-center">Lugar/Destino</th>
    <th class="text-center">Fecha</th>
    <th class="text-center">Hora</th>
    <th class="text-center">Estado</th>
    <th class="col-sm-1 text-center"></th>
   </tr>
   </thead>
   <tbody>';


   // Imprime todo los formatos solicitados
   foreach($okTable as $key){

    $t  = $obj -> Total_Productos(array($key[0]));
    $btn_file = '';


    $btn_active  = '<a class="pointer text-primary"><i class="bx bx-package bx-md" onclick="verTicket('.$key[0].')"></i></a>';

    $Files  =  $obj->select_idFichero(array($key[0]));

    if($Files){
    foreach ($Files as $file);
     $btn_active   = '<a class="pointer text-primary"  title="'.$file[2].'">';
     $btn_active  .= '<i class="bx  bx-cloud-download bx-md" onclick="verTicket('.$key[0].')"></i></a>';
    }

    $btn_active .= '<a class="pointer text-danger"><i  class="bx bx-block bx-md" onclick="CancelarFolio('.$key[0].',\''.Format_fol($key[0]).'\')"></i></a>';





    $table .= '<tr  class="pointer" >
    <td class="text-center"><strong>'.Format_fol($key[0]).'</strong></td>
    <td class="text-center">'.$key[2].'</td>
    <td class="text-center">'.$key[3].'</td>
    <td class="text-center">'.$key[4].'</td>
    <td class="text-center">'.Estado_Folio($key[5]).'</td>

    <td class="col-sm-1" >'.$btn_active.'</td>
    </tr>';
   }

   $table    .= '</tbody></table>';

   return $table;
}

// Complementos ****
 function Estado_Folio($idEstado){
  $estado = '';
  switch ($idEstado) {
    case 1:
      break;
    case 2:
     $estado = '<span class="text-primary">En proceso </span>';
    break;

  }
  return $estado;
 }

 function Format_fol($Folio) {
   $NewFolio = 0;
   if($Folio >= 1000){
    $NewFolio = $Folio;
   }
   else if($Folio >= 100){
    $NewFolio = "0".$Folio;
   }
   else if($Folio >= 10){
    $NewFolio = "00".$Folio;
   }
   else if($Folio >= 1){
    $NewFolio = "000".$Folio;
   }
   return $NewFolio;
  }
