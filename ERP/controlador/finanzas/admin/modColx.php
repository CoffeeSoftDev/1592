<?php

// session_start();
// include_once("../../../modelo/SQL_PHP/_RRHH3.php");
// $funcion = new RHHH;
// // ------------------
$id       = $_POST['id'];
$Cantidad = $_POST['Cant'];
$campo    = $_POST['txt'];

// $fi=$_POST['fi'];
// $ff=$_POST['ff'];

// -------------------
// $ps = $funcion ->verData($id,$fi,$ff);
// $opc = count($ps);
//
/*==========================================
*	  MAINclass="form-control input-xs"
=============================================*/
$txt='';

  // $txt=$txt.'
  // <input style="width:100%; height:120px;" type="text" class="bg-danger"
  // id="Editdesc'.$id.'"
  //
  // onkeypress="_ENTER('.$id.',\'desc\')"
  // value="'.$Cantidad.'">
  // ';


  $txt=$txt.'
  <input style="width:100%; height:120px;" type="text" class="bg-danger"
  id="Editdesc'.$id.'"

  onkeypress="_ENTER('.$id.',\'desc\')"
  value="'.$Cantidad.'">
  ';


// ============================================
//  JSON ENCODE
// ============================================

$encode = array(0=>$txt);
echo json_encode($encode);

?>
