<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class Caratula extends CRUD {



 /*-----------------------------------*/
 /*	compras detalles
 /*-----------------------------------*/
 function ver_Compras($idE,$idCG,$date){
  $array = array($idE,$idCG,$date); $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2)
  FROM hgpqgijw_finanzas.compras
  WHERE id_UDN = ? AND id_CG = ?
  AND Fecha_Compras = ? and factura is not null";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 /*-----------------------------------*/
 /*	Gastos
 /*-----------------------------------*/

 function	detalles_gastos($array){
  $sql="
  SELECT
  insumos_clase.Name_IC,
  proveedor.Name_Proveedor,
  gastos.Name_Gastos,
  compras.Gasto
  FROM
  hgpqgijw_finanzas.compras
  INNER JOIN hgpqgijw_finanzas.insumos_udn ON compras.id_UI = insumos_udn.idUI
  INNER JOIN hgpqgijw_finanzas.insumos_clase ON insumos_udn.id_IC = insumos_clase.idIC
  INNER JOIN hgpqgijw_finanzas.proveedor_udn ON compras.id_UP = proveedor_udn.idUP
  INNER JOIN hgpqgijw_finanzas.proveedor ON proveedor_udn.id_Proveedor = proveedor.idProveedor
  INNER JOIN hgpqgijw_finanzas.gastos_udn ON compras.id_UG = gastos_udn.idUG
  INNER JOIN hgpqgijw_finanzas.gastos ON gastos_udn.id_Gastos = gastos.idGastos   WHERE
  compras.Fecha_Compras = ? AND
  id_CG = ? and
  compras.id_UDN = ? and factura is null;

  ";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function Select_TotalVenta($idE,$date){
  $array1 = array($idE,$date);
  $total = 0.000; $row1 = null;
  $query1 = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE id_UDN = ? AND Fecha = ?";
  $sql1 = $this->_Select($query1,$array1);
  foreach($sql1 as $row1);
  if ( !isset($row1[0]) || $row1[0] != null ) {
   $array = array($row1[0]);
   $query2 = "SELECT ROUND(SUM(Subtotal),2) FROM hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.folio WHERE id_Folio = idFolio AND id_Folio = ?";
   $sql2 = $this->_Select($query2,$array);
   foreach($sql2 as $row2);
   if ( !isset($row2[0]) ) { $row2[0] = 0; }

   $query3 = "SELECT ROUND(SUM(Monto),2) FROM hgpqgijw_finanzas.bitacora_impuesto,hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.folio WHERE id_Folio = idFolio AND id_Bitacora = idVentasBit AND id_Folio = ?";
   $sql3 = $this->_Select($query3,$array);
   foreach($sql3 as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }

   $total = $total + $row2[0] + $row3[0];
  }

  return $total;
 }

 function Select_GastoClase($opc){
  $query = "";
  if ( $opc == 1) {
   $query = "SELECT idCG,Name_CG
   FROM hgpqgijw_finanzas.gasto_clase";
  }
  else if ( $opc == 2) {
   $query = "SELECT idCG,Name_CG FROM
   hgpqgijw_finanzas.gasto_clase WHERE idCG != 2";
  }
  $sql = $this->_Select($query,null);
  return $sql;
 }

 function Select_GastoClase_Compras($idE,$idCG,$date){
  $array = array($idE,$idCG,$date); $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2)
  FROM hgpqgijw_finanzas.compras
  WHERE id_UDN = ? AND id_CG = ?
  AND Fecha_Compras = ? and factura is null";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }


 function Select_GastoClase_Pagos($idE,$idCG,$date) {
  $array = array($idE,$idCG,$date); $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UDN = ? AND id_CG = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Detalle_Gastos($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT idCompras,id_UG,id_UP,ROUND(Gasto,2) FROM hgpqgijw_finanzas.compras WHERE Gasto IS NOT NULL AND id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Name_Proveedor($id){
  $array = array($id);
  $query = "SELECT Name_Proveedor FROM hgpqgijw_finanzas.proveedor_udn,hgpqgijw_finanzas.proveedor WHERE id_Proveedor = idProveedor AND idUP = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = '-'; }
  return $row[0];
 }
 function Select_Name_Gasto($id){
  $array = array($id);
  $query = "SELECT Name_Gastos FROM hgpqgijw_finanzas.gastos_udn,hgpqgijw_finanzas.gastos WHERE idGastos = id_Gastos AND idUG = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = '-'; }
  return $row[0];
 }
 function Select_Detalle_Pagos($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT idCompras,id_UG,id_UP,ROUND(Pago,2) FROM hgpqgijw_finanzas.compras WHERE Pago IS NOT NULL AND id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Detalle_Proveedor($idE,$date){
  $array = array($idE);
  $id_array = array(); $dato = 0;
  $query = "SELECT id_UP FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UDN = ? GROUP BY id_UP";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $key => $row) {
   $id = $row[0];

   $array = array($id,$date);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UP = ? AND Fecha_Compras = ?";
   $sql_prov = $this->_Select($query,$array);
   foreach($sql_prov as $gasto_proveedor);
   if ( !isset($gasto_proveedor[0]) ) { $gasto_proveedor[0] = 0; }

   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras = ?";
   $sql_prov = $this->_Select($query,$array);
   foreach($sql_prov as $pago_proveedor);
   if ( !isset($pago_proveedor[0]) ) { $pago_proveedor[0] = 0; }

   $Total_Deuda_proveedor = $gasto_proveedor[0] - $pago_proveedor[0];

   if ($gasto_proveedor[0] != 0 || $pago_proveedor[0] != 0) {
    $id_array[$dato] = $id;
    $dato = $dato + 1;
   }
  }
  return $id_array;
 }
 function Sum_Pagos_Actual($id,$date){
  $array = array($id,$date);
  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Sum_Gastos_Actual($id,$date){
  $array = array($id,$date);
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UP = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Deuda_Total_Prov($id,$date) {
  $array = array($id,$date);
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 2 AND id_UP = ? AND Fecha_Compras <= ?";
  $sql_prov = $this->_Select($query,$array);
  foreach($sql_prov as $gasto_proveedor);
  if ( !isset($gasto_proveedor[0]) ) { $gasto_proveedor[0] = 0; }

  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras <= ?";
  $sql_prov = $this->_Select($query,$array);
  foreach($sql_prov as $pago_proveedor);
  if ( !isset($pago_proveedor[0]) ) { $pago_proveedor[0] = 0; }

  $Total_Deuda_proveedor = $gasto_proveedor[0] - $pago_proveedor[0];

  return $Total_Deuda_proveedor;
 }

}


?>
