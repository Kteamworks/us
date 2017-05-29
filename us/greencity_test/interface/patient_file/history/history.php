<?php

//SANITIZE ALL ESCAPES
$sanitize_all_escapes=true;
//

//STOP FAKE REGISTER GLOBALS
$fake_register_globals=false;
//


    //session_start();
    //echo $_SESSION['myencpass']; 


 require_once("../../globals.php");
 require_once("$srcdir/patient.inc");
  require_once("$srcdir/encounter.inc");
 require_once("history.inc.php");
 require_once("$srcdir/options.inc.php");
 require_once("$srcdir/acl.inc");
  $encounter=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
$e=$_GET["encounter"] ? $_GET["encounter"] : $GLOBALS['encounter'];
setencounter($encounter);
$returnurl = $GLOBALS['concurrent_layout'] ? 'encounter_top.php' : 'patient_encounter.php';
 include_once("$srcdir/pid.inc");
 
 /* if ($GLOBALS['concurrent_layout'] && isset($_GET['set_pid'])) {
  include_once("$srcdir/pid.inc");
  setpid($_GET['set_pid']);
 } */
 
 
?>
<html>
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

<style type="text/css">
#HIS .label {
	color: black;
}
.table .label {

color:black;
float left;

}

</style>
<?php html_header_show();?>
<link rel="stylesheet" href="<?php echo $css_header;?>" type="text/css">
<link rel="stylesheet" type="text/css" href="../../../library/js/fancybox/jquery.fancybox-1.2.6.css" media="screen" />

 <link rel="stylesheet" href="../../../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"> 
<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>
<script type="text/javascript" src="../../../library/dialog.js"></script>
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
      <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.1/js/bootstrap.min.js"></script>
   
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../../library/js/common.js"></script>
<script type="text/javascript" src="../../../library/js/fancybox/jquery.fancybox-1.2.6.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    tabbify();
});
</script>



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
function setEnc(enc) {
 $.ajax({
    type: "POST",
    url: "post_enc.php",
    data: {
     ent:enc
    },
    success: function(response){
    // alert(response);
   
    },
    error:function(){
     console.log(error);
     alert('ajax error');
    } 
   });
 
}

</script>

<style type="css/text">
.timeline li a i {
    MARGIN-LEFT: 20PX;
    padding: 6px;
    border-radius: 13px;
}
#pnotes_ps_expand {
  height:auto;
  width:100%;
}
#tid {
	
	font-size: 1px;
	
}


#printb{ width:550px; size: 4.5in 3.2in;font-size:5px}
@page{}
</style>
</head>
<body class="body_top">
<div class="container-fluid">
<div class="row">
<?php
 if (acl_check('patients','med')) {
  $tmp = getPatientData($pid, "squad");
  if ($tmp['squad'] && ! acl_check('squads', $tmp['squad'])) {
   echo "<p>(".htmlspecialchars(xl('History not authorized'),ENT_NOQUOTES).")</p>\n";
   echo "</body>\n</html>\n";
   exit();
  }
 }
 else {  
  echo "<p>(".htmlspecialchars(xl('History not authorized'),ENT_NOQUOTES).")</p>\n";
  echo "</body>\n</html>\n";
  exit();
 }

 $result = getHistoryData($pid);
 if (!is_array($result)) {
  newHistoryData($pid);
  $result = getHistoryData($pid);
  while($res=sqlFetchArray($result)){
  
  }
 }
?>

<?php if (acl_check('patients','med','',array('write','addonly') )) { ?>
<div>
    <span class="title"><?php echo htmlspecialchars(xl('Patient History'),ENT_NOQUOTES); ?></span>
</div>
<div style='float:left;margin-right:10px'>
<!--<?php //echo htmlspecialchars(xl('for'),ENT_NOQUOTES);?>&nbsp;--><span class="title"><a href="../summary/demographics.php" onclick="top.restoreSession()"><?php echo htmlspecialchars(getPatientName($pid),ENT_NOQUOTES) ?></a></span>
</div>
<div>
    <a href="history_full.php" <?php if (!$GLOBALS['concurrent_layout']) echo "target='Main'"; ?>
     class="css_button"
     onclick="top.restoreSession()">
    <span><?php echo htmlspecialchars(xl("Edit"),ENT_NOQUOTES);?></span>
    </a>
    <a href="../summary/demographics.php" <?php if (!$GLOBALS['concurrent_layout']) echo "target='Main'"; ?> class="css_button" onclick="top.restoreSession()">
        <span><?php echo htmlspecialchars(xl('Back To Patient Dashboard'),ENT_NOQUOTES);?></span>
    </a>
</div>
<br/>
<?php } ?>
</div>
<div class="row">
<div class="col-md-8">
<div style='float:none; margin-top: 10px; margin-right:20px'>
    <table>
    <tr>
        <td>
		
            <!-- Demographics -->
            <div id="HIS">
                <ul class="tabNav">
                   <?php display_layout_tabs('HIS', $result, $result2); ?>
                </ul>
                <div class="tabContainer">
                   <?php display_layout_tabs_data('HIS', $result, $result2); ?>
                </div>
            </div>
        </td>
    </tr>
    </table>
</div>
</div>
<div>
<!--
<table cellspacing='0' cellpadding='0' border='0'>
 <tr>
  <td class="small" colspan='4'>
<a href="../../forms/vitals/new.php" onclick='top.restoreSession()'>
<?php echo htmlspecialchars(xl('G.E'),ENT_NOQUOTES); ?></a>
|
</td>
</tr>
</table>-->
</div>
<div class="col-md-4 col-sm-8 col-xs-8" style='font-size: 1.5em'>

