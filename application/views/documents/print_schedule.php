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

		$this->SetFont('times', 'NB', 13);
		// Title
		$this->Image(base_url().'images/dcit_logo.png', 63, 8, 23, 21, 'PNG', 'http://www.ntc.org', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 10, 'DIPOLOG CITY INSTITUTE OF TECHNOLOGY', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'N', 9);
		$this->Cell(0, 20, 'National Highway, Minaog, Dipolog City', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('times', 'B', 16);
		$this->Cell(0, 20, $sched['sched_name'], 0, 1, 'C', 0, '', 5, false, 'M', 'M');
	}

	// 	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-12);
		// Set font
		// Page number
		$this->SetFont('helvetica', 'I', 8);
		$this->MultiCell(0, 5, "", 'B', 'C', 0, 1, '', '', true);
		$this->MultiCell(0, 0, "This serves as an Official Receipt", 0, 'C', 0, 1, '', '', true);

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
$pdf->SetMargins(10, 38, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(0);


$pdf->setPrintFooter(false);

$pdf->set_schedule($schedule);

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
					border: 1px double #009933;
				}
			</style>
			
			<table cellpadding="5">
				<thead>

					<tr>
						<th width="8%"><h4>Subject Code</h4></th>
						<th width="25%"><h4>Descriptive Title</h4></th>
						<th width="6%"><h4>Year Level</h4></th>
						<th width="18%"><h4>Instructor</h4></th>
						<th width="4%"><h4>Lec</h4></th>
						<th width="4%"><h4>Lab</h4></th>
						<th width="4%"><h4>Units</h4></th>
						<th width="4%"><h4>Hrs/ Wk</h4></th>
						<th width="5%"><h4>Day</h4></th>
						<th width="7%"><h4>Start</h4></th>
						<th width="7%"><h4>End</h4></th>
						<th width="8%"><h4>Room</h4></th>
					</tr>
				</thead>
				<tbody>';
$lecunits = 0;
$labunits = 0;
$tunits = 0;

foreach ($year1 as $row) {
	$lecunits = $lecunits + $row['lec_units'];
	$labunits = $labunits + $row['lab_units'];
	$tunits = $tunits + $row['units'];

	$content .= '<tr>
					<th width="8%">'.$row['subject_code'].'</th>
					<td width="25%">'.$row['descriptive_title'].'</td>
					<td width="6%">'.$row['year_level_name'].'</td>
					<td width="18%">'.ucfirst($row['firstname']).' '.ucfirst($row['lastname']).'</td>
					<td width="4%">'.$row['lec_units'].'</td>
					<td width="4%">'.$row['lab_units'].'</td>
					<td width="4%">'.$row['units'].'</td>
					<td width="4%">'.$row['hours_week'].'</td>
					<td width="5%">';
						foreach ($row['time_scheds'] as $row2) {
							$content .='<p>'.$row2['day_abb'].'</p>';
						}
					$content .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content .= '<p>'.date('h:i a', strtotime($row2['time_start'])).'</p>';
						}
					$content .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content .= '<p>'.date('h:i a', strtotime($row2['time_end'])).'</p>';
						}
					$content .='</td>
					<td width="8%">'.$row['room_name'].'</td>
				</tr>
				';
}


$content .= '	<tr>
					<th width="8%"></th>
					<td width="25%"></td>
					<td width="6%"></td>
					<td width="18%"><h4>Total Units</h4></td>
					<td width="4%"><h4>'.$lecunits.'</h4></td>
					<td width="4%"><h4>'.$labunits.'</h4></td>
					<td width="4%"><h4>'.$tunits.'</h4></td>
					<td width="4%"></td>
					<td width="5%"></td>
					<td width="7%"></td>
					<td width="7%"></td>
					<td width="8%"></td>
				</tr>
				</tbody>
			</table>';
				

$pdf->writeHTML($content);




$pdf->AddPage();

