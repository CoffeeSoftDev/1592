<?php
session_start();
include_once('../../../modelo/SQL_PHP/_CXC.php');

$obj = new CXC;

$idE = $_SESSION['udn'];
$opc = $_POST['opc'];

switch ($opc) {
  case 1:
    $title = array('Folio', 'Fecha','Descripcion', 'CxC', 'Opción','Fecha captura','Observación');
    $tb    = lista_ticket($obj,$title);
    $modal = modal_cxc($obj);
    $encode = array($tb . $modal);
  break;

  case 2:
  $ID_CXC       = $_POST['idBitacora'];
  $Formas_Pago  = $_POST['formas_pago'];

  $obj->Update_Producto(array($Formas_Pago,$ID_CXC ));
  /* JSON ENCODE  -----------*/
  $mnsj ='>'.$ID_CXC  .', '.$Formas_Pago;
  $encode = array($mnsj);
  break;

   case 3:
  $ID_CXC       = $_POST['idBitacora'];
  $fechaPago  = $_POST['fechaPago'] . ' ' . date('H:i:s');

  $obj->Update_PaymentDate(array($fechaPago,$ID_CXC ));
  /* JSON ENCODE  -----------*/
  $mnsj ='>'.$ID_CXC  .', '.$fechaPago;
  $encode = array($mnsj);
  break;
   case 4:
  $ID_CXC       = $_POST['idBitacora'];
  $observacion  = $_POST['observacion'];

  $obj->Update_Observation(array($observacion,$ID_CXC ));
  /* JSON ENCODE  -----------*/
  $mnsj ='>'.$ID_CXC  .', '.$observacion;
  $encode = array($mnsj);
  break;

  /*----------------------------------------------*/
  /* Case 5: Listar pendientes CxC para el modal  */
  /*----------------------------------------------*/
  case 5:
    $f1 = $_POST['date'];
    $fechaEntera = strtotime($f1);
    $anio = date("Y", $fechaEntera);
    $mes  = date("m", $fechaEntera);
    $dia  = date("d", $fechaEntera);
    $f_i  = $anio.'-'.$mes.'-01';
    $f_f  = $anio.'-'.$mes.'-'.$dia;

    $ListFolio = $obj->Ver_Folio(array($f_i, $f_f));

    $tb = '';
    $totalPendiente = 0;
    $cantRegistros  = 0;
    $totalCobrado   = 0;

    foreach ($ListFolio as $key) {
      $List_ticket = $obj->ver_bitacora_ventas(array($key[0]));
      foreach ($List_ticket as $_key) {
        $List_fp = $obj->bitacora_formas_pago(array($_key[0]));
        foreach ($List_fp as $__key) {
          if ($__key[3] != 0) {
            $folio_display = $key[1].' / '.$__key[0];
            $desc_display  = $_key[1].' ('.$__key[1].')';
            $monto_display = evaluar($__key[3]);

            // Determinar estado por payment_date
            $estado = 'Pendiente';
            $badgeStyle = 'background:#fffbeb; color:#d97706; border:1px solid #fde68a;';
            if (!empty($__key[5])) {
              $estado = 'Cobrado';
              $badgeStyle = 'background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;';
              $totalCobrado += $__key[3];
            } else {
              $totalPendiente += $__key[3];
              $cantRegistros++;
            }

            $monto_escaped = str_replace("'", "\\'", $monto_display);
            $desc_escaped  = str_replace("'", "\\'", $desc_display);
            $folio_escaped = str_replace("'", "\\'", $folio_display);

            $tb .= '<tr>';
            $tb .= '<td class="text-center"><input type="checkbox" class="chkCxc" value="'.$__key[0].'"></td>';
            $tb .= '<td class="text-center text-danger">'.$folio_display.'</td>';
            $tb .= '<td class="text-center">'.$key[2].'</td>';
            $tb .= '<td>'.$desc_display.'</td>';
            $tb .= '<td class="text-right">'.$monto_display.'</td>';
            $tb .= '<td class="text-center"><span style="font-size:11px; padding:2px 10px; border-radius:999px; '.$badgeStyle.'">'.$estado.'</span></td>';
            if ($estado == 'Pendiente') {
              $tb .= '<td class="text-center"><button class="btn btn-primary btn-xs" onclick="seleccionarCxC('.$__key[0].',\''.$folio_escaped.'\',\''.$desc_escaped.'\',\''.$monto_escaped.'\')"><i class="bx bx-dollar-circle"></i> Cobrar</button></td>';
            } else {
              $tb .= '<td class="text-center"><span style="font-size:11px; color:#9ca3af;">-</span></td>';
            }
            $tb .= '</tr>';
          }
        }
      }
    }

    if ($tb == '') {
      $tb = '<tr><td colspan="7" class="text-center" style="padding:20px; color:#9ca3af;">No hay registros CxC en este periodo</td></tr>';
    }

    $encode = array($tb, evaluar($totalPendiente), $cantRegistros, evaluar($totalCobrado));
  break;

  /*----------------------------------------------*/
  /* Case 6: Historial de cobros CxC              */
  /*----------------------------------------------*/
  case 6:
    $f1 = $_POST['date'];
    $fechaEntera = strtotime($f1);
    $anio = date("Y", $fechaEntera);
    $mes  = date("m", $fechaEntera);
    $f_i  = $anio.'-'.$mes.'-01';
    $f_f  = $anio.'-'.$mes.'-'.date("d", $fechaEntera);

    $ListFolio = $obj->Ver_Folio(array($f_i, $f_f));
    $tb = '';

    foreach ($ListFolio as $key) {
      $List_ticket = $obj->ver_bitacora_ventas(array($key[0]));
      foreach ($List_ticket as $_key) {
        $List_fp = $obj->bitacora_formas_pago_full(array($_key[0]));
        foreach ($List_fp as $__key) {
          if ($__key[3] != 0 && !empty($__key[6])) {
            $tb .= '<tr>';
            $tb .= '<td class="text-center text-danger">'.$key[1].' / '.$__key[0].'</td>';
            $tb .= '<td>'.$_key[1].' ('.$__key[5].')</td>';
            $tb .= '<td class="text-right">'.evaluar($__key[3]).'</td>';
            $tb .= '<td class="text-center"><span style="font-size:11px; padding:2px 10px; border-radius:999px; background:#dbeafe; color:#2563eb; border:1px solid #93c5fd;">'.$__key[5].'</span></td>';
            $tb .= '<td class="text-center">'.date("d/m/Y", strtotime(str_replace('/', '-', $__key[6]))).'</td>';
            $tb .= '<td>'.($__key[7] ?? '-').'</td>';
            $tb .= '</tr>';
          }
        }
      }
    }

    if ($tb == '') {
      $tb = '<tr><td colspan="6" class="text-center" style="padding:20px; color:#9ca3af;">No hay cobros registrados este mes</td></tr>';
    }

    $encode = array($tb);
  break;

  /*----------------------------------------------*/
  /* Case 7: Registrar cobro CxC                  */
  /*----------------------------------------------*/
  case 7:
    $idFP        = $_POST['idFP'];
    $formaPago   = $_POST['formaPago'];
    $fechaCobro  = $_POST['fechaCobro'] . ' ' . date('H:i:s');
    $observacion = isset($_POST['observacion']) ? $_POST['observacion'] : '';

    // Actualizar tipo de pago y fecha en bitacora_formaspago
    $obj->Update_Producto(array($formaPago, $idFP));
    $obj->Update_PaymentDate(array($fechaCobro, $idFP));
    if ($observacion != '') {
      $obj->Update_Observation(array($observacion, $idFP));
    }

    $encode = array('ok');
  break;


}

