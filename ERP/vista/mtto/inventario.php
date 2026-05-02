<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>

 <?php include_once('stylesheet.php'); ?>

 <!-- Autocompleter -->
 <link href = "https://code.jquery.com/ui/1.10.4/themes/Start/jquery-ui.css" rel = "stylesheet">
 <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
 <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 <!-- Validator -->
 <link rel="stylesheet" href="recursos/validator/css/bootstrapValidator.css"/>

 <!-- DataTables -->
 <link rel="stylesheet"  href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
 <link rel="stylesheet"  href="https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.dataTables.min.css">

</head>
<body>
 <?php include_once('header.php'); ?>

 <div class="container">
  <div class="panel panel-default">
   <div class="panel-body">
    <div class="form-horizontal">


     <div class="form-group">
      <div class="col-sm-12 text-left"><label id="Titulo">   <strong><span class="icon-shopping-basket"></span> PRODUCTOS </strong></label></div>
     </div>
     <hr>
     <div class=" pull-right">
      <a data-toggle='modal'
      data-backdrop="static" data-target='#Producto' onclick="verModal()" class="btn btn-outline-success"><span class="icon-plus-circled"></span> Agregar productos</a>
     </div>
     <br>
     <br>
     <div class="col-xs-12" >
      <table class="display cell-border nowrap" style="width:100%" id="tbProductos">
       <thead>
        <tr>
         <th> Codigo </th>
         <th> Zona </th>
         <th> Familia </th>
         <th> Nombre </th>
         <th> Unidad </th>
         <th> Costo Ent  </th>
         <th> Costo Sal  </th>
         <th> Stock min</th>
         <th> Disponible</th>
         <th> Fecha</th>
         <th  > <span class="fa fa-gear"></span> </th>
        </tr>
       </thead>
      </table>

     </div>
    </div>

   </div><!-- ./Form-horizontal -->


  </div><!-- ./ panel body-->
 </div><!-- ./ panel panel-default -->
</div><!-- ./container -->


<!--  Modal -->

<div class="modal fade" id="Producto" tabindex="-1" role="dialog" >
 <div class="modal-dialog" id="mdialTamanio">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal" onclick="ver_tabla();">&times;</button>
    <h4 class="modal-title text-center"><span class="icon-shopping-basket"></span> Nuevo producto</h4>
   </div>
   <div class="modal-body">
    <div id="okRegistro"></div>
    <div id="code"></div>
   </div>
  </div>
 </div>

</div>


<!--  Modal Modificar-->

<div class="modal fade" id="ModProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" id="mdialTamanio">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal" onclick="ver_tabla();">&times;</button>
    <h4 class="modal-title text-center"><span class="icon-shopping-basket"></span> Modificar producto</h4>
   </div>
   <div class="modal-body">
    <div id="okRegistroU"></div>
    <div id="codeUp">
    </div><!-- codeUp -->
   </div>
  </div>
 </div>

</div>


<div class="modal fade" id="baja" role="dialog" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 <div class="modal-dialog" id="mdialTamanio">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal" onclick="ver_tabla();">&times;</button>
    <h4 class="modal-title text-center">Baja de Productos</h4>
   </div>
   <div class="modal-body">
    <div  id="baja_code">

    </div>
   </div>
  </div>
 </div>
</div>


<!-- JavaScript -->
<script src="recursos/js/mtto/inventario.js?t=<?=time()?>"></script>


<!-- dataTables-->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"      charset="utf-8"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"      charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"       charset="utf-8"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"      ></script>

<!-- Form Validator -->
<script src="recursos/validator/js/bootstrapValidator.js"></script>

<!-- time  -->
<script src="recursos/js/moment.js"></script>
<script src="recursos/js/combodate.js"></script>

<!-- bootstrap -->
<script src="recursos/js/bootstrap.min.js"></script>

</body>
</html>
