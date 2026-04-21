$(function(){
	$('#ipt_min_inventario').numeric('');
	$('#ipt_min_inventario').numeric('.');
	$('#ipt_precio').numeric('');
	$('#ipt_precio').numeric('.');
	tabla_productos();
});


function AgregarInsumo() {

	array = ['Productos', 'Costo'];
	data = get_data(array, 'data');

	$('.tabla_productos_canasta').html(data);
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: data+'&opc=12',
		success:function(data) {
			tabla_productos_canasta(2);
		}
	});
}

function show_hide_insumo(opc) {
	if (opc==1) {
	$('#content-pane-insumo').addClass('hide');
}else if(opc==2){
		$('#content-pane-insumo').removeClass('hide');
	}
}

//
function combo(){
	cb = $('#tipo_alta').val();
	switch (cb) {
		case '1'://PRODUCTOS
				$('.altaProductos').removeClass('hide');
				$('.tabla_productos').removeClass('hide');
				$('.altaCanasta').addClass('hide');
				$('.clientes_especiales').addClass('hide');
				$('.tabla_lista_canasta').addClass('hide');
				tabla_productos();
			break;
		case '2'://CANASTAS
				$('.altaProductos').addClass('hide');
				$('.clientes_especiales').addClass('hide');
				$('#content-pane-insumo').addClass('hide');
				$('.tabla_productos').addClass('hide');
				$('.altaCanasta').removeClass('hide');
				$('.tabla_lista_canasta').removeClass('hide');
				ultima_canasta();
				tabla_productos_canasta(1);
				tabla_canastas();
			break;
		case '3'://CANASTAS
				$('.altaProductos').addClass('hide');
				$('.altaCanasta').addClass('hide');
				$('#content-pane-insumo').addClass('hide');
				$('.tabla_lista_canasta').addClass('hide');
				$('.tabla_productos').addClass('hide');
				$('.clientes_especiales').removeClass('hide');
				tabla_clientes();
				tabla_productos_clientes(0,0);
			break;
	}
}
function Save_cliente(){
	valor = true;
	if ( !$('#ipt_cliente').val() ) {
		valor = false;
		$('#ipt_cliente').focus();
		$('.gb_cliente').addClass('has-error');
	}
	if ( valor ) {
		$.ajax({
			type: "POST",
			url: "controlador/dia/clientes_especiales.php",
			data: 'opc=2&cliente='+$('#ipt_cliente').val(),
			success:function(data) {
				tabla_clientes();
				$('.gb_cliente').removeClass('has-error');
			}
		});
	}
}
function Save_PrecioEspecial(idCliente,idProducto){
	$.ajax({
		type: "POST",
		url: "controlador/dia/clientes_especiales.php",
		data: 'opc=3&costo='+$('#PE'+idCliente+idProducto).val()+'&idCliente='+idCliente+'&idProducto='+idProducto,
		beforeSend:function(){
			$('.icono'+idCliente+idProducto).toggleClass('icon-dollar');
			$('.icono'+idCliente+idProducto).toggleClass('icon-spin6 animate-spin');
		},
		success:function(data) {
			$('.icono'+idCliente+idProducto).toggleClass('icon-spin6 animate-spin');
			$('.icono'+idCliente+idProducto).toggleClass('icon-dollar');
		}
	});
}
function tabla_clientes(){
	$.ajax({
		type: "POST",
		url: "controlador/dia/clientes_especiales.php",
		data: 'opc=0',
		beforeSend: function() {
			$('.tb_clientes').html("<h3 class='col-xs-12 col-sm-12 text-center text-primary'><span class='icon-spin6 animate-spin'></span> El internet esta algo lento hoy, espere un momento por favor.</h3>");
		},
		success:function(data) {
			$('.tb_clientes').html(data);
		}
	});
}
function tabla_productos_clientes(idCliente,name){
	$.ajax({
		type: "POST",
		url: "controlador/dia/clientes_especiales.php",
		data: 'opc=1&idCliente='+idCliente+'&Name='+name,
		beforeSend: function() {
			$('.tb_productos_clientes').html("<h3 class='col-xs-12 col-sm-12 text-center text-primary'><span class='icon-spin6 animate-spin'></span> El internet esta algo lento hoy, espere un momento por favor.</h3>");
		},
		success:function(data) {
			$('.tb_productos_clientes').html(data);
		}
	});
}
function tabla_productos(){
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=0&date='+$('#date').val(),
		beforeSend: function() {
			$('.tabla_productos').html("<h3 class='col-xs-12 col-sm-12 text-center text-primary'><span class='icon-spin6 animate-spin'></span> El internet esta algo lento hoy, espere un momento por favor.</h3>");
		},
		success:function(data) {
			$('.tabla_productos').html(data);
		}
	});
}
function tabla_productos_canasta(idTipo){
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=4&idTipo='+idTipo,
		beforeSend: function() {
			$('.tabla_productos_canasta').html("<h3 class='col-xs-12 col-sm-12 text-center text-success'><span class='icon-spin6 animate-spin'></span> El internet esta algo lento hoy, espere un momento por favor.</h3>");
		},
		success:function(data) {
			$('.tabla_productos_canasta').html(data);
		}
	});
}
function tabla_canastas(){
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=5&canasta='+$('#ipt_canasta').val(),
		beforeSend: function() {
			$('.tabla_canastas').html("<h3 class='col-xs-12 col-sm-12 text-center text-warning'><span class='icon-spin6 animate-spin'></span> El internet esta algo lento hoy, espere un momento por favor.</h3>");
		},
		success:function(data) {
			$('.tabla_canastas').html(data);
		}
	});
}
function ultima_canasta(){
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=7',
		success:function(data) {
			res = eval(data);
			// alert(res[0]);
			if ( res[0] != null ) {
				$('.canasta_terminar').removeClass('hide');
				$('#ipt_canasta').attr('disabled','disabled');
				$('.canasta_save').attr('disabled','disabled');
				$('#id_canasta').val(res[0]);
				$('#ipt_canasta').val(res[1]);
			}
		}
	});
}

