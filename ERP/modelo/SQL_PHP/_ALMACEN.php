<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class ALMACEN extends CRUD{

 function	zona(){

  $query	="SELECT * FROM hgpqgijw_usuarios.zona ORDER BY zona ASC";

  $sql	=	$this->_Select($query,null,"1");

  return	$sql;
 }

 function	Familia(){

  $query	="SELECT * FROM hgpqgijw_operacion.mtto_familia ";

  $sql	=	$this->_Select($query,null,"1");

  return	$sql;
 }

 function	Marca(){

  $query	="SELECT * FROM hgpqgijw_operacion.mtto_marca";

  $sql	=	$this->_Select($query,null,"1");

  return	$sql;
 }

 function	verProductos($id){
  $bd	  ='';
  $OPC1='';
  $array	=	array($id);

  if ($id==0) {
   $array=array(null);
  }else {
   $OPC1 ='AND idAlmacen = ?';
  }

  $query	=
  "SELECT
  zona,
  CodigoEquipo,
  mtto_almacen_insumo.NombreInsumo,
  Costo,
  precioVenta,

  mtto_familia.nombreFamilia,
  mtto_almacen_insumo.min_stock,
  cantidad,
  date_format(TiempodeVida,'%d-%m-%Y'),
  idAlmacen,

  nombreClase,
  mtto_almacen_productos.id_clase,
  Marca_producto,
  Descripcion,
  nombreUnidad,

  Nombre_Area

  FROM
  hgpqgijw_operacion.mtto_almacen_productos,
  hgpqgijw_operacion.mtto_almacen_insumo,
  hgpqgijw_operacion.mtto_almacen_area,
  hgpqgijw_operacion.mtto_familia,
  hgpqgijw_operacion.mtto_clase,
  hgpqgijw_operacion.mtto_marca,
  hgpqgijw_operacion.mtto_claseFamilia,
  hgpqgijw_operacion.unidad,
  hgpqgijw_usuarios.zona

  WHERE
  idInsumo     = id_insumo and
  id_marca     = idmarca   and
  id_unidad    = idunidad   and
  Area         = idarea   and
  mtto_almacen_productos.id_clase = idclase AND
  idclase  = mtto_claseFamilia.id_clase and
  id_familia = idfamilia and
  id_zona    = idzona


  ".$OPC1." ORDER BY  idAlmacen DESC";

  $sql	=	$this->_Select($query,$array);

  return	$sql;
 }

 function	Unidad(){
  $sql="SELECT * FROM
  hgpqgijw_operacion.unidad";
  $ps	=	$this->_Select($sql,null);
  return	$ps;
 }

 /*===========================================
 *									Codigo Equipos
 =============================================*/

 function totalEquipos($array){
  $key =0;
  $sql="SELECT count(*)
  FROM hgpqgijw_operacion.mtto_almacen_productos,
  hgpqgijw_operacion.mtto_almacen_insumo
  Where  id_insumo =idInsumo and NombreInsumo=? and id_clase=?";
  $ps = $this->_Select($sql,$array);
  foreach ($ps as $key);

  return $key[0];
 }

 function	contarProductos($id){
  $contar = 0;
  $array	=	array($id);

  $query	=
  "SELECT
  count(*)
  FROM
  hgpqgijw_operacion.mtto_almacen_insumo
  ";

  $sql	=	$this->_Select($query,$array,"1");
  foreach ($sql as $key) {
   $contar = $key[0];
  }
  $res =$contar+1;
  return	$res;
 }

 /*===========================================
 *									.ADD ARTICULO
 =============================================*/

 function Select_idArea($array){
  $row = 0;
  $query = "SELECT idArea FROM hgpqgijw_operacion.mtto_almacen_area WHERE Nombre_Area = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  return $row[0];
 }

 function Select_idEquipo($array){
  $row = 0;
  $query =
  "SELECT idInsumo
  FROM hgpqgijw_operacion.mtto_almacen_insumo
  WHERE NombreInsumo = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  return $row[0];
 }

 function Insert_Equipo($array){
  $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_insumo (NombreInsumo,min_stock) VALUES (?,?)";
  $this->_DIU($query,$array);
 }

 function Select_idCodigo($array){
  $query =
  "SELECT
  mtto_almacen_productos.idAlmacen,
  mtto_almacen_insumo.NombreInsumo,
  mtto_almacen_productos.CodigoEquipo
  FROM
  hgpqgijw_operacion.mtto_almacen_productos
  INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo
  WHERE CodigoEquipo= ?
  ";
  $sql = $this->_Select($query,$array,"2");

  return $sql;

 }

 function Insert_Codigo($array){
  $query =
  "INSERT INTO
  hgpqgijw_operacion.mtto_almacen_productos
  (CodigoEquipo,
   id_insumo,
   id_zona,
   id_clase,
   id_marca,

   cantidad,
   costo,
   precioVenta,
   TiempodeVida,
   Descripcion,

   EstadoProducto,
   FechaIngreso,
   id_tipo,
   id_unidad,
   Area,
   udn_almacen) VALUES (?,?,?,?,?,
    ?,?,?,?,?,?,?,?,?,?,?)";
    $this->_DIU($query,$array);
   }

   function _CLASE($array){
    $key =0;
    $sql=
    "SELECT
    mtto_clase.idclase,
    mtto_clase.nombreClase,
    mtto_clasefamilia.id_familia
    FROM
    hgpqgijw_operacion.mtto_clasefamilia
    INNER JOIN hgpqgijw_operacion.mtto_clase ON mtto_clasefamilia.id_clase = mtto_clase.idclase
    WHERE
    id_familia = ?";
    $ps = $this->_Select($sql,$array);


    return $ps;
   }

   function Select_idMarca($array){
    $row = 0;
    $query =
    "SELECT idmarca
    FROM hgpqgijw_operacion.mtto_marca
    WHERE Marca_producto = ?";
    $sql = $this->_Select($query,$array);
    foreach ($sql as $row);
    return $row[0];
   }

   function Insert_Marca($array){
    $query = "INSERT INTO hgpqgijw_operacion.mtto_marca (Marca_producto) VALUES (?)";
    $this->_DIU($query,$array);
   }

   /*===========================================
   *									...
   =============================================*/
   function Update_Producto($array){
    $query =
    "UPDATE hgpqgijw_operacion.mtto_almacen_productos SET
    CodigoEquipo= ?,
    id_insumo=?,
    id_zona=?,
    id_clase=?,
    id_marca=?,
    costo=?,
    precioVenta=?,
    Descripcion =?,
    TiempodeVida=?,
    id_unidad =?,
    Area = ?
    WHERE idAlmacen=?

    ";
    $this->_DIU($query,$array,"2");
   }

   /*===========================================
   *									.QUITAR ARTICULO
   =============================================*/

   function	QuitarArticulo($array,$array2){
    //Eliminar Articulo...
    $query	=	"
    DELETE FROM
    hgpqgijw_operacion.mtto_almacen_productos
    WHERE idAlmacen=?  ";

    $this->_DIU($query,$array,"1");
    // Guardar registro

    $query =
    "INSERT INTO
    hgpqgijw_operacion.mtto_bajaProductos
    (NombreProducto,Motivo,fecha) VALUES (?,?,?)";
    $this->_DIU($query,$array2);


   }

   /*===========================================
   *								INVENTARIO
   =============================================*/

   function	ExisteEnLista($id){


    $array	=	array($id);

    $query	=
    "
    SELECT
    mtto_almacen_insumo.NombreInsumo,
    listaproductos.id_productos,
    mtto_almacen_productos.idAlmacen
    FROM
    hgpqgijw_operacion.mtto_almacen_productos
    INNER JOIN hgpqgijw_operacion.listaproductos ON listaproductos.id_productos = mtto_almacen_productos.idAlmacen
    INNER JOIN hgpqgijw_operacion.mtto_almacen_insumo ON mtto_almacen_productos.id_insumo = mtto_almacen_insumo.idInsumo

    WHERE id_productos = ?
    ";

    $sql	=	$this->_Select($query,$array,"1");

    return	$sql;
   }


   /*-----------------------------------*/
   /*		Inventario
   /*-----------------------------------*/
   function	cbInventario(){
    $array = null;

    $sql="SELECT
    id_zona,
    zona
    FROM
    hgpqgijw_operacion.mtto_almacen_productos
    INNER JOIN hgpqgijw_usuarios.zona ON idzona = id_zona
    GROUP BY id_zona";
    $ps	=	$this->_Select($sql,$array);
    return	$ps;
   }


   function	cbAreaInventario($array){
    $sql="SELECT
    DISTINCT mtto_almacen_productos.Area,
    mtto_almacen_area.Nombre_Area,
    id_zona
    FROM
    hgpqgijw_operacion.mtto_almacen_productos
    INNER JOIN hgpqgijw_operacion.mtto_almacen_area ON mtto_almacen_productos.Area = mtto_almacen_area.idArea
    WHERE id_zona = ?";
    $ps	=	$this->_Select($sql,$array);
    return	$ps;
   }

  }
