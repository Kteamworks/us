<?php
include_once("../../globals.php");
include_once("$srcdir/api.inc");
include_once("$srcdir/forms.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");
//require_once("$srcdir/erx_javascript.inc.php");

// when the Cancel button is pressed, where do we go?
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';

if ($_POST['confirm']) {
       // set the deleted flag of the indicated form
   sqlStatement("update list_options set is_default=0 where list_id='".$_POST['admit_to_ward']."' and option_id='".$_POST['admit_to_bed']."'");
    $admit_to_ward=$_POST['admit_to_ward'];	
   $adm_new_ward=$_POST['adm_new_ward'];
	$status='admit';
  	$e=$GLOBALS['encounter'];
	sqlStatement("update billing set admitted=0 where encounter='".$e."' and code like '".$_POST['admit_to_ward']."%'");
	sqlInsert("INSERT into t_form_transfer SET
	pid={$_SESSION["pid"]},
   groupname = '" . $_SESSION["authProvider"] . "',
    user = '" . $_SESSION["authUser"] . "',
   authorized = $userauthorized, 
   activity=1, 
   date = NOW(),
   transfer_date=NOW(),
   provider          = '" . add_escape_custom($_POST["provider"]) . "',
  client_name          = '" . add_escape_custom($_POST["client_name"]) . "',
  admitted_to_ward          = '" . add_escape_custom($_POST["admit_to_ward"]) . "',
  admitted_to_bed          = '" . add_escape_custom($_POST["admit_to_bed"]) . "',
  transferred_to_ward          = '" . add_escape_custom($_POST["adm_new_ward"]) . "',
  transferred_to_bed          = '" . add_escape_custom($_POST["adm_new_bed"]) . "',
   encounter          = '" . add_escape_custom($_POST["encounter"]) . "'");
   
    sqlStatement("UPDATE t_form_admit SET status='admit',admit_to_ward='".$_POST['adm_new_ward']."',admit_to_bed='".$_POST['adm_new_bed']."' where encounter='".$e."'");
    sqlStatement("update list_options set is_default=1 where list_id='".$_POST['adm_new_ward']."' and option_id='".$_POST['adm_new_bed']."'");
   $patient=getPatientData($pid, "rateplan");
   $rate=$patient['rateplan'];
   $codetype=9;
    if($rate=="TPAInsurance")
	{
	$row1=sqlStatement("SELECT a.service_id service_id,a.code_type code_type,a.code,a.code_text,b.pr_price from codes a,prices b  where a.id=b.pr_id and a.code_type='".$codetype."' and b.pr_level='Insurance' and a.code like '".$_POST['adm_new_ward']."%' and b.pr_price!=0");
	while($row2= sqlFetchArray($row1))
	{
	$code=$row2['code'];
	$codetext=$row2['code_text'];
	$service_id=$row2['service_id'];
	$servicegrp_id=$row2['code_type'];
  	$codetype="Ward Charges";
  	$billed=0;
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="Insurance";
	 sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
	  "user = '" . $_SESSION["authUser"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
	  "service_id = '" . add_escape_custom($service_id) . "', " .
      "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
      "code = '" . add_escape_custom($code) . "', " .
      "code_text = '" . add_escape_custom($codetext) . "', " .
      "units = '" . add_escape_custom($units) . "', " .
      "billed = '" . add_escape_custom($billed) . "', " .
      "fee = '" . add_escape_custom($fee) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "modifier = '" . add_escape_custom($modif) . "', " .
	  "authorized = '" . add_escape_custom($authrzd) . "', " .
	  "activity = '" . add_escape_custom($act) . "', " .
	  "groupname = '" . add_escape_custom($grpn) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'");
	  sqlQuery("Update billing_main_copy set total_charges=total_charges + ? where encounter=?",array($fee,$encounter));   
	}
	}
	else{
    $row1=sqlStatement("SELECT a.code_type code_type,a.service_id service_id,a.code,a.code_text,b.pr_price from codes a,prices b  where a.id=b.pr_id and a.code_type='".$codetype."' and b.pr_level='standard' and a.code like '".$_POST['adm_new_ward']."%' and b.pr_price!=0");
	
	while($row2= sqlFetchArray($row1))
	{
	$code=$row2['code'];
	$codetext=$row2['code_text'];
	$service_id=$row2['service_id'];
	$servicegrp_id=$row2['code_type'];
  	$codetype="Ward Charges";
  	$billed=0;
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="Default";
	 sqlInsert("INSERT INTO billing SET " .
      "date = NOW(), " .
	  "user = '" . $_SESSION["authUser"] . "',".
      "bill_date = '" . add_escape_custom($onset_date) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
	  "service_id = '" . add_escape_custom($service_id) . "', " .
      "servicegrp_id = '" . add_escape_custom($servicegrp_id) . "', " .
      "code = '" . add_escape_custom($code) . "', " .
      "code_text = '" . add_escape_custom($codetext) . "', " .
      "units = '" . add_escape_custom($units) . "', " .
      "billed = '" . add_escape_custom($billed) . "', " .
      "fee = '" . add_escape_custom($fee) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "modifier = '" . add_escape_custom($modif) . "', " .
	  "authorized = '" . add_escape_custom($authrzd) . "', " .
	  "activity = '" . add_escape_custom($act) . "', " .
	  "groupname = '" . add_escape_custom($grpn) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'");
	  sqlQuery("Update billing_main_copy set total_charges=total_charges + ? where encounter=?",array($fee,$encounter));   
	}
   }
  
	// redirect back to the encounter
    $address = "{$GLOBALS['rootdir']}/patient_file/encounter/$returnurl";
    echo "\n<script language='Javascript'>top.restoreSession();window.location='$address';</script>\n";
    exit;
  
}
?>
<html>

<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.css" />
<link rel="stylesheet" href="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.treeview-1.4.1/jquery.treeview.css" />
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-1.7.2.min.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-v3.js"></script>
<script src="<?php echo $GLOBALS['webroot'] ?>/library/js/jAlert-master/src/jAlert-functions.js"> //optional!!</script>
<!-- supporting javascript code -->
<!--<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>-->

</head>

<body class="body_top">

<span class="title">Transfer</span>

<form method="post" action="<?php echo $rootdir;?>/patient_file/encounter/transfer_form.php" name="my_form" id="my_form">
<?php
// output each GET variable as a hidden form input
foreach ($_GET as $key => $value) {
    echo '<input type="hidden" id="'.$key.'" name="'.$key.'" value="'.$value.'"/>'."\n";
}
?>
<input type="hidden" id="confirm" name="confirm" value="1"/>
 <?php if (is_numeric($pid)) {
    
    $result = getAdmitData($pid, "*");
  }
   $client_name=($result['client_name']);
   $admit_to_ward=($result['admit_to_ward']);
    $admit_to_bed=($result['admit_to_bed']);
	
   ?>
   <input type="hidden" name="encounter" value="<?php echo $GLOBALS['encounter']?>"/>
    <input type="hidden" id="client_name" name="client_name" value='<?php echo attr($client_name);?>'>  
 <input type="hidden" id="admit_to_ward" name="admit_to_ward" value='<?php echo attr($admit_to_ward);?>'>  
<input type="hidden" id="admit_to_bed" name="admit_to_bed" value='<?php echo attr($admit_to_bed);?>'>


<p>
You are about to Transfer a Patient From '<?php echo attr($admit_to_ward);?>': '<?php echo attr($admit_to_bed);?>' To <a href='bedtrans.php'>Find Beds</a>
<tr>
	<td> <b><?php echo xlt('Selected'); ?>:</b></td>
	<input type=hidden name ="adm_to" value=<?php echo $_POST['adm_to']; ?> > </input>
	<?
	$bid23=$_POST['adm_to'];
	$quer23="select * FROM list_options where option_id='".$bid23."'";

	$res23 = sqlStatement($quer23);
	$result23 = sqlFetchArray($res23);
	$adm_new_ward=$result23['list_id'];
	$adm_new_bed=$result23['option_id'];
	echo "<td>"."<input type=\"text\" name=\"adm_new_ward\" value=\"$adm_new_ward\" readonly>"."</input></td>";
	echo "<td>"."<input type=\"text\" name=\"adm_new_bed\" value=\"$adm_new_bed\" readonly></input>"."</td>"; 
	
	?>
	</tr>
	
</p>
<input type="button" id="confirmbtn" name="confirmbtn" value="Yes, Transfer this Patient">
<input type="button" id="cancel" name="cancel" value="Cancel">
</form>

</body>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#confirmbtn").click(function() {
	var x = document.forms["my_form"]["adm_new_ward"].value;
	if(x == null || x == "")
	{
	return validateForm()
	}
	else
	{ 
	return ConfirmTransfer(); 
	}
	});
    $("#cancel").click(function() { location.href='<?php echo "$rootdir/patient_file/encounter/$returnurl";?>'; });
});

function ConfirmTransfer() {
		$.jAlert({'type': 'confirm','confirmQuestion':'Are you sure you wish to Transfer this Patient? ', 'onConfirm': function(){
        top.restoreSession();
        $("#my_form").submit();
        return true;   
  }, 'onDeny': function(){
    return false;    
  } });
   /* if (confirm("This action cannot be undone. Are you sure you wish to Transfer this Patient?")) {
        top.restoreSession();
        $("#my_form").submit();
        return true;
    }
    return false;*/
}
function validateForm() {
    var x = document.forms["my_form"]["adm_new_ward"].value;
	 var y = document.forms["my_form"]["adm_new_bed"].value;
    if (x == null || x == "") {
        alert("Please select the Ward");
        return false;
    }
	 if (y == null || y == "") {
        alert("Please select the Bed");
        return false;
    }
}
</script>

</html>



