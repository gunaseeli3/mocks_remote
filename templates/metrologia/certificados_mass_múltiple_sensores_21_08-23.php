
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
             
                <h2>CARGA MASIVA DE SENSORES CON UNO O MAS CERTIFICADOS</h2>
                 
            <div class="btn-actions-pane-right">
                <a href="templates/metrologia/ejemplo-sensores.xlsx" target="_blank" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary"><i class="fa-regular fa-file-excel btn-icon-wrapper"></i> Ejemplo Excel</a>
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
                <div class="col-md-12">
                    <div class="position-relative form-group">
                        <label for="exampleEmail11" class="">Archivos PDF</label>
                        <input type="file" name="pdfFiles[]" class="form-control" style="width: 50%;" multiple required accept=".pdf">
                    </div>
                </div>
                <div style="text-align: center;">
                <input type="hidden" name="sensorname"  value="<?=$mi_sensor?>">
                <button type="button" id="resetButton" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-secondary">
        <i class="fa-solid fa-undo btn-icon-wrapper"></i> Reiniciar
    </button>
                    <input type="button" id="processButton" value="Cargar archivos" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-primary">
                    <input type="button" id="process" value="Procesar" style="text-align: center; display: none;" class="mb-2 mr-2 btn-icon btn-shadow btn-outline-2x btn btn-outline-success">
                    <div class="loading-progress"></div>
                    <br><br>
                    <div class="col-sm-12" style="text-align: center; display: none;" id="loadingsection">
                        <h4>Resultados de la carga</h4>
                        <span class="text-dark"> Total de certificados a cargar:</span>
                        <span class="text-success total-certificates-count"><strong>0</strong></span><br>
                        <span class="text-dark"> Error de data incompleta:</span>
                        <span class="error-count incomplete-data-error-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Error fechas incongruentes:</span>
                        <span class="error-count inconsistent-dates-error-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Error de formato de fecha:</span>
                        <span class="error-count date-format-error-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Error de tipo de sensor desconocido:</span>
                        <span class="error-count unknown-sensor-type-error-count text-success"><strong>0</strong></span><br>
                        <span class="text-dark"> Lista de certificados no asociados:</span>
                        <span class="error-count unassociated-certificates-count text-success"><strong>N/A</strong></span><br>
                    </div>

                    <div class="error-messages"></div>

                </div>
                <br><br>
                <table style="width: 100%; text-align: center;" id="dataGrid" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <td><strong>No.</strong></td>
                            <td><strong>Sensor</strong></td>
                            <td><strong>Certificado</strong></td>
                            <td><strong>Emitido el</strong></td>
                            <td><strong>Vence el</strong></td>
                            <td><strong>Estado</strong></td>
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
       
            displayUploadValidationAlert("Por favor, sube tanto el archivo de Excel como los archivos PDF antes de continuar.");
            
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
        var pdfFiles = $("input[name='pdfFiles[]']").get(0).files;

        if (!excelFile || pdfFiles.length === 0) {
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

    // Hide the processButton and show the process element
     $("#processButton").hide();
    $("#process").show();

    // Initialize the progress timer
    var progress = $(".loading-progress").progressTimer({
        timeLimit: 5, // Set the time limit in milliseconds (10 seconds in this example)
        onFinish: function () {
            // Handle completion here
        }
    });

    // Create the AJAX request
    $.ajax({
        url: "templates/metrologia/includes/proceso_multiple.php",
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
        success: function (response) {
            var data = JSON.parse(response);
                $(".total-certificates-count").text(data.totalCertificates);
                $(".total-certificates-count").toggleClass("text-success", data.totalCertificates > 0);
                $(".total-certificates-count").toggleClass("text-danger", data.totalCertificates === 0);

                $(".incomplete-data-error-count").text(data.incompleteDataError);
                $(".incomplete-data-error-count").toggleClass("text-success", data.incompleteDataError === 0);
                $(".incomplete-data-error-count").toggleClass("text-danger", data.incompleteDataError > 0);

                $(".inconsistent-dates-error-count").text(data.inconsistentDatesError);
                $(".inconsistent-dates-error-count").toggleClass("text-success", data.inconsistentDatesError === 0);
                $(".inconsistent-dates-error-count").toggleClass("text-danger", data.inconsistentDatesError > 0);

                $(".date-format-error-count").text(data.dateFormatError);
                $(".date-format-error-count").toggleClass("text-success", data.dateFormatError === 0);
                $(".date-format-error-count").toggleClass("text-danger", data.dateFormatError > 0);

                $(".unknown-sensor-type-error-count").text(data.unknownSensorTypeError);
                $(".unknown-sensor-type-error-count").toggleClass("text-success", data.unknownSensorTypeError === 0);
                $(".unknown-sensor-type-error-count").toggleClass("text-danger", data.unknownSensorTypeError > 0);

                if (data.unassociatedCertificates.length > 0) {
                    var unassociatedList = data.unassociatedCertificates.join(", ");
                    $(".unassociated-certificates-count").text(unassociatedList);
                } else {
                    $(".unassociated-certificates-count").text("N/A");
                }

                var uploadStatus = data.uploadStatus;
                var errorMessages = data.errorMessages;

                if (uploadStatus === "error") {
    if (errorMessages.length > 0) {
        var errorMessageHtml = "<ul>";
        errorMessages.forEach(function(errorMessage) {
            errorMessageHtml += "<li>" + errorMessage + "</li>";
        });
        errorMessageHtml += "</ul>";

        $(".error-messages").html(errorMessageHtml);
    } else {
        $(".error-messages").empty();
    }
} else {
    $(".error-messages").empty();
}
               //  $(".loading-progress").hide();
                 // Clear the existing table rows
$("#dataGrid tbody").empty();

// Populate the table with data
var certificates = data.certificates; // Replace with the actual property name in the JSON response

for (var i = 0; i < certificates.length; i++) {
    var certificate = certificates[i];
 
    var rowHtml = '<tr>' +
        '<td>' + (i + 1) + '</td>' +
        '<td>' + certificate.getsensorname + '</td>' +    
        '<td>' + certificate.certificado + '</td>' +        
        '<td>' + certificate.emitido_el + '</td>' +
        '<td>' + certificate.vence_el + '</td>' +
        '<td>' + certificate.estado + '</td>' +
        '</tr>';

    $("#dataGrid tbody").append(rowHtml);
}
                // Show the loading section with the updated information
             $("#loadingsection").show();
                
             },
             error: function (xhr, status, error) {
            console.error("AJAX request error:", error);
            // Update loading progress to show an error
            $(".loading-progress ").removeClass("active").addClass("progress-bar-danger");
            progress.progressTimer('error', {
                errorText: 'ERROR!',
                onFinish: function () {
                    alert('There was an error processing your information!');
                }
            });
        },
        complete: function () {
            // Revert the button states after the request is complete
            $("#processButton").show();
            $("#process").hide();
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
        $("input[name='pdfFiles[]']").val('');
        $(".loading-progress").empty();
        $(".error-messages").empty();

        // Clear the existing table rows
        $("#dataGrid tbody").empty();
        
        // Reset counters
        $(".total-certificates-count").text("0");
        $(".incomplete-data-error-count").text("0");
        $(".inconsistent-dates-error-count").text("0");
        $(".date-format-error-count").text("0");
        $(".unknown-sensor-type-error-count").text("0");
        $(".unassociated-certificates-count").text("N/A");

        // Reset file input styling
        $("input[type='file']").removeClass("is-invalid");

        $(".loading-progress").empty();

        // Reset other UI elements or messages if needed
    }

});
</script>
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

.error-messages ul {
    list-style: none;
    padding: 0;
}

.error-messages ul li {
    margin: 5px 0;
}

    </style>

