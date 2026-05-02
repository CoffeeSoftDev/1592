<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">

 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta http-equiv="Expires" content="0">
 <meta http-equiv="Last-Modified" content="0">
 <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
 <meta http-equiv="Pragma" content="no-cache">

 <?php include('stylesheet.php'); ?>

 <!--  dataTables -->
 <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">

 <!--validator-->
 <link rel="stylesheet" href="recursos/validator/css/bootstrapValidator.css"/>

 <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>

 <!-- boot box -->
 <script src="recursos/plugin/bootbox.min.js" charset="utf-8"></script>


</head>
<body>

 <?php
 include('header.php');
 ?>

 <div class="container">
  <div class="row">
   <br>



   <div class="panel panel-default">
    <div class="panel-body" id="content-pane">

     <div class=" col-xs-12 col-sm-12">
      <h3><span class="icon-upload" ></span> Carga masiva </h3>
      <hr>
     </div>

     <div class="form-group">

      <div class="col-sm-3" id="cbZona">

      </div>
      <div class="form-group">

       <div class="col-sm-3" id="cbArea">
       </div>


       <div class="col-sm-4 col-xs-12 ">
        <form mehtod="post" id="export_excel">
         <!-- <label>Importar hoja de excel </label> -->
         <input style="width:100%" type="file" name="excel_file" class="btn btn-default btn-xs" id="excel_file" accept=".xls" onchange="cargaMasiva(1)"/>
        </form>


       </div>


       <div class="col-sm-2 col-xs-12">
        <a id="btnSubir" class="btn btn-success" onclick="cargaMasiva(2)" role="button"><span class="icon-upload" ></span>Subir</a>
        <a id="" class="btn btn-info" onclick="location.reload()" role="button"><span class=" icon-arrows-cw" ></span></a>
       </div>

      </div>


      <div  class="col-xs-12 col-sm-12" id="txtData" style="margin-top:20px;"></div>

      <div class="col-xs-12 col-sm-12 text-center" id="result" >
       <img src="recursos/img/logo.png" style=" max-width:30%; padding-bottom: 30px; " class="img-res">
      </div>


     </div><!-- ./ panel body-->
    </div><!-- ./ panel panel-default -->


   </div><!-- ./row -->
  </div>




  <!-- Form Validator -->

  <script src="recursos/validator/js/bootstrapValidator.js"></script>
  <script src="recursos/js/bootstrap.min.js"></script>

  <!-- dataTables -->
  <script src="recursos/datatables/js/datatables.min.js"></script>


  <!-- dateTime Picker -->
  <script src="recursos/js/moment.js" charset="utf-8"></script>
  <script src="recursos/js/es_moment.js" charset="utf-8"></script>
  <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>

  <script src="recursos/js/mtto/import_mtto.js">

  </script>


 </body>
 </html>
