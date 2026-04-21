<?php 
	//CONSULTA PARA CONTABILIZAR EL NUMERO DE SOBRES SUBIDOS DE ACUERDO A LA FECHA
	include_once('../SQL_PHP/_Finanzas.php');
	$finanzas = new Finanzas;
	$anio = $_GET['anio'];
	$mes = $_GET['mes'];
	$dia = $_GET['dia'];
	$array = array($anio.'-'.$mes.'-'.$dia);
	echo $finanzas -> Select_Count_Sobres($array);
?>