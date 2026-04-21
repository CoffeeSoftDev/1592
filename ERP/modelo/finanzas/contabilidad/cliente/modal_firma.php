
<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong>Identificación por firma cifrada</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-12 col-xs-12">Nombre corto ó Alias</label>
      <div class="col-sm-12 col-xs-12">
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-briefcase"></label></span>
          <input type="text" class="form-control input-sm" placeholder="Nombre corto ó Alias" id="alias" onkeypress="if(event.keyCode == 13){ Firma_Acceso();}";>
        </div>
        <div id="Res_Pass"></div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-12 col-xs-12">Firma cifrada</label>
      <div class="col-sm-12 col-xs-12">
        <div class="input-group">
          <span class="input-group-addon input-sm"><label class="icon-key"></label></span>
          <input type="password" class="form-control input-sm" placeholder="* * * * * * *" id="firma" onkeypress="if(event.keyCode == 13){ Firma_Acceso();}";>
        </div>
        <div id="Res_Pass"></div>
      </div>
    </div>
  </div>

  <div class="text-center" id="Res_Firma"></div>

  <div class="row">
    <br>
    <div class="form-group col-sm-4 col-sm-offset-2 col-xs-12">
        <button type="button" class="btn btn-danger btn-sm col-sm-12 col-xs-12" data-dismiss="modal">Cancelar</button>
    </div>
    <?php

    ?>
    <div class="form-group col-sm-4 col-xs-12">
        <button type="button" class="btn btn-primary btn-sm col-sm-12 col-xs-12" onclick="Firma_Acceso();">Acceder</button>
    </div>
  </div>
</div>
