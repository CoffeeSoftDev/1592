<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas_Cheques.php');
include_once('../../../modelo/SQL_PHP/_CRUD.php');
include_once('../../../modelo/SQL_PHP/_Utileria.php');

$fin  = new Files_Cheq;
$crud = new CRUD;
$util = new Util;
$idE  = $_SESSION['udn'];
// --
$opc = $_POST['opc'];

switch ($opc) {
  case 0:
  ?>

  <br>
  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <h3 class="text-center"><strong><span class=" icon-folder-empty"></span>CHEQUES</strong></h3>
      <hr>
    </div>
  </div>

  <div class="row">
    <div class="form-group col-sm-3 col-xs-12 Name_Cq">
      <label for="Name_Cq" class="control-label">Nombre</label>
      <input type="text" class="form-control input-sm" id="Name_Cq">
    </div>

    <div class="form-group col-sm-3 col-xs-12 Importe_Cq">
      <label for="Importe_Cq" class="control-label">Importe</label>
      <div class="input-group">
        <span class="input-group-addon"><i class="icon-dollar"></i> </span>
        <input type="text" class="form-control input-sm" id="Importe_Cq" placeholder="0.00">
      </div>
    </div>
    <div class="form-group col-sm-3 col-xs-12 Banco_Cq">
      <label for="Banco_Cq" class="control-label">Banco</label>
      <input type="text" class="form-control input-sm" id="Banco_Cq">
    </div>

    <div class="form-group col-sm-3 col-xs-12" id="cbDestino">

      <select class="form-control input-sm" >

      </select>
    </div>



    <div class="form-group col-sm-3 col-xs-12 Cuenta_Cq">
      <label for="Cuenta_Cq" class="control-label">Cuenta</label>
      <input type="text" class="form-control input-sm" id="Cuenta_Cq" onkeypress="BuscarCuenta()">
      <span class="text-dark" id="txtCuenta"></span>
    </div>
    <div class="form-group col-sm-3 col-xs-12 Cheque_Cq">
      <label for="Cheque_Cq" class="control-label">Cheque</label>
      <input type="text" class="form-control input-sm" id="Cheque_Cq">
    </div>
    <div class="form-group col-sm-3 col-xs-12 Concepto_Cq">
      <label for="Concepto_Cq" class="control-label">Concepto</label>
      <input type="text" class="form-control input-sm" id="Concepto_Cq">
    </div>
    <div class="form-group col-sm-3 col-xs-12">
      <label class="col-sm-12"><strong>Seleccionar archivos (Límite 20Mb)</strong></label>
      <input type="file" class="form-control input-sm" placeholder="Limite máximo 20Mb " id="archivos">
    </div>
  </div>


  <div id="Resul" class="text-center"> </div>

  <div class="row">


    <div class="form-group col-sm-6 col-sm-offset-3 col-xs-12">
      <a type="button"  id="btnCheque"
      class="btn btn-sm btn-info col-xs-12 col-sm-6 col-sm-offset-3 "
      OnClick="Save_Cheque();"><span class="icon-upload"></span> Guardar Información</a>
    </div>
  </div>

  <div class="tb_cheques row"></div>
  <script src="recursos/js/finanzas/cliente/cheques.js?t=<?=time()?>"></script>
  <?php
  break;
  case 1://TABLA BANCOS



  /***************************************************
  VARIABLES / PAGINACIÓN
  ****************************************************/
  $date = $_POST['date'];
  $date2 = '';
  if ( $_POST['date2'] != '') { $date2 = $_POST['date2']; }
  // echo '<br>'.$date2.'<br>';
  $paginaActual = $_POST['pag'];
  $Paginas = $fin->Select_Cont_Cheques($date,$date2,$idE);
  $url= "Tb_cheques";
  $Lotes = 5;
  $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

  echo $pag;
  if($paginaActual <= 1 ){
    $limit=0;
  }
  else{
    $limit = $Lotes*($paginaActual-1);
  }

  $Total_Pag = ceil($Paginas/$Lotes);

  $fecha = '';
  if ( $date2 != '') {
    $fecha = "'".$date."' AND '".$date2."'";
  }
  else {
    $fecha = "'".$date."' AND '".$date."'";
  }

  $query = "
  SELECT

  cheques.Nombre,
  banco.Banco,
  ROUND(Importe, 2),
  cheques.Concepto,
  cheques.idCheque,
  cuentacheque.NombreCuenta,
  Name_IC
  FROM
  hgpqgijw_finanzas.cheques
  INNER JOIN hgpqgijw_finanzas.cuentacheque ON cheques.Cuenta = cuentacheque.idCuenta
  INNER JOIN hgpqgijw_finanzas.banco ON cheques.id_Banco = banco.idBanco
  INNER JOIN hgpqgijw_finanzas.insumos_udn ON cheques.id_destino = insumos_udn.idUI
  INNER JOIN hgpqgijw_finanzas.insumos_clase ON insumos_udn.id_IC = insumos_clase.idIC
  WHERE cheques.id_UDN = ".$idE." AND Fecha BETWEEN ".$fecha." LIMIT $limit, $Lotes";
  $sql = $crud->_Select($query,null);


  ?>




  <div class="form-group col-sm-12 col-xs-12 text-right">
    <label>
    </label>
  </div>
  <div class="form-group col-sm-12 col-xs-12">
    <table class="table table-responsive table-bordered table-stripped table-hover">
      <thead>
        <tr>
          <th class="text-center col-sm-1">Destino</th>
          <th class="text-center col-sm-2">Nombre</th>
          <th class="text-center col-sm-1">Banco</th>
          <th class="text-center col-sm-2">Cuenta</th>
          <th class="text-center col-sm-1 col-xs-1">Importe</th>
          <th class="text-center col-sm-2 col-xs-1">Concepto</th>
          <th class="text-center col-sm-2 col-xs-1">Descargar</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($sql as $value) {
          $btn = '<button onclick="file_poliza('.$value[4].')" class="btn btn-xs btn-warning disabled=""
          data-toggle="modal" data-target="#M1""><span class="icon-upload"></span></button>';

          echo '
          <tr>
          <td>'.$value[6].'</td>
          <td>'.$value[0].'</td>
          <td class="text-center">'.$value[1].'</td>
          <td>'.$value[5].'</td>
          <td class="text-right"><span class="icon-dollar"></span> '.number_format($value[2], 2, '.', ',').'</td>
          <td>'.$value[3].'</td>
          <td class="text-center">
          '.$btn.'
          <button type="button" class="btn btn-xs btn-primary" OnClick="Print_cheques('.$value[4].');"><span class="icon-file-pdf"></span></button>
          <button type="button" class="btn btn-xs btn-danger" OnClick="Delete_Cheque('.$value[4].');"><span class="icon-cancel"></span></button>
          </td>
          </tr>
          ';
        }



        ?>
      </tbody>
    </table>
  </div>
  <?php
  break;


  case 2://AUTOCOMPLETE BANCOS
  $sql = $fin->Select_Banco();
  $arreglo = array();
  foreach ($sql as $key => $value) {
    $arreglo[$key] = $value[0];
  }
  echo json_encode($arreglo);
  break;

  case 3: //AutoComplete cuentas
  $sql     = $fin->verCuenta();
  $arreglo = array();

  foreach ($sql as $key => $value) {
    $arreglo[$key] = $value[0];
  }

  echo json_encode($arreglo);
  break;


  case 4:
  $date     = $_POST['fi'];
  $date2    = $_POST['ff'];
  $array    = array(null);
  $txt      = "";

  $fecha = '';
  if ( $date2 != '') {
    $fecha = "'".$date."' AND '".$date2."'";
  }
  else {
    $fecha = "'".$date."' AND '".$date."'";
  }


  $query = "
  SELECT
  cheques.Fecha,
  cheques.Nombre,
  banco.Banco,
  ROUND(Importe, 2),
  cheques.Concepto,
  cheques.idCheque,
  NombreCuenta,
  cuentacheque.Cuenta,
  Cheque
  FROM
  hgpqgijw_finanzas.cheques
  INNER JOIN hgpqgijw_finanzas.cuentacheque ON cheques.Cuenta = cuentacheque.idCuenta
  INNER JOIN hgpqgijw_finanzas.banco ON cheques.id_Banco = banco.idBanco
  WHERE id_UDN = 2 AND Fecha BETWEEN ".$fecha." ";
  $sql = $crud->_Select($query,null);

  foreach ($sql as $x ) {

    $a='<button  class=\"btn btn-primary btn-sm  \" onclick=\"Print_cheques('.$x[5].')\"><span class=\"icon-file-pdf\"></span></button>';

    $txt=$txt.'{
      "Fecha":"'.$x[0].'",
      "Nombre":"'.reemplazar($x[1]).'",
      "NoCuenta":"'.$x[7].'",
      "banco":"'.$x[2].'",
      "cuenta":"'.$x[6].'",
      "cheque":"'.reemplazar($x[8]).'",
      "importe":"$ '.reemplazar($x[3]).'",
      "concepto":"'.''.reemplazar($x[4]).'",
      "opc":"'.$a.'"

    },';
  }

  $txt = substr($txt,0,strlen($txt)-1);
  echo '{"data":['.$txt.']}';

  break;

  case 5:
  $tb = '
  <div class="col-xs-12 col-sm-12 ">
  <br>
  <table id="tbCheques" class="table table-striped table-bordered nowrap" style="width:100%">
  <thead>
  <tr>
  <th>Fecha</th>
  <th>Nombre</th>
  <th>Banco</th>
  <th># Cuenta</th>
  <th>Cuenta</th>
  <th># Cheque</th>
  <th>Importe</th>
  <th>Concepto</th>
  <th><span class="fa fa-gear"></span></th>
  </tr>

  </thead>
  </table>
  </div>
  ';
  sleep(1);
  echo $tb;
  break;

  case 6:

  $cb = '<label>DESTINO: </label> <select class="form-control input-sm" id="txtDestinos" >
  ';
  $array = array($idE,1);
  $sql   = $fin->verDestinos($array);

  foreach ($sql as $row ) {
    $cb = $cb.'<option value="'.$row[0].'">'.$row[1].'</option>';
  }

  $cb   =$cb.'</select>';
  echo $cb;
  break;
}


function reemplazar($x){
$val = '';    
  $buscar     = array(chr(13).chr(10), "\r\n", "\n", "\r");
  $reemplazar = array("", "", "", "");
  $val     = str_ireplace($buscar,$reemplazar,$x);
 return   $val; 
}
?>
