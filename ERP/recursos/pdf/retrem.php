<?php
  session_start();
  include_once('../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  $fin = new Finanzas;

  $idE = $_SESSION['udn'];
  $date = $_GET['date'];
  $date_hoy = $fin->NOW();

  $Obs = $fin->Select_Obs_Rem($idE,$date);

  //comprobar si existe un retiro de efectivo entre la fecha obtenida por tesoreria y la fecha de hoy
  //esto con el fin de bloquear o desbloquear los input-text de retiros
  $disabled = $fin->Select_Comprobacion_Retiro_Efectivo($idE,$date,$date_hoy);
  if( $disabled != 0 ) { $disabled = 1; }

  //obtener la fecha de retiro anterior a la fecha dada por tesoreria

  $sql = $fin->Select_SI_Retiro_Efectivo($idE,$date);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = null; } //Fecha de Saldo Inicial
  if ( !isset($row[1]) ) { $row[1] = 0; }    //Saldo Final Total
  if ( !isset($row[2]) ) { $row[2] = 0; }    //Saldo Final Efectivo
  if ( !isset($row[3]) ) { $row[3] = 0; }    //id Retiro Venta

  $SI_Temp = $row[1]; //Saldo Final Total del anterior retiro
  $SI_Efectivo_Temp = $row[2];//Saldo Final Efectivo del anterior retiro

  $Efectivo_Anterior = $fin->Select_Efectivo_Anterior($idE,$row[0],$date);
  $SI_Efectivo = $SI_Efectivo_Temp + $Efectivo_Anterior;
  $Efectivo_Actual = $fin->Select_Efectivo_Actual($idE,$date);
  $Efectivo_Retiro = $fin->Select_Efectivo_Retiro($idE,$date);
  $SF_Efectivo = ($SI_Efectivo - $Efectivo_Retiro) +  $Efectivo_Actual;

  //PROPINA
  $SI_Propina_Temp = $fin->Select_Propina_SI($idE,$row[0],$date,9);
  $SI_Propina_Anterior = $fin->Select_Propina_Anterior($idE,$row[0],$date,9);
  $SI_Propina = $SI_Propina_Anterior + $SI_Propina_Temp;
  $Propina_Actual = $fin->Select_Propina_Actual($idE,$date,9);
  $Propina_Retiro = $fin->Select_Retiro_PropActual($idE,$date,9);
  $SF_Propina = $SI_Propina + $Propina_Actual - $Propina_Retiro;

  //TOTAL
  $SI_Total = $SI_Efectivo + $SI_Propina;
  $Total_Hoy = $Efectivo_Actual + $Propina_Actual;
  $Total_Retiro = $Efectivo_Retiro + $Propina_Retiro;
  $SF_Total = $SF_Efectivo + $SF_Propina;


  // REMBOLSOS DE FONDO DE CAJA
  // Obtener la fecha y el saldo final del anterior rembolso
  $rem_row = null;
  $sql = $fin->Select_FechaRembolso_Remaster($idE,$date);
  foreach($sql as $rem_row);
  // $rem_row[0]; //Fecha rembolso
  if ( !isset($rem_row[0]) ) { $rem_row[0] = 0; }//Saldo Inicial
  if ( !isset($rem_row[1]) ) { $rem_row[1] = 0; } //SF del rembolso anterior
  $SI_Rembolso = $rem_row[1];

  //Comprobar si existe un rembolso realizado al día de hoy
  $disabled_rem = $fin->Select_ExisteRembolso_Remaste($idE,$rem_row[0],$date_hoy);
  if ( $disabled_rem != 0 ) { $disabled_rem = 1; }

  //Sumatoria de Gastos de Fondo
  $Gastos_Fondo = $fin->Select_GastosRembolso_Remaster($idE,$rem_row[0],$date);
  //Sumatoria de ANTICIPOS
  $Anticipos_Rembolso = 0;
  //Sumatoria de Pago de Proveedor
  $Proveedor_Rembolso = $fin->Select_ProveedorRembolso_Remaster($idE,$rem_row[0],$date);

  //Rembolso Sugerido
  $Rembolso_Sugerido = $Gastos_Fondo + $Anticipos_Rembolso + $Proveedor_Rembolso;

  //Rembolso ACTUAL
  $Rembolso_Actual = $fin->Select_RembolsoFondo_Remaste($idE,$date);

  //Saldo Final de Rembolso
  $SF_Rembolso = $SI_Rembolso - $Rembolso_Sugerido + $Rembolso_Actual;

  if ( $SI_Efectivo == 0 ) { $SI_Efectivo = '-'; } else { $SI_Efectivo = number_format($SI_Efectivo,2,'.',','); }
  if ( $Efectivo_Actual == 0 ) { $Efectivo_Actual = '-'; } else { $Efectivo_Actual = number_format($Efectivo_Actual,2,'.',','); }
  if ( $Efectivo_Retiro == 0 ) { $Efectivo_Retiro = '-'; } else { $Efectivo_Retiro = number_format($Efectivo_Retiro,2,'.',','); }
  if ( $SF_Efectivo == 0 ) { $SF_Efectivo = '-'; } else { $SF_Efectivo = number_format($SF_Efectivo,2,'.',','); }

  if ( $SI_Propina == 0 ) { $SI_Propina = '-'; } else { $SI_Propina = number_format($SI_Propina,2,'.',','); }
  if ( $Propina_Actual == 0 ) { $Propina_Actual = '-'; } else { $Propina_Actual = number_format($Propina_Actual,2,'.',','); }
  if ( $Propina_Retiro == 0 ) { $Propina_Retiro = '-'; } else { $Propina_Retiro = number_format($Propina_Retiro,2,'.',','); }
  if ( $SF_Propina == 0 ) { $SF_Propina = '-'; } else { $SF_Propina = number_format($SF_Propina,2,'.',','); }

  if ( $SI_Total == 0 ) { $SI_Total = '-'; } else { $SI_Total = number_format($SI_Total,2,'.',','); }
  if ( $Total_Hoy == 0 ) { $Total_Hoy = '-'; } else { $Total_Hoy = number_format($Total_Hoy,2,'.',','); }
  if ( $Total_Retiro == 0 ) { $Total_Retiro = '-'; } else { $Total_Retiro = number_format($Total_Retiro,2,'.',','); }
  if ( $SF_Total == 0 ) { $SF_Total = '-'; } else { $SF_Total = number_format($SF_Total,2,'.',','); }

  if ( $SI_Rembolso == 0 ) { $SI_Rembolso = '-'; } else { $SI_Rembolso = number_format($SI_Rembolso,2,'.',','); }
  if ( $Gastos_Fondo == 0 ) { $Gastos_Fondo = '-'; } else { $Gastos_Fondo = number_format($Gastos_Fondo,2,'.',','); }
  if ( $Anticipos_Rembolso == 0 ) { $Anticipos_Rembolso = '-'; } else { $Anticipos_Rembolso = number_format($Anticipos_Rembolso,2,'.',','); }
  if ( $Proveedor_Rembolso == 0 ) { $Proveedor_Rembolso = '-'; } else { $Proveedor_Rembolso = number_format($Proveedor_Rembolso,2,'.',','); }
  if ( $Rembolso_Sugerido == 0 ) { $Rembolso_Sugerido = '-'; } else { $Rembolso_Sugerido = number_format($Rembolso_Sugerido,2,'.',','); }
  if ( $Rembolso_Actual == 0 ) { $Rembolso_Actual = '-'; } else { $Rembolso_Actual = number_format($Rembolso_Actual,2,'.',','); }
  if ( $SF_Rembolso == 0 ) { $SF_Rembolso = '-'; } else { $SF_Rembolso = number_format($SF_Rembolso,2,'.',','); }

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

