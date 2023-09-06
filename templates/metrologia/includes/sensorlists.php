<?php
error_reporting(0);
ob_start();
session_start();

include_once("../lib/config.php");
include_once("../lib/mysqli_class.php");


// Fetch sensor data from the database
$sql = "SELECT id_sensor, nombre FROM sensores";
$result = $db_cms->select_query($sql);
 
if ($result !== FALSE) {
    foreach ($result as $row) {
        $sensor_data[] = $row;
    }
}


?>