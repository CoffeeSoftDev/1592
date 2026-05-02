<?php
	/*EL CRUD ESTA DIVIDO EN SUS 4 RESPECTIVAS PARTICIONES*/

	include_once('_Conect.php');//SE LLAMA EL ARCHIVO DE CONEXION

	//SE CREA LA CLASE CON UNA EXTENCION DE LA CLASE CONEXION
	Class CRUD extends Conection
	{

		/******************	CREATE UPDATE DELETE  **********************/

		public function _DIU($query,$array){//se envian los parametros sql y los campos
			try {
				//Se crea una variable para acceder a las diferentes bases de datos
				$mysql = "mysql";
				//se crea una variable stmt= statement llamando a la variable
				//de conexion mysql utilizando la funcion prepare para usar prepared statement
				$stmt = $this->$mysql->prepare($query);

				//determinamos el maximo de datos de un arreglo
				$j = count($array);

				//se crea un for para insertar,eliminar o actualizar los campos de n cantidad en el arreglo
				for ($i=0; $i < $j; $i++) {
					$x = $i + 1;
					$stmt->bindParam($x,$array[$i]);
				}

				//se ejecuta la sentencia
				$stmt->execute();
			} catch (PDOException $e) {
				echo "ERROR " . $e->getMessage();
			}
		}


		/******************	READ  **********************/

		public function _Select($query,$array){
			try {
				//Se crea una variable para acceder a las diferentes bases de datos
				$mysql = "mysql";
				//se crea una variable stm para almacenar el query
				$stm = $this->$mysql->prepare($query);
				//si el array contiene datos o es null se envia el stm a la conexion
				if ($array == null) {
					$stm->execute();
				}
				else{
					$stm->execute($array);
				}
				//almacenamos el resultado que la consulta en una variable para poder retornarla
				$resultado = $stm->fetchAll();
				return $resultado;
			} catch (Exception $e) {
				echo "ERROR " . $e->getMessage();
			}
		}
	}

?>
