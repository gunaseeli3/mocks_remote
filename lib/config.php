<?php
error_reporting(1); 
session_start();
ob_start();

$ip=$_SERVER["SERVER_ADDR"];

$variable_url = $_SERVER['HTTP_HOST'];
$db_username = "";
$db_pass="";
$db_server='localhost';

if($variable_url == "45.79.114.124"){
	$db_username='themaker';
	$db_pass='cdd0504688f84c380d32ac66dcd6356d';
}else{
	$db_username='root';
	$db_pass='';
}

$db_database='CerNet2';

include_once('mysqli_class.php');

$db_cms=new DBManager(); 
 
$db_cms2 = $db_cms->connect($db_server,$db_username,$db_pass,$db_database);
if ($db_cms2->connect_errno) {
    echo "Failed to connect to MySQL: " . $db_cms->connect_error;
}

 

?>