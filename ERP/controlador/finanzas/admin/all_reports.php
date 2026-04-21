<?php
session_start();
include_once("../../../modelo/SQL_PHP/_AllReports.php");
$obj = new REPORTES;
$idE = 1;
$opc = $_POST['opc'];

switch ($opc) {
  case 0://Categorias
      $date1 = $_POST['date1'];
      $date2 = $_POST['date2'];


      $sql = $obj->Select_Categorias($idE,$date1,$date2);
      foreach ($sql as $value) {
        $idCategoria = $value[0];
        echo '
        <table style="padding-top:300px; font-size:.7em;" id="size1" class="table table-reponsive table-condensed table-bordered">
          <thead>
            <tr>
              <th colspan="12" class="text-center">'.$value[1].'</th>
            </tr>
            <tr>
              <th style="color:#fff; background:#0F243E;">SUBCATEGORIA</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">PAX</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">NOCHE</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">TARIFA</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">EFECTIVO</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">TC</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">CXC</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">ANTICIPO</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">SUBTOTAL</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">IVA</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">HOSPEDAJE</th>
              <th style="color:#fff; background:#0F243E;" class="col-sm-1 col-xs-1 text-center">TOTAL</th>
            </tr>
          </thead>
          <tbody>';
          $Suma_Pax = 0; $Suma_Noche = 0; $Suma_Tarifa = 0;
          $Suma_Efectivo = 0; $Suma_TC = 0; $Suma_CxC = 0;
          $Suma_Anticipo = 0; $Suma_Subtotal = 0; $Suma_IVA = 0;
          $Suma_Hospedaje = 0; $Suma_Total = 0;
          $sql = $obj->Select_SubCategorias_x_Categoria($idCategoria,$date1,$date2);
          foreach ($sql as $data) {
            $idSubcategoria = $data[0];
            $Subcategoria = $data[1];
            $bitacora_venta = $obj->Select_bitacora_ventas($idSubcategoria,$date1,$date2);
            foreach ($bitacora_venta as $row);
            $Subtotal = $row[0];
            $Pax = $row[1];
            $Noche = $row[2];
            $Tarifa = $row[3];

            $Efectivo = $obj->Select_bitacora_formaspago($idSubcategoria,$date1,$date2,1);
            $TC = $obj->Select_bitacora_formaspago($idSubcategoria,$date1,$date2,2);
            $CxC = $obj->Select_bitacora_formaspago($idSubcategoria,$date1,$date2,3);
            $Anticipo = $obj->Select_bitacora_formaspago($idSubcategoria,$date1,$date2,4);

            $IVA = $obj->Select_bitacora_impuestos($idSubcategoria,$date1,$date2,1);
            $Hospedaje = $obj->Select_bitacora_impuestos($idSubcategoria,$date1,$date2,2);
            $Total = $Subtotal+$IVA+$Hospedaje;
            echo '
            <tr>
              <th ><span class="text-left">'.$Subcategoria.'</span></th>
              <td class="text-right" style="background:#D9EDF7;">'.$Pax.'</td>
              <td class="text-right" style="background:#D9EDF7;">'.$Noche.'</td>
              <td class="text-right" style="background:#D9EDF7;">$ '.number_format($Tarifa,2,'.',',').'</td>
              <td class="text-right" style="background:#FFFF00;">$ '.number_format($Efectivo,2,'.',',').'</td>
              <td class="text-right" style="background:#FFFF00;">$ '.number_format($TC,2,'.',',').'</td>
              <td class="text-right" style="background:#FFFF00;">$ '.number_format($CxC,2,'.',',').'</td>
              <td class="text-right" style="background:#FFFF00;">$ '.number_format($Anticipo,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($Subtotal,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($IVA,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($Hospedaje,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($Total,2,'.',',').'</td>
            </tr>
            ';
            $Suma_Pax = $Suma_Pax + $Pax; $Suma_Noche = $Suma_Noche + $Noche; $Suma_Tarifa = $Suma_Tarifa + $Tarifa;
            $Suma_Efectivo = $Suma_Efectivo + $Efectivo; $Suma_TC = $Suma_TC + $TC; $Suma_CxC = $Suma_CxC + $CxC;
            $Suma_Anticipo = $Suma_Anticipo + $Anticipo; $Suma_Subtotal = $Suma_Subtotal + $Subtotal; $Suma_IVA = $Suma_IVA + $IVA;
            $Suma_Hospedaje = $Suma_Hospedaje + $Hospedaje; $Suma_Total = $Suma_Total + $Total;
          }
          echo
          '<tr class="bg-default">
              <th ><span class="text-left">Total</span></th>
              <td class="text-right" >'.$Suma_Pax.'</td>
              <td class="text-right" >'.$Suma_Noche.'</td>
              <td class="text-right" >$ '.number_format($Suma_Tarifa,2,'.',',').'</td>
              <td class="text-right" >$ '.number_format($Suma_Efectivo,2,'.',',').'</td>
              <td class="text-right" >$ '.number_format($Suma_TC,2,'.',', ').'</td>
              <td class="text-right" >$ '.number_format($Suma_CxC,2,'.',',').'</td>
              <td class="text-right" >$ '.number_format($Suma_Anticipo,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($Suma_Subtotal,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($Suma_IVA,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($Suma_Hospedaje,2,'.',',').'</td>
              <td class="text-right">$ '.number_format($Suma_Total,2,'.',',').'</td>
           </tr>
          </tbody>
        </table>
        ';
      }
    break;
  case 1:
      $date = $_POST['date'];
      $Categoria = array();
      $Cantidad_Subcategoria = array();
      $Subcategoria = array();
      $sql = $obj->Select_Categorias($idE,$date,$date);
      foreach ($sql as $key => $value) {
        $idCategoria[$key] = $value[0];
        $Categoria[$key] = $value[1];
        // $Cantidad_Subcategoria[$key] = $obj->Select_Count_Subcategoría($idCategoria,$date,$date);
      }

      $cont_Categoria = count($Categoria);
      $one = array(
        $cont_Categoria
      );
      $respuesta = array_merge($Categoria,$Cantidad_Subcategoria);
      echo json_encode($respuesta);
    break;
}
?>
