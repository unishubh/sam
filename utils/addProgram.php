<?php

include('config.php');
include('mainClass.php');
$mainClass = new mainClass();

if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) 
{
    http_response_code($badRequest);
}



else
{
	$db = getDB();
	$params = array();
	$params=$_POST;
	$programName=$params['programName'];
	$stipend=$params['stipend'];
	$tenure=$params['tenure'];
	$sl=$params['sl'];
	$cl=$params['cl'];
	$duty=$params['duty'];
	$ml=$params['ml'];
	$maternity=$params['maternity']; 
	$stmt = $db->prepare( "INSERT INTO program VALUES ('','$programName','$stipend','$tenure','$sl','$ml','$cl','$maternity','$duty') ");
	$stmt->execute();
				
	http_response_code($success);
}
?>
