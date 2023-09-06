<?php 
//base de datos de prueba db_desarrollo
//base de datos real cercal
include("smarty/libs/Smarty.class.php");

$smarty = new smarty;

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
$connect = mysqli_connect($db_server,$db_username,$db_pass,$db_database);

	if($connect === false)
	{	
		die("Error, no se puede establecer conexion a la base de datos ".mysqli_connect_error());
	}

	include_once('lib/mysqli_class.php');

	$db_cms=new DBManager(); 
 
$db_cms2 = $db_cms->connect($db_server,$db_username,$db_pass,$db_database);
 
mysqli_set_charset($connect,"utf8");

?>