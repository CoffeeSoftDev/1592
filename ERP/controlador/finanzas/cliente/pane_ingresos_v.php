<?php
session_start();

include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
include_once('../../../modelo/SQL_PHP/_INGRESOS_TURISMO.php');
include_once('../../../modelo/SQL_PHP/_Utileria.php');

$fin  = new Finanzas;
$tur  = new TURISMO;
$util = new Util;


$opc   = $_POST['opc'];
$idE   = $_SESSION['udn'];
$array = array($idE);



switch ($opc) {
 /*-----------------------------------*/
 /*	 Categorias tabPane
 /*-----------------------------------*/

 case 0:

 $sql  = $fin->Select_Categoria($array);  ?>

 <div class="row">
  <div class=" col-sm-12 col-xs-12">
   <!-- Categorias  -->
   <ul class="nav nav-tabs">

    <?php

    foreach ($sql as $key => $value) {
     $active    = '';
     if ( $key == 0){   $active = 'active';   }


     ?>

     <li class="<?php echo $active; ?>">
      <a class="text-warning" data-toggle="tab" href="#tab"
      onClick="Subcategoria(<?php echo $value[0]; ?>);"> <strong><?php echo $value[1]; ?></strong>
     </a>
    </li>

   <?php } // End Foreach ?>


   <li class="'.$active.'">
    <a class="text-warning" data-toggle="tab" href="#tab" onClick="tc_view()"> <strong>T.C.</strong></a>
   </li>

   <li class="'.$active.'">
    <a class="text-warning" data-toggle="tab" href="#tab" onClick="cxc_view()"> <strong>CxC</strong></a>
   </li>


   <li class="'.$active.'">
    <a class="text-warning" data-toggle="tab" href="#tab" onClick="GRAL()"> <strong>REPORTE GRAL</strong></a>
   </li>

  </ul> <!-- End Categorias-->


  <div id="btnGral" class="col-sm-12"></div>
  <div class="tab_content_subcategoria" id="addCont"></div>

 </div>
</div>
<script>
// Subcategoria(1);
</script>

<script src="recursos/js/finanzas/cliente/ingresos.js?t=<?=time()?>"></script>
<?php
break;


/*----------------------------------------------*/
/*	IMPRESION DE SUBCATEGORIAS Y CONSULTA DE TABLA
/*-----------------------------------------------*/

case 1:
$disabled = '';
$date     = $_POST['date'];
$id       = $_POST['id'];
$now      = $fin->NOW();
$ayer     = $fin->Ayer();

// if ( $date != $now && $date != $ayer) {
//  $disabled = 'onClick="label_input($value[0]);"';
// }

  // Valor por defecto: sin onclick
    $disabled = '';

    if ($date == $now || $date == $ayer) {
        $disabled = 'onClick="label_input(' . $id . ');"';
    }

// echo $now.'function '. $disabled ;



$sub   = $fin->Select_group(array($id));
echo '
<br>
<div class="row form-group">
<div class="col-sm-6 col-xs-6" id="res_ingresos"></div>
<div class="col-sm-6 col-xs-6 text-right"><a class="btn btn-primary btn-xs hide" onclick="agregarSubcategoria('.$id.')"><i class="icon-plus"></i>Nueva subcategoria</a></div>
</div>

<div class="col-sm-12 col-xs-12 text-center" id="Group_Res"></div>';

foreach ($sub as $key ) {
 echo tabla_ingresos($fin,$key[0],$key[1]);
}

break;
/*----------------------------------------------*/
/*	DIRECCION - CONSULTA
/*-----------------------------------------------*/
case 2:
$date     = $_POST['date'];
$id       = $_POST['id'];
echo '<br>';
echo tabla_ingresos_admin($tur,$id);
break;

case 3:
$arreglo = array(1);
$sql     = $fin->Select_Categoria($arreglo);  


?>

<div class="row">
 <div class=" col-sm-12 col-xs-12">
  <!-- Categorias  -->
  <ul class="nav nav-tabs">
   <li class="">
    <a class="text-warning" data-toggle="tab" href="#tab" onClick="GRAL()"> <strong>REPORTE GRAL</strong></a>
   </li>

   <li class="">
    <a class="text-warning" data-toggle="tab" href="#tab" onClick="archivos_hotel(1)"> <strong> ARCHIVOS </strong></a>
   </li>
   <?php

   foreach ($sql as $key => $value) {
    $active    = '';
    // if ( $key == 0){   $active = 'active';   }

    ?>

    <li class="<?php echo $active; ?>">
     <a class="text-warning" data-toggle="tab" href="#tab"
     onClick="Subcategoria(<?php echo $value[0]; ?>);"> <strong><?php echo $value[1]; ?></strong>
    </a>
   </li>

  <?php } // End Foreach ?>


  <li class="'.$active.'">
   <a class="text-warning" data-toggle="tab" href="#tab" onClick="ver_tc()"> <strong>T.C.</strong></a>
  </li>

 </ul> <!-- End Categorias-->


 <div id="btnGral" class="col-sm-12"></div>
 <div class="tab_content_subcategoria" id="addCont"></div>

</div>
</div>
<script>
// Subcategoria(1);
</script>

<?php
break;

case 5://DATE NOW
$date = $fin->NOW();
echo $date;
break;

case 6: // formulario para agregar nueva subcategoria

$id  = $_POST['id'];
$frm = '';
$cb  = $fin ->Select_group(array($id));

$frm .= '
<form>

<div class="form-group">
<label>Subcategoria</label>
<input onInput="Existe('.$id.')"  type="text" class="form-control" id="txtSub"
placeholder="" ><b><span class="" id="txtExiste"></span></b>
</div>

<div class="form-group">
<label>Grupo</label>
<select class="form-control" id="txtGrupo">';

foreach ($cb as $key ) {
 $frm .='<option value="'.$key[0].'">'.$key[1].'</option>';
}


$frm .='
</select>
</div>
<div class="form-group">
<a style="width:100%" id="btnAgregar" class="btn btn-success btn-sm" onclick="insertar_sub('.$id.')">Guardar</a>
</div>

</form>
';
$encode = array(0=>$frm);
echo json_encode($encode);

break;

}

