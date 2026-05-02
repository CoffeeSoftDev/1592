<!DOCTYPE html>
<html>
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">


 <?php include('stylesheet.php'); ?>

 <!--  dataTables -->
 <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">

 <!-- Autocompleter -->
 <link href = "recursos/css/jquery-ui.css" rel = "stylesheet">
 <script src = "recursos/js/jquery.js"></script>
 <script src = "recursos/js/jquery-ui.js"></script>

 <!--validator-->
 <link rel="stylesheet" href="recursos/validator/css/bootstrapValidator.css"/>

 <!-- box icons -->
 <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'>

 <!--css -->
 <link rel="stylesheet" href="recursos/css/tpv/tpv.css"/>
 <link rel="stylesheet" href="recursos/css/style.css"/>

</head>
<body>

 <?php include('header.php'); ?>

 <div class="container-fluid">
  <div class="">
   <div class="col-sm-5">

    <div  id="panel-ant">
     <div class="panel panel-default">
      <div class="panel-body">
       <div class="row row-horizon">
        <div  id="pane-folios"></div>
       </div>


       <div class="form-horizontal">
        <div class=" form-group">
         <div class="col-sm-12">
         <center> <h5 for="label-control"><b> Valorización de productos</b>  </h5></center>
         </div>
         
        
        </div>

        <div class=" form-group">

         <div class="col-sm-2">
          <label for="label-control">area: Flores tropicales</label>
         </div>

         <div class="col-sm-6">
          <!-- <input style="width:100%" type="text" class="form-control input-xxs" value=""> -->
         </div>

         <!-- <div class="col-sm-2">
          <label for="label-control">Remisión:</label>
         </div>

         <div class="col-sm-2">
          <input style="width:100%" type="text" class="form-control input-xxs" value="">
         </div>

        </div>

        <div class=" form-group">
         <div class="col-sm-2">
          <label for="label-control">Clave:</label>
         </div>

         <div class="col-sm-3">
          <input style="width:100%" type="text" class="form-control input-xxs" value="">
         </div>

         <div class="col-sm-5">
          <label for="label-control">Nota de embarque:</label>
         </div>

         <div class="col-sm-2">
          <input style="width:100%" type="text" class="form-control input-xxs" value="">
         </div>
        </div>

        <div class=" form-group">
         <div class="col-sm-2">
          <label for="label-control">Lote:</label>
         </div>

         <div class="col-sm-4">
          <input style="width:100%" type="text" class="form-control input-xxs" value="">
         </div>

         <div class="col-sm-3">
          <label for="label-control"># Factura:</label>
         </div>

         <div class="col-sm-3">
          <input style="width:100%" type="text" class="form-control input-xxs" value="">
         </div> -->


        </div>

       </div>


       <hr>

       <div class="bg-info" id="content-folio">

       </div>

       <hr>

       <table class="table">
        <tbody><tr>
         <td class="active" width="40%">SubTotal</td>
         <td class="whiteBg" width="60%"><span id="Subtot">0.000</span>                         <span class="float-right"><b id="ItemsNum"><span>0</span> items</b></span>
         </td>
        </tr>
        <tr>
         <td class="active">Order TAX</td>
         <td class="whiteBg"><input type="text" value="12%" onchange="total_change()" class="total-input TAX" placeholder="N/A" maxlength="8">
          <span class="float-right"><b id="taxValue">0.000 USD</b></span>
         </td>
        </tr>
        <tr>
         <td class="active">Discount</td>
         <td class="whiteBg"><input type="text" value="" onchange="total_change()" class="total-input Remise" placeholder="N/A" maxlength="8">
          <span class="float-right"><b id="RemiseValue">USD</b></span>
         </td>
        </tr>
        <tr>
         <td class="active">Total</td>
         <td class="whiteBg light-blue text-bold"><span id="total">0.000</span> USD</td>
        </tr>
       </tbody></table>






      </div><!-- ./ panel body-->
     </div><!-- ./ panel panel-default -->
    </div>

   </div><!-- ./col-sm-4 -->




   <!-- <div class="col-sm-7">

   <div  id="panel-ant">

  -->




  <div class="col-sm-7 right-side nopadding">


   <div class="row row-horizon" id="btn_Categorias">

   </div>

   <!-- <div class="col-sm-12">
   <div class="input-group">
   <input type="text" class="form-control" placeholder="Search">
   <div class="input-group-btn">
   <button class="btn btn-default" type="submit">
   <i class="glyphicon glyphicon-search"></i>
  </button>
 </div>
</div>
</div> -->



<!-- product list section -->
<div class="slimScrollDivx" style="position: relative; overflow: hidden; width: auto; height: 600px;">

 <div id="productList2" style="overflow: hidden; width: auto; height: 650px;">

  <!--

  <div class="col-sm-2 col-xs-4">
  <a href="javascript:void(0)" class="addPct" id="product-2423424" onclick="add_posale('159')">

  <div class="product color07 flat-box waves-effect waves-block">

  <h3 id="proname">pepsi</h3>

  <input type="hidden" id="idname-159" name="name" value="pepsi">
  <input type="hidden" id="idprice-159" name="price" value="35">
  <input type="hidden" id="category" name="category" value="REFRESCOS">
  <div class="mask">
  <h3>35.0 PESOS</h3>
  <p></p>
 </div>
 <img src="https://www.myamericanmarket.com/6033-large_default/pepsi-throwback-soda.jpg" alt="pepsi">                        </div>
