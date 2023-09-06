

<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h2>Carga masiva de sensores</h2>
            <div class="btn-actions-pane-right">
                <a href="templates/metrologia/ejemplo-only-sensor.xlsx" target="_blank" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary"><i class="fa-regular fa-file-excel btn-icon-wrapper"></i> Ejemplo Excel</a>
                <a href="index.php?module=13&page=12" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-danger"><i class="fa-solid fa-x btn-icon-wrapper"></i> Cancelar</a>
            </div>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" id="uploadForm" name="uploadForm">
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label for="exampleEmail11" class="">Archivo de datos</label>
                        <input type="file" name="excelFile" class="form-control" style="width: 50%;" required accept=".xls, .xlsx">
                    </div>
                </div>
                 
                <div style="text-align: center;">
               
                
                    <input type="button" id="processButton" value="Cargar archivo" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary">
                    <button type="button" id="resetButton" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-secondary">
        <i class="fa-solid fa-undo btn-icon-wrapper"></i> Reiniciar
    </button>
                    
                    <input type="button" id="process" value="Procesar" style="text-align: center; display: none;" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">
                    <div class="loading-progress"></div>
                    <br><br>
                    <div class="col-sm-12" style="text-align: center; display: none;" id="loadingsection">
                        <h4>Resultados de la carga</h4>
                        <span class="text-dark"> Total de sensores a cargar:</span>
                        <span class="text-success total-sensors-count"><strong>0</strong></span><br>
                        <span class="text-dark"> Errores de carga:</span>
                        <span class="error-count upload-errors-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Sensores con información incompleta:</span>
                        <span class="error-count incomplete-sensor-data-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Tipos de sensor no existentes:</span>
                        <span class="error-count unknown-sensor-type-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Sensores duplicados en archivo:</span>
                        <span class="error-count duplicate-sensors-in-file-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Sensores duplicados en la base de datos:</span>
                        <span class="error-count duplicate-sensors-in-db-count text-success"><strong>0</strong></span><br>
                        <div class="duplicated-sensors-list">
                            <span class="text-dark"> Sensores duplicados detectados:</span>
                            <ul class="duplicated-sensors text-success">
                                <!-- Duplicated sensor names will be added here dynamically -->
                            </ul>
                        </div>
                        <div class="duplicated-sensors-in-file">
                            <span class="text-dark">Sensores duplicados detectados en el archivo:</span>
                            <ul class="duplicated-sensors-in-file text-success">
                                <!-- Duplicate sensor names in the file will be added here dynamically -->
                            </ul>
                        </div>
                    </div>


                    <div class="error-messages"></div>

                </div>
                <br><br>
                <table style="width: 100%; text-align: center;" id="dataGrid" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <td><strong>No.</strong></td>
                            <td><strong>Sensor </strong></td>
                            <td><strong>Serie </strong></td>
                            <td><strong>Tipo </strong></td>
                            <td><strong>País </strong></td>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </form>
            <br><br>
        </div>
    </div>
</div>
</div>
</div>

<!-- Modal -->

<!-- Modal for Upload Validation Alert -->
<div class="modal fade" id="uploadValidationModal" tabindex="-1" role="dialog" aria-labelledby="uploadValidationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadValidationModalLabel">Alerta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="uploadValidationAlert" class="alert alert-danger" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Confirm Upload -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirm Upload</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que quieres subir los archivos?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="confirmUploadButton">Confirmar</button>
            </div>
        </div>
    </div>
</div>

<!-- <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css"> -->
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
 
<script type="text/javascript" src="scripts/jquery.progresstimer.js"></script>

<style>
.modal-backdrop.show, .show.blockOverlay, .modal-backdrop, .blockOverlay {
    position: inherit;
}
.modal {
    top: 105px;
    padding-top: 15px;
}
/* Warning phase style */
.progress-bar-warning {
    background-color: #f0ad4e; /* Use the color of your choice */
}

/* Completion of timer style */
.progress-bar-success {
    background-color: #5cb85c; /* Use the color of your choice */
}

</style>

