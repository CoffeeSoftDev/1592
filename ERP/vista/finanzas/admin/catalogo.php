<?php
session_start();
$nivel = $_SESSION['nivel'];
$area = $_SESSION['area'];

if ( $_SESSION['nivel'] == 3 || $nivel == 1 ) {

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
  <link rel="stylesheet" href="recursos/css/multiple_select.css">
  <link rel="shortcut icon" type="image/png" href="http://www.argovia.com.mx/img/logo.png">
  <style media="screen">

  body{
   font-family: "Segoe UI";
   font-variant: normal;
  }

  .ms-choice {
   border: none;
   width: 100%;
   height: 16px;
  }



  </style>
 </head>


 <body>
  <?php include_once('header.php'); ?>
  <!-- CONTAINER -->
  <div class="container">
   <!-- PANEL -->
   <!-- <div class="panel panel-default">
   <div class="panel-body"> -->


   <div class="row">

    <div class="col-sm-8">
     <label id="Titulo"
     style="font-weight: 500; font-size:24px">
     <span class="fa fa-gear"></span> AJUSTES </label>
    </div>
    <div class="col-sm-4 text-right">

     <div class="btn-group" data-toggle="buttons">
      <button class="active btn btn-secondary btn-xs " autofocus="true" onclick="tabPane(1)">
       <input type="radio" value="2" name="rdExtra"><strong>
        <span class="icon-down-big" class="text-success"></span>
        INGRESOS
       </strong>
      </button>

      <button class="btn btn-secondary btn-xs " onclick="tabPane(2)">
       <input type="radio" value="3" name="rdExtra">
       <strong>
        <span class="icon-up-big"></span>
        EGRESOS
       </strong>
      </button>

     </div>
    </div><!-- ./ col-sm-12 -->
   </div>


   <br>


   <div class="row" id="tab1">
    <div class="col-md-4 col-xs-12">
     <div class="panel panel-default">

      <div class="panel-body">
       <h4 class="form-section"><i class="icon-head"></i> CATEGORIAS </h4>

       <div class="form-group col-sm-6 col-xs-12 inp_categoria">
        <label class="control-label" for="Categoria"> Concepto: </label>
        <input type="text" class="form-control input-sm" placeholder="Nombre" id="Categoria">
       </div>


       <div class="form-group col-sm-6 col-xs-12" id="Select_movimiento"></div>
       <div class="form-group col-sm-6 col-xs-12" id="Select_Impuestos"></div>

       <div class="form-group col-sm-6 col-xs-12">
        <br>
        <button type="button" class="btn btn-sm btn-info col-sm-12 col-xs-12" onclick="Insert_Categorias();">Agregar</button>
       </div>

       <div class="col-sm-3 col-xs-12 text-center" id="Res_Categoria"></div>
       <hr>
       <div class="form-group" id="tb_Cat"></div>

      </form>
     </div>
    </div>
   </div> <!-- categoria -->
   <div class="col-md-4 col-xs-12">
    <div class="panel panel-default">
     <div class="panel-body">

      <h4 class="form-section"><i class="icon-head"></i> SUBCATEGORIAS </h4>

      <div class="form-group col-sm-6 col-xs-12 inp_subcategoria">
       <label class="control-label" for="subcategoria">Subcategoría</label>
       <input type="text" class="form-control input-sm" placeholder="Nombre" id="subcategoria">
      </div>
      <div class="form-group col-sm-6 col-xs-12" id="Select_Categorias"></div>
      <div class="col-sm-12 col-xs-12 text-center" id="Res_Subcategoria"></div>
      <div class="form-group col-sm-12 col-xs-12">
       <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" onclick="Insert_Subcategoria();">Agregar</button>
      </div>
      <div class="form-group" id="tb_SubCat"></div>

     </div>
    </div>
   </div>


   <div class="col-md-4 col-xs-12">
    <div class="panel panel-default">
     <div class="panel-body">
      <h4 class="form-section"><i class="icon-head"></i> IMPUESTOS </h4>

      <div class="form-group col-sm-6 col-xs-12 inp_impuesto">
       <label class="control-label" for="Impuesto">Impuesto</label>
       <input type="text" class="form-control input-sm" placeholder="Nombre" id="Impuesto">
      </div>
      <div class="form-group col-sm-6 col-xs-12 inp_valor">
       <label class="contro-label" for="Valor_Imp">Valor</label>
       <div class="input-group">
        <input type="text" class="form-control input-sm" placeholder="Porcentaje" id="Valor_Imp">
        <span class="input-group-addon input-sm"><i class="icon-percent"></i></span>
       </div>
      </div>
      <div class="col-sm-12 col-xs-12 text-center" id="Res_Impuesto"></div>
      <div class="form-group col-sm-12 col-xs-12">
       <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" onclick="Insert_Impuestos();">Agregar</button>
      </div>
      <div class="form-group" id="tb_imp"></div>
     </div>

    </div>


    <div class="panel panel-default">
     <div class="panel-body">
      <h4 class="form-section"><i class="icon-head"></i> Formas de Pago </h4>
      <div class="form-group col-sm-12 col-xs-12 inp_fpago">
       <input type="text" class="form-control input-sm" placeholder="Nombre" id="FPago">
      </div>
      <div class="col-sm-12 col-xs-12 text-center" id="Res_FP"></div>
      <div class="form-group col-sm-12 col-xs-12">
       <label for=""> </label>
       <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1" onClick="Insert_FormasPago();">Agregar</button>
      </div>
      <div class="form-group" id="tb_FormasPago"></div>
     </div>
    </div>


   </div><!-- ./ -->


  </div><!-- ./row -->

  <div id="tab2" class="row">

   <div class="col-sm-4 col-xs-12">
    <div class="panel panel-default">
     <div class="panel-body">
      <h4 class="form-section"><i class="icon-head"></i> Destino </h4>
      <div class="form-group col-sm-12 col-xs-12 txtDestino">
       <label class="control-label" > Nombre </label>
       <input type="text" class="form-control input-sm"
       id="Destino">
      </div>

      <div class="col-sm-12 col-xs-12 text-center"
      id="dest"></div>

      <div class="form-group col-sm-12 col-xs-12">
       <label for=""> </label>

       <button type="button"
       class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1"
       onClick="AgregarDestino();">Agregar</button>
      </div>
      <div class="form-group" id="tb_des"></div>

     </div>
    </div><!-- ./FORMAS PAGO -->
   </div>



   <div class="col-sm-4 col-xs-12">
    <div class="panel panel-default">
     <div class="panel-body">
      <h4 class="form-section"><i class="icon-head"></i> Numero de Cuenta </h4>
      <div class="form-group col-sm-12 col-xs-12 Cuenta">
       <label class="control-label" > Nombre </label>
       <input type="text" class="form-control input-sm"
       id="txtCuenta">
      </div>

      <div class="col-sm-12 col-xs-12 text-center"
      id="Res_Cuenta"></div>

      <div class="form-group col-sm-12 col-xs-12">
       <label for=""> </label>

       <button type="button"
       class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-10 col-xs-offset-1"
       onClick="AgregarCuenta();">Agregar</button>
      </div>
      <div class="form-group" id="tb_Cuenta"></div>

     </div>
    </div><!-- ./FORMAS PAGO -->
   </div>






  </div>



  <!-- </div>
 </div>
</div> -->

<script src="recursos/js/jquery.js" charset="utf-8"></script>
<script src="recursos/js/jquery.numeric.js" charset="utf-8"></script>
<script src="recursos/js/multiple_select.js" charset="utf-8"></script>
<script src="recursos/js/bootstrap/bootstrap.js" charset="utf-8"></script>
<script src="recursos/js/finanzas/catalogo.js?t=<?=time()?>" charset="utf-8"></script>
<!-- <script>
$('#ImpIng').multipleSelect();
$('#ImpDesc').multipleSelect();
</script> -->
</body>
</html>
<?php
}else {
 echo '
 <script src="recursos/js/jquery.js"></script>
 <script src="recursos/js/index.js"></script>
 <script> nivel(\''.$nivel.'\',\''.$area.'\',\'\'); </script>
 <div class="res"></div>
 ';
}
?>
