<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Catalogo.php');
include_once("../../../modelo/SQL_PHP/_Utileria.php");
include_once("../../../modelo/SQL_PHP/_CRUD.php");
include_once("../../../modelo/UI_TABLE.php");

$util     = new Util;
$cat      = new Catalogo;
$crud     = new CRUD;
$tb       = new Table_UI;


$opc      = $_POST['opc'];

switch ($opc) {
 case 0://RELLENAR SELECT DE IMPUESTOS
 $option = '<label class="control-label" for="ImpIng">Impuesto</label>
 <select style="width:120px" class="form-control input-sm" id="ImpIng" multiple="multiple">';
 $sql = $cat->Select_Impuestos();
 foreach ($sql as $row) {
  $option = $option.'<option value="'.$row[0].'">'.$row[1].'</option>';
 }
 $option = $option.'</select>';
 echo $option;
 break;
 case 1://RELLENAR SELECT DE CATEGORIA
 $option = '<label class="control-label" for="ImpCat">Categorías</label>
 <select class="form-control input-sm" id="ImpCat" >
 <option value="0">SELECCIONAR CAT...</option>';
 $sql = $cat->Select_Categorias();
 foreach ($sql as $row) {
  $option = $option.'<option value="'.$row[0].'">'.$row[1].'</option>';
 }
 $option = $option.'</select>';
 echo $option;
 break;
 case 2://TABLA IMPUESTOS
 /***************************************************
 VARIABLES / PAGINACIÓN
 ****************************************************/
 $paginaActual = $_POST['pag'];
 $Paginas = $cat->Select_Cont_Impuestos();
 $url= "Select_TbImpuestos";
 $Lotes = 5;
 $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

 echo $pag;
 if($paginaActual <= 1 ){
  $limit=0;
 }
 else{
  $limit = $Lotes*($paginaActual-1);
 }

 $Total_Pag = ceil($Paginas/$Lotes);


 $query = "SELECT idImpuesto,Impuesto,Valor FROM hgpqgijw_finanzas.impuestos WHERE Stado = 1 LIMIT $limit, $Lotes";
 $sql = $crud->_Select($query,null);

 ?>
 <div class="col-sm-12 col-xs-12 text-right">
  <label style="font-size:12px; color:#A8A8A8;">Registros #<?php echo $Paginas;?></label>
 </div>
 <div class="col-sm-12 col-xs-12">
  <table class="table table-responsive table-stripped table-hover table-bordered table-condensed">
   <thead>
    <tr>
     <th class="text-center col-sm-6">Concepto</th>
     <th class="text-center col-sm-1">Valor</th>
     <th class="text-center col-sm-1">Eliminar</th>
    </tr>
   </thead>
   <tbody>
    <?php
    foreach ($sql as $row) {
     echo '
     <tr>
     <td>
     <label>'.$row[1].'</label>
     </td>
     <td class="text-center">
     <label>'.$row[2].'%</label>
     </td>
     <td class="text-center">
     <button type="button" class="btn btn-xs btn-danger" onClick="Delete_Impuestos('.$row[0].')"><span class="icon-cancel"></span> </button>
     </td>
     </tr>
     ';
    }
    ?>
   </tbody>
  </table>
 </div>
 <?php
 break;
 case 3://TABLA CATEGORÍA
 /***************************************************
 VARIABLES / PAGINACIÓN
 ****************************************************/
 $paginaActual = $_POST['pag'];
 $Paginas = $cat->Select_Cont_Categoria();
 $url= "Select_TbCategorias";
 $Lotes = 5;
 $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

 echo $pag;
 if($paginaActual <= 1 ){
  $limit=0;
 }
 else{
  $limit = $Lotes*($paginaActual-1);
 }

 $Total_Pag = ceil($Paginas/$Lotes);

 $query = "SELECT idCategoria,Categoria,TipoMovimiento FROM hgpqgijw_finanzas.categoria,hgpqgijw_finanzas.tipo_movimiento WHERE id_TMovimiento = idTMovimiento AND Stado = 1 LIMIT $limit, $Lotes";
 $sql = $crud->_Select($query,null);
 ?>
 <div class="col-sm-12 col-xs-12 text-right">
  <label style="font-size:12px; color:#A8A8A8;">Registros #<?php echo $Paginas;?></label>
 </div>
 <div class="col-sm-12 col-xs-12">
  <table class="table table-responsive table-stripped table-hover table-bordered table-condensed">
   <thead>
    <tr>
     <th class="text-center col-sm-6">Concepto</th>
     <th class="text-center col-sm-2">Movimiento</th>
     <th class="text-center col-sm-1">Impuestos</th>
     <th class="text-center col-sm-1">Eliminar</th>
    </tr>
   </thead>
   <tbody>
    <?php
    foreach ($sql as $row) {
     $array = array($row[0]);
     $query = "SELECT Valor FROM hgpqgijw_finanzas.impuestos,hgpqgijw_finanzas.categoria_impuesto WHERE id_Impuesto = idImpuesto AND id_Categoria = ?";
     $sql2 = $crud->_Select($query,$array);
     echo '
     <tr>
     <td>
     <label>'.$row[1].'</label>
     </td>
     <td>
     <label>'.$row[2].'</label>
     </td>
     <td class="text-center">';
     foreach ($sql2 as $data) {
      echo '<label> '.$data[0].'% </label> ';
     }
     echo
     '</td>
     <td class="text-center">
     <button type="button" class="btn btn-xs btn-danger" onClick="Delet_Categoria('.$row[0].');"><span class="icon-cancel"></span> </button>
     </td>
     </tr>
     ';
    }
    ?>
   </tbody>
  </table>
 </div>
 <?php
 break;
 case 4://TABLA SUBCATEGORÍA
 /***************************************************
 VARIABLES / PAGINACIÓN
 ****************************************************/
 $paginaActual = $_POST['pag'];
 $Paginas = $cat->Select_Cont_SubCategoria();
 $url= "Select_TbSubCategorias";
 $Lotes = 5;
 $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

 echo $pag;
 if($paginaActual <= 1 ){
  $limit=0;
 }
 else{
  $limit = $Lotes*($paginaActual-1);
 }

 $Total_Pag = ceil($Paginas/$Lotes);

 $query = "SELECT idSubcategoria,Subcategoria,Categoria FROM hgpqgijw_finanzas.subcategoria,hgpqgijw_finanzas.categoria WHERE idCategoria = id_Categoria AND hgpqgijw_finanzas.subcategoria.Stado = 1 AND hgpqgijw_finanzas.categoria.Stado = 1 LIMIT $limit, $Lotes";
 $sql = $crud->_Select($query,null);
 ?>
 <div class="col-sm-12 col-xs-12 text-right">
  <label style="font-size:12px; color:#A8A8A8;">Registros #<?php echo $Paginas;?></label>
 </div>
 <div class="col-sm-12 col-xs-12">
  <table class="table table-responsive table-stripped table-hover table-bordered table-condensed">
   <thead>
    <tr>
     <th class="text-center col-sm-6">Concepto</th>
     <th class="text-center col-sm-1">Impuestos</th>
     <th class="text-center col-sm-1">Eliminar</th>
    </tr>
   </thead>
   <tbody>
    <?php
    foreach ($sql as $row) {
     echo '
     <tr>
     <td>
     <label>'.$row[1].'</label>
     </td>
     <td>
     <label>'.$row[2].'</label>
     </td>
     <td class="text-center">
     <button type="button" class="btn btn-xs btn-danger" onClick="Delete_SubCategoria('.$row[0].');"><span class="icon-cancel"></span> </button>
     </td>
     </tr>
     ';
    }
    ?>
   </tbody>
  </table>
 </div>
 <?php
 break;
 case 5://TABLA FORMAS PAGO
 /***************************************************
 VARIABLES / PAGINACIÓN
 ****************************************************/
 $paginaActual = $_POST['pag'];
 $Paginas = $cat->Select_Cont_formapago();
 $url= "Select_TbFormasPago";
 $Lotes = 5;
 $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

 echo $pag;
 if($paginaActual <= 1 ){
  $limit=0;
 }
 else{
  $limit = $Lotes*($paginaActual-1);
 }

 $Total_Pag = ceil($Paginas/$Lotes);


 $query = "SELECT idFormas_Pago,FormasPago FROM hgpqgijw_finanzas.formas_pago WHERE Stado = 1 LIMIT $limit, $Lotes";
 $sql = $crud->_Select($query,null);
 ?>
 <div class="col-sm-12 col-xs-12 text-right">
  <label style="font-size:12px; color:#A8A8A8;">Registros #<?php echo $Paginas;?></label>
 </div>
 <table class="table table-stripped table-hover table-bordered table-condensed">
  <thead>
   <tr>
    <th colspan="3" class="text-center">FORMAS DE PAGO</th>
   </tr>
   <tr>
    <th class="text-center col-sm-6">Concepto</th>
    <th class="text-center col-sm-1">Eliminar</th>
   </tr>
  </thead>
  <tbody>
   <?php
   foreach ($sql as $row) {
    echo '
    <tr>
    <td>
    <label>'.$row[1].'</label>
    </td>
    <td class="text-center">
    <button type="button" class="btn btn-xs btn-danger" onClick="Delete_FormasPago('.$row[0].');"><span class="icon-cancel"></span> </button>
    </td>
    </tr>
    ';
   }
   ?>
  </tbody>
 </table>
 <?php
 break;
 case 6://SELECT MOVIMIENTOS
 $option = '<label for="">Categorías</label>
 <select class="form-control input-sm" id="TMovimientos" >';
 $sql = $cat->Select_Movimientos();
 foreach ($sql as $row) {
  $option = $option.'<option value="'.$row[0].'">'.$row[1].'</option>';
 }
 $option = $option.'</select>';
 echo $option;
 break;

 /*-----------------------------------*/
 /*
 /*-----------------------------------*/

 case 7:
 $Titulo   = array('CUENTA','<span class="fa fa-gear"></span>');
 $tag     = array('CG','EliminarCuenta');
 $tdMoneda = null;
 $conf = array(1,2,3);
 $sql      = $cat ->  verCuentas(null);
 $table  =  $tb  ->  Table($Titulo,$tag,$tdMoneda,$sql,$conf);

 echo $table;

 break;

 /*-----------------------------------*/
 /* Destino
 /*-----------------------------------*/

 case 8:
 $Titulo   = array('DESTINO','<span class="fa fa-gear"></span> ');
 $tag      = array('IC','EliminarDestino');
 $tdMoneda = null;
 $conf     = null;
 $idE      = $_SESSION['udn'];
 $array    = array($idE);

 $sql      = $cat ->  verDestino($array);
 $destino  =  $tb  ->  Table($Titulo,$tag,$tdMoneda,$sql,$conf);

 echo $destino;
 break;



}
?>
