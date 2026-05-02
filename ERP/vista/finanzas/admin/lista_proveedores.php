<?php
session_start();
$nivel     = $_SESSION['nivel'];

 ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

 <title>ARG</title>

 <!--Icono-->
 <link rel="shortcut icon" type="image/png" href="">

 <?php include('stylesheet.php'); ?>
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">


</head>
<body>

 <?php include('header.php'); ?>
 <br>

 <div class="container">
  <div class="panel panel-default">
   <div class="panel-body">
    <label><h3><span class="fa fa-truck"></span> LISTA DE PROVEEDORES</h3></label>
    <hr>
    <br>
    <div class="form-group">
     <div class="pull-right">
      <a type="button" class="btn btn-xs btn-info " data-toggle="modal" onclick="addComponent()" data-target="#M02"><span class="fa fa-table"></span> Nuevo Proveedor</a>
     </div>
    </div>
    <div class="row">
     <div class="col-xs-12 col-sm-12 ">
      <br>
      <br>
      <table id="tbProovedores" class="table table-striped table-bordered compact" style="width:100%">
       <thead>
        <tr class="text-center ">
         <th>#</th>
         <th>Proveedor</th>
         <th>Direccion</th>
         <th>Telefono</th>
         <th>Formas de Pago</th>
         <th>RFC</th>
         <!-- <th>Categoria</th> -->
         <!-- <th>Contacto</th> -->
         <th>Contacto</th>
         <th><span class="fa fa-gear"></span></th>

        </tr>
       </thead>
      </table>
     </div>
    </div>
   </div><!-- ./ panel body-->
  </div><!-- ./ panel panel-default -->

 </div><!-- Container -->

 <!-- modal -->
 <?php include('01modal_proveedores.php'); ?>

 <!-- modal -->
 <?php include('02modal_proveedores.php'); ?>

 <!-- modal -->
 <?php include('03modal_proveedores.php'); ?>



 <!-- ./ JavaScript -->
 <script src="recursos/js/jquery.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap/bootstrap.js" charset="utf-8"></script>

 <!-- <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script> -->
<script src="recursos/dataTables/datatables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js
" type="text/javascript"></script>

 <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" charset="utf-8"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"      charset="utf-8"></script>
 <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"      charset="utf-8"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"       charset="utf-8"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"       charset="utf-8"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"       charset="utf-8"></script>
 <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"       charset="utf-8"></script>


 <script src="recursos/js/finanzas/lista_proveedores.js?t=<?=time()?>"></script>
</body>

</html>
