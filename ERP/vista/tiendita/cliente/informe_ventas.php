<?php
session_start();
$nivel     = $_SESSION['nivel'];
if ($nivel == 10  || $nivel == 1) {
 include('informe_ventas_vista.php');
}else{

echo  'soy nivel'.$nivel;

}



?>