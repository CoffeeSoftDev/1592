<?php
session_start();
include_once("../../../modelo/SQL_PHP/_MTTO.php");
include_once("../../../modelo/SQL_PHP/_ALMACEN.php");
$obj = new MTTO;
// --

$id         = $_POST['id'];
$opc        = $_POST['opc'];
$baja       = 1;
$enable     = 'disabled';
$txt        = '';
/*===========================================
*									MAIN
=============================================*/

switch ($opc) {

 case 0:  // Formulario para dar de baja

 $arrayData  = array($id);
 $data       = $obj->Show_DATA_SINGLE($arrayData);

 if($data[9]==3){
  $enable = 'enabled';  $baja       = $data[5];
 } //habilita edicion & cantidad

 $txt=$txt.'
 <div class="form-horizontal">
 <div  id="Resultado_baja">
 <div class="form-group">
 <label class="col-sm-4">Equipo:</label>
 <div class="col-sm-8">
 <input type="text" class="form-control input-xs" disabled value="'.$data[1].'">
 </div>
 </div>

 <div class="form-group">
 <label class="col-sm-4">Categoria:</label>
 <div class="col-sm-8">
 <input type="text" class="form-control input-xs" disabled value="'.$data[7].'">
 </div>
 </div>


 <div class="form-group">
 <label class="col-sm-4">Cantidad actual:</label>
 <div class="col-sm-8">
 <input type="number" class="form-control input-xs" id="txtCantidad1" disabled value="'.$data[5].'">
 <div class=" bg-default" id="Res_Cantidad1"></div>
 </div>
 </div>

 <div class="form-group">
 <label class="col-sm-4">Cantidad por dar de baja:</label>
 <div class="col-sm-8">
 <input type="number" class="form-control input-xs" id="txtCantidad2" value="'.$baja.'" '.$enable.' min="1" max="'.$data[5].'">
 <div class=" bg-default" id="Res_Cantidad2"></div>
 </div>
 </div>

 <hr>


 <div class="form-group">
 <label class="col-sm-4">Contraseña:</label>
 <div class="col-sm-8">
 <input type="password" placeholder="Debes indicar tu contraseña para autorizar la baja" class="form-control input-xs" id="pass" autocomplete="off">
 <div class=" bg-default" id="Res_Pass"></div>
 </div>
 </div>



 <div class="form-group">
 <label  class="col-sm-4">Motivo:</label>
 <div class="col-sm-8">
 <textarea style="resize:none;"rows="4" class="form-control" id="motivo" ></textarea>
 <div class="bg-default" id="Res_Motivo"></div>
 </div>
 </div>

 </div>


 <div class="form-group">
 <div class="col-sm-12 " id="btnZone">
 <button type="button" class="btn btn-primary col-sm-12 col-sm-3 col-sm-offset-5" onclick="Alta_Baja('.$opc.','.$id.','.$data[9].')">Guardar</button>

 ';

 break;

 case 1:
 $arrayData  = array($id);
 $data       = $obj->Show_DATA_UP($arrayData);
 $res        = 1;
 if($data[5]==3){
  $enable = 'enabled';  $baja       = $data[2];
  $res =$data[1]-$data[2];
 } //habilita edicion & cantidad
 $txt=$txt.'
 <div class="form-horizontal">
 <div  id="Resultado_baja">
 <div class="form-group">
 <label class="col-sm-4">Equipo:</label>
 <div class="col-sm-8">
 <input type="text" class="form-control input-xs" disabled value="'.$data[4].'">
 </div>
 </div>

 <div class="form-group">
 <label class="col-sm-4">Categoria:</label>
 <div class="col-sm-8">
 <input type="text" class="form-control input-xs" disabled value="'.$data[0].'">
 </div>
 </div>


 <div class="form-group">
 <label class="col-sm-4">Stock Anterior:</label>
 <div class="col-sm-8">
 <input type="number" class="form-control input-xs"  disabled value="'.$data[1].'">
 <div class=" bg-default" id="Res_Cantidad1"></div>
 </div>
 </div>

 <div class="form-group">
 <label class="col-sm-4">Productos dados de baja:</label>
 <div class="col-sm-8">
 <input type="number" class="form-control input-xs"  disabled value="'.$data[2].'">
 <div class=" bg-default" id="Res_Cantidad1"></div>
 </div>
 </div>

 <div class="form-group">
 <label class="col-sm-4">Stock Actual:</label>
 <div class="col-sm-8">
 <input type="number" class="form-control input-xs" id="txtCantidad1" disabled value="'.$res.'">
 <div class=" bg-default" id="Res_Cantidad1"></div>
 </div>
 </div>


 <div class="form-group">
 <label class="col-sm-4">Dar de alta:</label>
 <div class="col-sm-8">
 <input type="number" class="form-control input-xs" id="txtCantidad2" value="'.$baja.'" '.$enable.' min="1" max="'.$data[2].'">
 <div class=" bg-default" id="Res_Cantidad2"></div>
 </div>
 </div>

 <hr>


 <div class="form-group">
 <label class="col-sm-4">Contraseña:</label>
 <div class="col-sm-8">
 <input type="password" placeholder="Debes indicar tu contraseña para autorizar la baja" class="form-control input-xs" id="pass" autocomplete="off">
 <div class=" bg-default" id="Res_Pass"></div>
 </div>
 </div>



 <div class="form-group">
 <label  class="col-sm-4">Motivo:</label>
 <div class="col-sm-8">
 <textarea style="resize:none;"rows="4" class="form-control" id="motivo" ></textarea>
 <div class="bg-default" id="Res_Motivo"></div>
 </div>
 </div>

 </div>


 <div class="form-group">
 <div class="col-sm-12 " id="btnZone">
 <button type="button" class="btn btn-primary col-sm-12 col-sm-3 col-sm-offset-5" onclick="Alta_Baja('.$opc.','.$id.','.$data[5].')">Guardar</button>

 ';

 break;

 case 3:
 $baja =BajaProductos($opc,$id);
 $txt=$txt.$baja;
 break;


}