<script>
$(document).ready(function () {

    
    // Process button click event
    $("#processButton").click(function () {
        if (!validateForm()) {
       
            displayUploadValidationAlert("Cargue el archivo de Excel antes de continuar.");
            return false;
        }
        else{
            $("#confirmationModal").modal('show'); 

        }
       
    });

     // Function to display upload validation alert inside the modal
     function displayUploadValidationAlert(message) {
        $("#uploadValidationAlert").text(message);
        $("#uploadValidationModal").modal('show');
    }

    // Function to validate the form
    function validateForm() {
        var excelFile = $("input[name='excelFile']").val();
        if (!excelFile ) {
            return false;
        }
        return true;
    }

    // Display alert inside the modal
    function displayAlert(message) {
        $("#alertMessage").text(message);        
        $("#alertContainer").show();
    }

    $("#confirmUploadButton").click(function () {
        var formData = new FormData($("#uploadForm")[0]);
        $("#processButton").hide();
        $("#process").show();
        var progress = $(".loading-progress").progressTimer({
            timeLimit: 1, // Time limit in milliseconds
            onFinish: function () {}
        });

    // Create the AJAX request
    $.ajax({
            url: "templates/metrologia/includes/proceso_sensor.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function (event) {
                    if (event.lengthComputable) {
                        var percentComplete = (event.loaded / event.total) * 100;
                        progress.progressTimer('value', percentComplete);
                        if (percentComplete >= 75) {
                            $(".loading-progress").addClass("progress-bar-warning");
                        }
                    }
                });
                return xhr;
            },
            success: function (data) {
                var response = JSON.parse(data);
                

// Update the total sensors count
$(".total-sensors-count").text(response.totalSensors);
$(".total-sensors-count").toggleClass("text-success", response.totalSensors > 0);
$(".total-sensors-count").toggleClass("text-danger", response.totalSensors === 0);

// Update the upload errors count
$(".upload-errors-count").text(response.errorMessages.length);
$(".upload-errors-count").toggleClass("text-danger", response.errorMessages.length > 0);

// Update the incomplete sensor data count
$(".incomplete-sensor-data-count").text(response.incompleteSensorData);
$(".incomplete-sensor-data-count").toggleClass("text-danger", response.incompleteSensorData > 0);

// Update the unknown sensor type count
$(".unknown-sensor-type-count").text(response.unknownSensorType);
$(".unknown-sensor-type-count").toggleClass("text-danger", response.unknownSensorType > 0);

// Update the duplicated sensors in file count
$(".duplicate-sensors-in-file-count").text(response.duplicateSensorsInFileCount);
$(".duplicate-sensors-in-file-count").toggleClass("text-danger", response.duplicateSensorsInFileCount > 0);

// Update the duplicated sensors in DB count
$(".duplicate-sensors-in-db-count").text(response.duplicateSensorsInDBCount);
$(".duplicate-sensors-in-db-count").toggleClass("text-danger", response.duplicateSensorsInDBCount > 0);


if (response.errorMessages.length === 0) {
        // No errors, hide the "Upload files" button and show the "Process" button
        $("#processButton").hide();
        $("#process").show();
    } else {
        // Errors occurred, keep the "Upload files" button visible
        $("#processButton").show();
        $("#process").hide();
    }

   // Populate the duplicated sensor list if available
    if (response.duplicatedSensors && response.duplicatedSensors.length > 0) {
        var duplicatedSensorsList = $(".duplicated-sensors");
        duplicatedSensorsList.empty();
        response.duplicatedSensors.forEach(function(sensorName) {
            duplicatedSensorsList.append("<li>" + sensorName + "</li>");
        });
        $(".duplicated-sensors-list").show(); // Show the duplicated sensor list section
    } else {
        $(".duplicated-sensors-list").hide(); // Hide the duplicated sensor list section
    }

    // Populate the duplicatedSensorsInFile list if available
    if (response.duplicatedSensorsInFile && response.duplicatedSensorsInFile.length > 0) {
        var duplicatedSensorsInFileList = $(".duplicated-sensors-in-file ul");
        duplicatedSensorsInFileList.empty();
        response.duplicatedSensorsInFile.forEach(function(sensorName) {
            duplicatedSensorsInFileList.append("<li>" + sensorName + "</li>");
        });
        $(".duplicated-sensors-in-file").show(); // Show the duplicated sensors in file section
    } else {
        $(".duplicated-sensors-in-file").hide(); // Hide the duplicated sensors in file section
    }


                // Handle the uploadStatus
    if (response.uploadStatus === "error") {
        var errorMessageHtml = "<ul>";
        response.errorMessages.forEach(function(errorMessage) {
            errorMessageHtml += "<li>" + errorMessage + "</li>";
        });
        errorMessageHtml += "</ul>";
        $(".error-messages").html(errorMessageHtml);
    } else {
        $(".error-messages").empty();
    }

                // Clear the existing table rows
                $("#dataGrid tbody").empty();

                // Populate the table with data
                var sensors = response.sensors; // Replace with the actual property name in the JSON response

                for (var i = 0; i < sensors.length; i++) {
                    var sensor = sensors[i];

                    var rowHtml = '<tr>' +
                        '<td>' + (i + 1) + '</td>' +
                        '<td>' + sensor.nombre + '</td>' +
                        '<td>' + sensor.serie + '</td>' +
                        '<td>' + sensor.tipo + '</td>' +
                        '<td>' + sensor.pais + '</td>' +
                        '</tr>';

                    $("#dataGrid tbody").append(rowHtml);
                }

                // Show the loading section with the updated information
                $("#loadingsection").show();
            },
            error: function (xhr, status, error) {
                console.error("AJAX request error:", error);
                $(".loading-progress").removeClass("active").addClass("progress-bar-danger");
                progress.progressTimer('error', {
                    errorText: 'ERROR!',
                    onFinish: function () {
                        alert('There was an error processing your information!');
                    }
                });
            },
            complete: function () {
               // $("#processButton").show();
               // $("#process").hide();
            }
    });

    // Close the modal
    $("#confirmationModal").modal('hide');
    });

    // Reset button click event
    $("#resetButton").click(function () {
            resetForm();
    });

    // Function to reset the form and UI elements
    function resetForm() {
        // Reset form elements
        $("input[name='excelFile']").val('');

        $(".loading-progress").empty();
        $(".error-messages").empty();

        // Clear the existing table rows
        $("#dataGrid tbody").empty();

        // Reset counters and text
        $(".total-sensors-count").text("0");
        $(".upload-errors-count").text("0");
        $(".incomplete-sensor-data-count").text("0");
        $(".unknown-sensor-type-count").text("0");
        $(".duplicate-sensors-in-file-count").text("0");
        $(".duplicate-sensors-in-db-count").text("0");

        // Clear duplicated sensor list
        $(".duplicated-sensors").empty();

        // Reset file input styling
        $("input[type='file']").removeClass("is-invalid");

        // Hide loading section
        $("#loadingsection").hide();
        $("#processButton").show();
          $("#process").hide();
    }
});
</script>

