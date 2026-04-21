<!DOCTYPE html>
<html lang="es" dir="ltr">

<head>

    <?php include_once('stylesheet.php'); ?>

    <!--validator-->
    <link rel="stylesheet" href="recursos/plugin/validator/css/bootstrapValidator.css" />

    <!--  dataTables -->
    <link rel="stylesheet" href="recursos/datatables/css/datatables.min.css">
</head>

<body>

    <?php include_once('header.php'); ?>

    <div class="">
        <div class="">
            <br>

            <div id="content-load">
                <div class="col-xs-12 col-sm-4">
                    <div class="panel panel-default">
                        <div class="col-sm-12 col-xs-12">
                            <h4 class=""><i class=" icon-cog-outline"></i> Agregar Producto</h4>

                            <hr>
                        </div>

                        <div class="panel-body " id="content-form">





                        </div><!-- ./ panel body-->
                    </div><!-- ./ panel panel-default -->
                </div><!-- col -->






                <div class="col-xs-12 col-sm-8">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="row form-group">
                                <div class="col-sm-8 ">
                                    <h3>Productos</h3>


                                </div>
                                <div class="col-sm-4">
                                    Grupos:
                                    <div id="cbGrupos">

                                    </div>

                                </div>
                            </div>

                            <hr>

                            <div id="content-table"></div>

                        </div><!-- ./ panel body-->
                    </div><!-- ./ panel panel-default -->
                </div><!-- col -->




            </div>

        </div><!-- ./row -->
    </div><!-- ./container -->



    <!-- JavaScript -->
    <script src="recursos/js/jquery-1.12.3.js"></script>

    <!-- datetime picker -->
    <script src="recursos/js/moment.js" charset="utf-8"></script>
    <script src="recursos/js/es_moment.js" charset="utf-8"></script>
    <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>


    <!-- dataTables -->
    <script src="recursos/datatables/js/datatables.min.js"></script>

    <!-- Form Validator -->
    <script src="recursos/plugin/validator/js/bootstrapValidator.js"></script>

    <script src="recursos/js/bootstrap.min.js"></script>
    <script src="recursos/js/flores/lista_productos.js?t=<?=time()?>"></script>
</body>

</html>