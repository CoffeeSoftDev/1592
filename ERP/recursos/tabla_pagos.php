<!--  -->
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
$idE = $_SESSION['empresa'];


$disable_date = ''; $btn_color = 'btn-danger';
$hoy = $fin->NOW();
$ayer = $fin->Ayer();
$date = $_POST['date'];
if($idE == 1){
    if($date != '2017-11-23' && $date != '2017-11-24'
    && $date != '2017-11-25' && $date != '2017-11-26'
    && $date != '2017-11-27' && $date != '2017-11-28'
    && $date != '2017-11-29' && $date != '2017-11-30'
    && $date != '2017-12-01' && $date != '2017-12-02'
    && $date != '2017-12-03' && $date != '2017-12-04'
    && $date != '2017-12-05' && $date != '2017-12-06'
    && $date != '2017-12-07' && $date != '2017-12-08'
    && $date != '2017-12-09' && $date != '2017-12-10'
    && $date != '2017-12-11' && $date != '2017-12-12'
    && $date != '2017-12-13' && $date != '2017-12-14'
    && $date != '2017-12-15' && $date != '2017-12-16'
    && $date != '2017-12-17' && $date != '2017-12-18'
    && $date != '2017-12-19' && $date != '2017-12-20'
    && $date != '2017-12-21' && $date != '2017-12-22'
    && $date != '2017-12-23' && $date != '2017-12-24'
    && $date != '2017-12-25' && $date != '2017-12-26'
    && $date != '2017-12-27' && $date != '2017-12-28'
    && $date != '2017-12-29' && $date != '2017-12-30'
    && $date != '2017-12-31' && $date != '2018-01-01'
    && $date != '2018-01-02'
    && $date != $hoy && $date != $ayer){
      $disable_date = 'disabled';
      $btn_color = 'btn-default';
    }
  }
  else{
    if($date != $hoy && $date != $ayer ){
        $disable_date = 'disabled';
        $btn_color = 'btn-default';

    }
  }


$UDN = $_SESSION['empresa'];
$Cb = $_POST['Cb'];
$var = "Status = 2 AND Fecha_Compras = '".$_POST['date']."' AND id_UDN =".$UDN;

