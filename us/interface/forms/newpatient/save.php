<?php
/**
 * Encounter form save script.
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://opensource.org/licenses/gpl-license.php>;.
 *
 * @package OpenEMR
 * @author  Brady Miller <brady@sparmy.com>
 * @link    http://www.open-emr.org
 */

$fake_register_globals=false;
$sanitize_all_escapes=true;

require_once("../../globals.php");
require_once("$srcdir/forms.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/patient.inc");

$date             = (isset($_POST['form_date']))            ? $_POST['form_date'] : '';
$onset_date       = (isset($_POST['form_onset_date']))      ? $_POST['form_onset_date'] : '';
$sensitivity      = (isset($_POST['form_sensitivity']))     ? $_POST['form_sensitivity'] : '';
$pc_catid         = (isset($_POST['pc_catid']))             ? $_POST['pc_catid'] : '';
$facility_id      = (isset($_POST['facility_id']))          ? $_POST['facility_id'] : '';
$billing_facility = (isset($_POST['billing_facility']))     ? $_POST['billing_facility'] : '';
$reason           = (isset($_POST['reason']))               ? $_POST['reason'] : '';
$type             = (isset($_POST['type']))? $_POST['type'] : '';
$mode             = (isset($_POST['mode']))                 ? $_POST['mode'] : '';
$provider_id      = (isset($_POST['form_provider'])) ? $_POST['form_provider'] : '';
$referral_source  = (isset($_POST['form_referral_source'])) ? $_POST['form_referral_source'] : '';
$tpaid            = (isset($_POST['instpa'])) ? $_POST['instpa'] : '';
$package          = (isset($_POST['package'])) ? $_POST['package'] : '';
$temp          = (isset($_POST['temp'])) ? $_POST['temp'] : '';
$weight          = (isset($_POST['weight'])) ? $_POST['weight'] : '';
$height          = (isset($_POST['height'])) ? $_POST['height'] : '';
$tod          = (isset($_POST['tod'])) ? $_POST['tod'] : '';
$review_after          = (isset($_POST['review_after'])) ? $_POST['review_after'] : '';
if(!empty($referral_source)) {
$referal_check =  sqlStatement("select user_id FROM users WHERE user_id = ?", array($referral_source));
if(sqlNumRows($referal_check) == 0) {
	$referal_id = sqlQuery("INSERT INTO users ( username, password, authorized, info, source, title, fname, lname, mname,  federaltaxid, federaldrugid, upin, facility, see_auth, active, npi, taxonomy, specialty, organization, valedictory, assistant, billname, email, email_direct, url, street, streetb, city, state, zip, street2, streetb2, city2, state2, zip2, phone, phonew1, phonew2, phonecell, fax, notes, abook_type,newcrop_user_role ) VALUES ( 'DR. ". strtoupper($referral_source) ."', '', 0, '', NULL, 'Dr.', '" . add_escape_custom($referral_source) . "', '', '', '', '', '', '', 0, 1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'spe','erxdoctor' )");
	$referral_sour = sqlStatement("SELECT * FROM users ORDER BY id DESC LIMIT 1");
	while($ref_id = sqlFetchArray($referral_sour)) {
		$referral_source = $ref_id['user_id'];
	}
}
$referrer = sqlQuery("select username FROM users WHERE user_id = ?", array($referral_source));
	$referrer_name = $referrer['username'];
}
$facilityresult = sqlQuery("select name FROM facility WHERE id = ?", array($facility_id));
$facility = $facilityresult['name'];


if ($GLOBALS['concurrent_layout'])
  $normalurl = "patient_file/encounter/encounter_top.php";
else
  $normalurl = "$rootdir/patient_file/encounter/patient_encounter.php";

/*
if($_SESSION['authUser']!='Receptionist'){
	$normalurl = "forms/fee_sheet/new.php";
}
else{
*/

if($pc_catid==10)
{
	//$normalurl = "patient_file/front_payment.php";
	$normalurl = "forms/fee_sheet/new.php";
}
if($pc_catid==16)
{
	
	$normalurl = "patient_file/encounter/load_form.php?formname=procedure_order";

}
if($pc_catid==12)
{
	
	$normalurl = "patient_file/encounter/load_form.php?formname=admit";

}
//}
$nexturl = $normalurl;

