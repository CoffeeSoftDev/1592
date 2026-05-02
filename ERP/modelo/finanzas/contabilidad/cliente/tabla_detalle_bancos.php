<?php
  session_start();

  include_once("../../../SQL_PHP/_CRUD.php");
  include_once("../../../SQL_PHP/_Finanzas.php");
  include_once("../../../SQL_PHP/_Utileria.php");

  //Se declaran los utiletos para utilizar las funciones segun los archivos externos
  $crud = new CRUD;
  $util = new Util;
  $fin = new Finanzas;

  $UDN = $_SESSION['empresa'];
  $date = $_POST['date'];

  $var = "idBancos = id_Bancos AND id_UB = idUB AND id_UDN = ".$UDN." AND Fecha_Banco = '".$date."' ORDER BY Name_Bancos asc";

  /*TRATAMIENTO - PAGINACIÓN -SQL */
  $sqlCount = "SELECT Name_Bancos,ROUND(Pago,2) FROM bancos,bancos_udn,bancos_bitacora WHERE ".$var;
  // echo $sqlCount.'<br><br>';
  $nroSol = $crud->_Select($sqlCount,null,"5");
  $array = array(); foreach ($nroSol as $num => $noS){ $array[$num] = $noS[0]; }

  /***************************************************
              VARIABLES / PAGINACIÓN
  ****************************************************/
  $paginaActual = $_POST['pag'];
  $Paginas = count($array);
  $url= "ver_detalle_bancos";
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
  $query = "SELECT Name_Bancos,ROUND(Pago,2) FROM bancos,bancos_udn,bancos_bitacora WHERE ".$var." LIMIT $limit, $Lotes";
    // echo $query;
  $sql = $crud->_Select($query,null,"5");
?>

<div class="col-sm-12 col-xs-12">
  <div class="col-sm-12 col-xs-12 text-right">
    <!-- <h5><label><?php echo "Registros: ".$Paginas; ?></label></h5> -->
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td colspan = "4"><strong>DETALLES DE BANCOS</strong></td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
            ?>
              <tr>
                <td class = "col-sm-3"><?php echo $data[0];?></td>
                <td class = "col-sm-3 text-right">$ <?php echo $data[1];?></td>
                <td class = "col-sm-3"></td>
                <td class = "col-sm-3"></td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
