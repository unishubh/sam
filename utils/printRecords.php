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
$month = '';
$year = '';

$mainClass = new mainClass();

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
	$month = $params['month'];
	$processed_date = $params['processed_date'];
	$year = $params['year'];
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
		$this->SetFont('Arial','B',10);
		$to_be_printed ="ABV-IIITM/F&A/Reg./2014"; 
		$this->Cell(0,0,$to_be_printed,0,0,'L');
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
function FancyTable($header, $data, $month,$year,$processed_date)
	{
		
		$this->SetFont('Arial','',15);
		$months=array('Jan',"Feb",'Mar','May','Apr','May','Jun','Jul','Aug','Sept',"Oct",'Nov','Dec');

		$month_ver = $months[$month].'-'.+$year%100;
		
		$count=0;
		$w = array(13, 35, 45, 33,20,20,37);
		
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],7,$header[$i],1,0,'C');
		$this->Ln();
		foreach($data as $row)
		{

			$count++;
			$this->Cell($w[0],7,$count,'LR');
			$this->Cell($w[1],7,$row['rollno'],'LR');
			$this->Cell($w[2],7,$row['name'],'LR');
			$this->Cell($w[3],7,$row['account'],'LR');
			$this->Cell($w[4],7,$month_ver,'LR');
			$this->Cell($w[5],7,number_format($row['payable']),'LR');
			$this->Cell($w[6],7,$processed_date,'LR');
			$this->Ln();
		}

		$this->Cell(array_sum($w),0,'','T');
		
	}

}
	$pdf = new PDF();

	$header = array('S.No.','Roll Number','Name','Account No','Month','payable','Process Date');
	$db = getDB();
	$stmt2 = $db->prepare("SELECT students.name as name,students.rollno as rollno,students.bank_ac_number as account,payable , processed_date	 FROM attendance LEFT JOIN students on attendance.student_id = students.id WHERE month=:month AND year=:year AND attendance.program_id=(SELECT id FROM program WHERE program_name = :program_name  ) AND attendance.processed_date=:processed_date");

	$stmt2->bindParam("program_name", $program_name,PDO::PARAM_STR);
	$stmt2->bindParam("month", $month);
	$stmt2->bindParam("year", $year);
	$stmt2->bindParam("processed_date", $processed_date);
	$stmt2->execute();
	$data=$stmt2->fetchAll(PDO::FETCH_ASSOC);
	$pdf->SetFont('Arial','',10);
	$pdf->AddPage();
	$pdf->FancyTable($header,$data,$month,$year,$processed_date);
	$pdf->Output();

?>