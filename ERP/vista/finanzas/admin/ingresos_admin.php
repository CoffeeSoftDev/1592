<?php
  //Llammar las clases exteriores
  include_once('../../../modelo/SQL_PHP/_Finanzas.php');
  include_once('../../../modelo/SQL_PHP/_Perfil.php');
  //Declarar los objetos de las clases exteriores
  $fin = new Finanzas;
  $perfil = new PERFIL;
  //Obtener por post el id de la empresa
  $idUDN = $_POST['udn'];

  $udn = $perfil->Select_Name_UDN($idUDN);//Obtener el nombre de la empresa
  $now = $fin->NOW();//Obtener la fecha de hoy
  $a = date("Y", strtotime("$now"));
  $m = date("m", strtotime("$now"));
  $d = date("d", strtotime("$now"));
  $inicio = $a.'-'.$m.'-1';
?>

<div id="Res"></div>
<div class="row">
  <hr>
  <div class="form-group col-sm-12 col-xs-12 text-center">
    <label class="col-xs-12 col-sm-12" style="border:2px solid #DCE4EC; border-radius:5px; padding:10px;"><span class="icon-dollar-1"></span>INGRESOS  DE <?php echo $udn; ?></label>
  </div>
</div>

<div class="row">
  <div class="form-group col-sm-3 col-xs-12 col-sm-offset-2">
    <div class="col-sm-12">
      <div class="input-group date calendariopicker">
        <input type="text" class="select_input form-control input-sm" value="<?php echo $inicio; ?>" id="date1" />
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
      </div>
    </div>
  </div>

  <input type="hidden" value="0" id="Date_Btn">
  <div class="form-group col-sm-1 col-xs-12" id="Btn_Date">
    <button type="button" class="btn btn-sm btn-info" style="background:none;" onClick="Btn_Date();"><span class="icon-toggle-off" style="color:#217DBB;"></span></button>
  </div>

  <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12">
      <div class="input-group date calendariopicker">
        <input type="text" class="select_input form-control input-sm" value="<?php echo $now; ?>" id="date2" />
        <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
      </div>
    </div>
  </div>

  <div class="form-group col-sm-3 col-xs-12">
    <div class="col-sm-12">
      <button class="btn btn-info col-sm-12 col-xs-12 btn-sm" onClick="ver_tabla_ingresos(1);"> <span class="icon-spin5 animate-spin"></span> Actualizar Tabla</button>
    </div>
  </div>
</div>
<br>

<!--S O L I C I T U D E S    D E   M A N T E N I M I E N T O-->
<script type="text/javascript">
  $(document).ready(function(){
    $(function () {
      $(".calendariopicker").datetimepicker({
        format: "YYYY-MM-DD",
        useCurrent: false,
        // minDate: moment().add(-1, "d").toDate(-40, "d"),
        widgetPositioning: {
          horizontal: "right",
          vertical: "bottom"
        },
      });
    });

    $(".calendariopicker").keypress(function (evt) {  return false; });
  });

  $(".calendariopicker").on("dp.change", function (e) { Date_Lock(); });
</script>
