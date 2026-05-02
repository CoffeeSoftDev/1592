<?php
//Si la variable archivo que pasamos por URL no esta
//establecida acabamos la ejecucion del script.
if (!isset($_GET['archivo']) || empty($_GET['archivo'])) {
   exit();
}
else {
	$url = $_GET['url'];
	//Utilizamos basename por seguridad, devuelve el
	//nombre del archivo eliminando cualquier directorio.
	$archivo = $_GET['archivo'];
	$archivo = basename($_GET['archivo']);


	$directorio = $url.$archivo;
	if (is_file($directorio)) {
   		header('Content-Disposition: attachment; filename='.$archivo);
   		header('Content-Type: application/force-download');
   		header('Content-Transfer-Encoding: binary');
   		header('Content-Length: '.filesize($directorio));
   		readfile($directorio);
	}
	else {
		echo 0;
	}
}
?>
