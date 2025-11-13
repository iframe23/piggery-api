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
	protected $receipt_id = '';
	protected $name = '';
	protected $date = '';

	public function get_receipt_id(){
		return $this->receipt_id;
	}

	public function set_receipt_id($receipt_id){
		$this->receipt_id = $receipt_id;
	}

	public function get_name(){
		return $this->name;
	}

	public function set_name($name){
		$this->name = $name;
	}

	public function get_date(){
		return $this->date;
	}

	public function set_date($date){
		$this->date = $date;
	}


	//Page header
	public function Header() {
		$this->SetFont('helvetica', 'N', 10);
		// Title
		$this->Image(site_url('images/dcit logo.png'), 25, 10, 25, 25, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
		$this->Cell(0, 8, 'DIPOLOG CITY INSTITUTE OF TECHNOLOGY', 0, 1, 'C', 0, '', 5, false, 'M', 'M');
		$this->SetFont('helvetica', 'B', 7);
		$this->Cell(0, 13, 'Minaog Highway, Dipolog City, Z. N.', 0, 1, 'C', 0, '', 5, false, 'M', 'M');

		$this->MultiCell(35, 0, date('M. j, Y ', strtotime($this->get_date())), 0, 'L', 0, 0, '', '', true);
		$this->MultiCell(35, 0, 'No. 0000'.$this->get_receipt_id(), 0, 'R', 0, 0, '', '', true);
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

	public function set_bid_date($date){
		$this->date = $date;
	}

	public function get_bid_date(){
		return $this->date;
	}

	public function set_permit_definition($permit_definition){
		$this->permit_definition = $permit_definition;
	}

	public function get_permit_definition(){
		return $this->permit_definition;
	}

	public function set_date_added($date){
		$this->date_added = $date;
	}

	public function get_date_added(){
		return $this->date_added;
	}

	public function set_date_expiry($date){
		$this->date_expiry = $date;
	}

	public function get_date_expiry(){
		return $this->date_expiry;
	}

	public function set_business_name($name){
		$this->business_name = $name;
	}

	public function get_business_name(){
		return $this->business_name;
	}

	public function set_or_number($number){
		$this->or_number = $number;
	}

	public function get_or_number(){
		return $this->or_number;
	}

	public function set_or_date($date){
		$this->or_date = $date;
	}

	public function get_or_date(){
		return $this->or_date;
	}

	public function set_amount($amount){
		$this->amount = $amount;
	}

	public function get_amount(){
		return $this->amount;
	}

	public function set_cashier($cashier){
		$this->cashier = $cashier;
	}

	public function get_cashier(){
		return $this->cashier;
	}

	public function set_permit_type($type){
		$this->permit_type = $type;
	}

	public function get_permit_type(){
		return $this->permit_type;
	}
}

// // create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, 'B8', true, 'UTF-8', false);

// set document information
$pdf->SetTitle('Permit No. - ');
$pdf->SetSubject('Permit');
$pdf->SetKeywords('Permit');


// set default header data


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(7, 1, 7);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(5);


// set margins

// set auto page breaks

// // set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->set_receipt_id($receipt->receipt_id);
$pdf->set_name($receipt->firstname.' '.$receipt->middlename.' '.$receipt->lastname);
$pdf->set_date($receipt->receipt_date);
// $pdf->set_date_expiry($permit->date_expiry);
// $pdf->set_or_number($permit->or_number);
// $pdf->set_or_date($permit->or_date);
// $pdf->set_amount($permit->amount);
// $pdf->set_cashier($permit->cashier);
// $pdf->set_permit_type($permit->permit_type);



// // set some language-dependent strings (optional)
// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
// 	require_once(dirname(__FILE__).'/lang/eng.php');
// 	$pdf->setLanguageArray($l);
// }

$pdf->AddPage();

// $pdf->SetFont('helvetica', 'N', 10);
// $pdf->MultiCell(90, 20, '', 0, 'L', 0, 0, '', '', true);
// $pdf->MultiCell(30, 20, 'Permit No. :', 0, 'L', 0, 0, '', '', true);
$pdf->SetFont('helvetica', 'N', 7);
$pdf->MultiCell(25, 19, '', 0, 'L', 0, 1, '', '', true);
$pdf->MultiCell(5, 5, '', 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(20, 5, 'Received from', 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(0, 2, $receipt->firstname.' '.$receipt->lastname, 'B', 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 0, '', 0, 'L', 0, 1, '', '', true);

$pdf->MultiCell(18, 5, 'Amounting to', 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(0, 2, $receipt->receipt_amount, 'B', 'L', 0, 1, '', '', true);
$pdf->MultiCell(0, 0, '', 0, 'L', 0, 1, '', '', true);

$pdf->MultiCell(20, 2, 'In payment for ', 0, 'L', 0, 0, '', '', true);
$pdf->MultiCell(0, 2, $receipt->particulars, 'B', 'L', 0, 0, '', '', true);

// $pdf->SetFont('helvetica', 'N', 10);
// $pdf->MultiCell(42, 20, 'Business Name :', 0, 'L', 0, 0, '', '', true);
// $pdf->SetFont('helvetica', 'B', 10);
// $pdf->MultiCell(0, 3, $permit->business_name, 'B', 'C', 0, 1, '', '', true);
// $pdf->MultiCell(0, 5, '', 0, 'L', 0, 1, '', '', true);

// $pdf->SetFont('helvetica', 'N', 10);
// $pdf->MultiCell(42, 20, 'Business Address :', 0, 'L', 0, 0, '', '', true);
// $pdf->SetFont('helvetica', 'B', 10);
// $pdf->MultiCell(0, 3, $permit->business_address, 'B', 'C', 0, 1, '', '', true);
// $pdf->MultiCell(0, 5, '', 0, 'L', 0, 1, '', '', true);

// $pdf->SetFont('helvetica', 'N', 10);
// $pdf->MultiCell(42, 20, 'Telephone Number :', 0, 'L', 0, 0, '', '', true);
// $pdf->SetFont('helvetica', 'B', 10);
// $pdf->MultiCell(40, 3, $owner->contact_number, 'B', 'C', 0, 0, '', '', true);
// $pdf->MultiCell(10, 3, '', 0, 'C', 0, 0, '', '', true);
// $pdf->SetFont('helvetica', 'N', 10);
// $pdf->MultiCell(35, 20, 'Fax Number :', 0, 'L', 0, 0, '', '', true);
// $pdf->SetFont('helvetica', 'B', 10);
// $pdf->MultiCell(0, 3, $owner->contact_number, 'B', 'C', 0, 1, '', '', true);
// $pdf->MultiCell(0, 5, '', 0, 'L', 0, 1, '', '', true);





// ---------------------------------------------------------

// set font

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('Request for Quotation 00', 'I');

//============================================================+
// END OF FILE
//============================================================+
