<?php
session_start();

include_once('../../modelo/SQL_PHP/_DIA_VENTAS.php');
$obj = new DIA_VENTAS;

include_once('../../modelo/SQL_PHP/_Utileria.php');
$util = new Util;

include_once('../../modelo/complementos.php');
$com = new Complementos;

include_once('../../modelo/UI_TABLE.php');
$table = new Table_UI;

include_once('../../modelo/SQL_PHP/_SQL.php');
$SQL = new SQL;

$idE = $_SESSION['udn'];
$opc = $_POST['opc'];

switch ($opc) {
  case 1: // Todos los tickets generados
    // sleep(1);
    $f1    = $_POST['f_i'];
    $f2    = $_POST['f_f'];
    $title = array('Folio', 'Cliente', 'Fecha', 'Venta', 'F. de Pago', 'Factura', '');
    $tb    = lista_ticket($obj, $util, $title, $f1, $f2);
    /* JSON ENCODE  -----------*/
    $encode = array($tb);
    break;

  case 2: // Formato de ticket
    $id_lista = $_POST['id'];
    $fol      = $_POST['fol'];
    $cliente  = $_POST['cliente'];
    $fecha    = $_POST['fecha'];
    $ticket   = ticket($obj, $util, $id_lista);
    /* JSON ENCODE  -----------*/
    $encode = array($ticket);

    break;

  case 3: // Clientes registrados
    $th  = array('Credito', 'Cliente', 'Telefono', 'Correo', 'Dias', 'Credito', '');
    $txt = array('', 'Name_Cliente', 'Telefono', 'Email', 'dias_credito', 'Credito');

    $tb  = table_input($th, $obj, $txt, null);
    /* JSON ENCODE  -----------*/
    $encode = array($tb);
    break;

  case 4: // Historial de credito
    $txt    = $_POST['txt'];
    $id     = $_POST['id'];
    $val    = $_POST['val'];

    $campos = array($txt);
    $where  = array('idCliente');
    $array  = array($val, $id);

    $ok = $SQL->_UPDATE($array, $campos, 'hgpqgijw_dia.clientes', $where);
    $encode   =   array(0 => $ok);
    break;

  case 5: // Detalles de la cuenta
    $idCliente = $_POST['idCliente'];
    $c  = $_POST['c'];
    $t  = $_POST['t'];
    $c1 = $_POST['c1'];

    $datos = array($c, $t, $c1);

    $frm   = '';
    $th    = array('Factura / Ticket', 'Fecha Factura', 'Importe de factura', 'Deposito', 'Fecha de ult. Pago', 'Venc.', 'Dias vencidos', 'Saldo Pendiente', '');
    $frm  .= header_format($obj, $th, $idCliente);


    $encode   = array(0 => $frm);
    break;

  case 6: // Abono a cuenta
    $id    = $_POST['id'];
    $monto = $_POST['Monto' . $id];
    $ob    = $_POST['Observacion' . $id];
    $fecha = $com->fecha_hora_servidor();

    $values = array($monto, $ob, $id, $fecha);
    $data   = array('abono', 'detalles', 'id_lista', 'Fecha_abono');

    $add    = $SQL->_INSERT($values, $data, 'hgpqgijw_dia.factura_abono');

    $encode   = array(0 => $id);
    break;

  case 7: //tabla facturas
    $f1    = $_POST['f_i'];
    $f2    = $_POST['f_f'];

    $title = array('Fol. Factura', 'Folio de ticket', 'Cliente', 'Fecha Factura', 'CFDI', 'Razon Social', 'C.P.', 'Telefono', 'Correo', 'Direccion', '');
    $tb    = lista_factura($obj, $util, $title, $f1, $f2);

    $encode = array($tb);
    break;

  case 8: // Crear factura con ticket
    $Folio = $_POST['factura'];
    $id    = $_POST['id'];
    $Monto = $_POST['Total'];
    $Fecha = $com->fecha_servidor();

    $folio = $obj->Existe_Factura(array($Folio));

    if ($folio) { // Se ha encontrado un folio existente.
      $campos     =  array('idFactura');
      $where      =  array('idLista');
      $array      = array($folio, $id);
      $SQL->_UPDATE($array, $campos, 'hgpqgijw_dia.lista_folio', $where);
    } else {
      $values = array($Folio, $Fecha, $Monto);
      $data   = array('Folio', 'Fecha_Factura', 'Total');
      $SQL->_INSERT($values, $data, 'hgpqgijw_dia.lista_facturas');
      // Obtener factura creada
      $idFactura  = $obj->get_id_factura();
      $campos     =  array('idFactura');
      $where      =  array('idLista');
      $array      = array($idFactura, $id);
      $SQL->_UPDATE($array, $campos, 'hgpqgijw_dia.lista_folio', $where);
    }


    $encode = array(' Folio encontrado ' . $folio);
    break;

  case 9: // Subir archivos de Factura 
    sleep(1);
    $id        = $_POST['link'];
    $num_files = $_POST['files'];
    $mnsj = subir_archivos($obj, $com, $id, $num_files);
    $encode = array($mnsj);
    break;

  case 10: // Detalles de abonos
    $id           = $_POST['idFactura'];
    $Factura      = $_POST['Factura'];
    $Cliente      = $_POST['Cliente'];
    $Importe      = $_POST['Importe'];

    $th           = array('Fecha de abono', 'Hora ', 'Cantidad', 'Observacion');
    $abonos       = ver_abonos($obj, $th, $id, $Factura, $Cliente, $Importe);
    $encode       = array($abonos);
    break;

  case 11: // Cancelar Folio 
    $id           = $_POST['id'];
    $SET          = array('id_Estado');
    $WHERE        = array('idLista');
    $array        = array(2, $id);
    $SQL->_UPDATE($array, $SET, 'hgpqgijw_dia.lista_folio', $WHERE);
    $encode = array($SQL);
    break;

  case 12: // Consultar facturas existentes
    $bd      = 'hgpqgijw_dia.lista_facturas';
    $select  = $SQL->Select_data('Folio', $bd, null, null, 1);
    $encode = array();

    foreach ($select as $key => $row) {
      $encode[$key] = $row[0];
    }

    break;
}

