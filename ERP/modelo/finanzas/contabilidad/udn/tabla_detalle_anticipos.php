<?php
  session_start();

  include_once("../../../SQL_PHP/_CRUD.php");
  include_once("../../../SQL_PHP/_Finanzas.php");
  include_once("../../../SQL_PHP/_Utileria.php");

  //Se declaran los utiletos para utilizar las funciones segun los archivos externos
  $crud = new CRUD;
  $util = new Util;
  $fin = new Finanzas;

  $date1 = $_POST['date1'];
  $date2 = $_POST['date2'];
  $idE = $_POST['udn'];

  $row = null; $array = array();
  if($date1 == $date2) {
    $array = array($idE,$date1);
    $query = "SELECT Empleado_Anticipo,Nombres,ROUND(SUM(Saldo),2) AS SALDO FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') = ? GROUP BY Empleado_Anticipo ORDER BY SALDO DESC";
  }
  else {
    $array = array($idE,$date1,$date2);
    $query = "SELECT Empleado_Anticipo,Nombres,ROUND(SUM(Saldo),2) AS SALDO FROM anticipos,empleados WHERE idEmpleado = Empleado_Anticipo AND UDN_Empleado = ? AND DATE_FORMAT(Fecha_Anticipo,'%Y-%m-%d') BETWEEN ? AND ? GROUP BY Empleado_Anticipo ORDER BY SALDO DESC";
  }

  $sql = $crud->_Select($query,$array,"6");
  $Nombres = array(); $idN = array(); $Cantidad = array();
  foreach ($sql as $key => $data_name) { $idN[$key] = $data_name[0]; $Nombres[$key] = $data_name[1]; $Cantidad[$key] = $data_name[2]; }
  $Count_Name = count($Nombres);
?>

<div  id="table-conf">
  <div class="table table-responsive" >
    <!--CONTENIDO-->
    <table class="table table-striped table-hover table-bordered table-condensed">
      <thead>
        <tr class="text-center " id="titulo">
            <td colspan = "4"><strong>DETALLES DE COLABORADORES</strong></td>
        </tr>
      </thead>
      <tbody>
        <?php for ($i=0; $i < $Count_Name; $i++) {

        ?>
          <tr>
            <td class = "col-sm-3"><?php echo $Nombres[$i]; ?></td>
            <td class = "col-sm-3"></td>
            <td class = "col-sm-3 text-right">$ <?php echo number_format($Cantidad[$i],2,'.',', ');?></td>
            <td class = "col-sm-3"></td>
          </tr>
        <?php }?>
      </tbody>
    </table>
  </div>
</div>
