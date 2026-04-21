url_file = 'controlador/direccion/';


function ingresos_anuales() {
    txtAnio1 = $('#txtAnio1').val();
    txtAnio2 = $('#txtAnio2').val();
   
    $.ajax({
        type: "POST",
        url: url_file + "verMesxMes2.php",
        data: "anio1=" + txtAnio1 + "&anio2=" + txtAnio2,
        beforeSend: function () {
            $('#ViewPanel').html('<br><br><center><i class="fa fa-spinner fa-pulse fa-3x fa-fw text-success"></i>' +
                '<h4><span>Cargando datos...</span></h4></center>');
        },
        success: function (rp) {
            data = eval(rp);
        
            $('#ViewPanel').html(data[0]);
        }
    });

}