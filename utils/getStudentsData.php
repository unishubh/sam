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
	//console.log("asdas");
	// $tabsArray=array("New","Approved By Renter","Price Card Sent","Quotation Sent","Renter Confirmed","Supplier Confirmed","Paperwork And Advance Done","Lead Final Stage","Moved To Orders","Rejected");
	$params = array();
	$params = $_REQUEST;
	if( !empty($params['search']['value']) ) { 
		$searchString=$params['search']['value'];
	}else{
		$searchString="";
	}
	$pageNo=intval($params['start']);
	$numberOfPages=intval($params['length']);
	$draw = intval($params['draw']);
	$orderColNo = intval($params['order'][0]['column']);
	$orderCol = $params['columns'][$orderColNo]['data'];
	$orderDir = $params['order'][0]['dir'];
	if(!isset($numberOfPages) || empty($numberOfPages)) $numberOfPages=10;

	$tab=$params['tab'];
	$month=$params['month'];
	$year=$params['year'];
	
	if($tab == 'all')
	{
		$responseObj = ($mainClass->getAllStudents($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir));
	
		if($responseObj)
		{
			echo json_encode($responseObj);
			http_response_code($success);
		}
	}else if($tab == 'currentMonth')
	{

		$responseObj=($mainClass->getthisMonthStudents($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir,$month,$year));
	
		if($responseObj)
		{
			echo json_encode($responseObj);
			http_response_code($success);
		}
	}
	
	
		
	
}
?>
