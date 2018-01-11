<?php
	require_once 'dbconfig.php';	
	session_start();
	
	var_dump($_SESSION);
	if(!isset($_SESSION['role']))
	{
		//not logged in
		echo json_encode(0);
		// http_response_code(403);

	}else if(isset($_SESSION['role']))
	{
		//logged in
		echo json_encode(1);
	}

?>