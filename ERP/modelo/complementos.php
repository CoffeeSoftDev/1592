<?php
Class Complementos{

 function fecha_hora_servidor(){
  $date  = date("Y-m-d G:H:s");
  return $date;
 } 
 
 function fecha_servidor(){
  $date  = date("Y-m-d");
  return $date;
 }

 function hora_servidor(){
  $date  = date("G:H:s");
  return $date;
 }
 function separar_fecha ($date){

  $y = date("Y", strtotime("$date"));
  $m = date("m", strtotime("$date"));
  $d = date("d", strtotime("$date"));

  $array = array($y,$m,$d );
  return $array;
 }

 function obtener_mes($mes){
  $m = '';
  switch ($mes) {
   case '01':$m = "Enero";break;
   case '02':$m = "Febrero";break;
   case '03':$m = "Marzo";break;
   case '05':$m = "Mayo";break;
   case '04':$m = "Abril";break;
   case '06':$m = "Junio";break;
   case '07':$m = "Julio";break;
   case '08':$m = "Agosto";break;
   case '09':$m = "Septiembre";break;
   case '10':$m = "Octubre";break;
   case '11':$m = "Noviembre";break;
   case '12':$m = "Diciembre";break;
  }

  return $m;


 }

 function msj_success($msj,$msjC){
  $txt = '';

  $txt .= '
  <div class="col-xs-12 col-sm-4 col-sm-offset-4">
  <center>
  <div class="center-block">
  <i style="font-size: 7em;" class="text-success bx bxs-check-circle" ></i>
  </div>
  <h3 style="font-size: 20px;"><b>'.$msj.'</b></h3>
  <br>
  <p>'.$msjC.'</p>
  <a onclick="location.reload()" class="btn btn-primary btn-lg btn-block">Aceptar</a>
  </center>
  </div>';

  return $txt;
 }
 
 function obtener_mes_corto($mes){
  $m = '';
  switch ($mes) {
   case '01':$m = "Ene";break;
   case '02':$m = "Feb";break;
   case '03':$m = "Mar";break;
   case '05':$m = "May";break;
   case '04':$m = "Abr";break;
   case '06':$m = "Jun";break;
   case '07':$m = "Jul";break;
   case '08':$m = "Ago";break;
   case '09':$m = "Sept";break;
   case '10':$m = "Oct";break;
   case '11':$m = "Nov";break;
   case '12':$m = "Dic";break;
  }

  return $m;

 }
 
 function obtener_dia($dia) {
  $txt   = ''; 
  
  $dias  = array('Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado');
  $fecha = $dias[date('N', strtotime($dia))];
  
  return $fecha;
 }
 
 function txt_data(){
   $txt   = 'hola mundo ';
   return $txt;
 }

 function dia_format($date){
  $lbl       = '';
  $dias      = array('','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');
  $dia_letra = $dias[date('N', strtotime($date))];

  $data  = $this-> separar_fecha($date);
  $mes   = $this-> obtener_mes($data[1]); 
  $lbl       = $dia_letra.', '.$data[2].' de '.$mes.' del '.$data[0];

  return $lbl;
 }

 function Select2($data,$value){
   $select2  = '';

   $select2 .= '<select class="js-example-basic-single" name="state">';
  
   foreach ($data as $key) {
    if($value==$key[1]){
    $select2 .= '<option>'.$key[1].'</option>';
    }else{
    $select2 .= '<option>'.$key[1].'</option>';
    }
     
   }
 
   $select2 .= '</select>';

   return $select2;
 }


}
?>
