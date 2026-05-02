<?php
// ARCHIVOS EXTERNOS
include_once('_CRUD.php');

class DIA_VENTAS extends CRUD
{

  /* ------------------------------------ */
  /*          Historial de ventas         */
  /* ------------------------------------ */
  function  list_ticket($array)
  {

    $sql  = "
  SELECT
  lista_folio.idLista,
  lista_folio.folio,
  Name_Cliente,
  date_format(fecha,'%Y-%m-%d'),
  Total,
  
  id_DatosFactura,
  id_tipo,
  idFactura,
  Mixto,
  Efectivo,
  
  TDC,
  Credito
  
  FROM
  hgpqgijw_dia.lista_folio
  INNER JOIN hgpqgijw_dia.clientes ON lista_folio.elaboro = clientes.idCliente
  WHERE
  lista_folio.id_Estado = 1
  
  and DATE_FORMAT(fecha,'%Y-%m-%d') Between  ? and ?  GROUP BY idLista
  order by idLista desc
  ";

    $ps   =   $this->_Select($sql, $array, '1');
    return  $ps;
  }

  function _TICKET_INFO($array)
  {
    $KEY = null;
    $sql  = "
  SELECT
  lista_folio.Mixto,
  lista_folio.Efectivo,
  lista_folio.TDC,
  lista_folio.Credito,

  lista_folio.id_DatosFactura,
  lista_folio.idFactura,
  lista_facturas.folio,
  lista_folio.fecha,
 
  clientes.Name_Cliente,
  lista_folio.folio,
  id_tipo
  
  FROM
  hgpqgijw_dia.lista_folio
  LEFT JOIN hgpqgijw_dia.clientes ON lista_folio.elaboro = clientes.idCliente
  LEFT JOIN hgpqgijw_dia.lista_facturas ON lista_folio.idFactura = lista_facturas.idFactura 
  WHERE
  idLista = ?
  ";

    $ps   =   $this->_Select($sql, $array, '1');
    foreach ($ps as $KEY);
    return  $KEY;
  }

  function Existe_Factura($array)
  {
    $folio = '';
    $query = "
      SELECT
      idFactura
      FROM
      hgpqgijw_dia.lista_facturas
      WHERE Folio = ? ";

    $sql = $this->_Select($query, $array, '4');

    foreach ($sql as $key) {
      $folio = $key[0];
    }

    return $folio;
  }

  function Select_Productos($array)
  {

    $query = "
  SELECT
  venta_productos.NombreProducto,
  lista_productos.cantidad,
  lista_productos.costo,
  ROUND((cantidad*costo),2),
  lista_productos.precio_especial,
  presentacion,
  lista_productos.idListaProductos,
  nota
  FROM
  hgpqgijw_dia.lista_folio
  INNER JOIN hgpqgijw_dia.lista_productos ON lista_productos.id_lista = lista_folio.idLista
  INNER JOIN hgpqgijw_dia.venta_productos ON lista_productos.id_productos = venta_productos.idProducto
  WHERE id_lista = ? and id_Canasta is null";

    $sql = $this->_Select($query, $array, '4');
    return $sql;
  }

  function Select_Canasta($array)
  {

    $query = "
  SELECT
  Canasta,
  lista_canasta.Cantidad,
  lista_canasta.costo,
  lista_canasta.Precio_especial,
  lista_canasta.Nota,
  lista_canasta.idListaCanasta
  FROM
  hgpqgijw_dia.lista_folio
  INNER JOIN hgpqgijw_dia.lista_canasta ON lista_canasta.id_Lista = lista_folio.idLista
  INNER JOIN hgpqgijw_dia.venta_canasta ON lista_canasta.id_Canasta = venta_canasta.idCanasta
  WHERE id_lista = ? and id_Canasta is null";

    $sql = $this->_Select($query, $array, '4');
    return $sql;
  }

  /* ------------------------------------ */
  /*               Clientes               */
  /* ------------------------------------ */

  function verFacturas($array)
  {
    $sql = "
  SELECT
  lista_facturas.idFactura,
  lista_facturas.Folio,
  
  lista_folio.folio,
  clientes.Name_Cliente,


  DATE_FORMAT(lista_facturas.Fecha_Factura,'%d-%m-%Y'),
  datos_facturacion.CFDI,
  datos_facturacion.Razon,

  datos_facturacion.CP,
  datos_facturacion.RFC,

  datos_facturacion.direccion,

  datos_facturacion.email,
  datos_facturacion.telefono,

  id_tipo
  FROM
  hgpqgijw_dia.lista_facturas
  LEFT JOIN hgpqgijw_dia.lista_folio ON lista_folio.idFactura = lista_facturas.idFactura
  LEFT JOIN hgpqgijw_dia.datos_facturacion ON lista_folio.id_DatosFactura = datos_facturacion.idFactura
  LEFT JOIN hgpqgijw_dia.clientes ON lista_folio.elaboro = clientes.idCliente
  WHERE Fecha_Factura between ? and ?
  ";
    $ps   =   $this->_Select($sql, $array, '1');
    return  $ps;
  }

