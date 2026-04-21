<div class="container">

 <div class="panel panel-default">
  <div class="panel-body">
   <div class="form-horizontal">


     <!-- tabs -->
      <ul class="mt-20 nav nav-tabs">

        <li>
        <a class="active" data-toggle="tab" href="#valorizacion" onclick="doc_valor(1)">
         <strong><span class="bx bxs-report"></span> Valorización</strong>
        </a>
       </li>

       <li>
         <a class="text-info" data-toggle="tab" href="#home" onclick="ver_tabla(1)">
          <strong>
           <span class="bx bxs-report"></span> Activos
          </strong>
         </a>
        </li>

      </ul>

      <div class="tab-content">


     <div id="valorizacion" class="tab-pane fade in active">

       <div id="doc_valor">

       </div>

     </div>


   </div>


<br>




    <!-- Grupo de botones -->

    <div class="col-sm-12 ">

      <!-- <ul class="nav nav-pills">
          <li class="active"><a data-toggle="pill" href="#home" onclick="ver_tabla(1)">Activos</a></li>
          <li><a data-toggle="pill" href="#menu1">No activos</a></li>
          <li><a data-toggle="pill" href="#menu2">Valorización</a></li>
        </ul> -->





     <div class="btn-group" data-toggle="buttons">

      <button class=" btn btn-default btn-xs " autofocus="true" onclick="ver_tabla(1)">
       <input type="radio" value="2" name="rdExtra">
       <strong> <span class="icon-ok-circled" class="text-success"></span> Activos </strong>
      </button>

      <button class="btn btn-default btn-xs " onclick="ver_tabla(0)">
       <input type="radio" value="3" name="rdExtra">
       <strong><span class="icon-cancel-circled"></span>No activos</strong>
      </button>

      <button class="btn btn-default btn-xs active" onclick="ver_reporte()">
       <input type="radio" value="4" name="rdExtra">
       <strong><span class="icon-cancel-circled"></span>Valorización</strong>
      </button>


     </div>
    </div><!-- ./-->


    <div	class="tab-content">

     <div class="col-xs-12 col-sm-12">
      <h3><label><span class="icon-wrench-1"></span> MATERIALES </label></h3>
     </div>

     <!-- Tablero de control -->

     <div class="form-group">
      <div class="col-xs-12 col-sm-3" id="cbZona">
       <select class="form-control input-xs"  id="select" onchange="">
        <option value="0">Selecciona una zona</option>
       </select>
      </div>


      <div class="col-sm-3 col-xs-12" id="cbCat">
       <select class="form-control input-xs" name="" id="txtCategoria" onchange="ver_tabla(1); ver_tabla(2);">
        <option value="0"> Selecciona una categoria</option>
       </select>
      </div>

      <div class="col-sm-3 col-xs-12" id="cbArea">
       <select class="form-control input-xs" name="" id="txtArea" onchange="ver_tabla(1); ver_tabla(2);">
        <option value="0"> Selecciona un area</option>
       </select>
      </div>



      <div class="col-sm-3 col-xs-12">
       <a  style="width:100%;" data-toggle='modal' data-target='#Producto' data-controls-modal="ModalUI" data-backdrop="static" data-keyboard="false"
       onclick="verModal();" class="btn btn-success btn-xs "><span class="icon-wrench-1"></span> Nuevo Material </a>
      </div>

     </div><!-- ./-->


     <!-- PESTAÑA	1
    -->

    <div	id="home"	class="tab-pane	fade	in	active">

     <div class="table-responsive" id="tabla">

      <table id="tbUsuarios" class="table table-striped table-bordered table-compact base-style" style="width:100% font-size:.8em;">
       <thead>
        <tr>
         <th class="col-sm-1">Código</th>
         <th>Zona</th>
         <th>Equipo</th>
         <th>Categoria</th>
         <th>Area</th>
         <th>Cantidad</th>
         <th>Costo</th>
         <th>Utilidad</th>
         <th>Estado</th>
         <th>Desc</th>
         <th></th>
        </tr>

       </thead>



      </table>

     </div>



    </div>

    <!--
    PESTAÑA	2
   -->
   <div	id="menu1"	class="tab-pane	fade">
    <br>
    <br>
    <table id="tbNoActivos" class="display cell-border nowrap" style="width:100%">
     <thead>
      <tr>
       <th>Codigo</th>
       <th>Negocio</th>
       <th>Equipo</th>
       <th>Categoria</th>
       <th>Area</th>
       <th>Stock</th>
       <th>Baja</th>
       <th>Motivo</th>
       <th>Costo</th>
       <th>Fecha</th>
       <th></th>

      </tr>

     </thead>



    </table>

   </div>

   <!--
   PESTAÑA	3
  -->
  <div	id="menu2"	class="tab-pane	fade">
  </div>


  </div><!--	./	tab-content	-->




 </div><!-- ./Form-horizontal -->
</div><!-- ./ panel body-->
</div><!-- ./ panel panel-default -->
</div>
