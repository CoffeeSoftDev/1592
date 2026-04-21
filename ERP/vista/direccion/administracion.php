<?php
  session_start();

  if ( $_SESSION['nivel'] != 1 && $_SESSION['nivel'] != 0 ) {
    $nivel = $_SESSION['nivel'];
    $area = $_SESSION['area'];

   echo '
     <script src="recursos/js/jquery.js"></script>
     <script src="recursos/js/index.js"></script>
     <script> nivel(\''.$nivel.'\',\''.$area.'\',\'\'); </script>
     <div class="res"></div>
     ';
  }
  else {
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

 <title>#</title>

 <!--Icono-->

 <?php include('stylesheets.php'); ?>
 <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.css">

</head>
<body>

 <?php include('navbar-custom.php'); ?>
 <?php include('sidebar-collapse.php'); ?>

 <!--  -->
 <div class="container-fluid">
  <div class="row">
   <br>
   <div class="col-sm-10 col-sm-offset-2 col-xs-12 main">
    <div  class="" id="panel-ant">
     <div class="panel panel-default">
      <div class="panel-body">

       <!--
       tabs-navs
      -->

      <ul class="nav nav-tabs">
       <li class="active">
        <a class="text-warning"
        data-toggle="tab" href="#home" onclick="dataUsers();"> <strong>Usuarios</strong></a>
       </li>
       <li><a class="text-info"
        data-toggle="tab" href="#menu1" onclick="Nivel_Area();"><strong>Areas y Niveles</strong></a></li>
        <li><a class="text-success"
         data-toggle="tab" href="#menu2" onclick=""><strong>#</strong></a></li>
        </ul>

        <div class="tab-content">

         <div id="home" class="tab-pane fade in active">
          <br>
          <div class="row">

           <div class="col-xs-12 col-sm-12 text-right">
            <button type="button" onclick="CargarCB()" class="btn btn-success btn-xs" data-toggle="modal" data-target="#M3">
             <span class="fa fa-user"></span> Agregar Usuario</button>
           </div>
           <br>

           <br>
           <div class="col-xs-12 col-sm-12 ">
            <table id="tbUsuarios" class="display compact nowrap" style="width:100%">
             <thead>
              <th>#</th>
              <th>USUARIO</th>
              <th>NIVEL</th>
              <th>CORREO</th>
              <th>NOMBRE</th>
              <th>PERMISO</th>
              <th>UDN</th>
              <th></th>
              <th></th>

             </thead>
            </table>
           </div>

          </div>

         </div><!-- ./ PESTAÑA 1 -->


         <div id="menu1" class="tab-pane fade">
          <br>

          <div class="row">
           <div class="col-sm-6">
            <br>
            <table id="tbArea" class="display compact nowrap" style="width:100%">
             <thead>
              <th>#</th>
              <th>Area</th>

             </thead>
            </table>
           </div>

           <div class="col-sm-6">
            <br>
            <table id="tbNivel" class="display compact nowrap" style="width:100%">
             <thead>
              <th>#</th>
              <th>Nivel</th>

             </thead>
            </table>
           </div>

          </div>


         </div><!-- ./ PESTAÑA 2 -->


         <div id="menu2" class="tab-pane fade">
          <br>

         </div><!-- ./ PESTAÑA 3  -->
        </div>


       </div><!-- ./ panel body-->
      </div><!-- ./ panel panel-default -->
     </div>
    </div>
   </div>
  </div>





  <?php
  include('modalUsuarios.php');
  ?>

  <?php
  include('footer.php');
  ?>
  <script src="recursos/js/direccion/administracion.js?t=<?=time()?>"></script>
>


  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.js"></script>

  <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" charset="utf-8"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js"      charset="utf-8"></script>
  <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"      charset="utf-8"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"       charset="utf-8"></script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


 </body>
 </html>
<?php } ?>