if ($opc==0) {
 $txt=$txt.'<button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-1" data-dismiss="modal" > Salir </button>';
}elseif ($opc==1) {
 $txt=$txt.'<button type="button" class="btn btn-danger col-xs-12 col-sm-3 col-sm-offset-1" data-dismiss="modal" > Salir </button>';
}

$txt=$txt.'</div></div></div>';

// ===========================================
//     ENCODE JSON
// ===========================================
$encode = array(
 0=>$txt);

 echo json_encode($encode);

 /*===========================================
 *									Funciones
 =============================================*/

 function BajaProductos($opc,$id){
  $almacen      = new ALMACEN;
  $data         = $almacen->verProductos($id);
  foreach   ($data as $dataset);


  $txt ='
  <div class="form-horizontal">

  <div  id="Resultado_baja">

  <div class="alert alert-success" role="alert">
  * Solo se pueden eliminar productos que han sigo agregados recientemente y no cuentan con ningun movimiento.
  </div>

  <div class="form-group">
  <label class="col-sm-4"> Zona :</label>
  <div class="col-sm-8">
  <input type="text" class="form-control input-xs"
  value="'.$dataset[0].'"
  disabled >
  </div>
  </div><!-- ./form-group -->


  <div class="form-group">
  <label class="col-sm-4"> Producto :</label>
  <div class="col-sm-8">
  <input type="text" class="form-control input-xs"
  value="'.$dataset[2].'"
  disabled >
  </div>
  </div><!-- ./form-group -->

  <div class="form-group">
  <label class="col-sm-4">Contraseña:</label>
  <div class="col-sm-8">
  <input type="password" placeholder="Debes indicar tu contraseña para autorizar la baja" class="form-control input-xs" id="pass" autocomplete="off">
  <div class=" bg-default" id="Res_Pass"></div>
  </div>
  </div><!-- ./form-group -->

  <div class="form-group">
  <label  class="col-sm-4">Motivo:</label>
  <div class="col-sm-8">
  <textarea style="resize:none;"rows="4" class="form-control" id="motivo" ></textarea>
  <div class=" bg-default" id="Res_Motivo"></div>
  </div>
  </div><!-- ./form-group -->

  </div>
  <div class="form-group">
  <div class="col-sm-12 " id="btnZone">
  <button type="button" class="btn btn-primary col-sm-12 col-sm-3 col-sm-offset-5" onclick="Alta_Baja('.$opc.','.$id.')">Guardar</button>

  ';

  return $txt ;
 }

 ?>
