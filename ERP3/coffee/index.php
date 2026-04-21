<?php
session_start();

if (empty($_COOKIE["IDU"])) {
    require_once('../acceso/ctrl/ctrl-logout.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../src/img/logos/logo_icon.png" type="image/x-icon">
    <title>15-92</title>

    <?php require_once('layout/head.php'); ?>
    <?php require_once('layout/core-libraries.php'); ?>

    <!-- CoffeeSoft Framework -->
    <script src="../src/js/coffeeSoft.js?t=<?php echo time(); ?>"></script>
    <script src="../src/js/plugins.js?t=<?php echo time(); ?>"></script>
    <script src="https://www.plugins.erp-varoch.com/ERP/JS/complementos.js"></script>
</head>

<body>
    <?php require_once('../layout/navbar.php'); ?>

    <main>
        <section id="sidebar"></section>

        <div id="main__content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item text-uppercase text-muted">Framework</li>
                    <li class="breadcrumb-item fw-bold active">CoffeeSoft Showcase</li>
                </ol>
            </nav>

            <!-- Main Container -->
            <div class="main-container" id="root"></div>

            <!-- Módulo CoffeeSoft Showcase -->
            <script src="js/coffee.js?t=<?php echo time(); ?>"></script>
        </div>
    </main>
</body>
</html>
