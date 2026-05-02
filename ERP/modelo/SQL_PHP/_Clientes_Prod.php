<?php
include_once ('_CRUD.php');

Class CLIENTES extends CRUD {
  function Select_Clientes(){
    // $query = "SELECT idCliente,Name_Cliente FROM hgpqgijw_dia.clientes WHERE Name_Cliente != '' OR NULL  AND estado_cliente = 1" ;
    $query = "SELECT idCliente,Name_Cliente FROM hgpqgijw_dia.clientes WHERE  estado_cliente = 1" ;
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_Productos(){
    $query = "SELECT idProducto,NombreProducto,Precio,Precio_Mayoreo,presentacion FROM hgpqgijw_dia.venta_productos";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Insert_Cliente($Name){
    $array = array($Name);
    $query = "INSERT INTO hgpqgijw_dia.clientes (Name_Cliente) VALUES (?)";
    $this->_DIU($query,$array);
  }
  function Select_Precio_especial($idCliente,$idProducto){
    $row = null; $array = array($idCliente,$idProducto);
    $query = "SELECT Costo FROM hgpqgijw_dia.precio_especial WHERE id_Cliente = ? AND id_Producto = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = ''; }
    return $row[0];
  }
  function Select_idPrecioEspecial($idCliente,$idProducto){
    $row = null; $array = array($idCliente,$idProducto);
    $query = "SELECT idEspecial FROM hgpqgijw_dia.precio_especial WHERE id_Cliente = ? AND id_Producto = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_PrecioEspecial($idCliente,$idProducto,$Costo){
    $array = array($idCliente,$idProducto,$Costo);
    $query = "INSERT INTO hgpqgijw_dia.precio_especial (id_Cliente,id_Producto,Costo) VALUES (?,?,?)";
    $this->_DIU($query,$array);
  }
  function Update_PrecioEspecial($Costo,$idEpecial){
    $array = array($Costo,$idEpecial);
    $query = "UPDATE hgpqgijw_dia.precio_especial SET Costo = ? WHERE idEspecial = ?";
    $this->_DIU($query,$array);
  }
  function Delete_PrecioEspecial($idCliente,$idProducto){
    $array = array($idCliente,$idProducto);
    $query = "DELETE FROM hgpqgijw_dia.precio_especial WHERE id_Cliente = ? AND id_Producto = ?";
    $this->_DIU($query,$array);
  }
}
?>
