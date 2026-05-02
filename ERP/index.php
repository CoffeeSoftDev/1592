<?php
session_start();

if ( isset($_SESSION['nivel']) ) {
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
 <html>
 <head>
  <meta charset="utf-8">
  <!-- <title>Argovia</title> -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">

  <link rel="shortcut icon" href="https://15-92.com/ERP3/src/img/logos/logo_icon.png">
  <!-- <title>Argovia Finca Resort</title> -->
  <link rel="stylesheet" href="recursos/icon-font/animation.css">
  <link rel="stylesheet" href="recursos/icon-font/fontello.css">

  <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.css">
  <!-- <link rel="stylesheet" href="recursos/css/sitio/base.css"> -->
  <link rel="stylesheet" href="recursos/css/admin.css">
 </head>



 <body>
  <main class="main">
   <div class="panel_main">
    <div class="panel panel-arg login">
     <div class="panel-heading text-center">
      <h1 class="panel-title">
       <img src="recursos/img/logo_c.png" alt="Argovia" height="57px"></h1>
      </div>

      <div class="panel-body">
       <div class="form-group col-sm-12 col-xs-12 Group_user">
        <label for="user" class="control-label">Usuario</label>
        <input type="text" class="form-control" placeholder="Usuario" autocomplete="off" id="user" onkeypress="if(event.keyCode == 13) login();">
       </div>

       <div class="form-group col-sm-12 col-xs-12 Group_pass">
        <label for="pass" class="control-label">Contraseña</label>
        <input type="password" class="form-control" placeholder="Contraseña" autocomplete="off" id="pass" onkeypress="if(event.keyCode == 13) login();">
       </div>


       <div class="form-group col-sm-12 col-xs-12 text-center Res_Login"></div>
       <div class="form-group col-sm-12 col-xs-12">
        <button type="button" class="btn btn-green col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1" onclick="login();">Iniciar Sesión</button>
       </div>
      </div>
     </div>
    </div>
   </main>
   <script src="recursos/js/jquery.js" charset="utf-8"></script>
   <script src="recursos/js/index.js?t=<?=time()?>" charset="utf-8"></script>
  </body>
  </html>
  <?php
 }
 ?>
