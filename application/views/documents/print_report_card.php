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
		$this->MultiCell(0, 8, "", 0, 'L', 0, 1, '', '', true);
		$this->MultiCell(35, 5, "", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(35, 5, "_________________", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(35, 5, "", 0, 'L', 0, 1, '', '', true);
		$this->MultiCell(42, 5, "", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(46, 5, "Registrar", 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(46, 5, "", 0, 'L', 0, 1, '', '', true);

		$this->MultiCell(0, 5, "", 0, 'L', 0, 1, '', '', true);

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
$pdf->SetMargins(10, 35, 10);
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
				    	font-size:13px;
				    }
				    table{
				    	font-size:10px;
				    	color:black;
				    }

				</style>
				<h5>Student'."'".'s Report Card</h5>
				<p>
					Sem./Summer <u>  '.$report_card['sem_name'].' - SY'.$report_card['school_year'].'  </u> Course & Year <u>  '.$report_card['course_abb'].' - '.$report_card['year_level_abb'].'  <br></u>
					Full Name <u>  '.$report_card['lastname'].', '.$report_card['firstname'].' '.$report_card['middlename'].'  </u>
					Gender <u>  '.$report_card['gender'].' </u><br>
					Permanent Address <u>  '.$report_card['address'].'  </u>
				</p>
				';

$left_column .= '
				<table border="1" cellpadding="2">
					<tbody>
						<tr>
							<th rowspan="2" colspan="2">Subject Code</th>
							<th style="text-align:center;" colspan="5">Grades</th>
							
						</tr>
						<tr>
							<th>Prelim</th>
							<th>Midterm</th>
							<th>Semi-Finals</th>
							<th>Finals</th>
							<th>Equivalent</th>
						</tr>
						';
				foreach ($enrolled_subjects as $row) {
						if ($row['prelim'] == 0) {
							$row['prelim'] = '';
						} 
						if ($row['midterm_cumu'] == 0) {
							$row['midterm_cumu'] = '';
						}
						if ($row['semi_finals_cumu'] == 0) {
							$row['semi_finals_cumu'] = '';
						}
						if ($row['finals_cumu'] == 0) {
							$row['finals_cumu'] = '';
						}
						if ($row['equivalent_grade'] == 0) {
							$row['equivalent_grade'] = '';
						}
							$left_column .= '
							<tr>
								<td colspan="2">'.$row['subject_code'].'</td>
								<td>'.$row['prelim'].'</td>
								<td>'.$row['midterm_cumu'].'</td>
								<td>'.$row['semi_finals_cumu'].'</td>
								<td>'.$row['finals_cumu'].'</td>
								<td>'.$row['equivalent_grade'].'</td>
							</tr>';
						}		

$left_column .= 	'</tbody>
				</table>
				<span>Total Units: <u>'.$report_card['total_units'].'</u></span>
				';

$right_column = '
				<style>
					*{
						color:black;
						font-size: 11px;
					}
				</style>
				<p>Grading System:</p>
				<table cellpadding="2">
					<tbody>
						<tr>
							<td>1.0   -</td>
							<td>95-100</td>
							<td>2.1   -</td>
							<td>84</td>
						</tr>
						<tr>
							<td>1.1   -</td>
							<td>94</td>
							<td>2.2   -</td>
							<td>83</td>
						</tr>
						<tr>
							<td>1.2   -</td>
							<td>93</td>
							<td>2.3   -</td>
							<td>82</td>
						</tr>
						<tr>
							<td>1.3   -</td>
							<td>92</td>
							<td>2.4   -</td>
							<td>81</td>
						</tr>
						<tr>
							<td>1.4   -</td>
							<td>91</td>
							<td>2.5   -</td>
							<td>80</td>
						</tr>
						<tr>
							<td>1.5   -</td>
							<td>90</td>
							<td>2.6   -</td>
							<td>79</td>
						</tr>
						<tr>
							<td>1.6   -</td>
							<td>89</td>
							<td>2.7   -</td>
							<td>78</td>
						</tr>
						<tr>
							<td>1.7   -</td>
							<td>88</td>
							<td>2.8   -</td>
							<td>77</td>
						</tr>
						<tr>
							<td>1.8   -</td>
							<td>87</td>
							<td>2.9   -</td>
							<td>76</td>
						</tr>
						<tr>
							<td>1.9   -</td>
							<td>86</td>
							<td>3.0   -</td>
							<td>75</td>
						</tr>
						<tr>
							<td>2.0   -</td>
							<td>85</td>
							<td>5.0   -</td>
							<td>FAILURE</td>
						</tr>
						<tr>
							<td>DR    -</td>
							<td>DROPPED</td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td>W     -</td>
							<td>WITHDRAW</td>
							<td></td>
							<td></td>
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
$pdf->writeHTMLCell(120, '', '', $y, $left_column, 0, 0, 1, true, 'J', true);

// set color for background

$pdf->writeHTMLCell(80, '', '', '', $right_column, 0, 0, 1, true, 'J', true);
// ---------------------------------------------------------

// set font

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Request for Quotation 00', 'I');

//============================================================+
// END OF FILE
//============================================================+
