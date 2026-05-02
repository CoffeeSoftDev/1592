    <div class="form-group row">

        <label class="col-sm-3 col-xs-12 col-form-label" id="Titulo">
        <h6> <i class="icon-chart"></i> ANALISIS DE INGRESOS </h6>
        </label>

        <!-- <label class="col-sm-1  text-right col-form-label"> DE: </label> -->

        <div class="col-sm-2">
            <div id="cbAnio1">

                <select class="form-control input-sm" id="txtAnio1">
                    <option value="0">Selecciona un año</option>
                </select>

            </div>
        </div>

        <div class="col-sm-2 ">
            <div id="cbAnio2">

                <select class="form-control input-sm" id="txtAnio2">
                    <option value="0">Selecciona un año</option>
                </select>
            </div>
        </div>

        <div class="col-sm-3">

            <select class="form-control input-sm" id="tipoReporte">
                <option value="0">ANUAL</option>
                <option value="1">COMPARAR TOTALES</option>
                <option value="2">GRAFICA TOTAL </option>
                <option value="3">COMPARACIÓN POR AREAS</option>
                <option value="4">TOTAL POR AREAS</option>
                <option value="5">GRAFICA POR AREAS</option>
            </select>

        </div>


        <div class="col-sm-2">
               <a class="btn btn-primary btn-xs" style="width:100%" onclick="verFormato()">
                <span class="icon-search-1"></span> Consultar</a>

            <!-- <a class="btn btn-primary btn-xs" style="width:100%" onclick="verFormato()">
                <span class="fa fa-search"></span> Busqueda</a> -->
        </div>

    </div>


    <div class="row">
        <div class="col-md-12 col-xs-12" id="ViewTablero">

        </div>
        <div class="col-md-12 col-xs-12" id="ViewPanel">
            <center>
                <br>
                <br>
                <br>
                <!-- <img src="recursos/img/logo_c.png" style=" max-width:30%; " class="img-res"> -->
            </center>
        </div>
    </div>