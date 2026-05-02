<?php
session_start();

if (!isset($_SESSION['nivel'])) {
 echo "<script> window.location = '';</script>";

}else {


 include_once('../../../modelo/SQL_PHP/_MTTO.php');
 $admin    = new MTTO;

 include_once('../../../modelo/SQL_PHP/_Finanzas_ADMIN.php');
 $finanzas = new Finanzas;

/* var */
 $idE      = $_SESSION['udn'];
 $date_now = $admin->NOW();
 $date     = $_POST['date'];


 $Detalles = $_POST['Detalles'];
 $idEquipo = $_POST['idEquipo'];
 $time_now = $admin->HoursNOW();

 $y = date("Y", strtotime("$date_now"));
 $m = date("m", strtotime("$date_now"));
 $d = date("d", strtotime("$date_now"));


 $mes = date('m');
 switch ($mes) {
  case 1:$mes  = "Enero";break;
  case 2:$mes  = "Febrero";break;
  case 3:$mes  = "Marzo";break;
  case 4:$mes  = "Abril";break;
  case 5:$mes  = "Mayo";break;
  case 6:$mes  = "Junio";break;
  case 7:$mes  = "Julio";break;
  case 8:$mes  = "Agosto";break;
  case 9:$mes  = "Septiembre";break;
  case 10:$mes = "Octubre";break;
  case 11:$mes = "Noviembre";break;
  case 12:$mes = "Diciembre";break;
 }


// ubicar carpeta & asignar nombre al archivo

 $file_C  = $mes.'_'.$y;
 $ruta    = 'recursos/gastos_file/'.$file_C.'/';
 $carpeta = '../../../'.$ruta;

 if (!file_exists($carpeta)) { // Crear carpeta
  mkdir($carpeta, 0777, true);
 }

// Procesamiento de archivos

 foreach ($_FILES as $cont => $key)  {
  if($key['error'] == UPLOAD_ERR_OK )//Si el archivo se paso correctamente Continuamos
  {
   $res = intval(preg_replace('/[^0-9]+/', '', $cont), 10);
   $NombreOriginal = $key['name'];//Obtenemos el nombre original del archivo
   $temporal = $key['tmp_name']; //Obtenemos la ruta Original del archivo
   $Destino = '../../../'.$ruta.$NombreOriginal;	//Creamos una ruta de destino con la variable ruta y el nombre original del archivo

   $size = ($key['size']/1024)/1024; //tamaño en Kb, con 4 decimales
   if($size > 20){
    echo "El archivo <strong>".$NombreOriginal."</strong> pesa mas de 20 Mb<br>";
   }
   else{
    $array = array($idE,$ruta,$NombreOriginal);
    $existencia = $admin->Select_Archivos($array);


    if(isset($existencia)){
     echo "<label class='text-warning'><span class='icon-attention'></span></label>El archivo <strong>[ ".$NombreOriginal." ]</strong> ya existe en el servidor, intente con otro nombre.<br>";


    }else{
     // echo $NombreOriginal." no existe<br>|".$idEquipo;

     move_uploaded_file($temporal, $Destino);

     $trozos    = explode(".", $NombreOriginal);
     $extension = end($trozos);
     $size      = ROUND(($key['size']/1024),4); //tamaño en Kb, con 4 decimales

     // Subir registros a la base de datos.
     $array     = array(
     $ruta,
     $date,
     $time_now,
     $NombreOriginal,
     $size,
     $extension,
     $Detalles,
     $idE);

     $finanzas -> subirArchivo($array,$idEquipo);
     // echo "<span class="icon-check text-success"></span> Se ha subido correctamente.";
    }
   }
  }
  else {
   echo "<label class='text-danger'><span class='icon-cancel'></span>Ah ocurrido un error al subir el archivo</label>";
  }
 }



}
?>
