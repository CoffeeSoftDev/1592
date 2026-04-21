 <?php
 // ARCHIVOS EXTERNOS
 include_once ('_CRUD.php');

 $bd ="";

 Class METAS extends CRUD{
  /*=============================================
  COMPONENTTES
  ===============================================*/

  function	AÑO_VIGENTE(){

   $query	=
   "SELECT YEAR
   ( Fecha )
   FROM
   hgpqgijw_finanzas.folio
   GROUP BY
   YEAR ( Fecha ) ORDER BY YEAR
   ( Fecha ) DESC";

   $sql	=	$this->_Select($query,null);

   return	$sql;
  }


  function VER_PROPINA($id,$anio,$mes,$opc,$propina){
   $rs = 0;
   $OPC1 ="";
   $array = array($id,$propina);

   if ($opc==2) {
    $OPC1 ="and MONTH(folio.Fecha) =".$mes;
   }


   $sql="
   SELECT
   id_FormasPago,
   FormasPago,
   SUM(Monto),Fecha
   FROM
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria
   WHERE
   idFormas_Pago =id_FormasPago AND
   idVentasBit   =id_Bitacora   AND
   id_Folio      =idFolio       AND
   id_Subcategoria = idSubcategoria AND
   id_FormasPago =? and id_Categoria=? ".$OPC1."
   AND YEAR(folio.Fecha) = ".$anio."
   Group by id_FormasPago
   " ;



   $ps = $this->_Select($sql,$array,"2");
   foreach ($ps as $key ) {
    $rs = $key[2];
   }
   return $rs;

  }

  function  VER_INGRESOS($id,$anio,$mes,$opc){
   $rs = 0;
   $OPC1 ="";
   $array = array($id);

   if ($opc==2) {
    $OPC1 ="and MONTH(folio.Fecha) =".$mes;
   }


   $sql = "
   SELECT
   categoria.Categoria,
   SUM(bitacora_ventas.Subtotal),

   subcategoria.idSubcategoria,
   subcategoria.Subcategoria,

   bitacora_ventas.Subtotal,
   folio.Fecha,

   folio.Folio,
   idCategoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.folio
   WHERE
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcate+-goria and
   subcategoria.id_Categoria = categoria.idCategoria and
   bitacora_ventas.id_Folio = folio.idFolio
   and idCategoria = ? ".$OPC1."
   AND YEAR(folio.Fecha) = ".$anio."
   Group by categoria.Categoria ";

   $ps = $this->_Select($sql,$array,"2");
   foreach ($ps as $key ) {
    $rs = $key[1];
   }
   return $rs;

  }

  function VER_CATEGORIAS($udn){

   $rs = "";
   $array = array($udn);

   $sql = "
   SELECT

   categoria.idCategoria,
   categoria.Categoria,
   categoria.id_TMovimiento

   FROM

   hgpqgijw_finanzas.categoria

   WHERE
   id_UDN = ? and idCategoria <> 12 and idCategoria <>13";

   $ps = $this->_Select($sql,$array,"1");
   return $ps;
  }

  function VER_FORMAS_PAGO(){
   $rs = "";
   $array = array(null);

   $sql = "
   SELECT
   *
   FROM
   hgpqgijw_finanzas.formas_pago;
   ";

   $ps = $this->_Select($sql,$array,"1");
   return $ps;
  }

  function  VER_TIPOSPAGOS($id,$anio,$mes,$opc){
   $rs = 0;
   $OPC1 ="";
   $array = array($id);

   if ($opc==2) {
    $OPC1 ="and MONTH(folio.Fecha) =".$mes;
   }


   $sql="
   SELECT
   id_FormasPago,
   FormasPago,
   SUM(Monto),Fecha
   FROM
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria
   WHERE
   idFormas_Pago =id_FormasPago AND
   idVentasBit   =id_Bitacora   AND
   id_Folio      =idFolio       AND
   id_Subcategoria = idSubcategoria AND
   Subcategoria <> 'PROPINAS'   AND

   id_FormasPago =? ".$OPC1."
   AND YEAR(folio.Fecha) = ".$anio."
   Group by id_FormasPago
   " ;



   $ps = $this->_Select($sql,$array,"2");
   foreach ($ps as $key ) {
    $rs = $key[2];
   }
   return $rs;

  }

  // ===========================================

  function TOTAL_GENERAL($anio,$mes,$opc){
   $rs=0;
   $OPC1 = "";
   if ($opc==2) {
    $OPC1 ="and MONTH(folio.Fecha) =".$mes;
   }
   $array = array(null);
   $sql ="
   SELECT
   bitacora_ventas.id_Folio,
   folio.Fecha,
   folio.Folio,
   Sum(Monto)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.subcategoria
   WHERE
   bitacora_ventas.id_Folio = folio.idFolio and
   bitacora_formaspago.id_Bitacora = bitacora_ventas.idVentasBit and
   bitacora_formaspago.id_FormasPago = formas_pago.idFormas_Pago and
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria
   ".$OPC1."
   AND YEAR(folio.Fecha) = ".$anio."
   ";

   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[3];
   }
   return $rs;

  }
  // -------------------------------
  function  GRAFICA_AÑO($f1,$f2){
   $rs = "";
   $array = array(null);

   $sql = "
   SELECT
   YEAR(Fecha) AS ANIO,
   ROUND(SUM(Subtotal),2)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio
   WHERE
   bitacora_ventas.id_Folio = folio.idFolio and
   YEAR(Fecha) BETWEEN '".$f1."' AND '".$f2."'

   GROUP BY ANIO
   ";

   $ps = $this->_Select($sql,$array,"1");

   return $ps;

  }

  // -------------------------------
  function TOTAL_GENERAL_AREA($anio,$mes,$opc,$area){
   $rs=0;
   $OPC1 = "";
   if ($opc==2) {
    $OPC1 ="and MONTH(folio.Fecha) =".$mes;
   }
   $array = array($area);
   $sql ="
   SELECT
   categoria.Categoria,
   subcategoria.idSubcategoria,
   subcategoria.Subcategoria,
   SUM(bitacora_ventas.Subtotal),
   bitacora_ventas.Subtotal,
   folio.Fecha,
   folio.Folio,
   idCategoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.folio
   WHERE
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria and
   subcategoria.id_Categoria = categoria.idCategoria and
   bitacora_ventas.id_Folio = folio.idFolio  AND
   idCategoria= ?
   ".$OPC1."
   AND YEAR(folio.Fecha) = ".$anio."
   ";

   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[3];
   }
   return $rs;

  }

  function TOTAL_GENERAL_SNIVA($anio){
   $sql ="
   SELECT
   categoria.Categoria,
   subcategoria.idSubcategoria,
   subcategoria.Subcategoria,
   SUM(Subtotal),
   folio.Fecha,
   folio.Folio,
   idCategoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.folio
   WHERE
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria and
   subcategoria.id_Categoria = categoria.idCategoria and
   bitacora_ventas.id_Folio = folio.idFolio
   AND YEAR(folio.Fecha) = ".$anio."
   ";

   $ps = $this->_Select($sql,null,"1");
   foreach ($ps as $key ) {
    $rs = $key[3];
   }
   return $rs;
  }

  /*==========================================
  *	GRAFICAS X AREA
  =============================================*/

  function GRAFICA($mes,$año){
   $rs=0;
   $array = array($mes,$año);
   $sql ="
   SELECT
   categoria.Categoria,
   subcategoria.idSubcategoria,
   subcategoria.Subcategoria,
   ROUND(SUM(Subtotal),2),
   MONTH(fecha) Mes,
   folio.Folio,
   idCategoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.folio
   WHERE
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria and
   subcategoria.id_Categoria = categoria.idCategoria and
   bitacora_ventas.id_Folio = folio.idFolio and
   MONTH(Fecha) = ? and YEAR(fecha)=?
   GROUP BY YEAR(fecha),MONTH(fecha)
   ";

   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[3];
   }
   return $rs;
  }

  function GRAFICAxAREA($categoria,$año){
   $rs=0;
   $array = array($categoria,$año);
   $sql ="
   SELECT
   ROUND(Sum(bitacora_ventas.Subtotal),2),
   folio.Fecha,
   categoria.Categoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria
   WHERE
   id_Folio = idFolio
   and id_Subcategoria = idSubcategoria
   and id_Categoria = idCategoria
   and id_Categoria = ?
   and YEAR(Fecha) = ?

   GROUP BY
   categoria.idCategoria

   ";

   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[0];
   }
   return $rs;
  }

  function VENTAS_x_AÑO($fi,$ff,$opc){
   $rs=0;
   $CON ="and YEAR(Fecha)  BETWEEN '".$fi."' and '".$ff."'" ;
   if($opc==2){
    $CON =" and YEAR(Fecha)='".$fi. "' " ;
   }


   $array = array(null);
   $sql ="
   SELECT
   categoria,
   ROUND(Sum(bitacora_ventas.Subtotal),2),
   folio.Fecha,
   categoria.Categoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria
   WHERE
   id_Folio = idFolio
   and id_Subcategoria = idSubcategoria
   and id_Categoria = idCategoria
   ".$CON."

   GROUP BY
   categoria.idCategoria";

   $ps = $this->_Select($sql,$array,"1");

   return $ps;
  }

  /*===========================================
  *									GRAFICAS GENERALES
  =============================================*/

  function VENTAS_AÑO($fi,$id){
   $rs=0;

   $CON =" and YEAR(Fecha)='".$fi. "' " ;

   $array = array($id);
   $sql ="
   SELECT
   categoria,
   ROUND(Sum(bitacora_ventas.Subtotal),2),
   folio.Fecha,
   categoria.Categoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria
   WHERE
   id_Folio = idFolio
   and id_Subcategoria = idSubcategoria
   and id_Categoria = idCategoria
   and idCategoria = ?
   ".$CON."

   GROUP BY
   categoria.idCategoria";

   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key) {
    $rs = $key[1];
   }
   return $rs;
  }



  /*==========================================
  *	RESUMEN GENERAL POR FECHA
  =============================================*/
  function VER_INGRESOS_RANGO_FECHA($fi,$ff,$opc){
   $rs = 0;
   $OPC1 ="";
   if ($opc==1) {

    $OPC1 =" AND YEAR(Fecha) BETWEEN '".$fi."' AND '".$ff."' ";
   }


   $sql = "
   SELECT
   categoria.Categoria,
   SUM( bitacora_ventas.Subtotal ),
   subcategoria.idSubcategoria,
   subcategoria.Subcategoria,
   folio.Fecha,
   folio.Folio,
   idCategoria,
   idFolio
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.folio
   WHERE
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria
   AND subcategoria.id_Categoria = categoria.idCategoria
   AND bitacora_ventas.id_Folio = folio.idFolio
   ".$OPC1."
   GROUP BY categoria ORDER BY idCategoria asc

   ";

   $ps = $this->_Select($sql,null,"1");


   return $ps;

  }

  function Observacion($valor,$idObservacion){
   $array = array($valor,$idObservacion);
   $query = "UPDATE hgpqgijw_finanzas.folio SET Observacion = ? WHERE idFolio = ?";
   $this->_DIU($query,$array,"2");
  }

  function verFolio($fi,$udn){

   $array =array($udn);
   $sql = "
   SELECT
   *
   FROM
   hgpqgijw_finanzas.folio
   WHERE
   Fecha = '".$fi."'
   and id_UDN = ? ";

   $ps = $this->_Select($sql,$array,"1");

   return $ps;
  }

  function  VER_INGRESOS_FECHA($id,$fi,$ff){
   $rs = 0;

   $array = array($id);

   $sql = "
   SELECT
   categoria.Categoria,
   SUM(bitacora_ventas.Subtotal),
   subcategoria.idSubcategoria,
   subcategoria.Subcategoria,
   bitacora_ventas.Subtotal,
   folio.Fecha,
   folio.Folio,
   idCategoria
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.subcategoria,
   hgpqgijw_finanzas.categoria,
   hgpqgijw_finanzas.folio
   WHERE
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria and
   subcategoria.id_Categoria = categoria.idCategoria and
   bitacora_ventas.id_Folio = folio.idFolio
   and idCategoria = ?
   AND Fecha BETWEEN '".$fi."' AND '".$ff."'";

   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[1];
   }
   return $rs;
  }

  function  VER_TIPOSPAGOS_FECHA($id,$fi,$ff){
   $rs = 0;
   $OPC1 ="";
   $array = array($id);

   $sql="
   SELECT
   id_FormasPago,
   FormasPago,
   SUM(Monto),Fecha
   FROM
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria
   WHERE
   idFormas_Pago =id_FormasPago AND
   idVentasBit   =id_Bitacora   AND
   id_Folio      =idFolio       AND
   id_Subcategoria = idSubcategoria AND
   subcategoria.id_categoria <> 9   AND

   id_FormasPago =?
   AND Fecha BETWEEN '".$fi."' AND '".$ff."'
   ";



   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[2];
   }
   return $rs;

  }

  function ver_tipos_pagos($array){
//    SELECT
// formas_pago.idFormas_Pago,
// formas_pago.FormasPago,
// bitacora_formaspago.Monto,
// folio.Fecha
// FROM
// folio
// INNER JOIN bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
// INNER JOIN bitacora_formaspago ON bitacora_formaspago.id_Bitacora = bitacora_ventas.idVentasBit
// INNER JOIN formas_pago ON bitacora_formaspago.id_FormasPago = formas_pago.idFormas_Pago
// WHERE 

// AND  MONTH (Fecha) = 1
//         AND YEAR (Fecha) = 2023
  }

  function VER_PROPINA_FECHA($id,$fi,$ff,$propina){
   $rs = 0;
   $array = array($id,$propina);

   $sql="
   SELECT
   id_FormasPago,
   FormasPago,
   SUM(Monto),
   Fecha
   FROM
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria
   WHERE
   idFormas_Pago =id_FormasPago AND
   idVentasBit   =id_Bitacora   AND
   id_Folio      =idFolio       AND
   id_Subcategoria = idSubcategoria AND
   id_FormasPago = ? and subcategoria.id_categoria = ?

   AND Fecha BETWEEN '".$fi."' AND '".$ff."'
   Group by id_FormasPago
   " ;



   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[2];
   }
   return $rs;

  }

  /*===========================================
  *					      CARATULA INGRESOS
  =============================================*/
  function	caratula_ingresos($fecha){

   $ingreso  = 0;

   $query	=
   "
   SELECT
   bitacora_ventas.id_Folio,
   folio.Fecha,
   folio.Folio,
   Sum(Monto)
   FROM
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.subcategoria
   WHERE
   bitacora_ventas.id_Folio = folio.idFolio and
   bitacora_formaspago.id_Bitacora = bitacora_ventas.idVentasBit and
   bitacora_formaspago.id_FormasPago = formas_pago.idFormas_Pago and
   bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria
   AND folio.Fecha = '".$fecha."'
   ";

   $sql	=	$this->_Select($query,null,"1");
   foreach ($sql as $key ) {
    $ingreso = $key[3];
   }

   return	$ingreso;
  }

  function ver_ingresos_turismo($date1,$date2,$idC){

   $array = array($date1,$date2,$idC);

   $query = "
   SELECT
   SUM(Monto),
   bitacora_ventas.id_Subcategoria,
   bitacora_ventas.idVentasBit,
   bitacora_formaspago.id_Bitacora,
   subcategoria.id_Categoria,
   subcategoria.Subcategoria
   FROM
   hgpqgijw_finanzas.folio
   INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Folio = folio.idFolio
   INNER JOIN hgpqgijw_finanzas.bitacora_formaspago ON bitacora_formaspago.id_Bitacora = bitacora_ventas.idVentasBit
   INNER JOIN hgpqgijw_finanzas.subcategoria ON bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria
   WHERE
   folio.Fecha BETWEEN ? AND ?
   and id_Categoria = ?
   ";


   $sql = $this->_Select($query,$array);
   foreach($sql as $row3);
   if ( !isset($row3[0]) ) { $row3[0] = 0; }
   return $row3[0];
  }
  /*-----------------------------------*/
  /*		Caratula gral
  /*-----------------------------------*/

  function	formas_pago($id,$fi,$ff,$opc){
   $OPC = 'subcategoria.id_categoria  = 1  AND';

   if($opc ==1) {
    $OPC = 'subcategoria.id_categoria <> 1  AND';
   }

   $rs = 0;
   $OPC1 ="";
   $array = array($id);

   $sql="
   SELECT
   id_FormasPago,
   FormasPago,
   SUM(Monto),Fecha
   FROM
   hgpqgijw_finanzas.formas_pago,
   hgpqgijw_finanzas.bitacora_formaspago,
   hgpqgijw_finanzas.bitacora_ventas,
   hgpqgijw_finanzas.folio,
   hgpqgijw_finanzas.subcategoria
   WHERE
   idFormas_Pago =id_FormasPago AND
   idVentasBit   =id_Bitacora   AND
   id_Folio      =idFolio       AND
   id_Subcategoria = idSubcategoria AND
   subcategoria.id_categoria <> 9   AND
   ".$OPC."
   id_FormasPago =?
   AND Fecha BETWEEN '".$fi."' AND '".$ff."'
   ";



   $ps = $this->_Select($sql,$array,"1");
   foreach ($ps as $key ) {
    $rs = $key[2];
   }
   return $rs;

  }
  /*-----------------------------------*/
  /* ** DESARROLLO MOD
  /*-----------------------------------*/
  function Select_formaspago_by_categoria($array){
   $query = "SELECT idFormas_Pago,FormasPago FROM hgpqgijw_finanzas.formas_pago WHERE grupo = ?";
   $sql = $this->_Select($query,$array);
   return $sql;
  }


  function Select_formaspago_by_categoria_2($array){
   $query = "SELECT idFormas_Pago,FormasPago FROM hgpqgijw_finanzas.formas_pago WHERE grupo = ? AND idFormas_Pago <> 8 and idFormas_Pago <> 10 ";
   $sql = $this->_Select($query,$array);
   return $sql;
  }


  function	Select_empleadosCortesia($array){
   $sql="  SELECT
   subcategoria.id_Categoria,
   subcategoria.Subcategoria,
   bitacora_ventas.Subtotal,
   bitacora_ventas.Tarifa,
   folio.Fecha
   FROM
   hgpqgijw_finanzas.subcategoria
   INNER JOIN hgpqgijw_finanzas.bitacora_ventas ON bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria
   INNER JOIN hgpqgijw_finanzas.folio ON bitacora_ventas.id_Folio = folio.idFolio
   WHERE
   subcategoria.id_Categoria = 12 AND
   subcategoria.Stado = 1 AND
   Fecha BETWEEN ? AND ?
   ";

   $ps	=	$this->_Select($sql,$array);
   return	$ps;
  }

    /*-----------------------------------*/
    /*		Cargos de habitacion
    /*-----------------------------------*/

    function ver_cargos_hab($id,$fi,$ff){
        $rs = 0;

        $array = array($id);

        $sql="
        SELECT
        idFormas_Pago,
        FormasPago,
        SUM(Monto),
        Fecha
        FROM
        hgpqgijw_finanzas.formas_pago,
        hgpqgijw_finanzas.bitacora_formaspago,
        hgpqgijw_finanzas.bitacora_ventas,
        hgpqgijw_finanzas.folio,
        hgpqgijw_finanzas.subcategoria
        WHERE
        idFormas_Pago =id_FormasPago AND
        idVentasBit   =id_Bitacora   AND
        id_Folio      =idFolio       AND
        id_Subcategoria = idSubcategoria AND
        subcategoria.id_categoria <> 9   AND

        id_FormasPago = ?
        AND Fecha BETWEEN '".$fi."' AND '".$ff."'";

        $ps = $this->_Select($sql,$array,"1");
        foreach ($ps as $rs );

        return $rs;

    }

    function	ver_nombre_fp($array){
        $fp = 'null ';
        $sql="SELECT FormasPago FROM hgpqgijw_finanzas.formas_pago Where idFormas_Pago = ? ";
        $ps	=	$this->_Select($sql,$array);

        foreach ($ps as $key) {
            $fp = $key[0];
        }
        return	$fp;
    }


 }
 ?>
