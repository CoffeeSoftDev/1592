
<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$udn = $_POST['udn'];

if($date1 == $date2){
  $var = "UDN_Sobre = ".$udn." AND Fecha = '".$date1."'";
}
else{
  $var = "UDN_Sobre = ".$udn." AND Fecha BETWEEN '".$date1."' AND '".$date2."'";
}

/*TRATAMIENTO - PAGINACIÓN -SQL */



$sqlCount = "SELECT COUNT(*) FROM hgpqgijw_finanzas.sobres WHERE ".$var;


// echo $sqlCount;
$nroSol = $crud->_Select($sqlCount,null,"5");







foreach ($nroSol as $noS);

/***************************************************
            VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= $noS[0];
$url= "ver_tabla_files";
$Lotes = 15;
$pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

echo $pag;
if($paginaActual <= 1 ){
    $limit=0;
}
else{
    $limit = $Lotes*($paginaActual-1);
}

//Consulta de la tabla

$query = "SELECT Archivo,ROUND(Peso,2),Hora,Type_File,Ruta,Fecha 
FROM hgpqgijw_finanzas.sobres WHERE ".$var." ORDER BY Archivo asc ";






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
                  <td><strong>Fecha</strong></td>
                  <td><strong>Hora</strong></td>
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
  	          	<td class="text-left"><?php echo $data[0]; ?></td>
  	            <td><?php echo $peso; ?></td>
                <td><?php echo $data[5]; ?></td>
  	            <td><?php echo $data[2]; ?></td>
  	            <td>
                  <a class="btn btn-xs btn-info" href="<?php echo ''.$data[4].''.$data[0]; ?>" target="_blank"><span class="icon-download"></span></a>
                </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
