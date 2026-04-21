
<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$UDN = $_SESSION['empresa']; $var1 = ""; $var2 = "";

if($_POST['Opc'] == 1){
  if($_POST['Tx'] != "") {
    $var1 = " AND Name_Proveedor LIKE '%".$_POST['Tx']."%' ";
  }
}

$var2 = " AND id_UDN = ".$UDN." ".$var1;

/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT COUNT(*) FROM proveedor,proveedor_udn WHERE id_Proveedor = idProveedor AND Stado = 1".$var2;

$nroSol = $crud->_Select($sqlCount,null,"5");
foreach ($nroSol as $noS);

/***************************************************
                        VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= $noS[0];
$url= "ver_proveedores";
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
$var3 = $var2." AND Fecha_Proveedor = '".$_POST['date']."'";
$query = "SELECT SUM(Pago)-SUM(Deuda) FROM proveedor_bitacora,proveedor_udn,proveedor WHERE idUP = id_UP AND idProveedor = id_Proveedor AND Stado = 1".$var3;
// echo $query;
$sql1 = $crud->_Select($query,null,"5");
foreach ($sql1 as $row1);
if($row1[0] == ""){$row1[0] == 0;}

//Consulta de la tabla
$query = "SELECT idUP,Name_Proveedor FROM proveedor,proveedor_udn WHERE id_Proveedor = idProveedor AND Stado = 1 ".$var2." ORDER BY Name_Proveedor asc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">
    <h5><label> <?php echo "Acumulado del día: $".$row1[0]; ?> </label></h5>
  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Proveedor"></div>
  <div class="col-sm-4 col-xs-12 text-right">
    <h5><label><?php echo "Colaboradores activos: ".$Paginas; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-6">Proveedor</td>
                  <td class="col-sm-2">Pago</td>
                  <td class="col-sm-2">Deuda</td>
                  <td colspan="1">Opciones</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              $array = array($id,$_POST['date']);
              $pago = $fin->Select_Pago_Proveedor($array);
              if($pago == 0){$pago = "";}
              $deuda = $fin->Select_Deuda_Proveedor($array);
              if($deuda == 0){ $deuda = "";}
            ?>
	            <tr class='text-center'>
  	          	<td class='col-sm-8 text-left'><?php echo $data[1]; ?></td>
  	            <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' <?php echo "id='Cant_Pago_".$num."' value = '".$pago."' onKeyUp='Moneda(\"Cant_Pago_".$num."\");' onBlur='Save_Pago(".$id.",".$num.");'";  if($pago != null){ echo "disabled";}?>>
                  </div>
                </td>
  	            <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' <?php echo "id='Cant_Deuda_".$num."' value = '".$deuda."' onKeyUp='Moneda(\"Cant_Deuda_".$num."\");' onBlur='Save_Pago(".$id.",".$num.");'";  if($deuda != null ){ echo "disabled";}?>>
                  </div>
                </td>
                <td>
                   <button type="button" title="Modificar" class="btn btn-info btn-xs col-xs-12 col-sm-12" onclick="Cell_Bloq('<?php echo "Cant_Pago_".$num?>');Cell_Bloq('<?php echo "Cant_Deuda_".$num;?>');"><span class="icon-pencil"></span> Modificar</button>
                </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
