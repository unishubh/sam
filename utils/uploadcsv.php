<?php

include('config.php');
include('mainClass.php');
$db = getDB();
		echo '1';

if(true){
		echo '2';
		
		
		//print_r($_FILES); 
		$filename=$_FILES["csvFile"]["tmp_name"];	
		print_r($_FILES);
 
 
		 if($_FILES["csvFile"]["size"] > 0)
		 {
		 			  	$file = fopen($filename, "r");
		 	for($i=0;$i<=2;$i++)
		 	{	
		     	$getData = fgetcsv($file, 10000, ",");
		     }


		    // $getData = fgetcsv($file, 10000, ",");
		     //echo $getData;
		     //echo '<br>';
		  	// $ar = explode(',',$getData);
		  	// print_r($ar);
		  	







	        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
	         {

	         	 $t = $getData[1];
		  	echo $t;
		  	 $stmt2 = $db->prepare("SELECT id,program_id FROM students WHERE rollno ='$t' ");
		  	 $stmt2->execute();
			 $data=$stmt2->fetchAll(PDO::FETCH_ASSOC);
			 print_r($data);
			// echo $data[0]['id'];
			// echo $data[0]['program_id'];
		  	// print_r($ar);

			 /*$str='';
			 $str .= $data[0]['id'].',';
			 $str .= $data[0]['program_id'].',';
			 $str .= $year.',';
			 $str .= $month.',';
			 $str .= $getData[5];
			 $str .= $getData[6];*/
 
 
	           $sql = "INSERT into attendance (student_id,program_id,year,month,present,absent,sanctioned,medical,contingency,maternity,duty,work_off,payable,form_submitted,report_generated) 
                   values ('$data[0]['id']','$data[0]['program_id']','$year','$month','$getData[5]','$getData[6]','$getData[16]','$getData[13]','$getData[10]','0','0','0','0','0','0')";
                $stmt2 = $db->prepare($sql);
		  	 $stmt2->execute(); 
				if(!isset($result))
				{
					echo "Err";		
				}
				else {
					  echo "No err";
				}
				break;
	         }
			
	         fclose($file);	
		 }
	}	 
 
 
 ?>