<?php
include_once("_CRUD.php");
Class Finanzas extends CRUD
{

/*-----------------------------------*/
/*		MODIFICACIONES UDN **
/*-----------------------------------*/
function Nuevo_folio($array){

  $query = "
  INSERT INTO
  hgpqgijw_finanzas.folio (Folio,Fecha,id_UDN,Observacion)
  VALUES
  (?,?,?,?)

  ";

  $this->_DIU($query,$array,"1");
 }

function ContarFolio(){
  $num   = 0;

  $query = "SELECT count(*) FROM hgpqgijw_finanzas.folio ";

  $sql   = $this->_Select($query,null,"5");
  foreach($sql as $row){
    $num = $row[0];
  }
  
   $num = $num + 1;
   return $num;
}

function ExisteFolio($array){
 $Existe = 0;
 $query = "SELECT * FROM hgpqgijw_finanzas.folio WHERE fecha = ? ";
 $sql   = $this->_Select($query,$array,"5");
 foreach($sql as $row){
    $Existe = 1;
  }
  return $Existe;
}


/*-----------------------------------*/
/*		MODIFICACIONES UDN **
/*-----------------------------------*/

function _SUMA_GASTOS($array,$data){
 $row = null;
 $bd  = "hgpqgijw_finanzas.";

 $new ='';
 $new = array($array[0],$array[1],$array[2]);

 $opc="AND id_CG = ? ";
 if($array[2]==0){
  $opc="";
  $new = array($array[0],$array[1]);
 }

 $query =
 "SELECT
 ROUND(SUM(Gasto),2) as Saldo
 FROM ".$bd."compras,
 ".$bd."gastos_udn,
 ".$bd."gastos
 WHERE
 compras.id_UDN = ?
 AND idUG = id_UG
 AND id_Gastos = idGastos
 ".$data."
 AND Fecha_Compras = ?
 ".$opc." ";
 $sql = $this->_Select($query,$new,"5");
 foreach($sql as $row);
 if(!isset($row[0])){ $row[0] = 0; }
 return $row[0];
}

function _Data_Hoy_Gastos_Categoria($array,$data){
 $row = null;
 $bd  = "hgpqgijw_finanzas.";
 $new = array($array[0],$array[1],$array[2]);

 $opc="AND id_CG = ? ";
 if($array[2]==0){
  $opc="";
  $new = array($array[0],$array[1]);
 }
 $query =
 "SELECT
 ROUND(SUM(Gasto),2)
 FROM ".$bd."compras,
 ".$bd."gastos_udn,
 ".$bd."gastos
 WHERE  id_UI = ?
 AND idUG = id_UG
 AND id_Gastos = idGastos
  ".$data."
 AND Fecha_Compras = ? ".$opc."
 and factura is null";
 $sql = $this->_Select($query,$new,"5");
 foreach($sql as $row);
 if(!isset($row[0])){ $row[0] = 0; }
 return $row[0];
}

function _Data_Hoy_Gastos($array,$data){

 $row = null;
 $new='';
 $new = array($array[0],$array[1],$array[2]);

 $opc="AND id_CG = ? ";
 if($array[2]==0){
  $opc="";
  $new = array($array[0],$array[1]);
 }


 $bd  = "hgpqgijw_finanzas.";
 $query = "SELECT ROUND(SUM(Gasto),2),Observacion,idCompras
 FROM
 ".$bd."compras,
 ".$bd."gastos_udn,
 ".$bd."gastos
 WHERE  id_UG = ?
 AND idUG = id_UG
 AND id_Gastos = idGastos
  ".$data."
 AND Fecha_Compras = ? ".$opc." ";

 $sql = $this->_Select($query,$new,"5");
 foreach($sql as $row);


 if(!isset($row[0])){ $row[0] = 0; }
 // return $row[0];

 return $row;
}

function ver_check_list($array){
  $sql = 'SELECT
  check_name,
  Archivo,
  Fecha,
  Hora,
  Ruta,
  motivo
  FROM
  hgpqgijw_finanzas.sobres
  INNER JOIN hgpqgijw_finanzas.check_list ON sobres.id_checklist = check_list.idcheck
  WHERE id_categoria =? AND
  Fecha between ? AND ? ';
  $ps  = $this->_Select($sql,$array,"5");

  return	$ps;
 }



/*-----------------------------------*/
/*		Compras
/*-----------------------------------*/
function verGastosCategoria($array){
 $row = null;
 $bd  = "hgpqgijw_finanzas.";
 $new = array($array[0],$array[1],$array[2]);

 $opc="AND id_CG = ? ";
 if($array[2]==0){
  $opc="";
  $new = array($array[0],$array[1]);
 }
 $query = "SELECT ROUND(SUM(Gasto),2) FROM ".$bd."compras WHERE  id_UI = ?  AND Fecha_Compras = ? ".$opc."
 and factura is not null
 ";
 $sql = $this->_Select($query,$new,"5");
 foreach($sql as $row);
 if(!isset($row[0])){ $row[0] = 0; }
 return $row[0];
}

/*-----------------------------------*/
/*		Utilerias
/*-----------------------------------*/

 function verFormasPago(){
  $array = null;

  $query = "SELECT * from  hgpqgijw_finanzas.categoria_proveedor ";
  $sql = $this->_Select($query,$array,"5");
  return $sql;

 }

 function verProveedores($id){
  $OPC="";
  $array = null;
  if ($id!=0) {
   $OPC=" and idProveedor = ?";
   $array = array($id);
  }

  $query = "SELECT
  *
  FROM
  hgpqgijw_finanzas.proveedor
  INNER JOIN hgpqgijw_finanzas.categoria_proveedor
  ON id_categoria = idcategoria
  WHERE EstadoProveedor=1 ".$OPC;
  $sql = $this->_Select($query,$array,"5");
  return $sql;

 }

 function updateProveedores($array){

  $query = "
  UPDATE hgpqgijw_finanzas.proveedor
  SET
  Name_Proveedor = ?,
  direccion      = ?,
  Contacto       = ?,
  telefono       = ?,
  id_categoria   = ?,
  rfc            = ?,
  FormasPago     = ?
  WHERE
  idProveedor   = ? ";

  $this->_DIU($query,$array,"5");


 }

 function autorizarBaja($array){

  $query = "
  UPDATE hgpqgijw_finanzas.proveedor
  SET
  EstadoProveedor = ?
  WHERE
  idProveedor   = ? ";

  $this->_DIU($query,$array,"5");


 }

 function insertProveedor($array){

  $query = "
  INSERT INTO
  hgpqgijw_finanzas.proveedor (Name_Proveedor,direccion,Contacto,telefono,id_categoria,rfc,FormasPago )
  VALUES
  (?,?,?,?,?,?,?)

  ";

  $this->_DIU($query,$array,"1");
 }

 function ExisteProveedor($idE){
  $array = array($idE);

  $query = "SELECT idProveedor
  FROM hgpqgijw_finanzas.proveedor WHERE Name_Proveedor = ? ";
  $sql = $this->_Select($query,$array,"5");

  return $sql;
 }

 /*==========================================
 *		CARGA INICIAL
 ============================================*/

 function Select_Retiro($idE){
  $array = array($idE);
  $query = "SELECT idRetiro,ROUND(SF,2),Fecha_Rembolso
  FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? ORDER BY idRetiro desc LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }

 function Select_SUM_Gastos_Fondo($idE,$date_SI,$date2){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date2);
   // $array = array($idE,$date_SI);

   $query = "SELECT ROUND(SUM(Gasto),2)
   FROM hgpqgijw_finanzas.compras
   WHERE id_UG IS NOT NULL AND id_CG = 3
   AND id_UDN = ? AND Fecha_Compras >= ? AND Fecha_Compras <= ?";
  }
  else{
   $array = array($idE,$date2);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras <= ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 function Select_Pago_Proveedor_Fondo($idE,$date_SI,$date_now){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  >= ? AND Fecha_Compras < ?";
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

 // ** AUN NO ESTA CONTEMPLADO
 function Select_SUM_Anticipo_Fondo($idE,$date_SI,$date_now){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') >= ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') <= ?";
  }
  else{
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') <= ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 // GASTOS -----------------------------------
 function Select_Group_Insumo_Remaster($idE){
  $bd  = "hgpqgijw_finanzas.";

  $array = array($idE);
  $query = "SELECT idUI,Name_IC FROM ".$bd."insumos_clase,".$bd."insumos_udn WHERE id_IC = idIC AND Stado = 1 AND id_UDN = ? ORDER BY Name_IC asc";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }

 function Select_Suma_Gastos($array){
  $row = null;
  $bd  = "hgpqgijw_finanzas.";

  $new='';
  $new = array($array[0],$array[1],$array[2]);

  $opc="AND id_CG = ? ";
  if($array[2]==0){
   $opc="";
   $new = array($array[0],$array[1]);
  }

  $query = "SELECT ROUND(SUM(Gasto),2) as Saldo FROM ".$bd."compras WHERE  id_UDN = ? AND Fecha_Compras = ?  ".$opc." ";
  $sql = $this->_Select($query,$new,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function Select_Data_Hoy_Gastos($array){

  $row = null;
  $new='';
  $new = array($array[0],$array[1],$array[2]);

  $opc="AND id_CG = ? ";
  if($array[2]==0){
   $opc="";
   $new = array($array[0],$array[1]);
  }


  $bd  = "hgpqgijw_finanzas.";
  $query = "SELECT ROUND(SUM(Gasto),2),Observacion FROM ".$bd."compras WHERE  id_UG = ?  AND Fecha_Compras = ? ".$opc." ";

  $sql = $this->_Select($query,$new,"5");
  foreach($sql as $row);


  if(!isset($row[0])){ $row[0] = 0; }
  // return $row[0];

  return $row;
 }


 function Select_Data_Hoy_Gastos_Categoria($array){
  $row = null;
  $bd  = "hgpqgijw_finanzas.";
  $new = array($array[0],$array[1],$array[2]);

  $opc="AND id_CG = ? ";
  if($array[2]==0){
   $opc="";
   $new = array($array[0],$array[1]);
  }
  $query = "SELECT ROUND(SUM(Gasto),2) FROM ".$bd."compras WHERE  id_UI = ?  AND Fecha_Compras = ? ".$opc." and factura is null";
  $sql = $this->_Select($query,$new,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function Select_Group_Gastos(){
  $bd  = "hgpqgijw_finanzas.";
  $query = "SELECT idCG,Name_CG FROM ".$bd."gasto_clase";
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }

 // PROVEEDOR -----------------------------------

 function Select_Suma_Total_Proveedor_Pagos($idE,$date1,$date2){
  $row = null; $array = array();
  $bd  = "hgpqgijw_finanzas.";
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query =
   "SELECT ROUND(SUM(Pago),2)
   FROM
   hgpqgijw_finanzas.compras
   WHERE id_UP IS NOT NULL AND id_UDN = ? AND Fecha_Compras = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }


 function Select_Suma_Total_Proveedor($idP,$date1,$date2){
  $row_p = null; $row_d = null; $array = array();
  $bd  = "hgpqgijw_finanzas.";

  if($date1 == $date2){
   $array = array($idP,$date1);
   $query_p =
   "SELECT ROUND(SUM(Pago),2)
   FROM ".$bd."compras
   WHERE id_UP IS NOT NULL
   AND id_UP = ? AND Fecha_Compras = ?";

   $query_d =
   "SELECT ROUND(SUM(Gasto),2)
   FROM ".$bd."compras
   WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UP = ? AND Fecha_Compras = ?";
  }

  else{
   $array = array($idP,$date1,$date2);
   $query_p = "SELECT ROUND(SUM(Pago),2) FROM ".$bd."compras WHERE id_UP IS NOT NULL AND id_UP = ? AND Fecha_Compras BETWEEN ? AND ?";
   $query_d = "SELECT ROUND(SUM(Gasto),2) FROM ".$bd."compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UP = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql_p = $this->_Select($query_p,$array,"5");
  foreach($sql_p as $row_p);
  if(!isset($row_p[0])){ $row_p[0] = 0; }


  $sql_d = $this->_Select($query_d,$array,"5");
  foreach($sql_d as $row_d);
  if(!isset($row_d[0])){ $row_d[0] = 0; }


  $total = $row_d[0] - $row_p[0];
  return $total;

 }

 function Select_Suma_Hoy_Proveedor_Gastos($array){
  $row = null;
  $bd  = "hgpqgijw_finanzas.";
  $query = "SELECT ROUND(SUM(Gasto),2) FROM ".$bd."compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function Select_Suma_Hoy_Proveedor_Pagos($array){
  $row = null;
  $bd  = "hgpqgijw_finanzas.";
  $query = "SELECT ROUND(SUM(Pago),2) FROM ".$bd."compras WHERE id_UP IS NOT NULL AND id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function Select_Hoy_Proveedor_Gastos($array){
  $row = null;
  $bd  = "hgpqgijw_finanzas.";
  $query = "SELECT ROUND(SUM(Gasto),2) FROM ".$bd."compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UP = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function Select_Hoy_Proveedor_Pagos($array){
  $row = null;
  $bd  = "hgpqgijw_finanzas.";
  $query = "SELECT ROUND(SUM(Pago),2) FROM ".$bd."compras WHERE id_UP IS NOT NULL AND id_UP = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function Select_Proveedor_Total($idUP,$date2){
  $array = array($idUP,$date2);
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE Status = 2 AND id_CG = 2 AND id_UP = ? AND Fecha_Compras <= ?";
  $sql = $this->_Select($query,$array,"5"); foreach($sql as $row);
  if(!isset($row[0])){ $deuda_pagada_proveedor = 0; } else{ $deuda_pagada_proveedor = $row[0]; }//deudas pagados de proveedor


  $row1 = null;
  $query1 = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE Status = 1 AND id_CG = 2 AND id_UP = ? AND Fecha_Compras <= ?";
  $sql1 = $this->_Select($query1,$array,"5"); foreach($sql1 as $row1);
  if(!isset($row1[0])){ $deuda_proveedor = 0; }else{ $deuda_proveedor = $row1[0]; }//deuda proveedor

  $row2 = null;

  $query2 = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE Status = 2 AND id_UP = ? AND Fecha_Compras <= ?";
  $sql2 = $this->_Select($query2,$array,"5"); foreach($sql2 as $row2);
  if(!isset($row2[0])){ $pago_proveedor = 0; } else{ $pago_proveedor = $row2[0]; }//pagos de proveedor

  $pagos = $pago_proveedor - $deuda_pagada_proveedor;
  $si = $deuda_proveedor - $pagos;

  return $si;
 }

 // GASTOS -----------------------------------


 function NOW(){
  $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }

 function Ayer(){
  $query = "SELECT DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }

 function Sunday(){
  $query = "SELECT DATE_SUB(CURDATE(), INTERVAL 2 DAY)";
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }

 function DayWek(){
  $query = "SELECT DAYOFWEEK(NOW());";
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }

 function Insert_Sobres($array) {
  $query = "INSERT INTO hgpqgijw_finanzas.sobres(UDN_Sobre,Ruta,Fecha,Hora,Archivo,Peso,Type_File) VALUES (?,?,?,?,?,?,?)";
  $this -> _DIU($query, $array, "5");
 }
 function Select_Archivos($array) {
  $row = null;
  $query = "SELECT Archivo FROM hgpqgijw_finanzas.sobres WHERE UDN_Sobre = ? AND Ruta = ? AND Archivo = ? ";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }



 //  #NUEVA INFORMACIÓN DE SOBRES

 //Acceso administración
 function Select_Admin($pass){
  $array = array($pass);
  $row = null;
  $query = "SELECT idUsuario FROM usuarios WHERE Nivel = 5 AND Area_Usuario = 4 AND Password = SHA(?)";
  $sql = $this->_Select($query,$array,"1");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_idVenta($ing){
  $array = array($ing);
  $row = null;
  $query = "SELECT idVenta FROM ventas WHERE Name_Venta = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Ventas($ing){
  $array = array($ing);
  $query = "INSERT INTO ventas (Name_Venta) VALUES (?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_UV($array){
  $row = null;
  $query = "SELECT idUV FROM ventas_udn WHERE id_Venta = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_UV($array){
  $query = "INSERT INTO ventas_udn (id_Venta,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Update_UV_Active($array){
  $query = "UPDATE ventas_udn SET Stado = 1 WHERE id_Venta = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UV_Inactive($array){
  $query = "UPDATE ventas_udn SET Stado = 0 WHERE id_Venta = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UV($array){
  $query = "UPDATE ventas_udn SET id_Venta = ?,Stado = 1 WHERE id_Venta = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_idDescuento($desc){
  $array = array($desc);
  $row = null;
  $query = "SELECT idDescuentos FROM hgpqgijw_finanzas.descuentos WHERE Name_Descuentos = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Descuentos($desc){
  $array = array($desc);
  $query = "INSERT INTO descuentos (Name_Descuentos) VALUES (?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_UD($array){
  $row = null;
  $query = "SELECT idUD FROM descuentos_udn WHERE id_Descuentos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_UD($array){
  $query = "INSERT INTO descuentos_udn (id_Descuentos,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Update_UD_Active($array){
  $query = "UPDATE descuentos_udn SET Stado = 1 WHERE id_Descuentos = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UD_Inactive($array){
  $query = "UPDATE descuentos_udn SET Stado = 0 WHERE id_Descuentos = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UD($array){
  $query = "UPDATE descuentos_udn SET id_Descuentos = ?, Stado = 1 WHERE id_Descuentos = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_idImpuesto($imp){
  $array = array($imp);
  $row = null;
  $query = "SELECT idImpuesto FROM impuestos WHERE Porcentaje = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Impuesto($imp){
  $array = array($imp);
  $query = "INSERT INTO impuestos (Porcentaje) VALUES (?)";
  $sql = $this->_Select($query,$array,"5");
 }
 function Select_UI($array){
  $row = null;
  $query = "SELECT idUI FROM impuestos_udn WHERE id_Impuesto = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_UI($array){
  $query = "INSERT INTO impuestos_udn (id_Impuesto,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Update_UI_Active($array){
  $query = "UPDATE impuestos_udn SET Stado = 1 WHERE id_Impuesto = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UI_Inactive($array){
  $query = "UPDATE impuestos_udn SET Stado = 0 WHERE id_Impuesto = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UI($array){
  $query = "UPDATE impuestos_udn SET id_Impuesto = ?,Stado = 1 WHERE id_Impuesto = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_idBanco($ban){
  $array = array($ban);
  $row = null;
  $query = "SELECT idBancos FROM bancos WHERE Name_Bancos = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Banco($ban){
  $array = array($ban);
  $query  = "INSERT INTO bancos (Name_Bancos) VALUES (?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_UB($array){
  $row = null;
  $query = "SELECT idUB FROM bancos_udn WHERE id_Bancos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_UB($array){
  $query = "INSERT INTO bancos_udn (id_Bancos,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Update_UB_Active($array){
  $query = "UPDATE bancos_udn SET Stado = 1 WHERE id_Bancos = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UB_Inactive($array){
  $query = "UPDATE bancos_udn SET Stado = 0 WHERE id_Bancos = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UB($array){
  $query = "UPDATE bancos_udn SET id_Bancos = ?,Stado = 1 WHERE id_Bancos = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_idInsumo($clase){
  $array = array($clase);
  $row = null;
  $query = "SELECT idIC FROM insumos_clase WHERE Name_IC = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Inser_ClaseInsumo($clase){
  $array = array($clase);
  $query = "INSERT INTO insumos_clase (Name_IC) VALUES (?)";
  $this->_DIU($query,$array,"5");
 }
 function Update_ClaseInsumo($array){
  $query = "UPDATE insumos_clase SET Name_IC = ? WHERE idIC = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_UIC($array){
  $row = null;
  $query = "SELECT idUI FROM insumos_udn WHERE id_IC = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_UIC($array){
  $query = "INSERT INTO insumos_udn (id_IC,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Update_UIC_Active($array){
  $query = "UPDATE insumos_udn SET Stado = 1 WHERE id_IC = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UIC_Inactive($array){
  $query = "UPDATE insumos_udn SET Stado = 0 WHERE id_IC = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_UIC($array){
  $query = "UPDATE insumos_udn SET id_IC = ? WHERE id_IC = ? AND id_UDN = ?";
  $this->_DIU($query,$array,"5");
 }


 //Ingresos
 function Select_Ingresos($idUDN) {

  $query = "SELECT idUV,Name_Venta,idVenta FROM ventas,ventas_udn WHERE id_Venta = idVenta AND Stado = 1 AND id_UDN = ".$idUDN;
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Count_Ingresos($idUDN){
  $row = null;
  $query = "SELECT Count(*) FROM ventas,ventas_udn WHERE id_Venta = idVenta AND Stado = 1 AND id_UDN = ".$idUDN;
  $sql = $this->_Select($query,null,"5");
  foreach ($sql as $row);
  return $row[0];
 }
 function Select_Cont_Descuentos($idUDN){
  $row = null;
  $query = "SELECT Count(*) FROM descuentos,descuentos_udn WHERE id_Descuentos = idDescuentos AND Stado = 1 AND  id_UDN = ".$idUDN;
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Cont_Impuestos($idUDN){
  $row = null;
  $query = "SELECT Count(*) FROM impuestos,impuestos_udn WHERE id_Impuesto = idImpuesto AND Stado = 1 AND  id_UDN = ".$idUDN;
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Descuentos($id) {
  $query = "SELECT idUD,Name_Descuentos,idDescuentos FROM descuentos,descuentos_udn WHERE id_Descuentos = idDescuentos AND Stado = 1 AND id_UDN = ".$id;
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Impuestos($id) {
  $query = "SELECT idUI,Porcentaje,idImpuesto FROM impuestos,impuestos_udn WHERE id_Impuesto = idImpuesto AND Stado = 1 AND id_UDN = ".$id;
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Efe_Pro($id) {
  $query = "SELECT idUB,Name_Bancos FROM bancos,bancos_udn WHERE id_Bancos = idBancos AND idBancos < 3 AND Stado = 1 AND id_UDN = ".$id;
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Propina() {
  $query = "SELECT idEfectivo,Name_Efectivo FROM efectivo";
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Bancos($id) {
  $query = "SELECT idUB,Name_Bancos,idBancos FROM bancos,bancos_udn WHERE id_Bancos = idBancos AND Stado = 1 AND id_UDN = ".$id;
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Count_Bancos($id) {
  $row = null;
  $query = "SELECT Count(*) FROM bancos,bancos_udn WHERE id_Bancos = idBancos AND Stado = 1 AND  id_UDN = ".$id;
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Ventas_BV($array){
  $row = null;
  $query = "SELECT idBV FROM venta_bitacora  WHERE id_UV = ? AND Fecha_Venta = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Ventas_BV($V,$idV,$date,$idE){
  if($idE == 9){ $idE = 6; }
  $array = array($idE,$date);
  $query = "SELECT idFolio FROM folio WHERE id_UDN = ? AND Fecha_Folio = ? ORDER BY idFolio desc LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);

  $array = array($V,$idV,$date,$row[0]);
  $query = "INSERT INTO venta_bitacora (Cantidad,id_UV,Fecha_Venta,id_Folio) VALUES (?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Insert_Folio($array){
  $query = "INSERT INTO folio (id_UDN,Fecha_Folio) VALUES (?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_Folio($idE,$date){
  if($idE == 9){ $idE = 6; }
  $row = null; $array = array($idE,$date);
  $query = "SELECT idFolio FROM folio WHERE id_UDN = ? AND Fecha_Folio = ? ORDER BY idFolio desc LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Folio_Remaster($array) {
  $row = null;
  $query = "SELECT idFolio FROM folio WHERE id_UDN = ? AND Fecha_Folio = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Update_Ventas_BV($array){
  $query = "UPDATE venta_bitacora SET Cantidad = ? WHERE id_UV = ? AND Fecha_Venta = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_Ventas_BV($array){
  $query = "DELETE FROM venta_bitacora WHERE id_UV = ? AND Fecha_Venta = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_Descuentos_BD($array){
  $row = null;
  $query = "SELECT idBD FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Descuento_BD($array){
  $query = "INSERT INTO descuentos_bitacora (Cantidad,id_UD,Fecha_Desc) VALUES (?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Update_Descuentos_BD($array){
  $query = "UPDATE descuentos_bitacora SET Cantidad = ? WHERE id_UD = ? AND Fecha_Desc = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_Decuentos_BD($array){
  $query = "DELETE FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_Impuestos_BI($array){
  $row = null;
  $query = "SELECT idBI FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Impuestos_BI($array){
  $query = "INSERT INTO impuestos_bitacora (Cantidad,id_UI,Fecha_Impuesto) VALUES (?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Update_Impuestos_BI($array){
  $query = "UPDATE impuestos_bitacora SET Cantidad = ? WHERE id_UI = ? AND Fecha_Impuesto = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_Impuestos_BI($array){
  $query = "DELETE FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_Bancos_BB($array){
  $row = null;
  $query = "SELECT idBB FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Bancos_BB($array){
  $query = "INSERT INTO bancos_bitacora (Pago,id_UB,Fecha_Banco) VALUES (?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Update_Banco_BB($array){
  $query = "UPDATE bancos_bitacora SET Pago = ? WHERE idBB = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_Bancos_BB($id){
  $array = array($id);
  $query = "DELETE FROM bancos_bitacora WHERE idBB = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_Data_Ingresos($array){
  $query = "SELECT id_UV,ROUND(Cantidad,2) FROM venta_bitacora,ventas_udn WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta = ?";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Data_Descuentos($array){
  $query = "SELECT id_UD,ROUND(Cantidad,2) FROM descuentos_bitacora,descuentos_udn WHERE id_UD = idUD AND id_UDN = ? AND Fecha_Desc = ?";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Data_Impuestos($array){
  $query = "SELECT id_UI,ROUND(Cantidad,2) FROM impuestos_bitacora,impuestos_udn WHERE id_UI = idUI AND id_UDN = ? AND Fecha_Impuesto = ?";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Data_Efectivo($array){
  $query = "SELECT id_Efectivo,Cantidad FROM efectivo_bitacora WHERE id_UDN = ? AND Fecha_Efectivo = ?";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_idEB_Efectivo($array){
  $row = null;
  $query = "SELECT idEB FROM efectivo_bitacora WHERE id_Efectivo = ? AND  id_UDN = ? AND Fecha_Efectivo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Data_Bancos($array){
  $query = "SELECT id_UB,ROUND(Pago,2) FROM bancos_bitacora,bancos_udn WHERE id_UB = idUB AND id_UDN = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Data_Moneda_Bitacora($array){
  $query = "SELECT id_Moneda,Cantidad,Valor FROM hgpqgijw_finanzas.hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Insert_Efectivo($array){
  $query = "INSERT INTO efectivo_bitacora (id_Efectivo,id_UDN,Cantidad,Fecha_Efectivo) VALUES (?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Update_Efectivo($array){
  $query = "UPDATE efectivo_bitacora SET Cantidad = ? WHERE idEB = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_Efectivo($id){
  $array = array($id);
  $query = "DELETE FROM efectivo_bitacora WHERE idEB = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_idMB_Moneda($array){
  $row = null;
  $query = "SELECT idME FROM hgpqgijw_finanzas.hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Bitacora_Moneda($array){
  $query = "INSERT INTO hgpqgijw_finanzas.hgpqgijw_finanzas.moneda_extranjera_bitacora (id_Moneda,id_UDN,Cantidad,Valor,Fecha_Moneda) VALUES (?,?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Update_Bitacora_Moneda($array){
  $query = "UPDATE hgpqgijw_finanzas.hgpqgijw_finanzas.moneda_extranjera_bitacora SET Cantidad = ? WHERE idME = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_Bitacora_Moneda($id){
  $array = array($id);
  $query = "DELETE FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE idME = ?";
  $this->_DIU($query,$array,"5");
 }



 //Gastos
 function Select_Gasto_Esp($array){
  $row = null;
  $query = "SELECT idGastos FROM gastos WHERE Name_Gastos = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }


 //firmas
 function Insert_Firma_Nueva($array){
  $query = "INSERT INTO firmas (Nombre,Alias,Cifrada,id_UDN) VALUES (?,?,SHA(?),?)";
  $this->_DIU($query,$array,"1");
 }
 function Select_True_Alias($array){
  $row = null;
  $query = "SELECT idFirma FROM firmas WHERE Alias = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"1");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; } else { $row[0] = 1; }
  return $row[0];
 }
 function Select_Nombre_Alias($array){
  $row = null;
  $query = "SELECT Nombre FROM firmas WHERE Alias = ? AND Cifrada = SHA(?) AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"1");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Update_Firma($var,$array){
  $query = "UPDATE firmas SET ".$var." WHERE idFirma = ?";
  $this->_DIU($query,$array,"1");
 }
 function Delete_Firma($array){
  $query = "DELETE FROM firmas WHERE idFirma = ?";
  $this->_DIU($query,$array,"1");
 }


 //PROVEEDORES - INSUMO
 function Select_Group_Insumo($idE){
  $array = array($idE);
  $query = "SELECT idIC,Name_IC FROM insumos_clase,insumos_udn WHERE id_IC = idIC AND Stado = 1 AND id_UDN = ? ORDER BY Name_IC asc";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }

 function Select_Cont_Insumo($idE,$idUI,$date1,$date2) {
  $row = null;
  if ($idUI == 0) {
   $array = array($idE,$date1,$date2);
   $query = "SELECT id_UG,Name_Gastos FROM hgpqgijw_finanzas.compras,hgpqgijw_finanzas.gastos,hgpqgijw_finanzas.gastos_udn WHERE idGastos = id_Gastos AND idUG = id_UG AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Name_Gastos";
  }
  else {
   $array = array($idUI,$date1,$date2);
   $query =
   "SELECT id_UG,Name_Gastos FROM
   hgpqgijw_finanzas.compras,
   hgpqgijw_finanzas.gastos,
   hgpqgijw_finanzas.gastos_udn
   WHERE idGastos = id_Gastos AND idUG = id_UG AND id_CG = 3 AND id_UI = ? AND Fecha_Compras BETWEEN ? AND ? GROUP BY Name_Gastos";
  }
  $sql = $this->_Select($query,$array,"5");
  $cant = array();
  foreach ($sql as $key => $row) {
   $cant[$key] = $row[0];
  }
  $result = count($cant);
  if ($result == 0) { $result = '-'; }
  return $result;
 }

 function Select_idAlmacen($idE){
  $array = array($idE);
  $query = "SELECT idIC,Name_IC FROM insumos_clase,insumos_udn WHERE idIC = id_IC AND Name_IC LIKE '%almacen%' AND id_UDN  = ? AND Stado = 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Name_Proveedor($key,$idE){
  $array = array($idE);
  $query = "SELECT Name_Proveedor FROM proveedor,proveedor_udn WHERE id_Proveedor = idProveedor AND id_UDN = ? AND Name_Proveedor LIKE '%".$key."%'";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Especific_Proveedor($id){
  $query = "SELECT Name_Proveedor FROM proveedor,proveedor_udn WHERE idProveedor = id_Proveedor AND idUP = ".$id;
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Especific_Insumo($id){
  $query = "SELECT Name_Gastos FROM gastos,gastos_udn WHERE idGastos = id_Gastos AND idUG = ".$id;
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Especific_CI($id){
  $query = "SELECT Name_IC FROM insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = ".$id;
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Especific_TG($id){
  $row = null;
  $query = "SELECT Name_CG FROM gasto_clase WHERE idCG = ".$id;
  $sql = $this->_Select($query,null,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Name_Insumo($key,$idE){
  $array = array($idE);
  $query = "SELECT Name_Gastos FROM gastos,gastos_udn WHERE id_Gastos = idGastos AND id_UDN = ? AND Name_Gastos LIKE '%".$key."%'";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_idProveedores($Proveedor){
  $array = array($Proveedor); $row = null;
  $query = "SELECT idProveedor FROM proveedor WHERE Name_Proveedor = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  return $row[0];
 }
 function Select_NameProvedor_X_Indentificador($idCompras){
  $array = array($idCompras); $row = null;
  $query = "SELECT Name_Proveedor FROM proveedor,proveedor_udn,compras WHERE idProveedor = id_Proveedor AND idUP = id_UP AND idCompras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  return $row[0];
 }
 function Insert_Proveedor($Proveedor){
  $array = array($Proveedor);
  $query = "INSERT INTO proveedor (Name_Proveedor) VALUES (?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_Empresa_Proveedor($array){
  $row = null;
  $query = "SELECT idUP FROM proveedor_udn WHERE id_Proveedor = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Empresa_Proveedor($array){
  $query = "INSERT INTO proveedor_udn (id_Proveedor,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Select_idGasto($Insumo){
  $array = array($Insumo);
  $row = null;
  $query = "SELECT idGastos FROM gastos WHERE Name_Gastos = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_NameGasto_X_identificador($Id){
  $array = array($Id); $row = null;
  $query = "SELECT Name_Gastos FROM gastos,gastos_udn,compras WHERE idGastos = id_Gastos AND idUG = id_UG AND idCompras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Gasto($Insumo){
  $array = array($Insumo);
  $query = "INSERT INTO gastos (Name_Gastos) VALUES (?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_Empresa_Gasto($array){
  $row = null;
  $query = "SELECT idUG FROM gastos_udn WHERE id_Gastos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Empresa_Gasto($array){
  $query = "INSERT INTO gastos_udn (id_Gastos,id_UDN,Stado) VALUES (?,?,1)";
  $sql = $this->_DIU($query,$array,"5");
 }
 function Select_Empresa_ClaseInsumo($array){
  $row = null;
  $query = "SELECT idUI FROM insumos_udn WHERE id_IC = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Name_Insumo_Clase($id){
  $array = array($id);
  $query = "SELECT Name_IC FROM insumos_clase WHERE idIC = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Name_Clase_Gasto($id){
  $array = array($id);
  $query = "SELECT Name_CG FROM gasto_clase WHERE idCG = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Bitacora_Compras($query,$array){
  $this->_DIU($query,$array,"5");
 }
 function Delete_Compras_Pago($id){
  $array = array($id);
  $query = "DELETE FROM compras WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Cantidad_Compras($array){
  $query = "UPDATE compras SET Pago = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Cantidad_Compras2($array){
  $query = "UPDATE compras SET Gasto = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Proveedor_Pago($array){
  $query = "UPDATE compras SET id_UP = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Insumo_Pago($array){
  $query = "UPDATE compras SET id_UG = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Insumo_Clase($array){
  $query = "UPDATE compras SET id_UI = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Clase_Gasto($array){
  $query = "UPDATE compras SET id_CG = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Observaciones_Compras($array){
  $query = "UPDATE compras SET Observacion = ? WHERE idCompras = ?";
  $this->_DIU($query,$array,"5");
 }


 //CRÉDITOS
 function Select_Cliente($key,$udn){
  $query = "SELECT
  Name_Credito
  FROM hgpqgijw_finanzas.creditos,hgpqgijw_finanzas.creditos_udn,grupovar_gvsl.udn
  WHERE idUDN = id_UDN AND idCredito = id_Credito AND idUDN = '".$udn."' AND Name_Credito LIKE '%".$key."%'";
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Creditos($array){
  $row = null;
  $query = "SELECT idCredito FROM hgpqgijw_finanzas.creditos WHERE Name_Credito = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Credito($array){
  $query = "INSERT INTO hgpqgijw_finanzas.creditos (Name_Credito) VALUES (?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_UC($array){
  $row = null;
  $query = "SELECT idUC FROM creditos_udn WHERE id_Credito = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_UC($array){
  $query = "INSERT INTO creditos_udn (id_Credito,id_UDN,Stado) VALUES (?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Insert_BC($array){
  $query = "INSERT INTO creditos_bitacora (Pago,id_UC,Fecha_Credito,id_TP) VALUES (?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Select_FC($idUC){
  $array = array($idUC);
  $query = "SELECT Cantidad,idCC FROM creditos_udn,creditos_consumo WHERE id_UC = idUC AND creditos_consumo.Stado = 1 AND id_UC = ? ORDER BY Fecha_Consumo asc";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Deuda($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn,grupovar_gvsl.udn WHERE id_UDN = idUDN AND idUC = id_UC AND idUDN = ? AND Fecha_Consumo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Pagos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Credito = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_CTD($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UC = ? AND creditos_consumo.Stado = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  return $row[0];
 }
 function Select_CTP($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UC = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Consumo($array){
  $query = "INSERT INTO creditos_consumo (id_UC,Cantidad,Fecha_Consumo,Stado) VALUES (?,?,?,1)";
  $this->_DIU($query,$array,"5");
 }
 function Update_CC($array){
  $query = "UPDATE creditos_consumo SET Stado = 0 WHERE idCC = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_Deuda_Credito($opc,$Stado,$udn,$date){
  $row = null; $array = array($Stado,$udn,$date);
  if($opc == '1'){
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE idUC = id_UC AND creditos_consumo.Stado = ? AND id_UDN = ? AND Fecha_Consumo < ?";
  }
  else{
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE idUC = id_UC AND creditos_consumo.Stado = ? AND id_UDN = ? AND Fecha_Consumo <= ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Pagos_Credito($opc,$udn,$date){
  $row = null; $array = array($udn,$date);
  if($opc == '1'){
   $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito < ?";
  }
  else{
   $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito <= ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ){ $row[0] = 0; }
  return $row[0];
 }



 //BANCOS
 function Select_Banco($array){
  $row = null;
  $query = "SELECT idBancos FROM bancos WHERE Name_Bancos = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Pago_BB($array){
  $row = null;
  $query = "SELECT Pago FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Deuda_BB($array){
  $row = null;
  $query = "SELECT Deuda FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_BB($array){
  $row = null;
  $query = "SELECT idBB FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_BB($array){
  $query = "INSERT INTO bancos_bitacora (Pago,Deuda,id_UB,Fecha_Banco) VALUES (?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Update_BB($array){
  $query = "UPDATE bancos_bitacora SET Pago = ?,Deuda = ? WHERE id_UB = ? AND Fecha_Banco = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_BB($array){
  $query = "DELETE FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $this->_DIU($query,$array,"5");
 }

 //CARTULA
 function Select_SUM_Ventas($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM ventas_udn,venta_bitacora WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Descuentos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_udn,descuentos_bitacora WHERE idUD = id_UD AND id_UDN = ? AND Fecha_Desc = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Impuestos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_udn,impuestos_bitacora WHERE idUI = id_UI AND id_UDN = ? AND Fecha_Impuesto = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Efectivo_Propina($idEf,$idE,$date){
  $array = array($idEf,$idE,$date); $row = null;
  $query = "SELECT ROUND(Cantidad,2) FROM efectivo_bitacora WHERE id_Efectivo = ? AND id_UDN = ? AND Fecha_Efectivo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row)){ $row[0] = 0;}
  return $row[0];
 }
 function Select_SUM_Dolares($array){
  $query = "SELECT Moneda,Cantidad,hgpqgijw_finanzas.moneda_extranjera_bitacora.Valor FROM moneda_extranjera,hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = idMoneda AND id_UDN = ? AND Fecha_Moneda = ? ORDER BY id_Moneda asc";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_SUM_Quetzales($array){
  $row = null;
  $query = "SELECT ROUND(Pago,2) FROM bancos,bancos_udn,bancos_bitacora WHERE  idBancos = id_Bancos AND id_UB = idUB AND Name_Bancos = 'Quetzales' AND id_UDN = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Propina($array){
  $row = null;
  $query = "SELECT ROUND(Pago,2) FROM bancos,bancos_udn,bancos_bitacora WHERE  idBancos = id_Bancos AND id_UB = idUB AND Name_Bancos = 'Propina' AND id_UDN = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_bancos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_udn,bancos_bitacora WHERE id_UB = idUB AND id_UDN = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_PagoCredito($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_udn,creditos_bitacora WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_DeudaCredito($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_udn,creditos_consumo WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Anticipo($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') = ?";
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Gastos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_CG = 3 AND id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SI_Proveedor($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras < ?";
  $sql = $this->_Select($query,$array,"5"); foreach($sql as $row);
  if(!isset($row[0])){ $deuda_pagada_proveedor = 0; } else{ $deuda_pagada_proveedor = $row[0]; }//deudas pagados de proveedor


  $row1 = null;
  $query1 =
  "SELECT ROUND(SUM(Gasto),2)
  FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 1 AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras < ?";
  $sql1 = $this->_Select($query1,$array,"5"); foreach($sql1 as $row1);
  if(!isset($row1[0])){ $deuda_proveedor = 0; }else{ $deuda_proveedor = $row1[0]; }//deuda proveedor

  $row2 = null;
  $query2 = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_UDN = ? AND Fecha_Compras < ?";
  $sql2 = $this->_Select($query2,$array,"5"); foreach($sql2 as $row2);
  if(!isset($row2[0])){ $pago_proveedor = 0; } else{ $pago_proveedor = $row2[0]; }//pagos de proveedor

  $pagos = $pago_proveedor - $deuda_pagada_proveedor;
  $si = $deuda_proveedor - $pagos;

  return $si;
 }
 function Select_Gasto_Proveedor($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 1 AND id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Pago_Proveedor($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }

 //RETIROS

 function Select_SUM_Ventas_Retiro($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM ventas_udn,venta_bitacora WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Descuentos_Retiro($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_udn,descuentos_bitacora WHERE idUD = id_UD AND id_UDN = ? AND Fecha_Desc BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_Impuestos_Retiro($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_udn,impuestos_bitacora WHERE idUI = id_UI AND id_UDN = ? AND Fecha_Impuesto BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }

 function Select_SUM_Gastos_Fondo_SI($idE,$date_SI,$date2){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date2);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras >= ? AND Fecha_Compras < ?";
  }
  else{
   $array = array($idE,$date2);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras < ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_SUM_GastosCorpo_Retiro($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 1 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }

 function Select_SUM_Anticipo_Fondo_SI($idE,$date_SI,$date_now){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') >= ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') < ?";
  }
  else{
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') < ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  return $row[0];
 }

 function Select_Pago_Proveedor_Fondo_SI($idE,$date_SI,$date_now){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  >= ? AND Fecha_Compras  < ?";
  }
  else{
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  < ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Rembolso_Fondo($idE,$date_SI,$date_now){
  $row = null;
  $array = array($idE,$date_SI,$date_now);
  $query = "SELECT ROUND(SUM(Rembolso),2) FROM retiros WHERE id_UDN = ? AND Fecha_Rembolso  BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Retiro($array){
  $query = "INSERT INTO retiros (id_UDN,Fecha_Rembolso,Gasto_Fondo,Anticipos,Pagos_Proveedor,SI,Rembolso,SF) VALUES (?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }

 //DETALLES DE GASTOS MANUAL
 function Select_Count_Gastos($array){
  $query = "SELECT id_UG FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND Status = 1 AND id_UDN = ? AND Fecha_Compras = ? GROUP BY id_UG";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Cantidad_Gasto($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND Status = 1 AND id_UDN = ? AND Fecha_Compras = ? AND id_UG = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }

 //DETALLES DE PROVEEDOR MANUAL
 function Select_Count_Proveedor($array){
  $query = "SELECT id_UP FROM compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras = ? GROUP BY id_UP";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Pagos_Proveedor($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE Status = 2 AND id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras = ? AND id_UP = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Gastos_Proveedor($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE Status = 1 AND id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras = ? AND id_UP = ?";
  $sq1 = $this->_Select($query,$array,"5");
  foreach($sq1 as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 //DETALLES DE COLABORADOR MANUAL
 function Select_Count_Colaboradores($array){
  $query = "SELECT Empleado_Anticipo,Nombres FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ? GROUP BY Empleado_Anticipo ORDER BY Nombres asc";
  $sql = $this->_Select($query,$array,"6");
  return $sql;
 }
 function Select_Cantidad_Colaboradores($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ?  AND Empleado_Anticipo = ? GROUP BY Empleado_Anticipo ORDER BY Nombres asc";
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 //DETALES DE BANCOS MANUAL
 function Select_Detalle_Bancos($array){
  $query = "SELECT Name_Bancos,ROUND(Pago,2) FROM bancos,bancos_udn,bancos_bitacora WHERE idBancos = id_Bancos AND id_UB = idUB AND id_UDN = ? AND Fecha_Banco = ? ORDER BY Name_Bancos asc";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }


 //DETALLE DE ALMACEN MANUAL
 function Select_Count_Almacen($array){
  $query = "SELECT id_UG,Name_Gastos FROM compras,insumos_clase,insumos_udn,gastos,gastos_udn WHERE id_UG IS NOT NULL AND id_UG = idUG AND id_Gastos = idGastos AND id_UI = idUI AND idIC = id_IC AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ? AND Fecha_Compras = ? GROUP BY id_UG ORDER BY Name_Gastos asc";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Entrada_Almacen($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras,insumos_clase,insumos_udn WHERE id_UI = idUI AND idIC = id_IC AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ? AND Fecha_Compras = ? AND  id_UG = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){$row[0] = 0;}
  return $row[0];
 }
 function Select_Salida_Almacen($array){
  $query = "SELECT ROUND(SUM(Pago),2) FROM compras,insumos_clase,insumos_udn WHERE id_UI = idUI AND idIC = id_IC AND Name_IC LIKE '%almacen%' AND compras.id_UDN = ? AND Fecha_Compras = ? AND  id_UG = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){$row[0] = 0;}
  return $row[0];
 }

 //MONEDA EXTRANJERA
 function Select_Data_Moneda() {
  $query = "SELECT idMoneda,Moneda,Abreviatura,Valor FROM  hgpqgijw_finanzas.moneda_extranjera WHERE Stado = 1 ORDER BY idMoneda asc";
  $sql = $this->_Select($query,null,"5");
  return $sql;
 }
 function Select_Count_Moneda() {
  $row = null;
  $query = "SELECT Count(*) FROM  moneda_extranjera WHERE Stado = 1";
  $sql = $this->_Select($query,null,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0;}
  return $row[0];
 }
 function Insert_Moneda($array){
  $query = "INSERT INTO moneda_extranjera (Moneda,Valor,Abreviatura,Stado) VALUES (?,?,?,1)";
  $this->_Select($query,$array,"5");
 }
 function Select_Moneda($moneda){
  $array = array($moneda);
  $row = null;
  $query = "SELECT idMoneda FROM moneda_extranjera WHERE Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Update_Moneda($array){
  $query = "UPDATE moneda_extranjera SET Moneda = ? WHERE idMoneda = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Abreviatura($array){
  $query = "UPDATE moneda_extranjera SET Abreviatura = ? WHERE idMoneda = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Valor($array){
  $query = "UPDATE moneda_extranjera SET Valor = ? WHERE idMoneda = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Desactivar_Moneda($id){
  $array = array($id);
  $query = "UPDATE moneda_extranjera SET Stado = 0 WHERE idMoneda = ?";
  $this->_DIU($query,$array,"5");
 }
 function Update_Activar_Moneda($array){
  $query = "UPDATE moneda_extranjera SET Abreviatura = ?, Valor = ?, Stado = 1 WHERE idMoneda = ?";
  $this->_DIU($query,$array,"5");
 }
 function Select_Cant_Efectivo($date_now,$idE){
  //Consulto la ultima fecha de retiro y el saldo final
  $array_date = array();
  $array_date = array($idE);
  if($idE == 9){ $array_date = array(6); }

  $date = null;
  $query_date = "SELECT Fecha_Retiro,SF_Efectivo FROM retiros_venta WHERE id_UDN = ? ORDER BY  idRetiroVenta DESC LIMIT 1";
  $sql_date = $this->_Select($query_date,$array_date,"5");
  foreach($sql_date as $date);
  if(!isset($date[1])){ $date[1] = 0;}

  $row_prop = null; $row_efect = null;
  if(isset($date[0])){//Si existe Retiro
   $array = array($date[0],$date_now,$idE);

   $query_prop = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 1 AND Fecha_Efectivo >= ? AND Fecha_Efectivo < ? AND id_UDN = ?";
   $sql_prop = $this->_Select($query_prop,$array,"5");
   foreach($sql_prop as $row_prop);
   if(!isset($row_prop[0])){$row_prop[0] = 0;}

   $query_efect = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 2 AND Fecha_Efectivo >= ? AND Fecha_Efectivo < ? AND id_UDN = ?";
   $sql_efect = $this->_Select($query_efect,$array,"5");
   foreach($sql_efect as $row_efect);
   if(!isset($row_efect[0])){$row_efect[0] = 0;}
  }
  else {//Si no existe retiro
   $array = array($date_now,$idE);

   $query_prop = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 1 AND Fecha_Efectivo < ? AND id_UDN = ?";
   $sql_prop = $this->_Select($query_prop,$array,"5");
   foreach($sql_prop as $row_prop);
   if(!isset($row_prop[0])){$row_prop[0] = 0;}

   $query_efect = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 2 AND Fecha_Efectivo < ? AND id_UDN = ?";
   $sql_efect = $this->_Select($query_efect,$array,"5");
   foreach($sql_efect as $row_efect);
   if(!isset($row_efect[0])){$row_efect[0] = 0;}
  }

  $Efectivo = $row_efect[0] - $row_prop[0] + $date[1];
  if($idE == 9){
   $Efectivo = $row_efect[0] - $row_prop[0];
  }
  return $Efectivo;
 }
 function Select_Cant_Hoy_Efectivo($date_now,$idE){
  $array = array($date_now,$idE); $row_prop = null; $row_efect = null;
  $query_prop = "SELECT Cantidad FROM efectivo_bitacora WHERE id_Efectivo = 1 AND Fecha_Efectivo = ? AND id_UDN = ?";
  $sql_prop = $this->_Select($query_prop,$array,"5");
  foreach($sql_prop as $row_prop);
  if(!isset($row_prop)){ $row_prop[0] = 0; }

  $query_efect = "SELECT Cantidad FROM efectivo_bitacora WHERE id_Efectivo = 2 AND Fecha_Efectivo = ? AND id_UDN = ?";
  $sql_efect = $this->_Select($query_efect,$array,"5");
  foreach($sql_efect as $row_efect);
  if(!isset($row_efect)){$row_efect[0] = 0;}

  $efectivo_hoy = $row_efect[0] - $row_prop[0];
  return $efectivo_hoy;
 }
 function Select_Cant_Moneda($idM,$date_now,$idE){
  $date = null;
  $array_date = array($idE);
  if($idE == 9){ $array_date = array(6); }

  $query_date = "SELECT Fecha_Retiro,idRetiroVenta FROM retiros_venta WHERE id_UDN = ? ORDER BY idRetiroVenta DESC LIMIT 1";
  $sql_date = $this->_Select($query_date,$array_date,"5");
  foreach($sql_date as $date);

  $row = null; $sf = null;
  if(isset($date[0])){
   $array = array($idM,$date[1]);
   $query_sf = "SELECT SF_Moneda FROM retiroventa_moneda WHERE id_Moneda = ? AND id_RetiroVenta = ?";
   $sql_sf = $this->_Select($query_sf,$array,"5");
   foreach ($sql_sf as $sf);

   $array = array($idM,$date[0],$date_now,$idE);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND Fecha_Moneda >= ? AND Fecha_Moneda < ? AND id_UDN = ?";
  }
  else{
   $array = array($idM,$date_now,$idE);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND Fecha_Moneda < ? AND id_UDN = ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){$row[0] = 0;}

  $moneda = $row[0] + $sf[0];
  if($idE == 9){
   $moneda = $row[0];
  }

  return $moneda;
 }
 function Select_Cant_Moneda_Valor($idM,$date_now,$idE){
  $date = null; $array_date = array($idE);
  $query_date = "SELECT Fecha_Retiro,idRetiroVenta FROM retiros_venta WHERE id_UDN = ? ORDER BY  idRetiroVenta DESC LIMIT 1";
  $sql_date = $this->_Select($query_date,$array_date,"5");
  foreach($sql_date as $date);

  $row = null; $sf = null;
  if(isset($date[0])){
   $array = array($idM,$date[1]);
   $query_sf = "SELECT ROUND(SF_Moneda*Valor,2) FROM retiroventa_moneda WHERE id_Moneda = ? AND id_RetiroVenta = ?";
   $sql_sf = $this->_Select($query_sf,$array,"5");
   foreach ($sql_sf as $sf);

   $array = array($idM,$date[0],$date_now,$idE);
   $query = "SELECT ROUND(SUM(Cantidad)*Valor,2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND Fecha_Moneda >= ? AND Fecha_Moneda < ? AND id_UDN = ?";
  }
  else{
   $array = array($idM,$date_now,$idE);
   $query = "SELECT ROUND(SUM(Cantidad)*Valor,2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND Fecha_Moneda < ? AND id_UDN = ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){$row[0] = 0;}

  $moneda = $row[0] + $sf[0];
  return $moneda;
 }
 function Select_Cant_Hoy_Moneda($idM,$date_now,$idE){
  $array = array($idM,$date_now,$idE);
  $query = "SELECT Cantidad FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND Fecha_Moneda = ? ANd id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){$row[0] = 0;}
  return $row[0];
 }
 function Select_Cant_Hoy_Moneda_Valor($idM,$date_now,$idE){
  $array = array($idM,$date_now,$idE);
  $query = "SELECT Cantidad*Valor FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND Fecha_Moneda = ? ANd id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){$row[0] = 0;}
  return $row[0];
 }
 function Select_SI_Retiro_Venta($idE){
  $row_si = null; $array_date = array($idE);
  $query_si = "SELECT SF_Total FROM retiros_venta WHERE id_UDN = ? ORDER BY  idRetiroVenta DESC LIMIT 1";
  $sql_si = $this->_Select($query_si,$array_date,"5");
  foreach($sql_si as $row_si);
  if(!isset($row_si[0])){ $row_si[0] = 0; }
  return $row_si[0];
 }
 function Select_Retiro_Total($date,$idE){
  $array = array($date,$idE);
  $query = "SELECT Retiro_Total FROM retiros_venta WHERE Fecha_Retiro = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ){ $row[0] = 0; }
  return $row[0];
 }
 function Select_idRetiroVenta($idE){
  $array = array($idE);
  $query = "SELECT idRetiroVenta FROM retiros_venta WHERE id_UDN = ? ORDER BY  idRetiroVenta DESC LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Insert_Retiro_Venta($array){
  $query = "INSERT INTO retiros_venta (id_UDN,Fecha_Retiro,SI_Total,Retiro_Total,SF_Total,SI_Efectivo,Retiro_Efectivo,SF_Efectivo) VALUES (?,?,?,?,?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }
 function Insert_RetiroVenta_Moneda($array){
  $query = "INSERT INTO retiroventa_moneda (id_RetiroVenta,id_Moneda,Valor,SI_Moneda,Retiro_Moneda,SF_Moneda) VALUES (?,?,?,?,?,?)";
  $this->_DIU($query,$array,"5");
 }



 //ADMINISTRACIÓN
 function Select_Suma_Venta_Sin_Impuestos($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE;  $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM venta_bitacora,ventas_udn WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta = ?";
  }
  else{
   $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM venta_bitacora,ventas_udn WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Venta_Sin_Impuestos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM venta_bitacora,ventas_udn WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Suma_Ingresos($id_Ing,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $id_Ing;  $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM venta_bitacora WHERE id_UV = ? AND Fecha_Venta = ?";
  }
  else{
   $array[0] = $id_Ing;  $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM venta_bitacora WHERE id_UV = ? AND Fecha_Venta BETWEEN ? AND ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Ingresos_SJ($id_Ing,$idE,$date1,$date2){
  $uv = null;
  $array = array($id_Ing,$idE);
  $query = "SELECT idUV FROM ventas_udn WHERE id_Venta = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $uv);

  $array = array();
  if($date1 == $date2){
   $array = array($uv[0],$date1);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM venta_bitacora WHERE id_UV = ? AND Fecha_Venta = ?";
  }
  else{
   $array = array($uv[0],$date1,$date2);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM venta_bitacora WHERE id_UV = ? AND Fecha_Venta BETWEEN ? AND ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_dia_Ingresos($array){
  $row = null;
  $query = "SELECT Cantidad FROM venta_bitacora WHERE id_UV = ? AND Fecha_Venta = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_dia_Ingresos_SJ($idV,$idE,$date){
  $row = null;
  $array = array($idV,$idE);
  $query = "SELECT idUV FROM ventas_udn WHERE id_Venta = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);


  $row2 = null;
  $array = array($row[0],$date);
  $query2 = "SELECT Cantidad FROM venta_bitacora WHERE id_UV = ? AND Fecha_Venta = ?";
  $sql2 = $this->_Select($query2,$array,"5");
  foreach($sql2 as $row2);
  if(!isset($row2[0])){ $row2[0] = 0; }
  return $row2[0];
 }
 function Select_Suma_Descuentos($id_Desc,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $id_Desc;  $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc = ?";
  }
  else{
   $array[0] = $id_Desc;  $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc BETWEEN ? AND ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Descuentos_SJ($id_Desc,$idE,$date1,$date2){
  $ud = null;
  $array = array($id_Desc,$idE);
  $query = "SELECT idUD FROM descuentos_udn WHERE id_Descuentos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $ud);

  $array = array();
  if($date1 == $date2){
   $array = array($ud[0],$date1);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc = ?";
  }
  else{
   $array = array($ud[0],$date1,$date2);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc BETWEEN ? AND ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_dia_Descuentos($array){
  $row = null;
  $query = "SELECT Cantidad FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_dia_Descuentos_SJ($id_Desc,$idE,$date){
  $ud = null;
  $array = array($id_Desc,$idE);
  $query = "SELECT idUD FROM descuentos_udn WHERE id_Descuentos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $ud);

  $row = null;
  $array = array($ud[0],$date);
  $query = "SELECT Cantidad FROM descuentos_bitacora WHERE id_UD = ? AND Fecha_Desc = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Impuestos($id_Imp,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $id_Imp;  $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto = ?";
  }
  else{
   $array[0] = $id_Imp;  $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto BETWEEN ? AND ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row = 0; }
  return $row[0];
 }
 function Select_Suma_Impuestos_SJ($id_Imp,$idE,$date1,$date2){
  $ui = null;
  $array = array($id_Imp,$idE);
  $query = "SELECT idUI FROM impuestos_udn WHERE id_Impuesto = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $ui);

  $array = array();
  if($date1 == $date2){
   $array = array($ui[0],$date1);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto = ?";
  }
  else{
   $array = array($ui[0],$date1,$date2);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto BETWEEN ? AND ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row = 0; }
  return $row[0];
 }
 function Select_Dia_Impuestos($array){
  $row = null;
  $query = "SELECT Cantidad FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Dia_Impuestos_SJ($idImp,$idE,$date){
  $ui = null;
  $array = array($idImp,$idE);
  $query = "SELECT idUI FROM impuestos_udn WHERE id_Impuesto = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $ui);

  $row = null;
  $array = array($ui[0],$date);
  $query = "SELECT Cantidad FROM impuestos_bitacora WHERE id_UI = ? AND Fecha_Impuesto = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Total_Impuestos($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE;  $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_bitacora,impuestos_udn WHERE idUI = id_UI AND id_UDN = ? AND Fecha_Impuesto = ?";
  }
  else{
   $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_bitacora,impuestos_udn WHERE idUI = id_UI AND id_UDN = ? AND Fecha_Impuesto BETWEEN ? AND ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Total_Impuestos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM impuestos_bitacora,impuestos_udn WHERE idUI = id_UI AND id_UDN = ? AND Fecha_Impuesto = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Total_Descuentos($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE;  $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_bitacora,descuentos_udn WHERE idUD = id_UD AND id_UDN = ? AND Fecha_Desc = ?";
  }
  else{
   $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_bitacora,descuentos_udn WHERE idUD = id_UD AND id_UDN = ? AND Fecha_Desc BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Total_Dia_Descuentos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM descuentos_bitacora,descuentos_udn WHERE idUD = id_UD AND id_UDN = ? AND Fecha_Desc = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Sum_Total_Propina_Efectivo($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query_prop = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 1 AND id_UDN = ? AND Fecha_Efectivo = ?";
   $query_efect = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 2 AND id_UDN = ? AND Fecha_Efectivo = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query_prop = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 1 AND id_UDN = ? AND Fecha_Efectivo BETWEEN ? AND ?";
   $query_efect = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 2 AND id_UDN = ? AND Fecha_Efectivo BETWEEN ? AND ?";
  }

  $row_prop = null;
  $sql_prop = $this->_Select($query_prop,$array,"5");
  foreach ($sql_prop as $row_prop);
  if(!isset($row_prop[0])){ $row_prop[0] = 0; }

  $row_efect = null;
  $sql_efect = $this->_Select($query_efect,$array,"5");
  foreach ($sql_efect as $row_efect);
  if(!isset($row_efect[0])){ $row_efect[0] = 0; }

  $Suma_Caja_Propina = $row_efect[0] - $row_prop[0];

  return $Suma_Caja_Propina;
 }
 function Select_Dif_Total_Propina_Efectivo($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query_prop = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 1 AND id_UDN = ? AND Fecha_Efectivo = ?";
   $query_efect = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 2 AND id_UDN = ? AND Fecha_Efectivo = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query_prop = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 1 AND id_UDN = ? AND Fecha_Efectivo BETWEEN ? AND ?";
   $query_efect = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = 2 AND id_UDN = ? AND Fecha_Efectivo BETWEEN ? AND ?";
  }

  $row_prop = null;
  $sql_prop = $this->_Select($query_prop,$array,"5");
  foreach ($sql_prop as $row_prop);
  if(!isset($row_prop[0])){ $row_prop[0] = 0; }

  $row_efect = null;
  $sql_efect = $this->_Select($query_efect,$array,"5");
  foreach ($sql_efect as $row_efect);
  if(!isset($row_efect[0])){ $row_efect[0] = 0; }

  $Suma_Caja_Propina = $row_efect[0];

  return $Suma_Caja_Propina;
 }
 function Select_Efectivo_Propina($id_Prop,$idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $id_Prop;  $array[1] = $idE; $array[2] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = ? AND id_UDN = ? AND Fecha_Efectivo = ?";
  }
  else{
   $array[0] = $id_Prop;  $array[1] = $idE; $array[2] = $date1; $array[3] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_Efectivo = ? AND id_UDN = ? AND Fecha_Efectivo BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Suma_Total_Efectivo_Propina($array){
  $row = null;
  $query = "SELECT Cantidad FROM efectivo_bitacora WHERE id_Efectivo = ? AND id_UDN = ? AND Fecha_Efectivo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Suma_Total_Moneda_Extranjera_Valor($id_Mon,$idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $id_Mon;  $array[1] = $idE; $array[2] = $date1;
   $query_efect = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda = ?";
  }
  else{
   $array[0] = $id_Mon;  $array[1] = $idE; $array[2] = $date1; $array[3] = $date2;
   $query_efect = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query_efect,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Suma_Total_Moneda_Extranjera($id_Mon,$idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $id_Mon;  $array[1] = $idE; $array[2] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda = ?";
  }
  else{
   $array[0] = $id_Mon;  $array[1] = $idE; $array[2] = $date1; $array[3] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Dia_Total_Moneda_Extranjera_Valor($array){
  $query = "SELECT SUM(Cantidad*hgpqgijw_finanzas.moneda_extranjera_bitacora.Valor) as MXN FROM  moneda_extranjera,hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = idMoneda AND id_UDN = ? AND Fecha_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Dia_Total_Moneda_Extranjera($array){
  $row = null;
  $query = "SELECT (Cantidad*hgpqgijw_finanzas.moneda_extranjera_bitacora.Valor) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Suma_Caja_Pago($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query_pago = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Credito = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query_pago = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Credito BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query_pago,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Suma_Caja_Deuda($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query_deuda = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Consumo = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query_deuda = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Consumo BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query_deuda,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Caja_Bancos($id_Ban,$date1,$date2){
  $array = array();
  if($date1 == $date2) {
   $array[0] = $id_Ban;  $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  }
  else {
   $array[0] = $id_Ban;  $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Caja_Bancos_SJ($id_Ban,$idE,$date1,$date2){
  $ub = null;
  $array = array($id_Ban,$idE);
  $query = "SELECT idUB FROM  bancos_udn WHERE id_Bancos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $ub);

  $array = array();
  if($date1 == $date2) {
   $array = array($ub[0],$date1);
   $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  }
  else {
   $array = array($ub[0],$date1,$date2);
   $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco BETWEEN ? AND ?";
  }

  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach ($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Dia_Bancos($array){
  $row = null;
  $query = "SELECT Pago FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Dia_Bancos_SJ($idBan,$idE,$date){
  $ub = null;
  $array = array($idBan,$idE);
  $query = "SELECT idUB FROM bancos_udn WHERE id_Bancos = ? AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $ub);

  $row = null;
  $array = array($ub[0],$date);
  $query = "SELECT Pago FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Dia_Bancos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_udn,bancos_bitacora WHERE id_UB = idUB AND id_UDN = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Dia_Pagos_Clientes($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Credito = ?;";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Dia_Deudas_Clientes($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE id_UC = idUC AND id_UDN = ? AND Fecha_Consumo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Dia_Propina($idP,$idUDN,$date){
  $array = array($idP,$idUDN,$date);
  $row = null;
  $query = "SELECT ROUND(Cantidad,2) FROM efectivo_bitacora WHERE id_Efectivo = ? AND id_UDN = ? AND Fecha_Efectivo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row)){ $row[0] = 0;}
  return $row[0];
 }


 //COLABORADORES
 function Select_Suma_Total_Anticipos($idE,$date1,$date2){
  $row = null;
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') BETWEEN ? AND ?";
  }
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Total_Anticipos_x_Colaborador($idEmpleado,$idE,$date1,$date2){
  $row = null;
  $array = array();
  if($date1 == $date2){
   $array[0] = $idEmpleado; $array[1] = $idE; $array[2] = $date1;
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND Empleado_Anticipo = ? AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ?";
  }
  else{
   $array[0] = $idEmpleado; $array[1] = $idE; $array[2] = $date1; $array[3] = $date2;
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND Empleado_Anticipo = ? AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') BETWEEN ? AND ?";
  }
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Anticipos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ?";
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Data_Hoy_Colaborador($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos WHERE Empleado_Anticipo = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ?";
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 //GASTOS
 function Select_Suma_Total_Gastos($idE,$date1,$date2){
  $row = null;
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Gasto),2) as Saldo FROM compras WHERE id_CG = 3 AND id_UDN = ? AND Fecha_Compras = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Gasto),2) as Saldo FROM compras WHERE id_CG = 3 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }


 //BANCOS
 function Select_Suma_Total_Bancos($idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora,bancos_udn WHERE idUB = id_UB AND id_UDN = ? AND Fecha_Banco = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora,bancos_udn WHERE idUB = id_UB AND id_UDN = ? AND Fecha_Banco BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Bancos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora,bancos_udn WHERE idUB = id_UB AND id_UDN = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Data_Hoy_Bancos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM bancos_bitacora WHERE id_UB = ? AND Fecha_Banco = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }



 //CREDITOS
 function Select_Suma_Total_Creditos($idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Total_Deudas($idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Hoy_Creditos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Hoy_Consumo($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo,creditos_udn WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Creditos_Consumo($idC,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array[0] = $idC; $array[1] = $date1;
   $query1 = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora WHERE id_UC = ? AND Fecha_Credito = ?";
   $query2 = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo WHERE id_UC = ? AND Fecha_Consumo = ?";
  }
  else{
   $array[0] = $idC; $array[1] = $date1; $array[2] = $date2;
   $query1 = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora WHERE id_UC = ? AND Fecha_Credito BETWEEN ? AND ?";
   $query2 = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo WHERE id_UC = ? AND Fecha_Consumo BETWEEN ? AND ?";
  }

  $sql1 = $this->_Select($query1,$array,"5");
  foreach($sql1 as $row1);
  if(!isset($row1[0])){ $row1[0] = 0; }

  $sql2 = $this->_Select($query2,$array,"5");
  foreach($sql2 as $row2);
  if(!isset($row2[0])){ $row2[0] = 0; }

  $total = $row2[0] - $row1[0];

  return $total;
 }
 function Select_Data_Hoy_Creditos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM creditos_bitacora WHERE id_UC = ? AND Fecha_Credito = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Data_Hoy_Consumo($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM creditos_consumo WHERE id_UC = ? AND Fecha_Consumo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }


 //ALMACEN
 function Select_Saldo_Entrada_Almacen($var,$opc,$array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras ".$opc." ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Saldo_Salidas_Almacen($var,$opc,$array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras ".$opc." ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Total_Almacen_Gastos($var,$idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Total_Almacen_Pagos($var,$idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Hoy_Almacen_Gastos($var,$array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Hoy_Almacen_Pagos($var,$array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM compras,insumos_clase,insumos_udn WHERE idIC = id_IC AND idUI = id_UI ".$var." AND compras.id_UDN = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Almacen_Saldo($idA,$date1,$date2){
  $row1 = null; $row2 = null;

  $array = array($idA,$date1,$date2);
  $query1 = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UG = ? AND Fecha_Compras BETWEEN ? AND ?";
  $query2 = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG = ? AND Fecha_Compras BETWEEN ? AND ?";

  $sql1 = $this->_Select($query1,$array,"5");
  foreach($sql1 as $row1);
  if(!isset($row1[0])){ $row1[0] = 0; }

  $sql2 = $this->_Select($query2,$array,"5");
  foreach($sql2 as $row2);
  if(!isset($row2[0])){ $row2[0] = 0; }

  $total = $row2[0] - $row1[0];

  return $total;
 }
 function Select_Suma_Almacen_Saldo_Categoria($idA,$date1,$date2){
  $row1 = null; $row2 = null;

  $array = array($idA,$date1,$date2);
  $query1 = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UI = ? AND Fecha_Compras BETWEEN ? AND ?";
  $query2 = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UI = ? AND Fecha_Compras BETWEEN ? AND ?";

  $sql1 = $this->_Select($query1,$array,"5");
  foreach($sql1 as $row1);
  if(!isset($row1[0])){ $row1[0] = 0; }

  $sql2 = $this->_Select($query2,$array,"5");
  foreach($sql2 as $row2);
  if(!isset($row2[0])){ $row2[0] = 0; }

  $total = $row2[0] - $row1[0];

  return $total;
 }
 function Select_Data_Hoy_Almacen_Gasto($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Data_Hoy_Almacen_Pagos($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UG = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Data_Hoy_Almacen_Gasto_Categoria($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UI = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Data_Hoy_Almacen_Pagos_Categoria($array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UI = ? AND Fecha_Compras = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 //COSTOS
 function Select_Combo_Costos($idE){
  $row = null; $array = array($idE);
  $query = "SELECT idUI,Name_IC FROM insumos_clase,insumos_udn WHERE idIC = id_IC AND Name_IC LIKE '%costo%' AND id_UDN = ?";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }

 //PROVEEDOR
 function Select_Saldo_Gasto_Proveedor($opc,$array){
  $row = null;
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras ".$opc." ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Saldo_Pagos_Proveedor($opc,$array){
  $row = null;
  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Pago IS NOT NULL AND id_UDN = ? AND Fecha_Compras ".$opc." ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Suma_Total_Proveedor_Gastos($idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array[0] = $idE; $array[1] = $date1;
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras = ?";
  }
  else{
   $array[0] = $idE; $array[1] = $date1; $array[2] = $date2;
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }


 //CARATULA ADMINISTRADOR =======================================================================

 function Select_Total_Venta($idE,$date1,$date2){
  $row_v = null; $row_i = null; $row_d = null; $array = array();

  if($date1 == $date2){
   $array = array($idE,$date1);

   $query_v =
   "SELECT ROUND(SUM(Cantidad),2)
   FROM
   hgpqgijw_finanzas.ventas_udn,
   hgpqgijw_finanzas.venta_bitacora
   WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta = ?";

   $query_i = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.impuestos_udn,hgpqgijw_finanzas.impuestos_bitacora WHERE idUI = id_UI AND id_UDN = ? AND Fecha_Impuesto = ?";
   $query_d = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.descuentos_udn,hgpqgijw_finanzas.descuentos_bitacora WHERE idUD = id_UD AND id_UDN = ? AND Fecha_Desc = ?";
  }

  else{
   $array = array($idE,$date1,$date2);


   $query_v = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.ventas_udn,hgpqgijw_finanzas.venta_bitacora WHERE idUV = id_UV AND id_UDN = ? AND Fecha_Venta BETWEEN ? AND ?";


   $query_i = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.impuestos_udn,hgpqgijw_finanzas.impuestos_bitacora WHERE idUI = id_UI AND id_UDN = ? AND Fecha_Impuesto BETWEEN ? AND ?";


   $query_d = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.descuentos_udn,hgpqgijw_finanzas.descuentos_bitacora WHERE idUD = id_UD AND id_UDN = ? AND Fecha_Desc BETWEEN ? AND ?";
  }

  $sql_v = $this->_Select($query_v,$array);
  foreach($sql_v as $row_v);
  if(!isset($row_v[0])){ $row_v[0] = 0; }

  $sql_i = $this->_Select($query_i,$array);
  foreach($sql_i as $row_i);
  if(!isset($row_i[0])){ $row_i[0] = 0; }

  $sql_d = $this->_Select($query_d,$array);
  foreach($sql_d as $row_d);
  if(!isset($row_d[0])){ $row_d[0] = 0; }

  $total = $row_v[0] + $row_i[0] - $row_d[0];
  return $total;
 }
 //==================================================================================================




 function Select_Total_Efectivo($opc,$idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($opc,$idE,$date1);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.efectivo_bitacora WHERE id_Efectivo = ? AND id_UDN = ? AND Fecha_Efectivo = ?";
  }
  else{
   $array = array($opc,$idE,$date1,$date2);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.efectivo_bitacora WHERE id_Efectivo = ? AND id_UDN = ? AND Fecha_Efectivo BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];
 }
 function Select_Total_Monedas($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array = array($idE,$date1);
   $query = "SELECT Moneda,ROUND(SUM(Cantidad),2),
   hgpqgijw_finanzas.moneda_extranjera_bitacora.Valor
   FROM
   hgpqgijw_finanzas.moneda_extranjera,
   hgpqgijw_finanzas.moneda_extranjera_bitacora
   WHERE id_Moneda = idMoneda AND id_UDN = ? AND Fecha_Moneda = ? GROUP BY Moneda ORDER BY id_Moneda asc";
  }
  else{
   $array = array($idE,$date1,$date2);
   $query = "SELECT Moneda,ROUND(SUM(Cantidad),2),hgpqgijw_finanzas.moneda_extranjera_bitacora.Valor FROM moneda_extranjera,hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = idMoneda AND id_UDN = ? AND Fecha_Moneda BETWEEN ? AND ? GROUP BY Moneda ORDER BY id_Moneda asc";
  }

  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Total_Bancos($idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idE,$date1);
   $query = "SELECT ROUND(SUM(Pago),2)
   FROM hgpqgijw_finanzas.bancos_udn,
   hgpqgijw_finanzas.bancos_bitacora
   WHERE id_UB = idUB AND id_UDN = ? AND Fecha_Banco = ?";
  }
  else{
   $array = array($idE,$date1,$date2);
   $query = "SELECT ROUND(SUM(Pago),2)
   FROM hgpqgijw_finanzas.bancos_udn,
   hgpqgijw_finanzas.bancos_bitacora
   WHERE id_UB = idUB AND id_UDN = ? AND Fecha_Banco BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }




 function Select_Total_DeudaCredito($idE,$date1,$date2) {
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idE,$date1);
   $query =
   "SELECT ROUND(SUM(Cantidad),2)
   FROM hgpqgijw_finanzas.creditos_udn,
   hgpqgijw_finanzas.creditos_consumo
   WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo = ?";
  }
  else{
   $array = array($idE,$date1,$date2);
   $query =
   "SELECT ROUND(SUM(Cantidad),2)
   FROM
   hgpqgijw_finanzas.creditos_udn,
   hgpqgijw_finanzas.creditos_consumo
   WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Consumo BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function Select_Total_PagosCredito($idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idE,$date1);
   $query = "SELECT ROUND(SUM(Pago),2) FROM
   hgpqgijw_finanzas.creditos_udn,
   hgpqgijw_finanzas.creditos_bitacora WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito = ?";
  }
  else{
   $array = array($idE,$date1,$date2);
   $query = "SELECT ROUND(SUM(Pago),2) FROM
   hgpqgijw_finanzas.creditos_udn,hgpqgijw_finanzas.creditos_bitacora WHERE idUC = id_UC AND id_UDN = ? AND Fecha_Credito BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }


 function Select_Total_Retiro($idE,$date1){
  $array = array($idE,$date1);
  $query = "SELECT idRetiro,ROUND(SF,2),Fecha_Rembolso,SI,Gasto_Fondo
  FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso <= ? ORDER BY idRetiro desc LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }


 function Select_Total_Anticipo($idE,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idE,$date1);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM
   hgpqgijw_finanzas.anticipos,
   hgpqgijw_finanzas.empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') = ?";
  }
  else{
   $array = array($idE,$date1,$date2);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Datos_RetiroVenta($idE,$date1){
  $array = array($idE,$date1);
  $query = "SELECT idRetiroVenta,Fecha_Retiro,SF_Total,SI_Total FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro <= ? ORDER BY idRetiroVenta DESC LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Total_Retiro_Efectivo($opc,$idE,$date_retiro,$date2){
  $row_e = null; $row_p = null; $array = array();

  if($date_retiro == null){
   $array = array($idE,$date2);
   $query_p = "SELECT ROUND(SUM(Cantidad),2) FROM arg_finanzs.efectivo_bitacora WHERE id_Efectivo = 1 AND id_UDN = ? AND Fecha_Efectivo ".$opc." ?";
   $query_e = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.efectivo_bitacora WHERE id_Efectivo = 2 AND id_UDN = ? AND Fecha_Efectivo ".$opc." ?";
  }
  else {
   $array = array($idE,$date_retiro,$date2);
   $query_p = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.efectivo_bitacora WHERE id_Efectivo = 1 AND id_UDN = ? AND Fecha_Efectivo >= ? AND Fecha_Efectivo ".$opc." ?";
   $query_e = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.efectivo_bitacora WHERE id_Efectivo = 2 AND id_UDN = ? AND Fecha_Efectivo >= ? AND Fecha_Efectivo ".$opc." ?";
  }

  $sql_p = $this->_Select($query_p,$array,"5");
  foreach($sql_p as $row_p);
  if(!isset($row_p[0])){ $row_p[0] = 0; }

  $sql_e = $this->_Select($query_e,$array,"5");
  foreach($sql_e as $row_e);
  if(!isset($row_e[0])){ $row_e[0] = 0; }

  $total = $row_e[0] - $row_p[0];
  return $total;
 }
 function Select_Retiro_Efectivo_Moneda($opc,$idE,$idM,$date_retiro,$date2){
  $row = null; $array = array();
  if($date_retiro == null){
   $array = array($idM,$idE,$date2);
   $query = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda ".$opc." ?";
  }
  else{
   $array = array($idM,$idE,$date_retiro,$date2);
   $query = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_Moneda = ? AND id_UDN = ? AND Fecha_Moneda > ? AND Fecha_Moneda ".$opc." ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }

  return $row[0];

 }
 function Select_Total_Salidas_Efectivo($idE,$date1,$date2){
  $row = null; $array = array();
  if(!isset($date1)){
   $array = array($idE,$date2);
   $query = "SELECT ROUND(SUM(Retiro_Total),2) FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro <= ?";
  }
  else{
   $array = array($idE,$date1,$date2);
   $query = "SELECT ROUND(SUM(Retiro_Total),2) FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro > ? AND Fecha_Retiro = ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Total_Deuda_Proveedor($idUP,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idUP,$date1);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras = ?";
  }
  else{
   $array = array($idUP,$date1,$date2);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_Total_Pago_Proveedor($idUP,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idUP,$date1);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras = ?";
  }
  else{
   $array = array($idUP,$date1,$date2);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_SUM_Total_Entradas($idUG,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idUG,$date1);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UG = ? AND Fecha_Compras = ?";
  }
  else{
   $array = array($idUG,$date1,$date2);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UG = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }
 function Select_SUM_Total_Salidas($idUG,$date1,$date2){
  $row = null; $array = array();
  if($date1 == $date2){
   $array = array($idUG,$date1);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UG = ? AND Fecha_Compras = ?";
  }
  else{
   $array = array($idUG,$date1,$date2);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UG = ? AND Fecha_Compras BETWEEN ? AND ?";
  }

  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 //Rembolso  y Retiro
 function Select_Total_Rembolso($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE;  $array[1] = $date1;
   $query2 = "SELECT ROUND(SUM(Rembolso),2) AS Rembolso FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso = ?";
  }
  else{
   $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
   $query2 = "SELECT ROUND(SUM(Rembolso),2) AS Rembolso FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso BETWEEN ? AND ?";
  }

  $sql2 = $this->_Select($query2,$array,"5");
  foreach ($sql2 as $data_rembolso);

  return $data_rembolso[0];
 }

 function Select_Total_Retiros($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE;  $array[1] = $date1;
   $query2 = "SELECT ROUND(SUM(Retiro_Total),2) AS Retiro FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro = ?";
  }
  else{
   $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
   $query2 = "SELECT ROUND(SUM(Retiro_Total),2) AS Retiro FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ?";
  }

  $sql2 = $this->_Select($query2,$array,"5");
  foreach ($sql2 as $data_retiro);

  return $data_retiro[0];
  // return 0;
 }
 function Select_Total_efectivos($idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idE;  $array[1] = $date1;
   $query2 = "SELECT ROUND(SUM(Retiro_Efectivo),2) AS Retiro FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro = ?";
  }
  else{
   $array[0] = $idE;  $array[1] = $date1; $array[2] = $date2;
   $query2 = "SELECT ROUND(SUM(Retiro_Efectivo),2) AS Retiro FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ?";
  }

  $sql2 = $this->_Select($query2,$array,"5");
  foreach ($sql2 as $data_retiro);

  return $data_retiro[0];
 }
 function Select_Total_Retiro_Moneda($idM,$idE,$date1,$date2){
  $array = array();
  if($date1 == $date2){
   $array[0] = $idM; $array[1] = $idE;  $array[2] = $date1;
   $query2 = "SELECT ROUND(SUM(Retiro_Moneda),2) FROM hgpqgijw_finanzas.retiroventa_moneda,hgpqgijw_finanzas.retiros_venta WHERE id_RetiroVenta = idRetiroVenta AND id_Moneda = ? AND id_UDN = ? AND Fecha_Retiro = ?";
  }
  else{
   $array[0] = $idM; $array[1] = $idE;  $array[2] = $date1; $array[3] = $date2;
   $query2 = "SELECT ROUND(SUM(Retiro_Moneda),2) FROM hgpqgijw_finanzas.retiroventa_moneda,hgpqgijw_finanzas.retiros_venta WHERE id_RetiroVenta = idRetiroVenta AND id_Moneda = ? AND id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ?";
  }

  $sql2 = $this->_Select($query2,$array,"5");
  foreach ($sql2 as $data_retiro);

  return $data_retiro[0];
 }

 //MOVIMIENTO DE CREDITO
 function Delete_Mov_Consumo($idC){
  $array = array($idC);
  $query = "DELETE FROM hgpqgijw_finanzas.creditos_consumo WHERE idCC = ?";
  $this->_DIU($query,$array,"5");
 }
 function Delete_Mov_Bitacora($idP){
  $array = array($idP);
  $query = "DELETE FROM hgpqgijw_finanzas.creditos_bitacora WHERE idBC = ?";
  $this->_DIU($query,$array,"5");
 }




 //CONSULTA DE FONDO FIJO DE ACUERDO A LAS FECHAS DADAS
 function Select_Fondo_Fijo($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT Fecha_Rembolso,SF FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso < ? ORDER BY Fecha_Rembolso DESC LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Gasto_Remaster($idE,$date_SI,$date1) {
  $row = null;
  if( !isset($date_SI) ) {
   $array = array($idE,$date1);
   $query = "SELECT ROUND(SUM(Gasto),2)
   FROM hgpqgijw_finanzas.compras
   where id_CG = 3 AND id_UDN = ? AND Fecha_Compras < ?";
  }
  else{
   $array = array($idE,$date_SI,$date1);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras where id_CG = 3 AND id_UDN = ? AND Fecha_Compras >= ? AND Fecha_Compras < ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Ancitipo_Remaster($idE,$date_SI,$date1) {
  $row = null;
  if ( !isset($date_SI) ) {
   $array = array($idE,$date1);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE Empleado_Anticipo = idEmpleado AND UDN_Empleado = ? AND Fecha_Anticipo < ?";
  }
  else {
   $array = array($idE,$date_SI,$date1);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE Empleado_Anticipo = idEmpleado AND UDN_Empleado = ? AND Fecha_Anticipo >= ? AND Fecha_Anticipo < ?";
  }
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Pago_Proveedor_Remaster ($idE,$dateSI,$date1) {
  $row = null;
  if ( !isset($dateSI) ) {
   $array = array($idE,$dateSI);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE Status = 2  AND id_UP IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras < ?";
  }
  else {
   $array = array($idE,$dateSI,$date1);
   $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE Status = 2  AND id_UP IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras >= ? AND Fecha_Compras < ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Gasto_Remaster_actual($idE,$date1,$date2) {
  $row = null;
  $array = array($idE,$date1,$date2);
  $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras where id_CG = 3 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Ancitipo_Remaster_actual($idE,$date1,$date2) {
  $row = null;
  $array = array($idE,$date1,$date2);
  $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE Empleado_Anticipo = idEmpleado AND UDN_Empleado = ? AND Fecha_Anticipo BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Pago_Proveedor_Remaster_actual($idE,$date1,$date2) {
  $row = null;
  $array = array($idE,$date1,$date2);
  $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE Status = 2  AND id_UP IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  return $row[0];
 }
 function Select_Rembolso_Remaster($idE,$date1,$date2) {
  $row = null;
  $array = array($idE,$date1,$date2);
  $query = "SELECT ROUND(SUM(Rembolso),2) FROM hgpqgijw_finanzas.retiros WHERE id_UDN = ? AND Fecha_Rembolso  BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 //CONSULTA DE RETIRO DE VENTAS
 function Select_SI_Retiro_Efectivo($idE,$date1) {
  $array = array($idE,$date1);
  $query = "SELECT Fecha_Retiro,SF_Total,SF_Efectivo,idRetiroVenta FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro < ? ORDER BY Fecha_Retiro DESC LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_Efectivo_Remaster($idE,$date_SI,$date1,$idEfectivo) {
  $row = null;
  if ( !isset($date_SI) ) {
   $array = array($idE,$date_SI,$idEfectivo);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.efectivo_bitacora WHERE id_UDN = ? AND Fecha_Efectivo < ? AND id_Efectivo = ?";
  }
  else {
   $array = array($idE,$date_SI,$date1,$idEfectivo);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.efectivo_bitacora WHERE id_UDN = ? AND Fecha_Efectivo >= ? AND Fecha_Efectivo < ? AND id_Efectivo = ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Efectivo_Remaster_actual($idE,$date1,$date2,$idEfectivo) {
  $row = null;
  $array = array($idE,$date1,$date2,$idEfectivo);
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM efectivo_bitacora WHERE id_UDN = ? AND Fecha_Efectivo BETWEEN ? AND ? AND id_Efectivo = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Moneda_Remaster_actual($idE,$date1,$date2,$idM) {
  $row = null;
  $array = array($idE,$date1,$date2,$idM);
  $query = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda BETWEEN ? AND ? AND id_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Retiro_Remaster($idE,$date1,$date2) {
  $row = null;
  $array = array($idE,$date1,$date2);
  $query = "SELECT ROUND(SUM(Retiro_Total),2) FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Comprobacion_Retiro_Efectivo($idE,$date1,$date2) {
  $array = array($idE,$date1,$date2);
  $query = "SELECT Fecha_Retiro FROM hgpqgijw_finanzas.retiros_venta WHERE id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Retiro_Efectivo_Resmaster($idE,$date1,$date2) {
  $row = null;
  $array = array($idE,$date1,$date2);
  $query = "SELECT ROUND(SUM(Retiro_Efectivo),2) FROM retiros_venta WHERE id_UDN = ? AND Fecha_Retiro BETWEEN ? AND ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_SF_Moneda_Remaster_Valor($idRetiroVenta,$idMoneda) {
  $row = null;
  $array = array($idRetiroVenta,$idMoneda);
  $query = "SELECT ROUND(SUM(SF_Moneda*Valor),2) FROM retiroventa_moneda WHERE id_RetiroVenta = ? AND id_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_SF_Moneda_Remaster_SN_Valor($idRetiroVenta,$idMoneda) {
  $row = null;
  $array = array($idRetiroVenta,$idMoneda);
  $query = "SELECT ROUND(SUM(SF_Moneda),2) FROM retiroventa_moneda WHERE id_RetiroVenta = ? AND id_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Moneda_Remaster($idE,$date_SI,$date1,$idM) {
  $row = null;
  if ( !isset($date_SI) ) {
   $array = array($idE,$date_SI,$idM);
   $query = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda < ? AND id_Moneda = ?";
  }
  else {
   $array = array($idE,$date_SI,$date1,$idM);
   $query = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda >= ? AND Fecha_Moneda < ? AND id_Moneda = ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Moneda_Remaster_SNValor($idE,$date_SI,$date1,$idM) {
  $row = null;
  if ( !isset($date_SI) ) {
   $array = array($idE,$date_SI,$idM);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda < ? AND id_Moneda = ?";
  }
  else {
   $array = array($idE,$date_SI,$date1,$idM);
   $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda >= ? AND Fecha_Moneda < ? AND id_Moneda = ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Moneda_Remaster_actual_SNValor($idE,$date1,$date2,$idM) {
  $row = null;
  $array = array($idE,$date1,$date2,$idM);
  $query = "SELECT ROUND(SUM(Cantidad),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda BETWEEN ? AND ? AND id_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Moneda_Remaster_actual_Valor($idE,$date1,$date2,$idM) {
  $row = null;
  $array = array($idE,$date1,$date2,$idM);
  $query = "SELECT ROUND(SUM(Cantidad*Valor),2) FROM hgpqgijw_finanzas.moneda_extranjera_bitacora WHERE id_UDN = ? AND Fecha_Moneda BETWEEN ? AND ? AND id_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Retiro_Moneda_Actual_SNV($idRetiro,$idMoneda) {
  $row = null;
  $array = array($idE,$date1,$date2,$idM);
  $query = "";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_idRetiro_Hoy($idE,$date) {
  $row = null;
  $array = array($idE,$date);
  $query = "SELECT idRetiroVenta FROM retiros_venta WHERE id_UDN = ? AND Fecha_Retiro = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Retiro_Moneda_Hoy_SNValor($idRetiro,$idMoneda) {
  $row = null;
  $array = array($idRetiro,$idMoneda);
  $query = "SELECT Retiro_Moneda FROM retiroventa_moneda WHERE id_RetiroVenta = ? AND id_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Retiro_Moneda_Hoy($idRetiro,$idMoneda) {
  $row = null;
  $array = array($idRetiro,$idMoneda);
  $query = "SELECT ROUND(Retiro_Moneda*Valor,2) FROM retiroventa_moneda WHERE id_RetiroVenta = ? AND id_Moneda = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }

 //FONDO DE CAJA
 function Select_Fondo_Caja_Remaster($idE,$date_SI,$date_now) {
  $row = null;
  if ( !isset($date_SI) ) {
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras <= ?";
  }
  else {
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras BETWEEN ? AND ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Anticipos_Remaster_fondo($idE,$date_SI,$date_now){
  if( isset($date_SI) ){
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') >= ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') <= ?";
  }
  else{
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') <= ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_Pago_Proveedor_Remaster_Fondo($idE,$date_SI,$date_now){
  if(isset($date_SI)){
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  >= ? AND Fecha_Compras <= ?";
  }
  else{
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  <= ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }


 //REMBOLSO DE FONDO DE CAJA
 function Select_FechaRembolso_Remaster($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT Fecha_Rembolso,SF FROM retiros WHERE id_UDN = ? AND Fecha_Rembolso < ? ORDER BY Fecha_Rembolso DESC LIMIT 1";
  $sql = $this->_Select($query,$array,"5");
  return $sql;
 }
 function Select_GastosRembolso_Remaster($idE,$date_rembolso,$date) {
  $row = null;
  if ( !isset($date_rembolso) ) {
   $array = array($idE,$date);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras < ?";
  }
  else {
   $array = array($idE,$date_rembolso,$date);
   $query = "SELECT ROUND(SUM(Gasto),2) FROM compras WHERE id_UG IS NOT NULL AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras >= ? AND Fecha_Compras < ?";
  }
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_AnticiposRembolso_Remaster($idE,$date_SI,$date_now){
  if( !isset($date_SI) ){
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') < ?";
  }
  else{
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Saldo),2) FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') >= ? AND DATE_FORMAT(Fecha_Anticipo, '%Y-%m-%d') < ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"6");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_ProveedorRembolso_Remaster($idE,$date_SI,$date_now){
  if(!isset($date_SI)){
   $array = array($idE,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  < ?";
  }
  else{
   $array = array($idE,$date_SI,$date_now);
   $query = "SELECT ROUND(SUM(Pago),2) FROM compras WHERE id_UP IS NOT NULL AND Status = 2 AND id_CG = 3 AND id_UDN = ? AND Fecha_Compras  >= ? AND Fecha_Compras < ?";
  }
  $row = null;
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_RembolsoFondo_Remaste($idE,$date) {
  $array = array($idE,$date);
  $query = "SELECT Rembolso FROM retiros WHERE id_UDN = ? AND Fecha_Rembolso = ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }
 function Select_ExisteRembolso_Remaste($idE,$dateRem,$date_now) {
  $array = array($idE,$dateRem,$date_now);
  $query = "SELECT Rembolso FROM retiros WHERE id_UDN = ? AND Fecha_Rembolso > ? AND Fecha_Rembolso <= ?";
  $sql = $this->_Select($query,$array,"5");
  foreach($sql as $row);
  if ( !isset($row[0]) ) { $row[0] = 0; }
  return $row[0];
 }


 /*-----------------------------------*/
 /*  GASTOS - COMPRAS
 /*-----------------------------------*/

 function	GastosF($array,$id_Clase){
  $id_CG="";
  $key  ="";

  if ($id_Clase!=0) {
   $id_CG = "and id_CG = ".$id_Clase." ";
  }

  $sql=
  "SELECT
  Fecha_Compras
  FROM
  hgpqgijw_finanzas.compras
  WHERE
  STATUS = 1
  ".$id_CG."
  AND id_UDN = ?
  AND Fecha_Compras BETWEEN ?
  AND ? and factura is not null
  GROUP BY Fecha_Compras";


  $ps	=	$this->_Select($sql,$array);
  foreach ($ps as $key );
  return	$ps;

 }

 function	GastosSUM($array,$id_Clase){
  $total  = 0;
  $id_CG="";

  if ($id_Clase!=0) {
   $id_CG = "and id_CG = ".$id_Clase." "; // 3
  }


  $sql=
  "SELECT
  ROUND( SUM( Gasto ), 2 )
  FROM
  hgpqgijw_finanzas.compras
  WHERE
  id_UDN = ?
  ".$id_CG."
  and factura is not null
  AND Fecha_Compras BETWEEN ?
  AND ?";

  $ps	=	$this->_Select($sql,$array);

  foreach ($ps as $key) {
   $total  = $key[0];
  }

  return	  $total ;
 }

 function GastosFechas($array){
  $row = null;
  $bd  = "hgpqgijw_finanzas.";

  $new='';
  $new = array($array[0],$array[1],$array[2]);

  $opc="AND id_CG = ? ";
  if($array[2]==0){
   $opc="";
   $new = array($array[0],$array[1]);
  }

  $query = "SELECT ROUND(SUM(Gasto),2) as Saldo
  FROM ".$bd."compras
  WHERE  id_UDN = ? AND Fecha_Compras = ?
  and factura is not null ".$opc." ";
  $sql = $this->_Select($query,$new,"5");
  foreach($sql as $row);
  if(!isset($row[0])){ $row[0] = 0; }
  return $row[0];
 }

 function	GastosNOM($array,$id_Clase){
  $id_CG="";

  if ($id_Clase!=0) {
   $id_CG = "and id_CG = ".$id_Clase." "; // 3
  }

  $sql="
  SELECT
  idUI,
  Name_IC,
  ROUND( SUM( Gasto ), 2 ) AS Saldo
  FROM
  hgpqgijw_finanzas.insumos_clase,
  hgpqgijw_finanzas.insumos_udn,
  hgpqgijw_finanzas.compras
  WHERE
  idIC = id_IC
  AND idUI = id_UI
  ".$id_CG."
  and factura is not null
  AND insumos_udn.id_UDN = ?
  AND Fecha_Compras BETWEEN ?
  AND ?
  GROUP BY
  Name_IC
  ORDER BY
  Saldo DESC";

  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 function	FoldingGastos($array,$id_Clase){

  $id_CG="";

  if ($id_Clase!=0) {
   $id_CG = "and id_CG = ".$id_Clase." "; // 3
  }

  $sql="
  SELECT idUG,Name_Gastos,ROUND(SUM(Gasto),2) as Saldo
  FROM hgpqgijw_finanzas.gastos,hgpqgijw_finanzas.gastos_udn,hgpqgijw_finanzas.compras
  WHERE idGastos = id_Gastos AND
  idUG = id_UG
  ".$id_CG." AND
  id_UI = ? AND
  compras.id_UDN = ? AND
  Fecha_Compras BETWEEN ? AND ? and factura is not null
  GROUP BY Name_Gastos ORDER BY Saldo DESC";


  $ps	=	$this->_Select($sql,$array);
  return	$ps;
 }

 /*-----------------------------------*/
 /*  Detalles de Compras [04-04-2019]
 /*-----------------------------------*/

function	content_data($array){
 $key = null;
		$sql="
  SELECT
  	proveedor.Name_Proveedor,
  	gastos.Name_Gastos,
   compras.Gasto,
   insumos_clase.Name_IC,
  	gasto_clase.Name_CG,
  	compras.Observacion,
  	compras.Fecha_Compras,
  	sobres.Archivo,
  	sobres.Ruta
  FROM
  	hgpqgijw_finanzas.compras
  LEFT JOIN hgpqgijw_finanzas.proveedor_udn ON compras.id_UP = proveedor_udn.idUP
  LEFT JOIN hgpqgijw_finanzas.proveedor ON proveedor_udn.id_Proveedor = proveedor.idProveedor
  LEFT JOIN hgpqgijw_finanzas.gastos_udn ON compras.id_UG = gastos_udn.idUG
  LEFT JOIN hgpqgijw_finanzas.gastos ON gastos_udn.id_Gastos = gastos.idGastos
  LEFT JOIN hgpqgijw_finanzas.gasto_clase ON compras.id_CG = gasto_clase.idCG
  LEFT JOIN hgpqgijw_finanzas.insumos_udn ON compras.id_UI = insumos_udn.idUI
  LEFT JOIN hgpqgijw_finanzas.insumos_clase ON insumos_udn.id_IC = insumos_clase.idIC
  LEFT JOIN hgpqgijw_finanzas.fcompras ON fcompras.id_compras = compras.idCompras
  LEFT JOIN hgpqgijw_finanzas.sobres ON fcompras.id_file = sobres.idSobre
   WHERE
  compras.idCompras = ?



  ";
		$ps	=	$this->_Select($sql,$array);

  foreach ($ps as $key );
		return	$key;
	}

}

?>