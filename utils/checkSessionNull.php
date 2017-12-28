 <?php
 	include('config.php');
 	 header("Access-Control-Allow-Origin: *");
	 if(!isset($_SESSION['role']))
	 {
	    echo true;
	 }
	 else
	 {
	 	echo false;
	 }

		
?>