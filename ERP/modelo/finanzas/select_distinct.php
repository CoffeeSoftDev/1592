<?php 
	//MODELO
	//REALIZA UNA CONSULTA A LA BASE DE DATOS PARA OBTENER LOS DIAS QUE SUBIERON ARCHIVOS
	include_once('../SQL_PHP/_Utileria.php');
    include_once('../SQL_PHP/_Finanzas.php');
    $utileria = new Util;
    $finanzas = new Finanzas;    
	$mes = $_GET['mes'];
    $mes_letra = $utileria -> mesLetra($mes);
    $array_respuesta = array('');
    $array_consulta = array('');    
    $directorio = '../../recursos/sobres_file/'.$mes_letra.'/';    
    $array_consulta = array($directorio);
	$consulta = $finanzas -> Select_Distinc_Fecha($array_consulta);
	$var = 0;
	foreach ($consulta as $key) {	
		$array_respuesta[$var] = $key[0];	
		$var++;
	}	
	echo json_encode($array_respuesta);	
	//dentro de la comparacion va a ver un ciclo que recorra todo el arreglo
?>