<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//============================================================+
// File name   : example_003.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 003 for TCPDF class
//               Custom Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Custom Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
	protected $date = '';
	protected $permit_definition = '';

	//Page header
	public function Header() {
		$this->SetFont('times', 'NB', 13);
		// Title
		$this->Image(base_url().'images/dcit_logo.png', 30, 5, 20, 18, 'PNG', 'http://www.ntc.org', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 10, 'DIPOLOG CITY INSTITUTE OF TECHNOLOGY', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'N', 9);
		$this->Cell(0, 10, 'National Highway, Minaog, Dipolog City', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 9);
		$this->Cell(0, 15, 'Tel. No. (065) 212-2978 / (065) 908-0092', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('times', 'BU', 10);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-22);
		// Set font
		// Page number
		$this->SetFont('helvetica', 'N', 9);
		$this->MultiCell(0, 8, "Approved by:", 0, 'L', 0, 1, '', '', true);
		$this->MultiCell(46, 5, "_________________", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(46, 5, "_________________", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(46, 5, "_________________", 0, 'L', 0, 1, '', '', true);
		$this->MultiCell(46, 5, "Dean/Dep't Head", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(46, 5, "Registrar", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(46, 5, "Acctg./Cashier", 0, 'L', 0, 1, '', '', true);

		$this->MultiCell(0, 5, "", 0, 'L', 0, 1, '', '', true);

	}

	public function set_bid_date($date){
		$this->date = $date;
	}

	public function get_bid_date(){
		return $this->date;
	}

}

// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, 'ORGANIZERL', true, 'UTF-8', false);

// set document information

// set default header data


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(10, 25, 10);
$pdf->SetHeaderMargin(7);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 10);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

$pdf->AddPage();

$left_column = '
				<style>
				    h1 {
				        color: navy;
				        font-family: times;
				        font-size: 24pt;
				        text-decoration: underline;
				    }
				    p {
				        color: #000;
				        font-family: helvetica;
				        font-size: 9pt;
				        line-height: 22px;
				    }
				    span{
				    	font-size:11px;
				    	color:black;
				    	line-height: 22px;
				    }
				    h5{
				    	font-family: times;
				    	color:black;
				    	text-align:center;
				    	line-height: 0;
				    }
				    table{
				    	font-size:10px;
				    	color:black;
				    }

				</style>
				<h5>Student'."'".'s Copy</h5>
				<p>
					Sem./Summer <u>  '.$account_sm['sem_name'].'  </u> Course & Year <u>  '.$account_sm['course_abb'].'  </u> Contact No. <u>  '.$account_sm['contact_number'].'  </u><br>
					Full Name <u>  '.$account_sm['lastname'].', '.$account_sm['firstname'].' '.$account_sm['middlename'].'  </u>
					Gender <u>  '.$account_sm['gender'].'  </u> Date <i> __________________ </i><br>
					Permanent Address <u>  '.$account_sm['address'].'  </u>
				</p>
				';

$left_column .= '
				<table border="1" cellpadding="2">
					<tbody>
						<tr>
							<th colspan="2">Subject Code</th>
							<th colspan="4">Descriptive Title</th>
							<th>Day</th>
							<th colspan="3">Time</th>
							<th>Unit</th>
							<th>Lec Unit</th>
							<th>Lab Unit</th>
						</tr>';
				foreach ($enrolled_subjects as $row) {
							$left_column .= '
							<tr>
								<td colspan="2">'.$row['subject_code'].'</td>
								<td colspan="4">'.$row['descriptive_title'].'</td>
								<td>';
									foreach ($row['time_scheds'] as $row2) {
										$left_column .= '<div>'.$row2['day_abb'].'</div>';
									}
								$left_column .= '</td>
								<td colspan="3">';
									foreach ($row['time_scheds'] as $row2) {
										$left_column .= '<div>'.date('h:ia',strtotime($row2['time_start'])).' - '.date('h:ia',strtotime($row2['time_end'])).'</div>';
									}
								$left_column .= '</td>
								<td>'.$row['units'].'</td>
								<td>'.$row['lec_units'].'</td>
								<td>'.$row['lab_units'].'</td>
							</tr>';
						}		

$left_column .= 	'</tbody>
				</table>
				<span>Total Units: <u>'.$account_sm['total_units'].'</u></span>
				';


$right_column = '
				<style>
			    h5{
			    	font-family: times;
			    	text-align:center;
			    }
			    table{
			    	font-size:11px;
			    }
			    table .under{
			    	border-bottom: 1px solid black;
			    }

				</style>
				<h5>STATEMENT OF ACCOUNT<br>S.Y. '.$account_sm['school_year'].'</h5>
				<table cellspacing="10">
					<tbody>
						<tr>
							<td colspan="2">Tuition Fee:</td>
							<td class="under">'.$account_sm['tuition_fee'].'</td>
						</tr>
						<tr>
							<td colspan="2">Miscellaneous Fee:</td>
							<td class="under">'.$account_sm['misc_fee'].'</td>
						</tr>
						<tr>
							<td colspan="2">Laboratory Fee:</td>
							<td class="under">'.$account_sm['lab_fee'].'</td>
						</tr>
						<tr>
							<td colspan="2">Others:</td>
							<td class="under"></td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td class="under"></td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td class="under"></td>
						</tr>
						<tr>
							<td colspan="2">Total Sem:</td>
							<td class="under">'.$account_sm['total_amount'].'</td>
						</tr>
						<tr>
							<td colspan="2">Less Down Payment:</td>
							<td class="under">'.$account_sm['down_payment'].'</td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td class="under"></td>
						</tr>
						<tr>
							<td colspan="2"></td>
							<td class="under"></td>
						</tr>
						<tr>
							<td colspan="2">Balance:</td>
							<td class="under">'.($account_sm['total_amount']-$account_sm['balance']).'</td>
						</tr>
					</tbody>
				</table>
				';



// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// get current vertical position
$y = $pdf->getY();

// set color for background
$pdf->SetFillColor(255, 255, 255);

// set color for text
$pdf->SetTextColor(0, 63, 127);

// write the first column
$pdf->writeHTMLCell(125, '', '', $y, $left_column, 0, 0, 1, true, 'J', true);

// set color for background


// set color for text
$pdf->SetTextColor(0, 0, 0);

// write the second column
$pdf->writeHTMLCell(70, '', '', '', $right_column, 0, 0, 1, true, 'J', true);


$pdf->AddPage();

$left_column = '
				<style>
				    h1 {
				        color: navy;
				        font-family: times;
				        font-size: 24pt;
				        text-decoration: underline;
				    }
				    p {
				        color: #000;
				        font-family: helvetica;
				        font-size: 9pt;
				        line-height: 22px;
				    }
				    span{
				    	font-size:11px;
				    	color:black;
				    	line-height: 5px;
				    }
				    h5{
				    	font-family: times;
				    	text-align:center;
				    	color:black;
				    	line-height:40px;
				    }
				    table{
				    	font-size:10px;
				    	color:black;
				    }

				</style>
				<h5>EXAMINATION PERMIT</h5>
				';

$left_column .= '
				<table border="1" cellpadding="4">
					<tbody>
						<tr>
							<th colspan="2">Subjects</th>
							<th>Prelim</th>
							<th>Midterm</th>
							<th>Semi-Final</th>
							<th>Final</th>
						</tr>';

						foreach ($enrolled_subjects as $row) {
							$left_column .= '
							<tr>
								<td colspan="2"><span>'.$row['subject_code'].'</span></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							';
						}

$left_column .= 	'
						<tr>
							<td colspan="2"><strong>Cashier Initial</strong></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="2"><strong>OR# & Date</strong></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
				';


$right_column = '
				<style>
			    h5{
			    	font-family: times;
			    	text-align:center;
			    }
			    table{
			    	font-size:11px;
			    }
			    table .under{
			    	border-bottom: 1px solid black;
			    }

				</style>
				<h5>SCHEDULE OF PAYMENTS</h5>
				<table cellpadding="4" cellspacing="20">
					<tbody>
						<tr>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>Prelim</td>
							<td class="under">'.$account_sm['per_grading'].'</td>
						</tr>
						<tr>
							<td>Midterm</td>
							<td class="under">'.$account_sm['per_grading'].'</td>
						</tr>
						<tr>
							<td>Semi-Final</td>
							<td class="under">'.$account_sm['per_grading'].'</td>
						</tr>
						<tr>
							<td>Final</td>
							<td class="under">'.$account_sm['per_grading'].'</td>
						</tr>
						<tr>
							<td>Total</td>
							<td class="under">'.($account_sm['per_grading']*4).'</td>
						</tr>
					</tbody>
				</table>
				';



// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// get current vertical position
$y = $pdf->getY();

// set color for background
$pdf->SetFillColor(255, 255, 255);

// set color for text
$pdf->SetTextColor(0, 63, 127);

// write the first column
$pdf->writeHTMLCell(125, '', '', $y, $left_column, 0, 0, 1, true, 'J', true);

// set color for background


// set color for text
$pdf->SetTextColor(0, 0, 0);

// write the second column
$pdf->writeHTMLCell(70, '', '', '', $right_column, 0, 0, 1, true, 'J', true);
// output the HTML content


// ---------------------------------------------------------

// set font

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Request for Quotation 00', 'I');

//============================================================+
// END OF FILE
//============================================================+