/*-----------------------------------*/
/*		 FUNCIONES
/*-----------------------------------*/

function tabla_ingresos($fin,$grupo,$nombreGrupo){
 $tb = '';
 $disabled = '';
 $date     = $_POST['date'];
 $id       = $_POST['id'];
 $now      = $fin->NOW();
 $ayer     = $fin->Ayer();

 // ------------
 $Sub       = 0;
 $pax       = 0;
 $tarifa    = 0;
 $N_M       = 0;
 $IVA       = 0;
 $IVA2      = 0;
 $TotalGral = 0;
 $DIF       = 0;
 $t2        = 0;
 $txt       = '';
 // -------------
 $Efectivo  = 0;
 $TC        = 0;
 $CxC       = 0;
 $Anticipo  = 0;
 $CargosHab = 0;
 $Cargos2   = 0;
 // -------------
 $id_g      = 1;

 if ( $date != $now && $date != $ayer) {
  $disabled = 'onClick="label_input($value[0]);"';
 }

 $sub   = $fin->Select_Subcategoria_x_grupo($id,$grupo);

 $imp   = $fin->Select_Impuestos($id);


 if ($id == 13) { // CARGOS DE HABITACION
  $id_g  = 2;
 }


 /*--------------Folio actual---------------*/
 $__getFolio = $fin-> ConsultarFolioActual(array($date));

 $tb .= '<div class="col-sm-3">';
 $tb .= '<label>Folio: </label> <span class="text-primary">'.$__getFolio.'</span>';
 $tb .= '</div>';
 //

 /*--------------TABLE HEAD ---------------*/
 $tb .= '
 <div class="">
 <table id="size1" style="font-size:.75em; font-weight:700;" class="table  table-bordered table-hover table-stripped">
 <thead>';

 if ($grupo!=1) {
  $tb .='<tr><th colspan="11"><span style="font-size:1em;">'.$nombreGrupo.'</span></th></tr>';
 }

 $tb .='<tr  class="bg-primary">
 <th  class="text-center col-sm-2" title="'.$id.'">Descripción</th>';



 switch ($id) {
  case 1:
    $tb .='<th class="text-center">Cliente </th>';
  break;

  case 3:
  case 4:
  case 5:
  case 8:
  $tb .='<th class="text-center">Tarifa </th>';
  break;

 }


 $tb .= td_tarifas($id);

 $fpago = $fin->Select_formaspago_by_categoria(array($id_g));


 if ($id != 12) { // Empleados & cortesia
  foreach ($fpago as $key => $value) { // encabezado formar de pago
   $tb .= '
   <th class="text-center col-sm-1 col-xs-1 ">
   <span class="icon-pencil">'.$value[1].' </th>';
  }

  $tb .= '<th class="text-center col-sm-1 col-xs-1">Subtotal</th>';
 }


 foreach ($imp as $key => $value) { //Formas de Pago
  $tb .= ' <th class="text-center col-sm-1 col-xs-1"> '.$value[1].'</th>';
 }

 if ( $id != 12 ) { // Empleados & cortesia
  $tb .='
  <th class="text-center col-sm-1 col-xs-1"> Total</th>
  <th class="text-center col-sm-1 col-xs-1"> Diferencia</th>';
 }
 $tb .='</tr></thead><tbody>';

 /*-------------- TABLE BODY --------------*/

 foreach ($sub as $key => $value) { // Vista de subcategorias.
  $ocupacion =  $fin ->  ConsultarOcupacion(array($__getFolio,$value[0]));

  $tb .= '<tr><td class="pointer" title="'.$ocupacion.'">'.$value[1].'</td>';

  switch ($id) {
   case 1:
    $active_btn = 'disabled';
    if($ocupacion == 0){  $active_btn = 'disabled';  }

    $tb .='<td class="text-center"><button class="btn btn-default btn-sm '.$active_btn.'" onclick="NuevoHuesped('.$ocupacion.')" ><i class="icon-user-add"></i></button></td>';
   break;
   case 3:
   case 4:
   case 5:
   case 8:
    $tb .= '<td class="pointer text-right" id="precio'.$id.'-'.$value[0].'"><span class="pointer" style="width:100%; height:100%;" onClick="sub_tarifa('.$id.','.$value[0].','.$value[3].');">'.evaluar($value[3]).'</span></td>';
   break;

  }




  /*-----------------------------------*/
  /*	Detalles de Tarifas
  /*-----------------------------------*/
  $monto    = $fin->Select_MontoSubtotal($date,$value[0]);
  $paxID    = $fin->ExisteEnBitacora($date,$value[0],'pax');
  $noID     = $fin->ExisteEnBitacora($date,$value[0],'Noche');
  $tar      = $fin->ExisteEnBitacora($date,$value[0],'Tarifa');
  $tarifa  += $tar;

  // '.input_td('pax',$value[0],$paxID).'

  switch ($id) {
   case 1:
   case 2:
   case 11:
   $tb .='
   <td class="pointer text-right bg-info" id="pax'.$value[0].'">
   <label id="lbl-frm" style="width:100%; height:100%;" onclick="tdEdit(\'pax\','.$value[0].','.$paxID.')">'.evaluar2($paxID).'</label>
   </td>';

   $tb .='
   <td class="pointer text-right bg-info" id="Noche'.$value[0].'">
   <label id="lbl-frm" style="width:100%; height:100%;" onclick="tdEdit(\'Noche\','.$value[0].','.$noID.')">'.evaluar2($noID).'</label>
   </td>

   <td class="pointer text-right bg-info" id="Tarifa'.$value[0].'">
   <label id="lbl-frm" style="width:100%; height:100%;" onclick="tdEdit(\'Tarifa\','.$value[0].',\''.$tar.'\')">'.evaluar2($tar).'</label>
   </td>';

   // '.input_td($value[0],'Tarifa',$tar).'

   $N_M   = $N_M + $noID;


   break;

   case 12:
   // $tb .= '<td class="pointer bg-default"></td>';
   $tar      = $fin->ExisteEnBitacora($date,$value[0],'Subtotal');
   $tarifa  += $tar;
   $tb .= '
   <td class="pointer text-right bg-info" id="SubTotal'.$value[0].'">
   <label id="lbl-frm" style="width:100%; height:100%;" onclick="tdEdit(\'SubTotal\','.$value[0].',\''.$tar.'\')">'.evaluar2($tar).'</label>
   </td>';
   break;

   case 9:
   $tb .= '
   <td class="pointer text-right bg-info" id="Tarifa'.$value[0].'">
   <label id="lbl-frm" style="width:100%; height:100%;" onclick="tdEdit(\'Tarifa\','.$value[0].',\''.$tar.'\')">'.evaluar2($tar).'</label>
   </td>';
   break;


   default:
   $tb .='
   <td class="pointer text-right bg-info" id="pax'.$value[0].'">
   <label id="lbl-frm" style="width:100%; height:100%;" onclick="tdEdit(\'pax\','.$value[0].','.$paxID.')">'.evaluar2($paxID).'</label>
   </td>';
   $tb .= '
   <td class="pointer text-right bg-info" id="Tarifa'.$value[0].'">
   <label id="lbl-frm" style="width:100%; height:100%;" onclick="tdEdit(\'Tarifa\','.$value[0].',\''.$tar.'\')">'.evaluar2($tar).'</label>
   </td>';
   break;


  }

  /*-----------------------------------*/
  /* Formas de Pago
  /*-----------------------------------*/

  if ( $id != 12) {
   foreach ($fpago as $key => $valfpago) {
    $montoFP = $fin->Select_MontoFPago($date,$value[0],$valfpago[0]);

    switch ($key) {

     case 0:  $Efectivo  += $montoFP; break;
     case 1:  $TC        += $montoFP; break;
     case 2:  $CxC       += $montoFP; break;
     case 3:  $Anticipo  += $montoFP; break;
     case 4:  $CargosHab += $montoFP; break;
     case 5:  $Cargos2   += $montoFP; break;

    }

    // $tb .='<td title="'.$valfpago[1].'" class="pointer text-right bg-warning text-primary-1" id="fp'.$value[0].'-'.$valfpago[0].'">

    // <label class="pointer" style="width:100%; height:100%;" onClick="label_input_formaspago('.$value[0].','.$valfpago[0].','.$id.');">'.evaluar($montoFP).'</label>
    // </td>';
        $dia = date('d', strtotime($date));
        // Validación de fecha
        $manana = date('Y-m-d', strtotime('+1 day', strtotime($now)));
        // $permitido = ($date === $now || $date === $ayer || $date === $manana);
      
        $permitido = ($date === $now || $date === $ayer || $date === $manana || $dia == '1' || $dia == '5');

    // $permitido = ($date === $now || $date === $ayer);

    // Armo el atributo condicionalmente
    $onClickAttr = $permitido 
        ? 'onClick="label_input_formaspago(' . (int)$value[0] . ',' . (int)$valfpago[0] . ',' . (int)$id . ');"'
        : '';

    // Agrego también un title extra para indicar que está bloqueado si no se puede
    $title = htmlspecialchars($valfpago[1], ENT_QUOTES, 'UTF-8');
    if (!$permitido) {
        $title .= ' · Solo modificable para mañana, hoy o ayer';
    }

    $tb .= '<td title="'.$title.'" class="text-right bg-warning text-primary-1" id="fp'.$value[0].'-'.$valfpago[0].'">';
    $tb .= '  <label class="pointer" style="width:100%; height:100%;" '.$onClickAttr.'>'.evaluar($montoFP).'</label>';
    $tb .= '</td>';




   }
  }

  /*-----------------------------------*/
  /*	SubTotal
  /*-----------------------------------*/
  if ( $id != 12 ) {

   $tb .=' <td id="sub'.$id.'-'.$value[0].'" class="text-right">
   <p class="not-allowed" style="width:100%; height:100%;" > '.evaluar($monto).' </p>
   </td>';

   $t2 += $monto;
  }

  foreach ($imp as $key => $valimp) {
   $monto = $fin->Select_MontoImpuesto($date,$value[0],$valimp[0]);

   switch ($key) {
    case 0:  $IVA  += $monto;   break;
    case 1:  $IVA2 += $monto;   break;
   }

   $tb .='<td id="Imp'.$value[0].'-'.$valimp[0].'" class="not-allowed text-right">
   <label><span class="not-allowed text-danger">'.evaluar($monto).'</span>   </label>
   </td>';
  }


  if ( $id != 12 ) {

   $total2     = $fin->Select_Total2($date,$value[0]);
   $TotalGral  = $TotalGral+$total2;
   $diferencia = $tar-$total2;


   $tb .=
   '<td class="not-allowed text-right" id="total2'.$value[0].'">
   <label class="not-allowed">'.evaluar($total2).'</label>
   </td>
   <td class="not-allowed bg-danger text-right" id="diferencia'.$value[0].'">
   <label class="not-allowed">'.evaluar($diferencia).'</label>
   </td>';
  }
  $tb .= '</tr>';

 }

 /*-----------------------------------*/
 /* TOTALES
 /*-----------------------------------*/

 $tb .='</tbody>

 <tfoot>
 <tr class="text-right">
 <td  class="text-left bg-info-1" >TOTAL</td>';

 switch ($id) {
  case 1:
  case 3:
  case 4:
  case 5:
  case 8:
  $tb .='<td class="bg-info-1"></td>';
  break;

 }

 switch ($id) {
  case 1:
  case 2:
  case 11:
  $tb .= '<td class="bg-info-1"></td>';
  $tb .= '
  <td class="bg-info-1">'.$N_M.'</td>
  <td class="bg-info-1">'.evaluar($tarifa).'</td>
  ';
  break;

  case 9:
  $tb .= '
  <td class="bg-info-1">'.evaluar($tarifa).'</td>';
  break;

  case 12: // empleados & Cortesias
  $tb .= '
  <td class="bg-info-1">'.evaluar($tarifa).'</td>';
  break;

  default:
  $tb .= '<td class="bg-info-1"></td>';
  $tb .= '
  <td class="bg-info-1">'.evaluar($tarifa).'</td>';
  break;

 }

 if ($id != 12) {

  for ($i=1; $i <=  count($fpago); $i++) {
   switch ($i) {
    case 1: $tb .='<td class="bg-info-1">'.evaluar($Efectivo).'</td>'; break;
    case 2: $tb .='<td class="bg-info-1">'.evaluar($TC).'</td>'; break;
    case 3: $tb .='<td class="bg-info-1">'.evaluar($CxC).'</td>'; break;
    case 4: $tb .='<td class="bg-info-1">'.evaluar($Anticipo).'</td>'; break;
    case 5: $tb .='<td class="bg-info-1">'.evaluar($CargosHab).'</td>'; break;
    case 6: $tb .='<td class="bg-info-1">'.evaluar($Cargos2).'</td>'; break;
   }

  }

  $tb .='<td class="bg-info-1">'.evaluar($t2).'</td>';


  foreach ($imp as $key => $valimp) {
   switch ($key) {
    case 0:  $tb .='<td class="bg-info-1">'.evaluar($IVA).'</td>';  break;
    case 1:  $tb .='<td class="bg-info-1">'.evaluar($IVA2).'</td>'; break;

   }
  }

  $tb .='
  <td class="bg-info-1">'.evaluar($TotalGral).'</td>
  <td class="bg-info-1">'.evaluar($DIF).'</td>
  ';

 }// Empleados & Cortesias




 $tb .='</tr>
 </tfoot>
 </table>
 </div>
 ';

 return $tb;
}

