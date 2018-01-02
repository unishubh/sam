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
	$username=$params['userName'];
	$email=$params['email'];
	$password=$params['password'];
	$password=md5($password);
	$role=$params['role'];
	$stmt = $db->prepare( "INSERT INTO users VALUES ('','$role','$email','$password','$username') ");
	$stmt->execute();
				
	http_response_code($success);
}
?>
