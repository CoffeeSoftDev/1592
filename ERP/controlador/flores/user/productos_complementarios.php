<?php
include_once("../../../modelo/SQL_PHP/_PEDIDOS.php");
$obj = new PEDIDOS;

session_start();
$encode = '';
$opc    = $_POST['opc'];
$idE    = $_SESSION['udn'];

switch ($opc) {

 case 0: // agregar opciones a la lista de flores<
   $Producto     = $_POST['Producto'];
   $idProducto   = $obj-> __getProducto(array($Producto));
   /* JSON ENCODE  -----------*/
   $encode = array(0=>$mnsj);
 break;

 case 1: // tabla de productos complementarios
   $tabla  = table_complementos($obj);
   /* JSON ENCODE  -----------*/
   $encode = array(0=>$mnsj);
 break;

case 2: // agergar a lista complementaria
  $Producto     = $_POST['producto'];
  $venta        = $_POST['venta'];
  $cantidad     = $_POST['cantidad'];



  // $idProducto   = $obj-> __getProducto_name(array($Producto));
  // foreach ($idProducto as $key );
  //
  // # -- agregar a lista opcional   --
  // $obj->__add_Complemento(array($idProducto,$cantidad,$venta));
  //
  // $mnsj = $Producto.' > '.$key[0];
  // /* JSON ENCODE  -----------*/
  $encode = array(0=>$mnsj);
break;

case 3: // lista de productos Complementarios
    list_complementos($obj,$idProducto);
break;

case 4: // Modal productos complementarios
$idProducto       = $_POST['id'];
$list_adicionales = $obj->_view_productos_adicionales(array($idProducto));
$list             = list_complementos($obj,$idProducto);

$mnsj = '
     <div class="row ">
        <div class="line col-sm-12">
             <label>Producto   ( '.$idProducto.') </label>
           <div class="input-group">
              <input type="text"
              class="form-control ui-autocomplete-input" id="modal_txt"
              placeholder="BUSCAR FLOR"
              onkeypress="__input_flores()" onblur="BuscarCosto()" autocomplete="off">

              <span class="input-group-addon input-sm"><i class="bx bxs-truck bx-sm"></i></span>

           </div>

        </div>
     </div>

    <div class="row ">
     <div class="line col-sm-12">
       <label>Precio Venta</label>
        <input  type="text"
        class="form-control ui-autocomplete-input" id="complemento_venta"
        placeholder="" disabled>
     </div>
    </div>

    <div class="row ">
     <div class=" col-sm-12">
       <label>Cantidad</label>
        <input onkeyup="CalcularTotal()"  type="text"
        class="form-control ui-autocomplete-input" id="complemento_cantidad"
        placeholder="" >
     </div>
    </div>


      <div class="row ">
     <div class=" col-sm-12">
       <label>Total</label>
        <input disabled type="text"
        class="form-control ui-autocomplete-input" id="complemento_total"
        placeholder=""
        onkeypress="__input_flores()" autocomplete="off">

     </div>
    </div>


<br>';



$encode = array(0=>$mnsj);


break;

}



echo json_encode($encode);

//----
function table_complementos($array){

  $tb = '';

  $tb .= '
  <table class="table table-bordered table-xtra-condensed" style="width:100%; ">
  <thead><tr>';

  $tb .='<th>'.$idProducto.'</th>';
  $tb .='</tr></thead>';


  /*--------- Body -----------*/
  $tb .='<tbody class="thead_productos">';
  $list = $obj-> _view_productos_adicionales(array($idProducto));

  foreach ($list as $key ) {
    $tb .= '<tr>';

     $tb .= '<td class="text-center " ><b title="key: ' . $key[0] . '">' . $key[2] . '<b></td>';
     $tb .= '<td class="">' . $key[2] . '</td>';
     $tb .= '<td class=""></td>';
     $tb .= '</tr>';
  }

 $tb .=' </tbody></table>';


}

/* ---------------------------------- */
/* list Complementarios               */
/* ---------------------------------- */
function list_complementos($obj,$idProducto){
    $tb  = '';

    $tb .= '
    <table class="table table-bordered table-xtra-condensed" style="width:100%; ">
    <thead><tr>';
    $tb .='
    <th>Producto</th>
    <th>Cantidad</th>
    <th>Costo</th>
    <th>Cancelar</th>';

    $tb .='</tr></thead>';

    /*--------- Body -----------*/
    $tb .='<tbody >';
    $data = $obj->_view_productos_adicionales(array($idProducto));

    foreach ($data as $key ) {
       $tb .='<td class="text-center" >'.$key[0].'</td>';

    }

    $tb .='</tbody></table>';

    return $tb;


}



?>
