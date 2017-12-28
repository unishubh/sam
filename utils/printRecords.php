<?php

include('config.php');
include('mainClass.php');
$student_id='';
$program_name='';
$presents='';
$absents='';
$sl='';
$ml='';
$cl='';
$maternity='';
$duty='';
$month = '';
$year ='';
$month = 05;
$year = 2017;

$mainClass = new mainClass();

// if ( $_SERVER['REQUEST_METHOD'] === 'GET' ) 
// {
//     http_response_code($badRequest);
// }

/*if(!isset($_SESSION['role']) || empty($_SESSION['role'])){
    session_destroy();
    http_response_code($session_error);
}
*/
if(false)
{

}
else
{
	//echo 10;
	$params = array();
	$params = $_REQUEST;
	

	/*$student_id=$params['student_id'];*/
	$program_name=$params['program_name'];
	/*$presents=$params['presents'];
	$absents=$params['absents'];
	$sl=$params['sl'];
	$ml=$params['ml'];
	$cl=$params['cl'];
	$maternity=$params['maternity'];
	$duty=$params['duty'];*/
	//$month = $params['month'];
	//$year = $params['year'];
	//print_r($_REQUEST);
	//echo 5;
/*	$responseObj = ($mainClass->saveRecords($month,$year,$student_id,$program_name,$presents,$absents,$sl,$ml,$cl,$maternity,$duty));

	if($responseObj)
	{
		echo json_encode($responseObj);
		http_response_code($success);
	}*/
	
	
}




require('../fpdf181/fpdf.php');
class PDF extends FPDF

{

function Header()
{

	$this->SetFont('Arial','',15);
		$this->Image('logo_mid.png',5,1,'PNG');
		$this->Cell(15);
		$this->Cell(0,0,"ABV-Indian Institute of Information Technology and Management",0,0,'C');
		$this->Ln(25);
		//$this->Cell(5);
		$this->SetFont('Arial','B',10);
		$to_be_printed ="ABV-IIITM/F&A/Reg./2014"; 
		$this->Cell(0,0,$to_be_printed,0,0,'L');
		$this->Cell(0,0,"DATE : 02 Nov 2017",0,0,'R');
		$this->SetFont('Arial','',10);
		$this->Ln(10);
		$this->Cell(0,0,"From: Deputy Registrar(A&A)",0,0,'L');
		$this->Ln(10);

}

function Footer()
{
	$this->Ln(20);
	$this->SetFont('Arial','B',10);
	$this->Cell(0,0,"Deputy Registrar(A&A)",0,0,'L');
}
function FancyTable($header, $data, $month,$year)
	{
		
		// Column widths
		$this->SetFont('Arial','',15);
		$months=array('Jan',"Feb",'Mar','May','Apr','May','Jun','Jul','Aug','Sept',"Oct",'Nov','Dec');
		//echo $month;

		$month_ver = $months[$month].'-'.+$year%100;
		
		$count=0;
		$w = array(15, 35, 45, 40,20,20);
		
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		// Data
		foreach($data as $row)
		{

			$count++;
			$this->Cell($w[0],6,$count,'LR');
			$this->Cell($w[1],6,$row['rollno'],'LR');
			$this->Cell($w[2],6,$row['name'],'LR');
			$this->Cell($w[3],6,$row['account'],'LR');
			$this->Cell($w[4],6,$month_ver,'LR');
			$this->Cell($w[5],6,number_format($row['payable']),'LR');
		//	$this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
			$this->Ln();
		}

		$this->Cell(array_sum($w),0,'','T');
		//echo sizeof()
		
		// Closing line
		
	}

}
	$pdf = new PDF();
	// Column headings

	$header = array('S.No.','Roll Number','Name','Account No','Month','payable');
	// Data loading
	//$data = $pdf->LoadData('countries.txt');
	$db = getDB();
	//echo $month;
	//echo $program_name;
	$stmt2 = $db->prepare("SELECT students.name as name,students.rollno as rollno,students.bank_ac_number as account,payable FROM attendance LEFT JOIN students on attendance.student_id = students.id WHERE month=:month AND year=:year AND attendance.program_id=(SELECT id FROM program WHERE program_name = :program_name  )");

	$stmt2->bindParam("program_name", $program_name,PDO::PARAM_STR);
	$stmt2->bindParam("month", $month);
	$stmt2->bindParam("year", $year);
	$stmt2->execute();
	$data=$stmt2->fetchAll(PDO::FETCH_ASSOC);
	//print_r($data);
	//echo $month;
//	var_dump($data);
	$pdf->SetFont('Arial','',14);
	$pdf->AddPage();
	$pdf->FancyTable($header,$data,$month,$year);
	$pdf->Output();

?>