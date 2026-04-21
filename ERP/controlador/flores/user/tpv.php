<?php
// include_once("../../modelo/SQL_PHP/_TPV.php");
// $obj    = new TPV;

include_once("../../../modelo/SQL_PHP/_FLORES.php");
$obj    = new _PRODUCTOS;

include_once("../../../modelo/UI_FORM.php");
$fx  = new FORM_UI;


$opc    = $_POST['opc'];

switch ($opc) {
 /*-----------------------------------*/
 /*	 Categoria - botones
 /*-----------------------------------*/

 case 1:
 $btn = ' <span class="categories selectedGat" ><i class="fa fa-home"></i></span>
 ';
 $sql = $obj ->_Categoria(array(1));

 foreach ($sql as $key) {
  $btn .= '<span class="categories" id="'.$key[0].'">'.$key[1].'</span>
  ';
 }


 $encode = array(
  0=> $btn);
  echo json_encode($encode);


  break;
  /*-----------------------------------*/
  /*	 Categoria - Productos
  /*-----------------------------------*/
  case 2:
  $sql       = $obj ->VerProductos(array(1));
  $productos = '';

  foreach ($sql as $key) {

   $productos .= '
   <div class="col-sm-2 col-xs-4">
   <a href="javascript:void(0)" class="addPct" id="product-2423424" onclick="add_posale()">

   <div class="product color01 flat-box waves-effect waves-block">

   <h3 id="proname">'.$key[0].'</h3>

   <input type="hidden" id="idname-159" name="name" value="pepsi">
   <input type="hidden" id="idprice-159" name="price" value="35">
   <input type="hidden" id="category" name="category" value="'.$key[3].'">
   <div class="mask">
   <h3>'.$key[2].'</h3>
   <p></p>
   </div>

   <img src=".." alt="'.$key[0].'">
   </div>
   </a>
   </div>
   ';
  }

  $encode = array(
   0=> $productos);
   echo json_encode($encode);

   break;

   case 3:
   $sql = $obj ->ver_requisiciones();
   $btn  = '';
   foreach ($sql as $key) {
    $btn .= '<span class="categories" onclick="verReporte()">'.$key[2].'</span> ';
   }

   $btn .= '<span class="categories" onclick="modal()"><i class="bx bx-plus bx-sm" ></i></span>';

   $encode = array(0=> $btn);
   echo json_encode($encode);
   break;
   /*-----------------------------------*/
   /*	 Remision modal
   /*-----------------------------------*/
   case 4:

   $frm = '';

   $d       = $fx ->input_text(array('Destino','Destino',''),'','');
   $c       = $fx ->input_text(array('Cliente','Cliente',''),'','');
   $r       = $fx -> input_number(array('#Remision ','Remision',''),'','');
   $cl      = $fx -> input_number(array('Clave ','Clave',''),'','');
   $nota    = $fx ->input_text(array('Nota de embarque','Nota',''),'','');
   $lote    = $fx ->input_text(array('Lote','Lote',''),'','');
   $Factura = $fx ->input_text(array('Factura','Factura',''),'','');
   $frm .= '
   <form id="Form" class="form-horizontal" onsubmit="return false" >
   '.$c.'
   '.$d.'
   '.$r.'
   '.$cl.'
   '.$nota.'
   '.$lote.'
   '.$Factura.'


   <div class="form-group">
   <div class="col-xs-12 col-sm-12 text-right">
  <div id="txt_rp"></div>
   <button type="submit" class="btn btn-info" onclick="CrearRemision()"><span class="icon-floppy"></span> Guardar </button>
   </div>
   </div>

   </form>
   ';

   $encode = array(0=> $frm);
   echo json_encode($encode);
   break;
  }

  ?>
