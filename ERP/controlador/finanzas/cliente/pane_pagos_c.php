<?php
  session_start();
  include_once('../../../modelo/SQL_PHP/_Finanzas_Compras.php');
  include_once('../../../modelo/SQL_PHP/_Utileria.php');
  $fin = new Compras_Fin;
  $util = new Util;
  $idE = $_SESSION['udn'];
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://GUARDAR MOVIMIENTOS DE PAGOS
      $Proveedor = strtr(strtoupper($_POST['Proveedor']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $Insumo = strtr(strtoupper($_POST['Insumo']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
      $Clase_Insumo = $_POST['Clase_Insumo'];
      $Pago = $_POST['Pago'];
      $Clase_Pago = $_POST['Clase_Gasto'];
      $Observaciones = $_POST['Observaciones'];
      $date = $_POST['date'];

      if ($Proveedor != "") {
         //Obtener el idproveedor
         $idP = $fin->Select_idProveedores($Proveedor);
         if($idP == null){
           $fin->Insert_Proveedor($Proveedor);//Insertar nuevo proveedor
           $idP = $fin->Select_idProveedores($Proveedor);//Consultar de nuevo el idProveedor
         }
         //crear arreglo para buscar relacion empresa
         $array = array($idP,$idE);
         $idUP = $fin->Select_Empresa_Proveedor($array);//Buscar el id de relación
         if($idUP == null){
           $fin->Insert_Empresa_Proveedor($array);//insertar nueva relación
           $idUP = $fin->Select_Empresa_Proveedor($array);//Consultar de nuevo el id relacion
         }
      //
         $array = array($idE,$idUP,$Clase_Pago,2,$Pago,$Observaciones,$date);
         $query = "INSERT INTO hgpqgijw_finanzas.compras (id_UDN,id_UP,id_CG,Status,Pago,Observacion,Fecha_Compras) VALUES (?,?,?,?,?,?,?)";
         $fin->Insert_Bitacora_Compras($query,$array);
      }
      if($Insumo != ""){
        //Obtener el idGasto
        $idG = $fin->Select_idGasto($Insumo);
        if($idG == null){
          $fin->Insert_Gasto($Insumo);
          $idG = $fin->Select_idGasto($Insumo);
        }
        //Crear arreglo para buscar relacion
        $array =  array($idG,$idE);
        $idUG = $fin->Select_Empresa_Gasto($array);
        if($idUG == null){
          $fin->Insert_Empresa_Gasto($array);
          $idUG = $fin->Select_Empresa_Gasto($array);
        }

        //Buscar la relacion clase_insumo empresa
        $array = array($Clase_Insumo,$idE);
        $idUI = $fin->Select_Empresa_ClaseInsumo($array);


        $array = array($idE,$idUG,$idUI,2,2,$Pago,$Observaciones,$date);
        $query = "INSERT INTO hgpqgijw_finanzas.compras (id_UDN,id_UG,id_UI,E_S,Status,Pago,Observacion,Fecha_Compras) VALUES (?,?,?,?,?,?,?,?)";
        $fin->Insert_Bitacora_Compras($query,$array);
      }
      break;
    case 1://MODIFICAR INFORMACIÓN PAGOS
        $tipo = $_POST['tipo'];
        $idCompras = $_POST['id'];
        switch ($tipo) {
          case 1://Proveedor
            $Proveedor = strtr(strtoupper($_POST['valor']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
            //Obtener el idproveedor
            $idP = $fin->Select_idProveedores($Proveedor);
            if($idP == null){
              $fin->Insert_Proveedor($Proveedor);//Insertar nuevo proveedor
              $idP = $fin->Select_idProveedores($Proveedor);//Consultar de nuevo el idProveedor
            }
            //crear arreglo para buscar relacion empresa
            $array = array($idP,$idE);
            $idUP = $fin->Select_Empresa_Proveedor($array);//Buscar el id de relación
            if($idUP == null){
              $fin->Insert_Empresa_Proveedor($array);//insertar nueva relación
              $idUP = $fin->Select_Empresa_Proveedor($array);//Consultar de nuevo el id relacion
            }
            //Actualizar el proveedor en compras
            $array = array($idUP,$idCompras);
            $fin->Update_Proveedor_Pago($array);
            echo $Proveedor;
            break;
          case 2://Insumo
            $Insumo = strtr(strtoupper($_POST['valor']),"àèìòùáéíóúçñäëïöü","ÀÈÌÒÙÁÉÍÓÚÇÑÄËÏÖÜ");
            //Obtener el idGasto
            $idG = $fin->Select_idGasto($Insumo);
            if($idG == null){
              $fin->Insert_Gasto($Insumo);
              $idG = $fin->Select_idGasto($Insumo);
            }
            //Crear arreglo para buscar relacion
            $array =  array($idG,$idE);
            $idUG = $fin->Select_Empresa_Gasto($array);
            if($idUG == null){
              $fin->Insert_Empresa_Gasto($array);
              $idUG = $fin->Select_Empresa_Gasto($array);
            }
            //Actualizar el insumo en compras
            $array = array($idUG,$idCompras);
            $fin->Update_Insumo_Pago($array);
            echo $Insumo;
            break;
          case 4://Cantidad de Pago
            $valor = $_POST['valor'];
            $array = array($valor,$idCompras);
            $fin->Update_Cantidad_Compras($array);
            echo $valor;
            break;
          case 6://Observaciones
            $valor = trim($_POST['valor']);
            $array = array($valor,$idCompras);
            $fin->Update_Observaciones_Compras($array);
            echo $valor;
            break;
        }
      break;
    case 2:
      $op = $_POST['pc'];
      $valor = $_POST['valor'];

      $option = '';
      if($op == 1){//Compras
          $sql = $fin->Select_Group_Insumo($idE);
          foreach ($sql as $row) {
            if($row[1] == $valor){ $sel = 'selected'; }else{ $sel = ''; }
            $option = $option.'<option value='.$row[0].' '.$sel.'>'.$row[1].'</option>';
          }
      }
      else if($op == 2){//Pagos
        $sql = $fin->Select_idAlmacen($idE);
        foreach ($sql as $row) {
          if($row[1] == $valor){ $sel = 'selected'; }else{ $sel = ''; }
          $option = $option.'<option value='.$row[0].' '.$sel.'>'.$row[1].'</option>';
        }
      }
      echo $option;
      break;
  }
?>