$content2 = '
			<style>				
				img{
					width:60px;
				}
				h1{
					text-align:center;
					font-size: 30px;
				}
				table th, td{
					border: 1px double #009933;
				}
			</style>
			
			<table cellpadding="5">
				<thead>

					<tr>
						<th width="8%"><h4>Subject Code</h4></th>
						<th width="25%"><h4>Descriptive Title</h4></th>
						<th width="6%"><h4>Year Level</h4></th>
						<th width="18%"><h4>Instructor</h4></th>
						<th width="4%"><h4>Lec</h4></th>
						<th width="4%"><h4>Lab</h4></th>
						<th width="4%"><h4>Units</h4></th>
						<th width="4%"><h4>Hrs/ Wk</h4></th>
						<th width="5%"><h4>Day</h4></th>
						<th width="7%"><h4>Start</h4></th>
						<th width="7%"><h4>End</h4></th>
						<th width="8%"><h4>Room</h4></th>
					</tr>
				</thead>
				<tbody>';
$lecunits = 0;
$labunits = 0;
$tunits = 0;

foreach ($year2 as $row) {
	$lecunits = $lecunits + $row['lec_units'];
	$labunits = $labunits + $row['lab_units'];
	$tunits = $tunits + $row['units'];

	$content2 .= '<tr>
					<th width="8%">'.$row['subject_code'].'</th>
					<td width="25%">'.$row['descriptive_title'].'</td>
					<td width="6%">'.$row['year_level_name'].'</td>
					<td width="18%">'.ucfirst($row['firstname']).' '.ucfirst($row['lastname']).'</td>
					<td width="4%">'.$row['lec_units'].'</td>
					<td width="4%">'.$row['lab_units'].'</td>
					<td width="4%">'.$row['units'].'</td>
					<td width="4%">'.$row['hours_week'].'</td>
					<td width="5%">';
						foreach ($row['time_scheds'] as $row2) {
							$content2 .='<p>'.$row2['day_abb'].'</p>';
						}
					$content2 .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content2 .= '<p>'.date('h:i a', strtotime($row2['time_start'])).'</p>';
						}
					$content2 .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content2 .= '<p>'.date('h:i a', strtotime($row2['time_end'])).'</p>';
						}
					$content2 .='</td>
					<td width="8%">'.$row['room_name'].'</td>
				</tr>
				';
}



$content2 .= '	<tr>
					<th width="8%"></th>
					<td width="25%"></td>
					<td width="6%"></td>
					<td width="18%"><h4>Total Units</h4></td>
					<td width="4%"><h4>'.$lecunits.'</h4></td>
					<td width="4%"><h4>'.$labunits.'</h4></td>
					<td width="4%"><h4>'.$tunits.'</h4></td>
					<td width="4%"></td>
					<td width="5%"></td>
					<td width="7%"></td>
					<td width="7%"></td>
					<td width="8%"></td>
				</tr>
				</tbody>
			</table>';
				

$pdf->writeHTML($content2);





$pdf->AddPage();

$content3 = '
			<style>				
				img{
					width:60px;
				}
				h1{
					text-align:center;
					font-size: 30px;
				}
				table th, td{
					border: 1px double #009933;
				}
			</style>
			
			
			<table cellpadding="5">
				<thead>

					<tr>
						<th width="8%"><h4>Subject Code</h4></th>
						<th width="25%"><h4>Descriptive Title</h4></th>
						<th width="6%"><h4>Year Level</h4></th>
						<th width="18%"><h4>Instructor</h4></th>
						<th width="4%"><h4>Lec</h4></th>
						<th width="4%"><h4>Lab</h4></th>
						<th width="4%"><h4>Units</h4></th>
						<th width="4%"><h4>Hrs/ Wk</h4></th>
						<th width="5%"><h4>Day</h4></th>
						<th width="7%"><h4>Start</h4></th>
						<th width="7%"><h4>End</h4></th>
						<th width="8%"><h4>Room</h4></th>
					</tr>
				</thead>
				<tbody>';
$lecunits = 0;
$labunits = 0;
$tunits = 0;

foreach ($year3 as $row) {
	$lecunits = $lecunits + $row['lec_units'];
	$labunits = $labunits + $row['lab_units'];
	$tunits = $tunits + $row['units'];

	$content3 .= '<tr>
					<th width="8%">'.$row['subject_code'].'</th>
					<td width="25%">'.$row['descriptive_title'].'</td>
					<td width="6%">'.$row['year_level_name'].'</td>
					<td width="18%">'.ucfirst($row['firstname']).' '.ucfirst($row['lastname']).'</td>
					<td width="4%">'.$row['lec_units'].'</td>
					<td width="4%">'.$row['lab_units'].'</td>
					<td width="4%">'.$row['units'].'</td>
					<td width="4%">'.$row['hours_week'].'</td>
					<td width="5%">';
						foreach ($row['time_scheds'] as $row2) {
							$content3 .='<p>'.$row2['day_abb'].'</p>';
						}
					$content3 .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content3 .= '<p>'.date('h:i a', strtotime($row2['time_start'])).'</p>';
						}
					$content3 .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content3 .= '<p>'.date('h:i a', strtotime($row2['time_end'])).'</p>';
						}
					$content3 .='</td>
					<td width="8%">'.$row['room_name'].'</td>
				</tr>
				';
}



