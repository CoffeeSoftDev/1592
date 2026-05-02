<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

date_default_timezone_set('America/Mexico_City');

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$disable_date = ''; $btn_color = 'btn-danger';
$hoy = $fin->NOW();
$ayer = $fin->Ayer();
$date = $_POST['date'];
if($date != $hoy && $date != $ayer){
  $disable_date = 'disabled';
  $btn_color = 'btn-default';
}

$UDN = $_SESSION['empresa'];

$var = "compras.Status = 1 AND Fecha_Compras = '".$_POST['date']."' AND compras.id_UDN =".$UDN;
$condicion = "";

if($_POST['Cb'] == 1){ $var = $var." AND Name_IC LIKE '%Almacen%'"; }
if($_POST['Cb'] == 2){ $var = $var." AND Name_IC LIKE '%Costo%'"; }
else if($_POST['Cb'] == 3){ $var = $var." AND id_UP IS NOT NULL AND id_CG = 2 "; }
else if($_POST['Cb'] == 4){ $var = $var." AND id_CG = 1"; }
else if($_POST['Cb'] == 5){ $var = $var." AND id_CG = 3"; }


/*TRATAMIENTO - PAGINACIÓN -SQL */
$sqlCount = "SELECT COUNT(*) FROM  compras,insumos_clase,insumos_udn WHERE id_IC = idIC AND idUI = id_UI AND ".$var;
// echo $sqlCount.'<br><br>';
$nroSol = $crud->_Select($sqlCount,null,"5");
foreach ($nroSol as $noS);

/***************************************************
            VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= $noS[0];
$url= "ver_gastos";
$Lotes = 20;
$pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

echo $pag;
if($paginaActual <= 1 ){
    $limit=0;
}
else{
    $limit = $Lotes*($paginaActual-1);
}

/***************************************************
            ACUMULADO DEL DIA
****************************************************/
$query = "SELECT ROUND(SUM(Gasto),2) FROM compras,insumos_clase,insumos_udn WHERE id_IC = idIC AND idUI = id_UI AND ".$var;
// echo $query;
$sql = $crud->_Select($query,null,"5");
foreach($sql as $compras); if(!isset($compras[0])){ $compras[0] = 0;}





//Consulta de la tabla
$query = "SELECT idCompras,id_UP,id_UG,id_UI,ROUND(Gasto,2),id_CG,Observacion,E_S,Status FROM compras,insumos_clase,insumos_udn WHERE id_IC = idIC AND idUI = id_UI AND ".$var." ORDER BY idCompras desc LIMIT $limit, $Lotes";
// echo $query;
$sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">
    <h5><label>Compras del día: $<?php echo $compras[0];?></label></h5>
  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Gastos"></div>
  <div class="col-sm-4 col-xs-12 text-right">
      <h5><label> Compras registradas: <?php echo $Paginas; ?></label></h5>
  </div>
</div>

<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <!-- <td>#</td> -->
                  <td>Proveedor</td>
                  <td>Insumo</td>
                  <td>Cantidad</td>
                  <td>Clases de Insumo</td>
                  <td>Tipo de Gasto</td>
                  <td>Observaciones</td>
                  <td>Eliminar</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              //Obtener el proveedor
              if(isset($data[1])){ $prov = $fin->Select_Especific_Proveedor($data[1]); } else{ $prov = $data[1]; }
              //Obtener el insumo
              if(isset($data[2])){ $insumo = $fin->Select_Especific_Insumo($data[2]); } else { $insumo = $data[2]; }
              //Obtener la clase insumo
              $CI = $fin->Select_Especific_CI($data[3]);
              //Obtener el tipo de gastos
              $TG = $fin->Select_Especific_TG($data[5]);
              //Pagos
            ?>
	            <tr class='text-center'>
                <!-- <td><?php echo $id; ?></td> -->
                <!-- PROVEEDOR -->
                <td id="Prov_Compras<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-left input-sm label-form" ><?php echo $prov;?></label>
                  <?php } else { ?>
                    <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('Prov_Compras','<?php echo $id; ?>','<?php echo $prov; ?>');"><?php echo $prov;?></label>
                  <?php } ?>
                </td>
                <!-- INSUMO -->
                <td id="Insumo_Compras<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-left input-sm label-form" ><?php echo $insumo;?></label>
                  <?php } else { ?>
                    <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('Insumo_Compras','<?php echo $id; ?>','<?php echo $insumo; ?>');"><?php echo $insumo;?></label>
                  <?php } ?>
                </td>
                <!-- CANTIDAD -->
                <td id="Cant_Compras<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-right input-sm label-form" >$ <?php echo $data[4];?></label>
                  <?php } else { ?>
                    <label class="form-control text-right input-sm label-form pointer" onClick="Convertir_input('Cant_Compras','<?php echo $id; ?>','<?php echo $data[4]; ?>');">$ <?php echo $data[4];?></label>
                  <?php } ?>
                </td>
                <!-- CLASE INSUMO -->
                <td id="ClaseInsumo_Compras<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-left input-sm label-form" ><?php echo $CI;?></label>
                  <?php } else { ?>
                    <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('ClaseInsumo_Compras','<?php echo $id; ?>','<?php echo $CI; ?>');"><?php echo $CI;?></label>
                  <?php } ?>
                </td>
                <!-- TIPO DE GASTO -->
                <td id="TipoGasto_Compras<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-left input-sm label-form" ><?php echo $TG;?></label>
                  <?php } else { ?>
                    <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('TipoGasto_Compras','<?php echo $id; ?>','<?php echo $TG; ?>');"><?php echo $TG;?></label>
                  <?php } ?>
                </td>
                <!-- OBSERVACIONES -->
                <td id="Observaciones_Compras<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-left input-sm label-form" >
                      <textarea class="form-control input-xs label-form" readonly style="background:none;"><?php echo $data[6]; ?></textarea>
                    </label>
                  <?php } else { ?>
                    <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('Observaciones_Compras','<?php echo $id; ?>','<?php echo $data[6]; ?>');">
                      <textarea class="form-control input-xs label-form pointer" readonly style="background:none;"><?php echo $data[6]; ?></textarea>
                    </label>
                  <?php } ?>
                 </td>
                <td>
                   <button type="button" title="Eliminar" class="btn btn-xs <?php echo $btn_color; ?>" data-toggle='modal' data-target='#Modal_Sobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_sino.php?opc=1&id=<?php echo $id; ?>','Sobre_Modal');"
                  <?php echo $disable_date; ?>><span class="icon-cancel"></span></button>
                 </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
