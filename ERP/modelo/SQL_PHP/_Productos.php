<?php
include_once ('_CRUD.php');

Class Productos extends CRUD {


 function Insert_Productos_Insumos($array){
  $query = "INSERT INTO hgpqgijw_dia.venta_productos
  (FechaIngreso,NombreProducto,precio,Status,id_tipo) VALUES (?,?,?,1,2)";
  $this->_DIU($query,$array);
 }

 function NOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }

 function Select_tabla_productos($array){
  $query = "SELECT idProducto,NombreProducto,presentacion,precio,min_inventario,min_mayoreo,precio_mayoreo FROM hgpqgijw_dia.venta_productos WHERE Status = 1 and id_tipo=? ORDER BY NombreProducto ASC";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_data_productos($idProducto){
  $array = array($idProducto);
  $query = "SELECT NombreProducto,presentacion,precio,min_inventario FROM hgpqgijw_dia.venta_productos WHERE idProducto = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Select_Inventario_Minimo($idProducto){
  $row = null; $array = array($idProducto);
  $query = "SELECT min_inventario FROM hgpqgijw_dia.venta_productos WHERE idProducto = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_count_productos($array){
  $query = "SELECT COUNT(idProducto) FROM hgpqgijw_dia.venta_productos WHERE Status = 1 and id_tipo=? ORDER BY NombreProducto ASC";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_idProducto($name,$presentacion){
  $row = null; $array = array($name,$presentacion);
  $query = "SELECT idProducto FROM hgpqgijw_dia.venta_productos WHERE NombreProducto = ? AND presentacion = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Productos($array){
  $query = "INSERT INTO hgpqgijw_dia.venta_productos (FechaIngreso,NombreProducto,presentacion,min_inventario,precio,precio_mayoreo,Status,min_mayoreo,id_tipo) VALUES (?,?,?,?,?,?,1,?,?)";
  $this->_DIU($query,$array);
 }
 function Select_Inventario_Inicial($idProducto){
  $array = array($idProducto);
  $query = "SELECT Fecha_Inventario,Inventario_total FROM hgpqgijw_dia.almacen_producto WHERE id_Producto = ? ORDER BY Fecha_Inventario DESC LIMIT 1";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Inventario_Vendido($idProducto,$date_inical){
  $array = array($idProducto,$date_inical);
  $query = "SELECT COUNT(idListaProductos) FROM hgpqgijw_dia.lista_folio,hgpqgijw_dia.lista_productos WHERE id_lista = idLista AND id_productos = ? AND fecha BETWEEN ? AND NOW()";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Inventario_Inicial2($idProducto){
  $array = array($idProducto);
  $query = "SELECT Fecha_Inventario,Inventario_total FROM hgpqgijw_dia.almacen_producto WHERE id_Producto = ? AND Fecha_Inventario <= NOW() ORDER BY Fecha_Inventario DESC LIMIT 1";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Inventario_Vendido2($idProducto,$date_inical,$date_final){
  $array = array($idProducto,$date_inical,$date_final);
  $query = "SELECT COUNT(idListaProductos) FROM hgpqgijw_dia.lista_folio,hgpqgijw_dia.lista_productos WHERE id_lista = idLista AND id_productos = ? AND fecha BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Movimientos_Entrada_Inventario($idProducto){
  $row = null; $array = array($idProducto);
  $query = "SELECT SUM(Inventario_total) FROM hgpqgijw_dia.almacen_producto WHERE Fecha_Inventario <= NOW() AND id_Producto = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Movimientos_Salida_Inventario($idProducto){
  $row = null; $array = array($idProducto);
  $query = "SELECT SUM(cantidad) FROM hgpqgijw_dia.lista_folio,hgpqgijw_dia.lista_productos WHERE id_lista = idLista AND fecha <= NOW() AND id_productos = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Update_Presentacion($valor,$idProducto) {
  $array = array($valor,$idProducto);
  $query = "UPDATE hgpqgijw_dia.venta_productos SET presentacion = ? WHERE idProducto = ?";
  $this->_DIU($query,$array);
 }
 function Update_Precio($valor,$idProducto) {
  $array = array($valor,$idProducto);
  $query = "UPDATE hgpqgijw_dia.venta_productos SET precio = ? WHERE idProducto = ?";
  $this->_DIU($query,$array);
 }
 function Insert_Nuevo_Inventario($valor,$idProducto) {
  $array = array($valor,$idProducto);
  $query = "INSERT INTO hgpqgijw_dia.almacen_producto (Inventario_total,id_Producto,Fecha_Inventario) VALUES (?,?,NOW())";
  $this->_DIU($query,$array);
 }
 function Update_Inventario_Minimo($valor,$idProducto) {
  $array = array($valor,$idProducto);
  $query = "UPDATE hgpqgijw_dia.venta_productos SET min_inventario = ? WHERE idProducto = ?";
  $this->_DIU($query,$array);
 }
 function Update_Mayoreo_Minimo($valor,$idProducto) {
  $array = array($valor,$idProducto);
  $query = "UPDATE hgpqgijw_dia.venta_productos SET min_mayoreo = ? WHERE idProducto = ?";
  $this->_DIU($query,$array);
 }
 function Update_Precio_Mayoreo($valor,$idProducto) {
  $array = array($valor,$idProducto);
  $query = "UPDATE hgpqgijw_dia.venta_productos SET precio_mayoreo = ? WHERE idProducto = ?";
  $this->_DIU($query,$array);
 }
 function Select_idCanasta($canasta) {
  $row = null; $array = array($canasta);
  $query = "SELECT idCanasta FROM hgpqgijw_dia.venta_canasta WHERE Canasta = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Canasta($canasta) {
  $array = array($canasta);
  $query = "INSERT INTO hgpqgijw_dia.venta_canasta (Canasta,Stado) VALUE (?,2)";
  $this->_DIU($query,$array);
 }
 function Update_Precio_Canasta($costo,$idCanasta) {
  $array = array($costo,$idCanasta);
  $query = "UPDATE hgpqgijw_dia.venta_canasta SET costo = ? WHERE idCanasta = ?";
  $this->_DIU($query,$array);
 }
 function Update_stado_canasta($idCanasta) {
  $array = array($idCanasta);
  $query = "UPDATE hgpqgijw_dia.venta_canasta SET Stado = 1 WHERE idCanasta = ?";
  $this->_DIU($query,$array);
 }
 function Select_Count(){
  $query = "SELECT COUNT(*) FROM hgpqgijw_dia.venta_canasta WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Tb_Canasta(){
  $query = "SELECT idCanasta,Canasta,Costo,Minimo_Mayoreo,Costo_Mayoreo FROM hgpqgijw_dia.venta_canasta WHERE Stado != 0 ORDER BY Canasta ASC";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_count_productos_canastas($idCanasta){
  $array = array($idCanasta);
  $query = "SELECT COUNT(*) FROM hgpqgijw_dia.canasta_productos WHERE id_Canasta = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_cantidad_productos_canastas($idCanasta){
  $array = array($idCanasta);
  $query = "SELECT ROUND(SUM(precio),2) FROM hgpqgijw_dia.canasta_productos,hgpqgijw_dia.venta_productos WHERE id_Producto = idProducto AND id_Canasta = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_cantidad_productos_canastas_mayoreo($idCanasta){
  $row1 = null; $array = array($idCanasta);
  $query = "SELECT ROUND(SUM(precio_mayoreo),2) FROM hgpqgijw_dia.canasta_productos,hgpqgijw_dia.venta_productos WHERE id_Producto = idProducto AND id_tipo = 1 AND id_Canasta = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row1);
  if ( !isset($row1[0]) ) { $row1[0] = 0; }

  $row2 = null;
  $query = "SELECT ROUND(SUM(precio),2) FROM hgpqgijw_dia.canasta_productos,hgpqgijw_dia.venta_productos WHERE id_Producto = idProducto AND id_tipo = 2 AND id_Canasta = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row2);
  if ( !isset($row2[0]) ) { $row2[0] = 0; }

  return ($row1[0] + $row2[0]);
 }
 function Select_Cantidad_Existencia($idCanasta){
   $row = null; $row1 = null;
   $query = "SELECT SUM(Inventario) FROM hgpqgijw_dia.almacen_canasta WHERE id_Canasta = ?";
   $sql = $this->_Select($query,$array);
   foreach ($sql as $row);

   $query = "SELECT COUNT() FROM hgpqgijw_dia.almacen_canasta WHERE id_Canasta = ?";
   $sql = $this->_Select($query,$array);
   foreach ($sql as $row);

 }

 function Select_Ultima_Canasta(){
  $query = "SELECT idCanasta,Canasta FROM hgpqgijw_dia.venta_canasta WHERE Stado = 2";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Insert_Producto_Canasta($idProducto,$idCanasta){
  $array = array($idProducto,$idCanasta);
  $query = "INSERT INTO hgpqgijw_dia.canasta_productos (id_Producto,id_Canasta) VALUES (?,?)";
  $this->_DIU($query,$array);
 }
 function Select_Producto_canasta($idCanasta){
  $array = array($idCanasta);
  $query = "SELECT idCanasta_Producto,id_Producto,NombreProducto,presentacion,precio,precio_mayoreo FROM hgpqgijw_dia.canasta_productos,hgpqgijw_dia.venta_productos WHERE id_Producto = idProducto AND id_Canasta = ? GROUP BY id_Producto ORDER BY NombreProducto ASC";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Cantidad_Producto_Canasta($idCanasta,$idProducto){
   $row = null; $array = array($idCanasta,$idProducto);
   $query = "SELECT Count(*) FROM hgpqgijw_dia.canasta_productos WHERE id_Canasta = ? AND id_Producto = ?";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row);
   if ( !isset($row[0]) ) { $row[0] = 0; }
   return $row[0];
 }
 function Delete_Producto_canasta($idCanastaProducto){
  $array = array($idCanastaProducto);
  $query = "DELETE FROM hgpqgijw_dia.canasta_productos WHERE idCanasta_Producto = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Delete_Producto($idProducto){
  $array = array($idProducto);
  $query = "UPDATE hgpqgijw_dia.venta_productos SET Status = 0 WHERE idProducto = ?";
  $this->_DIU($query,$array);
 }
 function Delete_Canasta($idCanasta){
  $array = array($idCanasta);
  $query = "UPDATE hgpqgijw_dia.venta_canasta SET Stado = 0 WHERE idCanasta = ?";
  $this->_DIU($query,$array);
 }
 function Insert_Almacen_Canasta($Cantidad,$idCanasta){
  $array = array($Cantidad,$idCanasta);
  $query = "INSERT INTO hgpqgijw_dia.almacen_canasta (Inventario,id_Canasta,Fecha_Inventario) VALUES (?,?,NOW())";
  $this->_DIU($query,$array);
 }
 function Select_Canasta_Entrada_Inventario($idCanasta){
   $row = null; $array = array($idCanasta);
   $query = "SELECT SUM(Inventario) FROM hgpqgijw_dia.almacen_canasta WHERE id_Canasta = ?";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row);
   if ( !isset($row[0]) ) { $row[0] = 0; }
   return $row[0];
 }
 function Select_Canasta_Salida_Inventario($idCanasta){
   $row = null; $array = array($idCanasta);
   $query = "SELECT SUM(Cantidad) FROM hgpqgijw_dia.lista_canasta WHERE id_Canasta = ?";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row);
   if ( !isset($row[0]) ) { $row[0] = 0; }
   return $row[0];
 }
}
?>
