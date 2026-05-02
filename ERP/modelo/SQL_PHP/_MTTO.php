<?php
include_once("../_CRUD.php");

Class MTTO extends CRUD{

  function	ClaveProducto($array){
   $t=0;
   $sql=
   "
   SELECT
   count(*)
   FROM
   hgpqgijw_operacion.mtto_almacen_equipos
   INNER JOIN hgpqgijw_operacion.mtto_almacen ON mtto_almacen.Equipo = mtto_almacen_equipos.idEquipo
   WHERE area = ? and id_zona =?
   ";

   $ps	=	$this->_Select($sql,$array);
   foreach ($ps as $key) {
    $t = $key[0];
   }
   return	$t;
  }



 function	ContarEquipos(){
  $t=0;
  $sql="SELECT count(*) FROM hgpqgijw_operacion.mtto_almacen_equipos";
  $ps	=	$this->_Select($sql,null);
  foreach ($ps as $key) {
   $t = $key[0];
  }
  return	$t;
 }

 function NOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function HoursNOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%H:%i:%s')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function Select_Archivos($array) {
  $row = null;
  $query = "SELECT Archivo FROM hgpqgijw_operacion.mtto_almacen WHERE Relacion = ? AND Ruta = ? AND Archivo = ? ";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }

 function Insert_Sobres($array) {
  $query = "UPDATE  hgpqgijw_operacion.mtto_almacen
  SET Relacion = ?,
  Ruta  = ?,
  Fecha = ?,
  Hora  = ?,
  Archivo = ?,
  Size    = ?,
  Type_File = ?,
  Descripcion = ?
  WHERE idAlmacen = ?";

  $this->_DIU($query,$array);
 }

 function Select_Cont_Sobres($date,$idE){
  $array = array($date,$idE);
  $query = "SELECT COUNT(*) FROM hgpqgijw_operacion.mtto_almacen WHERE Fecha = ? AND Relacion = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function cbZona(){
  $sql="SELECT *  FROM hgpqgijw_usuarios.zona";
  $ps	=	$this->_Select($sql,null);
  return	$ps;
 }


 /*===========================================
 *									Codigo Equipos
 =============================================*/
 function	NoProducto($array){
  $NoProductos =  0;
  $query	=
  "SELECT
  Count(*)
  FROM
  hgpqgijw_operacion.mtto_almacen WHERE Equipo = ? and area=?";

  $sql	=	$this->_Select($query,$array,"1");

  foreach ($sql as $key ) {
   $NoProductos =  $key[0];
  }


  return	$NoProductos;
 }

 function _area($array){
  $key =0;
  $sql="SELECT * FROM hgpqgijw_operacion.mtto_almacen_area Where Nombre_area=?";
  $ps = $this->_Select($sql,$array);
  foreach ($ps as $key);

  return $key[0];
 }

 function totalEquipos($array){
  $key =0;
  $sql="SELECT count(*)
  FROM hgpqgijw_operacion.mtto_almacen,hgpqgijw_operacion.mtto_almacen_equipos
  Where  idEquipo =Equipo and Nombre_Equipo=? ";
  $ps = $this->_Select($sql,$array);
  foreach ($ps as $key);

  return $key[0];
 }
