<?php
// ARCHIVOS EXTERNOS
include_once ('_CRUD.php');

Class direccion extends CRUD
{






 // ---------------------------------------------------
 //    admin-users
 // ---------------------------------------------------

 function dataUsers($array){

  $sql="SELECT
  Usuario,
  Nombre_Nivel,
  Email,
  gerente,
  Permiso,
  udn.UDN,
  Area_Usuario,
  idUsuario
  FROM usuarios,nivel,udn
  WHERE Nivel=idNivel and
  idUDN=usuarios.UDN";
  $ps = $this->_Select($sql,$array);

  return $ps;
 }

 function dataArea($array){

  $sql="SELECT * FROM area ";

  $ps = $this->_Select($sql,$array);


  return $ps;
 }


 function dataNivel($array){

  $sql="SELECT * FROM nivel WHERE idNivel <> 0";

  $ps = $this->_Select($sql,$array);


  return $ps;
 }

 function dataudn($array){

  $sql="SELECT idUDN,UDN FROM udn ";

  $ps = $this->_Select($sql,$array);


  return $ps;
 }

function newUsers($array){
	$sql = "INSERT INTO usuarios (Usuario,Password,Nivel,email,gerente,UDN,Area_Usuario) VALUES (?,SHA(?),?,?,?,?,?)";

 $ps = $this->_DIU($sql,$array);


 return $ps;
}

function Delete_User($array){
	$sql =
 "DELETE
FROM
	usuarios
WHERE
	idUsuario = ?";

 $ps = $this->_DIU($sql,$array);


 return $ps;
}

function dataUsers_1($array){

 $sql=
 "SELECT
 *
 FROM usuarios
 WHERE idUsuario=? ";
 $ps = $this->_Select($sql,$array);

 return $ps;
}

function UpdateUsers($array){
	$sql =
 "UPDATE usuarios set usuario=?,
 nivel=?,
 email=?,
 gerente=?,
 udn=?,
 area_usuario=? WHERE idUsuario=?";

 $ps = $this->_DIU($sql,$array);


 return $ps;
}

}
?>
