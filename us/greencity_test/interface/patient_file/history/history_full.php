<?php
/**
 *
 * Patient history form.
 *
 * LICENSE: This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 3
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

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//

require_once("../../globals.php");
require_once("$srcdir/patient.inc");
require_once("$srcdir/encounter.inc");
require_once("history.inc.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/options.inc.php");
$encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
$e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
setencounter($encounter);
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
 include_once("$srcdir/pid.inc");
$CPR = 4; // cells per row
//echo $encounter=$_GET["encounter"] ;
// Check authorization.
if (acl_check('patients','med')) {
  $tmp = getPatientData($pid, "squad");
  if ($tmp['squad'] && ! acl_check('squads', $tmp['squad']))
   die(htmlspecialchars(xl("Not authorized for this squad."),ENT_NOQUOTES));
}
if ( !acl_check('patients','med','',array('write','addonly') ))
  die(htmlspecialchars(xl("Not authorized"),ENT_NOQUOTES));
?>
<html>
<head>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header ?>" type="text/css">

<style>
.control_label {
 font-family: Arial, Helvetica, sans-serif;
 font-size: 10pt;
}
</style>

<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>

<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>

<script type="text/javascript" src="../../../library/js/jquery.1.3.2.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/options.js.php"); ?>
<script type="text/javascript">
function setMyPatient() {
<?php if ($GLOBALS['concurrent_layout']) { ?>
 // Avoid race conditions with loading of the left_nav or Title frame.
 if (!parent.allFramesLoaded()) {
  setTimeout("setMyPatient()", 500);
  return;
 }
<?php 
 $result = getPatientData($pid, "*, DATE_FORMAT(DOB,'%Y-%m-%d') as DOB_YMD");
 if (isset($_GET['set_pid'])) { ?>
 parent.left_nav.setPatient(<?php echo "'" . htmlspecialchars(($result['fname']) . " " . ($result['lname']),ENT_QUOTES) .
   "'," . htmlspecialchars($pid,ENT_QUOTES) . ",'" . htmlspecialchars(($result['genericname1']),ENT_QUOTES) .
   "','', ' " . htmlspecialchars(xl('DOB') . ": " . oeFormatShortDate($result['DOB_YMD']) . " " . xl('Age') . ": " . getPatientAgeDisplay($result['DOB_YMD']), ENT_QUOTES) . "'"; ?>);
 var EncounterDateArray = new Array;
 var CalendarCategoryArray = new Array;
 var EncounterIdArray = new Array;
 var Count = 0;
<?php
  //Encounter details are stored to javacript as array.
  $result4 = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? order by fe.date desc", array($pid));
  if(sqlNumRows($result4)>0) {
    while($rowresult4 = sqlFetchArray($result4)) {
?>
 EncounterIdArray[Count] = '<?php echo htmlspecialchars($rowresult4['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray[Count] = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($rowresult4['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray[Count] = '<?php echo htmlspecialchars(xl_appt_category($rowresult4['pc_catname']), ENT_QUOTES); ?>';
 Count++;
<?php
    }
  }
?>

 parent.left_nav.setPatientEncounter(EncounterIdArray,EncounterDateArray,CalendarCategoryArray);
  <?php
  $test = sqlStatement("SELECT fe.encounter,fe.encounter_ipop,fe.date,openemr_postcalendar_categories.pc_catname FROM form_encounter AS fe ".
    " left join openemr_postcalendar_categories on fe.pc_catid=openemr_postcalendar_categories.pc_catid  WHERE fe.pid = ? and  fe.encounter=? order by fe.date desc", array($pid,$e));
	 $test1=sqlFetchArray($test);
?>
 EncounterIdArray1= '<?php echo htmlspecialchars($test1['encounter'], ENT_QUOTES); ?>';
 EncounterDateArray1 = '<?php echo htmlspecialchars(oeFormatShortDate(date("Y-m-d", strtotime($test1['date']))), ENT_QUOTES); ?>';
 CalendarCategoryArray1 = '<?php echo htmlspecialchars(xl_appt_category($test1['pc_catname']), ENT_QUOTES); ?>';
 parent.left_nav.setEncounter(EncounterDateArray1,EncounterIdArray1,CalendarCategoryArray1);
<?php } // end setting new pid ?>
 parent.left_nav.setRadio(window.name, 'dem');
 parent.left_nav.syncRadios();
<?php } // end concurrent layout ?>
}
$(window).load(function() {
 setMyPatient();
});
</script>
<script LANGUAGE="JavaScript">
 //Added on 5-jun-2k14 (regarding 'Smoking Status - display SNOMED code description')
 var code_options_js = Array();
 
 <?php
 $smoke_codes = getSmokeCodes();
  
 foreach ($smoke_codes as $val => $code) {
            echo "code_options_js"."['" . attr($val) . "']='" . attr($code) . "';\n";
      }
 ?>
     
var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

function divclick(cb, divid) {
 var divstyle = document.getElementById(divid).style;
 if (cb.checked) {
  divstyle.display = 'block';
 } else {
  divstyle.display = 'none';
 }
 return true;
}

// Compute the length of a string without leading and trailing spaces.
function trimlen(s) {
 var i = 0;
 var j = s.length - 1;
 for (; i <= j && s.charAt(i) == ' '; ++i);
 for (; i <= j && s.charAt(j) == ' '; --j);
 if (i > j) return 0;
 return j + 1 - i;
}

function validate(f) {
<?php generate_layout_validation('HIS'); ?>
 return true;
}

function submitme() {
 var f = document.forms[0];
 if (validate(f)) {
  top.restoreSession();
  f.submit();
 }
}

function submit_history() {
    top.restoreSession();
    document.forms[0].submit();
}

//function for selecting the smoking status in radio button based on the selection of drop down list.
function radioChange(rbutton)
{
    if (rbutton == 1 || rbutton == 2 || rbutton == 15 || rbutton == 16)
     {
     document.getElementById('radio_tobacco[current]').checked = true;
     }
     else if (rbutton == 3)
     {
     document.getElementById('radio_tobacco[quit]').checked = true;
     }
     else if (rbutton == 4)
     {
     document.getElementById('radio_tobacco[never]').checked = true;
     }
     else if (rbutton == 5 || rbutton == 9)
     {
     document.getElementById('radio_tobacco[not_applicable]').checked = true;
     }
     else if (rbutton == '')
     {
     var radList = document.getElementsByName('radio_tobacco');
     for (var i = 0; i < radList.length; i++) {
     if(radList[i].checked) radList[i].checked = false;
     }
     }
     //Added on 5-jun-2k14 (regarding 'Smoking Status - display SNOMED code description')
     if(rbutton!=""){
         if(code_options_js[rbutton]!="")
            $("#smoke_code").html(" ( "+code_options_js[rbutton]+" )");
         else
             $("#smoke_code").html(""); 
     }
     else
        $("#smoke_code").html(""); 
}

//function for selecting the smoking status in drop down list based on the selection in radio button.
function smoking_statusClicked(cb) 
{    
     if (cb.value == 'currenttobacco')
     {
     document.getElementById('form_tobacco').selectedIndex = 1;
     }
     else if (cb.value == 'nevertobacco')
     {
     document.getElementById('form_tobacco').selectedIndex = 4;
     }
     else if (cb.value == 'quittobacco')
     {
     document.getElementById('form_tobacco').selectedIndex = 3;
     }
     else if (cb.value == 'not_applicabletobacco')
     {
     document.getElementById('form_tobacco').selectedIndex = 6;
     }
	 radioChange(document.getElementById('form_tobacco').value);	 
}

// The ID of the input element to receive a found code.
var current_sel_name = '';

// This is for callback by the find-code popup.
// Appends to or erases the current list of related codes.
function set_related(codetype, code, selector, codedesc) {
 var frc = document.forms[0][current_sel_name];
 var s = frc.value;
 if (code) {
  if (s.length > 0) s += ';';
  s += codetype + ':' + code;
 } else {
  s = '';
 }
 frc.value = s;
}

// This invokes the find-code popup.
function sel_related(e) {
 current_sel_name = e.name;
 dlgopen('../encounter/find_code_popup.php<?php if ($GLOBALS['ippf_specific']) echo '?codetype=REF' ?>', '_blank', 500, 400);
}

</script>

<script type="text/javascript">
/// todo, move this to a common library
$(document).ready(function(){
    if($("#form_tobacco").val()!=""){
        if(code_options_js[$("#form_tobacco").val()]!=""){
            $("#smoke_code").html(" ( "+code_options_js[$("#form_tobacco").val()]+" )");
        }
    }
    tabbify();
});
</script>

<style type="text/css">
div.tab {
	height: auto;
	width: auto;
}
td{
	font-size: 12;
}
</style>

</head>
<body class="body_top">

<?php
$result = getHistoryData($pid);
 $details=getPatientData($pid);
 $name=$details['fname'].' ' .$details['lname'];
if (!is_array($result)) {
  newHistoryData($pid);
  $result = getHistoryData($pid);
}

$fres = sqlStatement("SELECT * FROM layout_options " .
  "WHERE form_id = 'HIS' AND uor > 0 " .
  "ORDER BY group_name, seq");
?>

<form action="history_save.php?encounter=<?php echo $encounter?>" name='history_form' method='post' onsubmit='return validate(this)' >
    <input type='hidden' name='mode' value='save'>

    <div>
        <span class="title"><?php echo htmlspecialchars(xl('Patient History'),ENT_NOQUOTES); ?></span>
    </div>
    <div style='float:left;margin-right:10px'>
  <?php echo htmlspecialchars(xl('for'),ENT_NOQUOTES);?><br><b><?php echo htmlspecialchars(xl('Name:'),ENT_NOQUOTES)?>&nbsp;&nbsp;<span class="title"><a href="../summary/demographics.php" onclick='top.restoreSession()'><?php echo htmlspecialchars($name,ENT_NOQUOTES); ?></a></span>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <?php
  $details=getPatientData($pid);
  $age=$details['age'];
  $occupation=$details['occupation'];
  $husband=$details['guardiansname'];
  echo htmlspecialchars(xl('Occupation:') ,ENT_NOQUOTES);
  ?></b>
  &nbsp;&nbsp;
  <?php echo htmlspecialchars($occupation,ENT_NOQUOTES);?>
  <br>
  <b><?php echo htmlspecialchars(xl('Age:') ,ENT_NOQUOTES);?></b>
  &nbsp;&nbsp;
  <?php echo htmlspecialchars($age,ENT_NOQUOTES);?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <b><?php echo htmlspecialchars(xl('Husbands Name:') ,ENT_NOQUOTES);?></b>
  &nbsp;&nbsp;
  <?php echo htmlspecialchars($husband,ENT_NOQUOTES);?>
    </div>
	<br>
	<br><br><br>
    <div>
        <a href="javascript:submit_history();" class='css_button'>
            <span><?php echo htmlspecialchars(xl('Save'),ENT_NOQUOTES); ?></span>
        </a>
        <a href="history.php" <?php if (!$GLOBALS['concurrent_layout']) echo "target='Main'"; ?> class="css_button" onclick="top.restoreSession()">
            <span><?php echo htmlspecialchars(xl('Back To View'),ENT_NOQUOTES); ?></span>
        </a>
    </div>

    <br/>

    <!-- history tabs -->
    <div id="HIS" style='float:none; margin-top: 10px; margin-right:20px'>
        <ul class="tabNav" >
           <?php display_layout_tabs('HIS', $result, $result2); ?>
        </ul>

        <div class="tabContainer">
            <?php display_layout_tabs_data_editable('HIS', $result, $result2); ?>
        </div>
    </div>
</form>

<!-- include support for the list-add selectbox feature -->
<?php include $GLOBALS['fileroot']."/library/options_listadd.inc"; ?>

</body>

<script language="JavaScript">
<?php echo $date_init; // setup for popup calendars ?>
$(document).ready(function () {
	$('.tabNav').addClass('nav nav-tabs');
  $('.tabContainer').addClass('tab-pane');
     var ids = $(".nav-tabs").children().length; //think about it ;)
  var id = ids + 1;
     var tabId = 'iclick_' + id;
     $('.tabNav').append('<li><a href="#iclick_' + id + '" id="newt">Obstretic Examination</a></li>');
     $('.tabContainer').append('<div class="tab" id="' + tabId + '"><iframe src="<?php echo $GLOBALS['webroot'] ?>/interface/drugs/treatment.php" frameborder="0" width="2000" height="500" scrolling="auto" id="myFrame"></iframe></div>');

     // add this

  // $('.tabNav li:nth-child(' + id + ')').click.addClass('current').siblings().removeClass('current');

   $("#newt").click(function(){
	  $('.tabNav li:nth-child(' + id + ')').addClass('current').siblings().removeClass('current');
	   $("#"+tabId).addClass('current').siblings().removeClass('current');
	   });
	   
	   
     
	   
});
</script>

</html>
