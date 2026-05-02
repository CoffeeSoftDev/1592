
<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Tesoreria.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
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
  $var = $var." AND id_categoria = ".$_POST['Cat'];
}



/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT idinsumo FROM grupovar_gvsl.udn,grupovar_gvsl_finanzas.gastos,grupovar_gvsl_finanzas.proveedor,grupovar_gvsl_compras.categoria,grupovar_gvsl_compras.unidad,grupovar_gvsl_compras.insumo WHERE id_udn = idUDN AND id_gastos = idGastos AND id_proveedor = idProveedor AND id_unidad = idunidad AND id_categoria = idcategoria ".$var;
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
$url= "ver_modal";
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
$query = "SELECT idinsumo,id_gastos,Abreviatura,Name_Gastos,ROUND(precio,2),nombreUnidad,Name_Proveedor,nombrecategoria,marca,presentacion FROM grupovar_gvsl.udn,grupovar_gvsl_finanzas.gastos,grupovar_gvsl_finanzas.proveedor,grupovar_gvsl_compras.categoria,grupovar_gvsl_compras.unidad,grupovar_gvsl_compras.insumo WHERE id_udn = idUDN AND id_gastos = idGastos AND id_proveedor = idProveedor AND id_unidad = idunidad AND id_categoria = idcategoria ".$var." ORDER BY Abreviatura asc LIMIT $limit, $Lotes";
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
          <td>UDN</td>
          <td>Insumo</td>
          <td>Precio</td>
          <td>Unidad</td>
          <td>Proveedor</td>
          <td>Categoría</td>
          <td>Marca</td>
          <td>Presentación</td>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($sql as $num => $data) {
          $id  = $data[0];

          ?>
          <tr class='text-center'>
            <td id="<?php echo 'udn'.$id; ?>">
              <label class="form-control input-xs label-form pointer" onClick="Convertir_input('udn',<?php echo $id.",'".$data[2]."'"; ?>);"><?php echo $data[2];?></label>
            </td>
            <td id="<?php echo 'in'.$id; ?>"> <label class="form-control input-xs label-form pointer" onclick="Convertir_input('in',<?php echo $id.",'".$data[3]."'"; ?>);"><?php echo $data[3];?></label></td>
            <td id="<?php echo 'precio'.$id; ?>">
              <div class="input-group">
                <span class="input-group-addon input-xs" id="basic-addon2"><label class="icon-dollar"></label></span>
                <label class="form-control input-xs label-form pointer" onClick="Convertir_input('precio',<?php echo $id.",'".$data[4]."'"; ?>);"><?php echo $data[4];?></label>
              </div>
            </td>
            <td id="<?php echo 'unidad'.$id; ?>"><label class="form-control input-xs label-form pointer" onClick="Convertir_input('unidad',<?php echo $id.",'".$data[5]."'"; ?>);"><?php echo $data[5]; ?></label></td>
            <td id="<?php echo 'prov'.$id; ?>"><label class="form-control input-xs label-form pointer" onClick="Convertir_input('prov',<?php echo $id.",'".$data[6]."'"; ?>);"><?php echo $data[6]; ?></label></td>
            <td id="<?php echo 'cat'.$id; ?>"><label class="form-control input-xs label-form pointer" onClick="Convertir_input('cat',<?php echo $id.",'".$data[7]."'"; ?>);"><?php echo $data[7]; ?></label></td>
            <td id="<?php echo 'marca'.$id; ?>"><label class="form-control input-xs label-form pointer" onClick="Convertir_input('marca',<?php echo $id.",'".$data[8]."'"; ?>);"><?php echo $data[8]; ?></label></td>
            <td id="<?php echo 'prese'.$id; ?>">
              <label class="form-control input-xs label-form" onClick="Convertir_input('prese',<?php echo $id.",'".$data[9]."'"; ?>);">
                <textarea class="form-control input-xs label-form pointer" readonly style="background:none;"><?php echo $data[9]; ?></textarea>
              </label>
            </td>
          </tr>
          <?php
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
