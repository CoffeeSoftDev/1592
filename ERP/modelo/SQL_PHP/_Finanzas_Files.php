<?php
include_once("_CRUD.php");
Class Files_Fin extends CRUD {

 function NOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }
 function HoursNOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%H:%i:%s')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Archivos($array) {
  $row = null;
  $query = "SELECT Archivo FROM hgpqgijw_finanzas.sobres WHERE UDN_Sobre = ? AND Ruta = ? AND Archivo = ? ";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }

 function Insert_Sobres_vinculo($array) {
  $query = "INSERT INTO hgpqgijw_finanzas.sobres (UDN_Sobre,Ruta,Fecha,Hora,Archivo,Peso,Type_File,Descripcion,id_checklist) VALUES (?,?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }

 function Insert_Sobres($array) {
  $query = "INSERT INTO hgpqgijw_finanzas.sobres (UDN_Sobre,Ruta,Fecha,Hora,Archivo,Peso,Type_File,Descripcion) VALUES (?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }

 function Select_Cont_Sobres($date,$idE){
  $array = array($date,$idE);
  $query = "SELECT COUNT(*) FROM hgpqgijw_finanzas.sobres WHERE Fecha = ? AND UDN_Sobre = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function	ver_Categoria(){
  $sql="
  SELECT idCategoria,Categoria FROM hgpqgijw_finanzas.categoria WHERE id_udn = 1
  ";
  $ps	=	$this->_Select($sql,null);
  return	$ps;
 }

 function QuitarFichero($array){

  $query = "DELETE FROM hgpqgijw_finanzas.sobres WHERE idSobre = ? ";
  $this->_DIU($query,$array);
 }

function ver_CheckList($array){
 $sql="SELECT idcheck,check_name FROM hgpqgijw_finanzas.check_list Where id_udn = ?";
 $ps	=	$this->_Select($sql,$array);
 return	$ps;
}

}
?>