</a>
</div>





<div class="col-sm-2 col-xs-4">
<a href="javascript:void(0)" class="addPct" id="product-1232" onclick="add_posale('174')">
<div class="product color01 flat-box waves-effect waves-block">
<h3 id="proname">Helado de Fresa Natural</h3>
<input type="hidden" id="idname-174" name="name" value="Helado de Fresa Natural">
<input type="hidden" id="idprice-174" name="price" value="60">
<input type="hidden" id="category" name="category" value="POSTRES">
<div class="mask">
<h3>60.0 PESOS</h3>
<p></p>
</div>
</div>
</a>
</div>
<div class="col-sm-2 col-xs-4">
<a href="javascript:void(0)" class="addPct" id="product-1321321" onclick="add_posale('170')">
<div class="product color07 flat-box waves-effect waves-block">
<h3 id="proname">Cóctel de Camaron GDE</h3>
<input type="hidden" id="idname-170" name="name" value="Cóctel de Camaron GDE">
<input type="hidden" id="idprice-170" name="price" value="180">
<input type="hidden" id="category" name="category" value="COMIDAS">
<div class="mask">
<h3>180.0 PESOS</h3>
<p></p>
</div>
</div>
</a>
</div>
<div class="col-sm-2 col-xs-4">
<a href="javascript:void(0)" class="addPct" id="product-INSUMO1" onclick="add_posale('171')">
<div class="product color03 flat-box waves-effect waves-block">
<h3 id="proname">Empanadas de Camaron</h3>
<input type="hidden" id="idname-171" name="name" value="Empanadas de Camaron">
<input type="hidden" id="idprice-171" name="price" value="15">
<input type="hidden" id="category" name="category" value="COMIDAS">
<div class="mask">
<h3>15.0 PESOS</h3>
<p></p>
</div>
</div>
</a>
</div>
<div class="col-sm-2 col-xs-4">
<a href="javascript:void(0)" class="addPct" id="product-R1" onclick="add_posale('172')">
<div class="product color05 flat-box waves-effect waves-block">
<h3 id="proname">ARROZ CON CAMARÓN</h3>
<input type="hidden" id="idname-172" name="name" value="ARROZ CON CAMARÓN">
<input type="hidden" id="idprice-172" name="price" value="52">
<input type="hidden" id="category" name="category" value="COMIDAS">
<div class="mask">
<h3>52.0 PESOS</h3>
<p></p>
</div>
</div>
</a>
</div> -->
<!-- <div class="col-sm-2 col-xs-4">
<a href="javascript:void(0)" class="addPct" id="product-221" onclick="add_posale('175')">
<div class="product color02 flat-box waves-effect waves-block">
<h3 id="proname">Rebanada de Pastel</h3>
<input type="hidden" id="idname-175" name="name" value="Rebanada de Pastel">
<input type="hidden" id="idprice-175" name="price" value="70">
<input type="hidden" id="category" name="category" value="POSTRES">
<div class="mask">
<h3>70.0 PESOS</h3>
<p></p>
</div>
</div>
</a>
</div> -->
<!-- <div class="col-sm-2 col-xs-4">
<a href="javascript:void(0)" class="addPct" id="product-3321" onclick="add_posale('176')">
<div class="product color03 flat-box waves-effect waves-block">
<h3 id="proname">Rebanada de Pay de queso</h3>
<input type="hidden" id="idname-176" name="name" value="Rebanada de Pay de queso">
<input type="hidden" id="idprice-176" name="price" value="90">
<input type="hidden" id="category" name="category" value="POSTRES">
<div class="mask">
<h3>90.0 PESOS</h3>
<p></p>
</div>
</div>
</a>
</div>
<div class="col-sm-2 col-xs-4">
<a href="javascript:void(0)" class="addPct" id="product-345" onclick="add_posale('178')">
<div class="product color04 flat-box waves-effect waves-block">
<h3 id="proname">Agua Natural 1L.</h3>
<input type="hidden" id="idname-178" name="name" value="Agua Natural 1L.">
<input type="hidden" id="idprice-178" name="price" value="31">
<input type="hidden" id="category" name="category" value="REFRESCOS">
<div class="mask">
<h3>31.0 PESOS</h3>
<p></p>
</div>
</div>
</a>
</div>

</div> -->




</div>
<!-- <div class="slimScrollBar" style="background: rgb(0, 0, 0); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 651.905px;"></div><div class="slimScrollRail" style="width: 7px; height: 100%; position: absolute; top: 0px; display: block; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
</div> -->



<hr>


</div><!-- ./ panel panel-default -->
<!-- </div>  -->
</div><!-- ./col-sm-4 -->

</div><!-- ./row -->

</div>


<!--  JavaScript -->



<!-- Form Validator -->
<script src="recursos/js/jquery-1.12.3.js" ></script>

<!-- boot box -->
<script src="recursos/plugin/bootbox.min.js" charset="utf-8"></script>

<script src="recursos/validator/js/bootstrapValidator.js"></script>
<script src="recursos/js/bootstrap.min.js"></script><!-- datapicker traductor-->

<!-- dataTables -->
<script src="recursos/datatables/js/datatables.min.js"></script>

<!-- JS -->
<script src="recursos/js/flores/tpv.js"></script>


</body>
</html>
