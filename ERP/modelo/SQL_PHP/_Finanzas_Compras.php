<?php
include_once("_CRUD.php");

Class Compras_Fin extends CRUD {


 /*-----------------------------------*/
 /*	GASTOS COMPRAS
 /*-----------------------------------*/

 function	VER_GASTOS_COMPRAS($date){
  $sql="
  SELECT
  compras.FechaFactura,
  compras.NumPedido,
  compras.Factura,

  proveedor.Name_Proveedor,
  gastos.Name_Gastos,
  (compras.Gasto - compras.GastoIVA),

  compras.GastoIVA,
  compras.Gasto,
  insumos_clase.Name_IC,

  gasto_clase.Name_CG,
  compras.Observacion,
  compras.Gasto,
  idCompras
  FROM
  hgpqgijw_finanzas.compras
  INNER JOIN hgpqgijw_finanzas.proveedor_udn ON compras.id_UP = proveedor_udn.idUP
  INNER JOIN hgpqgijw_finanzas.proveedor ON proveedor_udn.id_Proveedor = proveedor.idProveedor
  INNER JOIN hgpqgijw_finanzas.gastos_udn ON compras.id_UG = gastos_udn.idUG
  INNER JOIN hgpqgijw_finanzas.gastos ON gastos_udn.id_Gastos = gastos.idGastos
  INNER JOIN hgpqgijw_finanzas.gasto_clase ON compras.id_CG = gasto_clase.idCG
  INNER JOIN hgpqgijw_finanzas.insumos_udn ON compras.id_UI = insumos_udn.idUI
  INNER JOIN hgpqgijw_finanzas.insumos_clase ON insumos_udn.id_IC = insumos_clase.idIC
  WHERE
  Fecha_Compras = '".$date."' and Factura is not null
  ";
  $ps	=	$this->_Select($sql,null);
  return	$ps;
 }

 function	BUSCAR_ARCHIVO($array){
  $existe = 1;
  $sql="
  SELECT
  id_file
  FROM
  hgpqgijw_finanzas.fcompras
  INNER JOIN hgpqgijw_finanzas.sobres
  ON fcompras.id_file = sobres.idSobre
  WHERE
  Fecha= ? AND
  id_compras = ?   ";
  $ps	=	$this->_Select($sql,$array);

  foreach ($ps as $key) {
   $existe = 2;
  }

  return	$existe;
 }

 function	EliminarArchivo($array){
  $ruta = "";
  $sql   =
  "SELECT
  id_file,
  idfc,
  Ruta,
  Archivo
  FROM
  hgpqgijw_finanzas.fcompras
  INNER JOIN hgpqgijw_finanzas.sobres ON id_file = idSobre
  WHERE
  id_compras = ?";
  $ps	   =	$this->_Select($sql,$array);
  foreach ($ps as $key);
  // ---
  $array = array($key[0]);
  $sql   = "DELETE FROM hgpqgijw_finanzas.sobres WHERE idSobre = ?";
  $this->_DIU($sql,$array);
  // ---
  $array = array($key[1]);
  $sql   = "DELETE FROM hgpqgijw_finanzas.fcompras WHERE IDFC = ? ";
  $this->_DIU($sql,$array);

  $ruta  = $key[2].''.$key[3];
  return $ruta;
 }

 /*-----------------------------------*/
 /*	PANES - DIVERSIFICADOS
 /*-----------------------------------*/

 function NOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function Ayer(){
  $query = "SELECT DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function Select_Pagadores($opc){
  $query = "";
  if ( $opc == 0) {
   $query = "SELECT idCG,Name_CG FROM hgpqgijw_finanzas.gasto_clase";
  }
  else{
   $query = "SELECT idCG,Name_CG FROM hgpqgijw_finanzas.gasto_clase WHERE idCG != 2";
  }
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Categoria($array){
  $query = "SELECT idUI,Name_IC
  FROM hgpqgijw_finanzas.insumos_clase,
  hgpqgijw_finanzas.insumos_udn WHERE id_IC = idIC
  and id_UDN = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Insert_Empresa_Proveedor($array){
  $query = "INSERT INTO hgpqgijw_finanzas.proveedor_udn (id_Proveedor,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array);
 }
 function Select_idGasto($Insumo){
  $array = array($Insumo);
  $row = null;
  $query = "SELECT idGastos FROM hgpqgijw_finanzas.gastos WHERE Name_Gastos = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Gasto($Insumo){
  $array = array($Insumo);
  $query = "INSERT INTO hgpqgijw_finanzas.gastos (Name_Gastos) VALUES (?)";
  $this->_DIU($query,$array);
 }
 function Select_Empresa_Gasto($array){
  $row = null;
  $query = "SELECT idUG FROM hgpqgijw_finanzas.gastos_udn WHERE id_Gastos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Empresa_Gasto($array){
  $query = "INSERT INTO hgpqgijw_finanzas.gastos_udn (id_Gastos,id_UDN,Stado) VALUES (?,?,1)";
  $sql = $this->_DIU($query,$array);
 }
 function Select_Empresa_ClaseInsumo($array){
  $row = null;
  $query = "SELECT idUI FROM hgpqgijw_finanzas.insumos_udn WHERE id_IC = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_idAlmacen($idE){
  $array = array($idE);
  $query = "SELECT idIC,Name_IC FROM hgpqgijw_finanzas.insumos_clase,hgpqgijw_finanzas.insumos_udn WHERE idIC = id_IC AND id_UDN  = ? AND Stado = 1";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Insert_Bitacora_Compras($query,$array){
  $this->_DIU($query,$array);
 }

 function Select_Especific_Proveedor($id){
  $query = "SELECT Name_Proveedor FROM hgpqgijw_finanzas.proveedor,hgpqgijw_finanzas.proveedor_udn WHERE idProveedor = id_Proveedor AND idUP = ".$id;
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Especific_Insumo($id){
  $query = "SELECT Name_Gastos FROM hgpqgijw_finanzas.gastos,hgpqgijw_finanzas.gastos_udn WHERE idGastos = id_Gastos AND idUG = ".$id;
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Especific_CI($id){
  $query = "SELECT Name_IC FROM hgpqgijw_finanzas.insumos_clase,hgpqgijw_finanzas.insumos_udn WHERE idIC = id_IC AND idUI = ".$id;
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Especific_TG($id){
  $row = null;
  $query = "SELECT Name_CG FROM hgpqgijw_finanzas.gasto_clase WHERE idCG = ".$id;
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function Select_Proveedores(){
  $query = "SELECT Name_Proveedor FROM hgpqgijw_finanzas.proveedor ORDER BY Name_Proveedor ASC";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Insumos(){
  $query = "SELECT Name_Gastos FROM hgpqgijw_finanzas.gastos ORDER BY Name_Gastos ASC";
  $sql = $this->_Select($query,null);
  return $sql;
 }

 function Select_NameGasto_X_identificador($Id){
  $array = array($Id); $row = null;
  $query = "SELECT Name_Gastos FROM hgpqgijw_finanzas.gastos,hgpqgijw_finanzas.gastos_udn,hgpqgijw_finanzas.compras WHERE idGastos = id_Gastos AND idUG = id_UG AND idCompras = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_NameProvedor_X_Indentificador($idCompras){
  $array = array($idCompras); $row = null;
  $query = "SELECT Name_Proveedor FROM hgpqgijw_finanzas.proveedor,hgpqgijw_finanzas.proveedor_udn,hgpqgijw_finanzas.compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND idCompras = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  return $row[0];
 }
 function Select_idProveedores($Proveedor){
  $array = array($Proveedor); $row = null;
  $query = "SELECT idProveedor FROM hgpqgijw_finanzas.proveedor WHERE Name_Proveedor = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  return $row[0];
 }
 function Insert_Proveedor($Proveedor){
  $array = array($Proveedor);
  $query = "INSERT INTO hgpqgijw_finanzas.proveedor (Name_Proveedor) VALUES (?)";
  $this->_DIU($query,$array);
 }
 function Select_Empresa_Proveedor($array){
  $row = null;
  $query = "SELECT idUP FROM hgpqgijw_finanzas.proveedor_udn WHERE id_Proveedor = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Update_Proveedor_Pago($array){
  $query = "UPDATE hgpqgijw_finanzas.compras SET id_UP = ? WHERE idCompras = ?";
  $this->_DIU($query,$array);
 }
 function Update_Insumo_Pago($array){
  $query = "UPDATE hgpqgijw_finanzas.compras SET id_UG = ? WHERE idCompras = ?";
  $this->_DIU($query,$array);
 }
 function Update_Insumo_Clase($array){
  $query = "UPDATE hgpqgijw_finanzas.compras SET id_UI = ? WHERE idCompras = ?";
  $this->_DIU($query,$array);
 }
 function Select_Name_Insumo_Clase($id){
  $array = array($id);
  $query = "SELECT Name_IC FROM hgpqgijw_finanzas.insumos_clase WHERE idIC = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Update_Cantidad_Compras2($array){
  $query = "UPDATE hgpqgijw_finanzas.compras SET Gasto = ? WHERE idCompras = ?";
  $this->_DIU($query,$array);
 }
 function Update_Cantidad_Compras($array){
  $query = "UPDATE hgpqgijw_finanzas.compras SET Pago = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Clase_Gasto($array){
  $query = "UPDATE hgpqgijw_finanzas.compras SET id_CG = ? WHERE idCompras = ?";
  $this->_DIU($query,$array);
 }
 function Select_Name_Clase_Gasto($id){
  $array = array($id);
  $query = "SELECT Name_CG FROM hgpqgijw_finanzas.gasto_clase WHERE idCG = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Update_Observaciones_Compras($array){
  $query = "UPDATE hgpqgijw_finanzas.compras SET Observacion = ? WHERE idCompras = ?";
  $this->_DIU($query,$array);
 }
 function Select_Group_Insumo($idE){
  $array = array($idE);
  $query = "SELECT idIC,Name_IC FROM hgpqgijw_finanzas.insumos_clase,hgpqgijw_finanzas.insumos_udn WHERE id_IC = idIC AND Stado = 1 AND id_UDN = ? ORDER BY Name_IC asc";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Group_Gastos(){
  $query = "SELECT idCG,Name_CG FROM hgpqgijw_finanzas.gasto_clase";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Delete_Compras_Pago($id){
  $array = array($id);
  $query = "DELETE FROM hgpqgijw_finanzas.compras WHERE idCompras = ?";
  $this->_DIU($query,$array);
 }
}
?>
