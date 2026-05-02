<!---->
<div class="modal fade " id="M3" role="dialog">
 <div class="modal-dialog">
  <div class="modal-content">
   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title text-center"></h4>
    <p class="text-center"></p>

   </div>
   <div class="modal-body">
    <div class="">
     <div id="">

      <div class="form-horizontal">

       <div class="form-group Group_name">
        <label  class="col-sm-4 ">Nombre Completo:</label>
        <div class="col-sm-8">
         <input type="text" class="form-control input-xs" id="txtGerente" name="product_code" required="">
        </div>
       </div>

       <!---->


       <div class="form-group Group_user">
        <label  class="col-sm-4 ">Usuario:</label>
        <div class="col-sm-8">
         <input type="text" class="form-control input-xs" id="txtUsuario" name="product_code" required="">
        </div>
       </div>

       <!---->
       <div class="form-group">
        <label  class="col-sm-4">Contraseña *:</label>
        <div class="col-sm-8">
         <input type="password" class="form-control input-xs" id="txtPass">
        </div>
       </div>
       <!---->


       <div class="form-group Group_pass">
        <label  class="col-sm-4">Ingresa nuevamente la contraseña* :</label>
        <div class="col-sm-8">
         <input type="password" onchange="ComprobrarPass()" class="form-control input-xs" id="txtPass2">
        </div>
       </div>

       <!---->

       <div class="form-group Group_mail">
        <label  class="col-sm-4 ">Correo:</label>
        <div class="col-sm-8">
         <input type="text" class="form-control input-xs" id="txtEmail" name="product_code" required="">
        </div>
       </div>

       <div class="form-group">
        <label class="col-sm-4 ">Negocio :</label>

        <div class="col-sm-8 Group_udn"  id="cbNegocio">
         <select class="form-control input-xs" required="" id="txtUDN">
          <option value="0">Selecciona</option>
         </select>
        </div>
       </div>


       <div class="form-group">
        <label class="col-sm-4 ">Área :</label>

        <div class="col-sm-8 Group_area"  id="cbArea">
         <select class="form-control input-xs" required="" id="txtArea">
          <option value="0">Selecciona</option>
         </select>
        </div>
       </div>


       <div class="form-group">
        <label for="status" class="col-sm-4 ">Nivel</label>

        <div class="col-sm-8 Group_nivel" id="cbNivel">
         <select class="form-control input-xs" name="status" id="txtNivel">
          <option value="0">Selecciona</option>

         </select>
        </div>
        <!---->

       </div>	<!--./ form-group -->




       <div class="form-group">
        <div class="col-xs-12 col-sm-12 text-success text-center" id="txtRes" >

        </div>
       </div>


       <div class="form-group">
        <!-- <label for="image" class="col-sm-2 control-label">Imagen</label> -->

        <!-- <div class="col-sm-8">
        <input type="file" name="imagefile" id="imagefile" onchange="">
       </div> -->
      </div>

      <div class="form-group" >

       <!-- <div class="col-xs-12 col-sm-2 col-sm-offset-8">
       <a type="button" class="btn btn-xs btn-danger  col-xs-12 " ></a>
      </div> -->

      <div class="col-xs-12 col-sm-4 col-sm-offset-8">
       <a type="button" onclick="saveData(0,0)" class="btn btn-xs btn-success  col-xs-12 ">Guardar</a>
      </div>
     </div>




    </div>



    <!-- CODE.. -->


   </div>
  </div><!-- row-->
 </div><!-- ./ modal-body -->
</div>
</div>
</div>



<!-- modal 2 -->
<!---->
<div class="modal fade " id="M2" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center"></h4>
                <p class="text-center"></p>

            </div>
            <div class="modal-body">
                <div  id="bajaUser">

                      <!-- CODE.. -->



                </div><!-- row-->
            </div><!-- ./ modal-body -->
        </div>
    </div>
</div>
