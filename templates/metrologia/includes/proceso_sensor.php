<?php

//error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// ini_set('display_errors', 1);
require_once dirname(__FILE__) . '/../../../config.ini.php';

$mod=$_REQUEST['mod'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Include PHPExcel library
    require_once dirname(__FILE__) . '/../../../Classes/PHPExcel.php';

    // Initialize counters
    $totalSensors = 0;
    $incompleteSensorData = 0;
    $unknownSensorType = 0;
    $duplicatedSensors = 0;
    $duplicateSensorsInFile_cnt = 0;
    // Initialize arrays to store sensor names and their occurrences
    $allSensorNames = array();
    $duplicateSensorsInFile = array();
    $duplicatedSensors_arr = array(); // Initialize the array
    $uploadError = false;
    $errorMessages = array();

    // Process Excel file
    if (isset($_FILES['excelFile']) && !empty($_FILES['excelFile']['name'])) {
        $excelFile = $_FILES['excelFile']['tmp_name'];

        $sensors = array();

        // Load the Excel file
        $inputFileType = PHPExcel_IOFactory::identify($excelFile);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($excelFile);

        // Get the active worksheet
        $worksheet = $objPHPExcel->getActiveSheet();

        // Loop through rows (assuming data starts from row 2)
        foreach ($worksheet->getRowIterator(2) as $row) {
            $rowData = $row->getCellIterator();

            // Get row values
            $sensorName = $rowData->current()->getValue();
            if ($sensorName === null || trim($sensorName) === '') {
                // Skip processing this row and move to the next iteration
                continue;
            }

            $rowData->next();
            $sensorSerial = $rowData->current()->getValue();
            $rowData->next();
            $sensorType = $rowData->current()->getValue();
            $rowData->next();
            $sensorCountry = $rowData->current()->getValue();

            // Validate and process row data
            $totalSensors++;

            // Validate and process row data
            if (empty($sensorName) || empty($sensorSerial) || empty($sensorType) || empty($sensorCountry)) {
                $incompleteSensorData++;
            }

            // Validate sensor types
            if (!in_array($sensorType, array("Termocupla", "T", "T/HR"))) {
                $unknownSensorType++;
            }

            $sql21 = "SELECT COUNT(*) as cnt FROM sensores WHERE nombre='$sensorName' ";
            $res_cnt2 = $db_cms->count_query_new($sql21);

            if ($res_cnt2 > 0) {
                $uploadError = true;
                $errorMessages[] = "El sensor '{$sensorName}' ya existe.";
                // Add the sensor name to the duplicatedSensors array
                $duplicatedSensors_arr[] = $sensorName;
                $duplicatedSensors++;
            }

            // Check for duplicate sensors in the file
            if (in_array($sensorName, $allSensorNames)) {
                if (!in_array($sensorName, $duplicateSensorsInFile)) {
                    $duplicateSensorsInFile[] = $sensorName;
                    $duplicateSensorsInFile_cnt++;
                }
            } else {
                $allSensorNames[] = $sensorName;
            }

            // Store sensor data in the array
            $sensors[] = array(
                'nombre' => $sensorName,
                'serie' => $sensorSerial,
                'tipo' => $sensorType,
                'pais' => $sensorCountry
            );
        }
    }

    

    // Check if error counters are equal to 0

    $errorMessages_cnt=count($errorMessages);

    if ($mod=='process' && $errorMessages_cnt === 0  && $incompleteSensorData === 0 && $unknownSensorType === 0 && $duplicatedSensors === 0 && $duplicateSensorsInFile_cnt === 0 ) {
        $c = 0;
        // Insert data into the database
        foreach ($sensors as $sensor) {
            $nombre = $sensor['nombre'];
            $serie = $sensor['serie'];
            $tipo = $sensor['tipo'];
            $pais = $sensor['pais'];
            $estado = "Vigente";

            // Check if the sensor already exists
            $sql2 = "SELECT COUNT(*) as cnt FROM sensores WHERE nombre='$nombre' ";
            $res_cnt = $db_cms->count_query_new($sql2);

            
                // Insert data into sensores table
                $insertQuery = "INSERT INTO sensores (nombre, serie, tipo, pais, estado)
                                VALUES ('$nombre', '$serie', '$tipo', '$pais', '$estado')";
                $res = $db_cms->insert_query_last($insertQuery);

                if ($res !== FALSE) {
                    // Construct the description for the backtrack record
                    $user = isset($_COOKIE['user']) ? $_COOKIE['user'] : "";
                    $action = "Creó";
                    $date_time_action = date('Y-m-d H:i:s');
                    $field1 = "Identificación del sensor";
                    $field1_value = $nombre;
                    $field2 = "Número de serie";
                    $field2_value = $serie;
                    $field3 = "Tipo de sensor";
                    $field3_value = $tipo;
                    $field4 = "Pais";
                    $field4_value = $pais;
                    $field5 = "Estado";
                    $field5_value = $estado;
                    $field6 = "Página";
                    $field6_value = "CARGA MASIVA DE SENSORES";

                    $description = "$user $action el $date_time_action <br>"
                        . "$field1 - $field1_value<br>"
                        . "$field2 - $field2_value<br>"
                        . "$field3 - $field3_value<br>"
                        . "$field4 - $field4_value<br>"
                        . "$field5 - $field5_value<br>"
                        . "$field6 - $field6_value<br>";

                    $description_base64 = base64_encode($description);

                    $backtrack_data = array(
                        'fecha' => $date_time_action,
                        'persona' => $_COOKIE['myid'],
                        'movimiento' => $action,
                        'modulo' => "Metrologia",
                        'descripcion' => $description_base64
                    );

                    $res_backtrack = $db_cms->add_query($backtrack_data, 'backtrack');
                
                
                } else {
                    $uploadError = true;
                    $errorMessages[] = "No se pudo insertar el registro en la base de datos.";
                    break;
                }
            
            $c++;
        }

        if (!$uploadError) {
            // Successful upload
        } else {
            $errorMessages[] = "Error al cargar datos o archivos. Por favor, compruebe su entrada.";
        }
    } else {
        $uploadError = true;

        if ($duplicatedSensors > 0) {
            $errorMessages[] = "Existen sensores duplicados en la base de datos.";
        }

        if ($duplicateSensorsInFile_cnt > 0) {
            $errorMessages[] = "Existen sensores duplicados en el archivo.";
        }

        if ($incompleteSensorData > 0) {
            $errorMessages[] = "Sensor con información incompleta";
        }
    
        if ($unknownSensorType > 0) {
            $errorMessages[] = "Tipo de sensor no existe";
        }
    }

    $duplicateSensorsInFileCount = count($duplicateSensorsInFile);
    $duplicateSensorsInDBCount = $duplicatedSensors;

    $response = array(
        'uploadStatus' => $uploadError ? "error" : "success",
        'errorMessages' => $errorMessages,
        'totalSensors' => $totalSensors,
        'incompleteSensorData' => $incompleteSensorData,
        'unknownSensorType' => $unknownSensorType,
        'duplicateSensorsInFileCount' => $duplicateSensorsInFileCount,
        'duplicateSensorsInDBCount' => $duplicateSensorsInDBCount,
        'duplicatedSensorsInFile' => $duplicateSensorsInFile, // Include the array here
        'duplicatedSensors' => $duplicatedSensors_arr, // Include the duplicatedSensors array here
        'sensors' => $sensors
    );

     
    
    

    echo json_encode($response);
} else {
    // Handle other HTTP methods or direct access
    http_response_code(400);
    echo 'Bad Request';
}
?>
