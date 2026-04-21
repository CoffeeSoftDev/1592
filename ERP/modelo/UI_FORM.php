<?php

Class FORM_UI{


 function text_area($array,$val,$disabled){

  $txt =
  '<div class="form-group">

  <label  class="col-xs-12 col-sm-4">'.$array[0].': </label>

  <div id="tag'.$array[1].'" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
  <input type="text"
  class="form-control input-xs"
  id="txt'.$array[1].'"
  name="'.$array[1].'"
  style="text-transform:uppercase;"
  onBlur    = "'.$array[2].'"
  onkeydown = "'.$array[2].'"
  value     = "'.$val.'"
  '.$disabled.'
  >
  </div>
  </div>';

  return $txt;
 }


 function input_text($array,$val,$disabled){

  $txt =
  '<div class="form-group">

  <label  class="col-xs-12 col-sm-4">'.$array[0].': </label>

  <div id="tag'.$array[1].'" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
  <input type="text"
  class="form-control input-xs"
  id="txt'.$array[1].'"
  name="'.$array[1].'"
  style=""
  onBlur    = "'.$array[2].'"
  onkeydown = "'.$array[2].'"
  value     = "'.$val.'"
  '.$disabled.'
  >
  </div>
  </div>';

  return $txt;
 }

 function input_number($array,$val,$disabled){

  $txt =
  '<div class="form-group">

  <label  class="col-xs-12 col-sm-4">'.$array[0].': </label>

  <div id="tag'.$array[1].'" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
  <input type="number"
  class="form-control input-xs"
  id="txt'.$array[1].'"
  name="'.$array[1].'"
  style="text-transform:uppercase;"
  onBlur    = "'.$array[2].'"
  onkeydown = "'.$array[2].'"
  value     = "'.$val.'"
  '.$disabled.'
  >
  </div>
  </div>';

  return $txt;
 }

 function input_multiple_select($array,$sql) {
  $txt='';
  $txt =$txt.'
  <div class="form-group">
  <label  class="col-xs-12 col-sm-4">'.$array[0].': </label>
  <div id="tag'.$array[1].'" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">';


  $txt = $txt.'<select class="form-control input-xs" id="txt'.$array[1].'" name="states[]" multiple="multiple">';
  foreach ($sql as $row ) {
   $txt = $txt.'<option value="'.$row[0].'">'.$row[1].'</option>';
  }

  $txt=$txt.'</select>';

  $txt=$txt.'</div></div>';

  return $txt;
 }

 function input_select($array,$sql,$disabled,$onchange){

  $txt =
  '<div class="form-group">

  <label  class="col-xs-12 col-sm-4">'.$array[0].': </label>

  <div id="tag'.$array[1].'" class="col-xs-12 col-sm-8 col-md-8 col-lg-8">';

  $txt = $txt.'<select class="form-control input-xs" id="txt'.$array[1].'" '.$disabled.' onchange="'.$onchange.'" >';
  foreach ($sql as $row ) {
   $txt = $txt.'<option value="'.$row[0].'">'.$row[1].'</option>';
  }

  $txt=$txt.'</select>';

  $txt=$txt.'</div></div>';

  return $txt;
 }

 

}

?>
