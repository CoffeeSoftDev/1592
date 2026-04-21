<?php
  include_once('../../../SQL_PHP/_Perfil.php');
  $per = new PERFIL;
  $sql = $per->Select_UDN();
?>
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong><?php echo $_GET['insumo'];?>  <span class="icon-pencil"></span>  </strong></h3>
    <input type="hidden" id="insumo-oculto" value="<?php echo $_GET['insumo'];?>">
</div>
<div class="modal-body">
  <div id="Res"></div>
  <div id="tabla_modal"></div>
</div>
