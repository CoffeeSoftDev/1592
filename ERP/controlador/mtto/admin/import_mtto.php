<?php



if(!empty($_FILES["excel_file"])){ // Si archivo no se encuentra vacio

 $file_array = explode(".", $_FILES["excel_file"]["name"]);

 if($file_array[1] == "xls"){

  session_start();
  include("../../../recursos/plugin/PHPExcel/Classes/PHPExcel.php");
  include_once("../../../modelo/SQL_PHP/_MTTO.php");
  $obj     =  new MTTO;
  sleep(2);

  $area     = $_POST['valArea'];
  $zona     = $_POST['valZona'];

  $opc      = $_POST['opc'];
  $idArea   = $_POST['area'];
  $idZona   = $_POST['zona'];
  $cont     = 0;
  $tbExiste = '';
  $badge    = '';

  $output = '';

  $output .= "

  <div class='table-responsive'>
  <table class='table table-bordered table-striped' id='tbExcel' style='font-size:.8em;'>

  <thead class='thead-light '>
  <tr>
  <th>#</th>
  <th>Ruta</th>
  <th>Nombre</th>
  <th>Categoria</th>
  <th>Zona</th>
  <th>Area</th>

  <th>Marca</th>
  <th>Proveedor</th>
  <th>Cantidad</th>

  <th>Costo</th>
  <th>Detalles</th>
  <th>Estado</th>

  <th>Utilidad</th>
  <th>Codigo</th>
  </tr>
  </thead>
  ";

  $tbExiste =$tbExiste.$output;

  $excelReader = PHPExcel_IOFactory::createReaderForFile($_FILES["excel_file"]["tmp_name"]);
  $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]);
  foreach($object->getWorksheetIterator() as $worksheet)
  {
   $highestRow = $worksheet->getHighestRow();
   for($row=2; $row<=$highestRow; $row++){
    $cont  = $cont+1;

    $A = Reemplazar(trim(  preg_replace('/\s+/', ' ',strtoupper($worksheet->getCell('A'.$row)->getValue() )  ))); //nombre
    $B = strtoupper($worksheet->getCell('B'.$row)->getValue() ); //categoria
    $C = strtoupper($worksheet->getCell('C'.$row)->getValue() ); //marca

    $D = strtoupper($worksheet->getCell('D'.$row)->getValue() ); //Proveedor
    $E = $worksheet->getCell('E'.$row)->getValue(); //cantidad
    $F = $worksheet->getCell('F'.$row)->getValue(); //costo

    $G = Reemplazar(trim(preg_replace('/\s+/', ' ',strtoupper($worksheet->getCell('G'.$row)->getValue())) )); //Detalles
    $H = $worksheet->getCell('H'.$row)->getValue(); //Estado
    $I = $worksheet->getCell('I'.$row)->getValue(); //Utilidad


    $codigo  =  OBTENER_CODIGO($obj,1,$area);
    $ruta   =  'recursos/img/productos/'.$area.'/'.$cont.'.png';


    /*---------  AGREGAR A MTTO  -----------*/

    //COMPROBAR SI EXISTE EL CODIGO DEL EQUIPO
    $array  = array($codigo);
    $cod    = $obj->Select_idCodigo($array);



    if($cod != 0){

    }else {

     $Fecha = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($I));


     if($opc==2){
      INSERTAR_REGISTROS($A,$B,$C,$D,$E,$F,$G,$H,$Fecha,$codigo,$idZona,$idArea,$ruta,$obj);
     }


     $output .= '
     <tr>
     <td>'.$cont.'</td>
     <td><img width="50" src="'.$ruta.'"></td>
     <td class="col-sm-2 text-left">'.$A.'</td>
     <td >'.$B.'</td>
     <td>'.$zona.'</td>
     <td>'.$area.'</td>

     <td>C : MARCA'.$C.'</td>
     <td>D= PROVEEDOR'.$D.'</td>
     <td>'.$E.'</td>

     <td class="text-right">'.$F.'</td>
     <td>'.$G.'</td>
     <td>'.$H.'</td>

     <td>'.$Fecha.'</td>
     <td class="col-sm-1"><label class="text-primary"><strong>'.$codigo.'</strong></label> </td>

     </tr>
     ';
    }



    /*---------  IMPRIMIR TABLA  -----------*/


   }
  }
  $output .= '</table></div>';

  if($opc==2){
   $badge = "<div class='alert  alert-success alert-dismissible  show' role='alert'>
   <span class='badge badge-pill badge-success'></span>
   <span class=' icon-ok-circled'></span> se han agregado ".$cont." productos con exito.
   <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
   <span aria-hidden='true'>×</span>
   </button>
   </div>";
  }else {
   $badge = "<div class='alert  alert-info alert-dismissible  show' role='alert'>
   <span class='badge badge-pill badge-success'></span>
   <span class=' icon-ok-circled'></span> se ha cargado una vista previa de los productos, total ".$cont." productos.
   <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
   <span aria-hidden='true'>×</span>
   </button>
   </div>";
  }

  //   echo $output;
  $encode = array(0=>$output,1=>$badge);
  echo json_encode($encode);
 } else {
  echo '<label class="text-danger">Invalid File</label>';
 }
}
//

