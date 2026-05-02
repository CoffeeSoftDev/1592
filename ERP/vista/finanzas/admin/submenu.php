<?php
  session_start();
  if ( ($_SESSION['nivel'] != 3 ) {
    $nivel = $_SESSION['nivel'];
    $area = $_SESSION['area'];

   echo '
     <script src="recursos/js/jquery.js"></script>
     <script src="recursos/js/index.js"></script>
     <script> nivel(\''.$nivel.'\',\''.$area.'\',\'\'); </script>
     <div class="res"></div>
     ';
  }
  else {
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
 <!-- <title>Argovia</title> -->
 <link rel="shortcut icon" href="http://www.argovia.com.mx/img/favicon.ico">

 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap.min.css">
 <link rel="stylesheet" href="recursos/css/bootstrap/bootstrap-datetimepicker.css">
 <link rel="stylesheet" href="recursos/css/bootstrap/css/font-awesome.css">
 <link rel="stylesheet" href="recursos/icon-font/fontello.css">
 <link rel="stylesheet" href="recursos/icon-font/animation.css">
 <link rel="stylesheet" href="recursos/css/formato.css">

</head>


<body>
 <?php include_once('header.php'); ?>
 <?php include_once('tab_navs.php'); ?>


<!--  JAVASCRIPT -->



 <script src="recursos/js/jquery.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap/bootstrap.js" charset="utf-8"></script>
 <script src="recursos/js/moment.js" charset="utf-8"></script>
 <script src="recursos/js/es_moment.js" charset="utf-8"></script>
 <script src="recursos/js/bootstrap-datetimepicker.js" charset="utf-8"></script>
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

        $(".calendariopicker").on("dp.change", function (e) {});
      });

      $('.calendariopicker').keypress(function (evt) {  return false; });
    });
  </script>

</body>
</html>
<?php
}
?>
