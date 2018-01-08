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
	$username=$params['username'];
	 
	$stmt = $db->prepare( "DELETE FROM ip_addr WHERE Name = '$username' ");
	$stmt->execute();
				
	http_response_code($success);
}
?>
