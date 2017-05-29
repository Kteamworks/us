<?php
require_once("../../globals.php");
$alcohol=  $_POST["alcohol"];
$cmspayout = $_POST['cmspayout'];
$drgdescription = $_POST['drgdescription'];
$tobacco = $_POST['tobacco'];
$obesity = $_POST['obesity'];
$dementia = $_POST['dementia'];
$vision_impairment = $_POST['vision_impairment'];

// mysql_select_db('mhat', $con);
$qry = 'UPDATE code_types SET alcohol="'.$alcohol.'", cmspayout="'.$cmspayout.'", drgdescription="'.$drgdescription.'" , tobacco="'.$tobacco.'" , obesity="'.$obesity.'" , dementia="'.$dementia.'" , vision_impairment="'.$vision_impairment.'"  where ct_key="DRG470"';
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
