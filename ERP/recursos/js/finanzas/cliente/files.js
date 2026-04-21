function Up_Files(){
	var archivos = document.getElementById("archivos");//Creamos un objeto con el elemento que contiene los archivos: el campo input file, que tiene el id = 'archivos'
	var archivo = archivos.files; //Obtenemos los archivos seleccionados en el imput

	cant_fotos = archivo.length;
	valor = true;
	if ( !$('#Detalles').val() ) {
		valor = false;
		$('#Resul').html("<label class='text-danger'><span class='icon-attention'></span>Escribir un detalle u observación.</label>");
	}

	if ( valor ) {
		if(cant_fotos > 0){
			//Creamos una instancia del Objeto FormDara.
			var filarch = new FormData();
			/* Como son multiples archivos creamos un ciclo for que recorra la el arreglo de los archivos seleccionados en el input
			Este y añadimos cada elemento al formulario FormData en forma de arreglo, utilizando la variable i (autoincremental) como
			indice para cada archivo, si no hacemos esto, los valores del arreglo se sobre escriben*/
			for(i=0; i<archivo.length; i++){
				filarch.append('archivo'+i,archivo[i]); //Añadimos cada archivo a el arreglo con un indice diferente
			}


			link  = $('#txtSubcategoria').val();
			// alert(link);
			filarch.append('date',$('#date').val());
			filarch.append('Detalles',$('#Detalles').val());
			filarch.append('link',link);

			/*Ejecutamos la función ajax de jQuery*/
			$.ajax({
				url:'controlador/finanzas/cliente/pane_file_c.php', //Url a donde la enviaremos
				type:'POST', //Metodo que usaremos
				contentType:false, //Debe estar en false para que pase el objeto sin procesar
				data:filarch, //Le pasamos el objeto que creamos con los archivos
				processData:false, //Debe estar en false para que JQuery no procese los datos a enviar
				cache:false, //Para que el formulario no guarde cache
				beforeSend: function() {
					$('#Resul').html("<label class='text-success'><span class='icon-spin6 animate-spin'></span>Cargando archivos...</label>");
				},
				success:function(data) {
					$('#Resul').html(data);
					$('#archivos').val('');
					$('#Detalles').val('');
					Select_tbarchivos();
				}
			});
		}
		else{
			$('#Resul').html("<label class='text-danger'><span class='icon-cancel'></span>Seleccionar por lo menos un archivo</label>");
		}
	}

}

function QuitarFichero(id) {
	$.ajax({
		type: "POST",
		url: 'controlador/finanzas/cliente/pane_file_v.php',
		data:'opc=2&id='+id,
		beforeSend:function(){
			$('.tb_files').html(Load('Espere un momento...'));
		},
		success:function(data) {
			Select_tbarchivos();
		}
	});
}
