<?php
/**
 * Add or edit an event in the calendar.
 *
 * Can be displayed as a popup window, or as an iframe via
 * fancybox.
 *
 * Copyright (C) 2005-2013 Rod Roark <rod@sunsetsystems.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * @package OpenEMR
 * @author  Rod Roark <rod@sunsetsystems.com>
 * @link    http://www.open-emr.org
 */

 // The event editor looks something like this:

 //------------------------------------------------------------//
 // Category __________________V   O All day event             //
 // Date     _____________ [?]     O Time     ___:___ __V      //
 // Title    ___________________     duration ____ minutes     //
 // Patient  _(Click_to_select)_                               //
 // Provider __________________V   X Repeats  ______V ______V  //
 // Status   __________________V     until    __________ [?]   //
 // Comments ________________________________________________  //
 //                                                            //
 //       [Save]  [Find Available]  [Delete]  [Cancel]         //
 //------------------------------------------------------------//

 $fake_register_globals=false;
 $sanitize_all_escapes=true;

require_once('../../globals.php');
require_once($GLOBALS['srcdir'].'/patient.inc');
require_once($GLOBALS['srcdir'].'/forms.inc');
require_once($GLOBALS['srcdir'].'/calendar.inc');
require_once($GLOBALS['srcdir'].'/formdata.inc.php');
require_once($GLOBALS['srcdir'].'/options.inc.php');
require_once($GLOBALS['srcdir'].'/encounter_events.inc.php');
require_once($GLOBALS['srcdir'].'/acl.inc');

 //Check access control
 if (!acl_check('patients','appt','',array('write','wsome') ))
   die(xl('Access not allowed'));

/* Things that might be passed by our opener. */
 $eid           = $_GET['eid'];         // only for existing events
 $date          = $_GET['date'];        // this and below only for new events
 $userid        = $_GET['userid'];
 $default_catid = $_GET['catid'] ? $_GET['catid'] : '5';
 //
 if ($date)
  $date = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6);
 else
  $date = date("Y-m-d");
 //
 $starttimem = '00';
 if (isset($_GET['starttimem']))
  $starttimem = substr('00' . $_GET['starttimem'], -2);
 //
 if (isset($_GET['starttimeh'])) {
  $starttimeh = $_GET['starttimeh'];
  if (isset($_GET['startampm'])) {
   if ($_GET['startampm'] == '2' && $starttimeh < 12)
    $starttimeh += 12;
  }
 } else {
  $starttimeh = date("G");
 }
 $startampm = '';

 $info_msg = "";

 ?>

 <script type="text/javascript" src="<?php echo $GLOBALS['webroot'] ?>/library/js/jquery.js"></script>

 <?php

 
 function SendSMS($time,$today,$un,$pn,$ppn)
{
	                     $user = 'kavaii';
						 $password = '12345';
						 $sender_id = 'KAVAII';//helloz welcom FAPcop abhiii'hiiiii
						 $sender = '7976345602';//9673776599 9320491970
						 //$sender =$pn;
						 $msg = 'GreencityCity Hospital- Appointment Due  with ';
						 $msg.= $un;
						 $msg.= " at ";
						 $msg.=$time;
						 $msg.=' hrs on ';
						 $msg.=$today;
						 $priority = 'sdnd';
						 $sms_type = 'normal';
						 //$data = array('user'=>$user, 'pass'=>$password, 'sender'=>$sender_id, 'phone'=>$sender, 'text'=>$msg,  'stype'=>$sms_type);//'priority'=>$priority,
						 //http://bhashsms.com/api/sendmsg.php?user='kavaii'&pass='12345'&sender='KAVAII'&phone='9782364064'&text='Hii'&stype='normal'&priority='sdnd'
						 
						 //http://bhashsms.com/api/sendmsg.php?user=kavaii&pass=12345&sender=kavaii%20&phone=9731960662%20&text=hii%20&priority=sdnd&stype=normal
						 $data='user='.$user.'&pass='.$password.'&sender='.$sender_id.'&phone='.$sender.'&text='.$msg.'&stype='.$sms_type.'&priority=sdnd'; 
						 
						 
						 $ch = curl_init('http://bhashsms.com/api/sendmsg.php?'.$data);
						 echo var_dump($data);
						 curl_setopt($ch, CURLOPT_POST, true);
						 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						 echo var_dump($ch);
						 try {
						  $response = curl_exec($ch);
						  echo var_dump($ch);
						  curl_close($ch);
						  echo var_dump($response);
						  echo 'Message has been sent.';
						 }catch(Exception $e){
						  echo 'Message: ' .$e->getMessage();
						 }
						 
						 
						 //Message to Doctor
						 /* $ch = curl_init('http://bhashsms.com/api/sendmsg.php?'.$data);
						 echo var_dump($data);
						 curl_setopt($ch, CURLOPT_POST, true);
						 curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
						 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						 echo var_dump($ch);
						 try {
						  $response = curl_exec($ch);
						  echo var_dump($ch);
						  curl_close($ch);
						  echo var_dump($response);
						  echo 'Message has been sent.';
						 }catch(Exception $e){
						  echo 'Message: ' .$e->getMessage();
						 } */
}

function InsertEventFull()
 {
	global $new_multiple_value,$provider,$event_date,$duration,$recurrspec,$starttime,$endtime,$locationspec;
	// =======================================
	// multi providers case
	// =======================================
        if (is_array($_POST['form_provider'])) {

            // obtain the next available unique key to group multiple providers around some event
            $q = sqlStatement ("SELECT MAX(pc_multiple) as max FROM openemr_postcalendar_events");
            $max = sqlFetchArray($q);
            $new_multiple_value = $max['max'] + 1;

            foreach ($_POST['form_provider'] as $provider) {
                $args = $_POST;
                // specify some special variables needed for the INSERT
                $args['new_multiple_value'] = $new_multiple_value;
                $args['form_provider'] = $provider;
                $args['event_date'] = $event_date;
                $args['duration'] = $duration * 60;
                $args['recurrspec'] = $recurrspec;
                $args['starttime'] = $starttime;
                $args['endtime'] = $endtime;
                $args['locationspec'] = $locationspec;
                InsertEvent($args);
				SendSMS();
            }

        // ====================================
        // single provider
        // ====================================
        } else {
            $args = $_POST;
            // specify some special variables needed for the INSERT
            $args['new_multiple_value'] = "";
            $args['event_date'] = $event_date;
            $args['duration'] = $duration * 60;
            $args['recurrspec'] = $recurrspec;
            $args['starttime'] = $starttime;
            $args['endtime'] = $endtime;
            $args['locationspec'] = $locationspec;
            InsertEvent($args);
			SendSMS();
        }
 }
