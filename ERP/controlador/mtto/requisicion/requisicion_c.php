<?php
  session_start();
  include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  include_once('../../../modelo/SQL_PHP/_Utileria.php');
  $fin = new Finanzas;
  $util = new Util;
  $idE = $_SESSION['udn'];
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://BUSCAR Y GUARDAR EQUIPO
        $ipt = strtr(strtoupper($_POST['ipt']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");

        $id = $fin->Select_idEquipo($ipt);//Buscar si existe el equipo
        if ( $id != 0) {
          $existe = $fin->Select_Equipo_Requisicion($id);
          if ( $existe == 0 ) {
            $fin->Insert_Requisicion_Temp($id);
          }
        }
        echo $id;
      break;
    case 1://GUARDAR REQUISICION
        $ipt = $_POST['ipt'];
        $cb = $_POST['cb'];
        $now = $fin->NOW();
        $retorno =  '0';

        $cont_actual = $fin->Select_Cont_Requisicion_actual(0);
        $cont_cantidad = $fin->Select_Cont_Requisicion_actual(1);
        if ( $cont_actual == $cont_cantidad ) {
          $cont_destino = $fin->Select_Cont_Requisicion_actual(2);
          if ( $cont_actual == $cont_destino ) {
            $cont_justificacion = $fin->Select_Cont_Requisicion_actual(3);
            if ( $cont_actual ==  $cont_justificacion ) {
              $fol = $fin->Folio_Req('');
              $array = array($fol,$now,$cb,$ipt);

              $fin->Insert_Requisicion($array);
              $folio = $fin->convert_folio($fol,'R');
              $retorno = $folio;
            }
          }
        }

        echo $retorno;
      break;
    case 2://REMOVER EQUIPO
        $idA = $_POST['idTbR'];
        $fin->Delete_ReqTemp($idA);
      break;
    case 3://UPDATE CANTIDAD
        $ipt = $_POST['ipt'];
        $id = $_POST['id'];
        $array = array($ipt,$id);

        $fin->Update_Cantidad_TbRequisicion($array);

        echo $ipt;
      break;
    case 4://UPDATE PRESENTACION
        $ipt = $_POST['ipt'];
        $id = $_POST['id'];
        $array = array($ipt,$id);

        $fin->Update_Presentacion_TbRequisicion($array);

        echo $ipt;
      break;
    case 5://UPDATE DESTINO
        $ipt = $_POST['ipt'];
        $id = $_POST['id'];
        $array = array($ipt,$id);

        $fin->Update_Destino_TbRequisicion($array);

        echo $ipt;
      break;
    case 6://CREAR EL COMBOBOX DE JUSTIFICACION
        $id = $_POST['id'];
        $sql = $fin->Select_ComboBox_Justificacion();
        echo '<select class="form-control input-sm" id="CB_'.$id.'" onChange="Convert_label_CB(\'Just\','.$id.');">
              <option value="0">Seleccionar </option>';
        foreach ($sql as $row) {
          echo '<option value="'.$row[0].'">'.$row[1].'</option>';
        }
        echo '</select>';
      break;
    case 7://UPDATE JUSTIFICACION
        $id = $_POST['id'];
        $idJ = $_POST['idJ'];
        $array = array($idJ,$id);
        $fin->Update_Justificacion_TbRequisicion($array);
        $name = $fin->Select_Name_CB($idJ);
        echo '<label class="pointer" style=" width:100%; height:30px; paddin:5px;" onClick="Convert_CB(\'Just\','.$id.');">'.$name.'</label>';
      break;
    case 8://AGREAGAR_EQUIPO
        $ipt = strtr(strtoupper($_POST['ipt']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");

        $id = $fin->Select_idEquipo($ipt);//Buscar si existe el equipo
        if ( $id == 0) {
          $fin->Insert_Equipo($ipt);
        }

      break;
  }
?>
