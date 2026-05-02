<?php
include_once("_CRUD.php");

  Class Finanzas extends CRUD {
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

    function Select_Saldo_Gasto_Proveedor($op,$array){
      $row = null;
      $query = "SELECT ROUND(SUM(Gasto),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND id_CG = 2 AND id_UDN = ? AND Fecha_Compras ".$op." ?";
      $sql = $this->_Select($query,$array,"5");
      foreach($sql as $row);
      if(!isset($row[0])){ $row[0] = 0; }
      return $row[0];
    }
    function Select_Saldo_Pagos_Proveedor($op,$array){
      $row = null;
      $query = "SELECT ROUND(SUM(Pago),2) FROM hgpqgijw_finanzas.compras WHERE id_UP IS NOT NULL AND Pago IS NOT NULL AND id_UDN = ? AND Fecha_Compras ".$op." ?";
      $sql = $this->_Select($query,$array,"5");
      foreach($sql as $row);
      if(!isset($row[0])){ $row[0] = 0; }
      return $row[0];
    }
  }
?>
