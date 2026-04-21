<?php

session_start();
include_once("../../../modelo/SQL_PHP/_METAS.php"); // <---
$obj = new METAS; // <--



$id    = $_POST['id'];
$valor = $_POST['Cant'];
$campo = $_POST['txt'];
// ---

$update= $obj->Observacion($valor,$id);

$txt='

<label style="width:100%; height:120px;"  id="lbldesc'.$id.'" class="pointer"  onclick="col('.$id.',\'desc\')">
'.$valor.'
</label>
';


// ===========================================
//  JSON ENCODE
// ===========================================

$encode = array(0=>$txt);
echo json_encode($encode);

?>
