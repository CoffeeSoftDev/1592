<?php
	//ARCHIVO ENCARGADO DE SUBIR ARCHIVOS
	session_start();
	if (!isset($_SESSION['nivel'])) {
		echo "<script> window.location = '../../../index.php'</script>";
	}
	else {
		include_once('../SQL_PHP/_Utileria.php');
		include_once('../SQL_PHP/_Finanzas.php');
		$utileria = new Util;
		$finanzas = new Finanzas;
		$prefijo = $utileria -> Logo_Empresa($_SESSION['empresa']);
		$fecha = date('n');
		$mes_letra = $utileria -> mesLetra($fecha);
		$directorio = '../../recursos/sobres_file/'.$mes_letra.'/';
		$nombre_fichero_solicitud = basename($_FILES['GrupoVAROCH']['name']);
		$new_nombre_fichero = $prefijo . "-" . $nombre_fichero_solicitud;
		$nombre_fichero_upload = $directorio . $new_nombre_fichero;
		$gestor_dir = opendir($directorio);//Abro la carpeta contenedora
		$contador = 0;
		while (false !== ($nombre_fichero = readdir($gestor_dir))) {
			if($nombre_fichero != "." && $nombre_fichero != "..") {
				if($nombre_fichero == $new_nombre_fichero) {
					$contador++;
					$consulta = $finanzas -> Select_Archivos();
					$extension = $utileria -> getExtension ($nombre_fichero_upload); //Obtengo la extension de los archivos
					$nombre_sin_ext = basename($nombre_fichero_upload, ".".$extension); //Obtengo el nombre del archivo sin extension
					$new_nombre_fichero = $nombre_sin_ext . " (" . $contador . ")" . "." . $extension;
					$nombre_fichero_upload = $directorio . $new_nombre_fichero;

					foreach ($consulta as $dato) {
						if(($nombre_sin_ext . " (" . $contador . ")" . "." . $extension) == $dato[0]) {
							$contador++;
							$new_nombre_fichero = $nombre_sin_ext . " (" . $contador . ")" . "." . $extension;
							$nombre_fichero_upload = $directorio . $new_nombre_fichero;
						}
					}
				}
			}
		}
	  	closedir($gestor_dir); //Cierro la carpeta contenedora
		//FICHERO SUBIDO
		if (move_uploaded_file($_FILES['GrupoVAROCH']['tmp_name'], $nombre_fichero_upload)) {
			$hora = date("H:i:s", time());
			$newDate = date("Y-m-d");
			$peso = $_FILES['GrupoVAROCH']['size'];
			$peso = $peso/1024;
			$array = array($directorio, $newDate, $hora, $new_nombre_fichero, $_SESSION['empresa'], $peso);
			$finanzas -> Insert_Sobres($array);
			echo 1;
		//FICHERO NO SUBIDO
		}else {
			echo 2;
		}
	}
?>
