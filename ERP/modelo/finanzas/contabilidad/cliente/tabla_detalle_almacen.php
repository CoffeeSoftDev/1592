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

  $var = "id_UG IS NOT NULL AND id_UG = idUG AND id_Gastos = idGastos AND id_UI = idUI AND idIC = id_IC AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ".$UDN." AND Fecha_Compras = '".$date."' GROUP BY id_UG";

  /*TRATAMIENTO - PAGINACIÓN -SQL */
  $sqlCount = "SELECT id_UG FROM compras,insumos_clase,insumos_udn,gastos,gastos_udn WHERE ".$var;
  // echo $sqlCount.'<br><br>';
  $nroSol = $crud->_Select($sqlCount,null,"5");
  $array = array(); foreach ($nroSol as $num => $noS){ $array[$num] = $noS[0]; }

  /***************************************************
              VARIABLES / PAGINACIÓN
  ****************************************************/
  $paginaActual = $_POST['pag'];
  $Paginas= count($array);
  $url= "ver_detalle_almacen";
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
  $query = "SELECT id_UG,Name_Gastos FROM compras,insumos_clase,insumos_udn,gastos,gastos_udn WHERE  ".$var." ORDER BY Name_Gastos asc LIMIT $limit, $Lotes";
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
                  <td colspan = "4"><strong>DETALLES DE ALMACÉN</strong></td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $Alm_array = array($UDN,$date,$data[0]);
              //Obtener los entrada de almacen
              $Entrada = $fin->Select_Entrada_Almacen($Alm_array);
              //Obtener los salida de almacen
              $Salida = $fin->Select_Salida_Almacen($Alm_array);

            ?>
              <tr>
                <td class = "col-sm-3"><?php echo $data[1];?></td>
                <td class = "col-sm-3 text-right">$ <?php echo $Entrada;?></td>
                <td class = "col-sm-3 text-right">$ <?php echo $Salida;?></td>
                <td class = "col-sm-3"></td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
