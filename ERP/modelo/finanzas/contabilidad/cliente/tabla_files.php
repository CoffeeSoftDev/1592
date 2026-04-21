
<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$date = $_POST['date'];
$var = "UDN_Sobre = ".$_SESSION['empresa']." AND Fecha = '".$date."'";

/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT COUNT(*) FROM sobres WHERE ".$var;
// echo $sqlCount;
$nroSol = $crud->_Select($sqlCount,null,"5");
foreach ($nroSol as $noS);

/***************************************************
            VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= $noS[0];
$url= "ver_files";
$Lotes = 20;
$pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

echo $pag;
if($paginaActual <= 1 ){
    $limit=0;
}
else{
    $limit = $Lotes*($paginaActual-1);
}

//Consulta de la tabla
$query = "SELECT Archivo,ROUND(Peso,2),Hora,Type_File,Ruta FROM sobres WHERE ".$var." ORDER BY Archivo asc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">
    <h5><label> </label></h5>
  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Proveedor"></div>
  <div class="col-sm-4 col-xs-12 text-right">
    <h5><label><?php echo "Archivos Cargados: ".$Paginas; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td><strong>Archivo</strong></td>
                  <td><strong>Peso</strong></td>
                  <td><strong>Hora</strong></td>
                  <td><strong>Tipo de Archivo</strong></td>
                  <td><strong>Descargar</strong></td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              if($data[1] > 1024 ){
                $size = $data[1]/1024;
                $peso = Round($size,2)." Mb";
              }
              else {
                $peso = $data[1]." Kb";
              }
            ?>
	            <tr class='text-center'>
  	          	<td><?php echo $data[0]; ?></td>
  	            <td><?php echo $peso; ?></td>
  	            <td><?php echo $data[2]; ?></td>
  	            <td><?php echo $data[3]; ?></td>
  	            <td>
                  <a class="btn btn-xs btn-warning" href="../../../<?php echo $data[4].'/'.$data[0]; ?>" download="<?php echo $data[0];?>"><span class="icon-download"></span></a>
                </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
