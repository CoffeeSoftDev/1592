
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
    $var1 = " AND Name_Bancos LIKE '%".$_POST['Tx']."%' ";
  }
}

$var2 = " AND id_UDN = ".$UDN." ".$var1;

/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT COUNT(*) FROM bancos,bancos_udn WHERE id_Bancos = idBancos AND Stado = 1".$var2;

$nroSol = $crud->_Select($sqlCount,null,"5");
foreach ($nroSol as $noS);

/***************************************************
                        VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= $noS[0];
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

//Consulta del monto acumulado del gastos
$var3 = $var2." AND Fecha_Banco = '".$_POST['date']."'";
$query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora,bancos_udn,bancos WHERE id_Bancos = idBancos AND idUB = id_UB AND Stado = 1".$var3;
// echo $query."<BR>";
$sql = $crud->_Select($query,null,"5");
foreach($sql as $pago); if( !isset($pago[0]) ){ $pago[0] = 0; }

//Consulta de la tabla
$query = "SELECT idUB,Name_Bancos,ROUND(Pago,2) FROM bancos,bancos_udn,bancos_bitacora WHERE id_Bancos = idBancos AND Stado = 1 ".$var2." GROUP BY Name_Bancos ORDER BY Name_Bancos asc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"5");
?>
<div class="row">
  <div class="col-sm-12 col-xs-12">
    <div class="col-sm-12 col-xs-12 text-right">
      <h5><label>Cantidad total del dia: $ <?php echo $pago[0]; ?> </label></h5>
    </div>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-6">Créditos</td>
                  <td class="col-sm-2">Pago</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              $array = array($id,$_POST['date']);
              $pago = $fin->Select_Pago_BB($array);
            ?>
	            <tr class='text-center'>
  	          	<td class='col-sm-8 text-left'><?php echo $data[1]; ?></td>
  	            <td class='col-sm-2'>
                  <div class='input-group'>
                    <span class='input-group-addon input-sm' id='basic-addon2'><label class='icon-dollar'></label></span>
                    <input type='text' class='form-control input-sm text-right' value="<?php echo number_format($pago,2,".",".");?>" disabled>
                  </div>
                </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
