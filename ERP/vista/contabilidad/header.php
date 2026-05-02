
<header>

 <nav style="background:linear-gradient(to bottom right, #004879, #0068A6);" class="navbar" >
  <div class="container-fluid">

   <div class="navbar-header" >

    <button  type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
     <span class="sr-only">Toggle navigation</span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
    </button>

    <a href="conta_admin" style="padding-left: 10px; padding-right: 10px;">
        <!--<img src="recursos/img/logo.png" alt="Argovia" height="50px">-->
    </a>
   </div>


   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


    <ul class="nav navbar-nav navbar-right">

     <li> <a style="text-decoration:none" href="conta_admin"><span class="  icon-tasks"></span> Historial de ventas </a></li>
     <li> <a style="text-decoration:none" href="conta_admin"><span class="  icon-tasks"></span> Moderniza </a></li>

   


     <li class="dropdown">
      <a style="text-decoration:none; " href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mi cuenta <span class="caret"></span></a>
      <ul class="dropdown-menu">
       <li><a style="text-decoration:none; " href="" data-toggle="modal" data-target="#exampleModal" onclick="perfil();"><span class="icon-user"></span> Perfil</a></li>
        <li role="separator" class="divider"></li>
       <li><a style="text-decoration:none; " href="salir"><span class="icon-off"></span> Cerrar Sesión</a></li>
      </ul>
     </li>
    </ul>
   </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
 </nav>
</header>


<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog modal-xs" role="document">
  <div class="modal-content">
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
  </div>
 </div>
</div>