/* JSON  ENCODE */
echo json_encode($encode);


/* ---------------------------------- */
/* Historial de ticket                */
/* ---------------------------------- */

function lista_ticket($obj,$th){
  $f1    = $_POST['date'];

  $fechaEntera = strtotime($f1);
  $anio        = date("Y", $fechaEntera);
  $mes         = date("m", $fechaEntera);
  $dia         = date("d", $fechaEntera);

  $f_i = $anio.'-'.$mes.'-01';
  $f_f = $anio.'-'.$mes.'-'.$dia;

  $ListFolio = $obj->Ver_Folio(array($f_i,$f_f));


  $tb  =  '<br> Folio: | '.$f_i.'  >> '.$f_f;

  $tb .= '<div style="margin-top:0;" class="table-">';
  $tb .= '<table id="viewFolios" class="table table-bordered  table-xtra-condensed table-hover pd-10"  style="width:100%; font-size:.8em; font-weight:700;">';

  /*----------THEAD------------*/
  $tb .= '<thead><tr>';

  for ($i = 0; $i < count($th); $i++) {
    $tb .= '<th class="text-center">' . $th[$i] . ' </th>';
  }

  $tb .= '<th class="text-center">Acciones</th>';
  $tb .= '</tr></thead>';

  /*----------TBODY------------*/
  $tb .= '<tbody>';

  foreach ($ListFolio as $key) {
    #Recorrido por folios

      #Recorrido por movimientos
      $List_ticket = $obj->ver_bitacora_ventas(array($key[0]));
      foreach ($List_ticket as $_key) {

        # -----
        $List_formas_pago = $obj-> bitacora_formas_pago(array($_key[0]));
      //
        foreach ($List_formas_pago as $__key) {

          if($__key[3] !=0){
            $tb .= '<tr >';
            $tb .= '<td class="text-center text-danger">' . $key[1] . '  / '.$__key[0].' </td>';
            $tb .= '<td class="text-center">' . $key[2] . '</td>';
            $tb .= '<td >' . $_key[1] . '   ('.$__key[1].')</td>';
            $tb .= '<td class="text-right">' .evaluar($__key[3]). '</td>';

          # Formas de pago
            $tb .= '
            <td class="text-center col-sm-2">
              <select id="SelectFP'.$__key[0].'" class="form-control input-xs" onchange="actualizarPago('.$__key[0].')" title="'.$__key[4].'">
            ';




            $cb = $obj-> select_tipoPago();

            foreach ($cb as $val ) {

              if($__key[4] == $val[0]){
                $tb .='<option value="'.$val[0].'" selected>'.$val[1].'</option>';
              }else{
                  $tb .='<option value="'.$val[0].'">'.$val[1].'</option>';
              }

            }

         // Validar si la fecha existe
            $fechaSQL = '';
            if (!empty($__key[5])) {
                $fechaSQL = date("Y-m-d", strtotime(str_replace('/', '-', $__key[5])));
            }


            $tb .= '
                <td class="text-center col-sm-2">
                <input type="date" id="fechaPago'.$__key[0].'" class="form-control input-xs" value="'.$fechaSQL.'" onchange="actualizarFecha('.$__key[0].')" title="Fecha de pago">
             
                </td>

                <td class="text-center col-sm-2">
                <input type="text" id="observacion'.$__key[0].'" class="form-control" value="'.$__key[6].'" onchange="actualizarDatos('.$__key[0].')" title="Observación">
                 </td>
            ';

            // Boton Cobrar por fila
            $folio_esc = htmlspecialchars($key[1].' / '.$__key[0], ENT_QUOTES);
            $desc_esc  = htmlspecialchars($_key[1].' ('.$__key[1].')', ENT_QUOTES);
            $monto_esc = htmlspecialchars(evaluar($__key[3]), ENT_QUOTES);

            $tb .= '<td class="text-center">';
            $tb .= '<button class="btn btn-primary btn-xs" onclick="abrirCobro('.$__key[0].',\''.$folio_esc.'\',\''.$desc_esc.'\',\''.$monto_esc.'\')">';
            $tb .= '<i class="bx bx-dollar-circle"></i> Cobrar</button>';
            $tb .= '</td>';

            $tb .= '</tr>';

            }
        }//END LIST FORMAS DE PAGO
      }

  }// END FOLIOS

  $tb .= '</tbody>';
  $tb .= '</table></div>';

  return $tb;
}


