<?php
	Class Conection {

		public function __construct(){
			$host   = "localhost";
			$user   = "root";
			$pass   = "";
			$opc = array(
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
				);

      $BD1	= "hgpqgijw_usuarios";

			try {
				$this->mysql = new PDO('mysql:host='.$host.';dbname='.$BD1,$user,$pass,$opc);
				$this->mysql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			} catch (PDOException $e) {
				echo "ERROR! ". $e->getMessage();
			}
		}
	}
?>
