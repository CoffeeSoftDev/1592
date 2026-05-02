<?php
 session_start();
?>
<div class="modal-body Perfil" >
  <div class="row" style="background:#EEECDF; margin:-15px;" >
    <div class="form-group col-sm-5 col-xs-12 text-center">
      <br>
      <img src="http://www.argovia.com.mx/img/logo.png" style="width:190px; height:80px;" alt="Argovia">
    </div>
    <div class="form-group col-sm-7 col-xs-12">
      <br>
      <div class="form-group col-sm-12 col-xs-12 text-center">
        <h2>Finanzas</h2>
      </div>
      <div class="form-group col-sm-12 col-xs-12 text-center">
        <h4> <?php echo $_SESSION['gerente']; ?> </h4>
      </div>
    </div>
  </div>
</div>
