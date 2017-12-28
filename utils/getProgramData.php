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
	$stmt = $db->prepare("SELECT * FROM program ");
	$stmt->execute();
				
	$data=$stmt->fetchAll(PDO::FETCH_OBJ);
	echo json_encode($data);
	http_response_code($success);
}
?>
