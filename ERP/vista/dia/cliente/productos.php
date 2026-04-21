<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Productos.php');
$obj   = new Productos;
$nivel = $_SESSION['nivel'];


if ($nivel == 10  || $nivel == 1) {
  $now = $obj->NOW();
  ?>


  <!DOCTYPE html>
  <html lang="es" dir="ltr">
  <head>

    <?php include('stylesheet.php'); ?>

    <!--  dataTables -->
    <!-- <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css"> -->
    <link href='https://unpkg.com/boxicons@1.9.3/css/boxicons.min.css' rel='stylesheet'>



    <!--<link rel="stylesheet" href="recursos/css/ui-ruler.css"/>-->
  </head>
  <body>
    <?php include_once('header.php'); ?>
    <div class="container-fluid">
      <div class="col-sm-12">
        <div class="card content-pane" style="width: 100%;">
          <div class="card-body">

            <div class="row col-sm-6 col-xs-12">
              <input type="hidden" class="select_input form-control input-sm" value="<?php echo $now; ?>" id="date">

              <div class="form-group col-sm-6 col-xs-12">
                <label class="control-label">Tipo de alta</label>
                <select class="form-control input-sm" id="tipo_alta" onchange="combo();">
                  <option value="1">Productos</option>
                  <option value="2">Canastas</option>
                  <option value="3">Clientes Preferenciales</option>
                </select>
              </div>
            </div>

            <!-- ALTA PRODUCTOS -->
            <div class="row altaProductos col-sm-6 col-xs-12">
              <div class="form-group col-sm-offset-4 col-sm-4 col-xs-12 gb_precio">
                <label for="ipt_precio" class="contro-label col-sm-12 col-xs-12"> </label>
                <button type="button" Class="btn btn-sm btn-info col-sm-12 col-xs-12 icon-plus" data-toggle="modal" data-target="#Modal_nuevo_producto">Nuevo Producto</button>
              </div>

              <div class="form-group col-sm-4 col-xs-12 gb_precio">
                <label for="ipt_precio" class="contro-label col-sm-12 col-xs-12"> </label>
                <button type="button" Class="btn btn-sm btn-success col-sm-12 col-xs-12 icon-print" onclick="print_inventario();">Imprimir</button>
              </div>
            </div>

            <!-- ALTA CANASTAS -->
            <div class="row altaCanasta col-sm-12 col-xs-12 hide">
              <div class="form-group col-sm-3 col-xs-12 gb_canasta">
                <label for="ipt_canasta" class="contro-label">Canasta</label>
                <div class="input-group">
                  <input type="hidden" class="select_input form-control input-sm" autocomplete="off" id="id_canasta" disabled>
                  <input type="text" class="select_input form-control input-sm" autocomplete="off" id="ipt_canasta">
                  <span class="input-group-addon input-sm load_check "><i class="icon-shopping-basket"></i> </span>
                </div>
              </div>

              <div class="form-group col-sm-2 col-xs-12 gb_precio">
                <label for="ipt_precio" class="contro-label col-sm-12 col-xs-12"> </label>
                <button type="button" Class="btn btn-sm btn-primary col-sm-12 col-xs-12 icon-floppy canasta_save" onclick="nueva_canasta();">Nueva canasta</button>
              </div>

              <div class="form-group col-sm-2 col-xs-12 canasta_terminar hide">
                <label for="ipt_precio" class="contro-label col-sm-12 col-xs-12"> </label>
                <button type="button" Class="btn btn-sm col-sm-12 col-xs-12 icon-floppy" style="background:#323231; color:#fff;" onclick="terminar();">Cerrar canasta</button>
              </div>

              <!-- <div class="form-group col-sm-2 col-xs-12 gb_precio">
                <label for="ipt_precio" class="contro-label col-sm-12 col-xs-12"> </label>
                <button type="button" Class="btn btn-sm btn-success col-sm-12 col-xs-12 icon-print" onclick="print_inventario();">Imprimir</button>
              </div> -->
            </div>

            <!-- CLIENTES ESPECIALES -->
            <div class="row clientes_especiales col-sm-12 col-xs-12 hide">
              <div class="form-group col-sm-3 col-xs-12 gb_cliente">
                <label for="ipt_canasta" class="contro-label">Cliente</label>
                <div class="input-group">
                  <span class="input-group-addon input-sm load_check "><i class="icon-user"></i> </span>
                  <input type="text" class="select_input form-control input-sm" autocomplete="off" id="ipt_cliente">
                </div>
              </div>
              <div class="form-group col-sm-2 col-xs-12">
                <label for=""> </label>
                <button type="button" class="btn btn-sm btn-primary col-sm-12 col-xs-12" onclick="Save_cliente();">Agregar Cliente</button>
              </div>

              <div class="row col-sm-12 col-xs-12">
                <div class="form-group col-sm-6 col-xs-6 tb_clientes">

                </div>
                <div class="form-group col-sm-6 col-xs-6 tb_productos_clientes">

                </div>

              </div>
            </div>


            <div class="row tabla_productos"></div>


            <!-- PRODUCTOS -->

            <?php include('productos_canastas.php'); ?>




          </div><!--./ card body -->
        </div>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Modal_nuevo_producto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h3 class="modal-title icon-cubes" id="exampleModalLabel"> NUEVO PRODUCTO</h3>
          </div>
          <div class="modal-body">

            <div class="form-group col-sm-12 col-xs-12 gb_producto">
              <label for="ipt_producto" class="contro-label">Producto</label>
              <input type="text" class="input-sm form-control" autocomplete="off" id="ipt_producto" placeholder="Nombre del producto">
            </div>
            <div class="form-group col-sm-6 col-xs-12 gb_presentacion">
              <label for="ipt_presentacion" class="contro-label">Presentación</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="icon-box-1"></i> </span>
                <input type="text" class="input-sm form-control text-right" autocomplete="off" id="ipt_presentacion" placeholder="100 gr">
              </div>
            </div>
            <div class="form-group col-sm-6 col-xs-12 gb_min_inventario">
              <label for="ipt_min_inventario" class="contro-label">Inventario mínimo</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="icon-hash"></i> </span>
                <input type="text" class="input-sm form-control text-right" autocomplete="off" id="ipt_min_inventario" placeholder="50">
              </div>
            </div>
            <div class="form-group col-sm-6 col-xs-12 gb_precio">
              <label for="ipt_precio" class="contro-label">Precio Menudeo</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="icon-dollar"></i> </span>
                <input type="text" class="input-sm form-control text-right" autocomplete="off" id="ipt_precio" placeholder="0.00">
              </div>
            </div>
            <div class="form-group col-sm-6 col-xs-12 gb_mayoreo">
              <label for="ipt_mayoreo" class="contro-label">Precio Mayoreo</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="icon-dollar"></i> </span>
                <input type="text" class="input-sm form-control text-right" autocomplete="off" id="ipt_mayoreo" placeholder="0.00">
              </div>
            </div>
            <div class="form-group col-sm-6 col-xs-12 gb_cant_mayoreo">
              <label for="ipt_mayoreo" class="contro-label">Cantidad de mayoreo</label>
              <div class="input-group">
                <span class="input-group-addon"><i class="icon-dollar"></i> </span>
                <input type="text" class="input-sm form-control text-right" autocomplete="off" id="ipt_cant_mayoreo" placeholder="0.00">
              </div>
            </div>
            <div class="form-group col-sm-6 col-xs-12 gb_mayoreo">
              <label for="ipt_mayoreo" class="contro-label">Tipo de producto</label>
              <select class="form-control input-sm" id="CB_Tipo">
                <option value="1">Venta</option>
                <option value="2">No Venta</option>
              </select>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-warning icon-cancel" data-dismiss="modal">Cancelar</button>
            <button type="button" Class="btn btn-sm btn-success icon-floppy" onclick="nuevo_producto();">Guardar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- JavaScript -->
    <script src = "recursos/js/jquery-1.12.3.js"></script>
    <script src = "recursos/js/jquery.numeric.js"></script>
    <script src="recursos/js/bootstrap.min.js"></script>
    <script src = "recursos/js/complementos.js"></script>
    <!-- datetime picker -->
    <script src="recursos/js/moment.js" charset="utf-8"></script>
    <script src="recursos/js/es_moment.js" charset="utf-8"></script>
    <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>
    <script src="recursos/js/dia/productos.js?t=<?=time()?>" charset="utf-8"></script>

    <script type="text/javascript">
      $(document).ready(function(){
        $(function () {
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

          $(".calendariopicker").on("dp.change", function (e) {
            tabla_productos();
          });
        });

        $('.calendariopicker').keypress(function (evt) {  return false; });
      });
    </script>
  </body>
  </html>
  <?php
}
?>