function modal_cxc($obj) {
  $cb_tipoPago = $obj->select_tipoPago();
  $opciones = '';
  foreach ($cb_tipoPago as $tp) {
    $opciones .= '<option value="'.$tp[0].'">'.$tp[1].'</option>';
  }
  $hoy = date('Y-m-d');

  $html = <<<HTML
  <div id="modalCxC" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.35); z-index:1050; overflow-y:auto; backdrop-filter:blur(4px);">
    <div style="width:620px; margin:10vh auto; background:#fff; border-radius:12px; border:1px solid #e5e7eb; box-shadow:0 25px 50px rgba(0,0,0,.15); overflow:hidden;">

      <!-- HEADER -->
      <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 20px; border-bottom:1px solid #e5e7eb; background:#f9fafb;">
        <div style="display:flex; align-items:center; gap:10px;">
          <div style="width:34px; height:34px; border-radius:8px; background:#dbeafe; display:flex; align-items:center; justify-content:center;">
            <i class="bx bx-receipt" style="color:#2563eb; font-size:18px;"></i>
          </div>
          <div>
            <strong style="font-size:13px; color:#1f2937;">Registrar Cobro</strong><br>
            <span style="font-size:11px; color:#9ca3af;">Cuentas por Cobrar</span>
          </div>
        </div>
        <button onclick="cerrarModalCxC()" style="width:30px; height:30px; border:none; background:transparent; cursor:pointer; border-radius:6px; color:#9ca3af; font-size:18px;">
          <i class="bx bx-x"></i>
        </button>
      </div>

      <!-- DETALLE CXC SELECCIONADO -->
      <div id="detalleCxcSeleccionado" style="padding:12px 20px; border-bottom:1px solid #e5e7eb; background:#f0f7ff;">
      </div>

      <!-- FORMULARIO -->
      <div style="padding:16px 20px;">
        <div class="row">
          <div class="col-sm-6">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Monto a Cobrar *</label>
            <div class="input-group input-group-sm" style="margin-top:4px;">
              <span class="input-group-addon">\$</span>
              <input type="number" step="0.01" id="txtMontoCobro" class="form-control" placeholder="0.00">
            </div>
          </div>
          <div class="col-sm-6">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Forma de Pago *</label>
            <select id="selectFormaPagoCobro" class="form-control input-sm" style="margin-top:4px;">{$opciones}</select>
          </div>
        </div>
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-6">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Fecha de Cobro *</label>
            <input type="date" id="txtFechaCobro" class="form-control input-sm" value="{$hoy}" style="margin-top:4px;">
          </div>
          <div class="col-sm-6">
            <label style="font-size:11px; color:#6b7280; font-weight:600;">Observacion</label>
            <input type="text" id="txtObservacionCobro" class="form-control input-sm" placeholder="Nota opcional..." style="margin-top:4px;">
          </div>
        </div>
      </div>

      <!-- FOOTER / BOTONES -->
      <div style="display:flex; justify-content:flex-end; gap:8px; padding:12px 20px; border-top:1px solid #e5e7eb; background:#f9fafb;">
        <button class="btn btn-default btn-sm" onclick="cerrarModalCxC()">Cancelar</button>
        <button class="btn btn-primary btn-sm" onclick="registrarCobroCxC()">
          <i class="bx bx-check"></i> Registrar Cobro
        </button>
      </div>

    </div>
  </div>
HTML;

  return $html;
}

/*-----------------------------------*/
/*	 Complementos **
/*-----------------------------------*/

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }

 return $res;
}

 ?>
