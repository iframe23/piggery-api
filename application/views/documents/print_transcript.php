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
	protected $transcript = array();

	public function get_transcript(){
		return $this->transcript;
	}

	public function set_transcript($transcript){
		$this->transcript = $transcript;
	}

	public function Header() {
		$transcript = $this->get_transcript();

		$this->SetFont('times', 'NB', 13);
		// Title
		$this->Image(base_url().'images/dcit_logo.png', 13, 12, 20, 18, 'PNG', 'http://www.ntc.org', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 10, 'DIPOLOG CITY INSTITUTE OF TECHNOLOGY', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'N', 9);
		$this->Cell(0, 10, 'National Highway, Minaog, Dipolog City', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 9);
		$this->Cell(0, 15, 'Tel. No. (065) 212-2978 / (065) 908-0092', 'B', 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('times', 'B', 10);

		$this->MultiCell(55, 9, "", 0, 'C', 0, 0, '', '', true);
		$this->MultiCell(75, 7, 'OFFICE OF THE REGISTRAR', 1, 'C', 0, 0, '', '', true, 0, false, true, 7, 'M');
		$this->MultiCell(55, 12, "", 0, 'C', 0, 1, '', '', true);

		$this->SetFont('helvetica', 'N', 9);
		$this->MultiCell(67, 5, "OFFICIAL TRANSCRIPT OF RECORDS OF:", 0, 'L', 0, 0, '', '', true);
		$this->SetFont('helvetica', 'B', 9);
		$this->MultiCell(135, 5, $transcript['firstname'].' '.$transcript['middlename'].' '.$transcript['lastname'] , 0, 'L', 0, 1, '', '', true);

		$this->SetFont('helvetica', 'N', 8);
		$this->MultiCell(25, 5, "Address", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(135, 5, ':'.$transcript['address'] , 0, 'L', 0, 1, '', '', true);

		$this->MultiCell(25, 5, "Date of Birth", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, ':'.$transcript['firstname'].' '.$transcript['middlename'].' '.$transcript['lastname'] , 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(25, 5, "Place of Birth", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, ':Place' , 0, 'L', 0, 1, '', '', true);

		$this->MultiCell(0, 1, '' , 'T', 'L', 0, 1, '', '', true);

		$this->SetFont('helvetica', 'B', 8);
		$this->MultiCell(60, 5, "EDUCATION", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, 'NAME OF SCHOOL' , 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, 'PLACE' , 0, 'L', 0, 1, '', '', true);

		$this->SetFont('helvetica', 'N', 8);
		$this->MultiCell(60, 5, "Primary", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, ': '.$transcript['primary_school'] , 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, '' , 0, 'L', 0, 1, '', '', true);

		$this->MultiCell(60, 5, "Intermediate", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, ': '.$transcript['intermediate_school'] , 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, '' , 0, 'L', 0, 1, '', '', true);

		$this->MultiCell(60, 5, "High School", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, ': '.$transcript['secondary_school'] , 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(60, 5, '' , 0, 'L', 0, 1, '', '', true);

		$this->MultiCell(0, 1, '' , 'T', 'L', 0, 1, '', '', true);

		$this->MultiCell(20, 1, 'Course' , 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(80, 1, $transcript['course_name'] , 0, 'L', 0, 1, '', '', true);

		$txt = '
				<style>
					*{
						color:black;
						font-size: 11px;
						font-weight:bold;
					}
				</style>
				<table cellpadding="2" border="1">
					<tbody>
						<tr>
							<td>Subject Code</td>
							<td colspan="4">Descriptive Title</td>
							<td>Rating</td>
							<td>Re-Ex</td>
							<td>Credits</td>
						</tr>
					</tbody>
				</table>
				';
				// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

				// get current vertical position
				$y = $this->getY();

				// set color for background
				$this->SetFillColor(255, 255, 255);

				// set color for text
				$this->SetTextColor(0, 63, 127);

				// write the first column
				$this->writeHTMLCell(0, '', '', $y, $txt, 0, 0, 1, true, 'J', true);
	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-90);
		// Set font
		// Page number
		$this->SetFont('helvetica', 'N', 9);
		$this->MultiCell(0, 1, "", 'T', 'L', 0, 1, '', '', true);
		
		$txt = '
				<style>
					*{
						color:black;
						font-size: 10px;
					}
				</style>
				<p>Grading System</p>
				<table cellspacing="1">
					<tbody>
						<tr>
							<td>1.0</td>
							<td colspan="3">96-100 EXCELLENT</td>
						</tr>
						<tr>
							<td>1.25</td>
							<td colspan="3">94-95 VERY GOOD</td>
						</tr>
						<tr>
							<td>1.50</td>
							<td colspan="3">91-93 GOOD</td>
						</tr>
						<tr>
							<td>1.75</td>
							<td colspan="3">88-90 ABOVE AVERAGE</td>
						</tr>
						<tr>
							<td>2.0</td>
							<td colspan="3">85-87 AVERAGE</td>
						</tr>
						<tr>
							<td>2.25</td>
							<td colspan="3">82-84</td>
						</tr>
						<tr>
							<td>2.75</td>
							<td colspan="3">76-78</td>
						</tr>
						<tr>
							<td>3.0</td>
							<td colspan="3">75</td>
						</tr>
						<tr>
							<td>5.0</td>
							<td colspan="3">50-50 FAILURE</td>
						</tr>
						<tr>
							<td>4.0</td>
							<td colspan="3">60-74</td>
						</tr>
						<tr>
							<td>W</td>
							<td colspan="3">WITHDRAWN</td>
						</tr>
						<tr>
							<td>INC</td>
							<td colspan="3">INCOMPLETE</td>
						</tr>
						<tr>
							<td>DR</td>
							<td colspan="3">DROPPED</td>
						</tr>
						<tr>
							<td>CREDIT</td>
							<td colspan="3">One unit of credits is one hour lecture or recitation for the period of complete semester.</td>
						</tr>
					</tbody>
				</table>
				';
				// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)


		$txt2 = '
				<table>
				<style>
					*{
						color:black;
					}
					.a{
						font-size: 12px;
						font-weight: bold;
						font-style: underline;
					}
					.b{
						font-size:9px;
					}
				</style>
					<tbody>
						<tr>
							<td></td>
							<td rowspan="3" colspan="2">Prepared by:</td>
							<td rowspan="3" colspan="2">Checked by:</td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"></td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"></td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"><span class="a">Marichou B. Gonzales</span></td>
							<td colspan="2"><span class="a">Rodelin L. Abitona</span></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"><span class="b">Registrar Encoder</span></td>
							<td colspan="2"><span class="b">Registrar Clerk</span></td>
						</tr>

						<tr>
							<td></td>
							<td colspan="2"></td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"></td>
							<td colspan="2"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"><span class="a">Marichou B. Gonzales</span></td>
							<td colspan="2"><span class="a">Rodelin L. Abitona</span></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="2"><span class="b">Registrar Encoder</span></td>
							<td colspan="2"><span class="b">Registrar Clerk</span></td>
						</tr>
					</tbody>
				</table>
				';
				// get current vertical position
				$y = $this->getY();

				// set color for background
				$this->SetFillColor(255, 255, 255);

				// set color for text
				$this->SetTextColor(0, 63, 127);

				// write the first column
				$this->writeHTMLCell(80, '', '', $y, $txt, 0, 0, 0, true, 'J', true);

				$this->writeHTMLCell(105, '', '', '', $txt2, 0, 0, 1, true, 'J', true);

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
$pdf->SetMargins(15, 96, 15);
$pdf->SetHeaderMargin(15);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 90);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->set_transcript($transcript);


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
				    	font-size:13px;
				    }
				    table{
				    	font-size:10px;
				    	color:black;
				    	border: 1px solid black;
				    }
				    table td{
				    	border-left: 1px solid black;
				    }

				</style>
				';

$left_column .= '
				<table cellpadding="4">
					<tbody>
						';
				foreach ($credentials as $row) {
						if ($row['equivalent_grade'] == 0) {
							$row['equivalent_grade'] = '--';
						}
							$left_column .= '
							<tr>
								<td>'.$row['subject_code'].'</td>
								<td colspan="4">'.$row['descriptive_title'].'</td>
								<td>'.$row['equivalent_grade'].'</td>
								<td></td>
								<td>'.$row['units'].'</td>
							</tr>';
						}		

$note = '';

				if ($transcript['transcript_status'] == 'Graduated') {
					$note = 'Graduated from the course of '.$transcript['course_name'].' with an S.O. number '.$transcript['so_number'];
				} else {
					$note = 'Undergraduate Transcript of Records for the course of '.$transcript['course_name'];
				}

$left_column .= 	'
						<tr><td></td><td colspan="4"></td><td></td><td></td><td></td></tr>
						<tr><td></td><td colspan="4"></td><td></td><td></td><td></td></tr>
						<tr><td></td><td colspan="4"></td><td></td><td></td><td></td></tr>
						<tr>
							<td></td>
							<td colspan="4">
								'.$note.'
							</td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
						<tr><td></td><td colspan="4"></td><td></td><td></td><td></td></tr>
						<tr><td></td><td colspan="4"></td><td></td><td></td><td></td></tr>
						<tr><td></td><td colspan="4"></td><td></td><td></td><td></td></tr>
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
