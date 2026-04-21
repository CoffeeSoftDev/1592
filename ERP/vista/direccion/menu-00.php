<?php
session_start();
$nivel   = $_SESSION['nivel'];

if ($_SESSION['nivel'] == 1 ) { // Sesión finanzas

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>

 <link rel="stylesheet" href="recursos/css/tabla_responsive.css">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta charset="utf-8">
 <title> Argovia Finca Resort</title>

 <!--Icono-->
 <link rel="shortcut icon" href="http://www.argovia.com.mx/img/favicon.ico">



 <!-- Bootstrap -->
 <link rel="stylesheet" href="recursos/css/bootstrap/css/font-awesome.min.css">
 <!-- <link rel="stylesheet" href="recursos/css/direccion/direccion.css"> -->
 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.min.css">
 <link rel="stylesheet" href="recursos/css/direccion/admin.css">
 <link rel="stylesheet" href="recursos/css/formato.css">
 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap-datetimepicker.css">
 <!-- <link rel="stylesheet" href="recursos/css/scrollbar.css"> -->
 <link rel="stylesheet" href="recursos/icon-font/fontello.css">
 <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'>

 <!-- dataTables -->
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="recursos/dataTables/css/datatables.min.css">

 <link rel="stylesheet" href="recursos/css/argovia_base.css">

<style media="screen">
.table tbody tr>td, .table tbody tr>th, .table tfoot tr>td, .table tfoot tr>th, .table thead tr>td, .table thead tr>th {
 border: 1px solid #ccc5b9;
}

.table-bordered {
 border: 1px solid #ccc5b9;
 /* border: 1px solid #323231; */
}
</style>

</head>

<body>

 <?php include('navbar-custom.php'); ?>
 <?php include('menu_sidebar.php'); ?>

 <div class="container-fluid">

  <div class="row">
   <br>

   <div class="col-sm-10 col-sm-offset-2 main">
    <div  class="" id="panel-ant">
     <div class="panel panel-default" id="content-pane">
      <div class="panel-body">
       <!--
       tabs Panel
      -->
      <div class="">
       <br>
       <div class="form-group row">

        <label
        class="col-sm-3 col-xs-12 col-form-label" id="Titulo"><span class="fa fa-area-chart"></span> INGRESOS ANUALES</label>


        <!-- <label class="col-sm-1  text-right col-form-label"> DE: </label> -->

        <div class="col-sm-2">
         <div  id="cbAnio1" >

          <select class="form-control input-sm" id="txtAnio1">
           <option value="0">Selecciona un año</option>
          </select>

         </div>
        </div>

        <div class="col-sm-2 ">
         <div  id="cbAnio2">

          <select class="form-control input-sm" id="txtAnio2">
           <option value="0">Selecciona un año</option>
          </select>
         </div>
        </div>

        <div class="col-sm-3">

         <select class="form-control input-sm" id="tipoReporte">
          <option value="0">ANUAL</option>
          <option value="1">COMPARAR TOTALES</option>
          <option value="2">GRAFICA TOTAL </option>
          <option value="3">COMPARACIÓN POR AREAS</option>
          <option value="4">TOTAL POR AREAS</option>
          <option value="5">GRAFICA POR AREAS</option>
         </select>

        </div>


        <div class="col-sm-2">
         <a  class="btn btn-primary btn-xs" style="width:100%" onclick="verFormato()">
          <span class="fa fa-search"></span> Busqueda</a>
        </div>

       </div>

       <!-- head -->


       <div class="row">
        <div class="col-md-12 col-xs-12" id="ViewTablero">
        </div>
        <div class="col-md-12 col-xs-12" id="ViewPanel">
         <center>
         <br>
         <br>
         <br>
         <!-- <img src="recursos/img/logo_c.png" style=" max-width:30%; " class="img-res"> -->
        </center>
        </div>
       </div>

      </div>
     </div><!-- -->
    </div>
   </div>



  </div><!-- ./main -->
 </div><!-- ./ row -->
</div><!-- ./ container-fluid -->



<?php
include('footer.php');
?>

<script src="recursos/js/Chart.js"></script>
<script src="recursos/js/chart.js-php.js"></script>
<script src="recursos/js/direccion/metas.js?t=<?=time()?>"></script>
</body>

</html>
<?php

 } else { // sesíon incorrecta
  $nivel = $_SESSION['nivel'];
  $area  = $_SESSION['area'];

  echo '
  <script src="recursos/js/jquery.js"></script>
  <script src="recursos/js/index.js"></script>
  <script> nivel(\''.$nivel.'\',\''.$area.'\',\'\'); </script>
  <div class="res"></div>
  ';
 }


 ?>
