<?php

Class Table_UI{
    
function table_view($Titulo,$tag,$tdMoneda,$sql){

  $total    = count($Titulo)-1;
  $tb       = '';

  $tb =$tb.'
  <div class="table-responsive">
  <table id="table_view" class="table table-bordered table-hover" style="width:100%">
  <thead>
  <tr>';

  for ($i=0; $i < count($Titulo) ; $i++) { // Imprimir thead
   $tb =$tb.'<th class="text-center">'.$Titulo[$i].'</th>';
  }

  $tb =$tb.'</tr></thead><tbody>';

  if ($sql!=null) {
   foreach ($sql as $key ) {

    $a = '<a onclick="ver_detalles(\''.$key[$total].'\')"
    class="btn btn-outline-success "> <i class="bx bx-file"></i>  Ver Formato</a>';
    $tb .='<tr>';

    for ($i=0; $i < count($Titulo)-1 ; $i++) { // Numero de columnas



     if (count($tdMoneda) == 0) { // Celda Sin formato de costo

      $tb =$tb.'<td class="text-center" id="'.$tag[$i].''.$key[$total].'">
      <label id="lbl-frm" >'.$key[$i].'</label>
      </td>';
     }else { // Celda con Formato de costo
      $class = 'class="text-center"';

      $etiqueta = $key[$i];

      for ($x=0; $x <count($tdMoneda) ; $x++) {


       if ($tdMoneda[$x]==$i) { // Compara la posicion , si ocupa formato
        $class = 'class="text-right"';
        $etiqueta = evaluar($key[$i]);
       }

      }//end for

      $tb =$tb.'<td '.$class.'>'.$etiqueta.' </td>';



     } //end else


    } // Numero de columas


    $tb=$tb.'<td class="text-center">'.$a.'</td>';


    $tb=$tb.'</tr>';

   }
  }


  $tb =$tb.'</tbody></table>';

  return $tb;


 }
    
    
    

 /*--------- Data Table Conf -----------*/
 function DATA_TABLE($title,$sql){
  $data = '';
  $txt  = '';

  foreach ($sql as $x) {

   $a = '<div class=\"btn-group pull-right\"><button class=\"btn btn-outline-info dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\"><span class=\"icon-params\"></span></button><ul class=\"dropdown-menu\"><li><a onclick=\"\"><i class=\"fa fa-edit\"></i> Editar</a></li><li><a  onclick=\"\"><i class=\"fa fa-trash\"></i>Borrar</a></li></ul></div>';

   $txt=$txt.'{';
    for ($i=0; $i <= count($title)-1 ; $i++) {
     $txt=$txt.'"'.$title[$i].'":"'.$x[$i].'",';
    }
    $txt=$txt.'"opc":" '.$a.'"
   },';
  }

  $txt = substr($txt,0,strlen($txt)-1);
  $data= '{"data":['.$txt.']}';
  return $data;
 }

 function VIEW_DATA_TABLE($title,$idTable){
  $txt ='';

  $txt = $txt.'

  <div class="">
  <table id="'.$idTable.'" class="table table-striped table-bordered ">

  <thead><tr class="text-center">';

  for ($i=0; $i <  count($title); $i++) {
   $txt = $txt.'<th >'.$title[$i].'</th>';
  }

  $txt = $txt.'</tr>
  </thead>

  </table>
  </div>
  ';
  return $txt;
 }



 function Simple_Table_btn($Titulo,$tdMoneda,$sql,$conf){
  $total    = count($Titulo)-1;
  $tb       = '';
  $opc      = 0 ;


  $tb =$tb.'
  <div class="row">

  <div class="col-sm-12 col-xs-12 text-right">
  <h5><label>Registros: '.count($sql).'</label></h5>
  </div>
  </div>

  <div class="col-sm-12">
  <table id="size1" class="table table-hover table-bordered table-striped">
  <thead>
  <tr class="tr-title text-left">';

  for ($i=0; $i < count($Titulo) ; $i++) { // Imprimir thead
   $tb =$tb.'<th class="text-center">'.$Titulo[$i].'</th>';
  }

  $tb =$tb.'</tr></thead><tbody>';

  foreach ($sql as $key ) {

   $opc  += 1;
   $tb=$tb.'<tr>';

   for ($i=0; $i < count($Titulo)-1 ; $i++) { // Numero de columnas


    if (count($tdMoneda) == 0) { // Celda Sin formato de costo
     $tb =$tb.'<td>
     '.$key[$i].'
     </td>';
    } else { // Celda con Formato de costo
     $class = '';

     $etiqueta = $key[$i];

     for ($x=0; $x <count($tdMoneda) ; $x++) {


      if ($tdMoneda[$x]==$i) { // Compara la posicion , si ocupa formato
       $class = 'class="text-right"';
       $etiqueta = evaluar($key[$i]);
      }

     }//end for

     $tb =$tb.'<td '.$class.'>'.$etiqueta.' </td>';



    } //end else


   } // Numero de columas



   /* Configurar boton de eliminar */
   $habilitar = '';
   if (count($conf) == null) {
    $tb=$tb.'<td class="text-center">
    <button class="btn btn-danger btn-xs" onClick="Eliminar('.$key[$total].')">
    <span class="icon-cancel"></span></button></td>';

   }else {

    for ($k=0; $k < count($conf) ; $k++) {
     if ($opc == $conf[$k]) { // 1 = 1
      $habilitar = 'disabled';
     }

    }


    $tb=$tb.'<td class="text-center">
    <button class="btn btn-danger btn-xs" '.$habilitar.'>
    <span class="icon-cancel"></span></button></td>';

   }

   $tb=$tb.'</tr>';

  }

  return $tb;
 }





 function CrearTB($arrayTitle,$sql,$EditTD,$finanzas){
  $total   = count($arrayTitle);
  $tb='';

  $TotalGral = 0 ;
  foreach ($sql as $key ){
   // $TotalGral = $TotalGral + ($key[5] + $key[6]);
   $TotalGral = $TotalGral + ($key[7] );
  }

  $tb =$tb.'
  <div class="text-right col-xs-12 col-sm-12" >
  <button class="btn btn-info " onclick="Imprimir()"><span class="fa fa-print"></span> Imprimir </button>
  </div>
  <div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-4 text-left">
  <h5><label>Gastos del día: '.evaluar($TotalGral).' </label></h5>
  </div>
  <div class="col-sm-4 col-xs-4 text-center" id="Res_Gastos"></div>
  <div class="col-sm-4 col-xs-4 text-right">
  <h5><label>Compras registradas: '.count($sql).'</label></h5>
  </div>
  </div>

  <div class="col-xs-12 col-sm-12 table-responsive">
  <table class="table table-striped table-hover table-bordered table-condensed"><thead><tr >';

  for ($i=0; $i < count($arrayTitle) ; $i++) {
   $tb =$tb.'<td class="text-center">'.$arrayTitle[$i].'</td>';
  }

  $tb =$tb.'</tr></thead><tbody>';


  foreach ($sql as $key ) {
   $tb=$tb.'<tr>';

   for ($i=0; $i < count($arrayTitle)-1 ; $i++) {

    if ($i==5 || $i==6 || $i==7) {
     $tb =$tb.'<td
     class="text-right" id="'.$EditTD[$i].''.$key[$total].'">
     <label onclick="Convertir_input(\''.$EditTD[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.evaluar($key[$i]).'</label>
     </td>';

    }else {
     $tb =$tb.'<td
     class="text-center" id="'.$EditTD[$i].''.$key[$total].'">
     <label onclick="Convertir_input(\''.$EditTD[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.$key[$i].'</label>
     </td>';
    }
   }


   $poliza = $finanzas -> SELECT_POLIZA($key[$total]);
   $pol    = 2;

   if (count($poliza)!=1) {
    $pol    = 1;
   }


   $tb =$tb.'<td class="text-center">
   <a class="btn btn-xs btn-info"
   data-toggle="modal" data-target="#M1"
   onclick="subirArchivo('.$key[$total].','.$pol.')">
   <span class=" icon-upload"></span></a>

   <a class="btn btn-xs btn-danger"
   data-toggle="modal" data-target="#exampleModal"
   onclick="Eliminar_Compras_G('.$key[$total].',1)">
   <span class="icon-cancel"></span></a></td>';

   $tb=$tb.'</tr>';

  }


  $tb =$tb.'</tbody></table></div>';

  return $tb;


 } //End crearTB

 function PrintTb($arrayTitle,$sql,$EditTD){
  $total   = count($arrayTitle);
  $tb='';

  $TotalGral = 0 ;
  foreach ($sql as $key ){ $TotalGral = $TotalGral + ($key[5] + $key[6]); }

  $tb =$tb.'

  <div class="col-sm-12 col-xs-12">
  <div class="col-sm-4 col-xs-4 text-left">
  <h5><label>Gastos del día: '.evaluar($TotalGral).' </label></h5>
  </div>
  <div class="col-sm-4 col-xs-4 text-center" id="Res_Gastos"></div>
  <div class="col-sm-4 col-xs-4 text-right">
  <h5><label>Compras registradas: '.count($sql).'</label></h5>
  </div>
  </div>

  <div class="col-xs-12 col-sm-12 table-responsive">
  <table class="table table-striped table-hover table-bordered table-condensed"><thead><tr >';

  for ($i=0; $i < count($arrayTitle) ; $i++) {
   $tb =$tb.'<td class="text-center">'.$arrayTitle[$i].'</td>';
  }

  $tb =$tb.'</tr></thead><tbody>';


  foreach ($sql as $key ) {
   $tb=$tb.'<tr>';

   for ($i=0; $i < count($arrayTitle) ; $i++) {

    if ($i==5 || $i==6 || $i==7) {
     $tb =$tb.'<td
     class="text-right" id="'.$EditTD[$i].''.$key[$total].'">
     <label onclick="Convertir_input(\''.$EditTD[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.evaluar($key[$i]).'</label>
     </td>';

    }else {
     $tb =$tb.'<td
     class="text-center" id="'.$EditTD[$i].''.$key[$total].'">
     <label onclick="Convertir_input(\''.$EditTD[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.$key[$i].'</label>
     </td>';
    }
   }





   $tb=$tb.'</tr>';

  }


  $tb =$tb.'</tbody></table></div>';

  return $tb;


 } //End crearTB


 function Simple_Table($Titulo,$tdMoneda,$sql){
  $total    = count($Titulo)-1;
  $tb       = '';
  $opc      = 0 ;
  $tb =$tb.'

  <div class="row">

  <div class="col-sm-12 col-xs-12 text-right">
  <h5><label>Registros: '.count($sql).'</label></h5>
  </div>
  </div>

  <div class="col-sm-12">
  <table id="size1" class="table table-hover table-bordered table-striped">
  <thead>
  <tr class="tr-title text-left">';

  for ($i=0; $i < count($Titulo) ; $i++) { // Imprimir thead
   $tb =$tb.'<th class="text-center">'.$Titulo[$i].'</th>';
  }

  $tb =$tb.'</tr></thead><tbody>';

  foreach ($sql as $key ) {

   $opc  += 1;
   $tb=$tb.'<tr>';

   for ($i=0; $i < count($Titulo) ; $i++) { // Numero de columnas


    if (count($tdMoneda) == 0) { // Celda Sin formato de costo
     $tb =$tb.'<td>
     '.$key[$i].'
     </td>';
    } else { // Celda con Formato de costo
     $class = '';

     $etiqueta = $key[$i];

     for ($x=0; $x <count($tdMoneda) ; $x++) {


      if ($tdMoneda[$x]==$i) { // Compara la posicion , si ocupa formato
       $class = 'class="text-right"';
       $etiqueta = evaluar($key[$i]);
      }

     }//end for

     $tb =$tb.'<td '.$class.'>'.$etiqueta.' </td>';



    } //end else


   } // Numero de columas



   /* Configurar boton de eliminar */
   // $habilitar = '';
   // if (count($conf) == null) {
   //  $tb=$tb.'<td class="text-center">
   //  <button class="btn btn-danger btn-xs" onClick="'.$tag[$i].'('.$key[$total].')">
   //  <span class="icon-cancel"></span></button></td>';
   //
   // }else {
   //
   //  for ($k=0; $k < count($conf) ; $k++) {
   //   if ($opc == $conf[$k]) { // 1 = 1
   //    $habilitar = 'disabled';
   //   }
   //
   //  }
   //
   //
   //  $tb=$tb.'<td class="text-center">
   //  <button class="btn btn-danger btn-xs" '.$habilitar.'>
   //  <span class="icon-cancel"></span></button></td>';
   //
   // }

   $tb=$tb.'</tr>';

  }

  return $tb;
 }


 // Plantilla para  crear una tabla con opciones de eliminar y editar
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
  <table id="size1" class="table table-hover table-striped">
  <thead>
  <tr class="tr-title text-left">';

  for ($i=0; $i < count($Titulo) ; $i++) { // Imprimir thead
   $tb =$tb.'<th class="text-center">'.$Titulo[$i].'</th>';
  }


  $tb =$tb.'</tr></thead><tbody>';





  foreach ($sql as $key ) {

   $opc  += 1;
   $tb=$tb.'<tr>';

   for ($i=0; $i < count($Titulo)-1 ; $i++) { // Numero de columnas


    if (count($tdMoneda) == 0) { // Celda Sin formato de costo
     $tb =$tb.'<td id="'.$tag[$i].''.$key[$total].'">
     <label id="lbl-frm" onclick="tdEdit(\''.$tag[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.$key[$i].'</label>
     </td>';
    } else { // Celda con Formato de costo

     for ($j=0; $j <count($tdMoneda) ; $j++) {
      if ($tdMoneda[$j]==$i) {
       $tb =$tb.'<td
       class="text-right" id="'.$tag[$i].''.$key[$total].'">
       <label onclick="tdEdit(\''.$tag[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.evaluar($key[$i]).'</label>
       </td>';
      } // end if
      else {
       $tb =$tb.'<td id="'.$tag[$i].''.$key[$total].'">
       <label onclick="tdEdit(\''.$tag[$i].'\' ,\''.$key[$total].'\',\''.$key[$i].'\')">'.$key[$i].'</label>
       </td>';

      } // end else

     }//end for
    } //end else


   } // Numero de columas



   /* Configurar boton de eliminar */
   $habilitar = '';
   if (count($conf) == null) {
    $tb=$tb.'<td class="text-center">
    <button class="btn btn-danger btn-xs" onClick="'.$tag[$i].'('.$key[$total].')">
    <span class="icon-cancel"></span></button></td>';

   }else {

    for ($k=0; $k < count($conf) ; $k++) {
     if ($opc == $conf[$k]) { // 1 = 1
      $habilitar = 'disabled';
     }

    }


    $tb=$tb.'<td class="text-center">
    <button class="btn btn-danger btn-xs" '.$habilitar.'>
    <span class="icon-cancel"></span></button></td>';

   }

   $tb=$tb.'</tr>';

  }


  $tb =$tb.'</tbody></table></div>';

  return $tb;


 } //End crearTB

}

