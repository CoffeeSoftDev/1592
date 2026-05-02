<?php
include_once ('_CRUD.php');

Class Movimientos extends CRUD {
  function Select_ListaNameProductos() {
    $query = "SELECT idProducto,NombreProducto,presentacion FROM hgpqgijw_dia.venta_productos";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_Fechas_Movimientos($date1,$date2,$idProducto){
    $array = array($date1,$date2,$idProducto,$date1,$date2,$idProducto);
    $query = "SELECT fecha AS fecha FROM hgpqgijw_dia.lista_folio,hgpqgijw_dia.lista_productos WHERE id_lista = idLista AND fecha BETWEEN ? AND ? AND id_productos = ? UNION SELECT Fecha_Inventario AS Fecha FROM hgpqgijw_dia.almacen_producto WHERE Fecha_Inventario BETWEEN ? AND ? AND id_Producto = ? ORDER BY Fecha asc";
    $sql = $this->_Select($query,$array);
    return $sql;
  }
  function Select_Movimientos_Entrada($date,$idProducto){
    $row = null; $array = array($date,$idProducto);
    $query = "SELECT SUM(Inventario_total) FROM hgpqgijw_dia.almacen_producto WHERE Fecha_Inventario = ? AND id_Producto = ?";
    $sql = $this->_Select($query,$array);
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = '-'; }
    return $row[0];
  }
  function Select_Movimientos_Salida($date,$idProducto){
    $row = null; $array = array($date,$idProducto);
    $query = "SELECT SUM(cantidad) FROM hgpqgijw_dia.lista_folio,hgpqgijw_dia.lista_productos WHERE id_lista = idLista AND fecha = ? AND id_productos = ?";
    $sql = $this->_Select($query,$array);
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = '-'; }
    return $row[0];
  }

  function Select_MinInventario_Productos($idProducto){
    $array = array($idProducto);
    $query = "SELECT min_inventario FROM hgpqgijw_dia.venta_productos WHERE idProducto = ?";
    $sql = $this->_Select($query,$array);
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_Movimientos_Entrada_Inventario($idProducto){
    $row = null; $array = array($idProducto);
    $query = "SELECT SUM(Inventario_total) FROM hgpqgijw_dia.almacen_producto WHERE Fecha_Inventario <= NOW() AND id_Producto = ?";
    $sql = $this->_Select($query,$array);
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = '-'; }
    return $row[0];
  }
  function Select_Movimientos_Salida_Inventario($idProducto){
    $row = null; $array = array($idProducto);
    $query = "SELECT SUM(cantidad) FROM hgpqgijw_dia.lista_folio,hgpqgijw_dia.lista_productos WHERE id_lista = idLista AND fecha <= NOW() AND id_productos = ?";
    $sql = $this->_Select($query,$array);
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = '-'; }
    return $row[0];
  }
}
?>
