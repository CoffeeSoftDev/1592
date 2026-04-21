<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1592</title>
    <!--Icono-->
    <link href="https://uploads-ssl.webflow.com/641377ccc905e8414f7c0a1f/6421fa7927ea13637a1c3ca5_Favicon-1.png"
        rel="shortcut icon" type="image/x-icon">

    <link rel="shortcut icon" href="" type="image/x-icon">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="recursos/icon-font/fontello.css">
    <link rel="stylesheet" href="https://plugins.erp-varoch.com/recursos/plugins/bootstrap-5.3.0/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="https://plugins.erp-varoch.com/recursos/css/huubie.css"> -->
    <link rel="stylesheet" href="recursos/css/1592.css">

    <link rel="stylesheet" href="https://plugins.erp-varoch.com/style.css">
    <!-- Icon fonts -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>

<body>
    <!-- Navbar -->
    <?php include('menu_navbar.php');?>

    <!-- Sidebar -->
    <div class="container-fluid" id="sidebarBody">
        <div class="row">

            <?php include('menu_sidebar.php');?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div style="margin-top:15px; " class=" card">
                    <div style="height:600px; " class="card-body">

                        <?php include('modulo_consulta.php'); ?>

                    </div>
                </div>
            </main>
        </div>
    </div>


    <script src="recursos/js/jquery-1.12.3.js"></script>
    <script src="recursos/plugin/bootbox.min.js"></script>
    <script src="recursos/js/bootstrap.min.js"></script>
    <script src="recursos/js/Chart.js"></script>
    <script src="recursos/js/chart.js-php.js"></script>
    <script src="recursos/js/direccion/metas.js?t=<?=time()?>"></script>
    <script src="recursos/js/direccion/ingresos_anuales.js?t=<?=time()?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://plugins.erp-varoch.com/recursos/plugins/bootstrap-5.3.0/js/bootstrap.bundle.min.js"></script>


</body>

</html>