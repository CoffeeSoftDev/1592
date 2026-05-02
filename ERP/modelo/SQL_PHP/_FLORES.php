<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');


Class _PRODUCTOS extends CRUD{

	function	_Categoria($array){
		$sql="
		SELECT
		hgpqgijw_ventas.venta_categoria.idcategoria,
		hgpqgijw_ventas.venta_categoria.nombrecategoria
		FROM
		hgpqgijw_ventas.venta_categoria
		WHERE id_udn = ?
		";
		$ps	=	$this->_Select($sql,$array);
		return	$ps;
	}

	function	_SubCategoria($array){
		$sql="
		SELECT
		idSubcategoria,
		Nombre_subcategoria
		FROM
		hgpqgijw_ventas.venta_subcategoria
		Where id_categoria = ?
		";
		$ps	=	$this->_Select($sql,$array);
		return	$ps;
	}

	function	VerProductos($array){
		$sql="
		SELECT
		NombreProducto,
		Costo,
		Venta,

		nombrecategoria,
		Stock_Inicial,
		Stock_Minimo,
		idProducto
		FROM
		hgpqgijw_ventas.venta_productos
		INNER JOIN hgpqgijw_ventas.venta_subcategoria ON hgpqgijw_ventas.venta_productos.id_subcategoria = hgpqgijw_ventas.venta_subcategoria.idSubcategoria
		INNER JOIN hgpqgijw_ventas.venta_categoria ON hgpqgijw_ventas.venta_subcategoria.id_categoria = hgpqgijw_ventas.venta_categoria.idcategoria
		WHERE id_udn = ?";
		$ps	=	$this->_Select($sql,$array);
		return	$ps;
	}

	function	VerProductos_by_categoria($array){
		$sql="
		SELECT
		NombreProducto,
		Costo,
		Venta,

		nombrecategoria,
		Stock_Inicial,
		Stock_Minimo,
		idProducto
		FROM
		hgpqgijw_ventas.venta_productos
		INNER JOIN hgpqgijw_ventas.venta_subcategoria ON hgpqgijw_ventas.venta_productos.id_subcategoria = hgpqgijw_ventas.venta_subcategoria.idSubcategoria
		INNER JOIN hgpqgijw_ventas.venta_categoria ON hgpqgijw_ventas.venta_subcategoria.id_categoria = hgpqgijw_ventas.venta_categoria.idcategoria
		WHERE id_udn = ?";
		$ps	=	$this->_Select($sql,$array);
		return	$ps;
	}

	function SAVE_FORM($array,$campos,$bd){
		$data ='';
		$x    ='';
		$query ='';
		$query = $query."INSERT INTO ".$bd."(" ;

		for ($i=0; $i < count($campos); $i++) {
			$data = $data."".$campos[$i].",";
			$x    = $x.'?,';
		}

		$data  = substr($data,0,strlen($data)-1);
		$x     = substr($x,0,strlen($x)-1);

		$query = $query.$data.") VALUES (".$x.")";
		$this->_DIU($query,$array);

		return $query;
	}

	function ver_requisiciones(){
		$sql="
		SELECT
		*
		FROM
		hgpqgijw_ventas.lista_productos
		WHERE id_Estado = 1";
		$ps	=	$this->_Select($sql,null);
		return	$ps;
	}




}



?>
