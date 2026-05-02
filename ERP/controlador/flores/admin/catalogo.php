<?php
include_once("../../../modelo/SQL_PHP/_FLORES.php");
$obj       = new _PRODUCTOS;

include_once("../../../modelo/UI_TABLE.php");
$table = new Table_UI;


switch ($_POST['opc']) {

 case 0: // Agregar nueva categoria
 $a = $_POST['categoria'];

 $array = array($a,1,1);
 $title = array('nombrecategoria','id_udn','status');

 $ok    = $obj ->SAVE_FORM($array,$title,'hgpqgijw_ventas.venta_categoria');
 break;

 case 1:
 $Titulo = array('Categoria',"<i class='bx bx-cog' ></i>");
 $tag    = array('nombrecategoria','EliminarCat');
 $sql    = $obj ->catalogo_cat();
 $tb = $table ->Table($Titulo,$tag,null,$sql,null);

 /*--------- JSON ENCODE -----------*/
 $encode	=	array(0=>$tb);
 echo	json_encode($encode);

 break;


 /*-----------------------------------*/
 /*		Sub Categoria
 /*-----------------------------------*/
 case 3:
 $a = $_POST['sub'];
 $b = $_POST['cat'];

 $array = array($a,$b,1);
 $title = array('Nombre_subcategoria','id_categoria','status_sub');

 $ok    = $obj ->SAVE_FORM($array,$title,'hgpqgijw_ventas.venta_subcategoria');
 break;

 case 4:
 $Titulo = array('Categoria','Sub categoria ',"<i class='bx bx-cog' ></i>");
 $tag    = array('nombrecategoria','Nombre_subcategoria','EliminarSub');
 $sql    = $obj ->catalogo_sub();
 $tb = $table ->Table($Titulo,$tag,null,$sql,null);

 /*--------- JSON ENCODE -----------*/
 $encode	=	array(0=>$tb);
 echo	json_encode($encode);
 break;

 /*-----------------------------------*/
 /*		Eliminar sub & cat
 /*-----------------------------------*/
 case 5:
 $id    = $_POST['id'];
 $array = array($id);
 $sql   = $obj -> DeleteCategoria($array);
 break;
}

?>
