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
    <br>
    <div class="row">
      <div class="col-xs-4 ">
        <img src="http://www.argovia.com.mx/img/logo.png" width="150px" class="img-rounded center-block">
      </div>

      <div class="col-xs-4 text-center">
        <h4 class="">Diversificados Argovia S.A. de C.V</h4 >
      </div>

      <div class="col-xs-4 text-right">
        <h4 class="col-sm-12"><strong> <?php echo $row[0]; ?> </strong></h4>
      </div>
    </div>
    <br>
    <div class="row">
      <div class="form-group col-sm-8 col-xs-8">
        <label for="">Nombre:</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="icon-pencil"></i> </span>
          <input type="text" class="form-control input-sm" value="<?php echo $row[1]; ?>" disabled>
        </div>
      </div>
      <div class="form-group col-sm-4 col-xs-4">
        <label for="">Importe:</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="icon-dollar"></i> </span>
          <input type="text" class="form-control input-sm" value="<?php echo $row[2]; ?>" disabled>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-4 col-xs-4">
        <label for="">Banco:</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="icon-home-1"></i> </span>
          <input type="text" class="form-control input-sm" value="<?php echo $row[3]; ?>" disabled>
        </div>
      </div>
      <div class="form-group col-sm-4 col-xs-4">
        <label for="">Cuenta</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="icon-credit-card"></i> </span>
          <input type="text" class="form-control input-sm" value="<?php echo $row[4]; ?>" disabled>
        </div>
      </div>
      <div class="form-group col-sm-4 col-xs-4">
        <label for="">Cheque</label>
        <div class="input-group">
          <span class="input-group-addon"><i class=" icon-dollar-1"></i> </span>
          <input type="text" class="form-control input-sm" value="<?php echo $row[5]; ?>" disabled>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-12 col-xs-12">
        <label for="">Concepto</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="icon-doc-text"></i> </span>
          <input type="text" class="form-control input-sm" value="<?php echo $row[6]; ?>" disabled>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-12 col-xs-12 text-center">
        <label for="">RECIBIÓ</label>
      </div>
      <div class="form-group col-sm-4 col-xs-4 text-center">
        <br>
        <span class="col-sm-12 col-xs-12" style="border-bottom:0.5px solid #000 ;"></span>
        <label for="">Nombre</label>
      </div>
      <div class="form-group col-sm-4 col-xs-4 text-center">
        <br>
        <span class="col-sm-12 col-xs-12" style="border-bottom:0.5px solid #000 ;"></span>
        <label for="">Firma</label>
      </div>
      <div class="form-group col-sm-4 col-xs-4 text-center">
        <br>
        <span class="col-sm-12 col-xs-12" style="border-bottom:0.5px solid #000 ;"></span>
        <label for="">Fecha</label>
      </div>
    </div>

    <hr>

    <div class="row">
      <div class="form-group col-sm-6 col-xs-6 text-center">
        <label for="">ELABORÓ</label>
        <br><br><br>
        <span class="col-sm-12 col-xs-12" style="border-bottom:0.5px solid #000 ;"></span>
      </div>
      <div class="form-group col-sm-6 col-xs-6 text-center">
        <label for="">AUTORIZÓ</label>
        <br><br><br>
        <span class="col-sm-12 col-xs-12" style="border-bottom:0.5px solid #000 ;"></span>
      </div>
    </div>

    <hr>

    <div class="row">
      <div class="form-group col-sm-12 col-xs-12 text-center">
          <label for="">CHEQUE ESCANEADO</label>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-sm-12 col-xs-12">
          <img src="<?php echo $ruta; ?>" width="100%" class="img-rounded center-block">
      </div>
    </div>

  </body>
</html>
