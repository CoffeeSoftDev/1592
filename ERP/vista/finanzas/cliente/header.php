<header>
 <nav class="navbar" >
  <div class="container-fluid">
   <!-- Brand and toggle get grouped for better mobile display -->
   <div class="navbar-header" >
    <button  type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
     <span class="sr-only">Toggle navigation</span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
     <span class="icon-bar"></span>
    </button>
    <!--<a href="finanzas" style="padding-left: 10px; padding-right: 10px;"><img src="recursos/img/logo.png" alt="Argovia" height="50px"> </a>-->
   </div>

   <!-- Navbar -->
   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <ul class="nav navbar-nav navbar-right">
     <?php
     if ($_SESSION['permiso']==1) {
      echo '     <li><a  href="finanzas" ><span class="icon-dollar"></span> Finanzas </a></li>
';
     }
     ?>

     <li><a style="" href="movimientos"><span class=" icon-dollar-1"></span> Movimientos</a></li>


     <li>
      <a href="#"  data-toggle="modal" data-target="#exampleModal" onClick="modal_acceso();"> <span class=" icon-money-1"></span> Retiros & Reembolsos</a>
     </li>

     <?php
     if ($_SESSION['permiso']==1) {
      // echo ' <li><a  href="proveedores"><span class="fa fa-truck"></span> Proveedores </a></li>';
     }
     ?>

     <li class="dropdown">
      <a style="text-decoration:none; " href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Acerca de <span class="caret"></span></a>
      <ul class="dropdown-menu">
       <li><a style="text-decoration:none; color:#000; hover:#684B21;" href="" data-toggle="modal" data-target="#exampleModal" onclick="tarjeta();"><span class="icon-user"></span> Perfil</a></li>
       <li role="separator" class="divider"></li>
       <li><a style="text-decoration:none; color:#000; hover:#684B21;" href="salir"><span class="icon-off"></span> Cerrar Sesión</a></li>
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

  </div>
 </div>
</div>
