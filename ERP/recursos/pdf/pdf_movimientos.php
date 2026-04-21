
<?php
session_start();

/* ARCHIVOS EXTERNOS */

require('../fpdf/fpdf.php');
include_once("../../modelo/SQL_PHP/_Gastos.php");
$obj    = new Gastos;

/*--------- DATA POST -----------*/
$contador    = $_GET['cont'];
$f1          = $_GET['f1'];
$f2          = $_GET['f2'];

/*---------   PDF   --------------*/
class PDF extends FPDF {
 //Se crea una funcion para editar el encabezado del archivo
 function Header()
 {
  // $this->SetFont('Times','B',15);
  // $this->SetTextColor('31', '52', '95');
  $this->SetFont('Arial','B',12);
  $this->Cell(130,5,'CONSULTA DE MOVIMIENTOS',0,0,'R');
  $this->Image('../../recursos/img/logo_c.png',165,5,35,15,'PNG');
  $this->SetFont('Arial','B',12);
  // $this->SetTextColor(120,120,120);
  $this->Cell(0,1,'',0,1,'R');
  $this->Cell(0,8,'',0,1,'R');
 }

 //Se crea una función para elaborar el pie de pagina del archivo y su numeración
 function Footer()
 {
  // Posición: a 1,5 cm del final
  $this->SetY(-15);
  // Arial italic 8
  $this->SetFont('Arial','I',8);
  // Número de página
  $this->Cell(0,10,utf8_decode('Página '.$this->PageNo().' / {nb}'),0,0,'C');
 }
}

//Se establece el objeto
$pdf = new PDF();
$pdf->AliasNbPages();//Se llama la numeración de página
$pdf->AddPage();//Agrega la hoja
$pdf ->Ln(2);
$pdf ->SetFont('Arial','b',10);
$pdf ->Cell(20,6,'PERIODO :',0,'C');
$pdf ->SetFont('Arial','',10);
$pdf ->Cell(30,6,'Del '.$f1.' al '.$f2,0,'C');
$pdf ->Ln(12);

for ($i        =  0; $i < $contador ; $i++) {
 $Cuenta = $obj -> GASTO_CLASE(array($_GET['data'.$i]));
 $pdf->SetFillColor(15, 36, 62); //color de fondo de las celdas
 $pdf->SetTextColor(255, 255, 255);
 $pdf ->SetFont('Arial','b',10);
 $pdf ->Cell(20,6,'CUENTA:',0,0,'C',true);
 $pdf ->SetFont('Arial','',10);
 $pdf ->Cell(170,6,$Cuenta,0,0,'L',true);
 $pdf ->Ln(6);

 $_array = $obj -> Destino(array($_GET['data'.$i],$f1,$f2));



 foreach ($_array as $key ) {
  $pdf ->SetFont('Arial','b',8);

  $pdf->SetFillColor(230, 230, 230); //color de fondo de las celdas
  $pdf->SetTextColor(22, 54, 92); //color de fondo de las celdas
  $pdf ->Cell(190,6,utf8_decode($key[1]),0,0,'L',true);

  $pdf->Ln(6);
  $pdf->Cell(15,5,utf8_decode('FECHA'),0,0,'C');
  $pdf->Cell(69,5,utf8_decode('CONCEPTO'),0,0,'C');
  $pdf->Cell(86,5,utf8_decode('OBSERVACIONES'),0,0,'C');
  $pdf->Cell(20,5,utf8_decode('MONTO'),0,0,'C');
  $pdf ->SetFont('Arial','',6);
  $_array_1 = $obj -> Gastos_clase(array($f1,$f2,$key[0]));
  $pdf->Ln(5);

  $total = 0;
  foreach ($_array_1 as $key0) {
   $total = $total + $key0[2];
   $pdf->Cell(15,4,utf8_decode($key0[0]),0,0,'C');
   $pdf->Cell(69,4,utf8_decode($key0[1]),0,0,'L');
   $pdf->Cell(86,4,utf8_decode($key0[3]),0,0,'L');
   $pdf->Cell(20,4,evaluar($key0[2]),0,0,'R');
   $pdf->Ln(4);
  }
  $pdf ->SetFont('Arial','b',6);

  $pdf->Cell(90,6,utf8_decode('Total'),0,0,'L');
  $pdf->Cell(100,6,evaluar($total),0,0,'R');
  $pdf->Ln(6);
 }



}//end For

function evaluar($val){
 $res = '';
 if ($val==0 || $val=="" || $val == null) {
  $res = '-';
 }else {
  $res ='$ '.number_format($val, 2, '.', ',');
 }
 return $res;
}

$pdf->Output();
?>
