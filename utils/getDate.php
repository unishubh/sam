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
	$year=$params['year'];
	$month=$params['month'];
	$programName=$params['prog'];
	$stmt=$db->prepare("SELECT id FROM program WHERE program_name='$programName'");
	$stmt->execute();
	$progid=$stmt->fetchAll(PDO::FETCH_OBJ);
	$progid= $progid[0]->id;
	//echo  $progid." ".$year." ".$month;
	$stmt2 = $db->prepare( "SELECT processed_date FROM attendance WHERE year=$year AND month=$month AND program_id=$progid");
	$stmt2->execute();
	
	$dates=$stmt2->fetchAll(PDO::FETCH_OBJ);
	//print_r($dates);
	$x=sizeof($dates);
	$data=Array();
	for($i=0;$i<$x;$i++)
	{
		if($dates[$i]->processed_date!='')
			array_push($data,$dates[$i]->processed_date);
	}
	echo json_encode($data);

				
	//xhttp_response_code($success);
}
?>
