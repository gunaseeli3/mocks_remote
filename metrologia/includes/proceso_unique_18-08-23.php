<?php

//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
  // ini_set('display_errors', 1);
 require_once dirname(__FILE__) . '/../../../config.ini.php';

$sensor_id = $_GET["sensor_id"]; // Get the sensor ID from the URL parameter

$sensorname = $_POST["sensorname"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include PHPExcel library
    require_once dirname(__FILE__) . '/../../../Classes/PHPExcel.php';

    // Initialize counters
    $totalCertificates = 0;
    $incompleteDataError = 0;
    $inconsistentDatesError = 0;
    $dateFormatError = 0;
    $unknownSensorTypeError = 0;
    $unassociatedCertificates = array();
    $errorMessages = array();
    $uploadError = false;

    // Process Excel file
    if (isset($_FILES['excelFile']) && !empty($_FILES['excelFile']['name'])) {
        $excelFile = $_FILES['excelFile']['tmp_name'];

        $certificates = array();
    
        // Load the Excel file
        $inputFileType = PHPExcel_IOFactory::identify($excelFile);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($excelFile);
    
        // Get the active worksheet
        $worksheet = $objPHPExcel->getActiveSheet();


        // Get the first row's title
        $firstRow = $worksheet->getRowIterator(1)->current();
        $firstRowData = $firstRow->getCellIterator();
        $firstRowTitle = $firstRowData->current()->getValue();

        // Determine the sensor_id based on the first row title
        // if ($firstRowTitle === "Sensor") {
        //     // Proceed with the provided $sensor_id
        //     $useProvidedSensorId = true;
        // } else {
        //     // Use the sensor_id from the URL parameter
        //     $useProvidedSensorId = false;
            
        // }

        $useProvidedSensorId = false;

    
        // Initialize counters and arrays
        $totalCertificates = 0;
        $incompleteDataError = 0;
        $inconsistentDatesError = 0;
        $dateFormatError = 0;
        $unknownSensorTypeError = 0;
        $unassociatedCertificates = array();

 
        // Loop through rows (assuming data starts from row 2)
        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = $row->getCellIterator();
             
            // Get row values
            // if ($useProvidedSensorId) {
            // $sensor_id_from_excel = $rowData->current()->getValue();
            // $rowData->next(); // Move to the next cell
            // }
            $certificate = $rowData->current()->getValue();
            $rowData->next(); // Move to the next cell
            $magnitude = $rowData->current()->getValue();
            $rowData->next();
            $issuedDate = $rowData->current()->getValue();
            $rowData->next();
            $expiresDate = $rowData->current()->getValue();
            $rowData->next();
            $status = $rowData->current()->getValue();
            $rowData->next();
            $country = $rowData->current()->getValue();

             // Determine the sensor_id to use for this row
            if ($useProvidedSensorId) {
                 $sql2 = "SELECT * FROM sensores WHERE nombre = trim('$sensor_id_from_excel')";
               $sensor_result = $db_cms->select_query_with_row($sql2);
                $sensor_id=$sensor_result['id_sensor'];
                $getsensorname=$sensor_result['nombre'];
                 
            }
            else{
                $sensor_id =$_GET["sensor_id"];
                $getsensorname=$sensorname;
            }
 
    
            // Validate and process row data
            $totalCertificates++;
    
            if (empty($sensor_id) || empty($certificate) || empty($magnitude) || empty($issuedDate) || empty($expiresDate) || empty($status) || empty($country)) {
                $incompleteDataError++;
            }


             // Example: Validate and associate PDF files
                $pdfCertificateFound = false;
                $expectedPdfFileName = $certificate . ".pdf"; // Expected PDF file name
                
                 // Loop through uploaded PDF files to check for association
                foreach ($_FILES['pdfFiles']['name'] as $uploadedPdfFileName) {
                    if ($uploadedPdfFileName === $expectedPdfFileName) {
                        $pdfCertificateFound = true;
                        break;
                    }
                }
                
                // If expected PDF file name not found, add to unassociated certificates
                if (!$pdfCertificateFound) {
                    $unassociatedCertificates[] = $certificate;
                }

                            // Convert Excel date serial numbers to valid date format strings
    $issuedDate = PHPExcel_Shared_Date::ExcelToPHP($issuedDate);
    $expiresDate = PHPExcel_Shared_Date::ExcelToPHP($expiresDate);

    // Format the dates as desired (adjust the format string as needed)
    $issuedDateFormatted = date('d-m-Y', $issuedDate);
    $expiresDateFormatted = date('d-m-Y', $expiresDate);

                 
    // Validate dates format
            if (!isValidDateFormat($issuedDateFormatted) || !isValidDateFormat($expiresDateFormatted)) {
                $dateFormatError++;
            } else {
                // Convert Excel date serial numbers to Unix timestamps
                $issuedDateSerialNumber = PHPExcel_Shared_Date::ExcelToPHP($issuedDate);
                $expiresDateSerialNumber = PHPExcel_Shared_Date::ExcelToPHP($expiresDate);

                // Convert to Unix timestamps by applying the offset and multiplying by the number of seconds in a day
                $issuedTimestamp = ($issuedDateSerialNumber - 25569) * 86400;
                $expiresTimestamp = ($expiresDateSerialNumber - 25569) * 86400;

                if ($issuedTimestamp === false || $expiresTimestamp === false || $expiresTimestamp <= $issuedTimestamp) {
                    $inconsistentDatesError++;
                }
            }


            
            // Validate sensor types
            if (!in_array($magnitude, array("Temperatura", "Humedad", "T/HR"))) {
                $unknownSensorTypeError++;
            }




            $certificateData = array(
                'sensor_id' => $sensor_id,
                'getsensorname' => $getsensorname,
                'certificado' => $certificate,
                'magnitud' => $magnitude,
                'emitido_el' => $issuedDateFormatted, // Use the formatted date
                'vence_el' => $expiresDateFormatted,
                'estado' => $status,
                'pais' => $country
            );
    
            // Push the certificate data into the certificates array
            $certificates[] = $certificateData;
    
             
             
             
        }
    }

    $errorMessages = array(); 