function DOBandEncounter()
 {
   global $event_date,$info_msg;
	 // Save new DOB if it's there.
	 $patient_dob = trim($_POST['form_dob']);
	 if ($patient_dob && $_POST['form_pid']) {
			 sqlStatement("UPDATE patient_data SET DOB = ? WHERE " .
									 "pid = ?", array($patient_dob,$_POST['form_pid']) );
	 }

	 // Auto-create a new encounter if appropriate.
	 //
	 if ($GLOBALS['auto_create_new_encounters'] && $_POST['form_apptstatus'] == '@' && $event_date == date('Y-m-d'))
	 {
		 $encounter = todaysEncounterCheck($_POST['form_pid'], $event_date, $_POST['form_comments'], $_POST['facility'], $_POST['billing_facility'], $_POST['form_provider'], $_POST['form_category'], false);
		 if($encounter){
				 $info_msg .= xl("New Visit created with id"); 
				 $info_msg .= " $encounter";
			}
		$cid=$_POST['form_category'];
		 if($cid!=5)
		{
		 $provider_id=$_POST['form_provider'];
		 $row1=sqlStatement("Select code,code_type,code_text,pr_price,username from codes a,prices b, users c where a.id=b.pr_id and a.code=c.username and c.id='".$provider_id."'");
		 $row2=  sqlFetchArray($row1);
		 $code=$row2['code'];
	     $codetext=$row2['code_text'];
	     $codetype="Doctor Charges";
		 $billed=0;
  	     $units=1;
  	     $fee=$row2['pr_price'];
	     $authrzd=1;
	     $modif="";
		 $act=1;
		 $grpn="Default";
		 $pid=$_POST['form_pid'];
		 $userid= $_SESSION['authUserID'];
		 if($cid==13)
		 {
			$codetype="Services"; 
			$code_text="CASUALTY CONSULTATION (DAY)"; 
			$code=$code_text;
			$fee=60;
		 }
		 if($cid==15)
		 {
			$codetype="Services"; 
			$code_text="CASUALTY CONSULTATION (NIGHT)"; 
			$code=$code_text;
			$fee=80;
		 }
		
    sqlInsert("INSERT INTO billing SET " .
      "date = '" . add_escape_custom($event_date) . "', " .
	  "user = '" . add_escape_custom($userid) . "', " .
      "bill_date = '" . add_escape_custom($event_date) . "', " .
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
		 
		 
		 }
	 }
 }
//================================================================================================================

// EVENTS TO FACILITIES (lemonsoftware)
//(CHEMED) get facility name
// edit event case - if there is no association made, then insert one with the first facility
if ( $eid ) {
    $selfacil = '';
    $facility = sqlQuery("SELECT pc_facility, pc_multiple, pc_aid, facility.name
                            FROM openemr_postcalendar_events
                              LEFT JOIN facility ON (openemr_postcalendar_events.pc_facility = facility.id)
                              WHERE pc_eid = ?", array($eid) );
    // if ( !$facility['pc_facility'] ) {
    if ( is_array($facility) && !$facility['pc_facility'] ) {
        $qmin = sqlQuery("SELECT facility_id as minId, facility FROM users WHERE id = ?", array($facility['pc_aid']) );
        $min  = $qmin['minId'];
        $min_name = $qmin['facility'];

        // multiple providers case
        if ( $GLOBALS['select_multi_providers'] ) {
            $mul  = $facility['pc_multiple'];
            sqlStatement("UPDATE openemr_postcalendar_events SET pc_facility = ? WHERE pc_multiple = ?", array($min,$mul) );
        }
        // EOS multiple

        sqlStatement("UPDATE openemr_postcalendar_events SET pc_facility = ? WHERE pc_eid = ?", array($min,$eid) );
        $e2f = $min;
        $e2f_name = $min_name;
    } else {
      // not edit event
      if (!$facility['pc_facility'] && $_SESSION['pc_facility']) {
        $e2f = $_SESSION['pc_facility'];
      } elseif (!$facility['pc_facility'] && $_COOKIE['pc_facility'] && $GLOBALS['set_facility_cookie']) {
	$e2f = $_COOKIE['pc_facility'];
      } else {
        $e2f = $facility['pc_facility'];
        $e2f_name = $facility['name'];
      }
    }
}
// EOS E2F
// ===========================
//=============================================================================================================================
if ($_POST['form_action'] == "duplicate" || $_POST['form_action'] == "save") 
 {
    // the starting date of the event, pay attention with this value
    // when editing recurring events -- JRM Oct-08
    $event_date = fixDate($_POST['form_date']);

    // Compute start and end time strings to be saved.
    if ($_POST['form_allday']) {
        $tmph = 0;
        $tmpm = 0;
        $duration = 24 * 60;
    } else {
        $tmph = $_POST['form_hour'] + 0;
        $tmpm = $_POST['form_minute'] + 0;
        if ($_POST['form_ampm'] == '2' && $tmph < 12) $tmph += 12;
        $duration = $_POST['form_duration'];
    }
    $starttime = "$tmph:$tmpm:00";
    //
    $tmpm += $duration;
    while ($tmpm >= 60) {
        $tmpm -= 60;
        ++$tmph;
    }
    $endtime = "$tmph:$tmpm:00";

    // Set up working variables related to repeated events.
    $my_recurrtype = 0;
    $my_repeat_freq = 0 + $_POST['form_repeat_freq'];
    $my_repeat_type = 0 + $_POST['form_repeat_type'];
    $my_repeat_on_num  = 1;
    $my_repeat_on_day  = 0;
    $my_repeat_on_freq = 0;
    if (!empty($_POST['form_repeat'])) {
      $my_recurrtype = 1;
      if ($my_repeat_type > 4) {
        $my_recurrtype = 2;
        $time = strtotime($event_date);
        $my_repeat_on_day = 0 + date('w', $time);
        $my_repeat_on_freq = $my_repeat_freq;
        if ($my_repeat_type == 5) {
          $my_repeat_on_num = intval((date('j', $time) - 1) / 7) + 1;
        }
        else {
          // Last occurence of this weekday on the month
          $my_repeat_on_num = 5;
        }
        // Maybe not needed, but for consistency with postcalendar:
        $my_repeat_freq = 0;
        $my_repeat_type = 0;
      }
    }

    // Useless garbage that we must save.
    $locationspecs = array("event_location" => "",
                            "event_street1" => "",
                            "event_street2" => "",
                            "event_city" => "",
                            "event_state" => "",
                            "event_postal" => ""
                        );
    $locationspec = serialize($locationspecs);

    // capture the recurring specifications
    $recurrspec = array("event_repeat_freq" => "$my_repeat_freq",
                        "event_repeat_freq_type" => "$my_repeat_type",
                        "event_repeat_on_num" => "$my_repeat_on_num",
                        "event_repeat_on_day" => "$my_repeat_on_day",
                        "event_repeat_on_freq" => "$my_repeat_on_freq",
                        "exdate" => $_POST['form_repeat_exdate']
                    );

    // no recurr specs, this is used for adding a new non-recurring event
    $noRecurrspec = array("event_repeat_freq" => "",
                        "event_repeat_freq_type" => "",
                        "event_repeat_on_num" => "1",
                        "event_repeat_on_day" => "0",
                        "event_repeat_on_freq" => "0",
                        "exdate" => ""
                    );

 }//if ($_POST['form_action'] == "duplicate" || $_POST['form_action'] == "save") 
//=============================================================================================================================
if ($_POST['form_action'] == "duplicate") {
	
	InsertEventFull();
	DOBandEncounter();

 }

// If we are saving, then save and close the window.
//
if ($_POST['form_action'] == "save") {
    /* =======================================================
     *                    UPDATE EVENTS
     * =====================================================*/
    if ($eid) {

        // what is multiple key around this $eid?
        $row = sqlQuery("SELECT pc_multiple FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid) );

        // ====================================
        // multiple providers
        // ====================================
        if ($GLOBALS['select_multi_providers'] && $row['pc_multiple']) {

            // obtain current list of providers regarding the multiple key
            $up = sqlStatement("SELECT pc_aid FROM openemr_postcalendar_events WHERE pc_multiple=?", array($row['pc_multiple']) );
            while ($current = sqlFetchArray($up)) { $providers_current[] = $current['pc_aid']; }

            // get the new list of providers from the submitted form
            $providers_new = $_POST['form_provider'];

            // ===== Only current event of repeating series =====
            if ($_POST['recurr_affect'] == 'current') {

                // update all existing event records to exlude the current date
                foreach ($providers_current as $provider) {
                    // update the provider's original event
                    // get the original event's repeat specs
                    $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array($provider,$row['pc_multiple']) );
                    $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                    $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                    if ($oldRecurrspec['exdate'] != "") { $oldRecurrspec['exdate'] .= ",".$selected_date; }
                    else { $oldRecurrspec['exdate'] .= $selected_date; }

                    // mod original event recur specs to exclude this date
                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        " pc_recurrspec = ? ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array(serialize($oldRecurrspec),$provider,$row['pc_multiple']) );
                }

                // obtain the next available unique key to group multiple providers around some event
                $q = sqlStatement ("SELECT MAX(pc_multiple) as max FROM openemr_postcalendar_events");
                $max = sqlFetchArray($q);
                $new_multiple_value = $max['max'] + 1;

                // insert a new event record for each provider selected on the form
                foreach ($providers_new as $provider) {
                    // insert a new event on this date with POST form data
                    $args = $_POST;
                    // specify some special variables needed for the INSERT
                    $args['new_multiple_value'] = $new_multiple_value;
                    $args['form_provider'] = $provider;
                    $args['event_date'] = $event_date;
                    $args['duration'] = $duration * 60;
                    // this event is forced to NOT REPEAT
                    $args['form_repeat'] = "0";
                    $args['recurrspec'] = $noRecurrspec;
                    $args['form_enddate'] = "0000-00-00";
                    $args['starttime'] = $starttime;
                    $args['endtime'] = $endtime;
                    $args['locationspec'] = $locationspec;
                    InsertEvent($args);
					SendSMS();
                }
            }

            // ===== Future Recurring events of a repeating series =====
            else if ($_POST['recurr_affect'] == 'future') {
                // update all existing event records to
                // stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                foreach ($providers_current as $provider) {
                    // mod original event recur specs to end on this date
                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        " pc_enddate = ? ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array($selected_date,$provider,$row['pc_multiple']) );
                }

                // obtain the next available unique key to group multiple providers around some event
                $q = sqlStatement ("SELECT MAX(pc_multiple) as max FROM openemr_postcalendar_events");
                $max = sqlFetchArray($q);
                $new_multiple_value = $max['max'] + 1;

                // insert a new event record for each provider selected on the form
                foreach ($providers_new as $provider) {
                    // insert a new event on this date with POST form data
                    $args = $_POST;
                    // specify some special variables needed for the INSERT
                    $args['new_multiple_value'] = $new_multiple_value;
                    $args['form_provider'] = $provider;
                    $args['event_date'] = $event_date;
                    $args['duration'] = $duration * 60;
                    $args['recurrspec'] = $recurrspec;
                    $args['starttime'] = $starttime;
                    $args['endtime'] = $endtime;
                    $args['locationspec'] = $locationspec;
                    InsertEvent($args);
					SendSMS();
                }
            }

            else {
                /* =================================================================== */
                // ===== a Single event or All events in a repeating series ==========
                /* =================================================================== */

                // this difference means that some providers from current was UNCHECKED
                // so we must delete this event for them
                $r1 = array_diff ($providers_current, $providers_new);
                if (count ($r1)) {
                    foreach ($r1 as $to_be_removed) {
                        sqlQuery("DELETE FROM openemr_postcalendar_events WHERE pc_aid=? AND pc_multiple=?", array($to_be_removed,$row['pc_multiple']) );
                    }
                }
    
                // perform a check to see if user changed event date
                // this is important when editing an existing recurring event
                // oct-08 JRM
                if ($_POST['form_date'] == $_POST['selected_date']) {
                    // user has NOT changed the start date of the event
                    $event_date = fixDate($_POST['event_start_date']);
                }

                // this difference means that some providers were added
                // so we must insert this event for them
                $r2 = array_diff ($providers_new, $providers_current);
                if (count ($r2)) {
                    foreach ($r2 as $to_be_inserted) {
                        $args = $_POST;
                        // specify some special variables needed for the INSERT
                        $args['new_multiple_value'] = $row['pc_multiple'];
                        $args['form_provider'] = $to_be_inserted;
                        $args['event_date'] = $event_date;
                        $args['duration'] = $duration * 60;
                        $args['recurrspec'] = $recurrspec;
                        $args['starttime'] = $starttime;
                        $args['endtime'] = $endtime;
                        $args['locationspec'] = $locationspec;
                        InsertEvent($args);
						SendSMS();
                    } 
                } 

                // after the two diffs above, we must update for remaining providers
                // those who are intersected in $providers_current and $providers_new
                foreach ($_POST['form_provider'] as $provider) {
                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        "pc_catid = '" . add_escape_custom($_POST['form_category']) . "', " .
                        "pc_pid = '" . add_escape_custom($_POST['form_pid']) . "', " .
                        "pc_title = '" . add_escape_custom($_POST['form_title']) . "', " .
                        "pc_time = NOW(), " .
                        "pc_hometext = '" . add_escape_custom($_POST['form_comments']) . "', " .
                        "pc_informant = '" . add_escape_custom($_SESSION['authUserID']) . "', " .
                        "pc_eventDate = '" . add_escape_custom($event_date) . "', " .
                        "pc_endDate = '" . add_escape_custom(fixDate($_POST['form_enddate'])) . "', " .
                        "pc_duration = '" . add_escape_custom(($duration * 60)) . "', " .
                        "pc_recurrtype = '" . add_escape_custom($my_recurrtype) . "', " .
                        "pc_recurrspec = '" . add_escape_custom(serialize($recurrspec)) . "', " .
                        "pc_startTime = '" . add_escape_custom($starttime) . "', " .
                        "pc_endTime = '" . add_escape_custom($endtime) . "', " .
                        "pc_alldayevent = '" . add_escape_custom($_POST['form_allday']) . "', " .
                        "pc_apptstatus = '" . add_escape_custom($_POST['form_apptstatus']) . "', "  .
                        "pc_prefcatid = '" . add_escape_custom($_POST['form_prefcat']) . "' ,"  .
                        "pc_facility = '" . add_escape_custom((int)$_POST['facility']) ."' ,"  . // FF stuff
                        "pc_billing_location = '" . add_escape_custom((int)$_POST['billing_facility']) ."' "  . 
                        "WHERE pc_aid = '" . add_escape_custom($provider) . "' AND pc_multiple = '" . add_escape_custom($row['pc_multiple'])  . "'");
                } // foreach
            }

        // ====================================
        // single provider
        // ====================================
        } elseif ( !$row['pc_multiple'] ) {
            if ( $GLOBALS['select_multi_providers'] ) {
                $prov = $_POST['form_provider'][0];
            } else {
                $prov =  $_POST['form_provider'];
            }

            if ($_POST['recurr_affect'] == 'current') {
                // get the original event's repeat specs
                $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid) );
                $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                if ($oldRecurrspec['exdate'] != "") { $oldRecurrspec['exdate'] .= ",".$selected_date; }
                else { $oldRecurrspec['exdate'] .= $selected_date; }

                // mod original event recur specs to exclude this date
                sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    " pc_recurrspec = ? ".
                    " WHERE pc_eid = ?", array(serialize($oldRecurrspec),$eid) );

                // insert a new event on this date with POST form data
                $args = $_POST;
                // specify some special variables needed for the INSERT
                $args['event_date'] = $event_date;
                $args['duration'] = $duration * 60;
                // this event is forced to NOT REPEAT
                $args['form_repeat'] = "0";
                $args['recurrspec'] = $noRecurrspec;
                $args['form_enddate'] = "0000-00-00";
                $args['starttime'] = $starttime;
                $args['endtime'] = $endtime;
                $args['locationspec'] = $locationspec;
                InsertEvent($args);
				SendSMS();
            }
            else if ($_POST['recurr_affect'] == 'future') {
                // mod original event to stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    " pc_enddate = ? ".
                    " WHERE pc_eid = ?", array($selected_date,$eid) );

                // insert a new event starting on this date with POST form data
                $args = $_POST;
                // specify some special variables needed for the INSERT
                $args['event_date'] = $event_date;
                $args['duration'] = $duration * 60;
                $args['recurrspec'] = $recurrspec;
                $args['starttime'] = $starttime;
                $args['endtime'] = $endtime;
                $args['locationspec'] = $locationspec;
                InsertEvent($args);
				SendSMS();
            }
            else {

    // perform a check to see if user changed event date
    // this is important when editing an existing recurring event
    // oct-08 JRM
    if ($_POST['form_date'] == $_POST['selected_date']) {
        // user has NOT changed the start date of the event
        $event_date = fixDate($_POST['event_start_date']);
    }

                // mod the SINGLE event or ALL EVENTS in a repeating series
                // simple provider case
                sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    "pc_catid = '" . add_escape_custom($_POST['form_category']) . "', " .
                    "pc_aid = '" . add_escape_custom($prov) . "', " .
                    "pc_pid = '" . add_escape_custom($_POST['form_pid']) . "', " .
                    "pc_title = '" . add_escape_custom($_POST['form_title']) . "', " .
                    "pc_time = NOW(), " .
                    "pc_hometext = '" . add_escape_custom($_POST['form_comments']) . "', " .
                    "pc_informant = '" . add_escape_custom($_SESSION['authUserID']) . "', " .
                    "pc_eventDate = '" . add_escape_custom($event_date) . "', " .
                    "pc_endDate = '" . add_escape_custom(fixDate($_POST['form_enddate'])) . "', " .
                    "pc_duration = '" . add_escape_custom(($duration * 60)) . "', " .
                    "pc_recurrtype = '" . add_escape_custom($my_recurrtype) . "', " .
                    "pc_recurrspec = '" . add_escape_custom(serialize($recurrspec)) . "', " .
                    "pc_startTime = '" . add_escape_custom($starttime) . "', " .
                    "pc_endTime = '" . add_escape_custom($endtime) . "', " .
                    "pc_alldayevent = '" . add_escape_custom($_POST['form_allday']) . "', " .
                    "pc_apptstatus = '" . add_escape_custom($_POST['form_apptstatus']) . "', "  .
                    "pc_prefcatid = '" . add_escape_custom($_POST['form_prefcat']) . "' ,"  .
                    "pc_facility = '" . add_escape_custom((int)$_POST['facility']) ."' ,"  . // FF stuff
                    "pc_billing_location = '" . add_escape_custom((int)$_POST['billing_facility']) ."' "  . 
                    "WHERE pc_eid = '" . add_escape_custom($eid) . "'");
            }
        }

        // =======================================
        // end Update Multi providers case
        // =======================================

        // EVENTS TO FACILITIES
        $e2f = (int)$eid;


    } else {
        /* =======================================================
         *                    INSERT NEW EVENT(S)
         * ======================================================*/

		InsertEventFull();
		
    }

    // done with EVENT insert/update statements

		DOBandEncounter();
		
 }

