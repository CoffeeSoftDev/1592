<?php
/*===================================================================
 *  cxr_view.php  —  Cuentas Recuperadas (CXR)  [cliente/UDN]
 *  Patrón: cxc_view.php   |   Modelo: _CXR.php
 *===================================================================*/
session_start();
include_once('../../../modelo/SQL_PHP/_CXR.php');

$obj   = new CXR;
$idUDN = $_SESSION['udn'];
$opc   = $_POST['opc'];

switch ($opc) {

  /*-------------------------------------------*/
  /* Case 1: Listado de CXR (saldo>0) + modal  */
  /*-------------------------------------------*/
  case 1:
    $tabla   = lista_cxr($obj, $idUDN);
    $modal   = modal_cxr($obj, $idUDN);
    $modalAb = modal_abono($obj);
    $encode  = array($tabla . $modal . $modalAb);
  break;

  /*-------------------------------------------*/
  /* Case 2: Subcategorías por categoría (cb)  */
  /*-------------------------------------------*/
  case 2:
    $idCat = $_POST['idCategoria'];
    $sub   = $obj->lsSubcategorias(array($idCat));
    $op    = '<option value="0">- Selecciona la cuenta -</option>';
    foreach ($sub as $s) {
      $op .= '<option value="'.$s[0].'">'.$s[1].'</option>';
    }
    $encode = array($op);
  break;

  /*-------------------------------------------*/
  /* Case 3: Guardar nueva CXR                 */
  /*-------------------------------------------*/
  case 3:
    $date          = $_POST['date'];
    $cliente       = trim($_POST['cliente']);
    $concepto      = trim($_POST['concepto']);
    $monto         = $_POST['monto'];
    $idSub         = $_POST['idSubcategoria'];
    $compromiso    = $_POST['compromiso'] != '' ? $_POST['compromiso'] : null;
    $encargado     = isset($_SESSION['user']) ? $_SESSION['user'] : '';

    $idFolio       = $obj->getFolioByFecha(array($date));
    $idFolioOrigen = $idFolio != 0 ? $idFolio : null;

    // [Cliente, Concepto, MontoInicial, Saldo, FechaCreacion, FechaCompromiso,
    //  id_UDN, id_Subcategoria, id_Folio_origen, encargado]
    $obj->addCXR(array(
      $cliente, $concepto, $monto, $monto, $date, $compromiso,
      $idUDN, $idSub, $idFolioOrigen, $encargado
    ));

    $encode = array('ok');
  break;

  /*-------------------------------------------*/
  /* Case 4: Datos del modal de abono          */
  /*-------------------------------------------*/
  case 4:
    $idCXR     = $_POST['idCXR'];
    $c         = $obj->getCXR(array($idCXR));
    $saldo     = isset($c[0]['Saldo']) ? $c[0]['Saldo'] : 0;
    $historial = tabla_historial($obj, $idCXR);
    // [saldo crudo (validación JS), historial HTML]
    $encode = array(number_format($saldo, 2, '.', ''), $historial);
  break;

  /*-------------------------------------------*/
  /* Case 5: Registrar abono                   */
  /*-------------------------------------------*/
  case 5:
    $idCXR = $_POST['idCXR'];
    $monto = $_POST['monto'];
    $idFP  = $_POST['formaPago'];
    $fecha = $_POST['fecha'];
    $obs   = trim($_POST['observacion']);

    $c     = $obj->getCXR(array($idCXR));
    $saldo = isset($c[0]['Saldo']) ? $c[0]['Saldo'] : 0;

    if ($monto <= 0) {
      $encode = array('err', 'El monto debe ser mayor a 0');
      break;
    }
    if ($monto > $saldo + 0.001) { // tolerancia por redondeo
      $encode = array('err', 'El abono ('.number_format($monto,2).') excede el saldo ('.number_format($saldo,2).')');
      break;
    }

    $idFolio    = $obj->getFolioByFecha(array($fecha));
    $idFolioVal = $idFolio != 0 ? $idFolio : null;

    // [MontoAbono, FechaAbono, Observacion, id_CXR, id_Folio, id_FormasPago]
    $obj->addAbono(array($monto, $fecha, $obs, $idCXR, $idFolioVal, $idFP));
    $obj->recalcularSaldo(array($idCXR));

    $encode = array('ok');
  break;

}

