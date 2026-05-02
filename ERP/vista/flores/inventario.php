<?php
session_start();
$nivel     = $_SESSION['nivel'];


// if ($nivel == 8) {

 ?>

<!DOCTYPE html>
<html lang="es">

<head>

  <title>Inventario</title>
  <?php include('hoja_de_estilos.php'); ?>

  <link rel="stylesheet" href="recursos/css/disponibilidad.css" />
  <link rel="stylesheet" href="recursos/css/style.css" />
  <!-- <link rel="stylesheet" href="recursos/css/ui-ruler.css" /> -->

</head>
<body>

  <?php include_once('header.php'); ?>

  <div class="container-fluid">
    <div class="col-sm-12">
      <div class="panel content-pane" >
        <div class="panel-body">

          <ul class="nav nav-tabs">
            <li class="active"><a class="text-warning" 
            data-toggle="tab" href="#pedidos" onclick="verPedidosFlores()"><strong>
                  <span class="icon-cube"></span> PEDIDOS </strong></a>
            </li>
          

            <li><a class="text-warning" data-toggle="tab" href="#tab1" onclick=""><strong>
                  <span class="icon-cube"></span> DISPONIBILIDAD </strong></a>
            </li>

            <li><a class="text-info" data-toggle="tab" onclick="ver_historico()" href="#tab2"><strong><span
                    class=" icon-print-4"></span> HISTORICO</strong></a>
            </li>
          
            <li><a class="text-info" data-toggle="tab" onclick="ver_inventario()" href="#tab2"><strong><span
                    class=" icon-print-4"></span> INVENTARIO </strong></a>
            </li>

              <li><a class="text-info" data-toggle="tab" onclick="ver_historico()" href="#tab2"><strong><span
                    class=" icon-print-4"></span> CONTROL DE DESPERDICIO</strong></a>
            </li>
          </ul>


          <div class="tab-content">
            
            <div id="pedidos" class="tab-pane fade in active">
              <div id="format_pedidos"></div>
            </div>


            <div id="tab1" class="tab-pane fade ">
              <div class="row ">
                <div class=" col-sm-3 ">
                  <label class="">Fecha de emisión</label>
                  <div class="">
                    <div class="input-group date calendariopicker">
                      <input type="text" class="select_input form-control input-sm" value="" id="date">
                      <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label>
                      </span>
                    </div>
                  </div>

                  <div id="ultimo_formato"></div>
                </div>
                <div class="col-sm-6">
                  <br>
                  <a class="btn btn-default" onclick="print_f1('tbDisponibilidad')"><i class='bx bxs-printer'></i>
                    Imprimir </a>
                </div>

                <div class="col-sm-3 text-right" id="area-folio">

                </div>

              </div>
              <br>

              <!-- Formato -->
              <div id="tbDisponibilidad"></div>
            </div>

            <div id="tab2" class="tab-pane fade">
              <div id="tb_historico"></div>
            </div>


            <div id="inv-formato" class="tab-pane fade">
              <div id="tb_inventarios"></div>
            </div>


          </div>
          <!--  -->



          <br>


        </div>
        <!--./ card body -->
      </div>
    </div>
  </div>


  <!-- JavaScript -->
  <script src="recursos/js/jquery-1.12.3.js"></script>
  <script src="recursos/js/bootstrap.min.js"></script>
  <script src="recursos/js/complementos.js"></script>


  <!-- dataTables -->
  <script src="recursos/datatables/js/datatables.min.js"></script>

  <!-- Form Validator -->
  <script src="recursos/plugin/validator/js/bootstrapValidator.js"></script>

  <!-- datetime picker -->
  <script src="recursos/js/moment.js" charset="utf-8"></script>
  <script src="recursos/js/es_moment.js" charset="utf-8"></script>
  <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>
  <script src="recursos/js/flores/inventario.js?t=<?=time()?>"></script>

  <script type="text/javascript">
  $(document).ready(function() {
    $(function() {
      $('.calendariopicker').datetimepicker({
        format: 'YYYY-MM-DD',
        useCurrent: false,
        defaultDate: new Date(),
        // minDate: moment().add(-1, 'd').toDate(-40, 'd'),
        widgetPositioning: {
          horizontal: 'right',
          vertical: 'bottom'
        },
      });

      $(".calendariopicker").on("dp.change", function(e) {
        // oculto();
      });
    });

    $('.calendariopicker').keypress(function(evt) {
      return false;
    });
  });
  </script>



</body>

</html>
<?php

// }else {
//  echo $nivel;
// }

?>