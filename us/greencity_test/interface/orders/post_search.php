<?php

require_once("../globals.php");
require_once($GLOBALS['srcdir'].'/patient.inc');
require_once($GLOBALS['srcdir'].'/csv_like_join.php');
require_once($GLOBALS['fileroot'].'/custom/code_types.inc.php');


$info_msg = "";
$codetype = $_REQUEST['codetype'];
if (!empty($codetype)) {
	$allowed_codes = split_csv_line($codetype);
}

$form_code_type = 'ICD9';

// Determine which code type will be selected by default.
$default = '';
if (!empty($form_code_type)) {
  $default = $form_code_type;
}
else if (!empty($allowed_codes) && count($allowed_codes) == 1) {
  $default = $allowed_codes[0];
}
else if (!empty($_REQUEST['default'])) {
  $default = $_REQUEST['default'];
}

// This variable is used to store the html element
// of the target script where the selected code
// will be stored in.
$target_element = $_GET['target_element'];

if($codetype) {
  $search_term = $_REQUEST['keyword'];
$res = main_code_set_search($form_code_type,$search_term);

  while ($row = sqlFetchArray($res)) {

	      $itercode = $row['code'];
      $itertext = trim($row['code_text']);
	      if (!empty($target_element)) {
        // add a 5th parameter to function to select the target element on the form for placing the code.
        $anchor = "<a href='' " .
          "onclick='return selcode_target(\"" . attr(addslashes($form_code_type)) . "\", \"" . attr(addslashes($itercode)) . "\", \"\", \"" . attr(addslashes($itertext)) . "\", \"" . attr(addslashes($target_element)) . "\")'>";
      }
      else {
        $anchor = "<a href='' " .
          "onclick='return selcode(\"" . attr(addslashes($form_code_type)) . "\", \"" . attr(addslashes($itercode)) . "\", \"\", \"" . attr(addslashes($itertext)) . "\")'>";
      }
	$iterqo = $itercode.",".$itertext;

	  $click = "<li onClick='selectCountry(" ."\"" .text($iterqo)."\");'>";
    echo "  <ul id='country-list'>$click" . text($itercode) . "";
    echo " " . text($itertext) . "</li></ul>\n";

  }
  }
  else {
	  echo "";
  }
?>