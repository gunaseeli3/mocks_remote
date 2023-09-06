<?php
 $sensor_existid = '';
 if (isset($_REQUEST["s"]) && $_REQUEST["s"] != '') {
     $sensor_existid = $_REQUEST["s"];
     $sql3 = "SELECT * FROM sensores WHERE id_sensor='$sensor_existid'";
     $res3 = $db_cms->select_query_with_row($sql3);
 
     $mi_sensor = $res3['nombre'];
 }
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 $idcert=$_REQUEST['idcert'];
  
 $table_name = 'sensores_certificados';
 if (!empty($_POST["submit_edit_action"]) && !empty($_POST["edit_action"])) {
     $field = array();
     $pais = $_REQUEST['pais'];
 
       $sql2 = "SELECT * FROM sensores_certificados WHERE id_sensor='{$_POST['selected_sensor_id']}' AND certificado='{$_POST['certificado']}' AND id_certificado != '$idcert'";
    $res_cnt = $db_cms->count_query($sql2);
  
     if ($res_cnt > 0) {
         $_SESSION["cms_status"] = "error";
         $_SESSION["cms_msg"] = "La combinación de Sensor y certificado ya existe en la base de datos.";
         header('Location:' . $current_page);
         exit();
     }
 
     // Step 2: Check if a file was uploaded and if it's a valid PDF
     if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
         $pdfFile = $_FILES['pdf_file']['tmp_name'];
         $pdfFileType = mime_content_type($pdfFile);
 
         if ($pdfFileType === 'application/pdf') {
             // Step 3: Update the data into the database
             $_POST['id_sensor'] = $_POST['selected_sensor_id']; // hidden id
 
             $fields_to_check = array("id_sensor", "certificado", "fecha_emision", "fecha_vencimiento", "pais", "estado");
             foreach ($fields_to_check as $field_name) {
                 if (!empty($_POST[$field_name])) {
                     $field[$field_name] = $_POST[$field_name];
                 }
             }

             $sql3 = "SELECT * FROM sensores_certificados WHERE id_certificado= '$idcert'";
             $res_bf = $db_cms->select_query_with_row($sql3);
           
 
             $update_condition = " where id_certificado = '{$idcert}'";
            $res = $db_cms->update_query_new($field, $table_name, $update_condition);

 
             if ($res !== FALSE) {
                 $certificateId = $res; // Retrieve the last inserted certificate ID
 
                 // Create the folder if it doesn't exist
                 $sensorId = $_POST['selected_sensor_id'];
                 $uploadDir = dirname(__FILE__) . '/../../templates/certificados/' . $sensorId;
                 if (!file_exists($uploadDir)) {
                     if (!mkdir($uploadDir, 0777, true)) {
                         $_SESSION["cms_status"] = "error";
                         $_SESSION["cms_msg"] = "No se pudo crear la carpeta {$sensorId}.";
                         header('Location:' . $current_page);
                         exit();
                     }
                 }
 
                 $fileName = "{$certificateId}.pdf";
                 $destination = $uploadDir . '/' . $fileName;
 
                 $movefile = move_uploaded_file($pdfFile, $destination);
 
                 if ($movefile) {
                     $_SESSION["cms_status"] = "success";
 
                     // Construct the description for the backtrack record
                      
                     $user = isset($_COOKIE["myid"]) ? $_COOKIE["myid"] : "";
                     $action = "Modificó"; // Example action
                     $date_time_action = date('Y-m-d H:i:s'); // Current date and time
 
                     // Add more fields and values as needed
                     $field1 = "Sensor";
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
    $field7_value = $idcert;
    $field8 = "página";
    $field8_value = "EDITAR CERTIFICADO";
  
 
    $description = "$user ha $action el $date_time_action<br>"
    . "$field1 cambio de {$res_bf['id_sensor']} a $field1_value<br>"
    . "$field2 cambio de {$res_bf['certificado']} a $field2_value<br>"
    . "$field3 cambio de {$res_bf['fecha_emision']} a $field3_value<br>"
    . "$field4 cambio de {$res_bf['fecha_vencimiento']} a $field4_value<br>"
    . "$field5 cambio de {$res_bf['pais']} a $field5_value<br>"
    . "$field6 cambio de {$res_bf['estado']} a $field6_value<br>"
    . "$field7 cambio de {$res_bf['idcert']} a $field7_value<br>";


$description_base64 = base64_encode($description);
  
$backtrack_data = array(
    'fecha' => $date_time_action,
    'persona' => $user,
    'movimiento' => $action,
    'modulo' => "Metrologia",
    'descripcion' => $description_base64
);
                    
       
    $res_backtrack = $db_cms->add_query($backtrack_data, 'backtrack');

                     if (!empty($_POST["edit_action"])) {
                         $_SESSION["cms_msg"] = "Datos modificados con éxito";
                     } else {
                         header('Location:' . $current_page);
                         exit();
                     }
                 } else {
                     $_SESSION["cms_status"] = "error";
                     $_SESSION["cms_msg"] = "No se pudo cargar el pdf.";
                 }
             } else {
                 $_SESSION["cms_status"] = "error";
                 $_SESSION["cms_msg"] = "No se pudo insertar el registro en la base de datos.";
             }
         } else {
             $_SESSION["cms_status"] = "error";
             $_SESSION["cms_msg"] = "Tipo de archivo invalido. Solo se permiten archivos PDF.";
         }
     } else {
         $_SESSION["cms_status"] = "error";
         $_SESSION["cms_msg"] = "No se cargó ningún archivo o se produjo un error durante la carga.";
     }
 }
 

 $sql="SELECT * FROM sensores_certificados WHERE id_certificado='$idcert'"; 
 $res=$db_cms->select_query_with_row($sql);

