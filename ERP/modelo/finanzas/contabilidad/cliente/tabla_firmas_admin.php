<?php
  session_start();

  include_once("../../../SQL_PHP/_CRUD.php");
  include_once("../../../SQL_PHP/_Finanzas.php");
  include_once("../../../SQL_PHP/_Utileria.php");

  //Se declaran los utiletos para utilizar las funciones segun los archivos externos
  $crud = new CRUD;
  $util = new Util;
  $fin = new Finanzas;

  $idE = $_SESSION['empresa'];

  /*TRATAMIENTO - PAGINACIÓN -SQL */
  $sqlCount = "SELECT idFirma FROM firmas WHERE id_UDN = ".$idE;
  // echo $sqlCount.'<br><br>';
  $noS[0] = null;
  $nroSol = $crud->_Select($sqlCount,null,"1");
  foreach ($nroSol as $num => $noS);
  if(!isset($noS[0])){ $noS[0] = 0; }
  /***************************************************
              VARIABLES / PAGINACIÓN
  ****************************************************/
  $paginaActual = $_POST['pag'];
  $Paginas = $noS[0];
  $url= "ver_firmas";
  $Lotes = 20;
  $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

  // echo $pag;
  if($paginaActual <= 1 ){
      $limit=0;
  }
  else{
      $limit = $Lotes*($paginaActual-1);
  }

  /*********************************************
        RELLENADO DE LA TABLA
  *********************************************/
  //Consulta de la tabla
  $query = "SELECT idFirma,Nombre,Alias,cifrada FROM firmas WHERE id_UDN = ".$idE." LIMIT $limit, $Lotes";
  // echo $query;
  $sql = $crud->_Select($query,null,"1");
?>

<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 col-sm-offset-4 text-center">
    <div id="tb_ver_firmas"></div>
  </div>
  <div class="col-sm-4 col-xs-12 text-right">
    <h5><label><?php echo "Registros: ".$Paginas; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class = "col-sm-7"><strong>Nombre Completo</strong></td>
                  <td class = "col-sm-2"><strong>Alias</strong></td>
                  <td class = "col-sm-2"><strong>Firma Cifrada</strong></td>
                  <td class = "col-sm-1"><strong>Eliminar</strong></td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
            ?>
              <tr>
                <td class='col-sm-8 col-xs-3' id="Name<?php echo $id; ?>">
                  <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('Name','<?php echo $id; ?>','<?php echo $data[1]; ?>');"><?php echo $data[1];?></label>
                </td>
                <td class='col-sm-2 col-xs-3' id="alias<?php echo $id; ?>">
                  <label class="form-control text-center input-sm label-form pointer" onClick="Convertir_input('alias','<?php echo $id; ?>','<?php echo $data[2]; ?>');"><?php echo $data[2]; ?></label>
                </td>
                <td class='col-sm-2 col-xs-3' id="cifrado<?php echo $id; ?>">
                  <label class="form-control text-center input-sm label-form pointer" onClick="Convertir_input('cifrado','<?php echo $id; ?>','<?php echo $data[3]; ?>');">* * * * * * * *</label>
                </td>
                <td class="col-sm-2 col-xs-1">
                  <button type="button" title="Eliminar" class="btn btn-danger btn-xs col-xs-12 col-sm-12" data-toggle='modal' data-target='#Modal_TeSobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_datos_ventas.php?opc=Fim&id=<?php echo $id;?>','TeSobre_Modal');"><span class="icon-cancel"></span></button>
                </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
