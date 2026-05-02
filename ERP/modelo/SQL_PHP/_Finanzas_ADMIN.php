<?php
include_once ('_CRUD.php');


Class Finanzas extends CRUD{

 /*-----------------------------------*/
 /*   subir archivos a la nube
 /*-----------------------------------*/

 function subirArchivo($array,$id_compras) {

  $query =
  "INSERT INTO
  hgpqgijw_finanzas.sobres(Ruta,Fecha,Hora,
   Archivo,Peso,Type_File,Descripcion,UDN_Sobre)
   VALUES(?,?,?,?,?,?,?,?)";

   $this ->_DIU($query,$array);

   // Ligar archivo con registro de Gastos


   $query = "SELECT idSobre FROM hgpqgijw_finanzas.sobres ORDER BY idSobre DESC LIMIT 1";
   $sql = $this->_Select($query,null);
   foreach ($sql as $key);


   $array = array($id_compras,$key[0]);
   $query =
   "INSERT INTO
   hgpqgijw_finanzas.fcompras(id_compras,id_file)
   VALUES(?,?)";

   $this->_DIU($query,$array);


  }

  function SELECT_POLIZA($key){
   $array = array($key);

   $sql ="
   SELECT
   sobres.Archivo,
   sobres.Descripcion,
   sobres.Peso,
   sobres.Fecha,
   sobres.Type_File,
   sobres.Fecha,
   sobres.Hora,
   sobres.idSobre,
   Ruta,
   id_compras
   FROM
   hgpqgijw_finanzas.sobres
   INNER JOIN hgpqgijw_finanzas.fcompras ON fcompras.id_file = sobres.idSobre
   WHERE
   id_compras = ? ";

   $query = $this->_Select($sql,$array);
   return $query;
  }







  function Select_Categoria($udn){
   $array = array($udn);
   $query = "SELECT idCategoria,categoria,id_TMovimiento FROM hgpqgijw_finanzas.categoria WHERE id_UDN=?";
   $sql = $this->_Select($query,$array);
   return $sql;
  }

  function Select_Subcategoria($id){
   $array = array($id);
   $query = "SELECT idSubcategoria,Subcategoria FROM hgpqgijw_finanzas.subcategoria WHERE id_Categoria = ?";
   $sql = $this->_Select($query,$array);
   return $sql;
  }

  function Select_Impuestos($id){
   $array = array($id);
   $query = "SELECT idImpuesto,Impuesto,Valor FROM hgpqgijw_finanzas.categoria,hgpqgijw_finanzas.impuestos,hgpqgijw_finanzas.categoria_impuesto WHERE idCategoria = id_Categoria AND idImpuesto = id_Impuesto AND id_Categoria = ?";
   $sql = $this->_Select($query,$array);
   return $sql;
  }

  function Select_formaspago(){
   $query = "SELECT idFormas_Pago,FormasPago FROM hgpqgijw_finanzas.formas_pago";
   $sql = $this->_Select($query,null);
   return $sql;
  }


  function Select_MontoSubtotal($id,$fi,$ff){
   $array = array($id);
   $query =
   "SELECT
   sum(Subtotal)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.folio
   WHERE
   id_Folio = idFolio and
   id_Subcategoria = idSubcategoria and
   fecha BETWEEN  '".$fi."' and '".$ff."'
   AND id_Subcategoria = ?";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row2);
   if ( !isset($row2[0]) ) { $row2[0] = 0; }
   return $row2[0];
  }

  function Select_MontoImpuesto($idS,$idImp,$fi,$ff){

   $array = array($idImp,$idS);

   $query = "
   SELECT
   SUM(Monto), -- SUMA DE IMPUESTOS
   folio.Folio,
   folio.Fecha
   FROM
   hgpqgijw_finanzas.bitacora_impuesto,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio
   WHERE
   id_Bitacora = idVentasBit and
   id_Folio = idFolio and
   bitacora_impuesto.id_Impuesto = ? and id_Subcategoria = ?
   and  fecha BETWEEN  '".$fi."' and '".$ff."'
   ";



   $sql = $this->_Select($query,$array);
   foreach ($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }
   return $row3[0];
  }



  function Select_Total1($idS,$fi,$ff){

   $array = array($idS);
   $query =
   "SELECT sum(Subtotal)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio
   WHERE   id_Subcategoria = ? and
   id_Folio = idFolio and
   fecha BETWEEN  '".$fi."' and '".$ff."' ";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row2);
   if ( !isset($row2[0]) ) { $row2[0] = 0; }
   // --

   $array = array($idS);
   $query =
   "SELECT SUM(Monto)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.bitacora_impuesto,
   hgpqgijw_finanzas.folio
   WHERE id_Bitacora = idVentasBit AND
   id_Folio = idFolio and
   id_Subcategoria = ? and
   fecha BETWEEN  '".$fi."' and '".$ff."' ";

   $sql = $this->_Select($query,$array);
   foreach($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }

   $total = $row2[0] + $row3[0];
   return $total;
  }

  function Select_MontoFPago($idS,$idFP,$fi,$ff){


   $array = array($idS,$idFP);
   $query = "
   SELECT
   sum(bitacora_formaspago.Monto),
   formas_pago.FormasPago,
   bitacora_ventas.Subtotal,
   bitacora_ventas.id_Folio,
   folio.Folio,
   folio.Fecha,
   bitacora_ventas.id_Subcategoria
   FROM
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio

   WHERE
   id_FormasPago = idFormas_Pago and
   id_Bitacora = idVentasBit and
   id_Folio = idFolio and
   folio.Fecha BETWEEN '".$fi."' AND '".$ff."' and id_Subcategoria = ? AND id_FormasPago = ?
   ";
   $sql = $this->_Select($query,$array);
   foreach ($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }
   return $row3[0];

  }

  function Select_Total2($idS,$fi,$ff){

   $array = array($idS);
   $query = "
   SELECT
   sum(bitacora_formaspago.Monto),
   formas_pago.FormasPago,
   bitacora_ventas.Subtotal,
   bitacora_ventas.id_Folio,
   folio.Folio,
   folio.Fecha,
   bitacora_ventas.id_Subcategoria
   FROM
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio

   WHERE
   id_FormasPago = idFormas_Pago and
   id_Bitacora = idVentasBit and
   id_Folio = idFolio and
   folio.Fecha BETWEEN '".$fi."' AND '".$ff."' and id_Subcategoria = ?
   ";


   $sql = $this->_Select($query,$array);
   foreach($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }
   return $row3[0];

  }
  function Select_TC_Data($date1,$date2){
    $array = array($date1,$date2);
    $query = "SELECT Monto,TCodigo,TTCodigo,Concepto,Especificacion,Cliente,Autorizacion,Observaciones,Fecha,idTC FROM hgpqgijw_finanzas.tc,hgpqgijw_finanzas.terminal,hgpqgijw_finanzas.tipo_terminal,hgpqgijw_finanzas.folio WHERE idTipoTerminal = id_TipoTerminal AND idTerminal = id_Terminal AND id_Folio = idFolio AND Fecha BETWEEN ? AND ?";
    $sql = $this->_Select($query,$array);
    return $sql;
  }
 }
 ?>
