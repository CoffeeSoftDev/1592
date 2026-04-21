<div class="modal" id="M02">
 <div class="modal-dialog" role="document">
  <div class="modal-content">
   <div class="modal-body">
    <div class="form-horizontal">
     <div class="form-group">
      <div class="col-xs-12 col-sm-12 text-center">

       <h3><span class="fa fa-truck"></span> AGREGAR PROVEEDOR</h3>
      </div>
     </div>
     <hr>
     <div class="form-group _Group_01">
      <label class="col-sm-4 ">Proveedor:</label>
      <div class="col-sm-8">
       <input type="text" class="form-control input-xs" id="_txtProveedor"  required=""> </div>
      </div>
      <!---->
      <div class="form-group _Group_02">
       <label class="col-sm-4 ">Direccion:</label>
       <div class="col-sm-8">
        <input type="text" class="form-control input-xs" id="_txtDireccion" > </div>
       </div>

        <!---->
        <div class="form-group _Group_03">
         <label class="col-sm-4 ">Telefono:</label>
         <div class="col-sm-8">
          <input type="text" class="form-control input-xs" id="_txtTelefono" name="product_code" required=""> </div>
         </div>
         <!---->
         <div class="form-group _Group_04">
          <label class="col-sm-4 ">RFC:</label>
          <div class="col-sm-8">
           <input type="text" class="form-control input-xs" id="_txtRFC"  required=""> </div>
          </div>

          <!---->
          <div class="form-group _Group_05">
           <label class="col-sm-4 ">Formas de Pago:</label>
           <div class="col-sm-8">
            <textarea  class="form-control " id="_txtFormasPago"  required=""> </textarea>
           </div>
           </div>

          <div class="form-group _Group_06">
           <label class="col-sm-4 ">Categoria :</label>
           <div class="col-sm-8 Group_area" id="_cbCategoria">
            <select class="form-control input-xs" required="" id="_txtCategoria">
             <option value="0">Selecciona</option>
            </select>
           </div>
          </div>

          <!---->
          <div class="form-group _Group_07">
           <label class="col-sm-4 ">Observacion:</label>
           <div class="col-sm-8">
            <textarea class="form-control " id="_txtContacto" ></textarea> </div>
           </div>

          <!--./ form-group -->
          <div class="form-group">
           <div class="col-xs-12 col-sm-12 text-center" id="_txtRes"> </div>
          </div>


         </div>
        </div>
        <div class="modal-footer">
         <button onclick="ModalData()" type="button" class="btn btn-success"><span class="fa fa-check"></span></span>Guargar</button>
         <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>

        </div>
       </div>
      </div>
     </div>
