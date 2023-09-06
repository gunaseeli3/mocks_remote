<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">Generación de certificados de calibración</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="">Buscar sensor</label>
                        <input type="text" name="" id="buscador_sensores" class="form-control">
                    </div>
                </div>
                <br>
                <div class="row" style="text-align: center;">
                    <div class="col-sm-6">
                        <table class="table">
                            <thead>
                                <th>Nombre sensor</th>
                                <th>Configuración</th>
                            </thead>
                            <tbody id="aqui_sensores_certificados">

                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Sensor a certificar: </label><label id="aqui_sensor_a_certificar"></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="">Cantidad de lecturas:</label>
                                <input type="number" name="" id="cantidad_lecturas" class="form-control">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="">Temperatura:</label>
                                <select name="" id="temperatura_seleccion" class="form-control">
                                    <option value="0">Seleccione...</option>
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="">Humedad:</label>
                                <select name="" id="Humedad_seleccion" class="form-control">
                                    <option value="0">Seleccione...</option>
                                    <option value="Si">Si</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-primary" id="configurar_certificado">Configurar</button>
                            </div>
                        </div>
                    </div>
                </div>


                <hr>

                <div class="row">
                    <div class="col-sm-6" id="aqui_mostrar_temp" style="text-align: center;">

                    </div>

                    <div class="col-sm-6" id="aqui_mostrar_hume" style="text-align: center;">

                    </div>
                    
                </div>

                <hr>

                <div class="row">
                    <button class="btn btn-success">Generar Certificado</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="design/js/script_metrologia.js"></script>