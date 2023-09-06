<?php
$carga=$_GET["k"];

if($carga==0)
{
$boton= "<a href='index.php?module=13&page=11&s=<?php echo $sensor; ?>&k=1' class='mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary'>Cargar archivos</a>";   
}
if($carga==1)
{
$boton= "<a href='index.php?module=13&page=11&s=<?php echo $sensor; ?>&k=2' class='mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success'>Procesar</a>";   
}

if($carga==2)
{
$boton= "";   
}

?>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h2>Carga masiva de certificados por archivo Excel para el sensor <?php echo $mi_sensor; ?></h2>
                <div class="btn-actions-pane-right">
                    <a href="#" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary"><i class="fa-regular fa-file-excel  btn-icon-wrapper"></i> Ejemplo Excel</a>
                    <a href="index.php?module=13&page=5&s=<?php echo $sensor; ?>&k=0" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger"><i class="fa-solid fa-x  btn-icon-wrapper"></i> Cancelar</a>
                </div>
            </div>
            <div class="card-body">
                <form class="">                   
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Archivo de datos</label><input type="file" class="form-control" style="width: 50%;" required></div>
                        </div>
                        <div class="col-md-12">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Archivos PDF</label><input type="file" class="form-control" style="width: 50%;" multiple required></div>
                        </div>                        
                    <div style="text-align:center;">
                        <?php echo $boton; ?><br><br>
                    </div>
                    <?php
                    if($carga==1)
                    {
                        echo "
                        <div class='col-sm-12' style='text-align:center;'>
                            <h4>Resultados de la carga</h4>
                            <span class='text-dark'> Total de certificados a cargar:</span> <span class='text-success'><strong>5</strong></span><br> 
                            <span class='text-dark'> Error de data incompleta:</span> <span class='text-success'><strong>0</strong></span> <br>
                            <span class='text-dark'> Error fechas incongruentes:</span> <span class='text-success'><strong>0</strong></span> <br>
                            <span class='text-dark'> Error de formato de fecha:</span> <span class='text-success'><strong>0</strong></span> <br>
                            <span class='text-dark'> Lista de certificados no asociados:</span> <span class='text-success'><strong>N/A</strong></span> <br>
                        </div>";    
                    }
                    if($carga==2)
                    {
                        echo "
                        <div class='col-sm-12' style='text-align:center;'>
                            <h2 class='text-success'>Certificados cargados correctamente</h4>
                        </div>
                        ";   
                    }
                    ?>
                    <br><br>
                    <table style="width: 100%; text-align:center;" id="example" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <td><strong>No.</strong></td>
                    <td><strong>Certificado</strong></td>
                    <td><strong>Emitido el</strong></td>
                    <td><strong>Vence el</strong></td>
                    <td><strong>Estado</strong></td>
                </tr>
                </thead>
            <tbody>
            <?php
            if($carga==1 or $carga==2)
            {
            echo"
                <tr>
                    <td>1</td>
                    <td>SCL-0125</td>
                    <td>05/06/2023</td>
                    <td>05/06/2024</td>
                    <td>Vigente</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>SCL-0125</td>
                    <td>05/06/2023</td>
                    <td>05/06/2024</td>
                    <td>Vigente</td>
                </tr>            
                <tr>
                    <td>3</td>
                    <td>SCL-0125</td>
                    <td>05/06/2023</td>
                    <td>05/06/2024</td>
                    <td>Vigente</td>
                </tr>                
                <tr>
                    <td>4</td>                
                    <td>SCL-0125</td>
                    <td>05/06/2023</td>
                    <td>05/06/2024</td>
                    <td>Vigente</td>
                </tr>               
                <tr>
                    <td>5</td>
                    <td>SCL-0125</td>
                    <td>05/06/2023</td>
                    <td>05/06/2024</td>
                    <td>Vigente</td>
                </tr>                
                ";
                
            }
            ?>

            </tbody>
        </table>


                </form>
                <br><br>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>

