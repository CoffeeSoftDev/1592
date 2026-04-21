<?php
// ARCHIVOS EXTERNOS
include_once('_CRUD.php');


Class REV extends CRUD
{

//  function  BUSQUEDA($key,$array){
//   $rs = "";

//   $sql	=
//   "SELECT
//   zona,
//   CodigoEquipo,
//   mtto_almacen_insumo.NombreInsumo,

//   Costo,
//   precioVenta,
//   mtto_familia.nombreFamilia,

//   mtto_almacen_insumo.min_stock,
//   cantidad,
//   date_format(TiempodeVida,'%d-%m-%Y'),

//   idAlmacen,
//   nombreClase,
//   mtto_almacen_productos.id_clase,

//   Marca_producto,
//   Descripcion,
//   min_stock,
//   idAlmacen,
//   nombreUnidad

//   FROM
//   hgpqgijw_operacion.mtto_almacen_productos,
//   hgpqgijw_operacion.mtto_almacen_insumo,
//   hgpqgijw_operacion.mtto_familia,
//   hgpqgijw_operacion.mtto_clase,
//   hgpqgijw_operacion.mtto_marca,
//   hgpqgijw_operacion.unidad,
//   hgpqgijw_operacion.mtto_claseFamilia,
//   hgpqgijw_usuarios.zona
//   WHERE
//   idInsumo     = id_insumo and
//   id_marca     = idmarca   and
//   mtto_almacen_productos.id_clase = idclase AND
//   idclase  = mtto_claseFamilia.id_clase and
//   id_familia = idfamilia and
//   id_zona    = idzona and
//   id_unidad  = idunidad and
//   id_zona    = ? and
//   Area       = ? and
//   NombreInsumo like '%$key%'

//   ORDER BY  idAlmacen asc";




//   $ps = $this->_Select($sql,$array,"1");
//   foreach ($ps as $rs) ;
//   return $ps;

//  }

//  function	nuevoFolio($array){

//   $query	=	"
//   INSERT INTO
//   hgpqgijw_operacion.mtto_lista_productos
//   (id_Estado,folio,foliofecha,id_zona,id_area)
//   VALUES(?,?,?,?,?)
//   ";

//   $this->_DIU($query,$array,"1");
//  }

//  function	obtenerFolio(){
//   $folio  = 0;
//   $query	=
//   "
//   SELECT
//   count(*)
//   FROM
//   hgpqgijw_operacion.mtto_lista_productos
//   ";

//   $sql	=	$this->_Select($query,null,"1");

//   foreach ($sql as $key) {
//    $folio = $key[0];
//   }
//   return	$folio;
//  }

//  function	FOLIO_ACTIVO(){
//   $folio  = 0;

//   $query=
//   'SELECT
//   idLista,
//   id_Estado,
//   folio,
//   zona,
//   Nombre_Area,
//   id_zona,
//   id_area
//   FROM
//   hgpqgijw_operacion.mtto_lista_productos
//   INNER JOIN hgpqgijw_usuarios.zona ON id_zona = idzona
//   INNER JOIN hgpqgijw_operacion.mtto_almacen_area ON id_area = idArea
//   order by idLista DESC limit 1
//   ';

//   $sql	=	$this->_Select($query,null,"1");


//   return	$sql;
//  }

//  function  LISTA_INVENTARIO($key){
//   $rs = "";
//   $array = array(null);

//   $sql	=
//   "
//   SELECT
//   mtto_almacen_insumo.NombreInsumo,
//   listaproductos.cantidadAnterior,
//   listaproductos.cantidadActual,
//   listaproductos.idListaProductos,
//   listaproductos.id_productos,
//   unidad.nombreUnidad
//   FROM
//   hgpqgijw_operacion.listaproductos
//   INNER JOIN hgpqgijw_operacion.mtto_lista_productos ON listaproductos.id_lista = mtto_lista_productos.idLista
//   INNER JOIN hgpqgijw_operacion.mtto_almacen_productos ON listaproductos.id_productos = mtto_almacen_productos.idAlmacen
//   INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
//   INNER JOIN hgpqgijw_operacion.unidad ON mtto_almacen_productos.id_unidad = unidad.idunidad
//   WHERE
//   mtto_lista_productos.id_Estado = 1;
//   ";




//   $ps = $this->_Select($sql,$array,"1");
//   foreach ($ps as $rs) ;
//   return $ps;

//  }


//  /*===========================================
//  *									MOVIMIENTOS - LISTA
//  ============================================*/

//  function	addMoviento($array){

//   $query	=	"
//   INSERT INTO
//   hgpqgijw_operacion.listaproductos
//   (id_lista,id_productos,cantidadAnterior,cantidadActual)
//   VALUES(?,?,?,?)
//   ";

//   $this->_DIU($query,$array,"1");
//  }

//  function	nuevoStock($array){

//   $query	=	"
//   UPDATE
//   hgpqgijw_operacion.mtto_almacen_productos
//   SET
//   cantidad = ?
//   WHERE
//   idAlmacen = ?
//   ";

//   $this->_DIU($query,$array,"1");
//  }

//  function	DeleteMovimiento($array){
//   $query	=	"
//   DELETE FROM
//   hgpqgijw_operacion.listaproductos
//   WHERE idListaProductos = ?
//   ";

//   $this->_DIU($query,$array,"1");
//  }

//  function	existeEnLista($producto,$id){

//   $array	=	array($producto,$id);

//   $query	=
//   "
//   SELECT
//   id_lista
//   FROM
//   hgpqgijw_operacion.listaproductos
//   WHERE
//   id_productos = ? and id_lista=?
//   ";

//   $sql	=	$this->_Select($query,$array,"1");

//   return	$sql;
//  }

//  function	DeleteFolio($array){
//   $query	=	"
//   UPDATE
//   hgpqgijw_operacion.mtto_lista_productos

//   SET motivo   = ? ,
//   id_Estado   = ?
//   WHERE idLista = ?
//   ";

//   $this->_DIU($query,$array,"1");
//  }

//  function	GuardarFolio($array){
//   $query	=	"
//   UPDATE
//   hgpqgijw_operacion.mtto_lista_productos

//   SET nota   = ? ,
//   autorizo   = ? ,
//   id_Estado  = ?
//   WHERE idLista = ?
//   ";

//   $this->_DIU($query,$array,"1");
//  }

//  /*===========================================
//  *									PDF - REPORT
//  =============================================*/

//  function  LISTA_PDF($key){
//   $rs = "";
//   $array = array($key);

//   $sql	=
//   "
//   SELECT

//   hgpqgijw_operacion.mtto_almacen_insumo.NombreInsumo,
//   hgpqgijw_operacion.listaproductos.cantidadAnterior,
//   hgpqgijw_operacion.listaproductos.cantidadActual,

//   hgpqgijw_operacion.listaproductos.idListaProductos,
//   listaproductos.id_productos,
//   hgpqgijw_usuarios.zona.zona,
//   id_lista

//   FROM
//   hgpqgijw_operacion.mtto_almacen_productos
//   INNER JOIN hgpqgijw_usuarios.zona ON hgpqgijw_usuarios.zona.idzona = hgpqgijw_operacion.mtto_almacen_productos.id_zona
//   INNER JOIN hgpqgijw_operacion.listaproductos ON hgpqgijw_operacion.listaproductos.id_productos = hgpqgijw_operacion.mtto_almacen_productos.idAlmacen
//   INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON hgpqgijw_operacion.mtto_almacen_productos.id_insumo = hgpqgijw_operacion.mtto_almacen_insumo.idInsumo
//   WHERE id_lista = ?
//   ";




//   $ps = $this->_Select($sql,$array,"1");

//   return $ps;

//  }

//  function	verFolio($id){

//   $key= null;
//   $array	=	array($id);

//   $query	=
//   "
//   SELECT
//   *
//   FROM
//   hgpqgijw_operacion.mtto_lista_productos
//   Where idLista = ?
//   ";

//   $sql	=	$this->_Select($query,$array,"1");

//   foreach ($sql as $key );
//   return	$key;
//  }

//  function RestaurarValor($array){

//   $query	=	"
//   UPDATE
//   hgpqgijw_operacion.mtto_almacen_productos
//   SET cantidad = ?
//   WHERE idAlmacen = ? ";

//   $this->_DIU($query,$array);
//  }

//  /*===========================================
//  *									[dataLista.php]
//  =============================================*/

//  function	verListaProductos($tipo,$mes,$anio){


//   $array = array($tipo);

//   $OPC1  = 'AND MONTH(foliofecha) = ?';
//   $OPC2  = 'AND YEAR (foliofecha) = ?';


//   if ($mes=='' || $mes== '0') {

//    $OPC1  = '';
//    /*   --  */

//    if ($anio=='0' || $anio=='') {
//     $OPC2   ='';
//    }else {
//     $array = array($tipo,$anio);
//    }
//    // ---

//   }else {

//    if ($anio=='0' || $anio=='') {

//     $array = array($tipo,$mes);
//     $OPC2   ='';
//    }else {

//     $array = array($tipo,$mes,$anio);
//    }

//   }


//   $query	=
//   "SELECT
//   mtto_lista_productos.idLista,
//   mtto_lista_productos.folio,
//   date_format(foliofecha, '%Y-%m-%d') AS fecha,
//   date_format(foliofecha, '%H:%i:%s') AS hora,
//   mtto_lista_productos.autorizo,
//   count(listaproductos.id_productos)

//   FROM
//   hgpqgijw_operacion.mtto_lista_productos
//   INNER JOIN hgpqgijw_operacion.listaproductos ON listaproductos.id_lista = mtto_lista_productos.idLista
//   WHERE
//   id_Estado = ?
//   ".$OPC1."
//   ".$OPC2."

//   GROUP BY idLista
//   ";

//   $sql	=	$this->_Select($query,$array,"1");

//   return	$sql;
//  }

//  function	cb_year(){

//   $query	=
//   "SELECT
//   YEAR (foliofecha) AS YEAR
//   FROM
//   hgpqgijw_operacion.mtto_lista_productos
//   GROUP BY
//   YEAR (foliofecha)";

//   $sql	=	$this->_Select($query,null,"1");

//   return	$sql;
//  }


//  /*-----------------------------------*/
//  /* ListaInventario
//  /*-----------------------------------*/

//  function	ProductoList($array){
//   $keyX = null;

//   $sql  = "
//   SELECT
//   cantidadAnterior,
//   cantidadActual,
//   id_lista,
//   id_productos
//   FROM
//   hgpqgijw_operacion.listaproductos
//   WHERE
//   id_lista = ?
//   AND id_productos = ?
//   ";
//   $ps	=	$this->_Select($sql,$array);


//   return	$ps;
//  }


//  function	NombreProducto($array){
//   $sql="
//   SELECT DISTINCT
//   NombreInsumo,
//   min_stock,
//   precioVenta,
//   id_productos,
//   cantidad
//   FROM
//   hgpqgijw_operacion.listaproductos
//   INNER JOIN hgpqgijw_operacion.mtto_lista_productos ON id_lista = idLista
//   INNER JOIN hgpqgijw_operacion.mtto_almacen_productos ON id_productos = idAlmacen
//   INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
//   INNER JOIN hgpqgijw_operacion.unidad ON mtto_almacen_productos.id_unidad = unidad.idunidad
//   WHERE
//   mtto_lista_productos.id_Estado = 2
//   AND mtto_lista_productos.id_zona = ? and
//   id_area = ?";
//   $ps	=	$this->_Select($sql,$array);
//   return	$ps;
//  }

//  function	FechaActiva($array,$fi,$ff){
//   $sql="
//   SELECT
//   DATE_FORMAT(foliofecha,'%Y-%m-%d'),
//   mtto_lista_productos.folio,
//   idLista
//   FROM
//   hgpqgijw_operacion.mtto_lista_productos
//   WHERE foliofecha
//   BETWEEN  '".$fi." 00:00:00' AND  '".$ff." 23:59:59'
//   and mtto_lista_productos.id_Estado = 2  and
//   id_zona = ? and
//   id_area = ?

//   ";
//   $ps	=	$this->_Select($sql,$array);
//   return	$ps;
//  }

}
?>
