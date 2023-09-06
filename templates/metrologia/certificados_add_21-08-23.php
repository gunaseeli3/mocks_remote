<?php
 $sensor_existid = '';
 if (isset($_REQUEST["s"]) && $_REQUEST["s"] != '') {
     $sensor_existid = $_REQUEST["s"];
     $sql3 = "SELECT * FROM sensores WHERE id_sensor='$sensor_existid'";
     $res3 = $db_cms->select_query_with_row($sql3);
 
     $mi_sensor = $res3['nombre'];
 }

 // error_reporting(E_ALL);
 //ini_set('display_errors', 1);
  
 $table_name = 'sensores_certificados';
 if ( !empty($_POST["add_action"])) {
     $field = array();
     $pais = $_REQUEST['pais'];
 
     $sql2 = "SELECT * FROM sensores_certificados WHERE id_sensor='{$_POST['selected_sensor_id']}' AND certificado='{$_POST['certificado']}'";
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
             // Step 3: Insert the data into the database
             $_POST['id_sensor'] = $_POST['selected_sensor_id']; // hidden id
 
             $fields_to_check = array("id_sensor", "certificado", "fecha_emision", "fecha_vencimiento", "pais", "estado");
             foreach ($fields_to_check as $field_name) {
                 if (!empty($_POST[$field_name])) {
                     $field[$field_name] = $_POST[$field_name];
                 }
             }
 
             $res = $db_cms->add_query1($field, $table_name);
 
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
 
                 $fileName = "{$_POST['certificado']}.pdf";
                 $destination = $uploadDir . '/' . $fileName;
 
                 $movefile = move_uploaded_file($pdfFile, $destination);
 
                 if ($movefile) {
                     $_SESSION["cms_status"] = "success";

                     $url = "templates/certificados/{$sensorId}/{$certificateId}.pdf";
                     //$update_sql = "UPDATE $table_name SET primary_url = '$url' WHERE id_certificado = ' $certificateId'";
                     //$udpatequery=$db_cms->update_query($update_sql);

 
                     // Construct the description for the backtrack record
                      
                     $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
                     $action = "Creó"; // Example action
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
                     $field7_value = $certificateId;
                     $field7 = "página";
                     $field7_value = "AÑADIR CERTIFICADO";
                     
  
 
                     $description = "$user has $action on $date_time_action <br>"
                         . "$field1 - $field1_value<br>"
                         . "$field2 - $field2_value<br>"
                         . "$field3 - $field3_value<br>"
                         . "$field4 - $field4_value<br>"
                         . "$field5 - $field5_value<br>"
                         . "$field6 - $field6_value<br>"
                         . "Tipo de archivo - Primario<br>"
                         . "URL - $url<br>";

                      
                     $description_base64 = base64_encode($description);
  
                    $backtrack_data = array(
                        'fecha' => $date_time_action,
                        'persona' => $_COOKIE['myid'],
                        'movimiento' => $action,
                        'modulo' => "Metrologia",
                        'descripcion' => $description_base64
                    );
                    
                    
                    $res_backtrack = $db_cms->add_query($backtrack_data,'backtrack');

                     if (!empty($_POST["add_action"])) {
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
 


require_once 'includes/sensorlists.php';

?>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4>Añadir certificado al sensor  <?php echo $mi_sensor; ?></h4>
                <div class="btn-actions-pane-right">
                
                   
                    <a href="index.php?module=13&page=11&s=<?php echo $_REQUEST["s"]; ?>&k=0" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary"><i class="fa-solid fa-cloud-arrow-up btn-icon-wrapper"></i> Carga masiva</a>
  
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
                        <form method="post" enctype="multipart/form-data" id="form1" name="form1">
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
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Nombre del certificado</label><input type="text" class="form-control" name="certificado" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="examplePassword11" class="">Fecha de calibración</label><input type="date" class="form-control" name="fecha_emision"   required>
</div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="examplePassword11" class="">Fecha de vencimiento</label><input type="date" class="form-control" name="fecha_vencimiento"   required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">País de emisión</label><input type="text" class="form-control" name="pais"   required></div>
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
                            <div class="position-relative form-group"><label for="exampleEmail11" class="">Archivo PDF cargado por usuario</label>
                            <input type="file" class="form-control" name="pdf_file" id="pdf_file" required></div>
                             
                            <label>
                            <input type="radio" name="certificate_type" checked value="Primario"> Primario PDF
            </label>
                        
                        </div>
                    </div>
                    <div style="text-align:center;">
                                               <input type="hidden" name="add_action" value="1"/>
                                               <a href="index.php?module=13&page=4&s=-" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger"><i class="fa-solid fa-x"></i> Cerrar</a>
					 		<button type="submit" name="submit_action" value="Aceptar" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">Aceptar</button>
 
                    
                    </div>
                </form>
                <br><br>
                </div>
            </div>
            
        </div>
        </div>
    </div>
    
</div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
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
    


    

    // Show confirmation modal when file input changes
    $('input[type="file"]').on('change', function() {
        var $fileInput = $(this);
        
       
        if ($fileInput.val() !== '' ) {
            $('#confirmationModal').modal('show');
        }
    });
    
    // Handle user's choice in confirmation modal
    $('#confirmUploadButton').click(function() {
        alert('Certificado primario elegido.');
   
    $('#form1').submit(); // Allow form submission
});

// Reset the file input value when modal is closed
$('#confirmationModal').on('hidden.bs.modal', function () {
    $('input[type="file"]').val('');
});

 
    $('#form1').submit(function(event) {
        var isValid = true;
        
        // Iterate over each required input field
        $(this).find(':input[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                return false; // Exit the loop if an empty value is found
            }
        });
        
        if (!isValid) {
            event.preventDefault();
            alert('Por favor, rellene todos los campos obligatorios.');
        }
    });
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
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button " class="btn btn-success" id="confirmUploadButton">Confirmar</button>
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



<style>
 

.modal-backdrop.show, .show.blockOverlay, .modal-backdrop, .blockOverlay {   
    position: inherit;
}
.modal {
     
    top: 105px;
    padding-top: 15px;
}

 
  </style>
