<?php
include_once("../../../modelo/SQL_PHP/_MTTO.php");
$obj = new MTTO;

$opc         = $_POST['opc'];
$categoria   = $_POST['categoria'];
$area        = $_POST['area'];

$json        = 'A';


switch ($opc) {

  case 1: // Documento de valorización
    /* JSON  ENCODE */
    $tb = doc_depto($obj);
    $json = array(0=>$tb);

  break;

}

// Imprimir Json
  echo json_encode($json);

// Funciones

function doc_depto($obj){
$doc = '';
# ENCABEZADO ---
$doc .= '
<div class="row">

<div class="col-xs-6 col-sm-6 ">
<h3> VALORIZACIÓN POR DEPARTAMENTO </h3>
</div>


<div class="col-xs-6 col-sm-6 text-right">
<h5 class=""><strong> //</strong></h5>
</div>


<div class="col-xs-12 col-sm-12 text-center">
<h3 class="form-control"><strong>sistemas </strong></h3>
</div>

</div>

';

# CUERPO ---




return $doc;

}

 ?>