<style>
.progress {
  background-image: -webkit-linear-gradient(top, #ebebeb 0%, #f5f5f5 100%);
  background-image:      -o-linear-gradient(top, #ebebeb 0%, #f5f5f5 100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#ebebeb), to(#f5f5f5));
  background-image:         linear-gradient(to bottom, #ebebeb 0%, #f5f5f5 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffebebeb', endColorstr='#fff5f5f5', GradientType=0);
  background-repeat: repeat-x;
}
.progress-bar {
  background-image: -webkit-linear-gradient(top, #337ab7 0%, #286090 100%);
  background-image:      -o-linear-gradient(top, #337ab7 0%, #286090 100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#337ab7), to(#286090));
  background-image:         linear-gradient(to bottom, #337ab7 0%, #286090 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff337ab7', endColorstr='#ff286090', GradientType=0);
  background-repeat: repeat-x;
}
.progress-bar-success {
  background-image: -webkit-linear-gradient(top, #5cb85c 0%, #449d44 100%);
  background-image:      -o-linear-gradient(top, #5cb85c 0%, #449d44 100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#5cb85c), to(#449d44));
  background-image:         linear-gradient(to bottom, #5cb85c 0%, #449d44 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5cb85c', endColorstr='#ff449d44', GradientType=0);
  background-repeat: repeat-x;
}
.progress-bar-info {
  background-image: -webkit-linear-gradient(top, #5bc0de 0%, #31b0d5 100%);
  background-image:      -o-linear-gradient(top, #5bc0de 0%, #31b0d5 100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#5bc0de), to(#31b0d5));
  background-image:         linear-gradient(to bottom, #5bc0de 0%, #31b0d5 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff5bc0de', endColorstr='#ff31b0d5', GradientType=0);
  background-repeat: repeat-x;
}
.progress-bar-warning {
  background-image: -webkit-linear-gradient(top, #f0ad4e 0%, #ec971f 100%);
  background-image:      -o-linear-gradient(top, #f0ad4e 0%, #ec971f 100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#f0ad4e), to(#ec971f));
  background-image:         linear-gradient(to bottom, #f0ad4e 0%, #ec971f 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff0ad4e', endColorstr='#ffec971f', GradientType=0);
  background-repeat: repeat-x;
}
.progress-bar-danger {
  background-image: -webkit-linear-gradient(top, #d9534f 0%, #c9302c 100%);
  background-image:      -o-linear-gradient(top, #d9534f 0%, #c9302c 100%);
  background-image: -webkit-gradient(linear, left top, left bottom, from(#d9534f), to(#c9302c));
  background-image:         linear-gradient(to bottom, #d9534f 0%, #c9302c 100%);
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffd9534f', endColorstr='#ffc9302c', GradientType=0);
  background-repeat: repeat-x;
}
.progress-bar-striped {
  background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
  background-image:      -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
  background-image:         linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
}
.error-messages {
    color: red;
    font-weight: bold;
    margin-top: 10px;
}

.error-messages ul, #loadingsection ul {
    max-height: 200px; /* Adjust the maximum height as needed */
    overflow: auto;   /* Add a scrollbar when content overflows */
    padding: 10px;
}

.error-messages ul li, #loadingsection ul li {
    margin-bottom: 5px;
}

    </style>