function clean_productos(){
	$('.gb_producto').removeClass('has-error');
	$('.gb_presentacion').removeClass('has-error');
	$('.gb_min_inventario').removeClass('has-error');
	$('.gb_precio').removeClass('has-error');
	$('.gb_mayoreo').removeClass('has-error');
	$('.gb_cant_mayoreo').removeClass('has-error');
}
function nuevo_producto(){
	valor = true;
	if ( !$('#ipt_producto').val() ) {
		valor = false;
		clean_productos();
		$('#ipt_producto').focus();
		$('.gb_producto').addClass('has-error');
	}
	else if ( !$('#ipt_presentacion').val() ) {
		valor = false;
		clean_productos();
		$('#ipt_presentacion').focus();
		$('.gb_presentacion').addClass('has-error');
	}
	else if ( !$('#ipt_min_inventario').val() ) {
		valor = false;
		clean_productos();
		$('#ipt_min_inventario').focus();
		$('.gb_min_inventario').addClass('has-error');
	}
	else if ( !$('#ipt_precio').val() ) {
		valor = false;
		clean_productos();
		$('#ipt_precio').focus();
		$('.gb_precio').addClass('has-error');
	}
	else if ( !$('#ipt_mayoreo').val() ) {
		valor = false;
		clean_productos();
		$('#ipt_mayoreo').focus();
		$('.gb_mayoreo').addClass('has-error');
	}
	else if ( !$('#ipt_cant_mayoreo').val() ) {
		valor = false;
		clean_productos();
		$('#ipt_cant_mayoreo').focus();
		$('.gb_cant_mayoreo').addClass('has-error');
	}

	if ( valor ) {
		valores = 'date='+$('#date').val()+'&producto='+$('#ipt_producto').val()+'&presentacion='+$('#ipt_presentacion').val()+
		'&min_inventario='+$('#ipt_min_inventario').val()+'&precio='+$('#ipt_precio').val()+'&mayoreo='+$('#ipt_mayoreo').val()
		+'&cant_mayoreo='+$('#ipt_cant_mayoreo').val()+'&tipo='+$('#CB_Tipo').val();
		$.ajax({
			type: "POST",
			url: "controlador/dia/productos.php",
			data: 'opc=1&'+valores,
			beforeSend: function() {
				$('.respuestas').html("<label class='col-xs-12 col-sm-12 text-center text-primary'><span class='icon-spin6 animate-spin'></span> Guardando nuevo producto</label>");
			},
			success:function(data) {
				if ( data != 0 ) {
					$('.respuestas').html("<label class='col-xs-12 col-sm-12 text-center text-danger'><span class='icon-attention'></span> Este producto ya esta registrado</label>");
				}
				else{
					tabla_productos();
				}
				clean_productos();
				$('#ipt_producto').val('');
				$('#ipt_presentacion').val('');
				$('#ipt_min_inventario').val('');
				$('#ipt_precio').val('');
				$('#ipt_mayoreo').val('');
				$('#ipt_cant_mayoreo').val('');
				$('#CB_Tipo').val('1');
			}
		});
	}
}

