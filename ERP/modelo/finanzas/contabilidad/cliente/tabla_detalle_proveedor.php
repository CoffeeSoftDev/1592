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

  $var = " id_UP IS NOT NULL AND id_CG = 2 AND Fecha_Compras = '".$date."' AND id_UDN = ".$UDN;

  /*TRATAMIENTO - PAGINACIÓN -SQL */
  $sqlCount = "SELECT id_UP FROM compras WHERE ".$var." GROUP BY id_UP";
  // echo $sqlCount.'<br><br>';
  $nroSol = $crud->_Select($sqlCount,null,"5");
  $array = array(); foreach ($nroSol as $num => $noS){ $array[$num] = $noS[0]; }

  /***************************************************
              VARIABLES / PAGINACIÓN
  ****************************************************/
  $paginaActual = $_POST['pag'];
  $Paginas= count($array);
  $url= "ver_detalle_proveedor";
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
  $query = "SELECT idCompras,id_UP FROM compras WHERE ".$var." GROUP BY id_UP ORDER BY idCompras asc LIMIT $limit, $Lotes";
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
                  <td colspan = "4"><strong>DETALLES DE PROVEEDOR</strong></td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              //Obtener el insumo
              if(isset($data[1])){ $prov = $fin->Select_Especific_Proveedor($data[1]); } else{ $prov = $data[1]; }

              $Prov_array = array($UDN,$date,$data[1]);
              //Gasto
              $gasto = $fin->Select_Gastos_Proveedor($Prov_array);
              //Pagos
              $pagos = $fin->Select_Pagos_Proveedor($Prov_array);

            ?>
              <tr>
                <td class = "col-sm-3"><?php echo $prov;?></td>
                <td class = "col-sm-3 text-right">$ <?php echo $gasto;?></td>
                <td class = "col-sm-3 text-right">$ <?php echo $pagos;?></td>
                <td class = "col-sm-3"></td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
