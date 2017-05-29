<?php
// Copyright (C) 2010-2015 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This is an inventory transactions list.

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/sql.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/classes/postmaster.php"); 
require_once("tcpdf_include.php");

$query = "SELECT drug,quantity FROM prescriptions WHERE date_added >= NOW() - INTERVAL 1 DAY";
$res = sqlStatement($query);
$prescriptions = array();
  while ($row = sqlFetchArray($res)) {
	  $prescriptions[] = $row;
  }
  foreach($prescriptions as $prescription) {
	 //Comment these later. This is just for testing
	 $prescription['drug']="Crocin 5 mg";
	 $prescription['quantity']=10;
    $message .='<table><tr><th>Drug:</th><td>'.$prescription['drug'].'</td></tr><tr><th>Quantity:</th><td>'.$prescription['quantity'].'</td></tr></table></div>';
  }
    $mail = new MyMailer();
	
    $email_subject=xl('Test Prescriptions');
    $email_sender="kavaiidev01@gmail.com";
    $mail->AddReplyTo($email_sender, $email_sender);
    $mail->SetFrom($email_sender, $email_sender);
	//there has to be a whre clause where facilityid=???
	$querye = "SELECT email FROM facility";
	$res_em = sqlStatement($querye);
	$rows = array();
	while($row1 = sqlFetchArray($res_em)) {
    $rows[] = $row1;
	}

  foreach ($rows as $eid) {

    $mail->AddAddress($email_sender, 'MHAT');
  }
    // Code for creating PDF
	
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false, true);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Dr.Manoj');
	$pdf->SetTitle('Prescriptions');
	$pdf->SetSubject('Email Prescriptions');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set default header data
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 065', PDF_HEADER_STRING);

	// set header and footer fonts
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
	}

	// ---------------------------------------------------------

	// set default font subsetting mode
	$pdf->setFontSubsetting(true);

	// Set font
	$pdf->SetFont('helvetica', '', 14, '', true);

	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();
	$message="Atleast this should come";
    $html = $message;

// Print text using writeHTMLCell()
	$pdf->writeHTMLCell(0, 0, '', '', $message, 0, 1, 0, true, '', true);
    $pdf->Output($_SERVER['DOCUMENT_ROOT'] . '/output2.pdf', 'F');
	
	//
    $mail->Subject = $email_subject;
    $mail->MsgHTML("<html><body><div class='wrapper'>".$message."</div></body></html>");
    $mail->IsHTML(true);
    $mail->AltBody = $message;
	$mail->AddAttachment($_SERVER['DOCUMENT_ROOT']."/output2.pdf");			    
    if ($mail->Send()) {
        return true;
    } else {
        $email_status = $mail->ErrorInfo;
        error_log("EMAIL ERROR: ".$email_status,0);
        return false;
    }
	
	?>