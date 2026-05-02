<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class Catalogo extends CRUD {
  function Select_idCategorias($name){
    $array = array($name);
    $query = "SELECT idcategoria FROM hgpqgijw_operacion.mtto_categoria WHERE nombreCategoria = ?";
    $sql = $this->_Select($query,$array);
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_Categoria($name){
    $array = array($name);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_categoria (nombreCategoria) VALUES (?)";
    $this->_DIU($query,$array);
  }
  function Select_Tb_Categoria($limit,$Lotes){
    $query = "SELECT idcategoria,nombreCategoria FROM hgpqgijw_operacion.mtto_categoria ORDER BY nombreCategoria ASC  LIMIT $limit, $Lotes";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_Cont_Categoria(){
    $query = "SELECT Count(*) FROM hgpqgijw_operacion.mtto_categoria";
    $sql = $this->_Select($query,null);
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_Tb_Producto($limit,$Lotes){
    $query = "SELECT idEquipo,Nombre_Equipo,min_stock FROM hgpqgijw_operacion.mtto_almacen_equipos ORDER BY Nombre_Equipo ASC LIMIT $limit,$Lotes";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_Cont_Producto(){
    $query = "SELECT COUNT(*) FROM hgpqgijw_operacion.mtto_almacen_equipos";
    $sql = $this->_Select($query,null);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_idProducto($name){
    $array = array($name);
    $query = "SELECT idEquipo FROM hgpqgijw_operacion.mtto_almacen_equipos WHERE Nombre_Equipo = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_Producto($name,$stock){
    $array = array($name,$stock);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_equipos (Nombre_Equipo,min_stock) VALUES (?,?)";
    $this->_DIU($query,$array);
  }
  function Delete_Producto($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_operacion.mtto_almacen_equipos WHERE idEquipo = ?";
    $this->_DIU($query,$array);
  }
  function Select_Tb_Area($limit,$Lotes){
    $query = "SELECT idArea,Nombre_Area FROM hgpqgijw_operacion.mtto_almacen_area ORDER BY Nombre_Area ASC LIMIT $limit,$Lotes";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_Cont_Area(){
    $query = "SELECT COUNT(*) FROM hgpqgijw_operacion.mtto_almacen_area";
    $sql = $this->_Select($query,null);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_idArea($name) {
    $array = array($name);
    $query = "SELECT idArea FROM hgpqgijw_operacion.mtto_almacen_area WHERE Nombre_Area = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_Area($name){
    $array = array($name);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_area (Nombre_Area) VALUES (?)";
    $this->_DIU($query,$array);
  }
  function Delete_Categoria($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_operacion.mtto_categoria WHERE idcategoria = ?";
    $this->_DIU($query,$array);
  }
  function Delete_Area($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_operacion.mtto_almacen_area WHERE idArea = ?";
    $this->_DIU($query,$array);
  }
  function Select_Cont_Clase($cat_faml){
    $array = array($cat_faml);
    $query = "SELECT Count(*) FROM hgpqgijw_operacion.mtto_clase,hgpqgijw_operacion.mtto_clasefamilia WHERE id_clase = idclase AND id_familia = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_Tb_Clase($cat_faml,$limit,$Lotes){
    $array = array($cat_faml);
    $query = "SELECT idclase,nombreClase FROM hgpqgijw_operacion.mtto_clase,hgpqgijw_operacion.mtto_clasefamilia WHERE id_clase = idclase AND id_familia = ? ORDER BY nombreClase ASC LIMIT $limit,$Lotes";
    $sql = $this->_Select($query,$array);
    return $sql;
  }
  function Select_idClase($name){
    $array = array($name);
    $query = "SELECT idclase FROM hgpqgijw_operacion.mtto_clase WHERE nombreClase = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_ClaseFamilia($fam,$clas){
    $array = array($fam,$clas);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_clasefamilia (id_familia,id_clase) VALUES (?,?)";
    $this->_DIU($query,$array);
  }
  function Insert_Clase($name){
    $array = array($name);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_clase (nombreClase) VALUES (?)";
    $this->_DIU($query,$array);
  }
  function Delete_Clases($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_operacion.mtto_clase WHERE idclase = ?";
    $this->_DIU($query,$array);
  }
  function Select_Cont_Familias($catg){
    $array = array($catg);
    $query = "SELECT Count(*) FROM hgpqgijw_operacion.mtto_familia,hgpqgijw_operacion.tipoalmacen WHERE idTipo = idTipoAlmacen AND idTipo = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_Tb_Familias($catg,$limit,$Lotes){
    $array = array($catg);
    $query = "SELECT idfamilia,nombreFamilia FROM hgpqgijw_operacion.mtto_familia,hgpqgijw_operacion.tipoalmacen WHERE idTipo = idTipoAlmacen AND idTipo = ? ORDER BY nombreFamilia ASC LIMIT $limit,$Lotes";
    $sql = $this->_Select($query,$array);
    return $sql;
  }
  function Select_idFamilia($name){
    $array = array($name);
    $query = "SELECT idfamilia FROM hgpqgijw_operacion.mtto_familia WHERE nombreFamilia = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_Familia($name,$cat){
    $array = array($name,$cat);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_familia (nombreFamilia,idTipo) VALUES (?,?)";
    $this->_DIU($query,$array);
  }
  function Delete_Familia($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_operacion.mtto_familia WHERE idfamilia = ?";
    $this->_DIU($query,$array);
  }
  function Select_Cont_Insumo(){
    $query = "SELECT Count(*) FROM hgpqgijw_operacion.mtto_almacen_insumo";
    $sql = $this->_Select($query,null);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_Tb_Insumos($limit,$Lotes){
    $query = "SELECT idInsumo,NombreInsumo,min_stock FROM hgpqgijw_operacion.mtto_almacen_insumo ORDER BY NombreInsumo ASC LIMIT $limit,$Lotes";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_idInsumos($name){
    $array = array($name);
    $query = "SELECT idInsumo FROM hgpqgijw_operacion.mtto_almacen_insumo WHERE NombreInsumo = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_Insumos($name,$stock){
    $array = array($name,$stock);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_insumo (NombreInsumo,min_stock) VALUES (?,?)";
    $this->_DIU($query,$array);
  }
  function Delete_Insumos($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_operacion.mtto_almacen_insumo WHERE idInsumo = ?";
    $this->_DIU($query,$array);
  }
  function Select_Cont_Marca(){
    $query = "SELECT Count(*) FROM hgpqgijw_operacion.mtto_marca";
    $sql = $this->_Select($query,null);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_Tb_Marca($limit,$Lotes){
    $query = "SELECT idmarca,Marca_Producto FROM hgpqgijw_operacion.mtto_marca ORDER BY Marca_Producto ASC LIMIT $limit,$Lotes";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_idMarca($name){
    $array = array($name);
    $query = "SELECT idmarca FROM hgpqgijw_operacion.mtto_marca WHERE Marca_Producto = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_Marca($name){
    $array = array($name);
    $query = "INSERT INTO hgpqgijw_operacion.mtto_marca (Marca_Producto) VALUES (?)";
    $this->_DIU($query,$array);
  }
  function Delete_Marca($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_operacion.mtto_marca WHERE idmarca = ?";
    $this->_DIU($query,$array);
  }
  function Select_TipoAlmacen(){
    $query = "SELECT idTipoAlmacen,NombreTipo FROM hgpqgijw_operacion.tipoalmacen";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_Familia(){
    $query = "SELECT idfamilia,nombreFamilia,NombreTipo FROM hgpqgijw_operacion.mtto_familia,hgpqgijw_operacion.tipoalmacen WHERE idTipoAlmacen = idTipo ";
    $sql = $this->_Select($query,null);
    return $sql;
  }
}
?>
