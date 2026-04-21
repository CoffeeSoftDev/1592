<?php
session_start(); 
include_once("../../../modelo/SQL_PHP/_PEDIDOS.php");
$obj = new PEDIDOS;

include_once( '../../../modelo/UI_FORM.php' );
$fx    = new FORM_UI;

$opc   = $_POST['opc'];
$idE   = $_SESSION['udn'];
$json  = '';

switch ($opc) {



case 1: // Tabla de clientes
   $th        = array( 'Cliente','Lugar' ,'Direccion', 'Telefono','Correo','Dias','Estatus' );
   $table     = list_proveedores( $obj,$th);
   $json	 = 	array( 0=>$table );
break;

case 2: // Modal de modificar clientes
   $idCliente = $_POST['idCliente'];
   $data      = $obj->Consultar_cliente(array($idCliente));
   $frm       = '';
   $frm .= '<form id="FormCliente" class="form-horizontal" onsubmit="return false" >';
   $frm .= $fx -> input_text( array( 'Cliente', 'Cliente', '' ,'BuscarCliente(2)' ), $data[1], '' );
   $frm .= $fx -> input_text( array( 'Lugar', 'Lugar', '' ,''), $data[2], '' );
   $frm .= $fx -> input_text( array( 'Direccion', 'Direccion','' ,'' ), $data[3], '' );
   $frm .= $fx -> input_text( array( 'Telefono', 'Telefono', '','' ), $data[4], '' );
   $frm .= $fx -> input_text( array( 'Correo', 'Correo', '' ,''), $data[5], '' );
   $frm .= $fx -> input_text( array( 'Credito', 'Credito', '' ,''), '', '' );
   $frm .= cbEstatus($data[7]);
   $frm .= '<div class="form-group">';
   $frm .= '<div class="txt-rp"></div><div  class="col-xs-12 col-sm-12 text-right">';
   $frm .= '<button class="btn btn-info " onclick="EditarFormulario('.$idCliente.')">
            <span class=""></span> Guardar </button>';
   $frm  .= '</div></div></form>';
   
   $json	 = 	array( 0=>$frm );
break;

case 3: // Modificar cliente
   $getters     =  array('Cliente','Lugar','Direccion','Telefono','Correo','Credito','Estado','idCliente');
   $data        = get_data($getters);
   $obj -> actualizar_cliente($data );
   $json        = array(0=> $data[0]);
break;

case 4: // Quitar Cliente
   $idCliente   = $_POST['idCliente'];
   $obj -> QuitarCliente(array(2,$idCliente));
break;

case 5: // Nuevo cliente Formulario
   $frm       = '';
   $frm .= '<form id="FormCliente" class="form-horizontal" onsubmit="return false" >';
   $frm .= $fx -> input_text( array( 'Cliente', 'Cliente', '','BuscarCliente(1)' ), '', '' );
   $frm .= $fx -> input_text( array( 'Lugar', 'Lugar', '','' ), '', '' );
   $frm .= $fx -> input_text( array( 'Direccion', 'Direccion','', '' ), '', '' );
   $frm .= $fx -> input_text( array( 'Telefono', 'Telefono', '' ,''), '', '' );
   $frm .= $fx -> input_text( array( 'Correo', 'Correo', '','' ), '', '' );
   $frm .= $fx -> input_text( array( 'Credito', 'Credito', '','' ), '', '' );
   $frm .= cbEstatus(1);
   $frm .= '<div class="form-group">';
   $frm .= '<div class="txt-rp"></div><div  class="col-xs-12 col-sm-12 text-right">';
   $frm .= '<button id="btnNuevoCliente" class="btn btn-success " onclick="AgregarCliente()">
            <span class=""></span> Guardar </button>';
   $frm  .= '</div></div></form>';
   $json	 = 	array( 0=>$frm );

break;

case 6: // Agregar Cliente
   $getters     =  array('Cliente','Lugar','Direccion','Telefono','Correo','Credito','Estado');
   $data        = get_data($getters);

   $obj->InsertarCliente($data);
break;

case 7: // Buscar existencia de cliente
   // $ClienteEncontrado = $_POST['txtNombre']; 
   $ClienteEncontrado = $obj->BuscarCliente(array($_POST['txtNombre']));
   $json	 = 	array( 0=>$ClienteEncontrado );
break;

}


