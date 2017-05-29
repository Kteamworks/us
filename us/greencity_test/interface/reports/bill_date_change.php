<?php 
/**
 * This is a report of Financial Summary by Service Code.
 *
 * This is a summary of service code charge/pay/adjust and balance,
 * with the ability to pick "important" codes to either highlight or
 * limit to list to. Important codes can be configured in
 * Administration->Service section by assigning code with
 * 'Service Reporting'.
 *
 * Copyright (C) 2006-2010 Rod Roark <rod@sunsetsystems.com>
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
 * @author  Visolve
 * @link    http://www.open-emr.org
 */

$sanitize_all_escapes=true;
$fake_register_globals=false;

require_once("../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/sql-ledger.inc");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formatting.inc.php");
require_once "$srcdir/options.inc.php";
require_once "$srcdir/formdata.inc.php";
require_once "$srcdir/appointments.inc.php";
?>

<html>
<head>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/dialog.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/common.js"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery-ui.js"></script>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<?php html_header_show();?>
<script type="text/javascript">

function printme() {
  var divstyle = document.getElementById('hideonprint').style;
  divstyle.display = 'none';
  divstyle.visibility = 'hidden';
  window.print();
  // divstyle.display = 'block';
 }

</script>
<style type="text/css">
/* specifically include & exclude from printing */

@media print {
    #report_parameters {
        visibility: hidden;
        display: none;
    }
    #report_parameters_daterange {
        visibility: visible;
        display: inline;
    }
    #report_results {
       margin-top:30px;
    }
	
	#report {
       margin:30px;
    }
}
table{
    border-style: none;
    border-top: none;
}

/* specifically exclude some from the screen */
@media screen {
    #report_parameters_daterange {
        visibility: hidden;
        display: none;
    }
}
#report_parameters {
  background-color: #ececec;
  margin-top: 10px; }

  #report_parameters table {
    border: none;
    width: 100%;
    border-collapse: collapse; }

    #report_parameters table table td {
      padding: 5px; }

	  #report_parameters table table table {
      border: none;
      border-collapse: collapse;
      font-size: 0.8em; }

      #report_parameters table table table td.label {
        text-align: right; }

#report_results table {
  
  width: 100%;
  border-collapse: collapse;
  margin-top: 0px; }
  #report_results table thead {
    padding: 5px;
    display: table-header-group;
    background-color: #ddd;
    text-align: left;
    font-weight: bold;
    font-size: 0.7em; }
  #report_results table th {
   
    padding: 5px; }
  #report_results table td {
    padding: 5px;
    
    font-size: 0.8em; }

	
	#report table {
  
  width: 100%;
  border-collapse: collapse;
  margin-top: 0px; }
  #report table thead {
    padding: 5px;
    display: table-header-group;
    background-color: #ddd;
    text-align: left;
    font-weight: bold;
    font-size: 0.7em; }
  #report th {
   
    padding: 5px; }
  #report table td {
    
    font-size: 0.8em; }
</style>

<title><?php echo xlt('Bill Change') ?></title>
</head>
<body leftmargin='0' topmargin='0' marginwidth='0' marginheight='0' class="body_top">


<form method='post' action='bill_date_change.php' id='theform'>
<div id="report_parameters">
<div id="hideonprint">
<input type='hidden' name='form_refresh' id='form_refresh' value=''/>
<input type='hidden' name='form_csvexport' id='form_csvexport' value=''/>
<input type='hidden' name='form_save' id='form_save' value=''/>
Enter the Bill Number to Change: <input type='text' name='bill_change' id='bill_change' value=''/><br/>
 Enter the date to Change:  <input type='text' name='form_from_date' id="form_from_date" size='10' value='<?php echo attr($form_from_date) ?>'
                                onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
                           <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                                id='img_from_date' border='0' alt='[?]' style='cursor:pointer'
                                title='<?php echo xla("Click here to choose a date"); ?>'><br/>
Enter the changed date:	   <input type='text' name='form_to_date' id="form_to_date" size='10' value='<?php echo attr($form_to_date) ?>'
                                onkeyup='datekeyup(this,mypcc)' onblur='dateblur(this,mypcc)' title='yyyy-mm-dd'>
                           <img src='../pic/show_calendar.gif' align='absbottom' width='24' height='22'
                                id='img_to_date' border='0' alt='[?]' style='cursor:pointer'
                                title='<?php echo xla("Click here to choose a date"); ?>'><br/><br/><br/>
                        
