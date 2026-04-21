<?php
  include_once('../../../modelo/SQL_PHP/_Catalogo_MTTO.php');
  include_once('../../../modelo/SQL_PHP/_Utileria.php');
  $fin = new Catalogo;
  $util = new Util;
  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://MATERIALES
      echo '
        <br>
        <div class="row col-sm-12">
          <div class="col-sm-6 col-xs-12">
            <div class="form-group col-sm-12 col-xs-12 control_categoria">
              <label for="Ipt_Categoria" class="control-label">Categoría</label>
                <input type="text" class="form-control input-sm" id="Ipt_Categoria"/>
            </div>
            <div class="form-group col-sm-12 col-xs-12">
              <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-12" onClick="Save_Categoria();"><span class="icon-floppy"></span> Guardar</button>
            </div>
            <div class="col-sm-12 col-xs-12 Res_Categoria text-center">

            </div>
            <div class="tb_categoria"></div>
          </div>
          <div class="col-sm-6 col-xs-12">
            <div class="form-group col-sm-12 col-xs-6 control_productos ">
              <label for="ipt_producto" class="control-label">Productos</label>
              <input type="text" id="ipt_producto" class="form-control input-sm hide"/>
            </div>
            <div class="form-group col-sm-6 col-xs-6 control_stock hide">
              <label for="inp_stock" class="control-label">Stock mínimo</label>
              <input type="text" id="inp_stock" class="form-control input-sm"/>
            </div>
            <div class="col-sm-12 col-xs-12 text-center Res_Productos"></div>
            <div class="form-group col-sm-12 col-xs-12 hide">
              <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-12" onClick="Save_Productos();"><span class="icon-floppy"></span> Guardar</button>
            </div>
            <div class="tb_productos"></div>
          </div>
        </div>
        <div class="row col-sm-12">
          <div class="col-sm-6 col-xs-12">
            <div class="form-group col-sm-12 col-xs-12 control_area">
              <label for="ipt_area" class="control-label">Área</label>
              <input type="text" id="ipt_area" class="form-control input-sm"/>
            </div>
            <div class="col-sm-12 col-xs-12 text-center Res_area"></div>
            <div class="form-group col-sm-12 col-xs-12">
              <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-12" onClick="Save_areas();"><span class="icon-floppy"></span> Guardar</button>
            </div>
            <div class="tb_areas"></div>
          </div>
        </div>
      ';
      break;
    case 1://TABLA CATEGORIA

      /*TRATAMIENTO - PAGINACIÓN -SQL */
      $cont_cat = $fin->Select_Cont_Categoria();

      /***************************************************
                  VARIABLES / PAGINACIÓN
      ****************************************************/
      $paginaActual = $_POST['pag'];
      $Paginas= $cont_cat;
      $url= "tb_materiales_categoria";
      $Lotes = 10;
      $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

      echo $pag;
      if($paginaActual <= 1 ){
          $limit=0;
      }
      else{
          $limit = $Lotes*($paginaActual-1);
      }

      //BUSQUEDA DE DATOS
      $sql = $fin->Select_Tb_Categoria($limit,$Lotes);

      echo '
        <div class="form-group col-sm-12 col-xs-12 text-right">
          <label>Total de registros: '.$cont_cat.'</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12">
            <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
              <thead>
                <tr>
                  <th class="col-sm-11 col-xs-11">Nombre</th>
                  <th class="col-sm-1 col-xs-1">Desactivar</th>
                </tr>
              </thead>
              <tbody>';
                foreach ($sql as $row) {
                  echo '
                  <tr>
                    <td>'.$row[1].'</td>
                    <td class="text-center">
                      <button type="button" title="Eliminar" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onClick="modal_eliminar(3,'.$row[0].');"><span class="icon-cancel"></span></button>
                    </td>
                  </tr>
                  ';
                }

                echo '
              </tbody>
            </table>
        </div>';
      break;
    case 2://TABLA PRODUCTOS

        /*TRATAMIENTO - PAGINACIÓN -SQL */
        $cont_cat = $fin->Select_Cont_Producto();

        /***************************************************
                    VARIABLES / PAGINACIÓN
        ****************************************************/
        $paginaActual = $_POST['pag'];
        $Paginas= $cont_cat;
        $url= "tb_materiales_productos";
        $Lotes = 10;
        $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

        echo $pag;
        if($paginaActual <= 1 ){
            $limit=0;
        }
        else{
            $limit = $Lotes*($paginaActual-1);
        }

        //BUSQUEDA DE DATOS
        $sql = $fin->Select_Tb_Producto($limit,$Lotes);

        echo '
          <div class="form-group col-sm-12 col-xs-12 text-right">
            <label>Total de registros: '.$cont_cat.'</label>
          </div>
          <div class="form-group col-sm-12 col-xs-12">
              <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
                <thead>
                  <tr>
                    <th class="col-sm-11 col-xs-11">Nombre</th>
                    <th class="col-sm-11 col-xs-11">Stock</th>

                  </tr>
                </thead>
                <tbody>';
                foreach ($sql as $row) {
                  echo
                    '<tr>
                      <td>'.$row[1].'</td>
                      <td class="text-center">'.$row[2].'</td>
                    </tr>';
                }
                echo
                '</tbody>
              </table>
          </div>';
      break;
    case 3://TABLA AREAS
        /*TRATAMIENTO - PAGINACIÓN -SQL */
        $cont_cat = $fin->Select_Cont_Area();

        /***************************************************
                    VARIABLES / PAGINACIÓN
        ****************************************************/
        $paginaActual = $_POST['pag'];
        $Paginas= $cont_cat;
        $url= "tb_materiales_areas";
        $Lotes = 10;
        $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

        echo $pag;
        if($paginaActual <= 1 ){
            $limit=0;
        }
        else{
            $limit = $Lotes*($paginaActual-1);
        }

        //BUSQUEDA DE DATOS
        $sql = $fin->Select_Tb_Area($limit,$Lotes);
        echo '
          <div class="form-group col-sm-12 col-xs-12 text-right">
            <label>Total de registros: '.$cont_cat.'</label>
          </div>
          <div class="form-group col-sm-12 col-xs-12">
              <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
                <thead>
                  <tr>
                    <th class="col-sm-11 col-xs-11">Nombre</th>
                    <th class="col-sm-1 col-xs-1">Desactivar</th>
                  </tr>
                </thead>
                <tbody>';
                foreach ($sql as $row) {
                  echo
                    '<tr>
                      <td>'.$row[1].'</td>
                      <td class="text-center">
                        <button type="button" title="Eliminar" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onClick="modal_eliminar(5,'.$row[0].');"><span class="icon-cancel"></span></button>
                      </td>
                    </tr>';
                }
                echo
                '</tbody>
              </table>
          </div>';
      break;
    case 4://CONSUMIBLES
        $sql = $fin->Select_TipoAlmacen();
        $sql2 = $fin->Select_Familia();
        echo '
          <br>
          <div class="row col-sm-12 col-xs-12">
            <div class="col-sm-6 col-xs-12">
              <div class="form-group col-sm-6 col-xs-12 Group_Familia">
                <label for="inp_familia" class="control-label">Familias</label>
                <input type="text" id="inp_familia" class="form-control input-sm"/>
              </div>
              <div class="form-group col-sm-6 col-xs-12 Group_Familia">
                <label for="inp_familia" class="control-label">Categoría</label>
                <select class="form-control input-sm" id="Sl_Familia" onChange="tb_consumibles_familias();">';
                foreach ($sql as $row) {
                  echo '
                  <option value="'.$row[0].'">'.$row[1].'</option>
                  ';
                }
                echo
                '</select>
              </div>
              <div class="col-sm-12 col-xs-12 Res_Familia text-center"></div>
              <div class="form-group col-sm-12 col-xs-12">
                <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-12" onClick="Save_Familias();">Guardar</button>
              </div>
              <div class="tb_familias"></div>
            </div>

            <div class="col-sm-6 col-xs-12">
              <div class="form-group col-sm-6 col-xs-12 Group_Clases">
                <label for="Inp_Clases" class="control-label">Clases</label>
                <input type="text" class="form-control input-sm" id="Inp_Clases"/>
              </div>

              <div class="form-group col-sm-6 col-xs-12 Group_Familia">
                <label for="inp_familia" class="control-label">Familia</label>
                <select class="form-control input-sm" id="SL_Clase" onChange="tb_consumibles_clases(1);">';
                  foreach ($sql2 as $row) {
                    echo '<option value="'.$row[0].'">'.$row[1].' <i> ('.$row[2].')</i> </option>';
                  }
                echo
                '</select>
              </div>
              <div class="col-sm-12 col-xs-12 Res_Clase text-center"></div>
              <div class="form-group col-sm-12 col-xs-12">
                <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-12" onClick="Save_Clases();">Guardar</button>
              </div>
              <div class="tb_clases"></div>
            </div>
          </div>

          <div class="row  col-sm-12 col-xs-12">
            <div class="col-sm-6 col-xs-12">
              <div class="form-group col-sm-12 col-xs-6 control_Insumos">
                <label for="ipt_insumo" class="control-label">Insumos</label>
                <input type="text" id="ipt_insumo" class="form-control input-sm hide"/>
              </div>
              <div class="form-group col-sm-6 col-xs-6 control_stock hide">
                <label for="inp_stock" class="control-label">Stock mínimo</label>
                <input type="text" id="inp_stock" class="form-control input-sm"/>
              </div>
              <div class="col-sm-12 col-xs-12 Res_Insumo text-center"></div>
              <div class="form-group col-sm-12 col-xs-12 hide">
                <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-12" onClick="Save_Insumos();">Guardar</button>
              </div>
              <div class="tb_insumos"></div>
            </div>

            <div class="col-sm-6 col-xs-12">
              <div class="form-group col-sm-12 col-xs-12 Group_Marca">
                <label for="ipt_marca" class="control-label">Marca</label>
                <input type="text" id="ipt_marca" class="form-control input-sm"/>
              </div>
              <div class="col-sm-12 col-xs-12 Res_Marca text-center"></div>
              <div class="form-group col-sm-12 col-xs-12">
                <button type="button" class="btn btn-sm btn-info col-sm-10 col-sm-offset-1 col-xs-12" onClick="Save_Marcas();">Guardar</button>
              </div>
              <div class="tb_marca"></div>
            </div>
          </div>
        ';
      break;
    case 5://TABLA CLASES
      /*TRATAMIENTO - PAGINACIÓN -SQL */
      $cat_faml = $_POST['SL_Clase'];
      $cont_cat = $fin->Select_Cont_Clase($cat_faml);

      /***************************************************
                  VARIABLES / PAGINACIÓN
      ****************************************************/
      $paginaActual = $_POST['pag'];
      $Paginas= $cont_cat;
      $url= "tb_consumibles_clases";
      $Lotes = 10;
      $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

      echo $pag;
      if($paginaActual <= 1 ){
          $limit=0;
      }
      else{
          $limit = $Lotes*($paginaActual-1);
      }

      //BUSQUEDA DE DATOS
      $sql = $fin->Select_Tb_Clase($cat_faml,$limit,$Lotes);
      echo '
        <div class="form-group col-sm-12 col-xs-12 text-right">
          <label>Total de registros: '.$cont_cat.'</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12">
            <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
              <thead>
                <tr>
                  <th class="col-sm-11 col-xs-11">Nombre</th>
                  <th class="col-sm-1 col-xs-1">Desactivar</th>
                </tr>
              </thead>
              <tbody>';
                foreach ($sql as $row) {
                  echo
                  '<tr>
                    <td>'.$row[1].'</td>
                    <td class="text-center">
                      <button type="button" title="Eliminar" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onClick="modal_eliminar2(5,'.$row[0].');"><span class="icon-cancel"></span></button>
                    </td>
                  </tr>';
                }
              '</tbody>
            </table>
        </div>';
      break;
    case 6://TABLA FAMILIAS
      /*TRATAMIENTO - PAGINACIÓN -SQL */
      $catg = $_POST['categ'];
      $cont_cat = $fin->Select_Cont_Familias($catg);

      /***************************************************
                  VARIABLES / PAGINACIÓN
      ****************************************************/
      $paginaActual = $_POST['pag'];
      $Paginas= $cont_cat;
      $url= "tb_consumibles_familias";
      $Lotes = 10;
      $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

      echo $pag;
      if($paginaActual <= 1 ){
          $limit=0;
      }
      else{
          $limit = $Lotes*($paginaActual-1);
      }

      //BUSQUEDA DE DATOS
      $sql = $fin->Select_Tb_Familias($catg,$limit,$Lotes);
      echo '
        <div class="form-group col-sm-12 col-xs-12 text-right">
          <label>Total de registros: '.$cont_cat.'</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12">
            <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
              <thead>
                <tr>
                  <th class="col-sm-11 col-xs-11">Nombre</th>
                  <th class="col-sm-1 col-xs-1">Desactivar</th>
                </tr>
              </thead>
              <tbody>';
                foreach ($sql as $row) {
                  echo
                  '<tr>
                    <td>'.$row[1].'</td>
                    <td class="text-center">
                      <button type="button" title="Eliminar" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onClick="modal_eliminar2(6,'.$row[0].');"><span class="icon-cancel"></span></button>
                    </td>
                  </tr>';
                }
              echo
              '</tbody>
            </table>
        </div>';
      break;
    case 7://TABLA INSUMOS
      /*TRATAMIENTO - PAGINACIÓN -SQL */
      $cont_cat = $fin->Select_Cont_Insumo();

      /***************************************************
                  VARIABLES / PAGINACIÓN
      ****************************************************/
      $paginaActual = $_POST['pag'];
      $Paginas= $cont_cat;
      $url= "tb_consumibles_insumo";
      $Lotes = 10;
      $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

      echo $pag;
      if($paginaActual <= 1 ){
          $limit=0;
      }
      else{
          $limit = $Lotes*($paginaActual-1);
      }

      //BUSQUEDA DE DATOS
      $sql = $fin->Select_Tb_Insumos($limit,$Lotes);
      echo '
        <div class="form-group col-sm-12 col-xs-12 text-right">
          <label>Total de registros: '.$cont_cat.'</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12">
            <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
              <thead>
                <tr>
                  <th class="col-sm-11 col-xs-11">Nombre</th>
                  <th class="col-sm-11 col-xs-11">Stock</th>

                </tr>
              </thead>
              <tbody>';
                foreach ($sql as $row) {
                  echo
                  '<tr>
                    <td>'.$row[1].'</td>
                    <td class="text-center">'.$row[2].'</td>
                  </tr>';
                }
              echo
              '</tbody>
            </table>
        </div>';
      break;
    case 8://TABLA MARCAS
      /*TRATAMIENTO - PAGINACIÓN -SQL */
      $cont_cat = $fin->Select_Cont_Marca();

      /***************************************************
                  VARIABLES / PAGINACIÓN
      ****************************************************/
      $paginaActual = $_POST['pag'];
      $Paginas= $cont_cat;
      $url= "tb_consumibles_marca";
      $Lotes = 10;
      $pag = $util->paginar($paginaActual,$Paginas,$url,$Lotes);

      echo $pag;
      if($paginaActual <= 1 ){
          $limit=0;
      }
      else{
          $limit = $Lotes*($paginaActual-1);
      }

      //BUSQUEDA DE DATOS
      $sql = $fin->Select_Tb_Marca($limit,$Lotes);
      echo '
        <div class="form-group col-sm-12 col-xs-12 text-right">
          <label>Total de registros: '.$cont_cat.'</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12">
            <table class="table table-bordered table-striped table-hover table-condensed table-responsive">
              <thead>
                <tr>
                  <th class="col-sm-11 col-xs-11">Nombre</th>
                  <th class="col-sm-1 col-xs-1">Desactivar</th>
                </tr>
              </thead>
              <tbody>';
                foreach ($sql as $row) {
                  echo
                  '<tr>
                    <td>'.$row[1].'</td>
                    <td class="text-center">
                      <button type="button" title="Eliminar" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#exampleModal" onClick="modal_eliminar2(8,'.$row[0].');"><span class="icon-cancel"></span></button>
                    </td>
                  </tr>';
                }
              echo
              '</tbody>
            </table>
        </div>';
      break;
    case 9://MODAL ELIMINAR
      $dat = $_POST['dat'];
      $id = $_POST['id'];

      echo '
      <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal">&times;</button>
       <h3 class="modal-title text-center">¡ A L E R T A !</h3>
      </div>
      <div class="modal-body">
       <div id="code_m" id="modal-form">
        <div class="row">
         <div class="col-sm-2 col-xs-12">
          <h1><span class="fa fa-warning text-warning fa-2x"></span></h1>
         </div>
         <div class="col-sm-10 col-xs-12 text-justify">
          <h3>Al eliminar este registro, se borrará de forma permanente toda la información relacionada a este. Esto ocacionara errores irreversibles en la contabilidad y/o almacén.</h3>
         </div>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <div class="form-group col-sm-12 col-xs-12 text-center">
          <label>¿DESEA CONTINUAR?</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12 text-center">
          <button class="btn btn-danger btn-sm col-sm-6" class="close" data-dismiss="modal"><span class="icon-cancel"></span>Cancelar</button>
          <button class="btn btn-info btn-sm col-sm-5" onClick="Delete_Registro('.$dat.','.$id.');"><span class="icon-ok"><span>Continuar</button>
        </div>
      </div>
      ';
      break;
    case 10://MODAL ELIMINAR2
      $dat = $_POST['dat'];
      $id = $_POST['id'];

      echo '
      <div class="modal-header">
       <button type="button" class="close" data-dismiss="modal">&times;</button>
       <h3 class="modal-title text-center">¡ A L E R T A !</h3>
      </div>
      <div class="modal-body">
       <div id="code_m" id="modal-form">
        <div class="row">
         <div class="col-sm-2 col-xs-12">
          <h1><span class="fa fa-warning text-warning fa-2x"></span></h1>
         </div>
         <div class="col-sm-10 col-xs-12 text-justify">
          <h3>Al eliminar este registro, se borrará de forma permanente toda la información relacionada a este. Esto ocacionara errores irreversibles en la contabilidad y/o almacén.</h3>
         </div>
        </div>
       </div>
      </div>
      <div class="modal-footer">
        <div class="form-group col-sm-12 col-xs-12 text-center">
          <label>¿DESEA CONTINUAR?</label>
        </div>
        <div class="form-group col-sm-12 col-xs-12 text-center">
          <button class="btn btn-danger btn-sm col-sm-6" class="close" data-dismiss="modal"><span class="icon-cancel"></span>Cancelar</button>
          <button class="btn btn-info btn-sm col-sm-5" onClick="Delete_Registro2('.$dat.','.$id.');"><span class="icon-ok"><span>Continuar</button>
        </div>
      </div>
      ';
      break;
  }
?>

<!-- <div class="form-group col-sm-4 col-xs-12">
    <table class="table table-bordered table-condensed table-hover table-stripped">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Desactivar</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td></td>
          <td></td>
        </tr>
      </tbody>
    </table>
</div> -->
