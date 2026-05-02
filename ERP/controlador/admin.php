<?php
  session_start();
  include_once('../modelo/SQL_PHP/_Admin.php');
  $admin = new Admin();

  $opc = $_POST['opc'];

  switch ($opc) {
    case 0://LOGIN
      sleep(1);
      $user = $_POST['user'];
      $pass = $_POST['pass'];
      
      //COMPRUEBA QUE EXISTE EL USUARIO PARA INICIAR SESION
      $array = array($user,$pass);
      $id = $admin->Select_Login($array);
      if ( $id != 0 ) { $_SESSION['u_fe'] = $user; }
      echo $id;
      break;
    case 1://MODAL PERFIL
        echo '
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h3 class="modal-title" id="exampleModalLabel"><span class="icon-user"></span>Perfil</h3>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-sm-12 col-xs-12 Group_User">
              <label for="Inpt_User" class="control-label">Usuario</label>
              <input type="text" class="form-control input-sm" id="Inpt_User">
            </div>
            <div class="form-group col-sm-12 col-xs-12 Group_APass">
              <label for="Inpt_APass" class="control-label">* Contraseña anterior</label>
              <input type="password" class="form-control input-sm" id="Inpt_APass">
            </div>
            <div class="form-group col-sm-12 col-xs-12 Group_NPass">
              <label for="Inpt_NPass" class="control-label">Contraseña nueva</label>
              <input type="password" class="form-control input-sm" id="Inpt_NPass">
            </div>
            <div class="form-group col-sm-12 col-xs-12 Group_NPass2">
              <label for="Inpt_NPass2" class="control-label">Confirmar contraseña</label>
              <input type="password" class="form-control input-sm" id="Inpt_NPass2">
            </div>
            <div class="form-group col-sm-12 col-xs-12" style="font-size:12px; color:#988A67;">
              <label><i>* Para cualquier modificación es obligatoria la contraseña anterior</i></label>
            </div>
            <div class="form-group col-sm-12 col-xs-12 text-center" id="Res_Perfil"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-info btn-sm" onClick="Datos_User();">Guardar Cambios</button>
        </div>
        ';
      break;
    case 2://DATOS DEL USUARIO
      $nuser = $_POST['user'];
      $apass = $_POST['Apass'];
      $npass = $_POST['NPass'];
      $auser = $_SESSION['u_fe'];

      $array = array($auser,$apass);
      $idUser = $admin->Select_Login($array);
      $res = '0';
      if( $idUser != 0 ) {
        $res = '1';
        $array = array($nuser,$idUser);
        $admin->Update_User($array);
        $_SESSION['u_fe'] = $nuser;

        if ($npass != '') {
          $array = array($npass,$idUser);
          $admin->Update_Pass($array);
          $res = '2';
        }
      }


      echo $res;

      break;
  }
?>
