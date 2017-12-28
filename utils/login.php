<?php 
	include("config.php");
	include('mainClass.php');
	$mainClass = new mainClass();

	if ( $_SERVER['REQUEST_METHOD'] === 'GET'  || !isset($_POST['password']) || empty($_POST['password']) || !isset($_POST['email']) || empty($_POST['email']) ) 
	{
		http_response_code($badRequest);
	}

	else
	{
		$email=$_POST['email'];
		$password=$_POST['password'];

		if(strlen(trim($email))>1 && strlen(trim($password))>1 )
		{

		    $uid = $mainClass->userLogin($email,$password);
		    if($uid)
		    {
		        http_response_code($success); 
		    }
		}

	}


?>