switch ($Cb) {
  case 0:
    /*TRATAMIENTO - PAGINACIÓN -SQL */
    $sqlCount = "SELECT COUNT(*) FROM compras WHERE ".$var;
    // echo $sqlCount.'<br><br>';
    $nroSol = $crud->_Select($sqlCount,null,"5");
    foreach ($nroSol as $noS);

    /***************************************************
                VARIABLES / PAGINACIÓN
    ****************************************************/
    $paginaActual = $_POST['pag'];
    $Paginas= $noS[0];
    $url= "ver_pago";
    $Lotes = 20;
    $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

    echo $pag;
    if($paginaActual <= 1 ){
        $limit=0;
    }
    else{
        $limit = $Lotes*($paginaActual-1);
    }

    $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE ".$var;
    // echo $query."<br><br>";
    $sql = $crud->_Select($query,null,"5");
    foreach($sql as $pagos); if(!isset($pagos[0])){ $pagos[0] = 0;}

    /*********************************************
          RELLENADO DE LA TABLA
    *********************************************/
    //Consulta de la tabla
    $query = "SELECT idCompras,id_UP,id_UG,id_UI,ROUND(Pago,2),id_CG,Observacion,E_S,Status FROM compras WHERE ".$var." ORDER BY idCompras desc LIMIT $limit, $Lotes";
    // echo $query;
    $sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">
    <h5><label>Cantidad del día: $<?php echo $pagos[0];?></label></h5>
  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Gastos"></div>
  <div class="col-sm-4 col-xs-12 text-right">
      <h5><label> Registros: <?php echo $Paginas; ?></label></h5>
  </div>
</div>
<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-2">Proveedor</td>
                  <td class="col-sm-2">Tipo de pago</td>
                  <td class="col-sm-2">Insumo</td>
                  <td class="col-sm-2">Clases de Insumo</td>
                  <td class="col-sm-2">Cantidad</td>
                  <td class="col-sm-2">Observaciones</td>
                  <td>Eliminar</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              //Obtener el proveedor
              if(isset($data[1])){
                $prov_temp = $fin->Select_Especific_Proveedor($data[1]);
                if($date != $hoy && $date != $ayer){
                  $prov = '<label class="form-control text-left input-sm label-form" >'.$prov_temp.'</label>';
                }
                else{
                  $prov = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'prov_pagos\',\''.$id.'\',\''.$prov_temp.'\');">'.$prov_temp.'</label>';
                }
              }
              else{ $prov = $data[1]; }
              //Obtener el insumo
              if(isset($data[2])){
                $insumo_temp = $fin->Select_Especific_Insumo($data[2]);
                if($date != $hoy && $date != $ayer){
                  $insumo = '<label class="form-control text-left input-sm label-form" >'.$insumo_temp.'</label>';
                }
                else {
                  $insumo = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'Insumo_pagos\',\''.$id.'\',\''.$insumo_temp.'\');">'.$insumo_temp.'</label>';
                }
              }
              else { $insumo = $data[2]; }
              //Obtener la clase insumo
              if(isset($data[3])){
                $CI_temp = $fin->Select_Especific_CI($data[3]);
                if($date != $hoy && $date != $ayer){
                  $CI = '<label class="form-control text-left input-sm label-form " >'.$CI_temp.'</label>';
                }
                else{
                  $CI = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'ClaseInsumo_pagos\',\''.$id.'\',\''.$CI_temp.'\');">'.$CI_temp.'</label>';
                }

              }
              else { $CI = $data[3]; }
              //Obtener el tipo de gastos
              if(isset($data[5])){
                $TG_temp = $fin->Select_Especific_TG($data[5]);
                if($date != $hoy && $date != $ayer){
                  $TG = '<label class="form-control text-left input-sm label-form " >'.$TG_temp.'</label>';
                }
                else {
                  $TG = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'TipoGasto_pagos\',\''.$id.'\',\''.$TG_temp.'\');">'.$TG_temp.'</label>';
                }
              }
              else { $TG = $data[5]; }
            ?>
	            <tr class='text-center'>
                <!-- <td><?php echo $id; ?></td> -->
                <td id="prov_pagos<?php echo $id;?>">
                  <?php echo $prov; ?>
                </td>
                <td id="TipoGasto_pagos<?php echo $id;?>">
                  <?php echo $TG; ?>
                </td>
                <td id="Insumo_pagos<?php echo $id;?>">
                  <?php echo $insumo; ?>
                </td>
                <td  id="ClaseInsumo_pagos<?php echo $id;?>">
                  <?php echo $CI; ?>
                </td>
                <td id="Cant_pagos<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-right input-sm label-form" >$ <?php echo $data[4];?></label>
                  <?php } else { ?>
                    <label class="form-control text-right input-sm label-form pointer" onClick="Convertir_input('Cant_pagos','<?php echo $id; ?>','<?php echo $data[4]; ?>');">$ <?php echo $data[4];?></label>
                  <?php }?>
                </td>
                <td id="Observaciones_pagos<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-left input-sm label-form" >
                      <textarea class="form-control input-xs label-form" readonly style="background:none;"><?php echo $data[6]; ?></textarea>
                    </label>
                  <?php } else { ?>
                    <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('Observaciones_pagos','<?php echo $id; ?>','<?php echo $data[6]; ?>');">
                      <textarea class="form-control input-xs label-form pointer" readonly style="background:none;"><?php echo $data[6]; ?></textarea>
                    </label>
                  <?php }?>
                </td>
                <td>
                   <button type="button" title="Eliminar" class="btn btn-xs <?php echo $btn_color; ?>" data-toggle='modal' data-target='#Modal_Sobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_sino.php?opc=2&id=<?php echo $id; ?>','Sobre_Modal');"
                   <?php echo $disable_date; ?> ><span class="icon-cancel"></span></button>
                 </td>
              </tr>

        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
