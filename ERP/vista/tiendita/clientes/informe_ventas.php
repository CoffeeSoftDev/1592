<?php
session_start();
$nivel     = $_SESSION['nivel'];
if ($nivel == 10) {
 include('informe_ventas_vista.php');
}else{


}



?>