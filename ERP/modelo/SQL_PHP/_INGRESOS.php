<?php
include_once ('_CRUD.php');

Class INGRESOS extends CRUD {
    
  function CONSULTAR_CHEQUE_PROMEDIO($array){

   $sql = "
   SELECT
    bitacora_ventas.id_Folio,
    bitacora_ventas.pax,
    bitacora_ventas.id_Subcategoria,
    bitacora_ventas.Subtotal,
    bitacora_ventas.Noche,
    folio.Fecha
   FROM
   hgpqgijw_finanzas.bitacora_ventas
   INNER JOIN hgpqgijw_finanzas.folio ON bitacora_ventas.id_Folio = folio.idFolio
   WHERE 
   MONTH(folio.Fecha) = ?
   AND YEAR(folio.Fecha) = ?
   AND id_Subcategoria = ?";

   $ps = $this->_Select($sql,$array,"1");
   return $ps;  
  }    

  function VER_CATEGORIAS_INGRESOS($udn){

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
   id_UDN = ? and idCategoria <> 12 and idCategoria <>13 and idCategoria <>11 and idCategoria <>9";

   $ps = $this->_Select($sql,$array,"1");
   return $ps;
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

  function Select_Subcategoria_x_grupo($id,$idGrupo){
    $array = array($id,$idGrupo);
    $query = "SELECT idSubcategoria,Subcategoria,id_grupo 
    FROM hgpqgijw_finanzas.subcategoria WHERE id_Categoria = ? and Stado = 1 
    and id_grupo=?  order by idSubcategoria asc";
    $sql = $this->_Select($query,$array);
    return $sql;
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
    grupo.idgrupo";
    $ps	=	$this->_Select($sql,$array);
    return	$ps;
   }

  function ver_ingresos_turismo($array){

  //  $array = array($date1,$date2,$idC);

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
   
  //  foreach($sql as $row3);
  //  if ( !isset($row3[0]) ) { $row3[0] = 0; }
  //  return $row3[0];

  return $sql;
  }

}