<?php
 $sensor_existid = '';
 if (isset($_REQUEST["s"]) && $_REQUEST["s"] != '') {
     $sensor_existid = $_REQUEST["s"];
     $sql3 = "SELECT * FROM sensores WHERE id_sensor='$sensor_existid'";
     $res3 = $db_cms->select_query_with_row($sql3);
 
     $mi_sensor = $res3['nombre'];
 }
 error_reporting(E_ALL);
 //ini_set('display_errors', 1);
 $idcert=$_REQUEST['idcert'];
 $certificateId= $idcert;

function renameExistingFile($existingFilePath, $newFilePath) {
    return rename($existingFilePath, $newFilePath);
}

function updateDatabaseAndBacktrack($certificateId, $sensorId, $fileTypeSuffix) {
    global $db_cms;
    // Update the database and create a backtrack record
    $table_name = 'sensores_certificados';
    $field = array();
    $fields_to_check = array("id_sensor", "certificado", "fecha_emision", "fecha_vencimiento", "pais", "estado");



    foreach ($fields_to_check as $field_name) {
        if (!empty($_POST[$field_name])) {
            $field[$field_name] = $_POST[$field_name];
        }
    }

    $field['id_sensor'] = $sensorId;
    $sql3 = "SELECT * FROM sensores_certificados WHERE id_certificado = '$certificateId'";
    $res_bf = $db_cms->select_query_with_row($sql3);


    // Update the database with new file information
    $fieldToUpdate = "primary_url";
    $fileType = $_POST['certificate_type'];
    if ($fileTypeSuffix === '_2') {
        $fieldToUpdate = "sec_url";
    } elseif ($fileTypeSuffix === '_3') {
        $fieldToUpdate = "exp_url";
    }

    //$field[$fieldToUpdate] =  "templates/certificados/{$sensorId}/{$certificateId}{$fileTypeSuffix}.pdf";


    $update_condition = " WHERE id_certificado = '{$certificateId}'";
   
    $res = $db_cms->update_query_new($field, $table_name, $update_condition);
    //echo "<pre>"; print_r($field); echo "</pre>";
    if ($res !== FALSE) {
        // Construct the description for the backtrack record
        $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
        $action = "Modificó"; // Example action
        $date_time_action = date('Y-m-d H:i:s'); // Current date and time

        // Add more fields and values as needed
        $field1 = "Sensor ID";
        $field1_value = $_POST['id_sensor'];
        $field2 = "Nombre del certificado";
        $field2_value = $_POST['certificado'];
        $field3 = "Fecha de calibración";
        $field3_value = $_POST['fecha_emision'];
        $field4 = "Fecha de vencimiento";
        $field4_value = $_POST['fecha_vencimiento'];
        $field5 = "País de emisión";
        $field5_value = $_POST['pais'];
        $field6 = "Estado";
        $field6_value = $_POST['estado'];
        $field7 = "ID";
        $field7_value = $certificateId;
        $field8 = "página";
        $field8_value = "EDITAR CERTIFICADO";
        $url = "templates/certificados/{$field1_value}/{$field2_value}.pdf";

        $description = "$user ha $action el $date_time_action<br>"
            . "$field1 cambio de {$res_bf['id_sensor']} a $field1_value<br>"
            . "$field2 cambio de {$res_bf['certificado']} a $field2_value<br>"
            . "$field3 cambio de {$res_bf['fecha_emision']} a $field3_value<br>"
            . "$field4 cambio de {$res_bf['fecha_vencimiento']} a $field4_value<br>"
            . "$field5 cambio de {$res_bf['pais']} a $field5_value<br>"
            . "$field6 cambio de {$res_bf['estado']} a $field6_value<br>"
            . "$field7 cambio de {$res_bf['id_certificado']} a $field7_value<br>"
            . "URL - $url<br>"
            . "$field8 - $field8_value<br>";

        $description_base64 = base64_encode($description);

        $backtrack_data = array(
            'fecha' => $date_time_action,
            'persona' => $_COOKIE['myid'],
            'movimiento' => $action,
            'modulo' => "Metrologia",
            'descripcion' => $description_base64
        );

        $res_backtrack = $db_cms->add_query($backtrack_data, 'backtrack');

        if (!empty($_POST["edit_action"])) {
            $_SESSION["cms_status"] = "sucess";
            $_SESSION["cms_msg"] = "Datos modificados con éxito";
        } else {
            header('Location:' . $current_page);
            exit();
        }
    } else {
        $_SESSION["cms_status"] = "error";
        $_SESSION["cms_msg"] = "No se pudo insertar el registro en la base de datos.";
    }
}
$table_name = 'sensores_certificados';
if (!empty($_POST["edit_action"])) {
    $field = array();
    $pais = $_REQUEST['pais'];

    $sensorId = $_POST['selected_sensor_id'];

    $certificateName = $_POST['certificado'];
    $tipo = $_POST['tipo'];

    $sql2 = "SELECT * FROM sensores_certificados WHERE id_sensor='{$_POST['selected_sensor_id']}' AND certificado='{$_POST['certificado']}' AND id_certificado != '$idcert'";
    $res_cnt = $db_cms->count_query($sql2);

    if ($res_cnt > 0) {
        $_SESSION["cms_status"] = "error";
        $_SESSION["cms_msg"] = "La combinación de Sensor y certificado ya existe en la base de datos.";
        header('Location:' . $current_page);
        exit();
    }

    // First, check if pdf_file input data is selected
    $table_name = 'sensores_certificados';
    $field = array();
    $fields_to_check = array("id_sensor", "certificado", "fecha_emision", "fecha_vencimiento", "pais", "estado");

    foreach ($fields_to_check as $field_name) {
        if (!empty($_POST[$field_name])) {
            $field[$field_name] = $_POST[$field_name];
        }
    }
    $field['id_sensor'] = $sensorId;
    $update_condition = " WHERE id_certificado = '{$certificateId}'";
     $res = $db_cms->update_query_new($field, $table_name, $update_condition);  

    $originalCertificateName = $_POST['certificado_bk'];
    $certificateNameChanged = ($_POST['certificado'] !== $originalCertificateName);


    // Handle multiple uploaded files
    $pdfFiles = $_FILES['pdf_file']['tmp_name'];
    $fileCount = count($pdfFiles);

    // Initialize an array to store the URLs of the moved files
    $movedFileUrls = array();

    $uploadDir = dirname(__FILE__) . '/../../templates/certificados/' . $sensorId;
    if (!file_exists($uploadDir)) {
        if (!mkdir($uploadDir, 0777, true)) {
            $_SESSION["cms_status"] = "error";
            $_SESSION["cms_msg"] = "No se pudo crear la carpeta {$sensorId}.";
            header('Location:' . $current_page);
            exit();
        }
    }

     // Fetch existing files for a specific certificate from the database
     $sql_existing_files = "SELECT * FROM sensores_certicados_ficheros WHERE id_certificado = '$certificateId' and tipo='$tipo'";
     $existing_files = $db_cms->select_query($sql_existing_files);

    // Check if a new PDF file is being uploaded for a primary certificate
if ($_POST['tipo'] === 'Primario' && $fileCount > 0) {
    // Check if there's an existing primary PDF
    $existingPrimaryPdf = null;
    foreach ($existing_files as $existing_file) {
        if ($existing_file['tipo'] === 'Primario') {
            $existingPrimaryPdf = $existing_file;
            break;
        }
    }

    // If an existing primary PDF is found, rename it to secondary and update filenames and database
    if ($existingPrimaryPdf) {
        $existingPrimaryFileName = $existingPrimaryPdf['nombre_archivo'];
        $newSecondaryFileName = str_replace('.pdf', '_2.pdf', $existingPrimaryFileName);

        // Rename the file in the file system
        $oldFilePath = $uploadDir . '/' . $existingPrimaryFileName;
        $newFilePath = $uploadDir . '/' . $newSecondaryFileName;

        if (rename($oldFilePath, $newFilePath)) {
            // Update the filename in the database
            $update_sql = "UPDATE sensores_certicados_ficheros SET nombre_archivo = '$newSecondaryFileName', tipo = 'Secundario' WHERE id = '{$existingPrimaryPdf['id']}'";
            $db_cms->execute_query($update_sql);
        }
    }

    // Rename existing "_2" files to "_3"
    foreach ($existing_files as $existing_file) {
        $existingFileName = $existing_file['nombre_archivo'];
        $tipo = $existing_file['tipo'];

        if ($tipo === 'Secundario' && strpos($existingFileName, '_2') !== false) {
            // Rename the file in the file system
            $newFileName = str_replace('_2', '_3', $existingFileName);
            $oldFilePath = $uploadDir . '/' . $existingFileName;
            $newFilePath = $uploadDir . '/' . $newFileName;

            if (rename($oldFilePath, $newFilePath)) {
                // Update the filename in the database
                $update_sql = "UPDATE sensores_certicados_ficheros SET nombre_archivo = '$newFileName' WHERE id = '{$existing_file['id']}'";
                $db_cms->execute_query($update_sql);
            }
        }
    }
}

    // Check if the certificate name is changed
    if ($certificateNameChanged) {
        // Rename all associated PDF files based on the new certificate name
        foreach ($existing_files as $existing_file) {
            $existingFileName = $existing_file['nombre_archivo'];
            $tipo = $existing_file['tipo'];

            // Determine the new filename based on tipo and index
            $index = ($tipo === 'Primario') ? "" : "_" . (intval($existing_file['id']) + 2);
            $newFileName = "{$certificateName}{$index}.pdf";

            // Rename the file in the file system
            $oldFilePath = $uploadDir . '/' . $existingFileName;
            $newFilePath = $uploadDir . '/' . $newFileName;

            if (rename($oldFilePath, $newFilePath)) {
                // Update the filename in the database
                $update_sql = "UPDATE sensores_certicados_ficheros SET nombre_archivo = '$newFileName' WHERE id = '{$existing_file['id']}'";
                $db_cms->update_query($update_sql);
            }
        }
    }

    for ($i = 0; $i < $fileCount; $i++) {
        $index = ($i > 0) ? "_$i" : ""; // Use index for Secundario and histórico files

        // Determine the tipo based on the selected radio button
        $tipo = $_POST['tipo'];

        // Determine the filename based on tipo
        if ($tipo === 'Primario') {
            $fileName = "{$_POST['certificado']}.pdf";
        } elseif ($tipo === 'Secundario' || $tipo === 'histórico') {
            $fileName = "{$_POST['certificado']}{$index}.pdf";
        }

        $destination = $uploadDir . '/' . $fileName;

        $movefileResult = move_uploaded_file($pdfFiles[$i], $destination);

        // Store the URL of the moved file in the array
        if ($movefileResult) {
            $movedFileUrls[] = $destination;
        }
    }

   

    // Determine the next index for histórico files
    $sql_next_historico_index = "SELECT MAX(SUBSTRING_INDEX(SUBSTRING_INDEX(nombre_archivo, '_', -1), '.', 1)) AS max_index FROM sensores_certicados_ficheros WHERE tipo = 'histórico'";
    $result_next_historico_index = $db_cms->select_query($sql_next_historico_index);
    $next_historico_index = intval($result_next_historico_index[0]['max_index']) + 1;

    foreach ($existing_files as $existing_file) {
        $existingFileName = $existing_file['nombre_archivo'];
        $tipo = $existing_file['tipo'];
        $index = ""; // Default for Primario and histórico

        if ($tipo === 'Secundario') {
            $index = "_" . (intval($existing_file['id']) + 2); // Increment index for Secundario
        } elseif ($tipo === 'histórico') {
            $index = "_$next_historico_index"; // Use next index for histórico
            $next_historico_index++;
        }

        $newFileName = str_replace(".pdf", "{$index}.pdf", $existingFileName);

        // Rename the file in the file system
        $oldFilePath = $uploadDir . '/' . $existingFileName;
        $newFilePath = $uploadDir . '/' . $newFileName;

        if (rename($oldFilePath, $newFilePath)) {
            // Update the filename in the database
            $update_sql = "UPDATE sensores_certicados_ficheros SET nombre_archivo = '$newFileName' WHERE id = '{$existing_file['id']}'";
            $db_cms->update_query($update_sql);
        }
    }

}



 $sql="SELECT * FROM sensores_certificados WHERE id_certificado='$idcert'"; 
 $res=$db_cms->select_query_with_row($sql);

