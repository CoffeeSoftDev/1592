<?php
session_start();

include_once("../../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

$opc         = $_POST['opc'];
$categoria   = $_POST['categoria'];
$area        = $_POST['area'];
$txt         = "";
$array       = array($opc);

$json        = '';


switch ($opc) {
 /*-----------------------------------*/
 /*		Productos dados de alta
 /*-----------------------------------*/

 case 21: //ALMACEN DE EQUIPOS
  $categoria   = $_POST['categoria'];
  $area        = $_POST['area'];

  $sql_data    = $obj -> Show_DATA($categoria,$area,1);
  $cont   = 0;
  $opc    = 1;

  $v       =empty($x[8]);

  /*----------TABLA DE MATERIALES ------------*/
  // $tb = 'TOTAL: '.count($sql).' opc:'.$opc.' area:'.$area.' /'.$categoria;

  // $tb .= '<div class="table-responsive">';
  // $tb .= '<table id="viewFolios" class="table table-bordered  table-condensed table-hover "  style="width:100%; font-size:.78em;">';

  /*----------THEAD------------*/
  $th           = array('Códigox', 'Zona', 'Equipo', 'Categoria', 'Área','Cantidad','Costo','Utilidad','Estado','');
  $tb .= '<thead><tr>';
  for ($i = 0; $i < count($th); $i++) {
    $tb .= '<th class="text-center">' . $th[$i] . '</th>';
  }
  $tb .= '</tr></thead>';
  /*----------TBODY------------*/


  foreach ($sql_data as $key ) {
    $tb .= '<tr>';
    $tb .= '<td class="col-sm-1">' . $key[0] . '</td>';
    $tb .= '<td class="text-center ">' . $key[16] . '</td>';
    $tb .= '<td class="text-center ">' .$key[1].'</td>';
    $tb .= '<td class="text-center ">' .$key[7].'</td>';

    $tb .= '<td class="text-center ">' .$key[2].'</td>';
    $tb .= '<td class="text-right ">' .$key[5].'</td>';
    $tb .= '<td class="text-right ">' .evaluar($key[10]).'</td>';

    #------
    $ico      = ICOpoliza($x[8]);

    $a    = '<a data-toggle ="modal" data-target="#M1" class="btn btn-outline-warning btn-xss "
             onclick="ModalPoliza('.$x[4].','.$opc.')">
             <span class="'.$ico.'"></span>
             </a>';

    $b    = '<a onclick     = "verModalActualizar('.$x[4].')"
             data-toggle="modal"
             data-target="#Producto" class="btn btn-outline-info btn-xss "><span class="fa fa-pencil" > </span></a>';

    $c    = '<a class ="btn btn-outline-danger btn-xss "
              data-toggle="modal" data-target="#baja"
              onclick="bajaProductos('.$x[4].',0)" > <span class="fa fa-thumbs-down"> </span>
              </a>';

    $tb .= '<td class="text-right ">' .$key[10].'</td>';
    $tb .= '<td class="text-right ">' .$key[10].'</td>';
    $tb .= '<td class="text-center col-sm-1">' .$a.'  '.$b.'  '.$c.'</td>';

      $tb .= '</tr>';
  }

  /* JSON  ENCODE */
  $json = array($tb);
  echo json_encode($json);
 break;





 case 1:
 $sql    = $obj -> Show_DATA($categoria,$area,$opc);
 $cont   = 0;


 foreach ($sql as $x ) {
  $opc=1;

  $v       =empty($x[8]);
  if ($v=="") { $opc=0;}
  $ico      = ICOpoliza($x[8]);
  $est      = EstadoProducto($x[14]);
  $cont    +=1;
  // ----
  $fechainicial = new DateTime($x[12]);
  $fechafinal = new DateTime($x[13]);

  $diferencia = $fechainicial->diff($fechafinal);
  $meses    = ( $diferencia->y * 12 ) + $diferencia->m;
  $txtMeses = $meses.' Meses';

  $url =$x[17];

  if ($x[17] == null || $x[17] == '') {
   $url  = 'recursos/img/box.png';
  }

  $code = '<span class    = \"text\"><strong>'.$x[0].'</strong></span>';


  $img  = '<img class     = \"img-preview\" data-action=\"zoom\" style=\"max-width:150px; height:80px\" src=\"'.$url.'\">';


  $a    = '<a data-toggle = \"modal\" data-target=\"#M1\" class=\"btn btn-outline-warning btn-xss \" onclick=\"ModalPoliza('.$x[4].','.$opc.')\"><span class=\"'.$ico.'\"></span></a>';

  $b    = '<a onclick     = \"verModalActualizar('.$x[4].')\" data-toggle=\"modal\" data-target=\"#Producto\" class=\"btn btn-outline-info btn-xss \"><span class=\"fa fa-pencil\" > </span></a>';

  $c    = '<a class       = \"btn btn-outline-danger btn-xss \" data-toggle=\"modal\" data-target=\"#baja\" onclick=\"bajaProductos('.$x[4].',0)\" > <span class=\"fa fa-thumbs-down\"> </span></a>';
  //---


  $buscar     = array(chr(13).chr(10), "\r\n", "\n", "\r");
  $reemplazar = array("", "", "", "");
  $cadena     = str_ireplace($buscar,$reemplazar,$x[15]);


  $txt=$txt.'{
   "Codigo"          :"'.$code.'",
   "udn"          :"'.$x[16].'",
   "equipo":"'.$x[1].'",
   "categoria":"'.$x[7].'",
   "area":"'.$x[2].'",
   "cantidad":"'.$x[5].'",
   "costo":"'.evaluar($x[10]).'",
   "tiempo":"'.$txtMeses.'",
   "fecha":"'.$est.'",
   "Desc":"'.$cadena.'",
   "conf":" '.$a.' '.$b.' '.$c.'"

  },';
 }



 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';

 break;

 case 0: // Almacen baja

 $sql    = $obj -> Show_DATA_BAJA($categoria,$area,$opc);
 $cont  =0;


 foreach ($sql as $x ) {
  $opc=1;

  $v       =empty($x[8]);
  if ($v=="") { $opc=0;}
  $ico      = ICOpoliza($x[8]);
  $est      = EstadoProducto($x[14]);
  $cont    +=1;
  // ----

  $code='<span class=\"label label-indigo\">'.$x[0].'</span>';

  $a='<a data-toggle=\"modal\" data-target=\"#M1\" class=\"btn btn-outline-warning btn-xss  \" onclick=\"ModalPoliza('.$x[4].','.$opc.')\"><span class=\"'.$ico.'\"></span></a>';

  $b='<a class=\"btn btn-outline-danger btn-xss \" data-toggle=\"modal\" data-target=\"#baja\" onclick=\"bajaProductos('.$x[4].',1)\" > <span class=\"fa fa-thumbs-up\"> </span></a>';
  //---

  $txt=$txt.'{
   "Codigo"          :"'.$code.'",
   "udn"          :"'.$x[3].'",
   "equipo":"'.$x[1].'",
   "categoria":"'.$x[7].'",
   "area":"'.$x[2].'",
   "cantidad":"'.$x[17].'",
   "costo":"'.evaluar($x[10]).'",
   "Baja":"'.$x[16].'",
   "res":"'.$x[18].'",
   "obs":"'.$x[15].'",
   "conf":" '.$a.' '.$b.'"

  },';
 }



 $txt = substr($txt,0,strlen($txt)-1);
 echo '{"data":['.$txt.']}';


 break;




}



/*=============================================
Funciones
===============================================*/

function ICOpoliza($value){
 $rp="";
 if ($value=="") {
  $rp="fa fa-cloud-upload";
 }else {
  $rp="fa fa-file-text-o";
 }
 return $rp;
}

function EstadoProducto($value)
{
 $estado = '';
 switch ($value) {
  case 1: $estado ='<span class=\"label label-success\">Bueno</span>'; break;
  case 2: $estado ='<span class=\"label label-warning\">Regular</span>'; break;
  case 3: $estado ='<span class=\"label label-danger\">Malo</span>'; break;

 }
 return $estado ;
}

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }
 return $res;
}


?>