<?php
    break;
  case 1:
    /*TRATAMIENTO - PAGINACIÓN -SQL */
    $sqlCount = "SELECT COUNT(*) FROM compras WHERE id_UP IS NOT NULL AND ".$var;
    // echo $sqlCount.'<br><br>';
    $nroSol = $crud->_Select($sqlCount,null,"5");
    foreach ($nroSol as $noS);

    /***************************************************
                VARIABLES / PAGINACIÓN
    ****************************************************/
    $paginaActual = $_POST['pag'];
    $Paginas= $noS[0];
    $url= "ver_pago";
    $Lotes = 20;
    $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

    echo $pag;
    if($paginaActual <= 1 ){
        $limit=0;
    }
    else{
        $limit = $Lotes*($paginaActual-1);
    }

    $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND ".$var;
    // echo $query."<br><br>";
    $sql = $crud->_Select($query,null,"5");
    foreach($sql as $pagos); if(!isset($pagos[0])){ $pagos[0] = 0;}
    /*********************************************
          RELLENADO DE LA TABLA
    *********************************************/
    //Consulta de la tabla
    $query = "SELECT idCompras,id_UP,id_CG,ROUND(Pago,2),Observacion,E_S,Status FROM compras WHERE id_UP IS NOT NULL AND ".$var." ORDER BY idCompras asc LIMIT $limit, $Lotes";
    // echo $query;
    $sql = $crud->_Select($query,null,"5");
  ?>
  <div class="col-sm-12 col-xs-12">
    <div class="col-sm-4 col-xs-12 text-left">
      <h5><label>Cantidad del día: $<?php echo $pagos[0];?></label></h5>
    </div>
    <div class="col-sm-4 col-xs-12 text-center" id="Res_Gastos"></div>
    <div class="col-sm-4 col-xs-12 text-right">
        <h5><label> Registros: <?php echo $Paginas; ?></label></h5>
    </div>
  </div>
  <div  id="table-conf">
      <div class="table table-responsive" >
          <!--CONTENIDO-->
          <table class="table table-striped table-hover table-bordered table-condensed">
              <thead>
                <tr class="text-center " id="titulo">
                    <td class="col-sm-3">Proveedor</td>
                    <td class="col-sm-3">Tipo de Pago</td>
                    <td class="col-sm-3">Cantidad</td>
                    <td class="col-sm-3">Observaciones</td>
                    <td>Eliminar</td>
                </tr>
              </thead>
              <tbody>
              <?php
              foreach ($sql as $num => $data) {
                $id   = $data[0];
                //Obtener el proveedor
                if(isset($data[1])){
                  $prov_temp = $fin->Select_Especific_Proveedor($data[1]);
                  if($date != $hoy && $date != $ayer){
                    $prov = '<label class="form-control text-left input-sm label-form" >'.$prov_temp.'</label>';
                  }
                  else{
                    $prov = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'prov_pagos\',\''.$id.'\',\''.$prov_temp.'\');">'.$prov_temp.'</label>';
                  }
                }
                else{ $prov = $data[1]; }
                //Obtener el tipo de gastos
                if(isset($data[2])){
                  $TG_temp = $fin->Select_Especific_TG($data[2]);
                  if($date != $hoy && $date != $ayer){
                    $TG = '<label class="form-control text-left input-sm label-form" >'.$TG_temp.'</label>';
                  }
                  else{
                    $TG = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'TipoGasto_pagos\',\''.$id.'\',\''.$TG_temp.'\');">'.$TG_temp.'</label>';
                  }
                }
                else { $TG = $data[2]; }
              ?>
  	            <tr class='text-center'>
                  <td id="prov_pagos<?php echo $id;?>">
                    <?php echo $prov; ?>
                  </td>
                  <td id="TipoGasto_pagos<?php echo $id;?>">
                    <?php echo $TG; ?>
                  </td>
                  <td id="Cant_pagos<?php echo $id;?>">
                    <?php if($date != $hoy && $date != $ayer){ ?>
                      <label class="form-control text-right input-sm label-form" >$ <?php echo $data[3];?></label>
                    <?php } else { ?>
                      <label class="form-control text-right input-sm label-form pointer" onClick="Convertir_input('Cant_pagos','<?php echo $id; ?>','<?php echo $data[3]; ?>');">$ <?php echo $data[3];?></label>
                    <?php } ?>
                  </td>
                  <td id="Observaciones_pagos<?php echo $id;?>">
                    <?php if($date != $hoy && $date != $ayer){ ?>
                      <label class="form-control text-left input-sm label-form" >
                        <textarea class="form-control input-sm label-form" readonly style="background:none;"><?php echo $data[4]; ?></textarea>
                      </label>
                    <?php } else { ?>
                      <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('Observaciones_pagos','<?php echo $id; ?>','<?php echo $data[4]; ?>');">
                        <textarea class="form-control input-sm label-form pointer" readonly style="background:none;"><?php echo $data[4]; ?></textarea>
                      </label>
                    <?php } ?>
                  </td>
                  <td>
                     <button type="button" title="Eliminar" class="btn btn-xs <?php echo $btn_color; ?> " data-toggle='modal' data-target='#Modal_Sobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_sino.php?opc=2&id=<?php echo $id; ?>','Sobre_Modal');"
                     <?php echo $disable_date; ?> ><span class="icon-cancel"></span></button>
                   </td>
                </tr>

          <?php
                  }
          ?>
          </tbody>
      </table>
    </div>
  </div>
  <?php
    break;
  case 2:
    /*TRATAMIENTO - PAGINACIÓN -SQL */
    $sqlCount = "SELECT COUNT(*) FROM compras WHERE id_UG IS NOT NULL AND ".$var;
    // echo $sqlCount.'<br><br>';
    $nroSol = $crud->_Select($sqlCount,null,"5");
    foreach ($nroSol as $noS);

    /***************************************************
                VARIABLES / PAGINACIÓN
    ****************************************************/
    $paginaActual = $_POST['pag'];
    $Paginas= $noS[0];
    $url= "ver_pago";
    $Lotes = 20;
    $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

    echo $pag;
    if($paginaActual <= 1 ){
        $limit=0;
    }
    else{
        $limit = $Lotes*($paginaActual-1);
    }

    $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NULL AND ".$var;
    // echo $query."<br><br>";
    $sql = $crud->_Select($query,null,"5");
    foreach($sql as $pagos); if(!isset($pagos[0])){ $pagos[0] = 0;}
    /*********************************************
          RELLENADO DE LA TABLA
    *********************************************/
    //Consulta de la tabla
    $query = "SELECT idCompras,id_UG,id_UI,ROUND(Pago,2),Observacion,E_S,Status FROM compras WHERE id_UG IS NOT NULL AND ".$var." ORDER BY idCompras asc LIMIT $limit, $Lotes";
    // echo $query;
    $sql = $crud->_Select($query,null,"5");