/*-----------------------------------*/
/*	Modal Formulario
/*-----------------------------------*/
function	Select_idProveedor($array){
 $row = 0;
 $query = "SELECT idProveedor FROM hgpqgijw_operacion.mtto_proveedores WHERE nombreProveedor = ?";
 $sql = $this->_Select($query,$array);
 foreach ($sql as $row);
 return $row[0];
	}


 /*  */

 function dataCat(){

  $sql="SELECT * FROM hgpqgijw_operacion.mtto_categoria ";

  $ps = $this->_Select($sql,null);


  return $ps;
 }

 function dataUDN(){

  $sql="SELECT * FROM hgpqgijw_usuarios.udn ";
  $ps = $this->_Select($sql,null);
  return $ps;
 }

 function data_AREA($array){
  $sql="SELECT * FROM hgpqgijw_operacion.mtto_almacen_area ";
  $ps = $this->_Select($sql,$array);
  return $ps;
 }

 function Insert_Almacen_Bitacora($array){
  $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_bitacora
  (Fecha,Observacion,Almacen,StockAnterior,ProductosBaja,Movimiento)
  VALUES (?,?,?,?,?,?)";
  $this->_DIU($query,$array,"2");
 }
 // cb
 function SELECT_area($array){
  $sql="SELECT Nombre_Area FROM hgpqgijw_operacion.mtto_almacen_area ";

  $ps = $this->_Select($sql,$array);
  return $ps;
 }

 function SELECT_NOMBRE($array){
  $sql="SELECT Nombre_Equipo FROM hgpqgijw_operacion.mtto_almacen_equipos ";

  $ps = $this->_Select($sql,$array);
  return $ps;
 }

 function SELECT_CODIGO($array){
  $sql="SELECT CodigoEquipo FROM hgpqgijw_operacion.mtto_almacen ";

  $ps = $this->_Select($sql,$array);
  return $ps;
 }

 function SELECT_MARCA($array){
  $sql="SELECT Marca_producto FROM hgpqgijw_operacion.mtto_marca ";

  $ps = $this->_Select($sql,$array);
  return $ps;
 }

 function SELECT_PROVEEDOR($array){
  $sql="SELECT nombreProveedor FROM hgpqgijw_operacion.mtto_proveedores ";

  $ps = $this->_Select($sql,$array);
  return $ps;
 }

/*-----------------------------------*/
/*	 GUARDAR REGISTRO - PRODUCTOS
/*-----------------------------------*/
function Insert_Proveedor($array){
 $query = "INSERT INTO hgpqgijw_operacion.mtto_proveedores (nombreProveedor) VALUES (?)";
 $this->_DIU($query,$array,"2");
}

function Select_idEquipo($array){
 $row = 0;
 $query = "SELECT idEquipo FROM hgpqgijw_operacion.mtto_almacen_equipos WHERE  Nombre_Equipo= ?";
 $sql = $this->_Select($query,$array);
 foreach ($sql as $row);
 return $row[0];
}

function Select_idArea($array){
 $row = 0;
 $query = "SELECT idArea FROM hgpqgijw_operacion.mtto_almacen_area WHERE Nombre_Area = ?";
 $sql = $this->_Select($query,$array);
 foreach ($sql as $row);
 return $row[0];
}

function Select_idMarca($array){
 $row = 0;
 $query = "SELECT idmarca
 FROM hgpqgijw_operacion.mtto_marca
 WHERE Marca_producto = ?";
 $sql = $this->_Select($query,$array);
 foreach ($sql as $row);
 return $row[0];
}