/* JSON  ENCODE */
echo json_encode($encode);

/* ---------------------------------- */
/* Historial de ticket                */
/* ---------------------------------- */

function lista_ticket($obj, $util, $th, $f1, $f2)
{

  $ticket = 'P';
  $tb = '<div class="table-responsive">';
  $tb .= '<table id="viewFolios" class="table table-bordered  table-condensed table-hover pd-10"  style="width:100%; font-size:.78em;">';

  /*----------THEAD------------*/
  $tb .= '<thead><tr>';
  for ($i = 0; $i < count($th); $i++) {
    $tb .= '<th class="text-center">' . $th[$i] . '</th>';
  }

  $tb .= '</tr></thead>';

  /*----------TBODY------------*/
  $tb .= '<tbody>';
  $List_ticket = $obj->list_ticket(array($f1, $f2));

  foreach ($List_ticket as $key) {
    $Fol_ticket = Folio($key[1], $ticket);

    $btn_active = ' <a title="Ver ticket de venta" class="fa-2x pointer text-primary" onclick="verFolios(' . $key[0] . ', \'' . $key[1] . '\',\'' . $key[2] . '\',\'' . $key[3] . '\' )"><span class="lnr lnr-store"></span></a>';

    $btn_active .= '<a title="Cancelar Venta" class="fa-2x text-danger pointer" onclick="CancelarFolio(' . $key[0] . ',\'' . $Fol_ticket . '\')"><span class="bx bx-block"></span></a>';



    if ($key[6] != 1) {
      $ticket = 'E';
    }


    $bg  = '';
    if ($key[5] != '' && $key[7] == '') {
      $bg = ' class="bg-warning text-warning"';
    }
    $tb .= '<tr ' . $bg . '>';

    $tb .= '<td class="text-center " ><b title="key: ' . $key[0] . '">' . $Fol_ticket . '<b></td>';
    $tb .= '<td class="">' . $key[2] . '</td>';
    $tb .= '<td class="text-center ">' . $key[3] . '</td>';
    $tb .= '<td class="text-right ">' . evaluar($key[4]) . '</td>';

    $tipo_pago  =  ico_tipo_pago($key[8], $key[9], $key[10], $key[11]);
    $tb .= '<td class="text-center col-sm-2"> ' . $tipo_pago . ' </td>';
    $tb .= '<td class="text-center "> ' . ico_factura($key[5]) . '  </td>';

    $tb .= '<td class="text-center col-sm-2">' . $btn_active . '</td>';
    $tb .= '</tr>';
  }

  $tb .= '</tbody>';

  $tb .= '</table></div>';

  $tb .= '';
  return $tb;
}

