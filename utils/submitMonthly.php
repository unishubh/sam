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
	$db=getDB();
	$params = array();
	$params = $_REQUEST;
	

	$id=$params['student_id'];
	$rollno=$params['rollno'];
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
	$work_off=$params['work_off'];
	$d=date("d/m/Y");
	$d=(string)$d;
	$stmt = $db->prepare("select id from students where rollno=:rollno");
	$stmt->bindParam("rollno", $rollno,PDO::PARAM_STR);
	$stmt->execute();
	$student_id=$stmt->fetchAll(PDO::FETCH_OBJ);
	$student_id=$student_id[0]->id;
	$responseObj = ($mainClass->saveRecords($month,$year,$student_id,$rollno,$program_name,$presents,$absents,$sl,$ml,$cl,$maternity,$duty,$work_off,1,$d));

	if($responseObj)
	{
		echo json_encode($responseObj);
		http_response_code($success);
	}
	
	
}
?>
