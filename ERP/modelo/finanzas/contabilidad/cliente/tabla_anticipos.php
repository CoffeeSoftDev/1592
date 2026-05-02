
<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$date_sql = $_POST['date'];
$d = date("d", strtotime("$date_sql"));
$m = date("m", strtotime("$date_sql"));
$a = date("Y", strtotime("$date_sql"));
$df = 0;

switch ($m) {
  case 1: $df = 31; break;//Enero
  case 2://Febrero
    $res4 = $a % 4;
    $res1 = $a % 100;
    $res4c = $a % 400;
    if( $res == 0 && $res1 != 0 || $res4c == 0 ){ $df = 29; }else{ $df = 28; }
    break;
  case 3: $df = 31; break;//Marzo
  case 4: $df = 30; break;//Abril
  case 5: $df = 31; break;//Mayo
  case 6: $df = 30; break;//Junio
  case 7: $df = 31; break;//Julio
  case 8: $df = 31; break;//Agosto
  case 9: $df = 30; break;//Septiembre
  case 10: $df = 31; break;//Octubre
  case 11: $df = 30; break;//Noviembre
  case 12: $df = 31; break;//Diciembre
}


$date = "";
if($d > 15){
  $date = " AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') BETWEEN '".$a."-".$m."-15' AND '".$a."-".$m."-".$df."'";
}
else if($d <= 15){
  $date = " AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') BETWEEN '".$a."-".$m."-01' AND '".$a."-".$m."-15'";
}
// $date = " AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') = '".$date_sql."'";
$var = "AND UDN_Empleado = ".$_SESSION['empresa'].$date;

/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT idEmpleado FROM empleados,anticipos WHERE idEmpleado = Empleado_Anticipo ".$var." GROUP BY Nombres ";
// echo $sqlCount;
$nroSol = $crud->_Select($sqlCount,null,"6");
$arr_con = array();
foreach ($nroSol as $key => $noS) {
  $arr_con[$key] = $noS[0];
}

/***************************************************
            VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= count($arr_con);
$url= "ver_anticipos";
$Lotes = 20;
$pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

echo $pag;
if($paginaActual <= 1 ){
    $limit=0;
}
else{
    $limit = $Lotes*($paginaActual-1);
}

//Consulta del monto acumulado del gastos
$query = "SELECT SUM(Saldo) FROM empleados,anticipos WHERE idEmpleado = Empleado_Anticipo ".$var;
// echo $query."<BR>";
$sql1 = $crud->_Select($query,null,"6");
foreach ($sql1 as $row1);
if($row1[0] == ""){$row1[0] = 0;}

//Consulta de la tabla
$query = "SELECT idEmpleado,Nombres,ROUND(SUM(Saldo),2),DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') FROM empleados,anticipos WHERE idEmpleado = Empleado_Anticipo ".$var." GROUP BY Nombres ORDER BY Nombres asc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"6");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-6 col-xs-6 text-left">
    <h5><label> <?php echo "Acumulado: $ ".number_format($row1[0],2,'.',','); ?> </label></h5>
  </div>
  <div class="col-sm-6 col-xs-6 text-right">
    <h5><label><?php echo "Colaboradores: ".$Paginas; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-1">#</td>
                  <td class="col-sm-6">Nombre</td>
                  <td class="col-sm-3">Cantidad</td>
                  <td class="col-sm-3">Fecha</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
            ?>
	            <tr class='text-center'>
  	          	<td class='col-sm-1'><?php echo $num+1; ?></td>
  	            <td class='col-sm-6  text-left'><?php echo $data[1]; ?></td>
  	            <td class='col-sm-3 text-center'>$ <?php echo $data[2]; ?></td>
  	            <td class='col-sm-3 text-center'><?php echo $data[3]; ?></td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