require_once 'includes/sensorlists.php';

?>
   <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                  <h4>CONFIGURACION DEL SENSOR <?php echo $mi_sensor; ?></h4>  
                 
                <div class="btn-actions-pane-right">
                
                    <a href="index.php?module=13&page=4&s=-" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger"><i class="fa-solid fa-x"></i> Cancelar</a>
                    <a href="index.php?module=13&page=11&s=<?php echo $sensor_existid; ?>&k=0" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary"><i class="fa-solid fa-cloud-arrow-up btn-icon-wrapper"></i> Carga masiva</a>
                   

                </div>
            </div>
            <div class="card-body">
            <div class="err">
            <?php
if (!empty($_SESSION["cms_status"]) && !empty($_SESSION["cms_msg"])) {
    $statusClass = ($_SESSION["cms_status"] === 'error') ? 'error' : 'success';
    ?>
    <div class="status_msg_<?php echo $statusClass; ?>">
        <?php
        echo $_SESSION["cms_msg"];
        ?>
    </div>
    <?php
    unset($_SESSION["cms_status"]);
    unset($_SESSION["cms_msg"]);
}

$baseurl = getBaseURL();
    $pdfPath = "templates/certificados/{$sensor_existid}/{$res['certificado']}.pdf";
            $pdfURL = $baseurl. '/' . $pdfPath;
