
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

$UDN = $_SESSION['empresa']; $var1 = ""; $var2 = "";

if($_POST['Opc'] == 1){
  if($_POST['Tx'] != "") {
    $var1 = " AND Name_Credito LIKE '%".$_POST['Tx']."%' ";
  }
}

$var2 = " AND id_UDN = ".$UDN." ".$var1;

/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT idUC FROM creditos,creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_Credito = idCredito AND creditos_udn.Stado = 1 AND creditos_consumo.Stado = 1  ".$var2." GROUP BY Name_Credito ORDER BY Name_Credito";
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
$url= "ver_creditos";
$Lotes = 20;
$pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

echo $pag;
if($paginaActual <= 1 ){
    $limit=0;
}
else{
    $limit = $Lotes*($paginaActual-1);
}

// Consulta del monto acumulado de la deuda
$query = "SELECT SUM(Cantidad) FROM creditos_consumo,creditos_udn WHERE idUC = id_UC AND creditos_consumo.Stado = 1  AND id_UDN = ".$UDN;
$sql = $crud->_Select($query,null,"5");
foreach($sql as $row);
$CTDA = $row[0];
//Consultamos las deudas saldadas
$query = "SELECT SUM(Cantidad) FROM creditos_consumo,creditos_udn WHERE idUC = id_UC AND creditos_consumo.Stado = 0  AND id_UDN = ".$UDN;
$sql = $crud->_Select($query,null,"5");
foreach($sql as $row);
$CTDI = $row[0];
//Consultamos los pagos realizados
$query = "SELECT SUM(Pago) FROM creditos_bitacora,creditos_udn  WHERE idUC = id_UC AND id_UDN = ".$UDN;
$sql = $crud->_Select($query,null,"5");
foreach($sql as $row);
$CTP = $row[0];
//Restamos la deuda inactiva de la suma de los pagos
$CTP = $CTP - $CTDI;
//Del resultado obtenido se resta de la Deuda activa y obtenemos la deuda actual
$Deuda = $CTDA - $CTP;


//Consulta de la tabla
$query = "SELECT idUC,Name_Credito FROM creditos,creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_Credito = idCredito AND creditos_udn.Stado = 1 AND creditos_consumo.Stado = 1  ".$var2." GROUP BY Name_Credito ORDER BY Name_Credito asc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">
    <h5><label> <?php echo "Acumulado de la Deuda: $".round($Deuda,2); ?> </label></h5>
  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Proveedor"></div>
  <div class="col-sm-4 col-xs-12 text-right">
    <h5><label><?php echo "Creditos activos: ".$Paginas; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-6">Créditos</td>
                  <td class="col-sm-2 text-center">Consumo actual</td>
                  <td class="col-sm-2 text-center">Pago actual</td>
                  <td class="col-sm-2 text-center">Deuda acumulada</td>
                  <td class="col-sm-2 text-center">Detalles</td>
                  <!-- <td colspan="1">Opciones</td> -->
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id  = $data[0];
              //Obtener la cantidad total de deuda activa (CTDA)
              $array = array($id,1);
              $CTDA = $fin->Select_CTD($array);
              if($CTDA == null || $CTDA == 0){$CTDA = 0;}

              //Obtener la cantidad total de deuda inactiva (CTDI)
              $array = array($id,0);
              $CTDI = $fin->Select_CTD($array);
              if($CTDI == null || $CTDI == 0){$CTDI = 0;}

              //Obtener la cantidad total de Pago (CTD)
              $array = array($id);
              $CTP = $fin->Select_CTP($array);
              if($CTP == null || $CTP == 0){ $CTP = 0;}

              //Restamos la deuda inactiva de la suma de los pagos
              $CTP = $CTP - $CTDI;
              //Del resultado obtenido se resta de la Deuda activa y obtenemos la deuda actual
              $Deuda = $CTDA - $CTP;

              $array = array($id,$date);
              //Obtener el pago del día del hoy
              $pago_hoy = $fin->Select_Data_Hoy_Creditos($array);
              //Obtener el consumo del día de hoy
              $deuda_hoy = $fin->Select_Data_Hoy_Consumo($array);
            ?>
	            <tr class='text-center'>
  	          	<td class='col-sm-6 text-left'><?php echo $data[1]; ?></td>
  	            <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' value='<?php echo $deuda_hoy; ?>' disabled>
                  </div>
                </td>
  	            <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' value='<?php echo $pago_hoy; ?>' disabled>
                  </div>
                </td>
  	            <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm' value='<?php echo $Deuda; ?>' disabled>
                  </div>
                </td>
  	            <td class='col-sm-2'>
                  <?php
                    $active = ''; $btn = 'btn-info';
                    if($deuda_hoy == 0 && $pago_hoy == 0){ $active = 'disabled'; $btn = 'btn-default'; }
                  ?>
                  <button type="button" class="btn btn-sm <?php echo $btn; ?>" <?php echo $active; ?> onclick="Cancelar_Credito(<?php echo $data[0]; ?>,'<?php echo $data[1]; ?>');"><span class="icon-eye"></span>Ver</button>
                </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