require_once 'includes/sensorlists.php';

?>
   <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <!-- <h4>Editar certificado DEL sensor <?php echo $mi_sensor; ?></h4> -->
                <h4>Editar certificado</h4>
                <div class="btn-actions-pane-right">
                
                    <a href="index.php?module=13&page=4&s=-" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger"><i class="fa-solid fa-x"></i> Cancelar</a>
                    <a href="index.php?module=13&page=11&s=<?php echo $sensor; ?>&k=0" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary"><i class="fa-solid fa-cloud-arrow-up btn-icon-wrapper"></i> Carga masiva</a>
                   

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
?>

                        </div>
            <form method="post" enctype="multipart/form-data" id="form2" name="form2">
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
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Nombre del certificado</label><input type="text" class="form-control" name="certificado" required value="<?php echo $res['certificado'];?>"></div>
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
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="File" class="">Archivo del certificado</label>
                            <input type="file" class="form-control" name="pdf_file" id="pdf_file" <?php if($res['primary_url']!='' && $res['sec_url'] != '' && $res['exp_url'] != '') { ?>required<?php } ?>>

                            <input type="hidden" name="existprimaryfile" id="existprimaryfile" value="<?php echo $res['primary_url'];?>"/>
                            <input type="hidden" name="existsecondaryfile" id="existsecondaryfile" value="<?php echo $res['sec_url'];?>"/>
                            <input type="hidden" name="existexpfile" id="existexpfile" value="<?php echo $res['exp_url'];?>"/>
                           
                            <label>
                            <input type="radio" name="certificate_type" <?php if ($res['primary_url'] != '') { echo 'checked'; } ?> value="Primario"> Primario
            </label>
            <label>

            <input type="radio" name="certificate_type" <?php if ($res['sec_url'] != '') { echo 'checked'; } ?> value="Secundario"> Secundario
 
            </label>
            <label>
            <input type="radio" name="certificate_type" <?php if ($res['exp_url'] != '') { echo 'checked'; } ?> value="Históricorio"> Históricorio
 
            </label><br>

                            <?php if ($res['primary_url'] != '') { ?>
    <a href="<?php echo $res['primary_url']; ?>" target="_blank">
    <i class="far pe-7s-copy-file"></i> Primario PDF
</a>
<?php } ?>
<?php if ($res['sec_url'] != '') { ?>
    <a href="<?php echo $res['sec_url']; ?>" target="_blank">
    <i class="far pe-7s-copy-file"></i> Secundario PDF
</a>
<?php } ?>