/*-----------------------------------*/
/*	 Funciones & Complementos
/*-----------------------------------*/
function OBTENER_CODIGO($obj,$z,$a){
 $CODIGO = '';
 $AREA   = '';
 $CLAVE  = '';
 $UDN    = 'AR';

 /*Clave de la zona */

 $zona   = $z;

 if ($zona >= 1 && $zona <= 9) {
  $zona ='0'.$zona;
 }else {  $zona = $zona ; }

 /*Clave del area */
 $array        = array($a);
 $AREA	        =	$obj	->	_area($array);


 if ($AREA==null && $AREA==0) {
  $allAreas  = $obj	->	SELECT_area(null);
  $AREA      = count($allAreas) + 1;
 }

 /* Obtener clave del producto  */
 $array = array($AREA,$zona);
 $all          = $obj -> ClaveProducto($array);
 $CLAVE      = Folio($all);

 $CODIGO  =$UDN.'-'.$zona.'-'.$AREA.'-'.$CLAVE;
 return  $CODIGO;

}

function Folio($Folio) {
 $NewFolio = 0; $Folio = $Folio + 1;
 if($Folio >= 100){
  $NewFolio = $Folio;
 }else if($Folio >= 10){
  $NewFolio = "0".$Folio;
 }
 else if($Folio >= 1){
  $NewFolio = "00".$Folio;
 }
 return $NewFolio;
}

function INSERTAR_REGISTROS($A,$B,$C,$D,$E,$F,$G,$H,$I,$codigo,$idZona,$idArea,$ruta,$obj){
 $hoy      = date('Y').'-'.date('m').'-'.date('d');
 $empresa  = 1;
 $zona     = $idZona;

 // BUSCAR EQUIPO ........

 $array        = array($A);
 $idEquipo     = $obj ->Select_idEquipo($array);

 //SI EXISTE GUARDAMOS EL ID

 if ($idEquipo == 0 || $idEquipo== "") {
  $obj->Insert_Equipo($array);
  $idEquipo = $obj->Select_idEquipo($array);
 }



 // Buscando proveedor ...
 if ($D == "" || $D == null) {
  $idPro  = 1; // Sin Proveedor

 }else {
  $array  = array($D);
  $idPro  = $obj ->Select_idProveedor($array);
  if ($idPro == 0) {
   $obj   -> Insert_Proveedor($array);
   $idPro =  $obj->Select_idProveedor($array);
  }
 }


 // Buscando Marca ...
 if ($C == "" ||$C == null) {
  $idMarca = 1;
 }else {

  $array   =   array($C);
  $idMarca =   $obj->Select_idMarca($array);

  if ($idMarca == 0) {
   $obj     -> Insert_Marca($array);
   $idMarca =  $obj ->Select_idMarca($array);
  }

 }

if ($F=='' || $F == null) {
 $F = 0;
}

 $categoria = 1;
 // $ruta      = null;
 $array = array(
  $codigo,
  $idEquipo,
  $idArea,

  1,//UDN ALMACEN
  1,//ESTADO
  $B,// categoria

  $E, // cantidad
  $F, //costo
  $I, // TiempoVida

  $G, // Observaciones
  $H,//Estado Producto
  $hoy,//FechaIngreso

  $zona,
  $ruta,
  $idPro,

  $idMarca);

  $obj->Insert_Codigo($array);
 }

 function Reemplazar($val){
  $data ='';
  $buscar     = array(chr(13).chr(10), "\r\n", "\n", "\r",'"');
  $reemplazar = array("", "", "", "",'\'');
  $data     = str_ireplace($buscar,$reemplazar,$val);



  return $data;
 }

 ?>