// =======================================
//    DELETE EVENT(s)
// =======================================
 else if ($_POST['form_action'] == "delete") {
        // =======================================
        //  multi providers event
        // =======================================
        if ($GLOBALS['select_multi_providers']) {

            // what is multiple key around this $eid?
            $row = sqlQuery("SELECT pc_multiple FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid) );

            // obtain current list of providers regarding the multiple key
            $providers_current = array();
            $up = sqlStatement("SELECT pc_aid FROM openemr_postcalendar_events WHERE pc_multiple=?", array($row['pc_multiple']) );
            while ($current = sqlFetchArray($up)) { $providers_current[] = $current['pc_aid']; }

            // establish a WHERE clause
            if ( $row['pc_multiple'] ) { $whereClause = "pc_multiple = '{$row['pc_multiple']}'"; }
            else { $whereClause = "pc_eid = '$eid'"; }

            if ($_POST['recurr_affect'] == 'current') {
                // update all existing event records to exlude the current date
                foreach ($providers_current as $provider) {
                    // update the provider's original event
                    // get the original event's repeat specs
                    $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events ".
                        " WHERE pc_aid = ? AND pc_multiple=?", array($provider,$row['pc_multiple']) );
                    $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                    $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                    if ($oldRecurrspec['exdate'] != "") { $oldRecurrspec['exdate'] .= ",".$selected_date; }
                    else { $oldRecurrspec['exdate'] .= $selected_date; }

                    // mod original event recur specs to exclude this date
                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        " pc_recurrspec = ? ".
                        " WHERE ". $whereClause, array(serialize($oldRecurrspec)) );
                }
            }
            else if ($_POST['recurr_affect'] == 'future') {
                // update all existing event records to stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                foreach ($providers_current as $provider) {
                    // update the provider's original event
                    sqlStatement("UPDATE openemr_postcalendar_events SET " .
                        " pc_enddate = ? ".
                        " WHERE ".$whereClause, array($selected_date) );
                }
            }
            else {
                // really delete the event from the database
                sqlStatement("DELETE FROM openemr_postcalendar_events WHERE ".$whereClause);
            }
        }

        // =======================================
        //  single provider event
        // =======================================
        else {

            if ($_POST['recurr_affect'] == 'current') {
                // mod original event recur specs to exclude this date

                // get the original event's repeat specs
                $origEvent = sqlQuery("SELECT pc_recurrspec FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid) );
                $oldRecurrspec = unserialize($origEvent['pc_recurrspec']);
                $selected_date = date("Ymd", strtotime($_POST['selected_date']));
                if ($oldRecurrspec['exdate'] != "") { $oldRecurrspec['exdate'] .= ",".$selected_date; }
                else { $oldRecurrspec['exdate'] .= $selected_date; }
                sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    " pc_recurrspec = ? ".
                    " WHERE pc_eid = ?", array(serialize($oldRecurrspec),$eid) );
            }

            else if ($_POST['recurr_affect'] == 'future') {
                // mod original event to stop recurring on this date-1
                $selected_date = date("Ymd", (strtotime($_POST['selected_date'])-24*60*60));
                sqlStatement("UPDATE openemr_postcalendar_events SET " .
                    " pc_enddate = ? ".
                    " WHERE pc_eid = ?", array($selected_date,$eid) );
            }

            else {
                // fully delete the event from the database
                sqlStatement("DELETE FROM openemr_postcalendar_events WHERE pc_eid = ?", array($eid) );
            }
        }
 }

 if ($_POST['form_action'] != "") {
  // Close this window and refresh the calendar display.
  echo "<html>\n<body>\n<script language='JavaScript'>\n";
  if ($info_msg) echo " alert('" . addslashes($info_msg) . "');\n";
  echo " if (opener && !opener.closed && opener.refreshme) opener.refreshme();\n";
  echo " window.close();\n";
  echo "</script>\n</body>\n</html>\n";
  exit();
 }

 //*********************************
 // If we get this far then we are displaying the form.
 //*********************************

