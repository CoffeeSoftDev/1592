<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
 <?php include_once('stylesheet.php'); ?>

 <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>

 <!-- DataTables -->

 <link rel="stylesheet"  href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
 <link rel="stylesheet"  href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">


</head>
<body>

 <?php
 include_once('header.php');
 ?>

 <div class="container">
  <div class="panel panel-default">
   <div class="panel-body">

    <div class="form-horizontal">
     <div class="form-group">
      <div class="col-sm-12 text-left"><label id="Titulo"> <strong> FOLIOS DE EQUIPOS </strong></label>
       <hr>
      </div>
     </div>

     <div class="form-group">


      <div class="col-sm-2 col-xs-12" id="cbCat">
       <select class="form-control input-xs" name=""
       id="txtCategoria" onchange="">
       <option value="0"> Selecciona una categoria</option>
      </select>
     </div><!-- ./ -->

     <div class="col-sm-2 col-xs-12" id="cbArea">
      <select class="form-control input-xs" name="" id="txtArea" onchange="ver_tabla(1); ver_tabla(2);">
       <option value="0"> Selecciona un area</option>
      </select>
     </div><!-- ./ -->

     <div class="col-sm-2 col-xs-12">
      <button class="btn btn-default btn-xs col-xs-12 col-sm-12 " onclick="verFolios()">
       <span class=" icon-grid"></span> Vista Previa
      </button>
     </div> <!-- -->

     <div class="col-sm-2 col-xs-12">
      <button class="btn btn-default btn-xs col-xs-12 col-sm-12 " onclick="ImprimirFolios()">
       <span class="icon-print-3"></span> Imprimir folios
      </button>
     </div> <!-- -->

    </div><!-- ./Form-group  -->


    <br>

    <div class="col-xs-12" id="">

       <table id="tbFolios" class="hover cell-border nowrap" style="width:100%">
        <thead>
         <tr>
          <th>Codigo</th>
          <th>Nombre</th>
          <th>Categoria</th>
          <th>Area</th>
          <th>Cantidad</th>
          <th>Vista</th>
         </tr>
        </thead>
       </table>


    </div>

   </div><!-- ./Form-horizontal -->
  </div>
 </div><!-- ./ panel panel-default -->
</div><!-- ./container -->

<!-- JavaScript -->
<script src="recursos/js/mtto/materiales_folio.js?t=<?=time()?>"></script>

<!-- dataTables-->

  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>


<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"      ></script>
<script src="recursos/js/bootstrap.min.js"></script>

</body>
</html>
