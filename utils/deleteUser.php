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
	$name=$params['name'];
	 
	$stmt = $db->prepare( "DELETE FROM users WHERE name = '$name' ");
	$stmt->execute();
				
	http_response_code($success);
}
?>
