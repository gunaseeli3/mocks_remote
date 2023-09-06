<?php
//$mi_sensor=$array_nombres[$sensor];

$table_name='sensores';
$cid=$_REQUEST['s'];

$sql="SELECT * FROM sensores WHERE id_sensor='$cid'"; 
$res=$db_cms->select_query_with_row($sql);

?>
<div class="row">
    <div class="col-sm-7">
        <div class="card">
            <div class="card-header">
                <h2>Información del sensor <?php echo $res['nombre']; ?></h2>
                <div class="btn-actions-pane-right">
                    <a href="?module=13&page=4" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary"><i class="fa-solid fa-arrow-left"></i> Volver</a>
                    <a href="index.php?module=13&page=9&s=<?=$cid?>"><button class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success"><i class="fa fa-pen"></i> Editar</button></a>
                </div>
            </div>
            <div class="card-body">
                <form class="">
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">ID</label><span class="form-control"><?php echo $res['id_sensor'];?></span></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Nombre del sensor</label><span class="form-control"><?php echo $res['nombre'];?></span></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Serie</label><span class="form-control"><?php echo $res['serie'];?></span></div>
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Tipo</label><span class="form-control"><?php echo $res['tipo'];?></span></div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">País</label><span class="form-control"><?php echo $res['pais'];?></span></div>
                        </div>
                    </div>

                    <!-- <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Estado</label><span class="form-control"><?php echo $res['estado'];?></span></div>
                        </div>
                         
                    </div> -->
                </form>
                <br><br>
                <h3>Histórico de certificados</h3>
                <div class="col-md-12">
                    <div id="accordion" class="accordion-wrapper mb-3">
                        <?php
// Fetch related certificates for the sensor
$sensor_id = $res['id_sensor'];
 $sql_certificates = "SELECT * FROM sensores_certificados WHERE id_sensor = '$sensor_id'";
$result_certificates = $db_cms->select_query($sql_certificates);
    
    if (!empty($result_certificates)) {
                            $i = 0;

                            foreach ($result_certificates as $certificate_data) {
    $ides = $certificate_data['id_certificado'];
    $certif = $certificate_data['certificado'];
    $emision = $certificate_data['fecha_emision'];
    $vencimiento = $certificate_data['fecha_vencimiento'];
    $en_sistema_desde = date('Y-m-d', strtotime($certificate_data['fecha_registro']));


    $expand = "";
    if ($i == 0) {
        $expand = "show";
    }

    echo "
    <div class='card'>
        <div id='headingOne' class='card-header'>
            <button type='button' data-toggle='collapse' data-target='#collapseOne_$ides' aria-expanded='true' aria-controls='collapseOne' class='text-left m-0 p-0 btn btn-link btn-block'>
                <h5 class='m-0 p-0'>$ides - $certif</h5>
            </button>
        </div>
        <div data-parent='#accordion' id='collapseOne_$ides' aria-labelledby='headingOne' class='collapse $expand'>
            <div class='card-body'>
                <div class='form-row' style='margin-bottom: 10px;'>
                    <div class='col-md-3'>
                        <div class='position-relative form-group'>
                            <label for='exampleEmail11'>Fecha de emisión:</label>
                            <span class='form-control' style='font-size: 14px;'>$emision</span>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='position-relative form-group'>
                            <label for='exampleEmail11'>Fecha de vencimiento:</label>
                            <span class='form-control' style='font-size: 14px;' >$vencimiento</span>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='position-relative form-group'>
                            <label for='exampleEmail11'>En sistema desde:</label>
                            <span class='form-control' style='font-size: 14px;'>$en_sistema_desde</span>
                        </div>
                    </div>
                    <div class='col-md-3'>
                        <div class='position-relative form-group'>
                            <label for='exampleEmail11'>Cargar PDF:</label>
                            <button class='mb-2 mr-2 btn-icon btn-square btn btn-primary form-control'>
                                <i class='fa-solid fa-cloud-arrow-up btn-icon-wrapper'></i>
                                <i class='fa-regular fa-file-pdf btn-icon-wrapper'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>";

    $i++;
}

                        }
                        else
                        {
                            echo"
                                <div class='card'>
                                    <div id='headingOne' class='card-header'>
                                        <button type='button' data-toggle='collapse' data-target='#collapseOne1' aria-expanded='true' aria-controls='collapseOne' class='text-left m-0 p-0 btn btn-link btn-block'>
                                            <h5 class='m-0 p-0'>Sin certificados</h5>
                                        </button>
                                    </div>
                                    <div data-parent='#accordion' id='collapseOne1' aria-labelledby='headingOne' class='collapse show'>
                                        <div class='card-body'>1. Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa

                                        </div>
                                    </div>
                                </div>";                
                        }
                        ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
        
     <div class="col-sm-5">
        <div class="card">
            <div class="card-header">
                <h2>Audit Log</h2>
            </div>
            <div class="card-body">
            </div>
        </div>
    </div>  
</div></div>
    </div>
</div>
</div>