echo json_encode($encode);


/*===================================================================
 *  Vistas
 *===================================================================*/

function lista_cxr($obj, $idUDN) {
  $ls = $obj->lsCXR(array($idUDN));

  $tb  = '<br>';
  $tb .= '<div class="row form-group">';
  $tb .= '<div class="col-sm-8 col-xs-12"><h4><span class="icon-dollar"></span> CUENTAS RECUPERADAS</h4></div>';
  $tb .= '<div class="col-sm-4 col-xs-12 text-right"><a class="btn btn-primary btn-sm" onclick="nuevaCXR()"><i class="icon-plus"></i> Nueva cuenta</a></div>';
  $tb .= '</div>';

  $tb .= '<div class="table-">';
  $tb .= '<table id="viewCXR" class="table table-bordered table-xtra-condensed table-hover pd-10" style="width:100%; font-size:.8em; font-weight:700;">';
  $tb .= '<thead><tr>';
  foreach (array('Cuenta','Cliente / Concepto','Monto inicial','Abonado','Saldo','Compromiso','Estado','Acciones') as $th) {
    $tb .= '<th class="text-center">'.$th.'</th>';
  }
  $tb .= '</tr></thead><tbody>';

  $totalSaldo = 0;

  foreach ($ls as $r) {
    $totalSaldo += $r['Saldo'];

    $cuenta   = ($r['categoria'] ? $r['categoria'].' / ' : '').$r['Subcategoria'];
    $concepto = $r['Cliente'].($r['Concepto'] ? ' <span style="color:#9ca3af;">('.$r['Concepto'].')</span>' : '');
    $semf     = semaforo($r['dias_restantes'], $r['id_estado'], $r['FechaCompromiso']);

    $tb .= '<tr>';
    $tb .= '<td>'.$cuenta.'</td>';
    $tb .= '<td>'.$concepto.'</td>';
    $tb .= '<td class="text-right">'.evaluar($r['MontoInicial']).'</td>';
    $tb .= '<td class="text-right">'.evaluar($r['Abonado']).'</td>';
    $tb .= '<td class="text-right"><strong>'.evaluar($r['Saldo']).'</strong></td>';
    $tb .= '<td class="text-center">'.$semf.'</td>';
    $tb .= '<td class="text-center"><span style="font-size:11px; padding:2px 10px; border-radius:999px; '.estadoStyle($r['id_estado']).'">'.$r['Estado'].'</span></td>';

    $cliente_esc = htmlspecialchars($r['Cliente'], ENT_QUOTES);
    $saldo_esc   = htmlspecialchars(evaluar($r['Saldo']), ENT_QUOTES);
    $tb .= '<td class="text-center"><button class="btn btn-success btn-xs" onclick="abonarCXR('.$r['idCXR'].',\''.$cliente_esc.'\',\''.$saldo_esc.'\')"><i class="bx bx-dollar-circle"></i> Abonar</button></td>';
    $tb .= '</tr>';
  }

  if (empty($ls)) {
    $tb .= '<tr><td colspan="8" class="text-center" style="padding:20px; color:#9ca3af;">No hay cuentas recuperadas pendientes</td></tr>';
  }

  $tb .= '</tbody>';
  if (!empty($ls)) {
    $tb .= '<tfoot><tr class="bg-info"><td colspan="4" class="text-right"><strong>TOTAL SALDO PENDIENTE</strong></td><td class="text-right"><strong>'.evaluar($totalSaldo).'</strong></td><td colspan="3"></td></tr></tfoot>';
  }
  $tb .= '</table></div>';

  return $tb;
}

