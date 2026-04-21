<?php
session_start();
include_once('../../modelo/SQL_PHP/_Finanzas_Cheques.php');
$fin = new Files_Cheq;

$idE = $_SESSION['udn'];
$id = $_GET['top'];

$sql = $fin->Select_Datos_Cheques($id);
foreach($sql as $row);
$ruta = '../../'.$row[7].''.$row[8];
?>

<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <title>IMPRIMIR</title>

 <link rel="stylesheet" href="../../recursos/css/formato_impresion.css">
 <link rel="stylesheet" href="../../recursos/icon-font/fontello.css">
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

 <div class="row">
  <div class="form-group col-sm-12 col-xs-12" >
   <label style="border: 1px solid #000000; font-size:16px; border-radius: 1px;" class="form-control text-center">
    POLIZA DE CHEQUE</label>
  <h3> <label class="col-sm-12 col-xs-12 text-center  "><?php echo $row[9]; ?></label></h3>
  </div>
 </div>


 <div class="row">
  <div class="form-group col-sm-12 col-xs-12" style="margin:0px;">
   <label class="col-sm-2 col-xs-2">FECHA: </label>
   <span class="col-sm-10 col-xs-10"><?php echo $row[0]; ?></span>
  </div>

  <div class="form-group col-sm-12 col-xs-12" style="margin:0px;">
   <label class="col-sm-2 col-xs-2">NOMBRE: </label>
   <span class="col-sm-10 col-xs-10"><?php echo $row[1]; ?></span>
  </div>

  <div class="form-group col-sm-12 col-xs-12" style="margin:0px;">
   <label class="col-sm-2 col-xs-2">IMPORTE: </label>
   <span class="col-sm-10 col-xs-10"><?php echo evaluar($row[2]); ?></span>
  </div>

  <div class="form-group col-sm-12 col-xs-12" style="margin:0px;">
   <label class="col-sm-2 col-xs-2">BANCO: </label>
   <span class="col-sm-10 col-xs-10"><?php echo $row[3]; ?></span>
  </div>

  <div class="form-group col-sm-12 col-xs-12" style="margin:0px;">
   <label class="col-sm-2 col-xs-2">CUENTA: </label>
   <span class="col-sm-10 col-xs-10"><?php echo $row[4]; ?></span>
  </div>

  <div class="form-group col-sm-12 col-xs-12" style="margin:0px;">
   <label class="col-sm-2 col-xs-2">CHEQUE: </label>
   <span class="col-sm-10 col-xs-10"><?php echo $row[5]; ?></span>
  </div>

  <div class="form-group col-sm-12 col-xs-12">
   <label class="col-sm-12 col-xs-12">CONCEPTO: </label>
   <span class="col-sm-12 col-xs-12"><?php echo $row[6]; ?></span>
  </div>

 </div>

 <div class="row">
  <div class="form-group col-sm-4 col-xs-4 text-center">
   <label for="">RECIBIÓ</label>
  </div>
 </div>
 <div class="row">
  <div class="form-group col-sm-4 col-xs-4">
   <label class="col-sm-4 col-xs-4">Nombre</label>
   <label class="col-sm-8 col-xs-8" style="border-bottom:1px solid #000;"> </label>
  </div>
 </div>
 <div class="row">
  <div class="form-group col-sm-4 col-xs-4">
   <label class="col-sm-4 col-xs-4">Fecha</label>
   <label class="col-sm-8 col-xs-8" style="border-bottom:1px solid #000;"> </label>
  </div>
 </div>
 <div class="row">
  <div class="form-group col-sm-4 col-xs-4">
   <label class="col-sm-4 col-xs-4">Firma</label>
   <label class="col-sm-8 col-xs-8" style="border-bottom:1px solid #000;"> </label>
  </div>
 </div>


 <div class="row">
  <div class="form-group col-sm-6 col-xs-6 text-center">
   <label for="">ELABORÓ</label>
   <br><br><br>
   <span class="col-sm-12 col-xs-12" style="border-top:0.5px solid #000 ;">NOMBRE Y FIRMA</span>
  </div>
  <div class="form-group col-sm-6 col-xs-6 text-center">
   <label for="">AUTORIZÓ</label>
   <br><br><br>
   <span class="col-sm-12 col-xs-12" style="border-top:0.5px solid #000 ;">NOMBRE Y FIRMA</span>
  </div>
 </div>

 <hr>

 <div class="row">
  <div class="form-group col-sm-12 col-xs-12">
   <span for="">COPIA CHEQUE</span>
  </div>
 </div>
 <div class="row">
  <div class="form-group col-sm-12 col-xs-12">
   <img src="<?php echo $ruta; ?>" width="100%" class="img-rounded center-block">
  </div>
 </div>

</body>
</html>
<?php
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
