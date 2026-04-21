<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong>Agregar Crédito</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-3 col-xs-12">Nombre</label>
      <div class="col-sm-9 col-xs-12">
        <input type="text" class="form-control input-sm" placeholder="Nombre del cliente" id="Name_Credito">
        <div id="Res_Cre"></div>
      </div>
    </div>
  </div>

  <div class="text-center" id="Res"></div>

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <div class="col-sm-12 col-xs-12">
        <button type="button" class="btn btn-danger btn-sm col-sm-5 col-xs-5" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary btn-sm col-sm-5 col-xs-5 col-sm-offset-2 col-xs-offset-2" onclick="Insert_Credito();">Agregar</button>
      </div>
    </div>

  </div>
</div>
