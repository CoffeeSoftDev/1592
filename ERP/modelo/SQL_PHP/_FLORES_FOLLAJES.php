<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class _PRODUCTOS extends CRUD{

 function list_pedidos(){
  $query = "SELECT idDisponibilidad FROM hgpqgijw_ventas.venta_disponibilidad WHERE  fecha = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function NOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function Insert_Folio($nFolio,$date) {
  $array = array($nFolio,$date);
  $query = "INSERT INTO hgpqgijw_ventas.venta_disponibilidad (folio,fecha) VALUES (?,?)";
  $this->_DIU($query,$array);
 }

 function Select_idFolio($date) {
  $array = array($date);
  $query = "SELECT idDisponibilidad FROM hgpqgijw_ventas.venta_disponibilidad WHERE  fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_Folio($date) {

  $array = array($date);
  $query = "SELECT idDisponibilidad,Folio FROM hgpqgijw_ventas.venta_disponibilidad WHERE  DATE_FORMAT(fecha,'%Y-%m-%d') = ?";
  $sql = $this->_Select($query,$array);


  return $sql;
 }

 function Select_FolioDesc($date) {
  $array = array($date);
  $query = "SELECT Folio FROM hgpqgijw_ventas.venta_disponibilidad WHERE fecha < ? ORDER BY idDisponibilidad DESC LIMIT 1";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_idBit($array){
  $query = "SELECT idBit FROM hgpqgijw_ventas.ventas_disponibilidad_productos WHERE id_disponibilidad = ? AND id_productos = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_MontoSubtotal($a,$b){

  $array = array($a,$b);

  $query ="SELECT
  cantidad_disponible
  FROM hgpqgijw_ventas.ventas_disponibilidad_productos
  WHERE id_disponibilidad = ? AND id_productos = ? ";

  $sql = $this->_Select($query,$array);

  foreach($sql as $row2);

  if ( !isset($row2[0]) ) { $row2[0] = 0; }

  return $row2[0];
 }


 function Select_LastFolio($date) {
  $row   = null;
  $array = array($date);
  $query = "SELECT idDisponibilidad,folio,date_format(fecha,'%Y-%m-%d') FROM hgpqgijw_ventas.venta_disponibilidad WHERE fecha < ? ORDER BY idDisponibilidad DESC LIMIT 1";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  return $sql;
 }

 /*-----------------------------------*/
 /* Formato
 /*-----------------------------------*/
 function	_Categoria($array){
  $sql="
  SELECT
  hgpqgijw_ventas.venta_categoria.idcategoria,
  hgpqgijw_ventas.venta_categoria.nombrecategoria
  FROM
  hgpqgijw_ventas.venta_categoria
  WHERE id_udn = ?
  ";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function	_SubCategoria($array){
  $sql="
  SELECT
  idSubcategoria,
  Nombre_subcategoria
  FROM
  hgpqgijw_ventas.venta_subcategoria
  Where id_categoria = ? and status_sub = 1
  ";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function	ver_productos_sub($udn,$idC){
  $OPC   = '';
  $array = null;

  if ($idC!=0) {
   $OPC = 'and idSubcategoria = ?';
   $array = array($udn,$idC);
  }else {
   $array = array($udn);
  }

  $sql="
  SELECT
  NombreProducto,
  Costo,
  Venta,

  Unidad,
  nombrecategoria,
  Stock_Inicial,

  Stock_Minimo,
  idProducto

  FROM
  hgpqgijw_ventas.venta_productos
  INNER JOIN hgpqgijw_ventas.venta_subcategoria ON venta_productos.id_subcategoria = venta_subcategoria.idSubcategoria
  INNER JOIN hgpqgijw_ventas.venta_categoria ON venta_subcategoria.id_categoria = venta_categoria.idcategoria
  INNER JOIN hgpqgijw_ventas.venta_unidad ON venta_productos.id_unidad = venta_unidad.idUnidad

  WHERE id_udn = ? ".$OPC."";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function update_col($array){
  $sql   = "UPDATE hgpqgijw_ventas.ventas_disponibilidad_productos
  SET cantidad_disponible = ?
  WHERE idBit = ? ";
  $ps	   = $this->_DIU($sql,$array,"1");
 }

 function	ver_formatos($array){
  $sql="
  SELECT
  venta_disponibilidad.folio,
  date_format(fecha,'%Y-%m-%d'),
  count(id_productos) as total,
  ROUND(SUM(cantidad_disponible * Venta),2) as gral,
  -- idDisponibilidad
  date_format(fecha,'%Y-%m-%d')
  FROM
  hgpqgijw_ventas.ventas_disponibilidad_productos
  INNER JOIN hgpqgijw_ventas.venta_disponibilidad ON ventas_disponibilidad_productos.id_disponibilidad = venta_disponibilidad.idDisponibilidad
  INNER JOIN hgpqgijw_ventas.venta_productos ON ventas_disponibilidad_productos.id_productos = venta_productos.idProducto
  GROUP BY folio
  ";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function SAVE_FORM($array,$campos,$bd){
  $data ='';
  $x    ='';
  $query ='';
  $query = $query."INSERT INTO ".$bd."(" ;

  for ($i=0; $i < count($campos); $i++) {
   $data = $data."".$campos[$i].",";
   $x    = $x.'?,';
  }

  $data  = substr($data,0,strlen($data)-1);
  $x     = substr($x,0,strlen($x)-1);

  $query = $query.$data.") VALUES (".$x.")";
  $this->_DIU($query,$array);

  return $query;
 }

 
 function	VerProductos($array){
		$sql="
		SELECT
		NombreProducto,
		Costo,
		Venta,

		nombrecategoria,
		Stock_Inicial,
		Stock_Minimo,
		idProducto
		FROM
		hgpqgijw_ventas.venta_productos
		INNER JOIN hgpqgijw_ventas.venta_subcategoria ON hgpqgijw_ventas.venta_productos.id_subcategoria = hgpqgijw_ventas.venta_subcategoria.idSubcategoria
		INNER JOIN hgpqgijw_ventas.venta_categoria ON hgpqgijw_ventas.venta_subcategoria.id_categoria = hgpqgijw_ventas.venta_categoria.idcategoria
		WHERE id_udn = ?";
		$ps	=	$this->_Select($sql,$array);
		return	$ps;
	}
}
