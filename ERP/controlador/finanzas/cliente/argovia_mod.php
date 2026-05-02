<?php
session_start();
$idE  = $_SESSION['udn'];

include_once('../../../modelo/SQL_PHP/_Finanzas_Cheques.php');
$fin = new Files_Cheq;



include_once('../../../modelo/SQL_PHP/_Finanzas_Cliente.php');
$obj = new Finanzas;

$opc    = $_POST['opc'];
$encode = '';

switch ($opc) {

  case 0: // Formulario subir archivo

  $id   = $_POST['id'];

  $array = array($id);

  $sql          = $fin ->FILE_POLIZA($array );

  $existeArchiv = count($sql) ;

  

  if ($existeArchiv== 0 ) {

    $frm  = FILE_FORM('RespaldoPoliza',$id);

    

  }else {

    $Titulo   = array(

    'Fecha',

    'archivo',

    'Descripcion',

    '<span class="icon-cog"></span> ');

    $tag      = array('','','','DescargarFile');

    $tdMoneda = null;

    $conf     = null;

    

    $frm      = Table($Titulo,$tag,$tdMoneda,$sql,$conf);

    }

    

    

    $encode = array(

    0=>$frm);

    echo json_encode($encode);

   

  break;

   

   

   /*-----------------------------------*/

   /* SUBIR RESPALDO DE POLIZA

   /*-----------------------------------*/

   

   case 1:

   $Obs    = $_POST['Detalles'];

   $id     = $_POST['idFile'];

   $udn    = 1;

   $msj    = 'ignoro';

   $sql    = '';

   /*-------- Input file ---------*/

   $ruta    = 'recursos/file_res/';

   $dataFecha=  date("Y")."-".date("m")."-".date("d");

   

   foreach ($_FILES as $cont => $key) {

    if($key['error'] == UPLOAD_ERR_OK ){

     

     $NombreOriginal = $key['name'];

     $temporal = $key['tmp_name'];

     $Destino  = '../../../'.$ruta.$NombreOriginal;

     $files    = $ruta.$NombreOriginal;

     move_uploaded_file($temporal, $Destino);

     

     $array = array($udn,$dataFecha,$NombreOriginal,$Obs,$files,$id);

     $Names = array('udn_file','Fecha','Archivo','Descripcion','Ruta','id_cheque');

     $msj   = $fin->SAVE_FORM($array,$Names,'hgpqgijw_finanzas.file_polizas');

     

     

    }else {

     $msj ="<label class='text-danger'><span class='icon-cancel'></span>Ah ocurrido un error al subir el archivo</label>";

    }

    

   } // end foreach

   

   

   $encode = array(0=>$msj);

   echo json_encode($encode);

   break;

   

   /*-----------------------------------*/

   /* ** Eliminar BackUp

   /*-----------------------------------*/

   case 2:

   sleep(2);

   $idFile = $_POST['id'];

   $array  = array($idFile);

   $msj    = $fin->EliminarBackUP($array);

   break;

   

   /*-----------------------------------*/

   /*		Ingresos - Formas de pago

   /*-----------------------------------*/

   

   

   case 3:

   $date   = $_POST['date'];

   $idS    = $_POST['idS']; //id Subcategoria

   

   $total2 = $obj ->Select_Total2($date,$idS);

   

   // $fp     = $obj ->Select_FP(array($idS));

   //

   // $subTotal =0;

   //

   // foreach ($fp as $key) {

   //   $subTotal = $subTotal +  (($total2)/100) * $key[0];

   // }

   //

   // $sub = $total2 -$subTotal;

   

   $respuesta = array($total2,$total2,$total2);

   echo json_encode($respuesta);

   break;

   /*-----------------------------------*/

   /*		subcategoria ingresos

   /*-----------------------------------*/

   case 4:

   $n    = $_POST['nombre'];

   $g    = $_POST['grupo'];

   $idC  = $_POST['idcat'];

   

   $msj = $fin->SAVE_FORM(

    array($n,$idC,$g,1),

    array('Subcategoria','id_categoria','id_grupo','Stado'),

    'hgpqgijw_finanzas.subcategoria');

    

    break;

    

    case 5:

    $n    = $_POST['nombre'];

    $idC  = $_POST['idcat'];

    

    $msj = $obj->BuscarSubcategoria(array($idC,$n));

    

    if ($msj!=0) {

     $msj   = '<i class="text-danger icon-cancel-circled "></i> '.$msj[0].' ya existe';

    }

    $encode=array($msj);

    echo json_encode($encode);

    break;

    

    case 6: // Cierre diario

    $encargado = $_POST['usuario'];

    $id        = $_POST['id'];

    $msj       = $obj->CIERRE_DIARIO(array($encargado,$id));

    break;

    

    case 7: // Tarifa subtotal

    $idC       = $_POST['idC'];

    $idS       = $_POST['idS'];

    $Precio    = $_POST['Precio'];

    $msj       = $obj->actualizar_precios(array($Precio,$idS));

    $dataFecha = date("Y")."-".date("m")."-".date("d");

    $array     = array($idS,$Precio,$dataFecha,'Somx');

    $Names     = array('id_subcategoria','Tarifa_anterior','FechaIngreso','Responsable');

    $msj       = $fin->SAVE_FORM($array,$Names,'hgpqgijw_finanzas.sub_precio');

    

    echo $Precio;

    break;

    

    

    /*-----------------------------------*/
    /*	 JS - FUNCIONES
    /*-----------------------------------*/

    case 8:

    

    $idFolio = $_POST['idFolio'];

    $date    = $_POST['date'];

    $btn     = 'disabled';

    $estatus = 0;

    $check = $obj -> ver_CheckList(array($idE));

    $txt   = '';

    

    // Alert ------------------------------------|

    

    $txt   .= '<div class="row">';

    $txt   .= '<div class="col-xs-12 col-sm-12">';

    $txt   .= '<div class="alert alert-warning"> <b><i class="bx bx-error-circle bx-md bx-fw" ></i></b> ';

    

    $txt   .= ' Para poder hacer cierre del día son necesarios los siguientes archivos, ';

    $txt   .= 'o en caso contrario indicar el motivo del porque no se subio.';

    $txt   .= '</div></div></div>';

    

    

    $txt .= '<div class="row"> ';

    

    

    foreach ($check as $key) {

     $list  = '[SIN ARCHIVO]';

     

     $archivos    = $obj -> sobres_check_list(array($date,$key[0]));

     

     

     if (count($archivos)) { // Si se subieron archivos ese dia

      $estatus += 1;

      foreach ($archivos as $data) {

       $list = $data[1];

       

       if($data[1]==''){

        $list = $data[5];

       }

       

      }

      $txt .=

      '<div class="col-xs-12 col-sm-12">

      <i style="font-size:1.7em" class="bx bxs-check-circle text-success " ></i>

      <span class="text-file" style=""> '.$key[1].'</span> <span class="subtitle">'.$list.' </span>

      </div>';

      

     }else{ // si no se encontro

      $txt .= '<div class="container form-group" id="txtLoad'.$key[0].'">';

      $txt .= '<div class="row">';

      $txt .= '<div class="col-xs-12 col-sm-5">';

      $txt .= '<i style="font-size:1.7em" class="bx bx-file  text-warning" ></i>';

      $txt .= '<span style="font-weight:500"> '.$key[1].'</span>';

      $txt .= '</div>'; // col-sm-5

      

      

      $txt .= '<div class="col-xs-12 col-sm-7">';

      

      $txt .=

      '<div id="content-btn'.$key[0].'" class="btn-group">

      <label class="btn  btn-xs btn-info"> Subir archivo

      <input type="file" style="display: none;" id="archivos'.$key[0].'" onchange="subir_fichero('.$key[0].','.$idFolio.')">

      </label>

      <a class="btn btn-xs btn-default" onclick="view_motivo('.$key[0].',1)">

      Motivo</a></div>';

      

      $txt .= '<div class="row hide" id="content-motivo'.$key[0].'">';

      $txt .= '<div class="col-sm-12 col-xs-12">';

      $txt .= '<textarea id="file_motivo'.$key[0].'" class="form-control" style="font-size:.8em;"></textarea>';

      $txt .= '</div>';

      $txt .= '<div style="padding-top:5px;" class="col-sm-12 text-right">';

      $txt .= ' <a onclick="view_motivo('.$key[0].',2)" class="btn btn-xs btn-danger">Cancelar</a>';

      $txt .= ' <a class="btn btn-xs btn-success" onclick="agregar_motivo('.$key[0].')" >Guardar</a>';

      $txt .= '</div>';

      $txt .= '</div>';

      $txt .= '</div>'; // col-sm-7

      

      $txt .= '</div>'; // row

      $txt .= '</div>'; // form-group

      

      

     }

     

     

    } // end foreach

    

    $txt .= '</div> ';

    

    if (count($check)==$estatus) {

     $btn     = '';

     $txt     = '';

    }

    

    $txt .= '<div style="padding-top:15px" class="row"><div class="col-xs-12 col-sm-12">';

    $txt .= '<input  id="txtCargo" class="form-control input-lg"  placeholder="Nombre de la persona a cargo" '.$btn.'>';

    $txt .= '<br><div class="form-group text-right">';

    $txt .= '<button class="btn btn-primary " onclick="CierreHotel('.$idFolio.')" '.$btn.'>';

    $txt .= 'Continuar... <i class="bx bx-right-arrow-circle bx-sm"></i></button></div>';

    $txt .= '</div>';

    

    $encode = array(0=>$txt);

    echo json_encode($encode);

    break;

    /*-----------------------------------*/

    /*	Subir motivo de archivo

    /*-----------------------------------*/

    case 9:
      $date    = $_POST['date'];
      $idCheck = $_POST['idCheck'];
      $motivo  = $_POST['motivo'];
      $array   = array($idE,$date,$idCheck,$motivo);

      

      $obj->Insert_sub_check($array);

      echo $date.' -  '.$idCheck;

    break;

    

    case 10:// modulo de check in

      $idOcupacion = $_POST['idOcupacion'];

      $frm  = '';

      $frm  = '

      <div class="row">

      

      <div class="col-sm-12 col-xs-12">

      <label>Nombre del huésped</label>

      <input id="txtHuesped" class="form-control input-sm ui-autocomplete-input" onfocus="add_cliente_input()" autocomplete="off" focus>

      </div>

      

      </div>

      ';



      $frm .= '

       <div  style="margin-top:10px" class="row">

      <div class="col-xs-12 col-sm-12">

      <label>Fecha de entrada </label>

      

      <div class="input-group date calendarioEntrada">

        <input type="text" class="select_input form-control input-sm" value="" id="txtEntrada">

        <span class="input-group-addon input-sm" id="basic-addon2" style=""><label class="fa fa-calendar"></label> </span>

      </div>

      </div>

      

      </div>

      ';

      

      $frm .= '

   

      <div  style="margin-top:10px" class="row">

      <div class="col-xs-12 col-sm-12">

      <label>Fecha de Salida </label>

      

      <div class="input-group date calendarioSalida">

        <input type="text" class="select_input form-control input-sm" value="" id="txtSalida">

        <span class="input-group-addon input-sm" id="basic-addon2" style=""><label class="fa fa-calendar"></label> </span>

      </div>

      </div>

      

      </div>

      

      <div style="margin-top:20px" class="row">

      <div class="col-xs-12 ">

       <label>Observaciones</label>

      <textarea class="form-control" id="txtObservacion">

      </textarea>

      </div>

      </div>





      <div style="margin-top:20px" class=" row">

      <div class="col-sm-12 col-xs-12">

      

      <a class="btn btn-primary col-sm-12" onclick="AgregarHuesped('.$idOcupacion.')"> Continuar </a>" 

      </div>

      </div>

      ';

      

      echo $frm;

    break;

    case 11: //   Asignar huesped a cuenta de hotel

      $huesped   = $_POST['huesped'];
      $entrada   = $_POST['entrada'];
      $salida    = $_POST['salida'];

      $id        = $_POST['id'];
      $observ    = $_POST['observacion'];
      
      $idHuesped = $obj->Consultar_huesped(array($huesped));


      if($idHuesped == 0){
      
      
      $idHuesped = $obj->nuevo_huesped(array($huesped));
      
        // $idHuesped = $obj->Consultar_huesped(array($huesped));
      }    

  
      $txt  =  $idHuesped;
      echo $txt;

    break;

    case 12:

     $nombres = array();
     $list = $obj -> check_as(array(1));
     foreach ($list as $key => $row) {
       $nombres[$key] = $row[1];
     }

     echo json_encode($nombres);
    break;

   }

   

   

   

   /*-----------------------------------*/

   /*	 JS - FUNCIONES

   /*-----------------------------------*/

   function FILE_FORM($onclick,$id){

    $frm = '';

    

    $frm = $frm.'

    

    <div class="row">

    <div class="form-group col-sm-12">

    <label class="col-sm-12 text-center">

    <strong>Seleccionar archivos</strong>

    </label>

    <div class="col-sm-8 col-sm-offset-2">

    <input type="file" class="form-control input-sm" id="txtArchivos"> </div>

    </div>

    </div><!--  ./ archivos -->

    

    <div class="row">

    <div class="form-group col-sm-12">

    <label class="col-sm-12 text-center">

    <strong>Detalles</strong>

    </label>

    <div class="col-sm-8 col-sm-offset-2">

    <textarea name="name" rows="4" class="col-sm-12 col-xs-12 form-control input-sm" id="txtDetalles"></textarea>

    </div>

    </div>

    </div><!-- ./ detalles -->

    

    <div id="Resul" class="text-center"> </div>

    <div class="row">

    <div class="form-group col-sm-8 col-sm-offset-2 col-xs-12">

    <label class="col-sm-12 text-center"> Limite máximo 20Mb * </label>

    <button style="width:100%" class="btn btn-sm btn-info" onclick="'.$onclick.'('.$id.')">

    <span class="icon-upload"></span> Subir Archivos</button>

    </div>

    </div>

    

    ';

    

    return $frm;

   }

   

   function Table($Titulo,$tag,$tdMoneda,$sql,$conf){

    

    $total    = count($Titulo)-1;

    $tb       = '';

    $opc      = 0 ;

    $tb =$tb.'

    

    <div class="row">

    <div class="col-sm-4 col-sm-offset-4 col-xs-4 text-center" id="Res"></div>

    <div class="col-sm-12 col-xs-12 text-right">

    <h5><label>Registros: '.count($sql).'</label></h5>

    </div>

    </div>

    

    <div class="">

    <table  class="table table-hover table-bordered table-striped table-table-condensed">

    <thead>

    <tr class="tr-title text-left">';

    

    for ($i=0; $i < count($Titulo) ; $i++) { // Imprimir thead

     $tb =$tb.'<td class="text-center">'.$Titulo[$i].'</td>';

    }

    

    

    $tb =$tb.'</tr></thead><tbody>';

    

    foreach ($sql as $key ) {

     $tb=$tb.'<tr>

     <td> <span class="icon-calendar"> </span> '.$key[0].'</td>

     <td>'.$key[1].'</td>

     <td>'.$key[2].'</td>

     <td class="text-center">

     <a href=" '.$key[3].'" title="Descargar" target="_blank" class="btn btn-xs btn-info"><span class="icon-download"></span> </a>

     <a class="btn btn-danger btn-xs" onclick="EliminarRespaldo('.$key[4].')"><span class="icon-cancel"></span></a>

     </td>

     </tr>';

    }

    

    

    //

    //

    // foreach ($sql as $key ) {

    //

    //  $opc  += 1;

    //  $tb=$tb.'<tr>';

    //

    //  for ($i=0; $i < count($Titulo) ; $i++) { // Numero de columnas

    //

    //

    //   if (count($tdMoneda) == 0) { // Celda Sin formato de costo

    //    $tb =$tb.'<td id="'.$tag[$i].''.$key[$total].'">

    //    <span id="lbl-frm" onclick="tdEdit(\''.$tag[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.$key[$i].'</span>

    //    </td>';

    //   } else { // Celda con Formato de costo

    //

    //    for ($j=0; $j <count($tdMoneda) ; $j++) {

    //     if ($tdMoneda[$j]==$i) {

    //      $tb =$tb.'<td

    //      class="text-right" id="'.$tag[$i].''.$key[$total].'">

    //      <span onclick="tdEdit(\''.$tag[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.evaluar($key[$i]).'</span>

    //      </td>';

    //     } // end if

    //     else {

    //      $tb =$tb.'<td id="'.$tag[$i].''.$key[$total].'">

    //      <span onclick="tdEdit(\''.$tag[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.$key[$i].'</span>

    //      </td>';

    //

    //     } // end else

    //

    //    }//end for

    //   } //end else

    //

    //

    //  } // Numero de columas

    //

    //

    //

    //  /* Configurar boton de eliminar */

    //  $habilitar = '';

    //  if (count($conf) == null) {

    //   $tb=$tb.'<td class="text-center">

    //   <button class="btn btn-danger btn-xs" onClick="'.$tag[$total].'('.$key[$total].')">

    //   <span class="icon-cancel"></span></button></td>';

    //

    //  }else {

    //

    //   for ($k=0; $k < count($conf) ; $k++) {

    //    if ($opc == $conf[$k]) { // 1 = 1

    //     $habilitar = 'disabled';

    //    }

    //

    //   }

    //

    //

    //   $tb=$tb.'<td class="text-center">

    //   <button class="btn btn-danger btn-xs" '.$habilitar.'>

    //   <span class="icon-cancel"></span></button></td>';

    //

    //  }

    //

    //  $tb=$tb.'</tr>';

    //

    // }

    

    

    $tb =$tb.'</tbody></table></div>';

    

    return $tb;

    

    

   } //End crearTB

   

   function evaluar($val){

    $res = '';

    if ($val==0 || $val=="") {

     $res = '-';

    }else {

     $res =' '.$val;

    }

    

    return $res;

   }

   

   

   ?>

