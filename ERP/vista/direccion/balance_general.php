<?php
session_start();
$nivel     = $_SESSION['nivel'];
if ($nivel == 1) {
 ?>
<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>

 <title> Ingresos </title>

 <meta name="viewport"
  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <meta charset="utf-8">


 <!-- Data tables -->
 <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">
 <link rel="stylesheet" type="text/css"
  href="https://cdn.datatables.net/fixedcolumns/3.2.5/css/fixedColumns.bootstrap4.min.css" />

 <!-- Bootstrap -->
 <link rel="stylesheet" href="recursos/css/bootstrap/css/font-awesome.min.css">
 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.min.css">
 <link rel="stylesheet" href="recursos/css/direccion/admin.css">
 <link rel="stylesheet" href="recursos/css/formato.css">
 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap-datetimepicker.css">
 <!-- Iconos -->
 <link rel="stylesheet" href="recursos/icon-font/fontello.css">
 <link rel="stylesheet" href="recursos/icon-font/animation.css">
 <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'>

 <link rel="stylesheet" href="recursos/css/tpv/card.css" />
 <link rel="stylesheet" href="recursos/css/formato.css">
 <link rel="stylesheet" href="recursos/css/style.css" />

 <!-- Autocompleter -->
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

 <!--  JavaScript -->
 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

 <!-- CSS style  -->
 <link rel="stylesheet" href="https://bersalfenix.com/CoffeSoft/plugins/style.css" />
 <link rel="stylesheet" href="https://bersalfenix.com/CoffeSoft/plugins/ui-ruler.css" />


 <script src="https://www.gstatic.com/charts/loader.js"></script>
 <script>
 // google.charts.load('current', {packages: ['corechart']});
 // google.charts.setOnLoadCallback(drawChart);
 </script>

</head>

<body>




 <?php include_once('navbar-custom.php'); ?>
 <?php
//   include('sidebar-collapse.php'); 
  ?>

 <div class="container-fluid">

  <div class="row">
   <div class="col-sm-10 col-sm-offset-2 col-xs-12">

    <div style="height:800px;" class="card mt-20">
     <div class="card-body">

      <ul class="nav nav-tabs">

       <li class="active">
        <a data-toggle="tab" href="#reporte">
         <strong>
          <span class=" icon-print-4x"> Reporte turismo</span> </strong>
        </a>
       </li>



       <li><a data-toggle="tab" href="#tab1"><strong><span class=" icon-print-4x">Ingresos generales</span>
         </strong></a>
       </li>

       <li><a class="text-info" data-toggle="tab" href="#tab2"><strong><span class=" icon-address-card-o"></span>
          Comparativa mensual por areas
         </strong></a>
       </li>

       <li><a class="text-info" data-toggle="tab" href="#tab3"><strong><span class="bx bxs-user-pin"></span> Cheque
          promedio
         </strong></a>
       </li>

      </ul><!-- nav tabs -->



      <div class="tab-content">

       <div id="reporte" class="tab-pane fade in active">

        <div class="row ">

         <div class=" col-sm-2 text-center">
          <select disabled class="form-control " id="OcupacionCB" onchange="">
           <option value="2023" selected>2023</option>
           <option value="2022">2022</option>
           <option value="2021">2021</option>
           <!-- <option value="2020">2020</option>
           <option value="2019">2019</option>
           <option value="2018">2018</option>
           <option value="2017">2017</option> -->
          </select>
         </div>

        <div class="col-sm-2">

        <button onclick="Contable();" class="btn btn-primary">Generar Reporte</button>

        </div>

        </div>

        <div class="row">
         <div class="col-sm-8 reporte_gral"></div>
        </div>



       </div>


       <div id="tab1" class="tab-pane fade">
        <br>
        <div class="row">

         <div class="col-sm-3 ">
          <div id="txtFecha" class="input-ranger ">
           <i class="fa fa-calendar"></i>&nbsp;
           <span></span>
           <i class="fa fa-caret-down"></i>
          </div>
         </div>

         <div class="col-sm-2 text-right">
          <a class="btn btn-primary input-ranger " onclick="mostrar_tendencias()"><i class="bx bx-search"></i> Consultar
          </a>
         </div>


        </div>

        <div class="row">
         <div class="col-sm-7 col-xs-12">
          <div class="content-tendencias"></div>
         </div>
        </div>


        <div class="graficos-tendencias">
         <div id="chart_area"></div>
        </div>
       </div>
       <!-- tab2  -->



       <div id="tab2" class="tab-pane fade">
        <br>
        <div class="row">

         <div class=" col-sm-2 text-center">
          <select class="form-control " id="txtAnio2" onchange="">
           <option value="2023">2023</option>
           <option value="2022">2022</option>
           <option value="2021">2021</option>
           <option value="2020">2020</option>
           <option value="2019">2019</option>
           <option value="2018">2018</option>
           <option value="2017">2017</option>
           <!--<option value="2016">2016</option>-->
           <!--<option value="2015">2015</option>-->
          </select>
         </div>

         <div class="col-sm-7 col-xs-12 ">
          <a class="btn btn-primary " onclick="Tendencias()"><i class="bx bx-search"></i> Consultar </a>
          <a class="btn btn-default hide" onclick="print_f1('formato-mensual')"><i class="bx bxs-printer"></i> Imprimir
          </a>
         </div>



        </div>

        <br>

        <div id="formato-mensual" class="mt-20 content-mes"></div>

       </div>


       <div id="tab3" class="tab-pane fade">
        <br>

        <div class="row">

         <div class="col-sm-2">
          <select class="form-control " id="txtFechaPromedio" onchange="">
           <option value="2021">2021</option>
           <option value="2020">2020</option>
           <option value="2019">2019</option>
           <option value="2018">2018</option>
          </select>
         </div>



         <div class="col-sm-2">
          <a class="btn btn-primary" onclick="ChequePromedio()"><i class="bx bx-search-alt"></i>Consultar</a>
         </div>

        </div><!-- ./row -->


        <div class="content-cheque-promedio">

         </center>
        </div>

       </div>


      </div>


     </div><!-- cardbody-->

    </div><!-- card-->


   </div><!-- col-sm-10 -->





  </div>
</body>


<!-- JS -->
<?php include('footer.php');?>

<!-- data Ranger Picker -->
<script type="text/javascript" src="recursos/range_calendar/moment.min.js"></script>
<script type="text/javascript" src="recursos/range_calendar/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="recursos/range_calendar/daterangepicker.css" />
<script src="recursos/js/direccion/balance_gral.js?t=<?=time()?>"></script>
<!-- dataTables -->
<script src="recursos/datatables/js/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/3.2.5/js/dataTables.fixedColumns.min.js">
</script>


<script src="https://bersalfenix.com/CoffeSoft/plugins/complementos.js?t=1606171722"></script>

<!--Chart JS -->
<script src="recursos/js/Chart.js"></script>
<script src="recursos/js/chart.js-php.js"></script>

</html>


<?php
}else{

 echo  '<h1>'.$nivel.'</h1>';

}
?>
