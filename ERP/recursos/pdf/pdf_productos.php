<?php
session_start();

include_once("../../modelo/SQL_PHP/_MTTO_REQUISICION.php");
$obj    = new REQUISICION;

$var    = $_GET['id'];

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

 </style>

 <style>
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

 $data       = $obj -> LISTA_PDF($var);
 $productos  = count($data);
 $INVENTARIO = $obj -> verFolio($var);

 // ---
 $CANCELADO   = '<div class="col-sm-12 col-xs-12 text-danger text-center"><h2>FOLIO CANCELADO </h2></div>';
  $OBS         = $INVENTARIO[6];
 if ($INVENTARIO[1]!=3) {
  $CANCELADO  = '';
  $OBS         = $INVENTARIO[4];
 }

 // ---
 $txt = $txt.'
 <div class="container">
 <div class="row">

 <div class="col-xs-3 col-sm-3 ">
 <img src="http://www.argovia.com.mx/img/logo.png" width="150px" class="img-rounded center-block">
 </div>

 <div class="col-xs-6 col-sm-6 text-center">
 <h4 class=""><strong>INVENTARIO FISICO</strong></h4>
 </div>

 <div class="col-xs-3 col-sm-3 text-right">
 <strong>'.$INVENTARIO[3].'</strong>
 </div>
 </div>

 <br>
 '.$CANCELADO.'
 <div class="row">

 <div class="col-xs-6 col-xs-offset-6 col-sm-offset-6 text-right ">
 <h4>
 <strong>'.$INVENTARIO[2].'</strong>
 </h4>
 </div>

 </div>
 <br>
 <br>
 <div class="row">
 <div class="col-md-12 text-right">
 <span> No.productos: '.$productos.'</span>
 </div>
 <br>
 <div class="col-md-12">
 <table class="table table-bordered ">
 <thead>
 <tr class="text-xs-center">
 <th>#</th>
 <th >Producto</th>
 <th>Precio</th>
 <th>Anterior</th>
 <th>Movimiento</th>
 <th>Actual</th>
 <th>Zona</th>
 </tr>
 </thead>
 <tbody>';
 $contador = 0;
 foreach ($data as $key ) {
  $movimiento = $key[2] - $key[1];
  $contador+=1;
  $txt= $txt.'
  <tr>
  <td class="text-right">'.$contador.'</td>
  <td>'.$key[0].'</td>
  <td class="text-right">'.evaluar($key[1]).'</td>
  <td class="text-right">'.$key[1].'</td>
  <td class="text-right">'.$movimiento.'</td>
  <td class="text-right">'.$key[2].'</td>
  <td class="text-right">RESTAURANT</td>
  </tr>';
 }

 $txt= $txt.'</tbody></table></div></div></div>
 <br>
 <br>
 <div class="container">
 <table class="table table-bordered">
 <thead>
 <tr>
 <td class="text-center"> OBSERVACIONES </td>
 </tr>
 </thead>
 <tr>
 <td class="text-justified">
 '.$OBS.'
 <br>
 <br>
 </td>
 </tr>
 <tbody>
 </tbody>
 </table>

 </div>
 <br>
 <div class="container">

 <div class="row">
 <div class="col-xs-12 col-sm-12 text-center">
 <label style="margin-bottom: 0px;">'.$INVENTARIO[5].'</label>
 <p>___________________________________</p>

 <p>AUTORIZÓ</p>
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
function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }
 return $res;
}
?>