function ticket($obj, $util, $id)
{
  $Total             = 0;
  $TotalCanasta      = 0;
  $btn_cancel        = '';
  $txt               = '';


  /*--------- Detalles del ticket-----------*/

  $_TICKET  = $obj->_TICKET_INFO(array($id));
  $folio    = get_tipo_folio($_TICKET[10], $_TICKET[9]);

  $txt  .= ' <div style="margin-top:10px;" class=" row "><div class="form-horizontal">';

  if ($_TICKET[5] == '') { // No tiene factura.

    $txt  .= '<div class="col-sm-4 col-dm-3 col-lg-3 col-xs-6 ">';
    $txt  .= '<a class="btn btn-sm btn-default" onclick="Factura(' . $id . ')"><span class="ico-1x lnr lnr-file-empty"></span> Facturar...</a>';
    $txt  .= '</div>';
  } else {

    $txt  .= '<div class=" col-sm-4 col-dm-3 col-lg-4 col-xs-6">';
    $txt  .= '<label class="control-label"> Factura:  </label> ' . $_TICKET[6] . '';
    $txt  .= '</div>';
  }

  $txt  .= '<div class="col-sm-3 col-xs-3 text-right">';
  $txt  .= '<a class="btn btn-sm btn-default" onclick="print_formato(\'contenedor_ticket\')"><span class="ico-1x lnr lnr-printer"></span> Imprimir </a>';
  $txt  .= '</div>';

  $txt  .= '</div></div>';




  $txt .= '<div style="margin-top:10px;" id="contenedor_ticket" class="">';
  $txt .= '
 <div class="row">
 <div class="col-xs-6 col-sm-8 text-left">
 <b>Cliente:</b>  ' . $_TICKET[8] . '
 </div>
 
 <div class="col-xs-6  col-sm-4 text-right" title="'.$id.'">
 <strong>Folio:</strong> <span class="text-danger ico-1x">' . $folio . '</span>
 </div>
 
 </div>
 
 <div class="row">
 <div class="col-xs-6 col-sm-6 text-left">
 <strong> Fecha:</strong> ' . $_TICKET[7] . '
 </div>
 </div>
 
 ';

  $sql_producto = $obj->Select_Productos(array($id));
  $txt .= '
 <div class="row"><div class="col-sm-12 text-right"><b>No.articulos : </b> ' . count($sql_producto) . ' </div></div>';
  $txt .= '<table style="font-size:.71em; font-weight:600;" class="table table-bordered table-striped table-condensed ticket_table pd-10" id="tbfolio">';
  $txt .= '<thead>
 <tr>
 <th class="text-center">Producto </th>

 <th class="text-center">Cant.</th>
 <th class="text-center">Costo</th>
 <th class="text-center">Total</th>
 <th class="text-center">Nota</th>
 <th class="text-center">Especial</th>
 </tr>
 </thead>';

  $txt .= '<tbody>';

  foreach ($sql_producto as $producto) {
    $Total += $producto[3];
    $precio_especial = '';
    if ($producto[4] == 1) {
      $precio_especial = '<span style="font-weight:700;" class="lnr lnr-checkmark-circle text-success"></span> ';
    } else if ($producto[4] == 3) {
      $precio_especial = '<span style="font-weight:700;" class="text-primary">Cortesía</span> ';
    }

    $txt = $txt . '
  <tr>
  <td title="'.$producto[6] .'">' . $producto[0] . '  (' . $producto[5] . ') </td>
  <td class="text-center">' . $producto[1] . ' </td>

  <td class="text-right">' . evaluar($producto[2]) . '</td>
  <td class="text-right">' . evaluar($producto[3]) . '</td>
  <td class="col-sm-4"><span style="font-size:1em;">' . $producto[7] . '</span></td>
  <td class="text-center">' . $precio_especial . '</td>
  </tr>';
  }
  $sql_canastas = $obj->Select_Canasta(array($id));


  $txt .= '</tbody>';

  if (count($sql_canastas)) { // Se vendieron canastas
    $txt .= '<tfoot><tr><td class="text-right" colspan="6"><h6>TOTAL: ' . evaluar($Total) . '</h6></td></tr></tfoot></table>';
    $txt .= '<div class="">';

    $txt .= '
  <div class="row">
  <div class="col-xs-6 col-sm-6 text-left">
  <strong>Canastas</strong>
  </div>
  <div class="col-xs-6 col-sm-6 text-right">
  
  </div>
  </div>
  ';
    $txt .= '
  <div class="row"><div class="col-sm-12 text-right"><b>No.articulos : </b> ' . count($sql_canastas) . ' </div></div>';
    $txt .= '<table class="table table-bordered table-xtra-condensed ticket_table" id="tbCanasta">';
    $txt .= '<thead>
  <th>Producto</th>
  <th>Presentación</th>
  <th>Cantidad</th>
  <th>Costo</th>
  <th>Total</th>
  <th>Especial</th>
  </thead>';
    foreach ($sql_canastas as $producto) {
      $TotalCanasta += $producto[3];
      $precio_especial = '';
      if ($producto[4] == 1) {
        $precio_especial = '<span style="font-weight:700;" class="lnr lnr-checkmark-circle text-success fa-2x"></span> ';
      }

      $txt = $txt . '
   <tr>
   <td>' . $producto[0] . '</td>
   <td class="text-right">' . $producto[5] . '</td>
   <td class="text-right">' . $producto[1] . '</td>
   <td class="text-right">' . evaluar($producto[2]) . '</td>
   <td class="text-right">' . evaluar($producto[3]) . '</td>
   <td class="text-center">' . $precio_especial . '</td>
   </tr>';
    }

    $txt .= '</tbody><tfoot><tr><td class="text-right" colspan="6">TOTAL: ' . evaluar($TotalCanasta) . '</td></tr></tfoot></table>';
    $txt .= '</div>';
  } else { // No se han vendido canastas
    $txt .= '</table>';
  }



  $Gran_Total = $Total + $TotalCanasta;
  $txt .= '<div class="row"><div class="col-xs-12 col-sm-12 text-right">';
  $txt .= '
 <label class="text-bold text-xlg">Total: ' . evaluar($Gran_Total) . '</label>';
  $txt .= '</div>';
  // Canastas

  $txt .= Forma_pago($_TICKET[0], $_TICKET[1], $_TICKET[2], $_TICKET[3]);
  $txt .= '</div>';

  $txt .= '<input id="txtTotalTicket" type="hidden" value="' . $Gran_Total . '">';

  return $txt;
}