/* JSON ENCODE  -------*/  
echo json_encode($json);

function get_data($array){
   $Object_data = '';

   for ($i=0; $i < count($array) ; $i++) { 
     $Object_data [] = $_POST[$array[$i]];
   }
   return $Object_data;
}

function list_proveedores($obj,$th){
   $tb  = '<div class="table-responsive">';
   $tb .= '<table id="tbClientes" class="table table-bordered table-condensed"  style="width:100%; font-size:.87em;">';

    /*----------THEAD------------*/
    $tb .= '<thead><tr>';
    for ( $i = 0; $i < count( $th ); $i++ ) {
        $tb .= '<th class="text-center">' . $th[$i] . '</th>';
    }

    $tb .= '<th class="text-center col-sm-1"><i class="bx bx-cog"></i></th>';
    $tb .= '</tr></thead>';

    /*----------TBODY------------*/
    $tb .= '<tbody>';

    $list = $obj-> list_cliente();

    foreach ( $list as $key ) {
        $btn = options_btn('Cliente',$key[0],$key[5]);
        $tb .= '<tr>';
        
        $tb .= '<td class="" id="lblCliente'.$key[0].'">' . $key[1] . '</td>';
        $tb .= '<td class="text-center ">'.$key[2].'</td>';
        $tb .= '<td class="text-center ">'.$key[3].'</td>';
        $tb .= '<td class="text-center">'.$key[4].'</td>';
        $tb .= '<td class="text-center ">'.$key[5].'</td>';
        $tb .= '<td class="text-center ">'.$key[6].'</td>';
        $tb .= '<td class="text-center ">'.estatus($key[7]).'</td>';
        $tb .= '<td class="text-center ">'.$btn.'</td>';
        $tb .= '</tr>';
    }


    $tb .= '</tbody>';
    $tb .= '</table>';
    $tb .= '</div>';
    return $tb;
}


/* ---------------- Complementos--------------- */
function estatus($idEstatus){
  $estatus = '';
    switch ($idEstatus) {
      case 1:
         $estatus = '<b><p class="text-success">Activo</p></b>';
      break;

      case 0:
         $estatus = '<b><p class="text-danger">Inactivo</p></b>';
      break;
        
    }

 return $estatus;
}

function options_btn($txt,$id,$status){
  $btn  = '';
  $btn  .= '<a style="text-decoration:none; " onclick="Editar'.$txt.'('.$id.')"> <i style="font-weight:400;" class=" bx bx-edit text-primary bx-md"></i></a>';
  if($status==1){
   $btn  .= '<a style="text-decoration:none; font-weight:400;" onclick="Desactivar'.$txt.'('.$id.')"> <i class=" bx bx-trash bx-md text-danger"></i></a>';
  }else{
   $btn  .= '<a style="text-decoration:none;" onclick="Eliminar'.$txt.'('.$id.')"> <i class=" bx bx-trash bx-md "></i></a>';
  }

  return $btn;
}

function cbEstatus($valor){
    $cb = '';
    $cb .= '<div class="form-group">';
    $cb .= '<label  class="control-label col-xs-12 col-sm-4" >Estado : </label>';
    $cb .= '<div class="col-xs-12 col-sm-8">';
    $cb .= '<select class="form-control input-sm" id="txtEstado">';
    if($valor==1){
    $cb .= '<option value="1" selected>ACTIVO</option>';
    $cb .= '<option value="2">INACTIVO</option>';

    }else{
    $cb .= '<option value="1" >ACTIVO</option>';
    $cb .= '<option value="0" selected>INACTIVO</option>'; 
    }


    $cb .= '';
    $cb .= '</select>';
    $cb .= '</div>';
    $cb .= '</div>';
  
  return $cb;
 }
?>