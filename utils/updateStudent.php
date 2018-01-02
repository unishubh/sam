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
	$rollno=$params['rollno'];
	$programName=$params['programName'];
	$email=$params['email'];
	$aadhar=$params['aadhar'];
	$phone=$params['phone'];
	$bank=$params['bank'];
	$cess=$params['cess']; 
	$enrolled=$params['enrolled'];
	$stmt = $db->prepare( "UPDATE students SET name ='$name' ,email='$email' ,aadhar='$aadhar' ,phone='$phone' ,bank_ac_number='$bank' ,end_date='$cess', enrolled='$enrolled' where rollno='$rollno' AND program_name='$programName'");
	$stmt->execute();
				
	http_response_code($success);
}
?>