function Forma_pago($Mixto, $Efectivo, $TDC, $Credito)
{
  $txt = '';
  $txt .= '<div class="row">';

  if ($Mixto == 1) {

    $txt  .= '<div class="col-xs-12 col-sm-12 text-center">';
    $txt .= '<label class="text-sm ">* PAGO MIXTO *</label><br>';
    $txt .= '</div>';

    $txt  .= '<div class="col-xs-12 col-sm-12 text-right">';
    $txt .= '<label class="text-sm "> EFECTIVO: ' . evaluar($Efectivo) . '</label><br>';
    $txt .= '<label class="text-sm "> TDC: ' . evaluar($TDC) . '</label><br>';
    $txt .= '<label class="text-sm "> CREDITO: ' . evaluar($Credito) . '</label>';
    $txt .= '</div>';
  } else {

    $txt  .= '<div class="col-xs-12 col-sm-12 text-center">';
    if ($Efectivo != 0) {
      $txt .= '<label class="text-bold ">* PAGO EN EFECTIVO * </label>';
    } else if ($TDC != 0) {
      $txt .= '<label class="text-bold ">* PAGO CON TARJETA DE CREDITO *</label>';
    } else if ($Credito != 0) {
      $txt .= '<label class="text-bold ">* VENTA A CREDITO *</label>';
    }

    $txt .= '</div>';
  }
  // no Mixto

  $txt .= '</div>';

  return $txt;
}

/* ---------------------------------- */
/*   Clientes                         */
/* ---------------------------------- */

function txt($type, $txt, $id, $val, $disabled)
{

  $frm = '<td><input id="' . $txt . '' . $id . '" class="text-input input-sm" type="' . $type . '"
  value="' . $val . '" onkeyup="actualizar_datos(\'' . $txt . '\',' . $id . ')" ' . $disabled . '></td>';

  if ($disabled == 'disabled') {
    $frm = '<td><label>' . evaluar($val) . '</label></td>';
  }

  return $frm;
}

