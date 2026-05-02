<?php
	Class Conection {

		public function __construct(){
			$host   = "15-92.com";
			$user   = "hgpqgijw_desarrollo";
			$pass   = "c(SomxD)3";
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
