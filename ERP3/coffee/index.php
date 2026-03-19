<?php require_once('layout/head.php'); ?>


<?php require_once('layout/script.php'); ?>
</head>

<body>
    <?php require_once('../layout/navbar.php'); ?>
    <main>
        <section id="sidebar"></section>
        <div id="main__content">
            <div id="root"></div>
        </div>
    </main>

    <script src="../src/js/CoffeSoft.js?t=<?php echo time(); ?>"></script>
    <script src="../src/js/complementos.js?t=<?php echo time(); ?>"></script>
    <script src="../src/js/plugin-table.js?t=<?php echo time(); ?>"></script>
    <script src="js/tareas.js?t=<?php echo time(); ?>"></script>
</body>

</html>