function table_input($t, $obj, $txt, $disabled){
  $rowspan    = $obj->rows_pan();
  $count_tab  = count($t);
  $tbacciones = $count_tab;
  $fila_x     = 0;

  $tb  = '';

  $tb .= '<div class="table-responsive">';

  /* ---------- Thead -----------*/
  $tb .= '<table id="tb_data" class="table table-bordered table-xtra-condensed" style="width:100%" >';
  $tb .= '<thead><tr>';
  for ($i = 0; $i < $count_tab; $i++) { // Imprimir thead
    $tb .= '<th style="vertical-align: inherit;" class="text-center">' . $t[$i] . '</th>';
  }
  $tb .= '</tr></thead>';

  /* ---------- Tbody -----------*/
  $tb .= '<tbody>';
  if (count($rowspan)) {
    foreach ($rowspan as $key) {
      $fila_x += 1;
      $tb .= '<tr>';
      $status = '<a class="pointer" onclick="activarCredito(0,' . $key[0] . ')"><i class="icon-toggle-on text-success" ></i></a>';
      if ($key[6] == '' || $key[6] == 0) {
        $status = '<a class="pointer" onclick="activarCredito(1,' . $key[0] . ')"><i class="icon-toggle-off " ></i></a>';
      }
      $tb .= '<td class="text-center">' . $status . '</td>';

      $tb .= txt('text', $txt[1], $key[0], $key[1], '');
      $tb .= txt('number', $txt[2], $key[0], $key[2], '');
      $tb .= txt('text', $txt[3], $key[0], $key[3], '');
      $tb .= txt('number', $txt[4], $key[0], $key[4], '');


      $SQL = $obj->verTodosLosdepositos(array($key[0]));
      $depositos = 0;
      foreach ($SQL as $_KEY) {
        $depositos = $_KEY[0];
      }

      $credito     = $key[5] - $depositos;

      $tb  .= '<td class="text-center">' . evaluar($credito) . '</td>';


      $tb .= '<td class="text-center">
    <a class="pointer" title="Cuenta del cliente" onclick="detallar_cuenta(' . $key[0] . ')">
    <i style="" class="fa-2x lnr lnr-store text-primary "></i>
    </a></td>';
      $tb .= '</td>';
    } //end foreach

  }
  $tb .= '</tbody>';
  $tb .= '</table>';
  $tb .= '</div>';
  return $tb;
}


