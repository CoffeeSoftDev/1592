<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class PEDIDOS extends CRUD {

/* ---------------------------------- */
/* Productos complementaros
/* ---------------------------------- */
function _view_productos_adicionales($array){
 $sql="
  SELECT
   *
  FROM
    hgpqgijw_ventas.productos_adicionales
  WHERE id_referencia = ?
  ";
  $ps = $this->_Select($sql,$array);

  return  $ps;
}

function __add_Complemento($array){ // añade nuevo complemento
  $query = "
  INSERT INTO hgpqgijw_ventas.productos_adicionales
  (id_producto_adicional,cantidad,Precio,id_referencia)
  VALUES (?,?,?,?)";
  $this->_DIU($query,$array);
}



function _nuevo_complemento($array){
  $query = "INSERT INTO hgpqgijw_ventas.listaproductos (id_lista,id_productos) VALUES (?,?)";
  $this->_DIU($query,$array);

}




function add_lista_comp($array){

 $sql = "


 ";

 $ps = $this->_Select($sql,$array,"1");

}



function consultar_costo(){

}

/* ---------------------------------- */
/* **  NUEVO PRODUCTO            */
/* ---------------------------------- */
function __getProducto($array){
  $getProducto = 0;
  $sql="
  SELECT
    venta_productos.idProducto,
    venta_productos.NombreProducto
  FROM
    hgpqgijw_ventas.venta_productos
  WHERE idProducto = ?
  ";
  $ps = $this->_Select($sql,$array);

  foreach($ps as $key){
    $getProducto = $key[0];
  }
  return  $getProducto;
}

function __getProducto_name($array){
  $getProducto = 0;
  $sql="
  SELECT
    venta_productos.idProducto,
    Venta
  FROM
    hgpqgijw_ventas.venta_productos
  WHERE NombreProducto = ?
  ";
  $ps = $this->_Select($sql,$array);

  foreach($ps as $key){
    $getProducto = $key[0];
  }
  return  $ps;
}

/* ---------------------------------- */
/* Pestaña Formato De Pedidos         */
/* ---------------------------------- */
 function DetalleDestino($array){
    $busqueda = 0;
    $sql="
    SELECT
    ciudad
    FROM
    hgpqgijw_ventas.cliente
    WHERE
    NombreCliente = ? ";
    $ps	=	$this->_Select($sql,$array);

    foreach ($ps as $key) {
      $busqueda = $key[0];
    }
    return	$busqueda;
 }

 function	get_id_cliente($array){
	$id_Proveedor = '';
  $sql="
  SELECT
  idCliente
  FROM
  hgpqgijw_ventas.cliente WHERE NombreCliente = ? ";
  $ps	=	$this->_Select($sql,$array);
  foreach($ps as $key){
			$id_Proveedor = $key[0];
		}
  return	$id_Proveedor;
 }

 function ActualizarCliente($array){
  $query = "UPDATE hgpqgijw_ventas.lista_productos SET id_cliente = ? WHERE idLista = ?";
  $this->_DIU($query,$array,"3");
  return $query;
 }

 function InformacionCliente($array){
   $sql="
  SELECT
  *
  FROM
  hgpqgijw_ventas.cliente WHERE idCliente = ? ";
  $ps	=	$this->_Select($sql,$array);
  return $ps;
 }

 /* EN PROCESO */
 function NumFolio(){

  $folio = 1;
  $query = "SELECT count(*) FROM hgpqgijw_ventas.lista_productos WHERE id_Estado = 2";
  $sql = $this->_Select($query,null);

  foreach($sql as $row){
   $folio = $row[0];
  }

  return $folio;
 }

 function Folio_activo($array){
  $key = 0;
  $sql="
  SELECT
  date_format(foliofecha,'%Y-%m-%d'),
  Destino,
  folio,
  idLista,
  id_Estado,
  Detalles
  FROM
  hgpqgijw_ventas.lista_productos
  WHERE id_Estado = 1 and idLista=?";

  $ps = $this->_Select($sql,$array);

  return  $ps;
 }

 /*AGREGAR PRODUCTO*/
 function agregar_producto_a_lista($array) {
  $query = "INSERT INTO hgpqgijw_ventas.listaproductos (id_lista,id_productos) VALUES (?,?)";
  $this->_DIU($query,$array);
 }

 function consutar_si_existe($array){

  $sql="
  SELECT
  idListaProductos,
  descripcion
  FROM
  hgpqgijw_ventas.listaproductos

  Where idListaProductos = ?
  ";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function get_ultimo_registro(){
  $registro = 0;
  $sql="
  SELECT
  idListaProductos,
  descripcion
  FROM
  hgpqgijw_ventas.listaproductos

  order by idListaProductos desc limit 1";

  $ps	=	$this->_Select($sql,null);
  foreach($ps as $key){
    $registro = $key[0];
  }

  return	$registro;
 }

function get_IDUnidad($array){
  $idUnidad = 0;
  $sql="
  SELECT
  idUnidad
  FROM
  hgpqgijw_ventas.venta_unidad
  WHERE Unidad = ?
  ";
  $ps = $this->_Select($sql,$array);

  foreach($ps as $key){
    $idUnidad = $key[0];
  }
  return  $idUnidad;
 }


/* ------------------------------------ */
/*            Pedidos activos           */
/* ------------------------------------ */
 function ver_folio($array){
  $key = 0;
  $sql="
  SELECT
  date_format(foliofecha,'%Y-%m-%d'),
  id_cliente,
  folio,
  idLista,
  id_Estado,
  Detalles
  FROM
  hgpqgijw_ventas.lista_productos
  WHERE idLista=?";

  $ps = $this->_Select($sql,$array);

  return  $ps;
 }

 function	row_data($array){
  $sql="
  SELECT
    listaproductos.idListaProductos,
    listaproductos.descripcion,
    listaproductos.cantidad,

    listaproductos.id_unidad,
    listaproductos.id_lista,
    venta_unidad.Unidad,

    observacion,
    costo

  FROM
  hgpqgijw_ventas.listaproductos
  LEFT JOIN hgpqgijw_ventas.venta_unidad
  ON listaproductos.id_unidad = venta_unidad.idUnidad
  WHERE  id_lista = ?
  ORDER BY idListaProductos asc";


  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }


/* ------------------------------------ */
/*            Imprimir lista            */
/* ------------------------------------ */

 function	Consultar_folio($array){

  $sql  ="SELECT
      lista_productos.idLista,
      lista_productos.folio,
      cliente.NombreCliente,
      date_format(foliofecha,'%Y-%m-%d'),
      cliente.NombreCliente,
      lista_productos.Detalles
    FROM
    hgpqgijw_ventas.cliente
    INNER JOIN hgpqgijw_ventas.lista_productos ON lista_productos.id_cliente = cliente.idCliente
    ORDER BY idLista asc ";

  $ps	=	$this->_Select($sql,$array,"1");
  return	$ps;
 }

 function select_idFichero($array){
  $sql= "SELECT idSobre,Ruta,Archivo FROM hgpqgijw_ventas.sobres WHERE id_lista = ?";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 /* ===========================*/

 function HoursNOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%H:%i:%s')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function Insert_Sobres($array) {
  $query = "INSERT INTO hgpqgijw_ventas.sobres (UDN_Sobre,Ruta,Fecha,Hora,Archivo,Peso,Type_File,id_lista) VALUES (?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }

 function	DeleteMovimiento($array){
  $query	=	"
  DELETE FROM hgpqgijw_ventas.listaproductos
  WHERE idListaProductos = ?
  ";

  $this->_DIU($query,$array,"1");
 }

 function	Group_destino(){
  $sql="
  SELECT
  NombreCliente
  FROM
  hgpqgijw_ventas.cliente WHERE estado_cliente = 1";
  $ps	=	$this->_Select($sql,null);
  return	$ps;
 }


 function pedido_activo($array){
  $sql="
  SELECT
  date_format(foliofecha,'%Y-%m-%d'),
  NombreCliente,
  folio,
  idLista,
  id_Estado
  FROM
  hgpqgijw_ventas.lista_productos
  INNER JOIN hgpqgijw_ventas.cliente ON lista_productos.id_cliente = cliente.idCliente
  WHERE id_Estado = 1";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function pedido_activo_id($array){
  $key = 0;
  $sql="
  SELECT
  date_format(foliofecha,'%Y-%m-%d'),
  Destino,
  folio,
  idLista,
  id_Estado,
  Detalles
  FROM
  hgpqgijw_ventas.lista_productos
  WHERE id_Estado = 1 and idLista=?";

  $ps	=	$this->_Select($sql,$array);
  foreach ($ps as $key);
  return	$key;
 }

 function get_data_item($item){
  $array = array($item);
  $sql="
  SELECT
  venta_productos.idProducto,
  venta_productos.NombreProducto,
  venta_unidad.Unidad
  FROM
  hgpqgijw_ventas.venta_productos
  INNER JOIN hgpqgijw_ventas.venta_unidad ON venta_productos.id_unidad = venta_unidad.idUnidad
  Where NombreProducto = ?
  ";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function	VerFormatos($array){

  $sql  ="
    SELECT
    lista_productos.idLista,
    lista_productos.folio,
    NombreCliente,
    date_format(foliofecha,'%Y-%m-%d'),
    date_format(foliofecha,'%H:%i:%s'),
    id_Estado,
    id_cliente

    FROM
    hgpqgijw_ventas.cliente
    INNER JOIN hgpqgijw_ventas.lista_productos ON
    hgpqgijw_ventas.lista_productos.id_cliente = hgpqgijw_ventas.cliente.idCliente
    WHERE
    lista_productos.id_Estado = 2 and
    DATE_FORMAT(foliofecha,'%Y-%m-%d') Between  ? and ?
    GROUP BY idLista ORDER BY idLista desc";

  $ps	=	$this->_Select($sql,$array,"1");
  return	$ps;
 }



 function Total_Productos($array){
  $data = 0;
  $sql  ="
  SELECT
  count(*) as total
  FROM
  hgpqgijw_ventas.listaproductos
  WHERE id_lista = ? ";

  $ps	=	$this->_Select($sql,$array,"1");
  foreach ($ps as $key) {
   $data    = $key[0];
  }

  return	$data;
 }

 function Select_data_list($id_lista){
  $array = array($id_lista);
  $query = "SELECT
  Tipo,
  DATE_FORMAT(foliofecha,'%d-%m-%Y') AS fecha,
  folio
  FROM
  hgpqgijw_ventas.lista_productos,
  tiposolicitud
  WHERE
  id_tipo = idtipo
  AND idLista = ?
  ";
  $sql = $this->_Select($query,$array,"4");
  return $sql;
 }


 function Unidad(){
  $sql="
  SELECT
  *
  FROM
  hgpqgijw_ventas.venta_unidad";
  $ps = $this->_Select($sql,null);
  return  $ps;
 }


/* ------------------------------------ */
/*            PEDIDOS > CLIENTES         */
/* ------------------------------------ */

function list_cliente(){
  $query = "SELECT * FROM hgpqgijw_ventas.cliente WHERE estado_cliente=1 OR estado_cliente=0 order by idCliente DESC";
  $sql = $this->_Select($query,null,"5");
	return $sql;
}

function Consultar_cliente($array){
		$key   = '';
		$query = "SELECT * FROM hgpqgijw_ventas.cliente WHERE idCliente = ?  ";
    $sql = $this->_Select($query,$array,"5");
		foreach($sql as $key);
		return $key;
}

function actualizar_cliente($array){

  $query = "UPDATE hgpqgijw_ventas.cliente
  SET NombreCliente = ?, ciudad = ?,direccion = ?,telefono = ?,correo = ?, credito = ?, estado_cliente = ?
  WHERE idCliente = ?";
  $this->_DIU($query,$array,"3");
  return $query;
}

function QuitarCliente($array){

  $query = "UPDATE hgpqgijw_ventas.cliente
  SET estado_cliente = ? WHERE idCliente = ?";
  $this->_DIU($query,$array,"3");
}


function InsertarCliente($array){
  $query = "INSERT INTO
  hgpqgijw_ventas.cliente
  (NombreCliente,ciudad,direccion,telefono,correo,credito,estado_cliente)
  VALUES (?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);

}

function BuscarCliente($array){
    $key   = 0;
		$query = "SELECT * FROM hgpqgijw_ventas.cliente WHERE NombreCliente = ?  ";
    $sql = $this->_Select($query,$array,"5");

		foreach($sql as $key){
       $key   = 1;
    }
		return $key;
}


}



?>
