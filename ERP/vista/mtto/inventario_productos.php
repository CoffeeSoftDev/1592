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

 <!-- add -->
 <link rel="stylesheet" type="text/css" href="recursos/add/wan-spinner.css">


</head>
<body>
 <?php include_once('header.php'); ?>

 <div class="container">
  <div class="panel panel-default">
   <div class="panel-body">
    <div class="form-horizontal">

     <!-- Titulo & Folio -->
     <div class="form-group">
      <div class="col-sm-6 text-left"><label id="Titulo">  <strong> INVENTARIO </strong></label></div>

      <div class="col-sm-6 text-right">
       <label class=" Folio" id="txtTitulo">  <strong>  P-001 </strong></label>

       <p id="fechaActual" style="font-weight:bold"> -/-/- </p>
      </div>
     </div>

     <hr>

     <!-- Botones -->

     <div class="form-group" >

      <div class="col-sm-2 col-xs-12" id="cbZona">
       <select class="form-control input-xs" id="txtZona"></select>
      </div>

      <div class="col-sm-2 col-xs-12" id="cbArea">
       <select class="form-control input-xs" id="txtArea">
       </select>
      </div>




      <!--  -->
      <div class="col-sm-6 ">
       <a class="btn btn-outline-success btn-xs" id="btnNuevoInventario" onclick="NuevoInventario()"><span class=" icon-doc-text"></span> Nuevo</a>


       <a class="btn btn-outline-info btn-xs" id="btnSave" onclick="UImodal(2)"
       data-toggle="modal" data-controls-modal="ModalUI" data-backdrop="static" data-keyboard="false" data-target="#ModalUI">
       <span class=" icon-floppy" ></span>Guardar</a>


       <a  class="btn btn-outline-danger btn-xs" id="btnCancel" onclick="UImodal(1)" data-toggle="modal" data-controls-modal="ModalUI" data-backdrop="static" data-keyboard="false" data-target="#ModalUI"  ><span class="fa fa-ban"></span> Cancelar</a>

       <a  class="btn btn-secondary btn-xs" id="btnCancel" onclick="Formato()" data-toggle="modal" data-controls-modal="ModalUI" data-backdrop="static" data-keyboard="false" data-target="#ModalUI"  ><span class="icon-doc-inv"></span> Productos </a>

      </div>




      <div class="col-sm-2 col-xs-12">
       <div class="input-group">
        <input type="text" class="form-control input-xs" placeholder="Busqueda" id="nombre" autocomplete="off" name="nombre" onkeyup="buscarLista()">
        <span class="input-group-addon icon-search-1 input-xs" id="basic-addon1"></span>
       </div>
      </div><!-- ./col-sm-3 -->

     </div>


     <div class="col-xs-12 col-sm-12" id="txtLoad">

     </div>


     <div class="form-group">
      <div class="col-sm-7 col-xs-12" >
       <div id="tbFormato1"></div>
      </div>

      <div class="col-sm-5 col-xs-12" id="tbFormato2">
      </div>
     </div>

    </div><!-- ./Form-horizontal -->
   </div><!-- ./ panel body-->
  </div><!-- ./ panel panel-default -->
 </div><!-- ./container -->





 <!-- Modal UI -->
 <div id="ModalUI" class="modal fade" role="dialog">
  <div class="modal-dialog " id="modal-ui">
   <!-- Modal content-->
   <div class="modal-content" id="code">
   </div>
  </div>
 </div>


 <!-- Modal -->

 <div class="modal fade" id="Producto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" id="mdialTamanio">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close form-horizontal" data-dismiss="modal" onclick="ver_tabla();">&times;</button>
     <h4 class="modal-title text-center"><span class="icon-shopping-basket"></span> Nuevo producto</h4>
    </div>
    <div class="modal-body">
     <div id="okRegistro"></div>
     <div id="codeFormulario"></div>
    </div>
   </div>
  </div>

 </div>




 <!-- JavaScript -->
 <script src="recursos/js/mtto/RequisicionProductos.js?t=<?=time()?>"></script>

 <!-- Form Validator -->
 <script src="recursos/validator/js/bootstrapValidator.js"></script>

 <!-- add -->
 <script src="recursos/add/wan-spinner.js"></script>

 <!-- time  -->
 <script src="recursos/js/moment.js"></script>
 <script src="recursos/js/combodate.js"></script>

 <!-- bootstrap -->
 <script src="recursos/js/bootstrap.min.js"></script>

</body>
</html>
