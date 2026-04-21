//MOSTRAR TABLA

function tabla_Area(pag) {
	var empresa = $("#select").val();
	var area = $("#txtArea").val();
	var categoria = $("#txtCat").val();



	$("#buscar").val("");
	//Establecer una conexion con el servidor
	var conexion = new XMLHttpRequest();

	var data = new FormData();
	data.append('empresa', empresa);
	data.append('pag', pag);
	data.append('area', area);

	//Envio de Datos
	conexion.onreadystatechange = function(){
		if(conexion.readyState == 4 && conexion.status == 200){
			document.getElementById('tabla').innerHTML = conexion.responseText;

		}
	};





	conexion.open('POST','../../menu/almacen/tabla/',true);
	conexion.send(data);
}

function ver_tabla(pag){

	var empresa = $("#select").val();
	var area = $("#txtArea").val();
	$("#buscar").val("");
	verArea();
	cbCategoria();

	//Establecer una conexion con el servidor
	var conexion = new XMLHttpRequest();

	var data = new FormData();
	data.append('empresa', empresa);
	data.append('pag', pag);
	data.append('area', area);

	//Envio de Datos
	conexion.onreadystatechange = function(){
		if(conexion.readyState == 4 && conexion.status == 200){
			document.getElementById('tabla').innerHTML = conexion.responseText;
		}
	};

	conexion.open('POST','../../menu/almacen/tabla/',true);
	conexion.send(data);
}

//MOSTRAR TABLA
function ver_tabla_2(pag){
	//EMPRESA
	var empresa = $("#select").val();
	$("#buscar").val("");

	//Establecer una conexion con el servidor
	var conexion = new XMLHttpRequest();

	var data = new FormData();
	data.append('empresa', empresa);
	data.append('pag', pag);

	//Envio de Datos
	conexion.onreadystatechange = function(){
		if(conexion.readyState == 4 && conexion.status == 200){
			document.getElementById('tabla').innerHTML = conexion.responseText;

		}
	};

	conexion.open('POST','../../menu/almacen/tabla-no/',true);
	conexion.send(data);
}

//ABRIR MODAL
function ver_tabla_key(pag){
	var keyword = $("#buscar").val();
	var area = $("#txtArea").val();

	var opcion = 0;
	verArea();
	cbCategoria();

	if(document.getElementById('opc1').checked){
		opcion = 1;
	}
	else if(document.getElementById('opc2').checked){
		opcion = 2;
	}
	else if(document.getElementById('opc3').checked){
		opcion = 3;
	}

	//Establecer una conexion con el servidor
	var conexion = new XMLHttpRequest();

	//Enviar variables por POST con FormData
	var data = new FormData();
	data.append('key', keyword);
	data.append('opc', opcion);
	data.append('pag', pag);
	data.append('area', area);
	//Envio de Datos
	conexion.onreadystatechange = function(){
		if(conexion.readyState == 4 && conexion.status == 200){
			document.getElementById('tabla').innerHTML = conexion.responseText;

		}
	};

	conexion.open('POST','../../menu/almacen/tabla/',true);
	conexion.send(data);
}

//ABRIR MODAL
function ver_tabla_key_2(pag){
	var keyword = $("#buscar").val();
	var opcion = 0;

	if(document.getElementById('opc1').checked){
		opcion = 1;
	}
	else if(document.getElementById('opc2').checked){
		opcion = 2;
	}
	else if(document.getElementById('opc3').checked){
		opcion = 3;
	}

	//Establecer una conexion con el servidor
	var conexion = new XMLHttpRequest();

	//Enviar variables por POST con FormData
	var data = new FormData();
	data.append('key', keyword);
	data.append('opc', opcion);
	data.append('pag', pag);
	//Envio de Datos
	conexion.onreadystatechange = function(){
		if(conexion.readyState == 4 && conexion.status == 200){
			document.getElementById('tabla').innerHTML = conexion.responseText;

		}
	};

	conexion.open('POST','../../menu/almacen/tabla-no/',true);
	conexion.send(data);
}

function Alta_Baja(opc){
	if(!$("#pass").val()){
		$("#Res_Motivo").html("");
		$('#Res_Pass').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
		$("#pass").focus();
	}
	else if(!$("#motivo").val()){
		$("Res_Pass").html("");
		$('#Res_Motivo').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
		$("#motivo").focus();
	}
	else{
		var baja = $("#motivo").val();
		var pass = $("#pass").val();
		var id = $("#hide").val();
		/*alert(""+baja+" - "+pass+" - "+id);*/

		$.ajax({
			type: "POST",
			url: '../../controlador/operacion/mtto/almacen_insert_ba.php',
			data: ('baja='+baja+'&pass='+pass+'&id='+id+'&opc='+opc),

			beforeSend: function() {
				$('#Resultado_baja').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
			},

			success:function(respuesta) {
				$('#Resultado_baja').html(respuesta);
				if(opc == 0){
					ver_tabla(1);
				}
				else if(opc == 1){
					ver_tabla_2(1);
				}
			}
		});
	}
}


