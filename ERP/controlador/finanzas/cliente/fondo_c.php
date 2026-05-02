<?php
  session_start();
  include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  $fin = new Finanzas;

  $idE = $_SESSION['udn'];
  $opc = $_POST['opc'];
  $date_now = $fin->NOW();

  switch ($opc) {
    case 0://Login
      $pass = $_POST['pass'];

      $res = $fin->Select_LoginFinanzas($pass);
      if ( $res != 0) { $res = 1; }

      echo $res;
      break;


    case 1://RELLENAR RETIRO & REEMBOLSOS

    /* ------------------------------------*/
    /*   RETIROS - MODULO
    /* ------------------------------------*/
      //variables de fecha obtenida de tesoreria y la fecha de hoy
      $date     = $_POST['date'];
      $date_hoy = $fin->NOW();
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

      /* ------------------------------------*/
      /* REMBOLSOS DE FONDO DE CAJA
      /* ------------------------------------*/

      // Obtener la fecha y el saldo final del anterior rembolso
       $dia_futuro  = date("Y-m-d",strtotime($date."+ 1 days"));
      $rem_row      = null;
      $sql          = $fin->_FechaRembolso_Remaster($idE,$dia_futuro);
      foreach ($sql as $rem_row);
      // $rem_row[0]; //Fecha rembolso
      if ( !isset($rem_row[0]) ) { $rem_row[0] = 0; }//Saldo Inicial
      if ( !isset($rem_row[1]) ) { $rem_row[1] = 0; } //SF del rembolso anterior
      $SI_Rembolso = $rem_row[1];

      //Comprobar si existe un rembolso realizado al día de hoy
      $disabled_rem = $fin->Select_ExisteRembolso_Remaste($idE,$rem_row[0],$date_hoy);
      if ( $disabled_rem != 0 ) { $disabled_rem = 1; }

      //Sumatoria de Gastos de Fondo
      $Gastos_Fondo = $fin->Select_GastosRembolso_Remaster($idE,$rem_row[0],$dia_futuro);
      //Sumatoria de ANTICIPOS
      $Anticipos_Rembolso = 0;
      //Sumatoria de Pago de Proveedor
      $Proveedor_Rembolso = $fin->Select_ProveedorRembolso_Remaster($idE,$rem_row[0],$date);



      //Rembolso Sugerido
      $Rembolso_Sugerido = $Gastos_Fondo + $Anticipos_Rembolso + $Proveedor_Rembolso;

      //Rembolso ACTUAL
      $Rembolso_Actual   = $fin->Select_RembolsoFondo_Remaste($idE,$date);
      //OBSERVACIONES
      $Obs = $fin->Select_Obs_Rem($idE,$date);

      //Saldo Final de Rembolso
      // $SF_Rembolso = $SI_Rembolso - $Rembolso_Sugerido + $Rembolso_Actual;


      $SF_Rembolso = $SI_Rembolso - $Gastos_Fondo;




      $resultado = array (
        $disabled,
        $disabled_rem,

        number_format($SI_Rembolso,2,'.',','),
        number_format($Gastos_Fondo,2,'.',','),
        number_format($Anticipos_Rembolso,2,'.',','),
        number_format($Proveedor_Rembolso,2,'.',','),
        number_format($Rembolso_Sugerido,2,'.',','),
        number_format($Rembolso_Actual,2,'.',','),
        number_format($SF_Rembolso,2,'.',','),

        number_format($SI_Efectivo,2,'.',','),
        number_format($Efectivo_Actual,2,'.',','),
        number_format($Efectivo_Retiro,2,'.',','),
        number_format($SF_Efectivo,2,'.',','),

        number_format($SI_Propina,2,'.',','),
        number_format($Propina_Actual,2,'.',','),
        number_format($Propina_Retiro,2,'.',','),
        number_format($SF_Propina,2,'.',','),

        number_format($SI_Total,2,'.',','),
        number_format($Total_Hoy,2,'.',','),
        number_format($Total_Retiro,2,'.',','),
        number_format($SF_Total,2,'.',','),
        $Obs,
        $row[3]
      );

      echo json_encode($resultado);
      // echo $rem_row[0];
      break;



    case 2://CALCULAR RETIRO
      $SI_Efect = $_POST['SI_Efect'];
      $Ret_Efect = $_POST['Ret_Efect'];
      $SH_Efect = $_POST['SH_Efect'];
      $SF_Efectivo = $SI_Efect - $Ret_Efect;
      $ok_efect = 0;
      if ( $SF_Efectivo < 0 ) { $ok_efect = 1; }


      $SI_Prop = $_POST['SI_Prop'];
      $Ret_Prop = $_POST['Ret_Prop'];
      $SH_Prop = $_POST['SH_Prop'];
      $SF_Prop = $SI_Prop - $Ret_Prop;
      $ok_prop = 0;
      if ( $SF_Prop < 0 ) { $ok_prop = 1; }

      $SF_Efectivo = $SI_Efect + $SH_Efect - $Ret_Efect;
      $SF_Prop = $SI_Prop + $SH_Prop - $Ret_Prop;
      $Ret_Total = $Ret_Efect + $Ret_Prop;
      $SF_Total = $SF_Efectivo + $SF_Prop;

      $res = array(
        $ok_efect,
        $ok_prop,
        number_format($SF_Efectivo,2,'.',','),
        number_format($SF_Prop,2,'.',','),
        number_format($Ret_Total,2,'.',','),
        number_format($SF_Total,2,'.',',')
      );

      echo json_encode($res);

      break;
    case 3://GUARDAR RETIRO
      $pass = $_POST['pass'];
      $date = $_POST['date'];

      $respuesta = '';
      $res = $fin->Select_LoginFinanzas($pass);
      if ( $res != 0) {//contraseña correcta
        $SI_Efect = $_POST['SI_Efect'];
        $Ret_Efect = $_POST['Ret_Efect'];
        $SF_EFect = $SI_Efect - $Ret_Efect;

        $SI_Prop = $_POST['SI_Prop'];
        $Ret_Prop = $_POST['Ret_Prop'];
        $SF_Prop = $SI_Prop - $Ret_Prop;

        $SI_Total = $SI_Efect + $SI_Prop;
        $Ret_Total = $Ret_Efect + $Ret_Prop;
        $SF_Total = $SF_EFect + $SF_Prop;

        if ( $SF_Total < 0 ) {
          $respuesta = 1;
        }
        else if ( $Ret_Total == 0 ) {
          $respuesta = 2;
        }
        else {

          //INSERTAR RETIRO EFECTIVO
          $array = array($idE,$SI_Total,$Ret_Total,$SF_Total,$SI_Efect,$Ret_Efect,$SF_EFect,$date);
          $fin->Insert_Retiro_Venta($array);

          $array_r = array($idE,$date);
          $idRE = $fin->Select_IdRetiroVenta($array_r);

          $array_c = array($idRE,9,$SI_Prop,$Ret_Prop,$SF_Prop);
          $fin->Insert_Retiro_Categoria($array_c);
          $respuesta = 'YA';
        }
      }
      else {
        $respuesta = 0;
      }

      echo $respuesta;
      break;
    case 4://GUARDAR REEMBOLSO
      $pass = $_POST['pass'];
      $respuesta = '';
      $res = $fin->Select_LoginFinanzas($pass);
      if ( $res != 0) {//contraseña correcta

        $date = $_POST['date'];
        $gasto = $_POST['gasto'];
        $obs_Rem = $_POST['obs_Rem'];
        $prov = $_POST['prov'];
        $si = $_POST['si_reem'];
        $reem = $_POST['reem'];
        $sf = $_POST['sf_reem'];

        $array = array($idE,$gasto,0,$prov,$si,$reem,$sf,$date,$obs_Rem);
        $fin->Insert_Reembolso($array);
        $respuesta = 'YA';
      }
      else {
        $respuesta = 0;
      }
      echo $respuesta;
      break;
    case 5: //SALDO FINAL REMBOLSO
      $gasto = $_POST['gasto'];
      $prov = $_POST['prov'];
      $si_reem = $_POST['si_reem'];
      $reem = $_POST['reem'];

      $SF = $si_reem -$gasto - $prov + $reem;
      echo number_format($SF,2,'.',',');
      break;
  }
?>
