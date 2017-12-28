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
	$user=$params['user'];
	$addr=$params['addr'];
	
	$stmt = $db->prepare( "UPDATE ip_addr SET Name = '".$user."' , ip='".$addr."' where Name='".$user."'" );
	$stmt->execute();
				
	http_response_code($success);
}
?>
