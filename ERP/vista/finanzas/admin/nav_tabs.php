

<div class="container contenido">
 <?php

 if ($nivel==1) {
  echo '
  <div class="alert alert-warning" role="alert">
  Usted esta iniciando sesión como <strong> Dirección General </strong>
  </div>
  ';

 }


 ?>

 <div class="panel panel-default" id="content-pane">
  <div class="panel-body">

   <div class="row">



    <div class="form-group col-sm-3 col-xs-12">
     <label class=" col-sm-12">Empresas</label>
     <div class="col-xs-12 col-sm-12">
      <select class="form-control input-sm" data-style="btn-primary" id="Empresas" onChange="fondo_caja();">
       <?php
       foreach ($udn as $row) {
        if ($idE ==$row[0]) {
         echo "<option value=".$row[0]." selected>".$row[1]."</option>";
        }else {
         echo "<option value=".$row[0].">".$row[1]."</option>";
        }
       }
       ?>
      </select>
     </div>
    </div>

    <div class="form-group col-sm-3 col-xs-12">
     <label class=" col-sm-12">Saldo Inicial *</label>
     <div class="col-xs-12 col-sm-12">
      <div class="input-group">
       <span class="input-group-addon input-sm" id="basic-addon2">
        <label class="icon-dollar"></label></span>
        <input type="text" class="form-control input-sm" id="SI_sobres" disabled>
       </div>
      </div>
     </div>



     <div class="form-group col-sm-3 col-xs-12">
      <label class=" col-sm-12">Saldo Final *</label>
      <div class="col-xs-12 col-sm-12">
       <div class="input-group">
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
        <input type="text" class="form-control input-sm" id="SF_sobres" disabled>
       </div>
      </div>
     </div>
    </div>

    <!-- NAV-TABS -->

    <div class="row">
     <div class="form-group col-sm-12 col-xs-12">

      <ul class="nav nav-tabs nav-primary">

       <!-- <li onclick="Get(1);" class="active">
        <a data-toggle="tab"  href="#home"><strong>REPORTE GRAL</strong></a>
       </li> -->

       <li onclick="Get(3);">
        <a data-toggle="tab" href="#menu1"><strong>INGRESOS</strong></a>
       </li>

       <!-- <li onclick="Get(8);">
        <a data-toggle="tab" href="#menu8"><strong>CHEQUES</strong></a>
       </li> -->

        <li onclick="list_cheques()">
          <a data-toggle="tab" href="#menu8"><strong>CHEQUES</strong></a>
        </li>

       <li onclick="Get(2);">
        <a  data-toggle="tab" href="#menu2"><strong>GASTOS</strong></a>
       </li>

       <li onclick="Get(12);">
        <a  data-toggle="tab" href="#menu7"><strong>COMPRAS</strong></a>
       </li>

       <li onclick="Get(4);">
        <a  data-toggle="tab" href="#menu3"><strong>ARCHIVOS</strong></a>
       </li>

       <li onclick="Get(6);">
        <a  data-toggle="tab" href="#menu4"><strong> PROVEEDOR</strong></a>
       </li>


       <li onclick="Get(7);">
        <a  data-toggle="tab" href="#menu5"><strong> RETIROS  </strong></a>
       </li>

      </ul>


      <!--Contenido  -->


      <div class="tab-content">
       <div class="">

        <div class="col-xs-6 col-sm-3">

         <div class="input-group date calendariopicker">
          <input type="text" class="select_input form-control input-sm"
          value="<?php echo $inicio; ?>" id="date1">
          <span class="input-group-addon input-sm" id="basic-addon2">
           <label class="fa fa-calendar"></label> </span>
          </div>

         </div><!-- ./ -->

         <div class="col-xs-6 col-sm-3">

          <div class="input-group date calendariopicker">
           <input type="text" class="select_input form-control input-sm"
           value="<?php echo $now;?>" id="date2">
           <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
          </div>

         </div><!-- ./ -->

         <div class="col-xs-6 col-sm-2 col-sm-offset-4" id="pane-btn">

         </div><!-- ./ -->
        </div>




        <!--   Tab 1  -->
        <div id="home" class="tab-pane fade in active">
         <br>

         <div class="form-group">
          <!-- <div class="col-sm-2 col-sm-offset-10 col-xs-12">
          <button type="button" class="btn btn-info btn-sm col-sm-12 col-xs-12" onclick="Get(1);" id="Act">Actualizar Caratula</button>
         </div> -->

         <!-- <div class="col-sm-2">
         <button  type="button" class="col-xs-12 btn btn-default btn-xs" onclick="Print_gral()">Imprimir</button>
        </div> -->
       </div>

       <div id="capa"></div>
      </div>

      <!--  TAB 2  -->

      <div id="menu1" class="tab-pane fade">
       <br>

       <div class="col-xs-12">
        <div class="tab_content">

         <!-- <div class="col-xs-12 col-sm-3 col-sm-offset-9">
          <button type="button" class="btn btn-info btn-sm col-sm-12 col-xs-12" onclick="panel(1);" id="Act">Buscar ingresos</button>
         </div> -->

        </div> <!-- ./ form-group -->
       </div><!-- ./ col-xs-12 -->

       <div id="date"></div>
      </div>

      <div id="menu8"  class="tab-pane fade">
       <button type="button" class="btn btn-sm btn-primary col-sm-2 col-sm-offset-10 text-right" onclick="list_cheques();">Actualizar Registros</button>
       <div id="cheque"></div>