<input type='submit' name='submit' id='submit' value='submit'/>
</div>
 <!-- end of parameters -->
</div>
  

</form>

<?php
if($_POST['submit'])
{

$bill_change=$_POST['bill_change'];
$date=$_POST['form_from_date'];
$datechange=$_POST['form_to_date'];
$billing="SELECT * from billing where bill_id='$bill_change' and activity='1' and DATE(date)='$date'";
	  $bills=sqlStatement($billing);
	  $bill=sqlFetchArray($bills);
	  $authuser=$_SESSION["authUser"];
//$qry="UPDATE vouchers set voureverseYN='1'  where voucher_no='$voucher_no'";
		
	//$r1=sqlStatement($qry);	  
$id="SELECT * from billing where bill_id='$bill_change' and activity='1'";
	  $id1=sqlStatement($id);
	  $id2=sqlFetchArray($id1);
	  if($id2['bill_id']==null)
       {
	         echo( "Please Enter the Proper Bill Number!" );
          }else{	
			sqlStatement("update billing set date='$datechange', user='".$_SESSION['authUserID']."' where DATE(date)='$date' and bill_id='$bill_change' and encounter='".$bill['encounter']."' and activity=1");
			/* while($billed= sqlFetchArray($bills))

			{
			
			   $ppid=$billed['pid'];
			   $eencounter=$billed['encounter'];
  	           $units=$billed['units'];
  	           $fee=$billed['fee'];
	           $authrzd=1;
	           $modif=$billed['modifier'];
	           $act=$billed['activity'];
	           $grpn=$billed['groupname'];
	           $onset_date=date('Y-m-d H:i:s');
			   $provider_id=$billed['provider_id'];
			   $payer_id=$billed['payer_id'];
			   $bill_id=$billed['bill_id'];
			   $payout=$billed['payout'];
			   $voucherpaid_YN=$billed['voucherpaid_YN'];
			   $admitted=$billed['admitted'];
               $code=$billed['code'];
	           $codetext=$billed['code_text'];
  	           $codetype=$billed['code_type'];
  	           $billed=1;
			  sqlStatement("UPDATE billing set date='$datechange' where ")
	              sqlStatement("UPDATE billing SET " .
                                "date =  '" . add_escape_custom($datechange) . "', " .
	                            "user = '" . $_SESSION["authUserID"] . "',".
                                "bill_date = '" . add_escape_custom($onset_date) . "', " .
                                "code_type = '" . add_escape_custom($codetype) . "', " .
                                "code = '" . add_escape_custom($code) . "', " .
                                "code_text = '" . add_escape_custom($codetext) . "', " .
                                "units = '" . add_escape_custom($units) . "', " .
                                "billed = '" . add_escape_custom($billed) . "', " .
                                "fee = '" . add_escape_custom(($fee)) . "', " .
                                "pid = '" . add_escape_custom($ppid) . "', " .
                                "encounter = '" . add_escape_custom($eencounter) . "', " .
	                            "modifier = '" . add_escape_custom($modif) . "', " .
	                            "authorized = '" . add_escape_custom($authrzd) . "', " .
	                            "activity = '" . add_escape_custom($act) . "', " .
	                            "groupname = '" . add_escape_custom($grpn) . "', " .
								"payer_id = '" . add_escape_custom($payer_id) . "', " .
								"bill_id = '" . add_escape_custom($bill_id) . "', " .
								"payout = '" . add_escape_custom(($payout)) . "', " .
								"voucherpaid_YN = '" . add_escape_custom($voucherpaid_YN) . "', " .
								"admitted = '" . add_escape_custom($admitted) . "', " .
                                "provider_id = '" . add_escape_custom($provider_id) . "'"); 
								
	  
            }  */
			echo( "UPDATED SUCCESSFULLY!" );
		  }
		}


?>
</body>

<!-- stuff for the popup calendar -->

<link rel='stylesheet' href='<?php echo $css_header ?>' type='text/css'>
<style type="text/css">@import url(../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../library/dynarch_calendar_setup.js"></script>
<script language="Javascript">
 Calendar.setup({inputField:"form_from_date", ifFormat:"%Y-%m-%d ", button:"img_from_date"});
Calendar.setup({inputField:"form_to_date", ifFormat:"%Y-%m-%d %H:%M:%S", button:"img_to_date", showsTime:true});
 top.restoreSession();
</script>
</html>