?>

                        </div>
            <form method="post" enctype="multipart/form-data" id="form2" name="form2" onsubmit="return validateForm()">
                <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-8">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Nombre del Sensor</label>
                            <input type="hidden" id="selected_sensor_id" name="selected_sensor_id" value="<?php echo $sensor_existid?>">
                            <input  type="text" id="sensor_dropdown" name="id_sensor" list="sensor_list" placeholder="Search for a sensor" required class="form-control" value="<?php echo $mi_sensor?>">
                            <datalist id="sensor_list"></datalist>

                        </div>
                        </div>                        
                    </div>
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="exampleEmail11" class="">Nombre del certificado</label>
                                <input type="text" class="form-control" name="certificado" id="certificado" required value="<?php echo $res['certificado'];?>">
                                <input type="hidden" class="form-control" name="certificado_bk" id="certificado_bk"  value="<?php echo $res['certificado'];?>"></div>
                        
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Fecha de calibración</label><input type="date" class="form-control" name="fecha_emision"  value="<?php echo $res['fecha_emision'];?>"   required>
</div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="examplePassword11" class="">Fecha de vencimiento</label><input type="date" class="form-control" name="fecha_vencimiento"  value="<?php echo $res['fecha_vencimiento'];?>"   required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">País de emisión</label><input type="text" class="form-control" name="pais" value="<?php echo $res['pais'];?>"   required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="examplePassword11" class="" >Estado</label>
                                <select class="form-control" name="estado" required>
                                    <option value="Vigente">Vigente</option>
                                    <option value="Vencido">Vencido</option>
                                </select>
                            </div>
                        </div>