function header_format($obj, $th, $idCliente)
{

  $_CLIENTE = $obj->get_Cliente(array($idCliente));
  $txt = '';

  $txt .= '<div class=" row"><div class=" col-sm-8 "><ul class="breadcrumb"><li><a onclick="ver_clientes()" class="pointer"><span class="lnr lnr-chevron-left-circle"></span> Lista de clientes </a></li> ';
  $txt .= '<li class="active"><a onclick="detallar_cuenta(' . $idCliente . ')" ><i class="pointer lnr lnr-sync"></i> Compras a crédito </a></li> </ul></div>';

  $txt .= '<div class="col-sm-4 col-xs-4 text-right"><a title="imprimir" style="color:black;" onclick="print_formato(\'FormatoCredito\')"><span class="ico-2x lnr lnr-printer"></span> Imprimir</a></div>';
  $txt .= '</div>';
  // Formato de Compras a credito
  $txt .= '<div class=" " id="FormatoCredito">';

  $txt .= '<div class="row">';
  $txt .= '<div class="col-xs-6 col-sm-8">';
  $txt .= '<span class="txt-dark"><b>Cliente: </b> ' . $_CLIENTE[1] . '</span> [' . $idCliente . ']<br>';
  $txt .= '<span class="txt-dark"><b>Telefono:</b> ' . $_CLIENTE[2] . '</span><br>';
  $txt .= '<span class="txt-dark"><b>Correo:</b>   ' . $_CLIENTE[3] . '</span><br>';

  $txt .= '</div>';

  $txt .= '
  <div class="col-xs-6 col-sm-4 text-right">
  <div class="input-group " style="width: 100%;">
  <div class="input-group-addon">
  <span class="fa-2x lnr lnr-calendar-full"></span>
  </div>
  <input type="text" class="form-control" value="' . $_CLIENTE[4] . '  dias" disabled>
  </div>
  
  </div>
  </div>';


  /*================Creditos del cliente ==========*/
  $deuda_total = 0;

  $tb  = '<div class=" table-responsive">';
  $tb .= '<table style="margin-top:10px" id="tbCredito" class="table table-striped table-condensed table-bordered  table-hover"  style="width:100%">';
  /*----------THEAD------------*/
  $tb .= '<thead><tr>';
  for ($i = 0; $i < count($th); $i++) {
    $tb .= '<th class="text-center">' . $th[$i] . '</th>';
  }
  $tb .= '</tr></thead>';
  /*----------TBODY------------*/
  $tb .= '<tbody>';
  $List_factura = $obj->factura_clientes(array($idCliente));

  foreach ($List_factura as $key) {
    $FOL     = get_tipo_folio($key[6], $key[5]);
    $abono   = 0;

    $ok    = $obj->get_abono(array($key[0]));
    $fecha = $obj->get_ult_abono(array($key[0]));

    if ($ok != 0) {
      $abono  = $ok[0];
    }
    $saldo = $key[3] - $abono;
    // <span title="'.$key[6].'">
    $tb .= '<tr >';
    $fol_factura = $key[1] . ' / ' . $FOL;
    $tb .= '<td class="text-center text-primary pointer" onclick="ver_abonos(' . $key[0] . ',\'' . $fol_factura . '\',\'' . $_CLIENTE[1] . '\',\'' . $key[3] . '\')">';
    $tb .= '<b>' . $fol_factura . '</b>';
    $tb .= '</td>';


    $tb .= '<td class="text-center">' . $key[2] . '</td>';
    $tb .= '<td class="text-right">' . evaluar($key[3]) . '</td>';
    $tb .= '<td class="text-right">' . evaluar($abono) . '</td>';
    $tb .= '<td class="text-center">' . $fecha . '</td>';
    $deuda_total += $saldo;
    // Vencimiento
    $venc    = agregar_dias($key[2], $_CLIENTE[4]);
    $dias = contar_dias($venc);
    $tb .= '<td class="text-center">' . $venc . '</td>';
    $tb .= '<td class="text-center">' . $dias . '</td>';
    $tb .= '<td class="text-right">' . evaluar($saldo) . '</td>';
    $tb .= '<td class="col-sm-2 text-center">';

    if ($saldo > 0) {
      $tb .= '<a style="text-decoration:none;" class="pointer " onclick="formulario_abonos(' . $key[0] . ',' . $idCliente . ',' . $saldo . ',0)"><i class="icon icon-calc text-info" ></i> Abonar </a>
    <a style="text-decoration:none;" class="pointer"  onclick="formulario_abonos(' . $key[0] . ',' . $idCliente . ',' . $saldo . ',1)"><i class="icon  icon-dollar-1 text-success"></i> Liquidar</a></td>';
    } else {
      $tb .= '<strong style="font-size:1.2em;">PAGADO</strong>';
    }

    $tb .= '</td></>';
  }



  $tb .= '</tbody>';

  $tb .= '</table></div>';
  $tb .= '<div class=" text-right"><h4>DEUDA TOTAL : ' . evaluar($deuda_total) . '</h4></div>';

  $txt .= $tb;
  $txt .= '</div>';
  return $txt;
}

function ver_abonos($obj, $th, $id, $Factura, $Cliente, $Importe)
{

  $total = 0;
  $tb = '<div class="text-right"><a style="text-decoration:none; color:black;" class="pointer" onclick="print_formato(\'Detalles_abonos\')"><span class="ico-2x lnr lnr-printer"></span> Imprimir</a>';
  $tb .= '</div>';
  $tb .= '<div  id="Detalles_abonos">';

  $tb .= '<div class="row">';

  $tb .= '<div class="col-xs-6 col-sm-6">';
  $tb .= '<span class="txt-dark"><b>Folio ó Factura : </b> ' . $Factura . '</span><br>';
  $tb .= '<span class="txt-dark"><b>Importe: </b> ' . evaluar($Importe) . '</span><br>';

  $tb .= '</div>';



  $tb .= '<div class="col-xs-6 col-sm-6">';
  $tb .= '<span class="txt-dark"><b>Cliente: </b> ' . $Cliente . '</span><br>';
  $tb .= '</div>';


  $tb .= '</div>';


  $tb  .= '<div class=" table-responsive">';
  $tb .= '<table style="margin-top:10px" id="tbCredito" class="table table-striped table-condensed table-bordered  table-hover"  style="width:100%">';

  /*----------THEAD------------*/

  $tb .= '<thead><tr>';
  for ($i = 0; $i < count($th); $i++) {
    $tb .= '<th class="text-center">' . $th[$i] . '</th>';
  }
  $tb .= '</tr></thead>';
  /*----------TBODY------------*/
  $tb .= '<tbody>';


  $list  = $obj->ver_montos(array($id));

  foreach ($list as $key) {

    $total   += $key[3];

    $tb .= '<tr>';
    $tb .= '<td class="text-center text-primary">' . $key[1] . '</td>';
    $tb .= '<td class="text-center">' . $key[2] . '</td>';
    $tb .= '<td class="text-center">' . evaluar($key[3]) . '</td>';
    $tb .= '<td class=" col-sm-6">' . $key[4] . '</td>';
    $tb .= '</tr>';
  }

  $tb .= '</tbody>';

  $tb .= '</table></div>';
  $tb .= '<div class=" text-right"><h4> TOTAL : ' . evaluar($total) . '</h4></div>';


  $tb .= '</div>';
  return $tb;
}

