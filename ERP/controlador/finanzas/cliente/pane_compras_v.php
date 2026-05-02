<?php
include_once('../../../modelo/SQL_PHP/_Finanzas_Compras.php');
include_once('../../../modelo/SQL_PHP/_Utileria.php');
$fin   = new Compras_Fin;
$util  = new Util;
session_start();

$opc   = $_POST['opc'];
$idE   = $_SESSION['udn'];


switch ($opc) {
 case 0://GASTOS
 ?>

 <br>

 <div class="row">
  <div class="form-group col-sm-12 col-xs-12">
   <h3 class="text-center">
    <strong><span class="icon-basket"></span>GASTOS </strong>
   </h3>
  </div>
 </div>

 <div class="row">
  <div class="col-sm-12 co-xs-12">
   <div class="form-group col-sm-4 col-xs-12">
    <label>Proveedor</label>
    <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="Proveedor" id="Proveedor">
   </div><!-- ./Proveedor-->

   <div class="form-group col-sm-4 col-xs-12">
    <label>Concepto</label>
    <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="Concepto" id="Insumo">
   </div><!-- ./Concepto -->

   <div class="form-group col-sm-4 col-xs-12">
    <label>Monto</label>
    <div class="input-group">
     <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
     <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="0.00" id="Gastos">
    </div>
   </div><!-- ./Monto -->

   <div class="form-group col-sm-4 col-xs-12 Categoria"></div><!-- ./Categoría-->

   <div class="form-group col-sm-4 col-xs-12 Pagador"></div><!-- ./Pagador-->

   <div class="form-group col-sm-4 col-xs-12">
    <label>Observaciones</label>
    <textarea class="form-control input-sm" id="Observaciones"></textarea>
   </div><!-- ./Pagador-->
  </div><!-- ./Div Contenedor -->
 </div><!--./Row-->

 <div class="row">
  <div class="col-sm-12 col-xs-12">
   <div class="text-center" id="Respuesta_Gastos"></div>
   <div class="form-group col-sm-12 col-xs-12">
    <button type="button" class="col-sm-4 col-sm-offset-4 col-xs-10 col-xs-offset-1 btn btn-primary" onclick="nuevos_gastos();">Guardar Gasto</button>
   </div>
  </div>
 </div>
 <div class="row">
  <div class="col-sm-12 col-xs-12" id="table_data"></div>
 </div>

 <script src="recursos/js/finanzas/cliente/gastos.js?t=<?=time()?>"></script>
 <?php
 break;
 case 1:
 $caso = $_POST['caso'];
 $sql = $fin->Select_Pagadores($caso);
 $select = '<label class="control-label" for="Clase_Gasto">Pagador</label>
 <select class="form-control input-sm col-sm-12 col-xs-12" id="Clase_Gasto">
 <option value="0">Seleccionar Pagador...</option>';
 foreach ($sql as $key => $value) {
  $select = $select.'<option value="'.$value[0].'">'.$value[1].'</option>';
 }
 $select = $select.'</select>';

 echo $select;
 break;
 case 2:
 $array = array($idE);
 $sql = $fin->Select_Categoria($array);
 $select = '<label  class="control-label" for="Clase_Insumo">Destino</label>
 <select class="form-control input-sm col-sm-12 col-xs-12" id="Clase_Insumo">
 <option value="0">Seleccionar Destino ...</option>';
 foreach ($sql as $key => $value) {
  $select = $select.'<option value="'.$value[0].'">'.$value[1].'</option>';
 }
 $select = $select.'</select>';

 echo $select;
 break;

 /*-----------------------------------*/
 /*        GASTOS - COMPRAS
 /*-----------------------------------*/

 case 3:


 $txt      = '';

 $txt      = $txt.'


 <br>
 <div class="row">
 <div class="col-sm-12 co-xs-12">

 <div class="form-group col-sm-4 col-xs-12">
 <h3 class="text-center">
 <strong><span class="icon-cart"></span> COMPRAS</strong>
 </h3>

 </div><!-- ./Titulo-->


 <div class="form-group col-sm-2 col-xs-12">
 <label># Pedido</label>
 <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="Folio de requisicion" id="Pedido">
 </div><!-- ./Pedido-->

 <div class="form-group col-sm-2 col-xs-12">
 <label># Factura </label>
 <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="Numero de Factura" id="Factura">
 </div><!-- ./Factura-->


 <div class="form-group col-sm-4 col-xs-12">
 <label># Fecha Factura </label>
 <div class="input-group date " id="datetimepicker1">
 <input type="text" class="select_input form-control input-sm" value=""
 id="dateFacture">
 <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
 </div>





 </div><!-- ./Fecha Factura-->

 </div>
 </div>

 <div class="row">
 <div class="col-sm-12 co-xs-12">
 <div class="form-group col-sm-4 col-xs-12">
 <label>Proveedor</label>
 <input type="text" class="form-control input-sm col-sm-12 col-xs-12" placeholder="Proveedor" id="Proveedor">
 </div><!-- ./Proveedor-->

 <div class="form-group col-sm-4 col-xs-12">
 <label>Concepto</label>
 <input type="text" class="form-control input-sm col-sm-12 col-xs-12"
 placeholder="Concepto" id="Insumo">
 </div><!-- ./Concepto -->

 <div class="form-group col-sm-2 col-xs-12">
 <label>SubTotal</label>

 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text"
 class="form-control input-sm col-sm-12 col-xs-12"
 placeholder="0.00" id="Gastos">
 </div>

 </div><!-- ./Monto -->

 <div class="form-group col-sm-2 col-xs-12">
 <label>Monto IVA</label>

 <div class="input-group">
 <span class="input-group-addon input-sm"><i class="icon-dollar"></i></span>
 <input type="text"
 class="form-control input-sm col-sm-12 col-xs-12"
 placeholder="0.00" id="GastosIVA">
 </div>

 </div><!-- ./Monto IVA-->



 <div class="form-group col-sm-4 col-xs-12 Categoria"></div><!-- ./Categoría-->

 <div class="form-group col-sm-4 col-xs-12 Pagador"></div><!-- ./Pagador-->

 <div class="form-group col-sm-4 col-xs-12">
 <label>Observaciones</label>
 <textarea class="form-control input-sm" id="Observaciones"></textarea>
 </div><!-- ./Pagador-->
 </div><!-- ./Div Contenedor -->
 </div><!--./Row-->


 <div class="row">
 <div class="col-sm-12 col-xs-12">
 <div class="text-center" id="Respuesta_Gastos"></div>
 <div class="form-group col-sm-12 col-xs-12">
 <button type="button" class="col-sm-4 col-sm-offset-4 col-xs-10 col-xs-offset-1 btn btn-primary"
 onclick="addCompras();"> Guardar Compra </button>
 </div>
 </div>
 </div>

 <div class="row">
 <div class="col-sm-12 col-xs-12" id="tabla_compras"></div>
 </div>

 <script src="recursos/js/finanzas/cliente/gastos.js?t=<?=time()?>"></script>
 <script type="text/javascript">



 $(document).ready(function(){

  $(function () {

   $("#datetimepicker1").datetimepicker({
    format: "YYYY-MM-DD",
    useCurrent: false,
    defaultDate: new Date(),
    widgetPositioning: {
     horizontal: "right",
     vertical: "bottom"
    },
   });

  });
 });
 </script>
 ';

 /*-----------JSON ENCODE----------------*/
 $encode = array( 0=>$txt);
 echo json_encode($encode);

 break;


}
?>
