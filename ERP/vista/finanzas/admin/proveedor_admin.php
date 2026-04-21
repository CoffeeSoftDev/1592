<?php

  // //Llammar las clases exteriores
  // include_once('../../../../modelo/SQL_PHP/_Finanzas.php');
  // include_once('../../../../modelo/SQL_PHP/_Perfil.php');
  // //Declarar los objetos de las clases exteriores
  // $fin = new Finanzas;
  // $perfil = new PERFIL;
  // //Obtener por post el id de la empresa
  // $idUDN = $_POST['udn'];
  //
  // $udn = $perfil->Select_Name_UDN($idUDN);//Obtener el nombre de la empresa
  // $now = $fin->NOW();//Obtener la fecha de hoy
  // $a = date("Y", strtotime("$now"));
  // $m = date("m", strtotime("$now"));
  // $d = date("d", strtotime("$now"));
  // $inicio = $a.'-'.$m.'-1';
?>

<div id="Res"></div>
<br>
<div class="row">


  <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12 col-xs-12">
      <label>Saldo Inicial</label>
      <div class="input-group">
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label> </span>
        <input type="text" class="select_input form-control input-sm" value="$ 0.00" id="SI_local" disabled/>
      </div>
    </div>
  </div>

  <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12 col-xs-12">
      <label>Gastos</label>
      <div class="input-group">
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label> </span>
        <input type="text" class="select_input form-control input-sm" value="$ 0.00" id="Gastos_local" disabled/>
      </div>
    </div>
  </div>

  <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12 col-xs-12">
      <label>Pagos</label>
      <div class="input-group">
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label> </span>
        <input type="text" class="select_input form-control input-sm" value="$ 0.00" id="Pagos_local" disabled/>
      </div>
    </div>
  </div>

  <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12 col-xs-12">
      <label>Saldo Final</label>
      <div class="input-group">
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label> </span>
        <input type="text" class="select_input form-control input-sm" value="$ 0.00" id="SF_local" disabled/>
      </div>
    </div>
  </div>


  <!-- <div class="form-group col-sm-3 col-xs-12 col-sm-offset-2">
    <div class="col-sm-12">
      <label> </label>
      <div class="input-group date calendariopicker">
        <input type="text" class="select_input form-control input-sm"  id="date1" />
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
      </div>
    </div>
  </div> -->

  <!-- <div class="form-group col-sm-1 col-xs-12" >
    <div class="col-sm-12 col-xs-12">
      <label> </label>
    </div>
    <div id="Btn_Date">
      <input type="hidden" value="0" id="Date_Btn">
      <button type="button" class="btn btn-sm btn-info" style="background:none;" onClick="Btn_Date();"><span class="icon-toggle-off" style="color:#217DBB;"></span></button>
    </div>
  </div> -->

  <!-- <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12">
      <label> </label>
      <div class="input-group date calendariopicker">
        <input type="text" class="select_input form-control input-sm" value="<?php echo $now; ?>" id="date2" />
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
      </div>
    </div>
  </div> -->

  <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12 col-xs-12">
      <label> </label>
      <button class="btn btn-info col-sm-12 btn-sm" onClick="data_proveedor();"> <span class="icon-spin5 animate-spin"></span> Actualizar Tabla</button>
    </div>
  </div>
  <hr>
</div>

<br>
<div id="data_proveedor"></div>
