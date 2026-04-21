<?php
    include_once("_CRUD.php");

Class PERFIL extends CRUD {
		function Select_Login($array) {
			$row = null;
			$query = "SELECT Nivel,Email,Gerente,UDN,idUsuario,Area_Usuario FROM usuarios WHERE Usuario = ? AND Password = SHA(?)";
			$sql = $this->_Select($query,$array,"1");
			foreach ($sql as $row);
			return $row;
		}
		function Select_User($array) {
			$row = null;
			$query = "SELECT Usuario FROM usuarios WHERE Usuario = ?";
			$sql = $this -> _Select($query, $array, "1");
			foreach ($sql as $row);
			return $row[0];
		}
		function Select_User_Id($array) {
			$row = null;
			$query = "SELECT Usuario FROM usuarios WHERE idUsuario = ?";
			$sql = $this -> _Select($query, $array, "1");
			foreach ($sql as $row);
			return $row[0];
		}
		function Select_Pass ($array){
			$row = null;
			$query = "SELECT Password FROM usuarios WHERE Password = SHA(?) AND idUsuario = ?";
			$sql = $this-> _Select($query,$array,"1");
			foreach ($sql as $row);
			return $row[0];
		}
		function Select_Datos_Usuarios_Id ($array) {
			$sql = 0;
			$query = "SELECT usuario, nivel, email, gerente, UDN FROM  Usuarios WHERE idUsuario = ?";
			$sql = $this -> _Select($query, $array, "1");
			return $sql;
		}
		function Select_Datos_Usuario (){
			$query = "SELECT idUsuario, usuario, Gerente, Nombre_Nivel, udn.UDN FROM nivel,usuarios,udn WHERE Nivel = idNivel AND usuarios.UDN = idUDN ORDER BY idUsuario ASC";
			$sql = $this -> _Select($query, null, "1");
			return $sql;
		}
		function Select_Datos_Usuario_Empresa ($array) {
			$sql = 0;
			$query = "SELECT idUsuario, usuario, Gerente, Nombre_Nivel, udn.UDN FROM nivel,usuarios,udn WHERE Nivel = idNivel AND usuarios.UDN = idUDN AND udn.idUDN = ? ORDER BY idUsuario ASC";
			$sql = $this -> _Select($query, $array, "1");
			return $sql;
		}
		function Select_UDN(){
			$query = "SELECT  idUDN, UDN FROM udn WHERE Stado = 1 AND idUDN != 9 ORDER BY UDN DESC";
			$sql = $this->_Select($query, null, "1");
			return $sql;
		}
    function Select_Name_UDN($id){
      $array = array($id);
      $query = "SELECT UDN FROM udn WHERE idUDN = ?";
      $sql = $this->_Select($query,$array,"1");
      foreach($sql as $row);
      return $row[0];
    }
		function Select_Nivel (){
			$query = "SELECT  idNivel, Nombre_Nivel FROM nivel ORDER BY Nombre_Nivel ASC";
			$sql = $this -> _Select($query, null, "1");
			return $sql;
		}
		function Update_Perfil_Pass($array){
			$query = "UPDATE usuarios SET Usuario = ?, Email = ?, Gerente = ?, Password = SHA(?) WHERE idUsuario = ?";
			$this->_DIU($query,$array,"1");
		}
		function Update_Perfil($array){
			$query = "UPDATE usuarios SET Usuario = ?,Email = ?,Gerente = ? WHERE idUsuario = ?";
			$this->_DIU($query,$array,"1");
		}
		function Update_User($array) {
			$query = "UPDATE usuarios SET Usuario = ?, Nivel = ?, Email = ?, Gerente = ?, UDN = ? WHERE idUsuario = ?";
			$this->_DIU($query,$array,"1");
		}
		function Update_User_Pass ($array) {
			$query = "UPDATE usuarios SET Usuario = ?, Password = SHA(?), Nivel = ?, Email = ?, Gerente = ?, UDN = ? WHERE idUsuario = ?";
			$this -> _DIU($query,$array, "1");
		}
		function Insert_User($array) {
			$query = "INSERT INTO usuarios(Usuario, Password, Nivel, Email, Gerente, UDN) VALUES (?, SHA(?), ?, ?, ?, ?)";
			$this -> _DIU($query, $array, "1");
		}
		function Delete_User($array) {
			$query = "DELETE FROM Usuarios WHERE idUsuario = ?";
			$this -> _DIU($query, $array, "1");
		}
  }
?>
