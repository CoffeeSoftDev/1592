<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$var = " AND id_UDN = ".$_SESSION['empresa'];

/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT COUNT(*) FROM insumos_clase,insumos_udn WHERE id_IC = idIC ".$var." AND Stado = 1";

$nroSol = $crud->_Select($sqlCount,null,"5");
foreach ($nroSol as $noS);

/***************************************************
                        VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= $noS[0];
$url= "ver_insumos";
$Lotes = 10;
$pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

echo $pag;
if($paginaActual <= 1 ){
    $limit=0;
}
else{
    $limit = $Lotes*($paginaActual-1);
}

//Consulta de la tabla
$query = "SELECT idIC,Name_IC FROM insumos_clase,insumos_udn WHERE id_IC = idIC ".$var." AND Stado = 1 ORDER BY Name_IC asc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-8 col-xs-12 text-left">
    <div id="Res_tb_ic"></div>
  </div>
  <div class="col-sm-4 col-xs-12 text-right">
    <h5><label><?php echo "Registros: ".$Paginas; ?></label></h5>
  </div>
</div>

<div >
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-1">#</td>
                  <td class="col-sm-10">Clase</td>
                  <td colspan="1">Opciones</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
            ?>
	            <tr class='text-center'>
  	          	<td class='col-sm-1 col-xs-1 text-center'><?php echo $num+1; ?></td>
  	            <td class='col-sm-10 col-xs-10' id="IC<?php echo $id; ?>">
                  <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('IC','<?php echo $id; ?>','<?php echo $data[1]; ?>');"><?php echo $data[1];?></label>
                </td>
                <td class="col-sm-1 col-xs-1">
                  <button type="button" title="Desactivar" class="btn btn-danger btn-xs col-xs-12 col-sm-12" data-toggle='modal' data-target='#Modal_TeSobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_datos_ventas.php?opc=IC&id=<?php echo $id;?>','TeSobre_Modal');"><span class="icon-cancel"></span></button>
                </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">

</script>
