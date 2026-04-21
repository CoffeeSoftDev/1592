<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');


Class ALMACEN_ADMIN extends CRUD
{
 /*===========================================
 *									PRODUCTOS EN ALMACEN
 =============================================*/
 function  VER_TOTAL_PRODUCTOS($array){
  $rs = "";
  $contarProductos=0;
  $sql = "
  SELECT
  count(*),
  SUM(costo*cantidad) as Total
  FROM
  hgpqgijw_operacion.mtto_almacen_productos
  INNER JOIN hgpqgijw_operacion. mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
  INNER JOIN hgpqgijw_operacion.mtto_clase ON mtto_almacen_productos.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_clasefamilia ON mtto_clasefamilia.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_familia ON mtto_clasefamilia.id_familia = mtto_familia.idfamilia
  WHERE cantidad > 0
  ";

  $ps = $this->_Select($sql,$array,"1");

  return $ps;

 }

 function  VER_TOTAL_STOCK_BAJO($array){
  $rs = "";
  $contarProductos=0;
  $sql = "
  SELECT
  count(*)
  FROM
  hgpqgijw_operacion.mtto_almacen_productos
  INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
  WHERE cantidad < min_stock
  ";

  $ps = $this->_Select($sql,$array,"1");
  foreach ($ps as $rs) {
   $contarProductos=$rs[0];
  }
  return $contarProductos;

 }

 function  VER_TOTAL_HOY($array){
  $rs = "";
  $contarProductos=0;
  $sql = "
  SELECT
  count(*)
  FROM
  hgpqgijw_operacion.mtto_almacen_productos
  INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
  WHERE DATE_FORMAT(FechaIngreso, '%m/%d/%y') = DATE_FORMAT(NOW(),'%m/%d/%y')
  ";

  $ps = $this->_Select($sql,$array,"1");
  foreach ($ps as $rs) {
   $contarProductos=$rs[0];
  }
  return $contarProductos;

 }

 function VER_TOTAL_GRUPOS($idTipo){
  $array = array($idTipo);
  $rs = "";
  $contarProductos=0;
  $sql = "
  SELECT
  zona,
  count(CodigoEquipo) as Productos,
  SUM(cantidad * costo) AS totalGral,
  mtto_familia.idTipo,
  SUM(cantidad * costo)
  FROM
  hgpqgijw_operacion.mtto_almacen_productos,
  hgpqgijw_operacion.mtto_clase,
  hgpqgijw_operacion.mtto_clasefamilia,
  hgpqgijw_operacion.mtto_familia,
  hgpqgijw_usuarios.zona
  WHERE
  idzona = id_zona
  AND mtto_almacen_productos.id_clase = mtto_clase.idclase
  AND mtto_clasefamilia.id_clase = mtto_clase.idclase
  AND mtto_clasefamilia.id_familia = mtto_familia.idfamilia
  AND cantidad > 0
  AND idTipo = 1
  GROUP BY
  id_zona
  ";

  $ps = $this->_Select($sql,$array,"1");

  return $ps;

 }


 // ----

 function  VER_TOTAL_COSTOS($array){
  $rs = "";
  $contarProductos=0;
  $sql = "
  SELECT
  zona,
  mtto_almacen_productos.CodigoEquipo,
  mtto_almacen_insumo.NombreInsumo,
  mtto_clase.nombreClase,
  mtto_familia.nombreFamilia,
  mtto_almacen_productos.cantidad,
  mtto_almacen_productos.costo,
  ROUND(costo*cantidad,2) AS Total,
  mtto_almacen_productos.precioVenta,
  mtto_almacen_productos.Descripcion

  FROM
  hgpqgijw_operacion.mtto_almacen_productos
  INNER JOIN hgpqgijw_usuarios.zona ON mtto_almacen_productos.id_zona = zona.idzona
  INNER JOIN hgpqgijw_operacion. mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
  INNER JOIN hgpqgijw_operacion.mtto_clase ON mtto_almacen_productos.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_clasefamilia ON mtto_clasefamilia.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_familia ON mtto_clasefamilia.id_familia = mtto_familia.idfamilia
  WHERE
  cantidad > 0";

  $ps = $this->_Select($sql,$array,"1");

  return $ps;

 }

 function  VER_STOCKBAJOS($array){
  $rs = "";
  $contarProductos=0;
  $sql = "
  SELECT
  zona,
  mtto_almacen_productos.CodigoEquipo,
  mtto_almacen_insumo.NombreInsumo,
  mtto_clase.nombreClase,
  mtto_familia.nombreFamilia,
  mtto_almacen_productos.cantidad,
  mtto_almacen_productos.costo,
  mtto_almacen_productos.precioVenta,
  ROUND(costo*cantidad,2) AS Total,
  mtto_almacen_productos.Descripcion,
  mtto_familia.idTipo,
  mtto_almacen_productos.udn_almacen
  FROM
  hgpqgijw_operacion.mtto_almacen_productos
  INNER JOIN hgpqgijw_usuarios.zona ON mtto_almacen_productos.id_zona = zona.idzona
  INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
  INNER JOIN hgpqgijw_operacion.mtto_clase ON mtto_almacen_productos.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_clasefamilia ON mtto_clasefamilia.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_familia ON mtto_clasefamilia.id_familia = mtto_familia.idfamilia
  WHERE cantidad < min_stock

  ";

  $ps = $this->_Select($sql,$array,"1");

  return $ps;

 }

 function  VER_HOY($array){
  $rs = "";
  $contarProductos=0;
  $sql = "
  SELECT
  zona,
  mtto_almacen_productos.CodigoEquipo,
  mtto_almacen_insumo.NombreInsumo,
  mtto_clase.nombreClase,
  mtto_familia.nombreFamilia,
  mtto_almacen_productos.cantidad,
  mtto_almacen_productos.costo,
  mtto_almacen_productos.precioVenta,
  ROUND(costo*cantidad,2) AS Total,
  mtto_almacen_productos.Descripcion,
  mtto_familia.idTipo,
  mtto_almacen_productos.udn_almacen
  FROM
  hgpqgijw_operacion.mtto_almacen_productos
  INNER JOIN hgpqgijw_usuarios.zona ON mtto_almacen_productos.id_zona = zona.idzona
  INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
  INNER JOIN hgpqgijw_operacion.mtto_clase ON mtto_almacen_productos.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_clasefamilia ON mtto_clasefamilia.id_clase = mtto_clase.idclase
  INNER JOIN hgpqgijw_operacion.mtto_familia ON mtto_clasefamilia.id_familia = mtto_familia.idfamilia
  WHERE DATE_FORMAT(FechaIngreso, '%m/%d/%y') = DATE_FORMAT(NOW(),'%m/%d/%y')

  ";

  $ps = $this->_Select($sql,$array,"1");

  return $ps;

 }

 /*===========================================
 *									MATERIALES EN ALMACEN
 =============================================*/
 function  VER_TOTAL_MATERIALES($array){
  $sql = "
  SELECT
  mtto_almacen.CodigoEquipo,
  mtto_almacen_equipos.Nombre_Equipo,
  mtto_almacen_area.Nombre_Area,

  mtto_almacen.Estado,
  mtto_almacen.cantidad,
  mtto_almacen.Costo,
  (Costo * cantidad),

  timestampdiff(month,curdate(),TiempodeVida) ,
  DATE_FORMAT(mtto_almacen.FechaIngreso,'%Y-%m-%d' ),
  mtto_almacen.Observaciones
  FROM
  hgpqgijw_operacion.mtto_almacen
  INNER JOIN hgpqgijw_operacion.mtto_almacen_equipos ON mtto_almacen.Equipo = mtto_almacen_equipos.idEquipo
  INNER JOIN hgpqgijw_operacion.mtto_almacen_area ON mtto_almacen.Area = mtto_almacen_area.idArea

  ";

  $ps = $this->_Select($sql,$array,"1");

  return $ps;

 }


 function  VER_COSTO_MATERIALES($array){
  $total  = 0;

  $sql = "
  SELECT

  SUM((Costo * cantidad))

  FROM
  hgpqgijw_operacion.mtto_almacen
  INNER JOIN hgpqgijw_operacion.mtto_almacen_equipos ON mtto_almacen.Equipo = mtto_almacen_equipos.idEquipo
  INNER JOIN hgpqgijw_operacion.mtto_almacen_area ON mtto_almacen.Area = mtto_almacen_area.idArea

  ";

  $ps = $this->_Select($sql,$array,"1");
  foreach ($ps as $key ) {
   $total  = $key[0];
  }

  return $total;
 }

 function  VER_BAJA_MATERIALES($array){
  $total  = 0;

  $sql = "
  SELECT
  CodigoEquipo,
  Nombre_Equipo,
  Nombre_Area,

  hgpqgijw_usuarios.udn.UDN,
  idAlmacen,
  cantidad,

  Abreviatura,
  nombrecategoria,
  ruta,

  id_categoria,
  Costo,
  (cantidad * costo) as total,

  date_format(FechaIngreso,'%Y-%m-%d'),
  TiempodeVida,
  EstadoProducto,

  mtto_almacen_bitacora.Fecha,
  mtto_almacen_bitacora.Observacion,
  StockAnterior,

  ProductosBaja,
    Observaciones

  FROM
  hgpqgijw_operacion.mtto_almacen,
  hgpqgijw_operacion.mtto_almacen_area,
  hgpqgijw_operacion.mtto_almacen_equipos,
  hgpqgijw_usuarios.udn,
  hgpqgijw_operacion.mtto_categoria,
  hgpqgijw_operacion.mtto_almacen_bitacora

  WHERE idUDN = UDN_Almacen and
  idArea = Area and
  idEquipo = Equipo and
  mtto_almacen_bitacora.Almacen = mtto_almacen.idAlmacen and
  id_categoria=idcategoria

  ";

  $ps = $this->_Select($sql,$array,"1");

  return $ps;
 }

}
?>
