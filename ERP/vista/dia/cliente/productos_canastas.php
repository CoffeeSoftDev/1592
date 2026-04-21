<div class="row tabla_lista_canasta hide">

 <div class="col-sm-6 col-xs-12">

  <div class="form-group">
   <div id="my-btns" class="btn-group" data-toggle="buttons">

    <label class="btn btn-default btn-sm  active" onclick="tabla_productos_canasta(1);show_hide_insumo(1);">
     <input type="radio"  name="rdbtn" checked="" value="1"><b>Productos</b></label>
     <label class="btn btn-default btn-sm " onclick="tabla_productos_canasta(2);show_hide_insumo(2);">
      <input type="radio" autocomplete="off" name="rdbtn" value="2"><b>Insumo</b></label>

     </div><!-- btn-group -->
    </div>

    <div id="content-pane-insumo" class="form-group">

     <div class="col-sm-6 col-xs-12">
      <div class="input-group">
       <span class="input-group-addon input-sm" id="basic-addon2">
        <label class="icon-search"></label></span>
        <input type="text" class="form-control " id="txtProductos" placeholder="Insumo">
       </div>
      </div><!-- ./ col -->


    <div class="col-sm-3 col-xs-12">
     <div class="input-group">
      <span class="input-group-addon" id="basic-addon2">
       <label class=" icon-dollar"></label></span>

       <input type="number" class="form-control input-sm ui-autocomplete-input " id="txtCosto" min="1" placeholder="Costo">

      </div>
     </div><!-- ./ col -->

     <div  class="col-sm-3 col-xs-12">
      <button  style="width:100%" class="btn btn-success  btn-sm" id="btnAgregar" onclick="AgregarInsumo()"><span class=" icon-plus-circled"></span> Agregar </button>
     </div>
   </div><!-- ./tablero-->
    <div class="row tabla_productos_canasta"></div>

   </div><!-- ./col-sm-6 col-xs-12 -->
   <div class=" col-sm-6 col-xs-12">
    <div class="tabla_canastas"></div>
   </div>

  </div><!-- ./row-->