-
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
    <div class="container">
      <div class="panel panel-default">
        <div class="panel-body">

          <br>
          <div class="row">
            <div class="col-xs-4 ">
              <img src="http://www.argovia.com.mx/img/logo.png" width="150px" class="img-rounded center-block">
            </div>

            <div class="col-xs-4 text-center">
              <h4 class="">Diversificados Argovia S.A. de C.V</h4 >
            </div>

            <div class="col-xs-4 text-right">
              <h4 class="col-sm-12"><strong> <?php echo $date; ?> </strong></h4>
            </div>
          </div>
          <br>

          <label class="col-xs-12 col-sm-12 form-control text-center" ><span class="icon-money"></span>Retiro de Efectivo</label>


          <!-- ./Efectivo -->
          <div class="row">
            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label>Acumulado Ventas</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SI_Efect" value="<?php echo $SI_Efectivo; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Saldo de Ventas hoy</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SH_Efect" value="<?php echo $Efectivo_Actual; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Retiro Ventas</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="Ret_Efect" value="<?php echo $Efectivo_Retiro; ?>" onKeyUp="retiro_ventas();" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Saldo Final Ventas</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SF_Efect" value="<?php echo $SF_Efectivo; ?>" disabled>
                </div>
              </div>
            </div>
          </div>
          <div class="row text-center Res_Efect"></div>
          <!-- .\Efectivo -->

          <!-- ./Propina -->
          <div class="row">
            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label>Acumulado Propina</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SI_Prop" value="<?php echo $SI_Propina; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Saldo de Propina hoy</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SH_Prop" value="<?php echo $Propina_Actual; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Retiro Propina</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="Ret_Prop" value="<?php echo $Propina_Retiro; ?>" onKeyUp="retiro_ventas();" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Saldo Final Propina</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SF_Prop" value="<?php echo $SF_Propina; ?>" disabled>
                </div>
              </div>
            </div>
          </div>
          <div class="row text-center Res_Prop"></div>
          <!-- .\Propina -->

          <!-- ./Total -->
          <div class="row">
            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label>Acumulado Total</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SI_Total" value="<?php echo $SI_Total; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Saldo Total hoy</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SH_Total" value="<?php echo $Total_Hoy; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Retiro Total</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="Retiro_Total" value="<?php echo $Total_Retiro; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <div class="col-sm-12 col-xs-12">
                <label> Saldo Final Total</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SF_Total" value="<?php echo $SF_Total; ?>" disabled>
                </div>
              </div>
            </div>
          </div>
          <!-- ./Total -->

          <!-- REMBOLSO -->
          <label class="col-xs-12 col-sm-12 form-control text-center" ><span class="icon-money"></span>Reembolso de Fondo</label>

          <div class="row">
            <div class="form-group col-sm-3 col-xs-3">
              <label class="col-sm-12 col-xs-12"><strong>Gastos Fondo</strong></label>
              <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                  <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="TG" value="<?php echo $SI_Rembolso; ?>" disabled>
                </div>
              </div>
            </div>
            <!-- <div class="form-group col-sm-4 col-xs-12">
              <label class="col-sm-12 col-xs-12"><strong>Anticipos</strong></label>
              <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                  <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="TA" value="<?php echo $Gastos_Fondo; ?>" disabled>
                </div>
              </div>
            </div> -->
            <div class="form-group col-sm-3 col-xs-3">
              <label class="col-sm-12 col-xs-12"><strong>Pagos de Proveedor</strong></label>
              <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                  <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="TP" value="<?php echo $Anticipos_Rembolso; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-6 col-xs-6">
              <label class="col-sm-12 col-xs-12"><strong>Observaciones</strong></label>
              <div class="col-xs-12 col-sm-12">
                <textarea class="form-control input-sm" style="resize:none;" rows="2" id="Obs_Rem" disabled><?php echo $Obs; ?></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-sm-3 col-xs-4">
              <label class="col-sm-12 col-xs-12"><strong>Saldo Inicial
              </strong></label>
              <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                  <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SI_Reem" value="<?php echo $Proveedor_Rembolso; ?>" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <label class="col-sm-12 col-xs-12"><strong>Reembolso Sugerido</strong></label>
              <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                  <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="Reem_Sug"  value="<?php echo $Rembolso_Sugerido; ?>" onKeyUp="Moneda('NSI');" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <label class="col-sm-12 col-xs-12"><strong>Reembolso</strong></label>
              <div class="col-xs-12 col-sm-12">
                <div class="input-group">
                  <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="Reem" value="<?php echo $Rembolso_Actual; ?>" onKeyUp = "Calculo_Reembolso();" disabled>
                </div>
              </div>
            </div>

            <div class="form-group col-sm-3 col-xs-4">
              <label class="col-sm-12 col-xs-12"><strong>Saldo Final</strong></label>
              <div class="col-xs-12 col-sm-12">
                <input type="hidden" id="SF_Temp" value="">
                <div class="input-group">
                  <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
                  <input type="text" class="form-control input-sm" id="SF_Reem" value="<?php echo $SF_Rembolso; ?>" disabled>
                </div>
              </div>
            </div>
          </div>
          <br>
          <br>
          <br>
          <div class="row">
            <div class="form-group col-sm-6 col-xs-6 text-center">
              <label class="col-sm-12 col-xs-12" style="border-top:1px solid #000;">Nombre y Firma</label>
              <label class="col-sm-12 col-xs-12">Entrego</label>
            </div>
            <div class="form-group col-sm-6 col-xs-6 text-center">
              <label class="col-sm-12 col-xs-12" style="border-top:1px solid #000;">Nombre y Firma</label>
              <label class="col-sm-12 col-xs-12">Recibio</label>
            </div>
          </div>

        </div>
      </div>
    </div>
  </body>
</html>
