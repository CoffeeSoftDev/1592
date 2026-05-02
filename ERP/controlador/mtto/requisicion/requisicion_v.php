<?php
  session_start();
  include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
  include_once('../../../modelo/SQL_PHP/_Utileria.php');
  $fin = new Finanzas;
  $util = new Util;
  $idE = $_SESSION['udn'];
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://AUTOCOMPLETE
      $sql = $fin->Select_Equipo();
      $res = array();
      foreach ($sql as $key => $value) {
        $res[$key] = $value[0];
      }
      echo json_encode($res);
      break;
    case 1://TABLA
      $caso = $_POST['caso'];
      $cont = $fin->Select_Count_Requisicion_Temp();
      $sql = $fin->Select_Requisicion_Temp();
      $bd_cantidad = ''; $bd_destino = ''; $bd_justificacion = '';


      switch ($caso) {
        case 1:
          $cont_actual = $fin->Select_Cont_Requisicion_actual(0);
          $cont_cantidad = $fin->Select_Cont_Requisicion_actual(1);
          $cont_destino = $fin->Select_Cont_Requisicion_actual(2);
          $cont_justificacion = $fin->Select_Cont_Requisicion_actual(3);
          if ( $cont_actual != $cont_cantidad ) { $bd_cantidad = 'bg-danger'; }
          if ( $cont_actual != $cont_destino ) { $bd_destino = 'bg-danger'; }
          if ( $cont_actual != $cont_justificacion ) { $bd_justificacion = 'bg-danger'; }

          break;
        default:
          $bd_cantidad = ''; $bd_destino = ''; $bd_justificacion = '';
          break;
      }

      echo '
        <div class="col-sm-6 col-xs-12 ERROR"></div>
        <div class="col-sm-6 col-xs-12 text-right">
          <label>Cantidad de registros: <strong>'.$cont.'</strong> </label>
        </div>
        <table class="table table-striped table-hover table-bordered table-responsive table-condensed">
           <thead>
              <tr>
                 <th class="text-center '.$bd_cantidad.'"><span class="icon-pencil"></span> CANTIDAD</th>
                 <th class="text-center">NOMBRE</th>
                 <th class="text-center"><span class="icon-pencil"></span> PRESENTACIÓN</th>
                 <th class="text-center '.$bd_destino.'"><span class="icon-pencil"></span> DESTINO</th>
                 <th class="text-center '.$bd_justificacion.'"><span class="icon-pencil"></span> JUSTIFICACIÓN</th>
                 <th class="text-center">REMOVER</th>
              </tr>
           </thead>
           <tbody>';
              $span_c = ''; $span_d = ''; $span_j = ''; $value = null;
              foreach ($sql as $value) {
                if ( $caso == 1) {
                  if ( !isset($value[3]) ) { $span_c = '<span class="icon-attention text-danger"></span>'; } else { $span_c = ''; }
                  if ( !isset($value[2]) ) { $span_d = '<span class="icon-attention text-danger"></span>'; } else { $span_d = ''; }
                  if ( !isset($value[5]) ) { $span_j = '<span class="icon-attention text-danger"></span>'; } else { $span_j = ''; }
                }
                echo
                '<tr>
                   <td style="padding:5px 5px 0px 5px;" id="td_Cant'.$value[0].'">
                      <label class="pointer text-center" style=" width:100%; height:30px; padding:5px;" onClick="Convert_Input(\'Cant\','.$value[0].',\''.$value[3].'\',3);">'.$span_c.''.$value[3].'</label>
                   </td>
                   <td>'.$value[1].'</td>
                   <td style="padding:5px 5px 0px 5px;" id="td_Pres'.$value[0].'">
                      <label class="pointer" style=" width:100%; height:30px; padding:5px;" onClick="Convert_Input(\'Pres\','.$value[0].',\''.$value[4].'\',4);">'.$value[4].'</label>
                   </td>
                   <td style="padding:5px 5px 0px 5px;" id="td_Dest'.$value[0].'">
                      <label class="pointer" style=" width:100%; height:30px; padding:5px;" onClick="Convert_Input(\'Dest\','.$value[0].',\''.$value[2].'\',5);">'.$span_d.''.$value[2].'</label>
                   </td>
                   <td style="padding:5px 5px 0px 5px;" id="td_Just'.$value[0].'">';
                      $name = $fin->Select_Name_CB($value[5]);
                      echo
                      '<label class="pointer" style=" width:100%; height:30px; padding:5px;" onClick="Convert_CB(\'Just\','.$value[0].');">'.$span_j.''.$name.'</label>';
                  echo
                   '</td>
                   <td class="text-center">
                     <button type="button" class="btn btn-danger btn-xs" onclick="Remover_Equipo('.$value[0].');">
                        <span class="icon-cancel"></span>
                     </button>
                   </td>
                </tr>';
              }
            echo
           '</tbody>
        </table>
      ';
      break;
    case 2: //TABLA REQUISICIONES PRINT
          /*TRATAMIENTO - PAGINACIÓN -SQL */
          $mes = $_POST['Mes'];
          $year = $_POST['Year'];
          // echo $mes.' - '.$year;

          $cont_req = $fin->Select_Cont_requsicion($mes,$year);

          /***************************************************
                      VARIABLES / PAGINACIÓN
          ****************************************************/
          $paginaActual = $_POST['pag'];
          $Paginas= $cont_req;
          $url= "tb_Requisiciones_printer";
          $Lotes = 10;
          $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

          echo $pag;
          if($paginaActual <= 1 ){
              $limit=0;
          }
          else{
              $limit = $Lotes*($paginaActual-1);
          }

          //BUSQUEDA DE DATOS
          $sql = $fin->Select_Tb_Requisiciones($mes,$year,$limit,$Lotes);

        echo '
          <div class="text-right col-sm-12 col-xs-12">
            <label>Requisiciones: #'.$cont_req.'</label>
          </div>
          <div class="col-sm-12 col-xs-12">
            <table class="table table-bordered table-stripped table-condensed table-hover table-responsive">
              <thead class="bg-info">
                <tr>
                <th class="text-center"><strong>ZONA</strong></th>
                  <th class="text-center"><strong>FOLIO</strong></th>
                  <th class="text-center"><strong>FECHA</strong></th>
                  <th class="text-center"><strong>IMPRIMIR</strong></th>
                </tr>
              </thead>
              <tbody>';
                foreach ($sql as $row) {
                  $folio = $fin->convert_folio($row[2],'R');
                  echo
                  '<tr>
                    <td>'.$row[1].'</td>
                    <td class="text-center">'.$folio.'</td>
                    <td class="text-center">'.$row[3].'</td>
                    <td class="text-center">
                      <button title="Imprimir" onClick="Print_requisicion('.$row[0].');" class="btn btn-xs btn-default"><span class="icon-print"></span></button>
                    </td>
                  </tr>';
                }
              echo
              '</tbody>
            </table>
          </div>
        ';
      break;
  }
?>
