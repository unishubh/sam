
<?php
date_default_timezone_set("Asia/Kolkata");
class mainClass
{

	public function userLogin($email,$password)
	{
		try{

			$db = getDB();
			// $hash_password= hash('sha256', $password);
			$stmt = $db->prepare("SELECT * FROM users WHERE email=:email AND  password=:password");
			$stmt->bindParam("email", $email,PDO::PARAM_STR) ;
			$stmt->bindParam("password", $password,PDO::PARAM_STR) ;
			$stmt->execute();
			$count=$stmt->rowCount();
			$data=$stmt->fetch(PDO::FETCH_OBJ);

			if($count==1)
			{
				
				$_SESSION['role']=$data->role;
				 echo var_dump($_SESSION);
				return true;
				

			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
		}
		catch(PDOException $e) {
			echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);
		}
	}

	public function getAllStudents($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
	{
		try{
	 		
			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;
		
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM students WHERE enrolled=1 AND  (name LIKE :searchString OR email LIKE :searchString OR rollno LIKE :searchString OR program_name LIKE :searchString OR program_start_date LIKE :searchString OR end_date LIKE :searchString)  ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
			$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
			$stmt->execute();
			
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			//print_r($data);
			$x=sizeof($data);
			for($i=0;$i<$x;$i++)
			{
				$data[$i]->id=$i+1;
			}
			// $data=$this->getLeadArray($data);
		
			$stmt = $db->prepare("SELECT * FROM students WHERE enrolled = 1 AND (name LIKE :searchString OR email LIKE :searchString OR rollno LIKE :searchString OR program_name LIKE :searchString OR program_start_date LIKE :searchString OR end_date LIKE :searchString) ");
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->execute();
			$allCount = $stmt->rowCount(); 
			
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['studentArray']=$json_data;
			// $detail['columns']= $columns;
			// $detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
				return $detail;
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				return false;
			}    

		}
		catch(PDOException $e) {
			echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);
		}   
	}











public function getAllPrograms($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
	{
		try{
	 		
			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;
		
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM program WHERE id LIKE :searchString OR program_name LIKE :searchString OR stipend LIKE :searchString OR tenure_months LIKE :searchString OR sanctioned LIKE :searchString OR medical LIKE :searchString OR contingency LIKE :searchString OR  maternity LIKE :searchString OR duty LIKE :searchString  ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
			$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
			$stmt->execute();
			
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			// $data=$this->getLeadArray($data);
		
			$stmt = $db->prepare("SELECT * FROM program WHERE id LIKE :searchString OR program_name LIKE :searchString OR stipend LIKE :searchString OR tenure_months LIKE :searchString OR sanctioned LIKE :searchString OR medical LIKE :searchString OR contingency LIKE :searchString OR  maternity LIKE :searchString OR duty LIKE :searchString");
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->execute();
			$allCount = $stmt->rowCount(); 
			
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['programArray']=$json_data;
			// $detail['columns']= $columns;
			// $detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
				return $detail;
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				return false;
			}    

		}
		catch(PDOException $e) {
			echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);
		}   
	}

public function getMonthlyData($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir,$month,$year)
	{
		try{
	 		
			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;
		
			$db = getDB();
			$stmt = $db->prepare("SELECT attendance.form_submitted,attendance.payable as payable,attendance.id,students.rollno as rollno,students.email as email,students.program_start_date,students.end_date,students.name,students.program_name,attendance.present,attendance.absent,attendance.sanctioned,attendance.medical,attendance.contingency,attendance.duty,attendance.work_off,attendance.payable,attendance.form_submitted, attendance.processed_date, students.phone,attendance.maternity FROM attendance LEFT JOIN students ON attendance.student_id = students.id WHERE (month=$month AND year=$year) AND ( students.name LIKE :searchString OR program_name LIKE :searchString OR students.phone LIKE :searchString OR email LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
			$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
			$stmt->execute();
			
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			// $data=$this->getLeadArray($data);
		
			$stmt = $db->prepare("SELECT attendance.payable as payable,attendance.id,students.rollno as rollno,students.email as email,students.program_start_date,students.end_date,students.name,students.program_name,attendance.present,attendance.absent,attendance.sanctioned,attendance.medical,attendance.contingency,attendance.duty,attendance.work_off,attendance.payable,attendance.form_submitted, attendance.processed_date, students.phone,attendance.maternity FROM attendance LEFT JOIN students ON attendance.student_id = students.id WHERE (month=$month AND year=$year) AND ( students.name LIKE :searchString OR program_name LIKE :searchString OR students.phone LIKE :searchString OR email LIKE :searchString) ");
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->execute();
			$allCount = $stmt->rowCount(); 
			
			$stmt = $db->prepare("SELECT program.sanctioned, program.medical, program.contingency,program.maternity,program.duty, SUM(attendance.sanctioned) total_sanctioned,SUM(attendance.medical) total_medical,SUM(attendance.contingency) total_contingency,SUM(attendance.duty) total_duty,SUM(attendance.maternity) total_maternity FROM attendance JOIN students  ON attendance.student_id = students.id 
JOIN program ON students.program_id = program.id WHERE month = $month AND year = $year GROUP BY student_id LIKE :searchString");
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->execute();
			$allowed = $stmt->fetchAll(PDO::FETCH_OBJ); 


			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data,
			"allowed"         => $allowed
			);

			$detail=array();
			$detail['studentArray']=$json_data;
			$db = null;
			if($detail)
			{
				return $detail;
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				return false;
			}    

		}
		catch(PDOException $e) {
			echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);
		}   
	}

// 	public function saveRecords($month,$year,$student_id,$program_name,$presents,$absents,$sl,$ml,$cl,$maternity,$duty)
// 	{
// 		$db = getDB();

// 		echo "sl is ".$sl."<br>";
// 		//echo typeof($sl);
// 		if($sl == "")
// 		{
// 			echo "empty";
// 			$sl = 0;
// 			$sldate = array();
// 		}
// 		else
// 		{
// 			$sldate = explode(',',$sl);
// 		$sl= sizeof($sldate);
// 		}
		
// 		if($ml == "")
// 		{
// 			$ml =0;
// 			$mldate = array();
// 		}
// 		else
// 		{
// 			$mldate = explode(',',$ml);
// 		$ml= sizeof($ml);
// 		}
		
// 		if($cl == "")
// 		{
// 			$cl =0;
// 			$cldate = array();
// 		}
// 		else
// 		{
// 			$cldate = explode(',',$cl);
// 		$cl= sizeof($cl);
// 		}
		
// 		if($maternity == "")
// 		{
// 			$maternity ==0;
// 			$maternitydate = array();
// 		}
// 		else
// 		{
// 			$maternitydate = explode(',',$maternity);
// 		$maternity= sizeof($maternity);
// 		}
		
// 		if($duty = 0)
// 		{
// 			$duty =0;
// 			$dutydate = array();
// 		}
// 		else
// 		{
// 		$dutydate=explode(',',$duty);
// 		$duty= sizeof($duty);

// 	}





// 		print_r($sldate);
// 		echo "<br>";








	
// 		$stmt = $db->prepare("SELECT COUNT(*) as total, SUM(sanctioned) sanctioned, SUM(contingency) cl, SUM(medical) ml, SUM(maternity) maternity, SUM(duty) duty  FROM attendance WHERE student_id = (SELECT student_id FROM attendance WHERE id=:student_id) GROUP BY student_id");
// 		$stmt->bindParam("student_id", $student_id,PDO::PARAM_STR);
// 		$stmt->execute();
// 		$previous_count=$stmt->fetchAll(PDO::FETCH_OBJ);
// 		//var_dump($previous_count);
// 		$previous_count = $previous_count[0];
// 		//var_dump($previous_count);
// 		// echo'<pre>';
// 		// echo $previous_count[0]->sanctioned;
// 		// echo '</pre>';

// 		$stmt2 = $db->prepare("SELECT * FROM program WHERE program_name= :program_name");

// 		$stmt2->bindParam("program_name", $program_name,PDO::PARAM_STR);
// 		$stmt2->execute();
// 		$allowed_count=$stmt2->fetchAll(PDO::FETCH_OBJ);
// 		//var_dump($allowed_count);
// 		$allowed_count = $allowed_count[0];
// 		//echo "<br>";
// 		//var_dump( $allowed_count);
// 		//echo $allowed_count->sanctioned;

// 		// echo $previous_count[0]->cl.'<br>';
// 			// echo $allowed_count->contingency;




// 		if((int)$allowed_count->sanctioned-(int)$previous_count->sanctioned <$sl)
// 			return '-1';
// 		if($allowed_count->contingency - $previous_count->cl < $cl)
// 		{
			
// 			echo $allowed_count->contingency - $previous_count->cl."<br>" ;
// 			return '-2';
// 		}
// 		if($allowed_count->medical - $previous_count->ml < $sl )
// 			return '-3';
// 		if($allowed_count->maternity - $previous_count->maternity < $maternity )
// 			return '-4';
// 		if($allowed_count->duty - $previous_count->duty < $duty)
// 			return '-5';


// 		$stmt2 = $db->prepare("SELECT stipend FROM program WHERE program_name= :program_name");

// 		$stmt2->bindParam("program_name", $program_name,PDO::PARAM_STR);
// 		$stmt2->execute();
// 		$stipend=$stmt2->fetchAll(PDO::FETCH_OBJ);
// 		//var_dump($stipend[0]->stipend);
// 		$stipend= $stipend[0]->stipend;
// 		//$stipend = 12400;
// 		$stmt2 = $db->prepare("SELECT sanctioned,medical,contingency,maternity,duty,work_off FROM attendance WHERE id=$student_id");

// 		//$stmt2->bindParam("stu", $student_id,PDO::PARAM_STR);
// 		$stmt2->execute();
// 		$leaves_claimed=$stmt2->fetchAll(PDO::FETCH_OBJ);
// 		//var_dump($leaves_claimed[0]);
// 		$leaves_claimed = $leaves_claimed[0];

// 		echo $absents."<br>";
// 		echo  $leaves_claimed->sanctioned."<br>";
// 		echo $leaves_claimed->medical;
// 		if($absents< $leaves_claimed->sanctioned+$leaves_claimed->medical+$leaves_claimed->contingency+$leaves_claimed->maternity+$leaves_claimed->duty+$sl+$ml+$cl+$maternity+$duty)
// 		{
// 			echo "Bahr hai";

// 			return -11;	
// 		}

// 		$presents = $presents + ($leaves_claimed->work_off+$sl+$ml+$cl+$maternity+$duty+$leaves_claimed->contingency+$leaves_claimed->medical+$leaves_claimed->sanctioned+$leaves_claimed->maternity+$leaves_claimed->duty);
// 		if($absents<0)
// 			return -10;

// 		$months = [31,28,31,30,31,30,31,31,30,31,30,31];
// 		if($year%4==0)
// 			$months[1] == 29;
// 		$amount = ($stipend/$months[$month])*($presents);
// 		echo $amount;

// 		$stmt2 = $db->prepare("SELECT student_id FROM attendance WHERE id=$student_id");

// 		$stmt2->bindParam("stu", $student_id,PDO::PARAM_STR);
// 		$stmt2->execute();
// 		$ide=$stmt2->fetchAll(PDO::FETCH_OBJ);
// 		$ide=$ide[0]->student_id;
// 		echo "SL DATE IS".sizeof($sldate).'<br>';
// 	//	echo $mldata;
// 		//var_dump($ide);
// 		if($sl != 0)
// 		{
// 		$type="sl";
// 		$sldate = implode('-',$sldate);
// 		$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ($student_id, '$sldate', '$type')";
// 		echo $qry;
// 		$stmt = $db->prepare($qry);
// 		$stmt->execute();
// 	}

// 		if($ml != 0)
// 		{
// 		$type="ml";
// 		$sldate = implode('-',$mldate);
// 		$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ($student_id, '$sldate', '$type')";
// 		echo $qry;
// 		$stmt = $db->prepare($qry);
// 		$stmt->execute();
// 	}


// 		if($cl != 0)
// 		{
// 		$type="cl";
// 		$sldate = implode('-',$cldate);
// 		$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ($student_id, '$sldate', '$type')";
// 		echo $qry;
// 		$stmt = $db->prepare($qry);
// 		$stmt->execute();
// }

// 		if($maternity != 0)
// 		{
// 		$type="maternity";
// 		$sldate = implode('-',$maternitydate);
// 		$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ($student_id, '$sldate', '$type')";
// 		echo $qry;
// 		$stmt = $db->prepare($qry);
// 		$stmt->execute();
// }



		
		
// 		$stmt = $db->prepare("UPDATE attendance SET payable=$amount,sanctioned = $sl+$leaves_claimed->sanctioned, medical = $ml+$leaves_claimed->medical, contingency = $cl+$leaves_claimed->contingency,maternity = $maternity+$leaves_claimed->maternity, duty=$duty+$leaves_claimed->duty ,form_submitted = 1 WHERE id=$student_id AND month = $month AND year = $year");
// 		$stmt->execute();
// 		http_response_code("200");
// 		return 1;


// 	}




