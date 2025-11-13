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
	protected $prospectus = array();

	public function get_prospectus(){
		return $this->prospectus;
	}

	public function set_prospectus($prospectus){
		$this->prospectus = $prospectus;
	}

	public function Header() {
		$prospectus = $this->get_prospectus();

		$this->SetFont('times', 'NB', 13);
		// Title
		$this->Image(base_url().'images/dcit_logo.png', 13, 8, 20, 18, 'PNG', 'http://www.ntc.org', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 10, 'DIPOLOG CITY INSTITUTE OF TECHNOLOGY', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'N', 9);
		$this->Cell(0, 10, 'National Highway, Minaog, Dipolog City', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 9);
		$this->Cell(0, 10, $prospectus['course_name'], 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->Cell(0, 10, $prospectus['prospectus_name'], 0, 1, 'C', 0, '', 5, false, 'M', 'M');
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
$pdf->SetMargins(15, 32, 15);
$pdf->SetHeaderMargin(10);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->set_prospectus($prospectus);


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
foreach ($category as $row) {
	# code...

					if (count($row['subjects']) > 0) {
						$left_column .= '<h5>'.$row['category_name'].'</h5>';
					}
					
	$left_column .=	'
					<table border="1" cellpadding="3">
						<tbody>
							<tr>
								<td>Subject Code</td>
								<td colspan="4">Descriptive Title</td>
								<td>Lec Units</td>
								<td>Lab Units</td>
								<td>Units</td>
								<td>Hrs/ Wk</td>
							</tr>
							';
							if (count($row['subjects']) > 0) {
								foreach ($row['subjects'] as $row2) {
									$left_column .= '
									<tr>
										<td>'.$row2['subject_code'].'</td>
										<td colspan="4">'.$row2['descriptive_title'].'</td>
										<td>'.$row2['lec_units'].'</td>
										<td>'.$row2['lab_units'].'</td>
										<td>'.$row2['units'].'</td>
										<td>'.$row2['hours_week'].'</td>
									</tr>';
								}		
							}

	$left_column .= 	'
						</tbody>
					</table>
					';
}
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
