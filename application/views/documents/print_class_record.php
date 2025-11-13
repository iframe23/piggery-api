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
	//Page header
	protected $teacher = '';

	public function get_teacher(){
		return $this->teacher;
	}

	public function set_teacher($teacher){
		$this->teacher = $teacher;
	}

	public function Header() {
		$teacher = $this->get_teacher();
		$this->SetFont('times', 'NB', 13);
		// Title
		$this->Image(base_url().'images/dcit_logo.png', 13, 6, 20, 18, 'PNG', 'http://www.ntc.org', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 10, 'DIPOLOG CITY INSTITUTE OF TECHNOLOGY', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'N', 9);
		$this->Cell(0, 30, 'National Highway, Minaog, Dipolog City', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 12);

		$this->Cell(0, 10, 'Class Record of '.$teacher['firstname'].' '.$teacher['lastname'].' for the subject '.$teacher['descriptive_title'].' ('.$teacher['subject_code'].')', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom

		// Set font
		// Page number


	}
}

// create new PDF document
$pdf = new MYPDF('P', PDF_UNIT, 'GOVERNMENTLEGAL', true, 'UTF-8', false);

// set document information

// set default header data


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(15, 40, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->set_teacher($teacher);


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
				    	font-size:13px;
				    }
				    table{
				    	font-size:11px;
				    	color:black;
				    }

				</style>
				';

					
$left_column .=	'
				<table border="1" cellpadding="4">
					<tbody>
						<tr>
							<td colspan="2"><h5>Student</h5></td>
							<td><h5>Prelim</h5></td>
							<td><h5>Midterm</h5></td>
							<td><h5>Semi-finals</h5></td>
							<td><h5>Finals</h5></td>
							<td><h5>Equivalent</h5></td>
						</tr>
						';
						if (count($students) > 0) {
							$i = 0;
							foreach ($students as $row) {
								$left_column .= '
								<tr>
									<td colspan="2"><strong>'.($i+1).'.</strong> '.$row['lastname'].', '.$row['firstname'].'</td>
									<td>'.$row['prelim'].'</td>
									<td>'.$row['midterm_cumu'].'</td>
									<td>'.$row['semi_finals_cumu'].'</td>
									<td>'.$row['finals_cumu'].'</td>
									<td>'.$row['equivalent_grade'].'</td>
								</tr>';
								$i++;
							}		
						} else {
							$left_column .= '
								<tr>
									<td style="text-align:center;" colspan="6">No students have enrolled this subject</td>
								</tr>';
						}

$left_column .= 	'
					</tbody>
				</table>
				';

// get current vertical position
$y = $pdf->getY();

// set color for background
$pdf->SetFillColor(255, 255, 255);

// set color for text
$pdf->SetTextColor(0, 63, 127);

// write the first column
$pdf->writeHTMLCell(0, '', '', $y, $left_column, 0, 0, 1, true, 'J', true);

// set color for background
// ---------------------------------------------------------

// set font

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Request for Quotation 00', 'I');

//============================================================+
// END OF FILE
//============================================================+
