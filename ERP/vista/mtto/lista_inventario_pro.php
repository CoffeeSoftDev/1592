<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
 <meta charset="utf-8">
 <?php include_once('stylesheet.php'); ?>
 <!-- <link rel="stylesheet" href="recursos/css/uiKitPastel.css"> -->
 <!-- dataTables -->
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

</head>

<body>
 <?php include_once('header.php'); ?>

 <div class="container">
  <div class="panel panel-default">
   <div class="panel-body">
    <div class="form-horizontal">


     <div class="form-group">
      <div class="col-sm-6 text-left"><label id="Titulo"><strong><span class="icon-doc-text"></span> LISTA DE INVENTARIOS </strong></label>
      </div>

      <div class="col-sm-6 text-right">
       <div class="btn-group" data-toggle="buttons">
        <button class="active btn btn-outline-success btn-xs " autofocus="true" onclick="verTipoReporte()">
         <input type="radio" value="2" name="rdExtra"><strong>
          <span class="icon-ok-circled"></span>
          Concluidos
         </strong>
        </button>

        <button class="btn btn-outline-danger btn-xs " onclick="verTipoReporte()">
         <input type="radio" value="3" name="rdExtra"><strong>
          <span class="icon-cancel-circled"></span>
          Cancelados        </strong>
         </button>

        </div>
       </div>

      </div>
      <hr>
      <div class="form-group">
       <div class="col-xs-12 col-sm-2">
        <select class="form-control input-xs"   id="txtMes">

         <option value="0">Elige un mes </option>
         <option value="1">Enero</option>
         <option value="2">Febrero</option>
         <option value="3">Marzo</option>
         <option value="4">Abril</option>
         <option value="5">Mayo</option>
         <option value="6">Junio</option>
         <option value="7">Julio</option>
         <option value="8">Agosto</option>
         <option value="9">Septiembre</option>
         <option value="10">Octubre</option>
         <option value="11">Noviembre</option>
         <option value="12">Diciembre</option>
        </select>
       </div>

       <div class="col-xs-12 col-sm-2" id="cbAnio">
        <select class="form-control input-xs"   id="txtAnio">
         <option value="0">Todos los reportes</option>
        </select>
       </div>

       <div class="col-xs-12 col-sm-2">
        <button onclick="verLista()" class="btn btn-outline-info btn-xs" name="button" style="width:100%"><span></span> <span class=" icon-search"></span> Buscar</button>
       </div>

      </div>
      <br>

      <div class="">
       <div class="col-xs-12 col-sm-6" >
        <div class="col-xs-12 col-sm-12 ">
         <table id="tbLista" class="display compact cell-border nowrap nowrap" style="width:100%">
          <thead>
           <th>#</th>
           <th>Folio</th>
           <th>Fecha</th>
           <th>Hora</th>
           <th>Productos</th>
           <th>Autorizo</th>
           <th><span class="fa fa-gear"></span></th>

          </thead>
         </table>
        </div>
       </div><!-- ./ lista de inventarios -->


       <div class="col-xs-12 col-sm-6" id="formato-lista" style="  border: 1px solid #DDDDDD;">
        <br>
        <div class=" text-center">
         <!-- <img src="recursos/img/logo.png" style=" max-width:30%; padding-bottom: 30px; " class="img-res"> -->


        </div>

       </div>

      </div>



     </div>
    </div><!-- ./ panel body-->
   </div><!-- ./ panel panel-default -->
  </div><!-- ./container -->

  <!-- JavaScript -->

  <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>

  <script src="recursos/js/mtto/listaInventario.js?t=<?=time()?>"></script>

  <!--  dataTables -->

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

  <!-- bootstrap -->
  <script src="recursos/js/bootstrap.min.js"></script>

 </body>
 </html>
