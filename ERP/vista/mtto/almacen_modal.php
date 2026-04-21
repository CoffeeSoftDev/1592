<?php
//Se incluyen los archivos externos
include_once("../../../modelo/SQL_PHP/_CRUD.php");
include_once("../../../modelo/SQL_PHP/_MTTO.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$obj = new MTTO;

//se escribe la consulta para mysqli sin importar cual sea
$query = "SELECT idUDN,UDN FROM udn ORDER BY UDN ASC";

//Se llama la funcion, declarando una variable si esta retorna algun valor
$one = $crud->_Select($query,null,"1");

$cb = $obj -> CB_CATEGORIA();
?>

<div class="form-horizontal" >
 <div class="form-group">
  <label class="col-xs-12 col-sm-4">Empresa:</label>
  <div class="col-xs-12 col-sm-8">
   <select class="form-control input-xs" name="tipoContrato" id="Empresa">
    <?php
    foreach ($one as $lista) {
     echo "<option value=".$lista[0].">" .$lista[1]."</option> ";
    }
    ?>
   </select>
  </div>
 </div>

 <div class="form-group">
  <label class="col-xs-12 col-sm-4">Categoria: </label>
  <div class="col-xs-12 col-sm-8">
   <select onchange="habilitar()" class="form-control input-xs" name="cbCat" id="cbCat">
    <option value="0">ELIGE UNA CATEGORIA</option>
    <?php
    foreach ($cb as $ls) {
     echo "<option value=".$ls[0].">" .$ls[1]."</option> ";
    }
    ?>
   </select>
  </div>
 </div>


 <div class="form-group">
  <label class="col-xs-12 col-sm-4">Nombre del Equipo:</label>
  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

   <input type="text" class="form-control input-xs" id="Equipo" autocomplete="off"
   onkeyup="auto(['#Equipo'],'../../controlador/operacion/mtto/almacen_auto_equipo.php','#Equipo_list');"
   onBlur="setTimeout('lost_focus(\'#Equipo_list\',0,\'\',\'\',\'\')',200);">

   <ul id="Equipo_list" class="autocomplete"></ul>
   <div class=" bg-default" id="Res_Equipo"></div>
  </div>
 </div>


 <div class="form-group">
  <label  class="col-xs-12 col-sm-4">Area:</label>
  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
   <input type="text" class="form-control input-xs" id="Area" autocomplete="off" onkeyup="auto(['#Area'],'../../controlador/operacion/mtto/almacen_auto_area.php','#Area_list');" autocomplete="off" onBlur="setTimeout('lost_focus(\'#Area_list\',0,\'\',\'\',\'\')',200);">
   <ul id="Area_list" class="autocomplete"></ul>
   <div class="bg-default" id="Res_Area"></div>
  </div>
 </div>


 <div class="form-group">
  <label  class="col-xs-12 col-sm-4">Código del Equipo: <a class="text-danger" onclick="show()">[Ver]</a>

  </label>

  <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">

   <input type="hidden" id="hidden" value="">

   <input type="text" class="form-control input-xs" id="Codigo" autocomplete="off" onkeyup="auto(['#Codigo','#hidden'],'../../controlador/operacion/mtto/almacen_auto_codigo.php','#Codigo_list');" autocomplete="off"

   onBlur="setTimeout('lost_focus(\'#Codigo_list\',0,\'\',\'\',\'\')',200);">

   <ul id="Codigo_list" class="autocomplete"></ul>

   <div class="bg-default" id="Res_Codigo"></div>

   <div class="" id="Info" style='display:none;'>
    <br>

    <pre>Criterio para codificar<h5><strong>BA-01-TA-01</strong></h5><h6>
 [BA] 2 digitos que indican la UDN donde se encuentra.
 [01] 2 digitos que indican el número del área.
 [TA] 2 letras que identifican al equipo.
 [01] 2 digitos que indican cuantos equipos similares existen
      en la UDN (en caso de ser equipo unico
      esta indicado como 00)</h6></pre>
   </div>

  </div>
 </div>

 <!--<div class="form-group">
 <label  class="col-sm-12">Escribe la calidad del equipo:</label>
 <div class="col-sm-12">
 <textarea style="resize:none;"rows="6" class="form-control" id="motivo" ></textarea>
 <div class="bg-default" id="Res_Motivo"></div>
</div>
</div>-->

<div class="form-group">
 <div class="col-xs-12 col-sm-4">
  <label>Cantidad:</label>
 </div>

 <div class="col-xs-12 col-sm-8">
  <input type="text" class="form-control input-xs" name="" value="1" id="txtCantidad" disabled onkeyup="Numero()">
 </div>


</div><!-- ./ -->


<div class="form-group" id="Resultado"></div>

<div class="form-group">
 <div class="col-xs-12 ">
  <button type="button" class="btn btn-success col-xs-12 col-sm-3 col-sm-offset-5" onclick="Nuevo_Codigo(1);">Guardar</button>


  <button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-1" data-dismiss="modal" onclick="ver_tabla(1);">Salir</button>
 </div>
</div>



</div>