$content3 .= '	<tr>
					<th width="8%"></th>
					<td width="25%"></td>
					<td width="6%"></td>
					<td width="18%"><h4>Total Units</h4></td>
					<td width="4%"><h4>'.$lecunits.'</h4></td>
					<td width="4%"><h4>'.$labunits.'</h4></td>
					<td width="4%"><h4>'.$tunits.'</h4></td>
					<td width="4%"></td>
					<td width="5%"></td>
					<td width="7%"></td>
					<td width="7%"></td>
					<td width="8%"></td>
				</tr>
				</tbody>
			</table>';
				

$pdf->writeHTML($content3);






$pdf->AddPage();

$content4 = '
			<style>				
				img{
					width:60px;
				}
				h1{
					text-align:center;
					font-size: 30px;
				}
				table th, td{
					border: 1px double #009933;
				}
			</style>
			
			<table cellpadding="5">
				<thead>

					<tr>
						<th width="8%"><h4>Subject Code</h4></th>
						<th width="25%"><h4>Descriptive Title</h4></th>
						<th width="6%"><h4>Year Level</h4></th>
						<th width="18%"><h4>Instructor</h4></th>
						<th width="4%"><h4>Lec</h4></th>
						<th width="4%"><h4>Lab</h4></th>
						<th width="4%"><h4>Units</h4></th>
						<th width="4%"><h4>Hrs/ Wk</h4></th>
						<th width="5%"><h4>Day</h4></th>
						<th width="7%"><h4>Start</h4></th>
						<th width="7%"><h4>End</h4></th>
						<th width="8%"><h4>Room</h4></th>
					</tr>
				</thead>
				<tbody>';

$lecunits = 0;
$labunits = 0;
$tunits = 0;

foreach ($year4 as $row) {
	$lecunits = $lecunits + $row['lec_units'];
	$labunits = $labunits + $row['lab_units'];
	$tunits = $tunits + $row['units'];

	$content4 .= '<tr>
					<th width="8%">'.$row['subject_code'].'</th>
					<td width="25%">'.$row['descriptive_title'].'</td>
					<td width="6%">'.$row['year_level_name'].'</td>
					<td width="18%">'.ucfirst($row['firstname']).' '.ucfirst($row['lastname']).'</td>
					<td width="4%">'.$row['lec_units'].'</td>
					<td width="4%">'.$row['lab_units'].'</td>
					<td width="4%">'.$row['units'].'</td>
					<td width="4%">'.$row['hours_week'].'</td>
					<td width="5%">';
						foreach ($row['time_scheds'] as $row2) {
							$content4 .='<p>'.$row2['day_abb'].'</p>';
						}
					$content4 .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content4 .= '<p>'.date('h:i a', strtotime($row2['time_start'])).'</p>';
						}
					$content4 .='</td>
					<td width="7%">';
						foreach ($row['time_scheds'] as $row2) {
							$content4 .= '<p>'.date('h:i a', strtotime($row2['time_end'])).'</p>';
						}
					$content4 .='</td>
					<td width="8%">'.$row['room_name'].'</td>
				</tr>
				';
}



$content4 .= '	<tr>
					<th width="8%"></th>
					<td width="25%"></td>
					<td width="6%"></td>
					<td width="18%"><h4>Total Units</h4></td>
					<td width="4%"><h4>'.$lecunits.'</h4></td>
					<td width="4%"><h4>'.$labunits.'</h4></td>
					<td width="4%"><h4>'.$tunits.'</h4></td>
					<td width="4%"></td>
					<td width="5%"></td>
					<td width="7%"></td>
					<td width="7%"></td>
					<td width="8%"></td>
				</tr>
				</tbody>
			</table>';
				

$pdf->writeHTML($content4);

$pdf->Output('schedule.pdf', 'I');
?>