public function saveRecords($month,$year,$student_id,$rollno,$program_name,$presents,$absents,$sl,$ml,$cl,$maternity,$duty,$work_off,$form_submit,$currdate)
	{
		//echo "Id is ".$student_id;
		$db = getDB();
		//$work_off = 375;
		//echo "sl is ".$sl."<br>";
		//echo typeof($sl);

		$stmt = $db->prepare("SELECT  * FROM students WHERE id = :studen_id ");
		//echo $student_id;
		$stmt->bindParam("studen_id", $student_id,PDO::PARAM_STR);
		$stmt->execute();
		$gender=$stmt->fetchAll(PDO::FETCH_OBJ);
		//var_dump($previous_count);
		$gender = $gender[0]->gender;


		if($sl == "")
		{
			//echo "empty";
			$sl = 0;
			$sldate = array();
		}
		else
		{
			$sldate = explode(',',$sl);
			$sl= sizeof($sldate);
		}
		
		if($ml == "")
		{
			$ml =0;
			$mldate = array();
		}
		else
		{
			$mldate = explode(',',$ml);
		$ml= sizeof($mldate);
		}
		
		if($cl == "")
		{
			$cl =0;
			$cldate = array();
		}
		else
		{
			$cldate = explode(',',$cl);
		$cl= sizeof($cldate);
		}
		
		if($gender==1 || $maternity == "" )
		{
			$maternity =0;
			$maternitydate = array();
		}
		else
		{
			$maternitydate = explode(',',$maternity);
		$maternity= sizeof($maternitydate);
		}

		if($duty == "")
		{
			$duty =0;
			$dutydate = array();
		}
		else
		{
			$dutydate = explode(',',$duty);
		$duty= sizeof($dutydate);
		}

		if($work_off == "")
		{
			$work_off =0;
			$work_offdate = array();
		}
		else
		{
			$work_offdate = explode(',',$work_off);
		$work_off= sizeof($work_offdate);
		}
		
		if($form_submit==0)
		{
			$currdate=NULL;
		}






		/*print_r($sldate);*/
		//echo "<br>";








	
		$stmt = $db->prepare("SELECT COUNT(*) as total, SUM(sanctioned) sanctioned, SUM(contingency) cl, SUM(medical) ml, SUM(maternity) maternity, SUM(duty) duty , SUM(work_off) work_off  FROM attendance WHERE student_id = :studen_id GROUP BY student_id");
		//echo $student_id;
		$stmt->bindParam("studen_id", $student_id,PDO::PARAM_STR);
		$stmt->execute();
		$previous_count=$stmt->fetchAll(PDO::FETCH_OBJ);
		//var_dump($previous_count);
		$previous_count = $previous_count[0];
		//echo "Previos count is <br>";
		//var_dump($previous_count);
		// echo'<pre>';
		// echo $previous_count[0]->sanctioned;
		// echo '</pre>';

		$stmt2 = $db->prepare("SELECT * FROM program WHERE program_name= :program_name");

		$stmt2->bindParam("program_name", $program_name,PDO::PARAM_STR);
		$stmt2->execute();
		$allowed_count=$stmt2->fetchAll(PDO::FETCH_OBJ);
		//var_dump($allowed_count);
		$allowed_count = $allowed_count[0];
		//echo "Showing allowed cpunts <br>";
		//var_dump( $allowed_count);
		//echo $allowed_count->sanctioned;

		// echo $previous_count[0]->cl.'<br>';
			// echo $allowed_count->contingency;




		if((int)$allowed_count->sanctioned-(int)$previous_count->sanctioned <$sl)
			return -1;
		if($allowed_count->contingency - $previous_count->cl < $cl)
		{
			
			//echo $allowed_count->contingency - $previous_count->cl."<br>" ;
			return -2;
		}
		if($allowed_count->medical - $previous_count->ml < $ml )
			return -3;
		if($allowed_count->maternity - $previous_count->maternity < $maternity )
			return -4;
		if($allowed_count->duty - $previous_count->duty < $duty)
			return -5;
		if($allowed_count->work_off - $previous_count->work_off < $work_off)
			return -6;


		$stmt2 = $db->prepare("SELECT stipend FROM program WHERE program_name= :program_name");

		$stmt2->bindParam("program_name", $program_name,PDO::PARAM_STR);
		$stmt2->execute();
		$stipend=$stmt2->fetchAll(PDO::FETCH_OBJ);
		//var_dump($stipend[0]->stipend);
		$stipend= $stipend[0]->stipend;
		//$stipend = 12400;
		/*$stmt2 = $db->prepare("SELECT sanctioned,medical,contingency,maternity,duty,work_off FROM attendance WHERE id=$student_id");

		//$stmt2->bindParam("stu", $student_id,PDO::PARAM_STR);
		$stmt2->execute();
		$leaves_claimed=$stmt2->fetchAll(PDO::FETCH_OBJ);
		echo 'Leaves Claimed <br>';
		var_dump($leaves_claimed[0]);
		$leaves_claimed = $leaves_claimed[0];
*/
		//echo $absents."<br>";
		//echo  $leaves_claimed->sanctioned."<br>";
		//echo $leaves_claimed->medical;
		
		$stmt = $db->prepare("SELECT  * FROM attendance WHERE student_id = :studen_id AND month=$month AND year=$year");
		//echo $student_id;
		$stmt->bindParam("studen_id", $student_id,PDO::PARAM_STR);
		$stmt->execute();
		$month_count=$stmt->fetchAll(PDO::FETCH_OBJ);
		//var_dump($previous_count);
		$month_count = $month_count[0];
		

		$nsl=$sl+$month_count->sanctioned;
		$ncl=$cl+$month_count->contingency;
		$nml=$ml+$month_count->medical;
		$nmaternity=$maternity+$month_count->maternity;
		$nduty=$duty+$month_count->duty;
		$nwork_off=$work_off+$month_count->work_off;

		if($absents< $nsl+$nml+$ncl+$nmaternity+$nduty+$nwork_off)
		{
			//echo "Bahr hai";

			return -11;	
		}

		$presents = (int)$presents + ($nsl+$nml+$ncl+$nmaternity+$nduty+$nwork_off);
		//echo "Presents ".$presents;
		if($absents<0)
			return -10;

		$months = [31,28,31,30,31,30,31,31,30,31,30,31];
		if($year%4 ==0 && $year%100!=0)
			$months[1] == 29;
		$amount = ($stipend/$months[$month-1])*($presents);
		//echo "<br>Month is ".$month;
		//echo $amount;

	
	//	echo $mldata;
		//var_dump($ide);
		if($sl != 0)
		{
		$type="sl";
		$x=sizeof($sldate);
		for($d = 0 ; $d<$x;$d++){
			$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ('$student_id', '$sldate[$d]', '$type')";
		//echo $qry;
		$stmt = $db->prepare($qry);
		$stmt->execute();
		
	}
		
	}

		if($ml != 0)
		{
		$type="ml";
		$x=sizeof($mldate);
		for($d = 0 ; $d<$x;$d++){
			$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ('$student_id', '$mldate[$d]', '$type')";
		//echo $qry;
		$stmt = $db->prepare($qry);
		$stmt->execute();
		}
	}


		if($cl != 0)
		{
		$type="cl";
		$x=sizeof($cldate);
		for($d = 0 ; $d<$x;$d++){
			$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ('$student_id', '$cldate[$d]', '$type')";
		//echo $qry;
		$stmt = $db->prepare($qry);
		$stmt->execute();
		}
}

		if($maternity != 0)
		{
		$type="maternity";
		$x=sizeof($maternitydate);
		for($d = 0 ; $d<$x;$d++){
			$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ('$student_id', '$maternitydate[$d]', '$type')";
		//echo $qry;
		$stmt = $db->prepare($qry);
		$stmt->execute();
		}
}

	if($duty != 0)
		{
		$type="duty";
		$x=sizeof($dutydate);
		for($d = 0 ; $d<$x;$d++){
			$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ('$student_id', '$dutydate[$d]', '$type')";
		//echo $qry;
		$stmt = $db->prepare($qry);
		$stmt->execute();
		}
}

if($work_off != 0)
		{
		$type="work_off";
		$x=sizeof($work_offdate);
		for($d = 0 ; $d<$x;$d++){
			$qry="INSERT INTO dateattendance (student_id,dates,type) VALUES ('$student_id', '$work_offdate[$d]', '$type')";
		//echo $qry;
		$stmt = $db->prepare($qry);
		$stmt->execute();
		}
}


		//echo "from here";
		
		//echo $currdate;
		$stmt = $db->prepare("UPDATE attendance SET payable=$amount,sanctioned = sanctioned+$sl, medical =medical+ $ml, contingency =contingency+$cl,maternity = maternity+$maternity, duty=duty+$duty, work_off=work_off+$work_off, form_submitted=$form_submit , processed_date='$currdate'  WHERE student_id=$student_id AND month = $month AND year = $year");
		$stmt->execute();	



		//http_response_code("200");
		$m=$presents.",".$amount;
		//echo "sdf".$m;
		return $m;


	}






	













	












    public function getLeadsActivatedButPriceNotSent($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
		try{

			$db = getDB();

			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;

			$isActive=1;
			$activate=1;
			$priceCardSent=0;
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.activate=:activate AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.activate=:activate AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 

				$columns=array("Deactivate","Reject","Send Price Card","Edit/Delete");
			}
			else if(in_array("leads", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.activate=:activate AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.activate=:activate AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 

				$columns=array("Deactivate","Reject","Send Price Card","Edit/Delete");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.activate=:activate AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.activate=:activate AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 


				$columns=array("Deactivate","Send Price Card","Edit/Delete");
			}
			else
			{
				return false;
				http_response_code($GLOBALS['unauthorized']);
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    
	        
     	}
		catch(PDOException $e) {
			echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);
		}   
    }
    
    public function getLeadsPriceSentButQuotationNotSent($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
		try
		{
			$db=null;
			$db = getDB();

			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;

			$isActive=1;
			$activate=1;
			$priceCardSent=1;
			$quotationSent=0;
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Reject","Send Quotation","Edit/Delete");
		      	
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Reject","Edit/Delete");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='operator'))
			{

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.priceCardSent=:priceCardSent AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Send Quotation","Edit/Delete");
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    
		}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }

    public function getLeadsQuotationSentButNotAccepted($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
 		try{

			$db = getDB();

 	 		if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
            $searchString = '%'.$searchString.'%';
	        $limitPage=$pageNo;

			$isActive=1;
			$activate=1;
			$quotationSent=1;
			$renterAcceptQuotation=0;
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Reject","Renter Accept Quotation","Edit/Delete");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Reject","Renter Accept Quotation","Edit/Delete");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.quotationSent=:quotationSent AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Renter Accept Quotation","Edit/Delete");
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    
     	}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }

    public function getLeadsQuotationAcceptedButSupplierNotConfirmed($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
		try
		{
			$db = getDB();

 	 		if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
            $searchString = '%'.$searchString.'%';
	        $limitPage=$pageNo;

			$isActive=1;
			$activate=1;
			$supplierConfirmed=0;
			$renterAcceptQuotation=1;
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Reject");
			}
			else if(in_array("leads", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Reject");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Reject","Confirm Supplier");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Deactivate","Confirm Supplier");
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    
     	}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }

    public function getLeadsSupplierConfirmedButPaperworkNotDone($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
		$responseObj=new stdClass;
		try{

 	 		if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
            $searchString = '%'.$searchString.'%';
	        $limitPage=$pageNo;
		
			$db = getDB();
			$isActive=1;
			$activate=1;
			$paperWorkAndAdvance=0;
			$supplierConfirmed=1;
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Reject","Paperwork And Advance Done");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Reject","Paperwork And Advance Done");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Reject","Paperwork And Advance Done");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.isActive=:isActive AND leads.supplierConfirmed=:supplierConfirmed AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND lead_equipment.leadId=leads.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Reject","Paperwork And Advance Done");
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    
			
     	}
	     catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }
    
    public function getLeadsPaperworkDoneButNotConvertedToOrder($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
		$responseObj=new stdClass;
		try
		{
 	 		if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
            $searchString = '%'.$searchString.'%';
	        $limitPage=$pageNo;

			$db = getDB();
			$confirmed=1;
			$paperWorkAndAdvance=1;
			$cancelled=0;
			$reject=0;
			$isActive=1;

			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Convert To Order","Reject");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Convert To Order","Reject");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='operator'))
			{

				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Convert To Order","Reject");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager'))
			{

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.paperWorkAndAdvance=:paperWorkAndAdvance AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("Convert To Order","Reject");
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    
			
     	}
	     catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }
    
    public function getLeadsAcceptedButNotMovedToOrder($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
		$responseObj=new stdClass;
		try{

			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;

			$db = getDB();
			$confirmed=1;
			$accept=1;
			$cancelled=0;
			$reject=0;
			$isActive=1;

			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();

				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 


				$columns=array("Move To Orders","Reject");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();

				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 


				$columns=array("Move To Orders","Reject");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();

				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 


				$columns=array("Move To Orders","Reject");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();

				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.accept=:accept AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 


				$columns=array("Move To Orders","Reject");
			}
			
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}


			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
				return $detail;
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				return false;
			}    
		}
		catch(PDOException $e) {
			echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);
		}   
    }

	public function getLeadsMovedToOrders($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
	{
		$responseObj=new stdClass;
		try{

			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;

			$db = getDB();
			$confirmed=1;
			$movedToOrders=1;
			$cancelled=0;
			$reject=0;
			$isActive=1;
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("See Order Status");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("See Order Status");
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("See Order Status");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE lead_equipment.leadId=leads.id AND lead_equipment.confirmed=:confirmed AND lead_equipment.cancelled=:cancelled AND lead_equipment.movedToOrders=:movedToOrders AND leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
				$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL);
				$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array("See Order Status");
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    

     	}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }

    public function getLeadsRejected($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
	{
		$responseObj=new stdClass;
		try{

			if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;

			$db = getDB();
			$reject=1;
			$isActive=1;
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array();
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.leadManagerId=:leadManagerId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array();
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND leads.LeadOperatorId=:LeadOperatorId AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array();
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.leadId=leads.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.leadId=leads.id AND lead_equipment.fulfilmentManager=:fulfilmentManager AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array();
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='operator'))
			{
				$stmt = $db->prepare("SELECT DISTINCTROW leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.leadId=leads.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);
				$data=$this->getLeadArray($data);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName AS name,renters.phone AS phone FROM leads,renters,lead_equipment WHERE leads.reject=:reject AND leads.isActive=:isActive AND leads.customerId=renters.id AND lead_equipment.leadId=leads.id AND lead_equipment.fulfilmentOperator=:fulfilmentOperator AND (renters.renterName LIKE :searchString OR renters.phone LIKE :searchString OR leads.leadPriority LIKE :searchString OR leads.id LIKE :searchString OR leads.leadDate LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
				
				$columns=array();
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['columns']= $columns;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
			else
			{
			    http_response_code($GLOBALS['noContent']);
			    return false;
			}    

     	}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }

	public function getMyInfo()
	{
		$responseObj=new stdClass;
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM staff_login WHERE userId=:id AND isActive=:isActive");
			$isActive=1;
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$stmt->bindParam("id", $_SESSION['userIdLMS'],PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;
			if($data)
			{
				$responseObj->efficiencyShow=0;
				$responseObj->majorRole=$data->majorRole;
				$responseObj->name=$data->name;
				if(in_array("leads",$_SESSION['accessLevelLMS']))
				{		
					$responseObj->role="Leads";
				}
				else if(in_array("fulfillment",$_SESSION['accessLevelLMS']))
				{		
					$responseObj->role="Fulfillment";
				}
				else
				{
					http_response_code($GLOBALS['unauthorized']);
					return false;
				}
				$responseObj->newUser=1;		
				$responseObj->accessLevelShow=1;		
				if($_SESSION['majorRoleLMS']=="manager")
				{
					$responseObj->role=$responseObj->role." Manager";

				}
				else if($_SESSION['majorRoleLMS']=="superManager")
				{
					$responseObj->role="Super Manager";
					$responseObj->efficiencyShow=1;
				}
				else if($_SESSION['majorRoleLMS']=="operator")
				{
					$responseObj->role=$responseObj->role." Operator";
					$responseObj->newUser=0;
				}
				else 
				{
					$responseObj->role="Admin";
					$responseObj->accessLevelShow=0;
					$responseObj->efficiencyShow=1;
				}
				$responseObj->phone=$data->phone;
				if($responseObj->newUser)
				{
					$team=$this->getMyTeam();
					$responseObj->team=$team;
				}
				$responseObj->email=$data->email;
				return $responseObj;
	        }
	        else
	        {
	        	http_response_code($GLOBALS['unauthorized']);
                return false;
	        }
     	}
	      catch(PDOException $e) {
  			
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	    
    }

    public function getTabs()
    {
 	    $responseObj=new stdClass;
     	try{
     		if(($_SESSION['majorRoleLMS']=='admin')||($_SESSION['majorRoleLMS']=='superManager'))
			{
 				$tabsArray=array("New","Approved By Renter","Price Card Sent","Quotation Sent","Renter Confirmed","Supplier Confirmed","Paperwork And Advance Done","Lead Final Stage","Moved To Orders","Rejected");
 			}

			else if((in_array("leads", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='manager'))
			{
				$tabsArray=array("New","Approved By Renter","Price Card Sent","Quotation Sent","Renter Confirmed","Paperwork And Advance Done","Lead Final Stage","Moved To Orders","Rejected");
			}
			else if((in_array("leads", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='operator'))
			{
				$tabsArray=array("New","Approved By Renter","Price Card Sent","Quotation Sent","Rejected");

			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')))
			{
				// $tabsArray=array("Approved By Renter","Price Card Sent","Renter Confirmed","Supplier Confirmed","Paperwork And Advance Done","Lead Final Stage","Moved To Orders","Rejected");
				$tabsArray=array("Renter Confirmed","Supplier Confirmed","Paperwork And Advance Done","Lead Final Stage","Moved To Orders","Rejected");

			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='operator')))
			{
				$tabsArray=array("Renter Confirmed","Supplier Confirmed","Paperwork And Advance Done","Lead Final Stage","Moved To Orders","Rejected");

			}
			else 
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$responseObj->tabs=$tabsArray;
			if(((in_array("leads", $_SESSION['accessLevelLMS']))&&($_SESSION['majorRoleLMS']=='operator'||$_SESSION['majorRoleLMS']=='manager'))||$_SESSION['majorRoleLMS']=="superManager"||$_SESSION['majorRoleLMS']=="admin")
				$responseObj->addLead=1;
			else
				$responseObj->addLead=0;
			
			$id=$this->getMyInfo();
			$responseObj->name=$id->name;
			return $responseObj;
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();	
		}
	}

	public function getTypesOfWork()
	{
		$responseObj=new stdClass;
		try{
			if((in_array("leads", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin');
			else 
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM type_of_work");
			$stmt->execute();
			$typeofworkArray=$stmt->fetchAll(PDO::FETCH_COLUMN);
			for($i=0;$i<count($typeofworkArray);$i++)
			{
				if($typeofworkArray[$i]){}
				else $typeofworkArray[$i]="";
			}
			$db = null;
			return $typeofworkArray;
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();	
		}
	}

	public function getOrderStatus($leadId)
	{
		$responseObj=new stdClass;
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT oe.id AS orderEquipId,oe.orderId AS orderId FROM orders o,order_equipment oe WHERE o.leadId=:leadId AND o.isActive=:isActive AND oe.orderId=o.id");
			$stmt->bindParam("leadId", $leadId,PDO::PARAM_INT);
			$isActive=1;
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$stmt->execute();
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			$responseObj=new stdClass;
			$responseObj->orderId=0;
			$responseObj->orderEquipId=array();
			for($i=0;$i<count($data);$i++)
			{
				$responseObj->orderId=$data[$i]->orderId;
				array_push($responseObj->orderEquipId, $data[$i]->orderEquipId);
			}
			$db = null;
			return $responseObj;
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();	
		}
	}

    public function getMakes()
     {
 	    $responseObj=new stdClass;
     	try{
			if((in_array("leads", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin');
			else 
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM make");
			$stmt->execute();
			$makeArray=$stmt->fetchAll(PDO::FETCH_COLUMN);
			for($i=0;$i<count($makeArray);$i++)
			{
				if($makeArray[$i]){}
				else $makeArray[$i]="";
			}
			$db = null;

            return $makeArray;
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();	
	    }
     }

    public function getModels()
     {
 	    $responseObj=new stdClass;
     	try
     	{
			if((in_array("leads", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin');
			else 
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$db = getDB();
			$stmt = $db->prepare("SELECT Model FROM model");
			$stmt->execute();
			$modelArray=$stmt->fetchAll(PDO::FETCH_COLUMN);
			for($i=0;$i<count($modelArray);$i++)
			{
				if($modelArray[$i]){}
				else $modelArray[$i]="";
			}
			$db = null;

			return $modelArray;
     	}
	      catch(PDOException $e) 
	      {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();	
	    }
     }

    public function getEquipmentTypes()
     {
 	    $responseObj=new stdClass;
     	try{
			if((in_array("leads", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin');
			else
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			
			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM equipment_type");
			$stmt->execute();
			$eqTypeArray=$stmt->fetchAll(PDO::FETCH_COLUMN);
			$db = null;

            return $eqTypeArray;
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();	
	    }
     }

    public function getLeadArray($data)
     {
		try{

          	$leadArray=array();
			for($i=0;$i<count($data);$i++)
			{
				$leadObj=new stdClass;
				$leadObj->sno=(string)($i+1);
				$leadObj->customerId=$data[$i]->customerId;
				$leadObj->name=$this->getNameFromCustomerId($data[$i]->customerId);
				$leadObj->phone=$this->getPhoneFromCustomerId($data[$i]->customerId);
				$leadObj->typeOfWork=$this->getNameFromtypeOfWorkId($data[$i]->typeOfWork);
				$leadObj->leadSource=$this->getNameFromSourceId($data[$i]->sources);
				$date= new DateTime($data[$i]->leadDate);
				$leadObj->leadDate=$date->format('m/d/Y h:i A');
				$leadObj->leadPriority=$data[$i]->leadPriority;
				if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin')
				{
					$leadObj->countData=$this->getCountOfAssignedFM($data[$i]->id);
				}
				if($_SESSION['majorRoleLMS']=='manager'&&in_array("fulfillment", $_SESSION['accessLevelLMS']))
				{
					$leadObj->countData=$this->getCountOfAssignedFO($data[$i]->id);
				}
				$leadObj->activate=$data[$i]->activate;
				$leadObj->reject=$data[$i]->reject;
				$leadObj->accept=$data[$i]->accept;
				$leadObj->priceCardSent=$data[$i]->priceCardSent;
				$leadObj->quotationSent=$data[$i]->quotationSent;
				$leadObj->quotation=$data[$i]->quotation;
				$leadObj->renterAcceptQuotation=$data[$i]->renterAcceptQuotation;
				$leadObj->paperWorkAndAdvance=$data[$i]->paperWorkAndAdvance;
				$leadObj->supplierConfirmed=$data[$i]->supplierConfirmed;
				$leadObj->movedToOrders=$data[$i]->movedToOrders;
				$leadObj->id=$data[$i]->id;
				$equipments=$this->getAllEquipmentsFromLeadId($data[$i]->id);
				$equipmentArray=array();
				foreach ($equipments as $equipment) 
				{
					if(in_array("fulfillment", $_SESSION['accessLevelLMS']))
					{
						if($_SESSION['majorRoleLMS']=='manager')
						{
							if($equipment->fulfilmentManager==$_SESSION['userIdLMS']){}
							else continue;
						}
						if($_SESSION['majorRoleLMS']=='operator')
						{
							if($equipment->fulfilmentOperator==$_SESSION['userIdLMS']){}
							else continue;
						}
					}
					$equipObj=new stdClass;
					$equipObj->equipId=$equipment->id;
					$equipObj->equipmentStage=$this->getEquipmentStage($equipment);
					$equipObj->duration=$equipment->duration;
					$equipObj->location=$equipment->location;
					$equipObj->equipName=$this->getNameFromEquipmentId($equipment->equipmentId);
					$date = new DateTime($equipment->expectedStartDate);
    				$equipObj->startDate=$date->format('m/d/Y h:i A') ;
					$equipObj->make=$this->getNameFromMakeId($equipment->makeId);
					$equipObj->model=$this->getNameFromModelId($equipment->modelId);
					$equipObj->operationHoursPerDay=$equipment->operationalHoursInADay;
					$equipObj->operationDaysPerMonth=$equipment->operationalDaysInAMonth;
					if((in_array("fulfillment", $_SESSION['accessLevelLMS'])&&($_SESSION['majorRoleLMS']=='manager'))||($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin'))
					{
						$equipObj->fulfilmentOperator=$this->getNameAndPhoneOfFulfilmentOperator($equipment->fulfilmentOperator);
					}
					if(($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin'))
					{
						$equipObj->priceCard=$equipment->priceCard;
					}
					
					$equipObj->operationHoursPerMonth=$equipment->operationalHoursInAMonth;
					$equipObj->vehicleDocuments=$equipment->vehicleDocuments;
					$equipObj->confirmed=$equipment->confirmed;
					$equipObj->cancelled=$equipment->cancelled;
					$equipObj->operatorLicense=$equipment->operatorLicense;
					$equipObj->capacity=$equipment->capacity;
					$equipObj->accomodation=$equipment->accommodation;
					$equipObj->quantity=$equipment->quantity;
					$equipObj->food=$equipment->food;
					$equipObj->district=$equipment->district;
					$equipObj->year=$equipment->year;
					$equipObj->shiftTypeDay=$equipment->shiftTypeDay;
					$equipObj->shiftTypeNight=$equipment->shiftTypeNight;
					$equipObj->jobLocation=$equipment->location;
					$equipObj->stage=$equipment->projectStage;
					array_push($equipmentArray, $equipObj);
				}
				$leadObj->equipments=$equipmentArray;
				array_push($leadArray, $leadObj);
			}
			return $leadArray;
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getNameAndPhoneOfFulfilmentOperator($id)
     {
		try{

			$db = getDB();
			$accessLevels=$this->getAccessLevels($id);
			if(in_array("fulfillment", $accessLevels))
			{  
				$isActive=1;
				$majorRole="operator";
				$stmt = $db->prepare("SELECT name,phone FROM staff_login WHERE userId=:id AND majorRole=:majorRole AND isActive=:isActive");
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->bindParam("majorRole", $majorRole,PDO::PARAM_STR);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->execute();
				$data=$stmt->fetch(PDO::FETCH_OBJ);
				if($data)
				{
					return array($data->name,$data->phone);
				}
				else
				{
					return array();
				}
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

    public function getNameFromtypeOfWorkId($id)
     {
		try
		{

			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM type_of_work WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->name;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getEquipIdFromLeadEquipmentId($id)
	 {
		try{

			$db = getDB();
			$stmt = $db->prepare("SELECT equipmentId FROM lead_equipment WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->equipmentId;
			}
			else
			{
				return false;
			}	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getNameFromEquipmentId($id)
	 {
		try{

			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM equipment_type WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->name;
			}
			else
			{
				return false;
			}	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getAllEquipmentsFromLeadId($id)
	 {
		try
		{

			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM lead_equipment WHERE leadId=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			return $data; 	
     	}
		catch(PDOException $e) 
		{
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getNameFromSourceId($id)
	{
		try{

			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM sources WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->name;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}

	public function getCountOfAssignedFM($id)
	{
		try{

			$db = getDB();
			$stmt = $db->prepare("SELECT COUNT(*) AS assignedCount FROM lead_equipment WHERE fulfilmentManager IS NOT NULL AND leadId=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$responseObj=new stdClass;
			$responseObj->totalCount=0;
			$responseObj->totalCount=0;
			if($data)
			{
				$responseObj->assignedCount=$data->assignedCount;
				$stmt = $db->prepare("SELECT COUNT(*) AS totalCount FROM lead_equipment WHERE leadId=:id");
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->execute();
				$data=$stmt->fetch(PDO::FETCH_OBJ);
				$responseObj->totalCount=$data->totalCount;
				return $responseObj;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}

	public function getCountOfAssignedFO($id)
	{
		try{

			$db = getDB();
			$stmt = $db->prepare("SELECT COUNT(*) AS assignedCount FROM lead_equipment WHERE fulfilmentOperator IS NOT NULL AND leadId=:id AND fulfilmentManager=:fulfilmentManager");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$responseObj=new stdClass;
			if($data)
			{
				$responseObj->assignedCount=$data->assignedCount;
				$stmt = $db->prepare("SELECT COUNT(*) AS totalCount FROM lead_equipment WHERE leadId=:id AND fulfilmentManager=:fulfilmentManager");
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$data=$stmt->fetch(PDO::FETCH_OBJ);
				$responseObj->totalCount=$data->totalCount;
				return $responseObj;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}
	
	public function getNameFromCustomerId($id)
	 {
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT renterName FROM renters WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->renterName;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }
	
	public function getPhoneFromCustomerId($id)
	 {
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT phone FROM renters WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->phone;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getDetailsFromUserId($id)
	 {
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM staff_login WHERE userId=:id and isActive=:isActive");
			$isActive=1;
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_INT);
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				unset($data->password);
				unset($data->userId);
				unset($data->isActive);
				return $data;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getNameFromModelId($id)
	 {
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT Model FROM model WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->Model;
			}
			else
			{
				return "";
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

	public function getNameFromMakeId($id)
	 {
		try{

			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM make WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->name;
			}
			else
			{
				return "";
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	 }

    public function getSources()
     {
 	    $responseObj=new stdClass;
     	try{
			if((in_array("leads", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='manager')||($_SESSION['majorRoleLMS']=='operator')));
			else if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin');
			else 
			{
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			$db = getDB();
			$stmt = $db->prepare("SELECT name FROM sources");
			$stmt->execute();
			$sourceArray=$stmt->fetchAll(PDO::FETCH_COLUMN);
			for($i=0;$i<count($sourceArray);$i++)
			{
				if($sourceArray[$i]){}
				else $sourceArray[$i]="";
			}
			$db = null;
            return $sourceArray;
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();	
	    }
     }

    public function getTargets($leadEquipId)
     {
 	    $responseObj=new stdClass;
     	try{

     		$db = getDB();
			if((in_array("fulfillment", $_SESSION['accessLevelLMS'])&&($_SESSION['majorRoleLMS']=='manager')))
			{
				$stmt = $db->prepare("SELECT typeOfLeadFM AS typeOfLead,closedPriceFM AS closedPrice,profitMarginFM AS profitMargin,easeOfFulfillmentFM AS easeOfFulfillment FROM lead_equipment WHERE fulfilmentManager=:fulfilmentManager AND id=:id");
				$stmt->bindParam("id", $leadEquipId,PDO::PARAM_INT) ;
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT) ;
				$stmt->execute();
            	$data=$stmt->fetch(PDO::FETCH_OBJ);
			}
			else if((in_array("fulfillment", $_SESSION['accessLevelLMS']))&&(($_SESSION['majorRoleLMS']=='operator')))
			{
				$stmt = $db->prepare("SELECT typeOfLeadFO AS typeOfLead,closedPriceFO AS closedPrice,profitMarginFO AS profitMargin,easeOfFulfillmentFO AS easeOfFulfillment FROM lead_equipment WHERE fulfilmentOperator=:fulfilmentOperator AND id=:id");
				$stmt->bindParam("id", $leadEquipId,PDO::PARAM_INT) ;
            	$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT) ;
				$stmt->execute();
            	$data=$stmt->fetch(PDO::FETCH_OBJ);
            	
			}
			else if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin')
			{
				$stmt = $db->prepare("SELECT typeOfLeadFM AS typeOfLead,closedPriceFM AS closedPrice,profitMarginFM AS profitMargin,easeOfFulfillmentFM AS easeOfFulfillment FROM lead_equipment WHERE id=:id");
				$stmt->bindParam("id", $leadEquipId,PDO::PARAM_INT) ;
				$stmt->execute();
            	$data=$stmt->fetch(PDO::FETCH_OBJ);
            	
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
        		return false;
			}
			if($data)
        	{
        		$data->role=$_SESSION['majorRoleLMS'];
				return $data;
        	}
        	else
        	{
        		http_response_code($GLOBALS['noContent']);
        		return false;
        	}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();	
	    }
     }

    public function getAccessLevels($id)
    {
 	    $responseObj=new stdClass;
     	try{
			$db = getDB();
			$stmt = $db->prepare("SELECT access FROM access_level INNER JOIN access ON access.accessLevel=access_level.id AND access.userId=:id");
            $stmt->bindParam("id", $id,PDO::PARAM_INT) ;
            $stmt->execute();
            $count=$stmt->rowCount();
            $access=$stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            $accessLevelArray=array();
            for($i=0;$i<count($access);$i++)
            {
				$accessData=($access[$i]);
				array_push($accessLevelArray,$accessData->access); 
            }
            return $accessLevelArray; 	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();	
	    }
    }
    
    public function getMyManager()
    {
     	try{
			
			$db = getDB();
			$isActive=1;
			$stmt = $db->prepare("SELECT managerId FROM staff_login WHERE userId=:id AND isActive=:isActive");
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$stmt->bindParam("id", $_SESSION['userIdLMS'],PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;
			if($data)
			{
				return $data->managerId;
			}
			else
			{
				return false;
			}
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			$responseObj->status=$e->getMessage();
	    }
    }
    
    public function getMyTeam()
    {
     	$responseObj=new stdClass;
     	try
     	{
			$db = getDB();
			$isActive=1;
			if($_SESSION['majorRoleLMS']=="admin")
			{
				$stmt = $db->prepare("SELECT * FROM staff_login WHERE adminId=:adminId AND isActive=:isActive");
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("adminId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
			}
			else if($_SESSION['majorRoleLMS']=="manager")
			{
				$stmt = $db->prepare("SELECT * FROM staff_login WHERE managerId=:managerId AND isActive=:isActive");
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("managerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
			}
			else if($_SESSION['majorRoleLMS']=="superManager")
			{
				$stmt = $db->prepare("SELECT * FROM staff_login WHERE superManagerId=:superManagerId AND isActive=:isActive");
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$stmt->bindParam("superManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
			}
			else
			{
				$db=null;
				http_response_code($GLOBALS['unauthorized']);
				return false;
			}
			$stmt->execute();
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			for($i=0;$i<count($data);$i++)
			{
				$userAccessLevels=$this->getAccessLevels($data[$i]->userId);
				unset($data[$i]->password);

				if(in_array("order", $userAccessLevels)||in_array("field", $userAccessLevels))
				{
					$data[$i]->equipsAttempted=0;
					$data[$i]->equipsConfirmed=0;
				}
				else
				{
					if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
					{
						$data[$i]->equipsAttempted=$this->getAttemptedLeadEquipments($data[$i]);
						$data[$i]->equipsConfirmed=$this->getConfirmedLeadEquipments($data[$i]);

					}
				}
				if($data[$i]->majorRole=="manager")$data[$i]->majorRole="Manager";
				if($data[$i]->majorRole=="operator")$data[$i]->majorRole="Operator";
				if($data[$i]->majorRole=="superManager")$data[$i]->majorRole="Super Manager";
				if($userAccessLevels)$data[$i]->accessLevel=$userAccessLevels;
				else $data[$i]->accessLevel=array("N/A");
			}
			$db = null;
			return $data;	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	    
    }
    
    public function getAttemptedLeadEquipments($userData)
    {
     	
     	try
     	{

			$db = getDB();
			$isActive=1;
			$userId=$userData->userId;
			$accessLevels=$this->getAccessLevels($userId);
			if(in_array("leads", $accessLevels))
			{
				if($userData->majorRole=="operator")
				{	
					$isActive=1;
					$activate=1;
					$cancelled=0;
					$leadOperatorId=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment,leads WHERE lead_equipment.leadId=leads.id AND lead_equipment.cancelled=:cancelled AND leads.isActive=:isActive AND leads.activate=:activate AND leads.LeadOperatorId=:leadOperatorId");
					$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
					$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("leadOperatorId", $leadOperatorId,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else if($userData->majorRole=="manager")
				{	
					$isActive=1;
					$activate=1;
					$cancelled=0;
					$leadManagerId=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment,leads WHERE lead_equipment.leadId=leads.id AND lead_equipment.cancelled=:cancelled AND leads.isActive=:isActive AND leads.activate=:activate AND leads.leadManagerId=:leadManagerId");
					$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
					$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL);
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("leadManagerId", $leadManagerId,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else
				{
					return 0;
				}
				$data=$stmt->fetch(PDO::FETCH_OBJ);
				return $data->countEquipments;
			
			}
			else if(in_array("fulfillment", $accessLevels))
			{
				if($userData->majorRole=="operator")
				{	
					$cancelled=0;
					$fulfilmentOperator=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment WHERE cancelled=:cancelled AND fulfilmentOperator=:fulfilmentOperator");
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("fulfilmentOperator", $fulfilmentOperator,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else if($userData->majorRole=="manager")
				{	
					$cancelled=0;
					$fulfilmentManager=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment WHERE cancelled=:cancelled AND fulfilmentManager=:fulfilmentManager");
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("fulfilmentManager", $fulfilmentManager,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else
				{
					return 0;
				}
				$data=$stmt->fetch(PDO::FETCH_OBJ);
				return $data->countEquipments;
			}
			else
			{
				return 0;
			}

     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }

    public function getConfirmedLeadEquipments($userData)
    {
     	try
     	{

			$db = getDB();
			$isActive=1;
			$userId=$userData->userId;
			$accessLevels=$this->getAccessLevels($userId);
			if(in_array("leads", $accessLevels))
			{
				if($userData->majorRole=="operator")
				{	
					$isActive=1;
					$renterAcceptQuotation=1;
					$cancelled=0;
					$leadOperatorId=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment,leads WHERE lead_equipment.leadId=leads.id AND lead_equipment.cancelled=:cancelled AND leads.isActive=:isActive AND leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.LeadOperatorId=:leadOperatorId");
					$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
					$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("leadOperatorId", $leadOperatorId,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else if($userData->majorRole=="manager")
				{	
					$isActive=1;
					$renterAcceptQuotation=1;
					$cancelled=0;
					$leadManagerId=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment,leads WHERE lead_equipment.leadId=leads.id AND lead_equipment.cancelled=:cancelled AND leads.isActive=:isActive AND leads.renterAcceptQuotation=:renterAcceptQuotation AND leads.leadManagerId=:leadManagerId");
					$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
					$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL);
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("leadManagerId", $leadManagerId,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else
				{
					return 0;
				}
				$data=$stmt->fetch(PDO::FETCH_OBJ);
				return $data->countEquipments;
			
			}
			else if(in_array("fulfillment", $accessLevels))
			{
				if($userData->majorRole=="operator")
				{	
					$moveToOrders=1;
					$cancelled=0;
					$fulfilmentOperator=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment WHERE cancelled=:cancelled AND fulfilmentOperator=:fulfilmentOperator AND movedToOrders=:moveToOrders");
					$stmt->bindParam("moveToOrders", $moveToOrders,PDO::PARAM_BOOL);
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("fulfilmentOperator", $fulfilmentOperator,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else if($userData->majorRole=="manager")
				{	
					$moveToOrders=1;
					$cancelled=0;
					$fulfilmentManager=$userId;
					$stmt = $db->prepare("SELECT count(*) as countEquipments from lead_equipment WHERE cancelled=:cancelled AND fulfilmentManager=:fulfilmentManager AND movedToOrders=:moveToOrders");
					$stmt->bindParam("moveToOrders", $moveToOrders,PDO::PARAM_BOOL);
					$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
					$stmt->bindParam("fulfilmentManager", $fulfilmentManager,PDO::PARAM_BOOL);
					$stmt->execute();
				}
				else
				{
					return 0;
				}
				$data=$stmt->fetch(PDO::FETCH_OBJ);
				return $data->countEquipments;
			}
			else
			{
				return 0;
			}

     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }

    public function getLeadEquipments($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
     	
     	try{

			$db = getDB();
			$isActive=1;
			
			if($_SESSION['majorRoleLMS']=='admin'||$_SESSION['majorRoleLMS']=='superManager')
			{
				$stmt = $db->prepare("SELECT le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
	            $searchString = '%'.$searchString.'%';
	            $limitPage=$pageNo;
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->execute();
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);

				$stmt = $db->prepare("SELECT le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
			}
			
			else if(in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager')
			{
				$stmt = $db->prepare("SELECT DISTINCTROW le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.fulfilmentManager=:fulfilmentManager) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
	            $searchString = '%'.$searchString.'%';
	            $limitPage=$pageNo;
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);

				$stmt = $db->prepare("SELECT DISTINCTROW le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.fulfilmentManager=:fulfilmentManager) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
			}

			else if(in_array("fulfillment", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='operator')
			{
				$stmt = $db->prepare("SELECT DISTINCTROW le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.fulfilmentOperator=:fulfilmentOperator) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
	            $searchString = '%'.$searchString.'%';
	            $limitPage=$pageNo;
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);

				$stmt = $db->prepare("SELECT DISTINCTROW le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.fulfilmentOperator=:fulfilmentOperator) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
			}
			else if(in_array("leads", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='manager')
			{
				$stmt = $db->prepare("SELECT le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model,leads WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.leadId=leads.id AND leads.leadManagerId=:leadManagerId) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
	            $searchString = '%'.$searchString.'%';
	            $limitPage=$pageNo;
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);

				$stmt = $db->prepare("SELECT le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model,leads WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.leadId=leads.id AND leads.leadManagerId=:leadManagerId) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
			}
			else if(in_array("leads", $_SESSION['accessLevelLMS']) && $_SESSION['majorRoleLMS']=='operator')
			{
				$stmt = $db->prepare("SELECT le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model,leads WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.leadId=leads.id AND leads.LeadOperatorId=:LeadOperatorId) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
	            $searchString = '%'.$searchString.'%';
	            $limitPage=$pageNo;
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
				$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$data=$stmt->fetchAll(PDO::FETCH_OBJ);

				$stmt = $db->prepare("SELECT le.*,et.name AS equipName,make.name AS makeName,model.Model AS modelName FROM lead_equipment le,equipment_type et,make ,model,leads WHERE (le.equipmentId=et.id AND make.id=le.makeId AND le.modelId=model.id AND le.leadId=leads.id AND leads.LeadOperatorId=:LeadOperatorId) AND ( CAST(le.id AS CHAR(10)) LIKE :searchString OR et.name LIKE :searchString OR model.Model LIKE :searchString OR make.name LIKE :searchString OR le.year LIKE :searchString)");
				$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->execute();
				$allCount = $stmt->rowCount(); 
			}
			else
			{
				http_response_code($GLOBALS['unauthorized']);
      			return false;
			}
			if($data)
			{
				for($i=0;$i<count($data);$i++)
				{
					if($data[$i]->cancelled==0 && !($this->checkCancelPermission($data[$i]->id)))
					{
						$data[$i]->cancelled=-1;
					}
				}
			}
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['jsondt']=$json_data;
			$detail['name']=$_SESSION['nameLMS'];
			$detail['majorRole']= $_SESSION['majorRoleLMS'];
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
			    return $detail;
			}
	        else
	        {
	        	http_response_code($GLOBALS['noContent']);
      			return false;
            }

     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }
    
	public function getLeadOperatorDetailsFromLeadId($leadId)
    {
		$responseObj=new stdClass;
     	try{

			$db = getDB();
			$isActive=1;
			$stmt = $db->prepare("SELECT LeadOperatorId FROM leads WHERE id=:id AND isActive=:isActive");
			$stmt->bindParam("id", $leadId,PDO::PARAM_STR);
	        $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
	        $stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$leadEquipArray=array();

			if($data)
			{
				return $this->getDetailsFromUserId($data->LeadOperatorId);
			}
			else
			{
				return false;
			}
		}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
     }

     public function getLeadManagerDetailsFromLeadId($leadId)
    {
		$responseObj=new stdClass;
     	try{

			$db = getDB();
			$isActive=1;
			$stmt = $db->prepare("SELECT leadManagerId FROM leads WHERE id=:id AND isActive=:isActive");
			$stmt->bindParam("id", $leadId,PDO::PARAM_STR);
	        $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
	        $stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$leadEquipArray=array();
			if($data)
			{
				return $this->getDetailsFromUserId($data->leadManagerId);
			}
			else
			{
				return false;
			}
		}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
     }


    public function getLeadEquipmentDetails($leadId)
     {
		$responseObj=new stdClass;
     	try{

			$db = getDB();
			$isActive=1;
			if($_SESSION['majorRoleLMS']=="manager")
			{
				if(in_array("leads", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT lead_equipment.* FROM lead_equipment,leads WHERE lead_equipment.leadId=:id AND leads.leadManagerId=:leadManagerId AND lead_equipment.leadId=leads.id");
					$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
					$stmt->bindParam("id", $leadId,PDO::PARAM_INT);
	        	}
	        	if(in_array("fulfillment", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT * FROM lead_equipment WHERE leadId=:id AND fulfilmentManager=:fulfilmentManager");
					$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
	        	}

			}
			else if($_SESSION['majorRoleLMS']=="operator")
			{
				if(in_array("leads", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT lead_equipment.* FROM lead_equipment,leads WHERE lead_equipment.leadId=:id AND leads.LeadOperatorId=:LeadOperatorId AND lead_equipment.leadId=leads.id");
					$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
					$stmt->bindParam("id", $leadId,PDO::PARAM_INT);
	        	}
	        	if(in_array("fulfillment", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT * FROM lead_equipment WHERE leadId=:id AND fulfilmentOperator=:fulfilmentOperator");
					$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
	        	}

			}
			else
				$stmt = $db->prepare("SELECT * FROM lead_equipment WHERE leadId=:id");
			$stmt->bindParam("id", $leadId,PDO::PARAM_STR);
	        // $cancelled=0;
	        // $stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL);
	        $stmt->execute();
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			$leadEquipArray=array();

			if($data)
        	{
        		$responseObjFinal=new stdClass;
        		$responseObjFinal->getMyTargets=0;
        		if((in_array("fulfillment", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager'))
				{
					$responseObjFinal->getMyTargets=1;
				}

        		$responseObjFinal->canMoveToOrder=$this->checkMoveToOrderPermission();
          		$responseObjFinal->canAccept=$this->checkAcceptPermission();
          		$responseObjFinal->canPaperWorkAndAdvance=$this->checkPaperWorkAndAdvancePermission();
          		if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin')$responseObjFinal->canAddLM=1;
				else $responseObjFinal->canAddLM=0;
				if($_SESSION['majorRoleLMS']=='manager'&&in_array("leads", $_SESSION['accessLevelLMS']))$responseObjFinal->canAddLO=1;
				else $responseObjFinal->canAddLO=0;
				if($_SESSION['majorRoleLMS']=='manager'&&in_array("fulfillment", $_SESSION['accessLevelLMS']))$responseObjFinal->canAddFO=1;
				else $responseObjFinal->canAddFO=0;
				if($_SESSION['majorRoleLMS']=='superManager'||$_SESSION['majorRoleLMS']=='admin')$responseObjFinal->canAddFM=1;
				else $responseObjFinal->canAddFM=0;
				$st = $db->prepare("SELECT customerId FROM leads WHERE id=:id");
				$st->bindParam("id", $leadId,PDO::PARAM_STR);
		        $st->execute();
				$data2=$st->fetch(PDO::FETCH_OBJ);
				$responseObjFinal->renterPhone=$this->getPhoneFromCustomerId($data2->customerId);
				$responseObjFinal->renterName=$this->getNameFromCustomerId($data2->customerId);
          		$responseObjFinal->id=$leadId;
          		$responseObjFinal->leadOperator=$this->getLeadOperatorDetailsFromLeadId($leadId);
          		$responseObjFinal->leadManager=$this->getLeadManagerDetailsFromLeadId($leadId);
        		for($i=0;$i<count($data);$i++)
        		{

	        		$responseObj=new stdClass;
	        		$canCancel=$this->checkCancelPermission($data[$i]->id);
	        		if($canCancel)
	          			$canCancel=1;
	          		else
	          			$canCancel=0;
	          		$responseObj->canCancel=$canCancel;
        			$responseObj->model=$this->getNameFromModelId($data[$i]->modelId);
	          		$responseObj->equipName=$this->getNameFromEquipmentId($data[$i]->equipmentId);
	          		$responseObj->make=$this->getNameFromMakeId($data[$i]->makeId);
	          		$responseObj->jobLocation=$data[$i]->location;
	          		$responseObj->district=$data[$i]->district;
	          		$responseObj->equipId=$data[$i]->id;
	          		$responseObj->shiftTypeDay=$data[$i]->shiftTypeDay;
	          		$responseObj->stage=$data[$i]->projectStage;
	          		// echo $data[$i]->fulfilmentManager;
	          		if($data[$i]->fulfilmentManager)
					{
						$responseObj->fulfilmentManager=1;
						$fulfilmentManagerDetails=$this->getDetailsFromUserId($data[$i]->fulfilmentManager);
						// echo $fulfilmentManagerPhone."HAHAH";
						// $fulfilmentManagerDetails=$this->checkUserInDB($fulfilmentManagerPhone);
						if($fulfilmentManagerDetails)
						{
							$g=new stdClass;
							$g->name=$fulfilmentManagerDetails->name;
							$g->phone=$fulfilmentManagerDetails->phone;
							$g->id=$fulfilmentManagerDetails->userId;
							$responseObj->fulfilmentManager=$g;
						}
	          		}
	          		else
	          		{
	          			$responseObj->fulfilmentManager=0;
	          		}
	          		if($data[$i]->fulfilmentOperator)
					{
						$fulfilmentOperatorDetails=$this->getDetailsFromUserId($data[$i]->fulfilmentOperator);
						// $fulfilmentOperatorDetails=$this->checkUserInDB($fulfilmentOperatorPhone);
						if($fulfilmentOperatorDetails)
						{
							$g=new stdClass;
							$g->name=$fulfilmentOperatorDetails->name;
							$g->phone=$fulfilmentOperatorDetails->phone;
							$g->id=$fulfilmentOperatorDetails->userId;
							$responseObj->fulfilmentOperator=$g;
						}
					}
	          		else
	          		{
	          			$responseObj->fulfilmentOperator=0;
	          		}
	          		if($data[$i]->supplierPhone1)$responseObj->supply=1;
					else $responseObj->supply=0;
					if($data[$i]->priceTag)$responseObj->price=1;
					else $responseObj->price=0;
	          		if($data[$i]->movedToOrders)$responseObj->movedToOrders=1;
					else $responseObj->movedToOrders=0;
	          		if($data[$i]->paperWorkAndAdvance)$responseObj->paperWorkAndAdvance=1;
					else $responseObj->paperWorkAndAdvance=0;
	          		if($data[$i]->accept)$responseObj->accept=1;
					else $responseObj->accept=0;
	          		$responseObj->shiftTypeNight=$data[$i]->shiftTypeNight;
	          		$responseObj->startDate=$data[$i]->expectedStartDate;
	          		$responseObj->operationalHoursInADay=$data[$i]->operationalHoursInADay;
	          		$responseObj->operationalDaysInAMonth=$data[$i]->operationalDaysInAMonth;
	          		$responseObj->operationalHoursInAMonth=$data[$i]->operationalHoursInAMonth;
	          		$responseObj->vehicleDocuments=$data[$i]->vehicleDocuments;
	          		$responseObj->operatorLicense=$data[$i]->operatorLicense;
	          		$responseObj->capacity=$data[$i]->capacity;
	          		$responseObj->year=$data[$i]->year;
	          		$responseObj->operatorAllowance=$data[$i]->operatorAllowance;
	          		$responseObj->confirmed=$data[$i]->confirmed;
					$responseObj->cancelled=$data[$i]->cancelled;
					$responseObj->paperWorkAndAdvance=$data[$i]->paperWorkAndAdvance;
					$responseObj->accept=$data[$i]->accept;
					$responseObj->movedToOrders=$data[$i]->movedToOrders;
	          		array_push($leadEquipArray, $responseObj);
	        	}
	        	$responseObjFinal->equipments=$leadEquipArray;
				$detail['details'] = $responseObjFinal;
				$detail['majorRole']= $_SESSION['majorRoleLMS'];
				$detail['name']=$_SESSION['nameLMS'];
				$detail['accessLevel']=$_SESSION['accessLevelLMS'];
				$chatClass = new chatClass();
				$detail['tabs']=$chatClass->getChannelsLeads($leadId);
				if($detail['tabs'])$detail['generalTabMsgs']=$chatClass->getMsgsLeads($detail['tabs'][0]['id']);
				if($_SESSION['majorRoleLMS']=='admin')
				{
					$stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE isActive=1");
					$stmt->execute();
					$team=$stmt->fetchAll(PDO::FETCH_ASSOC);
					
					// $stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE majorRole=:majorRole AND isActive=1");
					// $majorRole="manager";
					// $stmt->bindParam("majorRole", $majorRole,PDO::PARAM_INT) ;
					// $stmt->execute();
					// $team2=$stmt->fetchAll(PDO::FETCH_ASSOC);
					// $finalteam=array_merge($team,$team2);
					
					$detail['team'] = $team;

				}
				else if($_SESSION['majorRoleLMS']=='manager')
				{
					$stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE managerId=:id AND isActive=1");
					$stmt->bindParam("id", $_SESSION['userIdLMS'],PDO::PARAM_INT) ;
					$stmt->execute();
					$team=$stmt->fetchAll(PDO::FETCH_ASSOC);
					
					// $stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE majorRole=:majorRole AND isActive=1");
					// $majorRole="manager";
					// $stmt->bindParam("majorRole", $majorRole,PDO::PARAM_INT) ;
					// $stmt->execute();
					// $team2=$stmt->fetchAll(PDO::FETCH_ASSOC);
					// $finalteam=array_merge($team,$team2);
					$detail['team'] = $team;

				}
				else
				{
					$detail['team'] ="";
				}
				$db = null;
				if($detail)
				{
				    return $detail;
				}
				else
		        {
		        	http_response_code($GLOBALS['noContent']);
	      			return false;
		        } 	
				
	        }
	        else
	        {
	        	http_response_code($GLOBALS['noContent']);
      			return false;
	        } 	

     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}

	public function getEquipmentStage($equipment)
	{
		if($equipment->cancelled)
		{
			return "Cancelled";
		}
		else if($equipment->priceCard AND !$equipment->supplierPhone1)
		{
			return "Pricecard Sent";
		}
		else if($equipment->supplierPhone1 AND !$equipment->priceTag)
		{
			return "Supplier Assigned";
		}
		else if($equipment->priceTag AND !$equipment->paperWorkAndAdvance)
		{
			return "Price Tag Assigned";
		}
		else if($equipment->paperWorkAndAdvance AND !$equipment->accept)
		{
			return "Paperwork And Advance Done";
		}
		else if($equipment->accept AND !$equipment->movedToOrders)
		{
			return "In the Final Stage";
		}
		else if($equipment->movedToOrders)
		{
			return "Moved To Order";
		}
		else
		{
			return "New";
		}
		
	}
    public function getEquipmentDetails($id)
    {
     	try{

			$db = getDB();
			$isActive=1;
			if($_SESSION['majorRoleLMS']=="manager")
			{
				if(in_array("leads", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT lead_equipment.* FROM lead_equipment,leads WHERE lead_equipment.id=:id AND leads.leadManagerId=:leadManagerId AND lead_equipment.leadId=leads.id");
					$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
	        	}
	        	if(in_array("fulfillment", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT * FROM lead_equipment WHERE id=:id AND fulfilmentManager=:fulfilmentManager");
					$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
	        	}

			}
			else if($_SESSION['majorRoleLMS']=="operator")
			{
				if(in_array("leads", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT lead_equipment.* FROM lead_equipment,leads WHERE lead_equipment.id=:id AND leads.LeadOperatorId=:LeadOperatorId AND lead_equipment.leadId=leads.id");
					$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
	        	}
	        	if(in_array("fulfillment", $_SESSION['accessLevelLMS']))
				{
					$stmt = $db->prepare("SELECT * FROM lead_equipment WHERE id=:id AND fulfilmentOperator=:fulfilmentOperator");
					$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
	        	}

			}
			else
				$stmt = $db->prepare("SELECT * FROM lead_equipment WHERE id=:id");
			$stmt->bindParam("id", $id,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$equipment=$data;
			if($data)
			{
				$equipment->equipId=$id;
				if($equipment->fulfilmentManager)
				{
					$fulfilmentManagerDetails=$this->getDetailsFromUserId($equipment->fulfilmentManager);
					// $fulfilmentManagerDetails=$this->checkUserInDB($fulfilmentManagerPhone);
					if($fulfilmentManagerDetails)
					{
						$g=new stdClass;
						$g->name=$fulfilmentManagerDetails->name;
						$g->phone=$fulfilmentManagerDetails->phone;
						$g->id=$fulfilmentManagerDetails->userId;
						$equipment->fulfilmentManager=$g;
					}
				}
				else
				{
					$equipment->fulfilmentManager=0;
				}
				if($equipment->fulfilmentOperator)
				{
					$fulfilmentOperatorDetails=$this->getDetailsFromUserId($equipment->fulfilmentOperator);
					// $fulfilmentOperatorDetails=$this->checkUserInDB($fulfilmentOperatorPhone);
					if($fulfilmentOperatorDetails)
					{
						$g=new stdClass;
						$g->name=$fulfilmentOperatorDetails->name;
						$g->phone=$fulfilmentOperatorDetails->phone;
						$g->id=$fulfilmentOperatorDetails->userId;
						$equipment->fulfilmentOperator=$g;
					}
				}
				{
					$equipment->fulfilmentOperator=0;
				}
				$equipment->canCancel=$this->checkCancelPermission($id);
				if($equipment->canCancel)
          			$equipment->canCancel=1;
          		else
          			$equipment->canCancel=0;

				$equipment->model=$this->getNameFromModelId($data->modelId);
				$equipment->equipName=$this->getNameFromEquipmentId($data->equipmentId);
				$equipment->make=$this->getNameFromMakeId($data->makeId);
				$equipment->jobLocation=$data->location;
				$equipment->district=$data->district;
				$equipment->shiftTypeDay=$data->shiftTypeDay;
				$equipment->stage=$data->projectStage;
				$date= new DateTime($data->expectedStartDate);
				$equipment->startDate=$date->format('m/d/Y h:i A');
				$equipment->shiftTypeNight=$data->shiftTypeNight;
				$equipment->operationalHoursInADay=$data->operationalHoursInADay;
				$equipment->operationalDaysInAMonth=$data->operationalDaysInAMonth;
				$equipment->operationalHoursInAMonth=$data->operationalHoursInAMonth;
				$equipment->operationalHoursInAMonth=$data->operationalHoursInAMonth;
				$equipment->vehicleDocuments=$data->vehicleDocuments;
				$equipment->operatorLicense=$data->operatorLicense;
				$equipment->capacity=$data->capacity;
				$equipment->food=$data->food;
				$equipment->remarks=$data->remarks;
				$equipment->accept=$data->accept;
				$equipment->movedToOrders=$data->movedToOrders;
				$equipment->getMyTargets=0;
				if((in_array("fulfillment", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='manager')||(in_array("fulfillment", $_SESSION['accessLevelLMS'])&&$_SESSION['majorRoleLMS']=='operator')||$_SESSION['majorRoleLMS']=='superManager')
				{
					$equipment->getMyTargets=1;
				}
				$suppliers=new stdClass;
				if($data->supplierPhone1)
				{
					$suppliers->{'1'}=$this->checkSupplierInDB($data->supplierPhone1);
				}
				if($data->supplierPhone2)
				{
					$suppliers->{'2'}=$this->checkSupplierInDB($data->supplierPhone2);
				}
				if($data->supplierPhone3)
				{
					$suppliers->{'3'}=$this->checkSupplierInDB($data->supplierPhone3);
				}
				$equipment->suppliers=$suppliers;
				$equipment->equipStage=$this->getEquipmentStage($equipment);
				if($equipment->confirmed)
				{
					$equipment->status="Confirmed";
				}
				else if($equipment->cancelled)
				{
					$equipment->status="Cancelled";
				}
				else
				{
					$equipment->status="Processing";
				}
				unset($equipment->supplierPhone1);
				unset($equipment->supplierPhone2);
				unset($equipment->supplierPhone3);
				unset($equipment->typeOfLeadFM);
				unset($equipment->closedPriceFM);
				unset($equipment->profitMarginFM);
				unset($equipment->easeOfFulfillmentFM);
				unset($equipment->typeOfLeadFO);
				unset($equipment->closedPriceFO);
				unset($equipment->profitMarginFO);
				unset($equipment->easeOfFulfillmentFO);
				
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				return false;
			}
			$detail['details'] = $equipment;
			$detail['majorRole']= $_SESSION['majorRoleLMS'];
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$chatClass = new chatClass();
			$detail['tabs']=$chatClass->getChannelsEquipments($id);
			if($detail['tabs'])$detail['generalTabMsgs']=$chatClass->getMsgsEquipments($detail['tabs'][0]['id']);
			if($_SESSION['majorRoleLMS']=='admin')
			{
				$stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE isActive=1");
				$stmt->execute();
				$team=$stmt->fetchAll(PDO::FETCH_ASSOC);
				
				// $stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE majorRole=:majorRole AND isActive=1");
				// $majorRole="manager";
				// $stmt->bindParam("majorRole", $majorRole,PDO::PARAM_INT) ;
				// $stmt->execute();
				// $team2=$stmt->fetchAll(PDO::FETCH_ASSOC);
				// $finalteam=array_merge($team,$team2);
				$detail['team'] = $team;
			}
			else if($_SESSION['majorRoleLMS']=='manager')
			{
				$stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE managerId=:id AND isActive=1");
				$stmt->bindParam("id", $_SESSION['userIdLMS'],PDO::PARAM_INT) ;
				$stmt->execute();
				$team=$stmt->fetchAll(PDO::FETCH_ASSOC);
				
				// $stmt = $db->prepare("SELECT userId,name,majorRole FROM staff_login WHERE majorRole=:majorRole AND isActive=1");
				// $majorRole="manager";
				// $stmt->bindParam("majorRole", $majorRole,PDO::PARAM_INT) ;
				// $stmt->execute();
				// $team2=$stmt->fetchAll(PDO::FETCH_ASSOC);
				// $finalteam=array_merge($team,$team2);

				// for($i=0;$i<count())

				$detail['team'] = $team;

			}
			else
			{
				$detail['team'] ="";
			}
			$db = null;
			if($detail)
			{
				$detail['name']=$_SESSION['nameLMS'];
				return $detail;
			} 	
     	}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }

    public function checkDateQuarter($dateStr)
    {
    	// $datestr="2017-06-21 18:04:47";
		$newdate=strtotime($dateStr);
		$month=intval(date('m',$newdate));
		if($month>=0 && $month<=3)return 1;
		if($month>=4 && $month<=6)return 2;
		if($month>=7 && $month<=9)return 3;
		if($month>=10 && $month<=12)return 4;
    }

    public function getYearFromDate($dateStr)
    {
    	// $datestr="2017-06-21 18:04:47";
		$newdate=strtotime($dateStr);
		$year=intval(date('Y',$newdate));
		return $year;
    }

    public function getEquipmentInsights2()
    {
     	try{

			$db = getDB();
			// $years=array("");
			$stmt = $db->prepare("SELECT accept,expectedStartDate,id,equipmentId,year,priceTag,cancelled,movedToOrders FROM lead_equipment ORDER BY year,equipmentId");
			$stmt->execute();
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			$equipment=$data;
			if($data)
			{
				$mainArray=array();
				$i=0;
				$responseObj=new stdClass;
				while($i<count($data)-1)
				{
					// if($data[$i]->expectedStartDate)
					// {
					// 	$compYear1=$this->getYearFromDate($data[$i]->expectedStartDate);
					// 	$compYear2=$this->getYearFromDate($data[$i+1]->expectedStartDate);
					// 	if($compYear1==0)break;
					// 	if($compYear2==0)break;
					// }
					// else break;
					$common1=$data[$i]->equipmentId.$data[$i]->year;
					$common2=$data[$i+1]->equipmentId.$data[$i+1]->year;
					$responseObj->$common1=new stdClass;
					// echo $compYear1.$compYear2;
					$responseObj->$common2=new stdClass;
					$responseObj->$common1->data=array();
					$responseObj->$common2->data=array();
					$data[$i]->priceTag=intval($data[$i]->priceTag);
					$data[$i+1]->priceTag=intval($data[$i+1]->priceTag);
					$responseObj->$common1->minPrice=$data[$i]->priceTag;
					$responseObj->$common1->maxPrice=$data[$i]->priceTag;
					$responseObj->$common1->count=0;
					$responseObj->$common1->accept=0;
					$responseObj->$common2->minPrice=$data[$i+1]->priceTag;
					$responseObj->$common2->maxPrice=$data[$i+1]->priceTag;
					$responseObj->$common2->count=0;
					$responseObj->$common2->accept=0;
					if($common1==$common2)
					{
						while($i<count($data)-1 && $common1==$common2)
						{
							$data[$i]->priceTag=intval($data[$i]->priceTag);
							$responseObj->$common1->minPrice=min($responseObj->$common1->minPrice,$data[$i]->priceTag);
							$responseObj->$common1->maxPrice=max($responseObj->$common1->maxPrice,$data[$i]->priceTag);
							$responseObj->$common1->count+=1;
							if($data[$i]->accept)$responseObj->$common1->accept+=1;
							else $responseObj->$common1->accept=0;
							$responseObj->$common1->quarter=$this->checkDateQuarter($data[$i]->expectedStartDate);
							array_push($responseObj->$common1->data, $data[$i]);
							$i+=1;
							// if($data[$i]->expectedStartDate)
							// {
							// 	$compYear1=$this->getYearFromDate($data[$i]->expectedStartDate);
							// 	$compYear2=$this->getYearFromDate($data[$i+1]->expectedStartDate);
							// 	if($compYear1==0)break;
							// 	if($compYear2==0)break;
							// }
							// else break;
							// $common1=$data[$i]->equipmentId.$compYear1;
							// $common2=$data[$i+1]->equipmentId.$compYear2;
							$common1=$data[$i]->equipmentId.$data[$i]->year;
							$common2=$data[$i+1]->equipmentId.$data[$i+1]->year;
						}
						if($i==count($data)-1 AND $common1==$common2)
						{
							$data[$i]->priceTag=intval($data[$i]->priceTag);
							$responseObj->$common1->minPrice=min($responseObj->$common1->minPrice,$data[$i]->priceTag);
							$responseObj->$common1->maxPrice=max($responseObj->$common1->maxPrice,$data[$i]->priceTag);
							$responseObj->$common1->count+=1;
							if($data[$i]->accept)$responseObj->$common1->accept+=1;
							else $responseObj->$common1->accept=0;
							$responseObj->$common1->quarter=$this->checkDateQuarter($data[$i]->expectedStartDate);
							array_push($responseObj->$common1->data, $data[$i]);
							$i+=1;
						}
					}
					else
					{
						$data[$i]->priceTag=intval($data[$i]->priceTag);
						if(isset($responseObj->$common1->data)){}
						else $responseObj->$common1->data=array();
						$responseObj->$common1->minPrice=min($responseObj->$common1->minPrice,$data[$i]->priceTag);
						$responseObj->$common1->maxPrice=max($responseObj->$common1->maxPrice,$data[$i]->priceTag);
						$responseObj->$common1->count+=1;
						if($data[$i]->accept)$responseObj->$common1->accept+=1;
						else $responseObj->$common1->accept=0;
						$responseObj->$common1->quarter=$this->checkDateQuarter($data[$i]->expectedStartDate);
						array_push($responseObj->$common1->data, $data[$i]);
					}
					$i+=1;
					if($i==count($data)-1)
					{
						// if($data[$i]->expectedStartDate)
						// {
						// 	$compYear1=$this->getYearFromDate($data[$i]->expectedStartDate);
						// 	if($compYear1==0)break;
						// }
						// else break;
						$common1=$data[$i]->equipmentId.$data[$i]->year;
						$data[$i]->priceTag=intval($data[$i]->priceTag);
						$responseObj->$common1=new stdClass;
						$responseObj->$common1->data=array();
						$responseObj->$common1->minPrice=$data[$i]->priceTag;
						$responseObj->$common1->maxPrice=$data[$i]->priceTag;
						$responseObj->$common1->count=1;
						if($data[$i]->accept)$responseObj->$common1->accept=1;
						else $responseObj->$common1->accept=0;
						$responseObj->$common1->quarter=$this->checkDateQuarter($data[$i]->expectedStartDate);
						array_push($responseObj->$common1->data, $data[$i]);
						$i+=1;
					}
				}

				$responseObjFinal=new stdClass;
				// $mainArray=array();
				// echo var_dump($responseObj);
				foreach ($responseObj as $obj) 
				{
					$obj2=new stdClass;
					$obj2->equipName=$this->getNameFromEquipmentId($obj->data[0]->equipmentId);
					$tempObj=new stdClass;
					$tempObj->range=$obj->minPrice." - ".$obj->maxPrice;
					$tempObj->count=$obj->count;
					$tempObj->successRate=(($obj->accept/(float)$obj->count)*100)." %";	
					$nullObj=new stdClass;
					$nullObj->range="N/A";
					$nullObj->count="N/A";
					$nullObj->successRate="N/A";	
					$obj2->first=$nullObj;
					$obj2->second=$nullObj;
					$obj2->third=$nullObj;
					$obj2->fourth=$nullObj;
					if($obj->quarter==1)
					{
						$obj2->first=$tempObj;
					}
					if($obj->quarter==2)
					{
						$obj2->second=$tempObj;
					}
					if($obj->quarter==3)
					{
						$obj2->third=$tempObj;
					}
					if($obj->quarter==4)
					{
						$obj2->fourth=$tempObj;
					}
					array_push($mainArray, $obj2);
				}
				$responseObjFinal->data=$mainArray;
				return $responseObjFinal;
			}
			else
			{

			}
			
     	}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }

    public function initializeInsightObj()
    {
    	$responseObj=new stdClass;
    	
    	$responseObj->first=new stdClass;
		$responseObj->second=new stdClass;
		$responseObj->third=new stdClass;
		$responseObj->fourth=new stdClass;
		
		$responseObj->first->count=0;
		$responseObj->first->maxPrice=0;
		$responseObj->first->minPrice=0;
		$responseObj->first->acceptCount=0;
		$responseObj->first->movedToOrdersCount=0;
		$responseObj->first->cancelledCount=0;

		$responseObj->second->count=0;
		$responseObj->second->maxPrice=0;
		$responseObj->second->minPrice=0;
		$responseObj->second->acceptCount=0;
		$responseObj->second->movedToOrdersCount=0;
		$responseObj->second->cancelledCount=0;
		
		$responseObj->third->count=0;
		$responseObj->third->maxPrice=0;
		$responseObj->third->minPrice=0;
		$responseObj->third->acceptCount=0;
		$responseObj->third->movedToOrdersCount=0;
		$responseObj->third->cancelledCount=0;
		
		$responseObj->fourth->count=0;
		$responseObj->fourth->maxPrice=0;
		$responseObj->fourth->minPrice=0;
		$responseObj->fourth->acceptCount=0;
		$responseObj->fourth->movedToOrdersCount=0;
		$responseObj->fourth->cancelledCount=0;
		
		return $responseObj;
	}

    public function getEquipmentInsights($startDate,$endDate)
    {
     	try{

			$db = getDB();
			$stmt = $db->prepare("SELECT leads.leadDate AS leadDate, le.accept,le.id,et.name,le.year,le.priceTag,le.cancelled,le.movedToOrders FROM lead_equipment le, leads, equipment_type et WHERE leads.id=le.leadId AND et.id=le.equipmentId AND ((leads.leadDate BETWEEN :startDate AND :endDate) OR (COALESCE(:startDate,'') = '') OR (COALESCE(:endDate,'') = '')) ORDER BY le.equipmentId");
			$stmt->bindParam("startDate", $startDate,PDO::PARAM_STR);
	        $stmt->bindParam("endDate", $endDate,PDO::PARAM_STR);
	        $stmt->execute();
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			$graphObj=new stdClass;
			$responseArr=array();
			if($data)
			{
				$i=0;
				$responseObj=new stdClass;
				while($i<count($data))
				{
					$name=$data[$i]->name;
					$responseObj->$name=new stdClass;
					if($data[$i]->priceTag){}
					else $data[$i]->priceTag=0;
					if($this->checkDateQuarter($data[$i]->leadDate)==1)
					{
						$responseObj->$name=$this->initializeInsightObj();
						$responseObj->$name->first->maxPrice=$data[$i]->priceTag;
						$responseObj->$name->first->minPrice=$data[$i]->priceTag;
						$responseObj->$name->first->acceptCount=0;
						$responseObj->$name->first->movedToOrdersCount=0;
						$responseObj->$name->first->cancelledCount=0;
						if($data[$i]->accept)$responseObj->$name->first->acceptCount=1;
						if($data[$i]->movedToOrders)$responseObj->$name->first->movedToOrdersCount=1;
						if($data[$i]->cancelled)$responseObj->$name->first->cancelledCount=1;
					}
					if($this->checkDateQuarter($data[$i]->leadDate)==2)
					{
						$responseObj->$name=$this->initializeInsightObj();
						$responseObj->$name->second->count=1;
						$responseObj->$name->second->maxPrice=$data[$i]->priceTag;
						$responseObj->$name->second->minPrice=$data[$i]->priceTag;
						$responseObj->$name->second->acceptCount=0;
						$responseObj->$name->second->movedToOrdersCount=0;
						$responseObj->$name->second->cancelledCount=0;
						if($data[$i]->accept)$responseObj->$name->second->acceptCount=1;
						if($data[$i]->movedToOrders)$responseObj->$name->second->movedToOrdersCount=1;
						if($data[$i]->cancelled)$responseObj->$name->second->cancelledCount=1;
					}
					if($this->checkDateQuarter($data[$i]->leadDate)==3)
					{
						$responseObj->$name=$this->initializeInsightObj();
						$responseObj->$name->third->count=1;
						$responseObj->$name->third->maxPrice=$data[$i]->priceTag;
						$responseObj->$name->third->minPrice=$data[$i]->priceTag;
						$responseObj->$name->third->acceptCount=0;
						$responseObj->$name->third->movedToOrdersCount=0;
						$responseObj->$name->third->cancelledCount=0;
						if($data[$i]->accept)$responseObj->$name->third->acceptCount=1;
						if($data[$i]->movedToOrders)$responseObj->$name->third->movedToOrdersCount=1;
						if($data[$i]->cancelled)$responseObj->$name->third->cancelledCount=1;
					}
					if($this->checkDateQuarter($data[$i]->leadDate)==4)
					{
						$responseObj->$name=$this->initializeInsightObj();
						$responseObj->$name->fourth->count=1;
						$responseObj->$name->fourth->maxPrice=$data[$i]->priceTag;
						$responseObj->$name->fourth->minPrice=$data[$i]->priceTag;
						$responseObj->$name->fourth->acceptCount=0;
						$responseObj->$name->fourth->movedToOrdersCount=0;
						$responseObj->$name->fourth->cancelledCount=0;
						if($data[$i]->accept)$responseObj->$name->fourth->acceptCount=1;
						if($data[$i]->movedToOrders)$responseObj->$name->fourth->movedToOrdersCount=1;
						if($data[$i]->cancelled)$responseObj->$name->fourth->cancelledCount=1;
					}
					$graphObj->$name=new stdClass;
					if(isset($graphObj->$name->movedToOrdersDates)){}
					else $graphObj->$name->movedToOrdersDates=array($data[$i]->leadDate);

					if(isset($graphObj->$name->movedToOrdersPrices)){}
					else $graphObj->$name->movedToOrdersPrices=array($data[$i]->priceTag);

					if(isset($graphObj->$name->finalStageDates)){}
					else $graphObj->$name->finalStageDates=array($data[$i]->leadDate);

					if(isset($graphObj->$name->finalStagePrices)){}
					else $graphObj->$name->finalStagePrices=array($data[$i]->priceTag);

					if(isset($graphObj->$name->cancelledDates)){}
					else $graphObj->$name->cancelledDates=array($data[$i]->leadDate);

					if(isset($graphObj->$name->cancelledPrices)){}
					else $graphObj->$name->cancelledPrices=array($data[$i]->priceTag);

					while($i<count($data)-1 && $data[$i]->name==$data[$i+1]->name)
					{
						if($data[$i]->priceTag){}
						else $data[$i]->priceTag=0;
					
						if($this->checkDateQuarter($data[$i+1]->leadDate)==1)
						{
							$responseObj->$name->first->count+=1;
							$responseObj->$name->first->maxPrice=max($responseObj->$name->first->maxPrice,$data[$i+1]->priceTag);
							$responseObj->$name->first->minPrice=min($responseObj->$name->first->minPrice,$data[$i+1]->priceTag);
							if($data[$i+1]->accept)$responseObj->$name->first->acceptCount+=1;
							if($data[$i+1]->movedToOrders)$responseObj->$name->first->movedToOrdersCount+=1;
							if($data[$i+1]->cancelled)$responseObj->$name->first->cancelledCount+=1;
						}
						if($this->checkDateQuarter($data[$i+1]->leadDate)==2)
						{
							$responseObj->$name->second->count+=1;
							$responseObj->$name->second->maxPrice=max($responseObj->$name->second->maxPrice,$data[$i+1]->priceTag);
							$responseObj->$name->second->minPrice=min($responseObj->$name->second->minPrice,$data[$i+1]->priceTag);
							if($data[$i+1]->accept)$responseObj->$name->second->acceptCount+=1;
							if($data[$i+1]->movedToOrders)$responseObj->$name->second->movedToOrdersCount+=1;
							if($data[$i+1]->cancelled)$responseObj->$name->second->cancelledCount+=1;
						}
						if($this->checkDateQuarter($data[$i+1]->leadDate)==3)
						{
							$responseObj->$name->third->count+=1;
							$responseObj->$name->third->maxPrice=max($responseObj->$name->third->maxPrice,$data[$i+1]->priceTag);
							$responseObj->$name->third->minPrice=min($responseObj->$name->third->minPrice,$data[$i+1]->priceTag);
							if($data[$i+1]->accept)$responseObj->$name->third->acceptCount+=1;
							if($data[$i+1]->movedToOrders)$responseObj->$name->third->movedToOrdersCount+=1;
							if($data[$i+1]->cancelled)$responseObj->$name->third->cancelledCount+=1;
						}
						if($this->checkDateQuarter($data[$i+1]->leadDate)==4)
						{
							$responseObj->$name->fourth->count+=1;
							$responseObj->$name->fourth->maxPrice=max($responseObj->$name->fourth->maxPrice,$data[$i+1]->priceTag);
							$responseObj->$name->fourth->minPrice=min($responseObj->$name->fourth->minPrice,$data[$i+1]->priceTag);
							if($data[$i+1]->accept)$responseObj->$name->fourth->acceptCount+=1;
							if($data[$i+1]->movedToOrders)$responseObj->$name->fourth->movedToOrdersCount+=1;
							if($data[$i+1]->cancelled)$responseObj->$name->fourth->cancelledCount+=1;
						}
						if($data[$i]->movedToOrders)
						{
							array_push($graphObj->$name->movedToOrdersDates,$data[$i]->leadDate);
							array_push($graphObj->$name->movedToOrdersPrices,$data[$i]->priceTag);
						}
						else if($data[$i]->accept)
						{
							array_push($graphObj->$name->finalStageDates,$data[$i]->leadDate);
							array_push($graphObj->$name->finalStagePrices,$data[$i]->priceTag);
						}
						else if($data[$i]->cancelled)
						{
							array_push($graphObj->$name->cancelledDates,$data[$i]->leadDate);
							array_push($graphObj->$name->cancelledPrices,$data[$i]->priceTag);
						}
						$i++;
					}
					$i++;
					
				}

				foreach ($responseObj as $name => $nameData) 
				{
					$eachObj=new stdClass;
					$eachObj->equipName=$name;
					$eachObj->graphData=$graphObj->$name;
					foreach ($nameData as $quarter => $quarterData) 
					{
						$eachObj->$quarter=$quarterData;
						$eachObj->$quarter->range=$quarterData->minPrice." - ".$quarterData->maxPrice;
						if($quarterData->count)
						{
							$successRate=(($quarterData->acceptCount/(float)$quarterData->count)*100);
							$successRate=round($successRate,2);
						}
						else
						{
							$successRate=0;
						}
						$eachObj->$quarter->successRate=$successRate." %";
					}
					array_push($responseArr, $eachObj);
				}

			}
		
			return $responseArr;			
     	}
	      catch(PDOException $e) {
	      	
	      	echo $e->getMessage();
			http_response_code($GLOBALS['connection_error']);

	      }   
    }


	public function getMyLeads($searchString,$numberOfPages,$pageNo,$draw,$orderCol,$orderDir)
    {
     	$responseObj=new stdClass;
     	try
     	{
     		if(!isset($pageNo) || empty($pageNo)) $pageNo=0;
			$searchString = '%'.$searchString.'%';
			$limitPage=$pageNo;
		
			$db = getDB();
			$isActive=1;
			if($_SESSION['majorRoleLMS']=='superManager' || $_SESSION['majorRoleLMS']=='admin')
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters WHERE leads.isActive=:isActive AND renters.id=leads.customerId AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renters.renterName LIKE :searchString OR leads.leadPriority LIKE :searchString OR renters.phone LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);

				$st = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters WHERE leads.isActive=:isActive AND renters.id=leads.customerId AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renters.renterName LIKE :searchString OR leads.leadPriority LIKE :searchString OR renters.phone LIKE :searchString) ");
				$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$st->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$st->execute();
				$allCount=$st->rowCount();
			}
			if($_SESSION['majorRoleLMS']=='manager' && in_array('leads', $_SESSION['accessLevelLMS']))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters WHERE leads.leadManagerId=:leadManagerId AND leads.isActive=:isActive AND renters.id=leads.customerId AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);

				$st = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone  FROM leads,renters WHERE leads.leadManagerId=:leadManagerId AND leads.isActive=:isActive AND renters.id=leads.customerId AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString)");
				$st->bindParam("leadManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$st->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$st->execute();
				$allCount=$st->rowCount();
			}
			if($_SESSION['majorRoleLMS']=='operator' && in_array('leads', $_SESSION['accessLevelLMS']))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters WHERE leads.LeadOperatorId=:LeadOperatorId AND leads.isActive=:isActive AND renters.id=leads.customerId AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);

				$st = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters WHERE leads.LeadOperatorId=:LeadOperatorId AND leads.isActive=:isActive AND renters.id=leads.customerId AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString)");
				$st->bindParam("LeadOperatorId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$st->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$st->execute();
				$allCount=$st->rowCount();
			}
			if($_SESSION['majorRoleLMS']=='manager' && in_array('fulfillment', $_SESSION['accessLevelLMS']))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters,lead_equipment le WHERE  AND leads.isActive=:isActive AND renters.id=leads.customerId AND le.fulfilmentManager=:fulfilmentManager AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters,lead_equipment le WHERE  AND leads.isActive=:isActive AND renters.id=leads.customerId AND le.fulfilmentManager=:fulfilmentManager AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString)");
				$st->bindParam("fulfilmentManager", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$st->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$st->execute();
				$allCount=$st->rowCount();
			}
			if($_SESSION['majorRoleLMS']=='operator' && in_array('fulfillment', $_SESSION['accessLevelLMS']))
			{
				$stmt = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters,lead_equipment le WHERE  AND leads.isActive=:isActive AND renters.id=leads.customerId AND le.fulfilmentOperator=:fulfilmentOperator AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString) ORDER BY ".$orderCol." ".$orderDir." LIMIT :limitPage,:numberOfPages");
				$stmt->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);

				$stmt = $db->prepare("SELECT leads.*,renters.renterName as name,renters.phone as phone FROM leads,renters,lead_equipment le WHERE  AND leads.isActive=:isActive AND renters.id=leads.customerId AND le.fulfilmentOperator=:fulfilmentOperator AND le.leadId=leads.id AND (leads.id LIKE :searchString OR leads.leadDate LIKE :searchString OR renter.renterName LIKE :searchString OR leads.priority LIKE :searchString OR renters.phone LIKE :searchString)");
				$st->bindParam("fulfilmentOperator", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				$st->bindParam("searchString", $searchString,PDO::PARAM_STR);
				$st->execute();
				$allCount=$st->rowCount();
			}
			
			$stmt->bindParam("searchString", $searchString,PDO::PARAM_STR);
			$stmt->bindParam("numberOfPages", $numberOfPages,PDO::PARAM_INT);
			$stmt->bindParam("limitPage", $limitPage,PDO::PARAM_INT);
			$stmt->execute();
			$data=$stmt->fetchAll(PDO::FETCH_OBJ);
			$data=$this->getLeadArray($data);
			$db = null;
			
			$json_data = array(
			"draw"            => intval( $draw ),   
			"recordsTotal"    => intval( $allCount ),  
			"recordsFiltered" => intval($allCount),
			"data"            => $data
			);

			$detail=array();
			$detail['leadArray']=$json_data;
			$detail['accessLevel']=$_SESSION['accessLevelLMS'];
			$db = null;
			if($detail)
			{
				return $detail;
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				return false;
			}    
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
	    }
	}
    
    public function leadDelete($id)
    {
     	try{

			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM leads WHERE id=:id and isActive=:isActive");
			$isActive=1;
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);  
			$stmt->bindParam("id", $id,PDO::PARAM_INT);  
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				if($data->renterAcceptQuotation)
				{
					if($_SESSION['majorRoleLMS']=='manager'){}
					else
					{
						http_response_code($GLOBALS['unauthorized']);
						return false;
					}
				}
				$isActive=0;
				$deletedBy=$_SESSION['userIdLMS'];
				$time= date('Y-m-d H:i:s',time());
			    $st=$db->prepare("UPDATE leads SET isActive=:isActive,deletedBy=:deletedBy,deletedOn=:deletedOn WHERE id=:id");
			    $st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			    $st->bindParam("deletedOn",$time,PDO::PARAM_STR);
			    $st->bindParam("deletedBy",$deletedBy,PDO::PARAM_INT);
			    $st->bindParam("id", $id,PDO::PARAM_INT);  
				$st->execute();
				$x=$this->leadEquipDelete($id);
				$db=null;
				return true;
			}
			else
			{
				$db=null;
				http_response_code($GLOBALS['forbidden']);
				return false;
			} 	
		}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }

    public function leadActivate($id)
    {
     	try{
			$db = getDB();
			$isActive=1;
			$reject=0;
			$stmt = $db->prepare("SELECT * FROM leads WHERE id=:id AND isActive=:isActive AND reject=:reject");
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL);
			$stmt->bindParam("id", $id,PDO::PARAM_INT);  
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				if($data->activate)
				{
					http_response_code($GLOBALS['forbidden']);
	  				return false;
				}
				$activate=1;
				$lastUpdatedBy=$_SESSION['userIdLMS'];
				$time= date('Y-m-d H:i:s',time());
				$st=$db->prepare("UPDATE leads SET activate=:activate,lastUpdatedOn=:lastUpdatedOn,lastUpdatedBy=:lastUpdatedBy,activatedOn=:activatedOn WHERE id=:id");
				$st->bindParam("activate", $activate,PDO::PARAM_BOOL);  
				$time=date('Y-m-d H:i:s',time());
				$st->bindParam("lastUpdatedOn",$time,PDO::PARAM_STR);
				$st->bindParam("lastUpdatedBy",$lastUpdatedBy,PDO::PARAM_INT);
				$st->bindParam("activatedOn",$time,PDO::PARAM_STR);
				$st->bindParam("id", $id,PDO::PARAM_INT);  
				$st->execute();
				$this->assignApprovedBy($id);
				$db=null;
				return true;
			}
			else
			{
				$db=null;
				http_response_code($GLOBALS['noContent']);
  				return false;
			} 	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
 	}

 	public function leadDeactivate($id)
    {
     	try{

			$db = getDB();
			$isActive=1;
			$stmt = $db->prepare("SELECT * FROM leads WHERE id=:id AND isActive=:isActive");
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$stmt->bindParam("id", $id,PDO::PARAM_INT);  
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				if($data->activate==0)
				{
					http_response_code($GLOBALS['forbidden']);
					return false;
				}
				$activate=0;
				$renterAcceptQuotation=0;
				$supplierConfirmed=0;
				$priceCardSent=0;
				$movedToOrders=0;
				$quotationSent=0;
				$paperWorkAndAdvance=0;
				$priceCard=0;
				$st=$db->prepare("UPDATE lead_equipment SET priceCard=:priceCard WHERE leadId=:id");
			    $st->bindParam("id", $id,PDO::PARAM_BOOL);
			    $st->bindParam("priceCard", $priceCard,PDO::PARAM_STR);
			    $st->execute();
					
				$lastUpdatedBy=$_SESSION['userIdLMS'];
				$time= date('Y-m-d H:i:s',time());
			    $st=$db->prepare("UPDATE leads SET quotationSent=:quotationSent,renterAcceptQuotation=:renterAcceptQuotation,supplierConfirmed=:supplierConfirmed,priceCardSent=:priceCardSent,movedToOrders=:movedToOrders,activate=:activate,paperWorkAndAdvance=:paperWorkAndAdvance,lastUpdatedOn=:lastUpdatedOn,lastUpdatedBy=:lastUpdatedBy WHERE id=:id");
			    $st->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL) ;
				$st->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL) ;
				$st->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL) ;
				$st->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL) ;
				$st->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL) ;
				$st->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL) ;
				$st->bindParam("activate", $activate,PDO::PARAM_BOOL);  
					$st->bindParam("lastUpdatedOn",$time,PDO::PARAM_STR);
			    $st->bindParam("lastUpdatedBy",$lastUpdatedBy,PDO::PARAM_INT);
			    $st->bindParam("id", $id,PDO::PARAM_INT);  
				$st->execute();
				$db=null;
				return true;
			}
			else
			{
				$db=null;
				http_response_code($GLOBALS['noContent']);
				return false;
			} 	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
 	}

    public function leadAccept($id)
    {
     	try{

			$db = getDB();
			$isActive=1;
			$stmt = $db->prepare("SELECT * FROM leads WHERE id=:id AND isActive=:isActive");
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$stmt->bindParam("id", $id,PDO::PARAM_INT);  
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				if($data->accept)
				{
					http_response_code($GLOBALS['forbidden']);
					return false;
				}
				$accept=1;
				$lastUpdatedBy=$_SESSION['userIdLMS'];
				$time= date('Y-m-d H:i:s',time());
				$st=$db->prepare("UPDATE leads SET accept=:accept,lastUpdatedOn=:lastUpdatedOn,lastUpdatedBy=:lastUpdatedBy WHERE id=:id");
				$st->bindParam("accept", $accept,PDO::PARAM_BOOL);  
				$st->bindParam("lastUpdatedOn",$time,PDO::PARAM_STR);
				$st->bindParam("lastUpdatedBy",$lastUpdatedBy,PDO::PARAM_INT);
				$st->bindParam("id", $id,PDO::PARAM_INT);  
				$st->execute();
				$db=null;
				return true;
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				$db=null;
				return false;
			} 	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }

    public function leadReject($id)
    {
     	try
     	{
			$db = getDB();
			$isActive=1;
			$stmt = $db->prepare("SELECT * FROM leads WHERE id=:id AND isActive=:isActive");
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$stmt->bindParam("id", $id,PDO::PARAM_INT);  
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				if($data->reject)
				{
					http_response_code($GLOBALS['forbidden']);
					return false;
				}
				$reject=1;
				$accept=0;
				$lastUpdatedBy=$_SESSION['userIdLMS'];
				$time= date('Y-m-d H:i:s',time());
				$st=$db->prepare("UPDATE leads SET accept=:accept,reject=:reject,lastUpdatedOn=:lastUpdatedOn,lastUpdatedBy=:lastUpdatedBy WHERE id=:id");
				$st->bindParam("accept", $accept,PDO::PARAM_BOOL);  
				$st->bindParam("reject", $reject,PDO::PARAM_BOOL);  
				$st->bindParam("lastUpdatedOn",$time,PDO::PARAM_STR);
				$st->bindParam("lastUpdatedBy",$lastUpdatedBy,PDO::PARAM_INT);
				$st->bindParam("id", $id,PDO::PARAM_INT);  
				$st->execute();
				$db=null;
				return true;
			}
			else
			{
				http_response_code($GLOBALS['noContent']);
				$db=null;
				return false;
			} 	
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }
     
    public function leadEquipAdd($shiftTypeDay,$shiftTypeNight,$equipmentId,$leadId,$makeId,$location,$model,$expectedStartDate,$operatorAllowance,$year,$food,$accommodation,$operationalHoursInADay,$operationalDaysInAMonth,$vehicleDocuments,$operatorLicense,$capacity,$operationalHoursInAMonth,$projectStage,$quantity,$typeOfWork,$safetyMeasures,$duration,$districtId,$stateId)
	{
		try{
			
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO lead_equipment(shiftTypeDay,shiftTypeNight,equipmentId,leadId,makeId,location,model,expectedStartDate,operatorAllowance,year,food,accommodation,operationalHoursInADay,operationalDaysInAMonth,vehicleDocuments,operatorLicense,capacity,operationalHoursInAMonth,projectStage,quantity,cancelled,confirmed,paperWorkAndAdvance,accept,movedToOrders,priceCard,priceCardSent,:quotationSent,renterAcceptQuotation,supplierConfirmed,typeOfWork,safetyMeasures,duration,districtId,stateId) VALUES (:shiftTypeDay,:shiftTypeNight,:equipmentId,:leadId,:makeId,:location,:model,:expectedStartDate,:operatorAllowance,:year,:food,:accommodation,:operationalHoursInADay,:operationalDaysInAMonth,:vehicleDocuments,:operatorLicense,:capacity,:operationalHoursInAMonth,:projectStage,:quantity,:cancelled,:confirmed,:paperWorkAndAdvance,:accept,:movedToOrders,:priceCard,:priceCardSent,:quotationSent,:renterAcceptQuotation,:supplierConfirmed,:typeOfWork,:safetyMeasures,:duration,:districtId,:stateId)");  
		    

			$st=$db->prepare("SELECT id from equipment_type WHERE name=:name");
			$st->bindParam("name", $equipmentId,PDO::PARAM_STR);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			$equipmentTypeId=$data->id;
			
			$st=$db->prepare("SELECT id from make WHERE name=:name");
			$st->bindParam("name",$makeId,PDO::PARAM_STR);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			$makeId=$data->id;
			
			$paperWorkAndAdvance=0;
			$priceCard=0;
			$priceTag=0;
			$movedToOrders=0;
			$accept=0;
			$cancelled=0;
			$confirmed=0;
			$accept=0;
			$priceCardSent=0;
			$quotationSent=0;
			$renterAcceptQuotation=0;
			$supplierConfirmed=0;

			$stmt->bindParam("duration", $duration,PDO::PARAM_STR) ;
			$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL) ;
			$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL) ;
			$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL) ;
			$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL) ;
			$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL) ;
			$stmt->bindParam("priceCard", $priceCard,PDO::PARAM_STR) ;
			$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL) ;
			$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL) ;
			$stmt->bindParam("cancelled", $cancelled,PDO::PARAM_BOOL) ;
			$stmt->bindParam("confirmed", $confirmed,PDO::PARAM_BOOL) ;
			$stmt->bindParam("equipmentId", $equipmentTypeId,PDO::PARAM_INT) ;
			$stmt->bindParam("leadId", $leadId,PDO::PARAM_INT) ;
			$stmt->bindParam("makeId", $makeId,PDO::PARAM_INT) ;
			$stmt->bindParam("quantity", $quantity,PDO::PARAM_STR) ;
			$stmt->bindParam("projectStage", $projectStage,PDO::PARAM_STR) ;
			$stmt->bindParam("capacity", $capacity,PDO::PARAM_STR) ;
			$stmt->bindParam("location", $location,PDO::PARAM_STR) ;
			$stmt->bindParam("model", $model,PDO::PARAM_STR) ;
			$stmt->bindParam("year", $year,PDO::PARAM_STR) ;
			$stmt->bindParam("expectedStartDate", $expectedStartDate,PDO::PARAM_STR) ;
			$stmt->bindParam("typeOfWork", $typeOfWork,PDO::PARAM_STR) ;
			$stmt->bindParam("operatorAllowance", $operatorAllowance,PDO::PARAM_BOOL) ;
			$stmt->bindParam("food", $food,PDO::PARAM_BOOL) ;
			$stmt->bindParam("shiftTypeNight", $shiftTypeNight,PDO::PARAM_BOOL) ;
			$stmt->bindParam("shiftTypeDay", $shiftTypeDay,PDO::PARAM_BOOL) ;
			$stmt->bindParam("accommodation", $accommodation,PDO::PARAM_BOOL) ;
			$stmt->bindParam("operationalHoursInADay", $operationalHoursInADay,PDO::PARAM_STR) ;
			$stmt->bindParam("operationalDaysInAMonth", $operationalDaysInAMonth,PDO::PARAM_STR) ;
			$stmt->bindParam("operationalHoursInAMonth", $operationalHoursInAMonth,PDO::PARAM_STR) ;
			$stmt->bindParam("vehicleDocuments", $vehicleDocuments,PDO::PARAM_STR) ;
			$stmt->bindParam("operatorLicense", $operatorLicense,PDO::PARAM_STR) ;
			$stmt->bindParam("safetyMeasures", $safetyMeasures,PDO::PARAM_BOOL) ;
			$stmt->bindParam("districtId", $districtId,PDO::PARAM_INT) ;
			$stmt->bindParam("stateId", $stateId,PDO::PARAM_INT) ;
			$stmt->execute();
			$db=null;
			return true;
		}
		catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }

//orderEquipAdd($orderId,$data->shiftTypeDay,$data->shiftTypeNight,$data->equipmentId,$data->makeId,$data->location,$data->modelId,$data->district,$data->expectedStartDate,$data->operatorAllowance,$data->year,$data->food,$data->accommodation,$data->operationalHoursInADay,$data->operationalDaysInAMonth,$data->operationalHoursInAMonth,$data->vehicleDocuments,$data->operatorLicense,$data->capacity,$data->projectStage,$data->quantity,$data->supplierPhone1)

	public function orderEquipAdd($orderId,$shiftTypeDay,$shiftTypeNight,$equipmentId,$makeId,$siteLocation,$modelId,$district,$expectedStartDate,$operatorAllowance,$year,$food,$accommodation,$operationalHoursInADay,$operationalDaysInAMonth,$operationalHoursInAMonth,$vehicleDocuments,$operatorLicense,$capacity,$projectStage,$quantity,$supplierPhone1)
	{
		try{
			
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO order_equipment(orderId,shiftTypeDay,shiftTypeNight,equipmentId,makeId,siteLocation,modelId,district,expectedStartDate,operatorAllowance,year,food,accommodation,operationalHoursInADay,operationalDaysInAMonth,operationalHoursInAMonth,vehicleDocuments,operatorLicense,capacity,projectStage,quantity,supplierId) VALUES (:orderId,:shiftTypeDay,:shiftTypeNight,:equipmentId,:makeId,:siteLocation,:modelId,:district,:expectedStartDate,:operatorAllowance,:year,:food,:accommodation,:operationalHoursInADay,:operationalDaysInAMonth,:operationalHoursInAMonth,:vehicleDocuments,:operatorLicense,:capacity,:projectStage,:quantity,:supplierId)");  
		    

			$st=$db->prepare("SELECT id from suppliers WHERE phone=:phone");
			$st->bindParam("phone",$supplierPhone1,PDO::PARAM_STR);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($data)$supplierId=$data->id;
			else $supplierId=0;
			// if($capacity=='')$capacity=0;

			$stmt->bindParam("shiftTypeDay", $shiftTypeDay,PDO::PARAM_BOOL) ;
			$stmt->bindParam("shiftTypeNight", $shiftTypeNight,PDO::PARAM_BOOL) ;
			$stmt->bindParam("equipmentId", $equipmentId,PDO::PARAM_INT) ;
			$stmt->bindParam("supplierId", $supplierId,PDO::PARAM_INT) ;
			$stmt->bindParam("orderId", $orderId,PDO::PARAM_INT) ;
			$stmt->bindParam("makeId", $makeId,PDO::PARAM_INT) ;
			$stmt->bindParam("quantity", $quantity,PDO::PARAM_STR) ;
			$stmt->bindParam("projectStage", $projectStage,PDO::PARAM_STR) ;
			$stmt->bindParam("capacity", $capacity,PDO::PARAM_STR) ;
			$stmt->bindParam("siteLocation", $siteLocation,PDO::PARAM_STR) ;
			$stmt->bindParam("modelId", $modelId,PDO::PARAM_INT) ;
			$stmt->bindParam("district", $district,PDO::PARAM_STR) ;
			$stmt->bindParam("year", $year,PDO::PARAM_STR) ;
			$stmt->bindParam("expectedStartDate", $expectedStartDate,PDO::PARAM_STR) ;
			$stmt->bindParam("operatorAllowance", $operatorAllowance,PDO::PARAM_BOOL) ;
			$stmt->bindParam("food", $food,PDO::PARAM_BOOL) ;
			$stmt->bindParam("accommodation", $accommodation,PDO::PARAM_BOOL) ;
			$stmt->bindParam("operationalHoursInADay", $operationalHoursInADay,PDO::PARAM_STR) ;
			$stmt->bindParam("operationalDaysInAMonth", $operationalDaysInAMonth,PDO::PARAM_STR) ;
			$stmt->bindParam("operationalHoursInAMonth", $operationalHoursInAMonth,PDO::PARAM_STR) ;
			$stmt->bindParam("vehicleDocuments", $vehicleDocuments,PDO::PARAM_STR) ;
			$stmt->bindParam("operatorLicense", $operatorLicense,PDO::PARAM_STR) ;
			$stmt->execute();
			return $db->lastInsertId();
			$db=null;
			
		}
		catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }


    public function leadEquipUpdate($leadEquipId,$shiftTypeDay,$shiftTypeNight,$equipmentId,$makeId,$location,$model,$expectedStartDate,$operatorAllowance,$year,$food,$accommodation,$operationalHoursInADay,$operationalDaysInAMonth,$vehicleDocuments,$operatorLicense,$capacity,$operationalHoursInAMonth,$projectStage,$quantity,$typeOfWork,$safetyMeasures,$duration,$districtId,$stateId)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("UPDATE lead_equipment SET shiftTypeDay=:shiftTypeDay,shiftTypeNight=:shiftTypeNight,equipmentId=:equipmentId,makeId=:makeId,location=:location,model=:model,expectedStartDate=:expectedStartDate,operatorAllowance=:operatorAllowance,year=:year,food=:food,accommodation=:accommodation,operationalHoursInADay=:operationalHoursInADay,operationalDaysInAMonth=:operationalDaysInAMonth,vehicleDocuments=:vehicleDocuments,operatorLicense=:operatorLicense,capacity=:capacity,operationalHoursInAMonth=:operationalHoursInAMonth,projectStage=:projectStage,quantity=:quantity,typeOfWork=:typeOfWork,safetyMeasures=:safetyMeasures,duration=:duration,districtId=:districtId,stateId=:stateId WHERE id=:leadEquipId");  
		    

			$st=$db->prepare("SELECT id from equipment_type WHERE name=:name");
			$st->bindParam("name", $equipmentId,PDO::PARAM_STR);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($data)$equipmentTypeId=$data->id;
			else $makeId=$data;
			
			$st=$db->prepare("SELECT id from make WHERE name=:name");
			$st->bindParam("name",$makeId,PDO::PARAM_STR);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($data)$makeId=$data->id;
			else $makeId=$data;
			
			$paperWorkAndAdvance=0;
			$priceCard=0;
			$priceTag=0;
			$movedToOrders=0;
			$accept=0;
			$cancelled=0;
			$confirmed=0;
			$accept=0;

			$stmt->bindParam("duration", $duration,PDO::PARAM_STR) ;
			$stmt->bindParam("leadEquipId", $leadEquipId,PDO::PARAM_INT) ;
			$stmt->bindParam("equipmentId", $equipmentTypeId,PDO::PARAM_INT) ;
			$stmt->bindParam("makeId", $makeId,PDO::PARAM_INT) ;
			$stmt->bindParam("quantity", $quantity,PDO::PARAM_STR) ;
			$stmt->bindParam("projectStage", $projectStage,PDO::PARAM_STR) ;
			$stmt->bindParam("capacity", $capacity,PDO::PARAM_STR) ;
			$stmt->bindParam("location", $location,PDO::PARAM_STR) ;
			$stmt->bindParam("model", $model,PDO::PARAM_STR) ;
			$stmt->bindParam("year", $year,PDO::PARAM_STR) ;
			$stmt->bindParam("expectedStartDate", $expectedStartDate,PDO::PARAM_STR) ;
			$stmt->bindParam("operatorAllowance", $operatorAllowance,PDO::PARAM_BOOL) ;
			$stmt->bindParam("food", $food,PDO::PARAM_BOOL) ;
			$stmt->bindParam("shiftTypeNight", $shiftTypeNight,PDO::PARAM_BOOL) ;
			$stmt->bindParam("shiftTypeDay", $shiftTypeDay,PDO::PARAM_BOOL) ;
			$stmt->bindParam("accommodation", $accommodation,PDO::PARAM_BOOL) ;
			$stmt->bindParam("operationalHoursInADay", $operationalHoursInADay,PDO::PARAM_STR) ;
			$stmt->bindParam("operationalDaysInAMonth", $operationalDaysInAMonth,PDO::PARAM_STR) ;
			$stmt->bindParam("operationalHoursInAMonth", $operationalHoursInAMonth,PDO::PARAM_STR) ;
			$stmt->bindParam("vehicleDocuments", $vehicleDocuments,PDO::PARAM_BOOL) ;
			$stmt->bindParam("safetyMeasures", $safetyMeasures,PDO::PARAM_BOOL) ;
			$stmt->bindParam("operatorLicense", $operatorLicense,PDO::PARAM_BOOL) ;
			$stmt->bindParam("typeOfWork", $typeOfWork,PDO::PARAM_STR) ;
			$stmt->bindParam("districtId", $districtId,PDO::PARAM_INT) ;
			$stmt->bindParam("stateId", $stateId,PDO::PARAM_INT) ;
			$stmt->execute();
			$db=null;
			return true;
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
	    }
    }

	public function isActivated($leadId)
	 {
		try{
		
			$db = getDB();
			$isActive=1;
		    $st = $db->prepare("SELECT activate FROM leads WHERE id=:id and isActive=:isActive");  
		    $st->bindParam("id",$leadId,PDO::PARAM_BOOL);
			$st->bindParam("isActive",$isActive,PDO::PARAM_BOOL);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->activate;
			}
			else
			{
				return false;
			}
			$db=null;
			return true;
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
	    }
     }

	public function checkLeadInOrders($id)
	{
		try{
			$db = getDB();
		    $st = $db->prepare("SELECT * FROM orders WHERE leadId=:id");  
		    $st->bindParam("id",$id,PDO::PARAM_BOOL);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				return $data->id;
			}
			else
			{
				return false;
			}
			$db=null;
			return true;
		}
			catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }


	public function moveToOrders($id)
	{
		try{
			$db = getDB();
		    $st = $db->prepare("SELECT * FROM lead_equipment WHERE id=:id");  
		    $st->bindParam("id",$id,PDO::PARAM_BOOL);
			$st->execute();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($data)
			{
				$responseObj=new stdClass;
				$orderId=$this->checkLeadInOrders($data->leadId);
				if($orderId)
				{
					// $shiftType="";
					// if($data->shiftTypeDay)$shiftType="day";
					// if($data->shiftTypeNight)$shiftType="night";
					$orderEquipId=$this->orderEquipAdd($orderId,$data->shiftTypeDay,$data->shiftTypeNight,$data->equipmentId,$data->makeId,$data->location,$data->modelId,$data->district,$data->expectedStartDate,$data->operatorAllowance,$data->year,$data->food,$data->accommodation,$data->operationalHoursInADay,$data->operationalDaysInAMonth,$data->operationalHoursInAMonth,$data->vehicleDocuments,$data->operatorLicense,$data->capacity,$data->projectStage,$data->quantity,$data->supplierPhone1);
					
				}
				else
				{
					$stmt = $db->prepare("SELECT * FROM leads WHERE id=:id and isActive=:isActive");
					$isActive=1;
					$stmt->bindParam("isActive",$isActive,PDO::PARAM_BOOL);
					$stmt->bindParam("id",$data->leadId,PDO::PARAM_INT);
					$stmt->execute();
					$leadData=$stmt->fetch(PDO::FETCH_OBJ);
					$orderId=$this->orderAdd($leadData->customerId,$leadData->tableName,$data->leadId);
					
					$orderEquipId=$this->orderEquipAdd($orderId,$data->shiftTypeDay,$data->shiftTypeNight,$data->equipmentId,$data->makeId,$data->location,$data->modelId,$data->district,$data->expectedStartDate,$data->operatorAllowance,$data->year,$data->food,$data->accommodation,$data->operationalHoursInADay,$data->operationalDaysInAMonth,$data->operationalHoursInAMonth,$data->vehicleDocuments,$data->operatorLicense,$data->capacity,$data->projectStage,$data->quantity,$data->supplierPhone1);
					
				}
				$db=null;
				$responseObj->orderId=$orderId;
				$responseObj->orderEquipId=$orderEquipId;
				return $responseObj;
			}
			else
			{
				return false;
			}
			
		}
			catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
    }

	public function setAttachment($target_file,$leadEquipId)
	{
		try{

			$db=getDB();
			$st=$db->prepare("INSERT INTO attachments (tableName,url) VALUES (:tableName,:url)");
			$tableName="lead_equipment";
			$st->bindParam("tableName",$tableName,PDO::PARAM_STR);
			$st->bindParam("url",$target_file,PDO::PARAM_STR);
			$st->execute();
			$attachment_id=$db->lastInsertId();
			$st=$db->prepare("UPDATE lead_equipment SET attachmentId=:attachmentId WHERE id=:id");
			$st->bindParam("id",$leadEquipId,PDO::PARAM_STR);
			$st->bindParam("attachmentId",$attachment_id,PDO::PARAM_STR);
			$st->execute();
     	}
	      catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}

	public function getTypeOfWorkID($name)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT id FROM type_of_work WHERE name=:name");
			$stmt->bindParam("name", $name,PDO::PARAM_STR);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);

			return $data->id; 	
     	}
		catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}

	public function getSourcesID($name)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT id FROM sources WHERE name=:name");
			$stmt->bindParam("name", $name,PDO::PARAM_STR);  
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			return $data->id; 	
     	}
		catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}


     public function leadAdd($name,$phone,$leadManagerId,$LeadOperatorId,$tableName,$leadPriority,$leadDate,$leadType,$sources,$typeOfWork)
	{
		try{
			$db = getDB();
		    $customerId=$this->checkRenterInDB($phone);
		    $sourcesID=$this->getSourcesID($sources);
		    $typeOfWorkID=$this->getTypeOfWorkID($typeOfWork);
		    if($customerId==0)
			{
				$isActive=1;
				$stmt = $db->prepare("INSERT INTO renters(renterName,phone,isActive,createdBy,lastUpdatedBy) VALUES (:renterName,:phone,:isActive,:createdBy,:lastUpdatedBy)"); 
				$stmt->bindParam("renterName", $name,PDO::PARAM_STR) ;
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
				$stmt->bindParam("phone", $phone,PDO::PARAM_STR);
				$stmt->bindParam("createdBy", $_SESSION['userIdLMS'],PDO::PARAM_INT) ;
				$stmt->bindParam("lastUpdatedBy", $_SESSION['userIdLMS'],PDO::PARAM_INT) ;
				$stmt->execute();
			}
			else
			{
				$isActive=1;
      			$stmt = $db->prepare("UPDATE renters SET renterName=:renterName,lastUpdatedBy=:lastUpdatedBy WHERE phone=:phone AND isActive=:isActive"); 
				$stmt->bindParam("isActive", $isActive,PDO::PARAM_STR) ;
				$stmt->bindParam("renterName", $name,PDO::PARAM_STR) ;
				$stmt->bindParam("phone", $phone,PDO::PARAM_STR);
				$stmt->bindParam("lastUpdatedBy", $_SESSION['userIdLMS'],PDO::PARAM_INT) ;
				$stmt->execute();
			}
			$stmt = $db->prepare("INSERT INTO leads(leadManagerId,customerId,tableName,leadPriority,leadDate,isActive,createdOn,createdBy,lastUpdatedOn,lastUpdatedBy,deletedOn,deletedBy,leadType,sources,typeOfWork,renterAcceptQuotation,supplierConfirmed,paperWorkAndAdvance,priceCardSent,movedToOrders,accept,reject,activate,quotationSent) VALUES (:leadManagerId,:customerId,:tableName,:leadPriority,:leadDate,:isActive,:createdOn,:createdBy,:lastUpdatedOn,:lastUpdatedBy,:deletedOn,:deletedBy,:leadType,:sources,:typeOfWork,:renterAcceptQuotation,:supplierConfirmed,:paperWorkAndAdvance,:priceCardSent,:movedToOrders,:accept,:reject,:activate,:quotationSent)");  
		    
		    $time = date('Y-m-d H:i:s',time());
		    $defaultTime="0000-00-00 00:00:00";
		    $isActive=1;
		    $deletedOn=$defaultTime;
		    $deletedBy=0;
		    $quotationSent=0;
		    $renterAcceptQuotation=0;
		    $supplierConfirmed=0;
		    $priceCardSent=0;
		    $movedToOrders=0;
		    $paperWorkAndAdvance=0;
		    $accept=0;
		    $reject=0;
		    $activate=0;
	    	$createdBy=$_SESSION['userIdLMS'];
	    	$lastUpdatedBy=$_SESSION['userIdLMS'];
	    	$customerId=$this->checkRenterInDB($phone);
		    
    		$stmt->bindParam("quotationSent", $quotationSent,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("renterAcceptQuotation", $renterAcceptQuotation,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("supplierConfirmed", $supplierConfirmed,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("paperWorkAndAdvance", $paperWorkAndAdvance,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("priceCardSent", $priceCardSent,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("movedToOrders", $movedToOrders,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("accept", $accept,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("reject", $reject,PDO::PARAM_BOOL) ;
    		$stmt->bindParam("activate", $activate,PDO::PARAM_BOOL) ;	    		
			$stmt->bindParam("leadManagerId", $leadManagerId,PDO::PARAM_INT) ;
			$stmt->bindParam("typeOfWork", $typeOfWorkID,PDO::PARAM_INT) ;
			$stmt->bindParam("sources", $sourcesID,PDO::PARAM_INT) ;
			$stmt->bindParam("customerId", $customerId,PDO::PARAM_INT) ;
			$stmt->bindParam("tableName", $tableName,PDO::PARAM_STR) ;
			$stmt->bindParam("leadPriority", $leadPriority,PDO::PARAM_STR) ;
			$stmt->bindParam("leadDate", $leadDate,PDO::PARAM_STR) ;
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
			$stmt->bindParam("createdOn", $time,PDO::PARAM_STR) ;
			$stmt->bindParam("createdBy", $createdBy,PDO::PARAM_INT) ;
			$stmt->bindParam("lastUpdatedOn", $time,PDO::PARAM_STR) ;
			$stmt->bindParam("lastUpdatedBy", $lastUpdatedBy,PDO::PARAM_INT) ;
			$stmt->bindParam("deletedOn", $deletedOn,PDO::PARAM_STR) ;
			$stmt->bindParam("deletedBy", $deletedBy,PDO::PARAM_INT) ;
			$stmt->bindParam("leadType", $leadType,PDO::PARAM_STR) ;
			$stmt->execute();
			$last_id = $db->lastInsertId();
			if($LeadOperatorId!=0)
			{
				$stmt = $db->prepare("UPDATE leads set LeadOperatorId=:LeadOperatorId WHERE id=:id");
				$stmt->bindParam("id", $last_id,PDO::PARAM_INT) ;
				$stmt->bindParam("LeadOperatorId", $LeadOperatorId,PDO::PARAM_INT) ;
				$stmt->execute();
			}
			$db = null;
			echo $last_id;
			return $last_id;
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
    	}
	}


	public function orderAdd($customerId,$tableName,$leadId)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("INSERT INTO orders(leadId,customerId,tableName,orderStartDate,isActive,createdOn,createdBy,lastUpdatedOn,lastUpdatedBy) VALUES (:leadId,:customerId,:tableName,:orderStartDate,:isActive,:createdOn,:createdBy,:lastUpdatedOn,:lastUpdatedBy)");  
		    
		    $time = date('Y-m-d H:i:s',time());
		    $defaultTime="0000-00-00 00:00:00";
		    $isActive=1;
		    $createdBy=$_SESSION['userIdLMS'];
	    	$lastUpdatedBy=$_SESSION['userIdLMS'];
	    	
    		$stmt->bindParam("customerId", $customerId,PDO::PARAM_INT) ;
			$stmt->bindParam("leadId", $leadId,PDO::PARAM_INT) ;
			$stmt->bindParam("tableName", $tableName,PDO::PARAM_STR) ;
			$stmt->bindParam("orderStartDate", $time,PDO::PARAM_STR) ;
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
			$stmt->bindParam("createdOn", $time,PDO::PARAM_STR) ;
			$stmt->bindParam("createdBy", $createdBy,PDO::PARAM_INT) ;
			$stmt->bindParam("lastUpdatedOn", $time,PDO::PARAM_STR) ;
			$stmt->bindParam("lastUpdatedBy", $lastUpdatedBy,PDO::PARAM_INT) ;
			$stmt->execute();
			$last_id = $db->lastInsertId();
			return $last_id;
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
    	}
	}

	public function checkLevelInDB($level)
	{
		try{
      	  $db = getDB();
          $stmt = $db->prepare("SELECT access FROM access_level WHERE access=:access");
          $stmt->bindParam("access", $level,PDO::PARAM_STR);
  		  $stmt->execute();
          $data=$stmt->fetch(PDO::FETCH_OBJ);
          $db = null;
          if($data)
          {
          		return true;
          }
          else
          {
               return false;
          }
      }catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
    }
  	  
  	
 	}

 	public function checkUserInDB($phone)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM staff_login WHERE phone=:phone AND isActive=:isActive");
			$stmt->bindParam("phone", $phone,PDO::PARAM_STR);
			$isActive=1;
			$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;
			if($data)
			{
				return $data;
			}
			else
			{
				return false;
			}
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
    	}
 	}
 	
 	public function checkUserInDBAll($phone)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM staff_login WHERE phone=:phone");
			$stmt->bindParam("phone", $phone,PDO::PARAM_STR);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;
			if($data)
			{
				return $data;
			}
			else
			{
				return false;
			}
		}
		catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
    	}
  	
 	}

    public function checkRenterInDB($phone)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM renters WHERE phone=:phone");
			$stmt->bindParam("phone", $phone,PDO::PARAM_STR);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;
			if($data)
			{
				return $data->id;
			}
			else
			{
			   return false;
			}
		}catch(PDOException $e) {
		http_response_code($GLOBALS['connection_error']);
		echo $e->getMessage();
		}
 	}

 	public function getRenterName($phone)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT renterName,companyName FROM renters WHERE phone=:phone");
			$stmt->bindParam("phone", $phone,PDO::PARAM_STR);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;
			if($data)
			{
				return $data;
			}
			else
			{
			   return false;
			}
		}catch(PDOException $e) {
		http_response_code($GLOBALS['connection_error']);
		echo $e->getMessage();
		}
 	}

	public function checkSupplierInDB($phone)
	{
		try
		{
			$db = getDB();
			$stmt = $db->prepare("SELECT * FROM suppliers WHERE phone=:phone");
			$stmt->bindParam("phone", $phone,PDO::PARAM_STR);
			$stmt->execute();
			$data=$stmt->fetch(PDO::FETCH_OBJ);
			$db = null;
			if($data)
			{
				return $data;
			}
			else
			{
				return false;
			}
		}catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
    	}
 	}
    
    public function addSupplierInDB($phone,$name)
	{
		try{
      	  $db = getDB();
      	  $isActive=1;
          $stmt = $db->prepare("INSERT INTO suppliers (phone,supplierName,isActive) VALUES (:phone,:name,:isActive)");
          $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
  		  $stmt->bindParam("phone", $phone,PDO::PARAM_STR);
  		  $stmt->bindParam("name", $name,PDO::PARAM_STR);
  		  $stmt->execute();
          
      }catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
	    }
      	
     }

    public function updateSupplierInDB($phone,$name)
	{
		try{
      	  $db = getDB();
      	  $isActive=1;
          $stmt = $db->prepare("UPDATE suppliers SET isActive=:isActive,phone=:phone,supplierName=:name WHERE phone=:phone");
          $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
  		  $stmt->bindParam("phone", $phone,PDO::PARAM_STR);
  		  $stmt->bindParam("name", $name,PDO::PARAM_STR);
  		  $stmt->execute();
          
      }catch(PDOException $e) {
			http_response_code($GLOBALS['connection_error']);
			echo $e->getMessage();
	    }
      	
     }



	public function leadUserAdd($password,$email,$name,$phone,$accessLevels,$majorRole)
	{
		try{
			$db = getDB();
			$id1=$this->checkUserInDBAll($phone);
			if($id1)
			{
				$id=$id1->userId;
				echo "User Exists In DB.\n";
				if($id1->isActive==0)	
				{
					echo "User Exists But Not Active.\n";
					$isActive=1;
					if($_SESSION['majorRoleLMS']=="manager")
					{
						$superManagerId=0;
						$y=$this->checkUserInDB($_SESSION['phoneLMS']);
				        $stmt = $db->prepare("UPDATE staff_login SET isActive=:isActive,adminId=:adminId,managerId=:managerId,superManagerId=:superManagerId,majorRole=:majorRole WHERE userId=:id");
				        $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				    	$stmt->bindParam("managerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				    	$stmt->bindParam("superManagerId", $superManagerId,PDO::PARAM_INT);
				    	$stmt->bindParam("adminId", $y->adminId,PDO::PARAM_INT);
				    	$stmt->bindParam("majorRole", $majorRole,PDO::PARAM_STR);
						$stmt->bindParam("id", $id,PDO::PARAM_INT);
						$stmt->execute();
					}
					else if($_SESSION['majorRoleLMS']=="admin")
					{
						$stmt = $db->prepare("UPDATE staff_login SET isActive=:isActive,adminId=:adminId,majorRole=:majorRole WHERE userId=:id");
				        $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				    	$stmt->bindParam("adminId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				    	$stmt->bindParam("majorRole", $majorRole,PDO::PARAM_STR);
						$stmt->bindParam("id", $id,PDO::PARAM_INT);
						$stmt->execute();
					}
					else if($_SESSION['majorRoleLMS']=="superManager")
					{
						$y=$this->checkUserInDB($_SESSION['phoneLMS']);
				        $stmt = $db->prepare("UPDATE staff_login SET isActive=:isActive,adminId=:adminId,superManagerId=:superManagerId,majorRole=:majorRole WHERE userId=:id");
				        $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
				    	$stmt->bindParam("superManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
				    	$stmt->bindParam("adminId", $y->adminId,PDO::PARAM_INT);
				    	$stmt->bindParam("majorRole", $majorRole,PDO::PARAM_STR);
						$stmt->bindParam("id", $id,PDO::PARAM_INT);
						$stmt->execute();
					}
					else
					{
				        $db = null;
				    	return false;
					}
					if($majorRole=="superManager")
	                {

	                	$accessLevel=7;
	                	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
		                $stmt2->bindParam("userid", $id,PDO::PARAM_INT) ;
		                $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
		                $stmt2->execute();
		                $accessLevel=9;
	                	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
		                $stmt2->bindParam("userid", $id,PDO::PARAM_INT) ;
		                $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
		                $stmt2->execute();
	                }
					else 
					{
						if(in_array("leads", $accessLevels))
					    {
					    	$accessLevel=7;
					    	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
					        $stmt2->bindParam("userid", $id,PDO::PARAM_INT) ;
					        $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
					        $stmt2->execute();
					    }
					    else if(in_array("fulfillment", $accessLevels))
					    {
					    	$accessLevel=9;
					    	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
					        $stmt2->bindParam("userid", $id,PDO::PARAM_INT) ;
					        $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
					        $stmt2->execute();
					    }
				    }
				    
				}
				else
				{
					echo "User Exists And Active.\n";
					http_response_code($GLOBALS['forbidden']);
					return false;
				}
				$x=$this->leadUserUpdate($password,$email,$name,$phone,$phone);
				$db = null;
				return true;
			}
			else
			{
				$stmt = $db->prepare("INSERT INTO staff_login(password,email,name,phone,majorRole,adminId,managerId,superManagerId,isActive) VALUES (:hash_password,:email,:name,:phone,:majorRole,:adminId,:managerId,:superManagerId,:isActive)");  
                $hash_password= hash('sha256', $password);
                $isActive=1;
                $stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
                $stmt->bindParam("email", $email,PDO::PARAM_STR) ;
                $stmt->bindParam("name", $name,PDO::PARAM_STR) ;
                $stmt->bindParam("phone", $phone,PDO::PARAM_STR) ;
                $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
                
                if($_SESSION['majorRoleLMS']=="admin")
            	{
	                $stmt->bindParam("majorRole", $majorRole,PDO::PARAM_STR) ;
                    $managerId=0;
	                $superManagerId=0;
	                $stmt->bindParam("adminId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
	                $stmt->bindParam("managerId", $managerId,PDO::PARAM_INT);
	                $stmt->bindParam("superManagerId", $superManagerId,PDO::PARAM_INT);
                }
      			else if($_SESSION['majorRoleLMS']=="superManager")
            	{
	                $y=$this->checkUserInDB($_SESSION['phoneLMS']);
				    $managerId=0;
	                $stmt->bindParam("majorRole", $majorRole,PDO::PARAM_STR) ;
                    $stmt->bindParam("managerId", $managerId,PDO::PARAM_INT);
	                $stmt->bindParam("adminId", $y->adminId,PDO::PARAM_INT);
	                $stmt->bindParam("superManagerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);              
                }
      			else if($_SESSION['majorRoleLMS']=="manager")
            	{
            		$stmt->bindParam("majorRole", $majorRole,PDO::PARAM_STR) ;
                    $y=$this->checkUserInDB($_SESSION['phoneLMS']);
				    $superManagerId=0;
	                $stmt->bindParam("superManagerId", $superManagerId,PDO::PARAM_INT);
	                $stmt->bindParam("managerId", $_SESSION['userIdLMS'],PDO::PARAM_INT);
                	$stmt->bindParam("adminId", $y->adminId,PDO::PARAM_INT) ;
                }
      			else
            	{
	                $db=null;
					return false;
      			}
                $stmt->execute();
                $userinfo=$this->checkUserInDB($phone);
                $userid=$userinfo->userId;
                
                if($majorRole=="superManager")
                {
                	$accessLevel=7;
                	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
	                $stmt2->bindParam("userid", $userid,PDO::PARAM_INT) ;
	                $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
	                $stmt2->execute();
	                $accessLevel=9;
                	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
	                $stmt2->bindParam("userid", $userid,PDO::PARAM_INT) ;
	                $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
	                $stmt2->execute();
                }
                else
                {
	                if(in_array("leads", $accessLevels))
	                {
	                	$accessLevel=7;
	                	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
		                $stmt2->bindParam("userid", $userid,PDO::PARAM_INT) ;
		                $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
		                $stmt2->execute();
	                }
	                else if(in_array("fulfillment", $accessLevels))
	                {
	                	$accessLevel=9;
	                	$stmt2 = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userid,:accessLevel)");  
		                $stmt2->bindParam("userid", $userid,PDO::PARAM_INT) ;
		                $stmt2->bindParam("accessLevel", $accessLevel,PDO::PARAM_INT) ;
		                $stmt2->execute();
	                }
	                else
	                {
	                	http_response_code($GLOBALS['unauthorized']);
	                }
				}
				$db=null;
				return true;
			}
           }catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}

	
	public function leadUserDelete($phone)
	{
		try{
		  
			$db = getDB();
			$id=$this->checkUserInDB($phone);
			if($id)
			{
				if(($_SESSION['majorRoleLMS']=="manager" && $id->majorRole=="operator")||($_SESSION['majorRoleLMS']=="superManager" && $id->majorRole=="manager")||$_SESSION['majorRoleLMS']=="admin")
          		{
          			$isActive=0;
      				$stmt = $db->prepare("UPDATE staff_login SET isActive=:isActive WHERE userId=:userId");  
      				$stmt->bindParam("userId", $id->userId,PDO::PARAM_INT) ;
      				$stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
      				$stmt->execute();
      				$stmt = $db->prepare("DELETE from access WHERE userId=:userId");
      		    	$stmt->bindParam("userId", $id->userId,PDO::PARAM_INT) ;
      				$stmt->execute();
      				return true;
          		}
		  	}
			else
			{
				return false;
			}
           }catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}

	public function leadUserUpdate($password,$email,$name,$newPhone,$oldPhone)
	{
		try{
		  
			$db = getDB();
			$id=$this->checkUserInDB($oldPhone);
			if($id)
			{
	          	$id=$id->userId;
	          	if($password)
	      		{
				    $stmt = $db->prepare("UPDATE staff_login SET password=:hash_password,email=:email,name=:name,phone=:phone WHERE userId=:userId");
	                $hash_password= hash('sha256', $password);
	                $stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
	                $stmt->bindParam("email", $email,PDO::PARAM_STR) ;
	                $stmt->bindParam("name", $name,PDO::PARAM_STR) ;
	                $stmt->bindParam("phone", $newPhone,PDO::PARAM_STR) ;
	                $stmt->bindParam("userId", $id,PDO::PARAM_INT) ;
	                $stmt->execute();
				}
				else
				{
					$stmt = $db->prepare("UPDATE staff_login SET email=:email,name=:name,phone=:phone WHERE userId=:userId");  
	                $stmt->bindParam("email", $email,PDO::PARAM_STR) ;
	                $stmt->bindParam("name", $name,PDO::PARAM_STR) ;
	                $stmt->bindParam("phone", $newPhone,PDO::PARAM_STR) ;
	                $stmt->bindParam("userId", $id,PDO::PARAM_INT) ;
	                $stmt->execute();
				}
				$db = null;
	            http_response_code($GLOBALS['success']);
				return true;
			}
			else
			{
				return false;
			}
           }catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}
	
	public function leadUserProfileUpdate($email,$name,$newPhone,$password)
	{
		try{
		  
		  $db = getDB();
          $id=$_SESSION['userIdLMS'];
          if($id)
          {
          	if($password)
	        {  	
	          	$stmt = $db->prepare("UPDATE staff_login SET password=:hash_password,email=:email,name=:name,phone=:phone WHERE userId=:userId AND isActive=:isActive");  
	            $hash_password= hash('sha256', $password);
	            $stmt->bindParam("hash_password", $hash_password,PDO::PARAM_STR) ;
	            $stmt->bindParam("email", $email,PDO::PARAM_STR) ;
	            $stmt->bindParam("name", $name,PDO::PARAM_STR) ;
	            $stmt->bindParam("phone", $newPhone,PDO::PARAM_STR) ;
	            $isActive=1;
	            $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
				$stmt->bindParam("userId", $id,PDO::PARAM_INT) ;
	            $stmt->execute();
	            $db = null;
	            http_response_code($GLOBALS['success']);
				return true;
			}
			else
			{
				$stmt = $db->prepare("UPDATE staff_login SET email=:email,name=:name,phone=:phone WHERE userId=:userId AND isActive=:isActive");  
	            $stmt->bindParam("email", $email,PDO::PARAM_STR) ;
	            $stmt->bindParam("name", $name,PDO::PARAM_STR) ;
	            $stmt->bindParam("phone", $newPhone,PDO::PARAM_STR) ;
	            $isActive=1;
	            $stmt->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
				$stmt->bindParam("userId", $id,PDO::PARAM_INT) ;
	            $stmt->execute();
	            $db = null;
	            http_response_code($GLOBALS['success']);
				return true;
			}
		  }
			else
			{
				return false;
			}
           }catch(PDOException $e) {
  			http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
	    }
	}
	

	public function leadUserAssignLevel($id,$level)
	{
		try{
		  
		  $db = getDB();
          $st = $db->prepare("SELECT * from staff_login WHERE userId=:id and isActive=:isActive");  
          $st->bindParam("id", $id,PDO::PARAM_INT);
          $isActive=1;
          $st->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
	  	  $st->execute();
          $count=$st->rowCount();
          if($count==1)
          {
                
                $stmt = $db->prepare("SELECT id FROM access_level WHERE access=:access");
	            $stmt->bindParam("access", $level,PDO::PARAM_STR);
      		    $stmt->execute();
      		    $data=$stmt->fetch(PDO::FETCH_OBJ);
      		    if($data)
      		    {
      		    	$stmt = $db->prepare("INSERT INTO access(userId,accessLevel) VALUES (:userId,:accessLevel)");
      		    	$stmt->bindParam("userId", $id,PDO::PARAM_INT);
      		    	$stmt->bindParam("accessLevel", $data->id,PDO::PARAM_INT);
      		    	$stmt->execute();
      		    }
                $db = null;
                return true;
			}else{
				$db=null;
				return false;

           	}
           	}catch(PDOException $e){
           		http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
           	}
		}

	public function leadUserDeallocateLevel($id,$level)
	{
		try{
		  
		  $db = getDB();
          $st = $db->prepare("SELECT * from staff_login WHERE userId=:id and isActive=:isActive");  
          $st->bindParam("id", $id,PDO::PARAM_INT);
          $isActive=1;
          $st->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
	  	  $st->execute();
          $count=$st->rowCount();
          if($count==1)
          {
                
                $stmt = $db->prepare("SELECT id FROM access_level WHERE access=:access");
	            $stmt->bindParam("access", $level,PDO::PARAM_STR);
      		    $stmt->execute();
      		    $data=$stmt->fetch(PDO::FETCH_OBJ);
      		    if($data)
      		    {
      		    	$stmt = $db->prepare("DELETE from access WHERE userId=:userId,accessLevel=:accessLevel");
      		    	$stmt->bindParam("userId", $id,PDO::PARAM_INT);
      		    	$stmt->bindParam("accessLevel", $data->id,PDO::PARAM_INT);
      		    	$stmt->execute();
      		    }
                $db = null;
                return true;
			}else{
				$db=null;
				return false;

           	}
           	}catch(PDOException $e){
           		http_response_code($GLOBALS['connection_error']);
  			echo $e->getMessage();
           	}
		}

	
	public function setLastUpdated($id)
	{
		try{
			$db = getDB();
			$st = $db->prepare("SELECT * from leads WHERE id=:id and isActive=:isActive");  
			$isActive=1;
			$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL) ;
			$st->bindParam("id", $id,PDO::PARAM_INT);
			$st->execute();
			$count=$st->rowCount();
			if($count>=1)
			{
				$time = date('Y-m-d H:i:s',time());
				$lastUpdatedBy=$_SESSION['userIdLMS'];
				$stmt = $db->prepare("UPDATE leads SET lastUpdatedBy=:lastUpdatedBy,lastUpdatedOn=:lastUpdatedOn WHERE id=:id");  
				$stmt->bindParam("lastUpdatedBy", $lastUpdatedBy,PDO::PARAM_INT) ;
				$stmt->bindParam("lastUpdatedOn", $time,PDO::PARAM_STR) ;
				$stmt->bindParam("id", $id,PDO::PARAM_INT);
				$stmt->execute();
				$db = null;
				return true;
			}
			else
			{
				$db=null;
				return false;
			}
		}catch(PDOException $e){
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
		}	
	}

	public function assignFulfilmentOperator($id,$phone,$typeOfLeadFO,$closedPriceFO,$profitMarginFO,$easeOfFulfillmentFO)
	{
		try
		{
			$db = getDB();
			$st = $db->prepare("SELECT * from lead_equipment WHERE id=:id AND cancelled=:cancelled");  
			$st->bindParam("id", $id,PDO::PARAM_INT);
			$cancelled=0;
			$st->bindParam("cancelled", $cancelled,PDO::PARAM_INT);
			$st->execute();
			$count=$st->rowCount();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($count>=1)
			{
				$this->setLastUpdated($data->leadId);
				$userDetails=$this->checkUserInDB($phone);
				if($userDetails)
				{
					$fulfilmentOperator=$userDetails->userId;
					$accessLevels=$this->getAccessLevels($fulfilmentOperator);
					if($userDetails->majorRole=="operator" && in_array("fulfillment", $accessLevels))
					{
						$stmt = $db->prepare("UPDATE lead_equipment SET fulfilmentOperator=:fulfilmentOperator,typeOfLeadFO=:typeOfLeadFO,closedPriceFO=:closedPriceFO,profitMarginFO=:profitMarginFO,easeOfFulfillmentFO=:easeOfFulfillmentFO  WHERE id=:id");  
						$stmt->bindParam("fulfilmentOperator", $fulfilmentOperator,PDO::PARAM_INT) ;
						$stmt->bindParam("typeOfLeadFO", $typeOfLeadFO,PDO::PARAM_STR);
			  			$stmt->bindParam("closedPriceFO", $closedPriceFO,PDO::PARAM_STR);
			  			$stmt->bindParam("profitMarginFO", $profitMarginFO,PDO::PARAM_STR);
			  			$stmt->bindParam("easeOfFulfillmentFO", $easeOfFulfillmentFO,PDO::PARAM_STR);
						$stmt->bindParam("id", $id,PDO::PARAM_INT);
						$stmt->execute();
						$db = null;
						return true;
					}
					else
					{
						echo "User is not Fulfillment Operator";
						return false;
					}
				}
				else
				{
					echo "User Not In DB";
					return false;
				}

			}
			else
			{
				echo "Lead Equip Not In DB";
				$db=null;
				return false;
	       	}
       	}catch(PDOException $e){
           		http_response_code($GLOBALS['connection_error']);
  				echo $e->getMessage();
           	}
	}

	public function assignLeadOperator($id,$phone)
	{
		try
		{
			$db = getDB();
			$st = $db->prepare("SELECT * from leads WHERE id=:id and isActive=:isActive");  
			$isActive=1;
			$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$st->bindParam("id", $id,PDO::PARAM_INT);
			$st->execute();
			$count=$st->rowCount();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($count>=1)
			{
				$this->setLastUpdated($data->id);
				$userDetails=$this->checkUserInDB($phone);
				if($userDetails)
				{
					$leadOperator=$userDetails->userId;
					$accessLevels=$this->getAccessLevels($leadOperator);
					if($userDetails->majorRole=="operator" && in_array("leads", $accessLevels))
					{
						$stmt = $db->prepare("UPDATE leads SET LeadOperatorId=:LeadOperatorId WHERE id=:id");  
						$stmt->bindParam("LeadOperatorId", $leadOperator,PDO::PARAM_INT) ;
						$stmt->bindParam("id", $id,PDO::PARAM_INT);
						$stmt->execute();
						$db = null;
						return true;
					}
					else
					{
						echo "User is not Lead Operator";
						return false;
					}
				}
				else
				{
					echo "User Not In DB";
					return false;
				}

			}
			else
			{
				echo "Lead Not In DB";
				$db=null;
				return false;
	       	}
       	}catch(PDOException $e){
           		http_response_code($GLOBALS['connection_error']);
  				echo $e->getMessage();
           	}
	}

	public function assignLeadManager($id,$phone)
	{
		try
		{
			$db = getDB();
			$st = $db->prepare("SELECT * from leads WHERE id=:id and isActive=:isActive");  
			$isActive=1;
			$st->bindParam("isActive", $isActive,PDO::PARAM_BOOL);
			$st->bindParam("id", $id,PDO::PARAM_INT);
			$st->execute();
			$count=$st->rowCount();
			$data=$st->fetch(PDO::FETCH_OBJ);
			if($count>=1)
			{
				$this->setLastUpdated($data->id);
				$userDetails=$this->checkUserInDB($phone);
				if($userDetails)
				{
					$leadManagerId=$userDetails->userId;
					$accessLevels=$this->getAccessLevels($leadManagerId);
					if($userDetails->majorRole=="manager" && in_array("leads", $accessLevels))
					{
						$stmt = $db->prepare("UPDATE leads SET leadManagerId=:leadManagerId WHERE id=:id");  
						$stmt->bindParam("leadManagerId", $leadManagerId,PDO::PARAM_INT) ;
						$stmt->bindParam("id", $id,PDO::PARAM_INT);
						$stmt->execute();
						$db = null;
						return true;
					}
					else
					{
						echo "User is not Lead Manager";
						return false;
					}
				}
				else
				{
					echo "User Not In DB";
					return false;
				}

			}
			else
			{
				echo "Lead Not In DB";
				$db=null;
				return false;
	       	}
       	}catch(PDOException $e){
           		http_response_code($GLOBALS['connection_error']);
  				echo $e->getMessage();
           	}
	}

	public function assignFulfilmentManager($id,$phone,$typeOfLeadFM,$closedPriceFM,$profitMarginFM,$easeOfFulfillmentFM)
	{
		try
		{
			$db = getDB();
			$st = $db->prepare("SELECT * from lead_equipment WHERE id=:id and cancelled=:cancelled");
			$cancelled=0;
			$st->bindParam("id", $id,PDO::PARAM_INT);
			$st->bindParam("cancelled", $cancelled,PDO::PARAM_INT);
			$st->execute();
			$count=$st->rowCount();
			if($count>=1)
			{
	            $userDetails=$this->checkUserInDB($phone);
				if($userDetails)
				{
					$fulfilmentManager=$userDetails->userId;
					$accessLevels=$this->getAccessLevels($fulfilmentManager);
					if($userDetails->majorRole=="manager" && in_array("fulfillment", $accessLevels))
					{
						$stmt = $db->prepare("UPDATE lead_equipment SET fulfilmentManager=:fulfilmentManager,typeOfLeadFM=:typeOfLeadFM,closedPriceFM=:closedPriceFM,profitMarginFM=:profitMarginFM,easeOfFulfillmentFM=:easeOfFulfillmentFM WHERE id=:id");
			            $stmt->bindParam("fulfilmentManager", $fulfilmentManager,PDO::PARAM_INT) ;
						$stmt->bindParam("id", $id,PDO::PARAM_INT);
			  			$stmt->bindParam("typeOfLeadFM", $typeOfLeadFM,PDO::PARAM_STR);
			  			$stmt->bindParam("closedPriceFM", $closedPriceFM,PDO::PARAM_STR);
			  			$stmt->bindParam("profitMarginFM", $profitMarginFM,PDO::PARAM_STR);
			  			$stmt->bindParam("easeOfFulfillmentFM", $easeOfFulfillmentFM,PDO::PARAM_STR);
			  			$stmt->execute();
			            $db = null;
			            return true;
	        		}
	        		else
					{
						echo "User is not Fulfillment Manager";
						return false;
					}
				}
				else
				{
					echo "User Not In DB";
					return false;
				}

			}
			else
			{
				echo "Lead Equip Not In DB";
				$db=null;
				return false;
	       	}
       	}catch(PDOException $e){
           		http_response_code($GLOBALS['connection_error']);
  				echo $e->getMessage();
           	}
	}

	
	

	


	

}

?>