<?php
//$qry = "select * from form_encounter f, history_data h where f.encounter=h.encounter and f.pid=? group by f.encounter order by f.date desc";
$qry= "select * from form_encounter f where f.pid=? group by f.encounter order by f.date desc";
          $pdata = sqlStatement($qry, array($pid));
           $display=1;
	
            
                //print "<h1>".xl('History').":</h1>";
                // printRecDataOne($history_data_array, getRecHistoryData ($pid), $N);
                
				//echo $result1;
                //echo "   <table>\n";
                //display_layout_rows('HIS', $result1);
                //echo "   </table>\n";

			 
			 
?>
<ul style="list-style:none;">
<li><a href="../summary/pnotes_full_add.php?<?php echo $urlparms; ?>" id="note" class="black-tooltip iframe" data-original-title="Tooltip on bottom" onclick='top.restoreSession()' data-toggle="tooltip" data-placement="bottom" title="Add Patient Notes"> <span class="fa-stack fa-lg">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-sticky-note-o fa-stack-1x fa-inverse"></i>
</span></a></li>
</ul>
<?php $qry2 = "SELECT pnotes.body
FROM pnotes
WHERE pid = ?";
          $pnotes = sqlStatement($qry2, array($pid));
    while ($pnote = sqlFetchArray($pnotes)) {
    ?>
    <table class="table table-responsive">
    <tr>
  <th>Patient Notes</th>
    <td><?php $str = $pnote['body']; $brk = explode('(',$str); $string = explode(')',$brk[1]); echo $brk[0].'<br>'.$string[1]; ?></td>
  </tr>
  </table>
    <?php } ?>
    <br>
 <!-- <a href="http://103.230.38.89/testc/interface/forms/vitals/new.php" class="element iframe rx_modal pull-right" data-toggle="tooltip" data-placement="top" title="Add Vitals">

 <span class="fa-stack fa-lg">
  <i class="fa fa-circle fa-stack-2x"></i>
  <i class="fa fa-stethoscope fa-stack-1x fa-inverse"></i>
</span></a>
<?php $qry2 = "SELECT date,bps,bpd,height,weight FROM mangoo.form_vitals WHERE pid = ?";
          $vitals = sqlStatement($qry2, array($pid));
    while ($vital = sqlFetchArray($vitals)) {
    ?>
    <table class="table table-responsive">
    <tr>
  <th>Vitals</th>
    <td>Date:<?php echo $vital['date'] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Height:<?php echo $vital['height'] ?>in <br>Weight:<?php echo $vital['weight'] ?>lbs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;BP:<?php echo $vital['bps'] ?>mmHg</td>
  </tr>
  </table> -->
    <?php } ?>
<script>
$(".iframe").fancybox( {
  "left":10,"top":100,
 "overlayOpacity" : 0.0,
 "showCloseButton" : true,
 "frameHeight" : 550,
 "frameWidth" : 550
  });
</script>
<br>

<ul class="timeline">

<?php 	while ($prow = sqlFetchArray($pdata)) { ?>
                        <!-- timeline time label -->
                            <li class="time-label">
                                 <span class="bg-green">
								 
                                        <?php $newDate = date("d-m-Y", strtotime($prow['date'])); echo $newDate; ?>
                                    </span>                             </li>
                            <li>
                                                                  <a id="encounter" href="../encounter/encounter_top.php?set_encounter=<?php echo $prow['encounter'] ?>">  <i class="fa fa-mail-reply-all bg-yellow uni" style="margin-left: 20px;padding: 6px;border-radius: 13px;" title="View Details"></i></a>
                                                                <div class="timeline-item">
                                    <h3 class="timeline-header">
                                    
                                    <div class="user-block" style="display: inline-block;">
                                       
                                                                                            <img src="https://d30y9cdsu7xlg0.cloudfront.net/png/23420-200.png" class="img-circle img-bordered-sm" alt="User Image">
                                            
                                        <span class="username">
                                          <a href="#"><?php $doctorname = sqlStatement("SELECT username FROM users WHERE id=?", array($prow['provider_id']));  while($doctor = sqlFetchArray($doctorname)) { echo $doctor['username']; }?></a>
                                          
                                        </span>
                                        <span class="description"><i class="fa fa-clock-o"></i> <?php echo $prow['date']; ?></span>
										
                                      </div><!-- /.user-block -->
		
									  <table class='table table-responsive table-striped' style="display: list-item">
									  <tr>
				
<?php $qry_lab="select procedure_name from procedure_order a,procedure_order_code b where a.procedure_order_id=b.procedure_order_id and a.patient_id=? and a.encounter_id=?";	  
    $qry_lab1=sqlStatement($qry_lab,array($pid,$prow['encounter']));
	?>
	<th>Procedure Name</th><td>
	<?php while($qry_labdata = sqlFetchArray($qry_lab1) ) { ?> 
				
				<?php echo $qry_labdata['procedure_name']; ?><br>
				
<?php } ?>
</td></tr>
  </table>
                                    </h3>

                                   
                                </div>
                            </li>
                                                   
<?php } ?>
 <li>
                            <i class="fa fa-clock-o bg-gray"></i>
                        </li>
                        <ul class="pull-right" style="padding-right:25px;padding-bottom:10px;">
                        </ul>
                    </ul>
</div>
</div>
</div>
</body>
</html>
