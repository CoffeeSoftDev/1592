<!DOCTYPE html>
<html>
<head>
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <!-- <title>Argovia Finca Resort</title>
-->
<link rel="shortcut icon" href="http://www.argovia.com.mx/img/favicon.ico">


<?php
include_once('stylesheets.php');
?>


<!-- CSS -->
<link rel="stylesheet" href="recursos/css/direccion/dashboard.css">


<!--
Data Tables
-->

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
<!--  button / Data Tables -->
<link rel="stylesheet"  href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css">

</head>
<body>

 <?php include('navbar-custom.php'); ?>
 <?php
 include('sidebar-collapse.php');
 ?>


 <div class="container-fluid">
  <div class="row">
   <br>


   <div class="col-sm-10 col-sm-offset-2 main" >

    <div class="row">
     <div class="col-lg-3 col-md-6 col-xs-12">
      <div class="panel panel-notification ">
       <div class="panel-heading">
        <div class="row">
         <div class="col-xs-3 ">
          <p></p>
          <i class="icon-wrench-1 fa-2x text-primary"></i>
         </div>

         <div class="col-xs-9 text-right">
          <div class="col-xs-12 col-sm-12  text-center text-primary" id="txtTotalProductos" >0</div>
          <div class="col-xs-12 col-sm-12 text-center" ><h5><a onclick="cargarTabla(5)" class="text-dark">ITEMS </a> </h5></div>

         </div>
        </div>
       </div>
      </div>
     </div>

     <!-- notificacion 2 -->
     <div class="col-lg-3 col-md-6 col-xs-12">
      <div class="panel panel-notification ">
       <div class="panel-heading">
        <div class="row">
         <div class="col-xs-3 ">
          <p></p>
          <i  class=" icon-dollar fa-2x text-success"></i>
         </div>
         <div class="col-xs-9 text-right">

          <div class="col-xs-12 col-sm-12  text-center text-success" id="txtTotal" >$ 150.00</div>
          <div class="col-xs-12 col-sm-12 text-center "><h5><a onclick="cargarTabla(6)" class="text-dark">Costo</a>  </h5></div>

         </div>
        </div>
       </div>
      </div>
     </div>

     <!-- notificacion 3 -->
     <div class="col-lg-3 col-md-6 col-xs-12">
      <div class="panel panel-notification ">
       <div class="panel-heading">
        <div class="row">
         <div class="col-xs-3 ">
          <p></p>
          <i class=" icon-thumbs-down fa-2x text-danger" ><label></label></i>
         </div>
         <div class="col-xs-9 text-right">

          <div class="col-xs-12 col-sm-12  text-center text-danger" id="txtBajo"> 0</div>
          <div class="col-xs-12 col-sm-12 text-center "><h5><a class="text-dark" onclick="cargarTabla(7)">Dados de baja</a>  </h5></div>

         </div>

        </div>
       </div>
      </div>
     </div>
     <!-- notificacion 3 -->
     <div class="col-lg-3 col-md-6 col-xs-12">
      <div class="panel panel-notification ">
       <div class="panel-heading">
        <div class="row">
         <div class="col-xs-3 ">
          <p></p>
          <a class=" icon-basket-1 fa-2x text-warning" onclick=""><label></label></a>
         </div>
         <div class="col-xs-9 text-right">

          <span class="colorlib-counter js-counter" data-from="0" data-to="1539" data-speed="5000" data-refresh-interval="50">1539</span>

          <div class="col-xs-12 col-sm-12  text-center text-warning animate-box fadeInUp animated-fast" id="txtHoy" > 0 </div>
          <div class="col-xs-12 col-sm-12 text-center "><h5><a class="text-dark" onclick="cargarTabla(8)"> Agregados hoy </a> </h5></div>

         </div>

        </div>
       </div>
      </div>
     </div>

    </div><!--row -->



    <div class="panel panel-default">
     <div class="panel-body">

      <div class="desc">
       <span class="colorlib-counter js-counter" data-from="0" data-to="1539" data-speed="5000" data-refresh-interval="50">1539</span>
       <span class="colorlib-counter-label">Courses</span>
      </div>


      <div class="form-horizontal">
       <div class="col-xs-12 col-sm-12">
        <h3><label><span class="icon-wrench-1"></span> MATERIALES </label></h3>
        <hr>
        <br>
       </div>
       <div class="col-xs-12 col-sm-12 " id="ZonaDatos">

        <table class="table table-striped table-bordered" style="width:100%" id="tbProductos">
         <thead>
          <tr>
           <th> ZONA </th>
           <th> Productos </th>
           <th> TOTAL GENERAL </th>


          </tr>
         </thead>

        </table>
        <!-- <hr> -->




       </div><!-- ./Form-horizontal -->
      </div><!-- ./ panel body-->
     </div><!-- ./ panel panel-default -->



    </div><!-- col-sm-10 -->
   </div><!-- row -->








  </div> <!-- container-fluid -->






  <!-- JavaScript -->
  <script src="recursos/js/jquery.countTo.js"></script>

  <script src="https://code.jquery.com/jquery-3.3.1.js" ></script>
  <script src="recursos/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>

  <!--  dataTables button -->
  <script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" ></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" ></script>
  <script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" ></script>




  <script src="recursos/js/direccion/productos_admin.js"></script>
  <script src="recursos/js/direccion/materiales_admin.js"></script>
 </body>
 </html>
