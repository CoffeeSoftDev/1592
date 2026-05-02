<?php
session_start();

include_once("../../modelo/SQL_PHP/_Finanzas_Compras.php");
$fin    = new Compras_Fin;
include_once("../../modelo/UI_TABLE.php");
$tb   = new Table_UI;

$date    = $_GET['date'];
$txt    = '';
?>
<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <title>Formato de impresión</title>

 <link rel="stylesheet" href="../../recursos/css/formato_impresion.css">
 <link rel="stylesheet" href="../../recursos/css/bootstrap/bootstrap.min.css">

 <script type="text/javascript">
 function imprimir() {
  if (window.print) {
   window.print();
  }
  else {
   alert("La función de impresión no esta disponible en este navegador, intentelo con otro diferente.");
  }
 }
 </script>


 <style type="text/css" media="print">
 @page{
  margin-top:  20px;
  margin-bottom:   20px;
  margin-left:   20px;
  margin-right:    30px;
 }

 table{
  font-size: 10px;
 }
 .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
  padding: 3px;
  line-height: 1.32857143;
  vertical-align: top;
  border-top: 1.4px solid #ecf0f1;
 }
 </style>

</head>


<body onload="imprimir();">

<?php

$txt = $txt.'
<br>
<div class="row">

<div class="col-xs-3 col-sm-3 ">
<img src="../img/logo.png" width="150px" class="img-rounded center-block">
</div>

<div class="col-xs-6 col-sm-6 text-center">
<h5 class=""><strong>RENDICION DE CAJA DIVERSIFICADOS ARGOVIA S.A. DE C.V.</strong></h5>
</div>

<div class="col-xs-3 col-sm-3 ">
<p><strong> Fecha: </strong>'.$date.'</p>
<p><strong> Sucursal: </strong> OFICINAS ARGOVIA </p>
</div>
<br>
<br>
</div>';
/*-----------------------------------*/
/*		MAIN
/*-----------------------------------*/
$Titulo = array(
'Fecha ',
'Factura',
'# Pedido',
'Proveedor',
'Descripcion de articulo',
'Subtotal',
'Monto IVA',
'Total',
'Destino ',
'Pagador',
'Observaciones');


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
$tbs      = $tb  -> PrintTb($Titulo,$foreach,$EditTD);

$txt= $txt.$tbs.'

<div class="container">
<br><br><br>
<div class="row">
<div class="col-xs-4 col-sm-4 text-center">
<label style="margin-bottom: 0px;"></label>
<p>___________________________________</p>

<p>COMPRAS</p>
</div>

<div class="col-xs-4 col-sm-4 text-center">
<label style="margin-bottom: 0px;"></label>
<p>___________________________________</p>

<p>TESORERIA</p>
</div>

<div class="col-xs-4 col-sm-4 text-center">
<label style="margin-bottom: 0px;"></label>
<p>___________________________________</p>

<p>DIRECTOR GENERAL</p>
</div>

</div>
</div>
';
 // ===========================================
 //     PRINT
 // ===========================================
 echo $txt;
?>

</body>
</html>
<?php

	/*===========================================
	*				Funciones php
	=============================================*/

?>
