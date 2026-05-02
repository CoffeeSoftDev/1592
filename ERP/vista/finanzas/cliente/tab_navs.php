

<div class="container-fluid">
 <div class="panel panel-white format-doc">
  <!-- <div class="panel-heading" >
 </div> -->

 <div class="panel-body">

  <div class="row form-group ">
   <div class="col-xs-12 col-sm-3">
    <h4 ><span class=" icon-dollar-1"></span> MOVIMIENTOS </h4>
   </div>

   <div class="col-sm-3 col-xs-12">
    <label class=" col-sm-12">Fecha de emisión</label>
    <div class="col-sm-12">
     <div class="input-group date calendariopicker">
      <input type="text" class="select_input form-control input-sm" value="" id="date">
      <span class="input-group-addon input-sm" id="basic-addon2"><label class="fa fa-calendar"></label> </span>
     </div>
    </div>
   </div><!-- ./Fecha -->


   <div class="form-group col-sm-3 col-xs-12">
    <label class=" col-sm-12">Saldo Inicial</label>
    <div class="col-xs-12 col-sm-12">
     <div class="input-group">
      <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
      <input type="text" style="font-size:1.5rem; font-weight:400;" class="form-control input-sm" id="S_I_sobres" value="0.00" disabled>
     </div>
    </div>
   </div><!-- Saldo Inicial -->

   <div class="form-group col-sm-3 col-xs-12">
    <label class=" col-sm-12">Saldo Final</label>
    <div class="col-xs-12 col-sm-12">
     <div class="input-group">
      <span class="input-group-addon input-sm" id="basic-addon2"><label class="icon-dollar"></label></span>
      <input style="font-size:1.5rem; font-weight:400;" type="text" class="form-control input-sm" id="S_F_sobres" value="0.00" disabled>
     </div>
    </div>
   </div> <!-- ./Saldo Final -->
  </div>



  <ul class="nav nav-tabs">

   <li class="active">
    <a class="text-warning" data-toggle="tab" href="#" onClick="panel(1);"> <strong>Ingresos</strong></a>
   </li>
   <li>
    <a class="text-success" data-toggle="tab" href="#" onClick="panel(2);"><strong>Archivos</strong></a>
   </li>

   <li>
    <a class="text-success" data-toggle="tab" href="#" onClick="panel(9);"><strong>Reportes</strong></a>
   </li>
  
  <?php if($_SESSION['udn']==2) {?>
  
  
   <li>
    <a class="text-success" data-toggle="tab" href="#" onClick="panel(8);"><strong>Cheques</strong></a>
   </li>

   <li>
    <a class="text-info" data-toggle="tab" href="#" onClick="panel(3);"><strong>Gastos</strong></a>
   </li>

   <li>
    <a class="text-info" data-toggle="tab" href="#" onClick="panel(7);"><strong>Compras</strong></a>
   </li>

   <li>
    <a class="text-success" data-toggle="tab" href="#" onClick="panel(4);"><strong>Pagos</strong></a>
   </li>
   <li>
    <a class="text-success" data-toggle="tab" href="#" onClick="panel(5);"><strong>Proveedores</strong></a>
   </li>
   <li>
    <a class="text-success" data-toggle="tab" href="#" onClick="panel(6);"><strong>Caratula</strong></a>
   </li>
 
 <?php } ?>
    
  </ul><!-- ./tabs_navs -->
   <input type="hidden" class="input-sm form-control" value="1" id="Ipt_Oculto">


  <div class="tab_content"></div><!-- ./Tab_Contents -->
 </div><!-- ./Panel_Body -->
</div><!-- ./Panel -->
</div><!-- ./Container -->
