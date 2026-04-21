<?php
  session_start();
  include_once('../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  $fin = new Finanzas;

  $id = 0;

  if ($_GET['geto'] != 'u') {
    $id = $_GET['geto'];
  }
  else {
    $id = $fin->Select_Ultimo_idReq();
  }

  $sql = $fin->Select_Requisicion_id($id);
  foreach($sql as $row);
  $folio = $fin->convert_folio($row[1],'R');
  $cont = $fin->Select_Count_TbRequisicion_id($id);
  $sql = $fin->Select_TbRequisicion_id($id);
?>

<!DOCTYPE html>
<html>
  <head>
     <meta charset="utf-8">
     <title>IMPRIMIR</title>

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

      <br>
      <div class="row form-group">
        <div class="col-xs-4 ">
          <img src="http://www.argovia.com.mx/img/logo.png" width="150px" class="img-rounded center-block">
        </div>

        <div class="col-xs-4 text-center">
          <h4 class="">Diversificados Argovia S.A. de C.V</h4 >
        </div>

        <div class="col-xs-4 text-right">
          <h4 class="col-sm-12"><strong><?php echo $row[0]; ?></strong></h4>
        </div>
      </div>
      <br>
      <div class="row form-group">
        <div class="form-group">
          <div class="col-sm-12 col-xs-12 text-center">
            <label><strong>FORMATO DE REQUISICIÓN DE MATERIALES</strong> </label>            
          </div>
        </div>
      </div>
      <br>
      <div class="row form-group">
        <div class="form-group">
          <div class="col-sm-10 col-xs-10">
            <label class="control-label" for="Obs_Req">Zona: <?php echo $row[2]; ?></label>
          </div>
          <div class="col-sm-2 col-xs-2 text-right">
            <label class="control-label" for="Obs_Req">Folio: <?php echo $folio; ?></label>
          </div>
        </div>
      </div>
      <div class="row form-group">
          <div class="col-sm-12 col-xs-12 txt_obs">
            <label class="control-label" for="Obs_Req">Observaciones</label>
            <textarea style="resize:none;" class="form-control input-sm " id="Obs_Req" rows="4" cols="80"><?php echo $row[3]; ?></textarea>
          </div>
      </div>
      <div class="row form-group">
        <div class="col-sm-12 col-xs-12 text-right">
          <label>Registros: <?php echo $cont; ?></label>
        </div>
        <div class="col-sm-12 col-xs-12">
          <table class="table table-responsive table-bordered table-stripped table-condensed">
            <thead>
              <tr>
                <th class="text-center"><strong>#</strong></th>
                <th class="text-center"><strong>NOMBRE</strong></th>
                <th class="text-center"><strong>CANTIDAD</strong></th>
                <th class="text-center"><strong>PRESENTACIÓN</strong></th>
                <th class="text-center"><strong>DESTINO</strong></th>
                <th class="text-center"><strong>JUSTIFICACIÓN</strong></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($sql as $key => $value) { ?>
                <tr>
                  <td class="text-center"><?php echo $key+1; ?></td>
                  <td><?php echo $value[0]; ?></td>
                  <td class="text-center"><?php echo $value[1]; ?></td>
                  <td><?php echo $value[2]; ?></td>
                  <td><?php echo $value[3]; ?></td>
                  <td><?php echo $value[4]; ?></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <br><br>
      <div class="row form-group">
        <div class="col-sm-3 col-xs-3 text-center">
          <label>VO.BO</label>
        </div>
        <div class="col-sm-3 col-xs-3 text-center">
          <label>AUTORIZÓ</label>
        </div>
        <div class="col-sm-3 col-xs-3 text-center">
          <label>RECIBIÓ</label>
        </div>
        <div class="col-sm-3 col-xs-3 text-center">
          <label>ENVIÓ</label>
        </div>
      </div>
      <br>
      <div class="row form-group">
        <div class="col-sm-3 col-xs-3">
          <label class="col-sm-12 col-xs-12" style="border-bottom:1px solid;"></label>
        </div>
        <div class="col-sm-3 col-xs-3">
          <label class="col-sm-12 col-xs-12" style="border-bottom:1px solid;"></label>
        </div>
        <div class="col-sm-3 col-xs-3">
          <label class="col-sm-12 col-xs-12" style="border-bottom:1px solid;"></label>
        </div>
        <div class="col-sm-3 col-xs-3">
          <label class="col-sm-12 col-xs-12" style="border-bottom:1px solid;"></label>
        </div>
      </div>
  </body>
</html>
