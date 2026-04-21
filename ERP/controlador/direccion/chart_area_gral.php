<?php

/*==========================================
*		MAIN
=============================================*/
$fi   = $_POST['anio1'];
$ff   = $_POST['anio2'];
$txt  = '';

for ($i=$fi; $i <= $ff; $i++) {

 $txt  = $txt.'
 <div class="col-sm-6 col-xs-12">
 <label class="text-center"><h4>'.$i.'</h4></label>
 <div id="chart_area'.$i.'" style="width: 450px; height: 220px;"></div>
 </div>
 ';

}

/* ===========================================
*     ENCODE JSON
// ===========================================*/

$encode = array(
 0=>$txt);
 echo json_encode($encode);


 ?>
