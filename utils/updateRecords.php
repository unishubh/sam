<?php

include('config.php');
include('mainClass.php');
$mainClass = new mainClass();

if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) 
{
    http_response_code($badRequest);
}

else if(!isset($_SESSION['role']) || empty($_SESSION['role'])){
    session_destroy();
    http_response_code($session_error);
}

else
{
	
	$params = array();
	$params = $_REQUEST;
	

	$student_id=$params['student_id'];
	$program_name=$params['program_name'];
	$presents=$params['presents'];
	$absents=$params['absents'];
	$sl=$params['sl'];
	$ml=$params['ml'];
	$cl=$params['cl'];
	$maternity=$params['maternity'];
	$duty=$params['duty'];
	$month = $params['month'];
	$year = $params['year'];

	$responseObj = ($mainClass->saveRecords($month,$year,$student_id,$program_name,$presents,$absents,$sl,$ml,$cl,$maternity,$duty));

	if($responseObj)
	{
		echo json_encode($responseObj);
		http_response_code($success);
	}
	
	
}
?>
