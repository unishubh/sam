<?php
ini_set('display_errors',1);
		  	 		ini_set('display_startup_errors',1);
		  	 		error_reporting(E_ALL);
include('config.php');
include('mainClass.php');
$db = getDB();
		echo '1';

if(true){
		echo '2'." ";
		
	
									//echo $cess;
		
		//print_r($_FILES); 
		$filename=$_FILES["allStudentsCsvFile"]["tmp_name"];	
		//$filename = 'sampledata.csv';
		//print_r($_FILES);

 		$x=0;
		 //if($_FILES["csvFile"]["size"] > 0)
		// {
		 	$file = fopen($filename, "r");
		 	$stmt = $db->prepare("SELECT id,program_name,tenure_months FROM program ");
			$stmt->execute();		
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			$programname=Array();
			//$programid = Array();
			$x=sizeof($data);
			for($i=0;$i<$x;$i++)
			{
				$programname[$i]=$data[$i]->program_name;
				$programid[$programname[$i]]=$data[$i]->id;
				$tenure[$programname[$i]]=$data[$i]->tenure_months;
			}
print_r($programid);
print_r($tenure); 

			$stmt = $db->prepare("SELECT rollno FROM students ");
			$stmt->execute();		
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			$rollno=Array();
			$x=sizeof($data);
			for($i=0;$i<$x;$i++)
			{
				$rollno[$i]=$data[$i]->rollno;
			}
		//print_r($data);
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

		 			if(in_array($getData[7],$programname)){
		 				if(!in_array($getData[0],$rollno)){


									$dates=explode('-',$getData[8]);
									$year=(int)$tenure[$getData[7]];
									//echo $year." ";
									$year=(int)floor($year/12);
									//echo $year." ";
									$months=(int)$tenure[$getData[7]];
									//echo $months;
									$months%=12;
									//echo $months." ";
									$year_=(int)$dates[0];
									$year_+=$year;
									//echo $year_." ";
									$months_=(int)$dates[1];
									$months_+=$months;
									if($months_>12){
										$year_=$year_+1;
										$months_=$months_-12;
									}
									//echo $months_." ";
									$year_s=(string)$year_;
									$months_s=(string)$months_;
									if(strlen($months_s)==1)$months_s='0'.$months_s;
									$cess=$year_s.'-'.$months_s.'-'.$dates[2];

									$id = (int)$programid[$getData[7]];
									if(strtolower($getData[9])=='yes'){
										$schl=1;
									}
									else if(strtolower($getData[9]=='no')){
										$schl=0;
									}
						 
						 			$sql = "INSERT INTO students VALUES ('','$getData[0]','$getData[1]','$getData[2]','$getData[3]','$getData[4]','$getData[5]','$getData[6]','$getData[7]','$id','$getData[8]','$cess','$schl','1')";
						 			$stmt2 = $db->prepare($sql);
						  	 		$stmt2->execute(); 
						  	 		array_push($rollno, $getData[0]);

						  	 	}
		  	 	}
		  	 	else{
		  	 		$x=1;

		     		//print_r("dsadsdasdsa");
		     		 
		  	 	}
		     		


		     	}
		     
		     }


		   



		     return $x;




	       
			
	         fclose($file);	
		 //}
	}	 
 
 
 ?>