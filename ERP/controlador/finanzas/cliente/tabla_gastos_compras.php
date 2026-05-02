<?php
session_start();

include_once("../../../modelo/SQL_PHP/_CRUD.php");
include_once("../../../modelo/SQL_PHP/_Finanzas_Compras.php");
include_once("../../../modelo/SQL_PHP/_Utileria.php");
include_once("../../../modelo/UI_TABLE.php");
include_once('../../../modelo/SQL_PHP/_Finanzas_ADMIN.php');
$crud = new CRUD;
$util = new Util;
$fin  = new Compras_Fin;
$tb   = new Table_UI;
$finanzas = new Finanzas;

date_default_timezone_set('America/Mexico_City');

$disable_date = '';
$btn_color    = 'btn-danger';
$hoy          = $fin->NOW();
$ayer         = $fin->Ayer();
$date         = $_POST['date'];

if($date != $hoy && $date != $ayer){
 $disable_date = 'disabled';
 $btn_color = 'btn-default';
}

$UDN = $_SESSION['udn'];

/*-----------------------------------*/
/*		MAIN
/*-----------------------------------*/
$Titulo = array(
'Fecha Factura',
'# Factura',
'# Pedido',
'Proveedor',
'Insumo',
'Subtotal',
'Monto IVA',
'Total',
'Destino ',
'Pagador',
'Observaciones',
'<span class="fa fa-gear"></span>');


$EditTD = array(
 '',
 'txtPedido',
 'txtFactura',
 'Prov_Compras',
 'Insumo_Compras',
 'Cant_Compras',
 'Iva_Compras',
 'Total_Compras',
 'ClaseInsumo_Compras',
 'TipoGasto_Compras',
 'Observaciones_Compras');

$array = array($date);
$foreach  = $fin -> VER_GASTOS_COMPRAS($date);
$txt      = $tb  -> CrearTB($Titulo,$foreach,$EditTD,$finanzas);

/*-----------------------------------*/
/*		JSON ENCODE
/*-----------------------------------*/
$encode = array(0=>$txt);
echo json_encode($encode);


?>
