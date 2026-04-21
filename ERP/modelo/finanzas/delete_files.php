<?php
    //BORRA TODOS LOS FICHEROS DE LA CARPETA CONTENEDORA DE HACE DOS MESES
    //ELIMINA TODOS LOS REGISTROS DE LOS SOBRES DE HACE DOS MESES
    include_once('../SQL_PHP/_Utileria.php');
    include_once('../SQL_PHP/_Finanzas.php');
    $utileria = new Util;
    $finanzas = new Finanzas;
    $mes = date('m');
    $mes_letra = '';

    if($mes == 1){
      $mes = 11;
      $mes_letra = $utileria -> mesLetra(($mes));
    }
    else if($mes == 2){
      $mes = 12;
      $mes_letra = $utileria -> mesLetra(($mes));
    }
    else{
      $mes_letra = $utileria -> mesLetra(($mes-2));
    }

    echo '<script>alert('.$mes_letra.');</scrip>';
    $directorio = '../../recursos/sobres_file/'.$mes_letra.'/';
    //SE MANDA A LLAMAR LA FUNCION QUE REALIZA EL ELIMINADO DE LOS FICHEROS
    deldir($directorio);
    //CONSULTA PARA ELIMINAR REGISTROS DEL MES PASADO
    $array = array($directorio);
    $finanzas->Delete_Files($array);
    //FUNCION PARA ELIMINAR TODOS LOS FICHEROS DE LA CARPETA DEL MES PASADO
    function deldir($dir){
        $current_dir = opendir($dir);
        while($entryname = readdir($current_dir)){
            if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
                deldir("${dir}/${entryname}");
            }elseif($entryname != "." and $entryname!=".."){
                unlink("${dir}/${entryname}");
            }
        }
        closedir($current_dir);
        rmdir(${'dir'});
        mkdir(${'dir'});
    }
?>
