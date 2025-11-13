<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class MYPDF extends TCPDF {
	protected $schedule = '';


	public function get_schedule(){
		return $this->schedule;
	}

	public function set_schedule($schedule){
		$this->schedule = $schedule;
	}


	//Page header
	public function Header() {
		$sched = $this->get_schedule();

		$this->SetFont('times', 'NB', 25);
		// Title
		// $this->Image(base_url().'images/dcit_logo.png', 63, 8, 23, 21, 'PNG', 'http://www.ntc.org', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 15, 'Traced Contacts Report', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'N', 12);
		$this->Cell(0, 30, 'For Contact Tracing Purposes', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('times', 'B', 10);
	}

	// 	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		// $this->SetY(-12);
		// // Set font
		// // Page number
		// $this->SetFont('helvetica', 'I', 8);
		// $this->MultiCell(0, 5, "", 'B', 'C', 0, 1, '', '', true);
		// $this->MultiCell(0, 0, "This serves as an Official Receipt", 0, 'C', 0, 1, '', '', true);

	}

	public function set_permit_type($type){
		$this->permit_type = $type;
	}

	public function get_permit_type(){
		return $this->permit_type;
	}
}


$pdf = new MYPDF('L', PDF_UNIT, 'GOVERNMENTLEGAL', true, 'UTF-8', false);


// set default header data
$pdf->SetTitle('SY Schedule');

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 25, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(0);


$pdf->setPrintFooter(false);


// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 1);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 11);

// add a page
$pdf->AddPage();

$content = '
			<style>				
				img{
					width:60px;
				}
				h1{
					text-align:center;
					font-size: 30px;
				}
				table th, td{
					border: 1px double #737373;
				}
			</style>

			<h2>Individuals in contact with'.$user['firstname'].' '.$user['lastname'].' at '.$visiting_point['visiting_point_name'].' on '.date('M j, Y', strtotime($params['log_time'])).'</h2></br></br>

			<table cellpadding="5">
				<thead>

					<tr>
						<th><h4>Name</h4></th>
						<th><h4>Address</h4></th>
						<th><h4>Contact No.</h4></th>
						<th><h4>Email</h4></th>
						<th><h4>Temperature</h4></th>
						<th><h4>Log Time</h4></th>
					</tr>
				</thead>
				<tbody>';

foreach ($contacts as $row) {

	$content .= '<tr>
					<th>'.$row['firstname'].' '.$row['lastname'].'</th>
					<td>'.$row['address'].'</td>
					<td>'.$row['contact_number'].'</td>
					<td>'.$row['email'].'</td>
					<td>'.$row['temperature'].'</td>
					<td>'.date('M j, Y - h:i a', strtotime($row['log_time'])).'</td>
				</tr>
				';
}


	$content .= '
				</tbody>
			</table>';
				

$pdf->writeHTML($content);



$pdf->Output('schedule.pdf', 'I');