<br>
     <div style="padding-top:20px;" class="row text-center line" id="content-cheques"></div>
      
      </div>


       <div id="tab-cheques" class="tab-pane fade">
              <br>
              <br>
              <div class="row text-center" id="content-cheques ">

              </div>
        </div>

      <!-- CHEQUES-CTA -->
      <div id="menu9"  class="tab-pane fade">
       <br>
       <br>
       <div class="row form-group ">
        <div class="col-sm-7 col-xs-12"></div>

        <div class="col-sm-3 col-dm-3 col-xs-12">
         <input style="width:100%" type="file" name="excel_file" class="btn btn-default btn-xs" id="excel_file" accept=".xls" onchange="cargaMasiva(1)">
        </div>

        <div class="col-sm-2 col-xs-12">

         <a onclick="uiData(1)" class="btn btn-success btn-sm" title="Importar" id="btnCliente">
          <span class="icon-upload"></span> Cargar</a>

          <a class="btn btn-default btn-sm " title="Nueva cita " id="btnBuscar" onclick="Nueva_Cita()">
           <span class="icon-calendar-plus-o"></span> </a>

          </div>
         </div>

         <!-- <button type="button" class="btn btn-sm btn-info col-sm-3 col-sm-offset-9 text-right" onclick="Get(9);">Actualizar tabla</button> -->
         <div id="estado_cta"></div>
        </div>



        <!--  GASTOS-->
        <div id="menu2" class="tab-pane fade">
         <br>
         <div id="gastos"></div>
        </div>

        <!--  PESTAÑA ARCHIVOS -->
        <div id="menu3" class="tab-pane fade">
         <br>
         <div id="archivos"></div>
        </div>

        <!-- PESTAÑA PROVEEDORES -->
        <div id="menu4" class="tab-pane fade">
         <br>

         <div id="proveedores"></div>
        </div>

        <!--  PESTAÑA RETIROS  -->

        <div id="menu5" class="tab-pane fade">
         <br>
         <div id="Retiros"></div>
        </div>

        <!--  GASTOS COMPRAS  -->

        <div id="menu7" class="tab-pane fade">
         <br>
         <div id="GastosCompras"></div>
        </div>
       </div>



      </div>
     </div>



    </div>
   </div>
  </div>
