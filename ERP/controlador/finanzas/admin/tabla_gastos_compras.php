<?php
include_once("../../../modelo/SQL_PHP/_CRUD.php");
include_once("../../../modelo/SQL_PHP/_Finanzas.php");
include_once("../../../modelo/SQL_PHP/_Utileria.php");
include_once("../../../modelo/UI_TABLE.php");

$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$date1        = $_POST['date1'];
$date2        = $_POST['date2'];
$idE          = $_POST['udn'];
$bg_categoria = "#ECF0F1";
$id_Clase     = $_POST['clase'];
$tb           = '';

// ---------

$array     = array($idE,$date1,$date2);
$Fechas    = $fin -> GastosF($array,$id_Clase);
$Nom       = $fin -> GastosNOM($array,$id_Clase);
$suma      = $fin -> GastosSUM($array,$id_Clase);
$arrayData = array($suma,$idE,$id_Clase,$date1,$date2);

$Function   = array(
 'GastosFechas',
 'FoldingGastos',
 'Select_Data_Hoy_Gastos_Categoria'
);

/*-----------------------------------*/
/* Funciones PHP
/*-----------------------------------*/
$tb   = Tb_Scrollx($arrayData,$Fechas,$Nom,$Function,$fin);

/*-----------------------------------*/
/*		JSON ENCODE
/*-----------------------------------*/

$encode = array(0=>$tb);
echo json_encode($encode);




?>
