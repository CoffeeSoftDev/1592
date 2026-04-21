<div class="modal-header">
    <button type="button" class="close form-horizontal" data-dismiss="modal">&times;</button>
    <h3 class="modal-title text-center"><strong>Agregar Proveedor</strong></h3>
</div>
<div class="modal-body">

  <div class="row">
    <div class="form-group col-sm-12 col-xs-12">
      <label class="col-sm-3 col-xs-12">Nombre</label>
      <div class="col-sm-9 col-xs-12">
        <input type="text" class="form-control input-sm" placeholder="Nombre del proveedor" id="Name_Proveedor">
        <div id="Res_Prov"></div>
      </div>
    </div>
  </div>

  <div class="text-center" id="Res"></div>

  <div class="row">
    <br>
    <div class="form-group col-sm-6 col-xs-12">
        <button type="button" class="btn btn-danger btn-sm col-sm-12" data-dismiss="modal">Cancelar</button>
    </div>

    <div class="form-group col-sm-6 col-xs-12">
        <button type="button" class="btn btn-primary btn-sm col-sm-12" onclick="Insert_Proveedor();">Agregar</button>
    </div>
  </div>
</div>
