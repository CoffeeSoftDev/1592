<?php
session_start();
include_once("../../../modelo/UI_TABLE.php");
$table = new Table_UI;

include_once("../../../modelo/complementos.php");
$com = new Complementos;

include_once("../../../modelo/SQL_PHP/_SQL.php");
$sql = new SQL;

include_once("../../../modelo/SQL_PHP/_PEDIDOS.php");
$obj = new PEDIDOS;

$opc = $_POST['opc'];
$idE = $_SESSION['udn'];

switch ($opc) {

 case 0: // lista de productos

  $table   = '';
  $flag    = 0;
  $idLista = 0;
  $Destino = '';
  $Fecha   = '';

  $data    = $obj -> pedido_activo(null);

  if (count($data)) { // Existen pedidos activos

   foreach ($data as $key);
   $flag    = 1;
   $Fecha   = $key[0];
   $Destino = $key[1];
   $idLista = $key[3];
   $title   = array('#','PRODUCTO','CANTIDAD','UNIDAD','COSTO','TOTAL', 'OBSERVACIÓN' ,'<i class=" icon-cog-2"></i>','+');
   $table   = table_invoice($title,$obj,$idLista,$sql); //@tabla lista productos
  }


  $lblFolio = Format_Folio($idLista);
  $encode = array(
   0=> $flag,
   1=>$table,
   2=>$idLista,
   3=>$Fecha,
   4=>$Destino,
   5=>$lblFolio);
   echo json_encode($encode);
 break;

 case 1: // Crear Formato
  $Cliente       = $_POST['data0']; //Lugar
  $Fecha         = $_POST['data1']; // Date

  $HourNow       = $obj->HoursNOW();
  $FolioFecha    = $Fecha.' '.$HourNow;

  $idCliente     = $obj->get_id_cliente(array($Cliente));
  $num           =  $obj-> NumFolio();

  $array  = array($idCliente,$FolioFecha,1,$num);
  $values = array('id_cliente','foliofecha','id_Estado','folio');
  $sql-> _INSERT($array,$values,'hgpqgijw_ventas.lista_productos');

  $encode = array( 0=> $FolioFecha);
  echo json_encode($encode);
 break;

 case 2: // coleccion de datos
  $nombres = array();
  $bd      = 'hgpqgijw_ventas.venta_subcategoria';
  $sub = $sql ->Select_data('Nombre_subcategoria',$bd,null,null,1);
  foreach ($sub as $key => $row) {
   $nombres[$key] = $row[0];
  }

  $bd      = 'hgpqgijw_ventas.venta_productos';
  $select  = $sql ->Select_data('NombreProducto',$bd,null,null,1);

  foreach ($select as $key => $row) {
   $nombres[$key] = $row[0];
  }
  echo json_encode($nombres);
 break;

 case 3: // Subir pedido con fichero
  $idFolio = $_POST['fol'];
  $mnsj    = '';

  $date      = $com -> fecha_servidor();
  $time_now  = $obj->  HoursNOW();
  $data      = $com -> separar_fecha($date);
  $m         = $com -> obtener_mes($data[1]);
  $file_ruta = $m.'_'.$data[0];

     /*-------- Input file ---------*/
     foreach ($_FILES as $cont => $key) {

      if($key['error'] == UPLOAD_ERR_OK ){

       $trozos         = explode(".", $key['name']);
       $extension      = end($trozos);
       $NombreOriginal = $key['name'];
       $temporal       = $key['tmp_name'];

       $ruta           = 'recursos/flores/'.$file_ruta.'/';
       $carpeta        = '../../../'.$ruta;
       $size           = ($key['size']/1024)/1024;
       if (!file_exists($carpeta)) {
        mkdir($carpeta, 0777, true);
       }

       $files          = $ruta.$NombreOriginal;
       move_uploaded_file($temporal, '../../../'.$files);
       // ********    ARCHIVOS    *********
       /* -- Data Producto -- */
         $mnsj     = '';

       $data  = array(
       $idE,
       $ruta,
       $date,
       $time_now,
       $NombreOriginal,
       $size,
       $extension,
       $idFolio);
       $obj ->Insert_Sobres($data);

       $datos  = array(2,$idFolio);
       $campos = array('id_Estado');
       $where  = array('idLista');
       $sql -> _UPDATE($datos,$campos,'hgpqgijw_ventas.lista_productos',$where);

       $mnsj     = $com-> msj_success('Carga completa.','Su archivo se ha subido correctamente.');

      }// Files ok

     }//end FILES

  /* JSON ENCODE  -----------*/
  $encode = array(0=>$mnsj);
  echo json_encode($encode);
 break;

 case 4: // select
 break;

 #------------[SECCION MODAL DE FLORES ANEXOS 2022]-----------------

 case 14: # Agregar productos a la tabla
   $Producto     = $_POST['Producto'];
   $idProducto   = $obj-> __getProducto(array($Producto));
   $tb = '';

   $tb .= '
   <table class="table table-bordered table-xtra-condensed" style="width:100%; ">
   <thead><tr>';

   $tb .='<th>'.$idProducto.'</th>';
   $tb .='</tr></thead>';

   /*--------- Body -----------*/
   $tb .='<tbody class="thead_productos">';

   // foreach ($data as $key =>$v ) {

   // }

  $tb .=' </tbody></table>';

  $data = $Producto;
  echo json_encode($data);
 break;

 case 13:
    $select  = $obj ->Group_destino();
    $data = array();
    foreach ($select as $key => $row) {
     $data[$key] = $row[0];
    }
    echo json_encode($data);
 break;


 case 12: //  opciones

   /* JSON ENCODE  -----------*/
    // $idProducto = $_POST['idProducto'];
    $idProducto = $_POST['id'];


    $list_adicionales = $obj->_view_productos_adicionales(array($idProducto));
    $list    = list_complementos($obj,$idProducto);

    /* JSON ENCODE  -----------*/

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



   $mnsj .= '
      <div class="row line" id="divName">

      <div class="col-sm-12 text-right">

      <a style="width:100%" class="btn btn-lg btn-success btn-xs" onclick="btn_nuevo_producto()"> Añadir</a>
      </div>



      </div>


      <br>
      <div class="row  ">
      <div class="col-sm-12 ">'.$list.'</div>
      </div>










      <div class="row">

      <div class="col-sm-12" id="modalProductos">



      </div>

      </div>';


  $encode = array(0=>$mnsj);
  echo json_encode($encode);
 break;



 case 5:
 sleep(2);
    $idFolio = $_POST['fol'];
    $array   = array(2,$idFolio);
    $campos  = array('id_Estado');
    $bd      ='hgpqgijw_ventas.lista_productos';
    $where   = array('idLista');
    $sql ->  _UPDATE($array,$campos,$bd,$where);
    /* JSON ENCODE  -----------*/
    $encode = array($sql);

    echo json_encode($encode);
 break;


 case 6: // Mostrar destinos
    $select  = $obj ->Group_destino();
    $data = array();
    foreach ($select as $key => $row) {
     $data[$key] = $row[0];
    }
    echo json_encode($data);
 break;


   case 7: // actualizar registros
    $txt     = $_POST['txt'];
    $idFolio = $_POST['id'];
    $val     = $_POST['val'];
    // -----------------------
    $campos  = array($txt);
    $where   = array('idLista');
    $array   = array($val,$idFolio);

    $sql -> _UPDATE($array,$campos,'hgpqgijw_ventas.lista_productos',$where);
   break;


   case 9:
    $id    = $_POST['id'];
    $array = array($id);
    $sql   = $obj -> DeleteMovimiento($array);
   break;





   case 10:

   /*Crear Remision */

   $date     = $_POST['Date'];
   $destino  = $_POST['Destino'];

   $array    = array($destino,$date,1);
   $values   = array('Destino','foliofecha','id_Estado');

   $sql-> _INSERT($array,$values,'hgpqgijw_ventas.lista_productos');

   $query = $obj -> pedido_activo(null);
   $ok    = '';
   foreach ($query as $pedidos);
   /* id de Remision y asignar archivos */

   $Detalles  = $_POST['Detalles'];
   $time_now  = $obj->HoursNOW();
   $data      = $com -> separar_fecha($date);
   $m         = $com -> obtener_mes($data[1]);
   $file_ruta = $m.'_'.$data[0];

   /*-------- Input file ---------*/
   foreach ($_FILES as $cont => $key) {

    if($key['error'] == UPLOAD_ERR_OK ){

     $trozos         = explode(".", $key['name']);
     $extension      = end($trozos);
     $NombreOriginal = $key['name'];
     $temporal       = $key['tmp_name'];

     $ruta           = 'recursos/flores/'.$file_ruta.'/';
     $carpeta        = '../../../'.$ruta;
     $size           = ($key['size']/1024)/1024;
     if (!file_exists($carpeta)) {
      mkdir($carpeta, 0777, true);
     }

     $files          = $ruta.$NombreOriginal;
     move_uploaded_file($temporal, '../../../'.$files);

     /* -- Data Producto -- */
     $data  = array($idE,$ruta,$date,$time_now,$NombreOriginal,$size,$extension,$Detalles,$pedidos[3]);

     $obj ->Insert_Sobres($data);

     $datos  = array(2,$pedidos[3]);
     $campos = array('id_Estado');
     $where  = array('idLista');
     $sql -> _UPDATE($datos,$campos,'hgpqgijw_ventas.lista_productos',$where);

     $ok     = $com-> msj_success('Carga completa.','Su archivo se ha subido correctamente.');

    }// Files ok

   }//end FILES

   /* JSON ENCODE  -----------*/
   $encode = array($ok);
   echo json_encode($encode);
   break;


   /* Ver folios */

   case 11:
   $id     = $_POST['id'];


   /* JSON ENCODE  -----------*/
   $encode = array($id);
   echo json_encode($encode);
   break;

   #Obtener precio de producto
   case 12:

   $nombre = $_POST['Producto'];

   /* JSON ENCODE  -----------*/
   $encode = array($costo);
   echo json_encode($encode);

   break;

  }

  /* ---------------------------------- */
  /* list Complementarios               */
  /* ---------------------------------- */
  function list_complementos($obj,$idProducto){
      $tb          = '';
      $tb .= '
      <table class="table table-bordered table-xtra-condensed" style="width:100%; ">
      <thead><tr>';

      $tb .='
          <th>Producto ('.$idProducto.')</th>
          <th>Cantidad</th>
          <th>Unidad</th>
          <th>Costo</th>
          <th>Cancelar</th>
      ';

      $tb .='</tr></thead>';

      /*--------- Body -----------*/
      $tb .='<tbody >';

      $data = $obj->_view_productos_adicionales(array($idProducto));
      $tb .='<tr>';

      foreach ($data as $key ) {
         $tb .='<td class="text-center" >'.$key[0].'</td>';
      }

      $tb .='</tr>';
      $tb .='</tbody></table>';

      return $tb;
  }


  /* ---------------------------------- */
  /* modal pedidos                     */
  /* ---------------------------------- */
  function modal_pedidos($obj,$idProducto){
     $tb          = '';
     $tb .= '
      <table class="table table-bordered table-xtra-condensed" style="width:100%; ">
      <thead><tr>';
    $tb .='<th>Producto</th>';

      $tb .='</tr></thead>';

   /*--------- Body -----------*/
   $tb .='<tbody class="thead_productos">';
   // $data = $obj->row_data(array($idProducto));

   // foreach ($data as $key ) {

   // }

   $tb .='</tbody></table>';

   return $tb;

  }

  /* ---------------------------------- */
  /* Formato de pedidos                */
  /* ---------------------------------- */

  function table_invoice($t,$obj,$id,$sql){
   $tb          = '';
   $number_list = 0;
   $data = $obj->row_data(array($id));
   $count_reg = count($data);


   $pedidos   = $obj -> Folio_activo(array($id));
   foreach ($pedidos as $PEDIDOS);

   $tb .= '
   <table class="table table-bordered table-xtra-condensed" style="width:100%; ">
   <thead><tr class="pointer" onclick="head_tablas()">';

   $tb .='<th >'.$t[0].' </th>';

   $tb .='<th class="col-sm-2" class="col-sm-1 text-center">'.$t[1].' </th>';
   $tb .='<th class="col-sm-2 text-center">'.$t[2].'  </th>';
   $tb .='<th class="text-center col-sm-1">'.$t[3].' </th>';
   $tb .='<th class="col-sm-2 text-center">'.$t[4].' </th>';
   $tb .='<th>'.$t[5].'</th>';
   $tb .='<th>'.$t[6].'</th>';
   $tb .='<th>'.$t[7].'</th>';
   $tb .='<th>'.$t[8].'</th>';

   $tb .='</tr></thead>';

   /*--------- Body -----------*/
   $tb .='<tbody class="thead_productos">';

   foreach ($data as $key =>$v ) {

    $number_list = $number_list + 1;

    $tb .='<tr class="item-row">';

    $tb .='<td class="text-center text-danger">'.$key.'</td>';
    $tb .='<td class="col-sm-4">';
    $tb .='<input type="hidden" id="idItem'.$key.'" value="'.$v[0].'">';

    $tb .='
    <input id="txtItem'.$key.'" type="text" class="cell input-xs ui-autocomplete-input" autocomplete="off"  value="'.$v[1].'"
     onFocus="addBusqueda('.$key.')" onblur="GuardarDatos('.$key.')">
    </td>';

    $tb .='<td>
    <input min="1" class="cell input-xs" onblur="get_cant('.$key.');" id="Cant'.$key.'" value="'.$v[2].'">
    </td>';


   $select = data_list($key,$obj,$v[5],'');
   $tb .=' <td class="text-center" id="idUnidad'.$key.'">'.$select.'</td>';


   $tb .='<td class="text-right bg-info"> '.evaluar($v[7]).'</td>';


   $total = $v[2] * $v[7];

   $tb .='<td class="text-right bg-info">'.evaluar($total).'</td>';

   # Recorrido de productos

    $tb .=' <td class="col-sm-2" id="'.$key.'">';
    $tb .='<input class="cell input-xs bg-info" id="detalles'.$key.'" onblur="ActualizarDetalles('.$key.')" value="'.$v[6].'" > ';
    $tb .='</td>';

    $tb .='
    <td class="text-center">
    <a class="pointer" onclick="Quitar('.$v[0].')">
    <i class="bx bx-trash bx-md text-primary"></i>
    </a></td>';


    $tb .=' <td>    <button class="btn btn-warning bx  bx-star btn-xs"  onclick="__modal('.$v[0].','.$key.')"></button></td>';
    $tb .='</tr>';

    // <td class="text-center" id="idUnidad'.$i.'"></td>
   }

   # LISTA DE PRODUCTOS NO AGREGADOS

   for ($i=$count_reg; $i < 15 ; $i++) { // Imprimir thead
    $number_list = $number_list + 1;

    $tb .='<tr class="item-row">';

    $tb  .='<td class="text-center text-success">'.$i.'</td>';

    $tb  .='<td class="col-sm-5">';
    $tb  .='<input type="hidden" class="cell bg-warning" id="idItem'.$i.'" value="">';

    # BUSCAR PRODUCTO
    $tb  .='<input type="text" id="txtItem'.$i.'"
    class="cell input-xs ui-autocomplete-input" data-bv-field="area" autocomplete="off"
    onFocus="addBusqueda('.$i.');"  onblur="GuardarDatos('.$i.')">
    </td>';

    # Cantidad
    $tb  .='<td class="col-sm-1">
    <input min="1" class="cell input-xs" onblur="get_cant('.$i.');" id="Cant'.$i.'"  disabled>
    </td>';


    $data_list = data_list($i,$obj,'','disabled');
    $tb  .= '
    <td  class="text-center" id="idUnidad'.$i.'">'.$data_list.'</td>

    ';
   # Costo
    $tb  .= '
    <td>
    <input class="cell input-xs text-right bg-info" id="idCosto'.$i.'" disabled>
    </td>
    ';

    $tb  .='<td><input  class="cell input-xs text-right bg-info" id="costoFlor'.$i.'" value="" disabled></td>';
   # Observación
    $tb .='<td>
    <input class="cell input-xs bg-info" id="detalles'.$i.'" onblur="ActualizarDetalles('.$i.')" disabled></td>';
   # Botones de comandos
    $tb  .='<td class="text-center" id="idEliminar'.$i.'"></td>';
    $tb  .='<td><button class="btn btn-primary bx  bx-star btn-xs"  onclick="__modal('.$v[0].')" disabled></button></td>';
    $tb  .= '</tr>';

   }
   #------
   $tb .='
   <tr id="hiderow">
   <td colspan="6">
   <input type="hidden" id="UltimaFila" value="'.$number_list.'">
   <div class="add-wpr">
   <a id="addrow" onclick="nuevaFila('.$number_list.')" title="Agregar artículos">
   <i class="bx bxs-plus-circle bx-lg text-success" ></i></a></div></td>




   </tr>


   </tbody></table>

   <div class=" row">
   <div class=" col-xs-12">
   <label style="font-size:1.2em;" >NOTA.-</label>
   <textarea id="txtDetalles" placeholder="Agregar nota de pedido" class="form-control" onkeyup="actualizar_datos(\'Detalles\')">'.$PEDIDOS[5].'</textarea>
   </div>
   </div>
   ';

   return $tb;
  }

  function cbUnidad($obj,$idUnidad,$idFila){
   $cb = '';

   $cb .= '<select class="cell " onchange="ActualizarUnidad('.$idFila.')" id="txtUnidad'.$idFila.'">';

   $sql = $obj-> Unidad();
   foreach ($sql as $key) {
    if($key[0]==$idUnidad){
     $cb .= '<option value="'.$key[0].'" selected>'.$key[1].'</option>';
    }else{
     $cb .= '<option value="'.$key[0].'">'.$key[1].'</option>';
    }
   }



   $cb .= '</select>';

   return $cb;
  }

  function data_list($idFila,$obj,$selected,$disabled){
   $cb  = '<input value="'.$selected.'" class="cell input-xs" onchange="ActualizarUnidad('.$idFila.')" list="Unidad'.$idFila.'" id="txtUnidad'.$idFila.'" '.$disabled.'>';

   $cb .= '<datalist id="Unidad'.$idFila.'">';
   $sql = $obj-> Unidad();

   foreach ($sql as $key) {

    $cb .= '<option value="'.$key[1].'">';
   }
   $cb .= '</datalist>';


   return $cb;
  }


  function Format_Folio($Folio) {
   $NewFolio = 0; $Folio = $Folio + 1;
   if($Folio >= 1000){
    $NewFolio = $Folio;
   }
   else if($Folio >= 100){
    $NewFolio = "0".$Folio;
   }
   else if($Folio >= 10){
    $NewFolio = "00".$Folio;
   }
   else if($Folio >= 1){
    $NewFolio = "000".$Folio;
   }
   return $NewFolio;
  }