function Tb_Scrollx($sum,$Fechas,$Nom,$Function,$fin){

 $bg_categoria = "#ECF0F1";
 $Nombres      = array();
 $idUG         = array();
 $Cantidad     = array();
 $tb           = '';


 $tb  = $tb.'
 <div class="row">
 <div class="col-sm-12 col-xs-12">
 <div class="scrolling outer">
 <div class="inner">
 <table class="table table-condensed table-bordered">
 <thead>
 <tr>
 <th class="text-center">TOTAL DE GASTOS</th>
 <td id="th">'.evaluar($sum[0]).'</td>';


 foreach ($Fechas as $key ) {
  $array = array($sum[1],$key[0],$sum[2]); // idEmpresa, fecha, idClase

  $dato = $fin->  $Function[0] ($array);  // <-- Gastos x fecha
  $tb  = $tb.'<td id="td" style="height:28px;"><strong>'.$dato.'</strong></td>';
 }

 $tb  = $tb.'</tr></thead><tbody>
 <tr> <!-- Sub-->
 <th  class="text-center bg-danger">GASTOS</th>
 <td id="th" class="bg-danger text-center">TOTAL</td>';

 foreach ($Fechas as $key ) {
  $tb  = $tb.'<td id="td" class="bg-info text-center" ><strong>'.$key[0].'</strong></td>';
 }

 $tb  = $tb.' </tr>';
 /*--------------------------------------------*/
 /*	 Imprimir Conceptos en el rango de fechas
 /*-------------------------------------------*/

 foreach ($Nom as $key => $data_name) {
  $idUG[$key]       = $data_name[0];
  $Nombres[$key]    = $data_name[1];
  $Cantidad[$key]   = $data_name[2];
 }

 $Count_Name = count($Nombres);

 for ($i=0; $i < $Count_Name; $i++) { // recorrido por nombres

  $array = array($idUG[$i],$sum[1],$sum[3],$sum[4]);
  $fold  = $fin->  $Function[1]($array,$sum[2]);  // Folding Conceptos

  /* View folding */


  $tb  = $tb.'
  <tr id="v-folding" onClick="gastos_detalles('.$Count_Name.','.$i.');">

  <th id="v-folding">
  <span class="Name_Cat'.$i.' icon-right-dir"></span>'.$Nombres[$i].'
  </th>

  <td id="th" class="text-right"
  style=" background:'.$bg_categoria.'; cursor:pointer;" ">
  $ '.number_format($Cantidad[$i],2,'.',', ').'
  </td>';

  foreach ($Fechas as $key ) {
   $array = array($idUG[$i],$key[0],$sum[2]);
   $dato  = $fin->verGastosCategoria($array);
   $tb    = $tb.'<td id="td" class="text-right"> '.evaluar($dato).'</td>';
  }

  $tb  = $tb.'</tr>';



  /*Generar hide - folding */

  foreach ($fold as $value ) {
   $tb  = $tb.'
   <tr class="GD_'.$i.' hide ">
   <th style="" >'.$value[1].'</th>
   <td id="th" class="text-right"> '.$value[2].'</td>';

   foreach ($Fechas as $key ) { // llenar folding por fecha
    $array = array($value[0],$key[0],$sum[2]);
    $dato = $fin->Select_Data_Hoy_Gastos($array);
    $tb  = $tb.'<td id="td" class="text-right">'.evaluar($dato[0]).'</td>';
   }


  } // End fold


  $tb  = $tb.'</tr>';

 }// End For nombres



 $tb  = $tb.'

 </tbody>
 </table>
 </div><!-- ./ inner -->
 </div><!-- ./ scrolling -->
 </div>
 </div><!-- ./row -->';

 return $tb;
}

/*-----------------------------------*/
/*	 Complementos **
/*-----------------------------------*/

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="") {
  $res = '-';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }

 return $res;
}



?>