function tabla_ingresos_admin($fin,$id){

 $tb        = '';
 $disabled  = '';
 $date_i    = $_POST['date0'];
 $date      = $_POST['date'];
 // -------------
 $now      = $fin->NOW();
 $ayer     = $fin->Ayer();
 $sub      = $fin->Select_group(array($id));
 // -------------
 $total_efectivo  = 0;
 $total_cxc       = 0;
 $total_anticipos = 0;
 $total_tarjet    = 0;
 $gran_total_pax  = 0;

 // Archivos adjuntos
 $files       = $fin-> archivo_adjunto(array($id,$date_i,$date));

 $btn_adjunto = ' sin archivos ('.count($files).')';
 //
 if (count($files)==1) {
  foreach ($files as $_key) {
if ($_key[8]=='') {
   $btn_adjunto = '
   <a class="btn btn-xs btn-default" href="'.$_key[0].''.$_key[1].'" target="_blank"><i class="bx bx-paperclip"></i> Archivo adjunto ('.count($files).')  '.$date_i.'</a>';

}else {
 $btn_adjunto = '<a class="btn btn-default pull-right " onclick="ver_archivos(\''.$date_i.'\',\''.$date.'\','.$id.')">
 <i class="bx bx-paperclip"></i> Archivos adjuntos ('.count($files).')
 </a>';
}


 }


 }else if(count($files)>1){
  $btn_adjunto = '<a class="btn btn-default pull-right " onclick="ver_archivos(\''.$date_i.'\',\''.$date.'\','.$id.')">
  <i class="bx bx-paperclip"></i> Archivos adjuntos ('.count($files).')
  </a>';
 }

 $tb .='
 <div class="line form-group row ">

 <div class="col-xs-6 col-sm-6 ">
 </div>


 <div class="col-xs-6 col-sm-6 text-right">
 '.$btn_adjunto.'
 </div>

 </div>

 ';


 foreach ($sub as $key) { // Grupos de ingresos

  $total_pax = 0;
  // ------------
  $Sub       = 0;
  $pax       = 0;
  $tarifa    = 0;
  $N_M       = 0;
  $IVA       = 0;
  $IVA2      = 0;
  $TotalGral = 0;
  $DIF       = 0;
  $t2        = 0;
  $txt       = '';
  // -------------
  $Efectivo  = 0;
  $TC        = 0;
  $CxC       = 0;
  $Anticipo  = 0;
  $CargosHab = 0;
  $Cargos2   = 0;

  // -------------
  $id_g      = 1;



  if ( $date != $now && $date != $ayer) {
   $disabled = 'onClick="label_input($value[0]);"';
  }

  $sub   = $fin->Select_Subcategoria_x_grupo($id,$key[0]);

  $imp   = $fin->Select_Impuestos($id);


  if ($id == 13) { // CARGOS DE HABITACION
   $id_g  = 2;
  }



  /*--------------TABLE HEAD ---------------*/
  $tb .= '

  <div class="">




  <table id="size1"  class="table  table-bordered table-hover table-stripped" style="width:100%; font-size:.9em">
  <thead>';

  if ($key[0]!=1) {
   $tb .='<tr ><th colspan="11">'.$key[1].'</th></tr>';
  }

  $tb .='<tr class="bg-primary">
  <th class="text-center">SubCategoria</th>';
  $tb .= td_tarifas($id);

  $fpago = $fin->Select_formaspago_by_categoria(array($id_g));

  if ($id != 12) { // Empleados & cortesia
   foreach ($fpago as $key => $value) { // encabezado formar de pago
    $tb .= '<th class="text-center col-sm-1 col-xs-1 ">'.$value[1].'</th>';
   }

   $tb .= '<th class="text-center col-sm-1 col-xs-1">Subtotal</th>';
  }

  foreach ($imp as $key => $value) { //Formas de Pago
   $tb .= ' <th class="text-center col-sm-1 col-xs-1"> '.$value[1].'</th>';
  }

  if ( $id != 12 ) { // Empleados & cortesia
   $tb .='<th class="text-center col-sm-1 col-xs-1">Total</th>';
  }

  $tb .='</tr></thead><tbody>';



  /*-------------- TABLE BODY --------------*/

  foreach ($sub as $key => $value) { // Vista de subcategorias.

   $tb .= '<tr><td class="">'.$value[1].'</td>';

   /*-----------------------------------*/
   /*	Detalles de Tarifas
   /*-----------------------------------*/
   $monto    = $fin->Select_MontoSubtotal($date_i,$date,$value[0]);
   $paxID    = $fin->ExisteEnBitacora($date_i,$date,$value[0],'pax');
   $noID     = $fin->ExisteEnBitacora($date_i,$date,$value[0],'Noche');
   $tar      = $fin->Select_Total2($date_i,$date,$value[0]);
   // $tar      = $fin->ExisteEnBitacora($date_i,$date,$value[0],'Tarifa');
   $tarifa  += $tar;

   switch ($id) {
    case 1:
    case 2:
    case 11:
    $tb .='
    <td class="text-right bg-info" id="pax'.$value[0].'">
    '.evaluar2($paxID).'
    </td>';

    $tb .='
    <td class="text-right bg-info" id="Noche'.$value[0].'">
    '.evaluar2($noID).'
    </td>

    <td class="text-right bg-info" id="Tarifa'.$value[0].'">
    '.evaluar2($tar).'
    </td>';
    
    $N_M             = $N_M + $noID;
    $total_pax      += $paxID;
    $gran_total_pax += $paxID;
    break;

    case 12:
    // $tb .= '<td class="pointer bg-default"></td>';
    $tar      = $fin->ExisteEnBitacora($date_i,$date,$value[0],'Subtotal');
    $tarifa  += $tar;
    $tb .='
    <td class="text-right bg-info" id="SubTotal'.$value[0].'">
    '.evaluar2($tar).'
    </td>';
    break;

    case 9:
    $tb .= '
    <td class="text-right bg-info" id="Tarifa'.$value[0].'">
    '.evaluar2($tar).'
    </td>';
    break;


    default:
    $tb .='
    <td class="text-right bg-info" id="pax'.$value[0].'">
    '.evaluar2($paxID).'
    </td>';

    $total_pax += $paxID;
    $gran_total_pax += $paxID;
    $tb .= '
    <td class="text-right bg-info" id="Tarifa'.$value[0].'">
    '.evaluar2($tar).'
    </td>';
    break;


   }

   /*-----------------------------------*/
   /* Formas de Pago
   /*-----------------------------------*/

   if ( $id != 12) {
    foreach ($fpago as $key => $valfpago) {
     $montoFP = $fin->Select_MontoFPago($date_i,$date,$value[0],$valfpago[0]);

     switch ($key) {

      case 0:  $Efectivo  += $montoFP; break;
      case 1:  $TC        += $montoFP; break;
      case 2:  $CxC       += $montoFP; break;
      case 3:  $Anticipo  += $montoFP; break;
      case 4:  $CargosHab += $montoFP; break;
      case 5:  $Cargos2   += $montoFP; break;

     }

     $tb .='<td title="'.$valfpago[1].'" class="text-right bg-warning text-primary-1" id="fp'.$value[0].'-'.$valfpago[0].'">
     '.evaluar($montoFP).'
     </td>';
    }
   }

   /*-----------------------------------*/
   /*	SubTotal
   /*-----------------------------------*/
   if ( $id != 12 ) {

    $tb .=' <td id="sub'.$id.'-'.$value[0].'" class="text-right">
    '.evaluar($monto).'
    </td>';

   }

   foreach ($imp as $key => $valimp) {
    $monto = $fin->Select_MontoImpuesto($date_i,$date,$value[0],$valimp[0]);

    switch ($key) {
     case 0:  $IVA  += $monto;   break;
     case 1:  $IVA2 += $monto;   break;
    }

    $tb .='<td id="Imp'.$value[0].'-'.$valimp[0].'" class="text-right">
    '.evaluar($monto).'
    </td>';
   }


   if ( $id != 12 ) {

    $total2     = $fin->Select_Total2($date_i,$date,$value[0]);
    $TotalGral  = $TotalGral+$total2;
    $diferencia = $tar-$total2;


    $tb .=
    '<td class=" text-right" id="total2'.$value[0].'">
    '.evaluar($total2).'
    </td>';
   }
   $tb .= '</tr>';

  }

  /*-----------------------------------*/
  /* TOTALES
  /*-----------------------------------*/

  $tb .='</tbody>

  <tfoot>
  <tr class="text-right">
  <td  class="bg-info-1" >TOTAL</td>
  ';

  switch ($id) {
   case 1:
   case 2:
   case 11:
   $tb .= '<td class="bg-info-1">'.$total_pax.'</td>';
   $tb .= '
   <td class="bg-info-1">'.evaluar($N_M).'</td>
   <td class="bg-info-1">'.evaluar($tarifa).'</td>
   ';
   break;

   case 9:
   $tb .= '
   <td class="bg-info-1">'.evaluar($tarifa).'</td>';
   break;

   case 12: // empleados & Cortesias
   $tb .= '
   <td class="bg-info-1">'.evaluar($tarifa).'</td>';
   break;

   default:
   $tb .= '<td class="bg-info-1">'.$total_pax.'</td>';
   $tb .= '
   <td class="bg-info-1">'.evaluar($tarifa).'</td>';
   break;

  }

  // $total_efectivo  = 0;
  // $total_cxc       = 0;
  // $total_anticipos = 0;
  // $total_tarjet    = 0;

  if ($id != 12) {

   for ($i=1; $i <=  count($fpago); $i++) {
    switch ($i) {
     case 1:
     $tb .='<td class="bg-info-1">'.evaluar($Efectivo).'</td>';
     $total_efectivo += $Efectivo ;
     break;
     case 2:
     $tb .='<td class="bg-info-1">'.evaluar($TC).'</td>';
     $total_tarjet += $TC ;
     break;
     case 3:
     $tb .='<td class="bg-info-1">'.evaluar($CxC).'</td>';
     $total_cxc += $CxC ;
     break;
     case 4:
     $tb .='<td class="bg-info-1">'.evaluar($Anticipo).'</td>';
     $total_anticipos += $Anticipo ;
     break;
     case 5: $tb .='<td class="bg-info-1">'.evaluar($CargosHab).'</td>'; break;
     case 6: $tb .='<td class="bg-info-1">'.evaluar($Cargos2).'</td>'; break;
    }

   }

   $tb .='<td class="bg-info-1">'.evaluar($t2).'</td>';


   foreach ($imp as $key => $valimp) {
    switch ($key) {
     case 0:  $tb .='<td class="bg-info-1">'.evaluar($IVA).'</td>';  break;
     case 1:  $tb .='<td class="bg-info-1">'.evaluar($IVA2).'</td>'; break;

    }
   }

   $tb .='
   <td class="bg-info-1">'.evaluar($TotalGral).'</td>';

  }// Empleados & Cortesias



  $tb .='</tr>
  </tfoot>
  </table>
  </div>
  ';
 }

 $granTotal = $total_efectivo + $total_cxc + $total_tarjet + $total_anticipos;

 $tb .='
 <div style="width:60%" >
 <table class="table table-bordered" style="width:100%">
 <tbody>
 <tr>

 <td class="col-xs-3">Total / Efectivo </td>
 <td class="col-xs-3 text-right">'.evaluar($total_efectivo).'</td>
 <td class="col-xs-4 ">Total / Cuentas por cobrar </td>
 <td class="text-right">'.evaluar($total_cxc).'</td>

 </tr>

 <tr>

 <td>Total / Tarjeta </td>
 <td class="text-right">'.evaluar($total_tarjet).' </td>
 <td>Total / Anticipos </td>
 <td class="text-right">'.evaluar($total_anticipos).'</td>

 </tr>



 <tr><td colspan="4"></td>
 </tr>
 <tr>

 <td class="col-xs-3">Número de personas </td>
 <td class="col-xs-3 text-right">'.$gran_total_pax.'</td>
 <td class="col-xs-4 ">Gran total </td>
 <td class="text-right">'.evaluar($granTotal).'</td>

 </tr>

 <tr>
 <tbody>
 </table>
 </div>
 ';



 return $tb;
}

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }

 return $res;
}

function evaluar2($val){
 $res = '';
 if ($val==0 || $val=="" || $val==null) {
  $res = '-';
 }else {
  $res =''.$val;
 }

 return $res;
}


function td_tarifas($categoria){
 $td    = '';

 switch ($categoria) {
  case 1:
  $td .= '<th>Pax</th>';
  $td .= '<th>Noche</th><th>Monto</th>';
  break;
  case 2:
  $td .= '<th>Pax</th>';
  $td .= '<th>Mesas</th><th>Total</th>';
  break;

  case 9:
  $td .= '<th>Monto</th>';
  break;
  case 11:
  $td .= '<th>Pax</th>';

  $td .= '<th>Noche</th><th>Tarifa</th>';
  break;

  case 12:
  $td .= '<th>Monto</th>';
  break;
  default:
  $td .= '<th>Pax</th>';
  $td .= '<th>Monto</th>';
  break;
 }

 return  $td;
}

function input_td($id,$name,$val) {
 $td ='
 <input style="width:100%;"
 class=" input-xs total-input" value="'.$val.'"
 onkeypress="if(event.keyCode == 13) Edit('.$id.',\''.$name.'\')"
 id="Edit'.$name.$id.'">
 ';
 return $td;
}



?>