function Insert_Marca($array){
 $query = "INSERT INTO
 hgpqgijw_operacion.mtto_marca (Marca_producto) VALUES (?)";
 $this->_DIU($query,$array,"2");
}


 // ADD recientemente...



 function Insert_Equipo($array){
  $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_equipos (Nombre_Equipo) VALUES (?)";
  $this->_DIU($query,$array);
 }



 function Select_idCodigo($array){
  $row = 0;
  $query = "SELECT idAlmacen FROM hgpqgijw_operacion.mtto_almacen WHERE CodigoEquipo = ?";
  $sql = $this->_Select($query,$array,"2");
  foreach ($sql as $row);
  return $row[0];
 }

 function Insert_Codigo($array){
  $query = "
  INSERT INTO
  hgpqgijw_operacion.mtto_almacen (CodigoEquipo,Equipo,Area,UDN_Almacen,Estado,id_categoria,cantidad,costo,TiempodeVida,Observaciones,EstadoProducto,FechaIngreso,id_zona,rutaImagen,id_proveedor,id_marca) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }

 function Insert_Area($array){
  $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_area (Nombre_Area) VALUES (?)";
  $this->_DIU($query,$array,"2");
 }


 // Ver Tabla ---
 function Show_DATA($categoria,$area,$opc){

  $COND2 ='Area      =?    and ';
  $array =null;
  $COND1 =' id_categoria =?    and ';


  if ($categoria==0 ||$categoria=="0") {  // cbCat esta en ...
   $COND1 ='';

   if($area==0){  // cbArea ...
    $COND2  = '';
    $array  = array($opc);
   }else {     //   Activo ....
    $array = array($area,$opc);
   }

  }else {  // cbCat tiene un registro

   if($area==0){  // cbArea ...
    $COND2  = '';
    $array  = array($categoria,$opc);
   }else {     //   Activo ....
    $array = array($categoria,$area,$opc);
   }




  }

  // ------
  $row = 0;
  $query = "
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

  Observaciones,
  zona,
  rutaImagen,
  nombreProveedor
  FROM
  hgpqgijw_operacion.mtto_almacen,
  hgpqgijw_operacion.mtto_almacen_area,
  hgpqgijw_operacion.mtto_almacen_equipos,
  hgpqgijw_usuarios.udn,
  hgpqgijw_usuarios.zona,
  hgpqgijw_operacion.mtto_categoria,
  hgpqgijw_operacion.mtto_proveedores
  WHERE idUDN = UDN_Almacen and
  idArea = Area and
  idEquipo = Equipo and
  id_categoria=idcategoria and
  id_zona       = idzona  AND
  id_proveedor  =idProveedor AND
  ".$COND1."
  ".$COND2."
  Estado= ? ORDER BY idAlmacen desc
  ";

  $sql = $this->_Select($query,$array);

  return $sql;
 }

 function Show_DATA_BAJA($categoria,$area,$opc){
  $row = 0;
  $COND2 =' Area      =?    and ';
  $array =null;
  $COND1 =' id_categoria =?  and';


  if ($categoria==0 ||$categoria=="0") {  // cbCat esta en ...
   $COND1 ='';

   if($area==0){  // cbArea ...
    $COND2  = '';
    $array  = array($opc);
   }else {     //   Activo ....
    $array = array($area,$opc);
   }

  }else {  // cbCat tiene un registro

   if($area==0){  // cbArea ...
    $COND2  = '';
    $array  = array($categoria,$opc);
   }else {     //   Activo ....
    $array = array($categoria,$area,$opc);
   }
  }

  $query = "
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
  ProductosBaja

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
  id_categoria=idcategoria AND
  ".$COND1."
  ".$COND2."
  Estado = ? or Estado = 3

  ";

  $sql = $this->_Select($query,$array);

  return $sql;
 }

 //[Detalles de Poliza] -------------------------

 function SELECT_POLIZA($key){
  $array = array($key);

  $sql ="
  SELECT
  Archivo,
  Descripcion,
  Size,
  Fecha,
  Type_file,
  Ruta,
  Hora,
  idAlmacen
  FROM
  hgpqgijw_operacion.mtto_almacen,
  hgpqgijw_operacion.mtto_almacen_equipos,
  hgpqgijw_operacion.mtto_categoria
  WHERE
  idEquipo=Equipo and
  idcategoria=id_categoria and
  idAlmacen=?;
  ";

  $query = $this->_Select($sql,$array,"3");
  return $query;
 }

 function INSERT_IMG($array){
  $query = "
  UPDATE hgpqgijw_operacion.mtto_almacen
  SET ruta_poliza = ?
  WHERE idAlmacen = ?
  ";

  $this->_DIU($query,$array,"2");
 }

 function RemoverPoliza($id){

  $arreglo = array(null,null,null,null,null,null,null,$id);

  $query = "UPDATE  hgpqgijw_operacion.mtto_almacen
  SET
  Ruta  = ?,
  Fecha = ?,
  Hora  = ?,
  Archivo = ?,
  Size    = ?,
  Type_File = ?,
  Descripcion = ?
  WHERE idAlmacen = ?";

  $this->_DIU($query,$arreglo);

 }

 // [BAJA INSUMO] ----------------------------

 function Update_Almacen_Estado($array){
  $query = "UPDATE hgpqgijw_operacion.mtto_almacen
  SET Estado = ? WHERE idAlmacen = ?";
  $this->_DIU($query,$array,"2");
 }

 /*===========================================
 *
 =============================================*/
 function Show_DATA_SINGLE($array){
  $row = 0;

  $query = "
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
  date_format(TiempodeVida,'%Y-%m-%d'),

  Observaciones,
  EstadoProducto,
  nombreProveedor,

  rutaImagen,
  Marca_producto


  FROM
  hgpqgijw_operacion.mtto_almacen,
  hgpqgijw_operacion.mtto_almacen_area,
  hgpqgijw_operacion.mtto_almacen_equipos,
  hgpqgijw_usuarios.udn,
  hgpqgijw_operacion.mtto_categoria,
  hgpqgijw_operacion.mtto_proveedores,
  hgpqgijw_operacion.mtto_marca
  WHERE idUDN = UDN_Almacen and
  idArea = Area and
  idEquipo = Equipo and
  id_proveedor = idProveedor and
  id_marca     = idmarca   and
  id_categoria=idcategoria  and idAlmacen=?
  ";

  $sql = $this->_Select($query,$array);
  foreach ($sql as $row );
  return $row;
 }

 function Show_DATA_UP($array){
  $row = 0;

  $query = "
  SELECT

  nombreCategoria,
  StockAnterior,
  ProductosBaja,
  mtto_almacen_bitacora.Fecha,
  Equipo,
  idcategoria

  FROM
  hgpqgijw_operacion.mtto_almacen_bitacora,
  hgpqgijw_operacion.mtto_almacen_equipos,
  hgpqgijw_operacion.mtto_almacen,
  hgpqgijw_operacion.mtto_categoria

  WHERE
  mtto_almacen_bitacora.Almacen = mtto_almacen.idAlmacen AND
  mtto_almacen.Equipo = mtto_almacen_equipos.idEquipo and
  mtto_almacen.id_categoria = mtto_categoria.idcategoria
  and idAlmacen=?";

  $sql = $this->_Select($query,$array);
  foreach ($sql as $row );
  return $row;
 }

 function Update_Codigo($array){
  $query =
  "UPDATE
  hgpqgijw_operacion.mtto_almacen
  SET CodigoEquipo = ?,
  Equipo         = ?,
  Area           = ?,
  UDN_Almacen    = ?,
  cantidad       = ?,
  id_categoria   = ?,
  costo          = ?,
  TiempodeVida   = ?,
  EstadoProducto = ?,
  Observaciones  = ?,
  id_zona        = ?,
  id_Proveedor   = ?,
  id_marca       = ?

  WHERE idAlmacen = ?";
  $this->_DIU($query,$array,"2");
 }
 /*===========================================
 *									 BAJA EQUIPO
 =============================================*/
 function Update_Registro($array){
  $query =
  "UPDATE
  hgpqgijw_operacion.mtto_almacen
  SET
  cantidad=?
  WHERE idAlmacen = ?";
  $this->_DIU($query,$array,"2");
 }

 /*===========================================
 *									MODULO DE PRODUCTOS
 =============================================*/
 function SELECT_ARTICULO($array){
  $sql="SELECT NombreInsumo FROM hgpqgijw_operacion.mtto_almacen_insumo ";

  $ps = $this->_Select($sql,$array);
  return $ps;
 }

 function	NOMBRE_AREA($id){
  $area  = '';
  $array	=	array($id);

  $query	=
  "
  SELECT
  Nombre_Area
  FROM
  hgpqgijw_operacion.mtto_almacen_area
  WHERE idArea = ?;
  ";

  $sql	=	$this->_Select($query,$array,"1");
  foreach ($sql as $key ) {
   $area  = $key[0];
  }

  return	$area;
 }

 function	NOMBRE_CAT($id){
  $cat  = '';
  $array	=	array($id);

  $query	=
  "
  SELECT
  nombreCategoria
  FROM
  hgpqgijw_operacion.mtto_categoria
  WHERE
  idcategoria = ?
  ";

  $sql	=	$this->_Select($query,$array,"1");
  foreach ($sql as $key ) {
   $cat  = $key[0];
  }

  return	$cat;
 }
}
?>