?>
<div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-12 text-left">
    <h5><label>Cantidad del día: $<?php echo $pagos[0];?></label></h5>
  </div>
  <div class="col-sm-4 col-xs-12 text-center" id="Res_Gastos"></div>
  <div class="col-sm-4 col-xs-12 text-right">
      <h5><label> Registros: <?php echo $Paginas; ?></label></h5>
  </div>
</div>
<div  id="table-conf">
    <div class="table table-responsive" >
        <!--CONTENIDO-->
        <table class="table table-striped table-hover table-bordered table-condensed">
            <thead>
              <tr class="text-center " id="titulo">
                  <td class="col-sm-3">Insumo</td>
                  <td class="col-sm-3">Clases de Insumo</td>
                  <td class="col-sm-3">Cantidad</td>
                  <td class="col-sm-3">Observaciones</td>
                  <td>Eliminar</td>
              </tr>
            </thead>
            <tbody>
            <?php
            foreach ($sql as $num => $data) {
              $id   = $data[0];
              //Obtener el insumo
              if(isset($data[1])){
                $insumo_temp = $fin->Select_Especific_Insumo($data[1]);
                if($date != $hoy && $date != $ayer){
                  $insumo = '<label class="form-control text-left input-sm label-form" >'.$insumo_temp.'</label>';
                }
                else{
                  $insumo = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'Insumo_pagos\',\''.$id.'\',\''.$insumo_temp.'\');">'.$insumo_temp.'</label>';
                }
              }
              else { $insumo = $data[1]; }
              //Obtener la clase insumo
              if(isset($data[2])){
                $CI_temp = $fin->Select_Especific_CI($data[2]);
                if($date != $hoy && $date != $ayer){
                  $CI = '<label class="form-control text-left input-sm label-form" >'.$CI_temp.'</label>';
                }
                else{
                  $CI = '<label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input(\'ClaseInsumo_pagos\',\''.$id.'\',\''.$CI_temp.'\');">'.$CI_temp.'</label>';
                }
              }
              else { $CI = $data[2]; }
            ?>
              <tr class='text-center'>
                <td id="Insumo_pagos<?php echo $id;?>">
                  <?php echo $insumo; ?>
                </td>
                <td  id="ClaseInsumo_pagos<?php echo $id;?>">
                  <?php echo $CI; ?>
                </td>
                <td id="Cant_pagos<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-right input-sm label-form" >$ <?php echo $data[3];?></label>
                  <?php } else { ?>
                    <label class="form-control text-right input-sm label-form pointer" onClick="Convertir_input('Cant_pagos','<?php echo $id; ?>','<?php echo $data[3]; ?>');">$ <?php echo $data[3];?></label>
                  <?php }?>
                </td>
                <td id="Observaciones_pagos<?php echo $id;?>">
                  <?php if($date != $hoy && $date != $ayer){ ?>
                    <label class="form-control text-left input-sm label-form" >
                      <textarea class="form-control input-xs label-form" readonly style="background:none;"><?php echo $data[4]; ?></textarea>
                    </label>
                  <?php } else { ?>
                    <label class="form-control text-left input-sm label-form pointer" onClick="Convertir_input('Observaciones_pagos','<?php echo $id; ?>','<?php echo $data[4]; ?>');">
                      <textarea class="form-control input-xs label-form pointer" readonly style="background:none;"><?php echo $data[4]; ?></textarea>
                    </label>
                  <?php } ?>
                </td>
                <td>
                   <button type="button" title="Eliminar" class="btn btn-xs <?php echo $btn_color; ?>" data-toggle='modal' data-target='#Modal_Sobre' onClick="abrir_modal('../../../modelo/finanzas/contabilidad/cliente/modal_sino.php?opc=2&id=<?php echo $id; ?>','Sobre_Modal');"
                   <?php echo $disable_date; ?> ><span class="icon-cancel"></span></button>
                 </td>
              </tr>
        <?php
                }
        ?>
        </tbody>
    </table>
  </div>
</div>
<?php
    break;
}
