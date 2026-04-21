<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class SQL extends CRUD {
  function NOW() {
    $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
    $sql = $this->_Select($query,null);
    foreach($sql as $row);
    return $row[0];
  }
    
    
    
function _UPDATE($array,$campos,$bd,$where){
    $set       = '';
    $Condicion = '';
    $query     = '';

    $query .= " UPDATE  ".$bd." SET " ;

    for ($i=0; $i < count($campos); $i++) {
     $set .= $campos[$i]."=?, ";
    }

    $set  = substr($set,0,strlen($set)-2);


    for ($i=0; $i < count($where); $i++) {
     $Condicion .= $where[$i]." = ? and ";
    }

    $Condicion     = substr($Condicion,0,strlen($Condicion)-4);
    $query .= $set." WHERE ".$Condicion;

    $this->_DIU($query,$array,"3");

    return $query;
   }




   function _INSERT($array,$campos,$bd){
		$data ='';
		$x    ='';
		$query ='';
		$query = $query."INSERT INTO ".$bd."(" ;

		for ($i=0; $i < count($campos); $i++) {
			$data = $data."".$campos[$i].",";
			$x    = $x.'?,';
		}

		$data  = substr($data,0,strlen($data)-1);
		$x     = substr($x,0,strlen($x)-1);

		$query = $query.$data.") VALUES (".$x.")";
		$this->_DIU($query,$array);

		return $query;
	}

 function	Select_data($campos,$bd,$where,$array,$opc){
  $Condicion = '';
if ($where!=null) {
  for ($i=0; $i < count($where); $i++) {
   $Condicion .= $where[$i]." = ? and ";
  }
}

  $Condicion     = substr($Condicion,0,strlen($Condicion)-4);

  // -----
  switch ($opc){
   case 0:
   $sql = "SELECT ".$campos." FROM ". $bd . " WHERE " . $Condicion;
   break;
   case 1:
   $sql = "SELECT ".$campos." FROM ". $bd ." ";
   break;
  }

  $ps	=	$this->_Select($sql,$array,"1");
  return	$ps;

 }
}


 ?>
