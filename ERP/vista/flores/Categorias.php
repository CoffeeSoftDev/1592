<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>

 <?php include('stylesheet.php'); ?>

 <!--validator-->
 <link rel="stylesheet" href="recursos/plugin/validator/css/bootstrapValidator.css"/>

 <!--  dataTables -->
 <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">
 <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

 <?php include_once('header.php'); ?>


 <div class="container-fluid" style="">
  <div class="row">
   <br>

   <div class="col-sm-12  ">

    <div class="row" id="content-load">
     <div class="col-xs-12 col-sm-6">
      <div class="panel panel-default">
       <div class="panel-body">
        <h4><i class='bx bx-box' ></i> Nueva categoria</h4>
        <hr>

        <form class="form-horizontal " id="FormVacuna" onsubmit="return false">
         <div class="form-group" id="tagVacuna">
          <label for="txtVacuna" class="col-xs-4">NOMBRE</label>
          <div class="col-xs-8">
           <input id="txtCategoria" name="vacuna" type="text" class="form-control input-sm" autofocus>
          </div>
         </div>
         <!-- <div class="form-group" id="tagCad">
         <label for="txtCaducidad" class="control-label col-xs-4">Caducidad</label>
         <div class="col-xs-8">
         <input id="txtCaducidad" name="caducidad" type="text" class="form-control input-sm">
        </div>
       </div> -->

       <!-- <div class="form-group" id="content-rp">

      </div> -->


      <div class="form-group ">
       <div class="col-xs-offset-4 col-sm-8">
        <button style="width:100%"  type="submit" class="btn btn-primary" onclick="GuardarCategoria()">Agregar</button>
       </div>
      </div>
     </form>

     <hr>

     <div id="tbCategoria"></div>

    </div><!-- ./ panel body-->
   </div><!-- ./ panel panel-default -->
  </div><!-- col -->





  <div class="col-xs-12 col-sm-6">
   <div class="panel panel-default">
    <div class="panel-body">
     <h4><i class='bx bx-package' ></i> Nueva especie</h4>
     <hr>
     <form class="form-horizontal " id="FormVacuna" onsubmit="return false">
      <div class="form-group" id="tagSub">
       <label for="txtSub" class="col-xs-4">ESPECIE:</label>
       <div class="col-xs-8">
        <input id="txtSub" name="vacuna" type="text" class="form-control input-sm">
       </div>
      </div>

      <div class="form-group" id="tagCad">
       <label for="txtCaducidad" class=" col-xs-4"> FAMILIA:</label>
       <div class="col-xs-8" id="cbGrupos">

       </div>
      </div>

      <div class="form-group ">
       <div class="col-xs-offset-4 col-sm-8">
        <button style="width:100%"  type="submit" class="btn btn-primary" onclick="GuardarSub()">Agregar</button>
       </div>
      </div>
     </form>
     <div id="tbSub"></div>

    </div><!-- ./ panel body-->
   </div><!-- ./ panel panel-default -->
  </div><!-- col -->

 </div>
</div><!-- ./col-sm-10 -->
</div><!-- ./row -->
</div>






<?php
// include('vista/modal_cliente.php');
?>
<!-- JavaScript -->
<script src = "recursos/js/jquery-1.12.3.js"></script>


<!-- dataTables -->
<script src="recursos/datatables/js/datatables.min.js"></script>

<!-- Form Validator -->
<script src="recursos/plugin/validator/js/bootstrapValidator.js"></script>

<script src="recursos/js/bootstrap.min.js"></script>
<script src="recursos/js/flores/catalogo.js?t=<?=time()?>"></script>


</body>
</html>
