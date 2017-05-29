<?php
require_once("../../globals.php");

$diagnosis_title = $_POST['diagnosis_title'];
$diagnosis_code = $_POST['diagnosis_code'];
if(isset($_GET['id'])) {
$qry = 'UPDATE lists SET title="'.$diagnosis_title.'",diagnosis="'.$diagnosis_code.'" where (id ="' . $_GET['id'] . '")';
    $qry_res = sqlStatement($qry);
	
    if ($qry_res) {

         $result = array('success' => 'true');
    } else {
		$dberr = mysql_error();
         $result = array('success' => 'false', 'message' => 'Something happened');
		echo $result;
    }
	
 echo json_encode($result);
}
else {
$qry = 'INSERT into lists (pid,date,type,title,diagnosis,activity) values ("' . $pid . '","'. date('d-m-y').'","medical_problem","'. $diagnosis_title .'","'. $diagnosis_code .'","1")';
    $qry_res = sqlStatement($qry);
	
    if ($qry_res) {

         $result = array('success' => 'true');
    } else {
		$dberr = mysql_error();
         $result = array('success' => 'false', 'message' => 'Something happened');
		echo $result;
    }
	
 echo json_encode($result);
}
	
	?>