function Canasta(idCanasta){
	$('.lista_productos_canasta'+idCanasta).toggleClass('hide');
}

function nueva_canasta(){
	valor = true;
	if ( !$('#ipt_canasta').val() ) {
		valor = false;
		$('#ipt_canasta').focus();
		$('.gb_canasta').addClass('has-error');
	}

	if ( valor ) {
		$.ajax({
			type: "POST",
			url: "controlador/dia/productos.php",
			data: 'opc=6&canasta='+$('#ipt_canasta').val(),
			beforeSend: function() {
				$('.load_check').addClass('bg-primary');
				$('.load_check').html('<i class="icon-spin6 animate-spin"></i>');
			},
			success:function(data) {
				tabla_canastas();
				$('.load_check').removeClass('bg-primary');
				$('.load_check').html('<i class="icon-shopping-basket"></i>');
				$('.canasta_terminar').removeClass('hide');
				$('.canasta_save').attr('disabled','disabled');
				$('#ipt_canasta').attr('disabled','disabled');
				$('#id_canasta').val(data);
			}
		});
	}
}
function nuevo_producto_canasta(idProducto) {
	valor = true;
	if ( !$('#ipt_canasta').val() ) {
		valor = false;
		$('#ipt_canasta').focus();
		$('.gb_canasta').addClass('has-error');
	}

	if ( valor ) {
		$.ajax({
			type: "POST",
			url: "controlador/dia/productos.php",
			data: 'opc=8&canasta='+$('#ipt_canasta').val()+'&idCanasta='+$('#id_canasta').val()+'&idProducto='+idProducto,
			success:function(data) {
				tabla_canastas();
				$('.gb_canasta').removeClass('has-error');
				// $('.linea'+idProducto).addClass('hide');
			}
		});
	}
}
function delete_producto_canasta(idProductoCanasta,idProducto){
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=9&canasta='+idProductoCanasta,
		success:function(data) {
			tabla_canastas();
			$('.linea'+idProducto).removeClass('hide');
		}
	});
}
function terminar(){
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=13&idCanasta='+$('#id_canasta').val(),
		success:function(data) {
			$('#ipt_canasta').val('');
			$('#ipt_canasta').removeAttr('disabled','disabled');
			$('.canasta_save').removeAttr('disabled','disabled');
			$('.canasta_terminar').addClass('hide');
			tabla_canastas();
		}
	});
}
function Modificar_canasta(idCanasta,canasta){
	$('#id_canasta').val(idCanasta);
	$('#ipt_canasta').val(canasta);
	$('.canasta_terminar').removeClass('hide');
	$('.canasta_save').attr('disabled','disabled');
	$('#ipt_canasta').attr('disabled','disabled');
	tabla_canastas();
}

