<?php

include('config.php');
include('mainClass.php');
$db = getDB();
		echo '1';

if(true){
		echo '2';
		
		
		//print_r($_FILES); 
		$filename=$_FILES["allStudentsCsvFile"]["tmp_name"];	
		//$filename = 'sampledata.csv';
		//print_r($_FILES);
 
 
		 //if($_FILES["csvFile"]["size"] > 0)
		// {
		 	$file = fopen($filename, "r");
		 	$i=0;
		 	while(!feof($file))
		 	{	
		 		if($i == 0)
				{
		 			$getData = fgetcsv($file, 10000, ",");
		 			$i++;
		 			continue;
		 		}
		 		else
		 		{
		 			$getData = fgetcsv($file, 10000, ",");
		 			
		 			if($getData[2]=="male")
		 			{
		 				$getData[2]=1;
		 			}
		 			else
		 			{
		 				$getData[2]=0;
		 			}

		 			
		 			$sql = "INSERT INTO students VALUES ('','$getData[0]','$getData[1]','$getData[2]','$getData[3]','$getData[4]','$getData[5]','$getData[6]','$getData[7]','$getData[8]','$getData[9]','$getData[10]','$getData[11]')";
		 			$stmt2 = $db->prepare($sql);
		  	 		$stmt2->execute(); 
		     		//print_r("dsadsdasdsa");
		     		


		     	}
		     
		     }


		   








	       
			
	         fclose($file);	
		 //}
	}	 
 
 
 ?>