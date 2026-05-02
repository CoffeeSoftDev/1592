<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Catalogo.php');
include_once("../../../modelo/SQL_PHP/_Utileria.php");
include_once("../../../modelo/SQL_PHP/_CRUD.php");
$util = new Util;
$cat  = new Catalogo;
$crud = new CRUD;



$opc = $_POST['opc'];
$idE = $_SESSION['udn'];
switch ($opc) {
 case 1://AGREGAR IMPUESTOS
 $name = strtr(strtoupper($_POST['name']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $valor = $_POST['valor'];
 $id = $cat->Select_idImpuesto($name);
 if ( $id == 0) {
  $array = array($name,$valor);
  $cat->Insert_Impuestos($array);
  echo 1;
 }
 else {
  $cat->Update_StadoImpuestos(1,$id);
  echo 'L';
 }
 break;
 case 2://DESACTIVAR IMPUESTOS
 $id = $_POST['id'];
 $cat->Update_StadoImpuestos(0,$id);
 break;
 case 3://INSERTAR CATEGORIA
 $cont = $_POST['cont'];
 $categoria = strtr(strtoupper($_POST['categoria']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $movimiento = $_POST['movimiento'];

 $idCat = $cat->Select_idCategoria($categoria);
 if ( $idCat == 0) {
  $array = array($categoria,$movimiento);
  $cat->Insert_Categoria($array);
  $idCat = $cat->Select_idCategoria($categoria);
  for ($i=0; $i < $cont; $i++) {
   $array = array($idCat,$_POST['impuesto'.$i]);
   $cat->Insert_Categoria_Impuestos($array);
  }
  echo 1;
 }
 else {
  $cat->Update_Stado_Categoria(1,$idCat);
  echo 'L';
 }
 break;
 case 4://DESACTIVAR CATEGORIA
 $id = $_POST['id'];
 $cat->Update_Stado_Categoria(0,$id);
 break;
 case 5://INSERTAR SUBCATEGORIA
 $subcategoria = strtr(strtoupper($_POST['subcategoria']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $idC = $_POST['idC'];

 $idS = $cat->Select_idSubcategoria($subcategoria);
 if ( $idS == 0 ) {
  $array = array($subcategoria,$idC);
  $cat->Insert_Subcategoria($array);
  echo 1;
 }
 else {
  $cat->Update_Stado_Subcategoria(1,$idS);
  echo 'L';
 }
 break;
 case 6://DESACTIVAR SUBCATEGORIA
 $idS = $_POST['id'];
 $cat->Update_Stado_Subcategoria(0,$idS);
 break;
 case 7://INSERTAR FORMAS PAGO
 $fp = strtr(strtoupper($_POST['fp']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
 $idFP = $cat->Select_idFormasPago($fp);
 if ( $idFP == 0) {
  $cat->Insert_FormasPago($fp);
  echo 1;
 }
 else {
  $cat->Update_Stado_FormasPago(1,$idFP);
  echo 'L';
 }

 break;
 case 8://DESACTIVAR FORMAS PAGO
 $idFP = $_POST['idFP'];
 $cat->Update_Stado_FormasPago(0,$idFP);
 break;

 /*-----------------------------------*/
 /* Nuevo Destino
 /*-----------------------------------*/

 case 9:
 $des    = $_POST['des'];
 $array  = array($des);
 $cat->AgregarDestino($array,$idE);
 echo 1;
 break;



 case 10: // Desactivar destino

 $des = $_POST['des'];
 $cat -> UpdateDestino(0,$des);

 break;




}
?>
