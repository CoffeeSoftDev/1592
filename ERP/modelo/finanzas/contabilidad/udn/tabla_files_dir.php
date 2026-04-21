
<?php
session_start();

include_once("../../../SQL_PHP/_CRUD.php");
include_once("../../../SQL_PHP/_Finanzas.php");
include_once("../../../SQL_PHP/_Utileria.php");

//Se declaran los utiletos para utilizar las funciones segun los archivos externos
$crud = new CRUD;
$util = new Util;
$fin = new Finanzas;

$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$udn = $_POST['udn'];

if($date1 == $date2){
  $var = "UDN_Sobre = ".$udn." AND Fecha = '".$date1."'";
}
else{
  $var = "UDN_Sobre = ".$udn." AND Fecha BETWEEN '".$date1."' AND '".$date2."'";
}

/*TRATAMIENTO - PAGINACIÓN -SQL */



$sqlCount = "SELECT COUNT(*) FROM hgpqgijw_finanzas.sobres WHERE ".$var;


// echo $sqlCount;
$nroSol = $crud->_Select($sqlCount,null,"5");







foreach ($nroSol as $noS);

/***************************************************
            VARIABLES / PAGINACIÓN
****************************************************/
$paginaActual = $_POST['pag'];
$Paginas= $noS[0];
$url= "ver_tabla_files";
$Lotes = 15;
// $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

//Consulta de la tabla

$query = "SELECT Archivo,ROUND(Peso,2),Hora,Type_File,Ruta,Fecha 
FROM hgpqgijw_finanzas.sobres WHERE ".$var." ORDER BY Fecha asc ";

$sql = $crud->_Select($query,null,"5");


 $tb = '<div ><br>';
 $tb .= '<table id="viewFolios" class="table table-bordered  table-condensed "  >';
 
  /*----------THEAD------------*/
  $tb .= '<thead><tr>';
  $tb .= '
          <th class="text-center">Fecha</th>
          <th class="text-center">Hora</th>
          <th class="text-center">Archivo</th>
          <th class="text-center">Descarga</th>
         
         ';

  $tb .= '</tr></thead>';  

   foreach ($sql as $key) {
     
     if($key[0] != '' ){
      $tb .= '<tr>';
      $tb .= '<td class="text-center">' . $key[5] . '</td>';
      $tb .= '<td class="text-center">' . $key[2] . '</td>';
      $tb .= '<td>' . $key[0] . '</td>';
      $tb .= '<td class="text-center"><a 
      class="btn btn-info btn-xs" 
      title="Visualizar" 
      href="     ' . $key[4] . '' . $key[0] . ' " target="_blank"><i class="bx icon-eye"></i></a></td>';
      $tb .= '</tr>';
     }
     
   }



 $tb .= '</tbody>';
 $tb .= '</table></div>';


 echo $tb;




?>

