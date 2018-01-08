<?php
ini_set('display_errors',1);
		  	 		ini_set('display_startup_errors',1);
		  	 		error_reporting(E_ALL);
include('config.php');
include('mainClass.php');
$db = getDB();
		//echo '1';

if(true){
		//echo '2';
		
		
		//print_r($_FILES); 
		$filename=$_FILES["csvFile"]["tmp_name"];	
		//print_r($_POST);
		//print_r($_FILES);

 
 
		 if($_FILES["csvFile"]["size"] > 0)
		 {
		 	$file = fopen($filename, "r");
		 	for($i=0;$i<=4;$i++)
		 	{	
		     	$getData = fgetcsv($file, 10000, ",");
		     }
		     $month=$_POST["month"];
		     $year=$_POST["year"];


		    // $getData = fgetcsv($file, 10000, ",");
		     //echo $getData;
		     //echo '<br>';
		  	// $ar = explode(',',$getData);
		  	// print_r($ar);
		  	







	        while(!feof($file))
	         {
	         	$getData = fgetcsv($file, 10000, ",");
	         	 $t = $getData[2];
		  	//echo $t;
		  	 $stmt2 = $db->prepare("SELECT id,program_id FROM students WHERE rollno ='$t' ");
		  	 $stmt2->execute();
			 $data=$stmt2->fetchAll(PDO::FETCH_ASSOC);
			// print_r($data);
			// echo $data[0]['program_id'];
			 $stmt2 = $db->prepare("SELECT stipend FROM program WHERE id = '".$data[0]['program_id']."' " );
		  	 $stmt2->execute();
			 $stip=$stmt2->fetchAll(PDO::FETCH_ASSOC);
			 $payable=$stip[0]['stipend'];
			 //print_r($data);

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
 			$months = [31,28,31,30,31,30,31,31,30,31,30,31];
			if($year%4 ==0 && $year%100!=0)
				$months[1] == 29;

			$p=$months[$month-1]+3;
			$a=$p+1;
			//echo "$p:".$p;
			//echo "$a:".$a;

 
	           $sql = "INSERT into attendance (student_id,program_id,year,month,present,absent,sanctioned,medical,contingency,maternity,duty,work_off,payable,form_submitted) 
                   values ('".$data[0]['id']."','".$data[0]['program_id']."','$year','$month','".$getData[$p]."','".$getData[$a]."','0','0','0','0','0','0','$payable','0')";
                 //  echo $sql;
                $stmt2 = $db->prepare($sql);
		  	 $result=$stmt2->execute(); 
				if(isset($result))
				{
					echo "successfully uploaded csv file";		
				}
				else {
					  echo "file upload was unsuccessful";
				}
				break;
	         }
			
	         fclose($file);	
		 }
	}	 
 
 
 ?>