/*********************************************************************
        This has been migrate to the administration->lists
 $statuses = array(
  '-' => '',
  '*' => xl('* Reminder done'),
  '+' => xl('+ Chart pulled'),
  'x' => xl('x Cancelled'), // added Apr 2008 by JRM
  '?' => xl('? No show'),
  '@' => xl('@ Arrived'),
  '~' => xl('~ Arrived late'),
  '!' => xl('! Left w/o visit'),
  '#' => xl('# Ins/fin issue'),
  '<' => xl('< In exam room'),
  '>' => xl('> Checked out'),
  '$' => xl('$ Coding done'),
   '%' => xl('% Cancelled <  24h ')
 );
*********************************************************************/

 $repeats = 0; // if the event repeats
 $repeattype = '0';
 $repeatfreq = '0';
 $patientid = '';
 if ($_REQUEST['patientid']) $patientid = $_REQUEST['patientid'];
 $patientname = xl('Click to select');
 $patienttitle = "";
 $hometext = "";
 $row = array();
 $informant = "";

 // If we are editing an existing event, then get its data.
 if ($eid) {
  // $row = sqlQuery("SELECT * FROM openemr_postcalendar_events WHERE pc_eid = $eid");

  $row = sqlQuery("SELECT e.*, u.fname, u.mname, u.lname " .
    "FROM openemr_postcalendar_events AS e " .
    "LEFT OUTER JOIN users AS u ON u.id = e.pc_informant " .
    "WHERE pc_eid = ?", array($eid) );
  $informant = $row['fname'] . ' ' . $row['mname'] . ' ' . $row['lname'];

  // instead of using the event's starting date, keep what has been provided
  // via the GET array, see the top of this file
  if (empty($_GET['date'])) $date = $row['pc_eventDate'];
  $eventstartdate = $row['pc_eventDate']; // for repeating event stuff - JRM Oct-08
  $userid = $row['pc_aid'];
  $patientid = $row['pc_pid'];
  $starttimeh = substr($row['pc_startTime'], 0, 2) + 0;
  $starttimem = substr($row['pc_startTime'], 3, 2);
  $repeats = $row['pc_recurrtype'];
  $multiple_value = $row['pc_multiple'];

  // parse out the repeating data, if any
  $rspecs = unserialize($row['pc_recurrspec']); // extract recurring data
  $repeattype = $rspecs['event_repeat_freq_type'];
  $repeatfreq = $rspecs['event_repeat_freq'];
  $repeatexdate = $rspecs['exdate']; // repeating date exceptions

  // Adjustments for repeat type 2, a particular weekday of the month.
  if ($repeats == 2) {
    $repeatfreq = $rspecs['event_repeat_on_freq'];
    if ($rspecs['event_repeat_on_num'] < 5) {
      $repeattype = 5;
    }
    else {
      $repeattype = 6;
    }
  }

  $hometext = $row['pc_hometext'];
  if (substr($hometext, 0, 6) == ':text:') $hometext = substr($hometext, 6);
 }
 else {
    // a NEW event
    $eventstartdate = $date; // for repeating event stuff - JRM Oct-08
 
    //-------------------------------------
    //(CHEMED)
    //Set default facility for a new event based on the given 'userid'
    if ($userid) {
        /*************************************************************
        $pref_facility = sqlFetchArray(sqlStatement("SELECT facility_id, facility FROM users WHERE id = $userid"));
        *************************************************************/
        if ($_SESSION['pc_facility']) {
	        $pref_facility = sqlFetchArray(sqlStatement("
		        SELECT f.id as facility_id,
		        f.name as facility
		        FROM facility f
		        WHERE f.id = ?
	          ",
		        array($_SESSION['pc_facility'])
	          ));	
        } else {
          $pref_facility = sqlFetchArray(sqlStatement("
            SELECT u.facility_id, 
	          f.name as facility 
            FROM users u
            LEFT JOIN facility f on (u.facility_id = f.id)
            WHERE u.id = ?
            ", array($userid) ));
        }
        /************************************************************/
        $e2f = $pref_facility['facility_id'];
        $e2f_name = $pref_facility['facility'];
    }
    //END of CHEMED -----------------------
 }

 // If we have a patient ID, get the name and phone numbers to display.
 if ($patientid) {
  $prow = sqlQuery("SELECT lname, fname, phone_home, phone_biz, DOB " .
   "FROM patient_data WHERE pid = ?", array($patientid) );
  $patientname = $prow['fname'] . " " . $prow['lname'];
  if ($prow['phone_home']) $patienttitle .= " H=" . $prow['phone_home'];
  if ($prow['phone_biz']) $patienttitle  .= " W=" . $prow['phone_biz'];
 }

 // Get the providers list.
 $ures = sqlStatement("SELECT id, username, fname, lname FROM users WHERE " .
  "authorized != 0 AND active = 1 ORDER BY fname, lname");

 // Get event categories.
 $cres = sqlStatement("SELECT pc_catid, pc_catname, pc_recurrtype, pc_duration, pc_end_all_day " .
  "FROM openemr_postcalendar_categories ORDER BY pc_catname");

 // Fix up the time format for AM/PM.
 $startampm = '1';
 if ($starttimeh >= 12) { // p.m. starts at noon and not 12:01
  $startampm = '2';
  if ($starttimeh > 12) $starttimeh -= 12;
 }

?>
<html>
<head>
<?php html_header_show(); ?>
<title><?php echo $eid ? xlt('Edit') : xlt('Add New') ?> <?php echo xlt('Event');?></title>
<link rel="stylesheet" href='<?php echo $css_header ?>' type='text/css'>

<style>
td { font-size:0.8em; }
</style>

<style type="text/css">@import url(../../../library/dynarch_calendar.css);</style>
<script type="text/javascript" src="../../../library/topdialog.js"></script>
<script type="text/javascript" src="../../../library/dialog.js"></script>
<script type="text/javascript" src="../../../library/textformat.js"></script>
<script type="text/javascript" src="../../../library/dynarch_calendar.js"></script>
<?php include_once("{$GLOBALS['srcdir']}/dynarch_calendar_en.inc.php"); ?>
<script type="text/javascript" src="../../../library/dynarch_calendar_setup.js"></script>

<script language="JavaScript">

 var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

 var durations = new Array();
 // var rectypes  = new Array();
<?php
 // Read the event categories, generate their options list, and get
 // the default event duration from them if this is a new event.
 $cattype=0;
 if($_GET['prov']==true){
  $cattype=1;
 }
 $cres = sqlStatement("SELECT pc_catid, pc_cattype, pc_catname, " .
  "pc_recurrtype, pc_duration, pc_end_all_day " .
  "FROM openemr_postcalendar_categories ORDER BY pc_catname");
 $catoptions = "";
 $prefcat_options = "    <option value='0'>-- " . xlt("None") . " --</option>\n";
 $thisduration = 0;
 if ($eid) {
  $thisduration = $row['pc_alldayevent'] ? 1440 : round($row['pc_duration'] / 60);
 }
 while ($crow = sqlFetchArray($cres)) {
  $duration = round($crow['pc_duration'] / 60);
  if ($crow['pc_end_all_day']) $duration = 1440;

  // This section is to build the list of preferred categories:
  if ($duration) {
   $prefcat_options .= "    <option value='" . attr($crow['pc_catid']) . "'";
   if ($eid) {
    if ($crow['pc_catid'] == $row['pc_prefcatid']) $prefcat_options .= " selected";
   }
   $prefcat_options .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
  }

  if ($crow['pc_cattype'] != $cattype) continue;

  echo " durations[" . attr($crow['pc_catid']) . "] = " . attr($duration) . "\n";
  // echo " rectypes[" . $crow['pc_catid'] . "] = " . $crow['pc_recurrtype'] . "\n";
  $catoptions .= "    <option value='" . attr($crow['pc_catid']) . "'";
  if ($eid) {
   if ($crow['pc_catid'] == $row['pc_catid']) $catoptions .= " selected";
  } else {
   if ($crow['pc_catid'] == $default_catid) {
    $catoptions .= " selected";
    $thisduration = $duration;
   }
  }
  $catoptions .= ">" . text(xl_appt_category($crow['pc_catname'])) . "</option>\n";
 }
?>

<?php require($GLOBALS['srcdir'] . "/restoreSession.php"); ?>

 // This is for callback by the find-patient popup.
 function setpatient(pid, lname, fname, dob) {
  var f = document.forms[0];
  f.form_patient.value = fname + ' ' + lname;
  f.form_pid.value = pid;
  dobstyle = (dob == '' || dob.substr(5, 10) == '00-00') ? '' : 'none';
  document.getElementById('dob_row').style.display = dobstyle;
 }

 // This invokes the find-patient popup.
 function sel_patient() {
  dlgopen('find_patient_popup.php', '_blank', 500, 400);
 }

 // Do whatever is needed when a new event category is selected.
 // For now this means changing the event title and duration.
 function set_display() {
  var f = document.forms[0];
  var s = f.form_category;
  if (s.selectedIndex >= 0) {
   var catid = s.options[s.selectedIndex].value;
   var style_apptstatus = document.getElementById('title_apptstatus').style;
   var style_prefcat = document.getElementById('title_prefcat').style;
   if (catid == '2') { // In Office
    style_apptstatus.display = 'none';
    style_prefcat.display = '';
    f.form_apptstatus.style.display = 'none';
    f.form_prefcat.style.display = '';
   } else {
    style_prefcat.display = 'none';
    style_apptstatus.display = '';
    f.form_prefcat.style.display = 'none';
    f.form_apptstatus.style.display = '';
   }
  }
 }

 // Do whatever is needed when a new event category is selected.
 // For now this means changing the event title and duration.
 function set_category() {
  var f = document.forms[0];
  var s = f.form_category;
  if (s.selectedIndex >= 0) {
   var catid = s.options[s.selectedIndex].value;
   f.form_title.value = s.options[s.selectedIndex].text;
   f.form_duration.value = durations[catid];
   set_display();
  }
 }

 // Modify some visual attributes when the all-day or timed-event
 // radio buttons are clicked.
 function set_allday() {
  var f = document.forms[0];
  var color1 = '#777777';
  var color2 = '#777777';
  var disabled2 = true;
  if (document.getElementById('rballday1').checked) {
   color1 = '#000000';
  }
  if (document.getElementById('rballday2').checked) {
   color2 = '#000000';
   disabled2 = false;
  }
  document.getElementById('tdallday1').style.color = color1;
  document.getElementById('tdallday2').style.color = color2;
  document.getElementById('tdallday3').style.color = color2;
  document.getElementById('tdallday4').style.color = color2;
  document.getElementById('tdallday5').style.color = color2;
  f.form_hour.disabled     = disabled2;
  f.form_minute.disabled   = disabled2;
  f.form_ampm.disabled     = disabled2;
  f.form_duration.disabled = disabled2;
 }

 // Modify some visual attributes when the Repeat checkbox is clicked.
 function set_repeat() {
  var f = document.forms[0];
  var isdisabled = true;
  var mycolor = '#777777';
  var myvisibility = 'hidden';
  if (f.form_repeat.checked) {
   isdisabled = false;
   mycolor = '#000000';
   myvisibility = 'visible';
  }
  f.form_repeat_type.disabled = isdisabled;
  f.form_repeat_freq.disabled = isdisabled;
  f.form_enddate.disabled = isdisabled;
  document.getElementById('tdrepeat1').style.color = mycolor;
  document.getElementById('tdrepeat2').style.color = mycolor;
  document.getElementById('img_enddate').style.visibility = myvisibility;
 }

 // Constants used by dateChanged() function.
 var occurNames = new Array(
  '<?php echo xls("1st"); ?>',
  '<?php echo xls("2nd"); ?>',
  '<?php echo xls("3rd"); ?>',
  '<?php echo xls("4th"); ?>'
 );

 // Monitor start date changes to adjust repeat type options.
 function dateChanged() {
  var f = document.forms[0];
  if (!f.form_date.value) return;
  var d = new Date(f.form_date.value);
  var downame = Calendar._DN[d.getUTCDay()];
  var nthtext = '';
  var occur = Math.floor((d.getUTCDate() - 1) / 7);
  if (occur < 4) { // 5th is not allowed
   nthtext = occurNames[occur] + ' ' + downame;
  }
  var lasttext = '';
  var tmp = new Date(d.getUTCFullYear(), d.getUTCMonth() + 1, 0);
  if (tmp.getUTCDate() - d.getUTCDate() < 7) {
   // This is a last occurrence of the specified weekday in the month,
   // so permit that as an option.
   lasttext = '<?php echo xls("Last"); ?> ' + downame;
  }
  var si = f.form_repeat_type.selectedIndex;
  var opts = f.form_repeat_type.options;
  opts.length = 5; // remove any nth and Last entries
  if (nthtext ) opts[opts.length] = new Option(nthtext , '5');
  if (lasttext) opts[opts.length] = new Option(lasttext, '6');
  if (si < opts.length) f.form_repeat_type.selectedIndex = si;
 }

 // This is for callback by the find-available popup.
 function setappt(year,mon,mday,hours,minutes) {
  var f = document.forms[0];
  f.form_date.value = '' + year + '-' +
   ('' + (mon  + 100)).substring(1) + '-' +
   ('' + (mday + 100)).substring(1);
  f.form_ampm.selectedIndex = (hours >= 12) ? 1 : 0;
  f.form_hour.value = (hours > 12) ? hours - 12 : hours;
  f.form_minute.value = ('' + (minutes + 100)).substring(1);
 }

    // Invoke the find-available popup.
    function find_available(extra) {
        top.restoreSession();
        // (CHEMED) Conditional value selection, because there is no <select> element
        // when making an appointment for a specific provider
        var s = document.forms[0].form_provider;
        var f = document.forms[0].facility;
        <?php if ($userid != 0) { ?>
            s = document.forms[0].form_provider.value;
            f = document.forms[0].facility.value;
        <?php } else {?>
            s = document.forms[0].form_provider.options[s.selectedIndex].value;
            f = document.forms[0].facility.options[f.selectedIndex].value;
        <?php }?>
        var c = document.forms[0].form_category;
	var formDate = document.forms[0].form_date;
        dlgopen('<?php echo $GLOBALS['web_root']; ?>/interface/main/calendar/find_appt_popup.php' +
                '?providerid=' + s +
                '&catid=' + c.options[c.selectedIndex].value +
                '&facility=' + f +
                '&startdate=' + formDate.value +
                '&evdur=' + document.forms[0].form_duration.value +
                '&eid=<?php echo 0 + $eid; ?>' +
                extra,
                '_blank', 500, 400);
    }

</script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

</head>

<body class="body_top" onunload='imclosing()'>
<form>
<?php 
$today=date('Y-m-d');
//,date_format(pc_eventDate,'%y-%m-%d')
$query=sqlStatement("select username,pc_startTime,pc_pid from openemr_postcalendar_events e,users u where u.id=e.pc_aid and e.pc_eventDate='".$today."'");
while($res=sqlFetchArray($query)){
$pid=$res['pc_pid'];	
$pn=getPatientData($pid, "phone_cell");
$pn_no=$pn['phone_cell'];
$time=$res['pc_startTime'];	
$un=$res['username'];
SendSMS($time,$today,$un,$pn); 
}?>
</form>
</body>

<script language='JavaScript'>
<?php if ($eid) { ?>
 set_display();
<?php } else { ?>
 set_category();
<?php } ?>
 set_allday();
 set_repeat();

 Calendar.setup({inputField:"form_date", ifFormat:"%Y-%m-%d", button:"img_date"});
 Calendar.setup({inputField:"form_enddate", ifFormat:"%Y-%m-%d", button:"img_enddate"});
 Calendar.setup({inputField:"form_dob", ifFormat:"%Y-%m-%d", button:"img_dob"});
</script>

<script language="javascript">
// jQuery stuff to make the page a little easier to use

$(document).ready(function(){
    $("#form_save").click(function() { validate("save"); });
    $("#form_duplicate").click(function() { validate("duplicate"); });
    $("#find_available").click(function() { find_available(''); });
    $("#form_delete").click(function() { deleteEvent(); });
    $("#cancel").click(function() { window.close(); });

    // buttons affecting the modification of a repeating event
    $("#all_events").click(function() { $("#recurr_affect").val("all"); EnableForm(); SubmitForm(); });
    $("#future_events").click(function() { $("#recurr_affect").val("future"); EnableForm(); SubmitForm(); });
    $("#current_event").click(function() { $("#recurr_affect").val("current"); EnableForm(); SubmitForm(); });
    $("#recurr_cancel").click(function() { $("#recurr_affect").val(""); EnableForm(); HideRecurrPopup(); });

    // Initialize repeat options.
    dateChanged();
});

// Check for errors when the form is submitted.
function validate(valu) {
     var f = document.getElementById('theform');
    if (f.form_repeat.checked &&
        (! f.form_enddate.value || f.form_enddate.value < f.form_date.value)) {
        alert('<?php echo addslashes(xl("An end date later than the start date is required for repeated events!")); ?>');
        return false;
    }
    <?php
    if($_GET['prov']!=true){
    ?>
     if(f.form_pid.value == ''){
      alert('<?php echo addslashes(xl('Patient Name Required'));?>');
      return false;
     }
    <?php
    }
    ?>
    $('#form_action').val(valu);

    <?php if ($repeats): ?>
    // existing repeating events need additional prompt
    if ($("#recurr_affect").val() == "") {
        DisableForm();
        // show the current/future/all DIV for the user to choose one
        $("#recurr_popup").css("visibility", "visible");
        return false;
    }
    <?php endif; ?>

    return SubmitForm();
}

// disable all the form elements outside the recurr_popup
function DisableForm() {
    $("#theform").children().attr("disabled", "true");
}
function EnableForm() {
    $("#theform").children().removeAttr("disabled");
}
// hide the recurring popup DIV
function HideRecurrPopup() {
    $("#recurr_popup").css("visibility", "hidden");
}

function deleteEvent() {
    if (confirm("<?php echo addslashes(xl('Deleting this event cannot be undone. It cannot be recovered once it is gone. Are you sure you wish to delete this event?')); ?>")) {
        $('#form_action').val("delete");

        <?php if ($repeats): ?>
        // existing repeating events need additional prompt
        if ($("#recurr_affect").val() == "") {
            DisableForm();
            // show the current/future/all DIV for the user to choose one
            $("#recurr_popup").css("visibility", "visible");
            return false;
        }
        <?php endif; ?>

        return SubmitForm();
    }
    return false;
}

function SubmitForm() {
 var f = document.forms[0];
 <?php if (!($GLOBALS['select_multi_providers'])) { // multi providers appt is not supported by check slot avail window, so skip ?>
  if (f.form_action.value != 'delete') {
    // Check slot availability.
    var mins = parseInt(f.form_hour.value) * 60 + parseInt(f.form_minute.value);
    if (f.form_ampm.value == '2' && mins < 720) mins += 720;
    find_available('&cktime=' + mins);
  }
  else {
    top.restoreSession();
    f.submit();
  }
 <?php } else { ?>
  top.restoreSession();
  f.submit();
 <?php } ?>

  return true;
}

</script>
   
</html>
