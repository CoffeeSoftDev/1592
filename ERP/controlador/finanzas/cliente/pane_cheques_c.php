<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas_Cheques.php');
$fin = new Files_Cheq;

$opc = $_POST['opc'];

switch ($opc) {

 case 0://SUBIR ARCHIVOS

 $date_now     = $fin->NOW();
 $idE          = $_SESSION['udn'];
 $Name_Cq      = $_POST['Name_Cq'];
 $Importe_Cq   = $_POST['Importe_Cq'];
 $Banco_Cq     = $_POST['Banco_Cq'];
 $Cuenta_Cq    = $_POST['Cuenta_Cq'];
 $Cheque_Cq    = $_POST['Cheque_Cq'];
 $Concepto_Cq  = $_POST['Concepto_Cq'];
 $date         = $_POST['date'];
 $Dest         = $_POST['Destino'];

 $idBan        = $fin->Select_Id_Banco($Banco_Cq);

 if ( $idBan == 0 ) {
  $fin->Insert_Banco($Banco_Cq);
  $idBan = $fin->Select_Id_Banco($Banco_Cq);
 }

 // $time_now = $fin->HoursNOW();
 $y = date("Y", strtotime("$date_now"));
 $m = date("m", strtotime("$date_now"));
 $d = date("d", strtotime("$date_now"));

 $mes = date('m');
 switch ($mes) {
  case 1:$mes = "Enero";break;
  case 2:$mes = "Febrero";break;
  case 3:$mes = "Marzo";break;
  case 4:$mes = "Abril";break;
  case 5:$mes = "Mayo";break;
  case 6:$mes = "Junio";break;
  case 7:$mes = "Julio";break;
  case 8:$mes = "Agosto";break;
  case 9:$mes = "Septiembre";break;
  case 10:$mes = "Octubre";break;
  case 11:$mes = "Noviembre";break;
  case 12:$mes = "Diciembre";break;
 }
 $file_C = $mes.'_'.$y;

 $ruta = 'recursos/cheques/'.$y.'/'.$file_C.'/'; //Decalaramos una variable con la ruta en donde almacenaremos los archivos
 $carpeta = '../../../'.$ruta;


 //Busca si existe la carpeta si no la crea
 if (!file_exists($carpeta)) {
  mkdir($carpeta, 0777, true);
 }

 foreach ($_FILES as $cont => $key) //Iteramos el arreglo de archivos
 {
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
    $existencia = $fin->Select_Archivos($array);
    if(isset($existencia)){
     echo "<label class='text-warning'><span class='icon-attention'></span></label>
     El archivo <strong>[ ".$NombreOriginal." ]</strong> ya existe en el servidor, debe cambiar el nombre para continuar.<br>";
    }
    else{

     move_uploaded_file($temporal, $Destino);

     $trozos    = explode(".", $NombreOriginal);
     $extension = end($trozos);
     $size      = ROUND(($key['size']/1024),4);


     $arrayID  =  array($Cuenta_Cq);
     $idCuenta =  $fin->idCuenta($arrayID);

     // Guardar registro
     $array    = array($idBan,$idE,$Importe_Cq,$idCuenta,$Cheque_Cq,$Name_Cq,$NombreOriginal,$Concepto_Cq,$ruta,$date,$Dest);
     $fin->Insert_Cheques($array);
     echo "<label class='text-success'><span class='icon-ok-circled'></span>
     Se ha agregado correctamente.</label>";


    } // si archivo existe
   }
  }
  else {
   echo "<label class='text-danger'><span class='icon-cancel'></span>Ah ocurrido un error al subir el archivo</label>";
  }
 }

 break;

 case 1://ELIMINAR
 $id = $_POST['id'];
 $fin->Delete_Cheque($id);
 break;


 case 2:
 $id    = $_POST['cuenta'];
 $array = array($id);
 $rp    = $fin->DetalleCuenta($array);
 $txt   = 1;

 if ($rp=='0') {
  $rp   ='<span class="icon-warning-empty text-warning"></span> Cuenta no encontrada';
  $txt   = 0;
 }else {
  $rp ='<span class="icon-ok-circled text-success"></span> '.$rp;
 }


 $encode = array(
  0=>$txt,
  1=>$rp);


  echo json_encode($encode);
  break;
 }

 ?>
