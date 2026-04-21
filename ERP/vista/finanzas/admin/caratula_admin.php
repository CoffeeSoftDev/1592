<?php
//Llammar las clases exteriores

include_once('../../../modelo/SQL_PHP/_Finanzas.php');
include_once('../../../modelo/SQL_PHP/_Perfil.php');
// include_once("../../../modelo/SQL_PHP/_Utileria.php");


//Declarar los objetos de las clases exteriores
$fin = new Finanzas;
$perfil = new PERFIL;

/*

$util = new Util;
*/
//Obtener por post el id de la empresa
$idE = $_POST['udn'];
//
$udn = $perfil->Select_Name_UDN($idE);//Obtener el nombre de la empresa
$now = $fin->NOW();//Obtener la fecha de hoy

// $img = $util->Logo_Empresa($idE); // Obtener el logo de la empresa
// $sql = $fin->Select_Data_Moneda();//Obtener el iformación de las monedas


?>
  <div id="Res" class="text-center"></div>

  <input type="hidden" id="Logo_IMG" value="<?php /*echo $img;*/ ?>">
  <input type="hidden" id="Folio">
  <input type="hidden" value="1" id="Date_Btn">
  <div class="row">
    <div class="row">

      <div class="form-group col-sm-3 col-xs-12 col-sm-offset-2">
        <div class="col-sm-12">
          <div class="input-group date calendariopicker">
            <input type="text" class="select_input form-control input-sm" value="<?php echo $now; ?>" id="date1" />
            <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
          </div>
        </div>
      </div>


      <div class="form-group col-sm-1 col-xs-12" id="Btn_Date">
        <button type="button" class="btn btn-sm btn-info" style="background:none;"
        onClick="Btn_Date();"><span class="icon-toggle-on" style="color:#217DBB;"></span></button>
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
        <!-- <div class="col-sm-12 col-xs-12">
          <button type="button" class="btn btn-info btn-sm col-sm-12 col-xs-12" onClick="Llenar_Caratula();" id="Act">Actualizar Caratula</button>
        </div> -->
      </div>
    </div>
  </div>


  <div class="row">
    <div class="tb_caratula"></div>
  </div>

  <div id="detalle_gastos"></div>
  <div id="detalle_proveedor"></div>
  <div id="detalle_anticipos"></div>
  <div id="detalle_bancos"></div>
  <div id="detalle_almacen"></div>

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
