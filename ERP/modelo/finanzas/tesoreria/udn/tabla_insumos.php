
<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Tesoreria.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los objetos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$teso = new Tesoreria;

$var = "";

if($_POST['Opc'] != 0){
  if($_POST['Cb'] == 1){
    $var = "AND Name_Gastos LIKE '%".$_POST['Tx']."%'";
  }
  else if($_POST['Cb'] == 2){
    $var = "AND Name_Proveedor LIKE '%".$_POST['Tx']."%'";
  }
}

if($_POST['udn'] != 0){
  $var = $var." AND id_udn = ".$_POST['udn'];
}

if($_POST['Prov'] != 0){
  $var = $var." AND id_proveedor = ".$_POST['Prov'];
}

if($_POST['Cat'] != 0){
    $var = $var." AND id_clase = ".$_POST['Cat'];
}



/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT idinsumo FROM
grupovar_gvsl.udn,
grupovar_gvsl_finanzas.gastos,
grupovar_gvsl_finanzas.proveedor,
grupovar_gvsl_compras.clases,
grupovar_gvsl_compras.unidad,
grupovar_gvsl_compras.insumo WHERE id_udn = idUDN AND id_gastos = idGastos AND id_proveedor = idProveedor AND id_unidad = idunidad AND id_clase = idclase ".$var. " GROUP BY id_gastos";
// echo $sqlCount;
$nroSol = $crud->_Select($sqlCount,null,"5");
$array = array();
foreach ($nroSol as $num => $noS){
  $array[$num] = $noS[0];
}

/***************************************************
                        VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= count($array);
$url= "ver_insumos";
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
$query = "SELECT idinsumo,id_gastos,Name_Gastos,nombreclase
FROM grupovar_gvsl.udn,grupovar_gvsl_finanzas.gastos,grupovar_gvsl_finanzas.proveedor,grupovar_gvsl_compras.clases,grupovar_gvsl_compras.unidad,grupovar_gvsl_compras.insumo WHERE id_udn = idUDN AND id_gastos = idGastos AND id_proveedor = idProveedor AND id_unidad = idunidad AND id_clase = idclase ".$var." GROUP BY id_gastos ORDER BY Name_Gastos asc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-6 col-xs-12 text-left">
    <h5><label></label></h5>
  </div>
  <div class="col-sm-6 col-xs-12 text-right">
    <h5><label><?php echo "Cantidad de insumos: ".$Paginas; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                <?php if($_POST['udn'] == 0){ echo "<td>UDN</td>"; } ?>
                <td>Insumo</td>
                <?php if($_POST['Prov'] == 0){ echo "<td>Proveedor</td>"; } ?>
                <?php if($_POST['Cat'] == 0){ echo "<td>Categoria</td>"; } ?>
                <td>Opciones</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id  = $data[0];
              $sql = $teso->Select_UDN_Insumo_x_Insumo($data[1]); $udn = "";
              foreach ($sql as $row) { $udn = $udn.$row[0].", "; }

              $sql = $teso->Select_Proveedor_Insumo_x_Insumo($data[1]); $prov = "";
              foreach ($sql as $row) { $prov = $prov.$row[0].", "; }

            ?>
	            <tr class='text-center'>
                <td><?php echo $udn;?></td>
                <td><?php echo $data[2];?></td>
                <td><?php echo $prov?></td>
                <td><?php echo $data[3]?></td>
                <td>
                  <button type="button" class="btn btn-info btn-xs"  data-toggle='modal' data-target='#Modal_Tabla' onClick="abrir_modal('../../modelo/finanzas/tesoreria/udn/modal_table.php?insumo=<?php echo $data[2];?>','Tabla_Modal');setTimeout('ver_modal(1)',100);" ><span class="icon-eye"></span></button>
                </td>
              </tr>
        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
