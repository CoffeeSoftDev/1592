var url_file = 'controlador/flores/user/';

function listado_clientes() {
 $.ajax({
  type:"POST",
  url: "controlador/flores/user/clientes.php",
  data:"opc=1",
  beforeSend:function(){
   $('#content-clientes').html(Load_sm());
  },
  success:function(rp){
   data = eval(rp);
   setTimeout(function () { 
    
    $('#content-clientes').html(data[0]);
     export_data_table('#tbClientes');
    
   }, 500);
   
  }
 });
}

/* ---------------------------------- */
/* Nuevo Cliente                      */
/* ---------------------------------- */

function NuevoCliente() {
 modal_insertar = null;
 $.ajax({
  type: "POST",
  url: url_file+"clientes.php",
  data: 'opc=5',
  success: function (rp) {
   var data = eval(rp);
   modal_insertar = bootbox.dialog({
    title: '<i class="bx bx-edit ico-lg"></i> Nuevo Cliente',
    // size: 'small',
    message: data[0]
   });

  }

 });




}

function AgregarCliente() { 
 array = ['Cliente', 'Lugar', 'Direccion', 'Telefono', 'Correo', 'Credito', 'Estado'];

 arreglo_datos = get_name_data(array);
 $.ajax({
 type:"POST",
 url: url_file+"clientes.php",
 data:arreglo_datos+"opc=6",
 success:function(rp){
  modal_close(modal_insertar);
  listado_clientes();
 }
 });

}

function BuscarCliente(opc) { 
 cliente = $('#txtCliente').val();
 console.log(cliente);
 $.ajax({
 type:"POST",
 url: url_file+"clientes.php",
  data: "opc=7&txtNombre=" + cliente,
 
  success: function (rp) {
   data = eval(rp);
   if (data[0] == 1) {
    mnsj = _alert('El cliente ya se encuentra registrado', 'info');
    $('.txt-rp').html(mnsj);

    
    $('#btnNuevoCliente').attr('disabled','disabled');

   } else { 
    $('.txt-rp').html('');
    $('#btnNuevoCliente').removeAttr('disabled', 'disabled');
   }
 }
 });
}

// Editar Cliente

modal_editar = '';
function EditarCliente(idCliente) {
 
 $.ajax({
  type: "POST",
  url: "controlador/flores/user/clientes.php",
  data: 'opc=2&idCliente='+idCliente,
  success: function (rp) {
   var data = eval(rp);
   modal_editar = bootbox.dialog({
    title: '<i class="bx bx-edit ico-lg"></i> Editar Cliente',
    // size: 'small',
    message: data[0]
   });
   
  }
  
 });
 
 
 
 
}

function EditarFormulario(id) {

 array = ['Cliente', 'Lugar', 'Direccion', 'Telefono', 'Correo', 'Credito', 'Estado'];

 arreglo_datos = get_name_data(array);
 console.log(arreglo_datos);


 $.ajax({
  type: "POST",
  url: url_file + "clientes.php",
  data: arreglo_datos + "opc=3&idCliente=" + id,
  beforeSend: function () {
   $('.modal-body').html(Load_sm());
  },
  success: function (rp) {
   data = eval(rp);
   modal_close(modal_editar);
   listado_clientes();
  }
 });
}

function EliminarCliente(id) {
 NombreCliente = $('#lblCliente' + id).html();

 var dialog = bootbox.dialog({
  title: '',
  message: "<h4>¿ Estas seguro que deseas eliminar al cliente <b class='text-primary'>"+NombreCliente+"</b>?</h4>",
  // size: 'large',
  buttons: {
   cancel: {
    label: "Salir",
    className: 'btn-default',
    callback: function () {
    }
   },
   
   ok: {
    label: "Eliminar",
    className: 'btn-primary',
    callback: function () {
     QuitarCliente(id);
    }
   }
  }
 });
}

function QuitarCliente(id) {
 $.ajax({
 type:"POST",
 url: url_file+"clientes.php",
 data:"opc=4&idCliente="+id,

 success:function(rp){
  listado_clientes();
 }
 });
}




 function _alert(mnsj,tipo) { 
 alert  = '<div class="col-xs-12 col-sm-12"> <div class="alert alert-'+tipo+'">';
 alert += '<b> <i class="bx bx-info-circle ico-lg"></i> '+mnsj+'</b>';
 alert += '</div></div>';

 return alert;
}