function Convertir_input(td,idProducto,valor,caso,extra){
	$('#'+td+idProducto).html('<input class="form-control input-xs" id="ipt_'+td+idProducto+'" onkeypress="if(event.keyCode == 13) Update_tb_proveedor(\''+td+'\','+idProducto+',\''+valor+'\','+caso+',\''+extra+'\');"  value="'+valor+'"/>');
	$('#ipt_'+td+idProducto).focus();
}
function Update_tb_proveedor(td,idProducto,valor,caso,extra){
	valores = 'caso='+caso+'&extra='+extra+'&idProducto='+idProducto+'&valor1='+valor+'&valor2='+$('#ipt_'+td+idProducto).val();
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=2&'+valores,
		beforeSend: function() {
			$('.respuestas').html('<label class="col-xs-12 col-sm-12 text-center text-primary"><span class="icon-spin6 animate-spin"></span> Actualizando datos...</label>');
		},
		success:function(data) {
			res = eval(data);
			nuevo_valor = res[1];

			if ( td == 'Name' ) {
				if ( res[0] == 0) {
					$('.respuestas').html('<label class="col-xs-12 col-sm-12 text-center text-warning"><span class="icon-attention-2"></span> Este producto ya existe</label>');
					$('#'+td+idProducto).html('<label class="input-label pointer" onclick="Convertir_input(\''+td+'\','+idProducto+',\''+valor+'\','+caso+',\''+extra+'\');">'+valor+'</label>');
					setTimeout("$('.respuestas').html('');",2000);
				}
				else {
					$('#'+td+idProducto).html('<label class="input-label pointer" onclick="Convertir_input(\''+td+'\','+idProducto+',\''+nuevo_valor+'\','+caso+',\''+extra+'\',\'\');">'+nuevo_valor+'</label>');
					$('.respuestas').html('');
				}
			}
			else if ( td == 'Cant_Canasta' ) {
				$('#'+td+idProducto).html('<label class="pointer" onclick="Convertir_input(\''+td+'\','+idProducto+',\''+nuevo_valor+'\','+caso+',\'\');">'+nuevo_valor+'</label>');
				$('.respuestas').html('');
			}
			else if(td != 'Precio' || td != 'Precio_Mayoreo') {
				$('#'+td+idProducto).html('<label class="input-label pointer" onclick="Convertir_input(\''+td+'\','+idProducto+',\''+res[0]+'\','+caso+',\'\');"> '+nuevo_valor+'</label>');
				$('.respuestas').html('');
			}
			else if ( td != 'Name' ) {
				$('#'+td+idProducto).html('<label class="input-label pointer" onclick="Convertir_input(\''+td+'\','+idProducto+',\''+nuevo_valor+'\','+caso+',\'\');">'+nuevo_valor+'</label>');
				$('.respuestas').html('');
			}

			if ( td == 'Inventario_Inicial' || td == 'Inventario_Minimo') {
				refresh_inventario_inicial(idProducto);
				// $('.respuestas').html(td);
			}
		}
	});
}
function refresh_inventario_inicial(idProducto){
	valores = 'idProducto='+idProducto;
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=3&'+valores,
		success:function(data) {
			res = eval(data);
			// $('.respuestas').html(data);
			$('#Fecha'+idProducto).html('<label class="input-label">'+res[0]+'<label>');
			$('#Inventario_Actual'+idProducto).html('<label class="input-label">'+res[1]+'<label>');
			$('#Inventario_Status'+idProducto).html('<label class="input-label">'+res[2]+'<label>');
			$('#Contenido'+idProducto).html('<label class="input-label">'+res[3]+'<label>');
		}
	});
}

function print_inventario(){
	date = $('#date').val();
	window.open("recursos/pdf/pdf_inventario_final.php?&date="+date,"_blank");
}

function Delete_producto(idProducto){
	valores = 'idProducto='+idProducto;
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=10&'+valores,
		success:function(data) {
			tabla_productos();
		}
	});
}
function Delete_Canasta(idCanasta){
	valores = 'idCanasta='+idCanasta;
	$.ajax({
		type: "POST",
		url: "controlador/dia/productos.php",
		data: 'opc=11&'+valores,
		success:function(data) {
			tabla_canastas();
		}
	});
}
