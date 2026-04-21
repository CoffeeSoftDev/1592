<?php
include_once("_CRUD.php");

Class Util extends CRUD
{

  function solo_letras($var) {
      return trim(preg_replace('/\s+/', ' ', preg_replace('/[^a-zA-ZáéíóúüÁÉÍÓÚÜñÑ\s]+/u', '', $var)));
  }
  function solo_numeros($var) {
      $res = intval(preg_replace('/[^0-9]+/', '', $var), 10);
      if ( $res == '' ) { $res =  1; }
      return $res;
  }

 function Folio($Folio,$Area) {
     $NewFolio = 0; $Folio = $Folio + 1;
     if($Folio >= 1000){
        $NewFolio = $Area."".$Folio;
     }
     else if($Folio >= 100){
        $NewFolio = $Area."0".$Folio;
     }
     else if($Folio >= 10){
        $NewFolio = $Area."00".$Folio;
     }
     else if($Folio >= 1){
        $NewFolio = $Area."000".$Folio;
     }
     return $NewFolio;
  }

  function Mes_Letra($mes){
    switch ($mes) {
      case 1: return 'Ene'; break;
      case 2: return 'Feb'; break;
      case 3: return 'Mar'; break;
      case 4: return 'Abr'; break;
      case 5: return 'May'; break;
      case 6: return 'Jun'; break;
      case 7: return 'Jul'; break;
      case 8: return 'Ago'; break;
      case 9: return 'Sep'; break;
      case 10: return 'Oct'; break;
      case 11: return 'Nov'; break;
      case 12: return 'Dic'; break;
    }
  }


 function MSG_ERROR($arreglo){
  $text     = $arreglo[0];
  $onclick  = $arreglo[1];
  $mnsj = '
  <div class="text-center ">
  <i class="icon-cancel-circled text-danger" style="font-size:6rem;"></i>
  <p><h4><strong>'.$text.'</strong></h4></p>
  </div>

  <br>

  <div class="form-group">
  <div class="col-sm-12 col-xs-12" id="btnError">
  <center>
  <button type="button" class="btn  btn-default"
  '.$onclick.'
  data-dismiss="modal" style="width:200px"> Salir </button>
  </center>
  </div>
  </div>
  ';

  return  $mnsj;
 }



 function MODAL_SUCCESS($array){

  $title          = $array[0];
  $onclick        = $array[1];
  $info           = $array[2];
  $header         = $array[3];
  $id             = $array[4];

  $modal = '
  <div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  '.$header.'
  </div>

  <div class="modal-body">

  <div class="mx-auto d-block">
  <center>
  '.$title.'


  '.$info.'
  </center>
  </div>
  </div>


  <div class="modal-footer">
  <center>
  <button class="btn btn-default " data-dismiss="modal" onclick="'.$onclick.'()" style="width:200px font-size:12px"><span class=" icon-cancel-circled-outline"></span> Salir</button>
  </center>
  </div>

  ';

  return  $modal;
 }

 function paginar($paginaActual,$Paginas,$url,$Lotes){
  $noPaginas=ceil($Paginas/$Lotes);
  $med = 2;
  $min =0;
  $maxPag=4;
  if($noPaginas<4){
   $maxPag=$noPaginas;
  }

  if(($paginaActual+$med) >=$noPaginas){  $min =max(($noPaginas-$maxPag+1),1);   }



  else{ if(($paginaActual-$med)>0) { $min = ($paginaActual-$med); } else{ $min = 1; } }

  $max = $maxPag;
  $limit=0;
  $lista='';
  $tabla='';
  $limit = 0;
  $result =ceil($Paginas/$Lotes);


  if($noPaginas != 1){
   echo "
   <div class='text-center'> <ul class='pagination pagination-sm  bg-info' id='codea'>";
   if($paginaActual >1 ){
    $max = min($min+$maxPag-1,$Paginas); $pag = $paginaActual-1;
    if ($paginaActual >3 ){echo "<li><a href='javascript:$url(1);'> <span class='icon-angle-double-left'></span> </a></li>";}
    echo "<li><a href='javascript:$url($pag);'> <span class='icon-angle-left'></span> </a></li>";
   }

   for($i=$min; $i <=$max;$i++){
    if($i==$paginaActual){ echo "<li class='active'><a href='javascript:$url($i);'>$i</a></li>"; }
    else{ echo "<li><a href='javascript:$url($i);'>$i</a></li>";}
   }
   /* PAGINA FINAL*/
   $pags= $noPaginas-1;
   if($paginaActual < $noPaginas ){
    $pag = $paginaActual+1;
    echo "<li><a href='javascript:$url($pag);'> <span class='icon-angle-right'></span> </a></li>";
    echo "<li><a href='javascript:$url($noPaginas);'> <span class='icon-angle-double-right'></span></a></li>";
   }
   if($paginaActual <=1 ){ $limit=0;   }
   else{ $limit = $Lotes*($paginaActual-1);}
   echo "</ul></div>";
  }
 }
}