function verArea() {

	$.ajax({
		type: "POST",
		url: "../../controlador/operacion/mtto/cbArea.php",
		success:function(rp) {
			var data = eval(rp);
			$('#cbArea').html(data[0]);
		}
	});
}

function verCategoria() {
	$.ajax({
		type: "POST",
		url: "../../controlador/operacion/mtto/cbCategoria.php",
		success:function(rp) {
			var data = eval(rp);
			$('#cbCategoria1').html(data[0]);
		}
	});
}

function cbCategoria() {
	$.ajax({
		type: "POST",
		url: "../../controlador/operacion/mtto/cbCat.php",
		success:function(rp) {
			var data = eval(rp);
			$('#cbCat').html(data[0]);
		}
	});
}

function habilitar(){
	var flag = $('#cbCat').val();
	if (flag!=3) {
		$('#txtCantidad').attr("disabled", true);
	}else {
		$('#txtCantidad').attr("disabled", false);
	}

}


// Poliza de almacen
function ModalPoliza(id,opc){
	if (opc==1) {
		$('#SubirIMG').html(
			'<div class="form-group col-sm-12">'+
			'<div class="col-sm-12 ">'+
			'<input type="file" multiple="multiple"'+ 'class="form-control input-sm" id="img">'+
			'<div id="rs_img" class="text-center">'+
			'</div></div></div><!-- ./ form-group -->'+
			'<div class="form-group"> '+
			'<div class="col-sm-4 col-sm-offset-4">'+
			'<button type="button"'+
			'class="btn btn-large btn-block btn-success"'+
			'onclick="SubirImagen('+id+')">'+
			'<span class="fa fa-cloud-upload"></span>Subir</button>'+
			'<div class="bg-default" id="rs"></div>'+
			'</div></div> <!-- ./ form-group -->'
		);
	}else {

		$.ajax({
			type: "POST",
			url: '../../controlador/operacion/mtto/img_poliza.php',
			data: 'id='+id,
			beforeSend: function() {
				$('#Res_Presentacion').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando...</label>");
			},
			success:function(respuesta) {
				var data = eval(respuesta);
				$('#SubirIMG').html(data[0]);
			}
		});


	}


}

function SubirImagen(id_mtto) {
	var archivos = document.getElementById("img");
	var archivo = archivos.files;
	cant_fotos = archivo.length;

	if(cant_fotos > 0){
		var filarch = new FormData();

		for(i=0; i<archivo.length; i++){
			filarch.append('img'+i,archivo[i]);
		}
		filarch.append('id',id_mtto); //Añadimos cada

		$.ajax({
			url:'../../controlador/operacion/mtto/subir_poliza.php',
			type:'POST',
			contentType:false,
			data:filarch,
			processData:false,
			cache:false, //Para que el formulario no guarde cache
			beforeSend: function() {
				$('#rs').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span>Cargando imágenes...</label>");
			},
			success:function(data) {
				$('#SubirIMG').html(data);
				ver_tabla(1);
			}
		});
	}
}

function CantidadBaja() {
	var cantidad = $('#txtCantidad').html();
	var baja = $('#txtBaja').val();
	res = cantidad-baja;
	$('#txtRestantes').html(res);

	if (res<0) {

		$('#Resultado_baja').html("<label class='text-danger bg-info'> No se puede dar de baja productos que no tienes en el almacen </label>");
		blocked(true);
	}else {
		blocked(false);
		$('#Resultado_baja').html("");

	}

}

function Numero(){
	if(/^([a-zA-Z])*$/.test($('#txtCantidad').val())){
		$('#txtCantidad').val('');
	}
}


function blocked(habilitar) {
	document.getElementById("pass").disabled = habilitar;
	document.getElementById("motivo").disabled = habilitar;

}


function DarBaja(id) {
	var cantidad = $('#txtCantidad').html();
	var res = $('#txtRestantes').html();
	var motivo = $('#motivo').val();
	var pass = $('#pass').val();
	// -------------------------

	$.ajax({
		type: "POST",
		url: '../../controlador/operacion/mtto/almacen_insert_ba_3.php',
		data: 'res='+res+'&motivo='+motivo+'&id='+id+'&pass='+pass,
		// beforeSend: function() {
		// 	$('#Res_Presentacion').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span> Cargando...</label>");
		// },
		success:function(respuesta) {
			var data = eval(respuesta);
			$('#Resultado_baja').html(data[0]);
		}
	});

}

function show() {
	$("#Info").show();
}

function RemoverPoliza(id) {
	$.ajax({
		type: "POST",
		url: '../../controlador/operacion/mtto/RemoverPoliza.php',
		data: 'id='+id,
		beforeSend: function() {
			$('#SubirIMG').html("<label class='text-primary'><span class='icon-spin6 animate-spin'></span> Espere...</label>");
		},
		success:function(rp) {
			var data = eval(rp);
			$('#SubirIMG').html(data[0]);
			ver_tabla(1);
		}
	});
}
