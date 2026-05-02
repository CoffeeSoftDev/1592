<?php
session_start();
$nivel = $_SESSION['nivel'];
?>

<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  
<div class="profile-sidebar">

   <div class="profile-userpic">
    <!--<img src="recursos/img/user1.PNG" class="img-responsive" alt="">-->
   </div>
   
   <div class="profile-usertitle">
    <div class="profile-usertitle-name"><strong>Bienvenido<?php echo '  >'.$nivel; ?> </strong></div>
    <div class="profile-usertitle-status"><span class="indicator label-success"></span>En linea </div>
   </div>
   <div class="clear"></div>
  </div>

  <div class="divider"></div>

  <ul class="nav menu">

   <li ><a href="administracion">
    <em class="fa fa-gear">&nbsp;</em> Configuración </a>
   </li>

   <!-- argovia finca Resort -->
    <li class="parent">
     <a data-toggle="collapse" href="#sub-item-1">
      <em class="glyphicon glyphicon glyphicon-list-alt">&nbsp;</em>Argovia
      <span data-toggle="collapse" href="#sub-item-1" class="icon pull-right">
       <em class="icon-right-dir"></em>
      </span>
     </a>

     <ul class="children collapse" id="sub-item-1">
    
     <li>
        <a href="ingresos_turismo">
         <em class=" icon-dollar">&nbsp;</em> Ingresos Turismo
        </a>
      </li>

      <li>
        <a href="admin">
         <em class="fa fa-area-chart">&nbsp;</em> Ingresos Anuales
        </a>
      </li>


      <li>
        <a href="">
         <em class="bx bxs-calendar-alt">&nbsp;</em> MTTO -Pedidos
        </a>
      </li>
 

      <!-- <li>
        <a href="ss">
         <em class="bx bx-store">&nbsp;</em> Tiendita
        </a>
      </li> -->
     </ul>

    </li>

    <li><a href="https://15-92.com/ERP/PEDIDOS/pedidos.php"><em class="icon-cubes">&nbsp;</em> Pedidos </a></li>


    <li><a href="salir"><em class="fa fa-power-off">&nbsp;</em> Salir </a></li>

 </ul>

</div> <!-- END SIDE BAR-->
