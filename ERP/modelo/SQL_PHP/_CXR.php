<?php
/*===================================================================
 *  _CXR.php  —  Cuentas Recuperadas (CXR)
 *  BD: hgpqgijw_finanzas   |   CRUD base: _Select / _DIU
 *  Cabecera (cxr) + abonos (cxr_abono) con saldo arrastrado.
 *===================================================================*/
include_once("_CRUD.php");

Class CXR extends CRUD {

 /*-----------------------------------*/
 /*   Catalogos para el alta
 /*-----------------------------------*/

 function lsCategorias($array){ // [id_UDN]
  $query = "SELECT idCategoria, categoria
            FROM hgpqgijw_finanzas.categoria
            WHERE Stado = 1 AND id_udn = ?
            ORDER BY categoria ASC";
  return $this->_Select($query,$array);
 }

 function lsSubcategorias($array){ // [id_Categoria]
  $query = "SELECT idSubcategoria, Subcategoria
            FROM hgpqgijw_finanzas.subcategoria
            WHERE id_Categoria = ? AND Stado = 1 AND activo = 1
            ORDER BY Subcategoria ASC";
  return $this->_Select($query,$array);
 }

 function lsFormasPago(){
  $query = "SELECT idFormas_Pago, FormasPago
            FROM hgpqgijw_finanzas.formas_pago
            WHERE grupo = 1
            ORDER BY idFormas_Pago ASC";
  return $this->_Select($query,null);
 }

 function getFolioByFecha($array){ // [Fecha]  -> id_Folio del dia (0 si no hay)
  $idFolio = 0;
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row){ $idFolio = $row[0]; }
  return $idFolio;
 }

 /*-----------------------------------*/
 /*   CXR  (cabecera)
 /*-----------------------------------*/

 function addCXR($array){
  // [Cliente, Concepto, MontoInicial, Saldo, FechaCreacion, FechaCompromiso,
  //  id_UDN, id_Subcategoria, id_Folio_origen, encargado]
  $query = "INSERT INTO hgpqgijw_finanzas.cxr
            (Cliente, Concepto, MontoInicial, Saldo, FechaCreacion, FechaCompromiso,
             id_UDN, id_Subcategoria, id_Folio_origen, encargado)
            VALUES (?,?,?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }

 function lsCXR($array){ // [id_UDN]  -> cuentas vivas con saldo > 0 (arrastre)
  $query = "
   SELECT
     c.idCXR,
     c.Cliente,
     c.Concepto,
     ROUND(c.MontoInicial,2) AS MontoInicial,
     ROUND(c.MontoInicial - c.Saldo,2) AS Abonado,
     ROUND(c.Saldo,2) AS Saldo,
     DATE_FORMAT(c.FechaCreacion,'%Y-%m-%d')   AS FechaCreacion,
     DATE_FORMAT(c.FechaCompromiso,'%Y-%m-%d') AS FechaCompromiso,
     c.id_estado,
     e.Estado,
     s.Subcategoria,
     cat.categoria,
     DATEDIFF(c.FechaCompromiso, CURDATE()) AS dias_restantes
   FROM hgpqgijw_finanzas.cxr c
   INNER JOIN hgpqgijw_finanzas.cxr_estado e ON c.id_estado = e.idEstado
   LEFT  JOIN hgpqgijw_finanzas.subcategoria s ON c.id_Subcategoria = s.idSubcategoria
   LEFT  JOIN hgpqgijw_finanzas.categoria  cat ON s.id_Categoria = cat.idCategoria
   WHERE c.active = 1 AND c.Saldo > 0 AND c.id_UDN = ?
   ORDER BY c.FechaCompromiso ASC, c.idCXR DESC";
  return $this->_Select($query,$array);
 }

 function getCXR($array){ // [idCXR]
  $query = "SELECT * FROM hgpqgijw_finanzas.cxr WHERE idCXR = ?";
  return $this->_Select($query,$array);
 }

 function bajaCXR($array){ // [idCXR]  -> soft-delete
  $query = "UPDATE hgpqgijw_finanzas.cxr SET active = 0 WHERE idCXR = ?";
  $this->_DIU($query,$array);
 }

 /*-----------------------------------*/
 /*   Abonos  (detalle)
 /*-----------------------------------*/

 function addAbono($array){
  // [MontoAbono, FechaAbono, Observacion, id_CXR, id_Folio, id_FormasPago]
  $query = "INSERT INTO hgpqgijw_finanzas.cxr_abono
            (MontoAbono, FechaAbono, Observacion, id_CXR, id_Folio, id_FormasPago)
            VALUES (?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }

 function lsAbonos($array){ // [idCXR]  -> historial
  $query = "
   SELECT
     a.idAbono,
     DATE_FORMAT(a.FechaAbono,'%Y-%m-%d') AS FechaAbono,
     ROUND(a.MontoAbono,2) AS MontoAbono,
     a.Observacion,
     fp.FormasPago
   FROM hgpqgijw_finanzas.cxr_abono a
   LEFT JOIN hgpqgijw_finanzas.formas_pago fp ON a.id_FormasPago = fp.idFormas_Pago
   WHERE a.id_CXR = ? AND a.active = 1
   ORDER BY a.FechaAbono ASC, a.idAbono ASC";
  return $this->_Select($query,$array);
 }

 function bajaAbono($array){ // [idAbono]  -> soft-delete
  $query = "UPDATE hgpqgijw_finanzas.cxr_abono SET active = 0 WHERE idAbono = ?";
  $this->_DIU($query,$array);
 }

 function sumaAbonosCXR($array){ // [idCXR]  -> total abonado de una cuenta
  $total = 0;
  $query = "SELECT COALESCE(SUM(MontoAbono),0)
            FROM hgpqgijw_finanzas.cxr_abono
            WHERE id_CXR = ? AND active = 1";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row){ $total = $row[0]; }
  return $total;
 }

 /*-----------------------------------*/
 /*   Recalculo de saldo + estado
 /*   (llamar tras cada alta/baja de abono)
 /*-----------------------------------*/

 function recalcularSaldo($array){ // [idCXR]
  $query = "
   UPDATE hgpqgijw_finanzas.cxr c
   SET c.Saldo = c.MontoInicial - (
         SELECT COALESCE(SUM(a.MontoAbono),0)
         FROM hgpqgijw_finanzas.cxr_abono a
         WHERE a.id_CXR = c.idCXR AND a.active = 1
       ),
       c.id_estado = CASE
         WHEN c.MontoInicial - (
                SELECT COALESCE(SUM(a.MontoAbono),0)
                FROM hgpqgijw_finanzas.cxr_abono a
                WHERE a.id_CXR = c.idCXR AND a.active = 1
              ) <= 0 THEN 2
         WHEN c.FechaCompromiso IS NOT NULL AND c.FechaCompromiso < CURDATE() THEN 3
         ELSE 1
       END
   WHERE c.idCXR = ?";
  $this->_DIU($query,$array);
 }

 /*-----------------------------------*/
 /*   Integracion Reporte General (RESUMEN_GRAL.php)
 /*-----------------------------------*/

 function sumAbonosFormaPago($array){ // [fi, ff, id_UDN]  -> abonos del periodo x forma de pago
  $query = "
   SELECT
     fp.idFormas_Pago,
     fp.FormasPago,
     ROUND(SUM(a.MontoAbono),2) AS suma_monto
   FROM hgpqgijw_finanzas.cxr_abono a
   INNER JOIN hgpqgijw_finanzas.cxr c          ON a.id_CXR = c.idCXR
   INNER JOIN hgpqgijw_finanzas.formas_pago fp ON a.id_FormasPago = fp.idFormas_Pago
   WHERE a.FechaAbono BETWEEN ? AND ? AND c.id_UDN = ? AND a.active = 1
   GROUP BY fp.idFormas_Pago";
  return $this->_Select($query,$array);
 }

 function totalAbonos($array){ // [fi, ff, id_UDN]  -> total abonos del periodo
  $total = 0;
  $query = "SELECT COALESCE(SUM(a.MontoAbono),0)
            FROM hgpqgijw_finanzas.cxr_abono a
            INNER JOIN hgpqgijw_finanzas.cxr c ON a.id_CXR = c.idCXR
            WHERE a.FechaAbono BETWEEN ? AND ? AND c.id_UDN = ? AND a.active = 1";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row){ $total = $row[0]; }
  return $total;
 }

}
?>
