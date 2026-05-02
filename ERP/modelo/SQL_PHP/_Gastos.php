<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class Gastos extends CRUD {

 function	Destino ($array){

  $sql="
  SELECT
  id_UI,
  Name_IC
  FROM
  hgpqgijw_finanzas.compras
  INNER JOIN hgpqgijw_finanzas.insumos_udn ON id_UI = idUI
  INNER JOIN hgpqgijw_finanzas.insumos_clase ON id_IC = idIC
  WHERE
  hgpqgijw_finanzas.compras.id_CG = ? AND
  hgpqgijw_finanzas.compras.Fecha_Compras BETWEEN ? AND ?
  GROUP BY
  hgpqgijw_finanzas.insumos_clase.Name_IC
  ";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;

 }

 function Gastos_clase($array){
  $sql="
  SELECT
  compras.Fecha_Compras,
  gastos.Name_Gastos,
  compras.Gasto,
  compras.Observacion
  FROM
  hgpqgijw_finanzas.compras
  INNER JOIN hgpqgijw_finanzas.gastos_udn ON compras.id_UG = gastos_udn.idUG
  INNER JOIN hgpqgijw_finanzas.gastos ON gastos_udn.id_Gastos = gastos.idGastos
  WHERE
  compras.Fecha_Compras BETWEEN ? AND ? and
  id_ui = ? order by gasto desc
  ";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

function	GASTO_CLASE($array){
  $cuenta = '';
		$sql="SELECT Name_CG from hgpqgijw_finanzas.gasto_clase WHERE idCG = ? and Stado_CG =1";
  $ps	=	$this->_Select($sql,$array);
  foreach ($ps as $key) {
   $cuenta = $key[0];
  }
		return	$cuenta;
	}

}


?>
