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
    <a href="finanzas" style="padding-left: 10px; padding-right: 10px;"><img src="recursos/img/logo.png" alt="Argovia" height="50px"> </a>
   </div>


   <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">


    <ul class="nav navbar-nav navbar-right">

     <!-- <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
       <span class="icon-wrench-1"></span> MATERIALES <span class="caret"></span></a>

       <ul class="dropdown-menu">
        <li>
         <a href="mtto"  >
          <span class="icon-wrench-1"></span> ALMACEN </a>
         </li>

         <li role="separator" class="divider"></li>
         <li><a  href="mtto_folios"><span class="icon-barcode"></span> FOLIOS </a></li>

         <li role="separator" class="divider"></li>
         <li><a  href="carga-masiva"><span class=" icon-upload-outline"></span> CARGA MASIVA </a></li>


        </ul>


       </li> -->

<!--
       <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
         <span class="icon-shopping-basket"></span> PRODUCTOS <span class="caret"></span></a>

         <ul class="dropdown-menu">
          <li>
           <a href="productos"  >
            <span class="icon-pencil-1"></span> REGISTRO </a>
           </li>

           <li role="separator" class="divider"></li>

           <li>
            <a  href="inventario_productos"><span class="icon-pencil-1"></span> INVENTARIO </a>
           </li>

           <li role="separator" class="divider"></li>

           <li>
            <a  href="lista_inv_productos"><span class="icon-doc-text"></span> REPORTE DIARIO </a>
           </li>

           <li role="separator" class="divider"></li>
           <li>
            <a  href="formato_inventario"><span class="icon-doc-text"></span> REPORTE </a>
           </li>

          </ul>


         </li> -->





         <!-- ./  -->

         <li> <a style="text-decoration:none" href="flores_admin"><span class="icon-cog-1"></span> Flores</a></li>
         <li> <a style="text-decoration:none" href="flores_tpv"><span class="icon-cog-1"></span> Disponibilidad</a></li>


         
         <li> <a style="text-decoration:none" href="inventario">
             <span class="icon-book"></span>Flores y Follajes</a>
         </li>




         <li class="dropdown">
          <a style="text-decoration:none; " href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configuración <span class="caret"></span></a>
          <ul class="dropdown-menu">
           <li><a style="text-decoration:none; " href="" data-toggle="modal" data-target="#exampleModal" onclick="perfil();"><span class="icon-user"></span> Perfil</a></li>
           <!-- <li role="separator" class="divider"></li>
           <li><a style="text-decoration:none; color:#000; hover:#684B21;" target="_blank" href="manual_usuario"><span class="icon-file-pdf"></span> Manual de Usuario</a></li> -->
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