/* ---------------------------------- */
/* Pestaña Facturas                  */
/* ----------------------------------*/
function lista_factura($obj, $util, $th, $f1, $f2)
{


  $tb = '<div class="table-responsive">';
  $tb .= '<table id="tbFacturas" class="table table-striped table-bordered  table-hover pd-10"  style="width:100%">';

  /*----------THEAD------------*/
  $tb .= '<thead><tr>';
  for ($i = 0; $i < count($th); $i++) {
    $tb .= '<th class="text-center">' . $th[$i] . '</th>';
  }

  $tb .= '</tr></thead>';

  /*----------TBODY------------*/
  $tb .= '<tbody>';
  $List_ticket = $obj->verFacturas(array($f1, $f2));

  foreach ($List_ticket as $key) {
    $btn_active = '<a class="fa-2x pointer text-primary" onclick="verFolios(' . $key[0] . ', \'' . $key[1] . '\',\'' . $key[2] . '\',\'' . $key[3] . '\' )"><span class="lnr lnr-store"></span></a>';
    $ticket = 'P';
    if ($key[12] == 1) {
      $ticket = 'P';
    }

    $tb .= '<tr>';

    $tb .= '<td class="text-center"><b>' . $key[1] . '<b></td>';
    $tb .= '<td class="text-center">' . Folio($key[2], $ticket) . '</td>';
    $tb .= '<td class="text-center">' . $key[3] . '</td>';
    $tb .= '<td class="text-center">' . $key[4] . '</td>';
    $tb .= '<td class="text-center">' . $key[5] . '</td>';
    $tb .= '<td class="text-center">' . $key[6] . '</td>';
    $tb .= '<td class="text-center">' . $key[7] . '</td>';
    $tb .= '<td class="text-center">' . $key[8] . '</td>';
    $tb .= '<td class="text-center">' . $key[9] . '</td>';
    $tb .= '<td class="text-center">' . $key[10] . '</td>';
    $tb .= '<td class="col-sm-1 text-center" id="txtLoad' . $key[0] . '">';
    $file = $obj->archivo_factura(array($key[0]));

    if (count($file)) {

      foreach ($file as $_KEY) {
        $tb .= '<a style="text-decoration:none; font-size:1em; font-weight:500;"  class="pointer " href="' . $_KEY[0] . $_KEY[1] . '" target="_blank"><i style=" font-weight:600;" class="lnr lnr-download"></i> Descargar </a> ';
      }
    } else {
      $tb .= '<label style="text-decoration:none; font-size:1em; font-weight:500;" class="pointer text-primary">';
      $tb .= '<input type="file" style="display:none" id="archivos' . $key[0] . '" onchange="subir_fichero(' . $key[0] . ')">   <span style=" font-weight:600;" class="lnr lnr-upload"></span> Subir </a>';
    }
    $tb .= '</td>';

    $tb .= '</tr>';



    // 
    //   $tb .= '<td class="text-right">'.evaluar( $key[$x] ).'</td>';
    //   $tb .= '<td class="text-center">'.ico_factura( $key[5] ).'</td>';
    // 
    //   $tb .= '<td class="text-center">'.$btn_active.'</td>';
  }

  $tb .= '</tbody>';

  $tb .= '</table></div>';

  $tb .= '';
  return $tb;
}

