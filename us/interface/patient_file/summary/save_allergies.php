<?php
require_once("../../globals.php");

$allergy = $_POST['allergies'];

$qry = 'INSERT into lists (pid,date,type,title,activity) values ("' . $pid . '","'. date('d-m-y').'","allergy","'. $allergy .'","1")';
    $qry_res = sqlStatement($qry);
	
    if ($qry_res) {

         $result = array('success' => 'true');
    } else {
		$dberr = mysql_error();
         $result = array('success' => 'false', 'message' => 'Something happened');
		echo $dberr;
    }
	
 echo json_encode($result);

	?>
