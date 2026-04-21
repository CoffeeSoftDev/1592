<?php


class UI_COMPONENT {

  function _replace($val){
    $data ='';
    $data = str_replace('"', '\'', $val);
    return $data;
  }

  function _replace_with($val,$opc){
    $data ='';
    $data = str_replace('"', $opc, $val);
    return $data;
  }
}



 ?>
