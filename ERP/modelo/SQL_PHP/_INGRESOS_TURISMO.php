<?php
include_once("_CRUD.php");

Class TURISMO extends CRUD {
 #------------------------------------#
 #
 #------------------------------------#
 function NOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function Ayer(){
  $query = "SELECT DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  return $row[0];
 }

 function	Select_group($array){

  $sql="
    SELECT

      grupo.idgrupo,
      grupo.gruponombre
    FROM
      hgpqgijw_finanzas.grupo
      INNER JOIN hgpqgijw_finanzas.subcategoria ON subcategoria.id_grupo = grupo.idgrupo
      WHERE id_Categoria = ? and Stado = 1
    GROUP BY
    grupo.idgrupo
  ";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function Select_Subcategoria_x_grupo($id,$grupo){
  $array = array($id,$grupo);
  $query = "SELECT idSubcategoria,Subcategoria,id_grupo
  FROM hgpqgijw_finanzas.subcategoria
  WHERE id_Categoria = ? and Stado = 1 and
  id_grupo=? order by idSubcategoria asc";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Select_Impuestos($id){
  $array = array($id);
  $query = "SELECT idImpuesto,Impuesto,Valor FROM hgpqgijw_finanzas.categoria,hgpqgijw_finanzas.impuestos,hgpqgijw_finanzas.categoria_impuesto WHERE idCategoria = id_Categoria AND idImpuesto = id_Impuesto AND id_Categoria = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Select_formaspago_by_categoria($array){
  $query = "SELECT idFormas_Pago,FormasPago FROM hgpqgijw_finanzas.formas_pago WHERE grupo = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }


 /*-----------------------------------*/
 /*		** Consulta por fecha **
 /*-----------------------------------*/

 function Select_MontoSubtotal($date1,$date2,$idS){
  $array = array($date1,$date2,$idS);

  $query = "
  SELECT
  ROUND(SUM(Subtotal),2),
  folio.Fecha,
  folio.Folio
  FROM
  hgpqgijw_finanzas.folio
  INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
  WHERE FECHA BETWEEN ? and ? and id_Subcategoria = ? ";


  $sql = $this->_Select($query,$array);
  foreach($sql as $row2);
  if ( !isset($row2[0]) ) { $row2[0] = 0; }

  return $row2[0];

 }

 function	ExisteEnBitacora($date1,$date2,$idSub,$campo){

  $res = null;

  $sql = '
  SELECT
  SUM('.$campo.'),
  folio.Fecha,
  folio.Folio
  FROM
  hgpqgijw_finanzas.folio
  INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
  WHERE FECHA BETWEEN ? and ? and id_Subcategoria = ?';



  $ps 	 =	$this->_Select($sql,array($date1,$date2,$idSub));
  foreach ($ps as $key ) {  $res = $key[0]; }


  return	$res;
 }

 function Select_MontoFPago($date1,$date2,$idS,$idFP){
  $array = array($date1,$date2,$idS,$idFP);

  $query = "
  SELECT
  ROUND(SUM(Monto),2),
  bitacora_formaspago.id_FormasPago,
  bitacora_ventas.pax
  FROM
  hgpqgijw_finanzas.folio
  INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
  INNER JOIN hgpqgijw_finanzas.bitacora_formaspago ON bitacora_formaspago.id_Bitacora = bitacora_ventas.idVentasBit
  WHERE
  folio.Fecha BETWEEN ? AND ? AND
  bitacora_ventas.id_Subcategoria = ? and id_FormasPago = ? ";

  $sql = $this->_Select($query,$array);

  foreach ($sql as $row3);

  if ( !isset($row3[0]) ) { $row3[0] = 0; }

  return $row3[0];

 }

 function Select_MontoImpuesto($date1,$date2,$idS,$idImp){

  $array = array($date1,$date2,$idS,$idImp);
  $query = "
  SELECT

  ROUND(SUM(Monto),2)
  FROM
  hgpqgijw_finanzas.folio
  INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
  INNER JOIN hgpqgijw_finanzas.bitacora_impuesto ON bitacora_impuesto.id_Bitacora = bitacora_ventas.idVentasBit
  WHERE
  folio.Fecha BETWEEN ? AND ? AND
  bitacora_ventas.id_Subcategoria = ? AND id_Impuesto = ?";

  $sql = $this->_Select($query,$array);
  foreach ($sql as $row3);
  if ( !isset($row3[0]) ) { $row3[0] = 0; }
  return $row3[0];


 }

 function Select_Total2($date1,$date2,$idS){

  $array = array($date1,$date2,$idS);
  $query = "
  SELECT

  ROUND(SUM(Monto),2)
  FROM
  hgpqgijw_finanzas.folio
  INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
  INNER JOIN hgpqgijw_finanzas.bitacora_formaspago ON bitacora_formaspago.id_Bitacora = bitacora_ventas.idVentasBit
  WHERE
  folio.Fecha BETWEEN ? AND ? AND
  bitacora_ventas.id_Subcategoria = ?";


  $sql = $this->_Select($query,$array);
  foreach($sql as $row3);
  if ( !isset($row3[0]) ) { $row3[0] = 0; }
  return $row3[0];
 }

  function	archivo_adjunto($array){
  $file = '';
  $sql="
  SELECT
  sobres.Ruta,
  sobres.Archivo,
  sobres.file_categoria,
  sobres.Type_File,
  sobres.Fecha,
  sobres.UDN_Sobre,
  check_list.id_categoria,
  check_list.check_name,
  motivo
  FROM
  hgpqgijw_finanzas.sobres
  INNER JOIN hgpqgijw_finanzas.check_list ON sobres.id_checklist = check_list.idcheck
  WHERE id_categoria = ? and fecha between ? and ?
  ";

  $ps	=	$this->_Select($sql,$array);

  return	$ps;
 }

// Tendencias ...

function ver_reporte_mensual($array){
    $monto = 0;
  $sql="
  SELECT
      ROUND(SUM(Monto),2),
      bitacora_formaspago.id_FormasPago
  FROM
      hgpqgijw_finanzas.folio
      INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
      INNER JOIN hgpqgijw_finanzas.bitacora_formaspago ON bitacora_formaspago.id_Bitacora = bitacora_ventas.idVentasBit
  WHERE
   MONTH(folio.Fecha) = ?
  AND YEAR(folio.Fecha) = ?
  AND
  bitacora_ventas.id_Subcategoria = ? and id_FormasPago = ?
  ";

  $ps	=	$this->_Select($sql,$array);
  foreach($ps as $key){
      $monto = $key[0];
  }

  return	$monto;
}


}

?>