if ($mode == 'new')
{
	$encounter = generate_id();
	sqlStatement("UPDATE patient_data SET visit_status='0', doctor='$provider_id' WHERE pid='$pid'");
    addForm($encounter, "New Patient Encounter",
    sqlInsert("INSERT INTO form_encounter SET " .
      "date = '" . add_escape_custom($date) . "', " .
      "onset_date = '" . add_escape_custom($onset_date) . "', " .
      "reason = '" . add_escape_custom($reason) . "', " .
      "facility = '" . add_escape_custom($facility) . "', " .
      "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
	   "type = '" . add_escape_custom($type) . "', " .
      "facility_id = '" . add_escape_custom($facility_id) . "', " .
      "billing_facility = '" . add_escape_custom($billing_facility) . "', " .
      "sensitivity = '" . add_escape_custom($sensitivity) . "', " .
      "referral_source = '" . add_escape_custom($referral_source) . "', " .
	        "referrer_name = '" . add_escape_custom($referrer_name) . "', " .
	  "type_of_delivery = '" . add_escape_custom($tod) . "', " .
	  "weight = '" . add_escape_custom($weight) . "', " .
	  "height = '" . add_escape_custom($height) . "', " .
	  "temp = '" . add_escape_custom($temp) . "', " .
	  "review_after = '" . add_escape_custom($review_after) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "tpa_id = '" . add_escape_custom($tpaid) . "', " .
	  "package = '" . add_escape_custom($package) . "', " .
      "provider_id = '" . add_escape_custom($provider_id) . "'"),
      "newpatient", $pid, $userauthorized, $date);
	
	
	$p=sqlQuery("select date from patient_data where pid='$pid'");
	sqlFetchArray($p);
	$regdate=$p['date'];
	sqlInsert("INSERT INTO billing_main_copy SET " .
      "regdate = '" . add_escape_custom($regdate) . "', " .
      "facility_id = '" . add_escape_custom($facility_id) . "', " . 
      "pid = '" . add_escape_custom($pid) . "', " .
	  "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', " .
	  "encounterdt = now() , ".
	  "tpa_id_pri = '" . add_escape_custom($tpaid) . "' ") ;     
	  
	
	//sqlStatement("update insurance_data set encounter=?,provider=? where pid=?",array($encounter,$tpaid,$pid));
    $row1=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price,username from codes a,prices b, users c where a.id=b.pr_id and a.code_text=c.username and b.pr_level='standard'and  c.id='".$provider_id."'");
	$today = date("Y-m-d H:i:s"); 
	$time= date("H:i:s");
	$day=sqlStatement("select dayname('$today') day");
	$days=sqlFetchArray($day);
	$dayy=$days['day'];
	
	
		if(!($pc_catid==5 || $pc_catid==12 || $pc_catid==16 || $pc_catid==17||$pc_catid==22))
	{
	$patient=getPatientData($pid, "rateplan");
    $rate=$patient['rateplan'];
	if($rate=="HosInsurance")
	{
    $inc1=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price,username from codes a,prices b, users c where a.id=b.pr_id and a.code_text=c.username and b.pr_level='HosInsurance' and  c.id='".$provider_id."'");
	$inc2=  sqlFetchArray($inc1);
	$servicegrpid=$inc2['code_type'];
	$serviceid=$inc2['service_id'];
  	$code=$inc2['code'];
	$codetext=$inc2['code_text'];
	$codetype="Doctor Charges";
  	$billed=0;
  	$units=1;
  	$fee=$inc2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="HosInsurance";
	$onset_date1=date('Y-m-d H:i:s');

	if($pc_catid==13)
		 {
			$ct="CASUALTY CONSULTATION (DAY)"; 	 
		    $cas5=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct."' and a.code_type=6 and b.pr_level='HosInsurance'");
			$cas6=sqlFetchArray($cas5);
			$codetype="Services"; 
			$servicegrpid=$cat6['code_type'];
	        $serviceid=$cat6['service_id'];
			$codetext="CASUALTY CONSULTATION (DAY)"; 
			$code=$codetext;
			$fee=$cas6['pr_price'];
		 }
		 if($pc_catid==15)
		 {
			$ct1="CASUALTY CONSULTATION (NIGHT)";
		    $cas7=sqlStatement("Select a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct1."' and a.code_type=6 and b.pr_level='HosInsurance'");
			$cas8=sqlFetchArray($cas7);
			$codetype="Services"; 
			$servicegrpid=$cat8['code_type'];
	        $serviceid=$cat8['service_id'];
			$codetext="CASUALTY CONSULTATION (NIGHT)"; 
			$code=$codetext;
			$fee=$cas8['pr_price'];
		 }
	}
	else
    {
		
	$row2=  sqlFetchArray($row1);
  	$code=$row2['code'];
	$codetext=$row2['code_text'];
	$codetype="Doctor Charges";
  	$billed=0;
	$servicegrpid=$row2['code_type'];
	$serviceid=$row2['service_id'];
  	$units=1;
  	$fee=$row2['pr_price'];
	$authrzd=1;
	$modif="";
	$act=1;
	$grpn="Default";
	$onset_date1=date('Y-m-d H:i:s');

	if($pc_catid==13)
		 {
			$ct="CASUALTY CONSULTATION (DAY)"; 	 
		    $cas1=sqlStatement("Select  a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct."' and a.code_type=6 and b.pr_level='standard'");
			$cas2=sqlFetchArray($cas1);
			$codetype="Services"; 
				$servicegrpid=$cas2['code_type'];
	          $serviceid=$cas2['service_id'];
			$codetext="CASUALTY CONSULTATION (DAY)"; 
			$code=$codetext;
			$fee=$cas2['pr_price'];
		 }
		 if($pc_catid==15)
		 {
			$ct1="CASUALTY CONSULTATION (NIGHT)";
		    $cas3=sqlStatement("Select  a.service_id,code,code_type,code_text,pr_price from codes a,prices b where a.id=b.pr_id and a.code='".$ct1."' and a.code_type=6 and b.pr_level='standard'");
			$cas4=sqlFetchArray($cas3);
			$codetype="Services"; 
			$servicegrpid=$cas4['code_type'];
	        $serviceid=$cas4['service_id'];
			$codetext="CASUALTY CONSULTATION (NIGHT)"; 
			$code=$codetext;
			$fee=$cas4['pr_price'];
		 }
	}	
	if($code!=null)
	{
    sqlInsert("INSERT INTO billing SET " .
      "date = '" . add_escape_custom($onset_date1) . "', " .
	  "user = '" . $_SESSION["authUserID"] . "',".
      "bill_date = '" . add_escape_custom($onset_date1) . "', " .
	  "servicegrp_id = '" . add_escape_custom($servicegrpid) . "', " .
      "service_id = '" . add_escape_custom($serviceid) . "', " .
      "code_type = '" . add_escape_custom($codetype) . "', " .
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
	
}
else if ($mode == 'update')
{
  $id = $_POST["id"];
  $result = sqlQuery("SELECT encounter, sensitivity FROM form_encounter WHERE id = ?", array($id));
  if ($result['sensitivity'] && !acl_check('sensitivities', $result['sensitivity'])) {
   die(xlt("You are not authorized to see this encounter."));
  }
  $encounter = $result['encounter'];
  // See view.php to allow or disallow updates of the encounter date.
  $datepart = acl_check('encounters', 'date_a') ? "date = '" . add_escape_custom($date) . "', " : "";
  sqlStatement("UPDATE form_encounter SET " .
    $datepart .
    "onset_date = '" . add_escape_custom($onset_date) . "', " .
    "reason = '" . add_escape_custom($reason) . "', " .
    "facility = '" . add_escape_custom($facility) . "', " .
    "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
    "facility_id = '" . add_escape_custom($facility_id) . "', " .
	"type = '" . add_escape_custom($type) . "', " .
    "billing_facility = '" . add_escape_custom($billing_facility) . "', " .
    "sensitivity = '" . add_escape_custom($sensitivity) . "', " .
	"provider_id = '" . add_escape_custom($provider_id) . "', " .
	"tpa_id = '" . add_escape_custom($tpaid) . "', " .
	"package = '" . add_escape_custom($package) . "', " .
    "referral_source = '" . add_escape_custom($referral_source) . "' " .
    "WHERE id = '" . add_escape_custom($id) . "'");

	// billing_main_copy needs to be changed here / updated. 
	
	}
else {
  die("Unknown mode '" . text($mode) . "'");
}

setencounter($encounter);

// Update the list of issues associated with this encounter.
sqlStatement("DELETE FROM issue_encounter WHERE " .
  "pid = ? AND encounter = ?", array($pid,$encounter) );
if (is_array($_POST['issues'])) {
  foreach ($_POST['issues'] as $issue) {
    $query = "INSERT INTO issue_encounter ( pid, list_id, encounter ) VALUES (?,?,?)";
    sqlStatement($query, array($pid,$issue,$encounter));
  }
}

// Custom for Chelsea FC.
//
if ($mode == 'new' && $GLOBALS['default_new_encounter_form'] == 'football_injury_audit') {

  // If there are any "football injury" issues (medical problems without
  // "illness" in the title) linked to this encounter, but no encounter linked
  // to such an issue has the injury form in it, then present that form.

  $lres = sqlStatement("SELECT list_id " .
    "FROM issue_encounter, lists WHERE " .
    "issue_encounter.pid = ? AND " .
    "issue_encounter.encounter = ? AND " .
    "lists.id = issue_encounter.list_id AND " .
    "lists.type = 'medical_problem' AND " .
    "lists.title NOT LIKE '%Illness%'", array($pid,$encounter));

  if (sqlNumRows($lres) > 0) {
    $nexturl = "patient_file/encounter/load_form.php?formname=" .
      $GLOBALS['default_new_encounter_form'];
    while ($lrow = sqlFetchArray($lres)) {
      $frow = sqlQuery("SELECT count(*) AS count " .
         "FROM issue_encounter, forms WHERE " .
         "issue_encounter.list_id = ? AND " .
         "forms.pid = issue_encounter.pid AND " .
         "forms.encounter = issue_encounter.encounter AND " .
         "forms.formdir = ?", array($lrow['list_id'],$GLOBALS['default_new_encounter_form']));
      if ($frow['count']) $nexturl = $normalurl;
    }
  }
}
$result4 = sqlStatement("SELECT fe.encounter,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
	" left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? order by fe.date desc", array($pid));
?>
<html>
<body>
<script language='JavaScript'>
<?php if ($GLOBALS['concurrent_layout'])
 {//Encounter details are stored to javacript as array.
?>
	EncounterDateArray=new Array;
	CalendarCategoryArray=new Array;
	EncounterIdArray=new Array;
	Count=0;
	 <?php
			   if(sqlNumRows($result4)>0)
				while($rowresult4 = sqlFetchArray($result4))
				 {
	?>
					EncounterIdArray[Count]='<?php echo attr($rowresult4['encounter']); ?>';
					EncounterDateArray[Count]='<?php echo attr(oeFormatShortDate(date("Y-m-d", strtotime($rowresult4['date'])))); ?>';
					CalendarCategoryArray[Count]='<?php echo attr(xl_appt_category($rowresult4['pc_catname'])); ?>';
					Count++;
	 <?php
				 }
	 ?>
	 top.window.parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);
<?php } ?>
 top.restoreSession();
<?php if ($GLOBALS['concurrent_layout']) { ?>
<?php if ($mode == 'new') { ?>
 parent.left_nav.setEncounter(<?php echo "'" . oeFormatShortDate($date) . "', " . attr($encounter) . ", window.name"; ?>);
 parent.left_nav.setRadio(window.name, 'enc');
<?php } // end if new encounter ?>
 parent.left_nav.loadFrame('enc2', window.name, '<?php echo $nexturl; ?>');
<?php } else { // end if concurrent layout ?>
 window.location="<?php echo $nexturl; ?>";
<?php } // end not concurrent layout ?>
</script>

</body>
</html>