function modal_cxr($obj, $idUDN) {
  $cats = $obj->lsCategorias(array($idUDN));
  $opc_cat = '<option value="0">- Selecciona categoría -</option>';
  foreach ($cats as $c) {
    $opc_cat .= '<option value="'.$c[0].'">'.$c[1].'</option>';
  }

  $html = <<<HTML
  <div id="modalCXR" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:1050; overflow-y:auto;">
    <div style="width:620px; margin:8vh auto; background:#fff; border-radius:12px; border:1px solid #e5e7eb; box-shadow:0 25px 50px rgba(0,0,0,.15); overflow:hidden;">

      <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid #e5e7eb; background:#f9fafb;">
        <div>
          <strong style="font-size:13px; color:#1f2937;">Nueva Cuenta Recuperada</strong><br>
          <span style="font-size:11px; color:#9ca3af;">El saldo se arrastra hasta saldarse</span>
        </div>
        <button onclick="cerrarModalCXR()" style="width:30px; height:30px; border:none; background:transparent; cursor:pointer; font-size:18px; color:#9ca3af;"><i class="bx bx-x">x</i></button>
      </div>

      <div style="padding:16px 20px;">
        <div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Categoría *</label>
            <select id="cxrCategoria" class="form-control input-sm" onchange="cargarSubcatCXR()">{$opc_cat}</select>
          </div>
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Cuenta (Subcategoría) *</label>
            <select id="cxrSubcategoria" class="form-control input-sm"><option value="0">- Selecciona la cuenta -</option></select>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Cliente *</label>
            <input type="text" id="cxrCliente" class="form-control input-sm" placeholder="Nombre del cliente">
          </div>
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Concepto</label>
            <input type="text" id="cxrConcepto" class="form-control input-sm" placeholder="Opcional">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Monto inicial *</label>
            <div class="input-group input-group-sm">
              <span class="input-group-addon">\$</span>
              <input type="number" step="0.01" id="cxrMonto" class="form-control" placeholder="0.00">
            </div>
          </div>
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Fecha compromiso</label>
            <input type="date" id="cxrCompromiso" class="form-control input-sm">
          </div>
        </div>
        <div id="cxrMsg" class="text-center"></div>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:8px; padding:12px 20px; border-top:1px solid #e5e7eb; background:#f9fafb;">
        <button class="btn btn-default btn-sm" onclick="cerrarModalCXR()">Cancelar</button>
        <button class="btn btn-primary btn-sm" onclick="guardarCXR()"><i class="bx bx-check"></i> Guardar</button>
      </div>

    </div>
  </div>
HTML;

  return $html;
}

function modal_abono($obj) {
  $fp = $obj->lsFormasPago();
  $opc_fp = '';
  foreach ($fp as $f) {
    $opc_fp .= '<option value="'.$f[0].'">'.$f[1].'</option>';
  }
  $hoy = date('Y-m-d');

  $html = <<<HTML
  <div id="modalAbonoCXR" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:1060; overflow-y:auto;">
    <div style="width:640px; margin:8vh auto; background:#fff; border-radius:12px; border:1px solid #e5e7eb; box-shadow:0 25px 50px rgba(0,0,0,.15); overflow:hidden;">

      <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid #e5e7eb; background:#f9fafb;">
        <div>
          <strong style="font-size:13px; color:#1f2937;">Registrar Abono</strong><br>
          <span style="font-size:11px; color:#9ca3af;">Cliente: <span id="abCliente"></span></span>
        </div>
        <button onclick="cerrarModalAbonoCXR()" style="width:30px; height:30px; border:none; background:transparent; cursor:pointer; font-size:18px; color:#9ca3af;"><i class="bx bx-x">x</i></button>
      </div>

      <div style="padding:12px 20px; border-bottom:1px solid #e5e7eb; background:#f0f7ff; display:flex; gap:30px; align-items:center;">
        <div><span style="color:#9ca3af; font-size:11px;">Saldo actual</span><br><strong id="abSaldoTxt" style="color:#1f2937; font-size:16px;">-</strong></div>
      </div>

      <input type="hidden" id="abCXR">
      <input type="hidden" id="abSaldoNum" value="0">

      <div style="padding:16px 20px;">
        <div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Monto a abonar *</label>
            <div class="input-group input-group-sm">
              <span class="input-group-addon">\$</span>
              <input type="number" step="0.01" id="abMonto" class="form-control" placeholder="0.00">
            </div>
          </div>
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Forma de pago *</label>
            <select id="abFormaPago" class="form-control input-sm">{$opc_fp}</select>
          </div>
        </div>
        <div class="row">
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Fecha del abono *</label>
            <input type="date" id="abFecha" class="form-control input-sm" value="{$hoy}">
          </div>
          <div class="form-group col-sm-6 col-xs-12">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Observación</label>
            <input type="text" id="abObs" class="form-control input-sm" placeholder="Opcional">
          </div>
        </div>
        <div id="abMsg" class="text-center"></div>

        <div style="margin-top:8px;">
          <label style="font-size:11px; color:#6b7280; font-weight:600;">Historial de abonos</label>
          <div id="abHistorial"></div>
        </div>
      </div>

      <div style="display:flex; justify-content:flex-end; gap:8px; padding:12px 20px; border-top:1px solid #e5e7eb; background:#f9fafb;">
        <button class="btn btn-default btn-sm" onclick="cerrarModalAbonoCXR()">Cancelar</button>
        <button class="btn btn-success btn-sm" onclick="registrarAbono()"><i class="bx bx-check"></i> Registrar abono</button>
      </div>

    </div>
  </div>
HTML;

  return $html;
}

