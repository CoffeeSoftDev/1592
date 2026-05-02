<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class Catalogo extends CRUD
{
 function Select_Movimientos() {
  $query = "SELECT idTMovimiento,TipoMovimiento FROM hgpqgijw_finanzas.tipo_movimiento";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Impuestos() {
  $query = "SELECT idImpuesto,Impuesto FROM hgpqgijw_finanzas.impuestos WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_Cont_Impuestos(){
  $query = "SELECT Count(*) FROM hgpqgijw_finanzas.impuestos WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Categorias() {
  $query = "SELECT idCategoria,Categoria FROM hgpqgijw_finanzas.categoria WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  return $sql;
 }
 function Select_idImpuesto($name){
  $array = array($name);
  $query = "SELECT idImpuesto FROM hgpqgijw_finanzas.impuestos WHERE Impuesto = ?";
  $sql = $this->_Select($query,$array);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Impuestos($array){
  $query = "INSERT INTO hgpqgijw_finanzas.impuestos (Impuesto,Valor,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array);
 }
 function Update_StadoImpuestos($stado,$id){
  $array = array($stado,$id);
  $query = "UPDATE hgpqgijw_finanzas.impuestos SET Stado = ? WHERE idImpuesto = ?";
  $this->_DIU($query,$array);
 }

 //CATEGORIA
 function Select_Cont_Categoria(){
  $query = "SELECT Count(*) FROM hgpqgijw_finanzas.categoria WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_idCategoria($name){
  $array = array($name);
  $query = "SELECT idCategoria FROM hgpqgijw_finanzas.categoria WHERE Categoria = ?";
  $sql = $this->_Select($query,$array);
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Categoria($array){
  $query = "INSERT INTO hgpqgijw_finanzas.categoria (Categoria,id_TMovimiento,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array);
 }
 function Insert_Categoria_Impuestos($array){
  $query = "INSERT INTO hgpqgijw_finanzas.categoria_impuesto (id_Categoria,id_Impuesto) VALUES (?,?)";
  $this->_DIU($query,$array);
 }
 function Update_Stado_Categoria($Stado,$id){
  $array = array($Stado,$id);
  $query = "UPDATE hgpqgijw_finanzas.categoria SET Stado = ? WHERE idCategoria = ?";
  $this->_DIU($query,$array);
 }

 //SUBCATEGORIA
 function Select_Cont_SubCategoria(){
  $query = "SELECT Count(*) FROM hgpqgijw_finanzas.subcategoria WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_idSubcategoria($subcategoria) {
  $array = array($subcategoria);
  $query = "SELECT idSubcategoria FROM hgpqgijw_finanzas.subcategoria WHERE Subcategoria = ?";
  $sql = $this->_Select($query,$array);
  foreach ( $sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_Subcategoria($array){
  $query = "INSERT INTO hgpqgijw_finanzas.subcategoria (Subcategoria,id_Categoria,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array);
 }
 function Update_Stado_Subcategoria($Stado,$id){
  $array = array($Stado,$id);
  $query = "UPDATE hgpqgijw_finanzas.subcategoria SET Stado = ? WHERE idSubcategoria = ?";
  $this->_DIU($query,$array);
 }



 //FORMA DE PAGO
 function Select_Cont_formapago(){
  $query = "SELECT Count(*) FROM hgpqgijw_finanzas.formas_pago WHERE Stado = 1";
  $sql = $this->_Select($query,null);
  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_idFormasPago($formasPago) {
  $array = array($formasPago);
  $query = "SELECT idFormas_Pago FROM hgpqgijw_finanzas.formas_pago WHERE FormasPago = ?";
  $sql = $this->_Select($query,$array);
  foreach ( $sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Insert_FormasPago($formasPago){
  $array = array($formasPago);
  $query = "INSERT INTO hgpqgijw_finanzas.formas_pago (FormasPago,Stado) VALUES (?,1)";
  $this->_DIU($query,$array);
 }
 function Update_Stado_FormasPago($Stado,$id) {
  $array = array($Stado,$id);
  $query = "UPDATE hgpqgijw_finanzas.formas_pago SET Stado = ? WHERE idFormas_Pago = ?";
  $this->_DIU($query,$array);
 }



 /*-----------------------------------*/
 /* >> DESTINO
 /*-----------------------------------*/

 function AgregarDestino($array,$idE){

  $query ="INSERT into hgpqgijw_finanzas.insumos_clase(Name_IC) VALUES(?)";

  $this->_DIU($query,$array);

  // Obtener insumo_clase
  $sql="SELECT * FROM hgpqgijw_finanzas.insumos_clase Where Name_IC = ?";

  $ps	=	$this->_Select($sql,$array);

  foreach ($ps as $key);
  $data = array($idE,$key[0],1);
  $query ="INSERT INTO
  hgpqgijw_finanzas.insumos_udn
  (id_UDN,id_IC,Stado) values(?,?,?)";

  $this->_DIU($query,$data);
 }

 function CountDestino(){
  $query = "
  SELECT
  Count(*)
  FROM
  hgpqgijw_finanzas.insumos_udn
  INNER JOIN hgpqgijw_finanzas.insumos_clase ON
  insumos_udn.id_IC = insumos_clase.idIC
  WHERE Stado = 1 ";



  $sql = $this->_Select($query,null);

  foreach ($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function UpdateDestino($Stado,$id) {
  $array = array($Stado,$id);

  $query = "UPDATE
  hgpqgijw_finanzas.insumos_udn
  SET Stado = ?
  WHERE id_IC = ?";

  $this->_DIU($query,$array);
 }

 function	verDestino($array){
  $sql="SELECT

  insumos_clase.Name_IC,
  idIC
  FROM
  hgpqgijw_finanzas.insumos_udn
  INNER JOIN hgpqgijw_finanzas.insumos_clase ON
  insumos_udn.id_IC = insumos_clase.idIC
  WHERE Stado = 1 and id_UDN = ? ORDER BY idUI DESC";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function	_UPDATE_REGISTRO($array,$campo,$tb){
  $sql="
  UPDATE hgpqgijw_finanzas.".$tb."
  set name_".$campo." = ?
  WHERE
  id".$campo." = ? ;
  ";

  $ps	=	$this->_DIU($sql,$array,"1");
  return	$sql;
 }

 /*-----------------------------------*/
 /* >> DESTINO
 /*-----------------------------------*/

 function	verCuentas($array){
  $sql="SELECT

  Name_CG,
  idCG
  FROM
  hgpqgijw_finanzas.gasto_clase";
  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function _INSERTAR_REGISTRO($array){

  $query ="INSERT into hgpqgijw_finanzas.gasto_clase(Name_CG,Stado_CG) VALUES(?,?)";

  $this->_DIU($query,$array);
 }
 /*-----------------------------------*/
 /* >>  pax
 /*-----------------------------------*/

 function	ExistePax($array){
  $opc = 0;
  $sql ="SELECT
  bitacora_ventas.pax,
  folio.Folio,
  folio.Fecha,
  bitacora_ventas.id_Subcategoria
  FROM
  hgpqgijw_finanzas.bitacora_ventas
  INNER JOIN hgpqgijw_finanzas.folio ON bitacora_ventas.id_Folio = folio.idFolio
  WHERE Fecha = '2016-01-01'  and id_Subcategoria = 18";
  $ps 	=	$this->_Select($sql,$array);

  $opc = count($ps);

  return	$opc;
 }
}

?>
