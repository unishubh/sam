<?php

 	header("Access-Control-Allow-Origin: *");
	include('constants.php');
	session_start();
	/* DATABASE CONFIGURATION */
	define('DB_SERVER', 'localhost');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('DB_DATABASE', 'portal');
	// define("BASE_URL", "http://localhost/LeadManagementSystem/"); // Eg. http://yourwebsite.com


	function getDB()
	{
		$dbhost=DB_SERVER;
		$dbuser=DB_USERNAME;
		$dbpass=DB_PASSWORD;
		$dbname=DB_DATABASE;
		try {
		$dbConnection = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
		$dbConnection->exec("set names utf8");
		$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	    }
	    catch (PDOException $e) {
	    	http_response_code($connection_error);
		}

	}

	//getDB();
?>