function subir_archivos($obj, $com, $id, $num_files)
{
  $msj       = '';
  $date      = $com->fecha_servidor();
  $time      = $com->hora_servidor();
  $ymd       = $com->separar_fecha($date);
  $mes       = $com->obtener_mes($ymd[1]);
  $file_C    = $mes . '_' . $ymd[0];

  $ruta    = 'recursos/files_dia/' . $file_C . '/' . $ymd[2] . '/';
  $carpeta = '../../' . $ruta;

  //Busca si existe la carpeta si no la crea
  if (!file_exists($carpeta)) {
    mkdir($carpeta, 0777, true);
  }

  foreach ($_FILES as $cont => $key) {
    if ($key['error'] == UPLOAD_ERR_OK) {
      $NombreOriginal = $key['name']; //Obtenemos el nombre original del archivo
      $temporal = $key['tmp_name']; //Obtenemos la ruta Original del archivo
      $Destino = '../../' . $ruta . $NombreOriginal;

      move_uploaded_file($temporal, $Destino);

      $trozos    = explode(".", $NombreOriginal);
      $extension = end($trozos);
      $size      = ROUND(($key['size'] / 1024), 4); //tamaño en Kb, con 4 decimales

      $array = array(1, $ruta, $date, $time, $NombreOriginal, $size, $extension, $id);
      $obj->Insert_Files($array);

      $mnsj      = '<i class="ico-1x lnr lnr-file-empty"></i> ' . $NombreOriginal . '';
      //  $mnsj      = $array;
    } else {
      $mnsj      = 'ERROR DE ESCRITURA ';
    }
  }

  return $mnsj;
}

/* ---------------------------------- */
/*  Complementos                  */
/* ----------------------------------*/
function ico_factura($val)
{
  $ico = '<span class="ico-2x lnr lnr-checkmark-circle text-success"></span>';
  if ($val == null) {
    $ico = '';
  }
  return $ico;
}

function ico_tipo_pago($Mixto, $Efectivo, $TDC, $Credito)
{
  $ico = '';
  if ($Mixto == 1) {
    $ico = ' Pago Mixto';
  } else {

    if ($Efectivo != 0) {
      $ico = 'Efectivo';
    } else if ($TDC != 0) {
      $ico = 'TDC';
    } else if ($Credito != 0) {
      $ico = 'Credito';
    }
  }
  // no Mixto

  return $ico;
}


function Folio($Folio, $Area)
{
  $NewFolio = 0;
  $Folio = $Folio;
  if ($Folio >= 1000) {
    $NewFolio = $Area . '' . $Folio;
  } else if ($Folio >= 100) {
    $NewFolio = $Area . '0' . $Folio;
  } else if ($Folio >= 10) {
    $NewFolio = $Area . '00' . $Folio;
  } else if ($Folio >= 1) {
    $NewFolio = $Area . '000' . $Folio;
  }
  return $NewFolio;
}

function cb_factura($sql, $id, $val)
{
  $frm  = '';

  $frm .= '<select id="txtFactura' . $id . '"  class="text-input"  onchange="actualizar_datos(\'txtFactura\',' . $id . ')" >';
  if ($sql != null) {
    foreach ($sql as $row) {
      if ($val == '') {
        $frm .= '<option value="0" selected>SIN FACTURA </option>';
      } else if ($val == $row[1]) {
        $frm .= '<option value="' . $row[0] . '" selected>' . $row[1] . '</option>';
      } else {
        $frm .= '<option value="' . $row[0] . '">' . $row[1] . '</option>';
      }
    }
  }
  $frm .= '</select>';
  $frm .= '';
  return $frm;
}

function get_tipo_folio($tipo, $num)
{
  $serie = 'P';
  if ($tipo != 1) {
    $serie = 'E';
  }
  $folio    =  Folio($num, $serie);
  return $folio;
}

function agregar_dias($fecha, $days)
{
  $date =  date("d-m-Y", strtotime('+' . $days . ' day', strtotime($fecha)));
  return $date;
}

function contar_dias($fecha_final)
{
  $fecha     = date('Y-m-j');
  $datetime1 = new DateTime($fecha);
  $datetime2 = new DateTime($fecha_final);
  $interval  = $datetime1->diff($datetime2);
  $data      = $interval->format('%R%a días');



  return $data;
}