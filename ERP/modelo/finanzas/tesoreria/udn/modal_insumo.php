<?php
  include_once('../../../SQL_PHP/_Perfil.php');
  $per = new PERFIL;
  $sql = $per->Select_UDN();
?>
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong>Agregar Insumo</strong></h3>
</div>
<div class="modal-body">
    <!--/. UDN-->
    <div class="row">
        <div class="form-group col-sm-12 col-xs-12">
            <div class="col-sm-12 col-xs-12">
                <label>Empresa *</label>
                <select class="input-sm form-control" id="UDN">
                    <option value="0">Seleccionar Empresa</option>
                    <?php foreach ($sql as $row) {
                      echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                    } ?>
                </select>
                <div id="Res_UDN"> </div>
            </div>
        </div>
    </div>
    <!--\. UDN-->

   <!--/. INSUMO-->
   <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
        <div class="col-sm-12 col-xs-12">
            <label>Insumo *</label>
            <input type="text" class="form-control input-sm" id="Insumo" autocomplete="off"
            onkeyup="auto(['#Insumo'],'../../controlador/finanzas/tesoreria/auto_save.php?key2=Insumo&key3=Insumo_list','#Insumo_list');"
            onBlur="setTimeout('lost_focus(\'#Insumo_list\',0,\'\',\'\',\'\')',200);">
            <div id="Res_Insumo"></div>
            <ul id="Insumo_list" class="autocomplete"></ul>
        </div>
    </div>
   </div>
   <!--\. INSUMO-->

   <!--/. PROVEEDOR-->
   <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
        <div class="col-sm-12 col-xs-12">
            <label>Proveedor *</label>
            <input type="text" class="form-control input-sm" id="Proveedor" autocomplete="off"
            onkeyup="auto(['#Proveedor'],'../../controlador/finanzas/tesoreria/auto_save.php?key2=Proveedor&key3=Proveedor_list','#Proveedor_list');"
            onBlur="setTimeout('lost_focus(\'#Proveedor_list\',0,\'\',\'\',\'\')',200);">
            <div id="Res_Insumo"></div>
            <ul id="Proveedor_list" class="autocomplete"></ul>
        </div>
    </div>
   </div>
   <!--\. PROVEEDOR-->


   <div class="row">
       <div class="form-group col-sm-12 col-xs-12">
           <!--/. CATEGORÍA-->
           <div class="col-sm-6 colo-xs-12">
               <label>Categoría *</label>
               <input type="text" class="form-control input-sm" id="Categoria" autocomplete="off"
               onkeyup="auto(['#Categoria'],'../../controlador/finanzas/tesoreria/auto_save.php?key2=Categoria&key3=Categoria_list','#Categoria_list');"
               onBlur="setTimeout('lost_focus(\'#Categoria_list\',0,\'\',\'\',\'\')',200);">
               <div id="Res_Categoria"></div>
               <ul id="Categoria_list" class="autocomplete"></ul>
           </div>
           <!--\. CATEGORÍA-->

           <!--/. MARCA-->
           <div class="col-sm-6 col-xs-12">
               <label>Marca</label>
               <input type="text" class="form-control input-sm" id="Marca" autocomplete="off"
               onkeyup="auto(['#Marca'],'../../controlador/finanzas/tesoreria/auto_save.php?key2=Marca&key3=Marca_list','#Marca_list');"
               onBlur="setTimeout('lost_focus(\'#Marca_list\',0,\'\',\'\',\'\')',200);">
               <div id="Res_Marca"></div>
               <ul id="Marca_list" class="autocomplete"></ul>
           </div>
           <!--\. MARCA-->
       </div>
   </div>

   <div class="row">
       <div class="form-group col-sm-12 col-xs-12">
       <!--/. UNIDAD-->
       <div class="col-sm-6 col-xs-12">
           <label>Unidad *</label>
           <input type="text" class="form-control input-sm" id="Unidad" autocomplete="off"
           onkeyup="auto(['#Unidad'],'../../controlador/finanzas/tesoreria/auto_save.php?key2=Unidad&key3=Unidad_list','#Unidad_list');"
           onBlur="setTimeout('lost_focus(\'#Unidad_list\',0,\'\',\'\',\'\')',200);">
           <div id="Res_Unidad"></div>
           <ul id="Unidad_list" class="autocomplete"></ul>
       </div>
       <!--\. UNIDAD-->

       <!--. PRECIO-->
       <div class="col-sm-6 col-xs-12">
           <label>Precio *</label>
           <div class="input-group">
              <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
              <input type="text" class="form-control input-sm" id="Precio" placeholder = "0.00" onKeyUp="Moneda('Precio');">
              <div id="Res_Precio"></div>
            </div>
       </div>
       <!--. PRECIO-->
       </div>
   </div>

   <!--/. PRESENTACIÓN-->
   <div class="row">
       <div class="form-group col-sm-12 col-xs-12">
           <div class="col-sm-12 col-xs-12">
               <label>Presentación</label>
               <textarea class="form-control input-sm" id="Presentacion"></textarea>
           </div>
       </div>
   </div>
   <!--\. PRESENTACIÓN-->

   <div id="Res_Presentacion" class="text-center"></div>

   <!--/. INSERTAR-->
   <div class="row">
       <div class="form-group col-sm-12 col-xs-12">
           <div class="col-sm-12 col-xs-12">
               <button class="btn btn-sm btn-primary col-sm-8 col-sm-offset-2 col-xs-12" onClick="Insumo();">Insertar Insumo</button>
           </div>
       </div>
   </div>
   <!--\. INSERTAR-->
</div>
