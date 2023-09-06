<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                Gestion de sensores metrologia
            </div>
            <div class="card-body">
                <!--Aqui empiezan las tarjetas para ver los equipos-->
                <div class="row" style="text-align: center;" id="Targeta_principal">
                    <div class="col-sm-12">
                        <!--<label for="" class="text-primary">Aqui se listaran los equipos</label>-->
                        <input type="text" id="aqui_sensor" class="form-control" placeholder="Busque aqui su equipo" style="width: 50%;">
                        <br>
                        <img src="design/images/delete_loader.gif" id="img_carga_equipos_metrologia" style="width: 13%;margin-left: 0%;">
                        <table class="table" id="tabla_equipos_metrologia" >
                            <thead>
                                <th>Nombre equipo</th>
                                <th>Estado</th>
                                <th colspan="2">Acciones</th>
                            </thead>
                            <tbody id="listar_equipos_metrologia">

                            </tbody>
                        </table>
                    </div>
                    <!--
                    <div class="col-sm-6">
                        <label for="" class="text-primary">Aqui se mostraran los certificados del equipo seleccionado y los comentarios</label>
                        <table class="table">
                            <thead>
                                <th>Certificado</th>
                                <th>Calibración</th>
                                <th>Vencimiento</th>
                            </thead>
                            <tbody id="aqui_certificados_sensores">

                            </tbody>
                        </table>
                    </div>-->
                </div>
                <div id="Targeta_configuracion">
                    <button class="btn btn-danger" id="atras_sensores">X</button>
                    <form id="formulario_sensores">
                        <input type="hidden" name="movimiento" value="guardar_configuracion">
                        <div class="row" style="text-align: center;">
                            <div class="col-sm-12">
                                <label for="" class="text-primary">Configuración del sensor: </label><label for="" id="aqui_nombre_sensor"></label>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-4">
                                <label for="">Identificación sensor</label>
                                <input type="text" name="name_sensor" id="name_sensor" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                <label for="">Serie sensor</label>
                                <input type="text" name="serie_sensor" id="serie_sensor" class="form-control">
                            </div>
                            <div class="col-sm-4">
                                <label for="">Tipo sensor</label>
                                <input type="text" name="tipo_sensor" id="tipo_sensor" class="form-control">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table">
                                    <thead>
                                        <th>Certificado</th>
                                        <th>Calibración</th>
                                        <th>Vencimiento</th>
                                        <th>Pais</th>
                                        <th>Estado Certificado</th>
                                    </thead>
                                    <tbody id="aqui_certificados_sensores">
        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-success">Guardar</button>
                    </form>
                            </div>
                        </div>
                       
                </div>    
            </div>
        </div>
    </div>
</div>
<script src="design/js/script_metrologia.js"></script>