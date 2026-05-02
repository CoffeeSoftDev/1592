<?php
// session_start();
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
   <!-- <title>Argovia</title> -->

   <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.min.css">
   <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap-datetimepicker.css">
   <link rel="stylesheet" href="recursos/css/bootstrap/css/font-awesome.css">
   <link rel="stylesheet" href="recursos/icon-font/fontello.css">
   <link rel="stylesheet" href="recursos/icon-font/animation.css">
   <link rel="stylesheet" href="recursos/css/formato.css">
   <!-- <link rel="shortcut icon" type="image/png" href="http://www.argovia.com.mx/img/logo.png"> -->
  </head>
  <body>
    <?php include_once('header.php'); ?>

    <div class="container">
     <div  id="">
      <div class="panel panel-default">
       <div class="panel-body">
        <div class="form-horizontal">

           <div	class="tab-content">
            <div class="col-xs-12 col-sm-12">
             <h3><label><span class="fa fa-archive"></span> CATÁLOGO </label></h3>
             <hr>
            </div>

            <ul	class="nav	nav-tabs">
              <li class="active">
                <a class="text-warning" data-toggle="tab"	href="#home" onclick="pane(1);"> <strong>MATERIALES</strong></a>
              </li>
              <li>
                <a	class="text-info" data-toggle="tab"	href="#menu1" onclick="pane(2);"><strong>CONSUMIBLES Y FLORES</strong></a>
              </li>
              <!-- <li>
                <a class="text-info" data-toggle="tab"	href="#menu1" onclick="pane(3);"><strong>FLORES</strong></a>
              </li> -->
            </ul>

            <div class="row">
              <div class="form-group col-sm-12 col-xs-12">
                <div class="content_body"></div>
              </div>
            </div>
          </div>



         </div><!--	./	tab-content	-->

        </div> <!---->

       </div><!-- ./Form-horizontal -->
      </div><!-- ./ panel body-->
     </div><!-- ./ panel panel-default -->
    </div>

    <script src="recursos/js/jquery.js" charset="utf-8"></script>
    <script src="recursos/js/bootstrap/bootstrap.js" charset="utf-8"></script>
    <script src="recursos/js/jquery.numeric.js" charset="utf-8"></script>
    <script src="recursos/js/mtto/catalogo_mtto.js?t=<?=time()?>" charset="utf-8"></script>
  </body>
</html>
