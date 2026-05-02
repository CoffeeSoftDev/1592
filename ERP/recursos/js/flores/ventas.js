

function VerHistorialTicket(fecha_i, fecha_f) {
	$.ajax({
		type: "POST",
		url: "controlador/supervision/operacion.php",
		data: "opc=1&f_i=" + fecha_i + "&f_f=" + fecha_f,

		beforeSend: function(rp) {
			$("#tbProduccion").html(Load());
		},

		success: function(rp) {
			data = eval(rp);

			$("#tbHistorial").html(data[0]);
			simple_data_table("#viewFolios");

			$("#txtTicketHoy").datetimepicker({
				format: "YYYY-MM-DD",
				defaultDate: new Date()
			});
			// $("#txtTicketHoy").on("dp.change", function (e) {
			//  ViewFormatos();
			// });
		}
	});
}
