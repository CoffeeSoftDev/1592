<?php
session_start();

include_once("../../modelo/SQL_PHP/_.php"); 
$obj    = new ;

$var    = $_GET[''];
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

$txt = $txt.'';

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
