<?php
include_once("_CRUD.php");
Class Files_Cheq extends CRUD {
  function NOW(){
    $query = "SELECT DATE_FORMAT(NOW(),'%Y-%m-%d')";
    $sql = $this->_Select($query,null);
    foreach($sql as $row);
    return $row[0];
  }

  function Select_Banco(){
    $query = "SELECT Banco FROM hgpqgijw_finanzas.banco";
    $sql = $this->_Select($query,null);
    return $sql;
  }
  function Select_Id_Banco($banco){
    $row = null;
    $array = array($banco);
    $query = "SELECT idBanco FROM hgpqgijw_finanzas.banco WHERE Banco = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }
  function Insert_Banco($banco){
    $array = array($banco);
    $query = "INSERT INTO hgpqgijw_finanzas.banco (Banco) VALUES (?) ";
    $this->_DIU($query,$array);
  }

  function Select_Archivos($array) {
    $row = null;
    $query = "SELECT Archivo FROM hgpqgijw_finanzas.cheques WHERE id_UDN = ? AND Ruta = ? AND Archivo = ? ";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    return $row[0];
  }


  /* Cuenta */

  function	idCuenta($array){
    $idC = null;
    $sql="SELECT idCuenta FROM hgpqgijw_finanzas.cuentacheque Where CUENTA =?";
    $ps	=	$this->_Select($sql,$array);
    foreach ($ps as $key){
      $idC = $key[0];
    }
    return	$idC;
  }

  function Insert_Cheques($array) {
    $query = "INSERT INTO
    hgpqgijw_finanzas.cheques
    (id_Banco,id_UDN,Importe,Cuenta,Cheque,Nombre,Archivo,Concepto,Ruta,Fecha,id_destino) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $this->_DIU($query,$array);
  }

  function	verCuenta(){
    $sql="SELECT cuenta FROM hgpqgijw_finanzas.cuentacheque ";
    $ps	=	$this->_Select($sql,null);
    return	$ps;
  }

  function Select_Cont_Cheques($date,$date2,$idE){
    $row = null;
    if ( $date2 != '') {
      $array = array($date,$date2,$idE);
    }
    else {
      $array = array($date,$date,$idE);
    }
    $query = "SELECT Count(*) FROM hgpqgijw_finanzas.cheques WHERE Fecha BETWEEN ? AND ? AND id_UDN = ?";
    $sql = $this->_Select($query,$array);
    foreach($sql as $row);
    if ( !isset($row[0]) ) { $row[0] = 0; }
    return $row[0];
  }

  function Select_Datos_Cheques($id){
    $array = array($id);
    $query = "SELECT
    date_format(cheques.Fecha,' %d/%m/%Y '),
    cheques.Nombre,
    cheques.Importe,
    banco.Banco,
    cuentacheque.Cuenta,
    cheques.Cheque,
    cheques.Concepto,
    cheques.Ruta,
    cheques.Archivo,
    cuentacheque.NombreCuenta
    FROM
    hgpqgijw_finanzas.cheques
    INNER JOIN hgpqgijw_finanzas.cuentacheque ON cheques.Cuenta = cuentacheque.idCuenta
    INNER JOIN hgpqgijw_finanzas.banco ON cheques.id_Banco = banco.idBanco
    WHERE idCheque = ?";
    $sql = $this->_Select($query,$array);
    return $sql;
  }

  function Delete_Cheque($id){
    $array = array($id);
    $query = "DELETE FROM hgpqgijw_finanzas.cheques where idCheque = ?";
    $this->_DIU($query,$array);
  }

  function	DetalleCuenta($array){
    $busqueda = 0;
    $sql="
    SELECT
    NombreCuenta
    FROM
    hgpqgijw_finanzas.cuentacheque
    WHERE
    Cuenta = ? ";
    $ps	=	$this->_Select($sql,$array);

    foreach ($ps as $key) {
      $busqueda = $key[0];
    }
    return	$busqueda;
  } 

  /*-----------------------------------*/
  /*	 RESPALDO POLIZA CHEQUE
  /*-----------------------------------*/
  function SAVE_FORM($array,$campos,$bd){
    $data ='';
    $x    ='';
    $query ='';
    $query = $query."INSERT INTO ".$bd."(" ;

    for ($i=0; $i < count($campos); $i++) {
      $data = $data."".$campos[$i].",";
      $x    = $x.'?,';
    }

    $data  = substr($data,0,strlen($data)-1);
    $x     = substr($x,0,strlen($x)-1);

    $query = $query.$data.") VALUES (".$x.")";
    $this->_DIU($query,$array);

    return $query;
  }

  function	FILE_POLIZA($array){
    $sql="
    SELECT
    Fecha,
    Archivo,
    Descripcion,
    Ruta,
    idFile
    FROM hgpqgijw_finanzas.file_polizas
    WHERE id_cheque = ?
    ";
    $ps	=	$this->_Select($sql,$array);
    return	$ps;
  }

  function EliminarBackUP($array){

    $query = "DELETE FROM
    hgpqgijw_finanzas.file_polizas
    WHERE idFile = ?";
    $this->_DIU($query,$array);
  }

  function verDestinos($array) {
    $query = "SELECT
    idUI,
    Name_IC
    FROM
    hgpqgijw_finanzas.insumos_udn
    INNER JOIN hgpqgijw_finanzas.insumos_clase ON insumos_udn.id_IC = insumos_clase.idIC
    WHERE
    id_UDN = ?
    AND Stado = ?";
    $sql = $this->_Select($query,$array);


    return $sql;
  }

/*-----------------------------------*/
/*		Cierre Resumen gral
/*-----------------------------------*/
function CIERRE_DIARIO($array){
 $query = "";
 $this->_DIU($query,$array);
}

/*-----------------------------------*/
/*		Lista de cheques
/*-----------------------------------*/
function verCheques($array){
 $query = "SELECT
  cheques.Fecha,
  cheques.Nombre,
  banco.Banco,
  NombreCuenta,
  Cheque,
  ROUND(Importe, 2),
  cheques.Concepto,
  cheques.idCheque,
  cuentacheque.Cuenta
  FROM
  hgpqgijw_finanzas.cheques
  INNER JOIN hgpqgijw_finanzas.cuentacheque ON cheques.Cuenta = cuentacheque.idCuenta
  INNER JOIN hgpqgijw_finanzas.banco ON cheques.id_Banco = banco.idBanco
  WHERE id_UDN = ? AND Fecha BETWEEN ? and ?";
    $sql = $this->_Select($query,$array);


    return $sql;
}


}
?>
