<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas_Compras.php');
include_once('../../../modelo/SQL_PHP/_Utileria.php');
$fin = new Compras_Fin;
$util = new Util;
$idE = $_SESSION['udn'];
$opc = $_POST['opc'];

switch ($opc) {
 case 0://Guardar Gastos
 $Proveedor = strtr(strtoupper($_POST['Proveedor']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $Insumo = strtr(strtoupper($_POST['Insumo']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $Clase_Insumo = $_POST['Clase_Insumo'];
 $Gastos = $_POST['Gastos'];
 $Gastos_IVA = 0;
 $Clase_Gasto = $_POST['Clase_Gasto'];
 $Observaciones = $_POST['Observaciones'];
 $date = $_POST['date'];

 $idUP = null; $idUG = null;
 if ($Proveedor != "") {
  //Obtener el idproveedor
  $idP = $fin->Select_idProveedores($Proveedor);
  if($idP == null){
   $fin->Insert_Proveedor($Proveedor);//Insertar nuevo proveedor
   $idP = $fin->Select_idProveedores($Proveedor);//Consultar de nuevo el idProveedor
  }
  //crear arreglo para buscar relacion
  $array = array($idP,$idE);
  $idUP = $fin->Select_Empresa_Proveedor($array);//Buscar el id de relación
  if($idUP == null){
   $fin->Insert_Empresa_Proveedor($array);//insertar nueva relación
   $idUP = $fin->Select_Empresa_Proveedor($array);//Consultar de nuevo el id relacion
  }
 }

 if($Insumo != ""){
  //Obtener el idGasto
  $idG = $fin->Select_idGasto($Insumo);
  if($idG == null){
   $fin->Insert_Gasto($Insumo);
   $idG = $fin->Select_idGasto($Insumo);
  }
  //Crear arreglo para buscar relacion
  $array =  array($idG,$idE);
  $idUG = $fin->Select_Empresa_Gasto($array);
  if($idUG == null){
   $fin->Insert_Empresa_Gasto($array);
   $idUG = $fin->Select_Empresa_Gasto($array);
  }
 }

 //Buscar la relacion clase_insumo empresa
 // $array = array($Clase_Insumo,$idE);
 // $idUI = $fin->Select_Empresa_ClaseInsumo($array);
 $idUI = $Clase_Insumo;

 $ES = "";

 $sql = $fin->Select_idAlmacen($idE);
 $idIC = array();
 foreach ($sql as $i => $row) {
  $idIC[$i] = $row[0];
 }

 if(in_array($Clase_Insumo,$idIC,true)){
  $ES = 1;
 }


 if($ES != ""){ // Entradas & Salidas de almacen
  $array = array($idE,$idUP,$idUG,$idUI,$Clase_Gasto,$ES,1,$Gastos,$Gastos_IVA,$Observaciones,$date);//11 DATOS
  $query = "INSERT INTO hgpqgijw_finanzas.compras (id_UDN,id_UP,id_UG,id_UI,id_CG,E_S,Status,Gasto,GastoIVA,Observacion,Fecha_Compras) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
 }
 else{
  $array = array($idE,$idUP,$idUG,$idUI,$Clase_Gasto,1,$Gastos,$Gastos_IVA,$Observaciones,$date);//10 DATOS
  $query = "INSERT INTO hgpqgijw_finanzas.compras (id_UDN,id_UP,id_UG,id_UI,id_CG,Status,Gasto,GastoIVA,Observacion,Fecha_Compras) VALUES (?,?,?,?,?,?,?,?,?,?)";
 }

 $fin->Insert_Bitacora_Compras($query,$array);
 echo $Gastos_IVA;
 break;
 case 1://AUTOCOMPLETE PROVEEDOR
 $sql = $fin->Select_Proveedores();
 $arreglo = array();
 foreach ($sql as $key => $value) {
  $arreglo[$key] = $value[0];
 }
 echo json_encode($arreglo);
 break;
 case 2://AUTOCOMPLETE CONCEPTO
 $sql = $fin->Select_Insumos();
 $arreglo = array();
 foreach ($sql as $key => $value) {
  $arreglo[$key] = $value[0];
 }
 echo json_encode($arreglo);
 break;
 case 3://MODIFICACIÓN DE LA TABLA COMPRAS
 $tipo = $_POST['tipo'];
 $idCompras = $_POST['id'];
 switch ($tipo) {
  case 1://Proveedor
  $Proveedor = strtr(strtoupper($_POST['valor']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
  $Name_Gastos = $fin->Select_NameGasto_X_identificador($idCompras);
  if($Proveedor == '' && $Name_Gastos == ''){
   $Name_Proveedor = $fin->Select_NameProvedor_X_Indentificador($idCompras);
   $array = array($Name_Proveedor,'<label class="text-danger"><span class="icon-attention"></span> El proveedor y el insumo no pueden estar vacios al mismo tiempo</label>');
   echo json_encode($array);
  }
  else {
   //Obtener el idproveedor
   $idP = $fin->Select_idProveedores($Proveedor);
   if($idP == null){
    $fin->Insert_Proveedor($Proveedor);//Insertar nuevo proveedor
    $idP = $fin->Select_idProveedores($Proveedor);//Consultar de nuevo el idProveedor
   }
   //crear arreglo para buscar relacion empresa
   $array = array($idP,$idE);
   $idUP = $fin->Select_Empresa_Proveedor($array);//Buscar el id de relación
   if($idUP == null){
    $fin->Insert_Empresa_Proveedor($array);//insertar nueva relación
    $idUP = $fin->Select_Empresa_Proveedor($array);//Consultar de nuevo el id relacion
   }
   //Actualizar el proveedor en compras
   $array = array($idUP,$idCompras);
   $fin->Update_Proveedor_Pago($array);
   $array = array($Proveedor,'');
   echo json_encode($array);
  }
  break;
  case 2://Insumo
  $Insumo = strtr(strtoupper($_POST['valor']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
  $Name_Proveedor = $fin->Select_NameProvedor_X_Indentificador($idCompras);
  if($Insumo == '' && $Name_Proveedor == ''){
   $Name_Gastos = $fin->Select_NameGasto_X_identificador($idCompras);
   $array = array($Name_Gastos,'<label class="text-danger"><span class="icon-attention"></span> El proveedor y el insumo no pueden estar vacios al mismo tiempo</label>');
   echo json_encode($array);
  }
  else {
   //Obtener el idGasto
   $idG = $fin->Select_idGasto($Insumo);
   if($idG == null){
    $fin->Insert_Gasto($Insumo);
    $idG = $fin->Select_idGasto($Insumo);
   }
   //Crear arreglo para buscar relacion
   $array =  array($idG,$idE);
   $idUG = $fin->Select_Empresa_Gasto($array);
   if($idUG == null){
    $fin->Insert_Empresa_Gasto($array);
    $idUG = $fin->Select_Empresa_Gasto($array);
   }
   //Actualizar el insumo en compras
   $array = array($idUG,$idCompras);
   $fin->Update_Insumo_Pago($array);
   $array = array($Insumo,'');
   echo json_encode($array);
  }
  break;
  case 3://Clase de Insumo
  $Clase_Insumo = $_POST['valor'];
  $array = array($Clase_Insumo,$idE);
  $idUI = $fin->Select_Empresa_ClaseInsumo($array);

  $array = array($idUI,$idCompras);
  $fin->Update_Insumo_Clase($array);

  $Name_IC = $fin->Select_Name_Insumo_Clase($Clase_Insumo);
  $array = array($Name_IC,'');
  echo json_encode($array);
  break;
  case 4://Cantidad de Compras
  $valor = $_POST['valor'];
  $array = array($valor,$idCompras);
  $fin->Update_Cantidad_Compras2($array);
  $array = array($valor,'');
  echo json_encode($array);
  break;
  case 5://Tipo de Gasto
  $valor = $_POST['valor'];
  $array = array($valor,$idCompras);
  $fin->Update_Clase_Gasto($array);

  $Name_CG = $fin->Select_Name_Clase_Gasto($valor);
  $array = array($Name_CG,'');
  echo json_encode($array);
  break;
  case 6://Observaciones
  $valor = trim($_POST['valor']);
  $array = array($valor,$idCompras);
  $fin->Update_Observaciones_Compras($array);
  $array = array($valor,'');
  echo json_encode($array);
  break;
 }
 break;
 case 4://CONSULTA CLASE INSUMO
 $opc = $_POST['pc'];
 $valor = $_POST['valor'];

 if($opc == 1){//Compras
  $option = '';
  $sql = $fin->Select_Group_Insumo($idE);
  foreach ($sql as $row) {
   if($row[1] == $valor){ $sel = 'selected'; }else{ $sel = ''; }
   $option = $option.'<option value='.$row[0].' '.$sel.'>'.$row[1].'</option>';
  }
  echo $option;
 }
 else if($opc == 2){//Pagos
  $option = '';
  $sql = $fin->Select_idAlmacen($idE);
  foreach ($sql as $row) {
   if($row[1] == $valor){ $sel = 'selected'; }else{ $sel = ''; }
   $option = $option.'<option value='.$row[0].' '.$sel.'>'.$row[1].'</option>';
  }
  echo $option;
 }
 break;
 case 5://CONSULTA DE TIPO DE GASTO
 $valor = $_POST['valor'];

 $option = '';
 $sql = $fin->Select_Group_Gastos();
 foreach ($sql as $row) {
  if($row[1] == $valor){ $sel = 'selected'; }else{ $sel = ''; }
  $option = $option.'<option value='.$row[0].' '.$sel.'>'.$row[1].'</option>';
 }
 echo $option;
 break;


 case 6://MODAL ELIMINAR
 $id = $_POST['id'];
 $opcc = $_POST['opcc'];

 echo
 '<div class="modal-header">
 <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
 <h3 class="modal-title text-center text-danger"><strong><span class="icon-attention"></span>Eliminar Compras</strong></h3>
 </div>
 <div class="modal-body">
 <div class="row">
 <div class="form-group col-sm-12 col-xs-12 text-center">
 <label class="col-sm-12 col-xs-12">Esta seguro de realizar este movimiento, al eliminarlo no podrá recuperarse</label>
 </div>
 </div>
 <div class="row">
 <br>
 <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
 <button type="button" class="btn btn-danger btn-xs col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
 </div>
 <div class="form-group col-sm-4 col-xs-12">
 <button type="button" class="btn btn-primary btn-xs col-sm-12 col-xs-12" onclick="Eliminar_Compras_Pagos('.$id.','.$opcc.');">Continuar</button>
 </div>
 </div>
 </div>';
 break;
 case 7://Eliminar Compras
 $id = $_POST['id'];
 $fin->Delete_Compras_Pago($id);
 break;

 case 8: // Agregar gasto compras

 $Factura        = $_POST['Factura'];
 $Pedido         = $_POST['Pedido'];

 $Proveedor      = strtr(strtoupper($_POST['Proveedor']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $Insumo         = strtr(strtoupper($_POST['Insumo']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $Clase_Insumo   = $_POST['Clase_Insumo'];
 $Gastos         = $_POST['Gastos'];
 $Gastos_IVA     = $_POST['GastosIVA'];
 $Clase_Gasto    = $_POST['Clase_Gasto'];
 $Observaciones  = $_POST['Observaciones'];
 $date           = $_POST['date'];
 $datef          = $_POST['dateFacture'];

 /*----------------------*/
 $idUP = null; $idUG = null;


 if ($Proveedor != "") {

  /* Obtener el idproveedor */

  $idP     = $fin->Select_idProveedores($Proveedor);

  if($idP == null){
   $fin->Insert_Proveedor($Proveedor);//Insertar nuevo proveedor
   $idP = $fin->Select_idProveedores($Proveedor);//Consultar de nuevo el idProveedor
  }

  //crear arreglo para buscar relacion
  $array = array($idP,$idE);
  $idUP = $fin->Select_Empresa_Proveedor($array);//Buscar el id de relación

  if($idUP == null){
   $fin->Insert_Empresa_Proveedor($array);//insertar nueva relación
   $idUP = $fin->Select_Empresa_Proveedor($array);//Consultar de nuevo el id relacion
  }

 } // End Proveedor

 /* Obtener el idGasto */
 if($Insumo != ""){
  $idG = $fin->Select_idGasto($Insumo);

  if($idG == null){
   $fin->Insert_Gasto($Insumo);
   $idG = $fin->Select_idGasto($Insumo);
  }

  //Crear arreglo para buscar relacion
  $array =  array($idG,$idE);
  $idUG = $fin->Select_Empresa_Gasto($array);

  if($idUG == null){
   $fin->Insert_Empresa_Gasto($array);
   $idUG = $fin->Select_Empresa_Gasto($array);
  }
 } // End idGasto

 /* Obtener si tiene relacion con almacen */

 $idUI  = $Clase_Insumo;
 $ES    = "";

 $sql   = $fin->Select_idAlmacen($idE);
 $idIC  = array();

 foreach ($sql as $i => $row) {
  $idIC[$i] = $row[0];
 }

 if(in_array($Clase_Insumo,$idIC,true)){
  $ES = 1;
 }


 $Total  = $Gastos + $Gastos_IVA ;

//  if($ES != ""){
//   $array = array($idE,$idUP,$idUG,$idUI,$Clase_Gasto,$ES,1,$Total,$Gastos_IVA,$Observaciones,$date);//11 DATOS
//   $query = "INSERT INTO hgpqgijw_finanzas.compras (id_UDN,id_UP,id_UG,id_UI,id_CG,E_S,Status,Gasto,GastoIVA,Observacion,Fecha_Compras) VALUES (?,?,?,?,?,?,?,?,?,?,?)";

//  }else{
  $array = array($idE,$idUP,$idUG,$idUI,$Clase_Gasto,1,$Total,$Gastos_IVA,$Observaciones,$date,$Pedido,$Factura,$datef);//12 DATOS

  $query =
  "INSERT INTO hgpqgijw_finanzas.compras
  (id_UDN,id_UP,id_UG,id_UI,id_CG,Status,Gasto,GastoIVA,Observacion,Fecha_Compras,NumPedido,Factura,FechaFactura)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";

//  }

 $fin->Insert_Bitacora_Compras($query,$array);
 echo $datef;

 break ;

 case 9: // GASTO COMPRAS

 $id = $_POST['id'];
 $opcc = $_POST['opcc'];

 echo
 '<div class="modal-header">
 <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
 <h3 class="modal-title text-center text-danger"><strong><span class="icon-attention"></span>Eliminar Compras</strong></h3>
 </div>
 <div class="modal-body">
 <div class="row">
 <div class="form-group col-sm-12 col-xs-12 text-center">
 <label class="col-sm-12 col-xs-12">Esta seguro de realizar este movimiento, al eliminarlo no podrá recuperarse</label>
 </div>
 </div>
 <div class="row">
 <br>
 <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
 <button type="button" class="btn btn-danger btn-xs col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
 </div>
 <div class="form-group col-sm-4 col-xs-12">
 <button type="button" class="btn btn-primary btn-xs col-sm-12 col-xs-12" onclick="Eliminar_Compras_PG('.$id.','.$opcc.');">Continuar</button>
 </div>
 </div>
 </div>';

 break;

}
?>
