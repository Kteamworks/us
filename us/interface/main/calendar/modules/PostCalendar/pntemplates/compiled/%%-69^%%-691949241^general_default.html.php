<?php /* Smarty version 2.6.2, created on 2017-03-30 08:41:21
         compiled from C:%5Cxampp%5Chtdocs%5CKGreencity_Base1%5Cgreencity%5Cinterface%5Creports/../../templates/report/general_default.html */ ?>
<?php require_once(SMARTY_DIR . 'core' . DIRECTORY_SEPARATOR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'xl', 'C:\xampp\htdocs\KGreencity_Base1\greencity\interface\reports/../../templates/report/general_default.html', 90, false),array('function', 'html_options', 'C:\xampp\htdocs\KGreencity_Base1\greencity\interface\reports/../../templates/report/general_default.html', 177, false),array('modifier', 'date_format', 'C:\xampp\htdocs\KGreencity_Base1\greencity\interface\reports/../../templates/report/general_default.html', 187, false),)), $this); ?>
<html>
<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['css_header']; ?>
" type="text/css">
<?php echo '

<script language="JavaScript">

function clear_vars() {
  document.report.var1.value = "";
  document.report.var2.value = "";
}

function dopopup(aurl) {
 top.restoreSession();
 window.open(aurl, \'_blank\', \'width=750,height=550,resizable=1,scrollbars=1\');
}

</script>

'; ?>

</head>
<body bgcolor="<?php echo $this->_tpl_vars['STYLE']['BGCOLOR2']; ?>
">

<div id="reports_list">
<?php 
//get directories that might contain reports 
$reportsdir = './myreports/';
if (is_dir($reportsdir)) {
  $folder_array = array();
  if ($handle = opendir($reportsdir)) {
      while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..' && is_dir($reportsdir.$file)) {
          $folder_array[$file] = array();
        }
      }
      closedir($handle);
  }
  
  //fill elements of $folder_array with the php files in each directory
  foreach ($folder_array as $key => $val) {
    $reportsubdir = $reportsdir.$key.'/';
    if ($handle = opendir($reportsubdir)) {
        while (false !== ($file = readdir($handle))) {
          if ($file != '.' && $file != '..' && substr($file,-4,4) == '.php') {
          //if ($file != '.' && $file != '..') {
            $filename = substr($file,0,strlen($file)-4);
            $folder_array[$key][$filename] = $GLOBALS['webroot'].'/interface/reports/myreports/'.$key.'/'.$file;
          }
        }
        closedir($handle);
    }
  }
  //generate drop down menus
  echo "<FORM METHOD=POST NAME=choose>\n";
  foreach ($folder_array as $title => $link) {
    if (count($link) > 0) { //only create menus for those subdirectories with php reports in them
       echo "<select name=" . $title. " onchange='top.restoreSession();window.open(document.choose.".$title.".options[document.choose.".$title.".selectedIndex].value);".$title.".selectedIndex = 0'>\n";
       echo "<option value=".$GLOBALS['webroot'].'/interface/reports/myreports/'.$title.">".xl($title)."</option>\n";
       foreach ($link as $eachlink_name => $eachlink) {
         echo "<option value='".$eachlink."'>".xl($eachlink_name)."</option>\n";
       }
       echo "</select><br>\n";
    }
  }
  echo "</FORM>\n";
  
  //now deal with the reports that are just under myreports, not organized into subdirectories
  $reportsdir = './myreports/';
  $dir_array = array();
  if ($handle = opendir($reportsdir)) {
      while (false !== ($file = readdir($handle))) {
        if ($file != '.' && $file != '..' && substr($file,-4,4) == '.php') {
          $filename = substr($file,0,strlen($file)-4);
          array_push($dir_array,"<a href='$reportsdir$file' target='_blank' onclick='top.restoreSession()'>".xl($filename)."</a><br>\n");
        }
      }
      closedir($handle);
  }
  //print the links for reports under myreports
  foreach($dir_array as $var) {
    echo $var;
  }
}
 ?>

<ul>
<li>
<a href="custom_report_range.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Superbill Report'), $this);?>
</a>
</li><li>
<a href="appointments_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Appointments Report'), $this);?>
</a>
</li><li>
<a href="encounters_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Encounters Report'), $this);?>
</a>
<br/>
</li><li>
<a href="appt_encounter_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Appointments-Encounters Report'), $this);?>
</a>
</li>
<?php  if (! $GLOBALS['simplified_demographics']) {  ?>
<li>
<a href="insurance_allocation_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Patient Insurance Distribution Report'), $this);?>
</a>
</li><li>
<a href="../billing/indigent_patients_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Indigent Patients Report'), $this);?>
</a>
</li><li>
<a href="unique_seen_patients_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Unique Seen Patients Report'), $this);?>
</a>
</li><li>
<a href="patient_list.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Patient List'), $this);?>
</a>
</li>
<?php  }  ?>

