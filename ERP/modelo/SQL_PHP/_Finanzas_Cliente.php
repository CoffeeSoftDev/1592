<?php
include_once("_CRUD.php");

Class Finanzas extends CRUD {
 #------------------------------------#
 # Clientes de hotel
 #------------------------------------# 
 function check_as(){
   $query = "SELECT * FROM hgpqgijw_finanzas.clientes";
   $sql   = $this->_Select($query,$array);

   return $sql;
 }

 function ConsultarFolioActual($array){
    $__getFolio = 0; 
    $query = "
        SELECT *
        FROM hgpqgijw_finanzas.folio
        WHERE Fecha = ?";
        
    $sql = $this->_Select($query,$array);
    foreach($sql as $row){
      $__getFolio = $row[0];      
    }    
   
   return $__getFolio;
 }
 
 function ConsultarOcupacion($array){
   $_idOcupacion = 0; 
    $query = "
        SELECT idVentasBit
        FROM hgpqgijw_finanzas.bitacora_ventas
        WHERE id_Folio = ? and id_Subcategoria= ?";
        
    $sql = $this->_Select($query,$array);
    foreach($sql as $row){
      $_idOcupacion = $row[0];      
    }    
   
   return $_idOcupacion;    
 }
 
 function Insert_sub_check($array){
  $query = "INSERT INTO hgpqgijw_finanzas.sobres(UDN_sobre,Fecha,id_checklist,motivo) VALUES (?,?,?,?)";
  $this->_DIU($query,$array);
 }

 function nuevo_huesped($array){
  $idHuesped = ''; 
  $query = "INSERT INTO hgpqgijw_finanzas.clientes(NombreCliente) VALUES (?)";
  $this->_DIU($query,$array);
  #---
  $query = "SELECT idCliente FROM hgpqgijw_finanzas.clientes WHERE NombreCliente = ?";

  $sql = $this->_Select($query,$array);

  foreach ($sql as $key) {
   $idHuesped = $key[0];
  }

  return $idHuesped;
 }

 function Consultar_huesped($array){
  $idHuesped = '';
  $query  = "SELECT idCliente FROM hgpqgijw_finanzas.clientes WHERE NombreCliente = ?";
  $sql    = $this->_Select($query,$array);
  
  foreach ($sql as $key) {
    $idHuesped = $key[0];
  }

  return $idHuesped;
 }


 /*-----------------------------------*/
 /*  Check List archivos
 /*-----------------------------------*/
 function sobres_check_list($array){
  $files   = '';
  $sql = "
  SELECT
  Ruta,
  Archivo,
  Fecha,
  idSobre,
  id_checklist,
  motivo
  FROM
  hgpqgijw_finanzas.sobres
  WHERE Fecha = ? and id_checklist = ?";

  $ps	 =	$this->_Select($sql,$array);
  foreach ($ps as $files );

  return	$ps;
 }

 function ver_CheckList($array){
  $sql="SELECT idcheck,check_name FROM hgpqgijw_finanzas.check_list Where id_udn = ?";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

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

 /*-----------------------------------*/
 /*  Ingresos & Egresos
 /*-----------------------------------*/
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
  grupo.idgrupo";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }


 function	Select_Categoria_by_sub($array){
  $key = 0;
  $sql="SELECT id_Categoria FROM hgpqgijw_finanzas.subcategoria Where idSubcategoria = ?";
  $ps	=	$this->_Select($sql,$array);
  foreach ($ps as $x){
   $key = $x[0];
  }
  return	$key;
 }

 function Select_Categoria($array){
  $query = "SELECT idCategoria,categoria,id_TMovimiento FROM hgpqgijw_finanzas.categoria WHERE Stado = 1 and id_udn=?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Select_Subcategoria_x_grupo($id,$grupo){
  $array = array($id,$grupo);
  $query = "SELECT idSubcategoria,Subcategoria,id_grupo,tarifa FROM hgpqgijw_finanzas.subcategoria WHERE id_Categoria = ? and Stado = 1 and activo = 1 and id_grupo=? order by idSubcategoria asc";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function	BuscarSubcategoria($array){
  $key = 0;
  $sql="SELECT Subcategoria FROM hgpqgijw_finanzas.subcategoria WHERE id_Categoria = ? and Subcategoria = ? ";
  $ps	=	$this->_Select($sql,$array);
  foreach ($ps as $key);

  return	$key;
 }

 function Select_Subcategoria($id){
  $array = array($id);
  $query = "SELECT idSubcategoria,Subcategoria,id_grupo FROM hgpqgijw_finanzas.subcategoria WHERE id_Categoria = ? and Stado = 1 order by idSubcategoria asc";
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

 function Select_idFolio($date) {
  $array = array($date);
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_FolioDesc($date) {
  $array = array($date);
  $query = "SELECT Folio FROM hgpqgijw_finanzas.folio WHERE Fecha < ? ORDER BY idFolio DESC LIMIT 1";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Folio($nFolio,$date,$idE) {
  $array = array($nFolio,$date,$idE);
  $query = "INSERT INTO hgpqgijw_finanzas.folio (Folio,Fecha,id_UDN) VALUES (?,?,?)";
  $this->_DIU($query,$array);
 }

 function Select_idSubBitacora($array){
  $query = "SELECT idVentasBit FROM hgpqgijw_finanzas.bitacora_ventas WHERE id_Folio = ? AND id_Subcategoria = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_SubBitacora($array){
  $query = "INSERT INTO hgpqgijw_finanzas.bitacora_ventas (id_Folio,id_Subcategoria,Subtotal) VALUES (?,?,?)";
  $this->_DIU($query,$array);
 }
 function Update_SubBitacora($array){
  $query = "UPDATE hgpqgijw_finanzas.bitacora_ventas SET Subtotal = ? WHERE idVentasBit = ?";
  $this->_DIU($query,$array);
 }
 function Select_idImpBitacora($idSBit,$idImp){
  $array = array($idSBit,$idImp);
  $query = "SELECT idImp_Bitacora FROM hgpqgijw_finanzas.bitacora_impuesto WHERE id_Bitacora = ? AND id_Impuesto = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_ImpBitacora($array){
  $query = "INSERT INTO hgpqgijw_finanzas.bitacora_impuesto (id_Bitacora,id_Impuesto,Monto) VALUES (?,?,?)";
  $this->_DIU($query,$array);
 }
 function Update_ImpBitacora($array){
  $query = "UPDATE hgpqgijw_finanzas.bitacora_impuesto SET Monto = ? WHERE id_Bitacora = ? AND id_Impuesto = ?";
  $this->_DIU($query,$array);
 }

 function Select_idFPBitacora($array){
  $query = "SELECT idFP_Bitacora FROM hgpqgijw_finanzas.bitacora_formaspago WHERE id_Bitacora = ? AND id_FormasPago = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_FPBitacora($array){
  $query = "INSERT INTO hgpqgijw_finanzas.bitacora_formaspago (id_Bitacora,id_FormasPago,Monto) VALUES (?,?,?)";
  $this->_DIU($query,$array);
 }
 function Update_FPBitacora($array){
  $query = "UPDATE hgpqgijw_finanzas.bitacora_formaspago SET Monto = ? WHERE id_Bitacora = ? AND id_FormasPago = ?";
  $this->_DIU($query,$array);
 }

 function Select_MontoSubtotal($date,$idS){
  $array = array($date);
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  $idF = $row[0];

  if ( $idF != 0) {
   $array = array($idF,$idS);
   $query = "SELECT Subtotal FROM hgpqgijw_finanzas.bitacora_ventas WHERE id_Folio = ? AND id_Subcategoria = ?";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row2);
   if ( !isset($row2[0]) ) { $row2[0] = 0; }
   return $row2[0];
  }
 }

 /*-----------------------------------*/
 /* Pax
 /*-----------------------------------*/

 function Buscar_en_bitacora($date,$idSub,$campo){
  // >> Folio activo
  $res  = null;
  $fol  = null;
  $sql  = 'SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ? ';
  $ar1  = array($date);
  $ps 	 =	$this->_Select($sql,$ar1);
  foreach ($ps as $key ) {  $fol = $key[0]; }

  // Existe !
  if (count($ps)) {

   $sql  =' SELECT '.$campo.' FROM
   hgpqgijw_finanzas.bitacora_ventas
   WHERE id_Subcategoria = ? and id_Folio = ?';
   $ar2  = array($idSub,$fol);
   $ps 	 =	$this->_Select($sql,$ar2);
   foreach ($ps as $key ) {  $res = $key[0]; }

  }

  return	$res ;
 }

 function	ExisteEnBitacora($date,$idSub,$campo){

  // >> Folio activo
  $res  = null;
  $fol  = null;
  $sql  = 'SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ? ';
  $ar1  = array($date);
  $ps 	 =	$this->_Select($sql,$ar1);
  foreach ($ps as $key ) {  $fol = $key[0]; }

  // Existe !
  if (count($ps)) {

   $sql  =' SELECT '.$campo.' FROM
   hgpqgijw_finanzas.bitacora_ventas
   WHERE id_Subcategoria = ? and id_Folio = ?';
   $ar2  = array($idSub,$fol);
   $ps 	 =	$this->_Select($sql,$ar2);
   foreach ($ps as $key ) {  $res = $key[0]; }

  }

  return	$res;
 }

 function BitacoraUpdate($date,$idSub,$valor,$campo){

  /* Obtener folio */
  $array = array($date);
  $sql   = "SELECT idFolio FROM hgpqgijw_finanzas.folio
  WHERE Fecha = ?";

  $ps   	=	$this->_Select($sql,$array);
  foreach ($ps as $key);
  $idFolio = $key[0];

  /*Actualizar registro */

  $array = array($valor,$idSub,$idFolio);
  $query = "
  UPDATE hgpqgijw_finanzas.bitacora_ventas
  SET ".$campo." = ?
  WHERE id_Subcategoria = ? and id_Folio =?;
  ";

  $this->_DIU($query,$array);
 }

 function	PaxInsert($date,$idSub,$valor,$udn,$campo){

  // >> Existe un folio
  $fol  = null;
  $sql  = 'SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ? ';
  $ar1  = array($date);
  $ps 	 =	$this->_Select($sql,$ar1);
  foreach ($ps as $key ) {  $fol = $key[0]; }

  /* ------ Existe folio  -------*/

  if (count($ps)){

   // >> Crear idVentasBit

   $array = array($fol,$idSub,$valor);
   $sql   = "INSERT INTO hgpqgijw_finanzas.bitacora_ventas (id_folio,id_Subcategoria,".$campo.") VALUES (?,?,?)";
   $this->_DIU($sql,$array);

  }

  /* ------ No hay folio.  -------*/
  else {

   // Obtener ultimo folio
   $sql   = "SELECT count(idFolio) from hgpqgijw_finanzas.folio ORDER BY idFolio desc limit 1";
   $ps	   =	$this->_Select($sql,null);
   foreach ($ps as $key);

   $array = array(($key[0]+1),$date,$udn);
   $sql="INSERT INTO hgpqgijw_finanzas.folio (Folio,Fecha,id_UDN) VALUES (?,?,?)";
   $this->_DIU($sql,$array);

   // >>
   $fol  = null;
   $sql  = 'SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ? ';
   $ar1  = array($date);
   $ps 	 =	$this->_Select($sql,$ar1);
   foreach ($ps as $key ) {  $fol = $key[0]; }

   // >> Crear idVentasBit

   $array = array($fol,$idSub,$valor);
   $sql   = "INSERT INTO hgpqgijw_finanzas.bitacora_ventas (id_folio,id_Subcategoria,".$campo.") VALUES (?,?,?)";
   $this->_DIU($sql,$array);
  }

 }

 function Select_MontoImpuesto($date,$idS,$idImp){
  $array = array($date);
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  $idF = $row[0];

  if ( $idF != 0) {
   $array = array($idF,$idS);
   $query = "SELECT idVentasBit FROM hgpqgijw_finanzas.bitacora_ventas WHERE id_Folio = ? AND id_Subcategoria = ?";
   $sql = $this->_Select($query,$array);
   foreach ($sql as $row2);
   if ( !isset($row2[0]) ) { $row2[0] = 0; }
   $idSBit = $row2[0];

   $array = array($idSBit,$idImp);
   $query = "SELECT Monto FROM hgpqgijw_finanzas.bitacora_impuesto WHERE id_Bitacora = ? AND id_Impuesto = ?";
   $sql = $this->_Select($query,$array);
   foreach ($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }
   return $row3[0];
  }

 }

 function Select_Total1($date,$idS){
  $array = array($date);
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  $idF = $row[0];

  if ( $idF != 0) {
   $array = array($idF,$idS);
   $query = "SELECT Subtotal FROM hgpqgijw_finanzas.bitacora_ventas WHERE id_Folio = ? AND id_Subcategoria = ?";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row2);
   if ( !isset($row2[0]) ) { $row2[0] = 0; }

   $array = array($idF,$idS);
   $query = "SELECT SUM(Monto) FROM hgpqgijw_finanzas.bitacora_ventas,hgpqgijw_finanzas.bitacora_impuesto WHERE id_Bitacora = idVentasBit AND id_Folio = ? AND id_Subcategoria = ?";
   $sql = $this->_Select($query,$array);
   foreach($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }

   $total = $row2[0] + $row3[0];
   return $total;
  }
 }

 function Select_MontoFPago($date,$idS,$idFP){
  $array = array($date);
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  $idF = $row[0];

  if ( $idF != 0) {
   $array = array($idF,$idS);
   $query = "SELECT idVentasBit FROM hgpqgijw_finanzas.bitacora_ventas WHERE id_Folio = ? AND id_Subcategoria = ?";
   $sql = $this->_Select($query,$array);
   foreach ($sql as $row2);
   if ( !isset($row2[0]) ) { $row2[0] = 0; }
   $idSBit = $row2[0];

   $array = array($idSBit,$idFP);
   $query = "SELECT Monto FROM hgpqgijw_finanzas.bitacora_formaspago WHERE id_Bitacora = ? AND id_FormasPago = ?";
   $sql = $this->_Select($query,$array);
   foreach ($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }
   return $row3[0];
  }
 }
 function Select_Total2($date,$idS){
  $array = array($date);
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  $idF = $row[0];

  $array = array($idF,$idS);
  $query = "SELECT idVentasBit FROM hgpqgijw_finanzas.bitacora_ventas WHERE id_Folio = ? AND id_Subcategoria = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row2);
  if ( !isset($row2[0]) ) { $row2[0] = 0; }
  $idSBit = $row2[0];

  $array = array($idSBit);
  $query = "SELECT SUM(Monto) FROM hgpqgijw_finanzas.bitacora_formaspago WHERE id_Bitacora = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row3);
  if ( !isset($row3[0]) ) { $row3[0] = 0; }
  return $row3[0];
 }

 function	Select_FP($array){

  $sql="
  SELECT
  impuestos.Valor
  FROM
  hgpqgijw_finanzas.subcategoria
  INNER JOIN hgpqgijw_finanzas.categoria ON subcategoria.id_Categoria = categoria.idCategoria
  INNER JOIN hgpqgijw_finanzas.categoria_impuesto ON categoria_impuesto.id_Categoria = categoria.idCategoria
  INNER JOIN hgpqgijw_finanzas.impuestos ON categoria_impuesto.id_Impuesto = impuestos.idImpuesto
  WHERE idSubcategoria = ?

  ";

  $ps	=	$this->_Select($sql,$array);

  return	$ps;
 }



 function Select_Retiro($idE) {
  $array = array($idE);
  $query = "SELECT idRetiro,ROUND(SF,2),Fecha_Rembolso
  FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ?
  ORDER BY idRetiro desc LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Fondo_Caja_Remaster($idE,$date_SI,$date_now) {
  $row = null;
  if ( !isset($date_SI) ) {
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Gasto),2)
   FROM hgpqgijw_finanzas.compras WHERE id_UG
   IS NOT NULL AND id_CG = 3 AND id_UDN = ?
   AND Fecha_Compras <= ?";
  }
  else {
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Pago_Proveedor_Remaster_Fondo($idE,$date_SI,$date_now){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  >= ? AND Fecha_Compras <= ?";
  }
  else{
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  <= ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Login($array) {
  $query = "SELECT idUsuario,Nivel,Gerente,Email,UDN,Area_Usuario,Permiso FROM usuarios WHERE Usuario = ? AND Password = SHA(?)";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Select_Count_Moneda() {
  $row = null;
  $query = "SELECT Count(*) FROM hgpqgijw_finanzas.moneda_extranjera WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0;}
  return $row[0];
 }

 function Select_Data_Moneda() {
  $query = "SELECT idMoneda,Moneda,Abreviatura,Valor FROM hgpqgijw_finanzas.moneda_extranjera WHERE Stado = 1 ORDER BY idMoneda asc";
  $sql = $this->_Select($query,null);
  return $sql;
 }

 function Select_LoginFinanzas($pass){
  $array = array($pass);
  $query = "SELECT idUsuario FROM hgpqgijw_usuarios.usuarios WHERE Nivel = 3 AND Password = SHA(?)";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_Comprobacion_Retiro_Efectivo($idE,$date1,$date2) {
  $array = array($idE,$date1,$date2);
  $query = "SELECT Fecha_Retiro FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_SI_Retiro_Efectivo($idE,$date1) {
  $array = array($idE,$date1);
  $query =
  "SELECT
  Fecha_Retiro,
  SF_Total,
  SF_Efectivo,
  idRetiroVenta
  FROM
  hgpqgijw_finanzas.retiros_venta
  WHERE
  id_UDN = ?
  AND Fecha_Retiro < ?
  ORDER BY
  Fecha_Retiro DESC
  LIMIT 1";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 /* MODIFICACION DESARROLLO */
 function _FechaRembolso_Remaster($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT
  Fecha_Rembolso,SF
  FROM hgpqgijw_finanzas.retiros
  WHERE id_UDN = ? AND Fecha_Rembolso < ?
  ORDER BY idRetiro DESC LIMIT 1";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Fondo_detallado($array) {
  $query = "
  SELECT
  hgpqgijw_finanzas.retiros.SI,
  hgpqgijw_finanzas.retiros.Rembolso,
  hgpqgijw_finanzas.retiros.SF,
  hgpqgijw_finanzas.retiros.Observaciones,
  idRetiro
  FROM
  hgpqgijw_finanzas.retiros
  WHERE
  id_UDN = ?
  AND Fecha_Rembolso = ?
  ORDER BY
  idRetiro DESC

  ";

  $sql = $this->_Select($query,$array);
  return $sql;
 }



 function Select_FechaRembolso_Remaster($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT Fecha_Rembolso,SF
  FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso < ? ORDER BY Fecha_Rembolso DESC LIMIT 1";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function Select_ExisteRembolso_Remaste($idE,$dateRem,$date_now) {
  $row = null;
  if ( $dateRem == 0 ) {
   $array = array($idE,$date_now);
   $query = "SELECT Rembolso FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso <= ?";
  }
  else {
   $array = array($idE,$dateRem,$date_now);
   $query = "SELECT Rembolso FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso > ? AND Fecha_Rembolso <= ?";
  }
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_GastosRembolso_Remaster($idE,$date_rembolso,$date) {
  $row = null;
  if ( $date_rembolso == 0 ) {
   $array = array($idE,$date);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras < ?";
  }
  else {
   $array = array($idE,$date_rembolso,$date);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras >= ? AND Fecha_Compras < ?";
  }
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_ProveedorRembolso_Remaster($idE,$date_SI,$date_now){
  if( $date_SI == 0 ) {
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  < ?";
  }
  else{
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  >= ? AND Fecha_Compras < ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_RembolsoFondo_Remaste($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT Rembolso FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Obs_Rem($idE,$date){
  $row = null;
  $array = array($idE,$date);
  $query = "SELECT Observaciones FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Propina_SI($idE,$date1,$date2,$idC){
  $row = null;
  if ( !isset($date1) ) {
   $array = array($idC,$idE,$date2);
   $query =
   "SELECT
   SF_Categoria
   FROM
   hgpqgijw_finanzas.retiros_categoria,
   hgpqgijw_finanzas.retiros_venta
   WHERE
   id_RetiroEfectivo = id_RetiroEfectivo
   AND id_Categoria = ?
   AND id_UDN = ?
   AND Fecha_Retiro < ?";
  }
  else {
   $array = array($idC,$idE,$date1,$date2);
   $query =
   "SELECT
   SF_Categoria
   FROM
   hgpqgijw_finanzas.retiros_categoria,
   hgpqgijw_finanzas.retiros_venta
   WHERE
   id_RetiroEfectivo = id_RetiroEfectivo
   AND id_Categoria = ?
   AND id_UDN = ?
   AND Fecha_Retiro >= ?
   AND Fecha_Retiro < ?";
  }
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Retiro_PropActual($idE,$date2,$idC){
  $row = null;
  $array = array($idC,$idE,$date2);
  $query =
  "SELECT
  Retiro_Categoria
  FROM
  hgpqgijw_finanzas.retiros_categoria,
  hgpqgijw_finanzas.retiros_venta
  WHERE
  id_RetiroEfectivo = id_RetiroEfectivo
  AND id_Categoria = ?
  AND id_UDN = ?
  AND Fecha_Retiro = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Efectivo_Anterior($idE,$date1,$date2){
  if ( !isset($date1) ) {
   $array = array($idE,$date2);
   $query =
   "SELECT
   ROUND(SUM(Monto),2)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.subcategoria
   WHERE
   id_Categoria = idCategoria
   AND idSubcategoria = id_Subcategoria
   AND idVentasBit = id_Bitacora
   AND id_Folio = idFolio
   AND id_FormasPago = 1
   AND id_Categoria != 9
   AND id_Categoria != 10
   AND folio.id_UDN = ?
   AND Fecha < ?";
  }
  else {
   $array = array($idE,$date1,$date2);
   $query =
   "SELECT
   ROUND(SUM(Monto),2)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.subcategoria
   WHERE
   id_Categoria = idCategoria
   AND idSubcategoria = id_Subcategoria
   AND idVentasBit = id_Bitacora
   AND id_Folio = idFolio
   AND id_FormasPago = 1
   AND id_Categoria != 9
   AND id_Categoria != 10
   AND folio.id_UDN = ?
   AND Fecha >= ?
   AND Fecha < ?";
  }
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Efectivo_Actual($idE,$date2){
  $array = array($idE,$date2);
  $query =
  "SELECT
  ROUND(SUM(Monto),2)
  FROM
  hgpqgijw_finanzas.bitacora_ventas,
  hgpqgijw_finanzas.bitacora_formaspago,
  hgpqgijw_finanzas.folio,
  hgpqgijw_finanzas.categoria,
  hgpqgijw_finanzas.subcategoria
  WHERE
  id_Categoria = idCategoria
  AND idSubcategoria = id_Subcategoria
  AND idVentasBit = id_Bitacora
  AND id_Folio = idFolio
  AND id_FormasPago = 1
  AND folio.id_UDN = ?
  AND Fecha = ?
  AND id_Categoria != 9
  AND id_Categoria != 10";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Efectivo_Retiro($idE,$date){
  $array = array($idE,$date);
  $query =
  "SELECT
  Retiro_Efectivo
  FROM
  hgpqgijw_finanzas.retiros_venta
  WHERE
  id_UDN = ?
  AND Fecha_Retiro = ?
  ORDER BY
  idRetiroVenta DESC
  LIMIT 1";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Propina_Hoy($idR,$idC){
  $array = array($idR,$idC);
  $query =
  "SELECT
  SF_Categoria,
  Retiro_Categoria
  FROM
  retiros_categoria
  WHERE
  id_RetiroEfectivo = ?
  AND id_Categoria = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $sql;
 }
 function Select_Propina_Actual($idE,$date,$idC){
  $array = array($idE,$date,$idC);
  $query =
  "SELECT
  ROUND(SUM(Monto), 2)
  FROM
  hgpqgijw_finanzas.bitacora_ventas,
  hgpqgijw_finanzas.bitacora_formaspago,
  hgpqgijw_finanzas.folio,
  hgpqgijw_finanzas.categoria,
  hgpqgijw_finanzas.subcategoria
  WHERE
  id_Categoria = idCategoria
  AND idSubcategoria = id_Subcategoria
  AND idVentasBit = id_Bitacora
  AND id_Folio = idFolio
  AND id_FormasPago = 1
  AND folio.id_UDN = ?
  AND Fecha = ?
  AND id_Categoria = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Propina_Anterior($idE,$date1,$date2,$idC) {
  $array = array();
  $query = "";
  if ( !isset($date1) ) {
   $array = array($idC,$idE,$date2);
   $query =
   "SELECT
   ROUND(SUM(Monto),2)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.subcategoria
   WHERE
   id_Categoria = idCategoria
   AND idSubcategoria = id_Subcategoria
   AND idVentasBit = id_Bitacora
   AND id_Folio = idFolio
   AND id_FormasPago = 1
   AND id_Categoria = ?
   AND folio.id_UDN = ?
   AND Fecha < ?";
  }
  else {
   $array = array($idC,$idE,$date1,$date2);
   $query =
   "SELECT
   ROUND(SUM(Monto),2)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.subcategoria
   WHERE
   id_Categoria = idCategoria
   AND idSubcategoria = id_Subcategoria
   AND idVentasBit = id_Bitacora
   AND id_Folio = idFolio
   AND id_FormasPago = 1
   AND id_Categoria = ?
   AND folio.id_UDN = ?
   AND Fecha >= ?
   AND Fecha < ?";
  }
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Retiro_Venta($array) {
  $query = "INSERT INTO hgpqgijw_finanzas.retiros_venta (id_UDN,SI_Total,Retiro_Total,SF_Total,SI_EFectivo,Retiro_Efectivo,SF_Efectivo,Fecha_Retiro) VALUES (?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }
 function Select_IdRetiroVenta($array){
  $row = null;
  $query = "SELECT idRetiroVenta FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Retiro_Categoria($array){
  $query = "INSERT INTO hgpqgijw_finanzas.retiros_categoria (id_RetiroEfectivo,id_Categoria,SI_Categoria,Retiro_Categoria,SF_Categoria) VALUES (?,?,?,?,?)";
  $this->_DIU($query,$array);
 }
 function Insert_Reembolso($array){
  $query = "INSERT INTO hgpqgijw_finanzas.retiros (id_UDN,Gasto_Fondo,Anticipos,Pagos_Proveedor,SI,Rembolso,SF,Fecha_Rembolso,Observaciones) VALUES (?,?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }
 function Select_Zona(){
  $query = "SELECT idzona,zona FROM hgpqgijw_usuarios.zona";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Equipo(){
  $query = "SELECT Nombre_Equipo FROM hgpqgijw_operacion.mtto_almacen_equipos";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_idEquipo($name){
  $array = array($name);
  $query = "SELECT idEquipo FROM hgpqgijw_operacion.mtto_almacen_equipos WHERE Nombre_Equipo = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Equipo($name){
  $array = array($name);
  $query = "INSERT INTO hgpqgijw_operacion.mtto_almacen_equipos (Nombre_Equipo) VALUES (?)";
  $this->_DIU($query,$array);
 }
 function Select_Count_Requisicion_Temp(){
  $query = "SELECT COUNT(*) FROM hgpqgijw_operacion.tb_requisicion WHERE id_Requisicion IS NULL";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  if ( !isset($row[0]) || $row[0] == 0 ) { $row[0] = '--'; }
  return $row[0];
 }
 function Select_Requisicion_Temp(){
  $query = "SELECT idTbReq,Nombre_Equipo,Destino,Cantidad,Presentacion,id_Justificacion FROM hgpqgijw_operacion.tb_requisicion,hgpqgijw_operacion.mtto_almacen_equipos WHERE idEquipo = id_Equipo AND id_Requisicion IS NULL";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Detalle_Almacen($id){
  $array = array($id);
  $query = "SELECT Nombre_Equipo,nombreCategoria,Costo FROM hgpqgijw_operacion.mtto_almacen,hgpqgijw_operacion.mtto_almacen_equipos,hgpqgijw_operacion.mtto_categoria WHERE id_categoria = idcategoria AND Equipo = idEquipo AND idAlmacen = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Equipo_Requisicion($id) {
  $array = array($id);
  $query = "SELECT id_Equipo FROM hgpqgijw_operacion.tb_requisicion WHERE id_id_Equipo = ? AND id_Requisicion IS NULL";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Requisicion_Temp($id) {
  $array = array($id);
  $query = "INSERT INTO hgpqgijw_operacion.tb_requisicion (id_Equipo) VALUES (?)";
  $this->_DIU($query,$array);
 }
 function Delete_ReqTemp($idA) {
  $array = array($idA);
  $query = "DELETE FROM hgpqgijw_operacion.tb_requisicion WHERE idTbReq = ?";
  $this->_DIU($query,$array);
 }
 function Insert_Requisicion($array){
  $query  = "INSERT INTO hgpqgijw_operacion.requisicion (Folio,Fecha_Req,id_Zona,Observaciones) VALUES (?,?,?,?)";
  $this->_DIU($query,$array);

  $query1 = "SELECT idRequisicion FROM hgpqgijw_operacion.requisicion ORDER BY idRequisicion DESC LIMIT 1";
  $sql = $this->_Select($query1,null);
  foreach($sql as $row);

  $array2 = array($row[0]);
  $query2 = "UPDATE hgpqgijw_operacion.tb_requisicion SET id_Requisicion = ? WHERE id_Requisicion IS NULL";
  $this->_DIU($query2,$array2);

 }
 function Update_Cantidad_TbRequisicion($array) {
  $query = "UPDATE hgpqgijw_operacion.tb_requisicion SET Cantidad = ? WHERE idTbReq = ?";
  $this->_DIU($query,$array);
 }
 function Update_Presentacion_TbRequisicion($array) {
  $query = "UPDATE hgpqgijw_operacion.tb_requisicion SET Presentacion = ? WHERE idTbReq = ?";
  $this->_DIU($query,$array);
 }
 function Update_Destino_TbRequisicion($array) {
  $query = "UPDATE hgpqgijw_operacion.tb_requisicion SET Destino = ? WHERE idTbReq = ?";
  $this->_DIU($query,$array);
 }
 function Select_ComboBox_Justificacion(){
  $query = "SELECT idJustificacion,Justificacion FROM hgpqgijw_operacion.justificacion";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Update_Justificacion_TbRequisicion($array){
  $query = "UPDATE hgpqgijw_operacion.tb_requisicion SET id_Justificacion = ? WHERE idTbReq = ?";
  $this->_DIU($query,$array);
 }
 function Select_Name_CB($id){
  $array = array($id); $row = null;
  $query = "SELECT Justificacion FROM hgpqgijw_operacion.justificacion WHERE idJustificacion = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Cont_Requisicion_actual($opc){
  $row = null;
  $query = "";
  switch ($opc) {
   case 1://CANTIDAD
   $query = "SELECT COUNT(Cantidad) FROM hgpqgijw_operacion.tb_requisicion WHERE id_Requisicion IS NULL";
   break;
   case 2://DESTINO
   $query = "SELECT COUNT(Destino) FROM hgpqgijw_operacion.tb_requisicion WHERE id_Requisicion IS NULL";
   break;
   case 3://JUSTIFICACION
   $query = "SELECT COUNT(id_Justificacion) FROM hgpqgijw_operacion.tb_requisicion WHERE id_Requisicion IS NULL";
   break;
   default://TODOS
   $query = "SELECT COUNT(*) FROM hgpqgijw_operacion.tb_requisicion WHERE id_Requisicion IS NULL";
   break;
  }
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Cont_requsicion($mes,$year){
  $array = array($mes,$year); $row = null;
  $query = "SELECT COUNT(*) FROM hgpqgijw_operacion.requisicion,hgpqgijw_usuarios.zona WHERE id_Zona = idzona AND MONTH(Fecha_Req) = ? AND YEAR(Fecha_Req) = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Tb_Requisiciones($mes,$year,$limit,$Lotes){
  $array = array($mes,$year);
  $query = "SELECT idRequisicion,zona,Folio,Fecha_Req FROM hgpqgijw_operacion.requisicion,hgpqgijw_usuarios.zona WHERE id_Zona = idzona AND MONTH(Fecha_Req) = ? AND YEAR(Fecha_Req) = ? ORDER BY Folio DESC LIMIT $limit, $Lotes";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Requisicion_id($id) {
  $array = array($id);
  $query = "SELECT Fecha_Req,Folio,zona,Observaciones FROM hgpqgijw_usuarios.zona,hgpqgijw_operacion.requisicion WHERE id_Zona = idzona AND idRequisicion = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Select_Count_TbRequisicion_id($id) {
  $array = array($id);
  $query = "SELECT COUNT(*) FROM hgpqgijw_operacion.tb_requisicion WHERE id_Requisicion = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  return $row[0];
 }
 function Select_TbRequisicion_id($id) {
  $array = array($id);
  $query = "SELECT Nombre_Equipo,Cantidad,Presentacion,Destino,Justificacion FROM hgpqgijw_operacion.tb_requisicion,hgpqgijw_operacion.mtto_almacen_equipos,hgpqgijw_operacion.justificacion WHERE idEquipo = id_Equipo AND idJustificacion = id_Justificacion AND id_Requisicion = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Folio_Req() {
  $query = "SELECT Folio FROM hgpqgijw_operacion.requisicion ORDER BY Folio DESC LIMIT 1";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);

  if ( !isset($row[0]) ) { $row[0] = 0; }

  $fol = $row[0] + 1;
  return $fol;
 }
 function convert_folio($fol,$letra) {
  if ( $fol > 999 ) {
   $fol = $letra.''.$fol;
  }
  else if ( $fol > 99 ) {
   $fol = $letra.'0'.$fol;
  }
  else if ( $fol > 9) {
   $fol = $letra.'00'.$fol;
  }
  else {
   $fol = $letra.'000'.$fol;
  }
  return $fol;
 }
 function Meses($mes) {
  switch ($mes) {
   case 1: return 'ENERO'; break;
   case 2: return 'FEBRERO'; break;
   case 3: return 'MARZO'; break;
   case 4: return 'ABRIL'; break;
   case 5: return 'MAYO'; break;
   case 6: return 'JUNIO'; break;
   case 7: return 'JULIO'; break;
   case 8: return 'AGOSTO'; break;
   case 9: return 'SEPTIEMBRE'; break;
   case 10: return 'OCTUBRE'; break;
   case 11: return 'NOVIEMBRE'; break;
   case 12: return 'DICIEMBRE'; break;
  }
 }
 function Select_Mes_Requisiciones() {
  $query = "SELECT Date_format(Fecha_Req,'%m') as mes FROM hgpqgijw_operacion.requisicion GROUP BY mes";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Anio_Requisiciones() {
  $query = "SELECT Date_format(Fecha_Req,'%Y') as anio FROM hgpqgijw_operacion.requisicion GROUP BY anio";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Ultimo_idReq() {
  $query = "SELECT idRequisicion FROM hgpqgijw_operacion.requisicion ORDER BY idRequisicion DESC LIMIT 1";
  $sql = $this->_Select($query,null);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Folio_Now($date){
  $array = array($date);
  $query = "SELECT idFolio FROM hgpqgijw_finanzas.folio WHERE Fecha = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 'disabled'; }
  return $row[0];
 }
 function Select_Terminal(){
  $query = "SELECT idTerminal,TCodigo FROM hgpqgijw_finanzas.terminal";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Tipo_Terminal(){
  $query = "SELECT idTipoTerminal,TTCodigo FROM hgpqgijw_finanzas.tipo_terminal";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_idTC($folio){
  $array = array($folio);
  $query = "SELECT idTC FROM hgpqgijw_finanzas.tc WHERE id_Folio = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Save_TC($array){
  $query = "INSERT INTO hgpqgijw_finanzas.tc (Monto,id_Terminal,id_TipoTerminal,Concepto,Especificacion,Autorizacion,Observaciones,id_Folio,Cliente) VALUES (?,?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array);
 }
 function Update_TC($array){
  $query = "UPDATE hgpqgijw_finanzas.tc SET Monto = ?,id_Terminal = ?,id_TipoTerminal = ?,Concepto = ?,Especificacion = ?,Autorizacion = ?,Observaciones = ?,Cliente = ? WHERE idTC = ?";
  $this->_DIU($query,$array);
 }
 function Select_TC_Data($date){
  $array = array($date);
  $query = "SELECT Monto,TCodigo,TTCodigo,Concepto,Especificacion,Cliente,Autorizacion,Observaciones,Fecha,idTC FROM hgpqgijw_finanzas.tc,hgpqgijw_finanzas.terminal,hgpqgijw_finanzas.tipo_terminal,hgpqgijw_finanzas.folio WHERE idTipoTerminal = id_TipoTerminal AND idTerminal = id_Terminal AND id_Folio = idFolio  AND Fecha = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }
 function Delete_FROM($idTC){
  $array = array($idTC);
  $query = "DELETE FROM hgpqgijw_finanzas.tc WHERE idTC = ?";
  $this->_DIU($query,$array);
 }

 /*-----------------------------------*/
 /* ** DESARROLLO MOD
 /*-----------------------------------*/
 function Select_formaspago_by_categoria($array){
  $query = "SELECT idFormas_Pago,FormasPago FROM hgpqgijw_finanzas.formas_pago WHERE grupo = ?";
  $sql = $this->_Select($query,$array);
  return $sql;
 }

 function CIERRE_DIARIO($array){
  $query = "UPDATE hgpqgijw_finanzas.folio SET encargado = ? WHERE idFolio = ?";
  $this->_DIU($query,$array);
 }

 function actualizar_precios($array){
  $query =
  "UPDATE hgpqgijw_finanzas.subcategoria
  SET tarifa = ?
  WHERE idSubcategoria = ?";
  $this->_DIU($query,$array);
 }



}
?>
