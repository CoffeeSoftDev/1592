<?php
session_start();
include_once('../../../modelo/SQL_PHP/_Finanzas_Files.php');
include_once('../../../modelo/SQL_PHP/_CRUD.php');
include_once('../../../modelo/SQL_PHP/_Utileria.php');
$fin  = new Files_Fin;
$crud = new CRUD;
$util = new Util;
$idE  = $_SESSION['udn'];

$opc = $_POST['opc'];

switch ($opc) {
 case 0:
 ?>
 <br>
 <div class="row">
  <div class="form-group col-sm-12 col-xs-12">
   <h3 class="text-center"><strong><span class="icon-download"></span>Archivos</strong></h3>
   <hr>
  </div>
 </div>

 <div class="row">
  <div class="form-group col-sm-12">
   <label class="col-sm-12 text-center"><strong>Seleccionar archivos</strong></label>
   <div class="col-sm-4 col-sm-offset-4">
    <input type="file" class="form-control input-sm" id="archivos">
   </div>
  </div>
 </div>

 <?php
 $visible = 'hide';
 if ($idE==1) {
  $visible = '';
 }

 $cat = $fin->ver_CheckList(array($idE));
 echo '
 <div class="row '.$visible.'">
 <div class="form-group col-sm-12">
 <label class="col-sm-12 text-center"><strong>Vincular con: </strong></label>
 <div class="col-sm-4 col-sm-offset-4">
 <select class="form-control" id="txtSubcategoria">';

 foreach ($cat as $key) {
  echo '<option value="'.$key[0].'"> '.$key[1].'</option>';
 }

 echo '</select>
 </div>
 </div>
 </div>
 ';


 ?>

 <div class="row">
  <div class="form-group col-sm-12">
   <label class="col-sm-12 text-center"><strong>Detalles</strong></label>
   <div class="col-sm-4 col-sm-offset-4">
    <textarea name="name" rows="4" class="col-sm-12 col-xs-12 form-control input-sm" id="Detalles"></textarea>
   </div>
  </div>
 </div>

 <div id="Resul" class="text-center"> </div>

 <div class="row">
  <div class="form-group col-sm-12 col-xs-12">
   <label class="col-sm-12 text-center"> Limite máximo 20Mb * </label>
   <button type="button" class="btn btn-sm btn-info col-sm-2 col-sm-offset-5" OnClick="Up_Files();"><span class="icon-upload"></span> Subir Archivos</button>
  </div>
 </div>

 <div class="tb_files row"></div>
 <script src="recursos/js/finanzas/cliente/files.js?t=<?=time()?>"></script>
 <?php
 break;
 case 1:
 /***************************************************
 VARIABLES / PAGINACIÓN
 ****************************************************/
 $date = $_POST['date'];
 $paginaActual = $_POST['pag'];
 $Paginas = $fin->Select_Cont_Sobres($date,$idE);
 $url= "Select_tbarchivos";
 $Lotes = 10;
 $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

 echo $pag;
 if($paginaActual <= 1 ){
  $limit=0;
 }
 else{
  $limit = $Lotes*($paginaActual-1);
 }

 $Total_Pag = ceil($Paginas/$Lotes);


 $query = "SELECT idSobre,Archivo,Descripcion,ROUND(Peso,2),Hora,Type_File,Ruta FROM hgpqgijw_finanzas.sobres WHERE Fecha = '".$date."' LIMIT $limit, $Lotes";
 $sql = $crud->_Select($query,null);
 ?>
 <div class="form-group col-sm-12 col-xs-12">
  <table class="table table-responsive table-bordered table-stripped table-hover">
   <thead>
    <tr>
     <th class="text-center col-sm-2">Archivo</th>
     <th class="text-center col-sm-2 col-xs-1">Descripción</th>
     <th class="text-center col-sm-2 col-xs-1">Peso</th>
     <th class="text-center col-sm-2 col-xs-1">Hora</th>
     <th class="text-center col-sm-2 col-xs-1">Tipo</th>
     <th class="text-center col-sm-2 col-xs-1">Descargar</th>
    </tr>
   </thead>
   <tbody>
    <?php
    foreach ($sql as $value) {
     if($value[2] > 1024 ){
      $size = $value[3]/1024;
      $peso = Round($size,2)." Mb";
     }
     else {
      $peso = $value[3]." Kb";
     }
     echo '
     <tr>
     <td>'.$value[1].'</td>
     <td class="text-center">'.$value[2].'</td>
     <td class="text-right">'.$peso.'</td>
     <td class="text-center">'.$value[4].'</td>
     <td class="text-center">'.strtoupper($value[5]).'</td>
     <td class="text-center">
     <a href="'.$value[6].''.$value[1].'" title="Descargar" target="_blank" class="btn btn-xs btn-info"><span class="icon-download"></span> </a>

     <!-- <a onclick="QuitarFichero('.$value[0].')" title="Eliminar archivo " class="btn btn-xs btn-danger"><i class=" icon-trash-empty"></i></a> -->
     </td>
     </tr>
     ';
    }
    ?>
   </tbody>
  </table>
 </div>
 <?php
 break;

 /* Eliminar fichero de archivos */

 case 2:
 sleep(2);
 $id   = $_POST['id'];
 $ok   = $fin ->QuitarFichero(array($id));
 break;

}

?>