<?php
           

            
               $existurl="templates/certificados/{$sensor_existid}/{$res['certificado']}.pdf";

              ?>
            

                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="File" class="">Archivo del certificado</label>
 
                            <input type="file" class="form-control" name="pdf_file[]" id="pdf_file" multiple accept=".pdf" <?php if(!file_exists($existurl)) { echo 'required'; } ?>  >
                            <div  >
    <p>Choose the type of certificate:</p>
    <input type="radio" name="tipo" value="primario" > Primario
    <input type="radio" name="tipo" value="secundario"> Secundario
    <input type="radio" name="tipo" value="histórico"> histórico
    
</div>
                            
                           
            <br>
            <?php

 
$sql_pdf_files = "SELECT * FROM sensores_certicados_ficheros WHERE id_certificado = '$idcert'";
$result_pdf_files = $db_cms->select_query($sql_pdf_files);


if (!empty($result_pdf_files)) {$n=1;
    foreach ($result_pdf_files as $pdf_file) { 
        $pdfFileName = $pdf_file['nombre_archivo'];
        $pdfURL = "templates/certificados/{$pdf_file['id_sensor']}/$pdfFileName";

?>
            <a href="<?php echo $pdfURL; ?>" target="_blank" style="margin-bottom:5px>
    <i class="fa fa-filee"></i> Link Certificado<?=$n?>
</a><br>

<?php $n++;} } ?>

                            

 

                        </div>
                    </div>
                    <div style="text-align:center;    margin: auto;">
                    <input type="hidden" name="edit_action" value="1"/>
<input type="hidden" name="idcert" value="<?= $idcert ?>"/>

<button type="submit" name="submit_edit_action" value="Actualizar" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">Actualizar</button>

                    
                    </div>
                </form>
                <br><br>
                </div>
            </div>
            
        </div>
        </div>
    </div>
    
</div>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script>
function validateForm() {
    var tipoGroup = document.getElementById("tipoGroup");
    var tipoInputs = tipoGroup.getElementsByTagName("input");
    
    for (var i = 0; i < tipoInputs.length; i++) {
        if (tipoInputs[i].type === "radio" && tipoInputs[i].checked) {
            return true; // At least one option is selected, allow form submission
        }
    }
    
    alert("Please choose a certificate type.");
    return false; // Prevent form submission
}


 $(document).ready(function() {
    $('#form2').submit(function(e) {
        var certificateName = $('#certificado').val();
        var originalCertificateName = $('#certificado_bk').val();
        var pdfFiles = $('#pdf_file')[0].files;
        
        // Check if certificate name is changed
        var certificateNameChanged = (certificateName !== originalCertificateName);
        
        // Check if PDF file is uploaded
        var pdfFileUploaded = (pdfFiles.length > 0);
        
        if (certificateNameChanged || pdfFileUploaded) {
            // If certificate name changed or PDF uploaded, handle the logic
            
            if (certificateNameChanged && !pdfFileUploaded) {
                // Certificate name changed, enforce PDF upload
                alert("Cargue el nuevo archivo PDF para el certificado.");
                return false;
            }  
            
        }
        if (!$('input[name="tipo"]:checked').length) {
                        alert("Seleccione el tipo de certificado.");
                        return false;
                    }
                    return true;
    });
});
</script>



<!-- Similar structure for secondary and historical alerts -->


<style>
 

.modal-backdrop.show, .show.blockOverlay, .modal-backdrop, .blockOverlay {   
    position: inherit;
}
.modal {
     
    top: 105px;
    padding-top: 15px;
}

 
  </style>

 
 

   
