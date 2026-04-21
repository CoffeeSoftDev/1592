<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
$fin = new Finanzas;

include_once('../../../modelo/UI_TABLE.php');
$table    = new Table_UI;

$opc      = $_POST['opc'];
$date_now = $fin->NOW();
$idE      = $_SESSION['udn'];

switch ($opc) {

  case 0://CONSULTAR SALDO INICIAL Y FECHA DE INICIO

  $sql = $fin->Select_Retiro($idE);
  foreach($sql as $row);
  $SI = 0; if(!isset($row[1])){$SI=0;}else{$SI = $row[1];}
  if(isset($row[2])) {
    $date_SI = $row[2];

    // $array = array($idE,$date_SI,$date_now);

    //Gastos fondo
    $TG = $fin->Select_Fondo_Caja_Remaster($idE,$date_SI,$date_now);

    //Anticipos
    $TA = 0;

    //Pagos de proveedor
    $TP = $fin->Select_Pago_Proveedor_Remaster_Fondo($idE,$date_SI,$date_now);

    //Saldo Final
    $SF = $SI - $TG - $TA - $TP;

    $resultado = array(
      number_format($SI,2,".",","),
      number_format($SF,2,".",",")
    );
  }
  else{
    $resultado = array('0.00','0.00');
  }
  echo json_encode($resultado);
  break;



  case 1://ACCESO A RETIROS Y REMBOLSOS
  $pass = $_POST['pass'];				//Se recibe la contraseña

  $idU = $fin->Select_Admin($pass);
  $valor = 1; if ( $idU == null ) { $valor = 0; }
  $_SESSION['area'] = 4;

  echo $valor;
  break;
  case 2://MODAL ADMINISTRADOR
  ?>
  <div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong>Acceso Administrador</strong></h3>
  </div>
  <div class="modal-body">

    <div class="row">
      <div class="form-group col-sm-12 col-xs-12 Group_user">
        <label>El acceso a este apartado es única y exclusivamente para el encargado de realizar los retiros y reembolsos, por lo que solo necesita ingresar su contraseña debido a que el usuario ya esta registrado.</label>
      </div>

      <!-- <div class="form-group col-sm-12 col-xs-12 Group_user">
      <label for="user" class="control-label">Usuario</label>
      <input type="text" class="form-control" placeholder="Usuario" autocomplete="off" id="user" onkeypress="if(event.keyCode == 13) login();">
    </div> -->

    <div class="form-group col-sm-12 col-xs-12 Group_pass">
      <label for="pass" class="control-label">Contraseña</label>
      <input type="password" class="form-control" placeholder="Contraseña" onkeypress="if(event.keyCode == 13) Login_administracion();" autocomplete="off" id="pass" onkeypress="if(event.keyCode == 13) login();">
    </div>
  </div>

  <div class="text-center" id="Res_Finanzas"></div>

  <div class="row">
    <br>
    <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
      <button type="button" class="btn btn-danger btn-sm col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
    </div>
    <div class="form-group col-sm-4 col-xs-12">
      <button type="button" class="btn btn-primary btn-sm col-sm-12 col-xs-12" onclick="Login_administracion();">Acceder</button>
    </div>
  </div>
</div>
<?php
break;
case 3://MODAL RETIROS
?>
<div class="modal-header">
  <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
  <h3 class="modal-title text-center"><strong>RETIROS DE VENTA</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12 Group_user">
      <label>Por motivos de seguridad, cualquier movimiento dentro de esta sección requiere autorización.</label>
    </div>

    <!-- <div class="form-group col-sm-12 col-xs-12 Group_user">
    <label for="user" class="control-label">Usuario</label>
    <input type="text" class="form-control" placeholder="Usuario" autocomplete="off" id="user" onkeypress="if(event.keyCode == 13) login();">
  </div> -->

  <div class="form-group col-sm-12 col-xs-12 Group_pass">
    <label for="pass" class="control-label">Contraseña</label>
    <input type="password" class="form-control" placeholder="Contraseña" autocomplete="off" id="pass_re" onkeypress="if(event.keyCode == 13) save_retiro_ventas();">
  </div>
</div>

<div class="text-center" id="Res_Finanzas_RE"></div>

<div class="row">
  <br>
  <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
    <button type="button" class="btn btn-danger btn-sm col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
  </div>
  <div class="form-group col-sm-4 col-xs-12">
    <button type="button" class="btn btn-primary btn-sm col-sm-12 col-xs-12" onclick="save_retiro_ventas();">Acceder</button>
  </div>
</div>
</div>
<?php
break;
case 4://MODAL REEMBOLSOS
?>
<div class="modal-header">
  <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
  <h3 class="modal-title text-center"><strong>REEMBOLSOS DE EFECTIVO</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12 Group_user">
      <label>Por motivos de seguridad, cualquier movimiento dentro de esta sección requiere autorización.</label>
    </div>

    <!-- <div class="form-group col-sm-12 col-xs-12 Group_user">
    <label for="user" class="control-label">Usuario</label>
    <input type="text" class="form-control" placeholder="Usuario" autocomplete="off" id="user" onkeypress="if(event.keyCode == 13) login();">
  </div> -->

  <div class="form-group col-sm-12 col-xs-12 Group_pass">
    <label for="pass" class="control-label">Contraseña</label>
    <input type="password" class="form-control" placeholder="Contraseña" onkeypress="if(event.keyCode == 13) save_rembolso();" autocomplete="off" id="pass_rem">
  </div>
</div>

<div class="text-center" id="Res_RemFinanzas"></div>

<div class="row">
  <br>
  <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
    <button type="button" class="btn btn-danger btn-sm col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
  </div>
  <div class="form-group col-sm-4 col-xs-12">
    <button type="button" class="btn btn-primary btn-sm col-sm-12 col-xs-12" onclick="save_rembolso();">Acceder</button>
  </div>
</div>
</div>
<?php
break;

case 5: // Detalle de reembolsos
sleep(2);
$fecha    = $_POST['fecha'];
$Titulo   = array('Saldo anterior','Reembolso','Saldo actual','Concepto','<span class="icon-cog-2"></span>');
$tdMoneda = array(0,1,2);
$idE      = $_SESSION['udn'];
$sql      = $fin -> Fondo_detallado(array($idE,$fecha));
$tb       =  $table ->Simple_Table_btn($Titulo,$tdMoneda,$sql,null);
echo $tb;
break;

}
?>
