<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');


Class CXC extends CRUD{

  function  Ver_Folio($array){

     $sql = "
     SELECT
     idFolio,
     Folio,
     Fecha
     FROM
     hgpqgijw_finanzas.folio
     WHERE

     date_format(Fecha,'%Y-%m-%d') BETWEEN   ? AND ?

     ";

     $ps = $this->_Select($sql,$array,"1");
     return $ps;
  }

  function  ver_bitacora_ventas($array){

     $sql = "
      SELECT
        bitacora_ventas.idVentasBit,
        subcategoria.Subcategoria,
        bitacora_ventas.Subtotal
      FROM
        hgpqgijw_finanzas.bitacora_ventas
      INNER JOIN
        hgpqgijw_finanzas.subcategoria
      ON
        bitacora_ventas.id_Subcategoria = subcategoria.idSubcategoria
      WHERE
        id_Folio = ?  ";

     $ps = $this->_Select($sql,$array,"1");

     return $ps;

  }

  function  bitacora_formas_pago($array){

       $sql = "
        SELECT
          idFP_Bitacora,
          id_FormasPago,
          id_Bitacora,
          Monto,
          id_tipoPago,
          DATE_FORMAT(payment_date, '%d/%m/%Y') AS payment_date,
          observation
        FROM
          hgpqgijw_finanzas.bitacora_formaspago
        WHERE
          id_Bitacora = ?  and id_FormasPago = 3";

       $ps = $this->_Select($sql,$array,"1");

       return $ps;

    }

  function Update_Producto($array){
     $query =
     "UPDATE hgpqgijw_finanzas.bitacora_formaspago

     SET id_tipoPago= ?
     WHERE idFP_Bitacora=?

     ";
     $this->_DIU($query,$array,"2");
  }

  function Update_PaymentDate($array){
     $query =
     "UPDATE hgpqgijw_finanzas.bitacora_formaspago

     SET payment_date= ?
     WHERE idFP_Bitacora=?

     ";
     $this->_DIU($query,$array,"2");
  }

    function Update_Observation($array){
     $query =
     "UPDATE hgpqgijw_finanzas.bitacora_formaspago

     SET observation= ?
     WHERE idFP_Bitacora=?

     ";
     $this->_DIU($query,$array,"2");
  }


  function  bitacora_formas_pago_full($array){

    $sql = "
    SELECT
    hgpqgijw_finanzas.bitacora_formaspago.idFP_Bitacora,
    hgpqgijw_finanzas.bitacora_formaspago.id_FormasPago,
    hgpqgijw_finanzas.bitacora_formaspago.id_Bitacora,
    hgpqgijw_finanzas.bitacora_formaspago.Monto,
   
    hgpqgijw_finanzas.bitacora_formaspago.id_tipoPago,
    hgpqgijw_finanzas.tipo_formaspago.nombreFormasPago,
     hgpqgijw_finanzas.bitacora_formaspago.payment_date,
    hgpqgijw_finanzas.bitacora_formaspago.observation
    FROM
    hgpqgijw_finanzas.tipo_formaspago
    INNER JOIN hgpqgijw_finanzas.bitacora_formaspago
    ON hgpqgijw_finanzas.bitacora_formaspago.id_tipoPago = hgpqgijw_finanzas.tipo_formaspago.idFormasPago
    WHERE
    id_Bitacora = ?  and id_FormasPago = 3";

    $ps = $this->_Select($sql,$array,"1");

    return $ps;

  }

  function  select_tipoPago(){

     $sql = "
     SELECT
     *
     FROM
     hgpqgijw_finanzas.tipo_formaspago   ";

     $ps = $this->_Select($sql,null,"1");
     return $ps;
  }


}
