//ABRIR MODAL
function abrir_modal(url,div){
	//Establecer una conexion con el servidor
    // alert(url);
	var conexion = new XMLHttpRequest();
	//Envio de Datos
	conexion.onreadystatechange = function(){
		if(conexion.readyState == 4 && conexion.status == 200){
			document.getElementById(div).innerHTML = conexion.responseText;
		}
	};
	conexion.open('POST',url,true);
	conexion.send();
}