  function Insert_Files($array)
  {
    $query = "INSERT INTO hgpqgijw_dia.archivos (UDN_Sobre,Ruta,Fecha,Hora,Archivo,Peso,Type_File,id_Factura) 
  VALUES (?,?,?,?,?,?,?,?)";
    $this->_DIU($query, $array);
  }

  function archivo_factura($array)
  {
    $sql = 'SELECT Ruta,Archivo FROM hgpqgijw_dia.archivos WHERE id_Factura=? ';
    $ps   =   $this->_Select($sql, $array, '1');
    return  $ps;
  }

  function verTodosLosdepositos($array)
  {
    $query = '
    SELECT
      SUM(factura_abono.abono)
      FROM
      hgpqgijw_dia.factura_abono
      INNER JOIN hgpqgijw_dia.lista_folio ON factura_abono.id_lista = lista_folio.idLista
      WHERE Credito <> 0 and elaboro = ?';

    $sql   =   $this->_Select($query, $array, '1');
    return  $sql;
  }

  function ver_montos($array)
  {
    $query = 'SELECT
    idAbono,
    DATE_FORMAT(Fecha_abono,"%d-%m-%Y"),
    DATE_FORMAT(Fecha_abono,"%h:%i:%s"),
    
    factura_abono.abono,
    factura_abono.detalles,
    factura_abono.id_lista
    FROM hgpqgijw_dia.factura_abono
    WHERE id_lista = ?';

    $sql   =   $this->_Select($query, $array, '1');
    return  $sql;
  }

  function rows_pan()
  {
    $sql = "
  SELECT
  idCliente,
  Name_Cliente,

  Telefono,
  Email,
  
  dias_credito,
  ROUND(SUM(lista_folio.Credito),2),
  
  credito_activo
  FROM
  hgpqgijw_dia.clientes
  LEFT JOIN hgpqgijw_dia.lista_folio ON hgpqgijw_dia.lista_folio.elaboro = hgpqgijw_dia.clientes.idCliente
  -- WHERE credito_activo is not null
  WHERE estado_cliente = 1
  
  GROUP BY idCliente order by credito_activo desc";

    $ps   =   $this->_Select($sql, null, '1');
    return  $ps;
  }

  function get_id_factura()
  {
    $idFactura = 0;
    $sql = ' SELECT idFactura FROM hgpqgijw_dia.lista_facturas order by idFactura desc limit 1';
    $Query = $this->_Select($sql, null);
    foreach ($Query as $Key) {
      $idFactura = $Key[0];
    }
    return $idFactura;
  }

  function get_Cliente($array)
  {
    $_DATA = null;
    $query = "SELECT * FROM hgpqgijw_dia.clientes WHERE idCliente = ?";

    $sql   = $this->_Select($query, $array);
    foreach ($sql as $_DATA);
    return $_DATA;
  }

  function factura_clientes($array)
  {
    $query = "
    SELECT
    lista_folio.idLista,
    lista_facturas.Folio,
    fecha,
    Credito,
    elaboro,
    lista_folio.folio,
    id_tipo
    FROM
    hgpqgijw_dia.lista_folio
    LEFT JOIN hgpqgijw_dia.lista_facturas ON lista_folio.idFactura = lista_facturas.idFactura
    WHERE Credito <> 0 and elaboro = ?
    order by idLista desc ";

    $sql = $this->_Select($query, $array, '4');
    return $sql;
  }

  function get_abono($array)
  {
    $key = 0;
    $sql  = "
  SELECT
  SUM(abono)
  FROM hgpqgijw_dia.factura_abono
  WHERE id_lista = ?
  
  ";

    $ps   =   $this->_Select($sql, $array, '1');
    foreach ($ps as $key);

    return  $key;
  }

  function get_ult_abono($array)
  {
    $DATE = '';
    $sql  = "
  SELECT
  DATE_FORMAT(Fecha_abono,'%d-%m-%Y')
  FROM hgpqgijw_dia.factura_abono
  WHERE id_lista = ?
  order by id_lista desc limit 1";

    $ps   =   $this->_Select($sql, $array, '1');
    foreach ($ps as $key) {
      $DATE = $key[0];
    }

    return  $DATE;
  }


  function  list_ticket_forma_pago($array, $fp)
  {

    $sql  = "
  SELECT
  lista_folio.idLista,
  lista_folio.folio,
  Name_Cliente,
  date_format(fecha,'%Y-%m-%d'),
  Total,
  
  id_DatosFactura,
  id_tipo,
  idFactura,
  Mixto,
  Efectivo,
  
  TDC,
  Credito
  
  FROM
  hgpqgijw_dia.lista_folio
  INNER JOIN hgpqgijw_dia.clientes ON lista_folio.elaboro = clientes.idCliente
  WHERE
  lista_folio.id_Estado = 1 
  and " . $fp . " <> 0
  and DATE_FORMAT(fecha,'%Y-%m-%d') Between  ? and ?  GROUP BY idLista
  order by idLista desc
  ";

    $ps   =   $this->_Select($sql, $array, '1');
    return  $ps;
  }
}