function tabla_historial($obj, $idCXR) {
  $ls = $obj->lsAbonos(array($idCXR));

  if (empty($ls)) {
    return '<div style="padding:10px; color:#9ca3af; font-size:12px;">Sin abonos registrados</div>';
  }

  $tb  = '<table class="table table-bordered table-xtra-condensed" style="font-size:.78em; font-weight:600; margin-top:4px;">';
  $tb .= '<thead><tr>';
  foreach (array('Fecha','Monto','Forma de pago','Observación') as $th) {
    $tb .= '<th class="text-center">'.$th.'</th>';
  }
  $tb .= '</tr></thead><tbody>';

  $total = 0;
  foreach ($ls as $a) {
    $total += $a['MontoAbono'];
    $tb .= '<tr>';
    $tb .= '<td class="text-center">'.date("d/m/Y", strtotime($a['FechaAbono'])).'</td>';
    $tb .= '<td class="text-right">'.evaluar($a['MontoAbono']).'</td>';
    $tb .= '<td class="text-center">'.($a['FormasPago'] ? $a['FormasPago'] : '-').'</td>';
    $tb .= '<td>'.($a['Observacion'] ? $a['Observacion'] : '-').'</td>';
    $tb .= '</tr>';
  }
  $tb .= '</tbody>';
  $tb .= '<tfoot><tr class="bg-info"><td class="text-right"><strong>TOTAL</strong></td><td class="text-right"><strong>'.evaluar($total).'</strong></td><td colspan="2"></td></tr></tfoot>';
  $tb .= '</table>';

  return $tb;
}


/*===================================================================
 *  Complementos
 *===================================================================*/

function semaforo($dias, $idEstado, $compromiso) {
  if ($compromiso == '' || $compromiso === null) {
    return '<span style="color:#9ca3af;">- sin fecha -</span>';
  }
  $fecha = date("d/m/Y", strtotime($compromiso));

  if ($idEstado == 2) { $color = '#059669'; $txt = $fecha; }              // saldado
  elseif ($dias < 0)  { $color = '#dc2626'; $txt = $fecha.' · vencido ('.abs($dias).'d)'; }
  elseif ($dias <= 3) { $color = '#d97706'; $txt = $fecha.' · '.$dias.'d'; }
  else                { $color = '#059669'; $txt = $fecha.' · '.$dias.'d'; }

  return '<span style="color:'.$color.'; font-weight:700;">'.$txt.'</span>';
}

function estadoStyle($idEstado) {
  switch ($idEstado) {
    case 2:  return 'background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;'; // Saldado
    case 3:  return 'background:#fef2f2; color:#dc2626; border:1px solid #fecaca;'; // Vencido
    default: return 'background:#fffbeb; color:#d97706; border:1px solid #fde68a;'; // Abierto
  }
}

function evaluar($val) {
  if ($val == 0 || $val == "") { return '-'; }
  return '$ '.number_format($val, 2, '.', ',');
}
?>
