<?php
include_once ('_CRUD.php');

Class REPORTES extends CRUD{
  function Select_Categorias($idE,$date1,$date2){
    $array = array($idE,$date1,$date2);
    $query = "SELECT idCategoria,Categoria FROM hgpqgijw_finanzas.categoria,hgpqgijw_finanzas.folio,hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.subcategoria WHERE id_Folio = idFolio AND id_Categoria = idCategoria AND id_Subcategoria = idSubcategoria AND hgpqgijw_finanzas.folio.id_UDN = ? AND Fecha BETWEEN ? AND ? GROUP BY Categoria";
    $sql = $this->_Select($query,$array,"5");
    return $sql;
  }
  function Select_SubCategorias_x_Categoria($idCategoria,$date1,$date2){
    $array = array($idCategoria,$date1,$date2);
    $query = "SELECT idSubcategoria,Subcategoria FROM hgpqgijw_finanzas.folio,hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.subcategoria WHERE id_Folio = idFolio AND id_Subcategoria = idSubcategoria AND id_Categoria = ? AND Fecha BETWEEN ? AND ? GROUP BY Subcategoria";
    $sql = $this->_Select($query,$array,"5");
    return $sql;
  }
  function Select_bitacora_ventas($idSubcategoria,$date1,$date2){
    $array = array($idSubcategoria,$date1,$date2);
    $query = "SELECT ROUND(SUM(SubTotal),2),pax,Noche,ROUND(SUM(Tarifa),2) FROM hgpqgijw_finanzas.folio,hgpqgijw_finanzas.bitacora_ventas WHERE id_Folio = idFolio AND id_Subcategoria = ? AND Fecha BETWEEN ? AND ?";
    $sql = $this->_Select($query,$array,"5");
    return $sql;
  }
  function Select_bitacora_formaspago($idSubcategoria,$date1,$date2,$idFormasPago){
    $array = array($idSubcategoria,$date1,$date2,$idFormasPago); $row = null;
    $query = "SELECT ROUND(SUM(Monto),2) FROM hgpqgijw_finanzas.folio,hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.bitacora_formaspago WHERE id_Folio = idFolio AND id_Bitacora = idVentasBit AND id_Subcategoria = ? AND Fecha BETWEEN ? AND ? AND id_FormasPago = ?";
    $sql = $this->_Select($query,$array,"5");
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_bitacora_impuestos($idSubcategoria,$date1,$date2,$idImpuesto){
    $array = array($idSubcategoria,$date1,$date2,$idImpuesto); $row = null;
    $query = "SELECT ROUND(SUM(Monto),2) FROM hgpqgijw_finanzas.folio,hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.bitacora_impuesto WHERE id_Folio = idFolio AND id_Bitacora = idVentasBit AND id_Subcategoria = ? AND Fecha BETWEEN ? AND ? AND id_Impuesto = ?";
    $sql = $this->_Select($query,$array,"5");
    foreach ($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Select_Count_Subcategoría($idCategoria,$date1,$date2){
    $array = array($idCategoria,$date1,$date2);
    $query = "SELECT COUNT(*) FROM hgpqgijw_finanzas.folio,hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.subcategoria WHERE id_Folio = idFolio AND id_Subcategoria = idSubcategoria AND id_Categoria = ? AND Fecha BETWEEN ? AND ?";
    $sql = $this->_Select($query,$array,"5");
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
}
?>
