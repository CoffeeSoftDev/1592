<?php
  include_once('../../../modelo/SQL_PHP/_Catalogo_MTTO.php');
  $fin = new Catalogo;
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://CATEGORIA
      $name = strtr(strtoupper($_POST['ipt_categoria']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ"); //Conquetenamos el nombre completo
      $idC = $fin->Select_idCategorias($name);
      if ( $idC == 0 ) {
        $fin->Insert_Categoria($name);
      }
      echo $idC;
      break;
    case 1://PRODUCTOS
      $name = strtr(strtoupper($_POST['ipt_producto']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $stock = $_POST['inp_stock'];
      $idP = $fin->Select_idProducto($name);
      if ( $idP == 0 ) {
        $fin->Insert_Producto($name,$stock);
      }
      echo $idP;
      break;
    case 2://AREAS
      $name = strtr(strtoupper($_POST['ipt_area']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $idA = $fin->Select_idArea($name);
      if ( $idA == 0) {
        $fin->Insert_Area($name);
      }
      echo $idA;
      break;
    case 3://ELIMINAR MATERIALES
      $dat = $_POST['dat'];
      $id = $_POST['id'];
      if ( $dat == 3 ) {
        $fin->Delete_Categoria($id);
      }
      else if ( $dat == 4 ) {
        $fin->Delete_Producto($id);
      }
      else if ( $dat == 5) {
        $fin->Delete_Area($id);
      }
      break;
    case 4://CLASES
      $fam = $_POST['idFam'];
      $name = strtr(strtoupper($_POST['Inp_Clases']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $idC = $fin->Select_idClase($name);
      if ( $idC == 0 ) {
        $fin->Insert_Clase($name);
        $idC = $fin->Select_idClase($name);
        $fin->Insert_ClaseFamilia($fam,$idC);
      }
      echo $idC;
      break;
    case 5://FAMILIAS
      $cat = $_POST['cat'];
      $name = strtr(strtoupper($_POST['inp_familia']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $idF = $fin->Select_idFamilia($name);
      if ( $idF == 0 ) {
        $fin->Insert_Familia($name,$cat);
      }
      echo $idF;
      break;
    case 6://INSUMOS
      $name = strtr(strtoupper($_POST['ipt_insumo']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $stock = $_POST['inp_stock'];
      $idP = $fin->Select_idInsumos($name);
      if ( $idP == 0 ) {
        $fin->Insert_Insumos($name,$stock);
      }
      echo $idP;
      break;
    case 7://MARCA
      $name = strtr(strtoupper($_POST['ipt_marca']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $idM = $fin->Select_idMarca($name);
      if ( $idM == 0 ) {
        $fin->Insert_Marca($name);
      }
      echo $idM;
      break;
    case 8://ELIMINAR CONSUMIBLES
      $dat = $_POST['dat'];
      $id = $_POST['id'];
      if ( $dat == 5 ) {
        $fin->Delete_Clases($id);
      }
      else if ( $dat == 6 ) {
        $fin->Delete_Familia($id);
      }
      else if ( $dat == 7) {
        $fin->Delete_Insumos($id);
      }
      else if ( $dat == 8) {
        $fin->Delete_Marca($id);
      }
      break;
  }
?>
