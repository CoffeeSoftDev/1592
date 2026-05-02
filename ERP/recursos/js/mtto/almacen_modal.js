// INSERTAR PRODUCTO
function Nuevo_Codigo(opc){
	empresa = document.getElementById("Empresa");
	equipo	= document.getElementById("Equipo");
	area	= document.getElementById("Area");
	codigo 	= document.getElementById("Codigo");
	res_equipo 	= document.getElementById("Res_Equipo");
	res_area	= document.getElementById("Res_Area");
	res_codigo	= document.getElementById("Res_Codigo");
	categoria = $('#cbCat').val();
	cantidad = $('#txtCantidad').val();
	valor = true;

	if(!equipo.value){
		$('#Res_Equipo').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
		equipo.focus();
		valor = false;
		res_equipo.style.display = 'block';
		res_area.style.display = 'none';
		res_codigo.style.display = 'none';

	} else if(!area.value){
		$('#Res_Area').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
		valor = false;
		area.focus();
		res_equipo.style.display = 'none';
		res_area.style.display = 'block';
		res_codigo.style.display = 'none';

	} else if(!codigo.value){
		$('#Res_Codigo').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
		valor = false;
		codigo.focus();
		res_equipo.style.display = 'none';
		res_area.style.display = 'none';
		res_codigo.style.display = 'block';

	} else if (categoria==0){
		$('#Res_Categoria').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>* El campo es requerido </label>");
		valor = false;
		$('#cbCat').focus();
		res_equipo.style.display = 'none';
		res_area.style.display = 'none';
		res_codigo.style.display = 'block';
	}
	//-----
	if(valor){
		res_equipo.style.display = 'none';
		res_area.style.display = 'none';

		if(opc == 1) { // Insertar producto
			$.ajax({
				type: "POST",
				url: '../../controlador/operacion/mtto/almacen_insertar.php',
				data: ('empresa='+empresa.value+'&equipo='+equipo.value+'&area='+area.value+'&codigo='+codigo.value+'&categoria='+categoria+'&cant='+cantidad),
				beforeSend: function() {
					$('#Resultado').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
				},
				success:function(respuesta) {
					$('#Resultado').html(""+respuesta);
				}
			});

			//-----

		}	else if(opc == 2) { // Modificar almacen
			cantidad = $('#txtCantidad').val();
			cbCat = $('#cbCat').val();
			$.ajax({
				type: "POST",
				url: '../../controlador/operacion/mtto/almacen_modificar.php',
				data: ('empresa='+empresa.value+'&equipo='+equipo.value+'&area='+area.value+'&codigo='+codigo.value+'&hidden='+$('#hidden').val()+'&opc='+opc+'&cant='+cantidad+'&cbCat='+cbCat),

				beforeSend: function() {
					$('#Resultado').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
				},

				success:function(respuesta) {

					if(respuesta == 1){
						$('#Resultado').html("<label class='col-xs-12 col-sm-12 text-center text-primary'>¿Desea mantener el mismo código?</label>");
						$('#opcion').show();
						$('#btn_mod').hide();

					}else if(respuesta == 2){
						$('#Resultado').html("<label class='col-xs-12 col-sm-12 text-center text-danger'>Este código ya existe intente nuevamente.</label>");

					}else if(respuesta == 3){
						equipo.value = "";
						area.value = "";
						codigo.value = "";
						$('#Resultado').html("<label class='col-xs-12 col-sm-12 text-center text-primary'>El producto se modificó correctamente</label>");
						$('#opcion').hide();
						$('#btn_mod').show();

					}else{
						$('#Resultado').html(""+respuesta);
					}


				}

			});
		}

		// -----

		else if(opc == 3){
			cantidad = $('#txtCantidad').val();
			cbCat = $('#cbCat').val();
		
			$.ajax({
				type: "POST",
				url: '../../controlador/operacion/mtto/almacen_modificar.php',
				data: ('empresa='+empresa.value+'&equipo='+equipo.value+'&area='+area.value+'&codigo='+codigo.value+'&hidden='+$('#hidden').val()+'&opc='+opc+"&cant="+cantidad+'&cbCat='+cbCat),

				beforeSend: function() {
					$('#Resultado').html("<p class = 'text-primary text-center' id = 'mensaje'><span class='icon-spin6 animate-spin'></span>Enviando datos...</p>");
				},

				success:function(respuesta) {
					equipo.value = "";
					area.value = "";
					codigo.value = "";
					$('#txtCantidad').val('');
					$('#Resultado').html(""+respuesta);
					$('#opcion').hide();
					$('#btn_mod').show();
				}
			});
		}

	} // ./
}

function NoMod(){
	$('#Resultado').html("");
	$('#opcion').hide();
	$('#btn_mod').show();
}
