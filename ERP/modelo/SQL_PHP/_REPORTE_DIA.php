<?php 
class REPORTE_VENTAS extends CRUD {
  
  function Select_tabla_productos(){
    $query = "SELECT idProducto,NombreProducto,presentacion,precio,min_inventario,precio_mayoreo,id_subcategoria 
    FROM hgpqgijw_dia.venta_productos WHERE id_tipo = 1 AND Status = 1 ORDER BY NombreProducto,Status ASC";
    $sql = $this->_Select($query,null);
    return $sql;
  }

  function SUMA_FECHA($array){
    $total = 0;
    $sql  = "
    SELECT
    lista_folio.fecha,
    lista_productos.cantidad,
    
    lista_productos.costo,
    ROUND(costo * cantidad),
    
    lista_productos.id_lista,
    venta_productos.NombreProducto,
    
    venta_productos.id_subcategoria,
    presentacion,
    folio
    FROM
    hgpqgijw_dia.lista_folio
    INNER JOIN hgpqgijw_dia.lista_productos ON lista_productos.id_lista = lista_folio.idLista
    INNER JOIN hgpqgijw_dia.venta_productos ON lista_productos.id_productos = venta_productos.idProducto
    WHERE fecha = ? and idProducto = ?";

    $ps   =   $this->_Select($sql,$array, '1');
  
    return  $ps;
  }

  function  subcategoria(){
  $sql  = "SELECT * FROM hgpqgijw_dia.venta_subcategoria";
  $ps   =   $this->_Select($sql,null, '1');
  return  $ps;
 }

 function detalle_por_subcategoria($array){
  $sql  = "
	SELECT
	lista_folio.fecha,
  lista_productos.cantidad,
  
	lista_productos.costo,
	ROUND(costo * cantidad),
  
  lista_productos.id_lista,
	venta_productos.NombreProducto,
  
  venta_productos.id_subcategoria,
  presentacion,
  folio
  FROM
	hgpqgijw_dia.lista_folio
	INNER JOIN hgpqgijw_dia.lista_productos ON lista_productos.id_lista = lista_folio.idLista
	INNER JOIN hgpqgijw_dia.venta_productos ON lista_productos.id_productos = venta_productos.idProducto
  WHERE id_subcategoria = ? and DATE_FORMAT(fecha,'%Y-%m-%d') Between  ? and ? 
  and idProducto = ?";

  $ps   =   $this->_Select($sql,$array, '1');
  return  $ps;
 }


  function subcategoria_productos($array){
    $sql  = "
      SELECT
      idProducto,NombreProducto,presentacion
      FROM
      hgpqgijw_dia.venta_productos  
      WHERE id_subcategoria = ? ";
  $ps   =   $this->_Select($sql,$array, '1');
  return  $ps;
  }


 function obtener_folios($array){
    $sql  = "
    SELECT
      lista_folio.idLista,
      lista_folio.folio,

      lista_folio.fecha,
      lista_productos.id_productos,
      
      lista_productos.idListaProductos,
      lista_productos.costo,
      
      lista_productos.cantidad

      
    FROM
      hgpqgijw_dia.lista_folio
      INNER JOIN hgpqgijw_dia.lista_productos ON lista_productos.id_lista = lista_folio.idLista
    WHERE
      MONTH(lista_folio.Fecha) = ?
      AND YEAR(lista_folio.Fecha) = ?
      AND id_productos = ? ";

  $ps   =   $this->_Select($sql,$array, '1');
  return  $ps;
  }



}
?>