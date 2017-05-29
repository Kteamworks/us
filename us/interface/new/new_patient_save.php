<?php
require_once("../globals.php");
require_once("$srcdir/sql.inc");

// Validation for non-unique external patient identifier.
if (!empty($_POST["pubpid"])) {
  $form_pubpid = trim($_POST["pubpid"]);
  $result = sqlQuery("SELECT count(*) AS count FROM patient_data WHERE " .
    "pubpid = '$form_pubpid'");
  if ($result['count']) {
    // Error, not unique.
    require_once("new.php");
    exit();
  }
}

require_once("$srcdir/pid.inc");
require_once("$srcdir/patient.inc");
require_once("$srcdir/forms.inc");
require_once("$srcdir/sql.inc");
require_once("$srcdir/encounter.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once("$srcdir/formdata.inc.php");
//here, we lock the patient data table while we find the most recent max PID
//other interfaces can still read the data during this lock, however
sqlStatement("lock tables patient_data read");

$result = sqlQuery("select max(pid)+1 as pid from patient_data");

// TBD: This looks wrong to unlock the table before we have added our
// patient with its newly allocated pid!
//
sqlStatement("unlock tables");
//end table lock
$newpid = 1;

if ($result['pid'] > 1)
  $newpid = $result['pid'];

setpid($newpid);

if($pid == NULL) {
  $pid = 0;
}

// what do we set for the public pid?
if (isset($_POST["pubpid"]) && ($_POST["pubpid"] != "")) {
  $mypubpid = $_POST["pubpid"];
} else {
  $mypubpid = $pid;
}

if ($_POST['form_create']) {

  $form_fname = ucwords(trim($_POST["fname"]));
  $form_lname = ucwords(trim($_POST["lname"]));
  $form_mname = ucwords(trim($_POST["mname"]));

  // ===================
  // DBC SYSTEM WAS REMOVED
  $form_sex               = trim($_POST["sex"]) ;
  $form_dob               = trim($_POST["DOB"]) ;
  $form_street            = '' ;
  $form_city              = '' ;
  $form_postcode          = '' ;
  $form_countrycode       = '' ;
  // EOS DBC
  // ===================

  newPatientData(
    $_POST["db_id"],
    $_POST["title"],
    $form_fname,
    $form_lname,
    $form_mname,
    $form_sex, // sex
    $form_dob, // dob
    $form_street, // street
    $form_postcode, // postal_code
    $form_city, // city
    "", // state
    $form_countrycode, // country_code
    "", // ss
    "", // occupation
    "", // phone_home
    "", // phone_biz
    "", // phone_contact
    "", // status
    "", // contact_relationship
    "", // referrer
    "", // referrerID
    "", // email
    "", // language
    "", // ethnoracial
    "", // interpreter
    "", // migrantseasonal
    "", // family_size
    "", // monthly_income
    "", // homeless
    "", // financial_review
    "$mypubpid",
    $pid,
    "", // providerID
    "", // genericname1
    "", // genericval1
    "", // genericname2
    "", // genericval2
    "", // phone_cell
    "", // hipaa_mail
    "", // hipaa_voice
    0,  // squad
    0,  // $pharmacy_id = 0,
    "", // $drivers_license = "",
    "", // $hipaa_notice = "",
    "", // $hipaa_message = "",
    $_POST['regdate']
  );

  newEmployerData($pid);
  newHistoryData($pid);
  newInsuranceData($pid, "primary");
  newInsuranceData($pid, "secondary");
  newInsuranceData($pid, "tertiary");

  // Set referral source separately because we don't want it messed
  // with later by newPatientData().
  if ($refsource = trim($_POST["refsource"])) {
    sqlQuery("UPDATE patient_data SET referral_source = '$refsource' " .
      "WHERE pid = '$pid'");
  }

  
  
  
  	$encounter = generate_id();
	$pc_catid = 24;
	$date = date('Y-m-d 00:00:00');
    addForm($encounter, "New Patient Encounter",
    sqlInsert("INSERT INTO form_encounter SET " .
      "date = '" . add_escape_custom($date) . "', " .
      "pc_catid = '" . add_escape_custom($pc_catid) . "', " .
      "pid = '" . add_escape_custom($pid) . "', " .
      "encounter = '" . add_escape_custom($encounter) . "', "),
      "newpatient", $pid, $userauthorized, $date);
	   
var_dump($encounter);
}
?>
<html>
<body>
<script language="Javascript">
<?php
if ($alertmsg) {
  echo "alert('$alertmsg');\n";
}
if ($GLOBALS['concurrent_layout']) {
  echo "window.location='$rootdir/patient_file/summary/demographics.php?" .
    "set_pid=$pid&is_new=1&enc=$encounter';\n";
} else {
  echo "window.location='$rootdir/patient_file/patient_file.php?set_pid=$pid';\n";
}
?>
</script>

</body>
</html>