<?php if ($res['exp_url'] != '') { ?>
    <a href="<?php echo $res['exp_url']; ?>" target="_blank">
    <i class="far pe-7s-copy-file"></i> Históricorio PDF
</a>
    
<?php } ?>

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
<!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script>
    $(document).ready(function () {
        var sensorData = <?php echo json_encode($sensor_data); ?>;
        var sensorList = $("#sensor_list");

       


         // Populate the dropdown with sensor data
         sensorData.forEach(function (sensor) {
            var option = $("<option>").attr("data-value", sensor.id_sensor).text(sensor.nombre);
            sensorList.append(option);
        });


        $("#sensor_dropdown").on("input", function () {
            var searchText = $(this).val().toLowerCase();
            sensorList.find("option").each(function () {
                var optionText = $(this).text().toLowerCase();
                // Use 'nombre' for searching, but keep the value as 'id_sensor'
                $(this).toggle(optionText.indexOf(searchText) > -1);
            });
        });

        // Handle selection change to update the hidden field
        $("#sensor_dropdown").change(function () {
            var selectedValue = $(this).val();
            var selectedIdSensor = sensorList.find("option:contains('" + selectedValue + "')").attr("data-value");
            $("#selected_sensor_id").val(selectedIdSensor);
        });

         
       
});

$(document).ready(function() {
    // Show confirmation modal when file input changes
    $('input[type="file"]').on('change', function() {
        var $fileInput = $(this);
        var $certificateType = $('input[name="certificate_type"]:checked');
       
        if ($fileInput.val() !== '' && $certificateType.length) {
            $('#confirmationModal').modal('show');
        }
    });
    
    // Handle user's choice in confirmation modal
    $('#confirmUploadButton').click(function() {
    // Handle the user's choice here (primary, secondary, historical)
    var fileType = $('input[name="certificate_type"]:checked').val();    
    if (fileType === 'Primario') {       
            alert('Certificado primario elegido.');
        } 
        else if (fileType === 'Secundario') {
        // Secondary Certificate chosen, prevent form submission
        alert('Certificado secundario elegido. El certificado existente sigue siendo principal.');
    } else if (fileType === 'Históricorio') {
        // Historical Certificate chosen, prevent form submission
        alert('Certificado Histórico elegido. El certificado se almacenará como histórico.');
    } else {
        //$fileInput.val('');
    }
   // $('#confirmationModal').modal('hide'); // Hide the modal
    $('#form2').submit(); // Allow form submission
});

// Reset the file input value when modal is closed
$('#confirmationModal').on('hidden.bs.modal', function () {
    $('input[type="file"]').val('');
});

});

 

 
    $('#form2').submit(function(e) {
        
        var $fileInput = $('input[type="file"]');
        var $certificateType = $('input[name="certificate_type"]:checked');
        var $existPrimaryFile = $('#existprimaryfile');
        var $existSecondaryFile = $('#existsecondaryfile');
        var $existExpFile = $('#existexpfile');
        
        
            var fileType = $certificateType.val();
            var isValid = true;
            
            if (fileType === 'Primario' && $existPrimaryFile.val() === '') {
                // Update modal content for primary alert
                $('#alertModalLabel').text('Alerta - Certificado Primario');
                $('#alertModal .modal-body').text('Por favor, suba el archivo del certificado primario.');
                $('#alertModal').modal('show');
                isValid = false;
            } else if (fileType === 'Secundario' && $existSecondaryFile.val() === '') {
                // Update modal content for secondary alert
                $('#alertModalLabel').text('Alerta - Certificado Secundario');
                $('#alertModal .modal-body').text('Por favor, suba el archivo del certificado secundario.');
                $('#alertModal').modal('show');
                isValid = false;
            } else if (fileType === 'Históricorio' && $existExpFile.val() === '') {
                // Update modal content for historical alert
                $('#alertModalLabel').text('Alerta - Certificado Histórico');
                $('#alertModal .modal-body').text('Por favor, suba el archivo del certificado histórico.');
                $('#alertModal').modal('show');
                isValid = false;
            }
           
            if (!isValid) {
                e.preventDefault(); // Prevent form submission
            }
         
    });
 


 
</script>

<!-- Add this modal HTML at the end of your body tag -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Confirmar carga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            Estás seguro que quieres crear este 
                <span id="certificateType"></span>
                certificado?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                <button type="button " class="btn btn-success" id="confirmUploadButton">Confirm</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="alertModal" tabindex="-1" role="dialog" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alertModalLabel">Alerta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- This is where the dynamic alert message will be displayed -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

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

 
 

   
