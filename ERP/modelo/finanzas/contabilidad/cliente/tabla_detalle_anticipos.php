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

  $var = "idEmpleado = Empleado_Anticipo AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = '".$date."' AND UDN_Empleado = ".$UDN." GROUP BY Empleado_Anticipo ORDER BY Nombres asc";

  /*TRATAMIENTO - PAGINACIÓN -SQL */
  $sqlCount = "SELECT Empleado_Anticipo FROM anticipos,empleados WHERE ".$var;
  // echo $sqlCount.'<br><br>';
  $nroSol = $crud->_Select($sqlCount,null,"6");
  $array = array(); foreach ($nroSol as $num => $noS){ $array[$num] = $noS[0]; }

  /***************************************************
              VARIABLES / PAGINACIÓN
  ****************************************************/
  $paginaActual = $_POST['pag'];
  $Paginas = count($array);
  $url= "ver_detalle_anticipos";
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
  $query = "SELECT Empleado_Anticipo,Nombres FROM anticipos,empleados WHERE ".$var." LIMIT $limit, $Lotes";
  // echo $query;
  $sql = $crud->_Select($query,null,"6");
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
                  <td colspan = "4"><strong>DETALLES DE COLABORADORES</strong></td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              $Col_array = array($UDN,$date,$data[0]);
              $Cant = $fin->Select_Cantidad_Colaboradores($Col_array);
            ?>
              <tr>
                <td class = "col-sm-3"><?php echo $data[1];?></td>
                <td class = "col-sm-3"></td>
                <td class = "col-sm-3 text-right">$ <?php echo $Cant;?></td>
                <td class = "col-sm-3"></td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
