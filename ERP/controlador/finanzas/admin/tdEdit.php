<?php
session_start();
include_once("../../../modelo/SQL_PHP/_Catalogo.php");
$obj = new Catalogo;

include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
$fin = new Finanzas;


$opc       = $_POST['opc'];
$categoria = 0;
$txt       = '';


switch ($opc) {

 /*-----------------------------------*/
 /* Catalogo -
 /*-----------------------------------*/

 case 0: // vista Caja de texto

 $campo    = $_POST['txt'];
 $id       = $_POST['id_num'];
 $cantidad = $_POST['valor'];



 $txt=$txt.'
 <input type="text" class="form-control input-xs"
 id="Edit'.$campo.$id.'"
 autocomplete="off"
 onkeypress="if(event.keyCode==13){
  Edit('.$id.',\''.$campo.'\')
 }"
 value="'.$cantidad.'"
 onBlur="Edit('.$id.',\''.$campo.'\')">';

 break;

 case 1: // Opcion Actualizar
 $campo  = $_POST['txt'];
 $valor  = $_POST['valor'];
 $id     = $_POST['id_num'];
 $array  = array($valor,$id);
 $tb     = '';

 switch ($campo) {
  case 'IC':
  $tb     = 'insumos_clase';
  break;

  case 'CG':
  $tb     = 'gasto_clase';
  break;


 }

 $ok     = $obj    -> _UPDATE_REGISTRO($array,$campo,$tb);

 $txt    = $txt.'
 <label onclick="tdEdit(\''.$campo.'\','.$id.',\''.$valor.'\' )">'.$valor.'</label>';


 break;
 case 2: // Ingresar
 $n      = $_POST['nombre'];
 $array  = array($n,1);
 $obj->_INSERTAR_REGISTRO($array);


 echo 1;
 break;

 /*-----------------------------------*/
 /*	 INGRESOS - EDITAR
 /*-----------------------------------*/

 case 3:


 $campo        = $_POST['txt'];
 $id           = $_POST['id_num'];
 $cantidad     = $_POST['valor'];
 $date         = $_POST['date'];

 $data         = $cantidad;

 if ($cantidad ==0) {
  $data        = '';
 }


 $pax     = $fin->Buscar_en_bitacora($date,$id,$campo);
 $existe  = count($pax);
 // $existe  = $pax ;


 if ($existe!=0) { // Existen Datos
  $txt=$txt.'
  <input type="text" class="form-control input-xs"
  id="Edit'.$campo.$id.'"
  autocomplete="off"
  onkeypress="if(event.keyCode==13){ Edit('.$id.',\''.$campo.'\') }"
  value="'.$data.'"
  onBlur="Edit('.$id.',\''.$campo.'\')" autofocus> ';

 }else{


  $txt=$txt.'
  <input type="text" class="form-control input-xs"
  id="Edit'.$campo.$id.'"
  autocomplete="off"
  onkeypress="if(event.keyCode==13){ Insert('.$id.',\''.$campo.'\') }"
  value="'.$data.'" autofocus>';

 }

 break;

 case 4:  // Editar Registro
 $campo  = $_POST['txt'];
 $valor  = $_POST['valor'];
 $id     = $_POST['id_num'];
 $date   = $_POST['date'];

 $array = array($valor,$id);

 $pax   = $fin->BitacoraUpdate($date,$id,$valor,$campo);

 // Obtener categoria a partir del id SubCategoria
 $idCat     = $fin ->Select_Categoria_by_sub(array($id));
  $categoria = $idCat;
 $txt    = $txt.'
 <span onclick="tdEdit(\''.$campo.'\','.$id.',\''.$valor.'\' )">'.$valor.'</span>';
 break;


 case 5: // Insertar registro
 $campo  = $_POST['txt'];
 $valor  = $_POST['valor'];
 $id     = $_POST['id_num'];
 $date   = $_POST['date'];
 $udn    = $_SESSION['udn'];

 $pax  = $fin ->PaxInsert($date,$id,$valor,$udn,$campo);

 $txt    = $txt.'
 <label onclick="tdEdit(\''.$campo.'\','.$id.',\''.$valor.'\' )">'.$valor.'</label>';

 break;


 case 6: // ** caso especial saldos
 $campo  = $_POST['txt'];
 $valor  = $_POST['valor'];
 $id     = $_POST['id_num'];
 $date   = $_POST['date'];
 $udn    = $_SESSION['udn'];

 $pax     = $fin->Buscar_en_bitacora($date,$id,$campo);
 $existe  = count($pax);

 if ($existe!=0) { // Se encuentran algunos datos

  $array = array($valor,$id);
  $pax   = $fin->BitacoraUpdate($date,$id,$valor,$campo);
  $pax   = $fin->BitacoraUpdate($date,$id,$valor,'Subtotal');

 }else { // No se encuentra ningun registro
  $pax  = $fin ->PaxInsert($date,$id,$valor,$udn,$campo);

 }


 $txt    .= '
 <input style="width:100%; "
 class=" input-xs total-input" value="'.$valor.'"
 onkeypress="if(event.keyCode == 13) Saldos('.$id.',\'Tarifa\')"
 id="txtTarifa'.$id.'" autofocus>
 ';
 break;

}

// ============================================
//  JSON ENCODE
// ============================================

$encode = array(0=>$txt,1=>$categoria);
echo json_encode($encode);

?>
