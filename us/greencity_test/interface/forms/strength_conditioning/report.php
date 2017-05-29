<?php
// Copyright (C) 2006-2008 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

include_once("../../globals.php");
include_once($GLOBALS["srcdir"] . "/api.inc");

function strength_conditioning_report($pid, $encounter, $cols, $id) {
 /****
 $cols = 1; // force always 1 column
 $count = 0;
 $data = sqlQuery("SELECT * " .
  "FROM form_treatment_protocols WHERE " .
  "id = '$id'");
 if ($data) {
  print "<table cellpadding='0' cellspacing='0'>\n<tr>\n";
  foreach($data as $key => $value) {
   if ($key == "id" || $key == "pid" || $key == "user" || $key == "groupname" ||
       $key == "authorized" || $key == "activity" || $key == "date" ||
       $value == "" || $value == "0" || $value == "0.00") {
    continue;
   }

   if ($key == 'followup_required') {
    switch ($value) {
     case '1': $value = 'Yes'; break;
     case '2': $value = 'Pending investigation'; break;
    }
   }

   $key=ucwords(str_replace("_"," ",$key));
   print "<td valign='top'><span class='bold'>$key: </span><span class='text'>$value &nbsp;</span></td>\n";
   $count++;
   if ($count == $cols) {
    $count = 0;
    print "</tr>\n<tr>\n";
   }
  }
  print "</tr>\n</table>\n";
 }
 ****/
}
?>