// Check if error counters are equal to 0
if ($incompleteDataError === 0 && $inconsistentDatesError === 0 && $dateFormatError === 0 && $unknownSensorTypeError === 0) {
  
  
           
    
    $c=0;
    // Insert data into the database
    foreach ($certificates as $certificate) {
       // $id_sensor = $certificate['id_sensor'];
       // $id_sensor = $sensor_id;
         
        $id_sensor = $certificate['sensor_id'];
        $sensorname=$getsensorname;
        $sensor_id = $certificate['sensor_id'];
        $certificado = $certificate['certificado'];
        $fecha_emision = $certificate['emitido_el'];
        $fecha_vencimiento = $certificate['vence_el'];
        $estado = $certificate['estado'];
        $pais = $certificate['pais'];

        // Check if the certificate already exists for the selected sensor
         $sql2 = "SELECT COUNT(*) as cnt FROM sensores_certificados WHERE id_sensor='$sensor_id' AND certificado='{$certificado}'";
          $res_cnt = $db_cms->count_query_new($sql2);
        
         

    if ($res_cnt > 0) {
        $uploadError = true;
        $errorMessages[] = "El certificado '{$certificate['certificado']}' ya existe para el sensor seleccionado.";
    } else {

     
        // Convert the date format to 'YYYY-MM-DD' format
        $fecha_emision_mysql = DateTime::createFromFormat('d-m-Y', $fecha_emision)->format('Y-m-d');
        $fecha_vencimiento_mysql = DateTime::createFromFormat('d-m-Y', $fecha_vencimiento)->format('Y-m-d');

        // Insert data into sensors_certificates table
          $insertQuery = "INSERT INTO sensores_certificados (id_sensor, certificado, fecha_emision, fecha_vencimiento, estado, pais)
                        VALUES ('$id_sensor', '$certificado', '$fecha_emision_mysql', '$fecha_vencimiento_mysql', '$estado', '$pais')";
        $res = $db_cms->insert_query_last($insertQuery);
          
        if ($res !== FALSE) {
            $certificateId = $res; // Retrieve the last inserted certificate ID

            $expectedPdfFileName = $certificate['certificado'] . ".pdf";

            // Search for the matching filename in the $_FILES['pdfFiles'] array
            $pdfFileTmp = null;
            foreach ($_FILES['pdfFiles']['name'] as $index => $pdfFileName) {
                if ($pdfFileName === $expectedPdfFileName) {
                    $pdfFileTmp = $_FILES['pdfFiles']['tmp_name'][$index];
                    break;
                }
            }  
            
          // Move the related PDF file for this row
   $expectedPdfFileName = $certificado . ".pdf";

// Create the folder if it doesn't exist
$sensorId = $sensor_id;
 //$uploadDir = dirname(__FILE__) . '/../../templates/certificados/' . $sensorId;
 $uploadDir ="/var/www/html/CERNET_DESARROLLO_PRUEBAS/templates/certificados/" . $sensorId;


if (!file_exists($uploadDir)) {
    if (!mkdir($uploadDir, 0777, true)) {
        $uploadError = true;
        $errorMessages[] = "Error al crear el directorio de carga.";
    }
}

  $destination = $uploadDir . '/' . $expectedPdfFileName;
 
 
 
  $movefile = move_uploaded_file($pdfFileTmp, $destination);
 

if (!$movefile) {
    $uploadError = true;
    $errorMessages[] = "Error al mover el archivo PDF cargado para el certificado '{$certificado}'.";
  
}

            
            /// Construct the description for the backtrack record
                      
            $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
            $action = "Creó"; // Example action
            $date_time_action = date('Y-m-d H:i:s'); // Current date and time

            // Add more fields and values as needed
            $field1 = "Sensor";
            $field1_value = $sensor_id;
            $field2 = "Nombre del certificado";
            $field2_value = $certificado;
            $field3 = "Fecha de calibración";
            $field3_value = $fecha_emision;
            $field4 = "Fecha de vencimiento";
            $field4_value = $fecha_vencimiento;
            $field5 = "País de emisión";
            $field5_value = $pais;
            $field6 = "Estado";
            $field6_value = $estado;
            $field7 = "ID";
            $field7_value = $certificateId;
            $field8 = "página";
            $field8_value = "CARGA MASIVA DE CERTIFICADOS POR ARCHIVO EXCEL PARA EL SENSOR $sensorname";
            

            $url = "templates/certificados/{$sensor_id}/{$certificado}.pdf";
            $description = "$user $action el $date_time_action <br>"
                . "$field1 - $field1_value<br>"
                . "$field2 - $field2_value<br>"
                . "$field3 - $field3_value<br>"
                . "$field4 - $field4_value<br>"
                . "$field5 - $field5_value<br>"
                . "$field6 - $field6_value<br>"
                . "$field7 - $field7_value<br>"
                . "$field8 - $field8_value<br>"
                . "Tipo de archivo - Primario<br>"
                . "URL - $url<br>";

                $certificates[$c]['url']=$url;

             
            $description_base64 = base64_encode($description);
            
            $backtrack_data = array(
                'fecha' => $date_time_action,
                'persona' => $_COOKIE['myid'],
                'movimiento' => $action,
                'modulo' => "Metrologia",
                'descripcion' => $description_base64
            );
            
            $res_backtrack = $db_cms->add_query($backtrack_data,'backtrack');
            
             
        } else {
            $uploadError = true;
            $errorMessages[] = "No se pudo insertar el registro en la base de datos.";
                break;
        }
    }
    $c++;
}
    
    if (!$uploadError) {
       // $_SESSION["cms_status"] = "success";
    } else {
        
        $errorMessages[] = "Error al cargar datos o archivos. Por favor, compruebe su entrada.";
    }
} else {
    $uploadError = true;
    $errorMessages[] ="Entrada de datos no válida. Por favor, compruebe su entrada.";
}
$errorMessagesStr = implode("<br>", $errorMessages);
$response = array(
    'uploadStatus' => $uploadError ? "error" : "success",
    'errorMessages' => $errorMessages,
    'totalCertificates' => $totalCertificates,
    'incompleteDataError' => $incompleteDataError,
    'inconsistentDatesError' => $inconsistentDatesError,
    'dateFormatError' => $dateFormatError,
    'unknownSensorTypeError' => $unknownSensorTypeError,
    'unassociatedCertificates' => $unassociatedCertificates,
    'certificates' => $certificates   
);

echo json_encode($response);


} else {
    // Handle other HTTP methods or direct access
    http_response_code(400);
    echo 'Bad Request';
}

// Function to validate date format
function isValidDateFormat($date) {
    
    // Try 'd-m-Y' format
    $dateTime = DateTime::createFromFormat('d-m-Y', $date);
    if ($dateTime && $dateTime->format('d-m-Y') === $date) {
        return true;
    }
    
    // Try 'Y-m-d' format
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    if ($dateTime && $dateTime->format('Y-m-d') === $date) {
        return true;
    }
    
    return false;
}