<?php  if (! $GLOBALS['weight_loss_clinic']) {  ?>
<li>
<a href="front_receipts_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Front Office Receipts Report'), $this);?>
</a>
</li>
<?php  }  ?>

<li>
<a href="prescriptions_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Prescriptions Report'), $this);?>
</a>
</li><li>
<a href="sales_by_item.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Sales by Product Report'), $this);?>
</a>
</li><li>
<a href="collections_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Collections Report'), $this);?>
</a>
</li><li>
<a href="referrals_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Referrals Report'), $this);?>
</a>
</li><li>
<a href="non_reported.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Non Reported Report'), $this);?>
</a>
</li>

<?php  if ($GLOBALS['inhouse_pharmacy']) {  ?>
<li>
<a href="destroyed_drugs_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Destroyed Drugs Report'), $this);?>
</a>
</li>
<?php  }  ?>
<li>
<a href="receipts_by_method_report.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Receipts by Payment Method Report'), $this);?>
</a>
</li>
<!-- </ul> -->
<li>
<a href="chart_location_activity.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Chart Check-in/out Activity Report'), $this);?>
</a>
</li>
<li>
<a href="charts_checked_out.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Charts Checked Out'), $this);?>
</a>
</li>
<li>
<a href="services_by_category.php" target="_blank" onclick="top.restoreSession()"><?php echo smarty_function_xl(array('t' => 'Services by Category'), $this);?>
</a>
</li>
<?php 
 if ($GLOBALS['athletic_team']) {
  echo "<li>\n";
  echo "<a href='javascript:dopopup(\"players_report.php\")'>Team Roster</a>\n";
  echo "</li><li>\n";
  echo "<a href='javascript:dopopup(\"absences_report.php\")'>Days and Games Missed</a>\n";
  echo "</li><li>\n";
  echo "<a href='javascript:dopopup(\"football_injury_report.php\")'>Football Injury Reports</a>\n";
  echo "</li><li>\n";
  echo "<a href='javascript:dopopup(\"injury_overview_report.php\")'>Injury Overview Report</a>\n";
  echo "</li>\n";
 }
 if (!empty($GLOBALS['code_types']['IPPF'])) {
  echo "<li>\n";
  echo "<a href='javascript:dopopup(\"ippf_statistics.php?t=i\")'>IPPF Statistics</a>\n";
  echo "</li><li>\n";
  echo "<a href='javascript:dopopup(\"ippf_statistics.php?t=m\")'>MA Statistics</a>\n";
  echo "</li>\n";
 }
 ?>

</ul>

<a href="<?php echo $this->_tpl_vars['printable_link']; ?>
" target="_blank" onclick="top.restoreSession()">[<?php echo smarty_function_xl(array('t' => 'printable'), $this);?>
]</a>
&nbsp;
<br/>
<form name="report" action="index.php" method="get" onsubmit="return top.restoreSession()">
<table>
	<tr>
		<td><?php echo smarty_function_xl(array('t' => 'Reports'), $this);?>
:</td>
		<td><?php echo smarty_function_html_options(array('onChange' => "clear_vars()",'name' => 'query_id','selected' => $this->_tpl_vars['query_id'],'options' => $this->_tpl_vars['queries']), $this);?>
</td>
		<td>&nbsp;&nbsp;</td>
		<td><?php echo smarty_function_xl(array('t' => 'Var1'), $this);?>
:&nbsp;<input size="10" type="text" value="<?php echo $this->_tpl_vars['var1']; ?>
" name="var1"></td>
		<td>&nbsp;&nbsp;</td>
		<td><?php echo smarty_function_xl(array('t' => 'Var2'), $this);?>
:&nbsp;<input size="10" type="text" value="<?php echo $this->_tpl_vars['var2']; ?>
"name="var2"></td>
		<td>&nbsp;&nbsp;</td>
		<td><?php echo smarty_function_xl(array('t' => 'Show'), $this);?>
:&nbsp;</td>
		<td><?php echo smarty_function_html_options(array('name' => 'show','selected' => $this->_tpl_vars['show'],'options' => $this->_tpl_vars['show_options']), $this);?>
<input type="submit" value="<?php echo smarty_function_xl(array('t' => 'Go'), $this);?>
"></td>
	</tr>
	<tr>
		<td colspan="5"><br><h2><?php echo $this->_tpl_vars['title']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %B %e, %Y") : smarty_modifier_date_format($_tmp, "%A, %B %e, %Y")); ?>
</h2></td>
	</tr>
	<tr>
		<td colspan="5">
		<?php if (is_object ( $this->_tpl_vars['pager'] )): ?>
			<?php echo $this->_tpl_vars['pager']->render($this->_tpl_vars['show']); ?>

		<?php endif; ?>
		</td>
	</tr>
</table>
</form>
</div> <!-- end of reports_list -->
</body>
</html>