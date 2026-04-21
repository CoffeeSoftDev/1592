<!DOCTYPE html>
<html>

<head>
 <title>Pedidos de flores</title>
 <?php include('stylesheet.php'); ?>

 <!-- Autocompleter -->
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <link rel="stylesheet" href="recursos/css/tpv/card.css" />
 <link rel="stylesheet" href="recursos/css/style-invoice.css" />
 <link rel="stylesheet" href="recursos/css/style.css" />
 <link rel="stylesheet" href="https://plugins.erp-varoch.com/ui-ruler.css" />

 <style>
 .ui-autocomplete {
  max-height: 100px;
  overflow-y: auto;
  overflow-x: hidden;
 }

 * html .ui-autocomplete {
  height: 100px;
 }
 </style>
</head>

<body>

 <?php
 include('header.php');
 ?>

 <!-- container -->

 <div class="container-fluid">

  <div class="card " style="min-height:650px">
   <div class="card-body">

    <ul style="font-size:1.1em;" class="nav nav-tabs" role="tablist">
     <li class="nav-item active">
      <a class="nav-link active" href="#pedidos" onclick="consultar_datos()" role="tab" data-toggle="tab"> <i
       class=" icon-doc-text"></i>
       Formato de pedidos </a>
      </li>

      <li class="nav-item">
       <a class="nav-link" onclick="list_folio()" href="#list_pedidos" role="tab" data-toggle="tab">
        <span class="bx bx-list-ul "></span>Historial de pedidos</a>
       </li>

       <li class="nav-item">
        <a class="nav-link" onclick="consultar_lista()" href="#print_list" role="tab" data-toggle="tab">
         <span class=" icon-print-4">Imprimir lista</span></a>
        </li>

        <li class="nav-item">
         <a class="nav-link" onclick="listado_clientes()" href="#Clientes" role="tab" data-toggle="tab">
          <span class="bx bx-user"></span>Clientes</a>
         </li>

        </ul>






        <div class="tab-content ">

          <div class="console col-xs-12 col-sm-12 line">
            READ LINE
          </div>


         <div role="tabpanel" class="tab-pane fade in active" id="pedidos">
          <div class="row ">
           <div class="col-sm-3">

            <label class="">DESTINO: </label>
            <div class="input-group">
             <input style="width:100%;" type="text" class="input-sm form-control" id="txtDestino"
             placeholder="Lugar o Destino" onchange="actualizar_cliente()" onkeypress="BuscarLugar()">
             <span class="input-group-addon input-sm"><i class="bx bxs-truck bx-sm"></i></span>
            </div>
            <span id="txtLugarDir" class="text-muted" style="font-weight:500;"> </span>
           </div>


           <div class="col-sm-3">
            <label class="">FECHA: </label>
            <div class="input-group date calendariopicker">
             <input type="text" class="select_input form-control input-sm" value="" id="txtDate">
             <span class="input-group-addon " id="basic-addon2"><i class="bx bx-calendar-alt fa-2x"></i>
             </span>
            </div>
           </div>

           <div class="col-sm-6 ">
            <label style="height:14px;"></label>

            <div class="">
             <button id="txtNuevo" class="btn btn-success  hide" onclick="CrearPedido()"> <i
              class="bx bx-file-blank ico-md"></i> Nuevo </button>

              <button id="txtSubir" class="btn btn-primary " onclick="subirPedidos()"> <i class='bx bxs-send'></i>
               Terminar pedido </button>

               <label class="btn btn-default" id="btnArchivo">
                <input type="file" accept=".doc,.docx,.pdf" style="display: nonex;" id="txtArchivos" onchange="head_tablas()">
               </label>


               <a class="btn btn-default hide" title="Imprimir " onclick="div_print('plan_acciones_correctivas')">
                <i class="bx bxs-printer"></i> Imprimir </a>
               </div>

              </div>

              <div class="col-sm-2 line text-right hidden">
               <br>
               No de orden: <span class="text-danger format-folio" id="lblFolio">000</span>
              </div>

             </div><!-- row -->


             <div class="row">

              <br>
              <input type="hidden" id="txtFolio">


              <div class=" col-sm-12 col-xs-12 col-centered" id="content-pedidos"></div>

             </div>
            </div><!-- tab1 -->

            <div role="tabpanel" class="tab-pane fade " id="list_pedidos">

             <div class="col-sm-7 ">
              <div class="row">

              <div class="col-sm-10 col-xs-10 ">
                <div id="reportrange" class="input-ranger">
                 <i class="fa fa-calendar"></i>&nbsp;
                 <span></span>
                 <i class="fa fa-caret-down"></i>
                </div>
            </div>

              <div class="col-sm-2 col-xs-6">
                  <a class="btn btn-default " onclick="list_folio()">Buscar</a>
              </div>

            </div>

              <br>
              <div id="tbTicket"></div>
             </div>
             <div class="col-sm-5 " id="content-folio"></div>

            </div><!-- list pedidos-->


            <div role="tabpanel" class="tab-pane fade " id="Clientes">
             <div class="row">
              <div class="col-sm-8 col-xs-12"></div>

              <div class="col-sm-4 col-xs-12 text-right">
               <a class="btn btn-primary" onclick="NuevoCliente()">
                <i class="bx bxs-truck"></i> Nuevo Cliente
               </a>
              </div>

             </div>

             <div class="mt-10" id="content-clientes"></div>
            </div>

            <div role="tabpanel" class="tab-pane fade " id="print_list">

             <div class="row hide">
              <div class="col-sm-4">

               <div class="input-group date calendariopicker hide">
                <input type="text" class="select_input form-control input-sm" value="" id="txtList">
                <span class="input-group-addon " id="basic-addon2"><i class="lnr lnr-calendar-full fa-2x"></i>
                </span>
               </div>


               <div id="txtFecha" class="input-ranger">
                <i class="fa fa-calendar"></i>&nbsp;
                <span></span>
                <i class="fa fa-caret-down"></i>
               </div>
              </div><!-- ./ -->

              <div class="col-sm-2">
               <a class="input-ranger btn btn-primary btn-sm" onclick="consultar_lista()">Consultar</a>
              </div>
             </div> <!-- row -->

             <br>
             <div class="col-xs-12 col-sm-12 ">
              <div id="content-list-pdf"></div>
             </div>

            </div>


           </div>
          </div><!-- format-doc -->











         </div>


         <!--  JavaScript -->
         <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
         <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

         <!-- boot box -->
         <script src="recursos/plugin/bootbox.min.js" charset="utf-8"></script>

         <script src="recursos/validator/js/bootstrapValidator.js"></script>
         <script src="recursos/js/bootstrap.min.js"></script><!-- datapicker traductor-->

         <!-- datetime picker -->
         <script src="recursos/js/moment.js" charset="utf-8"></script>
         <script src="recursos/js/es_moment.js" charset="utf-8"></script>
         <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>

         <!-- dataTables -->
         <script src="recursos/datatables/js/datatables.min.js"></script>

         <!-- select2 -->
         <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
         <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

         <!-- data Ranger Picker -->
         <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
         <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
         <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


         <!-- JS -->
         <script src="recursos/js/flores/productos_complementarios.js?t=<?=time()?>"></script>
         <script src="recursos/js/flores/pedidos.js?t=<?=time()?>"></script>
         <script src="recursos/js/flores/clientes.js?t=<?=time()?>"></script>
         <script src="recursos/js/complementos.js?t=<?=time()?>"></script>


        </body>

        </html>
