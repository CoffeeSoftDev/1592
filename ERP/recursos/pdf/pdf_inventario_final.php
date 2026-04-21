<?php
  session_start();
  require('../fpdf/fpdf.php');
  include_once('../../modelo/SQL_PHP/_Productos.php');
  include_once('../../modelo/SQL_PHP/_Utileria.php');
  $obj = new Productos;
  $util = new Util;

  $date = $_GET['date'];
  $a = date("Y", strtotime("$date"));
  $m = date("m", strtotime("$date"));
  $d = date("d", strtotime("$date"));
  $m = $util->Mes_letra($m);
  $fecha = $d.'-'.$m.'-'.$a;

  $id_tipo = array(1);
  $cont = $obj->Select_count_productos($id_tipo);


  //Pare crear un encabezado y pie de pagina se crea una clase denominada pdf que sera el cuerpo del archivo
  class PDF extends FPDF {
    //Se crea una funcion para editar el encabezado del archivo
    function Header() {
      // $this->Image('../images/logos/'.$_GET['logo'].'.png',5,-5,35,35,'PNG');
      // $this->Image('../images/logo_gv_blue.png',58,4,15,15,'PNG');
      $this->SetFont('Times','B',20);
      $this->SetTextColor('31', '52', '95');
      $this->Cell(0,5,' DIVERSIFICADOS ARGOVIA S.A. DE C.V.',0,1,'C');
      $this->SetFont('Arial','B',12);
      $this->Cell(0,5,'INVENTARIO FINAL DE PRODUCTOS',0,1,'C');
    }
    //Se crea una función para elaborar el pie de pagina del archivo y su numeración
    function Footer() {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,5,utf8_decode('Página '.$this->PageNo().' / {nb}'),0,0,'C');
    }


  }

  //Se establece el objeto
  $pdf = new PDF('L','mm','A4');
  if($pdf->DefOrientation=='L' || $pdf->DefOrientation=='l'){
    $filas = 185;
  }
  if($pdf->DefOrientation=='P' || $pdf->DefOrientation=='p'){
    $filas = 260;
  }
  if($pdf->getY()>$filas){
    $pdf->AddPage();
  }
  $pdf->AliasNbPages();//Se llama la numeración de página
  $pdf->AddPage();//Agrega la hoja

  //FECHA DE CARATULA
  $pdf->SetFont('Arial','B',10);
  $pdf->Cell(0,4,$fecha,0,1,'R');
  $pdf->Cell(0,4,'',0,1,'R');

  $pdf->SetFont('Arial','B',7);
  $pdf->Cell(0,4,utf8_decode('# '.$cont.' Productos'),0,1,'R');

  $pdf->SetFont('Arial','B',8);
  $pdf->SetFillColor(217, 225, 242); //color de fondo de las celdas
  $pdf->Cell(10,4,utf8_decode('#'),1,0,'C',True);
  $pdf->Cell(57,4,utf8_decode('PRODUCTO'),1,0,'C',True);
  $pdf->Cell(30,4,utf8_decode('PRESENTACIÓN'),1,0,'C',True);
  $pdf->Cell(30,4,utf8_decode('PRECIO'),1,0,'C',True);
  $pdf->Cell(30,4,utf8_decode('MÍNIMO'),1,0,'C',True);
  $pdf->Cell(30,4,utf8_decode('EXISTENCIA'),1,0,'C',True);
  $pdf->Cell(30,4,utf8_decode('ESTATUS'),1,0,'C',True);
  $pdf->Cell(30,4,utf8_decode('CONTENIDO'),1,0,'C',True);
  $pdf->Cell(30,4,utf8_decode('VALOR TOTAL'),1,1,'C',True);

  $total = 0;
  $sql = $obj->Select_tabla_productos($id_tipo);
  foreach ($sql as $key => $value) {
    $hash = $key + 1;
    $idProducto = $value[0];
    $name_producto = $value[1];
    $presentacion = $value[2];
    $precio = $value[3];
    $min_inventario = $value[4];

    //INVENTARIO INICIAL
    $sql_almacen = $obj->Select_Inventario_Inicial2($idProducto,$date);
    $row = null; foreach($sql_almacen as $row);
    $fecha_inventario = $row[0];
    $inventario_inicial = $row[1];

    // INVENTARIO EXISTENTE
    $entrada = $obj->Select_Movimientos_Entrada_Inventario($idProducto);
    $salida  = $obj->Select_Movimientos_Salida_Inventario($idProducto);
    $actual  = $entrada - $salida;
    $costo   = $actual * $precio;
    $total   = $total + $costo;

    $contenido_letter = $util->solo_letras($presentacion);
    $contenido_number = $util->solo_numeros($presentacion);
    $contenido_full = 0;
    if ( $contenido_letter == 'gr' || $contenido_letter == 'GR') {
      $contenido_full = ($contenido_number / 1000) * $actual;
      $contenido_full = $contenido_full.' Kg';
    }
    else {
      $contenido_full = $contenido_number * $actual;
      $contenido_full = $contenido_full.' '.$contenido_letter;
    }

    $pdf->SetFont('Arial','',9);
    $pdf->Cell(10,4,($hash),1,0,'C');
    $pdf->Cell(57,4,utf8_decode($name_producto),1,0,'L');
    $pdf->Cell(30,4,$presentacion,1,0,'C');
    $pdf->Cell(30,4,'$ '.number_format($precio,2,'.',','),1,0,'R');
    $pdf->Cell(30,4,$min_inventario,1,0,'C');
    $pdf->Cell(30,4,$actual,1,0,'C');

    if ( $min_inventario < $actual ) {

      $pdf->SetTextColor('31', '111', '67');
      $pdf->SetFont('Symbol','',20);
      $pdf->Cell(30,4,chr('183'),1,0,'C');
      $pdf->SetTextColor('0', '0', '0');
    }
    else if ( $min_inventario >= $actual ) {
      $pdf->SetFont('Arial','B',7);
      $pdf->SetTextColor('221', '79', '66');
      $pdf->Cell(30,4,utf8_decode('¡ P E D I R !'),1,0,'C');
      $pdf->SetTextColor('0', '0', '0');
      $pdf->SetFont('Arial','',9);
    }

    $pdf->SetFont('Arial','',7);
    $pdf->Cell(30,4,$contenido_full,1,0,'C');
    $pdf->Cell(30,4,'$ '.number_format($costo,2,'.',','),1,1,'R');
  }



  $pdf->SetFillColor(178, 178, 178); //color de fondo de las celdas
  $pdf->Cell(247,4,'TOTAL',1,0,'R',True);
  $pdf->Cell(30,4,'$ '.number_format($total,2,'.',','),1,1,'R',True);


  $pdf->Output();
   $pdf->Output('ventas_diarias_'.$date.'.pdf','D');
?>
